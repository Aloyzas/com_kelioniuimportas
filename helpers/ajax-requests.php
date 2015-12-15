<?php

/*
 * Standalone script for ajax requests
 */

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

define('JPATH_BASE', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_kelioniuimportas');

require_once( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
require_once( JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php' );

// Instantiate the application.
$app = JFactory::getApplication('administrator');


$action = JRequest::getString('action', '');

if (!empty($action)) {

    require_once( 'ajax.php');

    switch ($action) {
        case 'downloadXML':
            echo AjaxHelper::downloadXML(JRequest::getString('id'));
            break;
        case 'downloadImage':
            echo AjaxHelper::downloadImage(JRequest::getInt('travelId', 0), JRequest::getString('image', ''), JRequest::getString('subdir', ''), base64_decode(JRequest::getString('url', '')));
            break;

        default:
            echo 'error: no such method.';
            break;
    }
}

$app->close();



