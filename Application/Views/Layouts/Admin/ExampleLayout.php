<?php 
    if($example_layout == NULL) {
        $request_helper->Redirect('/404');
    }

    if($example_layout['controller'] != null) {
        require_once CONSPATH . '/' . ($example_layout['controller']) . '.php';
        
        $class_space = '\Code\Controllers\\' . $example_layout['controller'];
        $class = new $class_space();

        call_user_func(array($class, $example_layout['action']));
    }

    $path = $example_layout['view'] . '/Example.php';
    $view->AddData('vars', $vars);
    $view->AddData('module', $module);
    $view->LoadViewLibraries();
    $view->Load($path);
    
?>