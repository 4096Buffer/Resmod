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

            console.log(sd_)
            AJAX.Post(location.href, sd_).success(data => {
                var ro_ = data

                console.log(ro_)
                var de_ = JSON.parse(ro_)

            })
        }
        
        
        AJAX.Post(location.href, sd).success(data => {
            var ro = data
            console.log(ro)
            var de = JSON.parse(ro)
            
            if(de.response == 'Success') {
                id = de.data.id
                se()
            } 
            console.log(de.response)
            
            
            
        })
        
    } catch(e) {
        console.error(e)
    }
}

window.addEventListener('load', lo_)