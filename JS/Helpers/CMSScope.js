Helpers.ModuleVariableScope = (() => {
   
    
    Helpers.AddAwakeCallback(() => {
        variables = Helpers.Scope.CreateScope('cms-variables')
        page = Helpers.Data.GetData('page')
        var end = () => {
            console.log(variables.GetVariable('PageVariables'))
        }
        Helpers.AJAX.Post(location.href, {
            controller : 'Page',
            action     : 'GetPageVariables',
            id         : page.id
        }).success(data => {
            var object = JSON.parse(data)
            if(object.response = 'Success') {
                variables.SetVariable('PageVariables', object.data)
                end()
            }
        })
        
    })
})()