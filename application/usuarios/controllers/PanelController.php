<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */
require_once '../application/default/models/Usuarios.php';

class Usuarios_PanelController extends Zsurforce_Generic_ControllerUsuarios
{
    public function init()
    {
        parent::init();
    }
    public function indexAction() 
    {
        
    }
    public function claveAction()
    {
        if ($this->_request->isPost()) {

            Zend_Loader::loadClass('Zend_Filter_StripTags');

            $f = new Zend_Filter_StripTags();

            $pass1  = $f->filter($this->_request->getPost('pass1'));
            $pass2 	= $f->filter($this->_request->getPost('pass2'));

            if ($pass1 != $pass2){
                $this->view->mensajeError = '¡Las claves ingresadas no coinciden!';
            } else {

                $model = new Models_Usuarios();
                $model->setPassword($pass1, $this->_user->usuario_id);
                $this->view->mensajeInformativo = '¡Clave Actualizada!';
            }
        }
    }
}
