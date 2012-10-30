<?php

/****************************************************************************/
/*                                                                          */
/* YOU MAY WISH TO MODIFY OR REMOVE THE FOLLOWING LINES WHICH SET DEFAULTS  */
/*                                                                          */
/****************************************************************************/

// Sets the default charset so that setCharset() is not needed elsewhere
Swift_Preferences::getInstance()
	->setCharset($GLOBALS['TL_CONFIG']['characterSet']);

// Without these lines the default caching mechanism is "array" but this uses a lot of memory.
// If possible, use a disk cache to enable attaching large attachments etc.
// You can override the default temporary directory by setting the TMPDIR environment variable.
if (!$GLOBALS['TL_CONFIG']['useFTP'])
{
    Swift_Preferences::getInstance()
        -> setTempDir(TL_ROOT . '/system/tmp')
        -> setCacheType('disk');
}

Swift_Preferences::getInstance()->setQPDotEscape(false);
