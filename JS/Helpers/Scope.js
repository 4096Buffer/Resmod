Helpers.Scope = (() => {
    var scopes = []
    var ScopeObject = function(name, variables = {}) {
        this.name       = name
        this.variables  = variables
    }

    ScopeObject.prototype.name      = null
    ScopeObject.prototype.variables = null
    
    ScopeObject.prototype.GetAllVariables = function() {
        console.log(this.variables)
        return this.variables
    }

    ScopeObject.prototype.SetVariable = function(name, value) {

        //for(var i = 0; i < this.variables.length; i++) {
        //    if(this.variables[i] == object) {
        //        if(this.variables[i].static) {
        //            console.error('Cannot edit static variable \'' + this.variables[i].name + '\' ')
        //            return;
        //        }
        //    }
        //}

        this.variables[name] = value
    }

    ScopeObject.prototype.CallFunction = function(name, args = []) {
        if(name in this.variables) {
            var variable = this.variables[name]

            if(typeof variable == 'function') {
                return variable.apply(this, args)
            }
        }

        console.error('Cannot call function. The function doesnt exists \'' + name + '\' or the variable is not function')
    }

    ScopeObject.prototype.GetVariable = function(name) {
        if(name in this.variables) {
            return this.variables[name]
        }

        return null
    }

    ScopeObject.prototype.RemoveVariable = function(name) {
        if(name in this.variables) {
            delete this.variables[name]
        }
    }

    var globalScope = undefined

    window.addEventListener('load',function(e) {
        document.GlobalScope = globalScope = new ScopeObject() 
    })

    return {
        CreateScope : function(name, variables = {}) {
            if(!name) {
                name = 'Scope-' + Math.round(Math.random() * 10000000).toString(16)
            }

            var scope = new ScopeObject(name, variables)
            scopes.push(scope)
            return scope
        },
        RemoveScope : function(name) {
            for(var i = 0; i < this.scopes.length; i++) {
                if(this.scopes[i].name = name) {
                    this.scopes.shift(this.scopes[i])
                }
            }
        }
    }
})()