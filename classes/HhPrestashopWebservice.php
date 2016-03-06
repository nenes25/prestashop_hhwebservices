<?php

include_once dirname(__FILE__) . '/../vendor/prestashop/prestashop-webservice-lib/PSWebServiceLibrary.php';

/**
 * Gestion des webservices Prestashop
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class HhPrestashopWebservice extends PrestaShopWebservice {

    
    /** @var HhCustomerWs instance de gestion des clients */
    protected $_customerInstance;

    /**
     * Récupération de l'identifiant d'un objet via ses paramètres
     * @return int $objectId || bool 
     */
    public function getObjectId($value, $field = 'id') {

        $options = array(
            'resource' => $this->_resource,
            'filter' => array($field => $value)
        );

        $xml = $this->get($options);
        $objectId = (string) $xml->children()->children()->attributes();

        if ($objectId != '')
            return $objectId;
        else
            return false;
    }
    
    /**
     * Récupération d'un objet vide
     * @return SimpleXml
     */
    public function getEmptyObject() {
        
        $options = array(
            'url' => $this->url.'/api/'.$this->_resource.'/?schema=synopsis'
            );
        
        $schema = $this->get($options);
        
        return $schema;
    }

    /**
     * Suppression d'un objet
     * @param type $resource
     * @param type $id
     */
    public function deleteObject($resource,$id) {
        $options = array(
            'resource' => $resource,
            'id' => $id,
        );
        $this->delete($options);
    }
    
    /**
     * Récupération de la classe de gestion des clients
     * @return \HhCustomerWs
     */
    function getCustomerInstance() {
        return new HhCustomerWs($this->url, $this->key, $this->debug);
    }

}
