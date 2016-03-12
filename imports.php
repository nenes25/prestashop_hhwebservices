<?php

//Inclusion de l'autoload composer
include_once 'vendor/autoload.php';

//Identifiants d'accès au webservice
$wsUrl = 'http://web.h-hennes.fr/prestashop/prestashop_1-6-0-14/';
$wsAuthKey = '2DGQR2Z717YUWLUCD3P27S9A7MCQ92K1';

//Traitement du fichier d'import : on part du principe que c'est basé sur des csv avec séparateur ";" et valeurs encadrés avec des "
// et que la première ligne contient les titres exacts des attributs prestahop
$csv = new HhWsCsvData();
$customerDatas = $csv->getDatas(dirname(__FILE__) . '/files/imports/', 'customers');

echo '<pre>';
print_r($customerDatas);
echo '</pre>';
die('debug');

//Initialisation du webservice
$ws = new HhPrestashopWebservice($wsUrl,$wsAuthKey, false);

//Instanciation de la classe de gestion des clients
$customerWs = $ws->getCustomerInstance();

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