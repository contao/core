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

;(function($) {

    "use strict";

    var Revolver, RevolverSlide;

    Revolver = new Class({

        defaults: {
            autoPlay:           true,           // whether or not to automatically begin playing the slides
            onReady:            function(){},   // gets called when revolver is setup and ready to go
            onPlay:             function(){},   // gets called when the play() method is called
            onStop:             function(){},   // gets called when the stop() method is called
            onPause:            function(){},   // gets called when the pause() method is called
            onRestart:          function(){},   // gets called when the restart() method is called
            rotationSpeed:      4000,           // how long (in milliseconds) to stay on each slide before going to the next
            slideClass:         'slide',        // this is what revolver will look for to determin what is a slide
            transition: {
                direction:      'left',         // which way to slide each slide. used for the 'slide' transition type only.
                easing:         'sine:in:out',  // default easing method
                onStart:        function(){},   // gets called when the transition animation begins
                onFinish:       false,          // deprecated
                onComplete:     function(){},   // gets called when the animation is done
                speed:          500,            // how long (in milliseconds) the transition should last
                type:           'fade'          // choose between none, fade, or slide
            }
        },

        previousSlide: null,    // key for previous slide
        currentSlide: null,     // key for current slide
        nextSlide: null,        // key for next slide
        numSlides: 0,           // total number of slides
        lastSlide: null,        // key for last slide
        container: null,        // the wrapper element for all slides
        slides: [],             // array of slides
        iteration: 0,           // keeps track of the number of transitions that have occured
        intervalId: null,       // id set by setInterval(), used for pause() method
        state: null,            // will contain the state of the slider
        options: null,          // will contain all options for the slider
        dimensions: null,       // contains width & height of the slider
        isAnimating: null,      // whethor revolver is currently in transition
        disabled: false,        // disables all functionality in a Revolver instance
        VERSION: '1.0.4',       // version info

        // constructor
        initialize: function(container, options)
        {
            // setOptions is a method provided by the Options mixin
            // it merges the options passed in with the defaults
            // this.setOptions(options);
            this.options        = Object.merge(this.defaults, options);

            this.container      = container;
            this.dimensions     = this.container.getSize();

            this.container.getChildren('.' + this.options.slideClass).each(function(slide)
            {
                this.addSlide(slide);
            }.bind(this));

            this.previousSlide  = this.lastSlide;
            this.currentSlide   = 0;
            this.nextSlide      = this.numSlides > 1 ? 1 : 0;
            this.status         = { paused: false, playing: false, stopped: true };
            this.isAnimating    = false;

            // Don't run if there's only one slide
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
            if (typeof this.options.transition.onFinish == 'function') {
                console.warn('The options.transition.onFinish property has been deprecated and will be removed in future versions. Please use options.transition.onComplete to aviod breakage. Love Revolver.js.');
                this.on('transitionComplete', this.options.transition.onFinish);
            }

            // fire onReady event
            this.options.onReady.bind(this)();

            // begin auto play, if enabled
            if (this.options.autoPlay)
            {
                this.play({}, true);
            }

            return this;
        },

        addSlide: function(slide)
        {
            this.slides.push( new RevolverSlide(slide) );

            this.numSlides      = this.slides.length;
            this.lastSlide      = this.numSlides === 0 ? null : this.numSlides - 1;
        },

        changeStatus: function(newStatus)
        {
            // set all status' as false
            Object.each(this.status, function(val, key)
            {
                this.status[key] = key === newStatus;
            }, this);

            return this;
        },
        
        transition: function(options)
        {
            if (this.disabled === false && this.isAnimating === false)
            {
                options             = Object.merge( Object.clone(this.options.transition), options );
                var doTransitions   = this.transitions[options.type].bind(this);
                this.isAnimating    = true;

                // do transition, and pass the transition options to it
                doTransitions(options);

                // update slider position
                this.currentSlide   = this.nextSlide;
                this.previousSlide  = this.currentSlide === 0 ? this.lastSlide : this.currentSlide - 1;
                this.nextSlide      = this.currentSlide === this.lastSlide ? 0 : this.currentSlide + 1;
                this.iteration++;

                // fire transitionStart event
                this.trigger('transitionStart');
            }

            return this;
        },

        transitions: {

            // no transition, just show and hide
            none: function(options)
            {
                this.slides[this.currentSlide].hide();
                this.slides[this.nextSlide].show();

                // fire transitionComplete event
                this.trigger('transitionComplete');
            },

            // fade in and out
            fade: function(options)
            {
                var currentSlide = this.slides[this.currentSlide],
                    nextSlide = this.slides[this.nextSlide],
                    fadeOut = new Fx.Tween(currentSlide.container, {
                        duration: options.speed,
                        transition: options.easing
                    }),
                    fadeIn = new Fx.Tween(nextSlide.container, {
                        duration: options.speed,
                        transition: options.easing,
                        onComplete: this.trigger.bind(this, 'transitionComplete')
                    });

                // fade out current slide
                currentSlide.show().container.setStyle('opacity', 1);
                fadeOut.start('opacity', 0);
                currentSlide.hide();
                
                // fade in next slide
                nextSlide.show().container.setStyle('opacity', 0);
                fadeIn.start('opacity', 1);
            },

            // slide in and out of the container
            slide: function(options)
            {
                var currentSlide = this.slides[this.currentSlide],
                    nextSlide = this.slides[this.nextSlide],
                    slideOut = new Fx.Morph(currentSlide.container, {
                        duration: options.speed,
                        transition: options.easing,
                        onComplete: function(){ currentSlide.hide(); }
                    }),
                    slideIn = new Fx.Morph(nextSlide.container, {
                        duration: options.speed,
                        transition: options.easing,
                        onComplete: this.trigger.bind(this, 'transitionComplete')
                    }),
                    resetPosition = {top: 0, left: 0};
                
                // prepare slides for animation
                currentSlide.container.setStyles(resetPosition);
                nextSlide.show();

                // do animation based on the direction
                if (options.direction === "up")
                {
                    nextSlide.container.setStyle('top', 0 - this.dimensions.y);
                    slideIn.start(resetPosition);
                    slideOut.start({top: this.dimensions.y, left: 0});
                }
                else if (options.direction === "right")
                {
                    nextSlide.container.setStyle('left', 0 - this.dimensions.x);
                    slideIn.start(resetPosition);
                    slideOut.start({top: 0, left: this.dimensions.x});
                }
                else if (options.direction === "down")
                {
                    nextSlide.container.setStyles({top: this.dimensions.y, left: 0});
                    slideIn.start(resetPosition);
                    slideOut.start({top: 0 - this.dimensions.y, left: 0});
                }
                else if (options.direction === "left")
                {
                    nextSlide.container.setStyle('left', this.dimensions.x);
                    slideIn.start(resetPosition);
                    slideOut.start({top: 0, left: 0 - this.dimensions.x});
                }
            },

            // reveal
            reveal: function(options)
            {
                var Revolver = this,
                    nextSlide = this.slides[this.nextSlide],
                    reveal = new Fx.Tween(nextSlide.container, {
                        duration: options.speed,
                        transition: options.easing,
                        onComplete: this.trigger.bind(this, 'transitionComplete')
                    });

                // make sure next slide is on top of current slide
                nextSlide.container.setStyles({'z-index': this.iteration + 1, 'width': 0});
                nextSlide.show();

                reveal.start('width', this.dimensions.x);
            }
        },

        play: function(options, firstTime)
        {
            if (this.disabled === false && !this.status.playing)
            {
                this.changeStatus('playing');
                this.trigger('play');

                // transition immediately?
                if (!firstTime)
                {
                    this.transition(options);
                }

                this.intervalId = setInterval(this.transition.bind(this), parseFloat(this.options.rotationSpeed));
            }

            return this;
        },

        pause: function()
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
        },

        stop: function()
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
        },

        reset: function()
        {
            // reset only if not already on the first slide
            if (this.currentSlide !== 0)
            {
                this.nextSlide = 0;
            }

            return this;
        },

        restart: function(options)
        {
            // bail out if the Revolver has been disabled
            if (this.disabled === true)
            {
                return this;
            }

            this.trigger('restart');
            return this.stop().play(options);
        },

        first: function(options)
        {
            return this.goTo(0, options);
        },

        previous: function(options)
        {
            return this.goTo(this.previousSlide, options);
        },

        goTo: function(i, options)
        {
            // keep transition arithmetic from breaking
            i = parseInt(i);

            // bail out if already on the intended slide
            // or the slider is disabled
            if (this.disabled === true || i === this.currentSlide)
            {
                return this;
            }

            this.nextSlide = i;

            return this.status.playing ? this.pause().play(options) : this.transition(options);
        },

        next: function(options)
        {
            return this.goTo(this.nextSlide, options);
        },

        last: function(options)
        {
            return this.goTo(this.lastSlide, options);
        },

        on: function(eventName, callback)
        {
            return this.container.addEvent(eventName + '.revolver', callback.bind(this));
        },

        off: function(eventName, callback)
        {
            return this.container.removeEvent(eventName + '.revolver', callback.bind(this));
        },

        trigger: function(eventName)
        {
            return this.container.fireEvent(eventName + '.revolver');
        }
    });

    // make Revolver globally available
    window.Revolver = Revolver;


    RevolverSlide = new Class({

        container: null,    // key for current slide

        // constructor
        initialize: function(container)
        {
            this.container  = container;

            return this;
        },

        show: function(options)
        {
            this.container.setStyles({display: 'block'});

            return this;
        },

        hide: function(options)
        {
            this.container.setStyles({display: 'none'});

            return this;
        }
    });


    Elements.implement({
        revolver: function(options){
            return this.each(function(element){
                return element.revolver(options);
            });
        }
    });


    Element.implement({
        revolver: function(options){
            if (this.retrieve('revolver') === null)
            {
                this.store('revolver', new Revolver(this, options));
            }
            return this;
        }
    });
    
})(document.id);