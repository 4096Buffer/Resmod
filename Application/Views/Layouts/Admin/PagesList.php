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
            Ustaw szablon
        </h2>
    </div>
    <!--
    <div class="label-container" style="text-align:center">
        <label>Lista stron</label>
    </div>
    -->
    <table class="table-main pages-list">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Description</th>
                <th>Address URI</th>
                <th>Template</th>
                <th>Active</th>
                <th>Edit</th>
                <th>Settings</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pages as $page) {?>
                <tr id-page="<?=$page['id']?>">
                    <td><?=$page['id']?></td>
                    <td><?=$page_variables[$page['id']]['title']['value']?></td>
                    <td>
                        <?php 
                            $d = $page['description'];
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
                            $r = $page['route address'];
                            if(strlen($r) > 14) {
                                echo trim(substr($r, 0, 14));
                                echo '..';
                            } else {
                                echo $r;
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            if($page['id_layout'] == 0) {
                                echo 'N/A. <a href="pages-templates">Set layout</a>';
                            } else {
                                $template = $templates[$page['id_layout']];
                                echo $template['title'];
                            }
                        ?>
                    </td>
                    <td>
                        <div class="check-icon pages" style="background-color:<?=$page['active'] ? '#009921' : '#a50000'?>" active="<?=$page['active']?>"></div>
                    </td>
                    <td>
                        <a href="<?=$page['route address'] . '?mode=live-edit'?>"><div class="edit-icon pages">Edit</div></a>
                    </td>
                    <td>
                        <div class="settings-open" style="cursor:pointer;">Settings</div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <div class="page-settings-box" page-id="0">
            <div class="close-x">&#10005;</div>
            
            <div class="main-title-container">
                <label class="main-title">
                    Ustawienia strony
                </label>
            </div>
            <div class="page-setting-container">
                <div class="page-setting">
                    Tytuł strony:
                </div>
                <input type="text" class="page-setting-value title"/>
            </div>
            <div class="page-setting-container">
                <div class="page-setting">
                    SEO słowa<br> kluczowe(po przecinku):
                </div>
                <input type="text" class="page-setting-value seo-keywords"/>
            </div>
            <div class="page-setting-container">
                <div class="page-setting">
                    SEO opis:
                </div>
                <input type="text" class="page-setting-value seo-description"/>
            </div>
            <div class="page-setting-container">
                <div class="page-setting">
                    SEO image:
                </div>
                <input type="text" class="page-setting-value seo-image"/> <!--SEO IMAGE FILE COLLECTION-->
            </div>
            <div class="page-setting-container">
                <div class="page-setting">
                    Favicon:
                </div>
                <input type="text" class="page-setting-value favicon"/> <!--SEO IMAGE FILE COLLECTION-->
            </div>
            <div class="save-btn-settings page">
                <div>Zapisz</div>
            </div>
            </div>
        </div>
    
</body>
</html>