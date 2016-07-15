<?php
// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';


Autoloader::add_classes(array(
	// Add classes you want to override here
	// Example: 'View' => APPPATH.'classes/view.php',
));

// Register the autoloader
Autoloader::register();

require APPPATH . '../vendor/autoload.php';

Autoloader::add_namespace('Fuel\\Behaviour', APPPATH.'classes/behaviour/', true);
Autoloader::add_namespace('Fuel\\Extranet', APPPATH.'classes/extranet/', true);
Autoloader::add_namespace('Controller', APPPATH.'classes/controller/', true);
Autoloader::add_namespace('Fuel\\Twig', APPPATH.'classes/twig/', true);
Autoloader::add_namespace('Fuel\\Model', APPPATH.'classes/model/', true);

$moduleList = array(
    'responsive'
);

foreach ($moduleList as $moduleName) {
    Autoloader::add_namespace($moduleName . '\\Controller', APPPATH . 'modules/' . $moduleName . '/classes/controller/', true);
    Autoloader::add_namespace($moduleName . '\\Twig', APPPATH . 'modules/' . $moduleName . '/classes/twig/', true);
    Autoloader::add_namespace($moduleName . '\\Model', APPPATH . 'modules/' . $moduleName . '/classes/model/', true);

    if (file_exists(APPPATH . 'modules/' . $moduleName . '/config/routes.php')) {
        $moduleRoutes = \Fuel\Core\Fuel::load(APPPATH . 'modules/' . $moduleName . '/config/routes.php');
    } else {
        echo new Exception('NOT FOUND : ' . APPPATH . 'modules/' . $moduleName . '/config/routes.php');
        die;
    }

    $preppedRoutes = array();
    foreach ($moduleRoutes as $name => $_route) {
        if ($name === '_root_')
        {
            $name = $moduleName;
        }
        elseif (strpos($name, $moduleName.'/') !== 0 and $name != $moduleName and $name !== '_404_')
        {
            $name = $moduleName.'/'.$name;
        }

        $preppedRoutes[$name] = $_route;
    }

    // var_dump($preppedRoutes);die;
    \Fuel\Core\Router::add($preppedRoutes, null, true);
}

Finder::instance()->add_path(APPPATH . 'views/');

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */
Fuel::$env = (isset($_SERVER['FUEL_ENV']) ? $_SERVER['FUEL_ENV'] : Fuel::DEVELOPMENT);

// Initialize the framework with the config file.
Fuel::init('config.php');
