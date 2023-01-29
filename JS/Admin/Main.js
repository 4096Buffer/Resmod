DOMHelper.waitForAllElm().then(() => {
	setTimeout(() => {
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
        
    }, 500)
}).catch(err => {
    /*
    *  This is only for developers
    */
    console.error(err)
})