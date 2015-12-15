<?php

defined('_JEXEC') or die;

abstract class AjaxHelper {

    public static function downloadXML($id) {
        $userId = JFactory::getUser()->get('id');
        if ($userId) {
            require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'xmldata.php';

            if ($id == 'countries') {
                XMLDataHelper::downloadFile('http://booking.gruda.lt/tours/api/?method=exportCountries', 'countries.xml');
            } else {
                XMLDataHelper::downloadFile('http://booking.gruda.lt/tours/api/?method=getTravels&travelType=' . $id, "travels$id.xml");
            }
        }
        return null;
    }

    public static function downloadImage($travelId, $image, $subdir, $url) {
        $userId = JFactory::getUser()->get('id');
        if ($userId) {
            require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'imagedownloader.php';

            KelioniuImportasImageDownloaderHelper::downloadImage($travelId, $image, $subdir, $url);
        } else {
            echo 'prisijungimo klaida';
        }
        return null;
    }

}

