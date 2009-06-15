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
    private $_log;

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

        require_once 'Zend/Loader/Autoloader.php';
        $loader = Zend_Loader_Autoloader::getInstance();
        //$loader->registerNamespace('App_');
        $loader->setFallbackAutoloader(true);
        $loader->suppressNotFoundWarnings(false);

    }
    /**
     * Configuraci�n del sistema que ser� le�da del archivo config.ini
     * - cada sistema deber� tener su propio archivo de configuraci�n
     * - cada entorno deber� tener sus propios datos de configuraci�n
     *
     *  ej. si es desarrollo, deber� apuntar a la basa de datos correspondiente,
     * cambia la conexi�n si es un sistema en producci�n
     */
    public function setConfig()
    {
        try{
            $this->_config = new Zend_Config_Ini('../application/config.ini');
        }catch(Exception $e){
            echo $e;
        }
        
    }
    public function setLog()
    {
        if(!isset($this->_config->general->log->error)){
            $log = 'errores.log';
        }
        $log_file = $log;

        $stream = fopen($log_file, 'a', false);
        if (!$stream) {
            throw new Exception('Failed to open stream');
        }

        $writer = new Zend_Log_Writer_Stream($stream);
        $this->_log = new Zend_Log($writer);
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
    * "solo debug" y que no es necesario que están ejecutando
    * constantemente en producción (información extra en logs, etc).
    */
    public function setErrorReporting()
    {
        if( (isset($this->_config->general->debug) && $this->_config->general->debug) ||
            (isset($this->_config->general->devel) && $this->_config->general->devel)) {

            ini_set('display_startup_errors', 1);
            ini_set('display_errors', 1);
            error_reporting(E_ALL|E_STRICT);
        }else{
            ini_set('display_startup_errors', 0);
            ini_set('display_errors', 0);
            error_reporting(0);
        }
    }
   /**
    * SESSION DEFAULT en caso que el sistema requiera siempre una sesión
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
        if($this->_config->general->devel){
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

            echo '<strong>Se ha producido un error al intentar recuperar los datos</strong> <br><br>';
            $this->_log->log($e->getMessage(), Zend_Log::EMERG);
            exit;
            
        }catch(Zend_Db_Adapter_Exception $e){

            echo '<strong>Se ha producido un error al conectar a la base de datos</strong><br><br>';
            $this->_log->log($e->getMessage(), Zend_Log::CRIT);
            exit;

        }catch(Zend_Exception $e){

            echo '<strong>Se ha producido un error inesperado</strong><br><br> ';
            $this->_log->log($e->getMessage(),Zend_Log::EMERG);
            exit;
            
        }
    }
    public function setController()
    {
        $this->_controller = Zend_Controller_Front::getInstance();
        /*
         * DEVEL / PROD comportamiento ante errores / fallas
         *
         * - Solo desplegar excepciones en modo desarrollo
         * - Solo en producci�n, ante errores de url's, redireccionar a un
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
         * MODULE DEFAULT define cual es el m�dulo por defecto
         * que debe levantar cuando inicie por primera vez la aplicaci�n
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
         * LOAD MODULES lee la lista de m�dulos existentes en el
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
            $this->setLog();
            $this->setErrorReporting();
            $this->setTimeZone();
            $this->setRegistry();
            $this->setSessionDefault();
            $this->setDatabase();
            $this->setController();
            $this->setModules();
            $this->setView();

            $this->_controller->dispatch();

        }catch(Zend_Exception $e){

            /* En producci�n no se desplega informaci�n de error
             * del sistema (considerar implementar env�o de email
             * o log de fallos) */
            if($this->_config->general->devel || $this->_config->general->debug){

                echo highlight_string($e);

            }
        }
    }
}