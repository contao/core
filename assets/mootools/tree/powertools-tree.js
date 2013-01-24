// packager build Tree/Tree Tree/Collapse Tree/Collapse.Persistent
/*
---

name: Class.Binds

description: A clean Class.Binds Implementation

authors: Scott Kyle (@appden), Christoph Pojer (@cpojer)

license: MIT-style license.

requires: [Core/Class, Core/Function]

provides: Class.Binds

...
*/

Class.Binds = new Class({

	$bound: {},

	bound: function(name){
		return this.$bound[name] ? this.$bound[name] : this.$bound[name] = this[name].bind(this);
	}

});

/*
---

name: Class.Singleton

description: Beautiful Singleton Implementation that is per-context or per-object/element

authors: Christoph Pojer (@cpojer)

license: MIT-style license.

requires: [Core/Class]

provides: Class.Singleton

...
*/

(function(){

var storage = {

	storage: {},

	store: function(key, value){
		this.storage[key] = value;
	},

	retrieve: function(key){
		return this.storage[key] || null;
	}

};

Class.Singleton = function(){
	this.$className = String.uniqueID();
};

Class.Singleton.prototype.check = function(item){
	if (!item) item = storage;

	var instance = item.retrieve('single:' + this.$className);
	if (!instance) item.store('single:' + this.$className, this);

	return instance;
};

var gIO = function(klass){

	var name = klass.prototype.$className;

	return name ? this.retrieve('single:' + name) : null;

};

if (('Element' in this) && Element.implement) Element.implement({getInstanceOf: gIO});

Class.getInstanceOf = gIO.bind(storage);

})();

/*
---

name: Tree

description: Provides a way to sort and reorder a tree via drag&drop.

authors: Christoph Pojer (@cpojer)

license: MIT-style license.

requires: [Core/Events, Core/Element.Event, Core/Element.Style, Core/Element.Dimensions, Core/Fx.Tween, Core/Element.Delegation, More/Drag.Move, Class-Extras/Class.Binds, Class-Extras/Class.Singleton]

provides: Tree

...
*/

