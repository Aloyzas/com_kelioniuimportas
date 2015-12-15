<?php

// No direct access to this file
defined('_JEXEC') or die;

class Kelione {

    public $id = 0;     //grudos id
    public $zoo_id = 0; //zoo item id
    public $pavadinimas = '';
    //
    public $aprasymas = '';
    public $trumpas_aprasymas = '';
    //
    public $iskaiciuota = '';
    public $neiskaiciuota = '';
    //
    public $papildoma_info = '';
    //
    public $salys = null;
    public $dienos = null;
    public $paveiksleliai = null;
    public $datos = null;

    public function __construct() {
        $this->salys = new KelionesSalys();
        $this->dienos = new KelionesDienos();
        $this->paveiksleliai = new KelionesPaveiksleliai();
        $this->datos = new KelionesDatos();
    }

    /**
     * Patikrina ar kelionė jau importuota į ZOO DB.     
     */
    public function isImported() {
        $db = JFactory::getDbo();

        $db->setQuery("SELECT EXISTS(SELECT * FROM #__kelioniuimportas a, #__zoo_item b WHERE a.id={$this->id} AND b.id=a.zoo_item_id)");
        return ((int) $db->loadResult() === 1);
    }

    public function Serialize() {
        $params = JComponentHelper::getParams('com_kelioniuimportas');

        $output = array();

        //trumpas aprasymas
        $uid = '6e3d923e-eb44-4a7e-9a4d-4d0b7befde92';
        $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->trumpas_aprasymas) . ' } }';

        //trumpas aprasymas
        $uid = 'ebe77fbe-d4c8-441b-a042-02d2d8ca62fb';
        $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->aprasymas) . ' } }';

        //iskaiciuota
        $uid = 'e86fbcf6-692f-48d8-ac9b-46a564c23c60';
        $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->iskaiciuota) . ' } }';

        //neiskaiciuota
        $uid = '1be9861d-f524-4274-bd38-0e35f15672b2';
        $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->neiskaiciuota) . ' } }';

        //paildoma informacija
        $uid = '90080905-36e5-416e-856d-99fc23056b00';
        $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->papildoma_info) . ' } }';

        //vezejas
        $uid = '0712a58f-690c-438f-8a46-49e12715bcac';
        $output[] = '"' . $uid . '":  { "option": { "0": "gruda" }, "select": "' . $params->getValue('vezejas', 'gruda') . '" }';

        //paveikslelis
        if ($this->paveiksleliai->getFirst() != '') {
            $uid = '7a3303c9-4abf-4243-9f94-4dc4d46ad52b';
            $output[] = '"' . $uid . '":  { "file": ' . json_encode('images/importas/' . $this->id . '/' . $this->paveiksleliai->getFirst()) . ' }';

            //galerija
            $uid = '408119b1-f46d-4c9c-bc34-14dd16bd9e48';
            $output[] = '"' . $uid . '":  { "value": ' . json_encode('images/importas/' . $this->id) . ', "title": "" }';
        }

        //trukme
        if ($this->datos->getTrukme() != '') {
            $uid = '073ba2cd-752e-476f-82f9-9e605f27a098';
            $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->datos->getTrukme()) . ' } }';
        }

        //kaina
        if ($this->datos->getKainaNuo() != '') {
            $uid = '306e85be-f3fa-45fe-a253-1d962aff4dd2';
            $output[] = '"' . $uid . '":  { "0": { "value": ' . json_encode($this->datos->getKainaNuo()) . ' } }';
        }

        //dienos
        if ($this->dienos->count() > 0) {
            $output[] = $this->dienos->Serialize();
        }

        //isvykimo datos
        if ($this->datos->count() > 0) {
            $output[] = $this->datos->Serialize();
        }

        return '{ ' . implode(', ', $output) . ' }';
    }

}

class Keliones implements Iterator {

    public $keliones = array();
    private $countriesList = array();
    protected $i = 0;

