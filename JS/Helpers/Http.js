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

      var loadScreen = document.querySelector('.load-screen')

      var endLoading = function() {
        if(loadScreen) {
          loadScreen.classList.add('load-screen--hidden')
          loadScreen.addEventListener('transitionend', e => {
            loadScreen.style.display = 'none'
          })
        }
      }
      
      loadScreen.style.display = 'flex'
      
      xhr.open("POST", url, true);
          
      //xhr.setRequestHeader("Content-type","multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2));
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
      xhr.send(JSON.stringify(data))
          
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