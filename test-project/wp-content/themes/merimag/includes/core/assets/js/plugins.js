(function($){
    var attrName = 'data-theme-post-id-to-title',
        ids = {
            pending: {},
            known: {}
        },
        isBusy = false,
        update = function(){
            $('['+ attrName +']').each(function(){
                var $this = $(this),
                    id = $this.attr(attrName);
                if (typeof ids.known[id] == 'undefined') {
                    ids.pending[id] = true;
                } else {
                    $this.removeAttr(attrName).text(ids.known[id]);
                }
            });

            if ($.isEmptyObject(ids.pending)) {
                return;
            }

            if (isBusy) {
                return;
            } else {
                isBusy = true;
            }
            
            $.ajax({
                url: ajaxurl,
                method: 'post',
                dataType: 'json',
                data: {
                    action: 'theme_post_ids_to_title',
                    ids: ids.pending
                }
            }).done(function(r){
                if (r.success) {
                    ids.pending = {};
                    ids.known = $.extend(ids.known, r.data['ids']);
                    //alert(r.data['ids']);
                }

                setTimeout(
                    update,
                    100 // .always() must be executed first to disable isBusy
                );
            }).fail(function(jqXHR, textStatus, errorThrown){
                alert(String(errorThrown));
            }).always(function(){
                isBusy = false;
            });
        },
        timeoutId;

    window.themeMultiSelectIdsToTitle = function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(update, 100); // wait template to be added in DOM
    }
})(jQuery);
/*  jQuery.flexMenu 1.6.2
    https://github.com/352Media/flexMenu
    Description: If a list is too long for all items to fit on one line, display a popup menu instead.
    Dependencies: jQuery, Modernizr (optional). Without Modernizr, the menu can only be shown on click (not hover). */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)}(function(e){var i,n=e(window).width(),l=e(window).height(),o=[];e(window).resize(function(){clearTimeout(i),i=setTimeout(function(){e(window).width()===n&&e(window).height()===l||(e(o).each(function(){e(this).flexMenu({undo:!0}).flexMenu(this.options)}),n=e(window).width(),l=e(window).height())},200)}),e.fn.flexMenu=function(i){var n,l=e.extend({threshold:2,cutoff:2,linkText:"More",linkTitle:"View More",linkTextAll:"Menu",linkTitleAll:"Open/Close Menu",shouldApply:function(){return!0},showOnHover:!0,popupAbsolute:!0,popupClass:"",undo:!1},i);return this.options=l,(n=e.inArray(this,o))>=0?o.splice(n,1):o.push(this),this.each(function(){var i,n,o,t,f,u,s=e(this),d=s.find("> li"),p=d.first(),r=d.last(),a=d.length,h=Math.floor(p.offset().top),c=Math.floor(p.outerHeight(!0)),M=!1;function w(e){return Math.ceil(e.offset().top)>=h+c}if(w(r)&&a>l.threshold&&!l.undo&&s.is(":visible")&&l.shouldApply()){var v=e('<ul class="flexMenu-popup" style="display:none;'+(l.popupAbsolute?" position: absolute;":"")+'"></ul>');for(v.addClass(l.popupClass),u=a;u>1;u--){if(n=w(i=s.find("> li:last-child")),u-1<=l.cutoff){e(s.children().get().reverse()).appendTo(v),M=!0;break}if(!n)break;i.appendTo(v)}M?s.append('<li class="flexMenu-viewMore flexMenu-allInPopup"><a href="#" title="'+l.linkTitleAll+'">'+l.linkTextAll+"</a></li>"):s.append('<li class="flexMenu-viewMore"><a href="#" title="'+l.linkTitle+'">'+l.linkText+"</a></li>"),w(o=s.find("> li.flexMenu-viewMore"))&&s.find("> li:nth-last-child(2)").appendTo(v),v.children().each(function(e,i){v.prepend(i)}),o.append(v),s.find("> li.flexMenu-viewMore > a").click(function(i){var n;n=o,e("li.flexMenu-viewMore.active").not(n).removeClass("active").find("> ul").hide(),v.toggle(),o.toggleClass("active"),i.preventDefault()}),l.showOnHover&&"undefined"!=typeof Modernizr&&!Modernizr.touch&&o.hover(function(){v.show(),e(this).addClass("active")},function(){v.hide(),e(this).removeClass("active")})}else if(l.undo&&s.find("ul.flexMenu-popup")){for(t=(f=s.find("ul.flexMenu-popup")).find("li").length,u=1;u<=t;u++)f.find("> li:first-child").appendTo(s);f.remove(),s.find("> li.flexMenu-viewMore").remove()}})}});

