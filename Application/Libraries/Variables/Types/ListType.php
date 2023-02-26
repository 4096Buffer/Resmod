<?php 

namespace Code\Libraries\Variables\Types;

class ListType extends \Code\Libraries\Variables\ModuleVariableObject {

    public function GetValue() {
        return $this->value;
    }

    public function EchoValue() {
        $this->EchoContent($this->value);
    }
    
    public function FormatArray() {
        $value = $this->value;
        $value = json_decode($value, true);
        
        return $value;
    }
}


?>