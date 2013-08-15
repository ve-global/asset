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

/**
 * Defines a basic array based dependency compiler
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class DependencyCompiler implements DependencyCompilerInterface
{

	/**
	 * Contains a list of groups to compile
	 * @var array
	 */
	protected $groups = [];

	/**
	 * Add a group of files to the compiler. Sample config:
	 *
	 * [
	 * 		'deps' => ['group1', 'group2'],
	 * 		'files' => [ 'file1', 'file2', 'file3' ]
	 * ]
	 *
	 * This will ensure that all files and dependencies from group1 and group2 are included before the files in "files"
	 *
	 * @param string $name   Name of the group for later identification
	 * @param array  $config
	 *
	 * @return DependencyCompilerInterface
	 */
	public function addGroup($name, $config)
	{
		$this->groups[$name] = $config;
		return $this;
	}

	/**
	 * Removes a group of files from the compiler. A removed group will not be included with the end result.
	 *
	 * @param string $name
	 *
	 * @return DependencyCompilerInterface
	 */
	public function removeGroup($name)
	{
		Arr::delete($this->groups, $name);
		return $this;
	}

	/**
	 * Should return a list of files in the order they are to be loaded in.
	 *
	 * @return array
	 */
	public function compile()
	{
		// Get a list of all the group names to use as a basis for which order to load from later
		$groups = array_keys($this->groups);
		$groupKeys = array_flip($groups);

		// For each group
		foreach ($this->groups as $name => $content)
		{
			// Get the dependencies
			$deps = Arr::get($content, 'deps', []);

			// Check each dependency is above the current group
			foreach ($deps as $dep)
			{
				// If the current group is above the dependency
				$groupIndex = $groupKeys[$name];
				$previousGroups = array_splice($groups, 0, $groupIndex+1);

				if ( ! in_array($dep, $previousGroups))
				{
					// Move the current group down under the dependency
					// Move $name below $dep
					// $groupIndex represents the child group, this one needs to move

					// Get the index of the parent group
					$depIndex = $groupKeys[$dep];
					$arrayStart = array_splice($groups, 0, $depIndex+1);
					$arrayEnd = array_splice($groups, $depIndex);

					$groups = $arrayStart + [$depIndex+1 => $name] + $arrayEnd;
					$groupKeys = array_flip($groups);
				}
			}
		}

		// Keep track of the files to return
		$fileList = [];
		foreach ($groups as $group)
		{
			$fileList = array_merge($fileList, $this->groups[$group]['files']);
		}

		return $fileList;
	}

	/**
	 * Returns the given group
	 *
	 * @param string $name
	 *
	 * @return mixed|null Null if no group is found
	 */
	public function getGroup($name)
	{
		return Arr::get($this->groups, $name, null);
	}
}
