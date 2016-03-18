<?php

/**
 * Classe de récupération des données Csv
 *
 * Renvoie un tableau contenant les valeurs des lignes csv
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class HhCsvData extends HhDataAbstract {

    /** @var string delimiteur du fichier csv */
    protected $_delimiter = ';';

    /** @var string enclosure du fichier csv */
    protected $_enclosure ='"';

    /**
     * Définition du délimiteur csv
     * @param type $delimiter
     */
    public function setDelimiter($delimiter) {
        $this->_delimiter = $delimiter;
    }

    /**
     * Récupération du délimiteur
     * @return type
     */
    public function getDelimiter(){
        return $this->_delimiter;
    }

    /**
     * Définition du séparateurs des datas
     * @param string $enclosure
     */
    public function setEnclosure($enclosure) {
        $this->_enclosure = $enclosure;
    }

    /**
     * Récupération enclosure
     * @return type
     */
    public function getEnclosure(){
        return $this->_enclosure;
    }

    /**
     * Récupération des données du fichier csv
     * @param string $fileDir
     * @param string $fileName
     * @param boolean $useCache
     */
    public function getDatas($fileDir, $fileName, $useCache = true) {

        $cache_key = str_replace('/', '_', $fileDir) . $fileName;

        if (array_key_exists($cache_key, $this->_datas) && $useCache) {
            return $this->_datas[$cache_key];
        }

        //Ouverture du fichier
        $csvFile = fopen($fileDir . $fileName . '.csv', 'r');

        $keys = array();
        $csvDatas = array();

        $i = 0;
        while ($row = fgetcsv($csvFile, 1000, $this->_delimiter , $this->_enclosure)) {
            //Gestion des entêtes
            if ($i == 0) {
                foreach ($row as $datas) {
                    $keys[] = $datas;
                }
            } else {
                $j = 0;
                $rowDatas = array();
                foreach ($row as $datas) {
                    $rowDatas[$keys[$j]] = $datas;
                    $j++;
                }
                $csvDatas[] = $rowDatas;
            }
            $i++;
        }

        $this->_datas[$cache_key] = $csvDatas;

        return $csvDatas;
    }
}
