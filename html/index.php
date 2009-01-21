<?php
/**
 * SURFORCE
 * 
 * @author  SURFORCE
 * @version 0.1
 */

/*
 * Definición de rutas base
 */
set_include_path(
    '.'
    . PATH_SEPARATOR . '../library'
    . PATH_SEPARATOR . '../application/default/models/'
    . PATH_SEPARATOR . get_include_path()
);

/*
 * Habilitar autocarga de clases
 */
include "Zend/Loader.php";
Zend_Loader::registerAutoload();

/*
 * Configuración del sistema que será leída del archivo config.ini
 * - cada sistema deberá tener su propio archivo de configuración
 * - cada entorno deberá tener sus propios datos de configuración
 *
 *  ej. si es desarrollo, deberá apuntar a la basa de datos correspondiente,
 * cambia la conexión si es un sistema en producción
 */
$config = new Zend_Config_Ini('../application/config.ini');

/*
 * Se dejan variables disponibles en el sistema
 * que generalmente se van a requieren en su
 * uso normal
 */

$registry = Zend_Registry::getInstance();

$registry->set('config', $config);
$registry->set('base_path_html', realpath('.'));
$registry->set('base_path_app',  realpath('..'));
$registry->set('debug', $config->general->debug);
$registry->set('devel', $config->general->devel);

if($config->general->timezone){
    date_default_timezone_set($config->general->timezone);
}
/*
 * DEBUG sirve para agregar rutinas en caso de necesitar
 * "solo debug" y que no es necesario que están ejecutando
 * constantemente en producción (información extra en logs, etc).
 */
if($config->general->debug){

    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);

    error_reporting(E_ALL|E_STRICT);
}

/*
 * SESSION DEFAULT en caso que el sistema requiera siempre una sesión
 * (como en el caso de las aplicaciones de ADMIN's)
 */

if($config->general->session){

    $session_name = $config->general->appname ? $config->general->appname : 'app';
    
    $session = new Zend_Session_Namespace($session_name);
    $registry->set('session', $session);
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
if($config->general->debug){
    $db_adapter = $config->database_devel->db->adapter;
    $db_config = $config->database_devel->db->config->toArray();
}else{
    $db_adapter = $config->database_prod->db->adapter;
    $db_config = $config->database_prod->db->config->toArray();
}

$db = Zend_Db::factory($db_adapter, $db_config);

Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('dbAdapter', $db);

switch(strtolower($db_adapter)){
    case 'mysql':
    case 'mysqli':
    case 'pdo_mysql':
        $db->query('SET NAMES \''.strtoupper($config->database->charset).'\'');
        $db->query('SET CHARACTER SET '.strtoupper($config->database->charset));
        break;
}

/*
 * SETUP CONTROLLER
 */
$controller = Zend_Controller_Front::getInstance();

/*
 * MODULE DEFAULT define cual es el módulo por defecto
 * que debe levantar cuando inicie por primera vez la aplicación
 */
if($config->modules->default){
    $mod_default = $config->modules->default;
}else{
    $mod_default = '../application/default/controllers';
}
$controller->setControllerDirectory(
    $mod_default
);

/*
 * LOAD MODULES lee la lista de módulos existentes en el 
 * config.ini y registra sus rutas para poder permitir
 * responder a las peticiones desde el exterior
 */
foreach($config->modules->controllers as  $name => $path){    
    $controller->addControllerDirectory($path, $name);
}

/*
 * DEVEL / PROD comportamiento ante errores / fallas
 * 
 * - Solo desplegar excepciones en modo desarrollo
 * - Solo en producción, ante errores de url's, redireccionar a un
 * controller por defecto.
 */
if($config->general->devel){
    $controller->throwExceptions(true);
}else{
    $controller->setParam('useDefaultControllerAlways', true);
}

/*
 * BOOTSTRAP LAYOUTS
 */
Zend_Layout::startMvc(
    array(
        'layoutPath' => '../application/default/layouts',
        'layout' => 'main'
	));

/* RUN! */
try{
    
    $controller->dispatch();
    
}catch(Zend_Exception $e){
    
    /* En producción no se desplega información de error 
     * del sistema (considerar implementar envío de email
     * o log de fallos) */
    if($config->general->devel || $config->general->debug){
        echo $e;    
    }

}