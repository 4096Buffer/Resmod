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
            Create new article
        </h2>
    </div>

    <div class="add-article-container">
        <div class="add-article-box">
            <form class="add-article-form">
                <div class="top-box">
                    <div class="left-sided">
                        <input type="text" placeholder="Article title" class="title"/>
                        <div class="link-settings" style="display: noned">
                            <label class="new-link">Link:</label>
                            <div class="link">/</div>
                            <label style="margin-left:3%">Link type:</label>
                            <span class="link-type-current"></span>
                            <button class="link-type-open">Change</button>
                            <div class="link-type-container" active="1">
                                <label class="container-label-radio add-article" aria-label="(/article/1)">Numeric Slash
                                    <input type="radio" name="radio" value="1">
                                </label>
                                    <label class="container-label-radio add-article" aria-label="(/article?id=1)">Numeric id
                                    <input type="radio" name="radio" value="2">
                                </label>
                                    <label class="container-label-radio add-article" aria-label="(/article/test-test)">By title
                                    <input type="radio" name="radio" value="3">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="right-sided">
                        <div class="settings-article">
                            <label class="title-label">Settings</label>
                            <div class="settings-option" style="display:flex;flex-direction:column;">
                                <label class="state-label">Article status:</label>
                                <div class="select-main-container article-states" style="padding: 2%;margin:auto;">
                                    <div class="select-main article-states" style="padding: 3%;background-color:#4a4a4a;">
                                    <div class="select-main-title" style="width: 93.4%;font-size:1rem;">
                                        In-build:
                                    </div>
                                    <div class="select-main-arrow" style="width:1rem;height: 1rem;"></div>
                                    </div>
                                    <div class="select-main-list article-states" style="padding:0;position:relative;">
                                        <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex; float:left;" value="2" active="true"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content">In-build</div></label>    
                                        <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex; float:left;" value="1" active="false"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content">Public</div></label>
                                        <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex; float:left;" value="3" active="false"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content">Private</div></label>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-option" style="display:flex;flex-direction:column;">
                                <label class="state-label">Article category:</label>
                                <div class="select-main-container article-categories" style="padding: 2%;margin:auto;">
                                    <div class="select-main article-categories" style="padding: 3%;background-color:#4a4a4a;">
                                    <div class="select-main-title" style="width: 93.4%;font-size:1rem;">
                                        <?=$categories[0]['name'] ?? 'No categories created'?>
                                    </div>
                                    <div class="select-main-arrow" style="width:1rem;height: 1rem;"></div>
                                    </div>
                                    <div class="select-main-list article-states" style="padding:0;position:relative;">
                                        <?php foreach($categories as $category) { ?>
                                            <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex; float:left;" active="true"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content"><?=$category['name']?></div></label>    
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wysiwyg-static-container">
                    <div class="wysiwyg-toolbar">
                        <div class="wysiwyg-option bold">
                            B
                        </div>
                        <div class="wysiwyg-option italic">
                            /
                        </div>
                        <div class="wysiwyg-option underline">
                            U
                        </div>
                        <div class="wysiwyg-option color">
                            <input type="color" />
                        </div>
                        <div class="wysiwyg-option align-left">
                            LE
                        </div>
                        <div class="wysiwyg-option align-center">
                            CE
                        </div>
                        <div class="wysiwyg-option align-right">
                            RI
                        </div>
                        <div class="wysiwyg-option font-size">
                            <select class="wysiwyg-font-size-select" name="font-size">
                                <option value="8px">8</option>
                                <option value="9px">9</option>
                                <option value="10px">10</option>
                                <option value="11px">11</option>
                                <option value="12px">12</option>
                                <option value="13px">13</option>
                                <option value="14px">14</option>
                                <option value="16px">16</option>
                                <option value="18px">18</option>
                                <option value="24px">24</option>
                                <option value="36px">36</option>
                                <option value="48px">48</option>
                                <option value="60px">60</option>
                                <option value="72px">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="wysiwyg-text-area">
                        <textarea></textarea>
                    </div>
                </div>
                <div class="submit-container">
                    <input type="submit" value="Post"/>
                </div>
            </form>
        </div>
    </div>

</div>
    
</body>
</html>