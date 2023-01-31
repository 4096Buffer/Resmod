<header class="header-admin-panel">
    <div class="header-admin-user">
        <span>
            <?=$login?>
        </span>

        <div class="header-admin-profile" style="background-image:url('<?=$avatar?>')"></div>

        
    </div>
    <div class="header-admin-profile-context-menu" open="false">
        <ol>
            <li><?=$name . ' ' . $surname;?></li>
            <li>Twój profile</li>
            <li>Ustawienia</li>
            <li href="javascript:void(0)" class="button-logout" ajax-controller="AdminProfile", ajax-action="Logout">Wyloguj się</li>
            <li>Pomoc</li>
        </ol>
    </div>
</header>

