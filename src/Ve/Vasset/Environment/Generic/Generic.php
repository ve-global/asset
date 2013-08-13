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
use Ve\Vasset\Environment\Driver;

/**
 * Allows the use of Vasset outside of a framework
 *
 * @package Ve\Vasset\Environment\Generic
 * @author Ve Interactive PHP Team
 */
class Generic extends Driver
{

	/**
	 * Should perform any action necessary for the driver to be able to interact with its environment
	 *
	 * @param $config array An array of config values
	 */
	protected function bootstrap(array $config = [])
	{
		// TODO: Implement bootstrap() method.
	}

	/**
	 * Should return a ConfigInterface to allow interaction with the environment's config fetching
	 *
	 * @return ConfigInterface
	 */
	protected function getConfigInstance()
	{
		// TODO: Implement getConfigInstance() method.
	}

}
