<?php 
	
namespace Code\Libraries;


class FileSystem extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth']);
	}
    
    
    public function UploadFile($name, $dir) {
        if(!array_key_exists($name, $_FILES)) {
            return ['error' => true, 'reason' => 'Invalid file name'];
        }

        $file = $_FILES[$name];
        
        $file_name = $file['name'];
        $file_tmp  = $file['tmp_name'];
        $split_ext = explode('.', $file_name);
        $file_ext  = end($split_ext);
        $new_name  = md5(uniqid('', true)) . '.' . $file_ext;
        $dest      = UPLPATH . '/' . $dir . '/' . $new_name ;

        if(!move_uploaded_file($file_tmp, $dest)) {
            return ['error' => true, 'reason' => 'Error while moving uploaded file'];
        }

        return ['error' => false, 'data' => ['dest' => $dest]];
    }
    
}



?>