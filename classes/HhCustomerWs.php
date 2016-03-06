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

        $schema = $this->getEmptyObject();
        $customerAttributes = $schema->children()->children();

        //Parcours des attributs du client, si une data existe on l'associe
        foreach ($customerAttributes as $attribute => $values) {

            if (array_key_exists($attribute, $datas))
                $schema->children()->children()->{$attribute} = $datas[$attribute];

            //Si le champ est nécessaire et qu'il n'est pas associé cela ne fonctionnera pas, on envoie une exception
            if ($schema->children()->children()->{$attribute}->attributes()->required && !array_key_exists($attribute, $datas)) {
                throw new PrestaShopWebserviceException('Erreur attribut obligatoire ' . $attribute . ' manquant !');
            }
        }

        //Si on veut ajouter des données commune ou statiques on peut les ajouter ici
        $customerAttributes->note = 'Client add with webservice';
        $customerAttributes->active = 1;
        $customerAttributes->id_default_group = 3;

        //Enregistrement du nouveau client
        $options = array(
            'resource' => $this->_resource,
            'postXml' => $schema->asXML(),
        );

        $xml = $this->add($options);
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

    
    public function deleteCustomer($email){
        
        if ( $idCustomer = $this->getObjectId($email,'email')) {
            $this->deleteObject($this->_resource,$idCustomer);
        }
        else {
            throw new PrestaShopWebserviceException('Impossible de supprimer le client '.$email.' n\'existe pas');
        }
    }
}
