/* Chosen by Patrick Filler, Jules Janssen, Jonnathan Soares, MIT-style license */
Elements.implement({chosen:function(a,b){return this.each(function(c){if(!c.hasClass("chzn-done"))return new Chosen(c,a,b)})}});var Chosen=new Class({active_field:!1,mouse_on_container:!1,results_showing:!1,result_highlighted:null,result_single_selected:null,choices:0,initialize:function(a){this.click_test_action=this.test_active_click.bind(this),this.form_field=a,this.is_multiple=this.form_field.multiple,this.is_rtl=this.form_field.hasClass("chzn-rtl"),this.set_up_html(),this.register_observers()},set_up_html:function(){var a,b,c;this.form_field.id||(this.form_field.id=String.uniqueID()),this.container_id=this.form_field.id.replace(/(:|\.)/g,"_")+"_chzn",this.f_width=this.form_field.measure(function(){return this.getSize().x>0?this.getSize().x:this.getStyle("width").toInt()}),this.default_text=this.form_field.get("data-placeholder")?this.form_field.get("data-placeholder"):Locale.get("Chosen.placeholder",this.form_field.multiple),this.container=(new Element("div",{id:this.container_id,"class":"chzn-container"+(this.is_rtl?" chzn-rtl":"")+" chzn-container-"+(this.is_multiple?"multi":"single")})).setStyle("width",this.f_width),this.is_multiple?this.container.set("html",'<ul class="chzn-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>'):this.container.set("html",'<a href="javascript:void(0)" class="chzn-single"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>'),this.form_field.setStyle("display","none").grab(this.container,"after"),this.dropdown=this.container.getElement("div.chzn-drop"),a=this.container.getCoordinates().height,b=this.f_width-this.dropdown.get_side_border_padding(),this.dropdown.setStyles({width:b,top:a}),this.search_field=this.container.getElement("input"),this.search_results=this.container.getElement("ul.chzn-results"),this.search_field_scale(),this.search_no_results=this.container.getElement("li.no-results"),this.is_multiple?(this.search_choices=this.container.getElement("ul.chzn-choices"),this.search_container=this.container.getElement("li.search-field")):(this.search_container=this.container.getElement("div.chzn-search"),this.selected_item=this.container.getElement(".chzn-single"),c=b-this.search_container.get_side_border_padding()-this.search_field.get_side_border_padding(),this.search_field.setStyle("width",c)),this.results_build(),this.set_tab_index()},register_observers:function(){this.container.addEvents({click:this.container_click.bind(this),mouseenter:this.mouse_enter.bind(this),mouseleave:this.mouse_leave.bind(this)}),this.search_results.addEvents({click:this.search_results_click.bind(this),mouseover:this.search_results_mouseover.bind(this),mouseout:this.search_results_mouseout.bind(this)}),this.form_field.addEvent("liszt:updated",this.results_update_field.bind(this)),this.search_field.addEvents({blur:this.input_blur.bind(this),keyup:this.keyup_checker.bind(this),keydown:this.keydown_checker.bind(this)}),this.is_multiple?(this.search_choices.addEvent("click",this.choices_click.bind(this)),this.search_field.addEvent("focus",this.input_focus.bind(this))):this.selected_item.addEvent("focus",this.activate_field.bind(this))},container_click:function(a){a&&a.type==="click"&&a.stopPropagation(),this.pending_destroy_click?this.pending_destroy_click=!1:(this.active_field?!this.is_multiple&&a&&(a.target===this.selected_item||a.target.getParents("a.chzn-single").length)&&(a.preventDefault(),this.results_toggle()):(this.is_multiple&&(this.search_field.value=""),document.addEvent("click",this.click_test_action),this.results_show()),this.activate_field())},mouse_enter:function(){this.mouse_on_container=!0},mouse_leave:function(){this.mouse_on_container=!1},input_focus:function(a){this.active_field||setTimeout(this.container_click.bind(this),50)},input_blur:function(a){this.mouse_on_container||(this.active_field=!1,setTimeout(this.blur_test.bind(this),100))},blur_test:function(a){!this.active_field&&this.container.hasClass("chzn-container-active")&&this.close_field()},close_field:function(){document.removeEvent("click",this.click_test_action),this.is_multiple||(this.selected_item.set("tabindex",this.search_field.get("tabindex")),this.search_field.set("tabindex",-1)),this.active_field=!1,this.results_hide(),this.container.removeClass("chzn-container-active"),this.winnow_results_clear(),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},activate_field:function(){!this.is_multiple&&!this.active_field&&(this.search_field.set("tabindex",this.selected_item.get("tabindex")),this.selected_item.set("tabindex",-1)),this.container.addClass("chzn-container-active"),this.active_field=!0,this.search_field.set("value",this.search_field.get("value")),this.search_field.focus()},test_active_click:function(a){a.target.getParents("#"+this.container_id).length?this.active_field=!0:this.close_field()},results_build:function(){this.parsing=!0,this.results_data=this.form_field.select_to_array(),this.is_multiple&&this.choices>0?(this.search_choices.getElements("li.search-choice").destroy(),this.choices=0):this.is_multiple||this.selected_item.getElements("span").set("text",this.default_text);var a="";this.results_data.each(function(b){b.group?a+=this.result_add_group(b):b.empty||(a+=this.result_add_option(b),b.selected&&this.is_multiple?this.choice_build(b):b.selected&&!this.is_multiple&&this.selected_item.getElements("span").set("html",b.html))},this),this.show_search_field_default(),this.search_field_scale(),this.search_results.set("html",a),this.parsing=!1},result_add_group:function(a){return a.disabled?"":(a.dom_id=this.container_id+"_g_"+a.array_index,'<li id="'+a.dom_id+'" class="group-result"><div>'+a.label+"</div></li>")},result_add_option:function(a){var b;return a.disabled?"":(a.dom_id=this.container_id+"_o_"+a.array_index,b=a.selected&&this.is_multiple?[]:["active-result"],a.selected&&b.push("result-selected"),a.group_array_index!=null&&b.push("group-option"),'<li id="'+a.dom_id+'" class="'+b.join(" ")+'"><div>'+a.html+"</div></li>")},results_update_field:function(){this.result_clear_highlight(),this.result_single_selected=null,this.results_build()},result_do_highlight:function(a){var b,c,d,e,f;a&&(this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.getStyle("maxHeight"),10),f=this.search_results.getScroll().y,e=d+f,c=this.result_highlight.getPosition(this.search_results).y+this.search_results.getScroll().y,b=c+this.result_highlight.getCoordinates().height,b>=e?this.search_results.scrollTo(0,b-d>0?b-d:0):c<f&&this.search_results.scrollTo(0,c))},result_clear_highlight:function(){this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},results_toggle:function(){this.results_showing?this.results_hide():this.results_show()},results_show:function(){var a;this.is_multiple||(this.selected_item.addClass("chzn-single-with-drop"),this.result_single_selected&&this.result_do_highlight(this.result_single_selected)),a=this.is_multiple?this.container.getCoordinates().height:this.container.getCoordinates().height-1,this.dropdown.setStyles({top:a,left:0}),this.results_showing=!0,this.search_field.focus(),this.search_field.set("value",this.search_field.get("value")),this.winnow_results()},results_hide:function(){this.is_multiple||this.selected_item.removeClass("chzn-single-with-drop"),this.result_clear_highlight(),this.dropdown.setStyle("left",-9e3),this.results_showing=!1},set_tab_index:function(a){var b;this.form_field.get("tabindex")&&(b=this.form_field.get("tabindex"),this.form_field.set("tabindex",-1),this.is_multiple?this.search_field.set("tabindex",b):(this.selected_item.set("tabindex",b),this.search_field.set("tabindex",-1)))},show_search_field_default:function(){this.is_multiple&&this.choices<1&&!this.active_field?(this.search_field.set("value",this.default_text),this.search_field.addClass("default")):(this.search_field.set("value",""),this.search_field.removeClass("default"))},search_results_click:function(a){var b=a.target.hasClass("active-result")?a.target:a.target.getParent(".active-result");b&&(this.result_highlight=b,this.result_select(a))},search_results_mouseover:function(a){var b=a.target.hasClass("active-result")?a.target:a.target.getParent(".active-result");b&&this.result_do_highlight(b)},search_results_mouseout:function(a){(a.target.hasClass("active-result")||a.target.getParent(".active-result"))&&this.result_clear_highlight()},choices_click:function(a){a.preventDefault(),this.active_field&&!a.target.hasClass("search-choice")&&!a.target.getParent(".search-choice")&&!this.results_showing&&this.results_show()},choice_build:function(a){var b=this.container_id+"_c_"+a.array_index;this.choices+=1;var c=(new Element("li",{id:b})).addClass("search-choice").set("html","<span>"+a.html+'</span><a href="#" class="search-choice-close" rel="'+a.array_index+'"></a>');this.search_container.grab(c,"before"),document.id(b).getElement("a").addEvent("click",this.choice_destroy_link_click.bind(this))},choice_destroy_link_click:function(a){a.preventDefault(),this.pending_destroy_click=!0,this.choice_destroy(a.target)},choice_destroy:function(a){this.choices-=1,this.show_search_field_default(),this.is_multiple&&this.choices>0&&this.search_field.value.length<1&&this.results_hide(),this.result_deselect(a.get("rel")),a.getParent("li").destroy()},result_select:function(a){var b,c,d,e;this.result_highlight&&(b=this.result_highlight,c=b.get("id"),this.result_clear_highlight(),b.addClass("result-selected"),this.is_multiple?this.result_deactivate(b):this.result_single_selected=b,e=c.substr(c.lastIndexOf("_")+1),d=this.results_data[e],d.selected=!0,this.form_field.options[d.options_index].selected=!0,this.is_multiple?this.choice_build(d):this.selected_item.getElement("span").set("text",d.text),(!this.is_multiple||!a.control)&&this.results_hide(),this.search_field.set("value",""),this.form_field.fireEvent("change"),typeof this.form_field.onchange=="function"&&this.form_field.onchange(),this.search_field_scale())},result_activate:function(a){a.addClass("active-result").setStyle("display","block")},result_deactivate:function(a){a.removeClass("active-result").setStyle("display","none")},result_deselect:function(a){var b,c;c=this.results_data[a],c.selected=!1,this.form_field.options[c.options_index].selected=!1,b=document.id(this.container_id+"_o_"+a),b.removeClass("result-selected").addClass("active-result").setStyle("display","block"),this.result_clear_highlight(),this.winnow_results(),this.form_field.fireEvent("change"),this.search_field_scale()},results_search:function(a){this.results_showing?this.winnow_results():this.results_show()},winnow_results:function(){var a,b,c,d,e,f,g,h,i,j;this.no_results_clear(),f=0,g=this.search_field.get("value")===this.default_text?"":(new Element("div",{text:this.search_field.get("value").trim()})).get("html"),d=new RegExp(g.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),j=new RegExp(g.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),this.results_data.each(function(b){if(!b.disabled&&!b.empty)if(b.group)document.id(b.dom_id).setStyle("display","none");else if(!this.is_multiple||!b.selected){a=!1,e=b.dom_id;if(d.test(b.text))a=!0,f+=1;else if(b.text.indexOf(" ")>=0||b.text.indexOf("[")===0)c=b.text.replace(/\[|\]/g,"").split(" "),c.length&&c.each(function(b){d.test(b)&&(a=!0,f+=1)});a?(g.length?(h=b.html.search(j),i=b.html.substr(0,h+g.length)+"</em>"+b.html.substr(h+g.length),i=i.substr(0,h)+"<em>"+i.substr(h)):i=b.html,document.id(e).get("html")!==i&&document.id(e).set("html",i),this.result_activate(document.id(e)),b.group_array_index!=null&&document.id(this.results_data[b.group_array_index].dom_id).setStyle("display","block")):(this.result_highlight&&e===this.result_highlight.get("id")&&this.result_clear_highlight(),this.result_deactivate(document.id(e)))}},this),f<1&&g.length?this.no_results(g):this.winnow_results_set_highlight()},winnow_results_clear:function(){this.search_field.set("value",""),this.search_results.getElements("li").each(function(a){a.hasClass("group-result")?a.setStyle("display","block"):!this.is_multiple||!a.hasClass("result-selected")?this.result_activate(a):void 0},this)},winnow_results_set_highlight:function(){if(!this.result_highlight){var a=this.is_multiple?[]:this.search_results.getElements(".result-selected"),b=a.length?a[0]:this.search_results.getElement(".active-result");b!=null&&this.result_do_highlight(b)}},no_results:function(a){var b=(new Element("li",{"class":"no-results"})).set("html",Locale.get("Chosen.noResults")+' "<span></span>"');b.getElement("span").set("html",a),this.search_results.grab(b)},no_results_clear:function(){this.search_results.getElements(".no-results").destroy()},keydown_arrow:function(){var a,b;this.result_highlight?this.results_showing&&(b=this.result_highlight.getNext("li.active-result"),b&&this.result_do_highlight(b)):(a=this.search_results.getElement("li.active-result"),a&&this.result_do_highlight(a)),this.results_showing||this.results_show()},keyup_arrow:function(){if(!this.results_showing&&!this.is_multiple)this.results_show();else if(this.result_highlight){var a=this.result_highlight.getAllPrevious("li.active-result");a.length?this.result_do_highlight(a[0]):(this.choices>0&&this.results_hide(),this.result_clear_highlight())}},keydown_backstroke:function(){this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.getElement("a")),this.clear_backstroke()):(this.pending_backstroke=this.search_choices.getLast("li.search-choice"),this.pending_backstroke.addClass("search-choice-focus"))},clear_backstroke:function(){this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},keyup_checker:function(a){this.search_field_scale();switch(a.key){case"backspace":this.is_multiple&&this.backstroke_length<1&&this.choices>0?this.keydown_backstroke():this.pending_backstroke||(this.result_clear_highlight(),this.results_search());break;case"enter":a.preventDefault(),this.results_showing&&this.result_select(a);break;case"esc":this.results_showing&&this.results_hide();break;case"tab":case"up":case"down":case"shift":case"ctrl":break;default:this.results_search()}},keydown_checker:function(a){this.search_field_scale(),a.key!=="backspace"&&this.pending_backstroke&&this.clear_backstroke();switch(a.key){case"backspace":this.backstroke_length=this.search_field.value.length;break;case"tab":this.mouse_on_container=!1;break;case"enter":a.preventDefault();break;case"up":a.preventDefault(),this.keyup_arrow();break;case"down":this.keydown_arrow()}},search_field_scale:function(){var a,b,c,d,e,f,g,h,i;this.is_multiple&&(c=0,g=0,e={position:"absolute",visibility:"hidden"},f=this.search_field.getStyles("font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"),Object.merge(e,f),b=new Element("div",{styles:e}),b.set("text",this.search_field.get("value")),$(document.body).grab(b),g=b.getCoordinates().width+25,b.destroy(),g>this.f_width-10&&(g=this.f_width-10),this.search_field.setStyle("width",g),a=this.container.getCoordinates().height,this.dropdown.setStyle("top",a))}});Element.implement({get_side_border_padding:function(){var a=this.getStyles("padding-left","padding-right","border-left-width","border-right-width"),b=Object.filter(a,function(a){return typeof a=="string"}),c=Object.map(b,function(a){return a.toInt()}),d=Object.values(c),e=0,f=d.length;if(f)while(f--)e+=d[f];return e},select_to_array:function(){var a=new SelectParser;return this.getChildren().each(function(b){a.add_node(b)}),a.parsed}});var SelectParser=new Class({options_index:0,parsed:[],add_node:function(a){a.nodeName.toUpperCase()==="OPTGROUP"?this.add_group(a):this.add_option(a)},add_group:function(a){var b=this.parsed.length;this.parsed.push({array_index:b,group:!0,label:a.label,children:0,disabled:a.disabled}),a.getChildren().each(function(c){this.add_option(c,b,a.disabled)},this)},add_option:function(a,b,c){a.nodeName.toUpperCase()==="OPTION"&&(a.text!==""?(b!=null&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.get("value"),text:a.get("text").trim(),html:a.get("html").replace("[",'<span style="color:#b3b3b3;padding-left:3px">[').replace("]","]</span>"),selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1)}});