    public function add($id, $pavadinimas, array $data) {
        require_once JPATH_COMPONENT . '/helpers/text.php';

        $kelione = new Kelione();

        $kelione->id = (int) $id;
        $kelione->pavadinimas = (string) $pavadinimas;

        if (isset($data['aprasymas'])) {
            $kelione->aprasymas = KelioniuImportasTextHelper::CleanText($data['aprasymas']);
        }

        if (isset($data['trumpas_aprasymas'])) {
            $trumpas_aprasymas = KelioniuImportasTextHelper::CleanText($data['trumpas_aprasymas']);
            if (strlen($trumpas_aprasymas) > 450) {
                $trumpas_aprasymas = substr($trumpas_aprasymas, 0, 450) . '...';
                $trumpas_aprasymas = KelioniuImportasTextHelper::closeTags($trumpas_aprasymas);
            }

            $kelione->trumpas_aprasymas = $trumpas_aprasymas;
        }

        if (isset($data['iskaiciuota'])) {
            $kelione->iskaiciuota = KelioniuImportasTextHelper::CleanText($data['iskaiciuota']);
        }

        if (isset($data['neiskaiciuota'])) {
            $kelione->neiskaiciuota = KelioniuImportasTextHelper::CleanText($data['neiskaiciuota']);
        }

        if (isset($data['papildoma_info'])) {
            $kelione->papildoma_info = KelioniuImportasTextHelper::CleanText($data['papildoma_info']);
        }

        if (isset($data['salys'])) {
            $kelione->salys = $data['salys'];
        }

        if (isset($data['dienos'])) {
            $kelione->dienos = $data['dienos'];
        }

        if (isset($data['datos'])) {
            $kelione->datos = $data['datos'];
        }

        if (isset($data['paveiksleliai'])) {
            $kelione->paveiksleliai = $data['paveiksleliai'];
        }

        if (isset($data['zoo_id'])) {
            $kelione->zoo_id = (int) $data['zoo_id'];
        }

        $this->keliones[] = $kelione;
    }

    public function addFromXML($xml) {
        $salys = new KelionesSalys();
        $xmlSalys = $xml->xpath('countries');
        require_once JPATH_COMPONENT . '/helpers/arrayhelper.php';
        if (!empty($xmlSalys)) {
            foreach ($xmlSalys[0] as $value) {
                $ind = KelioniuImportasArrayHelper::FindArrayIndex($this->countriesList, 'value', $value);
                if ($ind >= 0) {
                    $pavad = $this->countriesList[$ind]['text'];
                } else {
                    $pavad = $value;
                }
                $salys->add($value, $pavad);
            }
        }

        $pav = new KelionesPaveiksleliai();
        $xmlPav = $xml->xpath('images');
        if (!empty($xmlPav)) {
            foreach ($xmlPav[0] as $value) {
                $pav->add('http://booking.gruda.lt/public/uploads/travels/images/' . $value->file);
            }
        }

        $dienos = new KelionesDienos();
        $xmlDienos = $xml->xpath('itinerary');
        $imgTextPath = '/images/importas/' . $xml->id . '/t/';
        if (!empty($xmlDienos)) {
            foreach ($xmlDienos[0] as $value) {
                $text = $value->text;

                //isrenkam paveikslelius is teksto
                $regex = '@ src="[^"].*?"@si';
                $matches = array();
                preg_match_all($regex, $text, $matches);

                if (count($matches[0]) > 0) {
                    foreach ($matches[0] as $src) {
                        //isisaugom paveiksleliu atsiuntimui
                        $pav->add(substr($src, 6, -1), '', 't');

                        //pakeiciam linka tekste
                        $text = str_replace($src, 'src="' . $imgTextPath . $pav->getLast() . '"', $text);
                    }
                }

                //papildomu nuotraukos po dienu aprasymu
                $xmlTourImages = $value->xpath('images');
                $htmlBlock = '';
                foreach ($xmlTourImages[0] as $img) {
                    $pav->add('http://booking.gruda.lt/public/uploads/tours/images/' . $img->file, '', 't');
                    $htmlBlock .= '{resizeimage image=' . $imgTextPath . $pav->getLast() . '|width=245|class=img245}';
                    //$htmlBlock .= '<img width="245" style="padding-right:5px;" src="' . $imgTextPath . $pav->getLast() . '">';
                }
                if ($htmlBlock) {
                    $htmlBlock = '<p>' . $htmlBlock . '</p>';
                }
                $dienos->add($value->title, $text . $htmlBlock);
            }
        }

        $datos = new KelionesDatos();
        $xmlDatos = $xml->xpath('dates');
        if (!empty($xmlDatos)) {
            foreach ($xmlDatos[0] as $xmlData) {
                $xmlViesbuciai = $xmlData->xpath('hotels');
                foreach ($xmlViesbuciai[0] as $xmlViesbutis) {
                    $xmlKambariai = $xmlViesbutis->xpath('accommodations');
                    foreach ($xmlKambariai[0] as $xmlKambarys) {
                        $datos->add($xmlData->date, $xmlData->id, $xmlData->durationDays . 'D ' . $xmlData->durationNights . 'N', $xmlViesbutis->title, $xmlViesbutis->id, $xmlKambarys->title, $xmlKambarys->id, $xmlKambarys->price_eur, 'EUR', $xmlKambarys->capacity);
                    }
                }
            }
        }

        $data = array('aprasymas' => $xml->text,
            'trumpas_aprasymas' => $xml->text,
            'iskaiciuota' => $xml->info,
            'neiskaiciuota' => $xml->guide,
            'papildoma_info' => $xml->additionalInfo,
            'salys' => $salys,
            'dienos' => $dienos,
            'datos' => $datos,
            'paveiksleliai' => $pav);

        $this->add($xml->id, $xml->title, $data);
    }

