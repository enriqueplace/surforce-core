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
	protected $_name;

    /**
     *  Nombre del usuario
     * 
     * @var string
     */
    private $_nombre;
    /**
     *  Nombre del campo que esta
     * almacenado en la base el nombre
     * de usuario
     * 
     * @var string
     */
    private $_campoNombre;
    private $_campoEstado;

    public function __construct($nombre, $table = 'usuarios', $campo = null, $estado = null )
    {
        parent::__construct();
        
        $this->_nombre              = $nombre;
        $this->_name                = $table;
        $this->_campoNombre   = $campo;
        $this->_campoEstado     = $estado;
    }
	/**
	 * Obtener todos los usuarios 
	 * 
     * @param  string $where			condición de búsqueda
     * @param  string $limit			cantidad de registros
     * @param  string $order			campo de orden
     * @return object
    */	
	public function getAll($where = null, $limit = 0, $order = null  )
	{		
		return $this->fetchAll($where, $order, $limit);
	}
	/**
	 * Obtener un usuario específico 
	 * 
     * @param  integer $id				id del usuario
     * @return object
    */
	public function getUsuario( $id )
	{
		return $this->fetchRow("usuario_id = '$id'");
	}
	/**
	 * Definir si es un usuario válido a partir de su nombre 
	 * (estado = 1) 
	 * 
     * @param  string $nombre		nombre del usuario
     * @return boolean
    */	
	public function isValid()
	{   
		$result = $this->fetchRow(
            $this->_campoNombre." = '".$this->_nombre."' "
            ." AND usuario_estado = 1 AND usuario_baja <> 1 "
        );

		return !is_null($result);
	}
}