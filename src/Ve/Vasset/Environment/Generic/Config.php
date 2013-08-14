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

namespace Ve\Vasset\Environment\Generic;

use Ve\Vasset\Environment\ConfigInterface;

/**
 * Fetches
 *
 * @package Ve\Vasset\Environment\Generic
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
		$array = $this->config;

		foreach (explode('.', $key) as $key_part)
		{
			if (isset($array[$key_part]) === false)
			{
				if ( ! is_array($array) or ! array_key_exists($key_part, $array))
				{
					return $default;
				}
			}

			$array = $array[$key_part];
		}

		return $array;
	}

}
