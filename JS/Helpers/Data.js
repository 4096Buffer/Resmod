Helpers.Data = (() => {
    var data = {}

    return {
        AddData: obj => {
            if(typeof obj !== 'object') {
                return;
            }
            
            for(var key in obj) {
                data[key] = obj[key];
            }
        },

        GetData: name => {
            if(name in data) {
                return data[name];
            } else {
                return null;
            }
        }
    }
})()