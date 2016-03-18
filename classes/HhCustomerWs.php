<?php

/**
 * Gestion webservice des clients
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class HhCustomerWs extends HhPrestashopWebservice {

    protected $_resource = 'customers';

    /**
     * Création d'un client via le webservice
     * @param array $datas : données du client
     */
    public function createCustomer($datas) {

        /**
         * Données fixes pour l'import des clients
         */
        $additonnalDatas = array(
            'note' => 'Client add with webservice new version',
            'active' => 1,
            'id_default_group' => 3
        );

        $this->createObject($datas, $additonnalDatas);
    }

    /**
     * Mise à jour d'un client existant via le webservice
     * @param int $id identifiant du client
     * @param array $datas données du client
     */
    public function updateCustomer($id, $datas) {

        $options = array(
            'resource' => $this->_resource,
            'id' => $id
        );

        $responseXml = $this->get($options);
        $customerXml = $responseXml->children()->children();

        $hasDataChange = false;
        foreach ($datas as $key => $value) {
            if ($customerXml->{$key} && $customerXml->{$key} != $value) {
                $hasDataChange = true;
                $customerXml->{$key} = $value;
            }
        }

        //On sauvegarde uniquement si il y'a eut des changements
        if ( $hasDataChange ){
            $options = array(
                'resource' => $this->_resource,
                'id' => $id,
                'putXml' => $responseXml->asXML(),
            );
            $this->edit($options);
        }
    }


    /**
     * Suppression d'un client
     * @param string $email email du client a supprimer
     * @throws PrestaShopWebserviceException
     */
    public function deleteCustomer($email){

        if ( $idCustomer = $this->getObjectId($email,'email')) {
            $this->deleteObject($this->_resource,$idCustomer);
        }
        else {
            throw new PrestaShopWebserviceException('Impossible de supprimer le client '.$email.' n\'existe pas');
        }
    }
}
