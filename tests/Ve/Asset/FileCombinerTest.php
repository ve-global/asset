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

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

/**
 *
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class FileCombinerTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var FileCombinerInterface
	 */
	protected $object;

	protected function setUp()
	{
		$this->object = new FileCombiner;
	}

	/**
	 * @covers Ve\Asset\FileCombiner::addFile
	 * @covers Ve\Asset\FileCombiner::addFiles
	 * @covers Ve\Asset\FileCombiner::getFiles
	 */
	public function testAddGetFile()
	{
		$file = 'foobar';

		$this->object->addFile($file);

		$this->assertEquals(
			[$file],
			$this->object->getFiles()
		);

		$files = ['baz', 'bat'];

		$this->object->addFiles($files);

		$this->assertEquals(
			[$file] + $files,
			$this->object->getFiles()
		);
	}

	/**
	 * @covers Ve\Asset\FileCombiner::addFiles
	 * @covers Ve\Asset\FileCombiner::combine
	 */
	public function testCombine()
	{
		// Create two dummy files
		$root = vfsStream::setup('root');

		$file1 = new vfsStreamFile('file1');
		$file1->setContent("one\n");
		$root->addChild($file1);

		$file2 = new vfsStreamFile('file2');
		$file2->setContent("two\n");
		$root->addChild($file2);

		// Add them to the FC
		$this->object->addFiles([
				vfsStream::url('root/file1'),
				vfsStream::url('root/file2'),
			]);

		// Compile them and check the result
		$this->assertEquals(
			"one\ntwo\n",
			$this->object->combine()
		);
	}

	/**
	 * @covers            Ve\Asset\FileCombiner::addFiles
	 * @covers            Ve\Asset\FileCombiner::combine
	 * @expectedException \OutOfBoundsException
	 */
	public function testInvalidFile()
	{
		$this->object->addFile('nothing');
		$this->object->combine();
	}

}
