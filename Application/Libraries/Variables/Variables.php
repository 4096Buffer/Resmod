<?php 

namespace Code\Libraries;

/**
 * Library for getting the PAGE variables
 */

class Variables extends \Code\Core\BaseController {

	private $variables = [];
	private $page_id;

	public function __construct($id_page = null) {
		parent::__construct();

		$this->LoadLibrary('DataBase');
        
		$this->page_id = $id_page;
        

	}


	private function FetchVariables() {
		$result = $this->DataBase->DoQuery("SELECT * FROM variables");
		$fetches = $this->DataBase->FetchRows($result);
		
		foreach($fetches as $fetch) {
			$this->variables[] = [
				"id"      => $fetch['id'],
				"name"    => $fetch['name'],
				"value"   => $fetch['value'],
				"deleted" => $fetch['deleted'],
				"global"  => $fetch['global'],
				"id_page" => $fetch['id_page']
			];

		}

	}

	public function Get($name) {

		if(count($this->variables) == 0) {
			$this->FetchVariables();
		}
        
		foreach($this->variables as $variable) {
            
            if($variable["name"] == $name) {
                
				if($variable["id_page"] == $this->page_id) {
					return $variable['value'];

				} else {
					if($variable["global"] == 1) {
						return $variable['value'];
					}
				}
			}
		}

		return null;
	}


}

?>