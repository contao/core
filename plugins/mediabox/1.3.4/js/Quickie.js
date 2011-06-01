/*
---
description: MooTools wrapper to embed QuickTime movies.

license: MIT-style

authors:
- Jose Prado

requires:
  core/1.2.4: [Browser, Class.Extras, Array, Hash, Element, Element.Event]

provides:
  Quickie
  Browser.Plugins.QuickTime

...
*/
(function() {
	var qtEvents = ['begin', 'loadedmetadata', 'loadedfirstframe', 'canplay', 'canplaythrough',
	'durationchange', 'load', 'ended', 'error', 'pause', 'play', 'progress', 'waiting', 'stalled',
	'timechanged', 'volumechange'];
	
	Quickie = new Class({
			
		Implements: [Options, Events],
		version: '2.1',
		options: {/*
			onPlay: $empty,
			onStop: $empty,*/
			id: null,
			height: 1,
			width: 1,
			container: null,
			attributes: {
				controller: "true",
				postdomevents: "true"
			}
		},
		
		initialize: function(path, options){
			this.setOptions(options);
			options = this.options;
			this.id = this.options.id || 'Quickie_' + $time();
			this.path = path;
			var container = options.container;
			
			// Extra attributes
			options.attributes = $H(options.attributes);
			options.attributes.src = path;
			
			// Get Browser appropriate HTML code
			this.html = this.toHTML();
			
			if (Browser.Engine.trident) {
				document.getElementById(container).innerHTML = this.html;
				this.quickie = document.getElementById(this.id);
				this.ieFix.delay(10, this);
			} else {
				var wrapper = document.createElement('div');
				wrapper.innerHTML = this.html;
				this.quickie = wrapper.firstChild;
				this.transferEvents();
				document.id(container).empty();
				document.getElementById(container).appendChild(this.quickie);
			}
		},
		
		toHTML: function() {
			if (!this.html) {
				var attributes = this.options.attributes,
				    height     = (attributes.controller == "true") ? this.options.height + 16 : this.options.height,
				    width      = this.options.width,
				    element    = '';
					  
				if (Browser.Engine.trident) {					
					element = '<object id="'+this.id+'" ';
					element += 'width="'+width+'" ';
					element += 'height="'+height+'" ';
					element += 'classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" ';
					element += 'style="behavior:url(#qt_event_source);" ';
					element += 'codebase="http://www.apple.com/qtactivex/qtplugin.cab">';
					
					attributes.each(function(value, key) {
						element += '<param name="'+key+'" value="'+value+'" />';
					});
					
					element += '</object>';
				} else {
					element = '<embed id="'+this.id+'" ';
					element += 'width="'+width+'" ';
					element += 'height="'+height+'" ';
					element += 'pluginspage="http://www.apple.com/quicktime/download/" ';
					
					attributes.each(function(value, key) {
						element += key+'="'+value+'" ';
					});
					
					element += '/>';
				}
				this.html = element;
			}
			return this.html;
		},
		
		ieFix: function() {
			document.getElementById(this.id).SetResetPropertiesOnReload(false);
			document.getElementById(this.id).SetURL(this.path);
			this.transferEvents.delay(10, this);
		},
		
		transferEvents: function() {
			var element = this.quickie;
			
			qtEvents.each(function(evType) {
				addQTEvent(element, evType, this.fireEvent.pass(evType, this));
			}.bind(this));
		}
		
	});

	function addQTEvent(el, evType, fn, useCapture) {
		evType = 'qt_' + evType;
		if (el.addEventListener) {
			el.addEventListener(evType, fn, useCapture);
			return true;
		} else if (el.attachEvent) {
			var r = el.attachEvent('on' + evType, fn);
			return r;
		} else {
			el[evType] = fn;
		}
	}

})();

Browser.Plugins.QuickTime = (function(){
	if (navigator.plugins) {
		for (var i = 0, l = navigator.plugins.length; i < l; i++) {
			if (navigator.plugins[i].name.indexOf('QuickTime') >= 0) {
				return true;
			}
		}
	} else {
		try { var test = new ActiveXObject('QuickTime.QuickTime'); }
		catch(e) {}
		
		if (test) { return true; }
	}
	return false;
})();