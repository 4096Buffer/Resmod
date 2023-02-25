<?php 

if(!$this->Auth->IsAuth()) {
  $this->RequestHelper->Redirect('/');
}
die('This sub is deprecated <a href="/">go back</a>');
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
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'Header.php';
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'SideNav.php';
  ?>

<div class="main">
    <?php 
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'MainTitle.php';
    ?>
    <div class="main-add-templates-box">
        <h2 class="h2-main-add-templates">
            Dodaj moduł
        </h2>
        <a href=""
        <h2 class="h2-main-add-templates">
            Dodaj moduł
        </h2>
        </a>
    </div>
    <form class="modules-add-form">
      <div class="select-main-container module-groups" style="width: 50%;padding: 2%;font-size">
        <div class="select-main module-groups" style="padding: 3%;">
          <div class="select-main-title" style="width: 93.4%;font-size:1.5rem;">
            Wybierz kategorię
          </div>
          <div class="select-main-arrow" style="width:2rem;height: 1.5rem;width:1.5rem;"></div>
        </div>
        <div class="select-main-list module-groups" style="padding:0;">
            <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex;float:left;" active="true"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content">Wybierz kategorię</div></label>    
            <?php foreach($modules_groups as $group) { ?>
              <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex; float:left;" active="false"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content"><?=$group['name']?></div></label>
            <?php } ?>
        </div>
      </div>
      <div class="select-main-container pages" style="width: 50%;padding: 2%;font-size">
        <div class="select-main" style="padding: 3%;">
          <div class="select-main-title" style="width: 93.4%;font-size:1.5rem;">
            Wybierz kategorię
          </div>
          <div class="select-main-arrow" style="width:2rem;height: 1.5rem;width:1.5rem;"></div>
        </div>

        <div class="select-main-list  module-groups" style="padding:0;">
            <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex;float:left;" active="true"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content">Wybierz kategorię</div></label>    
            <?php foreach($pages as $page) { ?>
              <label class="select-main-option" page-id="<?=$page['id']?>" style="margin: 1%; font-size: 1rem; display:flex; float:left;" active="false"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content"><?=$page['title']?></div></label>
            <?php } ?>
        </div>
      </div>
      <div class="table-main-container">

      </div>
      <div class="module-add-submit-box">
        <button class="module-add-submit">Dodaj moduł</button>
      </div>
    </form>
  <?php 
    /*
    <table class="table-main smaller-table">
      <thead>
          <tr>
            <th></th>
            <th>Id</th>
            <th>Title</th>
            <th>Description</th>
            <th>Address URI</th>
           </tr>
      </thead>
      <tbody>
        <?php
          $search_enabled =  count($modules) > 10 ? true : false;
          if($search_enabled) {
            array_splice($modules, 10);
          }
        ?>
        <?php
          foreach($modules as $module) { ?>
          <tr id-module="<?=$module['id']?>">
            <td><div class="check-icon"></div></td>
            <td><?=$module['id']?></td>
            <td><?=$module['title']?></td>
            <td>
              <?php 
                $d = $module['description'];
                if(strlen($d) > 14) {
                  echo substr($d, 0, 14);
                } else {
                  echo $d;
                }

                echo '..';
              ?>
            </td>
            <td>
              <?php 
                $r = $module['description'];
                if(strlen($r) > 14) {
                  echo substr($r, 0, 14);
                  echo '..';
                } else {
                  echo $r;
                }
              ?>
            </td>
          </tr>
        
      </tbody>
    </table> */
  ?>
</div>
</body>
</html>