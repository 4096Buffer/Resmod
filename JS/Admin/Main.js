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
                'pages-list-scope'
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
                      text: "Statystyki wyświetleń stron"
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
            var modulesList = document.querySelector('#modules-list');
            var onChange = e => {
                var target = e.target.options[e.target.selectedIndex]
                var boxes  = document.getElementsByClassName('modules-add-list-box')
                var find   = undefined

                for(var i = 0; i < boxes.length; i++) {
                    if(boxes[i].getAttribute('show-value') == target.value) {
                        find = boxes[i]
                        break;
                    }
                }

                if(!find) {
                    for(var i = 0; i < boxes.length; i++) {
                        boxes[i].style.display = 'none'
                    }
                } else {
                    find.style.display = 'block';
                }
                
            }

            if(modulesList) {
                modulesList.addEventListener('change', onChange)
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
                                content.style.opacity          = '100%'
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
                e.preventDefault();
                var pageId     = scope.GetVariable('ajax-page-id')
                var templateId = scope.GetVariable('ajax-template-id')

                if(pageId == null|| templateId == null) {
                    alert('Please select page and template')
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
                        alert('Successfully changed template!')
                        location.reload()
                    } else {
                        alert('Unknown error! Please refresh the page and try again')
                    }
                })
            }
            if(submitForm) {
                submitForm.parentElement.addEventListener('submit', submit)
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
            var selectMainContainer = document.getElementsByClassName('select-main-container module-groups2')[0]
            if(selectMainContainer) {
                var selectMain = selectMainContainer.querySelector('.select-main')
                var selectList      = selectMainContainer.querySelector('.select-main-list')

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

                var showList = group => {
                    var modules = Helpers.Data.GetData('modules-list')
                    var container = document.querySelector('.modules-list-container')
                    var currentModules = []

                    container.innerHTML = ''
                    
                    for(var i = 0; i < modules.length; i++) {
                        if(modules[i].group == group) {
                            currentModules.push(modules[i])
                        }
                    }
                    if(currentModules.length > 0) {
                        var search_enabled = currentModules.length > 12 ? true : false
                        if(search_enabled) {
                            currentModules.splice(12, currentModules.length)
                        }

                        var v = ''
                        v += '<div class="modules-list">'
                            for(var i = 0; i < currentModules.length; i++) {
                                v += '  <div class="modules-list-item" module-id="' + currentModules[i].id + '">'
                                    v += currentModules[i].title
                                v += '  </div>'
                            }
                        v += '</div>'
                        
                        container.innerHTML = v
                        document.GlobalScope.CallFunction('content-module-upload')
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
                    showList(e.currentTarget.querySelector('.select-main-option-content').innerText)
                    
                }

                for(var i = 0; i < selectOptions.length; i++) {
                    selectOptions[i].addEventListener('click', selectItem)
                }
            }

        })();

        (() => {
            var addModuleBar = document.querySelector('.add-module-bar')
            var moduleAddBox = document.querySelector('.module-add-box')

            var click = e => {
                moduleAddBox.style.display = 'block'
            }
            if(addModuleBar) {
                addModuleBar.addEventListener('click', click)
            }

            var buttonSave = document.querySelector('.button-fixed, .save')

            var saveContent = e => {
                var sendData = Content.GetSendJSON()

                Helpers.AJAX.Post(location.href, {
                    controller : 'Modules',
                    action     : 'SaveCMS',
                    saveData   : sendData
                }).success(data => {
                    var obj = JSON.parse(data)

                    if(obj.response == 'Success') {
                        alert('Success')
                    } else {
                        alert('Error')
                    }
                })
            }

            if(buttonSave) {
                buttonSave.addEventListener('click', saveContent)
            }

            var moduleDeleteBtn = document.querySelectorAll('.module-live-edit-icon, .delete') //delete button ADD!!!
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
                        } else {
                            alert('Error changing active')
                        }
                    } catch(e) {
                        console.log(e)
                        alert('Error changing active')
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

                settingsBox.setAttribute('page-id', e.currentTarget.parentElement.parentElement.getAttribute('id-page'))
                classList.add('open-flex')
            }

            for(var i = 0; i < settingsPageBtn.length; i++) {
                settingsPageBtn[i].addEventListener('click', openSettings)
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