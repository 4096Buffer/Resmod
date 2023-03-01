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
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'Header.php';
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'SideNav.php';
    ?>

    <div class="main">
        <?php 
            include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'MainTitle.php';
        ?>
        
        <div class="main-add-templates-box">
            <h2 class="h2-main-add-templates">
                Manage users
            </h2>
        </div>
        <?php 
            if($vars->Get('user-login-system') == 'false') { ?>
                <div style="display: flex;justify-content: center;align-items: center;">
                    <h1 style="font-weight:100">User login system is not enabled! <a href="/settings" style="color: cornflowerblue;">Change it!</a></h1>  
                </div>  
        <?php } ?>
        
        <?php 
            $search_enabled = count($users) > 0 ? true : false;
            if($search_enabled) {
                array_splice($users, 15);
            }
        ?>

        <table class="table-main">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Login</th>
                    <th>Avatar</th>
                    <th>Bio</th>
                    <th>Active</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user) {?>
                    <tr id-user="<?=$user['id']?>">
                        <td><?=$user['id']?></td>
                        <td><?=$user['name']?></td>
                        <td><?=$user['surname']?></td>
                        <td><?=$user['login']?></td>
                        <td><?=$user['avatar'] ?? 'N/A'?></td>
                        <td><?=$user['bio'] ?? 'N/A'?></td>
                        <td><div class="check-icon mngusers" style="background-color:<?=$user['active'] ? '#009921' : '#a50000'?>" active="<?=$user['active']?>"></div></td>
                        <td><div class="edit-open users">Edit</div></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if($search_enabled) { ?>
            <form class="search-form users">
                <input type="text" class="search-input" placeholder="Search.." autocorrect="off"/>
                <input type="submit" class="search-submit" value="Szukaj"/>
            </form>
        <?php } ?>

        <div class="edit-users-box" user-id="0" style="display:none">
            <div class="close-x">&#10005;</div>
            
            <div class="main-title-container" style="margin-bottom:2%">
                <label class="main-title">
                    User settings
                </label>
            </div>
            <div class="edit-users-setting-container">
                <div class="edit-users-setting">
                    Name:
                </div>
                <input type="text" class="edit-users-setting-value name"/>
            </div>
            <div class="edit-users-setting-container">
                <div class="edit-users-setting">
                    Surname:
                </div>
                <input type="text" class="edit-users-setting-value surname"/>
            </div>
            <div class="edit-users-setting-container">
                <div class="edit-users-setting">
                    Login:
                </div>
                <input type="text" class="edit-users-setting-value login"/>
            </div>
            <div class="edit-users-setting-container">
                <div class="edit-users-setting">
                    Avatar:
                </div>
                <input type="text" class="edit-users-setting-value avatar"/> <!--SEO IMAGE FILE COLLECTION-->
            </div>
            <div class="edit-users-setting-container">
                <div class="edit-users-setting">
                    BIO:
                </div>
                <input type="text" class="edit-users-setting-value bio"/> <!--SEO IMAGE FILE COLLECTION-->
            </div>
            <div class="save-btn-settings users">
                <div>Save</div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>