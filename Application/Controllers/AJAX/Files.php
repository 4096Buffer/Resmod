<?php 
	
namespace Code\Controllers\AJAX;


class Files extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth']);
	}
    
    
    public function UploadFile() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized to upload a file');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        if(is_null($data['name'])) {
            $this->RequestHelper->SendJsonData(false, null, 'File name is null');
            return;
        }

        if(is_null($data['dir'])) {
            $this->RequestHelper->SendJsonData(false, null, 'Directory is null');
            return;
        }

        $name = $data['name'];
        $dir  = $data['dir'];

        if(!array_key_exists($name, $_FILES)) {
            $this->RequestHelper->SendJsonData(false, null, 'Invalid file name');
            return;
        }

        $file = $_FILES[$name];
        
        $file_name = $file['name'];
        $file_tmp  = $file['tmp_name'];
        $split_ext = explode('.', $file_name);
        $file_ext  = end($split_ext);
        $new_name  = md5(uniqid('', true)) . '.' . $file_ext;
        $dest      = UPLPATH . '/' . $dir . '/' . $new_name ;

        if(!move_uploaded_file($file_tmp, $dest)) {
            $this->RequestHelper->SendJsonData(false, null, 'Error while moving uploaded file');
            return;
        }

        $this->RequestHelper->SendJsonData(true, [ 'dest' => $dest ]);
    }
    
}



?>