var Helpers = (() => {
    //Add here a small functions that don't need a whole file

    var eventTypes = {}

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

    return {
        CreateEventManager : function() {
            return new Event()
        }
    }
})()