    public function LoadFromXMLFile($travelTypeId) {
        require_once JPATH_COMPONENT . '/helpers/xmldata.php';
        $file = XMLDataHelper::downloadFile('http://booking.gruda.lt/tours/api/?method=getTravels&travelType=' . $travelTypeId, "travels$travelTypeId.xml");

        $fullXML = simplexml_load_file($file);

        $XML = $fullXML->xpath('getTravels');
        foreach ($XML[0] as $item) {
            if ($item->visible == 1) {
                $this->addFromXML($item);
            }
        }
    }

    /**
     * Užkrauna keliones iš visų XML failų.
     */
    public function LoadAllFromXML() {
        $travelTypes = XMLDataHelper::getTravelTypes();

        foreach ($travelTypes as $type) {
            $this->LoadFromXMLFile((int) $type['value']);
        }
    }

    /**
     * Užkrauna įrašus iš DB. PASTABA: užkrauna tik dalį informacijos!
     * 
     * @param type $state - busena (-1 = visos; 0 = unpublished; 1 = published)
     * @param type $skip - įrašų ID kuriuos praleisti
     * @param type $load - įrašų ID kuriuos užkrauti
     */
    public function LoadFromDB($state = -1, $skip = array(), $load = array()) {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('a.id as zoo_id,a.name,a.state,a.elements,b.id');
        $query->from('#__zoo_item AS a, #__kelioniuimportas AS b');
        $query->where('a.id = b.zoo_item_id');

        if ($state != -1) {
            $query->where('a.state=' . (int) $state);
        }

        if (count($skip) > 0) {
            $query->where('b.id NOT IN (' . implode(',', $skip) . ')');
        }

        if (count($load) > 0) {
            $query->where('b.id IN (' . implode(',', $load) . ')');
        }

        $query->order('a.name');

        $db->setQuery($query);
        $items = $db->loadObjectList();

        foreach ($items as $item) {
            $data = array();

            $data['zoo_id'] = $item->zoo_id;

            $elements = json_decode($item->elements, true);

            if (isset($elements['6e3d923e-eb44-4a7e-9a4d-4d0b7befde92'][0]['value'])) {
                $data['trumpas_aprasymas'] = (string) $elements['6e3d923e-eb44-4a7e-9a4d-4d0b7befde92'][0]['value'];
            }

            if (isset($elements['d198fa4e-8df3-4854-a717-893281154ff8'])) {
                $datos = new KelionesDatos();
                foreach ($elements['d198fa4e-8df3-4854-a717-893281154ff8'] as $isvyk) {
                    if (!empty($isvyk)) {
                        if ($isvyk['data'] != '' || $isvyk['trukme'] != '' || $isvyk['viesbutis'] != '' || $isvyk['vietu'] != '' || $isvyk['kaina'] != '' || $isvyk['id']) {
                            $kaina = preg_replace('@[^0-9]@si', '', $isvyk['kaina']);
                            $valiuta = preg_replace('@[^A-Z]@si', '', $isvyk['kaina']);
                            $datosIds = explode(',', $isvyk['id']);
                            if (!isset($datosIds[0])) {
                                $datosIds[0] = '';
                            }
                            if (!isset($datosIds[1])) {
                                $datosIds[1] = '';
                            }
                            if (!isset($datosIds[2])) {
                                $datosIds[2] = '';
                            }
                            $datos->add($isvyk['data'], $datosIds[0], $isvyk['trukme'], $isvyk['viesbutis'], $datosIds[1], '', $datosIds[2], $kaina, $valiuta, $isvyk['vietu']);
                        }
                    }
                }
                $data['datos'] = $datos;
            }
            //categorijos
            $q2 = $db->getQuery(true);
            $q2->select('b.name');
            $q2->from('#__zoo_category_item a, #__zoo_category b');
            $q2->where('a.item_id=' . $item->zoo_id . ' AND b.id=a.category_id');
            $q2->order('b.name');

            $db->setQuery($q2);
            $cats = $db->loadObjectList();

            $salys = new KelionesSalys();
            foreach ($cats as $cat) {
                $salys->add(0, $cat->name);
            }

            $data['salys'] = $salys;

            $this->add($item->id, $item->name, $data);
        }
    }

