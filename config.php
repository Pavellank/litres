<?php
	define ( 'DATALIFEENGINE', true );
	define ( 'ROOT_DIR', dirname ( __FILE__ ) . '..' );
	define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );
	require_once  '../wp-config.php';
	define('DBHOST',    DB_HOST);
	define('DBUSER',    DB_USER);
    define('DBPASS',    DB_PASSWORD);
    define('DBNAME',    DB_NAME);
	$db_link = mysql_connect(DBHOST,DBUSER,DBPASS);
	mysql_select_db(DBNAME);
	mysql_query("SET NAMES utf8");
	
	$partner_id = 299612548;	//получить в ЛитРес
	$partner_a_id = 0; //для совпадений только по автору
	
?>