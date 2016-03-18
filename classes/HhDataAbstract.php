<?php
abstract class HhDataAbstract
{
  /**
   * @var array données de la classe
   */
   protected $_datas = array();

   /**
    * Instanciation de la classe
    */
   public function __construct()
   {

   }

   /**
    * Fonction de récupération des données
    */
   abstract public function getDatas($fileDir, $fileName, $useCache = true);
}
