<?php
	
	//ставим 1251 локаль т.к. внешние csv в 1251
	@setlocale(LC_ALL, array("Russian_Russia.1251","ru_RU.CP1251","ru_RU.cp1251","ru_RU","RU","rus_RUS.1251"));
	
	$partner_id = 299612548;
	$table_prefix = 'wp_';

	function multineedle_stripos($haystack, $needles, $offset=0) {
		foreach($needles as $needle) {
			if (stripos($haystack, $needle, $offset) !== false){
				$found[$needle] = stripos($haystack, $needle, $offset);
			}
		}
		return (isset($found) ? $found : false);
	}
	
	function delete_litres_book($hub_id){
		//удаляем книги путем простановки options=0
		$q = "UPDATE `litres_data` SET options=0 WHERE hub_id=" . $hub_id;
		mysql_query($q);
	}
	

	function compare_local_global(){

		global $partner_id, $table_prefix, $db_link;

		@include('dictionary.php');
		
		//ставим служебный индекс
		$q = "ALTER TABLE `" . $table_prefix . "posts` ADD FULLTEXT (`post_title`)";
		mysql_query($q,$db_link);
		//------------------------

		$q = "SELECT * FROM `litres_data` WHERE 
					`type` = 0 AND
					`hub_id` > 0 

				";

		$result = mysql_query($q,$db_link);

		
		if (mysql_num_rows($result)>0){
			while ($row = mysql_fetch_array($result)){
				
				$litres_link = ''; $book_title_t = ''; $book_title_1 = ''; $book_title_2 = '';
				
				if ($row['author_sname'] != '' && $row['book_title'] != ''){
					$row['book_title'] = trim(strtr($row['book_title'], $repl_ar));
					
					if (stripos($row['book_title'],'.') !== false){
						$book_title_t = explode('.',$row['book_title']);
						$book_title_1 = trim($book_title_t[0]);
						$book_title_2 = trim(end($book_title_t));
					}
					elseif (stripos($row['book_title'],':') !== false){
						$book_title_t = explode(':',$row['book_title']);
						$book_title_1 = trim($book_title_t[0]);
						$book_title_2 = trim(end($book_title_t));
					}
					elseif (stripos($row['book_title'],'!') !== false){
						$book_title_t = explode('!',$row['book_title']);
						$book_title_1 = trim($book_title_t[0]);
						$book_title_2 = trim(end($book_title_t));
					}
					else{
						$book_title_1 = $row['book_title'];
					}
					
					$q = "SELECT * FROM " . $table_prefix . "posts WHERE
							post_type = 'post'
							AND
							(
							" . (mb_strlen($book_title_1,'utf8') > 3 ? "MATCH(post_title) AGAINST ('\"" . $book_title_1 . "\"' IN BOOLEAN MODE)" : "post_title like '%" . $book_title_1 . "%'") . "
							" . (mb_strlen($book_title_2,'utf8') > 10 ? " OR MATCH(post_title) AGAINST ('\"" . $book_title_2 . "\"' IN BOOLEAN MODE)" : "") . "
							
							)
							AND
							(
								" . (mb_strlen($row['author_sname'],'utf8') > 3 ? "MATCH(post_title) AGAINST ('\"" . $row['author_sname'] . "\"' IN BOOLEAN MODE)" : "post_title like '%" . $row['author_sname'] . "%'") . "
								" .
								(
								$row['second_author_sname'] != '' ?
								"OR
									(MATCH(`post_title`) AGAINST ('\"" . $row['second_author_sname'] . "\"' IN BOOLEAN MODE))
									" 
								: ""						
								)
							. "
							)
						LIMIT 1";
						
					$res = mysql_query($q,$db_link);
				    echo $q;
                    die;
                    if (mysql_num_rows($res) > 0){
						$r = mysql_fetch_array($res);
						$litres_link = 'http://www.litres.ru/' . ($row['litres_url'] != '' ? $row['litres_url'] . '?lfrom=' : 'pages/biblio_book/?art=' . $row['hub_id'] . '&lfrom=' ) . $partner_id;
						
						$q = "DELETE FROM `wp_postmeta` WHERE post_id = " . $r['ID'] . " AND (meta_key = 'litres_link' OR meta_key = 'litres_hub_id')";
						mysql_query($q,$db_link);
						
						echo $q = "INSERT INTO `wp_postmeta` SET 
								post_id = " . $r['ID'] . ",
								meta_key = 'litres_link',
								meta_value = '" . $litres_link . "'";
						mysql_query($q,$db_link);
						
						echo $q = "INSERT INTO `wp_postmeta` SET 
								post_id = " . $r['ID'] . ",
								meta_key = 'litres_hub_id',
								meta_value = '" . $row['hub_id'] . "'";
						mysql_query($q,$db_link);
						
						echo $q = "UPDATE litres_data SET 
								local_book_id = " . $r['ID'] . "
								WHERE hub_id = " . $row['hub_id'];
						mysql_query($q,$db_link);
						
						//if (stripos($r['post_content'],'litres') === false){
							$r['post_content'] = preg_replace("/\[button style=\"green\" url=\"(.+)\"\]/i","[button style=\"green\" url=\"" . $litres_link . "\"]",$r['post_content']);
							$r['post_content'] = preg_replace("/\<a class=\"button green\" href=\"(.+)\"/i","<a class=\"button green\" href=\"" . $litres_link . "\"",$r['post_content']);
							if ($row['hub_id']){
								$r['post_content'] = preg_replace("/\<div id=\"litres_trials\"\>(.+)\<\/div\>/iUs","",$r['post_content']);
								$r['post_content'] .= '
									<div id="litres_trials">
										Скачать книгу в форматах:&nbsp;<a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=fb2.zip&lfrom=159590199" class="a-litres">FB2</a>	<a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=epub&lfrom=159590199" class="a-litres">ePUB</a> <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=a4.pdf&lfrom=159590199" class="a-litres">PDF</a> <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=txt.zip&lfrom=159590199" class="a-litres">TXT</a>
									</div>';
							}
							echo $q = "UPDATE wp_posts SET 
								post_content = '" . mysql_real_escape_string($r['post_content']) . "'
								WHERE ID = " . $r['ID'];
							mysql_query($q,$db_link);
						//}
					}
					
				}
				echo ($k++)."\r\n";
				unset($r); mysql_free_result($res);
			}
		}
		
		//убираем служебный индекс
		$q = "ALTER TABLE `" . $table_prefix . "posts` DROP INDEX `post_title`";
		mysql_query($q,$db_link);
		//-------------------------
		
		return true;
		
	}
	
	class picture {
	     
	    private $image_file;
	     
	    public $image;
	    public $image_type;
	    public $image_width;
	    public $image_height;
	     
	     
	    public function __construct($image_file) {
	        $this->image_file=$image_file;
	        $image_info = getimagesize($this->image_file);
	        $this->image_width = $image_info[0];
	        $this->image_height = $image_info[1];
	        switch($image_info[2]) {
	            case 1: $this->image_type = 'gif'; break;//1: IMAGETYPE_GIF
	            case 2: $this->image_type = 'jpeg'; break;//2: IMAGETYPE_JPEG
	            case 3: $this->image_type = 'png'; break;//3: IMAGETYPE_PNG
	            case 4: $this->image_type = 'swf'; break;//4: IMAGETYPE_SWF
	            case 5: $this->image_type = 'psd'; break;//5: IMAGETYPE_PSD
	            case 6: $this->image_type = 'bmp'; break;//6: IMAGETYPE_BMP
	            case 7: $this->image_type = 'tiffi'; break;//7: IMAGETYPE_TIFF_II (порядок байт intel)
	            case 8: $this->image_type = 'tiffm'; break;//8: IMAGETYPE_TIFF_MM (порядок байт motorola)
	            case 9: $this->image_type = 'jpc'; break;//9: IMAGETYPE_JPC
	            case 10: $this->image_type = 'jp2'; break;//10: IMAGETYPE_JP2
	            case 11: $this->image_type = 'jpx'; break;//11: IMAGETYPE_JPX
	            case 12: $this->image_type = 'jb2'; break;//12: IMAGETYPE_JB2
	            case 13: $this->image_type = 'swc'; break;//13: IMAGETYPE_SWC
	            case 14: $this->image_type = 'iff'; break;//14: IMAGETYPE_IFF
	            case 15: $this->image_type = 'wbmp'; break;//15: IMAGETYPE_WBMP
	            case 16: $this->image_type = 'xbm'; break;//16: IMAGETYPE_XBM
	            case 17: $this->image_type = 'ico'; break;//17: IMAGETYPE_ICO
	            default: $this->image_type = ''; break;
	        }
	        $this->fotoimage();
	    }
	     
	    private function fotoimage() {
	        switch($this->image_type) {
	            case 'gif': $this->image = imagecreatefromgif($this->image_file); break;
	            case 'jpeg': $this->image = imagecreatefromjpeg($this->image_file); break;
	            case 'png': $this->image = imagecreatefrompng($this->image_file); break;
	        }
	    }
	     
	    public function autoimageresize($new_w, $new_h) {
	        $difference_w = 0;
	        $difference_h = 0;
	        if($this->image_width < $new_w && $this->image_height < $new_h) {
	            $this->imageresize($this->image_width, $this->image_height);
	        }
	        else {
	            if($this->image_width > $new_w) {
	                $difference_w = $this->image_width - $new_w;
	            }
	            if($this->image_height > $new_h) {
	                $difference_h = $this->image_height - $new_h;
	            }
	                if($difference_w > $difference_h) {
	                    $this->imageresizewidth($new_w);
	                }
	                elseif($difference_w < $difference_h) {
	                    $this->imageresizeheight($new_h);
	                }
	                else {
	                    $this->imageresize($new_w, $new_h);
	                }
	        }
	    }
	     
	    public function percentimagereduce($percent) {
	        $new_w = $this->image_width * $percent / 100;
	        $new_h = $this->image_height * $percent / 100;
	        $this->imageresize($new_w, $new_h);
	    }
	     
	    public function imageresizewidth($new_w) {
	        $new_h = $this->image_height * ($new_w / $this->image_width);
	        $this->imageresize($new_w, $new_h);
	    }
	     
	    public function imageresizeheight($new_h) {
	        $new_w = $this->image_width * ($new_h / $this->image_height);
	        $this->imageresize($new_w, $new_h);
	    }
	     
	    public function imageresize($new_w, $new_h) {
	        $new_image = imagecreatetruecolor($new_w, $new_h);
	        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->image_width, $this->image_height);
	        $this->image_width = $new_w;
	        $this->image_height = $new_h;
	        $this->image = $new_image;
	    }
	     
	    public function imagesave($image_type='jpeg', $image_file=NULL, $image_compress=100, $image_permiss='') {
	        if($image_file==NULL) {
	            switch($this->image_type) {
	                case 'gif': header("Content-type: image/gif"); break;
	                case 'jpeg': header("Content-type: image/jpeg"); break;
	                case 'png': header("Content-type: image/png"); break;
	            }
	        }
	        switch($this->image_type) {
	            case 'gif': imagegif($this->image, $image_file); break;
	            case 'jpeg': imagejpeg($this->image, $image_file, $image_compress); break;
	            case 'png': imagepng($this->image, $image_file); break;
	        }
	        if($image_permiss != '') {
	            chmod($image_file, $image_permiss);
	        }
	    }
	     
	    public function imageout() {
	        imagedestroy($this->image);
	    }
	     
	    public function __destruct() {
	         
	    }
	     
	}
	
	
?>