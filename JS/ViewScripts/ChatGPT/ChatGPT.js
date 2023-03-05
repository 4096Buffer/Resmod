

var ChatGPT = function() {
    this.apiKey = ''
    this.dom    = null
    this.lastResponse = null

    this.BuildHTML()
    setTimeout(() => {
        this.dom = document.querySelector('.chat-gpt-container')
        this.AddEvents()
    }, 16)
}

ChatGPT.prototype.apiKey = null
ChatGPT.prototype.dom    = null
ChatGPT.prototype.lastResponse    = null

ChatGPT.prototype.BuildHTML = function() {
    var html = ''
    
    html += '<div class="open-chat-gpt">'
        html += '<div class="logo"></div>'
    html += '</div>'
    html += '<div class="chat-gpt-container">'
        html += '<div id="title-block"><div id="title">Chat GPT</div><div id="x">x</div></div>'
        html += '<div id="chat-history">'
        html += '</div>'
        html += '<div id="post-message">'
            html += '<input type="text" id="input-text" placeholder="Type something to ChatGPT.."/>'
            html += '<button id="send-button">></button>'
        html += '</div>'
    html += '</div>'

    document.body.innerHTML = html + document.body.innerHTML
}

ChatGPT.prototype.SaveUserMessage = function(message) {
    var chatHistory = this.dom.querySelector('#chat-history')
    var newResponse = document.createElement('div')
    
    newResponse.classList.add('message-block')
    newResponse.classList.add('user')
    newResponse.innerText = message

    chatHistory.appendChild(newResponse)
}

ChatGPT.prototype.Response = function(response, end = true) {
    var chatHistory = this.dom.querySelector('#chat-history')
    var newResponse = document.createElement('div')
    
    newResponse.classList.add('message-block')
    newResponse.classList.add('bot')
    if(!end) {
        newResponse.classList.add('loading')
        newResponse.innerText = 'Loading...'
    } else {
        this.lastResponse.remove()
    }

    if(response)newResponse.innerText = response
    chatHistory.appendChild(newResponse)

    this.lastResponse = newResponse
}

ChatGPT.prototype.AddEvents = function() {
    var inputText  = this.dom.querySelector('#input-text')
    var sendButton = this.dom.querySelector('#send-button')
    var openGpt    = document.body.querySelector('.open-chat-gpt')
    var xClose     = this.dom.querySelector('#x')

    inputText.addEventListener('keyup', e => {
        if(e.keyCode === 13) {
            var message = inputText.value

            if(message) {
                this.SendMessage(message)
                inputText.value  = ''
            }
        }
    })

    sendButton.addEventListener('click', e => {
        var message = inputText.value

        if(message) {
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
    
}

ChatGPT.prototype.SendMessage = function(message) {
    if(!this.apiKey) {
        console.error('ViewScript - ChatGPT.js: Empty API Key!')
        return {error : true, reason : 'ViewScript - ChatGPT.js: Empty API Key!'}
    }

    if(!message) {
        console.error('ViewScript - ChatGPT.js: Empty message!')
        return {error : true, reason : 'ViewScript - ChatGPT.js: Empty message!'}
    }

    this.SaveUserMessage(message)
    this.Response(undefined, false)

    Helpers.AJAX.Post('https://api.openai.com/v1/chat/completions', {
        model : 'gpt-3.5-turbo',
        "messages" : [{"role" : "user", "content": message}]
    }, { Authorization : `Bearer ${this.apiKey}` }).success(data => {
        try {
            var obj = JSON.parse(data)

            this.Response(obj.choices[0].message.content.trim())
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

    chatGpt.apiKey = 'YOUR_API_KEY' //will ask in future
})
