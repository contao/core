/* Contao Open Source CMS, (C) 2005-2012 Leo Feyer, LGPL license */
var Stylect={convertSelects:function(){$$("select").each(function(e){if(Browser.ie6||Browser.ie7||Browser.ie8)return;if(e.get("multiple"))return;if(e.hasClass("tl_chosen"))return;if((active=e.getElement('option[value="'+e.value+'"]'))!=null)var t=active.get("html");else var t=e.getElement("option").get("html");var n=e.getComputedSize().totalWidth||e.getStyle("width").toInt()+e.getStyle("border-left-width").toInt()+e.getStyle("border-right-width").toInt(),r=(new Element("div",{"class":"styled_select",html:"<span>"+t+"</span><b><i></i></b>",styles:{width:n-(Browser.safari||Browser.chrome?6:8)}})).inject(e,"before");e.disabled&&r.addClass("disabled"),r.getPosition().x!=e.getPosition().x&&(r.position({relativeTo:e,ignoreMargins:!0}),Browser.safari?r.setStyle("top",r.getStyle("top")=="22px"?"24px":0):Browser.opera&&r.getStyle("top")=="23px"&&r.setStyle("top","24px")),e.hasClass("active")&&r.addClass("active"),e.addEvent("change",function(){var t=e.getElement('option[value="'+e.value+'"]');r.getElement("span").set("html",t.get("html"))}).addEvent("keydown",function(t){setTimeout(function(){e.fireEvent("change")},100)}).addEvent("focus",function(){r.addClass("focused")}).addEvent("blur",function(){r.removeClass("focused")}).setStyle("opacity",0),Browser.webkit=Browser.chrome||Browser.safari||navigator.userAgent.match(/(?:webkit|khtml)/i),Browser.webkit&&e.setStyle("margin-bottom","4px"),(Browser.webkit||Browser.ie)&&r.setStyle("width",r.getStyle("width").toInt()-4)})}};window.addEvent("domready",function(){Stylect.convertSelects()}),window.addEvent("ajax_change",function(){Stylect.convertSelects()});