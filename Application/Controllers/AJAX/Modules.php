<?php 
	
namespace Code\Controllers\AJAX;

/**
 * AJAX class
 * Page ajax controler
*/

class Modules extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'RequestHelper', 'Auth', 'Module']);
	}

    private function GetLastModuleAdded() {
        $modules = $this->DataBase->Get("SELECT * FROM modules_added ORDER BY sort ASC");
        return \end($modules);
    }

    public function AddModule() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();
        
        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $pid = $data['page_id'] ?? null;
        $mid = $data['module_id'] ?? null;

        $def_module    = $this->DataBase->GetFirstRow("SELECT * FROM modules WHERE id = ?", [ $mid ]);
        $def_variables = $this->DataBase->Get("SELECT * FROM modules_variables WHERE module_id = ?", [ $def_module['id'] ]);

        $last_module   = $this->GetLastModuleAdded();
        $module_add    = $this->DataBase->DoQuery("INSERT INTO modules_added VALUES(NULL, ?, ?, ?, 1, ?, ?)", [ $mid, $pid, $last_module['sort'] + 1, 0, '[]' ]);
        $module_add    = $this->GetLastModuleAdded();

        foreach($def_variables as $dv) {
            $result = $this->DataBase->DoQuery("INSERT INTO modules_variables_values VALUES (NULL, NULL, ?, ?)", [ $dv['id'], $module_add['id'] ]);
        }

        if($this->DataBase->ErrorCode() != 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
            return;
        }

        $this->RequestHelper->SendJsonData(true, $module_add);
    }

    public function GetModuleHTML() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();
        
        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $idm = $data['idm'] ?? null;
        $idp = $data['idp'] ?? null;

        $html = $this->Module->GetModuleHTML($idm, $idp);

        if(!is_null($html) || $html != '') {
            $this->RequestHelper->SendJsonData(true, $html);
        } else {
            $this->RequestHelper->SendJsonData(false, null, 'HTML is null');
        }
        
    }

    public function SaveCMS() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();
        
        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $saveData = $data['saveData'] ?? null;

        if(is_null($saveData)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }
        $saveData = json_decode($saveData, true);
        
        foreach($saveData as $module) {
            foreach($module['variables'] as $variable) {
                
                if($variable['variable']['oldValue'] == $variable['variable']['value']) {
                    continue;
                }

                $update_result = $this->DataBase->DoQuery("UPDATE modules_variables_values SET value = ? WHERE id = ?", [ $variable['variable']['value'],  $variable['variable']['id'] ]);

                if($this->DataBase->ErrorCode() != 0) {
                    $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error!');
                    return;
                }
            }
        }

        $this->RequestHelper->SendJsonData(true);
    }

    public function EditModuleVariables() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $variables = $data['variables'] ?? null;
        $module_id = $data['module_id'] ?? null;

        foreach($variables as $key => $value) {
            if($value) {
                $variable = $this->DataBase->GetFirstRow("SELECT mvv.*, mv.title, mv.name, mv.default_value, mv.type FROM modules_variables_values mvv INNER JOIN modules_variables mv ON mvv.id_variable = mv.id WHERE mv.name = ? AND mvv.id_module = ?", [ $key, $module_id ]);
                
                $update_result = $this->DataBase->DoQuery("UPDATE modules_variables_values SET value = ? WHERE id = ?", [ $value, $variable['id'] ]);
            }
        }

        if($this->DataBase->ErrorCode() != 0) {
            $this->RequestHelper->SendJsonData(false, null, 'Unknown DB error');
        }

        $this->RequestHelper->SendJsonData(true);
        
    }

    public function DeleteModule() {
        if(!$this->Auth->IsAuth()) {
            $this->RequestHelper->SendJsonData(false, null, 'User is not authorized');
            return;
        }

        $data = $this->RequestHelper->GetObjectFromJson();

        if(is_null($data)) {
            $this->RequestHelper->SendJsonData(false, null, 'No data has been provided');
            return;
        }

        $module_id = $data['module_id'] ?? null;

        $module = $this->DataBase->GetFirstRow("SELECT * FROM modules_added WHERE id = ?", [ $module_id ]);

        if(is_null($module)) {
            $this->RequestHelper->SendJsonData(false, null, 'Module doesnt exists');
            return;
        }

        $result_del = $this->DataBase->DoQuery("DELETE FROM modules_added WHERE id = ?", [ $module_id ]);

        $vvalues    = $this->DataBase->Get("SELECT * FROM modules_variables_values WHERE id_module = ?", [ $module_id ]);

        foreach($vvalues as $vv) {
            $result_del_vv = $this->DataBase->DoQuery("DELETE FROM modules_variables_values WHERE id = ?", [ $vv['id'] ]);
        }

        $this->RequestHelper->SendJsonData(true);
    }
}



?>