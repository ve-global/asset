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
 *
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class VeAsset
{

	/**
	 * @var DependencyCompilerInterface
	 */
	protected $drc;

	protected $defaultTheme;

	// Construct with DRC, combination drivers
	public function __construct(DependencyCompilerInterface $drc)
	{
		$this->drc = $drc;
		$this->defaultTheme = 'default';
	}

	// Add, remove, update theme
	public function addTheme(){}

	public function removeTheme(){}

	public function updateTheme(){}

	// Add group to theme
	public function addGroup($group, $theme=null){}

	// remove group from theme
	public function removeGroup($group){}

	// update group, change files, disable, etc
	public function editGroup($group, $property, $value){}

	// Compile js/css into one tag
	public function css(){}

	public function js(){}
}
