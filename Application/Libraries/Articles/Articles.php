<?php 

namespace Code\Libraries;

class Articles extends \Code\Core\BaseController {
    private $articles;

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'RequestHelper', 'FileSystem']);
        $this->LoadArticles();
	}

    public function CreateObject($article) {
        $object = new \Code\Libraries\Articles\ArticleObject($article);

        return $object;
    }

    

    public function PreloadArticle($id) {
        foreach($this->articles as $article) {
            if($article->GetId() == $id) {
                $fetch = $this->DataBase->GetFirstRow("SELECT * FROM `articles` WHERE `id` = ?", [ $id ]);
                $new   = $this->CreateObject($fetch);

                unset($article);

                $this->articles[$new->GetId()] = $new;
                
                sort($this->articles);
            }
        }
    }

    public function LoadArticles() {
        $articles = $this->DataBase->Get("SELECT * FROM `articles`");
        if(is_null($articles)) {
            $articles = [];
            return;
        }

        sort($articles);

        foreach($articles as &$article) {
            $obj = $this->CreateObject($article);

            $this->articles[$article['id']] = $obj;
        }
        
    }

    public function Get($id) {
        $this->PreloadArticle($id);

        if(!isset($this->articles[$id])) {
            $this->LoadArticles();
        }

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

    private function ClearTitle($title) {
        $alphabet = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 
            'h', 'i', 'j', 'k', 'l', 'm', 'n', 
            'o', 'p', 'q', 'r', 's', 't', 'u', 
            'w', 'x', 'y', 'z'
        ];

        $new_title = strtolower($title);

        for($i = 0; $i < strlen($new_title); $i++) {
            $char = $new_title[$i];
                        
            if(array_key_exists($char, $alphabet) && is_numeric($char)) {
                $new_title[$char] = '-';
            }
        }

        return $new_title;
    }
    
    private function CreateLink($id, $type) {
        $link = '';

        switch ($type) {
            case '1':
                $link = '/articles/' . $id;
                break;
            case '2':
                $link = '/articles?id=' . $id;
                break;
            case '3':
                $article = $this->Get($id);
                $title = $this->ClearTitle($article->GetTitle());
                $link = '/articles/' . $title;
                break;
            default:
                $link = '';
                break;
        }

        return $link;
    } 

    public function Create($data) {
        $title            = $data['title'];
        $content          = $data['content'];
        $link_type        = $data['link_type'];
        $state            = $data['state'];
        $category         = $data['category'];
        $comments_enabled = $data['comments_enabled'];
        $author_id        = $data['author_id'];
        
        if(!array_key_exists('image', $_FILES)) {
            return [ 'error' => true, 'reason' => 'Image(Thumbnail) not found!' ];
        }

        $upload = $this->FileSystem->UploadFile('image', 'Thumbnails');

        if($upload['error']) {
            $this->RequestHelper->SendJsonData(false, null, $upload['reason']);
            return;
        }

        $insert = $this->DataBase->DoQuery("INSERT INTO `articles` VALUES (NULL, ?, ?, ?, ?, 1, ?, current_timestamp(), ?, ?, ?)", [ $title, $content, basename($upload['data']['dest']), '', $category, $comments_enabled, $state, $author_id]);

        if($this->DataBase->ErrorCode() !== 0) {
            return [ 'error' => true, 'reason' => 'Unknown DB Error!' ];
        }
        
        $id   = $this->GetLastAdded()['id'];
        $link = $this->CreateLink($id, $link_type);

        $this->DataBase->DoQuery("UPDATE `articles` SET `link` = ? WHERE `id` = ?", [ $link, $id ]);
        
        if($this->DataBase->ErrorCode() !== 0) {
            return [ 'error' => true, 'reason' => 'Unknown DB Error!' ];
        }

        return [ 'error' => false, 'data' => $this->Get($id)];
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
                continue;
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