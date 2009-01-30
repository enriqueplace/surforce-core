<?php
/**
 * Clase para gestionar los Menús del CORE
 *
 * @category 
 * @package 
 */
class Models_Menu extends Zend_Db_Table_Abstract
{
    public static function getMenu($id_aplicacion)
    {
        $gestor = new Models_Menu();

        $sql =  sprintf(
            '  SELECT texto, url '
            .' FROM menu '
            .' WHERE aplicacion_id = %u '
            .' AND estado = 1' , $id_aplicacion
        );        
        return $gestor->_db->fetchAll($sql);
    }
    public static function getMenuItemsFromModule($module_name)
    {
        $gestor = new Models_Menu();

        $sql =  sprintf(
            '  SELECT item_id, texto, url '
            .' FROM menu_items '
            .' WHERE app_module = "%s" '
            .' AND estado = 1' 
            .' ORDER BY item_id ASC ',
            $module_name
        );        
        return $gestor->_db->fetchAll($sql);
    }
}