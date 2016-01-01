<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */
 include __DIR__ . '/system/config/default.php';
 include __DIR__ . '/system/config/localconfig.php';

switch (strtok($_SERVER["REQUEST_URI"],'?')) {
  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/changelog.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/changelog.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendChangelog;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/confirm.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/confirm.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendConfirm;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/file.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/file.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendFile;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/help.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/help.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendHelp;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/index.php':
  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/':
  case '/'.$GLOBALS['TL_CONFIG']['backendPath']:
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/index.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendIndex;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/install.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/install.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Show error messages
    @ini_set('display_errors', 1);
    error_reporting(Config::get('errorReporting'));

    // Run the controller
    $controller = new BackendInstall;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/main.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/main.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendMain;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/page.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/page.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendPage;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/password.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/password.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendPassword;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/popup.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/popup.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendPopup;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/preview.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/preview.php');

    // Initialize the system
    define('TL_MODE', 'BE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendPreview;
    $controller->run();
    break;

  case '/'.$GLOBALS['TL_CONFIG']['backendPath'].'/switch.php':
    // Set the script name
    define('TL_SCRIPT', $GLOBALS['TL_CONFIG']['backendPath'].'/switch.php');

    // Initialize the system
    define('TL_MODE', 'FE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new BackendSwitch;
    $controller->run();
    break;

  default:
    // Set the script name
    define('TL_SCRIPT', 'index.php');

    // Initialize the system
    define('TL_MODE', 'FE');
    require __DIR__ . '/system/initialize.php';

    // Run the controller
    $controller = new FrontendIndex;
    $controller->run();
    break;
}
