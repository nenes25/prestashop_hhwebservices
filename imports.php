<?php

include_once 'vendor/autoload.php';

$wsUrl = 'http://yourshop.url';
$wsAuthKey = 'yourkey';

//Initialisation du webservice
$ws = new HhPrestashopWebservice($wsUrl,$wsAuthKey, false);

//Instanciation de la classe de gestion des clients
$customerWs = $ws->getCustomerInstance();

//Traitement du fichier d'import : on part du principe que c'est basé sur des csv avec séparateur ";" et valeurs encadrés avec des "
// et que la première ligne contient les titres exacts des attributs prestahop
//@ToDO : Mettre dans la class Ws
$csv = new HhCsvData();

//Traitement des clients : Ajout et suppression
$customerDatas = $csv->getDatas(dirname(__FILE__) . '/files/imports/', 'customers');

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