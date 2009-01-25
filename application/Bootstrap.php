<?php
/**
 * Bootstrap
 *
 * @author  SURFORCE
 * @version 0.1
 */
class Bootstrap
{
    private $_config;
    private $_registry;
    private $_controller;
    
    public function __construct()
    {
       
    }
    public function setPath()
    {
        set_include_path(
            '.'
            . PATH_SEPARATOR . '../library'
            . PATH_SEPARATOR . '../application/default/models/'
            . PATH_SEPARATOR . get_include_path()
        );         
    }
    public function setEnvironment()
    {
        $this->setPath();

        include "Zend/Loader.php";
        Zend_Loader::registerAutoload();

        $this->setTimeZone();

        $this->setErrorReporting();

    }
    /**
     * Configuración del sistema que será leída del archivo config.ini
     * - cada sistema deberá tener su propio archivo de configuración
     * - cada entorno deberá tener sus propios datos de configuración
     *
     *  ej. si es desarrollo, deberá apuntar a la basa de datos correspondiente,
     * cambia la conexión si es un sistema en producción
     */
    public function setConfig()
    {
        $this->_config = new Zend_Config_Ini('../application/config.ini');
    }
    /**
     * Se dejan variables disponibles en el sistema
     * que generalmente se van a requieren en su
     * uso normal
     */
    public function setRegistry()
    {
        $this->_registry = Zend_Registry::getInstance();

        $this->_registry->set('config', $this->_config);

        $this->_registry->set('base_path_html', realpath('.'));
        $this->_registry->set('base_path_app',  realpath('..'));
        
        $this->_registry->set('debug', $this->_config->general->debug);
        $this->_registry->set('devel', $this->_config->general->devel);
    }
    public function setTimeZone()
    {
        if($this->_config->general->timezone){
            date_default_timezone_set($this->_config->general->timezone);
        }
    }
   /**
    * DEBUG sirve para agregar rutinas en caso de necesitar
    * "solo debug" y que no es necesario que están ejecutando
    * constantemente en producción (información extra en logs, etc).
    */
    public function setErrorReporting()
    {
        if($this->_config->general->debug){

            ini_set('display_startup_errors', 1);
            ini_set('display_errors', 1);

            error_reporting(E_ALL|E_STRICT);
        }
    }
   /**
    * SESSION DEFAULT en caso que el sistema requiera siempre una sesión
    * (como en el caso de las aplicaciones de ADMIN's)
    */
    public function setSessionDefault()
    {
        if($this->_config->general->session){

            $session_name = $this->_config->general->appname ? $this->_config->general->appname : 'app';

            $session = new Zend_Session_Namespace($session_name);
            $this->_registry->set('session', $session);
        }
    }
    /*
     * Configuración Base de Datos
     *
     * - Se diferencias las conexiones según los ambientes de trabajo,
     *  database_devel para desarrollo y database_prod para producción,
     *  si devel = on va a la primera, de lo contrario es producción
     *  (recordar que cada entorno debe disponer de su .ini independiente).
     *
     */    
    public function setDatabase()
    {
        if($this->_config->general->debug){
            $db_adapter = $this->_config->database_devel->db->adapter;
            $db_config = $this->_config->database_devel->db->config->toArray();
        }else{
            $db_adapter = $this->_config->database_prod->db->adapter;
            $db_config = $this->_config->database_prod->db->config->toArray();
        }       

        try{

            $db = Zend_Db::factory($db_adapter, $db_config);

            Zend_Db_Table::setDefaultAdapter($db);
            Zend_Registry::set('dbAdapter', $db);

            switch(strtolower($db_adapter)){
                case 'mysql':
                case 'mysqli':
                case 'pdo_mysql':
                    $db->query('SET NAMES \''.strtoupper($this->_config->database->charset).'\'');
                    $db->query('SET CHARACTER SET '.strtoupper($this->_config->database->charset));
                    break;
            }

       }catch(Zend_Db_Statement_Exception $e){

            echo '<strong>Se ha producido un error al intentar recuperar los datos '
                .'['.$e->getMessage().']</strong> <br><br>';

        }catch(Zend_Db_Adapter_Exception $e){

            echo '<strong>Se ha producido un error al conectar a la base de datos'
                .'['.$e->getMessage().'] </strong><br><br>';
            
        }catch(Zend_Exception $e){

            echo '<strong>Se ha producido un error inesperado '
                .'['.$e->getMessage().'] </strong><br><br> ';
                
        }
    }
    public function setController()
    {
        $this->_controller = Zend_Controller_Front::getInstance();
        /*
         * DEVEL / PROD comportamiento ante errores / fallas
         *
         * - Solo desplegar excepciones en modo desarrollo
         * - Solo en producción, ante errores de url's, redireccionar a un
         * controller por defecto.
         */
        if($this->_config->general->devel){
            $this->_controller->throwExceptions(true);
        }else{
            $this->_controller->setParam('useDefaultControllerAlways', true);
        }
    }
    public function setModules()
    {
        /*
         * MODULE DEFAULT define cual es el módulo por defecto
         * que debe levantar cuando inicie por primera vez la aplicación
         */
        if($this->_config->modules->default){
            $mod_default = $this->_config->modules->default;
        }else{
            $mod_default = '../application/default/controllers';
        }
        $this->_controller->setControllerDirectory(
            $mod_default
        );
        
        /*
         * LOAD MODULES lee la lista de módulos existentes en el
         * config.ini y registra sus rutas para poder permitir
         * responder a las peticiones desde el exterior
         */
        foreach($this->_config->modules->controllers as  $name => $path){
            $this->_controller->addControllerDirectory($path, $name);
        }
    }
    public function setView()
    {
        /* View Default */
        Zend_Layout::startMvc(
            array(
                'layoutPath' => $this->_config->layout->default_path,
                'layout' => $this->_config->layout->default_name
            )
        );
    }
    public function run()
    {
        try{

            $this->setEnvironment();            

            $this->setConfig();
            $this->setRegistry();

            $this->setSessionDefault();
            $this->setDatabase();

            $this->setController();

            $this->setModules();
            $this->setView();

            $this->_controller->dispatch();

        }catch(Zend_Exception $e){

            /* En producción no se desplega información de error
             * del sistema (considerar implementar envío de email
             * o log de fallos) */
            if($this->_config->general->devel || $this->_config->general->debug){

                echo highlight_string($e);

            }
        }
    }
}