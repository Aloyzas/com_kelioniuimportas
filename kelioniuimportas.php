<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_kelioniuimportas')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// import joomla controller library
jimport('joomla.application.component.controller');

JHtml::_('stylesheet', JURI::root() . 'administrator/components/com_kelioniuimportas/assets/kelioniuimportas.css', array(), true);

$controller = JController::getInstance('KelioniuImportas');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
