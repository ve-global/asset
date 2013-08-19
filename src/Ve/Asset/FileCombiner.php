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
 * Loads files and combines them into one
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class FileCombiner implements FileCombinerInterface
{

	protected $files = [];

	/**
	 * Adds a file to the list of files to combine
	 *
	 * @param string $file
	 *
	 * @return FileCombinerInterface $this
	 */
	public function addFile($file)
	{
		$this->files[] = $file;
		return $this;
	}

	/**
	 * Adds many files to the list of files to combine
	 *
	 * @param array[string] $files
	 *
	 * @return FileCombinerInterface $this
	 */
	public function addFiles(array $files)
	{
		$this->files += $files;
		return $this;
	}

	/**
	 * Returns a list of all registered files
	 *
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * Returns all the files combined
	 *
	 * @param array[mixed] $config
	 *
	 * @return string
	 */
	public function combine(array $config = [])
	{
		$content = '';

		foreach ($this->files as $filePath)
		{
			// Try loading the file
			if (file_exists($filePath))
			{
				// If it does exist load the content and add it to the stuff to return
				$content .= file_get_contents($filePath);
			}
			else
			{
				// if it does not exist throw an exception
				throw new \OutOfBoundsException('Cannot find '.$filePath);
			}
		}

		return $content;
	}
}
