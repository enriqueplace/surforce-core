<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class IndexController extends Zsurforce_Generic_ControllerAdmin
{
	/**
	 * The default action - show the home page
	 */
    public function init()
    {
        parent::init();
        $this->view->headTitle('Admin Home');
        $this->view->placeholder('title')->set('Home');
    }
    public function indexAction() 
    {

    }
}