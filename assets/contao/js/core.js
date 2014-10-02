/* Contao Open Source CMS, (c) 2005-2014 Leo Feyer, LGPL license */
var AjaxRequest={toggleNavigation:function(a,d){a.blur();var b=$(d),c=$(a).getFirst("img");if(b){if(b.getStyle("display")=="none"){b.setStyle("display","inline");c.src=c.src.replace("modPlus.gif","modMinus.gif");$(a).store("tip:title",Contao.lang.collapse);new Request.Contao().post({action:"toggleNavigation",id:d,state:1,REQUEST_TOKEN:Contao.request_token})}else{b.setStyle("display","none");c.src=c.src.replace("modMinus.gif","modPlus.gif");$(a).store("tip:title",Contao.lang.expand);new Request.Contao().post({action:"toggleNavigation",id:d,state:0,REQUEST_TOKEN:Contao.request_token})}return false}new Request.Contao({evalScripts:true,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(f){var e=new Element("li",{id:d,"class":"tl_parent",html:f,styles:{display:"inline"}}).inject($(a).getParent("li"),"after");e.getElements("a").each(function(g){g.href=g.href.replace(/&ref=[a-f0-9]+/,"&ref="+Contao.referer_id)});$(a).store("tip:title",Contao.lang.collapse);c.src=c.src.replace("modPlus.gif","modMinus.gif");AjaxRequest.hideBox();window.fireEvent("ajax_change")}}).post({action:"loadNavigation",id:d,state:1,REQUEST_TOKEN:Contao.request_token});return false},toggleStructure:function(a,f,e,d){a.blur();var b=$(f),c=$(a).getFirst("img");if(b){if(b.getStyle("display")=="none"){b.setStyle("display","inline");c.src=c.src.replace("folPlus.gif","folMinus.gif");$(a).store("tip:title",Contao.lang.collapse);new Request.Contao({field:a}).post({action:"toggleStructure",id:f,state:1,REQUEST_TOKEN:Contao.request_token})}else{b.setStyle("display","none");c.src=c.src.replace("folMinus.gif","folPlus.gif");$(a).store("tip:title",Contao.lang.expand);new Request.Contao({field:a}).post({action:"toggleStructure",id:f,state:0,REQUEST_TOKEN:Contao.request_token})}return false}new Request.Contao({field:a,evalScripts:true,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(h){var g=new Element("li",{id:f,"class":"parent",styles:{display:"inline"}});var i=new Element("ul",{"class":"level_"+e,html:h}).inject(g,"bottom");if(d==5){g.inject($(a).getParent("li"),"after")}else{var l=false,k=$(a).getParent("li"),j;while(typeOf(k)=="element"&&(j=k.getNext("li"))){k=j;if(k.hasClass("tl_folder")){l=true;break}}if(l){g.inject(k,"before")}else{g.inject(k,"after")}}g.getElements("a").each(function(m){m.href=m.href.replace(/&ref=[a-f0-9]+/,"&ref="+Contao.referer_id)});$(a).store("tip:title",Contao.lang.collapse);c.src=c.src.replace("folPlus.gif","folMinus.gif");window.fireEvent("structure");AjaxRequest.hideBox();window.fireEvent("ajax_change")}}).post({action:"loadStructure",id:f,level:e,state:1,REQUEST_TOKEN:Contao.request_token});return false},toggleFileManager:function(b,g,d,f){b.blur();var c=$(g),e=$(b).getFirst("img"),a=$(b).getNext("img");if(c){if(c.getStyle("display")=="none"){c.setStyle("display","inline");e.src=e.src.replace("folPlus.gif","folMinus.gif");a.src=a.src.replace("folderC","folderO");$(b).store("tip:title",Contao.lang.collapse);new Request.Contao({field:b}).post({action:"toggleFileManager",id:g,state:1,REQUEST_TOKEN:Contao.request_token})}else{c.setStyle("display","none");e.src=e.src.replace("folMinus.gif","folPlus.gif");a.src=a.src.replace("folderO","folderC");$(b).store("tip:title",Contao.lang.expand);new Request.Contao({field:b}).post({action:"toggleFileManager",id:g,state:0,REQUEST_TOKEN:Contao.request_token})}return false}new Request.Contao({field:b,evalScripts:true,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(i){var h=new Element("li",{id:g,"class":"parent",styles:{display:"inline"}});var j=new Element("ul",{"class":"level_"+f,html:i}).inject(h,"bottom");h.inject($(b).getParent("li"),"after");h.getElements("a").each(function(k){k.href=k.href.replace(/&ref=[a-f0-9]+/,"&ref="+Contao.referer_id)});$(b).store("tip:title",Contao.lang.collapse);e.src=e.src.replace("folPlus.gif","folMinus.gif");a.src=a.src.replace("folderC.gif","folderO.gif");AjaxRequest.hideBox();window.fireEvent("ajax_change")}}).post({action:"loadFileManager",id:g,level:f,folder:d,state:1,REQUEST_TOKEN:Contao.request_token});return false},togglePagetree:function(b,g,e,a,f){b.blur();Backend.getScrollOffset();var c=$(g),d=$(b).getFirst("img");if(c){if(c.getStyle("display")=="none"){c.setStyle("display","inline");d.src=d.src.replace("folPlus.gif","folMinus.gif");$(b).store("tip:title",Contao.lang.collapse);new Request.Contao({field:b}).post({action:"togglePagetree",id:g,state:1,REQUEST_TOKEN:Contao.request_token})}else{c.setStyle("display","none");d.src=d.src.replace("folMinus.gif","folPlus.gif");$(b).store("tip:title",Contao.lang.expand);new Request.Contao({field:b}).post({action:"togglePagetree",id:g,state:0,REQUEST_TOKEN:Contao.request_token})}return false}new Request.Contao({field:b,evalScripts:true,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(i){var h=new Element("li",{id:g,"class":"parent",styles:{display:"inline"}});var j=new Element("ul",{"class":"level_"+f,html:i}).inject(h,"bottom");h.inject($(b).getParent("li"),"after");h.getElements("a").each(function(k){k.href=k.href.replace(/&ref=[a-f0-9]+/,"&ref="+Contao.referer_id)});$(b).store("tip:title",Contao.lang.collapse);d.src=d.src.replace("folPlus.gif","folMinus.gif");AjaxRequest.hideBox();window.fireEvent("ajax_change")}}).post({action:"loadPagetree",id:g,level:f,field:e,name:a,state:1,REQUEST_TOKEN:Contao.request_token});return false},toggleFiletree:function(b,h,d,f,a,g){b.blur();Backend.getScrollOffset();var c=$(h),e=$(b).getFirst("img");if(c){if(c.getStyle("display")=="none"){c.setStyle("display","inline");e.src=e.src.replace("folPlus.gif","folMinus.gif");$(b).store("tip:title",Contao.lang.collapse);new Request.Contao({field:b}).post({action:"toggleFiletree",id:h,state:1,REQUEST_TOKEN:Contao.request_token})}else{c.setStyle("display","none");e.src=e.src.replace("folMinus.gif","folPlus.gif");$(b).store("tip:title",Contao.lang.expand);new Request.Contao({field:b}).post({action:"toggleFiletree",id:h,state:0,REQUEST_TOKEN:Contao.request_token})}return false}new Request.Contao({field:b,evalScripts:true,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(j){var i=new Element("li",{id:h,"class":"parent",styles:{display:"inline"}});var k=new Element("ul",{"class":"level_"+g,html:j}).inject(i,"bottom");i.inject($(b).getParent("li"),"after");i.getElements("a").each(function(l){l.href=l.href.replace(/&ref=[a-f0-9]+/,"&ref="+Contao.referer_id)});$(b).store("tip:title",Contao.lang.collapse);e.src=e.src.replace("folPlus.gif","folMinus.gif");AjaxRequest.hideBox();window.fireEvent("ajax_change")}}).post({action:"loadFiletree",id:h,folder:d,level:g,field:f,name:a,state:1,REQUEST_TOKEN:Contao.request_token});return false},toggleSubpalette:function(a,d,c){a.blur();var b=$(d);if(b){if(!a.value){a.value=1;a.checked="checked";b.setStyle("display","block");new Request.Contao({field:a}).post({action:"toggleSubpalette",id:d,field:c,state:1,REQUEST_TOKEN:Contao.request_token})}else{a.value="";a.checked="";b.setStyle("display","none");new Request.Contao({field:a}).post({action:"toggleSubpalette",id:d,field:c,state:0,REQUEST_TOKEN:Contao.request_token})}return}new Request.Contao({field:a,evalScripts:false,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(e,f){var g=new Element("div",{id:d,html:e,styles:{display:"block"}}).inject($(a).getParent("div").getParent("div"),"after");if(f.javascript){document.write=function(i){var h="";i.replace(/<script src="([^"]+)"/i,function(k,j){h=j});h&&Asset.javascript(h,{onLoad:function(){Browser.exec(f.javascript)}})};Browser.exec(f.javascript)}a.value=1;a.checked="checked";g.getElements("a").each(function(h){h.href=h.href.replace(/&ref=[a-f0-9]+/,"&ref="+Contao.referer_id)});AjaxRequest.hideBox();window.fireEvent("subpalette");window.fireEvent("ajax_change")}}).post({action:"toggleSubpalette",id:d,field:c,load:1,state:1,REQUEST_TOKEN:Contao.request_token})},toggleVisibility:function(c,b,i){c.blur();var f=null,e=$(c).getFirst("img"),d=(e.src.indexOf("invisible")!=-1),a=c.getParent("div"),g;if(a.hasClass("tl_right")){f=a.getPrevious("div").getElement("img")}else{if(a.hasClass("tl_listing_container")){f=c.getParent("td").getPrevious("td").getFirst("div.list_icon");if(f==null){f=c.getParent("td").getPrevious("td").getElement("div.cte_type")}if(f==null){f=c.getParent("tr").getFirst("td").getElement("div.list_icon_new")}}else{if((g=a.getNext("div"))&&g.hasClass("cte_type")){f=g}}}if(f!=null){if(f.nodeName.toLowerCase()=="img"){if(f.getParent("ul.tl_listing").hasClass("tl_tree_xtnd")){if(d){f.src=f.src.replace(/_\.(gif|png|jpe?g)/,".$1")}else{f.src=f.src.replace(/\.(gif|png|jpe?g)/,"_.$1")}}else{if(f.src.match(/folPlus|folMinus/)){if(f.getParent("a").getNext("a")){f=f.getParent("a").getNext("a").getFirst("img")}else{f=new Element("img")}}var h;if(d){h=f.src.replace(/.*_([0-9])\.(gif|png|jpe?g)/,"$1");f.src=f.src.replace(/_[0-9]\.(gif|png|jpe?g)/,((h.toInt()==1)?"":"_"+(h.toInt()-1))+".$1")}else{h=f.src.replace(/.*_([0-9])\.(gif|png|jpe?g)/,"$1");f.src=f.src.replace(/(_[0-9])?\.(gif|png|jpe?g)/,((h==f.src)?"_1":"_"+(h.toInt()+1))+".$2")}}}else{if(f.hasClass("cte_type")){if(d){f.addClass("published");f.removeClass("unpublished")}else{f.addClass("unpublished");f.removeClass("published")}}else{if(d){f.setStyle("background-image",f.getStyle("background-image").replace(/_\.(gif|png|jpe?g)/,".$1"))}else{f.setStyle("background-image",f.getStyle("background-image").replace(/\.(gif|png|jpe?g)/,"_.$1"))}}}}if(i=="tl_style"){a.getParent("div").getElement("pre").toggleClass("disabled")}if(d){e.src=e.src.replace("invisible.gif","visible.gif");new Request.Contao({url:window.location.href,followRedirects:false}).get({tid:b,state:1})}else{e.src=e.src.replace("visible.gif","invisible.gif");new Request.Contao({url:window.location.href,followRedirects:false}).get({tid:b,state:0})}return false},toggleFeatured:function(a,d){a.blur();var b=$(a).getFirst("img"),c=(b.src.indexOf("featured_")==-1);if(!c){b.src=b.src.replace("featured_.gif","featured.gif");new Request.Contao().post({action:"toggleFeatured",id:d,state:1,REQUEST_TOKEN:Contao.request_token})}else{b.src=b.src.replace("featured.gif","featured_.gif");new Request.Contao().post({action:"toggleFeatured",id:d,state:0,REQUEST_TOKEN:Contao.request_token})}return false},toggleFieldset:function(b,d,c){b.blur();var a=$("pal_"+d);if(a.hasClass("collapsed")){a.removeClass("collapsed");new Request.Contao().post({action:"toggleFieldset",id:d,table:c,state:1,REQUEST_TOKEN:Contao.request_token})}else{a.addClass("collapsed");new Request.Contao().post({action:"toggleFieldset",id:d,table:c,state:0,REQUEST_TOKEN:Contao.request_token})}return false},toggleCheckboxGroup:function(a,d){a.blur();var b=$(d),c=$(a).getFirst("img");if(b){if(b.getStyle("display")!="block"){b.setStyle("display","block");c.src=c.src.replace("folPlus.gif","folMinus.gif");new Request.Contao().post({action:"toggleCheckboxGroup",id:d,state:1,REQUEST_TOKEN:Contao.request_token})}else{b.setStyle("display","none");c.src=c.src.replace("folMinus.gif","folPlus.gif");new Request.Contao().post({action:"toggleCheckboxGroup",id:d,state:0,REQUEST_TOKEN:Contao.request_token})}return true}return false},liveUpdate:function(b,c){var a=$(c);if(!a){return}new Request.Contao({onRequest:$("lu_message").set("html",'<p class="tl_info">Connecting to the Live Update server</p>'),onSuccess:function(d,e){if(d){$("lu_message").set("html",e.content)}else{$(b).submit()}}}).post({action:"liveUpdate",id:a.value,REQUEST_TOKEN:Contao.request_token})},displayBox:function(d){var c=$("tl_ajaxBox"),b=$("tl_ajaxOverlay"),a=window.getScroll();if(b==null){b=new Element("div",{id:"tl_ajaxOverlay"}).inject($(document.body),"bottom")}b.set({styles:{display:"block",top:a.y+"px"}});if(c==null){c=new Element("div",{id:"tl_ajaxBox"}).inject($(document.body),"bottom")}c.set({html:d,styles:{display:"block",top:(a.y+100)+"px"}})},hideBox:function(){var b=$("tl_ajaxBox"),a=$("tl_ajaxOverlay");if(a){a.setStyle("display","none")}if(b){b.setStyle("display","none")}}};var Backend={currentId:null,xMousePosition:0,yMousePosition:0,popupWindow:null,getMousePosition:function(a){Backend.xMousePosition=a.client.x;Backend.yMousePosition=a.client.y},openWindow:function(c,b,a){c.blur();b=Browser.ie?(b+40):(b+17);a=Browser.ie?(a+30):(a+17);Backend.popupWindow=window.open(c.href,"","width="+b+",height="+a+",modal=yes,left=100,top=50,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no")},openModalWindow:function(a,c,b){new SimpleModal({width:a,hideFooter:true,draggable:false,overlayOpacity:0.5,onShow:function(){document.body.setStyle("overflow","hidden")},onHide:function(){document.body.setStyle("overflow","auto")}}).show({title:c,contents:b})},openModalImage:function(a){var b=a||{};var c=new SimpleModal({width:b.width,hideFooter:true,draggable:false,overlayOpacity:0.5,onShow:function(){document.body.setStyle("overflow","hidden")},onHide:function(){document.body.setStyle("overflow","auto")}});c.show({title:b.title,contents:'<img src="'+b.url+'" alt="">'})},openModalIframe:function(b){var c=b||{};var a=(window.getSize().y-180).toInt();if(!c.height||c.height>a){c.height=a}var d=new SimpleModal({width:c.width,hideFooter:true,draggable:false,overlayOpacity:0.5,onShow:function(){document.body.setStyle("overflow","hidden")},onHide:function(){document.body.setStyle("overflow","auto")}});d.show({title:c.title,contents:'<iframe src="'+c.url+'" width="100%" height="'+c.height+'" frameborder="0"></iframe>'})},openModalSelector:function(b){var c=b||{},a=(window.getSize().y-180).toInt();if(!c.height||c.height>a){c.height=a}var d=new SimpleModal({width:c.width,btn_ok:Contao.lang.close,draggable:false,overlayOpacity:0.5,onShow:function(){document.body.setStyle("overflow","hidden")},onHide:function(){document.body.setStyle("overflow","auto")}});d.addButton(Contao.lang.close,"btn",function(){this.hide()});d.addButton(Contao.lang.apply,"btn primary",function(){var h=window.frames["simple-modal-iframe"],j=[],g,f;if(h===undefined){alert("Could not find the SimpleModal frame");return}if(h.document.location.href.indexOf("contao/main.php")!=-1){alert(Contao.lang.picker);return}g=h.document.getElementById("tl_listing").getElementsByTagName("input");for(f=0;f<g.length;f++){if(!g[f].checked||g[f].id.match(/^check_all_/)){continue}if(!g[f].id.match(/^reset_/)){j.push(g[f].get("value"))}}if(c.tag){$(c.tag).value=j.join(",");if(c.url.match(/page\.php/)){$(c.tag).value="{{link_url::"+$(c.tag).value+"}}"}c.self.set("href",c.self.get("href").replace(/&value=[^&]*/,"&value="+j.join(",")))}else{$("ctrl_"+c.id).value=j.join("\t");var e=(c.url.indexOf("contao/page.php")!=-1)?"reloadPagetree":"reloadFiletree";new Request.Contao({field:$("ctrl_"+c.id),evalScripts:false,onRequest:AjaxRequest.displayBox(Contao.lang.loading+" …"),onSuccess:function(i,k){$("ctrl_"+c.id).getParent("div").set("html",k.content);k.javascript&&Browser.exec(k.javascript);AjaxRequest.hideBox();window.fireEvent("ajax_change")}}).post({action:e,name:c.id,value:$("ctrl_"+c.id).value,REQUEST_TOKEN:Contao.request_token})}this.hide()});d.show({title:c.title,contents:'<iframe src="'+c.url+'" name="simple-modal-iframe" width="100%" height="'+c.height+'" frameborder="0"></iframe>',model:"modal"})},openModalBrowser:function(e,a,c,g){var b="file.php",d=(c=="file"?"&amp;switch=1":""),f=(a.indexOf("{{link_url::")!=-1);if(c=="file"&&(a==""||f)){b="page.php"}if(f){a=a.replace(/^\{\{link_url::([0-9]+)\}\}$/,"$1")}var h=new SimpleModal({width:768,btn_ok:Contao.lang.close,draggable:false,overlayOpacity:0.5,onShow:function(){document.body.setStyle("overflow","hidden")},onHide:function(){document.body.setStyle("overflow","auto")}});h.addButton(Contao.lang.close,"btn",function(){this.hide()});h.addButton(Contao.lang.apply,"btn primary",function(){var l=window.frames["simple-modal-iframe"],m,k,j;if(l===undefined){alert("Could not find the SimpleModal frame");return}k=l.document.getElementById("tl_listing").getElementsByTagName("input");for(j=0;j<k.length;j++){if(k[j].checked&&!k[j].id.match(/^reset_/)){m=k[j].get("value");break}}if(!isNaN(m)){m="{{link_url::"+m+"}}"}g.document.getElementById(e).value=m;this.hide()});h.show({title:g.document.getElement("div.mce-title").get("text"),contents:'<iframe src="contao/'+b+"?table=tl_content&amp;field=singleSRC&amp;value="+a+d+'" name="simple-modal-iframe" width="100%" height="'+(window.getSize().y-180).toInt()+'" frameborder="0"></iframe>',model:"modal"})},getScrollOffset:function(){document.cookie="BE_PAGE_OFFSET="+window.getScroll().y+"; path="+(Contao.path||"/")},autoSubmit:function(a){Backend.getScrollOffset();var c=new Element("input",{type:"hidden",name:"SUBMIT_TYPE",value:"auto"});var b=$(a)||a;c.inject(b,"bottom");b.submit()},vScrollTo:function(a){window.addEvent("load",function(){window.scrollTo(null,parseInt(a))})},limitPreviewHeight:function(){var b=null,d=null,c="",a=0;$$("div.limit_height").each(function(f){b=f.getCoordinates();if(a===0){a=f.className.replace(/[^0-9]*/,"").toInt()}if(!a){return}f.setStyle("height",a);var e=Contao.script_url+"system/themes/"+Contao.theme+"/images/";d=new Element("img",{"class":"limit_toggler",alt:"",title:Contao.lang.expand,width:20,height:24});new Tips.Contao(d,{offset:{x:0,y:30}});if(b.height<a){d.src=e+"expand_.gif";d.inject(f,"after");return}d.src=e+"expand.gif";d.setStyle("cursor","pointer");d.addEvent("click",function(){c=this.getPrevious("div").getStyle("height").toInt();this.getPrevious("div").setStyle("height",((c>a)?a:""));if(this.src.indexOf("expand.gif")!=-1){this.src=e+"collapse.gif";this.store("tip:title",Contao.lang.collapse)}else{this.src=e+"expand.gif";this.store("tip:title",Contao.lang.expand)}});d.inject(f,"after")})},toggleCheckboxes:function(d,e){var b=$$("input"),a=$(d).checked?"checked":"";for(var c=0;c<b.length;c++){if(b[c].type.toLowerCase()!="checkbox"){continue}if(e&&b[c].id.substr(0,e.length)!=e){continue}b[c].checked=a}},toggleCheckboxGroup:function(c,e){var b=$(c).className,a=$(c).checked?"checked":"";if(b=="tl_checkbox"){var d=$(e)?$$("#"+e+" .tl_checkbox"):$(c).getParent("fieldset").getElements(".tl_checkbox");d.each(function(f){f.checked=a})}else{if(b=="tl_tree_checkbox"){$$("#"+e+" .parent .tl_tree_checkbox").each(function(f){f.checked=a})}}Backend.getScrollOffset()},toggleCheckboxElements:function(c,b){var a=$(c).checked?"checked":"";$$("."+b).each(function(d){if(d.hasClass("tl_checkbox")){d.checked=a}});Backend.getScrollOffset()},toggleWrap:function(c){var b=$(c),a=(b.getProperty("wrap")=="off")?"soft":"off";b.setProperty("wrap",a)},toggleUnchanged:function(){$$("#result-list .tl_confirm").each(function(a){a.toggleClass("hidden")})},blink:function(){},addColorPicker:function(){return true},pickPage:function(c){var b=320,a=112;Backend.currentId=c;Backend.ppValue=$(c).value;Backend.getScrollOffset();window.open($$("base")[0].href+"contao/page.php?value="+Backend.ppValue,"","width="+b+",height="+a+",modal=yes,left="+(Backend.xMousePosition?(Backend.xMousePosition-(b/2)):200)+",top="+(Backend.yMousePosition?(Backend.yMousePosition-(a/2)+80):100)+",location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no")},pickFile:function(d,c){var b=320,a=112;Backend.currentId=d;Backend.ppValue=$(d).value;Backend.getScrollOffset();window.open($$("base")[0].href+"contao/file.php?value="+Backend.ppValue+"&filter="+c,"","width="+b+",height="+a+",modal=yes,left="+(Backend.xMousePosition?(Backend.xMousePosition-(b/2)):200)+",top="+(Backend.yMousePosition?(Backend.yMousePosition-(a/2)+80):100)+",location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no")},collapsePalettes:function(){$$("fieldset.hide").each(function(a){a.addClass("collapsed")});$$("label.error, label.mandatory").each(function(b){var a=b.getParent("fieldset");a&&a.removeClass("collapsed")})},addInteractiveHelp:function(){new Tips.Contao("p.tl_tip",{offset:{x:9,y:21},text:function(a){return a.get("html")}});["a[title]","input[title]"].each(function(a){new Tips.Contao($$(a).filter(function(b){return b.title!=""}),{offset:{x:0,y:26}})});$$("img[title]").filter(function(a){return a.title!=""}).each(function(a){new Tips.Contao(a,{offset:{x:0,y:((a.get("class")=="gimage")?60:30)}})})},makeParentViewSortable:function(a){var c=new Scroller(document.getElement("body"),{onChange:function(d,e){this.element.scrollTo(this.element.getScroll().x,e)}});var b=new Sortables(a,{contstrain:true,opacity:0.6,onStart:function(){c.start()},onComplete:function(){c.stop()},onSort:function(e){var h=e.getFirst("div"),f,d,g;if(!h){return}if(h.hasClass("wrapper_start")){if((f=e.getPrevious("li"))&&(g=f.getFirst("div"))){g.removeClass("indent")}if((d=e.getNext("li"))&&(g=d.getFirst("div"))){g.addClass("indent")}}else{if(h.hasClass("wrapper_stop")){if((f=e.getPrevious("li"))&&(g=f.getFirst("div"))){g.addClass("indent")}if((d=e.getNext("li"))&&(g=d.getFirst("div"))){g.removeClass("indent")}}else{if(h.hasClass("indent")){if((f=e.getPrevious("li"))&&(g=f.getFirst("div"))&&g.hasClass("wrapper_stop")){h.removeClass("indent")}else{if((d=e.getNext("li"))&&(g=d.getFirst("div"))&&g.hasClass("wrapper_start")){h.removeClass("indent")}}}else{if((f=e.getPrevious("li"))&&(g=f.getFirst("div"))&&g.hasClass("wrapper_start")){h.addClass("indent")}else{if((d=e.getNext("li"))&&(g=d.getFirst("div"))&&g.hasClass("wrapper_stop")){h.addClass("indent")}}}}}},handle:".drag-handle"});b.active=false;b.addEvent("start",function(){b.active=true});b.addEvent("complete",function(f){if(!b.active){return}var h,e,g,d;if(f.getPrevious("li")){h=f.get("id").replace(/li_/,"");e=f.getPrevious("li").get("id").replace(/li_/,"");g=window.location.search.replace(/id=[0-9]*/,"id="+h)+"&act=cut&mode=1&pid="+e;d=window.location.href.replace(/\?.*$/,"");new Request.Contao({url:d+g,followRedirects:false}).get()}else{if(f.getParent("ul")){h=f.get("id").replace(/li_/,"");e=f.getParent("ul").get("id").replace(/ul_/,"");g=window.location.search.replace(/id=[0-9]*/,"id="+h)+"&act=cut&mode=2&pid="+e;d=window.location.href.replace(/\?.*$/,"");new Request.Contao({url:d+g,followRedirects:false}).get()}}})},makeMultiSrcSortable:function(c,a){var b=new Sortables($(c),{contstrain:true,opacity:0.6}).addEvent("complete",function(){var f=[],d=$(c).getChildren("li"),e;for(e=0;e<d.length;e++){f.push(d[e].get("data-id"))}$(a).value=f.join(",")});b.fireEvent("complete")},makeWizardsSortable:function(){$$(".tl_listwizard").each(function(a){new Sortables(a,{contstrain:true,opacity:0.6,handle:".drag-handle"})});$$(".tl_tablewizard").each(function(b){var a=b.getElement(".sortable");new Sortables(a,{contstrain:true,opacity:0.6,handle:".drag-handle",onComplete:function(){Backend.tableWizardResort(a)}})});$$(".tl_modulewizard").each(function(a){new Sortables(a.getElement(".sortable"),{contstrain:true,opacity:0.6,handle:".drag-handle"})});$$(".tl_optionwizard").each(function(a){new Sortables(a.getElement(".sortable"),{contstrain:true,opacity:0.6,handle:".drag-handle"})});$$(".tl_checkbox_wizard").each(function(b){var a=b.getElement(".sortable");if(a.hasClass("sortable-done")){return}new Sortables(a,{contstrain:true,opacity:0.6,handle:".drag-handle"});a.addClass("sortable-done")})},listWizard:function(b,c,a){var h=$(a),m=$(b).getParent("li"),j=h.getChildren(),d=h.get("data-tabindex"),l,g,f,n,e;Backend.getScrollOffset();switch(c){case"copy":var k=m.clone(true).inject(m,"before");if(l=m.getFirst("input")){k.getFirst("input").value=l.value}break;case"up":if(g=m.getPrevious("li")){m.inject(g,"before")}else{m.inject(h,"bottom")}break;case"down":if(f=m.getNext("li")){m.inject(f,"after")}else{m.inject(h.getFirst("li"),"before")}break;case"delete":if(j.length>1){m.destroy()}else{m.getFirst("input").set("value","")}break}n=h.getChildren();for(e=0;e<n.length;e++){if(l=n[e].getFirst('input[type="text"]')){l.set("tabindex",d++)}}new Sortables(h,{contstrain:true,opacity:0.6,handle:".drag-handle"})},tableWizard:function(c,d,b){var q=$(b),g=q.getElement("tbody"),r=g.getChildren(),m=$(c).getParent("td"),a=m.getParent("tr"),e=q.getElement("thead").getFirst("tr"),o=a.getChildren(),k=0,p,j,h,l,f;for(f=0;f<o.length;f++){if(o[f]==m){break}k++}Backend.getScrollOffset();switch(d){case"rcopy":var n=new Element("tr");for(f=0;f<o.length;f++){h=o[f].clone(true).inject(n,"bottom");if(p=o[f].getFirst("textarea")){h.getFirst("textarea").value=p.value}}n.inject(a,"after");break;case"rup":if(j=a.getPrevious("tr")){a.inject(j,"before")}else{a.inject(g,"bottom")}break;case"rdown":if(h=a.getNext("tr")){a.inject(h,"after")}else{a.inject(g,"top")}break;case"rdelete":if(r.length>1){a.destroy()}else{a.getElements("textarea").set("text","")}break;case"ccopy":for(f=0;f<r.length;f++){l=r[f].getChildren()[k];h=l.clone(true).inject(l,"after");if(p=l.getFirst("textarea")){h.getFirst("textarea").value=p.value}}e.getFirst("td").clone(true).inject(e.getLast("td"),"before");break;case"cmovel":if(k>0){for(f=0;f<r.length;f++){l=r[f].getChildren()[k];l.inject(l.getPrevious(),"before")}}else{for(f=0;f<r.length;f++){l=r[f].getChildren()[k];l.inject(r[f].getLast(),"before")}}break;case"cmover":if(k<(o.length-2)){for(f=0;f<r.length;f++){l=r[f].getChildren()[k];l.inject(l.getNext(),"after")}}else{for(f=0;f<r.length;f++){l=r[f].getChildren()[k];l.inject(r[f].getFirst(),"before")}}break;case"cdelete":if(o.length>2){for(f=0;f<r.length;f++){r[f].getChildren()[k].destroy()}e.getFirst("td").destroy()}else{for(f=0;f<r.length;f++){r[f].getElements("textarea").set("text","")}}break}Backend.tableWizardResort(g);new Sortables(g,{contstrain:true,opacity:0.6,handle:".drag-handle",onComplete:function(){Backend.tableWizardResort(g)}});Backend.tableWizardResize()},tableWizardResort:function(c){var g=c.getChildren(),e=c.get("data-tabindex"),a,f,d,b;for(d=0;d<g.length;d++){f=g[d].getChildren();for(b=0;b<f.length;b++){if(a=f[b].getFirst("textarea")){a.set("tabindex",e++);a.name=a.name.replace(/\[[0-9]+\][[0-9]+\]/g,"["+d+"]["+b+"]")}}}},tableWizardResize:function(b){var a=Cookie.read("BE_CELL_SIZE");if(a==null&&b==null){return}if(b!=null){a="";$$(".tl_tablewizard textarea").each(function(d){d.setStyle("width",(d.getStyle("width").toInt()*b).round().limit(142,284));d.setStyle("height",(d.getStyle("height").toInt()*b).round().limit(66,132));if(a==""){a=d.getStyle("width")+"|"+d.getStyle("height")}});Cookie.write("BE_CELL_SIZE",a,{path:Contao.path})}else{if(a!=null){var c=a.split("|");$$(".tl_tablewizard textarea").each(function(d){d.setStyle("width",c[0]);d.setStyle("height",c[1])})}}},moduleWizard:function(c,d,b){var r=$(b),h=r.getElement("tbody"),q=$(c).getParent("tr"),s=h.getChildren(),f=h.get("data-tabindex"),p,o,l,n,g,e;Backend.getScrollOffset();switch(d){case"copy":var m=new Element("tr");l=q.getChildren();for(g=0;g<l.length;g++){var k=l[g].clone(true).inject(m,"bottom");if(o=l[g].getFirst("select")){k.getFirst("select").value=o.value}}m.inject(q,"after");m.getElement(".chzn-container").destroy();m.getElement(".tl_select_column").destroy();new Chosen(m.getElement("select.tl_select"));Stylect.convertSelects();Backend.convertEnableModules();break;case"up":if(m=q.getPrevious("tr")){q.inject(m,"before")}else{q.inject(h,"bottom")}break;case"down":if(m=q.getNext("tr")){q.inject(m,"after")}else{q.inject(h,"top")}break;case"delete":if(s.length>1){q.destroy()}break}s=h.getChildren();for(g=0;g<s.length;g++){l=s[g].getChildren();for(e=0;e<l.length;e++){if(n=l[e].getFirst("a.chzn-single")){n.set("tabindex",f++)}if(o=l[e].getFirst("select")){o.name=o.name.replace(/\[[0-9]+\]/g,"["+g+"]")}if(p=l[e].getFirst('input[type="checkbox"]')){p.set("tabindex",f++);p.name=p.name.replace(/\[[0-9]+\]/g,"["+g+"]")}}}new Sortables(h,{contstrain:true,opacity:0.6,handle:".drag-handle"})},optionsWizard:function(b,c,a){var o=$(a),g=o.getElement("tbody"),n=$(b).getParent("tr"),p=g.getChildren(),e=g.get("data-tabindex"),m,k,f,d;Backend.getScrollOffset();switch(c){case"copy":var l=new Element("tr");k=n.getChildren();for(f=0;f<k.length;f++){var h=k[f].clone(true).inject(l,"bottom");if(m=k[f].getFirst("input")){h.getFirst("input").value=m.value;if(m.type=="checkbox"){h.getFirst("input").checked=m.checked?"checked":""}}}l.inject(n,"after");break;case"up":if(l=n.getPrevious("tr")){n.inject(l,"before")}else{n.inject(g,"bottom")}break;case"down":if(l=n.getNext("tr")){n.inject(l,"after")}else{n.inject(g,"top")}break;case"delete":if(p.length>1){n.destroy()}break}p=g.getChildren();for(f=0;f<p.length;f++){k=p[f].getChildren();for(d=0;d<k.length;d++){if(m=k[d].getFirst("input")){m.set("tabindex",e++);m.name=m.name.replace(/\[[0-9]+\]/g,"["+f+"]");if(m.type=="checkbox"){m.id=m.name.replace(/\[[0-9]+\]/g,"").replace(/\[/g,"_").replace(/\]/g,"")+"_"+f;m.getNext("label").set("for",m.id)}}}}new Sortables(g,{contstrain:true,opacity:0.6,handle:".drag-handle"})},keyValueWizard:function(b,c,a){var o=$(a),g=o.getElement("tbody"),n=$(b).getParent("tr"),p=g.getChildren(),e=g.get("data-tabindex"),m,k,f,d;Backend.getScrollOffset();switch(c){case"copy":var l=new Element("tr");k=n.getChildren();for(f=0;f<k.length;f++){var h=k[f].clone(true).inject(l,"bottom");if(m=k[f].getFirst("input")){h.getFirst().value=m.value}}l.inject(n,"after");break;case"up":if(l=n.getPrevious("tr")){n.inject(l,"before")}else{n.inject(g,"bottom")}break;case"down":if(l=n.getNext("tr")){n.inject(l,"after")}else{n.inject(g,"top")}break;case"delete":if(p.length>1){n.destroy()}break}p=g.getChildren();for(f=0;f<p.length;f++){k=p[f].getChildren();for(d=0;d<k.length;d++){if(m=k[d].getFirst("input")){m.set("tabindex",e++);m.name=m.name.replace(/\[[0-9]+\]/g,"["+f+"]")}}}new Sortables(g,{contstrain:true,opacity:0.6,handle:".drag-handle"})},checkboxWizard:function(d,e,f){var a=$(f).getElement(".sortable"),c=$(d).getParent("span"),b;Backend.getScrollOffset();switch(e){case"up":if((b=c.getPrevious("span"))){c.inject(b,"before")}else{c.inject(a,"bottom")}break;case"down":if(b=c.getNext("span")){c.inject(b,"after")}else{c.inject(a,"top")}break}},metaWizard:function(f,d){var c=f.getParent("div").getElement("select");if(c.value==""){return}var a=$(d).getLast("li").clone(),e=a.getElement("span"),b=e.getElement("img");a.setProperty("data-language",c.value);e.set("text",c.options[c.selectedIndex].text+" ");b.inject(e,"bottom");a.getElements("input").each(function(h){h.value="";h.name=h.name.replace(/\[[a-z]{2}(_[A-Z]{2})?\]/,"["+c.value+"]");var j=h.getPrevious("label"),g=parseInt(j.get("for").replace(/ctrl_[^_]+_/,""));j.set("for",j.get("for").replace(g,g+1));h.id=j.get("for")});a.className=(a.className=="even")?"odd":"even";a.inject($(d),"bottom");f.getParent("div").getElement('input[type="button"]').setProperty("disabled",true);c.options[c.selectedIndex].setProperty("disabled",true);c.value="";c.fireEvent("liszt:updated")},metaDelete:function(c){var a=c.getParent("li"),b=c.getParent("div").getElement("select");if(a.getPrevious()===null&&a.getNext()===null){a.getElements("input").each(function(d){d.value=""})}else{b.getElement("option[value="+a.getProperty("data-language")+"]").removeProperty("disabled");a.destroy();b.fireEvent("liszt:updated")}$$("div.tip-wrap").destroy()},toggleAddLanguageButton:function(a){var b=a.getParent("div").getElement('input[type="button"]');if(a.value!=""){b.removeProperty("disabled")}else{b.setProperty("disabled",true)}},updateModuleLink:function(c){var d=c.getParent("tr").getLast("td"),b=d.getElement("a.module_link");b.href=b.href.replace(/id=[0-9]+/,"id="+c.value);if(c.value>0){d.getElement("a.module_link").setStyle("display","inline");d.getElement("img.module_image").setStyle("display","none")}else{d.getElement("a.module_link").setStyle("display","none");d.getElement("img.module_image").setStyle("display","inline")}},convertEnableModules:function(){$$("img.mw_enable").filter(function(a){return !a.hasEvent("click")}).each(function(a){a.addEvent("click",function(){Backend.getScrollOffset();var b=a.getNext("input");if(b.checked){b.checked="";a.src=a.src.replace("visible.gif","invisible.gif")}else{b.checked="checked";a.src=a.src.replace("invisible.gif","visible.gif")}})})},imageSize:function(e){var c=$(e),b=c.getParent().getChildren("input")[0],a=c.getParent().getChildren("input")[1],d=function(){if(c.get("value").toInt().toString()===c.get("value")){b.readOnly=true;a.readOnly=true;var f=$(c.getSelected()[0]).get("text");f=f.split("(").length>1?f.split("(").getLast().split(")")[0].split("x"):["",""];b.set("value","").set("placeholder",f[0]*1||"");a.set("value","").set("placeholder",f[1]*1||"")}else{b.set("placeholder","");a.set("placeholder","");b.readOnly=false;a.readOnly=false}};d();c.addEvent("change",d);c.addEvent("keyup",d)},editPreviewWizard:function(d){d=$(d);var j=d.getElement("img");var l={};var o;var e=false;var i;var b=d.get("data-original-width");var h=d.get("data-original-height");var m=function(){return j.getComputedSize().width/b};var c=function(){var q=m();var p=j.getComputedSize();o.setStyles({top:p.computedTop+(l.y.get("value")*q).round()+"px",left:p.computedLeft+(l.x.get("value")*q).round()+"px",width:(l.width.get("value")*q).round()+"px",height:(l.height.get("value")*q).round()+"px"});if(!l.width.get("value").toInt()||!l.height.get("value").toInt()){o.setStyle("display","none")}else{o.setStyle("display","")}};var g=function(){var s=m();var q=o.getStyles("top","left","width","height");var r=j.getComputedSize();var p={x:Math.max(0,Math.min(b,(q.left.toFloat()-r.computedLeft)/s)).round(),y:Math.max(0,Math.min(h,(q.top.toFloat()-r.computedTop)/s)).round()};p.width=Math.min(b-p.x,q.width.toFloat()/s).round();p.height=Math.min(h-p.y,q.height.toFloat()/s).round();if(!p.width||!p.height){p.x=p.y=p.width=p.height="";o.setStyle("display","none")}else{o.setStyle("display","")}Object.each(p,function(u,t){l[t].set("value",u)})};var a=function(p){p.preventDefault();if(e){return}e=true;i={x:p.page.x-d.getPosition().x-j.getComputedSize().computedLeft,y:p.page.y-d.getPosition().y-j.getComputedSize().computedTop};f(p)};var f=function(q){if(!e){return}q.preventDefault();var r=j.getComputedSize();var p={x:[Math.max(0,Math.min(r.width,i.x)),Math.max(0,Math.min(r.width,q.page.x-d.getPosition().x-r.computedLeft))],y:[Math.max(0,Math.min(r.height,i.y)),Math.max(0,Math.min(r.height,q.page.y-d.getPosition().y-r.computedTop))]};o.setStyles({top:Math.min(p.y[0],p.y[1])+r.computedTop+"px",left:Math.min(p.x[0],p.x[1])+r.computedLeft+"px",width:Math.abs(p.x[0]-p.x[1])+"px",height:Math.abs(p.y[0]-p.y[1])+"px"});g()};var k=function(p){f(p);e=false};var n=function(){d.getParent().getElements('input[name^="importantPart"]').each(function(p){["x","y","width","height"].each(function(q){if(p.get("name").substr(13,q.length)===q.capitalize()){l[q]=p=$(p)}})});if(Object.getLength(l)!==4){return}Object.each(l,function(p){p.getParent().setStyle("display","none")});d.addClass("tl_edit_preview_enabled");o=new Element("div",{"class":"tl_edit_preview_important_part"}).inject(d);c();j.addEvent("load",c);d.addEvents({mousedown:a,touchstart:a});$(document.documentElement).addEvents({mousemove:f,touchmove:f,mouseup:k,touchend:k,touchcancel:k,resize:c})};window.addEvent("domready",n)}};document.addEvent("mousedown",function(a){Backend.getMousePosition(a)});window.addEvent("domready",function(){$(document.body).addClass("js");if(Browser.Features.Touch){$(document.body).addClass("touch")}Backend.collapsePalettes();Backend.addInteractiveHelp();Backend.convertEnableModules();Backend.makeWizardsSortable();if(Elements.chosen!=undefined){$$("select.tl_chosen").chosen()}$$("textarea.monospace").each(function(a){Backend.toggleWrap(a)})});window.addEvent("load",function(){Backend.limitPreviewHeight()});window.addEvent("ajax_change",function(){Backend.addInteractiveHelp();Backend.makeWizardsSortable();if(Elements.chosen!=undefined){$$("select.tl_chosen").filter(function(a){return a.getStyle("display")!="none"}).chosen()}});