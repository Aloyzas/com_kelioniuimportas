<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class KelioniuImportasModelPrepareData extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getItems() {
        require_once JPATH_COMPONENT . '/helpers/xmldata.php';

        return XMLDataHelper::getTravelTypes();
    }

}
