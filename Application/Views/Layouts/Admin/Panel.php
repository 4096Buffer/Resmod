<?php 

if(!$this->Auth->IsAuth()) {
    $this->Redirect('/');
}

?>

<!DOCTYPE HTML>
<html>
<head>
    
<?php 
    include VIEWPATH . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'HeadAdmin.php'
?>

</head>
<body>
    
<div class="sidenav">
  <a href="#">Kokpit</a>
  <a href="#">Wygląd</a>
  <a href="#">Strony</a>
  <a href="#">Użytkownicy</a>
  <a href="#">Ustawienia</a>
  <a href="javascript:void(0)" class="button-logout" ajax-controller="Login", ajax-action="Logout">Wyloguj się</a>
</div>

    
<div class="main">
    
<?php 
        
$module->LoadModules();

?>

</div>
    

</body>
</html>