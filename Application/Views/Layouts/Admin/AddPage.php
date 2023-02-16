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

    <div class="main" style="justify-content: center;align-items: center;">
        <?php 
            include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'MainTitle.php';
        ?>
        
        <div class="main-add-templates-box">
            <h2 class="h2-main-add-templates">
                Stwórz stronę
            </h2>
        </div>
        <div class="form-add-page-container">
            <form class="form-add-page">
                <div class="add-page-input-box">
                    <label>
                        Tytuł strony:
                    </label>
                    <input type="text" class="add-page-input title" value="Example title">
                </div>
                <div class="add-page-input-box">
                    <label>
                        Adres strony:
                    </label>
                    <input type="text" class="add-page-input address" value="/example-title">
                </div>
                <div class="add-page-input-box">
                    <label>
                        SEO Opis strony:
                    </label>
                    <input type="text" class="add-page-input seo-description" value="This is description of your page! My page is the best :)">
                </div>
                <div class="add-page-input-box">
                    <label>
                        SEO słowa kluczowe strony:
                    </label>
                    <input type="text" class="add-page-input seo-keywords" value="my page, about cats, resmod, animals, dogs">
                </div>
                <div class="add-page-input-box">
                    <label>
                        SEO zdjęcie strony:
                    </label>
                    <input type="text" class="add-page-input seo-image" value="N/A">
                </div>
                <div class="add-page-input-box">
                    <label>
                        Favicon strony:
                    </label>
                    <input type="text" class="add-page-input favicon" value="N/A">
                </div>
                <div class="add-page-submit-box">
                    <div class="add-page-submit">
                        Stwórz stronę
                    </div>
                </div>
            </form>
            
        </div>
    </div>
<body>