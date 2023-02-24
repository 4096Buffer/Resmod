<?php 

namespace Code\Libraries\Articles;

class Articles extends \Code\Core\BaseController {
    private $articles;

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'RequestHelper']);
	}

    public function CreateObject($article) {
        $object = new \Code\Libraries\Articles\ArticleObject($article);

        return $object;
    }

    public function PreloadArticle($id) {
        foreach($this->articles as $article) {
            if($article->GetId() == $id) {
                $fetch = $this->DataBase->Get("SELECT * FROM `articles` WHERE `id` = ?", [ $id ]);
                $new   = $this->CreateObject($fetch);

                unset($article);
                $this->articles[] = $new;
                
                sort($this->articles);
            }
        }
    }

    public function LoadArticles() {
        $articles = $this->DataBase->Get("SELECT * FROM `articles`");
        sort($articles);
    }

    public function Get($id) {
        $this->PreloadArticle($id);

        return $this->articles[$id];
    }

    public function GetLastAdded() {
        $last = $this->DataBase->Get("SELECT * FROM articles");
        try {
            return \end($last);
        } catch(Exception $e) {
            return null;
        }
    }

    public function Create($data) {
        $title            = $data['title'];
        $content          = $data['content'];
        $image            = $data['image'];
        $category         = $data['category'];
        $comments_enabled = $data['comments_enabled'];
        $author_id        = $data['author_id'];

        $insert = $this->DataBase->DoQuery("INSERT INTO `articles` VALUES (NULL, ?, ?, ?, 1, ?, current_timestamp(), ?, ?)", [ $title, $content, $image, $category, $comments_enabled, $author_id]);

        if(!$this->DataBase->ErrorCode()) {
            return false;
        }
        
        return $this->GetLastAdded();
    }
    
    public function ChangeData($id, $data) {
        if(is_null($data) || is_null($id)) {
            return false;
        }

        $title            = $data['title'];
        $content          = $data['content'];
        $image            = $data['image'];
        $category         = $data['category'];
        $comments_enabled = $data['comments_enabled'];
        $active           = $data['active'];

        $data_used = [
            'title'            => $title            ?? null,
            'content'          => $content          ?? null,
            'image'            => $image            ?? null,
            'category'         => $category         ?? null,
            'comments_enabled' => $comments_enabled ?? null,
            'active'           => $active           ?? null
        ];

        foreach($data_used as $key => $value) {
            if(is_null($value)) {
                break;
            }

            $update = $this->DataBase->DoQuery("UPDATE `articles` SET " . $key . " = ? WHERE `id` = ?", [ $value ]);
        }

        if(!$this->DataBase->ErrorCode()) {
            return false;
        }

        return true;
    }

    public function Remove($id) {
        if(is_null($id)) {
            return false;
        }

        $delete = $this->DataBase->DoQuery("DELETE FROM `articles` WHERE `id` = ?", [ $id ]);
        
        if(!$this->DataBase->ErrorCode()) {
            return false;
        }

        return true;
    }
}


?>