(function(){

this.Tree = new Class({

	Implements: [Options, Events, Class.Binds, Class.Singleton],

	options: {
		/*onChange: function(){},*/
		indicatorOffset: 0,
		cloneOffset: {x: 16, y: 16},
		cloneOpacity: 0.8,
		checkDrag: Function.from(true),
		checkDrop: Function.from(true)
	},

	initialize: function(element, options){
		this.setOptions(options);
		element = this.element = document.id(element);
		return this.check(element) || this.setup();
	},

	setup: function(){
		this.indicator = new Element('div.treeIndicator');

		var self = this;
		this.handler = function(e){
			self.mousedown(this, e);
		};

		this.attach();
	},

	attach: function(){
		this.element.addEvent('mousedown:relay(li)', this.handler);
		document.addEvent('mouseup', this.bound('mouseup'));
		return this;
	},

	detach: function(){
		this.element.removeEvent('mousedown:relay(li)', this.handler);
		document.removeEvent('mouseup', this.bound('mouseup'));
		return this;
	},

	mousedown: function(element, event){
		event.preventDefault();

		this.padding = (this.element.getElement('li ul li') || this.element.getElement('li')).getLeft() - this.element.getLeft() + this.options.indicatorOffset;
		if (this.collapse === undefined && typeof Collapse != 'undefined')
			this.collapse = this.element.getInstanceOf(Collapse);

		if(!this.options.checkDrag.call(this, element)) return;
		if (this.collapse && Slick.match(event.target, this.collapse.options.selector)) return;

		this.current = element;
		this.clone = element.clone().setStyles({
			left: event.page.x + this.options.cloneOffset.x,
			top: event.page.y + this.options.cloneOffset.y,
			opacity: this.options.cloneOpacity
		}).addClass('drag').inject(document.body);

		this.clone.makeDraggable({
			droppables: this.element.getElements('li span'),
			onLeave: this.bound('hideIndicator'),
			onDrag: this.bound('onDrag'),
			onDrop: this.bound('onDrop')
		}).start(event);
	},

	mouseup: function(){
		if (this.clone) this.clone.destroy();
	},

	onDrag: function(el, event){
		clearTimeout(this.timer);
		if (this.previous) this.previous.fade(1);
		this.previous = null;

		if (!event || !event.target) return;

		var droppable = (event.target.get('tag') == 'li') ? event.target : event.target.getParent('li');
		if (!droppable || this.element == droppable || !this.element.contains(droppable)) return;

		if (this.collapse) this.expandCollapsed(droppable);

		var coords = droppable.getCoordinates(),
			marginTop =  droppable.getStyle('marginTop').toInt(),
			center = coords.top + marginTop + (coords.height / 2),
			isSubnode = (event.page.x > coords.left + this.padding),
			position = {
				x: coords.left + (isSubnode ? this.padding : 0),
				y: coords.top
			};

		var drop;
		if ([droppable, droppable.getParent('li')].contains(this.current)){
			this.drop = {};
		} else if (event.page.y >= center){
			position.y += coords.height;
			drop = {
				target: droppable,
				where: 'after',
				isSubnode: isSubnode
			};
			if (!this.options.checkDrop.call(this, droppable, drop)) return;
			this.setDropTarget(drop);
		} else if (event.page.y < center){
			position.x = coords.left;
			drop = {
				target: droppable,
				where: 'before'
			};
			if (!this.options.checkDrop.call(this, droppable, drop)) return;
			this.setDropTarget(drop);
		}

		if (this.drop.target) this.showIndicator(position);
		else this.hideIndicator();
	},

	onDrop: function(el){
		el.destroy();
		this.hideIndicator();

		var drop = this.drop,
			current = this.current;
		if (!drop || !drop.target) return;

		var previous = current.getParent('li');
		if (drop.isSubnode) current.inject(drop.target.getElement('ul') || new Element('ul').inject(drop.target), 'bottom');
		else current.inject(drop.target, drop.where || 'after');

		if (this.collapse){
			if (previous) this.collapse.updateElement(previous);
			this.collapse.updateElement(drop.target);
		}

		this.fireEvent('change');
	},

	setDropTarget: function(drop){
		this.drop = drop;
	},

	showIndicator: function(position){
		this.indicator.setStyles({
			left: position.x + this.options.indicatorOffset,
			top: position.y
		}).inject(document.body);
	},

	hideIndicator: function(){
		this.indicator.dispose();
	},

	expandCollapsed: function(element){
		var child = element.getElement('ul');
		if (!child || !this.collapse.isCollapsed(child)) return;

		element.set('tween', {duration: 150}).fade(0.5);
		this.previous = element;
		this.timer = (function(){
			element.fade(1);
			this.collapse.expand(element);
		}).delay(300, this);
	},

	serialize: function(fn, base){
		if (!base) base = this.element;
		if (!fn) fn = function(el){
			return el.get('id');
		};

		var result = {};
		base.getChildren('li').each(function(el){
			var child = el.getElement('ul');
			result[fn(el)] = child ? this.serialize(fn, child) : true;
		}, this);
		return result;
	}

});

})();


/*
---

name: Collapse

description: Allows to expand or collapse a list or a tree.

authors: Christoph Pojer (@cpojer)

license: MIT-style license.

requires: [Core/Events, Core/Element.Event, Core/Element.Style, Core/Element.Dimensions, Core/Fx, More/Element.Delegation, Class-Extras/Class.Singleton]

provides: Collapse

...
*/

