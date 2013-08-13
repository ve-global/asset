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

namespace Ve\Vasset\Environment\Fuelv1;

use Ve\Vasset\Environment\ConfigInterface;

/**
 * Defines an interface to interact with FuelPHP v1 style config class
 *
 * @package Ve\Vasset\Environment\Fuelv1
 * @author  Ve Interactive PHP Team
 */
class Config implements ConfigInterface
{

	/**
	 * Ensures the config file is loaded
	 */
	public function __construct()
	{
		\Config::load('vasset', true);
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
		return \Config::get('vasset.' . $key, $default);
	}

}
