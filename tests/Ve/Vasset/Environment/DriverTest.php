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

namespace Ve\Asset\Environment;

/**
 * Class ConfigTest
 *
 * @package Ve\Asset\Environment\Generic
 * @author  Ve Interactive PHP team
 */
class DriverTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Driver
	 */
	protected $instance;

	protected function setUp()
	{
		$this->instance = Driver::getEnvironment('generic');
	}

	/**
	 * @covers Ve\Asset\Environment\Driver::__construct
	 * @covers Ve\Asset\Environment\Driver::getEnvironment
	 * @covers Ve\Asset\Environment\Driver::bootstrap
	 * @covers Ve\Asset\Environment\Generic\Generic::bootstrap
	 */
	public function testGetEnvironment()
	{
		$this->assertInstanceOf(
			'Ve\Asset\Environment\Generic\Generic',
			$this->instance
		);
	}

	/**
	 * @covers Ve\Asset\Environment\Driver::__construct
	 * @covers Ve\Asset\Environment\Driver::getEnvironment
	 * @covers Ve\Asset\Environment\Driver::bootstrap
	 * @covers Ve\Asset\Environment\Driver::getConfigInstance
	 * @covers Ve\Asset\Environment\Driver::getConfig
	 * @covers Ve\Asset\Environment\Generic\Generic::bootstrap
	 * @covers Ve\Asset\Environment\Generic\Generic::getConfigInstance
	 */
	public function testGetConfig()
	{
		$this->assertInstanceOf(
			'Ve\Asset\Environment\Generic\Config',
			$this->instance->getConfig()
		);
	}

	/**
	 * @covers Ve\Asset\Environment\Driver::getEnvironment
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidInstance()
	{
		$instance = Driver::getEnvironment('foobar');
	}

}
