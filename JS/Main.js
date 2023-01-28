DOMHelper.waitForAllElm().then(() => {
	setTimeout(() => {
        (() => {
            var opacity = 0
            var max = 100
            var interval = null

            var minusOpacity = () => {
                if(opacity == max) {
                    clearInterval(interval)
                    return
                } 
                opacity+=1
                document.body.style.opacity = opacity + "%"
            }

            interval = setInterval(minusOpacity, 15)


        })();

        (() => {
            if(location.pathname == '/login') {
                
                var adminForm = document.querySelector('.admin-login-form')
                var adminFormLogin = document.querySelector('#admin-login-form-name')
                var adminFormPassword = document.querySelector('#admin-login-form-pass')
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
                            console.log(data)
                            var decodedJson = JSON.parse(data)
                        }
                        catch(e) {  
                            console.log("Can't parse json. (responseObject)")
                            console.log(responseObject)
                        }
                            
                        var response = decodedJson.response
                        var reason   = decodedJson.reason
                        
                        if(response == "Error") {
                            adminFormInfoText.innerHTML = reason
                                
                        } else if(response == "Success") {
                            location.href = "/panel"
                            
                        } else {
                            alert("Invalid response data")
                            
                        }
                    }

                    AJAX.Post(location.href, sendData).success(login)
                    console.log(responseObject)
                    
                    
                }

                adminForm.addEventListener('submit', adminFormSubmit)
             }
        })();
        
    }, 500)
}).catch(e => {
    console.error(e)
})

