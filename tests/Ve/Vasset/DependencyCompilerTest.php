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

namespace Ve\Asset;

/**
 * Class DependencyCompilerTest
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class DependencyCompilerTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var DependencyCompiler
	 */
	protected $object;

	protected function setUp()
	{
		$this->object = new DependencyCompiler;
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::getGroup
	 */
	public function testAddGetGroup()
	{
		$groupName = 'test';
		$group = [
			'files' => ['f1', 'f2', 'f3'],
		];

		$this->assertInstanceOf(
			'Ve\Asset\DependencyCompiler',
			$this->object->addGroup($groupName, $group)
		);

		$this->assertEquals(
			$group,
			$this->object->getGroup($groupName)
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::getGroup
	 * @covers Ve\Asset\DependencyCompiler::removeGroup
	 */
	public function testRemoveGroup()
	{
		$groupName = 'test';
		$group = [
			'files' => ['f1', 'f2', 'f3'],
		];

		$this->object->addGroup($groupName, $group);

		$this->assertEquals(
			$group,
			$this->object->getGroup($groupName)
		);

		$this->assertInstanceOf(
			'Ve\Asset\DependencyCompiler',
			$this->object->removeGroup($groupName)
		);

		$this->assertNull(
			$this->object->getGroup($groupName)
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::getGroup
	 */
	public function testOverrideGroup()
	{
		$groupName = 'test';
		$group = [
			'files' => ['f1', 'f2', 'f3'],
		];
		$group2 = [
			'files' => ['f4', 'f5', 'f6'],
		];

		$this->object->addGroup($groupName, $group);

		$this->assertEquals(
			$group,
			$this->object->getGroup($groupName)
		);

		$this->object->addGroup($groupName, $group2);

		$this->assertEquals(
			$group2,
			$this->object->getGroup($groupName)
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 */
	public function testSingleGroupCompile()
	{
		$this->object->addGroup('one', [
				'files' => ['f1', 'f2', 'f3'],
			]);

		$this->assertEquals(
			['one'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 */
	public function testTwoGroupCompile()
	{
		$this->object->addGroup('one', []);

		$this->object->addGroup('two', []);

		$this->assertEquals(
			['one', 'two'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 */
	public function testDependencyCompile()
	{
		$this->object->addGroup('one', [
				'deps' => ['two'],
			]);

		$this->object->addGroup('two', []);

		$this->assertEquals(
			['two', 'one'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 */
	public function testDependencyCompileThreeLevel()
	{
		$this->object->addGroup('one', [
				'deps' => ['two'],
			]);

		$this->object->addGroup('two', [
				'deps' => ['three'],
			]);

		$this->object->addGroup('three', []);

		$this->assertEquals(
			['three', 'two', 'one'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 */
	public function testDependencyCompileComplexTree()
	{
		$this->object->addGroup('one', [
				'deps' => ['two', 'four'],
			]);

		$this->object->addGroup('two', [
				'deps' => ['three'],
			]);

		$this->object->addGroup('four', [
				'deps' => ['three'],
			]);

		$this->object->addGroup('three', []);

		$this->assertEquals(
			['three', 'four', 'two', 'one'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 * @expectedException Ve\Asset\Exception\UnsatisfiableDependencyException
	 */
	public function testMissingDepCompile()
	{
		$this->object->addGroup('one', [
				'deps' => ['two'],
			]);

		$this->object->compile();
	}

}
