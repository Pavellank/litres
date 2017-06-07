<?php

	set_time_limit(0);

	include("config.php");

	include("functions.php");
	
	mysql_select_db(DB_NAME,$db_link);
	mysql_query("SET NAMES " . DB_CHARSET, $db_link);
	compare_local_global();
	
	echo 'finished';
?>