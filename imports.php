<?php

//AutoLoad
include_once 'vendor/autoload.php';

//Fichier de configuration
include_once 'config.php';

//Initialisation du webservice
$ws = new HhPrestashopWebservice($wsUrl,$wsAuthKey, false);

//Traitement du fichier d'import : on part du principe que c'est basé sur des csv avec séparateur ";" et valeurs encadrés avec des "
// et que la première ligne contient les titres exacts des attributs prestahop
// L'objectif de cette classe est de fournir un tableau avec les données à insérer sous la forme "clé" => "valeur" pour chaque entité
// Elle n'est pas spécialement détaillée car ce n'est pas le but.
$csv = new HhCsvData();

//Traitement des clients
/*$customerDatas = $csv->getDatas(dirname(__FILE__) . '/files/imports/', 'customers');
$customerWs = $ws->getInstanceType('customer');
$customerWs->setDatas($customerDatas);
$customerWs->processDatas();*/


//Traitement des adresses
$addressesDatas = $csv->getDatas(dirname(__FILE__) . '/files/imports/', 'addresses');
$addresseWs = $ws->getInstanceType('addresses');
$addresseWs->setDatas($addressesDatas);
$addresseWs->processDatas();