var Helpers = (() => {
    //Add here a small functions that don't need a whole file

    (function(){
        window.WindowWidth = 0;
        window.WindowHeight = 0;
      
        var resizeWindowSize = function() {
          WindowWidth = window.innerWidth
            || document.documentElement.clientWidth
            || document.body.clientWidth;
      
          WindowHeight = window.innerHeight
            || document.documentElement.clientHeight
            || document.body.clientHeight;
        };
      
        window.addEventListener('load', resizeWindowSize);
        window.addEventListener('resize', resizeWindowSize);
    })();

    var eventTypes = {}
    var viewModes  = {
        'LiveEdit' : 'live-edit',
        'Standard' : ''
    }

    var AddEventType = function(name, callback) {
        eventTypes[name] = callback
    }

    var Event = function() {
        this.events = []
    }

    /**
     * 
     * @param {string} type - Use the following type of events 
     * @param {function} callback - Execute after the event happened 
     * @returns - Object of event. Used for managing the event
     */

    Event.prototype.AddEvent = function(type, callback) {
        if(type in eventTypes) {
            var object = {
                id       : Math.round(Math.random() * 100000000).toString(),
                type     : eventTypes[type],
                callback : callback
            }

            this.events.push(object)
            object.type(callback)

            return object
        }
    }

    Event.prototype.RemoveEvent = function(id) {
        for(var i = 0; i < this.events.length; i++) {
            if(this.events[id] == id) {
                this.events.shift(this.events[i])
            }
        }
    }

    document.GlobalEvent = globalEvent = new Event()

    AddEventType('LoadUp', function(callback) {
        window.addEventListener('load', callback)
    })

    AddEventType('HTMLModified', function(callback) {
        window.addEventListener('DOMSubtreeModified', callback)
    })

    var GetCurrentViewMode = function() {
        var s = window.location.search.split('?')
        var send = {}

        for(var i = 0; i < s.length; i++) {
            var j = s[i].split('=')
            if(typeof j[0] !== 'undefined') {
                if(j[0] == 'mode') {
                    if(j[1] == viewModes['LiveEdit']) {
                        send = {
                            mode  : 'LiveEdit',
                            query : viewModes['LiveEdit']
                        }
                    } else {
                        send =  {
                            mode  : 'Standard',
                            query : viewModes['Standard']
                        }
                    }
                } else {
                    send = {
                        mode  : 'Standard',
                        query : viewModes['Standard']
                    }
                }
            }
        }

        return send
    }

    /**
     * 
     * @param {Object} data - Data to show modal 
     * {
     *  title: 'test',
     *  content : 'test',
     *  success : true,
     * }
     * @param {function} callbackEnd - Execute callback after closing modal
     */

    var CreateModal = function(data, callbackEnd = function() {}, timeout = 3000) {
        var title   = data.title
        var content = data.content
        var success = data.success
        
        var modalOld = document.querySelector('.modal-popup')

        if(modalOld) {
            document.querySelector('.modal-popup').remove()
        }
        
        var modal  = document.createElement('div')
        var html   = ''

        modal.classList.add('modal-popup')
        
        html += '<div class="close-x modal">&#10005;</div>'
        html += '<h2>' + title   + '</h2>'
        html += '<p>'  + content + '</h2>'

        modal.innerHTML = html
        modal.style.backgroundColor = success ? '#00640c' : '#810000'

        document.body.appendChild(modal)

        setTimeout(() => {
            var modalX = modal.querySelector('.close-x, .modal')
            modalX.addEventListener('click', e => {
                modal.style.opacity = '0%'
                modal.addEventListener('transitionend', () => {
                    modal.remove()
                    callbackEnd(e)
                })
                
            })
        }, 50)

        setTimeout(() => { 
            if(modal) {
                modal.style.opacity = '0%'
                modal.addEventListener('transitionend', () => {
                    modal.remove()
                    callbackEnd()
                })
            }
        }, timeout)
        
    }

    /**
     * 
     * @param {string} title - Title of the input box
     * @param {string} type - Type of the input
     * @param {function} callbackEnd - Execute callback after closing modal
     */

    var CreateInputBox = function(title, type, callbackEnd) {
        if(typeof title !== 'string') {
            console.error('Title must be a string')
        }

        if(typeof type !== 'string') {
            console.error('Type must be a string')
        }

        if(typeof callbackEnd !== 'function') {
            console.error('Callback must be a function')
        }

        var inputBoxBackground = document.createElement('div')
            document.body.appendChild(inputBoxBackground)
            inputBoxBackground.classList.add('input-box-background')
        var inputBoxContainer  = document.createElement('div')
            document.body.appendChild(inputBoxContainer)
            inputBoxContainer.classList.add('input-box-container')
        var inputBoxTitle      = document.createElement('div')
            inputBoxContainer.appendChild(inputBoxTitle) 
            inputBoxTitle.classList.add('input-box-title')
            inputBoxTitle.innerHTML = title
        var inputBox           = document.createElement('div')
            inputBoxContainer.appendChild(inputBox)
            inputBox.classList.add('input-box')
        var input              = document.createElement('input')
            inputBox.appendChild(input) 
            input.type = type
            input.classList.add('input-box-input')
        var inputBoxSubmit     = document.createElement('div')
            inputBoxContainer.appendChild(inputBoxSubmit)
            inputBoxSubmit.classList.add('input-box-submit')
        var buttonSubmit       = document.createElement('button')
            inputBoxSubmit.appendChild(buttonSubmit)
            buttonSubmit.innerText = 'Submit'

        buttonSubmit.addEventListener('click', function(e) {
            callbackEnd(input.value)
            inputBoxContainer.remove()
            inputBoxBackground.remove()
        })
    }

    /*
    var ContextMenu = function(dom) {
        this.groups = []
        this.blocks = {}
        this.dom    = dom
        this.eventScope = Helpers.NameEvents.CreateNEventScope()
        this.objectDOM = null

        this.Preload()
    }

    ContextMenu.prototype.groups     = null
    ContextMenu.prototype.blocks     = null
    ContextMenu.prototype.dom        = null
    ContextMenu.prototype.eventScope = null
    ContextMenu.prototype.objectDOM  = null

    ContextMenu.prototype.AddGroups = function(groups) {
        if(groups.constructor.name === 'Array') {
            this.groups = groups
        } else {
            this.groups.push(groups)
        }
    }

    ContextMenu.prototype.AddBlocks = function(blocks, group) {
        indexGroup = this.groups.indexOf(group)

        if(blocks.constructor.name === 'Array') {
            this.blocks = blocks
        } else if(this.blocks.constructor.name !== 'Array' && this.groups.length !== 0) {
            this.blocks[this.groups[indexGroup]].push(blocks)
        } else {
            this.blocks['default'] = typeof this.blocks['default'] === 'undefined' || this.blocks['default'].length === 0 ? [] : this.blocks['default']
            this.blocks['default'].push(blocks)
        }

        this.Preload()
    }

    ContextMenu.prototype.Preload = function() {
        var self = this
        if(document.querySelector('.context-menu')) {
            document.querySelector('.context-menu').remove()
        }
        
        this.BuildHTML()
        setTimeout(() => {
            self.objectDOM = document.querySelector('.context-menu')
            self.PreloadEvents()
        }, 16)

        
    }

    var getBlockDOM = function(self, key) {
        var cmElements = self.objectDOM.querySelectorAll('.context-menu-element')
        
        for(var i = 0; i < cmElements.length; i++) {
            var el = cmElements[i]

            if(el.classList.contains(key.replace(' ', '-').toLowerCase())) {
                return el
            }
            console.log(el.classList)
        }

        return null
    }

    var buildHtmlBlocks = function(self) {
        var html = ''
        
        if(self.groups.length === 0) {
            var defaults = self.blocks.default

            if(typeof defaults === 'undefined') {
                return ''
            }

            for(var i = 0; i < defaults.length; i++) {
                html += `<div class="context-menu-element ${defaults[i].key.replace(' ', '-').toLowerCase()}">`
                    html += `<div class="context-menu-element-title">${defaults[i].key}</div>`
                html += '</div>'
            }
        }

        return html
    }

    ContextMenu.prototype.BuildHTML = function() {
        var html = ''
        html += '<div class="context-menu" style="display:none">'
            html += '<div class="context-menu-box">'
                html += buildHtmlBlocks(this)
            html += '</div>'
        html += '</div>'

        document.body.innerHTML += html
    }

    ContextMenu.prototype.PreloadEvents = function() {
        this.eventScope.AddEvent('contextmenu', this.dom, 'cm__click', e => {
            e.preventDefault()

            var x = e.offsetX, y = e.offsetY,
            cmWidth = this.objectDOM.offsetWidth,
            cmHeight = this.objectDOM.offsetHeight

            if(x > (WindowWidth - cmWidth - window.offsetWidth)) { // replace with collapsible menus

            }

            x = x > WindowWidth - cmWidth ? WindowWidth - cmWidth : x
            y = y > WindowHeight - cmHeight ? WindowHeight - cmHeight : y

            this.objectDOM.style.left = `${x}px`
            this.objectDOM.style.top = `${y}px`
            this.objectDOM.style.display = 'block'
        })

        if(typeof this.blocks['default'] !== 'undefined') {
            var defaults = this.blocks['default']

            for(var i = 0; i < defaults.length; i++) {
                this.eventScope.AddEvent('click', getBlockDOM(this, defaults[i].key), `cm__click__${defaults[i].key.replace(' ', '__').toLowerCase()}`, defaults[i].callback)
            }
        }
    }

    ContextMenu.prototype.Reset = function() {
    }

    */

    var ScaleElementToParent = function(el, parent) {
        var ratio = (parent.offsetWidth / el.offsetWidth),
        padding   = el.offsetHeight * ratio

        el.style.transform = 'scale(' + ratio + ')';
        el.style.transformOrigin = 'top left'

        parent.style.paddingTop = `${padding}px`
    }

    return {
        CreateEventManager : function() {
            return new Event()
        },
        GetCurrentViewMode : function() {
            return GetCurrentViewMode()
        },
        CreateModal : function(data, callbackEnd = function() {}) {
            return CreateModal(data, callbackEnd)
        },
        CreateInputBox : function(title, type, callbackEnd) {
            return CreateInputBox(title, type, callbackEnd)
        },
        ScaleElementToParent : function(el, parent) {
            return ScaleElementToParent(el, parent)
        }

        /*
        CreateContextMenu : function(dom) {
            return new ContextMenu(dom)
        }
        */
       
    }
})();