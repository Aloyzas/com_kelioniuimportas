<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KelioniuImportasControllerPrepareData extends JControllerAdmin {

    protected $text_prefix = 'COM_KELIONIUIMPORTAS_PREPAREDATA';

    function getModel($name = 'PrepareData', $prefix = 'KelioniuImportasModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

}
