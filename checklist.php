<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<h3>НЕСОВПАДЕНИЯ</h3>
<?php
	
	include("config.php");
	mysql_select_db(DB_NAME1,$db_link[1]);
	mysql_query("SET NAMES " . DB_CHARSET, $db_link[1]);
	
	$q = "SELECT count(*) AS c FROM `wp_posts` WHERE post_status='publish'";
	$result = mysql_query($q,$db_link[1]);
	$row = mysql_fetch_array($result);
	$total_count = $row['c'];
	
	$q = "SELECT ID FROM `wp_posts`
			JOIN wp_postmeta ON (ID=post_id)
			WHERE
				wp_postmeta.meta_key='buy'
			GROUP BY ID
			ORDER BY post_title";
	$result = mysql_query($q,$db_link[1]);
	$litresed_count = mysql_num_rows($result);
	
	while ($row = mysql_fetch_array($result)){
		$ids[] = $row['ID'];
	}
	$ids = implode(',',$ids);
	
	$q = "SELECT * FROM `wp_posts`
			WHERE
				ID NOT IN (" . $ids . ")
			ORDER BY post_title";
	$result = mysql_query($q,$db_link[1]);
	
	echo 'В базе: ' . $total_count . ' | ' . 'Совпало: ' . $litresed_count . ' | ' . 'Не совпало: ' . ($total_count - $litresed_count) . "<br><br>"; 
	
	
	while ($row = mysql_fetch_array($result)){
		echo $row['ID'] . '|' . $row['post_title'] . "<br>";
	}
?>

</body>
</html>