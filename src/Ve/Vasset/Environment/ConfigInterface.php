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

namespace Ve\Vasset\Environment;

/**
 * Defines a common interface for interacting with configs
 *
 * @package Ve\Vasset\Environment
 * @author  Ve Interactive PHP Team
 */
interface ConfigInterface
{

	/**
	 * Returns a config value based on $key, defaulting to $default if $key cannot be found.
	 *
	 * @param string $key     Dot notated key
	 * @param mixed  $default Default value if the key is not found
	 *
	 * @return mixed
	 */
	public function get($key, $default = null);

}
