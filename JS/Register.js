var lo_ = (e) => {
    try {
        var id;
        var sd = {
            controller : 'Page',
            action     : 'GetPageId',
            pathname   : location.pathname
        }
        
        var se = () => {
            var sd_ = {
                controller : 'Page',
                action     : 'AddView',
                id         : id
            }
            AJAX.Post(location.href, sd_).success(data => {
                var ro_ = data
                var de_ = JSON.parse(ro_)

            })
        }
        
        
        AJAX.Post(location.href, sd).success(data => {
            var ro = data;
            var de = JSON.parse(ro)
            
            if(de.response == 'Success') {
                id = de.data.id
                se()
            } 
        })
        
    } catch(e) {
        
    }
}

var requestMethod = document.querySelector('#request-method').getAttribute('content');

if(requestMethod == 'GET') {
    window.addEventListener('load', lo_)
}