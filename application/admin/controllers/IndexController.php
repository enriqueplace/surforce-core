<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */

class Admin_IndexController extends Zsurforce_Generic_ControllerAdmin
{
    public function init()
    {
        parent::init();
        $this->view->headTitle('Admin Module');
        $this->view->placeholder('title')->set('Admin Module');
    }
    public function indexAction() 
    {
        
    }    
}
