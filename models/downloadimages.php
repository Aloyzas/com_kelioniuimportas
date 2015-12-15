<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class KelioniuImportasModelDownloadImages extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getItems() {
        require_once JPATH_COMPONENT . '/helpers/data.php';

        $travelTypeId = JRequest::getInt('ttid');
        $arrayTravelId = explode(',', JRequest::getString('tid'));

        $keliones = new Keliones();
        $keliones->LoadFromXMLFile($travelTypeId);

        $items = array();

        foreach ($keliones as $kelione) {
            if (in_array($kelione->id, $arrayTravelId)) { //atrenka tik pasirinktu kelioniu paveikslelius
                $paveiksleiai = $kelione->paveiksleliai->toArray();
                foreach ($paveiksleiai as $paveikslelis) {
                    $items[] = array('travelId' => (int) $kelione->id, 'image' => (string) $paveikslelis['byla'], 'subdir' => (string) $paveikslelis['subdir'], 'url' => base64_encode($paveikslelis['url']));
                }
            }
        }

        return $items;
    }

}
