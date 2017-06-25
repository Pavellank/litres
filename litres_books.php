<?php
error_reporting(-1);

	include("config.php");
	include("functions.php");
	include("rus-to-lat.php");
	
	mysql_select_db(DB_NAME,$db_link);
	mysql_query("SET NAMES " . DB_CHARSET,$db_link);
	
	$partner_id = 299612548;
	

	/*AND local_book_id IS NULL
					AND local_book_id_litres_catalog IS NULL*/
	/*AND ydisk_book_url != ''*/
	$q = "SELECT * FROM `litres_data`";

	$result = mysql_query($q,$db_link);
	while ($row = mysql_fetch_array($result)){
		if($row['local_book_id'] == NULL){
			$litres_link = 'http://www.litres.ru/' . ($row['litres_url'] != '' ? $row['litres_url'] . '?lfrom=' : 'pages/biblio_book/?art=' . $row['hub_id'] . '&lfrom=' ) . $partner_id;
			$yadisk_link = $row['ydisk_book_url'];
			
			//$full_story = nl2br($row['annotation']);
			
			$cover_name = mb_substr(str_ireplace(array('`','~','!','@','#','№','"',';','$','%','^',':','&','?','*','+','=','\'','|','\\','/',',','.',' '),'-',$row['author_name'] . '-' . $row['author_sname'] . '-' . $row['book_title']),0,80,'utf-8');
			
			$full_story = '<img src="http://я-книга.рф/upload/' . $cover_name . '.jpg" alt="' . mysql_real_escape_string($row['author_name'] . ' ' . $row['author_sname'] . ' - ' . $row['book_title']) . '" width="228" height="368" class="aligncenter size-full" />

<strong>Автор: </strong>' . $row['author_name'] . ' ' . $row['author_sname'] . '

<strong>Название: </strong>' . $row['book_title'] . '

<strong>Жанр: </strong>' . $row['genre_names'] . '

<strong>Язык книги: </strong>Русский

<strong>Формат: </strong>FB2, ePub, pdf, txt и другие

' . ($row['annotation'] !='' ? '<strong>Описание: </strong>' . $row['annotation'] : '') . '

<a class="button green" href="' . $litres_link . '">Скачать!</a>';

if ($row['hub_id']){
	$full_story .= '
		<div id="litres_trials">
			Скачать книгу в форматах:&nbsp;<a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=fb2.zip&lfrom=159590199" class="a-litres">FB2</a>	<a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=epub&lfrom=159590199" class="a-litres">ePUB</a> <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=a4.pdf&lfrom=159590199" class="a-litres">PDF</a> <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=txt.zip&lfrom=159590199" class="a-litres">TXT</a>
		</div>';
}

			if ($row['local_book_id_litres_catalog'] == NULL){

				$q = "
					post_author = 1,
					post_date = '" . date("Y-m-d H:i:s") . "',
					post_date_gmt = '" . date("Y-m-d H:i:s",time()-2*60*60) . "',
					post_content = '" . mysql_real_escape_string($full_story) . "',
					post_title = '" . mysql_real_escape_string($row['author_name'] . ' ' . $row['author_sname'] . ' - ' . $row['book_title']) . "',
					post_excerpt = '',
					post_status = 'publish',
					comment_status = 'closed',
					ping_status = 'closed',
					post_password = '',
					post_name = '" . mysql_real_escape_string(totranslit($row['author_name'] . ' ' . $row['author_sname'] . ' - ' . $row['book_title'])) . "', 
					to_ping = '',
					pinged = '',
					
					post_content_filtered = '',
					post_parent = 0,
					guid = '',
					menu_order = 0,
					post_type = 'post',
					post_mime_type = '',
					comment_count = 0
					";
				
				echo $q1 = 'INSERT INTO wp_posts SET ' . $q;
				mysql_query($q1,$db_link);
				$local_book_id = mysql_insert_id($db_link);
				
				echo $q = "UPDATE wp_posts SET guid = 'http://я-книга.рф/?p=" . $local_book_id . "' WHERE ID = " . $local_book_id;
				mysql_query($q,$db_link);
				
				//ищем автора
				echo $q = "SELECT * FROM `wp_terms` WHERE
						name like '%" . $row['author_sname'] . "%'
						AND
						name like '%" . $row['author_name'] . "%'
						LIMIT 1";

				$res = mysql_query($q,$db_link);
				if (mysql_num_rows($res) > 0){
					//автор есть в базе
					$row_auth = mysql_fetch_array($res);
					$author_id = $row_auth['term_id'];
					
					echo $q = "UPDATE `wp_term_taxonomy` SET count=count+1 WHERE term_id = " . $row_auth['term_id'];
					mysql_query($q,$db_link);
					
					$q = "SELECT * FROM wp_term_taxonomy WHERE term_id = " . $author_id;
					$res = mysql_query($q,$db_link);
					$row_tax = mysql_fetch_array($res);
					$taxonomy_author_id = $row_tax['term_taxonomy_id'];
				}
				else{
					//автор новый
					$q = "
						name = '" . mysql_real_escape_string($row['author_name'] . ' ' . $row['author_sname']) . "',
						slug = '" . mysql_real_escape_string(totranslit($row['author_name'] . ' ' . $row['author_sname'])) . "',
						term_group = 0";
					echo $q1 = 'INSERT INTO wp_terms SET ' . $q;
					mysql_query($q1,$db_link);

					$author_id = mysql_insert_id($db_link);
					
					echo $q = "INSERT INTO `wp_term_taxonomy` SET
							term_id = " . $author_id . ",
							taxonomy = 'post_tag',
							description = '',
							parent = 0,
							count = 0";
					mysql_query($q,$db_link);
					
					$taxonomy_author_id = mysql_insert_id($db_link);
				}
				
				//привязка поста и его тегов (автор)
				echo $q = "INSERT IGNORE INTO `wp_term_relationships` SET
						object_id = " . $local_book_id . ",
						term_taxonomy_id = " . $taxonomy_author_id . ",
						term_order = 0";
				mysql_query($q,$db_link);
				
				
				$q = "SELECT * FROM wp_term_taxonomy WHERE term_id = " . $row['local_category'];
				$res = mysql_query($q,$db_link);
				$row_tax = mysql_fetch_array($res);
				$taxonomy_cat_id = $row_tax['term_taxonomy_id'];
				//привязка поста и его тегов (жанр)
				echo $q = "INSERT INTO `wp_term_relationships` SET
						object_id = " . $local_book_id . ",
						term_taxonomy_id = " . $taxonomy_cat_id . ",
						term_order = 0";
				mysql_query($q,$db_link);
				
				
				echo $q = "UPDATE `wp_term_taxonomy` SET count=count+1 WHERE term_id = " . $row['local_category'];
				mysql_query($q,$db_link);
				
				
				//ссылка на литрес
				echo $q = "
						post_id = " . $local_book_id . ",
						meta_key = 'litres_link',
						meta_value = '" . $litres_link . "'";
				$q1 = 'INSERT INTO wp_postmeta SET ' . $q;
				mysql_query($q1,$db_link);
				
				echo $q = "
						post_id = " . $local_book_id . ",
						meta_key = 'litres_hub_id',
						meta_value = '" . $row['hub_id'] . "'";
				$q1 = 'INSERT INTO wp_postmeta SET ' . $q;
				mysql_query($q1,$db_link);
				
			}
			else{
				//книга обновляется
				echo $q = "UPDATE wp_posts SET
					post_content = '" . mysql_real_escape_string($full_story) . "',
					post_title = '" . mysql_real_escape_string($row['author_name'] . ' ' . $row['author_sname'] . ' - ' . $row['book_title']) . "',
					WHERE ID = " . $row['local_book_id_litres_catalog'];
				mysql_query($q,$db_link);
				$local_book_id = $row['local_book_id_litres_catalog'];
				
				//ссылка на литрес
				$q = "UPDATE `wp_postmeta` SET
						meta_value = '" . $litres_link . "'
						WHERE post_id = " . $local_book_id . "
						AND meta_key = 'litres_link'";
				mysql_query($q,$db_link);
				
				$q = "UPDATE `wp_postmeta` SET
						meta_value = '" . $row['hub_id'] . "'
						WHERE post_id = " . $local_book_id . "
						AND meta_key = 'litres_hub_id'";
				mysql_query($q,$db_link);
				
			}
			
			echo $q = "UPDATE `litres_data` SET 
										local_book_id_litres_catalog = " . $local_book_id . "
										WHERE hub_id = " . $row['hub_id'];
			mysql_query($q,$db_link);
		}
		else{
			$local_book_id = ($row['local_book_id_litres_catalog'] > 0 ? $row['local_book_id_litres_catalog'] : $row['local_book_id']);
		}
		
		//файлы
		//обложка
		$cover_id = $row['litres_id'];
		while (strlen($cover_id) < 8){
			$cover_id = '0' . $cover_id;
		}
		$cover_path = 'http://www.litres.ru/static/bookimages/' . $cover_id[0] . $cover_id[1] . '/' . $cover_id[2] . $cover_id[3] . '/' . $cover_id[4] . $cover_id[5] . '/' . $cover_id . '.bin.dir/' . $cover_id . '.cover.' . $row['cover_ext'];
		$new_image = new picture($cover_path);
		$new_image->imageresizewidth(150);
		$new_image->imagesave($new_image->image_type, '../upload/' . $cover_name . '.jpg', 85);
        $new_image->imageout();

        /*
        $new_image = new picture($cover_path);
        $new_image->imageresizewidth(50);
        $new_image->imagesave($new_image->image_type, '../../uploads/litres/' . $row['hub_id'] . '_small.jpg', 85);
        $new_image->imageout();
        */
		//fb2 фрагмент
		/*
		$file_id = $row['hub_id'];
		while (strlen($file_id) < 8){
			$file_id = '0' . $file_id;
		}
		$fb2_path = 'http://www.litres.ru/static/trials/' . $file_id[0] . $file_id[1] . '/' . $file_id[2] . $file_id[3] . '/' . $file_id[4] . $file_id[5] . '/' . $file_id . '.fb2.zip';
		if ($fb2 = file_get_contents($fb2_path)){
			file_put_contents('../../../books/litres/' . $row['hub_id'] . '.fb2.zip',$fb2);
		}
		*/
		
		//txt фрагмент
		/*
		$txt_id = $row['hub_id'];
		while (strlen($txt_id) < 8){
			$txt_id = '0' . $txt_id;
		}
		$txt_path = 'http://www.litres.ru/static/trials/' . $txt_id[0] . $txt_id[1] . '/' . $txt_id[2] . $txt_id[3] . '/' . $txt_id[4] . $txt_id[5] . '/' . $txt_id . '.txt';
		if ($txt = file_get_contents($txt_path)){
			file_put_contents('/home/godli209/domains/kniz.ru/public_html/download/' . $local_book_id . '.txt',$txt);
		}
		*/
	}
?>