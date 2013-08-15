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
 * Defines a basic array based dependency compiler
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class DependencyCompiler implements DependencyCompilerInterface
{

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
		// TODO: Implement addGroup() method.
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
		// TODO: Implement removeGroup() method.
	}

	/**
	 * Should return a list of files in the order they are to be loaded in.
	 *
	 * @return array
	 */
	public function compile()
	{
		// TODO: Implement compile() method.
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
		// TODO: Implement getGroup() method.
	}
}
