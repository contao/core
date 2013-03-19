/**
 * MooToolsAdapter 0.1
 * For all details and documentation:
 * http://github.com/inkling/backbone-mootools
 *
 * Copyright 2011 Inkling Systems, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * Extended by Andreas Schempp to support all mediaelement.js functions.
 * @author Andreas Schempp <http://iserv.ch>
 */

var MooToolsCompat = (function(window){
    var MooToolsAdapter = new Class({
        initialize: function(elements){
            for (var i = 0; i < elements.length; i++){
                this[i] = elements[i];
            }
            this.length = elements.length;
        },

        /**
         * Hide the elements defined by the MooToolsAdapter from the screen.
         */
        hide: function(){
            for (var i = 0; i < this.length; i++){
                this[i].setStyle('display', 'none');
            }
            return this;
        },
        
        show: function(){
            for (var i = 0; i < this.length; i++){
                this[i].setStyle('display', 'block');
            }
            return this;
        },

        /**
         * Append the frst element in the MooToolsAdapter to the elements found by the passed in
         * selector. If the selector selects more than one element, a clone of the first element is
         * put into every selected element except the first. The first selected element always
         * adopts the real element.
         *
         * @param selector A CSS3 selector.
         */
        appendTo: function(target){
            var elements = typeof target == 'string' ? window.getElements(target) : target;

            for (var i = 0; i < elements.length; i++){
                if (i > 0){
                    elements[i].adopt(Object.clone(this[0]));
                } else {
                    elements[i].adopt(this[0]);
                }
            }

            return this;
        },

        /**
         * Set the attributes of the element defined by the MooToolsAdapter.
         *
         * @param map:Object literal map definining the attributes and the values to which
         *     they should be set.
         *
         * @return MooToolsAdapter The object on which this method was called.
         */
        attr: function(attributeName, value){
            if (typeof attributeName == 'object'){
                for (var i = 0; i < this.length; i++){
                    this[i].set(map);
                }
                return this;
            }
            else if(typeof value == 'undefined'){
                return this[0].get(attributeName);
            }
            else{
                return this[0].set(attributeName, value);
            }
        },

        /**
         * Set the HTML contents of the elements contained by the MooToolsAdapter.
         *
         * @param htmlString:String A string of HTML text.
         *
         * @return MooToolsAdapter The object the method was called on.
         */
        html: function(htmlString){
            for (var i = 0; i < this.length; i++){
                this[i].set('html', htmlString);
            }
            return this;
        },

        /**
         * Remove an event namespace from an eventName.
         * For Example: Transform click.mootools -> click
         *
         * @param eventName:String A string representing an event name.
         *
         * @return String A string representing the event name passed without any namespacing.
         */
        removeNamespace_: function(eventName){
            var dotIndex = eventName.indexOf('.');

            if (dotIndex != '-1'){
                eventName = eventName.substr(0, dotIndex);
            }

            return eventName;
        },

        /**
         * Delegate an event that is fired on the elements defined by the selector to trigger the
         * passed in callback.
         *
         * @param selector:String A CSS3 selector defining which elements should be listenining to
         *     the event.
         * @param eventName:String The name of the event.
         * @param method:Function The callback to call when the event is fired on the proper
         *     element.
         *
         * @return MooToolsAdapter The object the method was called on.
         */
        delegate: function(selector, eventName, method){
            // Remove namespacing because it's not supported in MooTools.
            eventName = this.removeNamespace_(eventName);

            // Note: MooTools Delegation does not support delegating on blur and focus yet.
            for (var i = 0; i < this.length; i++){
                this[i].addEvent(eventName + ':relay(' + selector + ')', method);
            }
            return this;
        },

        /**
         * Bind the elements on the MooToolsAdapter to call the specific method for the specific
         * event.
         *
         * @param eventName:String The name of the event.
         * @param method:Function The callback to apply when the event is fired.
         *
         * @return MooToolsAdapter The object the method was called on.
         */
        bind: function(eventName, method){
            // Remove namespacing because it's not supported in MooTools.
            eventName = this.removeNamespace_(eventName);

            // Bind the events.
            for (var i = 0; i < this.length; i++){
                if (this[i] === null) continue;
                if (eventName == 'popstate' || eventName == 'hashchange' || !this[i].addEvent){
                    if (this[i].addEventListener)
                        this[i].addEventListener(eventName, method);
                } else {
                    this[i].addEvent(eventName, function(e) {
                        if (e){
                            e.pageX = e.page.x;
                            e.pageY = e.page.y;
                            e.which = e.rightClick ? 3 : 1;
                            e.keyCode = e.code;
                        }
                        method.call(this, e)
                    });
                }
            }
            return this;
        },

        /**
         * Unbind the bound events for the element.
         */
        unbind: function(eventName){
            // Remove namespacing because it's not supported in MooTools.
            eventName = this.removeNamespace_(eventName);

            for (var i = 0; i < this.length; i++){
                if (eventName !== ""){
                    this[i].removeEvent(eventName);
                } else {
                    this[i].removeEvents();
                }
            }
            return this;
        },

        /**
         * Return the element at the specified index on the MooToolsAdapter.
         * Equivalent to MooToolsAdapter[index].
         *
         * @param index:Number a numerical index.
         *
         * @return HTMLElement An HTML element from the MooToolsAdapter. Returns undefined
         *     if an element at that index does not exist.
         */
        get: function(index){
            return this[index];
        },

         /**
          * Removes from the DOM all the elements selected by the MooToolsAdapter.
          */
        remove: function(){
            for (var i = 0; i < this.length; i++){
                this[i].dispose();
            }
            return this;
        },

        /**
         * Add a callback for when the document is ready.
         */
        ready: function(callback){
            for (var i = 0; i < this.length; i++){
                window.addEvent('domready', callback);
            }
        },

        /**
         * Return the text content of all the elements selected by the MooToolsAdapter.
         * The text of the different elements is seperated by a space.
         *
         * @return String The text contents of all the elements selected by the MooToolsAdapter.
         */
        text: function(){
            var text = [];
            for (var i = 0; i < this.length; i++){
                text.push(this[i].get('text'));
            }
            return text.join(' ');
        },

        /**
         * Fire a specific event on the elements selected by the MooToolsAdapter.
         *
         * @param trigger:
         */
        trigger: function(eventName){
            for (var i = 0; i < this.length; i++){
                this[i].fireEvent(eventName);
            }
            return this;
        },

        /**
         * Read arbitrary data associated with the matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param name:A string naming the piece of data to read
         * 
         * @return The stored data
         */
        data: function(name){
            return this.get(name);
        },

        /**
         * Remove an attribute from each element in the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param propertyName:The attribute to remove
         * 
         * @return MooToolsAdapter
         */
        removeAttr: function(propertyName){
            for (var i = 0; i < this.length; i++){
                this[i].removeProperty(propertyName);
            }
            return this;
        },

        /**
         * Adds the specified class(es) to each of the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param className:One or more class names to be added
         * 
         * @return MooToolsAdapter
         */
        addClass: function(className){
            for (var i = 0; i < this.length; i++){
                this[i].addClass(className);
            }
            return this;
        },

        /**
         * Remove a single class, multiple classes, or all classes from each
         * element in the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param className:One or more space-separated classes to be removed
         * 
         * @return MooToolsAdapter
         */
        removeClass: function(className){
            for (var i = 0; i < this.length; i++){
                this[i].removeClass(className);
            }
            return this;
        },

        /**
         * nsert every element in the set of matched elements before the target
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param element:A selector, element, HTML string, or jQuery object
         * 
         * @return MooToolsAdapter
         */
        insertBefore: function(element){
            for (var i = 0; i < this.length; i++){
                this[i].inject(element[0], 'before');
            }
            return this;
        },

        /**
         * Get the descendants of each element in a set of matched elements,
         * filtered by a selector, jQuery object, or element
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param selector:A string containing a selector expression
         * 
         * @return MooToolsAdapter
         */
        find: function(selector){
            var elements = [];
            for (var i = 0; i < this.length; i++){
                elements.append(Array.from(this[i].getElements(selector)));
            }
            return new MooToolsAdapter(elements);
        },

        /**
         * Get the children of each element in a set of matched elements,
         * optionally filtered by a selector
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param selector:A string containing a selector expression
         * 
         * @return MooToolsAdapter
         */
        children: function(selector){
            var elements = [];
            for (var i = 0; i < this.length; i++){
                elements.append(Array.from(this[i].getChildren(selector)));
            }
            return new MooToolsAdapter(elements);
        },

        /**
         * Get the first element that matches the selector, beginning at the
         * current element and progressing up through the DOM tree
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param selector:A string containing a selector expression
         * 
         * @return MooToolsAdapter
         */
        closest: function(selector){
            for (var i = 0; i < this.length; i++){
                var c = this[i].getParent(selector);
                if (c)
                    return new MooToolsAdapter([c]);
            }
            return new MooToolsAdapter([]);
        },

        /**
         * Get the siblings of each element in the set of matched elements,
         * optionally filtered by a selector
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param selector:A string containing a selector expression
         * 
         * @return MooToolsAdapter
         */
        siblings: function(selector){
            var elements = [];
            for (var i = 0; i < this.length; i++){
                elements.append(Array.from(this[i].getSiblings(selector)));
            }
            return new MooToolsAdapter(elements);
        },

        /**
         * Insert content, specified by the parameter, to the end of each
         * element in the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param content:DOM element, HTML string, or jQuery object to insert
         * 
         * @return MooToolsAdapter
         */
        append: function(content){
            if (content.length){
                for (var i = 0; i < this.length; i++){
                    this.append(content[i]);
                }
                return this;
            }
            for (var i = 0; i < this.length; i++){
                this[i].adopt((i==0 ? content : content.clone()));
            }
            return this;
        },

        /**
         * Create a deep copy of the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @return MooToolsAdapter
         */
        clone: function(){
            var elements = [];
            for (var i = 0; i < this.length; i++){
                elements.append(Array.from(this[i].clone()));
            }
            return new MooToolsAdapter(elements);
        },

        /**
         * Remove the set of matched elements from the DOM
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @return MooToolsAdapter
         */
        remove: function(){
            for (var i = 0; i < this.length; i++){
                this[i].destroy();
                delete this[i];
            }
            this.length = 0;
            return this;
        },

        /**
         * Bind an event handler to the "keydown" JavaScript event, or trigger
         * that event on an element
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param fn:A function to execute each time the event is triggered
         * 
         * @return MooToolsAdapter
         */
        keydown: function(fn){
            return this.bind('keydown', fn);
        },

        /**
         * Bind an event handler to the "click" JavaScript event, or trigger
         * that event on an element
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param fn:A function to execute each time the event is triggered
         * 
         * @return MooToolsAdapter
         */
        click: function(fn){
            return this.bind('click', fn);
        },

        /**
         * Bind an event handler to the "resize" JavaScript event, or trigger
         * that event on an element
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param fn:A function to execute each time the event is triggered
         * 
         * @return MooToolsAdapter
         */
        resize: function(fn){
            return this.bind('resize', fn);
        },

        /**
         * Bind two handlers to the matched elements, to be executed when the
         * mouse pointer enters and leaves the elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param fn1:A function to execute when the mouse pointer enters the element
         * @param fn2:A function to execute when the mouse pointer leaves the element
         * 
         * @return MooToolsAdapter
         */
        hover: function(fn1, fn2){
            this.bind('mouseenter', fn1);
            this.bind('mouseleave', fn2);
        },

        /**
         * Iterate over a jQuery object, executing a function for each matched element
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param fn:A function to execute for each matched element
         * 
         * @return MooToolsAdapter
         */
        each: function(fn){
            for (var i = 0; i < this.length; i++){
                fn.call(this[i], i, this[i]);
            }
            return this;
        },

        /**
         * Check the current matched set of elements against a selector,
         * element, or jQuery object and return true if at least one of these
         * elements matches the given arguments
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param selector:A string containing a selector expression
         * 
         * @return MooToolsAdapter
         */
        is: function(selector){
            for (var i = 0; i < this.length; i++){
                if (this[i].match(selector)) return true;
            }
            return false;
        },

        /**
         * Get the value of a style property for the first element in the set
         * of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param property:The CSS property
         * @param value:The CSS property value
         * 
         * @return MooToolsAdapter
         */
        css: function(property, value){
            if (typeof property == 'object'){
                for (var i = 0; i < this.length; i++){
                    this[i].setStyles(property);
                }
                return this;
            }
            if (typeof value == 'undefined'){
                return this[0].getStyle(property);
            }
            for (var i = 0; i < this.length; i++){
                this[i].setStyle(property, value);
            }
            return this;
        },

        /**
         * Set the CSS width of each element in the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param value:An integer representing the number of pixels
         * 
         * @return MooToolsAdapter
         */
        width: function(value){
            if (typeof value == 'undefined'){
                // DOMWindow does not support getComputedSize
                return this[0].getComputedSize ? this[0].getComputedSize().width : this[0].getSize().x;
            }
            // fix numeric string
            value = value.toInt() == value ? value+'px' : value;
            for (var i = 0; i < this.length; i++){
                this[i].setStyle('width', value);
            }
            return this;
        },

        /**
         * Get the current computed width for the first element in the set of
         * matched elements, including padding and border
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param includeMargin:A Boolean indicating whether to include the element's margin
         * 
         * @return The outerWidth in pixels
         */
        outerWidth: function(includeMargin){
            return (includeMargin && this[0].getComputedSize) ? this[0].getComputedSize({styles:['padding','border','margin']}).totalWidth : this[0].getSize().x;
        },

        /**
         * Set the CSS height of each element in the set of matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param value:An integer representing the number of pixels
         * 
         * @return MooToolsAdapter
         */
        height: function(value){
            if (typeof value == 'undefined'){
                // DOMWindow does not support getComputedSize
                return this[0].getComputedSize ? this[0].getComputedSize().height : this[0].getSize().x;
            }
            // fix numeric string
            value = value.toInt() == value ? value+'px' : value;
            for (var i = 0; i < this.length; i++){
                this[i].setStyle('height', value);
            }
            return this;
        },

        /**
         * Get the current coordinates of the first element in the set of
         * matched elements, relative to the document
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @return An object containing the properties top and left
         */
        offset: function(){
            var pos = this[0].getPosition();
            return {top: pos.y, left: pos.x};
        },

        /**
         * Get the current coordinates of the first element in the set of
         * matched elements, relative to the offset parent
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @return An object containing the properties top and left
         */
        position: function(){
            var pos = this[0].getPosition(this[0].getOffsetParent());
            return {top: pos.y, left: pos.x};
        },

        /**
         * Stop the currently-running animation on the matched elements
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @param clearQueue:A Boolean indicating whether to remove queued animation
         * @param jumpToEnd:A Boolean indicating whether to complete the current animation immediately
         * 
         * @return MooToolsAdapter
         */
        stop: function(clearQueue, jumpToEnd){
            for (var i = 0; i < this.length; i++){
                var tween = this[i].get('tween').cancel();
                if (clearQueue){
                    tween.clearChain();
                }
                if (jumpToEnd){
                    if (tween.property) tween.set(tween.property, tween.to);
                }
            }
            return this;
        },

        /**
         * Hide the matched elements by fading them to transparent
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @return MooToolsAdapter
         */
        fadeOut: function(){
            for (var i = 0; i < this.length; i++){
                if (arguments.length > 0){
                    this[i].set('tween', {duration:arguments[0]});
                    if (arguments[1]){
                        var t = this[i],
                            fn = arguments[1];
                        t.get('tween').chain(function(){ fn.call(t); this.callChain() });
                    }
                }
                this[i].fade('out');
            }
            return this;
        },

        /**
         * Display the matched elements by fading them to opaque
         * 
         * @author Andreas Schempp <http://iserv.ch>
         * 
         * @return MooToolsAdapter
         */
        fadeIn: function(){
            for (var i = 0; i < this.length; i++){
                if (arguments.length > 0){
                    this[i].set('tween', {duration:arguments[0]});
                    if (arguments[1]){
                        var t = this[i],
                            fn = arguments[1];
                        t.get('tween').chain(function(){ fn.call(t); this.callChain() });
                    }
                }
                this[i].fade('in');
            }
            return this;
        }
    });

    /**
     * JQuery Selector Methods
     *
     * $(html) - Returns an HTML element wrapped in a MooToolsAdapter.
     * $(expression) - Returns a MooToolsAdapter containing an element set corresponding the
     *     elements selected by the expression.
     * $(expression, context) - Returns a MooToolsAdapter containing an element set corresponding
     *     to applying the expression in the specified context.
     * $(element) - Wraps the provided element in a MooToolsAdapter and returns it.
     *
     * @return MooToolsAdapter an adapter element containing the selected/constructed
     *     elements.
     */
    MooToolsCompat.$ = function(expression, context){
        var elements;

        // Handle $(html).
        if (typeof expression === 'string' && !context){
            if (expression.charAt(0) === '<' && expression.charAt(expression.length - 1) === '>'){
                elements = [new Element('div', {
                    html: expression
                }).getFirst()];
                return new MooToolsAdapter(elements);
            }
        } else if (typeof expression == 'object'){
            if (instanceOf(expression, MooToolsAdapter)){
                // Handle $(MooToolsAdapter)
                return expression;
            } else {
                // Handle $(element).
                return new MooToolsAdapter([expression]);
            }
        }

        // Handle $(expression) and $(expression, context).
        context = context || document;
        elements = [context.id(expression)] || context.getElements(expression);
        return new MooToolsAdapter(elements);
    };

    /*
     * $.ajax
     *
     * Maps a jQuery ajax request to a MooTools Request and sends it.
     */
    MooToolsCompat.$.ajax = function(params){
        var parameters = {
            url: params.url,
            method: params.type,
            data: params.data,
            emulation: false,
            onSuccess: function(responseText){
                params.success(JSON.parse(responseText));
            },
            onFailure: params.error,
            headers: { 'Content-Type': params.contentType }
        };

        new Request(parameters).send();
    };
    
    /*
     * $.extend
     *
     * Merge the contents of two or more objects together into the first object.
     */
    MooToolsCompat.$.extend = function(){
        var i = 1;
        if (typeof arguments[0] == 'boolean')
            i=2;
        
        var target = arguments[i-1];
        for (; i < arguments.length; i++){
            Object.append(target, arguments[i]);
        }
        
        return target;
    }

    Array.implement({
        children: function(selector){
            new MooToolsAdapter(this).children(selector);
        }
    });
    
    Slick.definePseudo('visible', function() {
        return (this.getStyle('visibility') != 'hidden');
    });

    return MooToolsCompat.$;
});
