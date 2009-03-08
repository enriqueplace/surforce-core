<?php
/**
 * Clase para gestionar los Menús del CORE
 *
 * @category 
 * @package 
 */
class Models_Menu extends Zend_Db_Table_Abstract
{
    public static function getMenu($table, $id_aplicacion)
    {
        $gestor = new Models_Menu();

        $sql =  sprintf(
            '  SELECT menu_texto, menu_url '
            .' FROM '.$table
            .' WHERE aplicacion_id = %u '
            .' AND menu_estado = 1' , $id_aplicacion
        );
        
        return $gestor->_db->fetchAll($sql);
    }
    public static function getMenuItemsFromModule($table, $module_name)
    {
        $gestor = new Models_Menu();

        $sql =  sprintf(
            '  SELECT menuitem_id, menuitem_texto, menuitem_url '
            .' FROM '.$table
            .' WHERE menuitem_app_module = "%s" '
            .' AND menuitem_estado = 1'
            .' ORDER BY menuitem_id ASC ',
            $module_name
        );        
        return $gestor->_db->fetchAll($sql);
    }
}