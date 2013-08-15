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

/**
 * Common interface for interacting with dependency compilers
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
interface DependencyCompilerInterface
{

	/**
	 * Add a group of files to the compiler. The exact format of this will be dependent on the implementing class.
	 *
	 * @param string $name   Name of the group for later identification
	 * @param mixed  $config See individual class documentation for expected type
	 *
	 * @return DependencyCompilerInterface
	 */
	public function addGroup($name, $config);

	/**
	 * Removes a group of files from the compiler. A removed group will not be included with the end result.
	 *
	 * @param string $name
	 *
	 * @return DependencyCompilerInterface
	 */
	public function removeGroup($name);

	/**
	 * Returns the given group
	 *
	 * @param string $name
	 *
	 * @return mixed|null Null if no group is found
	 */
	public function getGroup($name);

	/**
	 * Should return a list of files in the order they are to be loaded in.
	 *
	 * @return array
	 */
	public function compile();

}
