<?php 

  $applicationFolder = 'Application';

  $configFolder      = 'Config';
  $coreFolder        = 'Core';
  $librariesFolder   = 'Libraries';
  $controllersFolder = 'Controllers';
  $viewsFolder       = 'Views';

  $styleFolder       =  'CSS';
  $scriptFolder      =  'JS';
  $uploadFolder      =  'Uploads';

  define('SELFPATH', pathinfo(__FILE__, PATHINFO_DIRNAME));
  define('APPPATH', SELFPATH . DIRECTORY_SEPARATOR . $applicationFolder);

  $appPath = APPPATH . DIRECTORY_SEPARATOR;


  define('CONFPATH',  $appPath . $configFolder);
  define('COREPATH',  $appPath . $coreFolder);
  define('LIBPATH',   $appPath . $librariesFolder);
  define('VIEWPATH',  $appPath . $viewsFolder);
  define('CONSPATH',  $appPath . $controllersFolder);
  define('CSSPATH',   $styleFolder);
  define('JSPATH' ,   $scriptFolder);
  define('UPLPATH',   $uploadFolder);
  
  require_once(COREPATH . '/Core.php');
  
?>