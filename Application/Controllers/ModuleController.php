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
		$products = [];

		foreach($rows as $row) {
			/*$id = $row['id']; //Get rotue address of product: https://funnysite.com/product.php?id=42 -> https://funnysite.com/funny-product
			$route_address = ""; 
			$resultA = $this->DataBase->DoQuery("SELECT * FROM pages WHERE path=?", [ 'Product.php?id=' . $id ]);
			
			if($resultA->num_rows === 1) {
				$fetch = $resultA->fetch_assoc();
				$route_address = $fetch['route address'];

			}

			$row['route address'] = $route_address;
			*/
			
			$products[] = $row;
		}

		
		$this->View->AddData('products', $products);
		
	}

	

	
}

?>