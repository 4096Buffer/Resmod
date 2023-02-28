<?php 

namespace Code\Libraries;

/**
 * A helper for HTML actions
 */

class HTML extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();
	}
    
    public function GetText($html) {
        return strip_tags($html);
    }
    
    private function PrepareArguments(array $args) {
        $arg = '';
        
        foreach($args as $carg) {
            foreach($carg as $c => $v) {
                $arg .= $c . '="' . $v . '" ';
            }
            
        }
        
        return $arg;
    }
    
    public function CreateHTMLElement($tag, $inner, array $args = null) {
        if(is_null($tag)) {
            return '';
        }
        
        $html = '';
        $arg = '';
        
        if(!is_null($args)) {
            $arg = $this->PrepareArguments($args);
        }
        
        $html = '<' . $tag . ' ' . $arg . '>' . $inner . '</' . $tag . '>';
        
        return $html;
    }
    
    
}


?>