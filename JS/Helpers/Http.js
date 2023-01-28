
const AJAX = {
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
        
        var return_ = (obj) => {
            retData = obj
            
        }
        
        xhr.send(JSON.stringify(data))
        
		xhr.onreadystatechange = () => {
            if(xhr.readyState != 4) return;
			console.log(xhr.readyState);
            
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
		var success = false;
		var xhr = new XMLHttpRequest();

		xhr.open("GET", url, true);

		xhr.setRequestHeader('Content-Type', 'application/json');

		xhr.onreadystatechange = () => {
			if(this.readyState != 4) return;
			var success = false;
            
			if(this.status == 200) {
				success = true
				
			}

			return { succ : success, stat : status , res : this.responseText}
		}
        
        xhr.send(JSON.stringify(data))
	}


}