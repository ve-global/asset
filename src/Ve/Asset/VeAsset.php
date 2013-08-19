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

	// Add group to theme
	public function addGroup($group, $theme = null)
	{
		return $this;
	}

	// remove group from theme
	public function removeGroup($group)
	{
		return $this;
	}

	// update group, change files, disable, etc
	public function editGroup($group, $property, $value)
	{
		return $this;
	}

	// Compile js/css into one tag
	public function css(){}

	public function js(){}

	// Add and get inline js
	public function inlineJS($js)
	{
		return $this;
	}

	public function renderInlineJS()
	{

	}
}
