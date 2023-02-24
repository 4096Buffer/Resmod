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
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'LoadScreen.php';
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'Header.php';
    include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'SideNav.php';

  ?>

  <div class="main">
    <?php 
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'MainTitle.php';
    ?>
    
    <div class="dashboard-box-container">
      <div class="dashboard-box welcome-box">
          <h2>
              Welcome, <?=$login?>
          </h2>
          <ol>
            <li>Create your first article!</li>
            <li>Create your first subpage!</li>
            <li>Add beautiful template to your subpage!</li>
          </ol>
      </div>
      
      <div class="dashboard-box views-page-info-box">
        <h2>
          Top 10 viewed <br>subpages <br>this month
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
                <?=$page['title']?> - <?=$page['views']?>
              </li>
      <?php } ?>
        </ol>
      </div>

      <div class="dashboard-box system-info-box">
          <h2>
            CMS info
          </h2>
          <ol>
            <li>
              <label>Database: </label>
              <span><?=$database_info?></span>
            </li>
            <li>
              <label>PHP version: </label>
              <span><?=$php_info?></span>
            </li>
            <li>
              <label>Administrators: </label>
              <span><?=count($admin_users)?></span>
            </li>
            <li>
              <label>Users: </label>
              <span><?=$users ? count($users) : 'N/A'?></span>
            </li>
            <li>
              <label>Memory usage: </label>
              <span>
                <?=$memory_usage?> / <?=$max_memory_usage?> (MB)
              </span>
            </li>
          </ol>
      </div>
    </div>
    <!--

      <div class="solid-chart-container">
        <canvas id="solid-chart"></canvas>
      </div>

    -->
  </div>
    

</body>
</html>