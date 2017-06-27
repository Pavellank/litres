<?php
include("config.php");
include("functions.php");
include("rus-to-lat.php");

$optionArr = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME.";charset=utf8", DB_NAME, DBPASS, $optionArr);

$litresBookArr = $pdo->query('SELECT * FROM litres_data')->execute();
if(is_null($litresBookArr)){
    throw new Exception('Нет книг от Литрес');
}
foreach ($litresBookArr as $litresBook) {
    if (is_null($litresBook['local_book_id'])) {
        $litresLink = 'http://www.litres.ru/' . ($row['litres_url'] != '' ? $row['litres_url'] : 'pages/biblio_book/?art=' . $row['hub_id']  ). '&lfrom=' . $partner_id;
        $imgName = mb_substr(str_ireplace(array('`','~','!','@','#','№','"',';','$','%','^',':','&','?','*','+','=','\'','|','\\','/',',','.',' '),'-',$row['author_name'] . '-' . $row['author_sname'] . '-' . $row['book_title']),0,80,'utf-8');
        $author = $row['author_name'] . ' ' . $row['author_sname'];
        $annotation = $row['annotation'] !='' ? '<strong>Описание: </strong>' . $row['annotation'] : '';
        $fullStory = sprintf($fullStoryTemplate,
            $imgName,
            $author,
            $row['book_title'],
            $author,
            $row['book_title'],
            $row['genre_names'],
            $annotation,
            $litresLink
            );
    }

    if ($row['hub_id']){
        $fullStory .=
            '<div id="litres_trials">
                Скачать книгу в форматах:&nbsp;
                <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=fb2.zip&lfrom=159590199" class="a-litres">FB2</a>
                <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=epub&lfrom=159590199"    class="a-litres">ePUB</a>
                <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=a4.pdf&lfrom=159590199"  class="a-litres">PDF</a>
                <a href="http://www.litres.ru/gettrial/?art=' . $row['hub_id'] . '&format=txt.zip&lfrom=159590199" class="a-litres">TXT</a>
		    </div>';
    }
}