(function(){

this.Collapse = new Class({

	Implements: [Options, Class.Singleton],

	options: {
		animate: true,
		fadeOpacity: 0.5,
		className: 'collapse',
		selector: 'a.expand',
		listSelector: 'li',
		childSelector: 'ul'
	},

	initialize: function(element, options){
		this.setOptions(options);
		element = this.element = document.id(element);

		return this.check(element) || this.setup();
	},

	setup: function(){
		var self = this;
		this.handler = function(e){
			self.toggle(this, e);
		};

		this.mouseover = function(){
			if (self.hasChildren(this)) this.getElement(self.options.selector).fade(1);
		};

		this.mouseout = function(){
			if (self.hasChildren(this)) this.getElement(self.options.selector).fade(self.options.fadeOpacity);
		};

		this.prepare().attach();
	},

	attach: function(){
		var element = this.element;
		element.addEvent('click:relay(' + this.options.selector + ')', this.handler);
		if (this.options.animate){
			element.addEvent('mouseover:relay(' + this.options.listSelector + ')', this.mouseover);
			element.addEvent('mouseout:relay(' + this.options.listSelector + ')', this.mouseout);
		}
		return this;
	},

	detach: function(){
		this.element.removeEvent('click:relay(' + this.options.selector + ')', this.handler)
				.removeEvent('mouseover:relay(' + this.options.listSelector + ')', this.mouseover)
				.removeEvent('mouseout:relay(' + this.options.listSelector + ')', this.mouseout);
		return this;
	},

	prepare: function(){
		this.prepares = true;
		this.element.getElements(this.options.listSelector).each(this.updateElement, this);
		this.prepares = false;
		return this;
	},

	updateElement: function(element){
		var child = element.getElement(this.options.childSelector),
			icon = element.getElement(this.options.selector);

		if (!this.hasChildren(element)){
			if (!this.options.animate || this.prepares) icon.setStyle('opacity', 0);
			else icon.fade(0);
			return;
		}

		if (this.options.animate) icon.fade(this.options.fadeOpacity);
		else icon.setStyle('opacity', this.options.fadeOpacity);

		if (this.isCollapsed(child)) icon.removeClass('collapse');
		else icon.addClass('collapse');
	},

	hasChildren: function(element){
		var child = element.getElement(this.options.childSelector);
		return (child && child.getChildren().length);
	},

	isCollapsed: function(element){
		return (element.getStyle('display') == 'none');
	},

	toggle: function(element, event){
		if (event) event.preventDefault();

		if (!element.match(this.options.listSelector)) element = element.getParent(this.options.listSelector);

		if (this.isCollapsed(element.getElement(this.options.childSelector))) this.expand(element);
		else this.collapse(element);

		return this;
	},

	expand: function(element){
		element.getElement(this.options.childSelector).setStyle('display', 'block');
		element.getElement(this.options.selector).addClass(this.options.className);
		return this;
	},

	collapse: function(element){
		element.getElement(this.options.childSelector).setStyle('display', 'none');
		element.getElement(this.options.selector).removeClass(this.options.className);
		return this;
	}

});

})();


/*
---

name: Collapse.Persistent

description: Interface to automatically save the state to persistent storage.

authors: [Christoph Pojer (@cpojer), Sean McArthur (@seanmonstar)]

license: MIT-style license.

requires: [Collapse]

provides: Collapse.Persistent

...
*/

(function(){

this.Collapse.Persistent = new Class({

	Extends: this.Collapse,

	options: {

		getAttribute: function(element){
			return element.get('id');
		},

		getIdentifier: function(element){
			return 'collapse_' + element.get('id') + '_' + element.get('class').split(' ').join('_');
		}

	},

	setup: function(){
		this.key = this.options.getIdentifier.call(this, this.element);
		this.state = this.getState();
		this.parent();
	},

	prepare: function(){
		var obj = this.state;
		this.element.getElements(this.options.listSelector).each(function(element){
			if (!element.getElement(this.options.childSelector)) return;

			var state = obj[this.options.getAttribute.call(this, element)];
			if (state == 1) this.expand(element);
			else if (state == 0) this.collapse(element);
		}, this);

		return this.parent();
	},

	getState: function(){
		return {};
	},

	setState: function(element, state){
		this.state[this.options.getAttribute.call(this, element)] = state;
		return this;
	},

	expand: function(element){
		this.parent(element);
		this.setState(element, 1);
		return this;
	},

	collapse: function(element){
		this.parent(element);
		this.setState(element, 0);
		return this;
	}

});

})();

