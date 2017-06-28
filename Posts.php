<?php

/**
Модель для БД wp_posts
 */
class Posts extends ActiveRecord
{
    /* Соответствующие поля таблицы wp_posts */
    public $post_author             = 1;
    public $post_excerpt            = '';
    public $post_status             = 'publish';
    public $comment_status          = 'closed';
    public $ping_status             = 'closed';
    public $post_password           = '';
    public $to_ping                 = '';
    public $pinged                  = '';
    public $post_content_filtered   = '';
    public $post_parent             = 0;
    public $guid                    = '';
    public $menu_order              = 0;
    public $post_type               = 'post';
    public $post_mime_type          = '';
    public $comment_count           = 0;
    /* -------------------------------------- */

    public static function tableName(){
        return 'book_store_posts';
    }


    public public function __construct()
    {
        $model = new Posts();
        $model->post_date = date("Y-m-d H:i:s");
        $model->post_date_gmt = date("Y-m-d H:i:s",time()-2*60*60);

        return $model;
    }


    public function setData($litresBook)
    {
        $litresLink = 'http://www.litres.ru/' . ($litresBook->litres_url != '' ? $litresBook->litres_url . '?lfrom=' : 'pages/biblio_book/?art=' . $litresBook->hub_id . '&lfrom=' ) . Const::PARTNER_ID;       
        $imageName = mb_substr(str_ireplace(array('`','~','!','@','#','№','"',';','$','%','^',':','&','?','*','+','=','\'','|','\\','/',',','.',' '),'-',$litresBook->author_name . '-' . $litresBook->author_sname . '-' . $litresBook->book_title),0,80,'utf-8');
        $this->post_name = $this->book_title = $litresBook->author_name . ' ' . $litresBook->author_sname . ' - ' . $litresBook->book_title;
        $this->post_content = sprintf(Const::POST_DESCRIPTION,
                                    $imageName,
                                    $this->book_title,
                                    $litresBook->author_name . ' ' . $litresBook->author_sname,
                                    $litresBook->book_title,
                                    $litresBook->genre_names,
                                    $litresBook->annotation !='' ? '<strong>Описание: </strong>' . $litresBook->annotation : '',
                                    $litresLink
                                    );

        if (!is_null($litresLink->hub_id)){
            $this->post_content .= sprintf(Cont::POST_DOWNLOAD, $litresLink->hub_id);
        }    
    }
}