var Helpers = (() => {
    //Add here a small functions that don't need a whole file

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
        }
    }
})();