/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-addtest-atrule-domprefixes-hasevent-mq-prefixed-prefixedcss-prefixedcssvalue-prefixes-printshiv-setclasses-testallprops-testprop-teststyles !*/
!function(e,t,n){function r(e,t){return typeof e===t}function o(){var e,t,n,o,i,a,s;for(var l in E)if(E.hasOwnProperty(l)){if(e=[],t=E[l],t.name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(o=r(t.fn,"function")?t.fn():t.fn,i=0;i<e.length;i++)a=e[i],s=a.split("."),1===s.length?Modernizr[s[0]]=o:(!Modernizr[s[0]]||Modernizr[s[0]]instanceof Boolean||(Modernizr[s[0]]=new Boolean(Modernizr[s[0]])),Modernizr[s[0]][s[1]]=o),C.push((o?"":"no-")+s.join("-"))}}function i(e){return e.replace(/([a-z])-([a-z])/g,function(e,t,n){return t+n.toUpperCase()}).replace(/^-/,"")}function a(e){return e.replace(/([A-Z])/g,function(e,t){return"-"+t.toLowerCase()}).replace(/^ms-/,"-ms-")}function s(e){var t=_.className,n=Modernizr._config.classPrefix||"";if(x&&(t=t.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");t=t.replace(r,"$1"+n+"js$2")}Modernizr._config.enableClasses&&(t+=" "+n+e.join(" "+n),x?_.className.baseVal=t:_.className=t)}function l(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):x?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function u(e,t){return function(){return e.apply(t,arguments)}}function f(e,t,n){var o;for(var i in e)if(e[i]in t)return n===!1?e[i]:(o=t[e[i]],r(o,"function")?u(o,n||t):o);return!1}function c(e,t){return!!~(""+e).indexOf(t)}function d(){var e=t.body;return e||(e=l(x?"svg":"body"),e.fake=!0),e}function p(e,n,r,o){var i,a,s,u,f="modernizr",c=l("div"),p=d();if(parseInt(r,10))for(;r--;)s=l("div"),s.id=o?o[r]:f+(r+1),c.appendChild(s);return i=l("style"),i.type="text/css",i.id="s"+f,(p.fake?p:c).appendChild(i),p.appendChild(c),i.styleSheet?i.styleSheet.cssText=e:i.appendChild(t.createTextNode(e)),c.id=f,p.fake&&(p.style.background="",p.style.overflow="hidden",u=_.style.overflow,_.style.overflow="hidden",_.appendChild(p)),a=n(c,e),p.fake?(p.parentNode.removeChild(p),_.style.overflow=u,_.offsetHeight):c.parentNode.removeChild(c),!!a}function m(t,n,r){var o;if("getComputedStyle"in e){o=getComputedStyle.call(e,t,n);var i=e.console;if(null!==o)r&&(o=o.getPropertyValue(r));else if(i){var a=i.error?"error":"log";i[a].call(i,"getComputedStyle returning null, its possible modernizr test results are inaccurate")}}else o=!n&&t.currentStyle&&t.currentStyle[r];return o}function h(t,r){var o=t.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(a(t[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var i=[];o--;)i.push("("+a(t[o])+":"+r+")");return i=i.join(" or "),p("@supports ("+i+") { #modernizr { position: absolute; } }",function(e){return"absolute"==m(e,null,"position")})}return n}function v(e,t,o,a){function s(){f&&(delete M.style,delete M.modElem)}if(a=r(a,"undefined")?!1:a,!r(o,"undefined")){var u=h(e,o);if(!r(u,"undefined"))return u}for(var f,d,p,m,v,g=["modernizr","tspan","samp"];!M.style&&g.length;)f=!0,M.modElem=l(g.shift()),M.style=M.modElem.style;for(p=e.length,d=0;p>d;d++)if(m=e[d],v=M.style[m],c(m,"-")&&(m=i(m)),M.style[m]!==n){if(a||r(o,"undefined"))return s(),"pfx"==t?m:!0;try{M.style[m]=o}catch(y){}if(M.style[m]!=v)return s(),"pfx"==t?m:!0}return s(),!1}function g(e,t,n,o,i){var a=e.charAt(0).toUpperCase()+e.slice(1),s=(e+" "+P.join(a+" ")+a).split(" ");return r(t,"string")||r(t,"undefined")?v(s,t,o,i):(s=(e+" "+T.join(a+" ")+a).split(" "),f(s,t,n))}function y(e,t,r){return g(e,n,n,t,r)}function S(e,t){if("object"==typeof e)for(var n in e)L(e,n)&&S(n,e[n]);else{e=e.toLowerCase();var r=e.split("."),o=Modernizr[r[0]];if(2==r.length&&(o=o[r[1]]),"undefined"!=typeof o)return Modernizr;t="function"==typeof t?t():t,1==r.length?Modernizr[r[0]]=t:(!Modernizr[r[0]]||Modernizr[r[0]]instanceof Boolean||(Modernizr[r[0]]=new Boolean(Modernizr[r[0]])),Modernizr[r[0]][r[1]]=t),s([(t&&0!=t?"":"no-")+r.join("-")]),Modernizr._trigger(e,t)}return Modernizr}var C=[],E=[],b={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout(function(){t(n[e])},0)},addTest:function(e,t,n){E.push({name:e,fn:t,options:n})},addAsyncTest:function(e){E.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=b,Modernizr=new Modernizr;var _=t.documentElement,x="svg"===_.nodeName.toLowerCase();x||!function(e,t){function n(e,t){var n=e.createElement("p"),r=e.getElementsByTagName("head")[0]||e.documentElement;return n.innerHTML="x<style>"+t+"</style>",r.insertBefore(n.lastChild,r.firstChild)}function r(){var e=x.elements;return"string"==typeof e?e.split(" "):e}function o(e,t){var n=x.elements;"string"!=typeof n&&(n=n.join(" ")),"string"!=typeof e&&(e=e.join(" ")),x.elements=n+" "+e,u(t)}function i(e){var t=_[e[E]];return t||(t={},b++,e[E]=b,_[b]=t),t}function a(e,n,r){if(n||(n=t),v)return n.createElement(e);r||(r=i(n));var o;return o=r.cache[e]?r.cache[e].cloneNode():C.test(e)?(r.cache[e]=r.createElem(e)).cloneNode():r.createElem(e),!o.canHaveChildren||S.test(e)||o.tagUrn?o:r.frag.appendChild(o)}function s(e,n){if(e||(e=t),v)return e.createDocumentFragment();n=n||i(e);for(var o=n.frag.cloneNode(),a=0,s=r(),l=s.length;l>a;a++)o.createElement(s[a]);return o}function l(e,t){t.cache||(t.cache={},t.createElem=e.createElement,t.createFrag=e.createDocumentFragment,t.frag=t.createFrag()),e.createElement=function(n){return x.shivMethods?a(n,e,t):t.createElem(n)},e.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+r().join().replace(/[\w\-:]+/g,function(e){return t.createElem(e),t.frag.createElement(e),'c("'+e+'")'})+");return n}")(x,t.frag)}function u(e){e||(e=t);var r=i(e);return!x.shivCSS||h||r.hasCSS||(r.hasCSS=!!n(e,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),v||l(e,r),e}function f(e){for(var t,n=e.getElementsByTagName("*"),o=n.length,i=RegExp("^(?:"+r().join("|")+")$","i"),a=[];o--;)t=n[o],i.test(t.nodeName)&&a.push(t.applyElement(c(t)));return a}function c(e){for(var t,n=e.attributes,r=n.length,o=e.ownerDocument.createElement(N+":"+e.nodeName);r--;)t=n[r],t.specified&&o.setAttribute(t.nodeName,t.nodeValue);return o.style.cssText=e.style.cssText,o}function d(e){for(var t,n=e.split("{"),o=n.length,i=RegExp("(^|[\\s,>+~])("+r().join("|")+")(?=[[\\s,>+~#.:]|$)","gi"),a="$1"+N+"\\:$2";o--;)t=n[o]=n[o].split("}"),t[t.length-1]=t[t.length-1].replace(i,a),n[o]=t.join("}");return n.join("{")}function p(e){for(var t=e.length;t--;)e[t].removeNode()}function m(e){function t(){clearTimeout(a._removeSheetTimer),r&&r.removeNode(!0),r=null}var r,o,a=i(e),s=e.namespaces,l=e.parentWindow;return!T||e.printShived?e:("undefined"==typeof s[N]&&s.add(N),l.attachEvent("onbeforeprint",function(){t();for(var i,a,s,l=e.styleSheets,u=[],c=l.length,p=Array(c);c--;)p[c]=l[c];for(;s=p.pop();)if(!s.disabled&&w.test(s.media)){try{i=s.imports,a=i.length}catch(m){a=0}for(c=0;a>c;c++)p.push(i[c]);try{u.push(s.cssText)}catch(m){}}u=d(u.reverse().join("")),o=f(e),r=n(e,u)}),l.attachEvent("onafterprint",function(){p(o),clearTimeout(a._removeSheetTimer),a._removeSheetTimer=setTimeout(t,500)}),e.printShived=!0,e)}var h,v,g="3.7.3",y=e.html5||{},S=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,C=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,E="_html5shiv",b=0,_={};!function(){try{var e=t.createElement("a");e.innerHTML="<xyz></xyz>",h="hidden"in e,v=1==e.childNodes.length||function(){t.createElement("a");var e=t.createDocumentFragment();return"undefined"==typeof e.cloneNode||"undefined"==typeof e.createDocumentFragment||"undefined"==typeof e.createElement}()}catch(n){h=!0,v=!0}}();var x={elements:y.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video",version:g,shivCSS:y.shivCSS!==!1,supportsUnknownElements:v,shivMethods:y.shivMethods!==!1,type:"default",shivDocument:u,createElement:a,createDocumentFragment:s,addElements:o};e.html5=x,u(t);var w=/^$|\b(?:all|print)\b/,N="html5shiv",T=!v&&function(){var n=t.documentElement;return!("undefined"==typeof t.namespaces||"undefined"==typeof t.parentWindow||"undefined"==typeof n.applyElement||"undefined"==typeof n.removeNode||"undefined"==typeof e.attachEvent)}();x.type+=" print",x.shivPrint=m,m(t),"object"==typeof module&&module.exports&&(module.exports=x)}("undefined"!=typeof e?e:this,t);var w=function(){function e(e,t){var o;return e?(t&&"string"!=typeof t||(t=l(t||"div")),e="on"+e,o=e in t,!o&&r&&(t.setAttribute||(t=l("div")),t.setAttribute(e,""),o="function"==typeof t[e],t[e]!==n&&(t[e]=n),t.removeAttribute(e)),o):!1}var r=!("onblur"in t.documentElement);return e}();b.hasEvent=w;var N="Moz O ms Webkit",T=b._config.usePrefixes?N.toLowerCase().split(" "):[];b._domPrefixes=T;var j=function(e,t){var n=!1,r=l("div"),o=r.style;if(e in o){var i=T.length;for(o[e]=t,n=o[e];i--&&!n;)o[e]="-"+T[i]+"-"+t,n=o[e]}return""===n&&(n=!1),n};b.prefixedCSSValue=j;var P=b._config.usePrefixes?N.split(" "):[];b._cssomPrefixes=P;var z=function(t){var r,o=k.length,i=e.CSSRule;if("undefined"==typeof i)return n;if(!t)return!1;if(t=t.replace(/^@/,""),r=t.replace(/-/g,"_").toUpperCase()+"_RULE",r in i)return"@"+t;for(var a=0;o>a;a++){var s=k[a],l=s.toUpperCase()+"_"+r;if(l in i)return"@-"+s.toLowerCase()+"-"+t}return!1};b.atRule=z;var k=b._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];b._prefixes=k;var A=(b.testStyles=p,function(){var t=e.matchMedia||e.msMatchMedia;return t?function(e){var n=t(e);return n&&n.matches||!1}:function(t){var n=!1;return p("@media "+t+" { #modernizr { position: absolute; } }",function(t){n="absolute"==(e.getComputedStyle?e.getComputedStyle(t,null):t.currentStyle).position}),n}}());b.mq=A;var F={elem:l("modernizr")};Modernizr._q.push(function(){delete F.elem});var M={style:F.elem.style};Modernizr._q.unshift(function(){delete M.style});b.testProp=function(e,t,r){return v([e],n,t,r)};b.testAllProps=g,b.testAllProps=y;var L,$=b.prefixed=function(e,t,n){return 0===e.indexOf("@")?z(e):(-1!=e.indexOf("-")&&(e=i(e)),t?g(e,t,n):g(e,"pfx"))};b.prefixedCSS=function(e){var t=$(e);return t&&a(t)};!function(){var e={}.hasOwnProperty;L=r(e,"undefined")||r(e.call,"undefined")?function(e,t){return t in e&&r(e.constructor.prototype[t],"undefined")}:function(t,n){return e.call(t,n)}}(),b._l={},b.on=function(e,t){this._l[e]||(this._l[e]=[]),this._l[e].push(t),Modernizr.hasOwnProperty(e)&&setTimeout(function(){Modernizr._trigger(e,Modernizr[e])},0)},b._trigger=function(e,t){if(this._l[e]){var n=this._l[e];setTimeout(function(){var e,r;for(e=0;e<n.length;e++)(r=n[e])(t)},0),delete this._l[e]}},Modernizr._q.push(function(){b.addTest=S}),o(),s(C),delete b.addTest,delete b.addAsyncTest;for(var q=0;q<Modernizr._q.length;q++)Modernizr._q[q]();e.Modernizr=Modernizr}(window,document);


// Simple pagination

!function(e){var a={init:function(t){var s=e.extend({items:1,itemsOnPage:1,pages:0,displayedPages:5,edges:2,currentPage:0,useAnchors:!0,hrefTextPrefix:"#page-",hrefTextSuffix:"",prevText:'<i class="icofont-arrow-left"></i>',nextText:'<i class="icofont-arrow-right"></i>',ellipseText:"&hellip;",ellipsePageSet:!0,cssStyle:"light-theme",listStyle:"",labelMap:[],selectOnClick:!0,nextAtFront:!1,invertPageOrder:!1,useStartEdge:!0,useEndEdge:!0,onPageClick:function(e,a){},onInit:function(){}},t||{}),i=this;return s.pages=s.pages?s.pages:Math.ceil(s.items/s.itemsOnPage)?Math.ceil(s.items/s.itemsOnPage):1,s.currentPage?s.currentPage=s.currentPage-1:s.currentPage=s.invertPageOrder?s.pages-1:0,s.halfDisplayed=s.displayedPages/2,this.each(function(){i.addClass(s.cssStyle+" simple-pagination").data("pagination",s),a._draw.call(i)}),s.onInit(),this},selectPage:function(e){return a._selectPage.call(this,e-1),this},prevPage:function(){var e=this.data("pagination");return e.invertPageOrder?e.currentPage<e.pages-1&&a._selectPage.call(this,e.currentPage+1):e.currentPage>0&&a._selectPage.call(this,e.currentPage-1),this},nextPage:function(){var e=this.data("pagination");return e.invertPageOrder?e.currentPage>0&&a._selectPage.call(this,e.currentPage-1):e.currentPage<e.pages-1&&a._selectPage.call(this,e.currentPage+1),this},getPagesCount:function(){return this.data("pagination").pages},setPagesCount:function(e){this.data("pagination").pages=e},getCurrentPage:function(){return this.data("pagination").currentPage+1},destroy:function(){return this.empty(),this},drawPage:function(e){var t=this.data("pagination");return t.currentPage=e-1,this.data("pagination",t),a._draw.call(this),this},redraw:function(){return a._draw.call(this),this},disable:function(){var e=this.data("pagination");return e.disabled=!0,this.data("pagination",e),a._draw.call(this),this},enable:function(){var e=this.data("pagination");return e.disabled=!1,this.data("pagination",e),a._draw.call(this),this},updateItems:function(e){var t=this.data("pagination");t.items=e,t.pages=a._getPages(t),this.data("pagination",t),a._draw.call(this)},updateItemsOnPage:function(e){var t=this.data("pagination");return t.itemsOnPage=e,t.pages=a._getPages(t),this.data("pagination",t),a._selectPage.call(this,0),this},getItemsOnPage:function(){return this.data("pagination").itemsOnPage},_draw:function(){var t,s=this.data("pagination"),i=a._getInterval(s);a.destroy.call(this);var n="UL"===("function"==typeof this.prop?this.prop("tagName"):this.attr("tagName"))?this:e("<ul"+(s.listStyle?' class="'+s.listStyle+'"':"")+"></ul>").appendTo(this);if(s.prevText&&a._appendItem.call(this,s.invertPageOrder?s.currentPage+1:s.currentPage-1,{text:s.prevText,classes:"prev"}),s.nextText&&s.nextAtFront&&a._appendItem.call(this,s.invertPageOrder?s.currentPage-1:s.currentPage+1,{text:s.nextText,classes:"next"}),s.invertPageOrder){if(i.end<s.pages&&s.edges>0){if(s.useStartEdge){var l=Math.max(s.pages-s.edges,i.end);for(t=s.pages-1;t>=l;t--)a._appendItem.call(this,t)}s.pages-s.edges>i.end&&s.pages-s.edges-i.end!=1?n.append('<li class="disabled merimag-page-link merimag-page-link-disabled"><span class="ellipse">'+s.ellipseText+"</span></li>"):s.pages-s.edges-i.end==1&&a._appendItem.call(this,i.end)}}else if(i.start>0&&s.edges>0){if(s.useStartEdge){var r=Math.min(s.edges,i.start);for(t=0;t<r;t++)a._appendItem.call(this,t)}s.edges<i.start&&i.start-s.edges!=1?n.append('<li class="disabled merimag-page-link merimag-page-link-disabled"><span class="ellipse">'+s.ellipseText+"</span></li>"):i.start-s.edges==1&&a._appendItem.call(this,s.edges)}if(s.invertPageOrder)for(t=i.end-1;t>=i.start;t--)a._appendItem.call(this,t);else for(t=i.start;t<i.end;t++)a._appendItem.call(this,t);if(s.invertPageOrder){if(i.start>0&&s.edges>0&&(s.edges<i.start&&i.start-s.edges!=1?n.append('<li class="disabled merimag-page-link merimag-page-link-disabled"><span class="ellipse">'+s.ellipseText+"</span></li>"):i.start-s.edges==1&&a._appendItem.call(this,s.edges),s.useEndEdge))for(t=(r=Math.min(s.edges,i.start))-1;t>=0;t--)a._appendItem.call(this,t)}else if(i.end<s.pages&&s.edges>0&&(s.pages-s.edges>i.end&&s.pages-s.edges-i.end!=1?n.append('<li class="disabled merimag-page-link merimag-page-link-disabled"><span class="ellipse">'+s.ellipseText+"</span></li>"):s.pages-s.edges-i.end==1&&a._appendItem.call(this,i.end),s.useEndEdge))for(t=l=Math.max(s.pages-s.edges,i.end);t<s.pages;t++)a._appendItem.call(this,t);s.nextText&&!s.nextAtFront&&a._appendItem.call(this,s.invertPageOrder?s.currentPage-1:s.currentPage+1,{text:s.nextText,classes:"next"}),s.ellipsePageSet&&!s.disabled&&a._ellipseClick.call(this,n)},_getPages:function(e){return Math.ceil(e.items/e.itemsOnPage)||1},_getInterval:function(e){return{start:Math.ceil(e.currentPage>e.halfDisplayed?Math.max(Math.min(e.currentPage-e.halfDisplayed,e.pages-e.displayedPages),0):0),end:Math.ceil(e.currentPage>e.halfDisplayed?Math.min(e.currentPage+e.halfDisplayed,e.pages):Math.min(e.displayedPages,e.pages))}},_appendItem:function(t,s){var i,n,l=this,r=l.data("pagination"),g=e('<li class=" merimag-page-link"></li>'),p=l.find("ul");i={text:(t=t<0?0:t<r.pages?t:r.pages-1)+1,classes:""},r.labelMap.length&&r.labelMap[t]&&(i.text=r.labelMap[t]),i=e.extend(i,s||{}),t==r.currentPage||r.disabled?(r.disabled||"prev"===i.classes||"next"===i.classes?g.addClass("disabled merimag-page-link-disabled"):g.addClass("active principal-color-border-color merimag-page-link-active"),n=e('<span class="current">'+i.text+"</span>")):(n=r.useAnchors?e('<a href="'+r.hrefTextPrefix+(t+1)+r.hrefTextSuffix+'" class=" page-link">'+i.text+"</a>"):e("<span>"+i.text+"</span>")).click(function(e){return a._selectPage.call(l,t,e)}),i.classes&&n.addClass(i.classes),g.append(n),p.length?p.append(g):l.append(g)},_selectPage:function(e,t){var s=this.data("pagination");return s.currentPage=e,s.selectOnClick&&a._draw.call(this),s.onPageClick(e+1,t)},_ellipseClick:function(t){var s=this,i=this.data("pagination"),n=t.find(".ellipse");n.addClass("clickable").parent().removeClass("disabled"),n.click(function(t){if(!i.disable){var l=e(this),r=(parseInt(l.parent().prev().text(),10)||0)+1;l.html('<input type="number" min="1" max="'+i.pages+'" step="1" value="'+r+'">').find("input").focus().click(function(e){e.stopPropagation()}).keyup(function(t){var l=e(this).val();13===t.which&&""!==l?l>0&&l<=i.pages&&a._selectPage.call(s,l-1):27===t.which&&n.empty().html(i.ellipseText)}).bind("blur",function(t){var l=e(this).val();return""!==l&&a._selectPage.call(s,l-1),n.empty().html(i.ellipseText),!1})}return!1})}};e.fn.pagination=function(t){return a[t]&&"_"!=t.charAt(0)?a[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist on jQuery.pagination"):a.init.apply(this,arguments)}}(jQuery);


// Marquee Ticker

/**
 * jQuery.marquee - scrolling text like old marquee element
 * @author Aamir Afridi - aamirafridi(at)gmail(dot)com / http://aamirafridi.com/jquery/jquery-marquee-plugin
 */
(function(f){f.fn.marquee=function(x){return this.each(function(){var a=f.extend({},f.fn.marquee.defaults,x),b=f(this),c,t,e=3,y="animation-play-state",p=!1,E=function(a,b,c){for(var e=["webkit","moz","MS","o",""],d=0;d<e.length;d++)e[d]||(b=b.toLowerCase()),a.addEventListener(e[d]+b,c,!1)},F=function(a){var b=[],c;for(c in a)a.hasOwnProperty(c)&&b.push(c+":"+a[c]);b.push();return"{"+b.join(",")+"}"},l={pause:function(){p&&a.allowCss3Support?c.css(y,"paused"):f.fn.pause&&c.pause();b.data("runningStatus",
"paused");b.trigger("paused")},resume:function(){p&&a.allowCss3Support?c.css(y,"running"):f.fn.resume&&c.resume();b.data("runningStatus","resumed");b.trigger("resumed")},toggle:function(){l["resumed"==b.data("runningStatus")?"pause":"resume"]()},destroy:function(){clearTimeout(b.timer);b.find("*").addBack().unbind();b.html(b.find(".js-marquee:first").html())}};if("string"===typeof x)f.isFunction(l[x])&&(c||(c=b.find(".js-marquee-wrapper")),!0===b.data("css3AnimationIsSupported")&&(p=!0),l[x]());else{var u;
f.each(a,function(c,d){u=b.attr("data-"+c);if("undefined"!==typeof u){switch(u){case "true":u=!0;break;case "false":u=!1}a[c]=u}});a.speed&&(a.duration=parseInt(b.width(),10)/a.speed*1E3);var v="up"==a.direction||"down"==a.direction;a.gap=a.duplicated?parseInt(a.gap):0;b.wrapInner('<div class="js-marquee"></div>');var h=b.find(".js-marquee").css({"margin-right":a.gap,"float":"left"});a.duplicated&&h.clone(!0).appendTo(b);b.wrapInner('<div style="width:100000px" class="js-marquee-wrapper"></div>');
c=b.find(".js-marquee-wrapper");if(v){var k=b.height();c.removeAttr("style");b.height(k);b.find(".js-marquee").css({"float":"none","margin-bottom":a.gap,"margin-right":0});a.duplicated&&b.find(".js-marquee:last").css({"margin-bottom":0});var q=b.find(".js-marquee:first").height()+a.gap;a.startVisible&&!a.duplicated?(a._completeDuration=(parseInt(q,10)+parseInt(k,10))/parseInt(k,10)*a.duration,a.duration*=parseInt(q,10)/parseInt(k,10)):a.duration*=(parseInt(q,10)+parseInt(k,10))/parseInt(k,10)}else{var m=
b.find(".js-marquee:first").width()+a.gap;var n=b.width();a.startVisible&&!a.duplicated?(a._completeDuration=(parseInt(m,10)+parseInt(n,10))/parseInt(n,10)*a.duration,a.duration*=parseInt(m,10)/parseInt(n,10)):a.duration*=(parseInt(m,10)+parseInt(n,10))/parseInt(n,10)}a.duplicated&&(a.duration/=2);if(a.allowCss3Support){h=document.body||document.createElement("div");var g="marqueeAnimation-"+Math.floor(1E7*Math.random()),A=["Webkit","Moz","O","ms","Khtml"],B="animation",d="",r="";h.style.animation&&
(r="@keyframes "+g+" ",p=!0);if(!1===p)for(var z=0;z<A.length;z++)if(void 0!==h.style[A[z]+"AnimationName"]){h="-"+A[z].toLowerCase()+"-";B=h+B;y=h+y;r="@"+h+"keyframes "+g+" ";p=!0;break}p&&(d=g+" "+a.duration/1E3+"s "+a.delayBeforeStart/1E3+"s infinite "+a.css3easing,b.data("css3AnimationIsSupported",!0))}var C=function(){c.css("transform","translateY("+("up"==a.direction?k+"px":"-"+q+"px")+")")},D=function(){c.css("transform","translateX("+("left"==a.direction?n+"px":"-"+m+"px")+")")};a.duplicated?
(v?a.startVisible?c.css("transform","translateY(0)"):c.css("transform","translateY("+("up"==a.direction?k+"px":"-"+(2*q-a.gap)+"px")+")"):a.startVisible?c.css("transform","translateX(0)"):c.css("transform","translateX("+("left"==a.direction?n+"px":"-"+(2*m-a.gap)+"px")+")"),a.startVisible||(e=1)):a.startVisible?e=2:v?C():D();var w=function(){a.duplicated&&(1===e?(a._originalDuration=a.duration,a.duration=v?"up"==a.direction?a.duration+k/(q/a.duration):2*a.duration:"left"==a.direction?a.duration+n/
(m/a.duration):2*a.duration,d&&(d=g+" "+a.duration/1E3+"s "+a.delayBeforeStart/1E3+"s "+a.css3easing),e++):2===e&&(a.duration=a._originalDuration,d&&(g+="0",r=f.trim(r)+"0 ",d=g+" "+a.duration/1E3+"s 0s infinite "+a.css3easing),e++));v?a.duplicated?(2<e&&c.css("transform","translateY("+("up"==a.direction?0:"-"+q+"px")+")"),t={transform:"translateY("+("up"==a.direction?"-"+q+"px":0)+")"}):a.startVisible?2===e?(d&&(d=g+" "+a.duration/1E3+"s "+a.delayBeforeStart/1E3+"s "+a.css3easing),t={transform:"translateY("+
("up"==a.direction?"-"+q+"px":k+"px")+")"},e++):3===e&&(a.duration=a._completeDuration,d&&(g+="0",r=f.trim(r)+"0 ",d=g+" "+a.duration/1E3+"s 0s infinite "+a.css3easing),C()):(C(),t={transform:"translateY("+("up"==a.direction?"-"+c.height()+"px":k+"px")+")"}):a.duplicated?(2<e&&c.css("transform","translateX("+("left"==a.direction?0:"-"+m+"px")+")"),t={transform:"translateX("+("left"==a.direction?"-"+m+"px":0)+")"}):a.startVisible?2===e?(d&&(d=g+" "+a.duration/1E3+"s "+a.delayBeforeStart/1E3+"s "+a.css3easing),
t={transform:"translateX("+("left"==a.direction?"-"+m+"px":n+"px")+")"},e++):3===e&&(a.duration=a._completeDuration,d&&(g+="0",r=f.trim(r)+"0 ",d=g+" "+a.duration/1E3+"s 0s infinite "+a.css3easing),D()):(D(),t={transform:"translateX("+("left"==a.direction?"-"+m+"px":n+"px")+")"});b.trigger("beforeStarting");if(p){c.css(B,d);var h=r+" { 100%  "+F(t)+"}",l=c.find("style");0!==l.length?l.filter(":last").html(h):f("head").append("<style>"+h+"</style>");E(c[0],"AnimationIteration",function(){b.trigger("finished")});
E(c[0],"AnimationEnd",function(){w();b.trigger("finished")})}else c.animate(t,a.duration,a.easing,function(){b.trigger("finished");a.pauseOnCycle?b.timer=setTimeout(w,a.delayBeforeStart):w()});b.data("runningStatus","resumed")};b.bind("pause",l.pause);b.bind("resume",l.resume);a.pauseOnHover&&(b.bind("mouseenter",l.pause),b.bind("mouseleave",l.resume));p&&a.allowCss3Support?w():b.timer=setTimeout(w,a.delayBeforeStart)}})};f.fn.marquee.defaults={allowCss3Support:!0,css3easing:"linear",easing:"linear",
delayBeforeStart:1E3,direction:"left",duplicated:!1,duration:5E3,gap:20,pauseOnCycle:!1,pauseOnHover:!1,startVisible:!1}})(jQuery);

/**
 * jquery-circle-progress - jQuery Plugin to draw animated circular progress bars:
 * {@link http://kottenator.github.io/jquery-circle-progress/}
 *
 * @author Rostyslav Bryzgunov <kottenator@gmail.com>
 * @version 1.2.2
 * @licence MIT
 * @preserve
 */
!function(i){if("function"==typeof define&&define.amd)define(["jquery"],i);else if("object"==typeof module&&module.exports){var t=require("jquery");i(t),module.exports=t}else i(jQuery)}(function(i){function t(i){this.init(i)}t.prototype={value:0,size:100,startAngle:-Math.PI,thickness:"auto",fill:{gradient:["#3aeabb","#fdd250"]},emptyFill:"rgba(0, 0, 0, .1)",animation:{duration:1200,easing:"circleProgressEasing"},animationStartValue:0,reverse:!1,lineCap:"butt",insertMode:"prepend",constructor:t,el:null,canvas:null,ctx:null,radius:0,arcFill:null,lastFrameValue:0,init:function(t){i.extend(this,t),this.radius=this.size/2,this.initWidget(),this.initFill(),this.draw(),this.el.trigger("circle-inited")},initWidget:function(){this.canvas||(this.canvas=i("<canvas>")["prepend"==this.insertMode?"prependTo":"appendTo"](this.el)[0]);var t=this.canvas;if(t.width=this.size,t.height=this.size,this.ctx=t.getContext("2d"),window.devicePixelRatio>1){var e=window.devicePixelRatio;t.style.width=t.style.height=this.size+"px",t.width=t.height=this.size*e,this.ctx.scale(e,e)}},initFill:function(){function t(){var t=i("<canvas>")[0];t.width=e.size,t.height=e.size,t.getContext("2d").drawImage(g,0,0,r,r),e.arcFill=e.ctx.createPattern(t,"no-repeat"),e.drawFrame(e.lastFrameValue)}var e=this,a=this.fill,n=this.ctx,r=this.size;if(!a)throw Error("The fill is not specified!");if("string"==typeof a&&(a={color:a}),a.color&&(this.arcFill=a.color),a.gradient){var s=a.gradient;if(1==s.length)this.arcFill=s[0];else if(s.length>1){for(var l=a.gradientAngle||0,o=a.gradientDirection||[r/2*(1-Math.cos(l)),r/2*(1+Math.sin(l)),r/2*(1+Math.cos(l)),r/2*(1-Math.sin(l))],h=n.createLinearGradient.apply(n,o),c=0;c<s.length;c++){var d=s[c],u=c/(s.length-1);i.isArray(d)&&(u=d[1],d=d[0]),h.addColorStop(u,d)}this.arcFill=h}}if(a.image){var g;a.image instanceof Image?g=a.image:(g=new Image,g.src=a.image),g.complete?t():g.onload=t}},draw:function(){this.animation?this.drawAnimated(this.value):this.drawFrame(this.value)},drawFrame:function(i){this.lastFrameValue=i,this.ctx.clearRect(0,0,this.size,this.size),this.drawEmptyArc(i),this.drawArc(i)},drawArc:function(i){if(0!==i){var t=this.ctx,e=this.radius,a=this.getThickness(),n=this.startAngle;t.save(),t.beginPath(),this.reverse?t.arc(e,e,e-a/2,n-2*Math.PI*i,n):t.arc(e,e,e-a/2,n,n+2*Math.PI*i),t.lineWidth=a,t.lineCap=this.lineCap,t.strokeStyle=this.arcFill,t.stroke(),t.restore()}},drawEmptyArc:function(i){var t=this.ctx,e=this.radius,a=this.getThickness(),n=this.startAngle;i<1&&(t.save(),t.beginPath(),i<=0?t.arc(e,e,e-a/2,0,2*Math.PI):this.reverse?t.arc(e,e,e-a/2,n,n-2*Math.PI*i):t.arc(e,e,e-a/2,n+2*Math.PI*i,n),t.lineWidth=a,t.strokeStyle=this.emptyFill,t.stroke(),t.restore())},drawAnimated:function(t){var e=this,a=this.el,n=i(this.canvas);n.stop(!0,!1),a.trigger("circle-animation-start"),n.css({animationProgress:0}).animate({animationProgress:1},i.extend({},this.animation,{step:function(i){var n=e.animationStartValue*(1-i)+t*i;e.drawFrame(n),a.trigger("circle-animation-progress",[i,n])}})).promise().always(function(){a.trigger("circle-animation-end")})},getThickness:function(){return i.isNumeric(this.thickness)?this.thickness:this.size/14},getValue:function(){return this.value},setValue:function(i){this.animation&&(this.animationStartValue=this.lastFrameValue),this.value=i,this.draw()}},i.circleProgress={defaults:t.prototype},i.easing.circleProgressEasing=function(i){return i<.5?(i=2*i,.5*i*i*i):(i=2-2*i,1-.5*i*i*i)},i.fn.circleProgress=function(e,a){var n="circle-progress",r=this.data(n);if("widget"==e){if(!r)throw Error('Calling "widget" method on not initialized instance is forbidden');return r.canvas}if("value"==e){if(!r)throw Error('Calling "value" method on not initialized instance is forbidden');if("undefined"==typeof a)return r.getValue();var s=arguments[1];return this.each(function(){i(this).data(n).setValue(s)})}return this.each(function(){var a=i(this),r=a.data(n),s=i.isPlainObject(e)?e:{};if(r)r.init(s);else{var l=i.extend({},a.data());"string"==typeof l.fill&&(l.fill=JSON.parse(l.fill)),"string"==typeof l.animation&&(l.animation=JSON.parse(l.animation)),s=i.extend(l,s),s.el=a,r=new t(s),a.data(n,r)}})}});


