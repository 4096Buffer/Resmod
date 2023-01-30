<?php 

if(!$this->Auth->IsAuth()) {
  $this->RequestHelper->Redirect('/');
}

$login   = $_SESSION['login'] ?? null;
$avatar  = 'Uploads/Avatars/' . $_SESSION['avatar'] ?? null;
$name    = $_SESSION['name'];
$surname = $_SESSION['surname'];
$email   = $_SESSION['email'];

?>

<!DOCTYPE HTML>
<html>
<head>
    
<?php 
    include VIEWPATH . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'HeadAdmin.php'
?>

</head>
<body>
  
  <?php 
    include VIEWPATH . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Header.php';
    include VIEWPATH . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'SideNav.php';
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
    <!--

      <div class="solid-chart-container">
        <canvas id="solid-chart"></canvas>
      </div>

    -->
  </div>
    

</body>
</html>