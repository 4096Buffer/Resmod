<?php 
namespace Code\Libraries\Articles;

class ArticleObject extends \Code\Core\BaseController {
    
    protected $id;
    protected $title;
    protected $content;
    protected $image;
    protected $active;
    protected $category;
    protected $date;
    protected $comments_enabled;
    protected $author;
    protected $comments;

	public function __construct($article) {
		parent::__construct();
		$this->LoadLibrary(['DataBase', 'Auth', 'HTML', 'RequestHelper']);

        $this->id               = $article['id'];
        $this->title            = $article['title'];
        $this->content          = $article['content'];
        $this->image            = $article['image'];
        $this->active           = $article['active'];
        $this->date             = $article['date'];
        $this->comments_enabled = $article['comments_enabled']; 

        $this->category = $this->DataBase->GetFirstRow("SELECT * FROM `articles_categories` WHERE `name` = ?", [ $article['category'] ]);
        $this->author   = $this->DataBase->GetFirstRow("SELECT * FROM `users` WHERE `id` = ?", [ $article['author_id'] ]);
        $this->comments = $this->GetComments();
    }
    
    public function GetComments() {
        $comments = $this->DataBase->Get("SELECT * FROM `articles_comments` WHERE `article_id` = ?", [ $this->id ]);

        if(is_null($comments)) {
            return [];
        }

        foreach($comments as &$comment) {
            $author_id = $comment['author_id'];
            $author    = $this->DataBase->GetFirstRow("SELECT * FROM `users` WHERE `id` = ?", [ $author_id ]);

            if($author) {
                unset($author_id);
                $comment['author'] = $author;
            }
        }
    }

    public function GetDateObject() {
        return \date_create($this->$date);
    }

    public function GetId() {
        return $this->id;
    }

    public function GetTitle() {
        return $this->title;
    }

    public function GetContent() {
        return $this->content;
    }

    public function GetImage() {
        return $this->image;
    }

    public function GetActive() {
        return $this->active;
    }

    public function GetCategory() {
        return $this->category;
    }

    public function GetDate() {
        return $this->date;
    }

    public function GetCommentsEnabled() {
        return $this->comments_enabled == 1 ? true : false;
    }

    public function GetAuthor() {
        return $this->author;
    }
}

?>