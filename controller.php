<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');


class KelioniuImportasController extends JController {

    protected $default_view = 'preparedata';

    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/kelioniuimportas.php';

        // set default view if not set
        $view = JRequest::getCmd('view', $this->default_view);
        JRequest::setVar('view', $view);

        //Jei neparinktas keso raktas parenkame ji. Jis veliau naudojamas atsiustu duomenu kesinimui
        $cKey = JRequest::getVar('ckey');
        if (empty($cKey)) {
            $cKey = date('Ymd');
            return $this->setRedirect(JRoute::_('index.php?option=com_kelioniuimportas&view=' . $view . '&ckey=' . $cKey, false));             
        }
        //
        $layout = JRequest::getCmd('layout', 'default');
        $id = JRequest::getInt('id');

        // Set the submenu
        KelioniuImportasHelper::addSubmenu($view);
       
        // call parent behavior
        parent::display($cachable, $urlparams);
    }

}
