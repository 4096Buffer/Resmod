var Content = (function() {

    if(Helpers.GetCurrentViewMode().mode !== 'LiveEdit') {
        return;
    }
    
    
    var VariablesTypes = function() {
        this.types = {}
    }

    VariablesTypes.prototype.types = null;

    VariablesTypes.prototype.AddType = function(name, obj) {
        if(!(name in this.types)) {
            this.types[name] = obj
        }
    }

    VariablesTypes.prototype.CreateType = function(name, variable) {
        if(name in this.types) {
            return new this.types[name](variable);
        }

        return undefined;
    }

    var InputType = function(variable) {
        this.variable = variable

        var DOM = this.variable.GetDOM()

        this.DOM = DOM
        this.DOM.contentEditable = true
        
        this.DOM.addEventListener('keydown', e => {
            setTimeout(() => {
                var newValue = this.DOM.innerHTML
                if(this.variable.value !== newValue) {
                    this.variable.ChangeValue(newValue) 
                }
            }, 16)
        })

        DOM.addEventListener('blur', e => {
            if(DOM.innerText === '') {
                DOM.innerText =  this.variable.oldValue          
                this.variable.value = this.variable.oldValue     
            } 
        })
    }

    InputType.prototype.variable = null;
    
    InputType.prototype.Remove = function() {
        this.variable = null
        this.DOM.contentEditable = false
    }

    var WysiwygType = function(variable) {
        this.variable = variable
        var wysiwygObject = Content.Addons.Wysiwyg.Build(variable)
        var DOM = this.variable.GetDOM()

        this.wysiwygObject = wysiwygObject
        this.DOM           = DOM
        this.DOM.contentEditable = true

        wysiwygObject.BuildDOM()
        wysiwygObject.PrepareObjectDOM()
    }
    
    WysiwygType.prototype.variable = null

    WysiwygType.prototype.Remove = function() {
        this.variable = null
        this.DOM.contentEditable = false

        this.wysiwygObject.RemoveObject()

        this.wysiwygObject = null
    }

    var LinkType = function(variable) {
        
    }

    var CMSModule = function(id, global, globalExcept, idDefModule, idPage, sort) {

        this.id           = id
        this.global       = global
        this.globalExcept = globalExcept
        this.idDefModule  = idDefModule
        this.idPage       = idPage
        this.sort         = sort
        this.variables    = []
    }

    CMSModule.prototype.id           = null
    CMSModule.prototype.global       = null
    CMSModule.prototype.globalExcept = null
    CMSModule.prototype.idDefModule  = null
    CMSModule.prototype.idPage       = null
    CMSModule.prototype.sort         = null
    CMSModule.prototype.variables    = null

    CMSModule.prototype.FetchVariables = function() {
        if(getVariables) {
            for(var i = 0; i < getVariables.length; i++) {
                if(getVariables[i].module.id == this.id) {
                    var variables = getVariables[i].variables

                    for(var j = 0; j < variables.length; j++) {
                        var v = variables[j]
                        var buildVariable     = new CMSVariable(this, v.id, v.name, v.default_value, v.id_variable, v.type, v.value)
                        var buildVariableType = variablesTypes.CreateType(v.type, buildVariable)
                        
                        this.variables.push(buildVariableType)
                    }
                }
            }
        }
    }

    CMSModule.prototype.GetVariable = function(name) {
        for(var i = 0; i < this.variables.length; i++) {
            if(this.variables[i].name == name) {
                return this.variables[i]
            }
        }
    }

    CMSModule.prototype.GetDOM = function() {
        var modulesDom = document.querySelectorAll('cmsmodule')

        for(var i = 0; i < modulesDom.length; i++) {
            if(modulesDom[i].getAttribute('module-id') == this.id) {
                return modulesDom[i]
            }
        }
    }

    var CMSVariable = function(parent, id, name, defaultValue, idVariable, type, value) {
        
        this.parent       = parent
        this.id           = id
        this.name         = name
        this.defaultValue = defaultValue
        this.idVariable   = idVariable
        this.type         = type
        this.oldValue     = value
        this.value        = value
        this.changeEvent  = {}
    }

    CMSVariable.prototype.parent       = null;
    CMSVariable.prototype.name         = null;
    CMSVariable.prototype.id           = null;
    CMSVariable.prototype.defaultValue = null;
    CMSVariable.prototype.idVariable   = null;
    CMSVariable.prototype.type         = null;
    CMSVariable.prototype.oldValue     = null;
    CMSVariable.prototype.value        = null;

    CMSVariable.prototype.GetDOM = function() {
        var parentDom = this.parent.GetDOM()
        var elements  = parentDom.querySelectorAll('cmselement')

        for(var i = 0; i < elements.length; i++) {
            if(elements[i].getAttribute('var-name') == this.name) {
                return elements[i]
            }
        }
    }

    CMSVariable.prototype.ChangeValue = function(newValue) {
        this.value = newValue
        console.log('Change value of variable!', this)
    }
    

    var event         = Helpers.CreateEventManager()
    var getVariables  = []
    var variablesTypes = new VariablesTypes()
    var modules       = []

    var addVariablesTypes = function() {
        variablesTypes.AddType('InputType', InputType)
        variablesTypes.AddType('WysiwygType', WysiwygType)
    }

    var createCMSModules = function() {
        for(var i = 0; i < getVariables.length; i++) {
            var current = getVariables[i].module
            var buildModule = new CMSModule(current.id, current.global, current.global_except, current.id_module, current.id_page, current.sort)        //id, global, globalExcept, idDefModule, idPage, sort
            
            buildModule.FetchVariables()
            modules.push(buildModule)
        }
    }
    
    /**
     * 
     * We are only using the 'variable' key thats why we don't need to send other non-important objects
    */

    var getFixedModules = function() {
        var fm = modules

        for(var i = 0; i < fm.length; i++) {
            fm[i].variables.forEach(v => {
                for(var k in v) {
                    if(Object.getPrototypeOf(v[k]).constructor.name !== 'CMSVariable') {
                        delete v[k]
                    }
                }
            });
        }

        return fm
    }

    /**
     * Credits to Supantha Paul from https://codedamn.com
     * For giving the solution :)
    */

    var buildJSON = function() {
        var cache = [];
        var str = JSON.stringify(getFixedModules(), function(key, value) {
          if(typeof value === "object" && value !== null) {
            if (cache.indexOf(value) !== -1) {
              return
            }
            
            cache.push(value)
          }
          return value
        });
        
        cache = null

        return str
    }

    var getModuleHTML = function(idModule, callback) {
        var idPage = Helpers.Data.GetData('page').id

        Helpers.AJAX.Post('/', {
            controller : 'Modules',
            action     : 'GetModuleHTML',
            idm        : idModule,
            idp        : idPage
        }).success(data => {
            callback(data)
        })
    }

    
    var rebuildModules = function() {
        modules = []
        createCMSModules()
    }

    var rebuildEvents = function() {
        for(let i = 0; i < modules.length; i++) {
            deleteButton = modules[i].GetDOM().querySelector('.module-live-edit-icon.delete')

            deleteButton.addEventListener('click', e => {
                deleteModule(modules[i].GetDOM())
            })
        }
    }

    var rebuildContent = function(callback = function() {}) {
        var pageId = Helpers.Data.GetData('page').id
        Helpers.AJAX.Post('/', {
            controller : 'Page',
            action     : 'GetPageVariables',
            id         : pageId
        }).success(data => {
            var obj = JSON.parse(data)

            getVariables = obj.data

            addVariablesTypes()
            rebuildModules()
            rebuildEvents()
        })
    }

    var addModuleAJAX = function(result) {
        var id           = result.id
        var global       = result.global
        var globalExcept = result.global_except
        var idModule     = result.id_module
        var idPage       = result.id_page
        var sort         = result.sort

        getModuleHTML(id, data => {
            var newModule       = new CMSModule(id, global, globalExcept, idModule, idPage, sort)
            var moduleContainer = document.querySelector('.modules')
            var obj = JSON.parse(data)
            
            rebuildContent(function() {
                newModule.FetchVariables()
            })
            

            if(obj.response == 'Success') {
                moduleContainer.innerHTML += obj.data
            } else {
                alert('Cant get module. Restart page!')
            }
        })
    }

    var deleteModule = function(dom) {
        var moduleId = dom.getAttribute('module-id')

        Helpers.AJAX.Post(location.href, {
            controller : 'Modules',
            action     : 'DeleteModule',
            module_id  : moduleId
        }).success(data => {
            try {
                var obj = JSON.parse(data)

                if(obj.response == 'Success') {
                    dom.style.opacity = '0%'
                    dom.addEventListener('transitionend', () => {
                        removeModule(moduleId)
                        dom.remove()
                    })

                    Helpers.CreateModal({
                        title : 'Success!',
                        content : 'Successfully deleted module(id: ' + moduleId + ')',
                        success : true
                    })
                } else {
                    Helpers.CreateModal({
                        title : 'Error!',
                        content : 'Error while deleting module',
                        success : false
                    })
                }
            } catch(e) {
                Helpers.CreateModal({
                    title : 'Error!',
                    content : 'Error while deleting module',
                    success : false
                })
            }
        })
    }

    var removeModule = function(id) {
        for(var i = 0; i < modules.length; i++) {
            if(modules[i].id == id ) {
                for(var j = 0; j < modules[i].variables.length; j++) {
                    modules[i].variables[j].Remove()
                }

                delete modules[i]
                break;
            }
        }
    }

   

    event.AddEvent('LoadUp', e => {
        rebuildContent()
        
        document.GlobalScope.SetVariable('content-module-upload', function() {
            var modulesListItem = document.querySelectorAll('.modules-list-item')
            var moduleAddBox    = document.querySelector('.module-add-box')

            var addModule = e => {
                var moduleId = e.currentTarget.getAttribute('module-id')
                var pageId   = Helpers.Data.GetData('page').id

                Helpers.AJAX.Post(location.href, {
                    controller : 'Modules',
                    action     : 'AddModule',
                    page_id    : pageId,
                    module_id  : moduleId
                }).success(data => {
                    try {
                        var obj = JSON.parse(data)

                        if(obj.response == 'Success') {
                            moduleAddBox.style.display = 'none'
                            addModuleAJAX(obj.data)
                        } else {
                            alert('Error adding module! Restart page!')
                        }
                    } catch(e) {
                        alert('Error adding module! Restart page! json')
                    }
                })
            }

            for(var i = 0; i < modulesListItem.length; i++) {
                modulesListItem[i].addEventListener('click', addModule)
            }
        })
    })
    

    
    return {
        GetModules : function() {
            return modules
        },
        GetSendJSON : function() {  
            return buildJSON()
        },
        Addons : function() {
        },
        RemoveModule : function(id) { //delete from array
            return removeModule()
        },
        DeleteModule : function(dom) { //perm delete from db
            return deleteModule(dom)
        },
        Test : function() {
            return getFixedModules()
        }
    }
})();