Helpers.NameEvents = (() => {
    var scopes = {}

    var NEventObject = function(name, dom) {
        this.name       = name
        this.events     = {}
    }

    NEventObject.prototype.name      = null
    NEventObject.prototype.events    = null

    /**
     * @param {String} event      - event name
     * @param {String} id         - identifier of the event
     * @param {Function} callback - callback function
     */

    NEventObject.prototype.AddEvent = function(eventName, dom, id, callback) {
        var event = {
            eventName : eventName,
            id        : id,
            callback  : callback,
            dom       : dom
        }

        if(dom === null || (typeof dom === 'object' && dom.constructor.name === 'Array')) {
            console.error('NEvent: The DOM object is invalid! (nevent name ' + name + ')')
            return;
        }

        this.events[id] = event

        dom.addEventListener(eventName, callback, true)

        return event
    }

    NEventObject.prototype.GetEvent = function(id) {
        if(id in this.events) {
            return this.events[id]
        }

        return null;
    }

    NEventObject.prototype.RemoveEvent = function(id) {
        
        if(!this.GetEvent(id)) {
            return false;
        }


        var getEvent = this.GetEvent(id)

        getEvent.dom.removeEventListener(getEvent.eventName, getEvent.callback, true)

        delete this.events[id]
        
        console.log(this.events)
        return true
    }

    var createNEventObj = function(name) {
        if(!name || typeof name !== 'string') {
            name = 'NEvent-' + Math.round(Math.random() * 10000000).toString(16)
        }


        var obj = { name : new NEventObject(name) }
        var prototype = new NEventObject(name)

        scopes[name] = obj
        return prototype
    }

    removeNEventScope = function(name) {
        if(name in scopes) {
            delete scopes[name]
        }
    }

    var getNEventScope = function(name) {
        if(name in scopes) {
            return scopes[name]
        }
    }

    return {
        CreateNEventScope : function(name) {
            return createNEventObj(name)
        },
        RemoveNEventScope : function(name) {
            return removeNEventObj(name)
        },
        getNEventScope : function(name) {
            return getNEventScope(name)
        }
        
    }
})()