<?php if($this->Auth->IsAuth()) { ?>
<div class="module-add-box">
    <div class="module-add-box-title">
        Choose module:
    </div>
    <div class="select-main-container module-groups2" style="width: 50%;padding: 2%;margin:auto;">
        <div class="select-main module-groups" style="padding: 3%;background-color:#4a4a4a;">
          <div class="select-main-title" style="width: 93.4%;font-size:1.2rem;">
            Choose category:
          </div>
          <div class="select-main-arrow" style="height: 1.5rem;width:1.5rem;"></div>
        </div>
        <div class="select-main-list module-groups" style="padding:0;">
            <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex;float:left;" active="true"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content">Select category</div></label>    
            <?php foreach($modules_groups as $group) { ?>
              <label class="select-main-option" style="margin: 1%; font-size: 1rem; display:flex; float:left;" active="false"><!--<input type="checkbox" class="custom-checkbox"/>--><div class="select-main-option-content"><?=$group['name']?></div></label>
            <?php } ?>
        </div>
    </div>
    <div class="modules-list-container"></div>
    <div class="close-x">&#10005;</div>   
</div>

<div class="add-module-center">
    <div class="add-module-bar">
        +
    </div>
</div>

<div class="button-fixed save" style="bottom: 0; right:0">
    <button>
        Save
    </button>
</div>

<div class="button-fixed" style="bottom: 0; left:0">
    <a href="/pages-list">
        <button>
            Go back
        </button>
    </a>
</div>

<?php } ?>