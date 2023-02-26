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
    
    <div class="main-add-templates-box">
        <h2 class="h2-main-add-templates">
            Change template
        </h2>
    </div>
    <form class="form-add-template">
        <div class="label-container">
            <label>Choose subpage:</label>
        </div>
        <table class="table-main">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Address URI</th>
                    <th>Template</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pages as $page) {?>
                    <tr id-page="<?=$page['id']?>">
                        <td><?=$page['id']?></td>
                        <td><?=$page['title']?></td>
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
                                    echo substr($r, 0, 14);
                                    echo '..';
                                } else {
                                    echo $r;
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                if($page['id_layout'] == 0) {
                                    echo 'Not provided';
                                } else {
                                    $template = $templates[$page['id_layout']];
                                    echo $template['title'];
                                }
                            ?>
                        </td>
                        <td><div class="check-icon templates"></div></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="label-container">
            <label>Choose template:</label>
        </div>
        <!--
        <div class="templates-select-current">
            Wybierz strone
        </div>
        <div class="templates-select" open="false">
            <?php foreach($templates as $template) { ?>
                <label class="templates-select-option" id="<?=$template['id']?>" active="false"><?=$template['title']?></label>
            <?php } ?>
        </div>
        -->
        <div class="templates-list-select-container">
            <?php foreach($templates as $template) { ?>
                <div class="templates-list-select" template-id="<?=$template['id']?>">
                    <div class="label-template">
                        <label>
                            <?=$template['title']?>
                        </label>
                    </div>
                    <div class="description-template">
                        <?=$template['description']?>
                    </div>
                    <div class="label-template" style="margin-bottom:4%">
                        <label style="font-size:1.1rem">
                            Example page with this layout:
                        </label>
                    </div>
                    <iframe src="/example-template?id=<?=$template['id']?>" width="100%"></iframe>
                </div>
            <?php } ?>
        </div>
        <div class="templates-form-data-submit">
            <div class="templates-list-data">
                <label>
                    Subpage:
                </label>
                <div class="data-list" id="data-list-page">
                    Not provided
                </div>
                <label>
                    Template:
                </label>
                <div class="data-list" id="data-list-template">
                    Not provided
                </div>  
            </div>
            <input type="submit" value="Change template" class="templates-change-submit">
        </div>
        </div>
    </form>

</div>
</body>
</html>