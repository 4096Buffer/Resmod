<?php 

namespace Code\Libraries\Variables\Types;

class LinkType extends \Code\Libraries\Variables\ModuleVariableObject {
    
    /**
     * Link format
     * {
     *  text : 'Click on dat link',
     *  id_page : 2,
     *  outside : false,
     *  external_url : 'http://example.com'
     * }
     */

    private function JSONGetValue(string $key) {
        try {
            if(is_null($key)) {
                return null;
            }

            $asarray = json_decode($this->GetCValue(), true);
            
            if(array_key_exists($key, $asarray)) {
                return $asarray[$key];
            }

            return null;
        } catch(\Exception $e) {
            return null;
        }
    }

    public function Render() {
        $this->EchoContent($this->GetHTML());
    }
    
    private function GetCValue() {
        $value = $this->GetValue();
        if(is_null($value)) {
            $value =$this->GetDefValue();
        }

        return $value;
    }

    private function GetPage($id) {
        $page = $this->DataBase->GetFirstRow("SELECT * FROM pages WHERE id = ?", [ $id ]);
        return $page;
    }

    public function GetHTML() {
        $inner  = $this->JSONGetValue('text');
        $out    = $this->JSONGetValue('outside');
        $exlink = $this->JSONGetValue('external_url');
        $page   = $this->GetPage($this->JSONGetValue('id_page'));
        $plink  = $page['route address'] ?? null;
        $link   = $plink ?? $exlink;

        $args = [
            [ 'href' => $link ]
        ];

        if($out == true) {
            $args[] = [
                'target' => '_blank'
            ];
        }

        $value = $this->HTML->CreateHTMLElement('a', $inner, $args);

        return $value;
    }
}


?>