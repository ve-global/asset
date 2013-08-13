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
 * Allows an environment to be loaded and interacted with
 *
 * @package Ve\Vasset\Environment
 * @author  Ve Interactive PHP Team
 */
class Loader
{

	/**
	 * @var Driver
	 */
	protected $driver = null;

	public function __construct($environment)
	{
		$this->setEnvironment($environment);
	}

	/**
	 * Loads the required environment
	 *
	 * @param $environment string Name of the environment to use. Eg: fuelv1, default
	 */
	protected function setEnvironment($environment)
	{
		// Work out the base class from the name
		$namespace = 'Ve\Vasset\Environment\\';
		$driverName = ucfirst($environment);
		$className = $namespace . $driverName . '\\' . $driverName;

		// Check that the class actually exists
		if ( ! class_exists($className))
		{
			throw new \InvalidArgumentException($environment. ' is not a know environment');
		}

		$this->driver = new $className;
	}

	/**
	 * @return Driver
	 */
	public function getDriver()
	{
		return $this->driver;
	}

}
