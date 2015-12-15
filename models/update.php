<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class KelioniuImportasModelUpdate extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getItems() {
        require_once JPATH_COMPONENT . '/helpers/data.php';

        $keliones = new Keliones();
        $keliones->LoadCountriesListFromXMLFile();
        $keliones->LoadAllFromXML();
        
        return $keliones;
    }

    /**
     * Kelionių sąrašas kurio nebėra tiekėjo DB, tačiau vis dar yra mūsų DB.
     */
    public function getItemsPasalintos($ids = array()) {
        require_once JPATH_COMPONENT . '/helpers/data.php';

        $keliones = new Keliones();

        $keliones->LoadFromDB(1, $ids);

        return $keliones;
    }
    
    /**
     * Kelionių sąrašas kurios yra tiekėjo DB, tačiau neaktyvintos mūsų DB.
     */
    public function getItemsNeaktyvintos($ids = array()) {
        require_once JPATH_COMPONENT . '/helpers/data.php';

        $keliones = new Keliones();

        $keliones->LoadFromDB(0, array(), $ids);

        return $keliones;
    }
    
    /**
     * Importuotų kelionių sąrašas.
     */
    public function getItemsKeitimui($skip = array()) {
        require_once JPATH_COMPONENT . '/helpers/data.php';

        $keliones = new Keliones();

        $keliones->LoadFromDB(1, $skip);

        return $keliones;
    }    

}
