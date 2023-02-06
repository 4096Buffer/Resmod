<?php 

namespace Code\Libraries;

/**
 * Library for adding modules to layouts
 */

class Module extends \Code\Core\BaseController {

	private $modules = [];
	private $page_id = 0;

	public function __construct($page_id = null) {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'AppendFiles']);
		$this->page_id = $page_id;
	}

	private function GetControllerPath($controller) {
		return CONSPATH . '/' . $controller . '.php';
	}

	private function GetPath($name) {
		return $name . '.php';
	}

	private function GetModules() {
		$result = $this->DataBase->DoQuery("SELECT * FROM modules_added ORDER BY sort ASC");
		$fetches = $this->DataBase->FetchRows($result);

		foreach($fetches as $fetch) {
			$fetchA = $this->DataBase->GetFirstRow("SELECT * FROM modules WHERE id=?", [ $fetch['id_module'] ]);

			$module = [
				"id"            => $fetch['id'],
				"title"         => $fetchA['title'],
				"view"          => $fetchA['view'],
				"controller"    => $fetchA['controller'],
				"action"        => $fetchA['action'],
				"active"        => $fetch['active'],
				"global"        => $fetch['global'],
				"global_except" => $fetch['global_except'],
				"id_page"       => $fetch['id_page']
			];

			$this->modules[] = $module;
		}
	}
	
	
	private function LoadModule($module) {
		if($module['controller'] != null) {
			require_once $this->GetControllerPath($module['controller']);

			$class_space = '\Code\Controllers\\' . $module['controller'];
			$class = new $class_space();

			call_user_func(array($class, $module['action']));
		}

		$mvars_c = new \Code\Libraries\Variables\ModuleVariables($module['id'], $this->page_id);
		
		$this->View->AddData('mvars', $mvars_c);
		$this->View->Load($this->GetPath($module['view']));
	}

	

	public function LoadModules() {

		$this->GetModules();

		foreach($this->modules as $module) {
			$found = false;
			if($module['active'] == 1) {
				if($module['global'] == 1) {
					$except = $module['global_except'];
					if($except != '[]' || $except != '') {
						$r = str_replace('[' , '', $except);
						$r = str_replace(']', '', $r);
						$e = explode(',', $r);
						
						foreach($e as $v) {
							if($v == $this->page_id) {
								$found = false;
								break;
							}
						}

					} else {
						$found = true;
					}
				}

				if($module['id_page'] == $this->page_id) {
					$found = true;
				}
				
			}

			if($found) {
				$this->LoadModule($module);
			}
		}

	}


}

?>