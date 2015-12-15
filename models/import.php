<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class KelioniuImportasModelImport extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication();

        $travelTypeId = $this->getUserStateFromRequest($this->context . '.filter.traveltype_id', 'filter_traveltype_id');
        $this->setState('filter.traveltype_id', $travelTypeId);

        $countryId = $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id');
        $this->setState('filter.country_id', $countryId);
        
        $rodytiImp = $this->getUserStateFromRequest($this->context . '.filter.rodyti_importuotas', 'filter_rodyti_importuotas');
        $this->setState('filter.rodyti_importuotas', $rodytiImp);        

        // List state information.
        parent::populateState('a.title', 'asc');
    }

    public function getItems() {
        $travelTypeId = (int) $this->getUserStateFromRequest($this->context . '.filter.traveltype_id', 'filter_traveltype_id');
        //$countryId = (int) $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id');

        if ($travelTypeId) {
            require_once JPATH_COMPONENT . '/helpers/data.php';

            $keliones = new Keliones();
            $keliones->LoadCountriesListFromXMLFile();
            $keliones->LoadFromXMLFile($travelTypeId);
            return $keliones;
        } else {

            return null;
        }
    }

    public function getTravelTypes() {
        require_once JPATH_COMPONENT . '/helpers/xmldata.php';

        return XMLDataHelper::getTravelTypes();
    }

    public function getCountries() {
        require_once JPATH_COMPONENT . '/helpers/xmldata.php';

        return XMLDataHelper::getCountries();
    }

    public function getImportCategories() {
        $params = JComponentHelper::getParams('com_kelioniuimportas');

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Construct the query
        $query->select('id AS value, name AS text');
        $query->from('#__zoo_category');
        $query->where('parent=0 AND application_id=' . (int) $params->getValue('application_id', 1));
        $query->order('name');

        // Setup the query
        $db->setQuery($query->__toString());

        // Return the result
        return $db->loadObjectList();
    }

}
