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

    return {
        CreateEventManager : function() {
            return new Event()
        },
        GetCurrentViewMode : function() {
            return GetCurrentViewMode()
        }
    }
})();