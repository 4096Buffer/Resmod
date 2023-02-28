<?php 

if(!$this->Auth->IsAuth()) {
  $this->RequestHelper->Redirect('/');
}

?>

<!DOCTYPE HTML>
<html>
<head>
  <?php 
      include VIEWPATH . '/' . 'Partials' . '/' . 'HeadAdmin.php';
      echo $js_add;
  ?>
</head>
<body>
  
  <?php 
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'LoadScreen.php';
    //include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'Header.php';
    //include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'SideNav.php';
  ?>

  <header class="modules-edit-header">
    Edytuj moduł '<?=$cmodule['title']?>'
  </header>

  <div class="module-edit-container">
    <?php 
      $module->LoadSingleModule($cmodule['id']);
    ?>
  </div>
  <div class="button-fixed" style="bottom: 0; right:0">
    <button>
      Zapisz
    </button>
  </div>
  <div class="button-fixed" style="bottom: 0; left:0;">
    <a href="<?=$redirect_back?>">
      <button>
        Wróć do edytowania
      </button>
    </a>
  </div>
</body>
</html>