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

namespace Ve\Asset\Environment;

/**
 * Defines a common interface for interacting with environments
 *
 * @package Ve\Asset\Environment
 * @author  Ve Interactive PHP Team
 */
abstract class AbstractDriver
{

	/**
	 * @var ConfigInterface
	 */
	protected $configInstance = null;

	/**
	 * Should perform any action necessary for the driver to be able to interact with its environment
	 *
	 * @param $config array An array of config values
	 */
	protected abstract function bootstrap(array $config = []);

	/**
	 * Should return a ConfigInterface to allow interaction with the environment's config fetching
	 * @return ConfigInterface
	 */
	protected abstract function getConfigInstance();

	/**
	 * Loads the required environment
	 *
	 * @param $environment string Name of the environment to use. Eg: fuelv1, default
	 * @param $config      array  Optional config array
	 * 
	 * @return AbstractDriver
	 */
	public static function getEnvironment($environment, $config = [])
	{
		// Work out the base class from the name
		$namespace = 'Ve\Asset\Environment\\';
		$driverName = ucfirst($environment);
		$className = $namespace . $driverName . '\\' . $driverName;

		// Check that the class actually exists
		if ( ! class_exists($className))
		{
			throw new \InvalidArgumentException($environment. ' is not a known environment');
		}

		return new $className($config);
	}

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
