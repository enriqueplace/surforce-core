<?php
/**
 * LoginController
 * 
 * @author SURFORCE
 * @version 
 */
require_once '../application/default/models/Usuarios.php';

class Admin_LoginController extends Zsurforce_Generic_Controller {

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

                Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
                
                $dbAdapter = Zend_Registry::get('dbAdapter');
                $autAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                
                $autAdapter->setTableName('usuarios');
                $autAdapter->setIdentityColumn('usuario');
                $autAdapter->setCredentialColumn('clave');
                
                $autAdapter->setIdentity($usuario);                
                /*
                 * Habilitar el login solo si 
                 * el usuario es estado = 1 
                 */

                if( Models_Usuarios::isValid($usuario) ){
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