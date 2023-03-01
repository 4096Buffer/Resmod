<?php 
	
namespace Code\Controllers\AJAX;


class Files extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth', 'FileSystem']);
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

        $data_return = $this->FileSystem->UploadFile($name, $dir);

        $this->RequestHelper->SendJsonData($data_return['error'], $data_return['data'] ?? null, $data_return['reason'] ?? null);
    }
    
}



?>