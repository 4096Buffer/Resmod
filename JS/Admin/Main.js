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
            var logout    = (e) => {
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
            var onChange = (e) => {
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

            modulesList.addEventListener('change', onChange)
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