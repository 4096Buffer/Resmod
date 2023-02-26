<?php 

namespace Code\Libraries;

/**
 * Library for adding files to <head> tag
 * CSS/JS
 */

class AppendFiles extends \Code\Core\BaseController {
	private $css  = [];
	private $js   = [];
	private $data = [];
	private $view_scripts = [];


	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'HTML', 'Auth']);
	}

	private function GetAllFiles() {
		$this->css = [];
		$this->js  = [];

		$fetches = $this->DataBase->Get("SELECT * FROM append_files ORDER BY sort ASC");
		
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

		$vscripts = $this->DataBase->Get("SELECT * FROM view_scripts");

		foreach($vscripts as $vscript) {
			$html = '<script type="text/javascript" src="' . JSPATH . '/ViewScripts/' . $vscript['path'] . '"></script>';
			$vscript['html'] = $html;
			$this->view_scripts[] = $vscript;
		}
	}

	public function Append() {
		$this->GetAllFiles();

		$mains   = [];
        $admins  = [];

		foreach($this->css as $file) {
			if($file['isadmin'] == '1') {
				$admins[] = $file;
				continue;
			}
			
			$mains[] = $file;
		}

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

		if($this->Auth->IsAuth()) {
            foreach($admins as $admin) {
                echo $admin['html'];
            }
        } else {
			foreach($this->view_scripts as $vscript) {
				if($vscript['enabled'] == 1) {
					echo $vscript['html'];
				}
			}
		}

		$script = 'Helpers.Data.AddData(' . json_encode($this->data) . ')';
		echo $this->HTML->CreateHTMLElement('script', $script, [['type' => 'text/javascript']]);
	}

	public function AddData($key, $value) {
		$this->data[$key] = $value;
	}
}


?>