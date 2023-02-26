<?php 
	
namespace Code\Controllers;

/**
 * Controller for layouts
 * Used for getting data from database etc.
*/

class LayoutController extends \Code\Core\BaseController {

	public function __construct() {
		parent::__construct();

		$this->LoadLibrary(['DataBase', 'View', 'Variables', 'RequestHelper', 'Auth', 'HTML']);
	}

	/**
	 * Here add actions
	*/

	public function DashboardAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$database_info  = $this->DataBase->GetMysqlServerInfo();
		$php_info       = \phpversion();
		$admin_users    = 0;

		$rows_ausers = $this->DataBase->Get('SELECT * FROM admin_users');
		
		$rows_users = null;

		if($this->Variables->Get('user_login_system_enabled') == 'true') {
			$rows_users = $this->DataBase->Get('SELECT * FROM users');
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

		$pages                 = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0");
		$pages_views           = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0 ORDER BY views ASC");
		$modules               = $this->DataBase->Get("SELECT * FROM modules");
		$modules_groups        = $this->DataBase->Get("SELECT * FROM modules_groups");

		foreach($modules_groups as &$group) {
			$group['modules'] = [];

			foreach($modules as $module) {
				if($module['group'] == $group['name']) {
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

	public function TemplatesAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$pages             = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0");

		$pages_columns_uf  = $this->DataBase->GetColumnsNames('pages');

		$templates_rows    = $this->DataBase->Get("SELECT * FROM layouts WHERE hidden = 0");
		$templates = [];

		foreach($templates_rows as $template) {
			$templates[$template['id']] = $template;
		}
		
		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);

		$this->View->AddData('pages', $pages);
		$this->View->AddData('pages_columns', $pages_columns_uf);
		$this->View->AddData('templates', $templates);
	}

	public function ExampleShow() {
		$example_id     = $this->RequestHelper->GetQueryFragments()['id'] ?? null;
		$example_layout = $this->DataBase->GetFirstRow("SELECT * FROM layouts WHERE id = ? AND hidden = 0", [ $example_id ]);
		$view           = new \Code\Libraries\View();
		$request_helper = new \Code\Libraries\RequestHelper();

		$this->View->AddData('example_layout', $example_layout);
		$this->View->AddData('view', $view);
		$this->View->AddData('request_helper', $request_helper);
	}
	
	public function ModulesAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = trim($this->Auth->GetProfile()['login']);
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = trim($this->Auth->GetProfile()['name']);
		$surname = trim($this->Auth->GetProfile()['surname']);
		$email   = trim($this->Auth->GetProfile()['email']);

		$modules        = $this->DataBase->Get("SELECT * FROM modules");
		$modules_groups = $this->DataBase->Get("SELECT * FROM modules_groups");
		
		foreach($modules_groups as &$group) {
			$group['modules'] = [];

			foreach($modules as $module) {
				if($module['group'] == $group['name']) {
					$group['modules'][] = $module;
				}
			}
		}

		foreach($modules as &$module) {
			unset($module['view']);
			unset($module['controller']);
			unset($module['action']);
			unset($module['hidden']);
		}

		$data = [];
		$data['tables-modules-list'] = $modules;

		$script_js = 'Helpers.Data.AddData(' . json_encode($data) . ')';
		$js_add = $this->HTML->CreateHTMLElement('script', $script_js);

		$pages  = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0");
		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);

		$this->View->AddData('modules', $modules);
		$this->View->AddData('modules_groups', $modules_groups);

		$this->View->AddData('js_add', $js_add);
		$this->View->AddData('pages', $pages);
	}

	public function ModulesEdit() {
		/**
		 * Get all admin user data from session
		*/

		$login   = trim($this->Auth->GetProfile()['login']);
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = trim($this->Auth->GetProfile()['name']);
		$surname = trim($this->Auth->GetProfile()['surname']);
		$email   = trim($this->Auth->GetProfile()['email']);

		$query_string = $this->RequestHelper->GetQueryFragments();

		if(!$query_string) {
			$this->RequestHelper->Redirect('/');
		} else {
			if(!isset($query_string['id'])) {
				$this->RequestHelper->Redirect('/');
			}
		}
		
		$module_id = $query_string['id'];

		$module = $this->DataBase->GetFirstRow("
			SELECT ma.*, m.title, m.description, 
			m.view, m.controller, m.action, m.group 
			FROM modules_added ma 
			INNER JOIN modules m ON ma.id_module = m.id WHERE ma.id = ?", [ $module_id ]);
		if(!$module) {
			$this->RequestHelper->Redirect('/');
		}

		$data = [];
		$data['current-module'] = $module;

		$script_js = 'Helpers.Data.AddData(' . json_encode($data) . ')';
		$js_add = $this->HTML->CreateHTMLElement('script', $script_js);

		$query = $this->RequestHelper->GetQueryFragments();
		$redirect_back = isset($query['redirect-back']) ? $query['redirect-back'] : '/pages-list';

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
		
		$this->View->AddData('cmodule', $module);
		$this->View->AddData('js_add', $js_add);
		$this->View->AddData('redirect_back', $redirect_back);
	}

	public function LiveEdit() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$modules        = $this->DataBase->Get("SELECT * FROM modules");
		$modules_groups = $this->DataBase->Get("SELECT * FROM modules_groups");
		
		foreach($modules_groups as &$group) {
			$group['modules'] = [];

			foreach($modules as $module) {
				if($module['group'] == $group['name']) {
					$group['modules'][] = $module;
				}
			}
		}

		foreach($modules as &$module) {
			unset($module['view']);
			unset($module['controller']);
			unset($module['action']);
			unset($module['hidden']);
		}

		$data = [];
		$data['modules-list'] = $modules;

		$script_js = 'Helpers.Data.AddData(' . json_encode($data) . ')';
		$js_add = $this->HTML->CreateHTMLElement('script', $script_js);

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);

