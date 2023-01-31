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

    <div class="dashboard-box welcome-box">
        <h2>
            Witaj, <?=$login?>
        </h2>

        <ol class="next-steps">
          <li>Stwórz pierwszy artykuł!</li>
          <li>Dodaj pierwszy moduł!</li>
          <li>Dodaj pierwszą podstronę!</li>
        </ol>
    </div>
    <div class="dashboard-box system-info-box">
        <h3>
          Informacje o stronie
        </h3>
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