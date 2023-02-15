<?php 
namespace Code\Libraries\Variables;

/**
 * Library for variable objects
 * Variables/Types/....
*/

class ModuleVariableObject extends \Code\Core\BaseController {
    
    protected $id;
    protected $name;
    protected $default_value;
    protected $type;
    protected $module_id;
    protected $deleted;
    //protected $page_id;
    protected $value;
    
    
	public function __construct($variable = null) {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'Auth', 'HTML', 'RequestHelper']);
        
        $this->id            = $variable['id'];
        $this->name          = $variable['name'];
        $this->default_value = $variable['default_value'];
        $this->type          = $variable['type'];
        $this->module_id     = $variable['id_module'];
        $this->deleted       = 0;
       // $this->page_id       = $variable['id_page'];
        $this->value         = $variable['value'];
	}
    
    public function EchoContent($content) {
        if(!$this->Auth->IsAuth()) {
            echo $content;
        } else {
            $html_args = [
                [
                    'name'  => 'var-name',
                    'value' => $this->name
                ],
                [
                    'name'  => 'type',
                    'value' => 'text'
                ]
            ];
            
            
            echo $this->HTML->CreateHTMLElement('cmselement', $content, $html_args);
        }
    }
    
    
    //Get, set
    
    public function GetId() {
        return $this->id;
    }
    
    public function GetName() {
        return $this->name;
    }
    
    public function GetDefValue() {
        return $this->default_value;
    }
    
    public function GetTypeId() {
        return $this->type_id;
    }
    
    public function GetModuleId() {
        return $this->module_id;
    }
    
    public function GetDeleted() {
        return $this->deleted;
    }
    
    public function GetPageId() {
        return $this->page_id;
    }
    
    public function GetValue() {
        return $this->value;
    }
}

?>