    public function LoadCountriesListFromXMLFile() {
        require_once JPATH_COMPONENT . '/helpers/xmldata.php';

        $this->countriesList = XMLDataHelper::getCountries();
    }

    public function getByID($id) {
        foreach ($this->keliones as $kelione) {
            if ($kelione->id === $id) {
                return $kelione;
            }
        }
        return null;
    }

    public function current() {
        if ($this->valid()) {
            return $this->keliones[$this->i];
        } else {
            return null;
        }
    }

    public function key() {
        return $this->i;
    }

    public function next() {
        $this->i++;
    }

    public function rewind() {
        $this->i = 0;
    }

    public function valid() {
        return ($this->i >= 0) && ($this->i < count($this->keliones));
    }

    public function count() {
        return count($this->keliones);
    }

    /**
     * Gražina masyvą su kelionių ID
     * @return type
     */
    public function getIDList() {
        $list = array();
        foreach ($this as $item) {
            $list[] = $item->id;
        }
        return $list;
    }

}

class KelionesSalis {

    public $id = 0;
    public $pavadinimas = '';

    public function __construct($id, $pavadinimas) {
        $this->id = (int) $id;
        $this->pavadinimas = (string) $pavadinimas;
    }

}

class KelionesSalys {

    public $salys = array();

    public function toString() {
        return implode(', ', $this->toArray());
    }

    /**
     * Išveda kelionių pavadinimus į masyvą
     */
    public function toArray() {
        $list = array();
        foreach ($this->salys as $salis) {
            $list[] = $salis->pavadinimas;
        }

        return $list;
    }

    public function add($id, $pavadinimas) {
        $this->salys[] = new KelionesSalis($id, $pavadinimas);
    }

    /**
     * Patirkina ar į kelionę įeina nurodyta šalis pagal ID
     * @param type $id šalies ID
     * @return boolean
     */
    public function contains($id) {
        foreach ($this->salys as $salis) {
            if ($salis->id == $id) {
                return true;
            }
        }

        return false;
    }

}

