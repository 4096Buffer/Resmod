const Profile = {
    sendData : {
        controller : 'Login',
        action     : 'GetAdminProfile'
    },
    
    isLogged : () => {
        var responseObject = AJAX.Post(location.href, Profile.sendData)
        var decode = JSON.parse(responseObject.res)
        
        if(decode.response == 'Success') {
          return true;  
        } else {
            return false;
        }
    },
    
   GetData : () => {
       var responseObject = AJAX.Post(location.href, Profile.sendData)
       var decode = JSON.parse(responseObject.res)
       
       if(decode.response == 'Success') {
           return decode.data
       } else {
           return null
       }
   }
}