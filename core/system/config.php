<?php

define('MAIN', $_SERVER['DOCUMENT_ROOT'] . '/');

define('URL_MAIN', 'http://localhost/');

define('CORE', MAIN . 'core/');

define('ANGULAR', URL_MAIN . 'core/angular/');

define('DIR_CONTROLLERS', MAIN . 'core/controllers/');

define('DIR_VIEWS', MAIN . 'core/views/');

define('ASSETS', URL_MAIN . 'assets/');

/* Definir constantes do banco */
define('BD_HOST', 'localhost');
define('BD_USER', 'root');
define('BD_PASS', '');
define('BD_DATABASE', 'tcc');