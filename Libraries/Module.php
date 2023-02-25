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

		$this->LoadLibrary(['DataBase', 'View', 'AppendFiles', 'HTML', 'RequestHelper']);
		$this->page_id = $page_id;

		$this->FetchModules();
		
	}

	private function GetControllerPath($controller) {
		return CONSPATH . '/' . $controller . '.php';
	}

	private function GetPath($name) {
		return $name . '.php';
	}

	public function GetModules() {
		$modules  = $this->DataBase->Get("SELECT * FROM modules_added");
		
		if(!is_null($modules) && gettype($modules) == 'array') {
			return $modules;
		}

		return null;
	}

	public function LoadSingleModule($id) {
		$found = null;

		foreach($this->modules as $module) {
			if($module['id'] == $id) {
				if($module['active'] == 0) continue;
				$found = $module;
			}
		}

		$this->LoadModule($found);
	}

	private function FetchModules() {
		$fetches = $this->DataBase->Get("SELECT * FROM modules_added ORDER BY sort ASC");

		if(!$fetches) {
			$this->modules = [];
			return;
		}

		foreach($fetches as $fetch) {
			$fetchA = $this->DataBase->GetFirstRow("SELECT * FROM modules WHERE id = ?", [ $fetch['id_module'] ]);

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
	
	public function GetModuleHTML($id, $page_id) {
		$module = null;
		
		foreach($this->modules as $m) {
			if($m['id'] == $id) {
				$module = $m;
			}
		}

		if(is_null($module)) {
			return null;
		}

		$view_mode = $this->RequestHelper->GetViewMode();

		if($module['controller'] != null) {
			require_once $this->GetControllerPath($module['controller']);

			$class_space = '\Code\Controllers\\' . $module['controller'];
			$class = new $class_space();

			call_user_func(array($class, $module['action']));
		}

		
		$mvars_c = new \Code\Libraries\Variables\ModuleVariables($module['id'], $page_id);
		
		$this->View->AddData('mvars', $mvars_c);
		
		$html = '';
		
		$html .= '<cmsmodule module-id="'. $module['id'] . '">';
			//$html .= '<div class="module-live-edit-icon copy">Copy</div>';
				//echo '<a href="/modules-edit?id='. $module['id'] . '&redirect-back=' . $redirect_back . '"><div class="module-live-edit-icon edit">Edit</div></a>';
			$html .= '<div class="module-live-edit-icon delete">D</div>';
			$html .= '<div class="module-edit-content">';
				$html .= $this->View->Get($this->GetPath($module['view']));
			$html .= '</div>';
		$html .= '</cmsmodule>';
		

		return $html;
	}

	private function LoadModule($module) {
		$view_mode = $this->RequestHelper->GetViewMode();
		if($module['controller'] != null) {
			require_once $this->GetControllerPath($module['controller']);

			$class_space = '\Code\Controllers\\' . $module['controller'];
			$class = new $class_space();

			call_user_func(array($class, $module['action']));
		}

		$mvars_c = new \Code\Libraries\Variables\ModuleVariables($module['id'], $this->page_id);
		
		$this->View->AddData('mvars', $mvars_c);
		
		if($view_mode[0] == 'LiveEdit') {
			$redirect_back = $this->RequestHelper->GetHref();

			echo '<cmsmodule module-id="'. $module['id'] . '">';
				//echo '<div class="module-live-edit-icon copy">Copy</div>';
				//echo '<a href="/modules-edit?id='. $module['id'] . '&redirect-back=' . $redirect_back . '"><div class="module-live-edit-icon edit">Edit</div></a>';
				echo '<div class="module-live-edit-icon delete">D</div>';
				echo '<div class="module-edit-content">';
					$this->View->Load($this->GetPath($module['view']));
				echo '</div>';
			echo '</cmsmodule>';
		} else {
			$this->View->Load($this->GetPath($module['view']));
		}
	}

	

	public function LoadModules() {

		foreach($this->modules as $module) {
			$found = false;

			if($module['active'] == 1) {
				if($module['global'] == 1) {
					$except = $module['global_except'];
					
					if($except != '[]' || $except != '') {
						$e = json_decode($except);
						
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