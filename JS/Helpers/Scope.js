Helpers.Scope = (() => {
    var scopes = []
    var ScopeObject = function(name, variables = []) {
        this.name       = name
        this.variables  = []
    }

    ScopeObject.prototype.name      = null
    ScopeObject.prototype.variables = null
    

    ScopeObject.prototype.SetVariable = function(name, value, static = false) {
        object = {
            name   : name,
            value  : value,
            static : static
        }

        for(var i = 0; i < this.variables.length; i++) {
            if(this.variables[i] == object) {
                if(this.variables[i].static) {
                    console.error('Cannot edit static variable \'' + this.variables[i].name + '\' ')
                    return;
                }
            }
        }
        this.variables.push(object)
    }

    ScopeObject.prototype.GetVariable = function(name) {
        for(var i = 0; i < this.variables.length; i++) {
            if(this.variables[i].name = name) {
                return this.variables[i]
            }
        }
    }

    ScopeObject.prototype.RemoveVariable = function(name) {
        for(var i = 0; i < this.variables.length; i++) {
            if(this.variables[i].name = name) {
                this.variables.shift(this.variables[i])
            }
        }
    }

    var x = new ScopeObject()
    return {
        CreateScope : function(name, variables = []) {
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