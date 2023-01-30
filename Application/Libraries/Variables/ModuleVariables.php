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
		$this->page_id   = $id_page;


	}

	private function GetDefVariable($id) {
		$result = $this->DataBase->DoQuery("SELECT * FROM modules_variables WHERE id=?", [ $id ]);
		echo $this->DataBase->Error();
		$fetch =  $result->fetch_assoc();

		return $fetch;
	}


	private function FetchVariables() {
		$result = $this->DataBase->DoQuery("SELECT * FROM modules_variables_values WHERE id_module=?", [ $this->module_id ]);

		$fetches = $this->DataBase->FetchRows($result);

		foreach($fetches as $fetch) {
			$fetch['name']          = $this->GetDefVariable($fetch['id_variable'])['name'];
            $fetch['default_value'] = $this->GetDefVariable($fetch['id_variable'])['default_value'];
            $fetch['id_type']       = $this->GetDefVariable($fetch['id_variable'])['id_type'];
            //$fetch['id_page']       = $this->GetDefVariable($fetch['id_variable'])['id_page'];
            
			$this->variables[] = $fetch;
		}

	}
    
    private function GetVariableType($id) {
        $row = $this->DataBase->GetFirstRow("SELECT * FROM variable_types WHERE id = ?", [ $id ]);
        return $row['name'];
    }
    
    private function CreateObjectValue($variable) {
        $object = null;
        
        $type = $this->GetVariableType($variable['id_type']);
        
        $object_space = '\Code\Libraries\Variables\Types\\' . $type;
        if(class_exists($object_space)) {
            $object = new $object_space($variable);
        }
        
        return $object;
    }
    
	public function Get($name) {
		$find = null;

		if(count($this->variables) == 0) {
			$this->FetchVariables();
		}

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