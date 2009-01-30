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

    }
    /**
     * Configuraci髇 del sistema que ser帷 le韉a del archivo config.ini
     * - cada sistema deber帷 tener su propio archivo de configuraci髇
     * - cada entorno deber帷 tener sus propios datos de configuraci髇
     *
     *  ej. si es desarrollo, deber帷 apuntar a la basa de datos correspondiente,
     * cambia la conexi髇 si es un sistema en producci髇
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
        if(isset($this->_config->general->timezone)){
            date_default_timezone_set($this->_config->general->timezone);
        }else{
            date_default_timezone_set('America/Buenos_Aires');
        }
    }
   /**
    * DEBUG sirve para agregar rutinas en caso de necesitar
    * "solo debug" y que no es necesario que est谩n ejecutando
    * constantemente en producci贸n (informaci贸n extra en logs, etc).
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
    * SESSION DEFAULT en caso que el sistema requiera siempre una sesi贸n
    * (como en el caso de las aplicaciones de ADMIN's)
    */
    public function setSessionDefault()
    {
        if($this->_config->general->session){

            if(isset($this->_config->general->appname)){
                $session_name = $this->_config->general->appname;
            }else{
                $session_name = 'default_app_name';
            }

            $session = new Zend_Session_Namespace($session_name);
            $this->_registry->set('session', $session);
        }
    }
    /*
     * Configuraci贸n Base de Datos
     *
     * - Se diferencias las conexiones seg煤n los ambientes de trabajo,
     *  database_devel para desarrollo y database_prod para producci贸n,
     *  si devel = on va a la primera, de lo contrario es producci贸n
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
         * - Solo en producci髇, ante errores de url's, redireccionar a un
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
         * MODULE DEFAULT define cual es el m骴ulo por defecto
         * que debe levantar cuando inicie por primera vez la aplicaci髇
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
         * LOAD MODULES lee la lista de m骴ulos existentes en el
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
            $this->setTimeZone();
            $this->setErrorReporting();           
            $this->setRegistry();
            $this->setSessionDefault();
            $this->setDatabase();
            $this->setController();
            $this->setModules();
            $this->setView();

            $this->_controller->dispatch();

        }catch(Zend_Exception $e){

            /* En producci髇 no se desplega informaci髇 de error
             * del sistema (considerar implementar env韔 de email
             * o log de fallos) */
            if($this->_config->general->devel || $this->_config->general->debug){

                echo highlight_string($e);

            }
        }
    }
}