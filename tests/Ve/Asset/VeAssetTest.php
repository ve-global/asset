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
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use Ve\Asset\Environment\AbstractDriver;

/**
 *
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class VeAssetTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var VeAsset
	 */
	protected $object;

	public function setUp()
	{
		$drc = \Mockery::mock('Ve\Asset\DependencyCompilerInterface');
		$fc = \Mockery::mock('Ve\Asset\FileCombinerInterface');
		$env = \Mockery::mock('Ve\Asset\Environment\AbstractDriver');

		$this->object = new VeAsset($drc, $fc, $env);
	}

	/**
	 * @covers Ve\Asset\VeAsset::getTheme()
	 */
	public function testGetActiveTheme()
	{
		$this->assertEquals(
			[],
			$this->object->getTheme()
		);
	}

	/**
	 * @covers Ve\Asset\VeAsset::addTheme()
	 * @covers Ve\Asset\VeAsset::getTheme()
	 */
	public function testSetGetTheme()
	{
		$name = 'test';
		$config = ['foobar'];

		$this->object->addTheme($name, $config);

		$this->assertEquals(
			$config,
			$this->object->getTheme($name)
		);
	}

	/**
	 * @covers            Ve\Asset\VeAsset::getTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testGetInvalidTheme()
	{
		$this->object->getTheme('fake');
	}

	/**
	 * @covers            Ve\Asset\VeAsset::getTheme()
	 * @covers            Ve\Asset\VeAsset::addTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testRemoveTheme()
	{
		$name = 'test';
		$config = ['foobar'];

		$this->object->addTheme($name, $config);
		$this->object->removeTheme($name);
		$this->object->getTheme($name);
	}

	/**
	 * @covers Ve\Asset\VeAsset::getTheme()
	 * @covers Ve\Asset\VeAsset::addTheme()
	 * @covers Ve\Asset\VeAsset::updateTheme()
	 */
	public function testUpdateTheme()
	{
		$name = 'test';
		$config = [
			'foobar' => [
				'one' => 123,
			],
		];

		$this->object->addTheme($name, $config);

		$this->object->updateTheme('foobar.one', 456, $name);

		$this->assertEquals([
				'foobar' => [
					'one' => 456,
				],
			],
			$this->object->getTheme($name)
		);
	}

	/**
	 * Check that the default theme can be changed
	 * @covers Ve\Asset\VeAsset::getTheme()
	 * @covers Ve\Asset\VeAsset::updateTheme()
	 */
	public function testUpdateActiveTheme()
	{
		$this->object->updateTheme('foobar.one', 456);

		$this->assertEquals([
				'foobar' => [
					'one' => 456,
				],
			],
			$this->object->getTheme()
		);
	}

	/**
	 * @covers            Ve\Asset\VeAsset::removeTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testRemoveActiveTheme()
	{
		$this->object->removeTheme('default');
	}

	/**
	 * @covers Ve\Asset\VeAsset::getTheme()
	 * @covers Ve\Asset\VeAsset::addTheme()
	 * @covers Ve\Asset\VeAsset::updateTheme()
	 */
	public function testUpdateAlternateTheme()
	{
		$theme = 'foobar';

		$this->object->addTheme($theme, []);

		$this->object->updateTheme('test', 'value', $theme);

		$this->assertEquals(
			['test' => 'value'],
			$this->object->getTheme($theme)
		);
	}

	/**
	 * @covers Ve\Asset\VeAsset::getTheme()
	 * @covers Ve\Asset\VeAsset::updateTheme()
	 */
	public function testUpdateWithArray()
	{
		$this->object->updateTheme('test', 'should not see');

		$config = ['test' => 'value'];
		$this->object->updateTheme($config);

		$this->assertEquals(
			$config,
			$this->object->getTheme()
		);
	}

	/**
	 * @covers            Ve\Asset\VeAsset::updateTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testUpdateFakeTheme()
	{
		$this->object->updateTheme('key', 'value', 'fake');
	}

	/**
	 * Check the default theme cannot be deleted
	 *
	 * @covers            Ve\Asset\VeAsset::removeTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testDeleteActive()
	{
		$this->object->removeTheme('default');
	}

	/**
	 * Check changing default theme works
	 *
	 * @covers Ve\Asset\VeAsset::addTheme()
	 * @covers Ve\Asset\VeAsset::setActiveTheme()
	 * @covers Ve\Asset\VeAsset::getActiveTheme()
	 */
	public function testChangeDefault()
	{
		$name = 'new';
		$config = ['bla'];

		$this->object->addTheme($name, $config);
		$this->object->setActiveTheme($name);

		$this->assertEquals(
			$name,
			$this->object->getActiveTheme()
		);
	}

	/**
	 * @covers            Ve\Asset\VeAsset::setActiveTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testChangeInvalidActive()
	{
		$name = 'new';
		$this->object->setActiveTheme($name);
	}

	/**
	 * Check that changing the default theme name can only be a string
	 *
	 * @covers            Ve\Asset\VeAsset::setActiveTheme()
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidActive()
	{
		$this->object->setActiveTheme(123234);
	}

	/**
	 * @covers Ve\Asset\VeAsset::inlineJS()
	 * @covers Ve\Asset\VeAsset::renderInlineJS()
	 */
	public function testInlineJS()
	{
		$js = 'var test = "baz";';
		$this->object->inlineJS($js);

		$this->assertEquals(
			"<script>\n".$js."\n</script>",
			$this->object->renderInlineJS()
		);
	}

	public function testCombineThemes()
	{
		// Add something to the default theme
		$this->object->updateTheme([
			'groups' => [
				'js' => [
					'main' => [
						'files' => [
							'a.js',
							'b.js',
							'c.js',
						],
					]
				],
				'css' => [
					'main' => [
						'files' => [
							'a.css',
							'b.css',
							'c.css',
						],
					]
				]
			],
		]);

		$this->object->addTheme('two', [
				'groups' => [
					'js' => [
						'main' => [
							'files' => [
								'd.js',
							],
						],
					],
					'css' => [
						'main' => [
							'override' => true,
							'files' => [
								'd.css',
							],
						],
					],
				],
			]);

		$expected = [
			'groups' => [
				'js' => [
					'main' => [
						'files' => [
							'a.js',
							'b.js',
							'c.js',
							'd.js',
						],
					]
				],
				'css' => [
					'main' => [
						'files' => [
							'd.css',
						],
						'override' => true,
					]
				]
			],
		];

		$this->assertEquals(
			$expected,
			$this->object->combineThemes(['default', 'two'])
		);
	}

	public function testCombinesdfsdgfThemes()
	{
		$this->object = new VeAsset(new DependencyCompiler(), new FileCombiner(), AbstractDriver::getEnvironment('generic'));

		// Create two dummy files
		$root = vfsStream::setup('root');

		$defaultJSDir = new vfsStreamDirectory('js');

		$file1 = new vfsStreamFile('a.js');
		$file1->setContent("default a\n");
		$defaultJSDir->addChild($file1);

		$file2 = new vfsStreamFile('b.js');
		$file2->setContent("default b\n");
		$defaultJSDir->addChild($file2);

		$file3 = new vfsStreamFile('c.js');
		$file3->setContent("default c\n");
		$defaultJSDir->addChild($file3);

		$defaultThemeDir = new vfsStreamDirectory('default');
		$defaultThemeDir->addChild($defaultJSDir);
		$root->addChild($defaultThemeDir);

		// Set up theme 2 files
		$twoThemeJSDir = new vfsStreamDirectory('js');

		$file4 = new vfsStreamFile('d.js');
		$file4->setContent("two d\n");
		$twoThemeJSDir->addChild($file4);

		$file5 = new vfsStreamFile('e.js');
		$file5->setContent("two e\n");
		$twoThemeJSDir->addChild($file5);

		$file6 = new vfsStreamFile('a.js');
		$file6->setContent("two a\n");
		$twoThemeJSDir->addChild($file6);

		$twoThemeDir = new vfsStreamDirectory('two');
		$twoThemeDir->addChild($twoThemeJSDir);
		$root->addChild($twoThemeDir);

		// Add something to the default theme
		$this->object->updateTheme([
				'path' => 'vfs://root/default',
				'deps' => ['two'],
				'groups' => [
					'js' => [
						'main' => [
							'files' => [
								'a.js',
								'b.js',
								'c.js',
							],
						]
					],
				],
			]);

		$this->object->addTheme('two', [
				'path' => 'vfs://root/two',
				'groups' => [
					'js' => [
						'main' => [
							'files' => [
								'd.js',
							],
						],
						'blue' => [
							'deps' => [
								'main',
							],
							'files' => [
								'e.js'
							]
						]
					],
				],
			]);

		echo $this->object->js();

		die();
	}

}
