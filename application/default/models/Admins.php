<?php
/**
 * Clase para gestionar toda la información de los
 * Adminis
 * 
 * @category 
 * @package 
 */
class Models_Admins extends Zend_Db_Table_Abstract
{
    /**
     * Nombre de la tabla
     */
	protected $_name = 'administradores';

	/**
	 * Obtener todos los admins
	 * 
     * @param  string $where			condición de búsqueda
     * @param  string $limit			cantidad de registros
     * @param  string $order			campo de orden
     * @return object
    */	
	public static function getAll($where = null, $limit = 0, $order = null  )
	{
		$admins = new Models_Admins();
		return $admins->fetchAll($where, $order, $limit);
	}
	/**
	 * Obtener un usuario específico 
	 * 
     * @param  integer $id				id del usuario
     * @return object
    */
	public static function getAdmin( $id )
	{
		$usuario = new Models_Admins();
		return $usuario->fetchRow("id_admin = '$id'");
	}
	/**
	 * Definir si es un usuario válido a partir de su nombre 
	 * (estado = 1) 
	 * 
     * @param  string $nombre		nombre del usuario
     * @return boolean
    */	
	public static function isValid( $admin, $table = null, $campo = null, $estado = null  )
	{
		$admins = new Models_Admins();

        if($table){
            $admins->_name = $table;
        }
        if(is_null($campo)){
            $campo = 'admin';
        }
        if(is_null($estado)){
            $estado = 'estado';
        }
        
		$result = $admins->fetchRow($campo." = '".$admin."' AND ".$estado." = 1 AND baja <> 1 ");

		return !is_null($result);
	}
}