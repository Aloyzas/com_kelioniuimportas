<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KelioniuImportasViewPrepareData extends JView {

    protected $items;
    protected $downloadList;

    /**
     * Display the view
     */
    public function display($tpl = null) {     
        //atsidarom kelioniu tipus reikiamu atiusti failu sarasui
        $this->items = $this->get('Items');
        
        $this->downloadList = array('countries');
        
        foreach ($this->items as $item) {
           $this->downloadList[] =  $item['value'];
        }

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
        JToolBarHelper::title(JText::_('Kelionių importas') . ': ' . JText::_('Duomenų paruošimas'), 'busy');
    }

}
