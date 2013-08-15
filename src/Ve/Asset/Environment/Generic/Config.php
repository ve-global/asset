<?php

/**
 * This file is part of the Vasset package.
 *
 * Released under the MIT licence.
 * This file is free to use and reuse as long as the original credits are preserved.
 *
 * @license MIT License
 * @copyright 2013 Ve Interactive
 */

namespace Ve\Asset\Environment\Generic;

use FuelPHP\Common\Arr;
use Ve\Asset\Environment\ConfigInterface;

/**
 * Fetches
 *
 * @package Ve\Asset\Environment\Generic
 * @author Ve Interactive PHP Team
 */
class Config implements ConfigInterface
{

	protected $config = [];

	/**
	 * Creates a new generic config interface for loading php arrays from files
	 *
	 * @param array $configLocations Array of names to load configs from
	 */
	public function __construct(array $configLocations)
	{
		// Load all the files
		foreach ($configLocations as $location)
		{
			$fileContent = include($location);

			// Merge them together and store this for fetching later
			$this->config = $fileContent + $this->config;
		}
	}

	/**
	 * Returns a config value based on $key, defaulting to $default if $key cannot be found.
	 *
	 * @param string $key     Dot notated key
	 * @param mixed  $default Default value if the key is not found
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return Arr::get($this->config, $key, $default);
	}

}
