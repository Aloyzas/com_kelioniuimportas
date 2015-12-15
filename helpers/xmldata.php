<?php

// No direct access to this file
defined('_JEXEC') or die;

abstract class XMLDataHelper {

    //const GET_TRAVELS_METHOD_URL = '';

    protected static function putIndex($path) {
        $path = JPath::clean($path);
        $content = '<!DOCTYPE html><title></title>';
        file_put_contents($path . DS . 'index.html', $content);
    }

    protected static function prepareFolder() {
        $cKey = JRequest::getVar('ckey');

        $config = JFactory::getConfig();
        $tmpPath = $config->getValue('config.tmp_path');

        $dir = 'kelioniuimportas_' . $cKey;
        $path = JPath::clean($tmpPath . DS . $dir);

        if (!is_dir($path)) {
            JFolder::create($path);
            XMLDataHelper::putIndex($path);
        }

        return $path;
    }

    public static function downloadFile($url, $file) {
        $path = XMLDataHelper::prepareFolder();

        $fullPath = $path . DS . $file;

        if (!file_exists($fullPath)) {  //jei dar neatsiustas
            $ctx = stream_context_create(array('http' => array('timeout' => 120))); // nustatom dvieju minuciu timeouta
            $content = file_get_contents($url, false, $ctx);

            if (!empty($content)) {
                file_put_contents($fullPath, $content);
            }
        }

        return $fullPath;
    }

    public static function getTravelTypes() {

        $file = XMLDataHelper::downloadFile('http://booking.gruda.lt/tours/api/?method=exportTravelType', 'travelTypes.xml');

        $fullXML = simplexml_load_file($file);

        $XML = $fullXML->xpath('exportTravelType');
        $list = array();
        foreach ($XML[0] as $item) {
            if ($item->id) {
                $list[] = array('value' => (int) $item->id, 'text' => (string) $item->title);
            }
        }

        return $list;
    }

    public static function getCountries() {

        $file = XMLDataHelper::downloadFile('http://booking.gruda.lt/tours/api/?method=exportCountries', 'countries.xml');

        $fullXML = simplexml_load_file($file);

        $XML = $fullXML->xpath('exportCountries');
        $list = array();
        foreach ($XML[0] as $item) {
            if ($item->id) {
                $list[] = array('value' => (int) $item->id, 'text' => (string) $item->title);
            }
        }

        return $list;
    }

    public static function getTravels($travelTypeId, $countryId) {
        require_once JPATH_COMPONENT . '/helpers/data.php';
        $file = XMLDataHelper::downloadFile('http://booking.gruda.lt/tours/api/?method=getTravels&travelType=' . $travelTypeId, "travels$travelTypeId.xml");

        $fullXML = simplexml_load_file($file);

        $XML = $fullXML->xpath('getTravels');
        $keliones = new Keliones();
        foreach ($XML[0] as $item) {
            if ($item->visible) {
                $keliones->addFromXML($item);
            }
        }

        return $keliones;
    }

    /**
     * Ieško masyve nurodytos reikšmės
     * @param type $array duomenų masyvas
     * @param type $sKey ieškomo lauko pavadinimas
     * @param type $sValue ieškoma reikšmė
     * @param type $rKey rezultato lauko pavadinimas
     * @return boolean 
     */
    public static function arraySearch($array, $sKey, $sValue, $rKey) {
        if (is_array($array)) {
            foreach ($array as $item) {
                if ($item[$sKey] === $sValue) {
                    return $item[$rKey];
                }
            }
        }

        return null;
    }

}

