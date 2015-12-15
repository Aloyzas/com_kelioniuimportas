<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KelioniuImportasControllerImport extends JControllerAdmin {

    protected $text_prefix = 'COM_KELIONIUIMPORTAS_IMPORT';

    function getModel($name = 'Import', $prefix = 'KelioniuImportasModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function import() {
        $cKey = JRequest::getString('ckey');

        $parentId = JRequest::getInt('import_category_id');

        //patikriname ar pasirinkta kategorija importui
        if (!$parentId) {
            $this->setMessage('Pasirinkite kategoriją importui!', 'error');
            return $this->setRedirect(JRoute::_("index.php?option=com_kelioniuimportas&view=import&ckey=$cKey", false));
        }

        $tid = JRequest::getVar('cid');   //pasirinktu kelioniu ID
        $ttid = JRequest::getInt('travelTypeId');

        $params = JComponentHelper::getParams('com_kelioniuimportas');
        $appId = $params->getValue('application_id', 1);

        //importuojam keliones
        require_once JPATH_COMPONENT . '/helpers/data.php';

        $keliones = new Keliones();
        $keliones->LoadCountriesListFromXMLFile();
        $keliones->LoadFromXMLFile($ttid);

        $db = JFactory::getDbo();

        $userId = JFactory::getUser()->get('id');

        $error = array();
        $this->createSubcategories($keliones, $tid, $parentId, $appId);
        foreach ($keliones as $kelione) {
            if (in_array($kelione->id, $tid)) { //atrenka tik pasirinktas keliones
                $query = $db->getQuery(true);
                $query->insert('#__zoo_item');

                $cols = array();
                $vals = array();

                $cols[] = 'application_id';
                $vals[] = '1';

                $cols[] = 'type';
                $vals[] = $db->quote('kelione');

                $cols[] = 'state';
                $vals[] = '0';

                $cols[] = 'access';
                $vals[] = '1';

                $cols[] = 'created';
                $vals[] = 'NOW()';

                $cols[] = 'created_by';
                $vals[] = (int) $userId;

                $cols[] = 'modified';
                $vals[] = 'NOW()';

                $cols[] = 'modified_by';
                $vals[] = (int) $userId;

                $cols[] = 'publish_up';
                $vals[] = $db->quote('0000-00-00 00:00:00');

                $cols[] = 'publish_down';
                $vals[] = $db->quote('0000-00-00 00:00:00');

                $cols[] = 'priority';
                $vals[] = '0';

                $cols[] = 'hits';
                $vals[] = '0';

                $cols[] = 'created_by_alias';
                $vals[] = "''";

                $cols[] = 'searchable';
                $vals[] = '0';

                $cols[] = 'name';
                $vals[] = $db->quote($kelione->pavadinimas);

                $alias = $this->getUniqueAlias($kelione->pavadinimas);
                $cols[] = 'alias';
                $vals[] = $db->quote($alias);

                $cols[] = 'elements';
                $vals[] = $db->quote($kelione->Serialize());

                $arr = $kelione->salys->toArray();
                $catId = $this->getCategoryId($arr[0], $parentId);
                $paramsX = ' { "config.enable_comments": "0", "config.primary_category": "' . $catId . '" }';

                $cols[] = 'params';
                $vals[] = $db->quote($paramsX);

                $query->columns($cols);
                $query->values(implode(', ', $vals));

                $db->setQuery($query);
                if (!$db->execute()) {
                    $error[] = $db->getErrorNum() . ': Nepavyko importuoti kelionės (ID ' . $kelione->id . ').';
                } else {
                    //Iterpiam kategorijas
                    $itemId = $this->getItemIdByAlias($alias);
                    if ($itemId) {
                        $this->createSarysis($kelione->id, $itemId);
                        $this->setItemsCategories($itemId, $arr, $parentId);
                    }
                }
            }
        }

        if (!empty($error)) {
            $this->setMessage(implode('<br>', $error), 'error');
        }

        //importuojam paveikslelius
        $stid = implode(',', $tid);   //pasirinktu kelioniu ID
        return $this->setRedirect(JRoute::_("index.php?option=com_kelioniuimportas&view=downloadimages&ckey=$cKey&ttid=$ttid&tid=$stid", false));
    }

    protected function createSubcategories($keliones, $kid, $parentId, $appId) {
        $salys = array();

        foreach ($keliones as $kelione) {
            if (in_array($kelione->id, $kid)) {
                $salys = array_merge($salys, $kelione->salys->toArray());
            }
        }
        $salys = array_unique($salys, SORT_STRING);

        $db = JFactory::getDbo();

        foreach ($salys as $salis) {
            $query = 'SELECT EXISTS(SELECT id FROM #__zoo_category WHERE parent = ' . (int) $parentId . ' AND UPPER(TRIM(name)) = ' . $db->quote(strtoupper(trim((string) $salis))) . ')';
            $db->setQuery($query);
            if ((int) $db->loadResult() === 0) {
                $name = (string) $salis;
                $alias = $this->getUniqueAlias($name, '#__zoo_category');
                $query = 'INSERT INTO #__zoo_category (application_id,name,alias,description,parent,ordering,published,params) VALUES (' . $appId . ',' . $db->quote($name) . ',' . $db->quote($alias) . ',"",' . (int) $parentId . ',0,1,"")';
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

    protected function getUniqueAlias($title, $table = '#__zoo_item') {
        $alias = JFilterOutput::stringURLSafe($title);

        $new_alias = $alias;
        while ($this->checkAliasExists($new_alias, $table)) {
            $new_alias = JString::increment($new_alias, 'dash');
        }
        return $new_alias;
    }

    protected function checkAliasExists($alias, $table) {
        $db = JFactory::getDbo();

        $query = 'SELECT EXISTS (SELECT id FROM ' . $table . ' WHERE alias = ' . $db->Quote($alias) . ')';
        $db->setQuery($query);
        return (int) $db->loadResult() === 1;
    }

    protected function getCategoryId($name, $parentId) {
        $db = JFactory::getDbo();
        $query = 'SELECT id FROM #__zoo_category WHERE parent = ' . (int) $parentId . ' AND UPPER(TRIM(name)) = ' . $db->quote(strtoupper(trim($name))) . ' LIMIT 1';
        $db->setQuery($query);
        return $db->loadResult();
    }

    protected function getItemIdByAlias($alias) {
        $db = JFactory::getDbo();
        $query = 'SELECT id FROM #__zoo_item WHERE alias = ' . $db->quote($alias);
        $db->setQuery($query);
        return $db->loadResult();
    }

    protected function setItemsCategories($itemId, $cats, $parentId) {
        $db = JFactory::getDbo();
        foreach ($cats as $cat) {
            $catId = $this->getCategoryId($cat, $parentId);

            $query = 'SELECT EXISTS (SELECT item_id FROM #__zoo_category_item WHERE item_id = ' . (int) $itemId . ' AND category_id = ' . (int) $catId . ')';
            $db->setQuery($query);
            if ((int) $db->loadResult() === 0) {
                $query = "INSERT INTO #__zoo_category_item (category_id, item_id) VALUES($catId,$itemId)";
                $db->setQuery($query);
                $db->execute();
            };
        }
    }

    /**
     * Suriša ZOO ir grudos ID.
     * @param type $travelId 
     * @param type $zooItemId
     */
    protected function createSarysis($travelId, $zooItemId) {
        $db = JFactory::getDbo();

        $query = "INSERT INTO #__kelioniuimportas (id, zoo_item_id) VALUES($travelId,$zooItemId)";
        $db->setQuery($query);
        $db->execute();
    }

}
