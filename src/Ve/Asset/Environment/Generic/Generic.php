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

namespace Ve\Asset\Environment\Generic;

use Ve\Asset\Environment\ConfigInterface;
use Ve\Asset\Environment\AbstractDriver;

/**
 * Allows the use of Vasset outside of a framework
 *
 * @package Ve\Asset\Environment\Generic
 * @author  Ve Interactive PHP Team
 */
class Generic extends AbstractDriver
{

	protected $configFiles = [];

	/**
	 * Should perform any action necessary for the driver to be able to interact with its environment
	 *
	 * @param $config array An array of config values
	 */
	protected function bootstrap(array $config = [])
	{
		$this->configFiles = $config;
		array_unshift($this->configFiles, realpath(__DIR__.'/../../../../../resources/config/veasset.php'));
	}

	/**
	 * Should return a ConfigInterface to allow interaction with the environment's config fetching
	 *
	 * @return ConfigInterface
	 */
	protected function getConfigInstance()
	{
		return new Config($this->configFiles);
	}

}
