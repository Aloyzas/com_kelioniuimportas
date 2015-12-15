<?php

// No direct access to this file
defined('_JEXEC') or die;

abstract class KelioniuImportasImageDownloaderHelper {

    protected static function putIndex($path) {
        $path = JPath::clean($path);
        $content = '<!DOCTYPE html><title></title>';
        file_put_contents($path . DS . 'index.html', $content);
    }

    protected static function prepareFolder($path) {
        $path = JPath::clean($path);
        if (!is_dir($path)) {
            JFolder::create($path);
            KelioniuImportasImageDownloaderHelper::putIndex($path);
        }

        return $path;
    }

    /**
     * Atsiunčia paveikslėlį iš nurodyto adreso į standartinį katalogą.  
     * @param type $image paveikslėlio vardas
     * @param type $travelId kelionės ID - naudojamas pakatologiui sukurti
     * @param type $subdir2 papildomas pakatologis     
     * @param type $url paveikslelio adresas 
     * @return string
     */
    public static function downloadImage($subDir, $image, $subdir2, $url) {
        $pathImportas = JPATH_ROOT . DS . 'images' . DS . 'importas';
        KelioniuImportasImageDownloaderHelper::prepareFolder($pathImportas);

        $pathImages = $pathImportas . DS . $subDir;
        KelioniuImportasImageDownloaderHelper::prepareFolder($pathImages);

        //prijungiam papildoma pakatalogi
        if ($subdir2) {
            $pathImages = $pathImages . DS . $subdir2;
            KelioniuImportasImageDownloaderHelper::prepareFolder($pathImages);
        }

        $fullPath = $pathImages . DS . strtolower($image);

        if (!file_exists($fullPath)) {  //jei dar neatsiustas
            $ctx = stream_context_create(array('http' => array('timeout' => 60))); // nustatom minutes timeouta
            $content = file_get_contents($url, false, $ctx);
            if (!empty($content)) {
                file_put_contents($fullPath, $content);
            }
        }

        return $fullPath;
    }

}

