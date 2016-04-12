<?php
/**
 * Gestion webservice des adresses des clients
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class HhCustomerAddressWs extends HhPrestashopWebservice
{
    protected $_resource = 'addresses';
    protected $resource = 'addresses';

    protected $_id = 'alias';

    protected $_id_countries = array();
    protected $_customers = array();

    protected $_dataMapping = array(
        'customer_email' => array(
            'function' => 'getCustomerId'
        ),
        'country' => array(
            'function' => 'getCountryId'
        ),
        'address_erp_id' => array(
            'function' => 'getAddressAlias'
        )
    );

    /**
     * Traitement des données
     * On applique un mapping avant
     */
    public function processDatas()
    {
        $this->_datas = $this->mapDatas($this->_datas, $this->_dataMapping);
        $this->setResource($this->resource);
        parent::processDatas();
    }


    /**
     * Récupération de l'identifiant du client
     * @param array $row
     * @return string
     */
    public function getCustomerId($row)
    {
        if ($this->_customers[$row['customer_email']]) {
            $id_customer = $this->_customers[$row['customer_email']];
        } else {
            $this->setResource('customers');
            $id_customer = $this->getObjectId($row['customer_email'], 'email');
        }
        $row['id_customer'] = $id_customer;

        return $row;
    }

    /**
     * Récupération de l'identifiant du pays
     * @param type $row
     */
    public function getCountryId($row) {

        if ($this->_id_countries[$row['country']]) {
            $id_country = $this->_id_countries[$row['country']];
        } else {
            $this->setResource('countries');
            $id_country = $this->getObjectId($row['country'], 'name');
        }
        $row['id_country'] = $id_country;

        return $row;
    }

    /**
     *
     */
    public function getAddressAlias($row){

        $row['alias'] = $row['address_erp_id'];

        return $row;
    }

}
