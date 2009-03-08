<?php
/**
 * Clase para gestionar los Menús del CORE
 *
 * @category 
 * @package 
 */
class Admin_Models_Menu extends Zend_Db_Table_Abstract
{
    protected $_name;

    public function getMenu($table, $id_aplicacion)
    {        
        $sql =  sprintf(
            '  SELECT * '
            .' FROM '.$table
            .' WHERE aplicacion_id = %u '
            .' AND menu_estado = 1' , $id_aplicacion
        );        
        return $this->_db->fetchAll($sql);
    }
    public function getMenuItemsFromModule($table, $module_name)
    {
        $sql =  sprintf(
            '  SELECT * '
            .' FROM  '.$table
            .' WHERE admin_menu_item_app_module = "%s" '
            .' AND admin_menu_item_estado = 1'
            .' ORDER BY admin_menu_item_id ASC ',
            $module_name
        );        
        return $this->_db->fetchAll($sql);
    }
}