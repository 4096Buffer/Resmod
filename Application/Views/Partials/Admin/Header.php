<header class="header-admin-panel">
    <div class="header-admin-user">
        <span>
            <?=$name?> <?=$surname?>
        </span>

        <div class="header-admin-profile" style="background-image:url('<?=$avatar?>')"></div>  
    </div>
    <div class="header-admin-profile-context-menu" open="false">
        <ol>
            <li style="cursor:default">@<?=$login?></li>
            <li>Your profile</li>
            <li href="javascript:void(0)" class="button-logout" ajax-controller="AdminProfile" ajax-action="Logout">Logout</li>
            <li>Help</li>
        </ol>
    </div>
</header>