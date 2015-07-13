<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Interface listable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
interface listable
{
	public function delete();
	public function show();
	public function showAll();
	public function undo();
}


/**
 * Interface editable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
interface editable
{
	public function create();
	public function cut();
	public function copy();
	public function move();
	public function edit();
}


/**
 * Interface executable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
interface executable
{
	public function run();
	public function isActive();
}


/**
 * Interface uploadable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
interface uploadable {}
