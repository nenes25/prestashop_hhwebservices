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
     * Mise à jour d'un client à partir de son adresse email
     * @param type $email
     * @param type $datas
     */
    public function updateCustomer($email,$datas) {

        if ($idCustomer = $this->getObjectId($email, 'email')) {
            $this->updateObject($customerId, $datas);
        } 
        else {
            throw new PrestaShopWebserviceException('Impossible de modifier le client ' . $email . ' n\'existe pas');
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
