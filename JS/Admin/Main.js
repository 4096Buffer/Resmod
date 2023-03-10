Helpers.DOMHelper.waitForAllElm().then(() => {
    var event = Helpers.CreateEventManager()
    event.AddEvent('LoadUp', function() {
        (() => {
            var warningHack = () => {
                console.log('%cDo not type anything to this console if you dont know what you are doing. If someone told you to type something here he probably wants to hack you!', 'font-size:40px;color:red')
            }

            if(!localStorage.getItem('warningHack')) {
                warningHack()
            }

        })();

        (() => {
            
            var pagesScope = [
                'dashboard-scope',
                'templates-scope',
                'modules-scope',
                'pages-list-scope',
                'create-article-scope'
            ]

            for(var i = 0; i < pagesScope.length; i++) {
                var scope = Helpers.Scope.CreateScope(pagesScope[i])
                document.GlobalScope.SetVariable(pagesScope[i], scope)
            }

        })();

        (function() {
            var logoutBtn = document.querySelector(".button-logout")
            var logout    = e => {
                var controller = e.target.getAttribute('ajax-controller')
                var action     = e.target.getAttribute('ajax-action')
                var sendData = {
                    controller : controller,
                    action     : action
                }
                
                Helpers.AJAX.Post(location.href, sendData).success(data => {
                    console.log(data)
                    var decode = JSON.parse(data)
                    if(decode.response = 'Success') {
                        location.href = '/'
                    }

                })
                
            }
            if(logoutBtn) {
                logoutBtn.addEventListener('click', logout);
            }
        })();
        
        var randomRGB = () => {
            var r = 'rgba(' +
            (Math.floor(Math.random() * 255 + 1)).toString() + ',' + 
            (Math.floor(Math.random() * 255 + 1)).toString() + ',' + 
            (Math.floor(Math.random() * 255 + 1)).toString() + ',' +
            '1' + ')'
            
            return r
        };
        (() => {
            var closeBtn = document.querySelector('.close-x')
            var close = e => {
                
                if(e.currentTarget.parentElement.classList.contains('open-flex')) {
                    e.currentTarget.parentElement.classList.remove('open-flex')
                } else {
                    e.currentTarget.parentElement.style.display = 'none'
                }
            }
            if(closeBtn) {
                closeBtn.addEventListener('click', close)
            }
        })();
        (() => {
            var xValues = []
            var yValues = []
            var colors  = []
            
            var generateColors = () => {
                var y = yValues.length
                
                for(var i = 0; i < y; i++) {
                    colors.push(randomRGB())
                }
                
            }
            
            var setValues = (x_y, data) => {
                if(x_y == true) {
                    xValues = data
                } else {
                    yValues = data
                }
            }

            var createChart = () => {
                generateColors()
                
                new Chart("solid-chart", {
                  type: "bar",
                  data: {
                    labels: xValues,
                    datasets: [{
                      backgroundColor: colors,
                      data: yValues
                    }]
                  },
                  options: {
                    legend: {display: false},
                    title: {
                      display: true,
                      text: "Statystyki wy??wietle?? stron"
                    }, 
                    responsive: true,
                    maintainAspectRatio: false,
                  }
                });
            }
            
            var getViews = () => {
               var sendData = {
                    controller : 'Page',
                    action     : 'GetViews'
               }
               var views = [];

               Helpers.AJAX.Post(location.href, sendData).success(data => {
                   var decode = JSON.parse(data)
                   
                   if(decode.response == 'Success') {
                       setValues(false, decode.data)
                       createChart()
                   }
               })
            }
            
            var getPages = () => {
                var sendData = {
                    controller : 'Page',
                    action     : 'GetPages'
                }
                
                var pages = []
                
                Helpers.AJAX.Post(location.href, sendData).success(data => {
                    var decode = JSON.parse(data)
                    if(decode.response == 'Success') {
                        setValues(true, decode.data)
                        getViews()
                    }
                })
            }
            
            
            if(document.getElementById('solid-chart')) {
                getPages()
            }
            
        })();

        (() => {
            var avatar = document.querySelector('.header-admin-profile')
            var contextMenu = document.querySelector('.header-admin-profile-context-menu')

            var avatarClick = () => {
                var openAttr = contextMenu.getAttribute('open')
                if(openAttr == 'false') {
                    contextMenu.setAttribute('open', 'true')
                    contextMenu.style.display = 'block';
                } else {
                    contextMenu.setAttribute('open', 'false')
                    contextMenu.style.display = 'none';
                }
            }
            if(avatar) {
                avatar.addEventListener('click', avatarClick)
            }
            
        })();

        (() => {
            var collaps = document.getElementsByClassName('collapsible-sidenav')

            for(var i = 0; i < collaps.length; i++) {
                var openClose = e => {
                    if(e.target.getAttribute('collapsible') == 'true') {
                        var content = e.target.nextElementSibling

                        if(content.getAttribute('active') == 'false') {
                            content.style.display          = 'block'
                            setTimeout(() => {
                                content.style.opacity      = '100%'
                            }, 50)
                            e.target.style.background      = 'none'
                            e.target.style.backgroundColor = '#262626'

                            content.setAttribute('active', 'true')
                        } else {
                            content.style.opacity          = '0%'
                            setTimeout(() => {
                                content.style.display      = 'none'
                            }, 100)

                            e.target.style.backgroundColor = null
                            e.target.style.background      = 'none'

                            content.setAttribute('active', 'false')
                        }
                    } else {
                        location.href = e.target.getAttribute('href')
                    }
                }

                collaps[i].addEventListener('click', openClose)
            };

        })();
        
        (() => {
            var checkIcons = document.getElementsByClassName('check-icon templates')
            var scope = document.GlobalScope.GetVariable('templates-scope')
            var templatesListData = document.querySelector('.templates-list-data')
            
            var clickCheckIcon = e => {
                var rows = e.target.parentElement.parentElement.parentElement.children
                
                for(var i = 0; i < rows.length; i++) {
                    if(rows[i].getAttribute('active') == 'true') {
                        var checkIcon = rows[i].querySelector('.check-icon')
                        
                        checkIcon.style.setProperty('--checkicon-b-background', '#fff')
                        checkIcon.style.setProperty('--checkicon-a-background', '#fff')

                        rows[i].setAttribute('active', 'false')
                        rows[i].classList.remove('active-row-pages-templates')
                    }
                }

                var row = e.target.parentElement.parentElement
                var dataListPage = templatesListData.querySelector('#data-list-page')
                var childsRow = row.children
                var title     = childsRow[2].innerText

                e.target.style.setProperty('--checkicon-b-background', '#2a2a2a')
                e.target.style.setProperty('--checkicon-a-background', '#2a2a2a')

                row.setAttribute('active', 'true')
                row.classList.add('active-row-pages-templates')
                
                scope.SetVariable('ajax-page-id', row.getAttribute('id-page'))

                dataListPage.addEventListener('click', () => {
                    location.href = childsRow[4].innerText
                })

                dataListPage.innerText = title
            }
            
            for(var i = 0; i < checkIcons.length; i++) {
                checkIcons[i].addEventListener('click', clickCheckIcon)
            }

            var selectText = document.querySelector('.templates-select-current');
            var selectClick = e => {
                var selectDiv = document.querySelector('.templates-select')
                if(selectDiv.getAttribute('open') == 'false') {
                    selectText.style.setProperty('--arrow-select-transform', 'rotate(180deg)')
                    selectDiv.style.display = 'flex'
                    
                    setTimeout(() => {
                        selectDiv.style.opacity = '100%'
                    }, 50)

                    selectDiv.setAttribute('open', 'true')
                } else {
                    selectText.style.setProperty('--arrow-select-transform', 'rotate(0deg)')
                    selectDiv.style.opacity = '0%'

                    setTimeout(() => {
                        selectDiv.style.display = 'none'
                    }, 100)

                    selectDiv.setAttribute('open', 'false')
                }
            }

            if(selectText) {
                selectText.addEventListener('click', selectClick);
            }

            var selectOptions = document.getElementsByClassName('templates-select-option')
            
            var selectOptionClick = e => {
                selectText.innerText = e.target.innerText
            }

            for(var i = 0; i < selectOptions.length; i++) {
                selectOptions[i].addEventListener('click', selectOptionClick)
            }

            var templatesBoxes = document.getElementsByClassName('templates-list-select')
            var templatesListData = document.querySelector('.templates-list-data')

            var onClick = (e) => {
                var title = e.currentTarget.querySelector('.label-template').innerText
                var dataListTemplate = templatesListData.querySelector('#data-list-template')
                
                for(var i = 0; i < templatesBoxes.length; i++) {
                    templatesBoxes[i].style.backgroundColor = 'white'
                    templatesBoxes[i].style.color = '#000'
                }

                e.currentTarget.style.backgroundColor = '#424242'
                e.currentTarget.style.color = '#fff'

                scope.SetVariable('ajax-template-id', e.currentTarget.getAttribute('template-id'))
                
                dataListTemplate.addEventListener('click', () => {
                    location.href = '/example-template?id=' + scope.GetVariable('ajax-template-id').toString()
                })

                dataListTemplate.innerText = title;
            };

            for(var i = 0; i < templatesBoxes.length; i++) {
                templatesBoxes[i].addEventListener('click', onClick)
            }

            var submitForm = document.querySelector('.templates-change-submit')
            var submit = (e) => {
                e.preventDefault()

                var pageId     = scope.GetVariable('ajax-page-id')
                var templateId = scope.GetVariable('ajax-template-id')

                if(pageId == null|| templateId == null) {
                    Helpers.CreateModal({
                        title : 'Error!',
                        content : 'Please select template and page',
                        success : false
                    })
                    return;
                }

                Helpers.AJAX.Post(location.href, {
                    controller  : 'Page',
                    action      : 'ChangeTemplate',
                    id_page     : pageId,
                    id_template : templateId

                }).success(data => {
                    data = JSON.parse(data)

                    if(data.response == 'Success') {
                        Helpers.CreateModal({
                            title : 'Success!',
                            content : 'Successfully changed template',
                            success : true
                        })

                        location.href = '/pages-list'
                    } else {
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error while changing template!',
                            success : false
                        })
                    }
                })
            }
            if(submitForm) {
                submitForm.parentElement.parentElement.addEventListener('submit', submit)
            }

        })();
        (() => {
            var selectContainer = document.getElementsByClassName('select-main-container module-groups')[0]
            if(selectContainer) {
                var selectMain      = selectContainer.querySelector('.select-main')
                var selectList      = selectContainer.querySelector('.select-main-list')

                var select = e => {
                    var classList = selectList.classList

                    if(classList.contains('open-flex')) {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(180deg)')
                        selectList.style.opacity = '0%'
                        setTimeout(() => {
                            classList.remove('open-flex')
                        }, 100)
                        
                    } else {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(0deg)')
                        classList.add('open-flex')
                        setTimeout(() => {
                            selectList.style.opacity = '100%'
                        }, 100)
                    }
                }

                if(selectMain) {
                    //selectMain.addEventListener('click', select)
                }

                var selectOptions = selectList.getElementsByClassName('select-main-option')
                var showTable = group => {
                    var modules = Helpers.Data.GetData('tables-modules-list')
                    var container = document.querySelector('.table-main-container')
                    var currentModules = []

                    container.innerHTML = ''

                    for(var i = 0; i < modules.length; i++) {
                        if(modules[i].group == group) {
                            currentModules.push(modules[i])
                        }
                    }
                    
                    if(currentModules.length > 0) {
                        var search_enabled = currentModules.length > 10 ? true : false
                        if(search_enabled) {
                            currentModules.splice(10, currentModules.length)
                        }

                        var v = ''

                        v += '<table class="table-main smaller-table">'
                        v += ' <thead>'
                        v += '   <tr>'
                        v += '     <th></th>'
                        v += '     <th>Id</th>'
                        v += '     <th>Title</th>'
                        v += '     <th>Description</th>'
                        v += '     <th>Group</th>'
                        v += '   </tr>'
                        v += ' </thead>'
                        v += ' <tbody>'
                        for(var i = 0; i < currentModules.length; i++) {  
                            var id          = currentModules[i].id
                            var title       = currentModules[i].title
                            var description = currentModules[i].description
                            var group       = currentModules[i].group

                            description = description.length > 14 ? description.substr(0, 14) : description
                            
                            v += '<tr id-module="' + id + '">'
                            v += '  <td><div class="check-icon modules"></div></td>'
                            v += '  <td>' + id + '</td>'
                            v += '  <td>' + title + '</td>'
                            v += '  <td>' + description + '</td>'
                            v += '  <td>' + group + '</td>'
                        }
                        
                        v += '  </tbody>'
                        v += '</table>'

                        container.innerHTML = v
                        addEventTable()
                    }
                }

                var selectItem = e => {
                    for(var i = 0; i < selectOptions.length; i++) {
                        if(selectOptions[i] != e.currentTarget) {
                            selectOptions[i].setAttribute('active', 'false')
                        }
                    }

                    selectMain.querySelector('.select-main-title').innerText = e.currentTarget.querySelector('.select-main-option-content').innerText
                    e.currentTarget.setAttribute('active', 'true')
                    showTable(e.currentTarget.querySelector('.select-main-option-content').innerText)
                }

                for(var i = 0; i < selectOptions.length; i++) {
                    //selectOptions[i].addEventListener('click', selectItem)
                }
            }

            var scope = document.GlobalScope.GetVariable('modules-scope')
            var checkIcons = document.getElementsByClassName('check-icon modules')
            var clickIcon = e => {
                var rows = e.target.parentElement.parentElement.parentElement.children
                
                for(var i = 0; i < rows.length; i++) {
                    if(rows[i].getAttribute('active') == 'true') {
                        var checkIcon = rows[i].querySelector('.check-icon')
                        
                        checkIcon.style.setProperty('--checkicon-b-background', '#fff')
                        checkIcon.style.setProperty('--checkicon-a-background', '#fff')

                        rows[i].setAttribute('active', 'false')
                        rows[i].classList.remove('active-row-pages-templates')
                    }
                }

                var row = e.target.parentElement.parentElement

                e.target.style.setProperty('--checkicon-b-background', '#2a2a2a')
                e.target.style.setProperty('--checkicon-a-background', '#2a2a2a')

                row.setAttribute('active', 'true')
                row.classList.add('active-row-pages-templates')

                scope.SetVariable('ajax-module-id', row.getAttribute('id-module'))
            }

            var addEventTable = () => {
                if(checkIcons) {
                    for(var i = 0; i < checkIcons.length; i++) {
                        checkIcons[i].addEventListener('click', clickIcon)
                    }
                }
            }
        })();
        (() => {
            var scope = document.GlobalScope.GetVariable('modules-scope')
            var selectContainer = document.getElementsByClassName('select-main-container pages')[0]
            if(selectContainer) {
                var selectMain      = selectContainer.querySelector('.select-main')
                var selectList      = selectContainer.querySelector('.select-main-list')

                var select = e => {
                    var classList = selectList.classList

                    if(classList.contains('open-flex')) {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(180deg)')
                        selectList.style.opacity = '0%'
                        setTimeout(() => {
                            classList.remove('open-flex')
                        }, 100)
                        
                    } else {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(0deg)')
                        classList.add('open-flex')
                        setTimeout(() => {
                            selectList.style.opacity = '100%'
                        }, 100)
                    }
                }

                if(selectMain) {
                    selectMain.addEventListener('click', select)
                }
                
                var selectOptions = selectList.getElementsByClassName('select-main-option')
                var selectItem = e => {
                    for(var i = 0; i < selectOptions.length; i++) {
                        if(selectOptions[i] != e.currentTarget) {
                            selectOptions[i].setAttribute('active', 'false')
                        }
                    }

                    selectMain.querySelector('.select-main-title').innerText = e.currentTarget.querySelector('.select-main-option-content').innerText
                    e.currentTarget.setAttribute('active', 'true')
                    showList(e.currentTarget.querySelector('.select-main-option-content').innerText)
                }

                for(var i = 0; i < selectOptions.length; i++) {
                    selectOptions[i].addEventListener('click', selectItem)
                }
            }
        })();

        (() => {
            var scope  = document.GlobalScope.GetVariable('modules-scope')
            var form   = document.querySelector('.modules-add-form')

            var submitAction = e => {
                e.preventDefault()

                var pageId = scope.GetVariable('ajax-page-id')
                var moduleId = scope.GetVariable('ajax-module-id')

                if(pageId == null || moduleId == null) {
                    alert('Page or module was not specified')
                    return;
                }

                var data = {
                    controller : 'Modules',
                    action     : 'AddModule',
                    module_id  : moduleId,
                    page_id    : pageId
                }

                Helpers.AJAX.Post(location.href, data).success(data => {
                    try {
                        var obj = JSON.parse(data)
                        
                        if(obj.response != 'Success') {
                            alert('Error restart page!')
                        } else {
                            location.href = "/modules-edit?id=" + obj.data.module_added.id
                        }

                    } catch(e) {
                        alert('Error restart page!')
                    }
                })
            }

            if(form) {
                form.addEventListener('submit', submitAction)
            }
        })();
        (() => {
            

        })();
        
        (() => {
            var scope = document.GlobalScope.GetVariable('pages-list-scope')
            var checkActivePage = document.getElementsByClassName('check-icon pages')

            var changeActive = e => {
                var current = e.currentTarget
                var pageId = current.parentElement.parentElement.getAttribute('id-page')
                var newVal = current.getAttribute('active') == '1' ? '0' : '1'

                Helpers.AJAX.Post(location.href, {
                    controller : 'Page', 
                    action     : 'ChangeActive',
                    id_page    : pageId,
                    active     : newVal
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)
                        
                        if(obj.response == 'Success') {
                            if(current.getAttribute('active') == '1') {
                                current.setAttribute('active', '0')
                                current.style.backgroundColor = '#a50000'
                            } else {
                                current.setAttribute('active', '1')
                                current.style.backgroundColor = '#009921'
                            }

                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully changed property "active"',
                                success : true
                            }, function() {}, 2000)
                        } else {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error changing property "active"',
                                success : false
                            })
                        }
                    } catch(e) {
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error changing property "active"',
                            success : false
                        })
                    }
                })
            }

            for(var i = 0; i < checkActivePage.length; i++) {
                checkActivePage[i].addEventListener('click', changeActive)
            }

            var settingsPageBtn = document.getElementsByClassName('settings-open')
            var settingsBox     = document.querySelector('.page-settings-box')

            var openSettings = e => {
                var classList = settingsBox.classList
                var pageId    = e.currentTarget.parentElement.parentElement.getAttribute('id-page')
                var pages     = Helpers.Data.GetData('pages-list')

                var title       = document.querySelector('.page-setting-value.title')
                var keywords    = document.querySelector('.page-setting-value.seo-keywords')
                var description = document.querySelector('.page-setting-value.seo-description')
                var image       = document.querySelector('.page-setting-value.seo-image')
                var favicon     = document.querySelector('.page-setting-value.favicon')

                var GetVariableByName = (name,variables) => {
                    for(var i = 0; i < variables.length; i++) {
                        if(variables[i].name == name) {
                            return variables[i]
                        }
                    }

                    return {
                        value : ''
                    }
                }

                for(var i = 0; i < pages.length; i++) {
                    if(pages[i].id == pageId) {
                        title.value       = GetVariableByName('title',           pages[i].variables).value
                        keywords.value    = GetVariableByName('seo_keywords',    pages[i].variables).value
                        description.value = GetVariableByName('seo_description', pages[i].variables).value
                        image.value       = GetVariableByName('seo_image',       pages[i].variables).value
                        favicon.value     = GetVariableByName('favicon',         pages[i].variables).value
                    }
                }

                settingsBox.setAttribute('page-id', pageId)
                classList.add('open-flex')
            }

            for(var i = 0; i < settingsPageBtn.length; i++) {
                settingsPageBtn[i].addEventListener('click', openSettings)
            }

        })();

        (() => {
            var savePageSettings = document.querySelector('.save-btn-settings.page')

            var getRowByPageId = id => {
                var pagesTable = document.querySelector('.table-main.pages-list')
                var tbody      = pagesTable.querySelector('tbody')
                var rows       = tbody.children

                for(var i = 0; i < rows.length; i++) {
                    if(rows[i].getAttribute('id-page') == id) {
                        return rows[i]
                    }
                }

                return null;
            }

            var save = e => {
                var title       = document.querySelector('.page-setting-value.title')
                var keywords    = document.querySelector('.page-setting-value.seo-keywords')
                var description = document.querySelector('.page-setting-value.seo-description')
                var image       = document.querySelector('.page-setting-value.seo-image')
                var favicon     = document.querySelector('.page-setting-value.favicon')

                var pageId = e.currentTarget.parentElement.getAttribute('page-id')
                
                var sendData = {
                    title : title.value,
                    keywords : keywords.value,
                    description : description.value,
                    image : image.value,
                    favicon : favicon.value
                }

                var row = getRowByPageId(pageId)

                Helpers.AJAX.Post(location.href, {
                    controller : 'Page',
                    action     : 'ChangePageSettings',
                    id_page    : pageId,
                    data_page  : sendData
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)

                        if(obj.response == 'Success') {
                            row.children[1].innerText = title.value
                            row.children[2].innerText = description.value.length > 14 ? description.value.substr(0, 14) : description.value
                            
                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully changed page settings',
                                success : true
                            })
                        } else {
                           
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error changing page settings',
                                success : false
                            })
                        }
                    } catch(e) {
                        console.log(e)
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error changing page settings',
                            success : false
                        })
                    }
                })
            }
            if(savePageSettings) {
                savePageSettings.addEventListener('click', save)
            }
        })();

        (() => {
            var addPageForm = document.querySelector('.form-add-page') 
            if(addPageForm){
                var addPageSubmitBtn  = addPageForm.querySelector('.add-page-submit')

                var addPageSubmit = e => {
                    var title = addPageForm.querySelector('.add-page-input.title')
                    var address = addPageForm.querySelector('.add-page-input.address')
                    var seoDesc = addPageForm.querySelector('.add-page-input.seo-description')
                    var seoKeywords = addPageForm.querySelector('.add-page-input.seo-keywords')
                    var seoImage = addPageForm.querySelector('.add-page-input.seo-image')
                    var favicon = addPageForm.querySelector('.add-page-input.favicon')

                    var pageData = {
                        title        : title.value,
                        raddress     : address.value,
                        seo_desc     : seoDesc.value,
                        seo_keywords : seoKeywords.value,
                        seo_image    : seoImage.value,
                        favicon      : favicon.value
                    }

                    Helpers.AJAX.Post(location.href, {
                        controller : 'Page',
                        action     : 'CreatePage',
                        page_data  : pageData
                    }).success(data => {
                        try {
                            var obj = JSON.parse(data)

                            if(obj.response == 'Success') {
                                Helpers.CreateModal({
                                    title : 'Success!',
                                    content : 'Successfully created page!',
                                    success : true
                                }, e => {
                                    location.href = '/pages-list'
                                })
                            } else {
                                Helpers.CreateModal({
                                    title : 'Error!',
                                    content : 'Error while creating page',
                                    success : false
                                })
                            }
                        } catch(e) {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error while creating page',
                                success : false
                            })
                        }
                    })
                }

                addPageSubmitBtn.addEventListener('click', addPageSubmit)
            }
        })();

        (() => {
            var deletePageBtns = document.querySelectorAll('.delete-page')
            
            var deletePage = e => {
                var tr = e.currentTarget.parentElement.parentElement

                if(typeof tr.tagName === 'undefined' || tr.tagName.toLowerCase() !== 'tr') {
                    console.log(tr.tagName)
                    return;
                }

                var pageId = tr.getAttribute('id-page')
                if(!pageId) {
                    return;
                }

                
                if(!window.confirm('Are you sure you want to delete this page? You cant undo this action')) return;

                Helpers.AJAX.Post(location.href, {
                    controller : 'Page',
                    action     : 'DeletePage',
                    id         : pageId
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)

                        if(obj.response == 'Success') {
                            tr.remove()
                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully removed the page!',
                                success : true
                            })
                        } else {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : obj.reason,
                                success : false
                            })
                        }
                    } catch(e) {
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error while deleting page!',
                            success : false
                        })
                    }
                })
            }

            for(var i = 0; i < deletePageBtns.length; i++) {
                deletePageBtns[i].addEventListener('click', deletePage)
            }
        })();

        var addTableUsersEvents = () => {
            var checkActiveUser = document.getElementsByClassName('check-icon mngusers')

            var changeActive = e => {
                var current = e.currentTarget
                var userId = current.parentElement.parentElement.getAttribute('id-user')
                var newVal = current.getAttribute('active') == '1' ? '0' : '1'

                Helpers.AJAX.Post(location.href, {
                    controller : 'Users', 
                    action     : 'ChangeActive',
                    id_user    : userId,
                    active     : newVal
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)
                        
                        if(obj.response == 'Success') {
                            if(current.getAttribute('active') == '1') {
                                current.setAttribute('active', '0')
                                current.style.backgroundColor = '#a50000'
                            } else {
                                current.setAttribute('active', '1')
                                current.style.backgroundColor = '#009921'
                            }

                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully changed property "active"',
                                success : true
                            })
                        } else {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error changing property "active"',
                                success : false
                            })
                        }
                    } catch(e) {
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error while changing property "active"',
                            success : false
                        })
                    }
                })
            }

            for(var i = 0; i < checkActiveUser.length; i++) {
                checkActiveUser[i].addEventListener('click', changeActive)
            }

            var editOpenUsers = document.querySelectorAll('.edit-open.users')
            var editContainer = document.querySelector('.edit-users-box')

            var openContainer = e => {
                var userId   = e.currentTarget.parentElement.parentElement.getAttribute('id-user')
                var users    = Helpers.Data.GetData('users-list')
                var name     = document.querySelector('.edit-users-setting-value.name')
                var surname  = document.querySelector('.edit-users-setting-value.surname')
                var login    = document.querySelector('.edit-users-setting-value.login')
                var avatar   = document.querySelector('.edit-users-setting-value.avatar')
                var bio      = document.querySelector('.edit-users-setting-value.bio')

                editContainer.setAttribute('user-id', userId)
                
                for(var i = 0; i < users.length; i++) {
                    if(users[i].id == userId) {
                        name.value    = users[i].name
                        surname.value = users[i].surname
                        login.value   = users[i].login
                        avatar.value  = users[i].avatar
                        bio.value     = users[i].bio
                    }
                }
                editContainer.classList.add('open-flex')
            }

            for(var i = 0; i < editOpenUsers.length; i++) {
                editOpenUsers[i].addEventListener('click', openContainer)
            }
            
            var saveEditUsers = document.querySelector('.save-btn-settings.users')
           
            var saveEditData = e => {
                var userId  = e.currentTarget.parentElement.getAttribute('user-id')
                var name    = document.querySelector('.edit-users-setting-value.name').value
                var surname = document.querySelector('.edit-users-setting-value.surname').value
                var login   = document.querySelector('.edit-users-setting-value.login').value
                var avatar  = document.querySelector('.edit-users-setting-value.avatar').value
                var bio     = document.querySelector('.edit-users-setting-value.bio').value
                var data = {
                    name    : name,
                    surname : surname,
                    login   : login,
                    avatar  : avatar,
                    bio     : bio
                }

                Helpers.AJAX.Post(location.href, {
                    controller : 'Users',
                    action     : 'ChangeData',
                    id_user    : userId,
                    data       : data
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)

                        if(obj.response == 'Success') {
                            editContainer.classList.remove('open-flex')

                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully changed user data',
                                success : true
                            }, e => {
                                location.reload()
                            }, 1500)
                        } else {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error while changing user data(user id: ' + userId + ')',
                                success : false
                            })
                        }
                    } catch(e) {
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error while changing user data(user id: ' + userId + ')',
                            success : false
                        })
                    }
                })
            }
            if(saveEditUsers) {
                saveEditUsers.addEventListener('click', saveEditData)
            }
        };
        (() => {
            addTableUsersEvents()
        })();
        (() => {
            var searchForm = document.querySelector('.search-form.users')
            var usersTable = document.querySelector('.table-main')

            var searchUsers = e => {
                e.preventDefault()

                var input      = e.currentTarget.querySelector('.search-input').value
                var users      = Helpers.Data.GetData('users-list')
                var tableTbody = usersTable.querySelector('tbody')
                var records = []

                for(var i = 0; i < users.length; i++) {
                    var login = users[i].login.toLowerCase()
                    var name  = users[i].name.toLowerCase()
                    var surname = users[i].surname.toLowerCase()
                    var inputl = input.toLowerCase()

                    if(name.indexOf(inputl) !== -1 || surname.indexOf(inputl) !== -1 || login.indexOf(inputl) !== -1) {
                        records.push(users[i])
                        continue;
                    }
                }

                if(records.length > 0) {
                    records = records.length > 15 ? records.splice(0, 15) : records
                    tableTbody.innerHTML = ''

                    var html = ''
                    for(var i = 0; i < records.length; i++) { 
                        html += '<tr id-user="' + records[i].id +  '">'
                            html +=  '<td>' + records[i].id +      '</td>'
                            html +=  '<td>' + records[i].name +    '</td>'
                            html +=  '<td>' + records[i].surname + '</td>'
                            html +=  '<td>' + records[i].login +   '</td>'
                            html +=  '<td>' + records[i].email +   '</td>'
                            html +=  '<td>' + records[i].avatar +  '</td>'
                            html +=  '<td>' + records[i].bio +     '</td>'
                            html +=  '<td><div class="check-icon mngusers" style="background-color:' + (records[i].active ? '#009921' : '#a90000') + '" active="' + (records[i].active ? '1' : '0') + '">'
                            html +=  '</div>'
                            html +=  '</td>'
                            html +=  '<td><div class="edit-open users">Edit</div></td>'
                        html +=  '</tr>'
                    }

                    tableTbody.innerHTML = html
                    setTimeout(() => {
                        addTableUsersEvents()
                    }, 50)
                    
                    Helpers.CreateModal({
                        title : 'Info!',
                        content : 'Shown ' + records.length + (records.length > 1 ? ' records' : ' record'),
                        success : true
                    })
                } else {
                    Helpers.CreateModal({
                        title : 'Info!',
                        content : 'No user found!',
                        success : false
                    })
                }
            }

            if(searchForm) {
                searchForm.addEventListener('submit', searchUsers)
            }
        })();

        (() => {
            /*
            var loadScreen = document.querySelector('.load-screen')
            if(loadScreen) {
                loadScreen.classList.add('load-screen--hidden')
                
                loadScreen.addEventListener('transitionend', e => {
                    if(loadScreen) {
                        document.body.removeChild(loadScreen)
                    }
                })
            }
            */
        })();

        var addTableAdminsEvents = () => {
            var checkActiveUser = document.getElementsByClassName('check-icon mngadmins')

            var changeActive = e => {
                var current = e.currentTarget
                var userId = current.parentElement.parentElement.getAttribute('id-admin')
                var newVal = current.getAttribute('active') == '1' ? '0' : '1'

                if(userId == Helpers.Data.GetData('profile').id) {
                    Helpers.CreateModal({
                        title : 'Error!',
                        content : 'You cant disable your own account!',
                        success : false
                    })
                    return;
                }

                Helpers.AJAX.Post(location.href, {
                    controller : 'AdminProfile', 
                    action     : 'ChangeEnabled',
                    id_user    : userId,
                    enabled    : newVal
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)
                        
                        if(obj.response == 'Success') {
                            if(current.getAttribute('active') == '1') {
                                current.setAttribute('active', '0')
                                current.style.backgroundColor = '#a50000'
                            } else {
                                current.setAttribute('active', '1')
                                current.style.backgroundColor = '#009921'
                            }

                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully changed property "enabled"',
                                success : true
                            })
                        } else {
                            console.log(data)
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error changing property "enabled"',
                                success : false
                            })
                        }
                    } catch(e) {
                        console.log(data)
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error while changing property "active"',
                            success : false
                        })
                    }
                })
            }

            for(var i = 0; i < checkActiveUser.length; i++) {
                checkActiveUser[i].addEventListener('click', changeActive)
            }

            var editOpenAdmins = document.querySelectorAll('.edit-open.admins')
            var editContainer = document.querySelector('.edit-admins-box')

            var openContainer = e => {
                var adminId   = e.currentTarget.parentElement.parentElement.getAttribute('id-admin')
                var admins    = Helpers.Data.GetData('admins-list')
                var name     = document.querySelector('.edit-users-setting-value.name')
                var surname  = document.querySelector('.edit-users-setting-value.surname')
                var login    = document.querySelector('.edit-users-setting-value.login')
                var avatar   = document.querySelector('.edit-users-setting-value.avatar')
                var level   = document.querySelector('.edit-users-setting-value.level')

                editContainer.setAttribute('admin-id', adminId)
                
                for(var i = 0; i < admins.length; i++) {
                    if(admins[i].id == adminId) {
                        name.value    = admins[i].name
                        surname.value = admins[i].surname
                        login.value   = admins[i].login
                        avatar.value  = admins[i].avatar
                    }
                }
                editContainer.classList.add('open-flex')
            }

            for(var i = 0; i < editOpenAdmins.length; i++) {
                editOpenAdmins[i].addEventListener('click', openContainer)
            }
            
            var saveEditUsers = document.querySelector('.save-btn-settings.admins')
           
            var saveEditData = e => {
                var adminId  = e.currentTarget.parentElement.getAttribute('admin-id')
                var name    = document.querySelector('.edit-users-setting-value.name').value
                var surname = document.querySelector('.edit-users-setting-value.surname').value
                var login   = document.querySelector('.edit-users-setting-value.login').value
                var avatar  = document.querySelector('.edit-users-setting-value.avatar').value
                var data = {
                    name    : name,
                    surname : surname,
                    login   : login,
                    avatar  : avatar
                }

                Helpers.CreateInputBox('Type your password:', 'password', value => {
                    var sendProfile = Helpers.Data.GetData('profile')
                    sendProfile.password = value

                    Helpers.AJAX.Post(location.href, {
                        controller : 'AdminProfile',
                        action     : 'ChangeData',
                        id_admin   : adminId,
                        data       : data,
                        profile    : sendProfile
                    }).success(data => {
                        try {
                            var obj = JSON.parse(data)

                            if(obj.response == 'Success') {
                                editContainer.classList.remove('open-flex')

                                Helpers.CreateModal({
                                    title : 'Success!',
                                    content : 'Successfully changed admin data',
                                    success : true
                                })


                            } else {
                                Helpers.CreateModal({
                                    title : 'Error!',
                                    content : obj.reason,
                                    success : false
                                })
                            }
                        } catch(e) {
                            console.log(data)
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : 'Error while changing admin data(user id: ' + adminId + ')',
                                success : false
                            })
                        }
                    })
                })
            }
            
            if(saveEditUsers) {
                saveEditUsers.addEventListener('click', saveEditData)
            }
        };
        (() => {
            addTableAdminsEvents()
        })();
        (() => {
            var searchForm = document.querySelector('.search-form.admins')
            var adminsTable = document.querySelector('.table-main')

            var searchUsers = e => {
                e.preventDefault()

                var input      = e.currentTarget.parentElement.querySelector('.search-input').value
                var admins      = Helpers.Data.GetData('admins-list')
                var tableTbody = adminsTable.querySelector('tbody')
                var records = []

                for(var i = 0; i < admins.length; i++) {
                    var login = admins[i].login.toLowerCase()
                    var name  = admins[i].name.toLowerCase()
                    var surname = admins[i].surname.toLowerCase()
                    var inputl = input.toLowerCase()

                    if(name.indexOf(inputl) !== -1 || surname.indexOf(inputl) !== -1 || login.indexOf(inputl) !== -1) { //indexOf is the fastest way to check if string contains substring
                        records.push(admins[i])
                        continue;
                    }
                }

                if(records.length > 0) {
                    records = records.length > 15 ? records.splice(0, 15) : records
                    tableTbody.innerHTML = ''

                    var html = ''
                    for(var i = 0; i < records.length; i++) {
                        html += '<tr id-user="' + records[i].id +  '">\n'
                            html +=  '<td>' + records[i].id +      '</td>\n'
                            html +=  '<td>' + records[i].name +    '</td>\n'
                            html +=  '<td>' + records[i].surname + '</td>\n'
                            html +=  '<td>' + records[i].login +   '</td>\n'
                            html +=  '<td>' + records[i].email +   '</td>\n'
                            html +=  '<td>' + records[i].avatar.substr(0, 12) + '...' + '</td>\n'
                            html +=  '<td>' + records[i].level +   '</td>\n'
                            html +=  '<td>' + (records[i].active ? 'Now' : records[i].last_active) + '</td>\n'
                            html +=  '<td><div class="check-icon mngadmins" style="background-color:' + (records[i].enabled ? '#009921' : '#a90000') + '" active="' + (records[i].enabled ? '1' : '0') + '"></div></td>\n'
                            html +=  '<td><div class="edit-open admins">Edit</div></td>\n'
                        html +=  '</tr>'
                        
                    }

                    tableTbody.innerHTML = html
                    setTimeout(() => {
                        addTableAdminsEvents()
                    }, 50)
                    
                    //Helpers.CreateModal({
                  //      title : 'Info!',
                   //     content : 'Shown ' + records.length + (records.length > 1 ? ' records' : ' record'),
                    //    success : true
                   // })
                } else {
                  //  Helpers.CreateModal({
                  //      title : 'Info!',
                  //      content : 'No user found!',
                  //      success : false
                   // })
                }
            }

            if(searchForm) {
                searchForm.querySelector('.search-input').addEventListener('input', searchUsers)
            }
        })();

        (() => {
            var addAdminOptions = document.querySelectorAll('.add-admin-option')

            for(var i = 0; i < addAdminOptions.length; i++) {
                addAdminOptions[i].querySelector('input').addEventListener('focus', e => {
                    var option = e.currentTarget.parentElement
                    var label  = option.querySelector('label')
                    var input  = e.currentTarget

                    label.style.color = '#278500'
                    label.style.fontWeight = 'bold'

                    input.style.borderBottom = '3px solid black'
                })

                addAdminOptions[i].querySelector('input').addEventListener('blur', e => {
                    var option = e.currentTarget.parentElement
                    var label  = option.querySelector('label')
                    var input  = e.currentTarget

                    label.style.color = '#000'
                    label.style.fontWeight = '100'

                    input.style.borderBottom = '2px solid black'
                })
            }
        })();

        (() => {
            var adminAddForm = document.querySelector('.add-admin-form')

            var submitForm = e => {
                e.preventDefault()
                var name     = adminAddForm.querySelector('.add-admin-input.name').value
                var surname  = adminAddForm.querySelector('.add-admin-input.surname').value
                var login    = adminAddForm.querySelector('.add-admin-input.login').value
                var password = adminAddForm.querySelector('.add-admin-input.password').value
                var email    = adminAddForm.querySelector('.add-admin-input.email').value
                var avatar   = adminAddForm.querySelector('.add-admin-input.avatar').files[0]
                var formDataAvatar = new FormData()

                formDataAvatar.append('controller', 'Files')
                formDataAvatar.append('action', 'UploadFile')
                formDataAvatar.append('name', 'avatar')
                formDataAvatar.append('dir', 'Avatars')
                formDataAvatar.append('avatar', avatar)
                
                Helpers.AJAX.Post(location.href, formDataAvatar).success(data => {
                    try {
                        var obj = JSON.parse(data)
                        if(obj.response == 'Success') {
                            var formData       = new FormData()
                            var profileData = {
                                name     : name,
                                surname  : surname,
                                login    : login,
                                password : password,
                                email    : email,
                                avatar   : obj.data.dest
                            }

                            formData.append('controller', 'AdminProfile')
                            formData.append('action', 'CreateAdminAccount')
                            formData.append('profile_data', JSON.stringify(profileData))

                            Helpers.AJAX.Post(location.href, formData).success(datad => {
                                try {
                                    var objd = JSON.parse(datad)
                                    
                                    if(objd.response == 'Success') {
                                        Helpers.CreateModal({
                                            title : 'Success!',
                                            content : 'Successfully created admin account (id :' + objd.data.id + ').<br> Refresh to see changes.',
                                            success : true
                                        }, () => {
                                            location.reload()
                                        })
                                    } else {
                                        Helpers.CreateModal({
                                            title : 'Error!',
                                            content : objd.reason,
                                            success : false
                                        })
                                    }
                                } catch(e) {
                                    Helpers.CreateModal({
                                        title : 'Error!',
                                        content : 'Error while creating admin account',
                                        success : false
                                    })
                                }
                            })
                        } else {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : obj.reason,
                                success : false
                            })
                        }
                    } catch (e) {
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Error while uploading avatar',
                            success : false
                        })
                    }
                })

                
            }

            if(adminAddForm) {
                adminAddForm.addEventListener('submit', submitForm)
            }
        })();

        (() => {
            var addArticleForm    = document.querySelector('.add-article-form')
            if(!addArticleForm) return;
            var scopeGlobal = document.GlobalScope.GetVariable('create-article-scope')
            var linkTypeBtn       = addArticleForm.querySelector('.link-type-open')
            var linkTypeContainer = addArticleForm.querySelector('.link-type-container') 
            var radios            = linkTypeContainer.querySelectorAll('input[type=radio]')
            var newLink           = addArticleForm.querySelector('.link-settings').querySelector('.new-link').querySelector('.article-link')
            var articleTitle      = addArticleForm.querySelector('.title')
            var linkSettings      = addArticleForm.querySelector('.link-settings')
            var lastArticleId     = 1

            if(linkTypeBtn) {
                linkTypeBtn.addEventListener('click', e => {
                    e.preventDefault() //prevent refreshing (submitting form)

                    linkTypeContainer.classList.add('open-flex')
                })
            }


            Helpers.AJAX.Post(location.href, {
                controller : 'ArticlesAJAX',
                action     : 'GetLastArticle'
            }).success(data => {
                try {
                    var obj = JSON.parse(data)

                    if(obj.response === 'Success') {
                        lastArticleId = obj.data.id + 1
                    }

                
                } catch(e) {console.log(data)}
            })

            var makeLinkType = (title, type) => {
                var link = ''
                var alphabet = Helpers.GetLatinAlphabet()

                var clearTitle = function(title) {
                    var newTitle = title

                    if(!newTitle) {
                        newTitle = 'no-title'
                        return;
                    }

                    newTitle = newTitle.toLowerCase()

                    for(var i = 0; i < newTitle.length; i++) {
                        let char = newTitle.charAt(i)
                        
                        if(alphabet.indexOf(char.toLowerCase()) === -1 && !parseInt(char)) {
                            newTitle = newTitle.replaceAt(i, '-')
                        }
                    }

                    return newTitle
                }

                switch(type) {
                    case '1':
                        link = `/article/${lastArticleId}`
                        break;
                    case '2':
                        link = `/article?id=${lastArticleId}`
                        break;
                    case '3':
                        title = clearTitle(title)
                        link  = `/article/${title}`
                        break;
                    default:
                        link = ''
                        break;
                }

                return link
            }

            newLink.makeLinkType = makeLinkType

            if(articleTitle) {
                articleTitle.addEventListener('input', e => {
                    if(e.target.value) {
                        linkSettings.style.display = 'flex'

                        if(linkTypeContainer.getAttribute('active') === '3') {
                            var link = newLink.makeLinkType(e.target.value, linkTypeContainer.getAttribute('active'))
                            scope.SetVariable('article__titlelink', link)
                            newLink.innerHTML = `${link}`
                        }
                    } else {
                        linkSettings.style.display = 'none'
                    }
                })
            }            

            var setLinkType = e => {
                var value = e.currentTarget.value

                newLink.contentEditable = false
                linkTypeContainer.setAttribute('active', value)

                var link = newLink.makeLinkType(value, linkTypeContainer.getAttribute('active'))
                
                if(value === '3') {
                    newLink.contentEditable = true
                    scope.SetVariable('article__titlelink', link)
                }

                newLink.innerHTML = `${link}`
                setTimeout(() => {
                    linkTypeContainer.classList.remove('open-flex')
                }, 50)
            }

            window.addEventListener('click', e => {
                if(e.target !== newLink && linkTypeContainer.getAttribute('active') === '3') {
                    var expression = new RegExp('/article/', 'i')
                    var findTitle = newLink.innerText.replace(expression, '')
                    var makeLink = makeLinkType(findTitle, '3')
                    
                    newLink.innerText = makeLink //set
                }
            })

            if(radios) {
                for(var i = 0; i < radios.length; i++) {
                    radios[i].addEventListener('click', setLinkType)
                }
            }
        })();

        (() => {
            var selectMainContainer = document.getElementsByClassName('select-main-container article-states')[0]
            if(selectMainContainer) {
                var selectMain = selectMainContainer.querySelector('.select-main')
                var selectList = selectMainContainer.querySelector('.select-main-list')

                var select = e => {
                    var classList = selectList.classList

                    if(classList.contains('open-flex')) {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(180deg)')
                        selectList.style.opacity = '0%'
                        setTimeout(() => {
                            classList.remove('open-flex')
                        }, 100)
                        
                    } else {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(0deg)')
                        classList.add('open-flex')
                        setTimeout(() => {
                            selectList.style.opacity = '100%'
                        }, 100)
                    }
                }

                if(selectMain) {
                    selectMain.addEventListener('click', select)
                }

                var selectOptions = selectList.getElementsByClassName('select-main-option')
                var selectItem = e => {
                    for(var i = 0; i < selectOptions.length; i++) {
                        if(selectOptions[i] != e.currentTarget) {
                            selectOptions[i].setAttribute('active', 'false')
                        }
                    }

                    selectMain.querySelector('.select-main-title').innerText = e.currentTarget.querySelector('.select-main-option-content').innerText
                    e.currentTarget.setAttribute('active', 'true')
                }

                for(var i = 0; i < selectOptions.length; i++) {
                    selectOptions[i].addEventListener('click', selectItem)
                }
            }
        })();

        (() => {
            var selectMainContainer = document.getElementsByClassName('select-main-container article-categories')[0]
            if(selectMainContainer) {
                var selectMain = selectMainContainer.querySelector('.select-main')
                var selectList = selectMainContainer.querySelector('.select-main-list')

                var select = e => {
                    var classList = selectList.classList

                    if(classList.contains('open-flex')) {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(180deg)')
                        selectList.style.opacity = '0%'
                        setTimeout(() => {
                            classList.remove('open-flex')
                        }, 100)
                        
                    } else {
                        selectMain.style.setProperty('--arrow-rotation', 'rotate(0deg)')
                        classList.add('open-flex')
                        setTimeout(() => {
                            selectList.style.opacity = '100%'
                        }, 100)
                    }
                }

                if(selectMain) {
                    selectMain.addEventListener('click', select)
                }

                var selectOptions = selectList.getElementsByClassName('select-main-option')
                var selectItem = e => {
                    for(var i = 0; i < selectOptions.length; i++) {
                        if(selectOptions[i] != e.currentTarget) {
                            selectOptions[i].setAttribute('active', 'false')
                        }
                    }

                    selectMain.querySelector('.select-main-title').innerText = e.currentTarget.querySelector('.select-main-option-content').innerText
                    e.currentTarget.setAttribute('active', 'true')
                }

                for(var i = 0; i < selectOptions.length; i++) {
                    selectOptions[i].addEventListener('click', selectItem)
                }
            }
        })();

        (() => {
            var wysiwygContainer = document.querySelector('.wysiwyg-static-container')
            if(!wysiwygContainer) return;

            var toolbar  = wysiwygContainer.querySelector('.wysiwyg-toolbar')
            var textarea = wysiwygContainer.querySelector('.textarea')

            var bold        = toolbar.querySelector('.bold')
            var italic      = toolbar.querySelector('.italic')
            var underline   = toolbar.querySelector('.underline')
            var color       = toolbar.querySelector('.color')
            var alignLeft   = toolbar.querySelector('.align-left')
            var alignCenter = toolbar.querySelector('.align-center')
            var alignRight  = toolbar.querySelector('.align-right')
            var fontSize    = toolbar.querySelector('.font-size')
            var numerList   = toolbar.querySelector('.list')    
        
            var addBold = e => {
                document.execCommand('bold', false, null)
            }
            var addItalic = e => {
                document.execCommand('italic', false, null)
            }
            var addUnderline = e => {
                document.execCommand('underline', false, null)
            }
            var addColor = e => {
                var color = e.target.value
                document.execCommand('foreColor', false, color)
            }
            var addAlignL = e => {
                document.execCommand('justifyLeft', false, null)
            }
            var addAlignC = e => {
                document.execCommand('justifyCenter', false, null)
            }
            var addAlignR = e => {
                document.execCommand('justifyRight', false, null)
            }
            var addFontSize = e => {
                var size = e.target.value
                
                document.execCommand('fontSize', false, 1);
                var fontSize = ((parseFloat(size)) * 0.0625).toString() + 'rem';
                var els = textarea.querySelectorAll('[size]');

                for (var i = 0; i !== els.length; i++) {
                    els[i].style.fontSize = fontSize;
                    els[i].removeAttribute("size");
                }
            }
            var applyList = e => {
                document.execCommand('insertOrderedList', false, null)
            }

            var clearPaste = e => {
                e.preventDefault();
            
                var ev   = e.originalEvent || e;
                var data = ev.clipboardData;
                var text = data.getData('text/plain');
            
                document.execCommand("insertHTML", false, text);
            }
            

            var insertHTML = (dom) => {
                var sel, range;
                if (window.getSelection && (sel = window.getSelection()).rangeCount) {
                    range = sel.getRangeAt(0);
                    range.collapse(true);
                    range.insertNode(dom);
            
                    // Move the caret immediately after the inserted span
                    range.setStartAfter(dom);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }

            bold.addEventListener('mousedown', addBold)
            italic.addEventListener('mousedown', addItalic)
            underline.addEventListener('mousedown', addUnderline)
            color.addEventListener('change', addColor)
            alignLeft.addEventListener('mousedown', addAlignL)
            alignCenter.addEventListener('mousedown', addAlignC)
            alignRight.addEventListener('mousedown', addAlignR)
            fontSize.addEventListener('change', addFontSize)
            numerList.addEventListener('click', applyList)

            textarea.addEventListener('paste', clearPaste)
            window.addEventListener('keydown', e => {
                if(e.keyCode == 9) {
                    var tabSize = 4
                    var tab     = ''

                    for(var i = 0; i < tabSize; i++) {
                        tab+='\xa0' //space character
                    }

                    if(window.getSelection && window.getSelection().type === 'Range') {
                        var el = document.createElement('tab')
                        el.innerText = tab
                        textarea.appendChild(el)
                        insertHTML(el)
                    } else {
                        document.execCommand('insertText', false, tab)
                    }
                    e.preventDefault()
                }
            })
        })();

        (() => {
            var addArticleForm = document.querySelector('.add-article-form')
            if(!addArticleForm)return;

            var submitBtn = addArticleForm.querySelector('input[type=submit]')

            var createArticle = e => {
                e.preventDefault()

                var title    = addArticleForm.querySelector('.title').value
                var linkType = addArticleForm.querySelector('.link-type-container').getAttribute('active')
                var status   = addArticleForm.querySelector('.select-main-list.article-states').querySelector('.select-main-option[active=true]').getAttribute('value')
                var category = addArticleForm.querySelector('.select-main-list.article-categories').querySelector('.select-main-option[active=true]').getAttribute('value')
                var commentsEnabled = 1
                var image    = addArticleForm.querySelector('#file-system-upload').files[0]
                var content  = addArticleForm.querySelector('.textarea').innerHTML
                var authorId = Helpers.Data.GetData('profile').id

                var createData = {
                    title            : title,
                    link             : link,
                    content          : content,
                    image            : image,
                    link_type        : linkType,
                    category         : category,
                    state            : status,
                    comments_enabled : commentsEnabled,
                    author_id        : authorId
                }

                for(var key in createData) {
                    if(!createData[key]){
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : `Key '${key}' has to be specified`,
                            success : false
                        })

                        return;
                    }
                }

                var formData = new FormData()

                formData.append('controller', 'ArticlesAJAX')
                formData.append('action', 'CreateArticle')
                formData.append('image', image)
                formData.append('create_data', JSON.stringify(createData))

                Helpers.AJAX.Post(location.href, formData).success(data => {
                    try {
                        var obj = JSON.parse(data)

                        if(obj.response === 'Success') {
                            Helpers.CreateModal({
                                title : 'Success!',
                                content : 'Successfully created a new article!',
                                success : true
                            })

                            setTimeout(() => {
                                location.href = '/manage-articles'
                            }, 2000)
                        } else {
                            Helpers.CreateModal({
                                title : 'Error!',
                                content : obj.reason,
                                success : false
                            })
                        }
                    } catch(e) {
                        console.log(data)
                        Helpers.CreateModal({
                            title : 'Error!',
                            content : 'Unknwon error while creating article!',
                            success : false
                        })
                    }
                })
            }

            addArticleForm.addEventListener('submit', createArticle)

            var fileUploadContainer = document.querySelectorAll('.file-system.file-upload-container')
            var maxWidthRem = 5.5

            fileUploadContainer.forEach(el => {
                var fileInput    = el.querySelector('#file-system-upload, input[type=file]')
                var currentView  = el.querySelector('.file-upload-current')
                var imageView    = currentView.querySelector('.image-upload-view')
                var imagePreview = currentView.querySelector('.image-upload-preview-onhover')
                var currentLabel = currentView.querySelector('.filename')
                var currentImage = undefined

                var xPx  = imageView.getAttribute('x-px')

                fileInput.addEventListener('change', e => {
                    var file = fileInput.files[0]
                    var link = URL.createObjectURL(file)
                    var ext  = '.' + file.name.split('.')[1]
                    var type = file.type
                    var imageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']
                    
                    if(imageTypes.indexOf(type) !== -1) {
                        var calculateRem = (parseFloat(xPx) * 0.0625)
                        currentImage = new Image()
                        
                        currentImage.addEventListener('load', e => {
                            URL.revokeObjectURL(link)
                        })

                        currentImage.src = link

                        imageView.style.backgroundImage = `url(${link})`
                        imageView.style.width = (calculateRem > maxWidthRem ? maxWidthRem : calculateRem).toString() + 'rem'
                        
                        //imageView.style.width = (parseFloat(xPx) * 0.0625 / 14).toString() + 'rem' //scale it 1:14 a lot but yeah
                    } else {
                        imageView.style.backgroundImage = `url()` //reset if not image
                        imageView.style.width = ``
                    }

                    currentLabel.innerText = file.name.length > 10 ? ((file.name.substr(0, 6)).indexOf(ext) == -1 ? file.name.substr(0, 6) + '...' + ext : file.name.substr(0, 6)) : file.name

                    

                    imageView.addEventListener('click', e => {
                        if(!currentImage)return;
                        var image = imagePreview.querySelector('.image')

                        image.style.backgroundImage = `url(${link})`
                        //imagePreview.style.width = calculateRem.toString() + 'rem'
                        imagePreview.classList.add('open-flex')

                        var calculateX = (parseFloat(currentImage.width) * 0.0625)
                        var calculateY = (parseFloat(currentImage.height) * 0.0625)

                        image.style.width = calculateX.toString() + 'rem'
                        image.style.height = calculateY.toString() + 'rem'
                        //Helpers.ScaleElementToParent(image, imagePreview)
                    })

                    window.addEventListener('click', e => {
                        if(!currentImage)return;
                        if(e.target !== imageView && e.target !== imagePreview.querySelector('.image')) {
                            imagePreview.classList.remove('open-flex')
                        }
                    })

                    window.addEventListener('scroll', e => {
                        if(!currentImage)return;
                        if(imagePreview.classList.contains('open-flex')) {
                            imagePreview.classList.remove('open-flex')
                        }
                    })
                })

                
            })
            
        })();
    })
}).catch(err => {
    /*
    *  This is only for developers
    */
    console.error(err)
})

/*
* A few functions that can be used while developing the application
*/

var acceptWarning = () => {
    localStorage.setItem('warningHack', true)
}