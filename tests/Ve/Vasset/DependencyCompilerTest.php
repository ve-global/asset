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
			['f1', 'f2', 'f3'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 */
	public function testTwoGroupCompile()
	{
		$this->object->addGroup('one', [
				'files' => ['f1', 'f2', 'f3'],
			]);

		$this->object->addGroup('two', [
				'files' => ['f4', 'f5', 'f6'],
			]);

		$this->assertEquals(
			['f1', 'f2', 'f3', 'f4', 'f5', 'f6'],
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
				'files' => ['f1', 'f2', 'f3'],
			]);

		$this->object->addGroup('two', [
				'files' => ['f4', 'f5', 'f6'],
			]);

		$this->assertEquals(
			['f4', 'f5', 'f6', 'f1', 'f2', 'f3'],
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
				'files' => ['f1'],
			]);

		$this->object->addGroup('two', [
				'deps' => ['three'],
				'files' => ['f2'],
			]);

		$this->object->addGroup('three', [
				'files' => ['f3'],
			]);

		$this->assertEquals(
			['f3', 'f2', 'f1'],
			$this->object->compile()
		);
	}

	/**
	 * @covers Ve\Asset\DependencyCompiler::addGroup
	 * @covers Ve\Asset\DependencyCompiler::compile
	 * @expectedException Ve\Asset\Exception\CircularDependencyException
	 */
	public function testCircularDependencyCompile()
	{
		$this->object->addGroup('one', [
				'deps' => ['two'],
				'files' => ['f1', 'f2', 'f3'],
			]);

		$this->object->addGroup('two', [
				'deps' => ['one'],
				'files' => ['f4', 'f5', 'f6'],
			]);

		$this->object->compile();
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
				'files' => ['f1', 'f2', 'f3'],
			]);

		$this->object->compile();
	}

}
