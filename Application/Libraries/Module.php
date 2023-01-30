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
		$result = $this->DataBase->DoQuery("SELECT * FROM modules_added WHERE id_page=? ORDER BY sort ASC", [ $this->page_id ]);
		$fetches = $this->DataBase->FetchRows($result);

		foreach($fetches as $fetch) {
			$resultA = $this->DataBase->DoQuery("SELECT * FROM modules WHERE id=?", [ $fetch['id_module'] ]);
			$fetchA  = $resultA->fetch_assoc();

			$module = [
				"id"         => $fetch['id'],
				"title"      => $fetchA['title'],
				"view"       => $fetchA['view'],
				"controller" => $fetchA['controller'],
				"action"     => $fetchA['action'],
				"active"     => $fetch['active']
			];

			$this->modules[] = $module;
		}
	}
	
	public function LoadModules() {

		$this->GetModules($this->page_id);

		foreach($this->modules as $module) {
			if($module['active'] == 1) {
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
		}

	}


}

?>