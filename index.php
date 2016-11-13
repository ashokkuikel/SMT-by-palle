<?php

/**
 * Webframework zur einfachen Erstellung von Internet und Intranetlösungen
 * 
 * <code>
 * define('project_path', dirname(__FILE__) );
 * define('project_base', $_SERVER['HTTP_HOST']);
 * include('library/App.php');
 * </code>
 *
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 */

define('project_path', dirname(__FILE__));
define('project_base', $_SERVER['HTTP_HOST']);

if(is_dir("install")) {
  include_once 'install/install.php';
} else {
  include_once 'library/App.php';
}

?>
