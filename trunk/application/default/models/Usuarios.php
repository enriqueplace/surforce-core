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
    private $_nombreUsuario;
    /**
     *  Nombre del campo que esta
     * almacenado en la base el nombre
     * de usuario
     *
     * @var string
     */
    private $_campoNombre;
    private $_campoEstado;

    public function __construct($nombre_usuario = '', $table = 'usuarios', $campo = null, $estado = null )
    {
        parent::__construct();

        $this->_nombreUsuario  = $nombre_usuario;
        $this->_name           = $table;
        $this->_campoNombre    = $campo;
        $this->_campoEstado    = $estado;
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
            $this->_campoNombre." = '".$this->_nombreUsuario."' "
            ." AND usuario_estado = 1 AND usuario_baja <> 1 "
        );

        return !is_null($result);
    }
    /**
     * Cuando aun no se sabe el login del usuario,
     * registraremos los datos de acceso antes de
     * llegar al ingresar los datos.
     *
     * @return void;
     */
    public static function registrarAcceso($usuario_nombre = null, $usuario_module = null, $usuario_controller = null)
    {
        $model = new Models_Usuarios();

        $data = array(
            'remote_address'      => Zsurforce_Net_Ip::getIp(),
            'remote_address_real' => Zsurforce_Net_Ip::getIpReal(),
            'url_referer'         => $_SERVER['HTTP_REFERER'],
            'domain'              => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'date'                =>  date("Y-m-d H:i:s")
        );

        if(!is_null($usuario_nombre)){
            $data['usuario'] = $usuario_nombre;
        }
        if(!is_null($usuario_module)){
            $data['usuario_module'] = $usuario_module;
        }
        if(!is_null($usuario_controller)){
            $data['usuario_controller'] = $usuario_controller;
        }
        $model->_db->insert('access_site', $data);
    }
    public function setPassword($pass, $usuario_id = null)
    {
        $data = array(
            'usuario_clave'      => md5($pass)
        );
        if(!is_null($usuario_id)){
            $this->update($data, 'usuario_id = '.$usuario_id);
        }else{
            throw new Exception('funcionalidad no implementada');
        }
        
    }
}