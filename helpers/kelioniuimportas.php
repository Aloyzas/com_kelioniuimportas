<?php

// No direct access to this file
defined('_JEXEC') or die;

abstract class KelioniuImportasHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu) {
        $cKey = JRequest::getVar('ckey');
        $var = '';
        if (!empty($cKey)) {
            $var = '&ckey=' . $cKey;
        }

        if (JRequest::getString('view') != 'preparedata') {
            JSubMenuHelper::addEntry(JText::_('COM_KELIONIUIMPORTAS_SUBMENU_IMPORT'), 'index.php?option=com_kelioniuimportas&view=import' . $var, $submenu == 'import');
            JSubMenuHelper::addEntry(JText::_('COM_KELIONIUIMPORTAS_SUBMENU_UPDATE'), 'index.php?option=com_kelioniuimportas&view=update' . $var, $submenu == 'update');
        }
    }

}
