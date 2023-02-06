<?php 

if(!$this->Auth->IsAuth()) {
  $this->RequestHelper->Redirect('/');
}

?>

<!DOCTYPE HTML>
<html>
<head>
  <?php 
      include VIEWPATH . '/' . 'Partials' . '/' . 'HeadAdmin.php'
  ?>
</head>
<body>
  
  <?php 

    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'Header.php';
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'SideNav.php';

  ?>

  <div class="main">
    <?php 
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'MainTitle.php';
    ?>
    
    <div class="dashboard-box welcome-box">
        <h2>
            Witaj, <?=$login?>
        </h2>
        <ol>
          <li>Stwórz pierwszy artykuł!</li>
          <li>Dodaj pierwszy moduł!</li>
          <li>Dodaj pierwszą podstronę!</li>
        </ol>
    </div>
    
    <div class="dashboard-box views-page-info-box">
      <h2>
        Top 10 <br> wyświetlanych stron <br>w tym miesiącu
      </h2>
      <ol>
        <?php $i = 0;
          foreach($pages_views as $page) { ?>
            <?php 
              $i++;
              if($i > 8) {
                break;
              }
            ?>
            <li>
              <?=$page['description']?> - <?=$page['views']?>
            </li>
    <?php } ?>
      </ol>
    </div>

    <div class="dashboard-box modules-add">
        <div class="modules-add-left">
          <h2>
            Dodaj moduły
          </h2>
          <select name="modules-list" id="modules-list">
            <?php foreach($modules_groups as $group) { ?>
                <option value="select"></option>
                <option value="<?=strtolower($group['name'])?>">
                  <?=$group['name']?>
                </option>
            <?php } ?>
          </select>
        </div>
        <div class="modules-add-right">
          <?php foreach($modules_groups as $group) {?>
            <div class="modules-add-list-box" show-value="<?=strtolower($group['name'])?>">
                <ol>
                  <?php 
                    $i = 0;
                    foreach($group['modules'] as $module) { ?>
                    <?php 
                      $i++;
                      if($i > 8) {
                        break;
                      }
                    ?>
                    <li>
                      <a href="/add-module?module=<?=$module['id']?>">
                        <?=$module['title']?>
                      </a>
                    </li>
                  <?php } ?>
                <ol>
            </div>
          <?php } ?>
        </div>
    </div>

    <div class="dashboard-box system-info-box">
        <h2>
          Informacje o stronie
        </h2>
        <ol>
          <li>
            <label>Baza danych: </label>
            <span><?=$database_info?></span>
          </li>
          <li>
            <label>Wersja PHP: </label>
            <span><?=$php_info?></span>
          </li>
          <li>
            <label>Administratorzy: </label>
            <span><?=count($admin_users)?></span>
          </li>
          <li>
            <label>Użytkownicy: </label>
            <span><?=$users ? count($users) : 'N/A'?></span>
          </li>
          <li>
            <label>Użycie pamięci: </label>
            <span>
              <?=$memory_usage?> / <?=$max_memory_usage?> (MB)
            </span>
          </li>
        </ol>
    </div>
    <!--

      <div class="solid-chart-container">
        <canvas id="solid-chart"></canvas>
      </div>

    -->
  </div>
    

</body>
</html>