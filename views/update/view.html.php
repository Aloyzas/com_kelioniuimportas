<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KelioniuImportasViewUpdate extends JView {

    protected $items;
    protected $itemsPasalintos;
    protected $itemsNeaktyvintos;
    protected $itemsKeitimui;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $model = $this->getModel();

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->itemsPasalintos = $model->getItemsPasalintos($this->items->getIDList());
        $this->itemsNeaktyvintos = $model->getItemsNeaktyvintos($this->items->getIDList());
        $this->itemsKeitimui = $model->getItemsKeitimui($this->itemsPasalintos->getIDList());

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
        JToolBarHelper::title(JText::_('COM_KELIONIUIMPORTAS_MANAGER') . ': ' . 'Atnaujinimas', 'travel');

        JToolBarHelper::custom('update.update', 'update', 'update', 'Atnaujinti');
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_kelioniuimportas');
    }

}
