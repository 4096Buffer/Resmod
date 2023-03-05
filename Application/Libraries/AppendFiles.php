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
			$html_js  = '<script type="text/javascript" src="' . JSPATH . '/ViewScripts/' . $vscript['script_path'] . '"></script>';
			$html_css = '<link rel="stylesheet" href="' . CSSPATH . '/ViewStyles/' . $vscript['style_path'] . '" />';
			
			$vscript['html_js'] = $html_js;
			$vscript['html_css'] = $html_css;
			
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

			foreach($this->view_scripts as $vscript) {
				if($vscript['admin'] == 0) continue;
				if($vscript['enabled'] == 1) {
					if(!file_exists(CSSPATH . '/ViewStyles/' . $vscript['style_path'])) return; //CSS file for vscripts are optional
					echo $vscript['html_css'];
				}
			}

			foreach($this->view_scripts as $vscript) {
				if($vscript['admin'] == 0) continue;
				if($vscript['enabled'] == 1) {
					echo $vscript['html_js'];
				}
			}
        } else {
			foreach($this->view_scripts as $vscript) {
				if($vscript['admin'] == 1) continue;
				if($vscript['enabled'] == 1) {
					echo $vscript['html_css'];
				}
			}

			foreach($this->view_scripts as $vscript) {
				if($vscript['admin'] == 1) continue;
				if($vscript['enabled'] == 1) {
					echo $vscript['html_js'];
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