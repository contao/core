/* Contao Open Source CMS :: Copyright (C) 2005-2013 Leo Feyer :: LGPL license */
Request.Contao=new Class({Extends:Request.JSON,options:{url:window.location.href},initialize:function(e){if(e)try{this.options.url=e.field.getParent("form").getAttribute("action")}catch(t){}this.parent(e)},success:function(e){var t;try{t=this.response.json=JSON.decode(e,this.options.secure)}catch(n){t={content:e}}t==null&&(t={content:""}),t.content!=""&&(t.content=t.content.stripScripts(function(e){t.javascript=e.replace(/<!--|\/\/-->|<!\[CDATA\[\/\/>|<!\]\]>/g,"")}),t.javascript&&this.options.evalScripts&&$exec(t.javascript)),this.onSuccess(t.content,t)}}),Request.Mixed=Request.Contao;var AjaxRequest={toggleNavigation:function(e,t){e.blur();var n=$(t),r=$(e).getFirst("img");return n?(n.getStyle("display")=="none"?(n.setStyle("display","inline"),r.src=r.src.replace("modPlus.gif","modMinus.gif"),$(e).title=CONTAO_COLLAPSE,(new Request.Contao).post({action:"toggleNavigation",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(n.setStyle("display","none"),r.src=r.src.replace("modMinus.gif","modPlus.gif"),$(e).title=CONTAO_EXPAND,(new Request.Contao).post({action:"toggleNavigation",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!1):((new Request.Contao({evalScripts:!0,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(i,s){n=(new Element("li",{id:t,"class":"tl_parent",html:i,styles:{display:"inline"}})).inject($(e).getParent("li"),"after"),$(e).title=CONTAO_COLLAPSE,r.src=r.src.replace("modPlus.gif","modMinus.gif"),AjaxRequest.hideBox(),window.fireEvent("ajax_change")}})).post({action:"loadNavigation",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN}),!1)},toggleStructure:function(e,t,n,r){e.blur();var i=$(t),s=$(e).getFirst("img");return i?(i.getStyle("display")=="none"?(i.setStyle("display","inline"),s.src=s.src.replace("folPlus.gif","folMinus.gif"),$(e).title=CONTAO_COLLAPSE,(new Request.Contao({field:e})).post({action:"toggleStructure",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(i.setStyle("display","none"),s.src=s.src.replace("folMinus.gif","folPlus.gif"),$(e).title=CONTAO_EXPAND,(new Request.Contao({field:e})).post({action:"toggleStructure",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!1):((new Request.Contao({field:e,evalScripts:!0,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(i,o){var u=new Element("li",{id:t,"class":"parent",styles:{display:"inline"}}),a=(new Element("ul",{"class":"level_"+n,html:i})).inject(u,"bottom");if(r==5)u.inject($(e).getParent("li"),"after");else{var f=!1,l=$(e).getParent("li");while(typeOf(l)=="element"&&(next=l.getNext("li"))){l=next;if(l.hasClass("tl_folder")){f=!0;break}}f?u.inject(l,"before"):u.inject(l,"after")}$(e).title=CONTAO_COLLAPSE,s.src=s.src.replace("folPlus.gif","folMinus.gif"),window.fireEvent("structure"),AjaxRequest.hideBox(),window.fireEvent("ajax_change")}})).post({action:"loadStructure",id:t,level:n,state:1,REQUEST_TOKEN:REQUEST_TOKEN}),!1)},toggleFileManager:function(e,t,n,r){e.blur();var i=$(t),s=$(e).getFirst("img"),o=$(e).getNext("img");return i?(i.getStyle("display")=="none"?(i.setStyle("display","inline"),s.src=s.src.replace("folPlus.gif","folMinus.gif"),o.src=o.src.replace("folderC","folderO"),$(e).title=CONTAO_COLLAPSE,(new Request.Contao({field:e})).post({action:"toggleFileManager",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(i.setStyle("display","none"),s.src=s.src.replace("folMinus.gif","folPlus.gif"),o.src=o.src.replace("folderO","folderC"),$(e).title=CONTAO_EXPAND,(new Request.Contao({field:e})).post({action:"toggleFileManager",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!1):((new Request.Contao({field:e,evalScripts:!0,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(n,i){var u=new Element("li",{id:t,"class":"parent",styles:{display:"inline"}}),a=(new Element("ul",{"class":"level_"+r,html:n})).inject(u,"bottom");u.inject($(e).getParent("li"),"after"),$(e).title=CONTAO_COLLAPSE,s.src=s.src.replace("folPlus.gif","folMinus.gif"),o.src=o.src.replace("folderC.gif","folderO.gif"),AjaxRequest.hideBox(),window.fireEvent("ajax_change")}})).post({action:"loadFileManager",id:t,level:r,folder:n,state:1,REQUEST_TOKEN:REQUEST_TOKEN}),!1)},togglePagetree:function(e,t,n,r,i){e.blur();var s=$(t),o=$(e).getFirst("img");return s?(s.getStyle("display")=="none"?(s.setStyle("display","inline"),o.src=o.src.replace("folPlus.gif","folMinus.gif"),$(e).title=CONTAO_COLLAPSE,(new Request.Contao({field:e})).post({action:"togglePagetree",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(s.setStyle("display","none"),o.src=o.src.replace("folMinus.gif","folPlus.gif"),$(e).title=CONTAO_EXPAND,(new Request.Contao({field:e})).post({action:"togglePagetree",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!1):((new Request.Contao({field:e,evalScripts:!0,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(n,r){var s=new Element("li",{id:t,"class":"parent",styles:{display:"inline"}}),u=(new Element("ul",{"class":"level_"+i,html:n})).inject(s,"bottom");s.inject($(e).getParent("li"),"after"),$(e).title=CONTAO_COLLAPSE,o.src=o.src.replace("folPlus.gif","folMinus.gif"),AjaxRequest.hideBox(),window.fireEvent("ajax_change")}})).post({action:"loadPagetree",id:t,level:i,field:n,name:r,state:1,REQUEST_TOKEN:REQUEST_TOKEN}),!1)},toggleFiletree:function(e,t,n,r,i,s){e.blur();var o=$(t),u=$(e).getFirst("img");return o?(o.getStyle("display")=="none"?(o.setStyle("display","inline"),u.src=u.src.replace("folPlus.gif","folMinus.gif"),$(e).title=CONTAO_COLLAPSE,(new Request.Contao({field:e})).post({action:"toggleFiletree",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(o.setStyle("display","none"),u.src=u.src.replace("folMinus.gif","folPlus.gif"),$(e).title=CONTAO_EXPAND,(new Request.Contao({field:e})).post({action:"toggleFiletree",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!1):((new Request.Contao({field:e,evalScripts:!0,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(n,r){var i=new Element("li",{id:t,"class":"parent",styles:{display:"inline"}}),o=(new Element("ul",{"class":"level_"+s,html:n})).inject(i,"bottom");i.inject($(e).getParent("li"),"after"),$(e).title=CONTAO_COLLAPSE,u.src=u.src.replace("folPlus.gif","folMinus.gif"),AjaxRequest.hideBox(),window.fireEvent("ajax_change")}})).post({action:"loadFiletree",id:t,level:s,folder:n,field:r,name:i,state:1,REQUEST_TOKEN:REQUEST_TOKEN}),!1)},reloadFiletrees:function(){$$(".filetree").each(function(e){var t=e.id,n=t.replace(/_[0-9]+$/,"");(new Request.Contao({evalScripts:!0,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(t,n){var r=$(e.id+"_parent").getFirst("ul"),i=r.getLast("li");r.set("html",t),i.inject(r,"bottom"),AjaxRequest.hideBox(),window.fireEvent("ajax_change")}})).post({action:"loadFiletree",field:n,name:t,REQUEST_TOKEN:REQUEST_TOKEN})})},toggleSubpalette:function(e,t,n){e.blur();var r=$(t);if(r){e.value?(e.value="",e.checked="",r.setStyle("display","none"),(new Request.Contao({field:e})).post({action:"toggleSubpalette",id:t,field:n,state:0,REQUEST_TOKEN:REQUEST_TOKEN})):(e.value=1,e.checked="checked",r.setStyle("display","block"),(new Request.Contao({field:e})).post({action:"toggleSubpalette",id:t,field:n,state:1,REQUEST_TOKEN:REQUEST_TOKEN}));return}(new Request.Contao({field:e,evalScripts:!1,onRequest:AjaxRequest.displayBox(CONTAO_LOADING+" …"),onSuccess:function(n,r){var i=(new Element("div",{id:t,html:n,styles:{display:"block"}})).inject($(e).getParent("div").getParent("div"),"after");r.javascript&&$exec(r.javascript),e.value=1,e.checked="checked",AjaxRequest.hideBox(),Backend.hideTreeBody(),Backend.addInteractiveHelp(),Backend.addColorPicker(),window.fireEvent("subpalette"),window.fireEvent("ajax_change")}})).post({action:"toggleSubpalette",id:t,field:n,load:1,state:1,REQUEST_TOKEN:REQUEST_TOKEN})},toggleVisibility:function(e,t,n){e.blur();var r=null,i=$(e).getFirst("img"),s=i.src.indexOf("invisible")!=-1,o=e.getParent("div");o.hasClass("tl_right")?r=o.getPrevious("div").getElement("img"):o.hasClass("tl_listing_container")?(r=e.getParent("td").getPrevious("td").getFirst("div.list_icon"),r==null&&(r=e.getParent("td").getPrevious("td").getElement("div.cte_type")),r==null&&(r=e.getParent("tr").getFirst("td").getElement("div.list_icon_new"))):(next=o.getNext("div"))&&next.hasClass("cte_type")&&(r=next);if(r!=null)if(r.nodeName.toLowerCase()=="img")if(r.getParent("ul.tl_listing").hasClass("tl_tree_xtnd"))s?r.src=r.src.replace(/_\.(gif|png|jpe?g)/,".$1"):r.src=r.src.replace(/\.(gif|png|jpe?g)/,"_.$1");else{r.src.match(/folPlus|folMinus/)&&(r.getParent("a").getNext("a")?r=r.getParent("a").getNext("a").getFirst("img"):r=new Element("img"));if(s){var u=r.src.replace(/.*_([0-9])\.(gif|png|jpe?g)/,"$1");r.src=r.src.replace(/_[0-9]\.(gif|png|jpe?g)/,(u.toInt()==1?"":"_"+(u.toInt()-1))+".$1")}else{var u=r.src.replace(/.*_([0-9])\.(gif|png|jpe?g)/,"$1");r.src=r.src.replace(/(_[0-9])?\.(gif|png|jpe?g)/,(u==r.src?"_1":"_"+(u.toInt()+1))+".$2")}}else r.hasClass("cte_type")?s?(r.addClass("published"),r.removeClass("unpublished")):(r.addClass("unpublished"),r.removeClass("published")):s?r.setStyle("background-image",r.getStyle("background-image").replace(/_\.(gif|png|jpe?g)/,".$1")):r.setStyle("background-image",r.getStyle("background-image").replace(/\.(gif|png|jpe?g)/,"_.$1"));return n=="tl_style"&&o.getParent("div").getElement("pre").toggleClass("disabled"),s?(i.src=i.src.replace("invisible.gif","visible.gif"),(new Request({url:window.location.href})).get({tid:t,state:1})):(i.src=i.src.replace("visible.gif","invisible.gif"),(new Request({url:window.location.href})).get({tid:t,state:0})),!1},toggleFeatured:function(e,t){e.blur();var n=$(e).getFirst("img"),r=n.src.indexOf("featured_")==-1;return r?(n.src=n.src.replace("featured.gif","featured_.gif"),(new Request.Contao).post({action:"toggleFeatured",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})):(n.src=n.src.replace("featured_.gif","featured.gif"),(new Request.Contao).post({action:"toggleFeatured",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})),!1},toggleFieldset:function(e,t,n){e.blur();var r=$("pal_"+t);return r.hasClass("collapsed")?(r.removeClass("collapsed"),(new Request.Contao).post({action:"toggleFieldset",id:t,table:n,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(r.addClass("collapsed"),(new Request.Contao).post({action:"toggleFieldset",id:t,table:n,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!1},toggleCheckboxGroup:function(e,t){e.blur();var n=$(t),r=$(e).getFirst("img");return n?(n.getStyle("display")!="block"?(n.setStyle("display","block"),r.src=r.src.replace("folPlus.gif","folMinus.gif"),(new Request.Contao).post({action:"toggleCheckboxGroup",id:t,state:1,REQUEST_TOKEN:REQUEST_TOKEN})):(n.setStyle("display","none"),r.src=r.src.replace("folMinus.gif","folPlus.gif"),(new Request.Contao).post({action:"toggleCheckboxGroup",id:t,state:0,REQUEST_TOKEN:REQUEST_TOKEN})),!0):!1},liveUpdate:function(e,t){var n=$(t);if(!n)return;(new Request.Contao({onRequest:$("lu_message").set("html",'<p class="tl_info">Connecting to the Live Update server</p>'),onSuccess:function(t,n){t?$("lu_message").set("html",n.content):$(e).submit()}})).post({action:"liveUpdate",id:n.value,REQUEST_TOKEN:REQUEST_TOKEN})},displayBox:function(e){var t=$("tl_ajaxBox"),n=$("tl_ajaxOverlay"),r=window.getScroll();n==null&&(n=(new Element("div",{id:"tl_ajaxOverlay"})).inject($(document.body),"bottom")),n.set({styles:{display:"block",top:r.y+"px"}}),t==null&&(t=(new Element("div",{id:"tl_ajaxBox"})).inject($(document.body),"bottom")),t.set({html:e,styles:{display:"block",top:r.y+18+"px"}})},hideBox:function(){var e=$("tl_ajaxBox"),t=$("tl_ajaxOverlay");t&&t.setStyle("display","none"),e&&e.setStyle("display","none")}},Backend={currentId:null,xMousePosition:0,yMousePosition:0,popupWindow:null,getMousePosition:function(e){Backend.xMousePosition=e.client.x,Backend.yMousePosition=e.client.y},openWindow:function(e,t,n){e.blur(),t=Browser.Engine.trident?t+40:t+17,n=Browser.Engine.trident?n+30:n+17,Backend.popupWindow=window.open(e.href,"","width="+t+",height="+n+",modal=yes,left=100,top=50,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no")},getScrollOffset:function(){document.cookie="BE_PAGE_OFFSET="+window.getScroll().y+"; path=/"},autoSubmit:function(e){Backend.getScrollOffset();var t=new Element("input",{type:"hidden",name:"SUBMIT_TYPE",value:"auto"}),n=$(e);t.inject(n,"bottom"),n.submit()},vScrollTo:function(e){window.addEvent("load",function(){window.scrollTo(null,parseInt(e))})},showTreeBody:function(e,t){e.blur(),$(t).setStyle("display",$(e).checked?"inline":"none")},hideTreeBody:function(){var e=$$("ul"),t=null;for(var n=0;n<e.length;n++)e[n].hasClass("mandatory")?$("ctrl_"+e[n].id).checked="checked":e[n].hasClass("tl_listing")&&(t=e[n].getFirst("li").getNext("li"))&&t.hasClass("parent")&&t.setStyle("display","none")},limitPreviewHeight:function(){var e=null,t=null,n="",r=0;$$("div.limit_height").each(function(i){e=i.getCoordinates(),r==0&&(r=i.className.replace(/[^0-9]*/,"").toInt());if(!$chk(r))return;i.setStyle("height",r);var s=CONTAO_SCRIPT_URL+"system/themes/"+CONTAO_THEME+"/images/";t=new Element("img",{"class":"limit_toggler",alt:"",width:20,height:24});if(e.height<r){t.src=s+"expand_.gif",t.inject(i,"after");return}t.src=s+"expand.gif",t.setStyle("cursor","pointer"),t.addEvent("click",function(){n=this.getPrevious("div").getStyle("height").toInt(),this.getPrevious("div").setStyle("height",n>r?r:""),this.src=this.src.indexOf("expand.gif")!=-1?s+"collapse.gif":s+"expand.gif"}),t.inject(i,"after")})},toggleCheckboxes:function(e,t){var n=$$("input"),r=e.checked?"checked":"";for(var i=0;i<n.length;i++){if(n[i].type.toLowerCase()!="checkbox")continue;if(t&&n[i].id.substr(0,t.length)!=t)continue;n[i].checked=r}},toggleCheckboxGroup:function(e,t){var n=$(e).className,r=$(e).checked?"checked":"";if(n=="tl_checkbox"){var i=$(t)?$$("#"+t+" .tl_checkbox"):$(e).getParent("fieldset").getElements(".tl_checkbox");i.each(function(e){e.checked=r})}else n=="tl_tree_checkbox"&&$$("#"+t+" .parent .tl_tree_checkbox").each(function(e){e.checked=r});Backend.getScrollOffset()},toggleCheckboxElements:function(e,t){var n=$(e).checked?"checked":"";$$("."+t).each(function(e){e.hasClass("tl_checkbox")&&(e.checked=n)}),Backend.getScrollOffset()},toggleWrap:function(e){var t=$(e),n=t.getProperty("wrap")=="off"?"soft":"off";t.setProperty("wrap",n);if(!Browser.Engine.trident){var r=t.value,i=t.clone();i.setProperty("wrap",n),i.setProperty("id",$(e).getProperty("id")),i.value=r,i.replaces(t)}},blink:function(){var e=null;$$("img.blink").each(function(t){e==null&&(e=t.hasClass("opacity")),e?t.removeClass("opacity"):t.addClass("opacity")})},addColorPicker:function(){},pickPage:function(e){var t=320,n=112;Backend.currentId=e,Backend.ppValue=$(e).value,Backend.getScrollOffset(),window.open($$("base")[0].href+"contao/page.php?value="+Backend.ppValue,"","width="+t+",height="+n+",modal=yes,left="+(Backend.xMousePosition?Backend.xMousePosition-t/2:200)+",top="+(Backend.yMousePosition?Backend.yMousePosition-n/2+80:100)+",location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no")},pickFile:function(e,t){var n=320,r=112;Backend.currentId=e,Backend.ppValue=$(e).value,Backend.getScrollOffset(),window.open($$("base")[0].href+"contao/file.php?value="+Backend.ppValue+"&filter="+t,"","width="+n+",height="+r+",modal=yes,left="+(Backend.xMousePosition?Backend.xMousePosition-n/2:200)+",top="+(Backend.yMousePosition?Backend.yMousePosition-r/2+80:100)+",location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no")},collapsePalettes:function(e){$$("fieldset.hide").each(function(e){e.addClass("collapsed")}),$$("label.error","label.mandatory").each(function(e){(fs=e.getParent("fieldset"))&&fs.removeClass("collapsed")})},addInteractiveHelp:function(){$$("p.tl_tip").each(function(e){if(e.retrieve("complete"))return;e.addEvent("mouseover",function(){e.timo=setTimeout(function(){var t=e.getTop(),n=$("tl_helpBox");n||(n=(new Element("div",{id:"tl_helpBox"})).inject($(document.body),"after")),n.set({html:e.get("html"),styles:{display:"block",top:t+18+"px"}})},1e3)}),e.addEvent("mouseout",function(){var t=$("tl_helpBox");t&&t.setStyle("display","none"),clearTimeout(e.timo)}),e.store("complete",!0)})},makeParentViewSortable:function(e){var t=new Sortables(e,{contstrain:!0,opacity:.6});t.active=!1,t.addEvent("start",function(){t.active=!0}),t.addEvent("complete",function(e){if(!t.active)return;if(e.getPrevious("li")){var n=e.get("id").replace(/li_/,""),r=e.getPrevious("li").get("id").replace(/li_/,""),i=window.location.search.replace(/id=[0-9]*/,"id="+n)+"&act=cut&mode=1&pid="+r,s=window.location.href.replace(/\?.*$/,"");(new Request({url:s+i})).get()}else if(e.getParent("ul")){var n=e.get("id").replace(/li_/,""),r=e.getParent("ul").get("id").replace(/ul_/,""),i=window.location.search.replace(/id=[0-9]*/,"id="+n)+"&act=cut&mode=2&pid="+r,s=window.location.href.replace(/\?.*$/,"");(new Request({url:s+i})).get()}})},listWizard:function(e,t,n){var r=$(n),i=$(e).getParent("li"),s=r.getChildren();Backend.getScrollOffset();switch(t){case"copy":var o=i.clone(!0).inject(i,"before");if(input=i.getFirst("input"))o.getFirst("input").value=input.value;break;case"up":(previous=i.getPrevious("li"))?i.inject(previous,"before"):i.inject(r,"bottom");break;case"down":(next=i.getNext("li"))?i.inject(next,"after"):i.inject(r.getFirst("li"),"before");break;case"delete":s.length>1&&i.destroy()}rows=r.getChildren();var u=1;for(var a=0;a<rows.length;a++)(input=rows[a].getFirst('input[type="text"]'))&&input.set("tabindex",u++)},tableWizard:function(e,t,n){var r=$(n),i=r.getElement("tbody"),s=i.getChildren(),o=$(e).getParent("td"),u=o.getParent("tr"),a=u.getChildren(),f=0;for(var l=0;l<a.length;l++){if(a[l]==o)break;f++}Backend.getScrollOffset();switch(t){case"rcopy":var c=new Element("tr");for(var l=0;l<a.length;l++){var h=a[l].clone(!0).inject(c,"bottom");if(textarea=a[l].getFirst("textarea"))h.getFirst("textarea").value=textarea.value}c.inject(u,"after");break;case"rup":var p=u.getPrevious("tr");p.getPrevious("tr")?u.inject(p,"before"):u.inject(i,"bottom");break;case"rdown":(h=u.getNext("tr"))?u.inject(h,"after"):u.inject(i.getFirst("tr").getNext("tr"),"before");break;case"rdelete":s.length>2&&u.destroy();break;case"ccopy":for(var l=0;l<s.length;l++){var d=s[l].getChildren()[f],h=d.clone(!0).inject(d,"after");if(textarea=d.getFirst("textarea"))h.getFirst("textarea").value=textarea.value}break;case"cmovel":if(f>0)for(var l=0;l<s.length;l++){var d=s[l].getChildren()[f];d.inject(d.getPrevious(),"before")}else for(var l=0;l<s.length;l++){var d=s[l].getChildren()[f];d.inject(s[l].getLast(),"before")}break;case"cmover":if(f<a.length-2)for(var l=0;l<s.length;l++){var d=s[l].getChildren()[f];d.inject(d.getNext(),"after")}else for(var l=0;l<s.length;l++){var d=s[l].getChildren()[f];d.inject(s[l].getFirst(),"before")}break;case"cdelete":if(a.length>2)for(var l=0;l<s.length;l++)s[l].getChildren()[f].destroy()}s=i.getChildren();var v=1;for(var l=0;l<s.length;l++){var m=s[l].getChildren();for(var g=0;g<m.length;g++)if(textarea=m[g].getFirst("textarea"))textarea.set("tabindex",v++),textarea.name=textarea.name.replace(/\[[0-9]+\][[0-9]+\]/ig,"["+(l-1)+"]["+g+"]")}Backend.tableWizardResize()},tableWizardResize:function(e){var t=Cookie.read("BE_CELL_SIZE");if(t==null&&e==null)return;if(e!=null){var t="";$$(".tl_tablewizard textarea").each(function(n){n.setStyle("width",(n.getStyle("width").toInt()*e).round().limit(142,284)),n.setStyle("height",(n.getStyle("height").toInt()*e).round().limit(66,132)),t==""&&(t=n.getStyle("width")+"|"+n.getStyle("height"))}),Cookie.write("BE_CELL_SIZE",t)}else if(t!=null){var n=t.split("|");$$(".tl_tablewizard textarea").each(function(e){e.setStyle("width",n[0]),e.setStyle("height",n[1])})}},moduleWizard:function(e,t,n){var r=$(n),i=r.getElement("tbody"),s=$(e).getParent("tr"),o=i.getChildren();Backend.getScrollOffset();switch(t){case"copy":var u=new Element("tr"),a=s.getChildren();for(var f=0;f<a.length;f++){var l=a[f].clone(!0).inject(u,"bottom");if(select=a[f].getFirst("select"))l.getFirst("select").value=select.value}u.inject(s,"after"),u.getElement(".chzn-container").destroy(),new Chosen(u.getElement("select.tl_select")),Stylect.convertSelects();break;case"up":(u=s.getPrevious("tr"))?s.inject(u,"before"):s.inject(i,"bottom");break;case"down":(u=s.getNext("tr"))?s.inject(u,"after"):s.inject(i,"top");break;case"delete":o.length>1&&s.destroy()}o=i.getChildren();var c=1;for(var f=0;f<o.length;f++){var a=o[f].getChildren();for(var h=0;h<a.length;h++)if(select=a[h].getFirst("select"))select.set("tabindex",c++),select.name=select.name.replace(/\[[0-9]+\]/ig,"["+f+"]")}},optionsWizard:function(e,t,n){var r=$(n),i=r.getElement("tbody"),s=$(e).getParent("tr"),o=i.getChildren();Backend.getScrollOffset();switch(t){case"copy":var u=new Element("tr"),a=s.getChildren();for(var f=0;f<a.length;f++){var l=a[f].clone(!0).inject(u,"bottom");if(input=a[f].getFirst("input"))l.getFirst("input").value=input.value,input.type=="checkbox"&&(l.getFirst("input").checked=input.checked?"checked":"")}u.inject(s,"after");break;case"up":(u=s.getPrevious("tr"))?s.inject(u,"before"):s.inject(i,"bottom");break;case"down":(u=s.getNext("tr"))?s.inject(u,"after"):s.inject(i,"top");break;case"delete":o.length>1&&s.destroy()}o=i.getChildren();var c=["value","label","default"],h=1;for(var f=0;f<o.length;f++){var a=o[f].getChildren();for(var p=0;p<a.length;p++)if(input=a[p].getFirst("input"))input.set("tabindex",h++),input.name=input.name.replace(/\[[0-9]+\]/g,"["+f+"]"),input.type=="checkbox"&&(input.id=input.name.replace(/\[[0-9]+\]/g,"").replace(/\[/g,"_").replace(/\]/g,"")+"_"+f,input.getNext("label").set("for",input.id))}},keyValueWizard:function(e,t,n){var r=$(n),i=r.getElement("tbody"),s=$(e).getParent("tr"),o=i.getChildren();Backend.getScrollOffset();switch(t){case"copy":var u=new Element("tr"),a=s.getChildren();for(var f=0;f<a.length;f++){var l=a[f].clone(!0).inject(u,"bottom");if(input=a[f].getFirst("input"))l.getFirst().value=input.value}u.inject(s,"after");break;case"up":(u=s.getPrevious("tr"))?s.inject(u,"before"):s.inject(i,"bottom");break;case"down":(u=s.getNext("tr"))?s.inject(u,"after"):s.inject(i,"top");break;case"delete":o.length>1&&s.destroy()}o=i.getChildren();var c=["key","value"],h=1;for(var f=0;f<o.length;f++){var a=o[f].getChildren();for(var p=0;p<a.length;p++)if(input=first=a[p].getFirst("input"))input.set("tabindex",h++),input.name=input.name.replace(/\[[0-9]+\]/g,"["+f+"]")}},checkboxWizard:function(e,t,n){var r=$(n),i=$(e).getParent("span");Backend.getScrollOffset();switch(t){case"up":(span=i.getPrevious("span"))&&!span.hasClass("fixed")?i.inject(span,"before"):i.inject(r,"bottom");break;case"down":(span=i.getNext("span"))?i.inject(span,"after"):(all=r.getFirst("span.fixed"))&&i.inject(all,"after")}},updateModuleLink:function(e){var t=e.getParent("td").getNext("td"),n=t.getElement("a.module_link");n.href=n.href.replace(/id=[0-9]+/,"id="+e.value),e.value>0?(t.getElement("a.module_link").setStyle("display","inline"),t.getElement("img.module_image").setStyle("display","none")):(t.getElement("a.module_link").setStyle("display","none"),t.getElement("img.module_image").setStyle("display","inline"))}};document.addEvent("mousedown",function(e){Backend.getMousePosition(e)}),window.addEvent("domready",function(){Elements.chosen!=undefined&&$$("select.tl_chosen").chosen(),Backend.hideTreeBody(),Backend.blink.periodical(600),$$("textarea.monospace").each(function(e){Backend.toggleWrap(e)}),Backend.collapsePalettes(),Backend.addInteractiveHelp(),Backend.addColorPicker()}),window.addEvent("load",function(){Backend.limitPreviewHeight()});var ContextMenu={initialize:function(){$$("a.edit-header").each(function(e){e.addClass("invisible")}),$$("a.contextmenu").each(function(e){var t=e.getNext("a");if(!t||!t.hasClass("edit-header"))return;e.addEvent("contextmenu",function(n){n.preventDefault(),ContextMenu.show(e,t,n)})}),$(document.body).addEvent("click",function(){ContextMenu.hide()})},show:function(e,t,n){ContextMenu.hide();var r=e.getFirst("img"),i=t.getFirst("img"),s=(new Element("div",{id:"contextmenu",html:'<a href="'+e.href+'" title="'+e.title+'">'+e.get("html")+" "+r.alt+'</a><a href="'+t.href+'" title="'+t.title+'">'+t.get("html")+" "+i.alt+"</a>",styles:{top:e.getPosition().y-6+"px"}})).inject($(document.body),"bottom");s.setStyle("left",e.getPosition().x-s.getSize().x/2)},hide:function(){$("contextmenu")!=null&&$("contextmenu").destroy()}};window.addEvent("domready",function(){ContextMenu.initialize()}),window.addEvent("structure",function(){ContextMenu.initialize()});var TinyCallback={getScrollOffset:function(e){tinymce.dom.Event.add(tinymce.isGecko?e.getDoc():e.getWin(),"focus",function(e){Backend.getScrollOffset()})}};