class KelionesDiena {

    public $pavadinimas = '';
    public $aprasymas = '';

    public function __construct($pavadinimas, $aprasymas) {
        require_once JPATH_COMPONENT . '/helpers/text.php';
        $this->pavadinimas = (string) $pavadinimas;
        $this->aprasymas = KelioniuImportasTextHelper::CleanText($aprasymas, '<img>');
    }

}

class KelionesDienos implements Iterator {

    public $dienos = array();
    protected $i = 0;

    public function add($pavadinimas, $aprasymas) {
        $this->dienos[] = new KelionesDiena($pavadinimas, $aprasymas);
    }

    public function Serialize() {
        if ($this->count() > 0) {
            $output = array();

            $uid = 'b217a61b-cb35-42ab-9577-e4ef0d36e61b';

            foreach ($this->dienos as $i => $diena) {
                $output[] = '"' . (int) $i . '": { "title": ' . json_encode($diena->pavadinimas) . ', "description": ' . json_encode($diena->aprasymas) . ' }';
            }

            return '"' . $uid . '":  { ' . implode(', ', $output) . ' }';
        } else {
            return '';
        }
    }

    public function current() {
        if ($this->valid()) {
            return $this->dienos[$this->i];
        } else {
            return null;
        }
    }

    public function key() {
        return $this->i;
    }

    public function next() {
        $this->i++;
    }

    public function rewind() {
        $this->i = 0;
    }

    public function valid() {
        return ($this->i >= 0) && ($this->i < count($this->dienos));
    }

    public function count() {
        return count($this->dienos);
    }

}

class KelionesPaveikslelis {

    public $url = '';
    public $byla = '';
    public $subdir = '';

    public function __construct($url, $byla = '', $subdir = '') {

        if ($byla == '') {
            $pos = strrpos($url, '/');
            if ($pos > 0) {
                $pos++;
            }
            $byla = substr($url, $pos);
        }

        $url = str_replace(' ', '%20', $url); //isvalom tarpus is adresu       
        $this->url = (string) $url;

        $this->byla = strtolower(str_replace('%20', ' ', $byla)); //failuose paliekam tarpus
        $this->subdir = $subdir;
    }

}

class KelionesPaveiksleliai {

    public $paveiksleliai = array();

    public function add($url, $byla = '', $subdir = '') {
        $this->paveiksleliai[] = new KelionesPaveikslelis($url, $byla, $subdir);
    }

    public function toArray() {
        $array = array();
        foreach ($this->paveiksleliai as $pav) {
            $array[] = array('byla' => $pav->byla, 'subdir' => $pav->subdir, 'url' => $pav->url);
        }
        return $array;
    }

    /**
     * Gražina pirmo paveikslėlio vardą.
     */
    public function getFirst() {
        if (count($this->paveiksleliai) > 0) {
            return strtolower($this->paveiksleliai[0]->byla);
        }
        return '';
    }

    /**
     * Gražina paskutinio paveikslėlio vardą.
     */
    public function getLast() {
        if (count($this->paveiksleliai) > 0) {
            return strtolower($this->paveiksleliai[count($this->paveiksleliai) - 1]->byla);
        }
        return '';
    }

}

class KelionesData {

    public $datos_id;
    public $viesbucio_id;
    public $kambario_id;
    public $data = '';
    public $trukme = '';
    public $kaina = '';
    public $valiuta = '';
    public $viesbutis = '';
    public $kambarys = '';
    public $laisvu_vietu = '';

