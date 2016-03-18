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
     * Création d'un objet
     * @param array $datas données de création de l'objet
     * @param array $additionnal_datas => parametres de création statique
     */
    public function createObject( array $datas , array $additionnal_datas) {

        $schema = $this->getEmptyObject();
        $objectAttributes = $schema->children()->children();

        //Parcours des attributs du client, si une data existe on l'associe
        foreach ($objectAttributes as $attribute => $values) {

            if (array_key_exists($attribute, $datas))
                $schema->children()->children()->{$attribute} = $datas[$attribute];

            //Si le champ est nécessaire et qu'il n'est pas associé cela ne fonctionnera pas, on envoie une exception
            if ($schema->children()->children()->{$attribute}->attributes()->required && !array_key_exists($attribute, $datas)) {
                throw new PrestaShopWebserviceException('Erreur attribut obligatoire ' . $attribute . ' manquant !');
            }
        }

        //Ajout des données statiques ( Pas de vérification pour l'instant, attention à ce qui est mis ) !
        if (sizeof($additionnal_datas)) {
            foreach ($additionnal_datas as $key => $value) {
                $objectAttributes->{$key} = $value;
            }
        }

        //Enregistrement du nouveau client
        $options = array(
            'resource' => $this->_resource,
            'postXml' => $schema->asXML(),
        );

        $xml = $this->add($options);

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
     * Mapping des datas
     * @param array $datas : Données à insérer sous forme de tableau : clé => valeur
     * @param array $params : Paramètres de mapping
     */
    public function mapDatas(array $datas, array $params)
    {

        $i = 0;
        $keysToMaps = array();
        foreach ($datas as $data) {

            if ($i == 0) {
                //On determine le clés qui doivent être modifiées
                foreach ($data as $key => $value) {
                    if (array_key_exists($key, $params)) {
                        $keysToMaps[] = $key;
                    }
                }
            }

            foreach ( $keysToMaps as $map ) {
                if (array_key_exists($map, $data)){

                    if ( isset($params[$map]['function']) && method_exists($this, $params[$map]['function'])) {

                        $data = call_user_func_array(array($this,$params[$map]['function']), array($data));
                    }
                    else {
                        echo 'Methode '.$params[$map]['function'].' existe pas';
                    }
                }
            }

            $i++;
        }
    }

    /**
     * Récupération de la classe de gestion des clients
     * @return \HhCustomerWs
     */
    function getCustomerInstance() {
        return new HhCustomerWs($this->url, $this->key, $this->debug);
    }

}
