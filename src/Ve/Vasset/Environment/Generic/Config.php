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
		// TODO: Implement get() method.
	}

}
