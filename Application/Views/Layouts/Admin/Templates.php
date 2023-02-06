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

<div class="main">
    <?php 
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'MainTitle.php';
    ?>
    
    <div class="main-add-templates-box">
        <h2 class="h2-main-add-templates">
            Dodaj szablon
        </h2>
    </div>

    <form class="form-add-template">
        <div class="label-container">
            <label>Wybierz stronę:</label>
        </div>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Address URI</th>
                    <th>Template</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pages as $page) {?>
                    <tr>
                        <td><div class="check-icon"></div></td>
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
                                $template = $templates[$page['id_layout']];
                                echo $template['title'];
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="label-container">
            <label>Wybierz szablon:</label>
        </div>
        <!--
        <div class="templates-select-current">
            Wybierz strone
        </div>
        <div class="templates-select" open="false">
            <?php foreach($templates as $template) { ?>
                <label class="templates-select-option" id="<?=$template['id']?>" active="false"><?=$template['title']?></label>
            <?php } ?>
        </select>
        -->
        
        <?php foreach($templates as $template) { ?>
            <div class="templates-list-select">
                
            </div>
        <?php } ?>
        
        </div>
    </form>

</div>
</body>
</html>