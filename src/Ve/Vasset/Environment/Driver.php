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
 *
 * @package Ve\Vasset\Environment
 * @author Ve Interactive PHP team
 */
abstract class Driver
{

	public abstract function bootstrap();

	public abstract function getConfigInstance();

}
