Helpers.AJAX = (() => {
  return {
    Post: (url, data = null) => {
      var xhr = new XMLHttpRequest();
      var success = undefined;
      var error   = undefined;
      var finish  = undefined;
      var funs = {
        success: function(fun) {
          success = fun;
          return funs;
        },
        error: function(fun) {
          error = fun;
          return funs;
        },
        finish: function(fun) {
          finish = fun;
          return funs;
        }
      };
          
          
      xhr.open("POST", url, true);
          
      //xhr.setRequestHeader("Content-type","multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2));
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

      xhr.send(JSON.stringify(data))
          
      xhr.onreadystatechange = () => {
        if(xhr.readyState != 4) return;
              
        if(xhr.status >= 200 && xhr.status < 300) {
          if(success) {
              success(xhr.responseText)
          }
                  
        } else {
          if(error) {
            error(xhr.responseText)
          }
        }  

        if(finish) {
            finish(xhr.responseText)
        }
      }
        
      
      return funs;
          
          
    },

    Get: (url, data = null) => {
      /**
       * @Rebuild the whole GET function
       */
      
    }
  } 

  
})()