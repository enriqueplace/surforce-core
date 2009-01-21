<?php
/**
 * Clase para gestionar toda la información de Reportes
 *
 * @category 
 * @package 
 */
class Models_Menu extends Zend_Db_Table_Abstract
{
    public static function getMenu($idApplication)
    {
        $gestor = new Models_Menu();
        $sql =  sprintf('SELECT texto, url FROM menu WHERE idAplicacion = %u and estado = 1' , $idApplication);
        return $gestor->_db->fetchAll($sql);
    }
}