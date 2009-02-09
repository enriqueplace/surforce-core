<?php
/**
 * LoginController
 *
 * @author SURFORCE
 * @version
 */
require_once '../application/default/models/Usuarios.php';

class Usuarios_LoginController extends Zsurforce_Generic_Controller {

    public function init()
    {
        parent::init();
    }
    public function indexAction()
    {
        $this->view->message = '';

        if ($this->_request->isPost()) {

            Zend_Loader::loadClass('Zend_Filter_StripTags');

            $f = new Zend_Filter_StripTags();

            $usuario 	= $f->filter($this->_request->getPost('usuario'));
            $password 	= $f->filter($this->_request->getPost('password'));

            if (empty($usuario)) {
                $this->view->message = '¡Nombre vacío!';
            } else {

                try{
                    Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');

                    $dbAdapter = Zend_Registry::get('dbAdapter');
                    $autAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

                     /*
                      * Carga configuración dinámica de los datos de la
                      * tabla que contiene usuarios del sistema
                      */
                    $usuarios_tabla     = $this->_config->database->table->usuarios;
                    $usuarios_login     = $this->_config->database->table->usuarios_login;
                    $usuarios_pass      = $this->_config->database->table->usuarios_password;
                    $usuarios_estado    = $this->_config->database->table->usuarios_estado;

                    $autAdapter->setTableName($usuarios_tabla);
                    $autAdapter->setIdentityColumn($usuarios_login);
                    $autAdapter->setCredentialColumn($usuarios_pass);

                    $autAdapter->setIdentity($usuario);
                    /*
                     * Habilitar el login solo si
                     * el usuario es estado = 1
                     */

                    if( Models_Usuarios::isValid($usuario, $usuarios_tabla, $usuarios_login, $usuarios_estado) ){
                        $autAdapter->setCredential(md5($password));
                    }else{
                        $autAdapter->setCredential('');                        
                    }

                    $aut = Zend_Auth::getInstance();
                    $result = $aut->authenticate($autAdapter);

                    if ($result->isValid()) {
                        $data = $autAdapter->getResultRowObject(null, 'clave');
                        $aut->getStorage()->write($data);
                        $this->_redirect('/');
                    } else {
                        $this->view->message = '¡Usuario o Clave incorrectos!';
                        echo "usuario invalido";
                    }

                }catch(Zend_Db_Statement_Exception $e){
                    
                    $this->view->mensajeError =
                        'Se ha producido un error al intentar recuperar los datos <br><br>'
                        .' En este momento se envió un reporte con el fallo al área de sistemas' ;

                        if($this->_devel){
                             $this->view->mensajeError .= $e;
                        }
                        mail(
                            $this->_config->email->system,
                            'SURFORCE_USUARIOS: error sintaxis en bd de login',
                            var_export($usuario, true) . ': '. $e
                        );

                }catch(Zend_Db_Adapter_Exception $e){
                    $this->view->mensajeError =
                        'Se ha producido un error al conectar a la base de datos.'
                        .' Por favor reintente en unos minutos';

                        mail(
                            $this->_config->email->system,
                            'SURFORCE_USUARIOS: error conexion en bd',
                            var_export($usuario, true) . ': '. $e
                        );

                }catch(Zend_Exception $e){
                    $this->view->mensajeError =
                        'Se ha producido un error inesperado.'
                        .' Por favor reintente en unos minutos';

                        mail(
                            $this->_config->email->system,
                            'SURFORCE_USUARIOS: login error general',
                            var_export($usuario, true) . ': '. $e
                        );

                }
            }
        }

        $this->view->title = 'Login';

        $this->render();
    }
    function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
}