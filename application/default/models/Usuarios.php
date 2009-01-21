<?php
/**
 * Clase para gestionar toda la información de Usuarios
 * 
 * @category 
 * @package 
 */
class Models_Usuarios extends Zend_Db_Table_Abstract
{
    /**
     * Nombre de la tabla
     */
	protected $_name = 'usuarios';

	/**
	 * Obtener todos los usuarios 
	 * 
     * @param  string $where			condición de búsqueda
     * @param  string $limit			cantidad de registros
     * @param  string $order			campo de orden
     * @return object
    */	
	public static function getAll($where = null, $limit = 0, $order = null  )
	{
		$usuarios = new Models_Usuarios();
		return $usuarios->fetchAll($where, $order, $limit);
	}
	/**
	 * Obtener un usuario específico 
	 * 
     * @param  integer $id				id del usuario
     * @return object
    */
	public static function getUsuario( $id )
	{
		$usuario = new Models_Usuarios();
		return $usuario->fetchRow("idUsuario = '$id'");
	}
	/**
	 * Definir si es un usuario válido a partir de su nombre 
	 * (estado = 1) 
	 * 
     * @param  string $nombre		nombre del usuario
     * @return boolean
    */	
	public static function isValid( $usuario )
	{
		$Usuarios = new Models_Usuarios();
		$result = $Usuarios->fetchRow("usuario = '".$usuario."' AND estado = 1");
		
		return $result <> NULL;
	}
}