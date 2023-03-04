Helpers.AJAX = (() => {
  return {
    Post: (url, data = null, headers = {}) => {
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

      var loadScreen = document.querySelector('.load-screen')

      var endLoading = function() {
        if(loadScreen) {
          loadScreen.classList.add('load-screen--hidden')
          loadScreen.addEventListener('transitionend', e => {
            loadScreen.style.display = 'none'
          })
        }
      }
      
      //loadScreen.style.display = 'flex'
      
      xhr.open("POST", url, true);

      if(data instanceof FormData) {
        contentType = null
      } else if(typeof data === 'object' && data !== null) {
        contentType = 'application/json'
        data = JSON.stringify(data)
      } else {
        contentType = 'application/x-www-form-urlencoded'

        if(typeof data == 'string' || typeof data == 'number' || typeof data == 'boolean') {
          data = data.toString()
        } else {
          data = null
        }

      }
          
      //xhr.setRequestHeader("Content-type","multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2));
      if(contentType) {
        xhr.setRequestHeader('Content-type', contentType)
      }

      for(var key in headers) {
        xhr.setRequestHeader(key, headers[key])
      }
      
      xhr.send(data)
      
      xhr.onreadystatechange = () => {
        if(xhr.readyState != 4) return;
              
        if(xhr.status >= 200 && xhr.status < 300) {
          if(success) {
              endLoading()
              success(xhr.responseText)
          }
                  
        } else {
          if(error) {
            endLoading()
            error(xhr.responseText)
          }
        }  

        if(finish) {
            endLoading()
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