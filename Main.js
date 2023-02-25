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
            var opacity = 0
            var max = 100
            var interval = null

            var minusOpacity = () => {
                if(opacity == max) {
                    clearInterval(interval)
                    return
                } 
                opacity+=10
                document.body.style.opacity = opacity + "%"
            }

            interval = setInterval(minusOpacity, 15)


        })();

        (() => {
            if(location.pathname == '/login') {
                
                var adminForm = document.querySelector('.admin-login-form-box').querySelector('form')
                var adminFormLogin = document.querySelector('#admin-login-login')
                var adminFormPassword = document.querySelector('#admin-login-pass')
                var adminFormController = adminForm.getAttribute('ajax-controller')
                var adminFormInfoText = adminForm.querySelector('.info-text')
                var adminFormAction = adminForm.getAttribute('ajax-action')
                
                var adminFormSubmit = (e) => {
                    e.preventDefault();
                    
                    var login = adminFormLogin.value;
                    var pass = adminFormPassword.value;
                    var sendData = {
                        login : login,
                        password : pass,
                        controller : adminFormController,
                        action : adminFormAction
                    }

                    var login = (data) => {
                        try {
                            var decodedJson = JSON.parse(data)
                            var response = decodedJson.response
                            var reason   = decodedJson.reason
                            
                            if(response == "Error") {
                                adminFormInfoText.innerHTML = reason
                            } else if(response == "Success") {
                                location.href = "/dashboard-admin"
                            } else {
                                alert("Invalid response data")
                            }
                        }
                        catch(e) { 
                            console.log(data)
                            console.log("Can't parse json. (responseObject)")
                        }
                    }

                    Helpers.AJAX.Post(location.href, sendData).success(login)
                }

                adminForm.addEventListener('submit', adminFormSubmit)
             }
        })();
        
    }, 500)
}).catch(e => {
     /*
    *  This is only for developers
    */
    console.error(e)
})

/*
* A few functions that can be used while developing the application
*/

var acceptWarning = () => {
    localStorage.setItem('warningHack', true)
}