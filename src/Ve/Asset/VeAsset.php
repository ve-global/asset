<?php

/**
 * This file is part of the VeAsset package.
 *
 * Released under the MIT licence.
 * This file is free to use and reuse as long as the original credits are preserved.
 *
 * @license MIT License
 * @copyright 2013 Ve Interactive
 */

namespace Ve\Asset;

use FuelPHP\Common\Arr;
use Ve\Asset\Environment\AbstractDriver;

/**
 *
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class VeAsset
{

	public static $defaultTheme = 'default';

	/**
	 * @var DependencyCompilerInterface
	 */
	protected $drc;

	/**
	 * @var FileCombinerInterface
	 */
	protected $fc;

	/**
	 * @var AbstractDriver
	 */
	protected $env;

	/**
	 * @var string
	 */
	protected $activeTheme;

	/**
	 * @var array
	 */
	protected $themes = [];

	/**
	 * Container for any inline JS
	 * @var array
	 */
	protected $inlineJS = [];

	/**
	 * @param DependencyCompilerInterface $drc
	 * @param FileCombinerInterface       $fc
	 * @param AbstractDriver              $env
	 */
	public function __construct(DependencyCompilerInterface $drc, FileCombinerInterface $fc, AbstractDriver $env)
	{
		$this->drc = $drc;
		$this->fc = $fc;
		$this->env = $env;

		// Make sure the default theme exists
		$this->themes[static::$defaultTheme] = [];
		$this->activeTheme = static::$defaultTheme;
	}

	/**
	 * Sets the name of the default theme. Call with no parameters to reset to the original default.
	 *
	 * @param string $active
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setActiveTheme($active = null)
	{
		if ( ! is_string($active))
		{
			throw new \InvalidArgumentException('Active theme name must be a string');
		}

		if (!array_key_exists($active, $this->themes))
		{
			throw new \InvalidArgumentException('Unknown theme '.$active);
		}

		$this->activeTheme = $active;
		return $this;
	}

	/**
	 * Gets the name of the default theme
	 *
	 * @return string
	 */
	public function getActiveTheme()
	{
		return $this->activeTheme;
	}

	/**
	 * Adds a theme
	 *
	 * @param string $name
	 * @param array  $config
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	public function addTheme($name, array $config)
	{
		if ( ! is_string($name))
		{
			throw new \InvalidArgumentException('Theme name must be a string');
		}

		$this->themes[$name] = $config;
		return $this;
	}

	/**
	 * Gets the given theme
	 *
	 * @param string|null $name
	 *
	 * @return array
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getTheme($name = null)
	{
		if (is_null($name))
		{
			$name = $this->getActiveTheme();
		}

		if ( ! array_key_exists($name, $this->themes))
		{
			throw new \InvalidArgumentException('Unknown theme '.$name);
		}

		return $this->themes[$name];
	}

	/**
	 * Removes the given theme
	 *
	 * @param $name
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	public function removeTheme($name)
	{
		if ($name === $this->getActiveTheme())
		{
			throw new \InvalidArgumentException('You cannot remove the active theme');
		}

		unset($this->themes[$name]);
		return $this;
	}

	/**
	 * Allows a theme value to be set after the theme has been added
	 *
	 * @param string      $key   Dot notated key of the config element to change
	 * @param null|mixed  $value New value to set
	 * @param null|string $theme Theme to change, defaults to the default theme
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateTheme($key, $value = null, $theme = null)
	{
		if (is_null($theme))
		{
			$theme = $this->getActiveTheme();
		}

		if ( ! array_key_exists($theme, $this->themes))
		{
			throw new \InvalidArgumentException('Unknown theme '.$theme);
		}

		if (is_array($key))
		{
			// Merge the arrays
			$this->themes[$theme] = $key + $this->themes[$theme];
		}
		else
		{
			// Just update the key
			Arr::set($this->themes[$theme], $key, $value);
		}

		return $this;
	}

	protected function generate($group)
	{
		// Compile the themes based on the active one.

		// Create a list, add the active theme then add deps and deps of deps
		$themeList = $this->calculateNeededThemes();

		// Use the DRC to build the list of themes in order to check
		$this->drc->reset();
		foreach ($themeList as $theme)
		{
			$this->drc->addGroup($theme, Arr::get($this->themes, $theme.'.deps', []));
		}

		$themeList = $this->drc->compile();
		// Make sure they are in the correct loading order
		$themeList = array_reverse($themeList);

		// Build a list of groups from all themes that need to be loaded
		$megaTheme = $this->combineThemes($themeList);

		// Use the DRC again to work out what order the groups need to be added in
		$this->drc->reset();
		foreach (Arr::get($megaTheme, 'groups.'.$group) as $name => $config)
		{
			$this->drc->addGroup($name, Arr::get($config, 'deps', []));
		}
		$groupList = $this->drc->compile();

		// Combine all the lists of files from all the groups for loading
		$fileList = [];
		foreach ($groupList as $groupName)
		{
			$fileList = array_merge($fileList, Arr::get($megaTheme, 'groups.'.$group.'.'.$groupName.'.files', []));
		}

		print_r($fileList);
		exit;

		// Load a file, check for it in the active theme then bubble up through the deps list if it is not found

		return '';
	}

	/**
	 * Calculates which themes are needed
	 *
	 * @param string $theme base theme to start from, defaults to the active theme if null/nothing is passed
	 *
	 * @return array[string] A list of all the themes that need to be loaded
	 */
	public function calculateNeededThemes($theme = null, &$result = [])
	{
		if (is_null($theme))
		{
			$theme = $this->getActiveTheme();
		}

		if ( ! in_array($theme, $result))
		{
			$result[] = $theme;
			$deps = Arr::get($this->themes, $theme.'.deps', []);
			foreach ($deps as $dep)
			{
				$this->calculateNeededThemes($dep, $result);
			}
		}
		else
		{
			// TODO: Throw an exception or something, circular dependency detected
		}

		return $result;
	}

	/**
	 * Creates a list of the active theme and all of its dependences. All the configs are then combined into one large
	 * array to create a psudo "master" theme that will be used to compile the needed assets to load
	 *
	 * @param array $themes List of themes to combine
	 */
	public function combineThemes($themes)
	{
		$masterThemeConfig = [];
		$groupTypes = ['js', 'css'];

		// For each theme
		foreach ($themes as $themeName)
		{
			$config = $this->themes[$themeName];

			foreach ($groupTypes as $groupType)
			{
				// for each group
				foreach (Arr::get($config, 'groups.'.$groupType, []) as $groupName => $group)
				{
					$newConfig = $group;

					// If the group has override, set the value, else add the config together
					if (Arr::get($group, 'override', false) === false)
					{
						$existingConfig = Arr::get($masterThemeConfig, 'groups.'.$groupType.'.'.$groupName, []);
						$newConfig = array_merge_recursive($existingConfig, $newConfig);
					}

					Arr::set($masterThemeConfig, 'groups.'.$groupType.'.'.$groupName, $newConfig);
				}
			}
		}

		return $masterThemeConfig;
	}

	// Compile js/css into one tag
	public function css()
	{
		return $this->generate('css');
	}

	public function js()
	{
		return $this->generate('js');
	}

	/**
	 * Adds inline js
	 *
	 * @param string $js
	 *
	 * @return $this
	 */
	public function inlineJS($js)
	{
		$this->inlineJS[] = $js;
		return $this;
	}

	/**
	 * Renders stored inline js
	 *
	 * @return string
	 */
	public function renderInlineJS()
	{
		return "<script>\n".implode('', $this->inlineJS)."\n</script>";
	}
}
