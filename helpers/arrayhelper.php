<?php

// No direct access to this file
defined('_JEXEC') or die;

abstract class KelioniuImportasArrayHelper {

    /**
     * Randa masyvo įrašo indeksą.
     * @param type $array paieškos masyvas
     * @param type $field paieškos laukas
     * @param type $value ieškoma reikšmė
     */
    public static function FindArrayIndex($array, $field, $value) {
        if (is_array($array)) {
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i][$field] == $value) {
                    return $i;
                }
            }
        }

        return -1;
    }

}

