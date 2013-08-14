<?php

namespace Ve\Vasset\Environment\Generic;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @covers Ve\Vasset\Environment\Generic\Config::get
	 * @covers Ve\Vasset\Environment\Generic\Config::__construct
	 */
	public function testGet()
	{
		$instance = new Config([
			realpath(__DIR__.'/../../../../resources/config/main.php'),
		]);

		$this->assertEquals(
			'value',
			$instance->get('first')
		);

		$this->assertEquals(
			[
				'first' => true,
				'second' => [
					'third' => true,
				]
			],
			$instance->get('second')
		);

		$this->assertEquals(
			true,
			$instance->get('second.first')
		);

		$this->assertEquals(
			true,
			$instance->get('second.second.third')
		);

		$this->assertEquals(
			'zero',
			$instance->get(0)
		);

		$this->assertEquals(
			'one',
			$instance->get(1)
		);

		$this->assertEquals(
			null,
			$instance->get('does not exist')
		);

		$this->assertEquals(
			'default',
			$instance->get('does not exist', 'default')
		);
	}

	/**
	 * @covers Ve\Vasset\Environment\Generic\Config::get
	 * @covers Ve\Vasset\Environment\Generic\Config::__construct
	 */
	public function testGetMultipleFiles()
	{
		$instance = new Config([
			realpath(__DIR__.'/../../../../resources/config/main.php'),
			realpath(__DIR__.'/../../../../resources/config/second.php'),
		]);

		$this->assertEquals(
			'changed',
			$instance->get('first')
		);

		$this->assertEquals(
			[
				'first' => false,
				'second' => [
					'third' => false,
				]
			],
			$instance->get('second')
		);

		$this->assertEquals(
			false,
			$instance->get('second.first')
		);

		$this->assertEquals(
			false,
			$instance->get('second.second.third')
		);

		$this->assertEquals(
			'zero',
			$instance->get(0)
		);

		$this->assertEquals(
			'one',
			$instance->get(1)
		);

		$this->assertEquals(
			1,
			$instance->get('new')
		);
	}

}