    public function __construct($data, $datosId, $trukme, $viesbutis, $viesbucioId, $kambarys, $kambarioId, $kaina, $valiuta, $laisvu_vietu) {
        $this->data = (string) $data;
        $this->datos_id = (int) $datosId;
        $this->trukme = (string) $trukme;
        $this->viesbutis = (string) $viesbutis;
        $this->viesbucio_id = (int) $viesbucioId;
        $this->kaina = (string) $viesbutis;
        $this->kambarys = (string) $kambarys;
        $this->kambario_id = (int) $kambarioId;

        if (strpos($kaina, '.00') > 0) {
            $kaina = str_replace('.00', '', $kaina);
            $kaina = str_replace(',', '', $kaina);
        }

        $this->kaina = (string) $kaina;
        $this->valiuta = (string) $valiuta;
        $this->laisvu_vietu = (string) $laisvu_vietu;
    }

    public function getValiuta() {
        if ($this->valiuta == 'LTL') {
            return 'Lt';
        } else {
            return $this->valiuta;
        }
    }

}

class KelionesDatos implements Iterator {

    public $datos = array();

    public function add($data, $datosId, $trukme, $viesbutis, $viesbucioId, $kambarys, $kambarioId, $kaina, $valiuta, $laisvu_vietu) {
        $this->datos[] = new KelionesData($data, $datosId, $trukme, $viesbutis, $viesbucioId, $kambarys, $kambarioId, $kaina, $valiuta, $laisvu_vietu);
    }

    public function Serialize() {
        if ($this->count() > 0) {
            $output = array();

            $uid = 'd198fa4e-8df3-4854-a717-893281154ff8';

            foreach ($this->datos as $i => $data) {
                $output[] = '"' . (int) $i . '": { "data": ' . json_encode($data->data) . ', "trukme": ' . json_encode($data->trukme) . ', "viesbutis": ' . json_encode($data->viesbutis) . ', "vietu": ' . json_encode($data->laisvu_vietu) . ', "kaina": ' . json_encode($data->kaina . ' ' . $data->getValiuta()) . ', "id": ' . json_encode($data->datos_id . ',' . $data->viesbucio_id . ',' . $data->kambario_id) . ' }';
            }

            return '"' . $uid . '":  { ' . implode(', ', $output) . ' }';
        } else {
            return '';
        }
    }

    /**
     * Gražina kelionės trumę (pirmą pasitaikiusią).
     */
    public function getTrukme() {
        if (count($this->datos) > 0) {
            return strtolower($this->datos[0]->trukme);
        }
        return '';
    }

    /**
     * Gražina mažiausią kainą.
     */
    public function getKainaNuo() {
        $min = '';
        $val = '';
        if (count($this->datos) > 0) {
            foreach ($this->datos as $data) {
                if (($min == '') || ($data->kaina < $min)) {
                    $min = $data->kaina;
                    $val = $data->getValiuta();
                }
            }
        }

        if ($min != '') {
            return 'nuo ' . $min . ' ' . $val;
        } else {
            return '';
        }
    }

    /**
     * Palygina išvykimo datas. TRUE jei viskas sutampa.
     */
    public function compareTo(KelionesDatos $datos2) {
        if ($this->count() !== $datos2->count()) {
            return false;
        }

        foreach ($this->datos as $data) {
            $rasta = false;

            $i = 0;
            while (!$rasta && $i < $datos2->count()) {
                $data2 = $datos2->datos[$i];
                if ($data->datos_id == $data2->datos_id && $data->viesbucio_id == $data2->viesbucio_id && $data->kambario_id == $data2->kambario_id) { //randam irasa
                    if ($data->data == $data2->data && $data->kaina == $data2->kaina) { //palyginam ar irasas nepasikeites
                        $rasta = true;
                    } else {
                        return false;
                    }
                }
                $i++;
            }

            if ($rasta === false) {
                return false;
            }
        }

        return true;
    }

    public function current() {
        if ($this->valid()) {
            return $this->datos[$this->i];
        } else {
            return null;
        }
    }

    public function key() {
        return $this->i;
    }

    public function next() {
        $this->i++;
    }

    public function rewind() {
        $this->i = 0;
    }

    public function valid() {
        return ($this->i >= 0) && ($this->i < count($this->datos));
    }

    public function count() {
        return count($this->datos);
    }

}

?>
