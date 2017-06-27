<?php

/**
Модель для БД wp_posts
 */
class Posts
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

    private $pdo;


    public function tableName()
    {
        return 'wp_posts';
    }


    public function __construct(PDO $pdo, $date)
    {
        $this->pdo           = $pdo;
        $this->post_date     = date('Y-m-d H:i:s', $date);
        $this->post_date_gmt = date('Y-m-d H:i:s', $date-2*60*60);
    }


    /**
     * Функция вставляет одну запись в таблицу wp_posts
     * @return int id вставленной записи
     * @throws Exception
     */
    public function save()
    {
        $this->pdo->beginTransaction();

        $postId = $this->pdo->lastInsertId();
        $this->guid = SITE . '/?p=$postId';
        $tableFieldArr = get_object_vars($this);
        $query = $this->pdo->prepare('INSERT INTO ' . $this->tableName() . '(' .implode(',', $tableFieldArr). ')');
        foreach ($tableFieldArr as $tableField) {
            switch ($tableField) {
                case 'guid':
                case 'pinged':
                case 'to_ping':
                case 'post_date':
                case 'post_name':
                case 'post_type':
                case 'post_title':
                case 'post_status':
                case 'ping_status':
                case 'post_content':
                case 'post_excerpt':
                case 'post_password':
                case 'post_date_gmt':
                case 'comment_status':
                case 'post_mime_type':
                case 'post_content_filtered':
                    $query->bindValue($tableField, PDO::PARAM_STR);
                    break;

                case 'menu_order':
                case 'post_author':
                case 'post_parent':
                case 'comment_count':
                    $query->bindValue($tableField, PDO::PARAM_INT);
                    break;

                default:
                    throw new Exception('Не определено свойство ' . $tableField);
            }
        }

        $query->execute();

        $this->pdo->commit();

        return $postId;
    }

}