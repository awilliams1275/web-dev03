<?php

use Jadu\Notifications\Notification;

static $loaded = false;

if($loaded === true) {
    return true;
}

if (isset($_SERVER['SERVER_PORT']) && preg_match('~443$~', $_SERVER['SERVER_PORT'])) {
    $_SERVER['HTTPS'] = 'on';
}

require_once('JaduAutoload.php');
require_once('Bootstrap.php');
require_once('Config/Manager.php');

// The path to the main home directory (this contains the same value for both galaxies and the main CMS)
define('MAIN_HOME_DIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '/..') . '/'));

$serviceContainer = Jadu_Service_Container::getInstance();
$serviceContainer->set('autoloader', $autoloader);

// Initialise the config manager
$configManager = new Jadu_Config_Manager(MAIN_HOME_DIR . 'config');
$coreConfigManager = new Jadu_Config_Manager(MAIN_HOME_DIR . 'var/config');

if (php_sapi_name() == 'cli') {
    // Add CLI module
    $configManager->addModule('cli', MAIN_HOME_DIR . 'config/cli');
}
else {
    // Only when config files when not running from CLI
    $configManager->setCachePath(MAIN_HOME_DIR . 'var/cache');
}

// Set the container
Jadu_Config_Manager::setInstance($configManager);
$serviceContainer->set('configManager', $configManager, true);
$serviceContainer->set('coreConfigManager', $coreConfigManager);
$serviceContainer->set('registry', new \JaduFramework\Container\Registry());
$serviceContainer->get('registry')->register('MAIN_HOME_DIR', MAIN_HOME_DIR);

$configContainer = new Jadu\Config\Container();
$configContainer
    ->set('core', $coreConfigManager)
    ->set('jadu', $configManager);

$serviceContainer->set('config.container', $configContainer);

// Bootstrap the CMS
$bootstrap = new Jadu_Bootstrap($_SERVER, $serviceContainer);
$bootstrap->setConfigManager($configManager);
$bootstrap->initialise();

// Create a global $jadu variable which is our DI container
$jadu = Jadu_Service_Container::getInstance();

// Force onto SSL if required.
if (php_sapi_name() != 'cli' && ((defined('SSL_ENABLED') && SSL_ENABLED) && (defined('FORCE_SECURE') && FORCE_SECURE)) && PROTOCOL != 'https://') {
    header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
    exit;
}

$allRoutes = $serviceContainer->getConfig('routes');

foreach($allRoutes['routes'] as $key => $values) {
    $routes = array();

    if(!is_array($values)) {
        $routes[] = $values;
    } else {
        $routes = $values;
    }

    // Parse each route
    foreach($routes as $route) {
        Jadu_Service_Container::getInstance()->getRouter()->add(new Jadu_Route($route['method'], $route->getValue(), $route['action']));
    }
}

// Backwards compatibility config file
if (is_readable(MAIN_HOME_DIR . 'var/config.php')) {
    include(MAIN_HOME_DIR . 'var/config.php');
}

// Set http.filters
$jadu->set('http.filters', new Jadu\Http\Filter(new Jadu_Config_Manager(MAIN_HOME_DIR . 'var/filters'), $jadu->getEventContainer()));
$jadu->get('http.filters')->filter('before.render');

$loaded = true;
