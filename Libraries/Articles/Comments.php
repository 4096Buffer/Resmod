<?php 

namespace Code\Libraries\Articles;

class Comments extends \Code\Core\BaseController {
    private $comments;
    private $id_parent;

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'RequestHelper']);
        $this->id_parent = 1;
	}

    public function SetIdParent($id) {
        $this->id_parent = $id;
    }

    public function PreloadComment($id) {
        foreach($this->comments as $comment) {
            if($comment['id'] == $id) {
                $fetch = $this->DataBase->Get("SELECT * FROM `articles_comments` WHERE `id` = ? AND `article_id` = ?", [ $id, $this->id_parent ]);

                unset($comment);
                $this->comments[] = $fetch;
                
                sort($this->comments);
            }
        }
    }

    public function LoadComments() {
        $comments = $this->DataBase->Get("SELECT * FROM `articles_comments` WHERE `article_id` = ?", [ $this->id_parent ]);
        sort($comments);
    }

    public function Get($id) {
        $this->PreloadComment($id);

        return $this->comments[$id];
    }

    public function GetLastAdded() {
        $last = $this->DataBase->Get("SELECT * FROM `articles_comments` WHERE `id_article` = ?", [ $this->id_parent ]);
        try {
            return \end($last);
        } catch(Exception $e) {
            return null;
        }
    }

    public function Create($data) {
        $content   = $data['content'];
        $id_parent = $data['id_article'];
        $id_author = $data['id_author'];

        $insert = $this->DataBase->DoQuery("INSERT INTO `articles_comments` VALUES (NULL, ?, 0, 0, 1, current_timestamp(), ?, ?)", [ $content, $id_parent, $id_author ]);

        if(!$this->DataBase->ErrorCode()) {
            return false;
        }
        
        return $this->GetLastAdded();
    }
    
    public function ChangeData($id, $data) {
        if(is_null($data) || is_null($id)) {
            return false;
        }

        $content = $data['content'];
        $active  = $data['active'];

        $data_used = [
            'content'          => $content          ?? null,
            'active'           => $active           ?? null
        ];

        foreach($data_used as $key => $value) {
            if(is_null($value)) {
                break;
            }

            $update = $this->DataBase->DoQuery("UPDATE `articles_comments` SET " . $key . " = ? WHERE `id` = ? AND `article_id` = ?", [ $value, $this->id_parent]);
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

        $delete = $this->DataBase->DoQuery("DELETE FROM `articles_comments` WHERE `id` = ? AND `id_article` = ?", [ $id, $this->id_parent ]);
        
        if(!$this->DataBase->ErrorCode()) {
            return false;
        }

        return true;
    }
}


?>