<?php 

$login = $_SESSION['login'] ?? null;

?>

<div class="dashboard-box welcome-box">
    <h2>
        Witaj w <?=$vars->Get('basename')?>
    </h2>

    <ol class="next-steps">
      <li>Stwórz pierwszy artykuł!</li>
      <li>Dodaj pierwszy moduł!</li>
      <li>Dodaj pierwszą podstronę!</li>
    </ol>
</div>