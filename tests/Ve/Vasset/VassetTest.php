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

namespace Ve\Vasset;

/**
 *
 * @package Ve\Vasset\Environment
 * @author  Ve Interactive PHP Team
 */
class VassetTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
	}

	public function testConstruct()
	{
		$object = new Vasset();

		$this->assertInstanceOf('Ve\Vasset\Vasset', $object);
	}

}

