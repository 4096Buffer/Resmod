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
                Manage administrators
            </h2>
        </div>
        <?php 
            $search_enabled = count($admins) > 0 ? true : false;
            if($search_enabled) {
                array_splice($admins, 15);
            }
        ?>

        <table class="table-main">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Level</th>
                    <th>Last active</th>
                    <th>Enabled</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($admins as $admin) {?>
                    <tr id-admin="<?=$admin['id']?>">
                        <td><?=$admin['id']?></td>
                        <td><?=$admin['name']?></td>
                        <td><?=$admin['surname']?></td>
                        <td><?=$admin['login']?></td>
                        <td><?=$admin['email']?></td>
                        <td><?=substr($admin['avatar'], 0, -30) . '...' ?? 'N/A'?></td> <!--temp-->
                        <td><?=$admin['level']?></td>
                        <td><?=$admin['active'] ? 'Now'  : ($admin['last_active'] ?? 'N/A')?></td>
                        <td><div class="check-icon mngadmins" style="background-color:<?=$admin['enabled'] ? '#009921' : '#a50000'?>" active="<?=$admin['enabled']?>"></div></td>
                        <td><div class="edit-open admins">Edit</div></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if($search_enabled) { ?>
            <form class="search-form admins">
                <input type="text" class="search-input" placeholder="Search.." autocorrect="off"/>
                <!--<input type="submit" class="search-submit" value="Szukaj"/>-->
            </form>
        <?php } ?>

        <h2 class="h2-main-add-templates">
            Create new administrator account
        </h2>
        <div class="add-admin-container">
           <form class="add-admin-form">
                <div class="add-admin-option">
                    <label>Name:</label>
                    <input type="text" class="add-admin-input name"/>
                </div>
                <div class="add-admin-option">
                    <label>Surname:</label>
                    <input type="text" class="add-admin-input surname"/>
                </div>
                <div class="add-admin-option">
                    <label>Login:</label>
                    <input type="text" class="add-admin-input login"/>
                </div>
                <div class="add-admin-option">
                    <label>Password:</label>
                    <input type="password" class="add-admin-input password"/>
                </div>
                <div class="add-admin-option">
                    <label>Email:</label>
                    <input type="email" class="add-admin-input email"/>
                </div>
                <div class="add-admin-option">
                    <label>Avatar:</label>
                    <input type="file" class="add-admin-input avatar"/>
                </div>
                <!--<input type="text" class="add-admin-input level"/>-->
                <div class="add-admin-submit-box">
                    <input type="submit" class="add-admin-submit" value="Add admin" />
                </div>
           </form> 
        </div>

        
        <div class="edit-admins-box" admin-id="0" style="display:none">
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
            <div class="save-btn-settings admins">
                <div>Save</div>
            </div>
            </div>
        </div>

    </div>
</body>
</html>