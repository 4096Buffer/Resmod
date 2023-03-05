

var ChatGPT = function() {
    this.apiKey          = ''
    this.dom             = null
    this.lastResponse    = null
    this.lastResponseEnd = true
    this.lastMessage     = null

    this.BuildHTML()
    setTimeout(() => {
        this.dom = document.querySelector('.chat-gpt-container')
        this.AddEvents()
    }, 16)
}

ChatGPT.prototype.apiKey          = null
ChatGPT.prototype.dom             = null
ChatGPT.prototype.lastResponse    = null
ChatGPT.prototype.lastResponseEnd = null
ChatGPT.prototype.lastMessage     = null

ChatGPT.prototype.BuildHTML = function() {
    var html = ''
    
    html += '<div class="open-chat-gpt">'
        html += '<div class="logo"></div>'
    html += '</div>'
    html += '<div class="chat-gpt-container">'
        html += '<div id="title-block"><div id="title">Chat GPT</div><div id="x">x</div></div>'
        html += '<div id="chat-history">'
        html += '</div>'
        html += '<div class="input">'
            html += '<div class="response-controll hidden"><div class="status">Regenarate</div></div>'
            html += '<div id="post-message">'
                html += '<input type="text" id="input-text" placeholder="Type something to ChatGPT.."/>'
                html += '<button id="send-button">></button>'
            html += '</div>'
        html += '</div>'
    html += '</div>'

    document.body.innerHTML = html + document.body.innerHTML
}

ChatGPT.prototype.ChangeResponseControll = function (stop) {
    var input       = this.dom.querySelector('.input')
    var responseBtn = input.querySelector('.response-controll')
    var status      = responseBtn.querySelector('.status')
    
    var text        = stop ? 'Stop' : 'Regenerate'
    var option      = (+stop).toString()

    if(responseBtn.classList.contains('hidden')) {
        responseBtn.classList.remove('hidden')
    }

    status.setAttribute('option', option)
    status.innerText = text
}

ChatGPT.prototype.SaveUserMessage = function(message) {
    var chatHistory = this.dom.querySelector('#chat-history')
    var newResponse = document.createElement('div')
    
    newResponse.classList.add('message-block', 'user')
    newResponse.innerText = message

    chatHistory.appendChild(newResponse)
    chatHistory.scrollTop = chatHistory.scrollHeight
}

ChatGPT.prototype.Response = function(response, end = true, regenerate = false) {
    var chatHistory = this.dom.querySelector('#chat-history')
    var input       = this.dom.querySelector('.input')
    var responseBtn = input.querySelector('.response-controll')
    var newResponse = null

    if(!regenerate && (!this.lastResponse || !this.lastResponse.classList.contains('loading'))) {
        newResponse = document.createElement('div')
        newResponse.classList.add('message-block', 'bot')
    } else {
        newResponse = this.lastResponse
    }

    if(!end) {
        this.ChangeResponseControll(true)

        newResponse.classList.add('loading')
        newResponse.innerText = 'Loading...'

        this.lastResponseEnd = false
    } else {
        this.ChangeResponseControll(false)
        
        if(!chatHistory.contains(this.lastResponse)) {
            this.lastResponseEnd = true
            this.lastMessage  = response
            responseBtn.classList.add('hidden')
            this.lastResponse = null
            return;
        }

        this.lastResponse.classList.remove('loading')
        this.lastResponseEnd = true
    }


    if(response) newResponse.innerText = response
    if(!regenerate) chatHistory.appendChild(newResponse)

    chatHistory.scrollTop = chatHistory.scrollHeight
    
    this.lastResponse = newResponse
    this.lastMessage  = response
}

ChatGPT.prototype.RemoveLastResponse = function() {
    var responses = this.dom.querySelector('#chat-history').querySelectorAll('.message-block.bot')
    var last = responses[responses.length - 1]

    try {
        last.classList.add('loading')
        last.innerText = 'Loading...'
        this.lastResponse = null
    } catch (e) {}
}

ChatGPT.prototype.AddEvents = function() {
    var input       = this.dom.querySelector('.input')
    var inputText   = input.querySelector('#input-text')
    var sendButton  = input.querySelector('#send-button')
    var openGpt     = document.body.querySelector('.open-chat-gpt')
    var xClose      = this.dom.querySelector('#x')
    var responseBtn = input.querySelector('.response-controll')

    inputText.addEventListener('keyup', e => {
        if(e.keyCode === 13) {
            var message = inputText.value

            if(message && this.lastResponseEnd === true) {
                this.SendMessage(message)
                inputText.value  = ''
            }
        }
    })

    sendButton.addEventListener('click', e => {
        var message = inputText.value

        if(message && this.lastResponseEnd === true) {
            this.SendMessage(message)
            inputText.value  = ''
        }
    })

    openGpt.addEventListener('click', e => {
        this.dom.classList.add('open')
        openGpt.classList.add('hidden')
    })

    xClose.addEventListener('click', e => {
        this.dom.classList.remove('open')
        openGpt.classList.remove('hidden')
    })

    responseBtn.addEventListener('click', e => {
        var status = parseInt(e.currentTarget.querySelector('.status').getAttribute('option'))
        
        if(status) { // stop response
            this.RemoveLastResponse()
        } else {  // regenerate
            this.SendMessage(this.lastMessage, true)
        }
    })
    
}

ChatGPT.prototype.SendMessage = function(message, regenerate = false) {
    if(!this.apiKey) {
        console.error('ViewScript - ChatGPT.js: Empty API Key!')
        return {error : true, reason : 'ViewScript - ChatGPT.js: Empty API Key!'}
    }

    if(!message) {
        console.error('ViewScript - ChatGPT.js: Empty message!')
        return {error : true, reason : 'ViewScript - ChatGPT.js: Empty message!'}
    }

    if(!regenerate) {
        this.SaveUserMessage(message)
    }

    this.Response(undefined, false, regenerate)

    Helpers.AJAX.Post('https://api.openai.com/v1/chat/completions', {
        model : 'gpt-3.5-turbo',
        "messages" : [{"role" : "user", "content": message}]
    }, { Authorization : `Bearer ${this.apiKey}` }).success(data => {
        try {
            var obj = JSON.parse(data)

            this.Response(obj.choices[0].message.content.trim(), true, regenerate)
        } catch (e) {
            this.Response('Error occured while sending request')
        }
    })
}

this.globalEvent.AddEvent('LoadUp', e => {    
    if(Helpers.GetCurrentViewMode().mode !== 'LiveEdit') {
        return;
    }
    var chatGpt = new ChatGPT()

    chatGpt.apiKey = 'API_KEY'
})