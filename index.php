<?php 
  require_once 'php/config.inc.php';
  require_once 'class/DB.class.php';
  require_once 'class/Main.class.php';
  require_once 'class/Login.class.php';
  require_once 'class/Historie-navstev.class.php';
  require_once 'class/Detail-navstevy.class.php';
  require_once 'class/Navsteva.class.php';
  require_once 'twig-master/lib/Twig/Autoloader.php';
  
  /**
   * Pripravi sablonovaci system Twig; udajne bez cachovani
   * Cesta k slozce s template je relativni od umisteni index.php
   */        
  Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader); 
  
  $main = new Main(@$_GET['page'], new DB());

	$template = $twig->loadTemplate($main->tpl);
	echo $template->render($main->render);
