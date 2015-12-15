<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KelioniuImportasViewImport extends JView {

    protected $items;
    protected $state;
    protected $travelTypes;
    protected $countries;
    protected $importCategories;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->travelTypes = $this->get('TravelTypes');
        $this->countries = $this->get('Countries');
        $this->importCategories = $this->get('importCategories');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {       
        JToolBarHelper::title(JText::_('COM_KELIONIUIMPORTAS_MANAGER') . ': ' . JText::_('COM_KELIONIUIMPORTAS_IMPORT'), 'travel');

        JToolBarHelper::custom('import.import', 'import', 'import', 'Importuoti');
        JToolBarHelper::divider();        
        JToolBarHelper::preferences('com_kelioniuimportas');        
    }

}
