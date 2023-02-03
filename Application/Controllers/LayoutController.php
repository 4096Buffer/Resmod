<?php 
	
namespace Code\Controllers;

/**
 * Controller for layouts
 * Used for getting data from database etc.
*/

class LayoutController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'Variables']);
	}

	/**
	 * Here add actions
	*/

	public function DashboardAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $_SESSION['login'] ?? null;
		$avatar  = 'Uploads/Avatars/' . $_SESSION['avatar'] ?? null;
		$name    = $_SESSION['name'];
		$surname = $_SESSION['surname'];
		$email   = $_SESSION['email'];

		$database_info  = $this->DataBase->GetMysqlServerInfo();
		$php_info       = \phpversion();
		$admin_users    = 0;

		$result_ausers = $this->DataBase->DoQuery('SELECT * FROM admin_users');
		$rows_ausers   = $this->DataBase->FetchRows($result_ausers);
		
		$rows_users = null;

		if($this->Variables->Get('user_login_system_enabled') == 'true') {
			$result_users = $this->DataBase->DoQuery('SELECT * FROM users');
			$rows_users   = $this->FetchRows($rows_users);
		}
		
		$memory_usage          = \memory_get_usage(true);
		$memory_usage          = $memory_usage /= 1048576;
		$max_memory_usage      = \ini_get('memory_limit');
		$max_memory_usage_unit = \substr($max_memory_usage, -1);
		$u = strtolower($max_memory_usage_unit);

		//Delete last character that is specifing memory unit because we don't need it anymore
		$max_memory_usage = substr($max_memory_usage, 0, -1); 

		/**
		 * Check if 'memory_limit' unit isn't m if true multiply max memory usage by 1024
		 * Why? Because we are converting everything to megabytes
		 */

		if($u != 'm') {
			$max_memory_usage *= 1024;
		}

		$pages_result          = $this->DataBase->DoQuery("SELECT * FROM pages WHERE hidden = 0");
		$pages                 = $this->DataBase->FetchRows($pages_result);

		$pages_views_result    = $this->DataBase->DoQuery("SELECT * FROM pages WHERE hidden = 0 ORDER BY views ASC");
		$pages_views           = $this->DataBase->FetchRows($pages_views_result);

		$modules_result        = $this->DataBase->DoQuery("SELECT * FROM modules");
		$modules               = $this->DataBase->FetchRows($modules_result);

		$modules_groups_result = $this->DataBase->DoQuery("SELECT * FROM modules_groups");
		$modules_groups        = $this->DataBase->FetchRows($modules_groups_result);


		foreach($modules_groups as &$group) {
			$group['modules'] = [];

			foreach($modules as $module) {
				if($module['id_group'] == $group['id']) {
					$group['modules'][] = $module;
				}
			}
		}

		


		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);

		$this->View->AddData('database_info', $database_info);
		$this->View->AddData('php_info', $php_info);
		$this->View->AddData('admin_users', $rows_ausers);
		$this->View->AddData('users', $rows_users);
		$this->View->AddData('memory_usage', $memory_usage);
		$this->View->AddData('max_memory_usage', $max_memory_usage);

		$this->View->AddData('pages', $pages);
		$this->View->AddData('pages_views', $pages_views);

		$this->View->AddData('modules', $modules);
		$this->View->AddData('modules_groups', $modules_groups);
		
	}

	
}

?>