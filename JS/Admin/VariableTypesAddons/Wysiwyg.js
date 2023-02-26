(function() {
    if(Helpers.GetCurrentViewMode().mode !== 'LiveEdit') return;

    var nEventScope = new Helpers.NameEvents.CreateNEventScope()

    Content.Addons.Wysiwyg = function() {
        var WysiwygObject = function(variable) {
            if(typeof variable === 'undefined'){
                console.error('WysiwygObject: \'variable\' is undefined!')
                return {};
            }

            this.variable         = variable
            this.objectDOM        = undefined
            this.focus            = false
            this.elementFocus     = false
            this.selection        = {}
            this.hide             = true
            this.checkHideEvent   =   checkHideEvent.bind(this)
            this.checkHideToolbar = checkHideToolbar.bind(this)

            this.BuildDOM()
            setTimeout(() => {
                this.PrepareObjectDOM()
            }, 50)
        }

        WysiwygObject.prototype.variable     = null
        WysiwygObject.prototype.objectDOM    = undefined
        WysiwygObject.prototype.elementFocus = false
        WysiwygObject.prototype.focus        = false
        WysiwygObject.prototype.selection    = null
        WysiwygObject.prototype.contextMenu  = null

        var fontSizes = {
            '8'  : '8px',
            '9'  : '9px',
            '10' : '10px',
            '11' : '11px',
            '12' : '12px',
            '13' : '13px',
            '14' : '14px',
            '16' : '16px',
            '18' : '18px',
            '24' : '24px',
            '36' : '36px',
            '48' : '48px',
            '60' : '60px',
            '72' : '72px'
        };

        WysiwygObject.prototype.PrepareObjectDOM = function () {
            if(!this.objectDOM) {
                var dom = document.querySelectorAll('.wysiwyg-container')
                if(dom) {
                    for(var i = 0; i < dom.length; i++) {
                        if(dom[i].getAttribute('to-var') == this.variable.name && dom[i].getAttribute('var-parent') == this.variable.parent.id) {
                            this.objectDOM = dom[i]
                            this.AddEvents()
                            break;
                        }
                    }
                }
            }
        }

        WysiwygObject.prototype.Show = function() {
            this.hide = false;
            this.objectDOM.style.visibility = 'visible'
        }

        WysiwygObject.prototype.Hide = function() {
            this.hide = true;
            this.objectDOM.style.visibility = 'hidden'
        }

        WysiwygObject.prototype.SetPosition = function(e) {
            if(typeof this.objectDOM == 'undefined') {
                console.error('WysiwygObject is undefined!')
                return;
            }

            var rect = this.variable.GetDOM().getBoundingClientRect()
            var top  = 0
            var left = 0

            if (rect.top >= this.objectDOM.offsetHeight) {
                top = rect.top - this.objectDOM.offsetHeight
            } else if ((window.WindowHeight - rect.bottom) >= this.objectDOM.offsetHeight) {
                top = (rect.bottom + this.objectDOM.offsetHeight)
            } else {
                top = this.objectDOM.offsetHeight
            }
        
            var centerX = rect.left + rect.width / 2;
            var offsetHalfWidth = this.objectDOM.offsetWidth;
        
            if (centerX < offsetHalfWidth) {
                left = offsetHalfWidth;
            } else if (centerX > (window.WindowWidth - offsetHalfWidth)) {
                left = window.WindowWidth - offsetHalfWidth;
            }

            this.objectDOM.style.top  = `${top}px`
            this.objectDOM.style.left = `${left}px`
        }

        WysiwygObject.prototype.BuildDOM = function() {
            if(this.objectDOM)return;
            var html = ''

            html += `<div class="wysiwyg-container" to-var=${this.variable.name} var-parent=${this.variable.parent.id} style="visibility:hidden">`
                html += '<div class="wysiwyg-option bold">'
                    html += 'B'
                html += '</div>'
                html += '<div class="wysiwyg-option italic">'
                    html += '/'
                html += '</div>'
                html += '<div class="wysiwyg-option underline">'
                    html += 'U'
                html += '</div>'
                html += '<div class="wysiwyg-option color">'
                    html += '<input type="color" />'
                html += '</div>'
                html += '<div class="wysiwyg-option align-left">'
                    html += 'LE'
                html += '</div>'
                html += '<div class="wysiwyg-option align-center">'
                    html += 'CE'
                html += '</div>'
                html += '<div class="wysiwyg-option align-right">'
                    html += 'RI'
                html += '</div>'
                html += '<div class="wysiwyg-option font-size">' 
                    html += '<select class="wysiwyg-font-size-select" name="font-size">'
                        for(var key in fontSizes) {
                            html += `<option value="${fontSizes[key]}">${key}</option>`
                        }
                html += '</div>'
            html += '</div>'
            
            document.body.insertAdjacentHTML('beforeBegin', html)
        }

        WysiwygObject.prototype.getCurrentForeColor = function() {
            var option   = this.objectDOM.querySelector('.wysiwyg-option.color')
            var input    = option.querySelector('input')
            var hexValue = input.value

            return hexValue
        }

        WysiwygObject.prototype.getCurrentFontSize = function() {
            var option   = this.objectDOM.querySelector('.wysiwyg-option.font-size')
            var select   = option.querySelector('select')
            var value    = select.value

            return value
        }

        var checkToolbar = function(obj, target) {
            while (target) {
              if (target === obj.objectDOM  || target == obj.variable.GetDOM()) {
                return true;
              }

              target = target.parentNode;
            }
            
            return false;
        };
        
        var checkHideEvent = function(e) {
            if (checkToolbar(this, e.target)) {
              return true;
            }
        
            return false;
        };

        var checkHideToolbar = function(e) {
            if(!this.checkHideEvent(e)) {
                this.Hide();
            } else {
                this.Show();
            }

            this.SetPosition(e)
        }

        var clearPaste = function(e) {
            e.preventDefault();
        
            var ev   = e.originalEvent || e;
            var data = ev.clipboardData;
            var text = data.getData('text/plain');
        
            document.execCommand("insertHTML", false, text);
        };

        WysiwygObject.prototype.GetOptions = function() {
            return [
                { className : '.wysiwyg-option.bold',         event: 'click',  callback : this.applyFunction.bind(this, this.applyBold),        command : 'bold',          args : [] },
                { className : '.wysiwyg-option.italic',       event: 'click',  callback : this.applyFunction.bind(this, this.applyItalic),      command : 'italic',        args : [] },
                { className : '.wysiwyg-option.underline',    event: 'click',  callback : this.applyFunction.bind(this, this.applyUnderline),   command : 'underline',     args : [] },
                { className : '.wysiwyg-option.color',        event: 'input',  callback : this.applyFunction.bind(this, this.applyColor),       command : 'foreColor',     args : [ this.getCurrentForeColor ] },
                { className : '.wysiwyg-option.align-left',   event: 'click',  callback : this.applyFunction.bind(this, this.applyAlignLeft),   command : 'justifyLeft',   args : [] },
                { className : '.wysiwyg-option.align-center', event: 'click',  callback : this.applyFunction.bind(this, this.applyAlignCenter), command : 'justifyCenter', args : [] },
                { className : '.wysiwyg-option.align-right',  event: 'click',  callback : this.applyFunction.bind(this, this.applyAlignRight),  command : 'justifyRight',  args : [] },
                { className : '.wysiwyg-option.font-size',    event: 'change', callback : this.applyFunction.bind(this, this.applyFontSize),    command : 'justifyRight',  args : [ this, this.getCurrentFontSize ] }
            ]
        }

        WysiwygObject.prototype.applyFunction = function(fun, args) {
            var results = []

            for(var i = 0; i < args.length; i++) {
                if(typeof args[i] === 'function') {
                    var result = args[i].call(this)
                    results.push(result)
                    break;
                }

                results.push(args[i])
            }
            
            
            if(results.length > 1) {
                fun.apply(this, results)
            } else if(typeof results[0] !== 'undefined'){
                fun(results[0])
            } else {
                fun()
            }

            var DOM      = this.variable.GetDOM() 
            var newValue = DOM.innerHTML
            if(this.variable.value !== newValue) {
                this.variable.ChangeValue(newValue) 
            }
        }

        
        
        WysiwygObject.prototype.AddEvents = function() {
            var self        = this
            var variableDOM = this.variable.GetDOM()
            var options = this.GetOptions();
            
            nEventScope.AddEvent('keydown', variableDOM, 'keydownVariable', e => {
                setTimeout(() => {
                    var newValue = variableDOM.innerHTML
                    if(this.variable.value !== newValue) {
                        this.variable.ChangeValue(newValue) 
                    }
                }, 16)
            })

            nEventScope.AddEvent('mouseup',     document,        'mouseupWindow',  this.checkHideToolbar)
            nEventScope.AddEvent('selectstart', variableDOM,     'selectVariable', this.checkHideToolbar)
            nEventScope.AddEvent('paste',       variableDOM,     'pasteVariable',  clearPaste)
            nEventScope.AddEvent('blur',        variableDOM,     'blurVariable',   e => {
                if(variableDOM.innerText === '') {
                    variableDOM.innerText = this.variable.oldValue
                    this.variable.value = this.variable.oldValue     
                } 
            })

            /*
            variableDOM.addEventListener('selectstart', e => {
                var wysiwygOptions = document.querySelectorAll('.wysiwyg-option')
                
                for(var i = 0; i < wysiwygOptions.length; i++) {
                    wysiwygOptions[i].classList.remove('active')
                }

                variableDOM.addEventListener('mouseup', e => {
                    for(var i = 0; i < options.length; i++) {
                        let command   = options[i].command
                        let className = options[i].className;
                        let dom       = this.objectDOM.querySelector(className);

                        if(document.queryCommandState(command)) {
                            dom.classList.add('active')
                        }
                    }
                })

                variableDOM.addEventListener('keydown', e => {
                    for(var i = 0; i < options.length; i++) {
                        let command   = options[i].command
                        let className = options[i].className;
                        let dom       = this.objectDOM.querySelector(className);

                        if(document.queryCommandState(command)) {
                            dom.classList.add('active')
                        }
                    }
                })
            })

            */

            for (var i = 0; i < options.length; i++) {
                let className = options[i].className;
                let event     = options[i].event;
                let args      = options[i].args;
                let callback  = options[i].callback;
                let dom       = this.objectDOM.querySelector(className);
                
                var callbackFunction = function() {
                    return callback.call(self, args);
                };

                dom.addEventListener(event, callbackFunction);
            }
            
        }

        function selectionIsCmd(cmd) {
            var isCmd = false;
            if (document.queryCommandState) {
                isCmd = document.queryCommandState(cmd);
            }
            return isCmd;
        }

        function getSelectionText() {
            var text = "";
            if (window.getSelection) {
                text = window.getSelection().toString();
            } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
            }
            return text;
        }
        
        WysiwygObject.prototype.applyBold = function() {
            document.execCommand('bold', false, null);
        }

        WysiwygObject.prototype.applyItalic = function() {
            document.execCommand('italic', false, null);
        }

        WysiwygObject.prototype.applyUnderline = function() {
            document.execCommand('underline', false, null);
        }

        WysiwygObject.prototype.applyColor = function(color) {
            document.execCommand('foreColor', false, color);
        }

        WysiwygObject.prototype.applyColor = function(color) {
            document.execCommand('foreColor', false, color);
        }

        WysiwygObject.prototype.applyAlignLeft = function() {
            document.execCommand('justifyLeft', false, null);
        }

        WysiwygObject.prototype.applyAlignCenter = function() {
            document.execCommand('justifyCenter', false, null);
        }

        WysiwygObject.prototype.applyAlignRight = function() {
            document.execCommand('justifyRight', false, null);
        }

        WysiwygObject.prototype.applyFontSize = function(self, size) {
            document.execCommand('fontSize', false, 1);
            var fontSize = ((parseFloat(size)) * 0.0625).toString() + 'rem';
            var els = self.variable.GetDOM().querySelectorAll('[size]');

            for (var i = 0; i !== els.length; i++) {
                els[i].style.fontSize = fontSize;
                els[i].removeAttribute("size");
            }
        }


        WysiwygObject.prototype.GetDOM = function() {
            if(this.objectDOM) {
                return this.objectDOM
            }

            var wysiwygs = document.querySelectorAll('.wysiwyg-container')
            
            for(var i = 0; i < wysiwygs.length; i++) {
                if(wysiwygs[i].getAttribute('to-var') == this.variable.name && wysiwygs[i].getAttribute('var-parent') == this.variable.parent.id) {
                    return wysiwygs[i]
                }
            }

            this.BuildDOM()
            setTimeout(this.GetDOM, 16)
        }


        WysiwygObject.prototype.RemoveObject = function() {
            //window.removeEventListener('mouseup', this.checkHideToolbar)
            nEventScope.RemoveEvent('mouseupWindow')
            nEventScope.RemoveEvent('selectVariable')

            this.objectDOM.remove()
            
            this.variable       = null
            this.objectDOM      = null
            this.focus          = null
            this.elementFocus   = null
            this.selection      = null
            this.hide           = null

           
            ///this.checkHideEvent = null
        }

        return {
            Build : function(variable) {
                return new WysiwygObject(variable)
            }
        }

    }();

})()