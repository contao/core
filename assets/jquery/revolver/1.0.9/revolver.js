/* =========================================================================================================
 *
 * "Y8888888b.                                     `Y88                                        ::
 *   888   Y88b                                     888
 *   888   dX8P   .d888b. `Y8b      d8P  .d8888b.   888 `Y8b      d8P  .d888b.  `Y88.d88b.    `Y88  .d8888b
 *   888888YK    d8P   Y8b  Y8b    d8P  d88P""Y88b  888   Y8b    d8P  d8P   Y8b  888P" "Y8b    888  88K
 *   888  "Y8b.  888888888   Y8b  d8P   88K    X88  888    Y8b  d8P   888888888  888           888  "Y8888b.
 *   888    88b  Y8b.         Y8bd8P    Y88b..d88P  888     Y8bd8P    Y8b.       888           888       X88
 * .d888    888   "Y888P"      Y88P      "Y8888P"   888.     Y88P      "Y888P"   888      ::   888   Y88888"
 *          Y88b.                                                                             .88P
 *                                                                                           d88"
 * =========================================================================================================
 * 
 * Revolver.js
 *
 * Revolver is a content slider built with no assumptions about your markup. Think of Revolver as a 
 * boilerplate or framework for making your own slider, exactly the way you want it. But don't let that 
 * scare you, it's really easy, I promise!
 * 
 * Documentation:   http://revolverjs.com
 * Support:         https://github.com/johnnyfreeman/revolver/issues
 * Bug Fixes:       https://github.com/johnnyfreeman/revolver/pulls
 * Author:          Johnny Freeman (http://johnnyfreeman.us)
 * 
 * Contribute:
 * 
 * If Revolver has been beneficial to you and you'd like to give back, there are a few ways you can 
 * contribute. You can answer questions on StackOverflow and our issue tracker. Or if you have a feature 
 * request or a bug fix you can submit a pull request on Github at http://github.com/johnnyfreeman/revolver.
 * 
 * License:
 * 
 * This software is open source and free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 */


