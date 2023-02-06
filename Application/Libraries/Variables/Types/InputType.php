<?php 

namespace Code\Libraries\Variables\Types;

class InputType extends \Code\Libraries\Variables\ModuleVariableObject {
    
    public function Render() {
        $this->EchoContent($this->GetHTML());
    }
    
    public function GetHTML() {
        
        $value = $this->GetValue();
        if(is_null($value)) {
            $value =$this->GetDefValue();
        }
        
        return $value;
    }
}


?>