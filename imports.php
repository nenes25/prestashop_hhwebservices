<?php

//AutoLoad
include_once 'vendor/autoload.php';

//Fichier de configuration
include_once 'config.php';

//Initialisation du webservice
$ws = new HhPrestashopWebservice($wsUrl,$wsAuthKey, false);

//Instanciation de la classe de gestion des clients
$customerWs = $ws->getCustomerInstance();

//Traitement du fichier d'import : on part du principe que c'est basé sur des csv avec séparateur ";" et valeurs encadrés avec des "
// et que la première ligne contient les titres exacts des attributs prestahop
// L'objectif de cette classe est de fournir un tableau avec les données à insérer sous la forme "clé" => "valeur" pour chaque entité
// Elle n'est pas spécialement détaillée car ce n'est pas le but.
$csv = new HhCsvData();

//Données des clients
$customerDatas = $csv->getDatas(dirname(__FILE__) . '/files/imports/', 'customers');

//Traitement des clients
foreach ($customerDatas as $customerData) {
    //Suppression des clients
    if ($customerData['toDelete'] == 1) {
        try {
            echo 'Suppression du client '.$customerData['email'].'<br />';
            $customerWs->deleteCustomer($customerData['email']);
        } catch (PrestaShopWebserviceException $e) {
            echo $e->getMessage();
        }
    //Gestion des ajouts et modifications
    } else {
        //On vérifie si le client existe via son email
        if ($customerId = $customerWs->getObjectId($customerData['email'], 'email')) {
            echo 'Maj du client ' . $customerData['email'] . ' - id prestashop ' . $customerId . '<br />';
            try {
                $customerWs->updateCustomer($customerId, $customerData);
            } catch (PrestaShopWebserviceException $e) {
                echo $e->getMessage();
            }
        } else {
            echo 'Creation du client' . $customerData['email'] . '<br />';
            try {
                $customerWs->createCustomer($customerData);
            } catch (PrestaShopWebserviceException $e) {
                echo $e->getMessage();
            }
        }
    }
}
