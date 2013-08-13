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
 * Defines a common interface for interacting with environments
 *
 * @package Ve\Vasset\Environment
 * @author  Ve Interactive PHP Team
 */
abstract class Driver
{

	/**
	 * @var ConfigInterface
	 */
	protected $configInstance = null;

	/**
	 * Should perform any action necessary for the driver to be able to interact with its environment
	 */
	protected abstract function bootstrap();

	/**
	 * Should return a ConfigInterface to allow interaction with the environment's config fetching
	 * @return ConfigInterface
	 */
	protected abstract function getConfigInstance();

	/**
	 * Calls the bootstrap method to allow for setup
	 */
	public function __construct()
	{
		$this->bootstrap();
	}

	/**
	 * Returns a config object to allow fetching of config values
	 *
	 * @return ConfigInterface
	 */
	public function getConfig()
	{
		if (is_null($this->configInstance))
		{
			$this->configInstance = $this->getConfigInstance();
		}

		return $this->configInstance;
	}

}
