<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KelioniuImportasViewDownloadImages extends JView {

    protected $items;

    /**
     * Display the view
     */
    public function display($tpl = null) {

        //paveikslelių sąrašas atsiuntimui
        $this->items = $this->get('Items');

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
        JToolBarHelper::title(JText::_('Kelionių importas') . ': ' . JText::_('Paveikslėlių atsiuntimas'), 'busy');
    }

}
