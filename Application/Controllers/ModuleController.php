<?php 
	
namespace Code\Controllers;

class ModuleController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View']);
	}

	public function GetProducts() {
		$result = $this->DataBase->DoQuery("SELECT * FROM products");
		$rows   = $this->DataBase->FetchRows($result);
		$products = $rows;
		
		$this->View->AddData('products', $products);
		
	}

	

	
}

?>