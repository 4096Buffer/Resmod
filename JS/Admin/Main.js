DOMHelper.waitForAllElm().then(() => {
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
            var logoutBtn = document.querySelector(".button-logout")
            var logout    = e => {
                var controller = e.target.getAttribute('ajax-controller')
                var action     = e.target.getAttribute('ajax-action')
                var sendData = {
                    controller : controller,
                    action     : action
                }
                
                AJAX.Post(location.href, sendData).success(data => {
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
        }
        
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

               AJAX.Post(location.href, sendData).success(data => {
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
                
                AJAX.Post(location.href, sendData).success(data => {
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
                            e.target.style.background      = 'none'
                            e.target.style.backgroundColor = '#262626'

                            content.setAttribute('active', 'true')
                        } else {
                            content.style.display          = 'none'
                            e.target.style.backgroundColor = null
                            e.target.style.background      = 'none'

                            content.setAttribute('active', 'false')
                        }
                    } else {
                        location.href = e.target.getAttribute('href')
                    }
                }

                collaps[i].addEventListener('click', openClose)
            }
        })();

        (() => {
            var checkIcons = document.getElementsByClassName('check-icon')
            var active = undefined

            var setActive = el => {
                active = el
            }

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

                e.target.style.setProperty('--checkicon-b-background', '#2a2a2a')
                e.target.style.setProperty('--checkicon-a-background', '#2a2a2a')

                row.setAttribute('active', 'true')
                row.classList.add('active-row-pages-templates')

                active = row
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

            selectText.addEventListener('click', selectClick);

            var selectOptions = document.getElementsByClassName('templates-select-option')
            
            var selectOptionClick = e => {
                selectText.innerText = e.target.innerText
            }
            for(var i = 0; i < selectOptions.length; i++) {
                selectOptions[i].addEventListener('click', selectOptionClick)
            }

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