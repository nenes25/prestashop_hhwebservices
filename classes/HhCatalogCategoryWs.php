<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HhCatalogCategoryWs
 *
 * @author advisa
 */
class HhCatalogCategoryWs extends HhPrestashopWebservice
{

    protected $_resource = 'categories';
    const WS_RESOURCE = 'categories';

    /** Champ unique de recherche */
    protected $_id = 'code';

     protected $_dataMapping = array(
        'parent_category_code' => array(
            'function' => 'getParentCategoryId'
        ),
        'name' => array(
            'language' => true,
        ),
        'meta_title' => array(
            'language' => true,
            'lang_mode' => 'copy', //@Todo : Mode copy => Meme valeur pour toutes les langue
        ),
        'meta_description' => array(
            'language' => true,
            'lang_mod' => 'columns', //@ToDo : Mode columns => 1 colonne par langue
        ),
        'meta_keywords' => array(
            'language' => true,
        ),
        'link_rewrite' => array(
            'language' => true,
        ),
    );

    public function processDatas()
    {
        $this->_datas = $this->mapDatas($this->_datas, $this->_dataMapping);
        echo '<pre>';
        var_dump($this->_datas);
        echo '</pre>';
        $this->setResource(self::WS_RESOURCE);
        parent::processDatas();
    }

    /**
     * Récupération de l'identifiant de la catégorie parente
     * @param array $row
     * @return int
     */
    public function getParentCategoryId($row){

        $row['id_parent'] = 2;
        return $row;
    }


}
