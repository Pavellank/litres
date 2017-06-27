<?php

/**
Модель для БД wp_posts
 */
class Posts extends ActiveRecord
{
    /* Соответствующие поля таблицы wp_posts */
    public $post_author             = 1;
    public $post_date;
    public $post_date_gmt;
    public $post_content;
    public $post_title;
    public $post_excerpt            = '';
    public $post_status             = 'publish';
    public $comment_status          = 'closed';
    public $ping_status             = 'closed';
    public $post_password           = '';
    public $post_name;
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
}