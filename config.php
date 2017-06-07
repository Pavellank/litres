<?php
	define ( 'DATALIFEENGINE', true );
	
	define ( 'ROOT_DIR', dirname ( __FILE__ ) . '/../../..' );
	define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

	require_once ENGINE_DIR . '/classes/mysql.php';
	require_once ENGINE_DIR . '/data/dbconfig.php';

	$db_link = mysql_connect(DBHOST,DBUSER,DBPASS);
	mysql_select_db(DBNAME);
	mysql_query("SET NAMES utf8");
	
	$table_prefix = PREFIX . '_';
	
	$partner_id = 0;	//получить в ЛитРес
	$partner_a_id = 0; //для совпадений только по автору
	
?>