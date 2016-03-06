<?php

/**
 * Classe de récupération des données Csv
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class HhCsvData {

    protected $_datas = array();

    /**
     * Instanciation du modèle
     */
    public function __construct() {
        
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
        while ($row = fgetcsv($csvFile, 1000, ";", '"')) {
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
