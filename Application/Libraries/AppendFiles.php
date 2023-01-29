<?php 

namespace Code\Libraries;

class AppendFiles extends \Code\Core\BaseController {
	private $css = [];
	private $js = [];


	public function __construct() {
		parent::__construct();

		$this->LoadLibrary('DataBase');
	}

	private function GetAllFiles() {
		$this->css = [];
		$this->js  = [];

		$result = $this->DataBase->DoQuery("SELECT * FROM append_files");
		$fetches = $this->DataBase->FetchRows($result);
		

		foreach($fetches as $fetch) {

			switch ($fetch['type']) {
				case 'Script':
					$html = '<script type="text/javascript" src="'. JSPATH . DIRECTORY_SEPARATOR . $fetch['path'] . '" ></script>';
					$this->js[] = $html;

					break;
				
				default:
					$html = '<link rel="stylesheet" href="' . CSSPATH . DIRECTORY_SEPARATOR . $fetch['path'] . '" />';
					$this->css[] = $html;
					
					break;
			}

		}
	}

	public function Append($is_admin) {
		$this->GetAllFiles();

		foreach($this->css as $file) {
			echo $file;
		}

		$helpers = [];
		$mains   = [];
        $admins  = [];


		foreach($this->js as $file) {

			if(\strpos($file, 'Helpers') !== false) {
				$helpers[] = $file;
				continue;
			} else if(\strpos($file, 'Admin') !== false) {
                $admins[] = $file;
                continue;
            }

			$mains[] = $file;
        }

		foreach($helpers as $helper) {
			echo $helper;
		}
        
        if($is_admin) {
            foreach($admins as $admin) {
                echo $admin;
            }
        } else {
            foreach($mains as $main) {
			 echo $main;
		    }
        }

	}
}


?>