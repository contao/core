<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package News
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Test;


/**
 * Reads and writes news archives
 *
 * @package   Models
 * @author    Yanick Witschi <https://github.com/Toflar>
 * @copyright Yanick Witschi 2012
 */
class PageModel extends \Model
{

    /**
     * Another nice variable
     * @var string
     */
    protected $varTest = 'dummy';


    /**
     * Do some nice thing with a model property
     *
     * @return string modified dummy
     */
    public function returnDummy()
    {
        $strData = '';

        if ('foobar' == 'foobar')
        {
            $strData = 'fill up the method with a useless testing body';
        }

        return str_shuffle($this->varTest);
    }
}
