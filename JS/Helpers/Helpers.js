var Helpers = (() => {
    //Add here a small functions that don't need a whole file
    var awake = []

    var AddAwakeCallback = (callback, wait = 0) => {
        awake.push(
            {
                finished : false,
                callback : callback,
                wait     : wait
            }
        )
    }

    var getAwake = () => {
        return awake;
    }

    var AwakeCallbacks = () => {
        for(var i = 0; i < awake.length; i++) {
            current = awake[i]
            if(!current.finished) {
                setTimeout(() => {
                    current.callback()
                    current.finished = true
                }, current.wait)
            }
        }
    }

    window.addEventListener('load', AwakeCallbacks)

    return {
        AddAwakeCallback : AddAwakeCallback,
        getAwake         : getAwake
    }
})()