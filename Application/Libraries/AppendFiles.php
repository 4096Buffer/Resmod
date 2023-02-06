<?php 

namespace Code\Libraries;

/**
 * Library for adding files to <head> tag
 * CSS/JS
 */

class AppendFiles extends \Code\Core\BaseController {
	private $css = [];
	private $js = [];


	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'HTML']);
	}

	private function GetAllFiles() {
		$this->css = [];
		$this->js  = [];

		$result = $this->DataBase->DoQuery("SELECT * FROM append_files ORDER BY sort ASC");
		$fetches = $this->DataBase->FetchRows($result);
		

		foreach($fetches as $fetch) {
			switch ($fetch['type']) {
				case 'Script':
					$html = '<script type="text/javascript" src="'. JSPATH . '/' . $fetch['path'] . '" ></script>';
					$fetch['html'] = $html;
					$this->js[] = $fetch;
					
					break;
				default:
					$html = '<link rel="stylesheet" href="' . CSSPATH . '/' . $fetch['path'] . '" />';
					$fetch['html'] = $html;
					$this->css[] = $fetch;
					break;
			}
		}
	}

	public function Append($is_admin) {
		$this->GetAllFiles();

		foreach($this->css as $file) {
			echo $file['html'];
		}

		$mains   = [];
        $admins  = [];


		foreach($this->js as $file) {
			if($file['isadmin'] == '1') {
				$admins[] = $file;
				continue;
			}

			$mains[] = $file;
        }

        

        foreach($mains as $main) {
			echo $main['html'];
		}

		if($is_admin) {
            foreach($admins as $admin) {
                echo $admin['html'];
            }
        }
		
		$this->HTML->Create//dodaj skrypt

	}
}


?>