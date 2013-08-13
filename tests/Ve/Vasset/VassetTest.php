<?php

namespace Ve\Vasset;

class VassetTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
	}

	public function testConstruct()
	{
		$object = new Vasset();

		$this->assertInstanceOf('Ve\Vasset\Vasset', $object);
	}

}

