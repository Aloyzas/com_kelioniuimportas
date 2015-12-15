<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KelioniuImportasControllerUpdate extends JControllerAdmin {

    protected $text_prefix = 'COM_KELIONIUIMPORTAS_UPDATE';

    function getModel($name = 'Update', $prefix = 'KelioniuImportasModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function update() {
        $msg = '';
        $db = JFactory::getDbo();

        $cKey = JRequest::getString('ckey');

        $aId = JRequest::getVar('aid');   //pasirinktu pasalinti kelioniu ID

        if (count($aId) > 0) {
            JArrayHelper::toInteger($aId, 0);

            $db->setQuery('UPDATE #__zoo_item SET state=0 WHERE id IN (' . implode(',', $aId) . ')');
            if ($db->execute()) {
                $msg .= count($aId) . ' kelionės pažymėtos neaktyviomis.<br>';
            } else {
                
            }
        }

        $bId = JRequest::getVar('bid');   //pasirinktu aktyvinimui kelioniu ID

        if (count($bId) > 0) {
            JArrayHelper::toInteger($bId, 0);

            $db->setQuery('UPDATE #__zoo_item SET state=1 WHERE id IN (' . implode(',', $bId) . ')');
            if ($db->execute()) {
                $msg .= count($bId) . ' kelionės aktyvintos.<br>';
            } else {
                
            }
        }

        $cId = JRequest::getVar('cid');   //pasirinktu aktyvinimui kelioniu ID

        if (count($cId) > 0) {
            JArrayHelper::toInteger($cId, 0);
            require_once JPATH_COMPONENT . '/helpers/data.php';

            $keliones = new Keliones();
            $keliones->LoadCountriesListFromXMLFile();
            $keliones->LoadAllFromXML();

            $i = 0;
            $j = 0;
            foreach ($cId as $zoo_id) {
                $db->setQuery('SELECT id FROM #__kelioniuimportas WHERE zoo_item_id = ' . (int) $zoo_id);
                $gruda_id = (int) $db->loadResult();
                if ($gruda_id) {
                    $kelione = $keliones->getByID($gruda_id);
                    if ($kelione) {
                        $db->setQuery('SELECT elements FROM #__zoo_item WHERE id = ' . (int) $zoo_id);
                        $result = $db->loadResult();
                        if ($result) {
                            $elements = json_decode($result, true);
                            $datosSer = $kelione->datos->Serialize();
                            if ($datosSer) {
                                $datosSer = str_replace('"d198fa4e-8df3-4854-a717-893281154ff8": ', '', $datosSer);
                                $datos = json_decode($datosSer, true);
                                $elements['d198fa4e-8df3-4854-a717-893281154ff8'] = $datos;
                            } else {
                                unset($elements['d198fa4e-8df3-4854-a717-893281154ff8']);
                                $j++;
                            }
                            $db->setQuery('UPDATE #__zoo_item SET elements=' . $db->quote(json_encode((object) $elements)) . ' WHERE id = ' . (int) $zoo_id);
                            if ($db->execute()) {
                                $i++;
                            } else {
                                $msg .= '<p class="error">Įvyko klaida atnaujinant kelionę ZOO_ID=' . $zoo_id . '</p><br>';
                            };
                        }
                    }
                }
            }
            if ($i > 0) {
                if ($j > 0) {
                    $msg .= (int) ($i) . ' kelionių atnaujintos išvykimo datos (' . $j . ' iš jų pašalintos viso išvykimo datos).<br>';
                } else {
                    $msg .= (int) ($i) . ' kelionių atnaujintos išvykimo datos.<br>';
                }
            }
        }

        if ($msg) {
            $this->setMessage($msg);
        }
        return $this->setRedirect(JRoute::_("index.php?option=com_kelioniuimportas&view=update&ckey=$cKey", false));
    }

}
