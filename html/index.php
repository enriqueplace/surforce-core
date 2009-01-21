<?php
/**
 * SURFORCE
 * 
 * @author  SURFORCE
 * @version 0.1
 */

require_once '../application/Bootstrap.php';

$bootstrap = new Bootstrap();

$bootstrap->setPath();
$bootstrap->setAutoload();
$bootstrap->setConfig();
$bootstrap->setRegistry();
$bootstrap->setTimeZone();

$bootstrap->setErrorReporting();
$bootstrap->setSessionDefault();
$bootstrap->setDatabase();

$bootstrap->setController();

$bootstrap->setModules();
$bootstrap->setLayoutDefault();

$bootstrap->run();
