<?php 
	
namespace Code\Controllers;

/**
 * Controller for modules
 * Used for getting data from database etc.
*/

class ModuleController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View']);
	}

	/**
	 * Here add actions
	*/
}

?>