		$this->View->AddData('modules', $modules);
		$this->View->AddData('modules_groups', $modules_groups);
		$this->View->AddData('js_add', $js_add);

	}

	public function PagesListAdmin() {
		/**
		 * Get all admin user data from session
		*/
		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$pages          = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0");
		$templates_rows = $this->DataBase->Get("SELECT * FROM layouts WHERE hidden = 0");
		$templates = [];

		foreach($templates_rows as $template) {
			$templates[$template['id']] = $template;
		}

		$page_variables = [];
		$variables_g = $this->DataBase->Get("SELECT * FROM variables");

		foreach($variables_g as $vg) {
			$page_variables[$vg['id_page']][$vg['name']] = $vg;
		}

		$pages = $this->DataBase->Get("SELECT * FROM pages WHERE hidden = 0");
		
		foreach($pages as &$page) {
			$variables = $this->DataBase->Get("SELECT * FROM variables WHERE id_page = ?", [ $page['id'] ]);
			$page['variables'] = $variables;
		}

		$data = [];
		$data['pages-list'] = $pages;

		$script_js = 'Helpers.Data.AddData(' . json_encode($data) . ')';
		$js_add = $this->HTML->CreateHTMLElement('script', $script_js);

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);

		$this->View->AddData('pages', $pages);
		$this->View->AddData('templates', $templates);
		$this->View->AddData('page_variables', $page_variables);
		$this->View->AddData('js_add', $js_add);
	}

	public function AddPageAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
	}

	public function ManageUsersAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$users = $this->DataBase->Get("SELECT * FROM users");

		foreach ($users as &$user) {
			unset($user['password']);
		}

		$data = [];
		$data['users-list'] = $users;

		$script_js = 'Helpers.Data.AddData(' . json_encode($data) . ')';
		$js_add = $this->HTML->CreateHTMLElement('script', $script_js);

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
		$this->View->AddData('users', $users);

		$this->View->AddData('js_add', $js_add);
	}

	public function ManageAdminsAdmin() {
		/**
		 * Get all admin user data from session
		*/

		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$admins = $this->DataBase->Get("SELECT * FROM admin_users");

		foreach ($admins as &$admin) {
			unset($admin['password']);
		}

		$data = [];
		$data['admins-list'] = $admins;

		$script_js = 'Helpers.Data.AddData(' . json_encode($data) . ')';
		$js_add = $this->HTML->CreateHTMLElement('script', $script_js);

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
		$this->View->AddData('admins', $admins);

		$this->View->AddData('js_add', $js_add);
	}

	public function SettingsAdmin() {
		/**
		 * Get all admin user data from session
		*/
		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
	}

	public function CreateArticleAdmin() {
		/**
		 * Get all admin user data from session
		*/
		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];

		$categories = $this->DataBase->Get("SELECT * FROM articles_categories");

		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);

		$this->View->AddDatA('categories', $categories);
	}
	
	public function ManageArticlesAdmin() {
		/**
		 * Get all admin user data from session
		*/
		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];


		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
	}

	public function ManageCategoriesAdmin() {
		/**
		 * Get all admin user data from session
		*/
		$login   = $this->Auth->GetProfile()['login'];
		$avatar  = $this->Auth->GetProfile()['avatar'];
		$name    = $this->Auth->GetProfile()['name'];
		$surname = $this->Auth->GetProfile()['surname'];
		$email   = $this->Auth->GetProfile()['email'];


		/**
		 * Add all variables to the 'View' class
		*/

		$this->View->AddData('login', $login);
		$this->View->AddData('avatar', $avatar);
		$this->View->AddData('name', $name);
		$this->View->AddData('surname', $surname);
		$this->View->AddData('email', $email);
	}	

	public function Articles() {
		
	}
}

?>