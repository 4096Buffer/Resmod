Helpers.DOMHelper.waitForAllElm().then(() => {
	setTimeout(() => {
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
                'templates-scope'
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
                    var decode = JSON.parse(data)
                    
                    if(decode.response = 'Success') {
                        location.href = '/'
                    }

                })
                
            }
            
            logoutBtn.addEventListener('click', logout);
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

            avatar.addEventListener('click', avatarClick)
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
            var checkIcons = document.getElementsByClassName('check-icon')
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
                    //console.log(childsRow[4].innerText)
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

            submitForm.parentElement.addEventListener('submit', submit)

        })();
        
    }, 500)
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