<?php

class Autoload
{
    public function __construct(array $aliases = array())
    {
        foreach($aliases as $class => $alias) {
            $this->alias($class, $alias);
        }
    }

    public function alias($from, $to)
    {
        if(class_exists($from)) {
            class_alias($from, $to, true);
        }
    }

    public function loadClass($class)
    {
        $file = $this->loadUnderScores($class);
        if (!$file) {
            $file = $this->loadNameSpaced($class);
        }

        if ($file) {
            include_once($file);
        }
    }

    public function loadUnderScores($class)
    {
        $parts = explode('_', $class);

        if ($parts[0] == 'Jadu') {

            if (isset($parts[1])) {
                switch($parts[1]) {
                    case 'Custom':
                        $parts[1] = 'custom';
                        break;
                    
                    case 'Utilities':
                        $parts[1] = 'utilities';
                        break;

                    case 'XForms2':
                        $parts[1] = 'xforms2';
                        break;
                }
            }

            array_shift($parts);
            $file = implode(DIRECTORY_SEPARATOR, $parts) . '.php';

            if (file_exists(__DIR__ . '/' . $file)) {
                return $file;
            }
        }
        return false;
    }

    public function loadNameSpaced($class)
    {
        $file = str_replace('\\', '/', $class) . '.php';

        if (file_exists(__DIR__ . '/' . $file)) {
            return $file;
        }

        return false;
    }
}



$rootDir = defined('MAIN_HOME_DIR') ? MAIN_HOME_DIR : dirname(dirname(__FILE__));
require_once($rootDir .'/vendor/autoload.php');

$autoloader = new Autoload(array(
    'Jadu\AbstractController' => 'Jadu_AbstractController',
    'Jadu\Abstracts\Container' => 'Jadu_Abstracts_Container',
    'Jadu\Config' => 'Jadu_Config',
    'Jadu\Response\AbstractResponse' => 'Jadu_Response_AbstractResponse',
    'Jadu\Response\HtmlResponse' => 'Jadu_Response_HtmlResponse',
    'Jadu\Response\InvalidResponse' => 'Jadu_Response_InvalidResponse',
    'Jadu\Response\JsonResponse' => 'Jadu_Response_JsonResponse',
    'Jadu\Response\RedirectResponse' => 'Jadu_Response_RedirectResponse',
    'Jadu\Response\Header' => 'Jadu_Response_Header',
    'Jadu\Response\Interfaces\InterfaceResponse' => 'Jadu_Response_Interfaces_InterfaceResponse',
    'Jadu\Response\CSVResponse' => 'Jadu_Response_CSVResponse',
    'Jadu\UI\Controllers\Resource' => 'Jadu_UI_Controllers_Resource',
    'Jadu\UI\Controllers\ControlCentre\Page' => 'Jadu_UI_Controllers_ControlCentre_Page',
));

spl_autoload_register(array($autoloader, 'loadClass'));
