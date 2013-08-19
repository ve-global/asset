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
 * Defines a common interface for being able to combine groups of files
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
interface FileCombinerInterface
{

	/**
	 * Adds a file to the list of files to combine
	 *
	 * @param string $file
	 *
	 * @return FileCombinerInterface $this
	 */
	public function addFile($file);

	/**
	 * Adds many files to the list of files to combine
	 *
	 * @param array[string] $files
	 *
	 * @return FileCombinerInterface $this
	 */
	public function addFiles(array $files);

	/**
	 * Returns a list of all registered files
	 *
	 * @return array
	 */
	public function getFiles();

	/**
	 * Returns all the files combined
	 *
	 * @param array[mixed] $config
	 *
	 * @return string
	 */
	public function combine(array $config = []);

}
