// packager build Custom-Event/* Mobile/Browser.Features.Touch Mobile/Swipe
/*
---

name: Element.defineCustomEvent

description: Allows to create custom events based on other custom events.

authors: Christoph Pojer (@cpojer)

license: MIT-style license.

requires: [Core/Element.Event]

provides: Element.defineCustomEvent

...
*/

(function(){

[Element, Window, Document].invoke('implement', {hasEvent: function(event){
	var events = this.retrieve('events'),
		list = (events && events[event]) ? events[event].values : null;
	if (list){
		var i = list.length;
		while (i--) if (i in list){
			return true;
		}
	}
	return false;
}});

var wrap = function(custom, method, extended){
	method = custom[method];
	extended = custom[extended];

	return function(fn, name){
		if (extended && !this.hasEvent(name)) extended.call(this, fn, name);
		if (method) method.call(this, fn, name);
	};
};

var inherit = function(custom, base, method){
	return function(fn, name){
		base[method].call(this, fn, name);
		custom[method].call(this, fn, name);
	};
};

var events = Element.Events;

Element.defineCustomEvent = function(name, custom){
	var base = events[custom.base];

	custom.onAdd = wrap(custom, 'onAdd', 'onSetup');
	custom.onRemove = wrap(custom, 'onRemove', 'onTeardown');

	events[name] = base ? Object.append({}, custom, {

		base: base.base,

		condition: function(event, name){
			return (!base.condition || base.condition.call(this, event, name)) &&
				(!custom.condition || custom.condition.call(this, event, name));
		},

		onAdd: inherit(custom, base, 'onAdd'),
		onRemove: inherit(custom, base, 'onRemove')

	}) : custom;

	return this;
};

Element.enableCustomEvents = function(){
  Object.each(events, function(event, name){
    if (event.onEnable) event.onEnable.call(event, name);
  });
};

Element.disableCustomEvents = function(){
  Object.each(events, function(event, name){
    if (event.onDisable) event.onDisable.call(event, name);
  });
};

})();


/*
---

name: Browser.Features.Touch

description: Checks whether the used Browser has touch events

authors: Christoph Pojer (@cpojer)

license: MIT-style license.

requires: [Core/Browser]

provides: Browser.Features.Touch

...
*/

Browser.Features.Touch = (function(){
	try {
		document.createEvent('TouchEvent').initTouchEvent('touchstart');
		return true;
	} catch (exception){}
	
	return false;
})();

// Android doesn't have a touch delay and dispatchEvent does not fire the handler
Browser.Features.iOSTouch = (function(){
	var name = 'cantouch', // Name does not matter
		html = document.html,
		hasTouch = false;

	if (!html.addEventListener) return false;

	var handler = function(){
		html.removeEventListener(name, handler, true);
		hasTouch = true;
	};

	try {
		html.addEventListener(name, handler, true);
		var event = document.createEvent('TouchEvent');
		event.initTouchEvent(name);
		html.dispatchEvent(event);
		return hasTouch;
	} catch (exception){}

	handler(); // Remove listener
	return false;
})();


/*
---

name: Swipe

description: Provides a custom swipe event for touch devices

authors: Christopher Beloch (@C_BHole), Christoph Pojer (@cpojer), Ian Collins (@3n)

license: MIT-style license.

requires: [Core/Element.Event, Custom-Event/Element.defineCustomEvent, Browser.Features.Touch]

provides: Swipe

...
*/

(function(){

var name = 'swipe',
	distanceKey = name + ':distance',
	cancelKey = name + ':cancelVertical',
	dflt = 50;

var start = {}, disabled, active;

var clean = function(){
	active = false;
};

var events = {

	touchstart: function(event){
		if (event.touches.length > 1) return;

		var touch = event.touches[0];
		active = true;
		start = {x: touch.pageX, y: touch.pageY};
	},
	
	touchmove: function(event){
		if (disabled || !active) return;
		
		var touch = event.changedTouches[0],
			end = {x: touch.pageX, y: touch.pageY};
		if (this.retrieve(cancelKey) && Math.abs(start.y - end.y) > 10){
			active = false;
			return;
		}
		
		var distance = this.retrieve(distanceKey, dflt),
			delta = end.x - start.x,
			isLeftSwipe = delta < -distance,
			isRightSwipe = delta > distance;

		if (!isRightSwipe && !isLeftSwipe)
			return;
		
		event.preventDefault();
		active = false;
		event.direction = (isLeftSwipe ? 'left' : 'right');
		event.start = start;
		event.end = end;
		
		this.fireEvent(name, event);
	},

	touchend: clean,
	touchcancel: clean

};

Element.defineCustomEvent(name, {

	onSetup: function(){
		this.addEvents(events);
	},

	onTeardown: function(){
		this.removeEvents(events);
	},

	onEnable: function(){
		disabled = false;
	},

	onDisable: function(){
		disabled = true;
		clean();
	}

});

})();
