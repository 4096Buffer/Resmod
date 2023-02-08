<?php 

namespace Code\Libraries\Variables;

/**
 * Module variables class for getting the object of variable
*/

class ModuleVariables extends \Code\Core\BaseController {

	private $variables = [];
	private $module_id;
	private $page_id;

	public function __construct($id_module = null, $id_page = null) {
		parent::__construct();

		$this->LoadLibrary(['DataBase']);

		$this->module_id = $id_module;
		$this->page_id      = $id_page;

		if(!is_null($id_module) && !is_null($id_page)) {
			$this->FetchVariables();
		}
		
	}

	//private function GetDefVariable($id) {
	//	$result = $this->DataBase->DoQuery("SELECT * FROM modules_variables WHERE id=?", [ $id ]);
	//	echo $this->DataBase->Error();
	//	$fetch =  $result->fetch_assoc();

	//	return $fetch;
	//}

	private function FetchVariables() {
		$result = $this->DataBase->DoQuery("
			SELECT
			mvv.*, mv.*
			FROM
			modules_variables_values 
			mvv INNER JOIN modules_variables mv 
			ON mv.id = mvv.id_variable WHERE mvv.id_module=?", [ $this->module_id ]);
		$fetches = $this->DataBase->FetchRows($result);

		foreach($fetches as $fetch) {
			//$fetch['name']          = $this->GetDefVariable($fetch['id_variable'])['name'];
           // $fetch['default_value'] = $this->GetDefVariable($fetch['id_variable'])['default_value'];
            //$fetch['type']       = $this->GetDefVariable($fetch['id_variable'])['type'];
            //$fetch['id_page']       = $this->GetDefVariable($fetch['id_variable'])['id_page'];
            
			$this->variables[] = $fetch;
		}
	}
    
    private function CreateObjectValue($variable) {
        $object = null;
        $type = $variable['type'];

        $object_space = '\Code\Libraries\Variables\Types\\' . $type;
        if(class_exists($object_space)) {
            $object = new $object_space($variable);
        } 
        
        return $object;
    }
    
	public function Get($name) {
		$find = null;

		foreach($this->variables as $variable) {
			if($variable['name'] == $name) {
				$find = $this->CreateObjectValue($variable);
			}
		}

        if(is_null($find)) {
			return null;
        }
        
		return $find;
	}

}

?>