/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-csstransitions-shiv-cssclasses-testprop-testallprops-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function x(a){j.cssText=a}function y(a,b){return x(prefixes.join(a+";")+(b||""))}function z(a,b){return typeof a===b}function A(a,b){return!!~(""+a).indexOf(b)}function B(a,b){for(var d in a){var e=a[d];if(!A(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function C(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:z(f,"function")?f.bind(d||b):f}return!1}function D(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+n.join(d+" ")+d).split(" ");return z(b,"string")||z(b,"undefined")?B(e,b):(e=(a+" "+o.join(d+" ")+d).split(" "),C(e,b,c))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m="Webkit Moz O ms",n=m.split(" "),o=m.toLowerCase().split(" "),p={},q={},r={},s=[],t=s.slice,u,v={}.hasOwnProperty,w;!z(v,"undefined")&&!z(v.call,"undefined")?w=function(a,b){return v.call(a,b)}:w=function(a,b){return b in a&&z(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=t.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(t.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(t.call(arguments)))};return e}),p.csstransitions=function(){return D("transition")};for(var E in p)w(p,E)&&(u=E.toLowerCase(),e[u]=p[E](),s.push((e[u]?"":"no-")+u));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)w(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},x(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._domPrefixes=o,e._cssomPrefixes=n,e.testProp=function(a){return B([a])},e.testAllProps=D,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+s.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};
 

;(function ($, window) {

    "use strict";

    // constructor
    var Revolver = function (container, options)
    {
        // merge new options (recursively) with defaults
        this.options = $.extend(true, {}, this.defaults, options);

        // setup revolver
        this.container      = container;
        this.dimensions     = { height: this.container.height(), width: this.container.width() };
        this.currentSlide   = 0;
        this.slides         = this.container.find('.'+this.options.slideClass).each( $.proxy(function(){ this.addSlide(this); }, this) );
        this.previousSlide  = this.lastSlide;
        this.status         = { paused: false, playing: false, stopped: true };
        this.isAnimating    = false;

        // Completely disable Revolver
        // if there is only one slide
        if (this.numSlides <= 1) {
            this.disabled = true;
            return;
        }

        // always disable isAnimating flag 
        // after transition is complete
        this.on('transitionComplete', function() {
            this.isAnimating = false;
        });

        // register all event handlers
        this.on('play', this.options.onPlay);
        this.on('stop', this.options.onStop);
        this.on('pause', this.options.onPause);
        this.on('restart', this.options.onRestart);
        this.on('transitionStart', this.options.transition.onStart);
        this.on('transitionComplete', this.options.transition.onComplete);
        
        // temperorary fix for deprecated option
        if (typeof this.options.transition.onFinish === 'function') {
            console.warn('The options.transition.onFinish property has been deprecated and will be removed in future versions. Please use options.transition.onComplete to aviod breakage. Love Revolver.js.');
            this.on('transitionComplete', this.options.transition.onFinish);
        }

        // fire onReady event handler
        $.proxy(this.options.onReady, this)();

        // begin auto play, if enabled
        if (this.options.autoPlay)
        {
            this.play({}, true);
        }

        return this;
    };
    
    // default options
    Revolver.prototype.defaults = {
        autoPlay:           true,           // whether or not to automatically begin playing the slides
        onReady:            function(){},   // gets called when revolver is setup and ready to go
        onPlay:             function(){},   // gets called when the play() method is called
        onStop:             function(){},   // gets called when the stop() method is called
        onPause:            function(){},   // gets called when the pause() method is called
        onRestart:          function(){},   // gets called when the restart() method is called
        rotationSpeed:      4000,           // how long (in milliseconds) to stay on each slide before going to the next
        slideClass:         'slide',        // this is what revolver will look for to determin what is a slide
        transition: {
            easing:         'swing',        // default easing method
            onStart:        function(){},   // gets called when the transition animation begins
            onFinish:       false,          // deprecated
            onComplete:     function(){},   // gets called when the animation is done
            speed:          500,            // how long (in milliseconds) the transition should last
            type:           'fade'          // choose between none, fade, slide, or reveal
        }
    };

    Revolver.prototype.previousSlide = null;     // key for previous slide
    Revolver.prototype.currentSlide  = null;     // key for current slide
    Revolver.prototype.nextSlide     = null;     // key for next slide
    Revolver.prototype.numSlides     = 0;        // total number of slides
    Revolver.prototype.lastSlide     = null;     // key for last slide
    Revolver.prototype.container     = null;     // the wrapper element for all images
    Revolver.prototype.slides        = [];       // array of slides
    Revolver.prototype.iteration     = 0;        // keeps track of the number of transitions that have occured
    Revolver.prototype.intervalId    = null;     // id set by setInterval(), used for pause() method
    Revolver.prototype.status        = null;     // will contain the state of the slider
    Revolver.prototype.options       = null;     // will contain all options for the slider
    Revolver.prototype.dimensions    = null;     // contains width & height of the slider
    Revolver.prototype.isAnimating   = null;     // whethor revolver is currently in transition
    Revolver.prototype.disabled      = false;    // disables all functionality in a Revolver instance
    Revolver.prototype.VERSION       = '1.0.6';  // version info

    Revolver.prototype.addSlide = function(slide)
    {
        // if jquery object is passed get the first HTMLElement
        if (slide instanceof $ && slide[0] instanceof HTMLElement) {
            slide = slide[0];
        };

        this.slides.push(slide);

        this.numSlides     = this.slides.length;
        this.lastSlide     = this.numSlides === 0 ? 0 : this.numSlides - 1;
        var currentPlusOne = this.currentSlide + 1;
        this.nextSlide     = currentPlusOne > this.lastSlide ? 0 : currentPlusOne;
    };

    Revolver.prototype.changeStatus = function(newStatus)
    {
        // set all status' as false
        var Revolver = this;
        $.each(this.status, function(key, val)
        {
            Revolver.status[key] = key === newStatus;
        });

        return this;
    };

    // do transition
    Revolver.prototype.transition = function(options)
    {
        if (this.disabled === false && this.isAnimating === false)
        {
            options             = $.extend(true, {}, this.options.transition, options);
            var doTransition    = $.proxy(this.transitions[options.type], this);
            this.isAnimating    = true;

            // do transition
            doTransition(options);

            // update slider position
            this.currentSlide   = this.nextSlide;
            this.previousSlide  = this.currentSlide === 0 ? this.lastSlide : this.currentSlide - 1;
            this.nextSlide      = this.currentSlide === this.lastSlide ? 0 : this.currentSlide + 1;
            this.iteration++;

            // fire onTransition event
            this.trigger('transitionStart');
        }

        return this;
    };

    // logic for transitions
    Revolver.prototype.transitions = {

        // no transition, just show and hide
        none: function(options)
        {
            this.slides.eq(this.currentSlide).hide();
            this.slides.eq(this.nextSlide).show();
            this.trigger('transitionComplete');
        },

        // reveal
        reveal: function(options)
        {
            var Revolver = this;

            this.slides.eq(this.nextSlide)
                .css({width: 0, height: this.dimensions.height, 'z-index': this.iteration+1})
                .show()
                .animate(
                    {width: this.dimensions.width},
                    options.speed,
                    options.easing,
                    this.trigger.bind(this, 'transitionComplete')
                );

            return this;
        }
    };

    Revolver.prototype.play = function(options, firstTime)
    {
        if (this.disabled === false && !this.status.playing)
        {
            this.changeStatus('playing');
            this.trigger('play');

            // if this isn't the first run
            // then do transition immediately
            if (!firstTime)
            {
                this.transition(options);
            }

            this.intervalId = setInterval( $.proxy(this.transition, this), parseFloat(this.options.rotationSpeed));
        }

        return this;
    };

    Revolver.prototype.pause = function()
    {
        if (this.disabled === false && !this.status.paused)
        {
            this.changeStatus('paused');
            this.trigger('pause');

            if (this.intervalId !== null)
            {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }

        return this;
    };

    Revolver.prototype.stop = function()
    {
        if (this.disabled === false && !this.status.stopped)
        {
            this.changeStatus('stopped');
            this.trigger('stop');

            if (this.intervalId !== null)
            {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }
        
        return this.reset();
    };

    Revolver.prototype.reset = function()
    {
        // reset only if not already on the first slide
        if (this.currentSlide !== 0)
        {
            this.nextSlide = 0;
        }

        return this;
    };

    Revolver.prototype.restart = function(options)
    {
        if (this.disabled === true)
        {
            return this;
        }

        this.trigger('restart');
        return this.stop().play(options);
    };

    Revolver.prototype.first = function(options)
    {
        return this.goTo(0, options);
    };

    Revolver.prototype.previous = function(options)
    {
        return this.goTo(this.previousSlide, options);
    };

    Revolver.prototype.goTo = function(i, options)
    {
        // keep transition arithmetic from breaking
        i = parseInt(i);

        // bail out if already
        // on the intended slide
        if (this.disabled === true || i === this.currentSlide)
        {
            return this;
        }

        this.nextSlide = i;

        return !this.status.playing ? this.transition(options) : this.pause().play(options);
    };

    Revolver.prototype.next = function(options)
    {
        return this.goTo(this.nextSlide, options);
    };

    Revolver.prototype.last = function(options)
    {
        return this.goTo(this.lastSlide, options);
    };

    Revolver.prototype.on = function(eventName, callback)
    {
        return this.container.on(eventName + '.revolver', $.proxy(callback, this));
    };

    Revolver.prototype.off = function(eventName, callback)
    {
        return this.container.off(eventName + '.revolver', $.proxy(callback, this));
    };

    Revolver.prototype.trigger = function(eventName)
    {
        return this.container.trigger(eventName + '.revolver');
    };

    /**
     * Fade Transition
     */
    Revolver.prototype.transitions.fade = function(options) {
        var revolver, easingMap, easing, nextSlide, nextSlidePromise, currentSlide;
        var revolver, easingMap, easing, nextSlide, currentSlide, nextSlidePromise;

        revolver        = this;
        easingMap       = {swing: 'ease-in-out', linear: 'linear'};
        easing          = easingMap[options.easing];
        nextSlide       = this.slides.eq(this.nextSlide);
        currentSlide    = this.slides.eq(this.currentSlide);

        // move current slide above the next slide
        currentSlide.css('z-index', this.numSlides);
        nextSlide.css('z-index', this.nextSlide);

        // now that the nextSlide is tucked behind 
        // the current one, we can show() it
        nextSlidePromise = nextSlide.show(0).promise();

        // after we are sure the next slide is visable
        // we'll fade out the current one
        nextSlidePromise.done(function() {

            // using css3 transitions
            if (Modernizr.csstransitions)
            {
                currentSlide.css({
                    opacity: 0,
                    transition: 'opacity ' + (options.speed / 1000) + 's ' + easing
                });

                setTimeout(function() {
                    currentSlide.hide().css({
                        opacity: 1,
                        transition: 'opacity 0s ' + easing
                    });
                    revolver.trigger('transitionComplete');
                }, options.speed);
            }

            // using jquery animations
            else
            {
                currentSlide.fadeOut(
                    options.speed,
                    options.easing,
                    function() { 
                        revolver.trigger('transitionComplete'); 
                    }
                );
            };
            
        });
    };

    /**
     * Slide Transition
     */
    Revolver.prototype.transitions.slide = function(options) {
        return $.proxy(this.transitions.slide[options.direction], this)(options);
    };

    // define default value for the new option
    Revolver.prototype.defaults.transition.direction = 'left';

    // slide left
    Revolver.prototype.transitions.slide.left = function(options) {
        var currentSlide, easing, easingMap, nextSlide, nextSlidePromise, resetPosition, revolver;

        currentSlide    = this.slides.eq(this.currentSlide);
        easingMap       = {swing: 'ease-in-out', linear: 'linear'};
        easing          = easingMap[options.easing];
        nextSlide       = this.slides.eq(this.nextSlide);
        revolver        = this;

        // position/reveal the next slide in preperation for the animation
        nextSlidePromise = nextSlide.css({left: revolver.dimensions.width, top: 0, transition: 'left 0s ' + easing}).show(0).promise();

        nextSlidePromise.done(function() {
            // move next slide above the current slide
            nextSlide.css('z-index', this.numSlides);
            currentSlide.css('z-index', this.currentSlide);

            if (Modernizr.csstransitions)
            {
                currentSlide.css({left: 0 - revolver.dimensions.width, top: 0, transition: 'left ' + (options.speed / 1000) + 's ' + easing});
                nextSlide.css({top: 0, left: 0, transition: 'left ' + (options.speed / 1000) + 's ' + easing});

                setTimeout(function() {
                    currentSlide.hide();
                    revolver.trigger('transitionComplete');
                }, options.speed);
            }
            else
            {
                currentSlide.stop(true).animate(
                    {left: 0 - revolver.dimensions.width, top: 0},
                    options.speed,
                    options.easing,
                    function() {
                        $(this).hide();
                    }
                );

                // slide next out of the container
                nextSlide.stop(true).animate(
                    {top: 0, left: 0},
                    options.speed,
                    options.easing,
                    function() {
                        this.trigger('transitionComplete');
                    }
                );
            };
        });
    };

    // slide right
    Revolver.prototype.transitions.slide.right = function(options) {
        var currentSlide, easing, easingMap, nextSlide, nextSlidePromise, resetPosition, revolver;

        currentSlide    = this.slides.eq(this.currentSlide);
        easingMap       = {swing: 'ease-in-out', linear: 'linear'};
        easing          = easingMap[options.easing];
        nextSlide       = this.slides.eq(this.nextSlide);
        revolver        = this;

        // position/reveal the next slide in preperation for the animation
        nextSlidePromise = nextSlide.css({left: 0 - revolver.dimensions.width, top: 0, transition: 'left 0s ' + easing}).show(0).promise();

        nextSlidePromise.done(function() {
            // move next slide above the current slide
            nextSlide.css('z-index', this.numSlides);
            currentSlide.css('z-index', this.currentSlide);

            if (Modernizr.csstransitions)
            {
                currentSlide.css({left: revolver.dimensions.width, top: 0, transition: 'left ' + (options.speed / 1000) + 's ' + easing});
                nextSlide.css({top: 0, left: 0, transition: 'left ' + (options.speed / 1000) + 's ' + easing});

                setTimeout(function() {
                    currentSlide.hide();
                    revolver.trigger('transitionComplete');
                }, options.speed);
            }
            else
            {
                currentSlide.stop(true).animate(
                    {left: revolver.dimensions.width, top: 0},
                    options.speed,
                    options.easing,
                    function() {
                        $(this).hide();
                    }
                );

                // slide next out of the container
                nextSlide.stop(true).animate(
                    {top: 0, left: 0},
                    options.speed,
                    options.easing,
                    function() {
                        this.trigger('transitionComplete');
                    }
                );
            };
        });
    };

    // slide up
    Revolver.prototype.transitions.slide.up = function(options) {
        var currentSlide, easing, easingMap, nextSlide, nextSlidePromise, resetPosition, revolver;

        currentSlide    = this.slides.eq(this.currentSlide);
        easingMap       = {swing: 'ease-in-out', linear: 'linear'};
        easing          = easingMap[options.easing];
        nextSlide       = this.slides.eq(this.nextSlide);
        revolver        = this;

        // position/reveal the next slide in preperation for the animation
        nextSlidePromise = nextSlide.css({left: 0, top: revolver.dimensions.height, transition: 'top 0s ' + easing}).show(0).promise();

        nextSlidePromise.done(function() {
            // move next slide above the current slide
            nextSlide.css('z-index', this.numSlides);
            currentSlide.css('z-index', this.currentSlide);

            if (Modernizr.csstransitions)
            {
                currentSlide.css({left: 0, top: 0 - revolver.dimensions.height, transition: 'top ' + (options.speed / 1000) + 's ' + easing});
                nextSlide.css({top: 0, left: 0, transition: 'top ' + (options.speed / 1000) + 's ' + easing});

                setTimeout(function() {
                    currentSlide.hide();
                    revolver.trigger('transitionComplete');
                }, options.speed);
            }
            else
            {
                currentSlide.stop(true).animate(
                    {left: 0, top: 0 - revolver.dimensions.height},
                    options.speed,
                    options.easing,
                    function() {
                        $(this).hide();
                    }
                );

                // slide next out of the container
                nextSlide.stop(true).animate(
                    {top: 0, left: 0},
                    options.speed,
                    options.easing,
                    function() {
                        this.trigger('transitionComplete');
                    }
                );
            };
        });
    };

    // slide down
    Revolver.prototype.transitions.slide.down = function(options) {
        var currentSlide, easing, easingMap, nextSlide, nextSlidePromise, resetPosition, revolver;

        currentSlide    = this.slides.eq(this.currentSlide);
        easingMap       = {swing: 'ease-in-out', linear: 'linear'};
        easing          = easingMap[options.easing];
        nextSlide       = this.slides.eq(this.nextSlide);
        revolver        = this;

        // position/reveal the next slide in preperation for the animation
        nextSlidePromise = nextSlide.css({left: 0, top: 0 - revolver.dimensions.height, transition: 'top 0s ' + easing}).show(0).promise();

        nextSlidePromise.done(function() {
            // move next slide above the current slide
            nextSlide.css('z-index', this.numSlides);
            currentSlide.css('z-index', this.currentSlide);

            if (Modernizr.csstransitions)
            {
                currentSlide.css({left: 0, top: revolver.dimensions.height, transition: 'top ' + (options.speed / 1000) + 's ' + easing});
                nextSlide.css({top: 0, left: 0, transition: 'top ' + (options.speed / 1000) + 's ' + easing});

                setTimeout(function() {
                    currentSlide.hide();
                    revolver.trigger('transitionComplete');
                }, options.speed);
            }
            else
            {
                currentSlide.stop(true).animate(
                    {left: 0, top: revolver.dimensions.height},
                    options.speed,
                    options.easing,
                    function() {
                        $(this).hide();
                    }
                );

                // slide next out of the container
                nextSlide.stop(true).animate(
                    {top: 0, left: 0},
                    options.speed,
                    options.easing,
                    function() {
                        this.trigger('transitionComplete');
                    }
                );
            };
        });
    };

    // make Revolver globally available
    window.Revolver = Revolver;
    
    // jquery plugin
    $.fn.revolver = function(options)
    {
        return this.each(function()
        {
            // store the revolver object using jquery's data method
            if (!$.data(this, 'revolver'))
            {
                $.data(this, 'revolver', new Revolver($(this), options));
            }
        });
    };

})(jQuery, this);
