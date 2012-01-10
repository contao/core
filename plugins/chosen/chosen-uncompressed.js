/*
---
name: Chosen

description: Creates a Picker, which can be used for anything

authors:
 - Patrick Filler
 - Jules Janssen
 - Jonnathan Soares

requires:
 - Core/*
 - More/Element.Measure
 - More/Locale

license: MIT-style license

provides: Chosen
...
*/
Elements.implement({
	chosen: function(data, options){
		return this.each(function(el){
			if (!el.hasClass("chzn-done")) {
				return new Chosen(el, data, options);
			}
		});
   	}
});

var Chosen = new Class({

	active_field: false,
	mouse_on_container: false,
	results_showing: false,
	result_highlighted: null,
	result_single_selected: null,
	choices: 0,

	initialize: function(elmn){

		this.click_test_action = this.test_active_click.bind(this);
		this.form_field = elmn;
		this.is_multiple = this.form_field.multiple;
		this.is_rtl = this.form_field.hasClass("chzn-rtl");
		this.set_up_html();
		this.register_observers();

	},

	set_up_html: function(){

		var dd_top, dd_width, sf_width;

		if (!this.form_field.id) this.form_field.id = String.uniqueID();
		this.container_id = this.form_field.id.replace(/(:|\.)/g, '_') + "_chzn";
		this.f_width = this.form_field.measure(function() {
			// PATCH: measure elements in collapsed palettes (see #3627)
			//return this.getSize().x;
			return (this.getSize().x > 0) ? this.getSize().x : this.getStyle('width').toInt();
		});

		this.default_text = this.form_field.get('data-placeholder') ? this.form_field.get('data-placeholder') : Locale.get('Chosen.placeholder', this.form_field.multiple);

		this.container = new Element('div', {
			'id': 		this.container_id,
			'class': 	'chzn-container'+ (this.is_rtl ? ' chzn-rtl' : '') + " chzn-container-" + (this.is_multiple ? "multi" : "single")
		}).setStyle('width', this.f_width);

		if (this.is_multiple){

			this.container.set('html', '<ul class="chzn-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>');

		} else {

			this.container.set('html', '<a href="javascript:void(0)" class="chzn-single"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>');

		}

		this.form_field.setStyle('display', 'none').grab(this.container, 'after');
		this.dropdown = this.container.getElement('div.chzn-drop');

		dd_top = this.container.getCoordinates().height;
		dd_width = this.f_width - this.dropdown.get_side_border_padding();
		this.dropdown.setStyles({
			'width': 	dd_width,
			'top': 		dd_top
		});

		this.search_field = this.container.getElement('input');
		this.search_results = this.container.getElement('ul.chzn-results');
		this.search_field_scale();
		this.search_no_results = this.container.getElement('li.no-results');

		if (this.is_multiple){

			this.search_choices = this.container.getElement('ul.chzn-choices');
			this.search_container = this.container.getElement('li.search-field');

		} else {

			this.search_container = this.container.getElement('div.chzn-search');
			this.selected_item = this.container.getElement('.chzn-single');

			sf_width = dd_width - this.search_container.get_side_border_padding() - this.search_field.get_side_border_padding();
			this.search_field.setStyle('width', sf_width);

		}

		this.results_build();

		this.set_tab_index();

	},

	register_observers: function(){

		this.container.addEvents({
			click: this.container_click.bind(this),
			mouseenter: this.mouse_enter.bind(this),
			mouseleave: this.mouse_leave.bind(this)
		});

		this.search_results.addEvents({
			click: this.search_results_click.bind(this),
			mouseover: this.search_results_mouseover.bind(this),
			mouseout: this.search_results_mouseout.bind(this)
		});

		this.form_field.addEvent("liszt:updated", this.results_update_field.bind(this));

		this.search_field.addEvents({
			blur: this.input_blur.bind(this),
			keyup: this.keyup_checker.bind(this),
			keydown: this.keydown_checker.bind(this)
		});

		if (this.is_multiple){

			this.search_choices.addEvent("click", this.choices_click.bind(this));

			this.search_field.addEvent("focus", this.input_focus.bind(this));

		} else {

			this.selected_item.addEvent("focus", this.activate_field.bind(this));

		}

	},

	container_click: function(evt){

		if (evt && evt.type === "click"){
			evt.stopPropagation();
		}

		if (!this.pending_destroy_click){

			if (!this.active_field){

				if (this.is_multiple){

					this.search_field.value = '';

				}

				document.addEvent('click', this.click_test_action);
				this.results_show();

			}else if (!this.is_multiple && evt && (evt.target === this.selected_item || evt.target.getParents('a.chzn-single').length)){

				evt.preventDefault();
				this.results_toggle();

			}

			this.activate_field();

		} else {

			this.pending_destroy_click = false;

		}

	},

	mouse_enter: function(){
		this.mouse_on_container = true;
	},

	mouse_leave: function(){
		this.mouse_on_container = false;
	},

	input_focus: function(evt){
		if (!this.active_field){
			setTimeout(this.container_click.bind(this), 50);
		}
	},

	input_blur: function(evt){
		if (!this.mouse_on_container){
			this.active_field = false;
			setTimeout(this.blur_test.bind(this), 100);
		}
	},

	blur_test: function(evt){
		if (!this.active_field && this.container.hasClass("chzn-container-active")){
			this.close_field();
		}
	},

	close_field: function(){
		document.removeEvent('click', this.click_test_action);

		if (!this.is_multiple){
			this.selected_item.set('tabindex', this.search_field.get('tabindex'));
			this.search_field.set('tabindex', -1);
		}

		this.active_field = false;
		this.results_hide();
		this.container.removeClass("chzn-container-active");
		this.winnow_results_clear();
		this.clear_backstroke();
		this.show_search_field_default();

		this.search_field_scale();

	},

	activate_field: function(){

		if (!this.is_multiple && !this.active_field){
			this.search_field.set('tabindex', this.selected_item.get('tabindex'));
			this.selected_item.set('tabindex', -1);
		}
		this.container.addClass("chzn-container-active");
		this.active_field = true;
		this.search_field.set('value', this.search_field.get('value'));

		this.search_field.focus();

	},

	test_active_click: function(evt){

		if (evt.target.getParents('#' + this.container_id).length){
			this.active_field = true;
		} else {
			this.close_field();
		}

	},

	results_build: function(){

		this.parsing = true;
		this.results_data = this.form_field.select_to_array();

		if (this.is_multiple && this.choices > 0){

			this.search_choices.getElements("li.search-choice").destroy();
			this.choices = 0;

		}else if (!this.is_multiple){

			this.selected_item.getElements("span").set('text', this.default_text);

		}

		var content = '';
		this.results_data.each(function(data){
			if (data.group){
				content += this.result_add_group(data);
			}else if (!data.empty){
				content += this.result_add_option(data);
				if (data.selected && this.is_multiple){
					this.choice_build(data);
				}else if (data.selected && !this.is_multiple){
					this.selected_item.getElements("span").set('text', data.text);
				}
			}
		}, this);

		this.show_search_field_default();
		this.search_field_scale();
		this.search_results.set('html', content);

		this.parsing = false;

	},

	result_add_group: function(group){

		if (!group.disabled){

			group.dom_id =  this.container_id + "_g_" + group.array_index;
			return '<li id="' + group.dom_id + '" class="group-result"><div>'+ group.label + '</div></li>';

		} else {
			return '';
		}

	},

	result_add_option: function(option){

		var classes;
		if (!option.disabled){
			option.dom_id =  this.container_id + "_o_" + option.array_index;
			classes = option.selected && this.is_multiple ? [] : ["active-result"];

			if (option.selected){
				classes.push("result-selected");
			}

			if (option.group_array_index != null){
				classes.push("group-option");
			}

			return '<li id="' + option.dom_id + '" class="' + classes.join(' ') + '"><div>'+ option.html + '</div></li>';

		} else {

			return '';

		}

	},

	results_update_field: function(){

		this.result_clear_highlight();
		this.result_single_selected = null;
		this.results_build();

	},

	result_do_highlight: function(el){

		var high_bottom, high_top, maxHeight, visible_bottom, visible_top;

		if (el){

			this.result_clear_highlight();
			this.result_highlight = el;
			this.result_highlight.addClass("highlighted");
			maxHeight = parseInt(this.search_results.getStyle("maxHeight"), 10);

			visible_top = this.search_results.getScroll().y;
			visible_bottom = maxHeight + visible_top;

			high_top = this.result_highlight.getPosition(this.search_results).y + this.search_results.getScroll().y;
			high_bottom = high_top + this.result_highlight.getCoordinates().height;
			if (high_bottom >= visible_bottom){
				this.search_results.scrollTo(0, (high_bottom - maxHeight) > 0 ? high_bottom - maxHeight : 0);
			}else if (high_top < visible_top){
				this.search_results.scrollTo(0, high_top);
			}

		}

	},

	result_clear_highlight: function(){

		if (this.result_highlight){
			this.result_highlight.removeClass("highlighted");
		}
		this.result_highlight = null;

	},

	results_toggle: function(){
		if (this.results_showing) {
			this.results_hide();
		} else {
			this.results_show();
		}
	},

	results_show: function(){

		var dd_top;
		if (!this.is_multiple){

			this.selected_item.addClass("chzn-single-with-drop");

			if (this.result_single_selected){
				this.result_do_highlight(this.result_single_selected);
			}

		}

		dd_top = this.is_multiple ? this.container.getCoordinates().height : this.container.getCoordinates().height - 1;

		this.dropdown.setStyles({
			"top": dd_top,
			"left": 0
		});

		this.results_showing = true;
		this.search_field.focus();
		this.search_field.set('value', this.search_field.get('value'));

		this.winnow_results();

	},

	results_hide: function(){

		if (!this.is_multiple){
			this.selected_item.removeClass("chzn-single-with-drop");
		}

		this.result_clear_highlight();
		this.dropdown.setStyle('left', -9000);

		this.results_showing = false;

	},

	set_tab_index: function(el){

		var ti;
		if (this.form_field.get('tabindex')){
			ti = this.form_field.get('tabindex');
			this.form_field.set('tabindex', -1);

			if (this.is_multiple){
				this.search_field.set('tabindex', ti);
			} else {
				this.selected_item.set('tabindex', ti);
				this.search_field.set('tabindex', -1);
			}
		}

	},

	show_search_field_default: function(){

		if (this.is_multiple && this.choices < 1 && !this.active_field){
			this.search_field.set('value', this.default_text);
			this.search_field.addClass("default");
		} else {
			this.search_field.set('value', "");
			this.search_field.removeClass("default");
		}
	},

	search_results_click: function(evt){

		var target = evt.target.hasClass("active-result") ? evt.target : evt.target.getParent(".active-result");

		if (target){
			this.result_highlight = target;
			this.result_select(evt);
		}

	},

	search_results_mouseover: function(evt){

		var target = evt.target.hasClass("active-result") ? evt.target : evt.target.getParent(".active-result");
		if (target){
			this.result_do_highlight(target);
		}

	},

	search_results_mouseout: function(evt){

		if (evt.target.hasClass("active-result") || evt.target.getParent('.active-result')){
			this.result_clear_highlight();
		}
	},

	choices_click: function(evt){

		evt.preventDefault();
		if (this.active_field && !(evt.target.hasClass("search-choice") || evt.target.getParent('.search-choice')) && !this.results_showing){
			this.results_show();
		}

	},

	choice_build: function(item){

		var choice_id = this.container_id + "_c_" + item.array_index;
		this.choices += 1;

		var el = new Element('li', {'id': choice_id})
					.addClass('search-choice')
					.set('html', '<span>' + item.html + '</span><a href="#" class="search-choice-close" rel="' + item.array_index + '"></a>');

		this.search_container.grab(el, 'before');

		document.id(choice_id).getElement("a")
		.addEvent('click', this.choice_destroy_link_click.bind(this));

	},

	choice_destroy_link_click: function(evt){

		evt.preventDefault();

		this.pending_destroy_click = true;
		this.choice_destroy(evt.target);

	},

	choice_destroy: function(link){

		this.choices -= 1;
		this.show_search_field_default();
		if (this.is_multiple && this.choices > 0 && this.search_field.value.length < 1){
			this.results_hide();
		}
		this.result_deselect(link.get("rel"));
		link.getParent('li').destroy();

	},

	result_select: function(evt){
		var high, high_id, item, position;

		if (this.result_highlight){

			high = this.result_highlight;
			high_id = high.get("id");
			this.result_clear_highlight();
			high.addClass("result-selected");

			if (this.is_multiple){
				this.result_deactivate(high);
			} else {
				this.result_single_selected = high;
			}

			position = high_id.substr(high_id.lastIndexOf("_") + 1);
			item = this.results_data[position];
			item.selected = true;
			this.form_field.options[item.options_index].selected = true;

			if (this.is_multiple){
				this.choice_build(item);
			} else {
				this.selected_item.getElement("span").set('text', item.text);
			}

			if (!this.is_multiple || !evt.control) this.results_hide();
			this.search_field.set('value', "");
			this.form_field.fireEvent("change");

			// PATCH: fireEvent seems to be missing the onchange attribute
			if (typeof(this.form_field.onchange) == 'function') this.form_field.onchange(); 

			this.search_field_scale();

		}

	},

	result_activate: function(el){

		el.addClass("active-result").setStyle('display', 'block');

	},

	result_deactivate: function(el){

		el.removeClass("active-result").setStyle('display', 'none');

	},

	result_deselect: function(pos){
		var result, result_data;

		result_data = this.results_data[pos];
		result_data.selected = false;
		this.form_field.options[result_data.options_index].selected = false;
		result = document.id( this.container_id + "_o_" + pos);
		result.removeClass("result-selected").addClass("active-result").setStyle('display', 'block');
		this.result_clear_highlight();
		this.winnow_results();

		this.form_field.fireEvent("change");
		this.search_field_scale();

	},

	results_search: function(evt){

		if (this.results_showing){
			this.winnow_results();
		} else {
			this.results_show();
		}

	},

	winnow_results: function(){
		var found, option, parts, regex, result_id, results, searchText, startpos, text, zregex;

		this.no_results_clear();
		results = 0;
		searchText = this.search_field.get('value') === this.default_text ? "" : new Element('div', {text: this.search_field.get('value').trim()}).get('html');
		// PATCH: change the search type from "starts with" to "contains"
		//regex = new RegExp('^' + searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
		regex = new RegExp(searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
		zregex = new RegExp(searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');

		this.results_data.each(function(option){

			if (!option.disabled && !option.empty){

				if (option.group){
					document.id(option.dom_id).setStyle('display', 'none');
				}else if (!(this.is_multiple && option.selected)){
					found = false;
					result_id = option.dom_id

					if (regex.test(option.text)){
						found = true;
						results += 1;
					}else if (option.text.indexOf(" ") >= 0 || option.text.indexOf("[") === 0){
						parts = option.text.replace(/\[|\]/g, "").split(" ");

						if (parts.length){
							parts.each(function(part){
								if (regex.test(part)){
									found = true;
									results += 1;
								}
							});
						}

					}

					if (found){

						if (searchText.length){

							startpos = option.html.search(zregex);
							text = option.html.substr(0, startpos + searchText.length) + '</em>' + option.html.substr(startpos + searchText.length);
							text = text.substr(0, startpos) + '<em>' + text.substr(startpos);

						} else {

							text = option.html;

						}

						if (document.id(result_id).get('html') !== text){
							document.id(result_id).set('html', text);
						}

						this.result_activate(document.id(result_id));

						if (option.group_array_index != null){
							document.id(this.results_data[option.group_array_index].dom_id).setStyle('display', 'block');
						}

					} else {

						if (this.result_highlight && result_id === this.result_highlight.get('id')){

							this.result_clear_highlight();

						}

						this.result_deactivate(document.id(result_id));

					}

				}

			}

		}, this);

		if (results < 1 && searchText.length){
			this.no_results(searchText);
		} else {
			this.winnow_results_set_highlight();
		}

	},

	winnow_results_clear: function(){

		this.search_field.set('value', '');
		this.search_results.getElements("li").each(function(li){
			li.hasClass("group-result") ? li.setStyle('display', 'block') : !this.is_multiple || !li.hasClass("result-selected") ? this.result_activate(li) : void 0;
		}, this);

	},

	winnow_results_set_highlight: function(){

		if (!this.result_highlight){

			var selected_results = !this.is_multiple ? this.search_results.getElements(".result-selected") : [];
			var do_high = selected_results.length ? selected_results[0] : this.search_results.getElement(".active-result");
        	if (do_high != null) {
				this.result_do_highlight(do_high);
			}

		}

	},

	no_results: function(terms){

		var no_results_html = new Element('li', {'class': 'no-results'}).set('html', Locale.get('Chosen.noResults')+' "<span></span>"');
		no_results_html.getElement("span").set('html', terms);
		this.search_results.grab(no_results_html);

	},

	no_results_clear: function(){
		this.search_results.getElements(".no-results").destroy();
	},

	keydown_arrow: function(){
		var first_active, next_sib;

		if (!this.result_highlight){

			first_active = this.search_results.getElement("li.active-result");

			if (first_active){
				this.result_do_highlight(first_active);
			}

		}else if (this.results_showing){

			next_sib = this.result_highlight.getNext("li.active-result");

			if (next_sib){
				this.result_do_highlight(next_sib);
			}

		}

		if (!this.results_showing){
			this.results_show();
		}

	},

	keyup_arrow: function(){

		if (!this.results_showing && !this.is_multiple){

			this.results_show();

		}else if (this.result_highlight){

			var prev_sibs = this.result_highlight.getAllPrevious("li.active-result");

			if (prev_sibs.length){
				this.result_do_highlight(prev_sibs[0]);
			} else {

				if (this.choices > 0){
					this.results_hide();
				}

				this.result_clear_highlight();

			}
		}
	},

	keydown_backstroke: function(){

		if (this.pending_backstroke){
			this.choice_destroy(this.pending_backstroke.getElement("a"));
			this.clear_backstroke();
		} else {
			this.pending_backstroke = this.search_choices.getLast("li.search-choice");
			this.pending_backstroke.addClass("search-choice-focus");
		}
	},

	clear_backstroke: function(){

		if (this.pending_backstroke){
			this.pending_backstroke.removeClass("search-choice-focus");
		}
		this.pending_backstroke = null;

	},

	keyup_checker: function(evt){
		this.search_field_scale();

		switch (evt.key){
			case 'backspace':

				if (this.is_multiple && this.backstroke_length < 1 && this.choices > 0){
					this.keydown_backstroke();
				}else if (!this.pending_backstroke){
					this.result_clear_highlight();
					this.results_search();
				}
				break;

			case 'enter':

				evt.preventDefault();
				if (this.results_showing){
					this.result_select(evt);
				}
				break;
			case 'esc':
				if (this.results_showing) {
					this.results_hide();
				}
				break;
			case 'tab':
			case 'up':
			case 'down':
			case 'shift':
			case 'ctrl':
				break;

			default:
				this.results_search();

		}

	},

	keydown_checker: function(evt){
		this.search_field_scale();

		if (evt.key !== 'backspace' && this.pending_backstroke){
			this.clear_backstroke();
		}

		switch(evt.key){
			case 'backspace':
				this.backstroke_length = this.search_field.value.length;
				break;

			case 'tab':
				this.mouse_on_container = false;
				break;

			case 'enter':
				evt.preventDefault();
				break;

			case 'up':
				evt.preventDefault();
				this.keyup_arrow();
				break;

			case 'down':
				this.keydown_arrow();
				break;
		}

	},

	search_field_scale: function(){
		var dd_top, div, h, style, style_block, styles, w, _i, _len;

		if (this.is_multiple){
			h = 0;
			w = 0;
			style_block = {
				position: 'absolute',
				visibility: 'hidden'
			};
			styles = this.search_field.getStyles('font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing');
			Object.merge(style_block, styles);
			div = new Element('div', {
				'styles': style_block
			});
			div.set('text', this.search_field.get('value'));
			document.body.grab(div);
			w = div.getCoordinates().width + 25;
			div.destroy();
			if (w > this.f_width - 10) {
				w = this.f_width - 10;
			}
			this.search_field.setStyle('width', w);
			dd_top = this.container.getCoordinates().height;
			this.dropdown.setStyle('top', dd_top);
		}

	}
});

Element.implement({
	get_side_border_padding: function(){
		var styles = this.getStyles('padding-left', 'padding-right', 'border-left-width', 'border-right-width');
		var notNull = Object.filter(styles, function(value){
			return (typeof(value) == 'string');
		});
		var mapped = Object.map(notNull, function(s){ return s.toInt();});
		var array = Object.values(mapped);
		var result = 0, l = array.length;
		if (l){
			while (l--) result += array[l];
		}
		return result;
	},
	select_to_array: function(){
		var parser = new SelectParser();

		this.getChildren().each(function(child){
			parser.add_node(child);
		});

		return parser.parsed;
	}
});

var SelectParser = new Class({

	options_index: 0,
	parsed: [],

	add_node: function(child){

		if (child.nodeName.toUpperCase() === "OPTGROUP"){
			this.add_group(child);
		} else {
			this.add_option(child);
		}
	},

	add_group: function(group){

		var group_position = this.parsed.length;
		this.parsed.push({
			array_index: group_position,
			group: true,
			label: group.label,
			children: 0,
			disabled: group.disabled
		});

		group.getChildren().each(function(option){
			this.add_option(option, group_position, group.disabled);
		}, this);
	},

	add_option: function(option, group_position, group_disabled){

		if (option.nodeName.toUpperCase() === "OPTION") {

			if (option.text !== ""){
				if (group_position != null) {
					this.parsed[group_position].children += 1;
				}

				this.parsed.push({
					array_index: this.parsed.length,
					options_index: this.options_index,
					value: option.get("value"),
					text: option.get("text").trim(),
					html: option.get("html"),
					selected: option.selected,
					disabled: group_disabled === true ? group_disabled : option.disabled,
					group_array_index: group_position
				});
			} else {
				this.parsed.push({
					array_index: this.parsed.length,
					options_index: this.options_index,
					empty: true
				});
			}

			this.options_index += 1;
		}
	}
});