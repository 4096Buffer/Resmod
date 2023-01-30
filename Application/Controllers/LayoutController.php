<?php 
	
namespace Code\Controllers;

/**
 * Controller for layouts
 * Used for getting data from database etc.
*/

class LayoutController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View']);
	}



	/**
	 * Here add actions
	*/
}

?>