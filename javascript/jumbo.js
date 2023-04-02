/*! jQuery v1.9.1 | (c) 2005, 2012 jQuery Foundation, Inc. | jquery.org/license
//@ sourceMappingURL=jquery.min.map
*/(function(e,t){var n,r,i=typeof t,o=e.document,a=e.location,s=e.jQuery,u=e.$,l={},c=[],p="1.9.1",f=c.concat,d=c.push,h=c.slice,g=c.indexOf,m=l.toString,y=l.hasOwnProperty,v=p.trim,b=function(e,t){return new b.fn.init(e,t,r)},x=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,w=/\S+/g,T=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,N=/^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/,C=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,k=/^[\],:{}\s]*$/,E=/(?:^|:|,)(?:\s*\[)+/g,S=/\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,A=/"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,j=/^-ms-/,D=/-([\da-z])/gi,L=function(e,t){return t.toUpperCase()},H=function(e){(o.addEventListener||"load"===e.type||"complete"===o.readyState)&&(q(),b.ready())},q=function(){o.addEventListener?(o.removeEventListener("DOMContentLoaded",H,!1),e.removeEventListener("load",H,!1)):(o.detachEvent("onreadystatechange",H),e.detachEvent("onload",H))};b.fn=b.prototype={jquery:p,constructor:b,init:function(e,n,r){var i,a;if(!e)return this;if("string"==typeof e){if(i="<"===e.charAt(0)&&">"===e.charAt(e.length-1)&&e.length>=3?[null,e,null]:N.exec(e),!i||!i[1]&&n)return!n||n.jquery?(n||r).find(e):this.constructor(n).find(e);if(i[1]){if(n=n instanceof b?n[0]:n,b.merge(this,b.parseHTML(i[1],n&&n.nodeType?n.ownerDocument||n:o,!0)),C.test(i[1])&&b.isPlainObject(n))for(i in n)b.isFunction(this[i])?this[i](n[i]):this.attr(i,n[i]);return this}if(a=o.getElementById(i[2]),a&&a.parentNode){if(a.id!==i[2])return r.find(e);this.length=1,this[0]=a}return this.context=o,this.selector=e,this}return e.nodeType?(this.context=this[0]=e,this.length=1,this):b.isFunction(e)?r.ready(e):(e.selector!==t&&(this.selector=e.selector,this.context=e.context),b.makeArray(e,this))},selector:"",length:0,size:function(){return this.length},toArray:function(){return h.call(this)},get:function(e){return null==e?this.toArray():0>e?this[this.length+e]:this[e]},pushStack:function(e){var t=b.merge(this.constructor(),e);return t.prevObject=this,t.context=this.context,t},each:function(e,t){return b.each(this,e,t)},ready:function(e){return b.ready.promise().done(e),this},slice:function(){return this.pushStack(h.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(0>e?t:0);return this.pushStack(n>=0&&t>n?[this[n]]:[])},map:function(e){return this.pushStack(b.map(this,function(t,n){return e.call(t,n,t)}))},end:function(){return this.prevObject||this.constructor(null)},push:d,sort:[].sort,splice:[].splice},b.fn.init.prototype=b.fn,b.extend=b.fn.extend=function(){var e,n,r,i,o,a,s=arguments[0]||{},u=1,l=arguments.length,c=!1;for("boolean"==typeof s&&(c=s,s=arguments[1]||{},u=2),"object"==typeof s||b.isFunction(s)||(s={}),l===u&&(s=this,--u);l>u;u++)if(null!=(o=arguments[u]))for(i in o)e=s[i],r=o[i],s!==r&&(c&&r&&(b.isPlainObject(r)||(n=b.isArray(r)))?(n?(n=!1,a=e&&b.isArray(e)?e:[]):a=e&&b.isPlainObject(e)?e:{},s[i]=b.extend(c,a,r)):r!==t&&(s[i]=r));return s},b.extend({noConflict:function(t){return e.$===b&&(e.$=u),t&&e.jQuery===b&&(e.jQuery=s),b},isReady:!1,readyWait:1,holdReady:function(e){e?b.readyWait++:b.ready(!0)},ready:function(e){if(e===!0?!--b.readyWait:!b.isReady){if(!o.body)return setTimeout(b.ready);b.isReady=!0,e!==!0&&--b.readyWait>0||(n.resolveWith(o,[b]),b.fn.trigger&&b(o).trigger("ready").off("ready"))}},isFunction:function(e){return"function"===b.type(e)},isArray:Array.isArray||function(e){return"array"===b.type(e)},isWindow:function(e){return null!=e&&e==e.window},isNumeric:function(e){return!isNaN(parseFloat(e))&&isFinite(e)},type:function(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?l[m.call(e)]||"object":typeof e},isPlainObject:function(e){if(!e||"object"!==b.type(e)||e.nodeType||b.isWindow(e))return!1;try{if(e.constructor&&!y.call(e,"constructor")&&!y.call(e.constructor.prototype,"isPrototypeOf"))return!1}catch(n){return!1}var r;for(r in e);return r===t||y.call(e,r)},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},error:function(e){throw Error(e)},parseHTML:function(e,t,n){if(!e||"string"!=typeof e)return null;"boolean"==typeof t&&(n=t,t=!1),t=t||o;var r=C.exec(e),i=!n&&[];return r?[t.createElement(r[1])]:(r=b.buildFragment([e],t,i),i&&b(i).remove(),b.merge([],r.childNodes))},parseJSON:function(n){return e.JSON&&e.JSON.parse?e.JSON.parse(n):null===n?n:"string"==typeof n&&(n=b.trim(n),n&&k.test(n.replace(S,"@").replace(A,"]").replace(E,"")))?Function("return "+n)():(b.error("Invalid JSON: "+n),t)},parseXML:function(n){var r,i;if(!n||"string"!=typeof n)return null;try{e.DOMParser?(i=new DOMParser,r=i.parseFromString(n,"text/xml")):(r=new ActiveXObject("Microsoft.XMLDOM"),r.async="false",r.loadXML(n))}catch(o){r=t}return r&&r.documentElement&&!r.getElementsByTagName("parsererror").length||b.error("Invalid XML: "+n),r},noop:function(){},globalEval:function(t){t&&b.trim(t)&&(e.execScript||function(t){e.eval.call(e,t)})(t)},camelCase:function(e){return e.replace(j,"ms-").replace(D,L)},nodeName:function(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()},each:function(e,t,n){var r,i=0,o=e.length,a=M(e);if(n){if(a){for(;o>i;i++)if(r=t.apply(e[i],n),r===!1)break}else for(i in e)if(r=t.apply(e[i],n),r===!1)break}else if(a){for(;o>i;i++)if(r=t.call(e[i],i,e[i]),r===!1)break}else for(i in e)if(r=t.call(e[i],i,e[i]),r===!1)break;return e},trim:v&&!v.call("\ufeff\u00a0")?function(e){return null==e?"":v.call(e)}:function(e){return null==e?"":(e+"").replace(T,"")},makeArray:function(e,t){var n=t||[];return null!=e&&(M(Object(e))?b.merge(n,"string"==typeof e?[e]:e):d.call(n,e)),n},inArray:function(e,t,n){var r;if(t){if(g)return g.call(t,e,n);for(r=t.length,n=n?0>n?Math.max(0,r+n):n:0;r>n;n++)if(n in t&&t[n]===e)return n}return-1},merge:function(e,n){var r=n.length,i=e.length,o=0;if("number"==typeof r)for(;r>o;o++)e[i++]=n[o];else while(n[o]!==t)e[i++]=n[o++];return e.length=i,e},grep:function(e,t,n){var r,i=[],o=0,a=e.length;for(n=!!n;a>o;o++)r=!!t(e[o],o),n!==r&&i.push(e[o]);return i},map:function(e,t,n){var r,i=0,o=e.length,a=M(e),s=[];if(a)for(;o>i;i++)r=t(e[i],i,n),null!=r&&(s[s.length]=r);else for(i in e)r=t(e[i],i,n),null!=r&&(s[s.length]=r);return f.apply([],s)},guid:1,proxy:function(e,n){var r,i,o;return"string"==typeof n&&(o=e[n],n=e,e=o),b.isFunction(e)?(r=h.call(arguments,2),i=function(){return e.apply(n||this,r.concat(h.call(arguments)))},i.guid=e.guid=e.guid||b.guid++,i):t},access:function(e,n,r,i,o,a,s){var u=0,l=e.length,c=null==r;if("object"===b.type(r)){o=!0;for(u in r)b.access(e,n,u,r[u],!0,a,s)}else if(i!==t&&(o=!0,b.isFunction(i)||(s=!0),c&&(s?(n.call(e,i),n=null):(c=n,n=function(e,t,n){return c.call(b(e),n)})),n))for(;l>u;u++)n(e[u],r,s?i:i.call(e[u],u,n(e[u],r)));return o?e:c?n.call(e):l?n(e[0],r):a},now:function(){return(new Date).getTime()}}),b.ready.promise=function(t){if(!n)if(n=b.Deferred(),"complete"===o.readyState)setTimeout(b.ready);else if(o.addEventListener)o.addEventListener("DOMContentLoaded",H,!1),e.addEventListener("load",H,!1);else{o.attachEvent("onreadystatechange",H),e.attachEvent("onload",H);var r=!1;try{r=null==e.frameElement&&o.documentElement}catch(i){}r&&r.doScroll&&function a(){if(!b.isReady){try{r.doScroll("left")}catch(e){return setTimeout(a,50)}q(),b.ready()}}()}return n.promise(t)},b.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(e,t){l["[object "+t+"]"]=t.toLowerCase()});function M(e){var t=e.length,n=b.type(e);return b.isWindow(e)?!1:1===e.nodeType&&t?!0:"array"===n||"function"!==n&&(0===t||"number"==typeof t&&t>0&&t-1 in e)}r=b(o);var _={};function F(e){var t=_[e]={};return b.each(e.match(w)||[],function(e,n){t[n]=!0}),t}b.Callbacks=function(e){e="string"==typeof e?_[e]||F(e):b.extend({},e);var n,r,i,o,a,s,u=[],l=!e.once&&[],c=function(t){for(r=e.memory&&t,i=!0,a=s||0,s=0,o=u.length,n=!0;u&&o>a;a++)if(u[a].apply(t[0],t[1])===!1&&e.stopOnFalse){r=!1;break}n=!1,u&&(l?l.length&&c(l.shift()):r?u=[]:p.disable())},p={add:function(){if(u){var t=u.length;(function i(t){b.each(t,function(t,n){var r=b.type(n);"function"===r?e.unique&&p.has(n)||u.push(n):n&&n.length&&"string"!==r&&i(n)})})(arguments),n?o=u.length:r&&(s=t,c(r))}return this},remove:function(){return u&&b.each(arguments,function(e,t){var r;while((r=b.inArray(t,u,r))>-1)u.splice(r,1),n&&(o>=r&&o--,a>=r&&a--)}),this},has:function(e){return e?b.inArray(e,u)>-1:!(!u||!u.length)},empty:function(){return u=[],this},disable:function(){return u=l=r=t,this},disabled:function(){return!u},lock:function(){return l=t,r||p.disable(),this},locked:function(){return!l},fireWith:function(e,t){return t=t||[],t=[e,t.slice?t.slice():t],!u||i&&!l||(n?l.push(t):c(t)),this},fire:function(){return p.fireWith(this,arguments),this},fired:function(){return!!i}};return p},b.extend({Deferred:function(e){var t=[["resolve","done",b.Callbacks("once memory"),"resolved"],["reject","fail",b.Callbacks("once memory"),"rejected"],["notify","progress",b.Callbacks("memory")]],n="pending",r={state:function(){return n},always:function(){return i.done(arguments).fail(arguments),this},then:function(){var e=arguments;return b.Deferred(function(n){b.each(t,function(t,o){var a=o[0],s=b.isFunction(e[t])&&e[t];i[o[1]](function(){var e=s&&s.apply(this,arguments);e&&b.isFunction(e.promise)?e.promise().done(n.resolve).fail(n.reject).progress(n.notify):n[a+"With"](this===r?n.promise():this,s?[e]:arguments)})}),e=null}).promise()},promise:function(e){return null!=e?b.extend(e,r):r}},i={};return r.pipe=r.then,b.each(t,function(e,o){var a=o[2],s=o[3];r[o[1]]=a.add,s&&a.add(function(){n=s},t[1^e][2].disable,t[2][2].lock),i[o[0]]=function(){return i[o[0]+"With"](this===i?r:this,arguments),this},i[o[0]+"With"]=a.fireWith}),r.promise(i),e&&e.call(i,i),i},when:function(e){var t=0,n=h.call(arguments),r=n.length,i=1!==r||e&&b.isFunction(e.promise)?r:0,o=1===i?e:b.Deferred(),a=function(e,t,n){return function(r){t[e]=this,n[e]=arguments.length>1?h.call(arguments):r,n===s?o.notifyWith(t,n):--i||o.resolveWith(t,n)}},s,u,l;if(r>1)for(s=Array(r),u=Array(r),l=Array(r);r>t;t++)n[t]&&b.isFunction(n[t].promise)?n[t].promise().done(a(t,l,n)).fail(o.reject).progress(a(t,u,s)):--i;return i||o.resolveWith(l,n),o.promise()}}),b.support=function(){var t,n,r,a,s,u,l,c,p,f,d=o.createElement("div");if(d.setAttribute("className","t"),d.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",n=d.getElementsByTagName("*"),r=d.getElementsByTagName("a")[0],!n||!r||!n.length)return{};s=o.createElement("select"),l=s.appendChild(o.createElement("option")),a=d.getElementsByTagName("input")[0],r.style.cssText="top:1px;float:left;opacity:.5",t={getSetAttribute:"t"!==d.className,leadingWhitespace:3===d.firstChild.nodeType,tbody:!d.getElementsByTagName("tbody").length,htmlSerialize:!!d.getElementsByTagName("link").length,style:/top/.test(r.getAttribute("style")),hrefNormalized:"/a"===r.getAttribute("href"),opacity:/^0.5/.test(r.style.opacity),cssFloat:!!r.style.cssFloat,checkOn:!!a.value,optSelected:l.selected,enctype:!!o.createElement("form").enctype,html5Clone:"<:nav></:nav>"!==o.createElement("nav").cloneNode(!0).outerHTML,boxModel:"CSS1Compat"===o.compatMode,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0,boxSizingReliable:!0,pixelPosition:!1},a.checked=!0,t.noCloneChecked=a.cloneNode(!0).checked,s.disabled=!0,t.optDisabled=!l.disabled;try{delete d.test}catch(h){t.deleteExpando=!1}a=o.createElement("input"),a.setAttribute("value",""),t.input=""===a.getAttribute("value"),a.value="t",a.setAttribute("type","radio"),t.radioValue="t"===a.value,a.setAttribute("checked","t"),a.setAttribute("name","t"),u=o.createDocumentFragment(),u.appendChild(a),t.appendChecked=a.checked,t.checkClone=u.cloneNode(!0).cloneNode(!0).lastChild.checked,d.attachEvent&&(d.attachEvent("onclick",function(){t.noCloneEvent=!1}),d.cloneNode(!0).click());for(f in{submit:!0,change:!0,focusin:!0})d.setAttribute(c="on"+f,"t"),t[f+"Bubbles"]=c in e||d.attributes[c].expando===!1;return d.style.backgroundClip="content-box",d.cloneNode(!0).style.backgroundClip="",t.clearCloneStyle="content-box"===d.style.backgroundClip,b(function(){var n,r,a,s="padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",u=o.getElementsByTagName("body")[0];u&&(n=o.createElement("div"),n.style.cssText="border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px",u.appendChild(n).appendChild(d),d.innerHTML="<table><tr><td></td><td>t</td></tr></table>",a=d.getElementsByTagName("td"),a[0].style.cssText="padding:0;margin:0;border:0;display:none",p=0===a[0].offsetHeight,a[0].style.display="",a[1].style.display="none",t.reliableHiddenOffsets=p&&0===a[0].offsetHeight,d.innerHTML="",d.style.cssText="box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",t.boxSizing=4===d.offsetWidth,t.doesNotIncludeMarginInBodyOffset=1!==u.offsetTop,e.getComputedStyle&&(t.pixelPosition="1%"!==(e.getComputedStyle(d,null)||{}).top,t.boxSizingReliable="4px"===(e.getComputedStyle(d,null)||{width:"4px"}).width,r=d.appendChild(o.createElement("div")),r.style.cssText=d.style.cssText=s,r.style.marginRight=r.style.width="0",d.style.width="1px",t.reliableMarginRight=!parseFloat((e.getComputedStyle(r,null)||{}).marginRight)),typeof d.style.zoom!==i&&(d.innerHTML="",d.style.cssText=s+"width:1px;padding:1px;display:inline;zoom:1",t.inlineBlockNeedsLayout=3===d.offsetWidth,d.style.display="block",d.innerHTML="<div></div>",d.firstChild.style.width="5px",t.shrinkWrapBlocks=3!==d.offsetWidth,t.inlineBlockNeedsLayout&&(u.style.zoom=1)),u.removeChild(n),n=d=a=r=null)}),n=s=u=l=r=a=null,t}();var O=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,B=/([A-Z])/g;function P(e,n,r,i){if(b.acceptData(e)){var o,a,s=b.expando,u="string"==typeof n,l=e.nodeType,p=l?b.cache:e,f=l?e[s]:e[s]&&s;if(f&&p[f]&&(i||p[f].data)||!u||r!==t)return f||(l?e[s]=f=c.pop()||b.guid++:f=s),p[f]||(p[f]={},l||(p[f].toJSON=b.noop)),("object"==typeof n||"function"==typeof n)&&(i?p[f]=b.extend(p[f],n):p[f].data=b.extend(p[f].data,n)),o=p[f],i||(o.data||(o.data={}),o=o.data),r!==t&&(o[b.camelCase(n)]=r),u?(a=o[n],null==a&&(a=o[b.camelCase(n)])):a=o,a}}function R(e,t,n){if(b.acceptData(e)){var r,i,o,a=e.nodeType,s=a?b.cache:e,u=a?e[b.expando]:b.expando;if(s[u]){if(t&&(o=n?s[u]:s[u].data)){b.isArray(t)?t=t.concat(b.map(t,b.camelCase)):t in o?t=[t]:(t=b.camelCase(t),t=t in o?[t]:t.split(" "));for(r=0,i=t.length;i>r;r++)delete o[t[r]];if(!(n?$:b.isEmptyObject)(o))return}(n||(delete s[u].data,$(s[u])))&&(a?b.cleanData([e],!0):b.support.deleteExpando||s!=s.window?delete s[u]:s[u]=null)}}}b.extend({cache:{},expando:"jQuery"+(p+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(e){return e=e.nodeType?b.cache[e[b.expando]]:e[b.expando],!!e&&!$(e)},data:function(e,t,n){return P(e,t,n)},removeData:function(e,t){return R(e,t)},_data:function(e,t,n){return P(e,t,n,!0)},_removeData:function(e,t){return R(e,t,!0)},acceptData:function(e){if(e.nodeType&&1!==e.nodeType&&9!==e.nodeType)return!1;var t=e.nodeName&&b.noData[e.nodeName.toLowerCase()];return!t||t!==!0&&e.getAttribute("classid")===t}}),b.fn.extend({data:function(e,n){var r,i,o=this[0],a=0,s=null;if(e===t){if(this.length&&(s=b.data(o),1===o.nodeType&&!b._data(o,"parsedAttrs"))){for(r=o.attributes;r.length>a;a++)i=r[a].name,i.indexOf("data-")||(i=b.camelCase(i.slice(5)),W(o,i,s[i]));b._data(o,"parsedAttrs",!0)}return s}return"object"==typeof e?this.each(function(){b.data(this,e)}):b.access(this,function(n){return n===t?o?W(o,e,b.data(o,e)):null:(this.each(function(){b.data(this,e,n)}),t)},null,n,arguments.length>1,null,!0)},removeData:function(e){return this.each(function(){b.removeData(this,e)})}});function W(e,n,r){if(r===t&&1===e.nodeType){var i="data-"+n.replace(B,"-$1").toLowerCase();if(r=e.getAttribute(i),"string"==typeof r){try{r="true"===r?!0:"false"===r?!1:"null"===r?null:+r+""===r?+r:O.test(r)?b.parseJSON(r):r}catch(o){}b.data(e,n,r)}else r=t}return r}function $(e){var t;for(t in e)if(("data"!==t||!b.isEmptyObject(e[t]))&&"toJSON"!==t)return!1;return!0}b.extend({queue:function(e,n,r){var i;return e?(n=(n||"fx")+"queue",i=b._data(e,n),r&&(!i||b.isArray(r)?i=b._data(e,n,b.makeArray(r)):i.push(r)),i||[]):t},dequeue:function(e,t){t=t||"fx";var n=b.queue(e,t),r=n.length,i=n.shift(),o=b._queueHooks(e,t),a=function(){b.dequeue(e,t)};"inprogress"===i&&(i=n.shift(),r--),o.cur=i,i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,a,o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return b._data(e,n)||b._data(e,n,{empty:b.Callbacks("once memory").add(function(){b._removeData(e,t+"queue"),b._removeData(e,n)})})}}),b.fn.extend({queue:function(e,n){var r=2;return"string"!=typeof e&&(n=e,e="fx",r--),r>arguments.length?b.queue(this[0],e):n===t?this:this.each(function(){var t=b.queue(this,e,n);b._queueHooks(this,e),"fx"===e&&"inprogress"!==t[0]&&b.dequeue(this,e)})},dequeue:function(e){return this.each(function(){b.dequeue(this,e)})},delay:function(e,t){return e=b.fx?b.fx.speeds[e]||e:e,t=t||"fx",this.queue(t,function(t,n){var r=setTimeout(t,e);n.stop=function(){clearTimeout(r)}})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,n){var r,i=1,o=b.Deferred(),a=this,s=this.length,u=function(){--i||o.resolveWith(a,[a])};"string"!=typeof e&&(n=e,e=t),e=e||"fx";while(s--)r=b._data(a[s],e+"queueHooks"),r&&r.empty&&(i++,r.empty.add(u));return u(),o.promise(n)}});var I,z,X=/[\t\r\n]/g,U=/\r/g,V=/^(?:input|select|textarea|button|object)$/i,Y=/^(?:a|area)$/i,J=/^(?:checked|selected|autofocus|autoplay|async|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped)$/i,G=/^(?:checked|selected)$/i,Q=b.support.getSetAttribute,K=b.support.input;b.fn.extend({attr:function(e,t){return b.access(this,b.attr,e,t,arguments.length>1)},removeAttr:function(e){return this.each(function(){b.removeAttr(this,e)})},prop:function(e,t){return b.access(this,b.prop,e,t,arguments.length>1)},removeProp:function(e){return e=b.propFix[e]||e,this.each(function(){try{this[e]=t,delete this[e]}catch(n){}})},addClass:function(e){var t,n,r,i,o,a=0,s=this.length,u="string"==typeof e&&e;if(b.isFunction(e))return this.each(function(t){b(this).addClass(e.call(this,t,this.className))});if(u)for(t=(e||"").match(w)||[];s>a;a++)if(n=this[a],r=1===n.nodeType&&(n.className?(" "+n.className+" ").replace(X," "):" ")){o=0;while(i=t[o++])0>r.indexOf(" "+i+" ")&&(r+=i+" ");


n.className=b.trim(r)}return this},removeClass:function(e){var t,n,r,i,o,a=0,s=this.length,u=0===arguments.length||"string"==typeof e&&e;if(b.isFunction(e))return this.each(function(t){b(this).removeClass(e.call(this,t,this.className))});if(u)for(t=(e||"").match(w)||[];s>a;a++)if(n=this[a],r=1===n.nodeType&&(n.className?(" "+n.className+" ").replace(X," "):"")){o=0;while(i=t[o++])while(r.indexOf(" "+i+" ")>=0)r=r.replace(" "+i+" "," ");n.className=e?b.trim(r):""}return this},toggleClass:function(e,t){var n=typeof e,r="boolean"==typeof t;return b.isFunction(e)?this.each(function(n){b(this).toggleClass(e.call(this,n,this.className,t),t)}):this.each(function(){if("string"===n){var o,a=0,s=b(this),u=t,l=e.match(w)||[];while(o=l[a++])u=r?u:!s.hasClass(o),s[u?"addClass":"removeClass"](o)}else(n===i||"boolean"===n)&&(this.className&&b._data(this,"__className__",this.className),this.className=this.className||e===!1?"":b._data(this,"__className__")||"")})},hasClass:function(e){var t=" "+e+" ",n=0,r=this.length;for(;r>n;n++)if(1===this[n].nodeType&&(" "+this[n].className+" ").replace(X," ").indexOf(t)>=0)return!0;return!1},val:function(e){var n,r,i,o=this[0];{if(arguments.length)return i=b.isFunction(e),this.each(function(n){var o,a=b(this);1===this.nodeType&&(o=i?e.call(this,n,a.val()):e,null==o?o="":"number"==typeof o?o+="":b.isArray(o)&&(o=b.map(o,function(e){return null==e?"":e+""})),r=b.valHooks[this.type]||b.valHooks[this.nodeName.toLowerCase()],r&&"set"in r&&r.set(this,o,"value")!==t||(this.value=o))});if(o)return r=b.valHooks[o.type]||b.valHooks[o.nodeName.toLowerCase()],r&&"get"in r&&(n=r.get(o,"value"))!==t?n:(n=o.value,"string"==typeof n?n.replace(U,""):null==n?"":n)}}}),b.extend({valHooks:{option:{get:function(e){var t=e.attributes.value;return!t||t.specified?e.value:e.text}},select:{get:function(e){var t,n,r=e.options,i=e.selectedIndex,o="select-one"===e.type||0>i,a=o?null:[],s=o?i+1:r.length,u=0>i?s:o?i:0;for(;s>u;u++)if(n=r[u],!(!n.selected&&u!==i||(b.support.optDisabled?n.disabled:null!==n.getAttribute("disabled"))||n.parentNode.disabled&&b.nodeName(n.parentNode,"optgroup"))){if(t=b(n).val(),o)return t;a.push(t)}return a},set:function(e,t){var n=b.makeArray(t);return b(e).find("option").each(function(){this.selected=b.inArray(b(this).val(),n)>=0}),n.length||(e.selectedIndex=-1),n}}},attr:function(e,n,r){var o,a,s,u=e.nodeType;if(e&&3!==u&&8!==u&&2!==u)return typeof e.getAttribute===i?b.prop(e,n,r):(a=1!==u||!b.isXMLDoc(e),a&&(n=n.toLowerCase(),o=b.attrHooks[n]||(J.test(n)?z:I)),r===t?o&&a&&"get"in o&&null!==(s=o.get(e,n))?s:(typeof e.getAttribute!==i&&(s=e.getAttribute(n)),null==s?t:s):null!==r?o&&a&&"set"in o&&(s=o.set(e,r,n))!==t?s:(e.setAttribute(n,r+""),r):(b.removeAttr(e,n),t))},removeAttr:function(e,t){var n,r,i=0,o=t&&t.match(w);if(o&&1===e.nodeType)while(n=o[i++])r=b.propFix[n]||n,J.test(n)?!Q&&G.test(n)?e[b.camelCase("default-"+n)]=e[r]=!1:e[r]=!1:b.attr(e,n,""),e.removeAttribute(Q?n:r)},attrHooks:{type:{set:function(e,t){if(!b.support.radioValue&&"radio"===t&&b.nodeName(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(e,n,r){var i,o,a,s=e.nodeType;if(e&&3!==s&&8!==s&&2!==s)return a=1!==s||!b.isXMLDoc(e),a&&(n=b.propFix[n]||n,o=b.propHooks[n]),r!==t?o&&"set"in o&&(i=o.set(e,r,n))!==t?i:e[n]=r:o&&"get"in o&&null!==(i=o.get(e,n))?i:e[n]},propHooks:{tabIndex:{get:function(e){var n=e.getAttributeNode("tabindex");return n&&n.specified?parseInt(n.value,10):V.test(e.nodeName)||Y.test(e.nodeName)&&e.href?0:t}}}}),z={get:function(e,n){var r=b.prop(e,n),i="boolean"==typeof r&&e.getAttribute(n),o="boolean"==typeof r?K&&Q?null!=i:G.test(n)?e[b.camelCase("default-"+n)]:!!i:e.getAttributeNode(n);return o&&o.value!==!1?n.toLowerCase():t},set:function(e,t,n){return t===!1?b.removeAttr(e,n):K&&Q||!G.test(n)?e.setAttribute(!Q&&b.propFix[n]||n,n):e[b.camelCase("default-"+n)]=e[n]=!0,n}},K&&Q||(b.attrHooks.value={get:function(e,n){var r=e.getAttributeNode(n);return b.nodeName(e,"input")?e.defaultValue:r&&r.specified?r.value:t},set:function(e,n,r){return b.nodeName(e,"input")?(e.defaultValue=n,t):I&&I.set(e,n,r)}}),Q||(I=b.valHooks.button={get:function(e,n){var r=e.getAttributeNode(n);return r&&("id"===n||"name"===n||"coords"===n?""!==r.value:r.specified)?r.value:t},set:function(e,n,r){var i=e.getAttributeNode(r);return i||e.setAttributeNode(i=e.ownerDocument.createAttribute(r)),i.value=n+="","value"===r||n===e.getAttribute(r)?n:t}},b.attrHooks.contenteditable={get:I.get,set:function(e,t,n){I.set(e,""===t?!1:t,n)}},b.each(["width","height"],function(e,n){b.attrHooks[n]=b.extend(b.attrHooks[n],{set:function(e,r){return""===r?(e.setAttribute(n,"auto"),r):t}})})),b.support.hrefNormalized||(b.each(["href","src","width","height"],function(e,n){b.attrHooks[n]=b.extend(b.attrHooks[n],{get:function(e){var r=e.getAttribute(n,2);return null==r?t:r}})}),b.each(["href","src"],function(e,t){b.propHooks[t]={get:function(e){return e.getAttribute(t,4)}}})),b.support.style||(b.attrHooks.style={get:function(e){return e.style.cssText||t},set:function(e,t){return e.style.cssText=t+""}}),b.support.optSelected||(b.propHooks.selected=b.extend(b.propHooks.selected,{get:function(e){var t=e.parentNode;return t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex),null}})),b.support.enctype||(b.propFix.enctype="encoding"),b.support.checkOn||b.each(["radio","checkbox"],function(){b.valHooks[this]={get:function(e){return null===e.getAttribute("value")?"on":e.value}}}),b.each(["radio","checkbox"],function(){b.valHooks[this]=b.extend(b.valHooks[this],{set:function(e,n){return b.isArray(n)?e.checked=b.inArray(b(e).val(),n)>=0:t}})});var Z=/^(?:input|select|textarea)$/i,et=/^key/,tt=/^(?:mouse|contextmenu)|click/,nt=/^(?:focusinfocus|focusoutblur)$/,rt=/^([^.]*)(?:\.(.+)|)$/;function it(){return!0}function ot(){return!1}b.event={global:{},add:function(e,n,r,o,a){var s,u,l,c,p,f,d,h,g,m,y,v=b._data(e);if(v){r.handler&&(c=r,r=c.handler,a=c.selector),r.guid||(r.guid=b.guid++),(u=v.events)||(u=v.events={}),(f=v.handle)||(f=v.handle=function(e){return typeof b===i||e&&b.event.triggered===e.type?t:b.event.dispatch.apply(f.elem,arguments)},f.elem=e),n=(n||"").match(w)||[""],l=n.length;while(l--)s=rt.exec(n[l])||[],g=y=s[1],m=(s[2]||"").split(".").sort(),p=b.event.special[g]||{},g=(a?p.delegateType:p.bindType)||g,p=b.event.special[g]||{},d=b.extend({type:g,origType:y,data:o,handler:r,guid:r.guid,selector:a,needsContext:a&&b.expr.match.needsContext.test(a),namespace:m.join(".")},c),(h=u[g])||(h=u[g]=[],h.delegateCount=0,p.setup&&p.setup.call(e,o,m,f)!==!1||(e.addEventListener?e.addEventListener(g,f,!1):e.attachEvent&&e.attachEvent("on"+g,f))),p.add&&(p.add.call(e,d),d.handler.guid||(d.handler.guid=r.guid)),a?h.splice(h.delegateCount++,0,d):h.push(d),b.event.global[g]=!0;e=null}},remove:function(e,t,n,r,i){var o,a,s,u,l,c,p,f,d,h,g,m=b.hasData(e)&&b._data(e);if(m&&(c=m.events)){t=(t||"").match(w)||[""],l=t.length;while(l--)if(s=rt.exec(t[l])||[],d=g=s[1],h=(s[2]||"").split(".").sort(),d){p=b.event.special[d]||{},d=(r?p.delegateType:p.bindType)||d,f=c[d]||[],s=s[2]&&RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"),u=o=f.length;while(o--)a=f[o],!i&&g!==a.origType||n&&n.guid!==a.guid||s&&!s.test(a.namespace)||r&&r!==a.selector&&("**"!==r||!a.selector)||(f.splice(o,1),a.selector&&f.delegateCount--,p.remove&&p.remove.call(e,a));u&&!f.length&&(p.teardown&&p.teardown.call(e,h,m.handle)!==!1||b.removeEvent(e,d,m.handle),delete c[d])}else for(d in c)b.event.remove(e,d+t[l],n,r,!0);b.isEmptyObject(c)&&(delete m.handle,b._removeData(e,"events"))}},trigger:function(n,r,i,a){var s,u,l,c,p,f,d,h=[i||o],g=y.call(n,"type")?n.type:n,m=y.call(n,"namespace")?n.namespace.split("."):[];if(l=f=i=i||o,3!==i.nodeType&&8!==i.nodeType&&!nt.test(g+b.event.triggered)&&(g.indexOf(".")>=0&&(m=g.split("."),g=m.shift(),m.sort()),u=0>g.indexOf(":")&&"on"+g,n=n[b.expando]?n:new b.Event(g,"object"==typeof n&&n),n.isTrigger=!0,n.namespace=m.join("."),n.namespace_re=n.namespace?RegExp("(^|\\.)"+m.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,n.result=t,n.target||(n.target=i),r=null==r?[n]:b.makeArray(r,[n]),p=b.event.special[g]||{},a||!p.trigger||p.trigger.apply(i,r)!==!1)){if(!a&&!p.noBubble&&!b.isWindow(i)){for(c=p.delegateType||g,nt.test(c+g)||(l=l.parentNode);l;l=l.parentNode)h.push(l),f=l;f===(i.ownerDocument||o)&&h.push(f.defaultView||f.parentWindow||e)}d=0;while((l=h[d++])&&!n.isPropagationStopped())n.type=d>1?c:p.bindType||g,s=(b._data(l,"events")||{})[n.type]&&b._data(l,"handle"),s&&s.apply(l,r),s=u&&l[u],s&&b.acceptData(l)&&s.apply&&s.apply(l,r)===!1&&n.preventDefault();if(n.type=g,!(a||n.isDefaultPrevented()||p._default&&p._default.apply(i.ownerDocument,r)!==!1||"click"===g&&b.nodeName(i,"a")||!b.acceptData(i)||!u||!i[g]||b.isWindow(i))){f=i[u],f&&(i[u]=null),b.event.triggered=g;try{i[g]()}catch(v){}b.event.triggered=t,f&&(i[u]=f)}return n.result}},dispatch:function(e){e=b.event.fix(e);var n,r,i,o,a,s=[],u=h.call(arguments),l=(b._data(this,"events")||{})[e.type]||[],c=b.event.special[e.type]||{};if(u[0]=e,e.delegateTarget=this,!c.preDispatch||c.preDispatch.call(this,e)!==!1){s=b.event.handlers.call(this,e,l),n=0;while((o=s[n++])&&!e.isPropagationStopped()){e.currentTarget=o.elem,a=0;while((i=o.handlers[a++])&&!e.isImmediatePropagationStopped())(!e.namespace_re||e.namespace_re.test(i.namespace))&&(e.handleObj=i,e.data=i.data,r=((b.event.special[i.origType]||{}).handle||i.handler).apply(o.elem,u),r!==t&&(e.result=r)===!1&&(e.preventDefault(),e.stopPropagation()))}return c.postDispatch&&c.postDispatch.call(this,e),e.result}},handlers:function(e,n){var r,i,o,a,s=[],u=n.delegateCount,l=e.target;if(u&&l.nodeType&&(!e.button||"click"!==e.type))for(;l!=this;l=l.parentNode||this)if(1===l.nodeType&&(l.disabled!==!0||"click"!==e.type)){for(o=[],a=0;u>a;a++)i=n[a],r=i.selector+" ",o[r]===t&&(o[r]=i.needsContext?b(r,this).index(l)>=0:b.find(r,this,null,[l]).length),o[r]&&o.push(i);o.length&&s.push({elem:l,handlers:o})}return n.length>u&&s.push({elem:this,handlers:n.slice(u)}),s},fix:function(e){if(e[b.expando])return e;var t,n,r,i=e.type,a=e,s=this.fixHooks[i];s||(this.fixHooks[i]=s=tt.test(i)?this.mouseHooks:et.test(i)?this.keyHooks:{}),r=s.props?this.props.concat(s.props):this.props,e=new b.Event(a),t=r.length;while(t--)n=r[t],e[n]=a[n];return e.target||(e.target=a.srcElement||o),3===e.target.nodeType&&(e.target=e.target.parentNode),e.metaKey=!!e.metaKey,s.filter?s.filter(e,a):e},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(e,t){return null==e.which&&(e.which=null!=t.charCode?t.charCode:t.keyCode),e}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(e,n){var r,i,a,s=n.button,u=n.fromElement;return null==e.pageX&&null!=n.clientX&&(i=e.target.ownerDocument||o,a=i.documentElement,r=i.body,e.pageX=n.clientX+(a&&a.scrollLeft||r&&r.scrollLeft||0)-(a&&a.clientLeft||r&&r.clientLeft||0),e.pageY=n.clientY+(a&&a.scrollTop||r&&r.scrollTop||0)-(a&&a.clientTop||r&&r.clientTop||0)),!e.relatedTarget&&u&&(e.relatedTarget=u===e.target?n.toElement:u),e.which||s===t||(e.which=1&s?1:2&s?3:4&s?2:0),e}},special:{load:{noBubble:!0},click:{trigger:function(){return b.nodeName(this,"input")&&"checkbox"===this.type&&this.click?(this.click(),!1):t}},focus:{trigger:function(){if(this!==o.activeElement&&this.focus)try{return this.focus(),!1}catch(e){}},delegateType:"focusin"},blur:{trigger:function(){return this===o.activeElement&&this.blur?(this.blur(),!1):t},delegateType:"focusout"},beforeunload:{postDispatch:function(e){e.result!==t&&(e.originalEvent.returnValue=e.result)}}},simulate:function(e,t,n,r){var i=b.extend(new b.Event,n,{type:e,isSimulated:!0,originalEvent:{}});r?b.event.trigger(i,null,t):b.event.dispatch.call(t,i),i.isDefaultPrevented()&&n.preventDefault()}},b.removeEvent=o.removeEventListener?function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n,!1)}:function(e,t,n){var r="on"+t;e.detachEvent&&(typeof e[r]===i&&(e[r]=null),e.detachEvent(r,n))},b.Event=function(e,n){return this instanceof b.Event?(e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||e.returnValue===!1||e.getPreventDefault&&e.getPreventDefault()?it:ot):this.type=e,n&&b.extend(this,n),this.timeStamp=e&&e.timeStamp||b.now(),this[b.expando]=!0,t):new b.Event(e,n)},b.Event.prototype={isDefaultPrevented:ot,isPropagationStopped:ot,isImmediatePropagationStopped:ot,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=it,e&&(e.preventDefault?e.preventDefault():e.returnValue=!1)},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=it,e&&(e.stopPropagation&&e.stopPropagation(),e.cancelBubble=!0)},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=it,this.stopPropagation()}},b.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(e,t){b.event.special[e]={delegateType:t,bindType:t,handle:function(e){var n,r=this,i=e.relatedTarget,o=e.handleObj;
return(!i||i!==r&&!b.contains(r,i))&&(e.type=o.origType,n=o.handler.apply(this,arguments),e.type=t),n}}}),b.support.submitBubbles||(b.event.special.submit={setup:function(){return b.nodeName(this,"form")?!1:(b.event.add(this,"click._submit keypress._submit",function(e){var n=e.target,r=b.nodeName(n,"input")||b.nodeName(n,"button")?n.form:t;r&&!b._data(r,"submitBubbles")&&(b.event.add(r,"submit._submit",function(e){e._submit_bubble=!0}),b._data(r,"submitBubbles",!0))}),t)},postDispatch:function(e){e._submit_bubble&&(delete e._submit_bubble,this.parentNode&&!e.isTrigger&&b.event.simulate("submit",this.parentNode,e,!0))},teardown:function(){return b.nodeName(this,"form")?!1:(b.event.remove(this,"._submit"),t)}}),b.support.changeBubbles||(b.event.special.change={setup:function(){return Z.test(this.nodeName)?(("checkbox"===this.type||"radio"===this.type)&&(b.event.add(this,"propertychange._change",function(e){"checked"===e.originalEvent.propertyName&&(this._just_changed=!0)}),b.event.add(this,"click._change",function(e){this._just_changed&&!e.isTrigger&&(this._just_changed=!1),b.event.simulate("change",this,e,!0)})),!1):(b.event.add(this,"beforeactivate._change",function(e){var t=e.target;Z.test(t.nodeName)&&!b._data(t,"changeBubbles")&&(b.event.add(t,"change._change",function(e){!this.parentNode||e.isSimulated||e.isTrigger||b.event.simulate("change",this.parentNode,e,!0)}),b._data(t,"changeBubbles",!0))}),t)},handle:function(e){var n=e.target;return this!==n||e.isSimulated||e.isTrigger||"radio"!==n.type&&"checkbox"!==n.type?e.handleObj.handler.apply(this,arguments):t},teardown:function(){return b.event.remove(this,"._change"),!Z.test(this.nodeName)}}),b.support.focusinBubbles||b.each({focus:"focusin",blur:"focusout"},function(e,t){var n=0,r=function(e){b.event.simulate(t,e.target,b.event.fix(e),!0)};b.event.special[t]={setup:function(){0===n++&&o.addEventListener(e,r,!0)},teardown:function(){0===--n&&o.removeEventListener(e,r,!0)}}}),b.fn.extend({on:function(e,n,r,i,o){var a,s;if("object"==typeof e){"string"!=typeof n&&(r=r||n,n=t);for(a in e)this.on(a,n,r,e[a],o);return this}if(null==r&&null==i?(i=n,r=n=t):null==i&&("string"==typeof n?(i=r,r=t):(i=r,r=n,n=t)),i===!1)i=ot;else if(!i)return this;return 1===o&&(s=i,i=function(e){return b().off(e),s.apply(this,arguments)},i.guid=s.guid||(s.guid=b.guid++)),this.each(function(){b.event.add(this,e,i,r,n)})},one:function(e,t,n,r){return this.on(e,t,n,r,1)},off:function(e,n,r){var i,o;if(e&&e.preventDefault&&e.handleObj)return i=e.handleObj,b(e.delegateTarget).off(i.namespace?i.origType+"."+i.namespace:i.origType,i.selector,i.handler),this;if("object"==typeof e){for(o in e)this.off(o,n,e[o]);return this}return(n===!1||"function"==typeof n)&&(r=n,n=t),r===!1&&(r=ot),this.each(function(){b.event.remove(this,e,r,n)})},bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)},trigger:function(e,t){return this.each(function(){b.event.trigger(e,t,this)})},triggerHandler:function(e,n){var r=this[0];return r?b.event.trigger(e,n,r,!0):t}}),function(e,t){var n,r,i,o,a,s,u,l,c,p,f,d,h,g,m,y,v,x="sizzle"+-new Date,w=e.document,T={},N=0,C=0,k=it(),E=it(),S=it(),A=typeof t,j=1<<31,D=[],L=D.pop,H=D.push,q=D.slice,M=D.indexOf||function(e){var t=0,n=this.length;for(;n>t;t++)if(this[t]===e)return t;return-1},_="[\\x20\\t\\r\\n\\f]",F="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",O=F.replace("w","w#"),B="([*^$|!~]?=)",P="\\["+_+"*("+F+")"+_+"*(?:"+B+_+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+O+")|)|)"+_+"*\\]",R=":("+F+")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|"+P.replace(3,8)+")*)|.*)\\)|)",W=RegExp("^"+_+"+|((?:^|[^\\\\])(?:\\\\.)*)"+_+"+$","g"),$=RegExp("^"+_+"*,"+_+"*"),I=RegExp("^"+_+"*([\\x20\\t\\r\\n\\f>+~])"+_+"*"),z=RegExp(R),X=RegExp("^"+O+"$"),U={ID:RegExp("^#("+F+")"),CLASS:RegExp("^\\.("+F+")"),NAME:RegExp("^\\[name=['\"]?("+F+")['\"]?\\]"),TAG:RegExp("^("+F.replace("w","w*")+")"),ATTR:RegExp("^"+P),PSEUDO:RegExp("^"+R),CHILD:RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+_+"*(even|odd|(([+-]|)(\\d*)n|)"+_+"*(?:([+-]|)"+_+"*(\\d+)|))"+_+"*\\)|)","i"),needsContext:RegExp("^"+_+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+_+"*((?:-\\d)?\\d*)"+_+"*\\)|)(?=[^-]|$)","i")},V=/[\x20\t\r\n\f]*[+~]/,Y=/^[^{]+\{\s*\[native code/,J=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,G=/^(?:input|select|textarea|button)$/i,Q=/^h\d$/i,K=/'|\\/g,Z=/\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,et=/\\([\da-fA-F]{1,6}[\x20\t\r\n\f]?|.)/g,tt=function(e,t){var n="0x"+t-65536;return n!==n?t:0>n?String.fromCharCode(n+65536):String.fromCharCode(55296|n>>10,56320|1023&n)};try{q.call(w.documentElement.childNodes,0)[0].nodeType}catch(nt){q=function(e){var t,n=[];while(t=this[e++])n.push(t);return n}}function rt(e){return Y.test(e+"")}function it(){var e,t=[];return e=function(n,r){return t.push(n+=" ")>i.cacheLength&&delete e[t.shift()],e[n]=r}}function ot(e){return e[x]=!0,e}function at(e){var t=p.createElement("div");try{return e(t)}catch(n){return!1}finally{t=null}}function st(e,t,n,r){var i,o,a,s,u,l,f,g,m,v;if((t?t.ownerDocument||t:w)!==p&&c(t),t=t||p,n=n||[],!e||"string"!=typeof e)return n;if(1!==(s=t.nodeType)&&9!==s)return[];if(!d&&!r){if(i=J.exec(e))if(a=i[1]){if(9===s){if(o=t.getElementById(a),!o||!o.parentNode)return n;if(o.id===a)return n.push(o),n}else if(t.ownerDocument&&(o=t.ownerDocument.getElementById(a))&&y(t,o)&&o.id===a)return n.push(o),n}else{if(i[2])return H.apply(n,q.call(t.getElementsByTagName(e),0)),n;if((a=i[3])&&T.getByClassName&&t.getElementsByClassName)return H.apply(n,q.call(t.getElementsByClassName(a),0)),n}if(T.qsa&&!h.test(e)){if(f=!0,g=x,m=t,v=9===s&&e,1===s&&"object"!==t.nodeName.toLowerCase()){l=ft(e),(f=t.getAttribute("id"))?g=f.replace(K,"\\$&"):t.setAttribute("id",g),g="[id='"+g+"'] ",u=l.length;while(u--)l[u]=g+dt(l[u]);m=V.test(e)&&t.parentNode||t,v=l.join(",")}if(v)try{return H.apply(n,q.call(m.querySelectorAll(v),0)),n}catch(b){}finally{f||t.removeAttribute("id")}}}return wt(e.replace(W,"$1"),t,n,r)}a=st.isXML=function(e){var t=e&&(e.ownerDocument||e).documentElement;return t?"HTML"!==t.nodeName:!1},c=st.setDocument=function(e){var n=e?e.ownerDocument||e:w;return n!==p&&9===n.nodeType&&n.documentElement?(p=n,f=n.documentElement,d=a(n),T.tagNameNoComments=at(function(e){return e.appendChild(n.createComment("")),!e.getElementsByTagName("*").length}),T.attributes=at(function(e){e.innerHTML="<select></select>";var t=typeof e.lastChild.getAttribute("multiple");return"boolean"!==t&&"string"!==t}),T.getByClassName=at(function(e){return e.innerHTML="<div class='hidden e'></div><div class='hidden'></div>",e.getElementsByClassName&&e.getElementsByClassName("e").length?(e.lastChild.className="e",2===e.getElementsByClassName("e").length):!1}),T.getByName=at(function(e){e.id=x+0,e.innerHTML="<a name='"+x+"'></a><div name='"+x+"'></div>",f.insertBefore(e,f.firstChild);var t=n.getElementsByName&&n.getElementsByName(x).length===2+n.getElementsByName(x+0).length;return T.getIdNotName=!n.getElementById(x),f.removeChild(e),t}),i.attrHandle=at(function(e){return e.innerHTML="<a href='#'></a>",e.firstChild&&typeof e.firstChild.getAttribute!==A&&"#"===e.firstChild.getAttribute("href")})?{}:{href:function(e){return e.getAttribute("href",2)},type:function(e){return e.getAttribute("type")}},T.getIdNotName?(i.find.ID=function(e,t){if(typeof t.getElementById!==A&&!d){var n=t.getElementById(e);return n&&n.parentNode?[n]:[]}},i.filter.ID=function(e){var t=e.replace(et,tt);return function(e){return e.getAttribute("id")===t}}):(i.find.ID=function(e,n){if(typeof n.getElementById!==A&&!d){var r=n.getElementById(e);return r?r.id===e||typeof r.getAttributeNode!==A&&r.getAttributeNode("id").value===e?[r]:t:[]}},i.filter.ID=function(e){var t=e.replace(et,tt);return function(e){var n=typeof e.getAttributeNode!==A&&e.getAttributeNode("id");return n&&n.value===t}}),i.find.TAG=T.tagNameNoComments?function(e,n){return typeof n.getElementsByTagName!==A?n.getElementsByTagName(e):t}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){while(n=o[i++])1===n.nodeType&&r.push(n);return r}return o},i.find.NAME=T.getByName&&function(e,n){return typeof n.getElementsByName!==A?n.getElementsByName(name):t},i.find.CLASS=T.getByClassName&&function(e,n){return typeof n.getElementsByClassName===A||d?t:n.getElementsByClassName(e)},g=[],h=[":focus"],(T.qsa=rt(n.querySelectorAll))&&(at(function(e){e.innerHTML="<select><option selected=''></option></select>",e.querySelectorAll("[selected]").length||h.push("\\["+_+"*(?:checked|disabled|ismap|multiple|readonly|selected|value)"),e.querySelectorAll(":checked").length||h.push(":checked")}),at(function(e){e.innerHTML="<input type='hidden' i=''/>",e.querySelectorAll("[i^='']").length&&h.push("[*^$]="+_+"*(?:\"\"|'')"),e.querySelectorAll(":enabled").length||h.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),h.push(",.*:")})),(T.matchesSelector=rt(m=f.matchesSelector||f.mozMatchesSelector||f.webkitMatchesSelector||f.oMatchesSelector||f.msMatchesSelector))&&at(function(e){T.disconnectedMatch=m.call(e,"div"),m.call(e,"[s!='']:x"),g.push("!=",R)}),h=RegExp(h.join("|")),g=RegExp(g.join("|")),y=rt(f.contains)||f.compareDocumentPosition?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)while(t=t.parentNode)if(t===e)return!0;return!1},v=f.compareDocumentPosition?function(e,t){var r;return e===t?(u=!0,0):(r=t.compareDocumentPosition&&e.compareDocumentPosition&&e.compareDocumentPosition(t))?1&r||e.parentNode&&11===e.parentNode.nodeType?e===n||y(w,e)?-1:t===n||y(w,t)?1:0:4&r?-1:1:e.compareDocumentPosition?-1:1}:function(e,t){var r,i=0,o=e.parentNode,a=t.parentNode,s=[e],l=[t];if(e===t)return u=!0,0;if(!o||!a)return e===n?-1:t===n?1:o?-1:a?1:0;if(o===a)return ut(e,t);r=e;while(r=r.parentNode)s.unshift(r);r=t;while(r=r.parentNode)l.unshift(r);while(s[i]===l[i])i++;return i?ut(s[i],l[i]):s[i]===w?-1:l[i]===w?1:0},u=!1,[0,0].sort(v),T.detectDuplicates=u,p):p},st.matches=function(e,t){return st(e,null,null,t)},st.matchesSelector=function(e,t){if((e.ownerDocument||e)!==p&&c(e),t=t.replace(Z,"='$1']"),!(!T.matchesSelector||d||g&&g.test(t)||h.test(t)))try{var n=m.call(e,t);if(n||T.disconnectedMatch||e.document&&11!==e.document.nodeType)return n}catch(r){}return st(t,p,null,[e]).length>0},st.contains=function(e,t){return(e.ownerDocument||e)!==p&&c(e),y(e,t)},st.attr=function(e,t){var n;return(e.ownerDocument||e)!==p&&c(e),d||(t=t.toLowerCase()),(n=i.attrHandle[t])?n(e):d||T.attributes?e.getAttribute(t):((n=e.getAttributeNode(t))||e.getAttribute(t))&&e[t]===!0?t:n&&n.specified?n.value:null},st.error=function(e){throw Error("Syntax error, unrecognized expression: "+e)},st.uniqueSort=function(e){var t,n=[],r=1,i=0;if(u=!T.detectDuplicates,e.sort(v),u){for(;t=e[r];r++)t===e[r-1]&&(i=n.push(r));while(i--)e.splice(n[i],1)}return e};function ut(e,t){var n=t&&e,r=n&&(~t.sourceIndex||j)-(~e.sourceIndex||j);if(r)return r;if(n)while(n=n.nextSibling)if(n===t)return-1;return e?1:-1}function lt(e){return function(t){var n=t.nodeName.toLowerCase();return"input"===n&&t.type===e}}function ct(e){return function(t){var n=t.nodeName.toLowerCase();return("input"===n||"button"===n)&&t.type===e}}function pt(e){return ot(function(t){return t=+t,ot(function(n,r){var i,o=e([],n.length,t),a=o.length;while(a--)n[i=o[a]]&&(n[i]=!(r[i]=n[i]))})})}o=st.getText=function(e){var t,n="",r=0,i=e.nodeType;if(i){if(1===i||9===i||11===i){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=o(e)}else if(3===i||4===i)return e.nodeValue}else for(;t=e[r];r++)n+=o(t);return n},i=st.selectors={cacheLength:50,createPseudo:ot,match:U,find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(et,tt),e[3]=(e[4]||e[5]||"").replace(et,tt),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||st.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&st.error(e[0]),e},PSEUDO:function(e){var t,n=!e[5]&&e[2];return U.CHILD.test(e[0])?null:(e[4]?e[2]=e[4]:n&&z.test(n)&&(t=ft(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){return"*"===e?function(){return!0}:(e=e.replace(et,tt).toLowerCase(),function(t){return t.nodeName&&t.nodeName.toLowerCase()===e})},CLASS:function(e){var t=k[e+" "];return t||(t=RegExp("(^|"+_+")"+e+"("+_+"|$)"))&&k(e,function(e){return t.test(e.className||typeof e.getAttribute!==A&&e.getAttribute("class")||"")})},ATTR:function(e,t,n){return function(r){var i=st.attr(r,e);return null==i?"!="===t:t?(i+="","="===t?i===n:"!="===t?i!==n:"^="===t?n&&0===i.indexOf(n):"*="===t?n&&i.indexOf(n)>-1:"$="===t?n&&i.slice(-n.length)===n:"~="===t?(" "+i+" ").indexOf(n)>-1:"|="===t?i===n||i.slice(0,n.length+1)===n+"-":!1):!0}},CHILD:function(e,t,n,r,i){var o="nth"!==e.slice(0,3),a="last"!==e.slice(-4),s="of-type"===t;return 1===r&&0===i?function(e){return!!e.parentNode}:function(t,n,u){var l,c,p,f,d,h,g=o!==a?"nextSibling":"previousSibling",m=t.parentNode,y=s&&t.nodeName.toLowerCase(),v=!u&&!s;if(m){if(o){while(g){p=t;while(p=p[g])if(s?p.nodeName.toLowerCase()===y:1===p.nodeType)return!1;h=g="only"===e&&!h&&"nextSibling"}return!0}if(h=[a?m.firstChild:m.lastChild],a&&v){c=m[x]||(m[x]={}),l=c[e]||[],d=l[0]===N&&l[1],f=l[0]===N&&l[2],p=d&&m.childNodes[d];while(p=++d&&p&&p[g]||(f=d=0)||h.pop())if(1===p.nodeType&&++f&&p===t){c[e]=[N,d,f];break}}else if(v&&(l=(t[x]||(t[x]={}))[e])&&l[0]===N)f=l[1];else while(p=++d&&p&&p[g]||(f=d=0)||h.pop())if((s?p.nodeName.toLowerCase()===y:1===p.nodeType)&&++f&&(v&&((p[x]||(p[x]={}))[e]=[N,f]),p===t))break;return f-=i,f===r||0===f%r&&f/r>=0}}},PSEUDO:function(e,t){var n,r=i.pseudos[e]||i.setFilters[e.toLowerCase()]||st.error("unsupported pseudo: "+e);return r[x]?r(t):r.length>1?(n=[e,e,"",t],i.setFilters.hasOwnProperty(e.toLowerCase())?ot(function(e,n){var i,o=r(e,t),a=o.length;while(a--)i=M.call(e,o[a]),e[i]=!(n[i]=o[a])}):function(e){return r(e,0,n)}):r}},pseudos:{not:ot(function(e){var t=[],n=[],r=s(e.replace(W,"$1"));return r[x]?ot(function(e,t,n,i){var o,a=r(e,null,i,[]),s=e.length;while(s--)(o=a[s])&&(e[s]=!(t[s]=o))}):function(e,i,o){return t[0]=e,r(t,null,o,n),!n.pop()}}),has:ot(function(e){return function(t){return st(e,t).length>0}}),contains:ot(function(e){return function(t){return(t.textContent||t.innerText||o(t)).indexOf(e)>-1}}),lang:ot(function(e){return X.test(e||"")||st.error("unsupported lang: "+e),e=e.replace(et,tt).toLowerCase(),function(t){var n;do if(n=d?t.getAttribute("xml:lang")||t.getAttribute("lang"):t.lang)return n=n.toLowerCase(),n===e||0===n.indexOf(e+"-");while((t=t.parentNode)&&1===t.nodeType);return!1}}),target:function(t){var n=e.location&&e.location.hash;return n&&n.slice(1)===t.id},root:function(e){return e===f},focus:function(e){return e===p.activeElement&&(!p.hasFocus||p.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:function(e){return e.disabled===!1},disabled:function(e){return e.disabled===!0},checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,e.selected===!0},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeName>"@"||3===e.nodeType||4===e.nodeType)return!1;return!0},parent:function(e){return!i.pseudos.empty(e)},header:function(e){return Q.test(e.nodeName)},input:function(e){return G.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||t.toLowerCase()===e.type)},first:pt(function(){return[0]}),last:pt(function(e,t){return[t-1]}),eq:pt(function(e,t,n){return[0>n?n+t:n]}),even:pt(function(e,t){var n=0;for(;t>n;n+=2)e.push(n);return e}),odd:pt(function(e,t){var n=1;for(;t>n;n+=2)e.push(n);return e}),lt:pt(function(e,t,n){var r=0>n?n+t:n;for(;--r>=0;)e.push(r);return e}),gt:pt(function(e,t,n){var r=0>n?n+t:n;for(;t>++r;)e.push(r);return e})}};for(n in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})i.pseudos[n]=lt(n);for(n in{submit:!0,reset:!0})i.pseudos[n]=ct(n);function ft(e,t){var n,r,o,a,s,u,l,c=E[e+" "];if(c)return t?0:c.slice(0);s=e,u=[],l=i.preFilter;while(s){(!n||(r=$.exec(s)))&&(r&&(s=s.slice(r[0].length)||s),u.push(o=[])),n=!1,(r=I.exec(s))&&(n=r.shift(),o.push({value:n,type:r[0].replace(W," ")}),s=s.slice(n.length));for(a in i.filter)!(r=U[a].exec(s))||l[a]&&!(r=l[a](r))||(n=r.shift(),o.push({value:n,type:a,matches:r}),s=s.slice(n.length));if(!n)break}return t?s.length:s?st.error(e):E(e,u).slice(0)}function dt(e){var t=0,n=e.length,r="";for(;n>t;t++)r+=e[t].value;return r}function ht(e,t,n){var i=t.dir,o=n&&"parentNode"===i,a=C++;return t.first?function(t,n,r){while(t=t[i])if(1===t.nodeType||o)return e(t,n,r)}:function(t,n,s){var u,l,c,p=N+" "+a;if(s){while(t=t[i])if((1===t.nodeType||o)&&e(t,n,s))return!0}else while(t=t[i])if(1===t.nodeType||o)if(c=t[x]||(t[x]={}),(l=c[i])&&l[0]===p){if((u=l[1])===!0||u===r)return u===!0}else if(l=c[i]=[p],l[1]=e(t,n,s)||r,l[1]===!0)return!0}}function gt(e){return e.length>1?function(t,n,r){var i=e.length;while(i--)if(!e[i](t,n,r))return!1;return!0}:e[0]}function mt(e,t,n,r,i){var o,a=[],s=0,u=e.length,l=null!=t;for(;u>s;s++)(o=e[s])&&(!n||n(o,r,i))&&(a.push(o),l&&t.push(s));return a}function yt(e,t,n,r,i,o){return r&&!r[x]&&(r=yt(r)),i&&!i[x]&&(i=yt(i,o)),ot(function(o,a,s,u){var l,c,p,f=[],d=[],h=a.length,g=o||xt(t||"*",s.nodeType?[s]:s,[]),m=!e||!o&&t?g:mt(g,f,e,s,u),y=n?i||(o?e:h||r)?[]:a:m;if(n&&n(m,y,s,u),r){l=mt(y,d),r(l,[],s,u),c=l.length;while(c--)(p=l[c])&&(y[d[c]]=!(m[d[c]]=p))}if(o){if(i||e){if(i){l=[],c=y.length;while(c--)(p=y[c])&&l.push(m[c]=p);i(null,y=[],l,u)}c=y.length;while(c--)(p=y[c])&&(l=i?M.call(o,p):f[c])>-1&&(o[l]=!(a[l]=p))}}else y=mt(y===a?y.splice(h,y.length):y),i?i(null,a,y,u):H.apply(a,y)})}function vt(e){var t,n,r,o=e.length,a=i.relative[e[0].type],s=a||i.relative[" "],u=a?1:0,c=ht(function(e){return e===t},s,!0),p=ht(function(e){return M.call(t,e)>-1},s,!0),f=[function(e,n,r){return!a&&(r||n!==l)||((t=n).nodeType?c(e,n,r):p(e,n,r))}];for(;o>u;u++)if(n=i.relative[e[u].type])f=[ht(gt(f),n)];else{if(n=i.filter[e[u].type].apply(null,e[u].matches),n[x]){for(r=++u;o>r;r++)if(i.relative[e[r].type])break;return yt(u>1&&gt(f),u>1&&dt(e.slice(0,u-1)).replace(W,"$1"),n,r>u&&vt(e.slice(u,r)),o>r&&vt(e=e.slice(r)),o>r&&dt(e))}f.push(n)}return gt(f)}function bt(e,t){var n=0,o=t.length>0,a=e.length>0,s=function(s,u,c,f,d){var h,g,m,y=[],v=0,b="0",x=s&&[],w=null!=d,T=l,C=s||a&&i.find.TAG("*",d&&u.parentNode||u),k=N+=null==T?1:Math.random()||.1;for(w&&(l=u!==p&&u,r=n);null!=(h=C[b]);b++){if(a&&h){g=0;while(m=e[g++])if(m(h,u,c)){f.push(h);break}w&&(N=k,r=++n)}o&&((h=!m&&h)&&v--,s&&x.push(h))}if(v+=b,o&&b!==v){g=0;while(m=t[g++])m(x,y,u,c);if(s){if(v>0)while(b--)x[b]||y[b]||(y[b]=L.call(f));y=mt(y)}H.apply(f,y),w&&!s&&y.length>0&&v+t.length>1&&st.uniqueSort(f)}return w&&(N=k,l=T),x};return o?ot(s):s}s=st.compile=function(e,t){var n,r=[],i=[],o=S[e+" "];if(!o){t||(t=ft(e)),n=t.length;while(n--)o=vt(t[n]),o[x]?r.push(o):i.push(o);o=S(e,bt(i,r))}return o};function xt(e,t,n){var r=0,i=t.length;for(;i>r;r++)st(e,t[r],n);return n}function wt(e,t,n,r){var o,a,u,l,c,p=ft(e);if(!r&&1===p.length){if(a=p[0]=p[0].slice(0),a.length>2&&"ID"===(u=a[0]).type&&9===t.nodeType&&!d&&i.relative[a[1].type]){if(t=i.find.ID(u.matches[0].replace(et,tt),t)[0],!t)return n;e=e.slice(a.shift().value.length)}o=U.needsContext.test(e)?0:a.length;while(o--){if(u=a[o],i.relative[l=u.type])break;if((c=i.find[l])&&(r=c(u.matches[0].replace(et,tt),V.test(a[0].type)&&t.parentNode||t))){if(a.splice(o,1),e=r.length&&dt(a),!e)return H.apply(n,q.call(r,0)),n;break}}}return s(e,p)(r,t,d,n,V.test(e)),n}i.pseudos.nth=i.pseudos.eq;function Tt(){}i.filters=Tt.prototype=i.pseudos,i.setFilters=new Tt,c(),st.attr=b.attr,b.find=st,b.expr=st.selectors,b.expr[":"]=b.expr.pseudos,b.unique=st.uniqueSort,b.text=st.getText,b.isXMLDoc=st.isXML,b.contains=st.contains}(e);var at=/Until$/,st=/^(?:parents|prev(?:Until|All))/,ut=/^.[^:#\[\.,]*$/,lt=b.expr.match.needsContext,ct={children:!0,contents:!0,next:!0,prev:!0};b.fn.extend({find:function(e){var t,n,r,i=this.length;if("string"!=typeof e)return r=this,this.pushStack(b(e).filter(function(){for(t=0;i>t;t++)if(b.contains(r[t],this))return!0}));for(n=[],t=0;i>t;t++)b.find(e,this[t],n);return n=this.pushStack(i>1?b.unique(n):n),n.selector=(this.selector?this.selector+" ":"")+e,n},has:function(e){var t,n=b(e,this),r=n.length;return this.filter(function(){for(t=0;r>t;t++)if(b.contains(this,n[t]))return!0})},not:function(e){return this.pushStack(ft(this,e,!1))},filter:function(e){return this.pushStack(ft(this,e,!0))},is:function(e){return!!e&&("string"==typeof e?lt.test(e)?b(e,this.context).index(this[0])>=0:b.filter(e,this).length>0:this.filter(e).length>0)},closest:function(e,t){var n,r=0,i=this.length,o=[],a=lt.test(e)||"string"!=typeof e?b(e,t||this.context):0;for(;i>r;r++){n=this[r];while(n&&n.ownerDocument&&n!==t&&11!==n.nodeType){if(a?a.index(n)>-1:b.find.matchesSelector(n,e)){o.push(n);break}n=n.parentNode}}return this.pushStack(o.length>1?b.unique(o):o)},index:function(e){return e?"string"==typeof e?b.inArray(this[0],b(e)):b.inArray(e.jquery?e[0]:e,this):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){var n="string"==typeof e?b(e,t):b.makeArray(e&&e.nodeType?[e]:e),r=b.merge(this.get(),n);return this.pushStack(b.unique(r))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}}),b.fn.andSelf=b.fn.addBack;function pt(e,t){do e=e[t];while(e&&1!==e.nodeType);return e}b.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return b.dir(e,"parentNode")},parentsUntil:function(e,t,n){return b.dir(e,"parentNode",n)},next:function(e){return pt(e,"nextSibling")},prev:function(e){return pt(e,"previousSibling")},nextAll:function(e){return b.dir(e,"nextSibling")},prevAll:function(e){return b.dir(e,"previousSibling")},nextUntil:function(e,t,n){return b.dir(e,"nextSibling",n)},prevUntil:function(e,t,n){return b.dir(e,"previousSibling",n)},siblings:function(e){return b.sibling((e.parentNode||{}).firstChild,e)},children:function(e){return b.sibling(e.firstChild)},contents:function(e){return b.nodeName(e,"iframe")?e.contentDocument||e.contentWindow.document:b.merge([],e.childNodes)}},function(e,t){b.fn[e]=function(n,r){var i=b.map(this,t,n);return at.test(e)||(r=n),r&&"string"==typeof r&&(i=b.filter(r,i)),i=this.length>1&&!ct[e]?b.unique(i):i,this.length>1&&st.test(e)&&(i=i.reverse()),this.pushStack(i)}}),b.extend({filter:function(e,t,n){return n&&(e=":not("+e+")"),1===t.length?b.find.matchesSelector(t[0],e)?[t[0]]:[]:b.find.matches(e,t)},dir:function(e,n,r){var i=[],o=e[n];while(o&&9!==o.nodeType&&(r===t||1!==o.nodeType||!b(o).is(r)))1===o.nodeType&&i.push(o),o=o[n];return i},sibling:function(e,t){var n=[];for(;e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n}});function ft(e,t,n){if(t=t||0,b.isFunction(t))return b.grep(e,function(e,r){var i=!!t.call(e,r,e);return i===n});if(t.nodeType)return b.grep(e,function(e){return e===t===n});if("string"==typeof t){var r=b.grep(e,function(e){return 1===e.nodeType});if(ut.test(t))return b.filter(t,r,!n);t=b.filter(t,r)}return b.grep(e,function(e){return b.inArray(e,t)>=0===n})}function dt(e){var t=ht.split("|"),n=e.createDocumentFragment();if(n.createElement)while(t.length)n.createElement(t.pop());return n}var ht="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",gt=/ jQuery\d+="(?:null|\d+)"/g,mt=RegExp("<(?:"+ht+")[\\s/>]","i"),yt=/^\s+/,vt=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,bt=/<([\w:]+)/,xt=/<tbody/i,wt=/<|&#?\w+;/,Tt=/<(?:script|style|link)/i,Nt=/^(?:checkbox|radio)$/i,Ct=/checked\s*(?:[^=]|=\s*.checked.)/i,kt=/^$|\/(?:java|ecma)script/i,Et=/^true\/(.*)/,St=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,At={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],area:[1,"<map>","</map>"],param:[1,"<object>","</object>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:b.support.htmlSerialize?[0,"",""]:[1,"X<div>","</div>"]},jt=dt(o),Dt=jt.appendChild(o.createElement("div"));At.optgroup=At.option,At.tbody=At.tfoot=At.colgroup=At.caption=At.thead,At.th=At.td,b.fn.extend({text:function(e){return b.access(this,function(e){return e===t?b.text(this):this.empty().append((this[0]&&this[0].ownerDocument||o).createTextNode(e))},null,e,arguments.length)},wrapAll:function(e){if(b.isFunction(e))return this.each(function(t){b(this).wrapAll(e.call(this,t))});if(this[0]){var t=b(e,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){var e=this;while(e.firstChild&&1===e.firstChild.nodeType)e=e.firstChild;return e}).append(this)}return this},wrapInner:function(e){return b.isFunction(e)?this.each(function(t){b(this).wrapInner(e.call(this,t))}):this.each(function(){var t=b(this),n=t.contents();n.length?n.wrapAll(e):t.append(e)})},wrap:function(e){var t=b.isFunction(e);return this.each(function(n){b(this).wrapAll(t?e.call(this,n):e)})},unwrap:function(){return this.parent().each(function(){b.nodeName(this,"body")||b(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(e){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&this.appendChild(e)})},prepend:function(){return this.domManip(arguments,!0,function(e){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&this.insertBefore(e,this.firstChild)})},before:function(){return this.domManip(arguments,!1,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return this.domManip(arguments,!1,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},remove:function(e,t){var n,r=0;for(;null!=(n=this[r]);r++)(!e||b.filter(e,[n]).length>0)&&(t||1!==n.nodeType||b.cleanData(Ot(n)),n.parentNode&&(t&&b.contains(n.ownerDocument,n)&&Mt(Ot(n,"script")),n.parentNode.removeChild(n)));return this},empty:function(){var e,t=0;for(;null!=(e=this[t]);t++){1===e.nodeType&&b.cleanData(Ot(e,!1));while(e.firstChild)e.removeChild(e.firstChild);e.options&&b.nodeName(e,"select")&&(e.options.length=0)}return this},clone:function(e,t){return e=null==e?!1:e,t=null==t?e:t,this.map(function(){return b.clone(this,e,t)})},html:function(e){return b.access(this,function(e){var n=this[0]||{},r=0,i=this.length;if(e===t)return 1===n.nodeType?n.innerHTML.replace(gt,""):t;if(!("string"!=typeof e||Tt.test(e)||!b.support.htmlSerialize&&mt.test(e)||!b.support.leadingWhitespace&&yt.test(e)||At[(bt.exec(e)||["",""])[1].toLowerCase()])){e=e.replace(vt,"<$1></$2>");try{for(;i>r;r++)n=this[r]||{},1===n.nodeType&&(b.cleanData(Ot(n,!1)),n.innerHTML=e);n=0}catch(o){}}n&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(e){var t=b.isFunction(e);return t||"string"==typeof e||(e=b(e).not(this).detach()),this.domManip([e],!0,function(e){var t=this.nextSibling,n=this.parentNode;n&&(b(this).remove(),n.insertBefore(e,t))})},detach:function(e){return this.remove(e,!0)},domManip:function(e,n,r){e=f.apply([],e);var i,o,a,s,u,l,c=0,p=this.length,d=this,h=p-1,g=e[0],m=b.isFunction(g);if(m||!(1>=p||"string"!=typeof g||b.support.checkClone)&&Ct.test(g))return this.each(function(i){var o=d.eq(i);m&&(e[0]=g.call(this,i,n?o.html():t)),o.domManip(e,n,r)});if(p&&(l=b.buildFragment(e,this[0].ownerDocument,!1,this),i=l.firstChild,1===l.childNodes.length&&(l=i),i)){for(n=n&&b.nodeName(i,"tr"),s=b.map(Ot(l,"script"),Ht),a=s.length;p>c;c++)o=l,c!==h&&(o=b.clone(o,!0,!0),a&&b.merge(s,Ot(o,"script"))),r.call(n&&b.nodeName(this[c],"table")?Lt(this[c],"tbody"):this[c],o,c);if(a)for(u=s[s.length-1].ownerDocument,b.map(s,qt),c=0;a>c;c++)o=s[c],kt.test(o.type||"")&&!b._data(o,"globalEval")&&b.contains(u,o)&&(o.src?b.ajax({url:o.src,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0}):b.globalEval((o.text||o.textContent||o.innerHTML||"").replace(St,"")));l=i=null}return this}});function Lt(e,t){return e.getElementsByTagName(t)[0]||e.appendChild(e.ownerDocument.createElement(t))}function Ht(e){var t=e.getAttributeNode("type");return e.type=(t&&t.specified)+"/"+e.type,e}function qt(e){var t=Et.exec(e.type);return t?e.type=t[1]:e.removeAttribute("type"),e}function Mt(e,t){var n,r=0;for(;null!=(n=e[r]);r++)b._data(n,"globalEval",!t||b._data(t[r],"globalEval"))}function _t(e,t){if(1===t.nodeType&&b.hasData(e)){var n,r,i,o=b._data(e),a=b._data(t,o),s=o.events;if(s){delete a.handle,a.events={};for(n in s)for(r=0,i=s[n].length;i>r;r++)b.event.add(t,n,s[n][r])}a.data&&(a.data=b.extend({},a.data))}}function Ft(e,t){var n,r,i;if(1===t.nodeType){if(n=t.nodeName.toLowerCase(),!b.support.noCloneEvent&&t[b.expando]){i=b._data(t);for(r in i.events)b.removeEvent(t,r,i.handle);t.removeAttribute(b.expando)}"script"===n&&t.text!==e.text?(Ht(t).text=e.text,qt(t)):"object"===n?(t.parentNode&&(t.outerHTML=e.outerHTML),b.support.html5Clone&&e.innerHTML&&!b.trim(t.innerHTML)&&(t.innerHTML=e.innerHTML)):"input"===n&&Nt.test(e.type)?(t.defaultChecked=t.checked=e.checked,t.value!==e.value&&(t.value=e.value)):"option"===n?t.defaultSelected=t.selected=e.defaultSelected:("input"===n||"textarea"===n)&&(t.defaultValue=e.defaultValue)}}b.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,t){b.fn[e]=function(e){var n,r=0,i=[],o=b(e),a=o.length-1;for(;a>=r;r++)n=r===a?this:this.clone(!0),b(o[r])[t](n),d.apply(i,n.get());return this.pushStack(i)}});function Ot(e,n){var r,o,a=0,s=typeof e.getElementsByTagName!==i?e.getElementsByTagName(n||"*"):typeof e.querySelectorAll!==i?e.querySelectorAll(n||"*"):t;if(!s)for(s=[],r=e.childNodes||e;null!=(o=r[a]);a++)!n||b.nodeName(o,n)?s.push(o):b.merge(s,Ot(o,n));return n===t||n&&b.nodeName(e,n)?b.merge([e],s):s}function Bt(e){Nt.test(e.type)&&(e.defaultChecked=e.checked)}b.extend({clone:function(e,t,n){var r,i,o,a,s,u=b.contains(e.ownerDocument,e);if(b.support.html5Clone||b.isXMLDoc(e)||!mt.test("<"+e.nodeName+">")?o=e.cloneNode(!0):(Dt.innerHTML=e.outerHTML,Dt.removeChild(o=Dt.firstChild)),!(b.support.noCloneEvent&&b.support.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||b.isXMLDoc(e)))for(r=Ot(o),s=Ot(e),a=0;null!=(i=s[a]);++a)r[a]&&Ft(i,r[a]);if(t)if(n)for(s=s||Ot(e),r=r||Ot(o),a=0;null!=(i=s[a]);a++)_t(i,r[a]);else _t(e,o);return r=Ot(o,"script"),r.length>0&&Mt(r,!u&&Ot(e,"script")),r=s=i=null,o},buildFragment:function(e,t,n,r){var i,o,a,s,u,l,c,p=e.length,f=dt(t),d=[],h=0;for(;p>h;h++)if(o=e[h],o||0===o)if("object"===b.type(o))b.merge(d,o.nodeType?[o]:o);else if(wt.test(o)){s=s||f.appendChild(t.createElement("div")),u=(bt.exec(o)||["",""])[1].toLowerCase(),c=At[u]||At._default,s.innerHTML=c[1]+o.replace(vt,"<$1></$2>")+c[2],i=c[0];while(i--)s=s.lastChild;if(!b.support.leadingWhitespace&&yt.test(o)&&d.push(t.createTextNode(yt.exec(o)[0])),!b.support.tbody){o="table"!==u||xt.test(o)?"<table>"!==c[1]||xt.test(o)?0:s:s.firstChild,i=o&&o.childNodes.length;while(i--)b.nodeName(l=o.childNodes[i],"tbody")&&!l.childNodes.length&&o.removeChild(l)
}b.merge(d,s.childNodes),s.textContent="";while(s.firstChild)s.removeChild(s.firstChild);s=f.lastChild}else d.push(t.createTextNode(o));s&&f.removeChild(s),b.support.appendChecked||b.grep(Ot(d,"input"),Bt),h=0;while(o=d[h++])if((!r||-1===b.inArray(o,r))&&(a=b.contains(o.ownerDocument,o),s=Ot(f.appendChild(o),"script"),a&&Mt(s),n)){i=0;while(o=s[i++])kt.test(o.type||"")&&n.push(o)}return s=null,f},cleanData:function(e,t){var n,r,o,a,s=0,u=b.expando,l=b.cache,p=b.support.deleteExpando,f=b.event.special;for(;null!=(n=e[s]);s++)if((t||b.acceptData(n))&&(o=n[u],a=o&&l[o])){if(a.events)for(r in a.events)f[r]?b.event.remove(n,r):b.removeEvent(n,r,a.handle);l[o]&&(delete l[o],p?delete n[u]:typeof n.removeAttribute!==i?n.removeAttribute(u):n[u]=null,c.push(o))}}});var Pt,Rt,Wt,$t=/alpha\([^)]*\)/i,It=/opacity\s*=\s*([^)]*)/,zt=/^(top|right|bottom|left)$/,Xt=/^(none|table(?!-c[ea]).+)/,Ut=/^margin/,Vt=RegExp("^("+x+")(.*)$","i"),Yt=RegExp("^("+x+")(?!px)[a-z%]+$","i"),Jt=RegExp("^([+-])=("+x+")","i"),Gt={BODY:"block"},Qt={position:"absolute",visibility:"hidden",display:"block"},Kt={letterSpacing:0,fontWeight:400},Zt=["Top","Right","Bottom","Left"],en=["Webkit","O","Moz","ms"];function tn(e,t){if(t in e)return t;var n=t.charAt(0).toUpperCase()+t.slice(1),r=t,i=en.length;while(i--)if(t=en[i]+n,t in e)return t;return r}function nn(e,t){return e=t||e,"none"===b.css(e,"display")||!b.contains(e.ownerDocument,e)}function rn(e,t){var n,r,i,o=[],a=0,s=e.length;for(;s>a;a++)r=e[a],r.style&&(o[a]=b._data(r,"olddisplay"),n=r.style.display,t?(o[a]||"none"!==n||(r.style.display=""),""===r.style.display&&nn(r)&&(o[a]=b._data(r,"olddisplay",un(r.nodeName)))):o[a]||(i=nn(r),(n&&"none"!==n||!i)&&b._data(r,"olddisplay",i?n:b.css(r,"display"))));for(a=0;s>a;a++)r=e[a],r.style&&(t&&"none"!==r.style.display&&""!==r.style.display||(r.style.display=t?o[a]||"":"none"));return e}b.fn.extend({css:function(e,n){return b.access(this,function(e,n,r){var i,o,a={},s=0;if(b.isArray(n)){for(o=Rt(e),i=n.length;i>s;s++)a[n[s]]=b.css(e,n[s],!1,o);return a}return r!==t?b.style(e,n,r):b.css(e,n)},e,n,arguments.length>1)},show:function(){return rn(this,!0)},hide:function(){return rn(this)},toggle:function(e){var t="boolean"==typeof e;return this.each(function(){(t?e:nn(this))?b(this).show():b(this).hide()})}}),b.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=Wt(e,"opacity");return""===n?"1":n}}}},cssNumber:{columnCount:!0,fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":b.support.cssFloat?"cssFloat":"styleFloat"},style:function(e,n,r,i){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var o,a,s,u=b.camelCase(n),l=e.style;if(n=b.cssProps[u]||(b.cssProps[u]=tn(l,u)),s=b.cssHooks[n]||b.cssHooks[u],r===t)return s&&"get"in s&&(o=s.get(e,!1,i))!==t?o:l[n];if(a=typeof r,"string"===a&&(o=Jt.exec(r))&&(r=(o[1]+1)*o[2]+parseFloat(b.css(e,n)),a="number"),!(null==r||"number"===a&&isNaN(r)||("number"!==a||b.cssNumber[u]||(r+="px"),b.support.clearCloneStyle||""!==r||0!==n.indexOf("background")||(l[n]="inherit"),s&&"set"in s&&(r=s.set(e,r,i))===t)))try{l[n]=r}catch(c){}}},css:function(e,n,r,i){var o,a,s,u=b.camelCase(n);return n=b.cssProps[u]||(b.cssProps[u]=tn(e.style,u)),s=b.cssHooks[n]||b.cssHooks[u],s&&"get"in s&&(a=s.get(e,!0,r)),a===t&&(a=Wt(e,n,i)),"normal"===a&&n in Kt&&(a=Kt[n]),""===r||r?(o=parseFloat(a),r===!0||b.isNumeric(o)?o||0:a):a},swap:function(e,t,n,r){var i,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];i=n.apply(e,r||[]);for(o in t)e.style[o]=a[o];return i}}),e.getComputedStyle?(Rt=function(t){return e.getComputedStyle(t,null)},Wt=function(e,n,r){var i,o,a,s=r||Rt(e),u=s?s.getPropertyValue(n)||s[n]:t,l=e.style;return s&&(""!==u||b.contains(e.ownerDocument,e)||(u=b.style(e,n)),Yt.test(u)&&Ut.test(n)&&(i=l.width,o=l.minWidth,a=l.maxWidth,l.minWidth=l.maxWidth=l.width=u,u=s.width,l.width=i,l.minWidth=o,l.maxWidth=a)),u}):o.documentElement.currentStyle&&(Rt=function(e){return e.currentStyle},Wt=function(e,n,r){var i,o,a,s=r||Rt(e),u=s?s[n]:t,l=e.style;return null==u&&l&&l[n]&&(u=l[n]),Yt.test(u)&&!zt.test(n)&&(i=l.left,o=e.runtimeStyle,a=o&&o.left,a&&(o.left=e.currentStyle.left),l.left="fontSize"===n?"1em":u,u=l.pixelLeft+"px",l.left=i,a&&(o.left=a)),""===u?"auto":u});function on(e,t,n){var r=Vt.exec(t);return r?Math.max(0,r[1]-(n||0))+(r[2]||"px"):t}function an(e,t,n,r,i){var o=n===(r?"border":"content")?4:"width"===t?1:0,a=0;for(;4>o;o+=2)"margin"===n&&(a+=b.css(e,n+Zt[o],!0,i)),r?("content"===n&&(a-=b.css(e,"padding"+Zt[o],!0,i)),"margin"!==n&&(a-=b.css(e,"border"+Zt[o]+"Width",!0,i))):(a+=b.css(e,"padding"+Zt[o],!0,i),"padding"!==n&&(a+=b.css(e,"border"+Zt[o]+"Width",!0,i)));return a}function sn(e,t,n){var r=!0,i="width"===t?e.offsetWidth:e.offsetHeight,o=Rt(e),a=b.support.boxSizing&&"border-box"===b.css(e,"boxSizing",!1,o);if(0>=i||null==i){if(i=Wt(e,t,o),(0>i||null==i)&&(i=e.style[t]),Yt.test(i))return i;r=a&&(b.support.boxSizingReliable||i===e.style[t]),i=parseFloat(i)||0}return i+an(e,t,n||(a?"border":"content"),r,o)+"px"}function un(e){var t=o,n=Gt[e];return n||(n=ln(e,t),"none"!==n&&n||(Pt=(Pt||b("<iframe frameborder='0' width='0' height='0'/>").css("cssText","display:block !important")).appendTo(t.documentElement),t=(Pt[0].contentWindow||Pt[0].contentDocument).document,t.write("<!doctype html><html><body>"),t.close(),n=ln(e,t),Pt.detach()),Gt[e]=n),n}function ln(e,t){var n=b(t.createElement(e)).appendTo(t.body),r=b.css(n[0],"display");return n.remove(),r}b.each(["height","width"],function(e,n){b.cssHooks[n]={get:function(e,r,i){return r?0===e.offsetWidth&&Xt.test(b.css(e,"display"))?b.swap(e,Qt,function(){return sn(e,n,i)}):sn(e,n,i):t},set:function(e,t,r){var i=r&&Rt(e);return on(e,t,r?an(e,n,r,b.support.boxSizing&&"border-box"===b.css(e,"boxSizing",!1,i),i):0)}}}),b.support.opacity||(b.cssHooks.opacity={get:function(e,t){return It.test((t&&e.currentStyle?e.currentStyle.filter:e.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":t?"1":""},set:function(e,t){var n=e.style,r=e.currentStyle,i=b.isNumeric(t)?"alpha(opacity="+100*t+")":"",o=r&&r.filter||n.filter||"";n.zoom=1,(t>=1||""===t)&&""===b.trim(o.replace($t,""))&&n.removeAttribute&&(n.removeAttribute("filter"),""===t||r&&!r.filter)||(n.filter=$t.test(o)?o.replace($t,i):o+" "+i)}}),b(function(){b.support.reliableMarginRight||(b.cssHooks.marginRight={get:function(e,n){return n?b.swap(e,{display:"inline-block"},Wt,[e,"marginRight"]):t}}),!b.support.pixelPosition&&b.fn.position&&b.each(["top","left"],function(e,n){b.cssHooks[n]={get:function(e,r){return r?(r=Wt(e,n),Yt.test(r)?b(e).position()[n]+"px":r):t}}})}),b.expr&&b.expr.filters&&(b.expr.filters.hidden=function(e){return 0>=e.offsetWidth&&0>=e.offsetHeight||!b.support.reliableHiddenOffsets&&"none"===(e.style&&e.style.display||b.css(e,"display"))},b.expr.filters.visible=function(e){return!b.expr.filters.hidden(e)}),b.each({margin:"",padding:"",border:"Width"},function(e,t){b.cssHooks[e+t]={expand:function(n){var r=0,i={},o="string"==typeof n?n.split(" "):[n];for(;4>r;r++)i[e+Zt[r]+t]=o[r]||o[r-2]||o[0];return i}},Ut.test(e)||(b.cssHooks[e+t].set=on)});var cn=/%20/g,pn=/\[\]$/,fn=/\r?\n/g,dn=/^(?:submit|button|image|reset|file)$/i,hn=/^(?:input|select|textarea|keygen)/i;b.fn.extend({serialize:function(){return b.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=b.prop(this,"elements");return e?b.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!b(this).is(":disabled")&&hn.test(this.nodeName)&&!dn.test(e)&&(this.checked||!Nt.test(e))}).map(function(e,t){var n=b(this).val();return null==n?null:b.isArray(n)?b.map(n,function(e){return{name:t.name,value:e.replace(fn,"\r\n")}}):{name:t.name,value:n.replace(fn,"\r\n")}}).get()}}),b.param=function(e,n){var r,i=[],o=function(e,t){t=b.isFunction(t)?t():null==t?"":t,i[i.length]=encodeURIComponent(e)+"="+encodeURIComponent(t)};if(n===t&&(n=b.ajaxSettings&&b.ajaxSettings.traditional),b.isArray(e)||e.jquery&&!b.isPlainObject(e))b.each(e,function(){o(this.name,this.value)});else for(r in e)gn(r,e[r],n,o);return i.join("&").replace(cn,"+")};function gn(e,t,n,r){var i;if(b.isArray(t))b.each(t,function(t,i){n||pn.test(e)?r(e,i):gn(e+"["+("object"==typeof i?t:"")+"]",i,n,r)});else if(n||"object"!==b.type(t))r(e,t);else for(i in t)gn(e+"["+i+"]",t[i],n,r)}b.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(e,t){b.fn[t]=function(e,n){return arguments.length>0?this.on(t,null,e,n):this.trigger(t)}}),b.fn.hover=function(e,t){return this.mouseenter(e).mouseleave(t||e)};var mn,yn,vn=b.now(),bn=/\?/,xn=/#.*$/,wn=/([?&])_=[^&]*/,Tn=/^(.*?):[ \t]*([^\r\n]*)\r?$/gm,Nn=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,Cn=/^(?:GET|HEAD)$/,kn=/^\/\//,En=/^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,Sn=b.fn.load,An={},jn={},Dn="*/".concat("*");try{yn=a.href}catch(Ln){yn=o.createElement("a"),yn.href="",yn=yn.href}mn=En.exec(yn.toLowerCase())||[];function Hn(e){return function(t,n){"string"!=typeof t&&(n=t,t="*");var r,i=0,o=t.toLowerCase().match(w)||[];if(b.isFunction(n))while(r=o[i++])"+"===r[0]?(r=r.slice(1)||"*",(e[r]=e[r]||[]).unshift(n)):(e[r]=e[r]||[]).push(n)}}function qn(e,n,r,i){var o={},a=e===jn;function s(u){var l;return o[u]=!0,b.each(e[u]||[],function(e,u){var c=u(n,r,i);return"string"!=typeof c||a||o[c]?a?!(l=c):t:(n.dataTypes.unshift(c),s(c),!1)}),l}return s(n.dataTypes[0])||!o["*"]&&s("*")}function Mn(e,n){var r,i,o=b.ajaxSettings.flatOptions||{};for(i in n)n[i]!==t&&((o[i]?e:r||(r={}))[i]=n[i]);return r&&b.extend(!0,e,r),e}b.fn.load=function(e,n,r){if("string"!=typeof e&&Sn)return Sn.apply(this,arguments);var i,o,a,s=this,u=e.indexOf(" ");return u>=0&&(i=e.slice(u,e.length),e=e.slice(0,u)),b.isFunction(n)?(r=n,n=t):n&&"object"==typeof n&&(a="POST"),s.length>0&&b.ajax({url:e,type:a,dataType:"html",data:n}).done(function(e){o=arguments,s.html(i?b("<div>").append(b.parseHTML(e)).find(i):e)}).complete(r&&function(e,t){s.each(r,o||[e.responseText,t,e])}),this},b.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(e,t){b.fn[t]=function(e){return this.on(t,e)}}),b.each(["get","post"],function(e,n){b[n]=function(e,r,i,o){return b.isFunction(r)&&(o=o||i,i=r,r=t),b.ajax({url:e,type:n,dataType:o,data:r,success:i})}}),b.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:yn,type:"GET",isLocal:Nn.test(mn[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":Dn,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":e.String,"text html":!0,"text json":b.parseJSON,"text xml":b.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?Mn(Mn(e,b.ajaxSettings),t):Mn(b.ajaxSettings,e)},ajaxPrefilter:Hn(An),ajaxTransport:Hn(jn),ajax:function(e,n){"object"==typeof e&&(n=e,e=t),n=n||{};var r,i,o,a,s,u,l,c,p=b.ajaxSetup({},n),f=p.context||p,d=p.context&&(f.nodeType||f.jquery)?b(f):b.event,h=b.Deferred(),g=b.Callbacks("once memory"),m=p.statusCode||{},y={},v={},x=0,T="canceled",N={readyState:0,getResponseHeader:function(e){var t;if(2===x){if(!c){c={};while(t=Tn.exec(a))c[t[1].toLowerCase()]=t[2]}t=c[e.toLowerCase()]}return null==t?null:t},getAllResponseHeaders:function(){return 2===x?a:null},setRequestHeader:function(e,t){var n=e.toLowerCase();return x||(e=v[n]=v[n]||e,y[e]=t),this},overrideMimeType:function(e){return x||(p.mimeType=e),this},statusCode:function(e){var t;if(e)if(2>x)for(t in e)m[t]=[m[t],e[t]];else N.always(e[N.status]);return this},abort:function(e){var t=e||T;return l&&l.abort(t),k(0,t),this}};if(h.promise(N).complete=g.add,N.success=N.done,N.error=N.fail,p.url=((e||p.url||yn)+"").replace(xn,"").replace(kn,mn[1]+"//"),p.type=n.method||n.type||p.method||p.type,p.dataTypes=b.trim(p.dataType||"*").toLowerCase().match(w)||[""],null==p.crossDomain&&(r=En.exec(p.url.toLowerCase()),p.crossDomain=!(!r||r[1]===mn[1]&&r[2]===mn[2]&&(r[3]||("http:"===r[1]?80:443))==(mn[3]||("http:"===mn[1]?80:443)))),p.data&&p.processData&&"string"!=typeof p.data&&(p.data=b.param(p.data,p.traditional)),qn(An,p,n,N),2===x)return N;u=p.global,u&&0===b.active++&&b.event.trigger("ajaxStart"),p.type=p.type.toUpperCase(),p.hasContent=!Cn.test(p.type),o=p.url,p.hasContent||(p.data&&(o=p.url+=(bn.test(o)?"&":"?")+p.data,delete p.data),p.cache===!1&&(p.url=wn.test(o)?o.replace(wn,"$1_="+vn++):o+(bn.test(o)?"&":"?")+"_="+vn++)),p.ifModified&&(b.lastModified[o]&&N.setRequestHeader("If-Modified-Since",b.lastModified[o]),b.etag[o]&&N.setRequestHeader("If-None-Match",b.etag[o])),(p.data&&p.hasContent&&p.contentType!==!1||n.contentType)&&N.setRequestHeader("Content-Type",p.contentType),N.setRequestHeader("Accept",p.dataTypes[0]&&p.accepts[p.dataTypes[0]]?p.accepts[p.dataTypes[0]]+("*"!==p.dataTypes[0]?", "+Dn+"; q=0.01":""):p.accepts["*"]);for(i in p.headers)N.setRequestHeader(i,p.headers[i]);if(p.beforeSend&&(p.beforeSend.call(f,N,p)===!1||2===x))return N.abort();T="abort";for(i in{success:1,error:1,complete:1})N[i](p[i]);if(l=qn(jn,p,n,N)){N.readyState=1,u&&d.trigger("ajaxSend",[N,p]),p.async&&p.timeout>0&&(s=setTimeout(function(){N.abort("timeout")},p.timeout));try{x=1,l.send(y,k)}catch(C){if(!(2>x))throw C;k(-1,C)}}else k(-1,"No Transport");function k(e,n,r,i){var c,y,v,w,T,C=n;2!==x&&(x=2,s&&clearTimeout(s),l=t,a=i||"",N.readyState=e>0?4:0,r&&(w=_n(p,N,r)),e>=200&&300>e||304===e?(p.ifModified&&(T=N.getResponseHeader("Last-Modified"),T&&(b.lastModified[o]=T),T=N.getResponseHeader("etag"),T&&(b.etag[o]=T)),204===e?(c=!0,C="nocontent"):304===e?(c=!0,C="notmodified"):(c=Fn(p,w),C=c.state,y=c.data,v=c.error,c=!v)):(v=C,(e||!C)&&(C="error",0>e&&(e=0))),N.status=e,N.statusText=(n||C)+"",c?h.resolveWith(f,[y,C,N]):h.rejectWith(f,[N,C,v]),N.statusCode(m),m=t,u&&d.trigger(c?"ajaxSuccess":"ajaxError",[N,p,c?y:v]),g.fireWith(f,[N,C]),u&&(d.trigger("ajaxComplete",[N,p]),--b.active||b.event.trigger("ajaxStop")))}return N},getScript:function(e,n){return b.get(e,t,n,"script")},getJSON:function(e,t,n){return b.get(e,t,n,"json")}});function _n(e,n,r){var i,o,a,s,u=e.contents,l=e.dataTypes,c=e.responseFields;for(s in c)s in r&&(n[c[s]]=r[s]);while("*"===l[0])l.shift(),o===t&&(o=e.mimeType||n.getResponseHeader("Content-Type"));if(o)for(s in u)if(u[s]&&u[s].test(o)){l.unshift(s);break}if(l[0]in r)a=l[0];else{for(s in r){if(!l[0]||e.converters[s+" "+l[0]]){a=s;break}i||(i=s)}a=a||i}return a?(a!==l[0]&&l.unshift(a),r[a]):t}function Fn(e,t){var n,r,i,o,a={},s=0,u=e.dataTypes.slice(),l=u[0];if(e.dataFilter&&(t=e.dataFilter(t,e.dataType)),u[1])for(i in e.converters)a[i.toLowerCase()]=e.converters[i];for(;r=u[++s];)if("*"!==r){if("*"!==l&&l!==r){if(i=a[l+" "+r]||a["* "+r],!i)for(n in a)if(o=n.split(" "),o[1]===r&&(i=a[l+" "+o[0]]||a["* "+o[0]])){i===!0?i=a[n]:a[n]!==!0&&(r=o[0],u.splice(s--,0,r));break}if(i!==!0)if(i&&e["throws"])t=i(t);else try{t=i(t)}catch(c){return{state:"parsererror",error:i?c:"No conversion from "+l+" to "+r}}}l=r}return{state:"success",data:t}}b.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(e){return b.globalEval(e),e}}}),b.ajaxPrefilter("script",function(e){e.cache===t&&(e.cache=!1),e.crossDomain&&(e.type="GET",e.global=!1)}),b.ajaxTransport("script",function(e){if(e.crossDomain){var n,r=o.head||b("head")[0]||o.documentElement;return{send:function(t,i){n=o.createElement("script"),n.async=!0,e.scriptCharset&&(n.charset=e.scriptCharset),n.src=e.url,n.onload=n.onreadystatechange=function(e,t){(t||!n.readyState||/loaded|complete/.test(n.readyState))&&(n.onload=n.onreadystatechange=null,n.parentNode&&n.parentNode.removeChild(n),n=null,t||i(200,"success"))},r.insertBefore(n,r.firstChild)},abort:function(){n&&n.onload(t,!0)}}}});var On=[],Bn=/(=)\?(?=&|$)|\?\?/;b.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=On.pop()||b.expando+"_"+vn++;return this[e]=!0,e}}),b.ajaxPrefilter("json jsonp",function(n,r,i){var o,a,s,u=n.jsonp!==!1&&(Bn.test(n.url)?"url":"string"==typeof n.data&&!(n.contentType||"").indexOf("application/x-www-form-urlencoded")&&Bn.test(n.data)&&"data");return u||"jsonp"===n.dataTypes[0]?(o=n.jsonpCallback=b.isFunction(n.jsonpCallback)?n.jsonpCallback():n.jsonpCallback,u?n[u]=n[u].replace(Bn,"$1"+o):n.jsonp!==!1&&(n.url+=(bn.test(n.url)?"&":"?")+n.jsonp+"="+o),n.converters["script json"]=function(){return s||b.error(o+" was not called"),s[0]},n.dataTypes[0]="json",a=e[o],e[o]=function(){s=arguments},i.always(function(){e[o]=a,n[o]&&(n.jsonpCallback=r.jsonpCallback,On.push(o)),s&&b.isFunction(a)&&a(s[0]),s=a=t}),"script"):t});var Pn,Rn,Wn=0,$n=e.ActiveXObject&&function(){var e;for(e in Pn)Pn[e](t,!0)};function In(){try{return new e.XMLHttpRequest}catch(t){}}function zn(){try{return new e.ActiveXObject("Microsoft.XMLHTTP")}catch(t){}}b.ajaxSettings.xhr=e.ActiveXObject?function(){return!this.isLocal&&In()||zn()}:In,Rn=b.ajaxSettings.xhr(),b.support.cors=!!Rn&&"withCredentials"in Rn,Rn=b.support.ajax=!!Rn,Rn&&b.ajaxTransport(function(n){if(!n.crossDomain||b.support.cors){var r;return{send:function(i,o){var a,s,u=n.xhr();if(n.username?u.open(n.type,n.url,n.async,n.username,n.password):u.open(n.type,n.url,n.async),n.xhrFields)for(s in n.xhrFields)u[s]=n.xhrFields[s];n.mimeType&&u.overrideMimeType&&u.overrideMimeType(n.mimeType),n.crossDomain||i["X-Requested-With"]||(i["X-Requested-With"]="XMLHttpRequest");try{for(s in i)u.setRequestHeader(s,i[s])}catch(l){}u.send(n.hasContent&&n.data||null),r=function(e,i){var s,l,c,p;try{if(r&&(i||4===u.readyState))if(r=t,a&&(u.onreadystatechange=b.noop,$n&&delete Pn[a]),i)4!==u.readyState&&u.abort();else{p={},s=u.status,l=u.getAllResponseHeaders(),"string"==typeof u.responseText&&(p.text=u.responseText);try{c=u.statusText}catch(f){c=""}s||!n.isLocal||n.crossDomain?1223===s&&(s=204):s=p.text?200:404}}catch(d){i||o(-1,d)}p&&o(s,c,p,l)},n.async?4===u.readyState?setTimeout(r):(a=++Wn,$n&&(Pn||(Pn={},b(e).unload($n)),Pn[a]=r),u.onreadystatechange=r):r()},abort:function(){r&&r(t,!0)}}}});var Xn,Un,Vn=/^(?:toggle|show|hide)$/,Yn=RegExp("^(?:([+-])=|)("+x+")([a-z%]*)$","i"),Jn=/queueHooks$/,Gn=[nr],Qn={"*":[function(e,t){var n,r,i=this.createTween(e,t),o=Yn.exec(t),a=i.cur(),s=+a||0,u=1,l=20;if(o){if(n=+o[2],r=o[3]||(b.cssNumber[e]?"":"px"),"px"!==r&&s){s=b.css(i.elem,e,!0)||n||1;do u=u||".5",s/=u,b.style(i.elem,e,s+r);while(u!==(u=i.cur()/a)&&1!==u&&--l)}i.unit=r,i.start=s,i.end=o[1]?s+(o[1]+1)*n:n}return i}]};function Kn(){return setTimeout(function(){Xn=t}),Xn=b.now()}function Zn(e,t){b.each(t,function(t,n){var r=(Qn[t]||[]).concat(Qn["*"]),i=0,o=r.length;for(;o>i;i++)if(r[i].call(e,t,n))return})}function er(e,t,n){var r,i,o=0,a=Gn.length,s=b.Deferred().always(function(){delete u.elem}),u=function(){if(i)return!1;var t=Xn||Kn(),n=Math.max(0,l.startTime+l.duration-t),r=n/l.duration||0,o=1-r,a=0,u=l.tweens.length;for(;u>a;a++)l.tweens[a].run(o);return s.notifyWith(e,[l,o,n]),1>o&&u?n:(s.resolveWith(e,[l]),!1)},l=s.promise({elem:e,props:b.extend({},t),opts:b.extend(!0,{specialEasing:{}},n),originalProperties:t,originalOptions:n,startTime:Xn||Kn(),duration:n.duration,tweens:[],createTween:function(t,n){var r=b.Tween(e,l.opts,t,n,l.opts.specialEasing[t]||l.opts.easing);return l.tweens.push(r),r},stop:function(t){var n=0,r=t?l.tweens.length:0;if(i)return this;for(i=!0;r>n;n++)l.tweens[n].run(1);return t?s.resolveWith(e,[l,t]):s.rejectWith(e,[l,t]),this}}),c=l.props;for(tr(c,l.opts.specialEasing);a>o;o++)if(r=Gn[o].call(l,e,c,l.opts))return r;return Zn(l,c),b.isFunction(l.opts.start)&&l.opts.start.call(e,l),b.fx.timer(b.extend(u,{elem:e,anim:l,queue:l.opts.queue})),l.progress(l.opts.progress).done(l.opts.done,l.opts.complete).fail(l.opts.fail).always(l.opts.always)}function tr(e,t){var n,r,i,o,a;for(i in e)if(r=b.camelCase(i),o=t[r],n=e[i],b.isArray(n)&&(o=n[1],n=e[i]=n[0]),i!==r&&(e[r]=n,delete e[i]),a=b.cssHooks[r],a&&"expand"in a){n=a.expand(n),delete e[r];for(i in n)i in e||(e[i]=n[i],t[i]=o)}else t[r]=o}b.Animation=b.extend(er,{tweener:function(e,t){b.isFunction(e)?(t=e,e=["*"]):e=e.split(" ");var n,r=0,i=e.length;for(;i>r;r++)n=e[r],Qn[n]=Qn[n]||[],Qn[n].unshift(t)},prefilter:function(e,t){t?Gn.unshift(e):Gn.push(e)}});function nr(e,t,n){var r,i,o,a,s,u,l,c,p,f=this,d=e.style,h={},g=[],m=e.nodeType&&nn(e);n.queue||(c=b._queueHooks(e,"fx"),null==c.unqueued&&(c.unqueued=0,p=c.empty.fire,c.empty.fire=function(){c.unqueued||p()}),c.unqueued++,f.always(function(){f.always(function(){c.unqueued--,b.queue(e,"fx").length||c.empty.fire()})})),1===e.nodeType&&("height"in t||"width"in t)&&(n.overflow=[d.overflow,d.overflowX,d.overflowY],"inline"===b.css(e,"display")&&"none"===b.css(e,"float")&&(b.support.inlineBlockNeedsLayout&&"inline"!==un(e.nodeName)?d.zoom=1:d.display="inline-block")),n.overflow&&(d.overflow="hidden",b.support.shrinkWrapBlocks||f.always(function(){d.overflow=n.overflow[0],d.overflowX=n.overflow[1],d.overflowY=n.overflow[2]}));for(i in t)if(a=t[i],Vn.exec(a)){if(delete t[i],u=u||"toggle"===a,a===(m?"hide":"show"))continue;g.push(i)}if(o=g.length){s=b._data(e,"fxshow")||b._data(e,"fxshow",{}),"hidden"in s&&(m=s.hidden),u&&(s.hidden=!m),m?b(e).show():f.done(function(){b(e).hide()}),f.done(function(){var t;b._removeData(e,"fxshow");for(t in h)b.style(e,t,h[t])});for(i=0;o>i;i++)r=g[i],l=f.createTween(r,m?s[r]:0),h[r]=s[r]||b.style(e,r),r in s||(s[r]=l.start,m&&(l.end=l.start,l.start="width"===r||"height"===r?1:0))}}function rr(e,t,n,r,i){return new rr.prototype.init(e,t,n,r,i)}b.Tween=rr,rr.prototype={constructor:rr,init:function(e,t,n,r,i,o){this.elem=e,this.prop=n,this.easing=i||"swing",this.options=t,this.start=this.now=this.cur(),this.end=r,this.unit=o||(b.cssNumber[n]?"":"px")},cur:function(){var e=rr.propHooks[this.prop];return e&&e.get?e.get(this):rr.propHooks._default.get(this)},run:function(e){var t,n=rr.propHooks[this.prop];return this.pos=t=this.options.duration?b.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):rr.propHooks._default.set(this),this}},rr.prototype.init.prototype=rr.prototype,rr.propHooks={_default:{get:function(e){var t;return null==e.elem[e.prop]||e.elem.style&&null!=e.elem.style[e.prop]?(t=b.css(e.elem,e.prop,""),t&&"auto"!==t?t:0):e.elem[e.prop]},set:function(e){b.fx.step[e.prop]?b.fx.step[e.prop](e):e.elem.style&&(null!=e.elem.style[b.cssProps[e.prop]]||b.cssHooks[e.prop])?b.style(e.elem,e.prop,e.now+e.unit):e.elem[e.prop]=e.now}}},rr.propHooks.scrollTop=rr.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},b.each(["toggle","show","hide"],function(e,t){var n=b.fn[t];b.fn[t]=function(e,r,i){return null==e||"boolean"==typeof e?n.apply(this,arguments):this.animate(ir(t,!0),e,r,i)}}),b.fn.extend({fadeTo:function(e,t,n,r){return this.filter(nn).css("opacity",0).show().end().animate({opacity:t},e,n,r)},animate:function(e,t,n,r){var i=b.isEmptyObject(e),o=b.speed(t,n,r),a=function(){var t=er(this,b.extend({},e),o);a.finish=function(){t.stop(!0)},(i||b._data(this,"finish"))&&t.stop(!0)};return a.finish=a,i||o.queue===!1?this.each(a):this.queue(o.queue,a)},stop:function(e,n,r){var i=function(e){var t=e.stop;delete e.stop,t(r)};return"string"!=typeof e&&(r=n,n=e,e=t),n&&e!==!1&&this.queue(e||"fx",[]),this.each(function(){var t=!0,n=null!=e&&e+"queueHooks",o=b.timers,a=b._data(this);if(n)a[n]&&a[n].stop&&i(a[n]);else for(n in a)a[n]&&a[n].stop&&Jn.test(n)&&i(a[n]);for(n=o.length;n--;)o[n].elem!==this||null!=e&&o[n].queue!==e||(o[n].anim.stop(r),t=!1,o.splice(n,1));(t||!r)&&b.dequeue(this,e)})},finish:function(e){return e!==!1&&(e=e||"fx"),this.each(function(){var t,n=b._data(this),r=n[e+"queue"],i=n[e+"queueHooks"],o=b.timers,a=r?r.length:0;for(n.finish=!0,b.queue(this,e,[]),i&&i.cur&&i.cur.finish&&i.cur.finish.call(this),t=o.length;t--;)o[t].elem===this&&o[t].queue===e&&(o[t].anim.stop(!0),o.splice(t,1));for(t=0;a>t;t++)r[t]&&r[t].finish&&r[t].finish.call(this);delete n.finish})}});function ir(e,t){var n,r={height:e},i=0;for(t=t?1:0;4>i;i+=2-t)n=Zt[i],r["margin"+n]=r["padding"+n]=e;return t&&(r.opacity=r.width=e),r}b.each({slideDown:ir("show"),slideUp:ir("hide"),slideToggle:ir("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(e,t){b.fn[e]=function(e,n,r){return this.animate(t,e,n,r)}}),b.speed=function(e,t,n){var r=e&&"object"==typeof e?b.extend({},e):{complete:n||!n&&t||b.isFunction(e)&&e,duration:e,easing:n&&t||t&&!b.isFunction(t)&&t};return r.duration=b.fx.off?0:"number"==typeof r.duration?r.duration:r.duration in b.fx.speeds?b.fx.speeds[r.duration]:b.fx.speeds._default,(null==r.queue||r.queue===!0)&&(r.queue="fx"),r.old=r.complete,r.complete=function(){b.isFunction(r.old)&&r.old.call(this),r.queue&&b.dequeue(this,r.queue)},r},b.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2}},b.timers=[],b.fx=rr.prototype.init,b.fx.tick=function(){var e,n=b.timers,r=0;for(Xn=b.now();n.length>r;r++)e=n[r],e()||n[r]!==e||n.splice(r--,1);n.length||b.fx.stop(),Xn=t},b.fx.timer=function(e){e()&&b.timers.push(e)&&b.fx.start()},b.fx.interval=13,b.fx.start=function(){Un||(Un=setInterval(b.fx.tick,b.fx.interval))},b.fx.stop=function(){clearInterval(Un),Un=null},b.fx.speeds={slow:600,fast:200,_default:400},b.fx.step={},b.expr&&b.expr.filters&&(b.expr.filters.animated=function(e){return b.grep(b.timers,function(t){return e===t.elem}).length}),b.fn.offset=function(e){if(arguments.length)return e===t?this:this.each(function(t){b.offset.setOffset(this,e,t)});var n,r,o={top:0,left:0},a=this[0],s=a&&a.ownerDocument;if(s)return n=s.documentElement,b.contains(n,a)?(typeof a.getBoundingClientRect!==i&&(o=a.getBoundingClientRect()),r=or(s),{top:o.top+(r.pageYOffset||n.scrollTop)-(n.clientTop||0),left:o.left+(r.pageXOffset||n.scrollLeft)-(n.clientLeft||0)}):o},b.offset={setOffset:function(e,t,n){var r=b.css(e,"position");"static"===r&&(e.style.position="relative");var i=b(e),o=i.offset(),a=b.css(e,"top"),s=b.css(e,"left"),u=("absolute"===r||"fixed"===r)&&b.inArray("auto",[a,s])>-1,l={},c={},p,f;u?(c=i.position(),p=c.top,f=c.left):(p=parseFloat(a)||0,f=parseFloat(s)||0),b.isFunction(t)&&(t=t.call(e,n,o)),null!=t.top&&(l.top=t.top-o.top+p),null!=t.left&&(l.left=t.left-o.left+f),"using"in t?t.using.call(e,l):i.css(l)}},b.fn.extend({position:function(){if(this[0]){var e,t,n={top:0,left:0},r=this[0];return"fixed"===b.css(r,"position")?t=r.getBoundingClientRect():(e=this.offsetParent(),t=this.offset(),b.nodeName(e[0],"html")||(n=e.offset()),n.top+=b.css(e[0],"borderTopWidth",!0),n.left+=b.css(e[0],"borderLeftWidth",!0)),{top:t.top-n.top-b.css(r,"marginTop",!0),left:t.left-n.left-b.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var e=this.offsetParent||o.documentElement;while(e&&!b.nodeName(e,"html")&&"static"===b.css(e,"position"))e=e.offsetParent;return e||o.documentElement})}}),b.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(e,n){var r=/Y/.test(n);b.fn[e]=function(i){return b.access(this,function(e,i,o){var a=or(e);return o===t?a?n in a?a[n]:a.document.documentElement[i]:e[i]:(a?a.scrollTo(r?b(a).scrollLeft():o,r?o:b(a).scrollTop()):e[i]=o,t)},e,i,arguments.length,null)}});function or(e){return b.isWindow(e)?e:9===e.nodeType?e.defaultView||e.parentWindow:!1}b.each({Height:"height",Width:"width"},function(e,n){b.each({padding:"inner"+e,content:n,"":"outer"+e},function(r,i){b.fn[i]=function(i,o){var a=arguments.length&&(r||"boolean"!=typeof i),s=r||(i===!0||o===!0?"margin":"border");return b.access(this,function(n,r,i){var o;return b.isWindow(n)?n.document.documentElement["client"+e]:9===n.nodeType?(o=n.documentElement,Math.max(n.body["scroll"+e],o["scroll"+e],n.body["offset"+e],o["offset"+e],o["client"+e])):i===t?b.css(n,r,s):b.style(n,r,i,s)},n,a?i:t,a,null)}})}),e.jQuery=e.$=b,"function"==typeof define&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return b})})(window);
/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(L){var u;L.fn.emptyForce=function(){for(var ah=0,ai;(ai=L(this)[ah])!=null;ah++){if(ai.nodeType===1){L.cleanData(ai.getElementsByTagName("*"))}if(L.jqplot.use_excanvas){ai.outerHTML=""}else{while(ai.firstChild){ai.removeChild(ai.firstChild)}}ai=null}return L(this)};L.fn.removeChildForce=function(ah){while(ah.firstChild){this.removeChildForce(ah.firstChild);ah.removeChild(ah.firstChild)}};L.fn.jqplot=function(){var ah=[];var aj=[];for(var ak=0,ai=arguments.length;ak<ai;ak++){if(L.isArray(arguments[ak])){ah.push(arguments[ak])}else{if(L.isPlainObject(arguments[ak])){aj.push(arguments[ak])}}}return this.each(function(an){var at,ar,aq=L(this),am=ah.length,al=aj.length,ap,ao;if(an<am){ap=ah[an]}else{ap=am?ah[am-1]:null}if(an<al){ao=aj[an]}else{ao=al?aj[al-1]:null}at=aq.attr("id");if(at===u){at="jqplot_target_"+L.jqplot.targetCounter++;aq.attr("id",at)}ar=L.jqplot(at,ap,ao);aq.data("jqplot",ar)})};L.jqplot=function(an,ak,ai){var aj=null,ah=null;if(arguments.length===3){aj=ak;ah=ai}else{if(arguments.length===2){if(L.isArray(ak)){aj=ak}else{if(L.isPlainObject(ak)){ah=ak}}}}if(aj===null&&ah!==null&&ah.data){aj=ah.data}var am=new R();L("#"+an).removeClass("jqplot-error");if(L.jqplot.config.catchErrors){try{am.init(an,aj,ah);am.draw();am.themeEngine.init.call(am);return am}catch(al){var ao=L.jqplot.config.errorMessage||al.message;L("#"+an).append('<div class="jqplot-error-message">'+ao+"</div>");L("#"+an).addClass("jqplot-error");document.getElementById(an).style.background=L.jqplot.config.errorBackground;document.getElementById(an).style.border=L.jqplot.config.errorBorder;document.getElementById(an).style.fontFamily=L.jqplot.config.errorFontFamily;document.getElementById(an).style.fontSize=L.jqplot.config.errorFontSize;document.getElementById(an).style.fontStyle=L.jqplot.config.errorFontStyle;document.getElementById(an).style.fontWeight=L.jqplot.config.errorFontWeight}}else{am.init(an,aj,ah);am.draw();am.themeEngine.init.call(am);return am}};L.jqplot.version="1.0.7";L.jqplot.revision="1224";L.jqplot.targetCounter=1;L.jqplot.CanvasManager=function(){if(typeof L.jqplot.CanvasManager.canvases=="undefined"){L.jqplot.CanvasManager.canvases=[];L.jqplot.CanvasManager.free=[]}var ah=[];this.getCanvas=function(){var ak;var aj=true;if(!L.jqplot.use_excanvas){for(var al=0,ai=L.jqplot.CanvasManager.canvases.length;al<ai;al++){if(L.jqplot.CanvasManager.free[al]===true){aj=false;ak=L.jqplot.CanvasManager.canvases[al];L.jqplot.CanvasManager.free[al]=false;ah.push(al);break}}}if(aj){ak=document.createElement("canvas");ah.push(L.jqplot.CanvasManager.canvases.length);L.jqplot.CanvasManager.canvases.push(ak);L.jqplot.CanvasManager.free.push(false)}return ak};this.initCanvas=function(ai){if(L.jqplot.use_excanvas){return window.G_vmlCanvasManager.initElement(ai)}return ai};this.freeAllCanvases=function(){for(var aj=0,ai=ah.length;aj<ai;aj++){this.freeCanvas(ah[aj])}ah=[]};this.freeCanvas=function(ai){if(L.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==u){window.G_vmlCanvasManager.uninitElement(L.jqplot.CanvasManager.canvases[ai]);L.jqplot.CanvasManager.canvases[ai]=null}else{var aj=L.jqplot.CanvasManager.canvases[ai];aj.getContext("2d").clearRect(0,0,aj.width,aj.height);L(aj).unbind().removeAttr("class").removeAttr("style");L(aj).css({left:"",top:"",position:""});aj.width=0;aj.height=0;L.jqplot.CanvasManager.free[ai]=true}}};L.jqplot.log=function(){if(window.console){window.console.log.apply(window.console,arguments)}};L.jqplot.config={addDomReference:false,enablePlugins:false,defaultHeight:300,defaultWidth:400,UTCAdjust:false,timezoneOffset:new Date(new Date().getTimezoneOffset()*60000),errorMessage:"",errorBackground:"",errorBorder:"",errorFontFamily:"",errorFontSize:"",errorFontStyle:"",errorFontWeight:"",catchErrors:false,defaultTickFormatString:"%.1f",defaultColors:["#4bb2c5","#EAA228","#c5b47f","#579575","#839557","#958c12","#953579","#4b5de4","#d8b83f","#ff5800","#0085cc","#c747a3","#cddf54","#FBD178","#26B4E3","#bd70c7"],defaultNegativeColors:["#498991","#C08840","#9F9274","#546D61","#646C4A","#6F6621","#6E3F5F","#4F64B0","#A89050","#C45923","#187399","#945381","#959E5C","#C7AF7B","#478396","#907294"],dashLength:4,gapLength:4,dotGapLength:2.5,srcLocation:"jqplot/src/",pluginLocation:"jqplot/src/plugins/"};L.jqplot.arrayMax=function(ah){return Math.max.apply(Math,ah)};L.jqplot.arrayMin=function(ah){return Math.min.apply(Math,ah)};L.jqplot.enablePlugins=L.jqplot.config.enablePlugins;L.jqplot.support_canvas=function(){if(typeof L.jqplot.support_canvas.result=="undefined"){L.jqplot.support_canvas.result=!!document.createElement("canvas").getContext}return L.jqplot.support_canvas.result};L.jqplot.support_canvas_text=function(){if(typeof L.jqplot.support_canvas_text.result=="undefined"){if(window.G_vmlCanvasManager!==u&&window.G_vmlCanvasManager._version>887){L.jqplot.support_canvas_text.result=true}else{L.jqplot.support_canvas_text.result=!!(document.createElement("canvas").getContext&&typeof document.createElement("canvas").getContext("2d").fillText=="function")}}return L.jqplot.support_canvas_text.result};L.jqplot.use_excanvas=((!L.support.boxModel||!L.support.objectAll||!$support.leadingWhitespace)&&!L.jqplot.support_canvas())?true:false;L.jqplot.preInitHooks=[];L.jqplot.postInitHooks=[];L.jqplot.preParseOptionsHooks=[];L.jqplot.postParseOptionsHooks=[];L.jqplot.preDrawHooks=[];L.jqplot.postDrawHooks=[];L.jqplot.preDrawSeriesHooks=[];L.jqplot.postDrawSeriesHooks=[];L.jqplot.preDrawLegendHooks=[];L.jqplot.addLegendRowHooks=[];L.jqplot.preSeriesInitHooks=[];L.jqplot.postSeriesInitHooks=[];L.jqplot.preParseSeriesOptionsHooks=[];L.jqplot.postParseSeriesOptionsHooks=[];L.jqplot.eventListenerHooks=[];L.jqplot.preDrawSeriesShadowHooks=[];L.jqplot.postDrawSeriesShadowHooks=[];L.jqplot.ElemContainer=function(){this._elem;this._plotWidth;this._plotHeight;this._plotDimensions={height:null,width:null}};L.jqplot.ElemContainer.prototype.createElement=function(ak,am,ai,aj,an){this._offsets=am;var ah=ai||"jqplot";var al=document.createElement(ak);this._elem=L(al);this._elem.addClass(ah);this._elem.css(aj);this._elem.attr(an);al=null;return this._elem};L.jqplot.ElemContainer.prototype.getWidth=function(){if(this._elem){return this._elem.outerWidth(true)}else{return null}};L.jqplot.ElemContainer.prototype.getHeight=function(){if(this._elem){return this._elem.outerHeight(true)}else{return null}};L.jqplot.ElemContainer.prototype.getPosition=function(){if(this._elem){return this._elem.position()}else{return{top:null,left:null,bottom:null,right:null}}};L.jqplot.ElemContainer.prototype.getTop=function(){return this.getPosition().top};L.jqplot.ElemContainer.prototype.getLeft=function(){return this.getPosition().left};L.jqplot.ElemContainer.prototype.getBottom=function(){return this._elem.css("bottom")};L.jqplot.ElemContainer.prototype.getRight=function(){return this._elem.css("right")};function w(ah){L.jqplot.ElemContainer.call(this);this.name=ah;this._series=[];this.show=false;this.tickRenderer=L.jqplot.AxisTickRenderer;this.tickOptions={};this.labelRenderer=L.jqplot.AxisLabelRenderer;this.labelOptions={};this.label=null;this.showLabel=true;this.min=null;this.max=null;this.autoscale=false;this.pad=1.2;this.padMax=null;this.padMin=null;this.ticks=[];this.numberTicks;this.tickInterval;this.renderer=L.jqplot.LinearAxisRenderer;this.rendererOptions={};this.showTicks=true;this.showTickMarks=true;this.showMinorTicks=true;this.drawMajorGridlines=true;this.drawMinorGridlines=false;this.drawMajorTickMarks=true;this.drawMinorTickMarks=true;this.useSeriesColor=false;this.borderWidth=null;this.borderColor=null;this.scaleToHiddenSeries=false;this._dataBounds={min:null,max:null};this._intervalStats=[];this._offsets={min:null,max:null};this._ticks=[];this._label=null;this.syncTicks=null;this.tickSpacing=75;this._min=null;this._max=null;this._tickInterval=null;this._numberTicks=null;this.__ticks=null;this._options={}}w.prototype=new L.jqplot.ElemContainer();w.prototype.constructor=w;w.prototype.init=function(){if(L.isFunction(this.renderer)){this.renderer=new this.renderer()}this.tickOptions.axis=this.name;if(this.tickOptions.showMark==null){this.tickOptions.showMark=this.showTicks}if(this.tickOptions.showMark==null){this.tickOptions.showMark=this.showTickMarks}if(this.tickOptions.showLabel==null){this.tickOptions.showLabel=this.showTicks}if(this.label==null||this.label==""){this.showLabel=false}else{this.labelOptions.label=this.label}if(this.showLabel==false){this.labelOptions.show=false}if(this.pad==0){this.pad=1}if(this.padMax==0){this.padMax=1}if(this.padMin==0){this.padMin=1}if(this.padMax==null){this.padMax=(this.pad-1)/2+1}if(this.padMin==null){this.padMin=(this.pad-1)/2+1}this.pad=this.padMax+this.padMin-1;if(this.min!=null||this.max!=null){this.autoscale=false}if(this.syncTicks==null&&this.name.indexOf("y")>-1){this.syncTicks=true}else{if(this.syncTicks==null){this.syncTicks=false}}this.renderer.init.call(this,this.rendererOptions)};w.prototype.draw=function(ah,ai){if(this.__ticks){this.__ticks=null}return this.renderer.draw.call(this,ah,ai)};w.prototype.set=function(){this.renderer.set.call(this)};w.prototype.pack=function(ai,ah){if(this.show){this.renderer.pack.call(this,ai,ah)}if(this._min==null){this._min=this.min;this._max=this.max;this._tickInterval=this.tickInterval;this._numberTicks=this.numberTicks;this.__ticks=this._ticks}};w.prototype.reset=function(){this.renderer.reset.call(this)};w.prototype.resetScale=function(ah){L.extend(true,this,{min:null,max:null,numberTicks:null,tickInterval:null,_ticks:[],ticks:[]},ah);this.resetDataBounds()};w.prototype.resetDataBounds=function(){var ao=this._dataBounds;ao.min=null;ao.max=null;var ai,ap,am;var aj=(this.show)?true:false;for(var al=0;al<this._series.length;al++){ap=this._series[al];if(ap.show||this.scaleToHiddenSeries){am=ap._plotData;if(ap._type==="line"&&ap.renderer.bands.show&&this.name.charAt(0)!=="x"){am=[[0,ap.renderer.bands._min],[1,ap.renderer.bands._max]]}var ah=1,an=1;if(ap._type!=null&&ap._type=="ohlc"){ah=3;an=2}for(var ak=0,ai=am.length;ak<ai;ak++){if(this.name=="xaxis"||this.name=="x2axis"){if((am[ak][0]!=null&&am[ak][0]<ao.min)||ao.min==null){ao.min=am[ak][0]}if((am[ak][0]!=null&&am[ak][0]>ao.max)||ao.max==null){ao.max=am[ak][0]}}else{if((am[ak][ah]!=null&&am[ak][ah]<ao.min)||ao.min==null){ao.min=am[ak][ah]}if((am[ak][an]!=null&&am[ak][an]>ao.max)||ao.max==null){ao.max=am[ak][an]}}}if(aj&&ap.renderer.constructor!==L.jqplot.BarRenderer){aj=false}else{if(aj&&this._options.hasOwnProperty("forceTickAt0")&&this._options.forceTickAt0==false){aj=false}else{if(aj&&ap.renderer.constructor===L.jqplot.BarRenderer){if(ap.barDirection=="vertical"&&this.name!="xaxis"&&this.name!="x2axis"){if(this._options.pad!=null||this._options.padMin!=null){aj=false}}else{if(ap.barDirection=="horizontal"&&(this.name=="xaxis"||this.name=="x2axis")){if(this._options.pad!=null||this._options.padMin!=null){aj=false}}}}}}}}if(aj&&this.renderer.constructor===L.jqplot.LinearAxisRenderer&&ao.min>=0){this.padMin=1;this.forceTickAt0=true}};function q(ah){L.jqplot.ElemContainer.call(this);this.show=false;this.location="ne";this.labels=[];this.showLabels=true;this.showSwatches=true;this.placement="insideGrid";this.xoffset=0;this.yoffset=0;this.border;this.background;this.textColor;this.fontFamily;this.fontSize;this.rowSpacing="0.5em";this.renderer=L.jqplot.TableLegendRenderer;this.rendererOptions={};this.preDraw=false;this.marginTop=null;this.marginRight=null;this.marginBottom=null;this.marginLeft=null;this.escapeHtml=false;this._series=[];L.extend(true,this,ah)}q.prototype=new L.jqplot.ElemContainer();q.prototype.constructor=q;q.prototype.setOptions=function(ah){L.extend(true,this,ah);if(this.placement=="inside"){this.placement="insideGrid"}if(this.xoffset>0){if(this.placement=="insideGrid"){switch(this.location){case"nw":case"w":case"sw":if(this.marginLeft==null){this.marginLeft=this.xoffset+"px"}this.marginRight="0px";break;case"ne":case"e":case"se":default:if(this.marginRight==null){this.marginRight=this.xoffset+"px"}this.marginLeft="0px";break}}else{if(this.placement=="outside"){switch(this.location){case"nw":case"w":case"sw":if(this.marginRight==null){this.marginRight=this.xoffset+"px"}this.marginLeft="0px";break;case"ne":case"e":case"se":default:if(this.marginLeft==null){this.marginLeft=this.xoffset+"px"}this.marginRight="0px";break}}}this.xoffset=0}if(this.yoffset>0){if(this.placement=="outside"){switch(this.location){case"sw":case"s":case"se":if(this.marginTop==null){this.marginTop=this.yoffset+"px"}this.marginBottom="0px";break;case"ne":case"n":case"nw":default:if(this.marginBottom==null){this.marginBottom=this.yoffset+"px"}this.marginTop="0px";break}}else{if(this.placement=="insideGrid"){switch(this.location){case"sw":case"s":case"se":if(this.marginBottom==null){this.marginBottom=this.yoffset+"px"}this.marginTop="0px";break;case"ne":case"n":case"nw":default:if(this.marginTop==null){this.marginTop=this.yoffset+"px"}this.marginBottom="0px";break}}}this.yoffset=0}};q.prototype.init=function(){if(L.isFunction(this.renderer)){this.renderer=new this.renderer()}this.renderer.init.call(this,this.rendererOptions)};q.prototype.draw=function(ai,aj){for(var ah=0;ah<L.jqplot.preDrawLegendHooks.length;ah++){L.jqplot.preDrawLegendHooks[ah].call(this,ai)}return this.renderer.draw.call(this,ai,aj)};q.prototype.pack=function(ah){this.renderer.pack.call(this,ah)};function y(ah){L.jqplot.ElemContainer.call(this);this.text=ah;this.show=true;this.fontFamily;this.fontSize;this.textAlign;this.textColor;this.renderer=L.jqplot.DivTitleRenderer;this.rendererOptions={};this.escapeHtml=false}y.prototype=new L.jqplot.ElemContainer();y.prototype.constructor=y;y.prototype.init=function(){if(L.isFunction(this.renderer)){this.renderer=new this.renderer()}this.renderer.init.call(this,this.rendererOptions)};y.prototype.draw=function(ah){return this.renderer.draw.call(this,ah)};y.prototype.pack=function(){this.renderer.pack.call(this)};function S(ah){ah=ah||{};L.jqplot.ElemContainer.call(this);this.show=true;this.xaxis="xaxis";this._xaxis;this.yaxis="yaxis";this._yaxis;this.gridBorderWidth=2;this.renderer=L.jqplot.LineRenderer;this.rendererOptions={};this.data=[];this.gridData=[];this.label="";this.showLabel=true;this.color;this.negativeColor;this.lineWidth=2.5;this.lineJoin="round";this.lineCap="round";this.linePattern="solid";this.shadow=true;this.shadowAngle=45;this.shadowOffset=1.25;this.shadowDepth=3;this.shadowAlpha="0.1";this.breakOnNull=false;this.markerRenderer=L.jqplot.MarkerRenderer;this.markerOptions={};this.showLine=true;this.showMarker=true;this.index;this.fill=false;this.fillColor;this.fillAlpha;this.fillAndStroke=false;this.disableStack=false;this._stack=false;this.neighborThreshold=4;this.fillToZero=false;this.fillToValue=0;this.fillAxis="y";this.useNegativeColors=true;this._stackData=[];this._plotData=[];this._plotValues={x:[],y:[]};this._intervals={x:{},y:{}};this._prevPlotData=[];this._prevGridData=[];this._stackAxis="y";this._primaryAxis="_xaxis";this.canvas=new L.jqplot.GenericCanvas();this.shadowCanvas=new L.jqplot.GenericCanvas();this.plugins={};this._sumy=0;this._sumx=0;this._type=""}S.prototype=new L.jqplot.ElemContainer();S.prototype.constructor=S;S.prototype.init=function(ak,ao,am){this.index=ak;this.gridBorderWidth=ao;var an=this.data;var aj=[],al,ah;for(al=0,ah=an.length;al<ah;al++){if(!this.breakOnNull){if(an[al]==null||an[al][0]==null||an[al][1]==null){continue}else{aj.push(an[al])}}else{aj.push(an[al])}}this.data=aj;if(!this.color){this.color=am.colorGenerator.get(this.index)}if(!this.negativeColor){this.negativeColor=am.negativeColorGenerator.get(this.index)}if(!this.fillColor){this.fillColor=this.color}if(this.fillAlpha){var ai=L.jqplot.normalize2rgb(this.fillColor);var ai=L.jqplot.getColorComponents(ai);this.fillColor="rgba("+ai[0]+","+ai[1]+","+ai[2]+","+this.fillAlpha+")"}if(L.isFunction(this.renderer)){this.renderer=new this.renderer()}this.renderer.init.call(this,this.rendererOptions,am);this.markerRenderer=new this.markerRenderer();if(!this.markerOptions.color){this.markerOptions.color=this.color}if(this.markerOptions.show==null){this.markerOptions.show=this.showMarker}this.showMarker=this.markerOptions.show;this.markerRenderer.init(this.markerOptions)};S.prototype.draw=function(an,ak,am){var ai=(ak==u)?{}:ak;an=(an==u)?this.canvas._ctx:an;var ah,al,aj;for(ah=0;ah<L.jqplot.preDrawSeriesHooks.length;ah++){L.jqplot.preDrawSeriesHooks[ah].call(this,an,ai)}if(this.show){this.renderer.setGridData.call(this,am);if(!ai.preventJqPlotSeriesDrawTrigger){L(an.canvas).trigger("jqplotSeriesDraw",[this.data,this.gridData])}al=[];if(ai.data){al=ai.data}else{if(!this._stack){al=this.data}else{al=this._plotData}}aj=ai.gridData||this.renderer.makeGridData.call(this,al,am);if(this._type==="line"&&this.renderer.smooth&&this.renderer._smoothedData.length){aj=this.renderer._smoothedData}this.renderer.draw.call(this,an,aj,ai,am)}for(ah=0;ah<L.jqplot.postDrawSeriesHooks.length;ah++){L.jqplot.postDrawSeriesHooks[ah].call(this,an,ai,am)}an=ak=am=ah=al=aj=null};S.prototype.drawShadow=function(an,ak,am){var ai=(ak==u)?{}:ak;an=(an==u)?this.shadowCanvas._ctx:an;var ah,al,aj;for(ah=0;ah<L.jqplot.preDrawSeriesShadowHooks.length;ah++){L.jqplot.preDrawSeriesShadowHooks[ah].call(this,an,ai)}if(this.shadow){this.renderer.setGridData.call(this,am);al=[];if(ai.data){al=ai.data}else{if(!this._stack){al=this.data}else{al=this._plotData}}aj=ai.gridData||this.renderer.makeGridData.call(this,al,am);this.renderer.drawShadow.call(this,an,aj,ai,am)}for(ah=0;ah<L.jqplot.postDrawSeriesShadowHooks.length;ah++){L.jqplot.postDrawSeriesShadowHooks[ah].call(this,an,ai)}an=ak=am=ah=al=aj=null};S.prototype.toggleDisplay=function(ai,ak){var ah,aj;if(ai.data.series){ah=ai.data.series}else{ah=this}if(ai.data.speed){aj=ai.data.speed}if(aj){if(ah.canvas._elem.is(":hidden")||!ah.show){ah.show=true;ah.canvas._elem.removeClass("jqplot-series-hidden");if(ah.shadowCanvas._elem){ah.shadowCanvas._elem.fadeIn(aj)}ah.canvas._elem.fadeIn(aj,ak);ah.canvas._elem.nextAll(".jqplot-point-label.jqplot-series-"+ah.index).fadeIn(aj)}else{ah.show=false;ah.canvas._elem.addClass("jqplot-series-hidden");if(ah.shadowCanvas._elem){ah.shadowCanvas._elem.fadeOut(aj)}ah.canvas._elem.fadeOut(aj,ak);ah.canvas._elem.nextAll(".jqplot-point-label.jqplot-series-"+ah.index).fadeOut(aj)}}else{if(ah.canvas._elem.is(":hidden")||!ah.show){ah.show=true;ah.canvas._elem.removeClass("jqplot-series-hidden");if(ah.shadowCanvas._elem){ah.shadowCanvas._elem.show()}ah.canvas._elem.show(0,ak);ah.canvas._elem.nextAll(".jqplot-point-label.jqplot-series-"+ah.index).show()}else{ah.show=false;


ah.canvas._elem.addClass("jqplot-series-hidden");if(ah.shadowCanvas._elem){ah.shadowCanvas._elem.hide()}ah.canvas._elem.hide(0,ak);ah.canvas._elem.nextAll(".jqplot-point-label.jqplot-series-"+ah.index).hide()}}};function M(){L.jqplot.ElemContainer.call(this);this.drawGridlines=true;this.gridLineColor="#cccccc";this.gridLineWidth=1;this.background="#fffdf6";this.borderColor="#999999";this.borderWidth=2;this.drawBorder=true;this.shadow=true;this.shadowAngle=45;this.shadowOffset=1.5;this.shadowWidth=3;this.shadowDepth=3;this.shadowColor=null;this.shadowAlpha="0.07";this._left;this._top;this._right;this._bottom;this._width;this._height;this._axes=[];this.renderer=L.jqplot.CanvasGridRenderer;this.rendererOptions={};this._offsets={top:null,bottom:null,left:null,right:null}}M.prototype=new L.jqplot.ElemContainer();M.prototype.constructor=M;M.prototype.init=function(){if(L.isFunction(this.renderer)){this.renderer=new this.renderer()}this.renderer.init.call(this,this.rendererOptions)};M.prototype.createElement=function(ah,ai){this._offsets=ah;return this.renderer.createElement.call(this,ai)};M.prototype.draw=function(){this.renderer.draw.call(this)};L.jqplot.GenericCanvas=function(){L.jqplot.ElemContainer.call(this);this._ctx};L.jqplot.GenericCanvas.prototype=new L.jqplot.ElemContainer();L.jqplot.GenericCanvas.prototype.constructor=L.jqplot.GenericCanvas;L.jqplot.GenericCanvas.prototype.createElement=function(al,aj,ai,am){this._offsets=al;var ah="jqplot";if(aj!=u){ah=aj}var ak;ak=am.canvasManager.getCanvas();if(ai!=null){this._plotDimensions=ai}ak.width=this._plotDimensions.width-this._offsets.left-this._offsets.right;ak.height=this._plotDimensions.height-this._offsets.top-this._offsets.bottom;this._elem=L(ak);this._elem.css({position:"absolute",left:this._offsets.left,top:this._offsets.top});this._elem.addClass(ah);ak=am.canvasManager.initCanvas(ak);ak=null;return this._elem};L.jqplot.GenericCanvas.prototype.setContext=function(){this._ctx=this._elem.get(0).getContext("2d");return this._ctx};L.jqplot.GenericCanvas.prototype.resetCanvas=function(){if(this._elem){if(L.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==u){window.G_vmlCanvasManager.uninitElement(this._elem.get(0))}this._elem.emptyForce()}this._ctx=null};L.jqplot.HooksManager=function(){this.hooks=[];this.args=[]};L.jqplot.HooksManager.prototype.addOnce=function(ak,ai){ai=ai||[];var al=false;for(var aj=0,ah=this.hooks.length;aj<ah;aj++){if(this.hooks[aj]==ak){al=true}}if(!al){this.hooks.push(ak);this.args.push(ai)}};L.jqplot.HooksManager.prototype.add=function(ai,ah){ah=ah||[];this.hooks.push(ai);this.args.push(ah)};L.jqplot.EventListenerManager=function(){this.hooks=[]};L.jqplot.EventListenerManager.prototype.addOnce=function(al,ak){var am=false,aj,ai;for(var ai=0,ah=this.hooks.length;ai<ah;ai++){aj=this.hooks[ai];if(aj[0]==al&&aj[1]==ak){am=true}}if(!am){this.hooks.push([al,ak])}};L.jqplot.EventListenerManager.prototype.add=function(ai,ah){this.hooks.push([ai,ah])};var U=["yMidAxis","xaxis","yaxis","x2axis","y2axis","y3axis","y4axis","y5axis","y6axis","y7axis","y8axis","y9axis"];function R(){this.animate=false;this.animateReplot=false;this.axes={xaxis:new w("xaxis"),yaxis:new w("yaxis"),x2axis:new w("x2axis"),y2axis:new w("y2axis"),y3axis:new w("y3axis"),y4axis:new w("y4axis"),y5axis:new w("y5axis"),y6axis:new w("y6axis"),y7axis:new w("y7axis"),y8axis:new w("y8axis"),y9axis:new w("y9axis"),yMidAxis:new w("yMidAxis")};this.baseCanvas=new L.jqplot.GenericCanvas();this.captureRightClick=false;this.data=[];this.dataRenderer;this.dataRendererOptions;this.defaults={axesDefaults:{},axes:{xaxis:{},yaxis:{},x2axis:{},y2axis:{},y3axis:{},y4axis:{},y5axis:{},y6axis:{},y7axis:{},y8axis:{},y9axis:{},yMidAxis:{}},seriesDefaults:{},series:[]};this.defaultAxisStart=1;this.drawIfHidden=false;this.eventCanvas=new L.jqplot.GenericCanvas();this.fillBetween={series1:null,series2:null,color:null,baseSeries:0,fill:true};this.fontFamily;this.fontSize;this.grid=new M();this.legend=new q();this.noDataIndicator={show:false,indicator:"Loading Data...",axes:{xaxis:{min:0,max:10,tickInterval:2,show:true},yaxis:{min:0,max:12,tickInterval:3,show:true}}};this.negativeSeriesColors=L.jqplot.config.defaultNegativeColors;this.options={};this.previousSeriesStack=[];this.plugins={};this.series=[];this.seriesStack=[];this.seriesColors=L.jqplot.config.defaultColors;this.sortData=true;this.stackSeries=false;this.syncXTicks=true;this.syncYTicks=true;this.target=null;this.targetId=null;this.textColor;this.title=new y();this._drawCount=0;this._sumy=0;this._sumx=0;this._stackData=[];this._plotData=[];this._width=null;this._height=null;this._plotDimensions={height:null,width:null};this._gridPadding={top:null,right:null,bottom:null,left:null};this._defaultGridPadding={top:10,right:10,bottom:23,left:10};this._addDomReference=L.jqplot.config.addDomReference;this.preInitHooks=new L.jqplot.HooksManager();this.postInitHooks=new L.jqplot.HooksManager();this.preParseOptionsHooks=new L.jqplot.HooksManager();this.postParseOptionsHooks=new L.jqplot.HooksManager();this.preDrawHooks=new L.jqplot.HooksManager();this.postDrawHooks=new L.jqplot.HooksManager();this.preDrawSeriesHooks=new L.jqplot.HooksManager();this.postDrawSeriesHooks=new L.jqplot.HooksManager();this.preDrawLegendHooks=new L.jqplot.HooksManager();this.addLegendRowHooks=new L.jqplot.HooksManager();this.preSeriesInitHooks=new L.jqplot.HooksManager();this.postSeriesInitHooks=new L.jqplot.HooksManager();this.preParseSeriesOptionsHooks=new L.jqplot.HooksManager();this.postParseSeriesOptionsHooks=new L.jqplot.HooksManager();this.eventListenerHooks=new L.jqplot.EventListenerManager();this.preDrawSeriesShadowHooks=new L.jqplot.HooksManager();this.postDrawSeriesShadowHooks=new L.jqplot.HooksManager();this.colorGenerator=new L.jqplot.ColorGenerator();this.negativeColorGenerator=new L.jqplot.ColorGenerator();this.canvasManager=new L.jqplot.CanvasManager();this.themeEngine=new L.jqplot.ThemeEngine();var aj=0;this.init=function(av,ar,ay){ay=ay||{};for(var at=0;at<L.jqplot.preInitHooks.length;at++){L.jqplot.preInitHooks[at].call(this,av,ar,ay)}for(var at=0;at<this.preInitHooks.hooks.length;at++){this.preInitHooks.hooks[at].call(this,av,ar,ay)}this.targetId="#"+av;this.target=L("#"+av);if(this._addDomReference){this.target.data("jqplot",this)}this.target.removeClass("jqplot-error");if(!this.target.get(0)){throw"No plot target specified"}if(this.target.css("position")=="static"){this.target.css("position","relative")}if(!this.target.hasClass("jqplot-target")){this.target.addClass("jqplot-target")}if(!this.target.height()){var au;if(ay&&ay.height){au=parseInt(ay.height,10)}else{if(this.target.attr("data-height")){au=parseInt(this.target.attr("data-height"),10)}else{au=parseInt(L.jqplot.config.defaultHeight,10)}}this._height=au;this.target.css("height",au+"px")}else{this._height=au=this.target.height()}if(!this.target.width()){var aw;if(ay&&ay.width){aw=parseInt(ay.width,10)}else{if(this.target.attr("data-width")){aw=parseInt(this.target.attr("data-width"),10)}else{aw=parseInt(L.jqplot.config.defaultWidth,10)}}this._width=aw;this.target.css("width",aw+"px")}else{this._width=aw=this.target.width()}for(var at=0,ap=U.length;at<ap;at++){this.axes[U[at]]=new w(U[at])}this._plotDimensions.height=this._height;this._plotDimensions.width=this._width;this.grid._plotDimensions=this._plotDimensions;this.title._plotDimensions=this._plotDimensions;this.baseCanvas._plotDimensions=this._plotDimensions;this.eventCanvas._plotDimensions=this._plotDimensions;this.legend._plotDimensions=this._plotDimensions;if(this._height<=0||this._width<=0||!this._height||!this._width){throw"Canvas dimension not set"}if(ay.dataRenderer&&L.isFunction(ay.dataRenderer)){if(ay.dataRendererOptions){this.dataRendererOptions=ay.dataRendererOptions}this.dataRenderer=ay.dataRenderer;ar=this.dataRenderer(ar,this,this.dataRendererOptions)}if(ay.noDataIndicator&&L.isPlainObject(ay.noDataIndicator)){L.extend(true,this.noDataIndicator,ay.noDataIndicator)}if(ar==null||L.isArray(ar)==false||ar.length==0||L.isArray(ar[0])==false||ar[0].length==0){if(this.noDataIndicator.show==false){throw"No Data"}else{for(var al in this.noDataIndicator.axes){for(var an in this.noDataIndicator.axes[al]){this.axes[al][an]=this.noDataIndicator.axes[al][an]}}this.postDrawHooks.add(function(){var aD=this.eventCanvas.getHeight();var aA=this.eventCanvas.getWidth();var az=L('<div class="jqplot-noData-container" style="position:absolute;"></div>');this.target.append(az);az.height(aD);az.width(aA);az.css("top",this.eventCanvas._offsets.top);az.css("left",this.eventCanvas._offsets.left);var aC=L('<div class="jqplot-noData-contents" style="text-align:center; position:relative; margin-left:auto; margin-right:auto;"></div>');az.append(aC);aC.html(this.noDataIndicator.indicator);var aB=aC.height();var ax=aC.width();aC.height(aB);aC.width(ax);aC.css("top",(aD-aB)/2+"px")})}}this.data=L.extend(true,[],ar);this.parseOptions(ay);if(this.textColor){this.target.css("color",this.textColor)}if(this.fontFamily){this.target.css("font-family",this.fontFamily)}if(this.fontSize){this.target.css("font-size",this.fontSize)}this.title.init();this.legend.init();this._sumy=0;this._sumx=0;this.computePlotData();for(var at=0;at<this.series.length;at++){this.seriesStack.push(at);this.previousSeriesStack.push(at);this.series[at].shadowCanvas._plotDimensions=this._plotDimensions;this.series[at].canvas._plotDimensions=this._plotDimensions;for(var aq=0;aq<L.jqplot.preSeriesInitHooks.length;aq++){L.jqplot.preSeriesInitHooks[aq].call(this.series[at],av,this.data,this.options.seriesDefaults,this.options.series[at],this)}for(var aq=0;aq<this.preSeriesInitHooks.hooks.length;aq++){this.preSeriesInitHooks.hooks[aq].call(this.series[at],av,this.data,this.options.seriesDefaults,this.options.series[at],this)}this.series[at]._plotDimensions=this._plotDimensions;this.series[at].init(at,this.grid.borderWidth,this);for(var aq=0;aq<L.jqplot.postSeriesInitHooks.length;aq++){L.jqplot.postSeriesInitHooks[aq].call(this.series[at],av,this.data,this.options.seriesDefaults,this.options.series[at],this)}for(var aq=0;aq<this.postSeriesInitHooks.hooks.length;aq++){this.postSeriesInitHooks.hooks[aq].call(this.series[at],av,this.data,this.options.seriesDefaults,this.options.series[at],this)}this._sumy+=this.series[at]._sumy;this._sumx+=this.series[at]._sumx}var am,ao;for(var at=0,ap=U.length;at<ap;at++){am=U[at];ao=this.axes[am];ao._plotDimensions=this._plotDimensions;ao.init();if(this.axes[am].borderColor==null){if(am.charAt(0)!=="x"&&ao.useSeriesColor===true&&ao.show){ao.borderColor=ao._series[0].color}else{ao.borderColor=this.grid.borderColor}}}if(this.sortData){ah(this.series)}this.grid.init();this.grid._axes=this.axes;this.legend._series=this.series;for(var at=0;at<L.jqplot.postInitHooks.length;at++){L.jqplot.postInitHooks[at].call(this,av,this.data,ay)}for(var at=0;at<this.postInitHooks.hooks.length;at++){this.postInitHooks.hooks[at].call(this,av,this.data,ay)}};this.resetAxesScale=function(aq,am){var ao=am||{};var ap=aq||this.axes;if(ap===true){ap=this.axes}if(L.isArray(ap)){for(var an=0;an<ap.length;an++){this.axes[ap[an]].resetScale(ao[ap[an]])}}else{if(typeof(ap)==="object"){for(var al in ap){this.axes[al].resetScale(ao[al])}}}};this.reInitialize=function(au,al){var ay=L.extend(true,{},this.options,al);var aw=this.targetId.substr(1);var ar=(au==null)?this.data:au;for(var av=0;av<L.jqplot.preInitHooks.length;av++){L.jqplot.preInitHooks[av].call(this,aw,ar,ay)}for(var av=0;av<this.preInitHooks.hooks.length;av++){this.preInitHooks.hooks[av].call(this,aw,ar,ay)}this._height=this.target.height();this._width=this.target.width();if(this._height<=0||this._width<=0||!this._height||!this._width){throw"Target dimension not set"}this._plotDimensions.height=this._height;this._plotDimensions.width=this._width;this.grid._plotDimensions=this._plotDimensions;this.title._plotDimensions=this._plotDimensions;this.baseCanvas._plotDimensions=this._plotDimensions;this.eventCanvas._plotDimensions=this._plotDimensions;this.legend._plotDimensions=this._plotDimensions;var am,ax,at,ao;for(var av=0,aq=U.length;av<aq;av++){am=U[av];ao=this.axes[am];ax=ao._ticks;for(var at=0,ap=ax.length;at<ap;at++){var an=ax[at]._elem;if(an){if(L.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==u){window.G_vmlCanvasManager.uninitElement(an.get(0))}an.emptyForce();an=null;ax._elem=null}}ax=null;delete ao.ticks;delete ao._ticks;this.axes[am]=new w(am);this.axes[am]._plotWidth=this._width;this.axes[am]._plotHeight=this._height}if(au){if(ay.dataRenderer&&L.isFunction(ay.dataRenderer)){if(ay.dataRendererOptions){this.dataRendererOptions=ay.dataRendererOptions}this.dataRenderer=ay.dataRenderer;au=this.dataRenderer(au,this,this.dataRendererOptions)}this.data=L.extend(true,[],au)}if(al){this.parseOptions(ay)}this.title._plotWidth=this._width;if(this.textColor){this.target.css("color",this.textColor)}if(this.fontFamily){this.target.css("font-family",this.fontFamily)}if(this.fontSize){this.target.css("font-size",this.fontSize)}this.title.init();this.legend.init();this._sumy=0;this._sumx=0;this.seriesStack=[];this.previousSeriesStack=[];this.computePlotData();for(var av=0,aq=this.series.length;av<aq;av++){this.seriesStack.push(av);this.previousSeriesStack.push(av);this.series[av].shadowCanvas._plotDimensions=this._plotDimensions;this.series[av].canvas._plotDimensions=this._plotDimensions;for(var at=0;at<L.jqplot.preSeriesInitHooks.length;at++){L.jqplot.preSeriesInitHooks[at].call(this.series[av],aw,this.data,this.options.seriesDefaults,this.options.series[av],this)}for(var at=0;at<this.preSeriesInitHooks.hooks.length;at++){this.preSeriesInitHooks.hooks[at].call(this.series[av],aw,this.data,this.options.seriesDefaults,this.options.series[av],this)}this.series[av]._plotDimensions=this._plotDimensions;this.series[av].init(av,this.grid.borderWidth,this);for(var at=0;at<L.jqplot.postSeriesInitHooks.length;at++){L.jqplot.postSeriesInitHooks[at].call(this.series[av],aw,this.data,this.options.seriesDefaults,this.options.series[av],this)}for(var at=0;at<this.postSeriesInitHooks.hooks.length;at++){this.postSeriesInitHooks.hooks[at].call(this.series[av],aw,this.data,this.options.seriesDefaults,this.options.series[av],this)}this._sumy+=this.series[av]._sumy;this._sumx+=this.series[av]._sumx}for(var av=0,aq=U.length;av<aq;av++){am=U[av];ao=this.axes[am];ao._plotDimensions=this._plotDimensions;ao.init();if(ao.borderColor==null){if(am.charAt(0)!=="x"&&ao.useSeriesColor===true&&ao.show){ao.borderColor=ao._series[0].color}else{ao.borderColor=this.grid.borderColor}}}if(this.sortData){ah(this.series)}this.grid.init();this.grid._axes=this.axes;this.legend._series=this.series;for(var av=0,aq=L.jqplot.postInitHooks.length;av<aq;av++){L.jqplot.postInitHooks[av].call(this,aw,this.data,ay)}for(var av=0,aq=this.postInitHooks.hooks.length;av<aq;av++){this.postInitHooks.hooks[av].call(this,aw,this.data,ay)}};this.quickInit=function(){this._height=this.target.height();this._width=this.target.width();if(this._height<=0||this._width<=0||!this._height||!this._width){throw"Target dimension not set"}this._plotDimensions.height=this._height;this._plotDimensions.width=this._width;this.grid._plotDimensions=this._plotDimensions;this.title._plotDimensions=this._plotDimensions;this.baseCanvas._plotDimensions=this._plotDimensions;this.eventCanvas._plotDimensions=this._plotDimensions;this.legend._plotDimensions=this._plotDimensions;for(var aq in this.axes){this.axes[aq]._plotWidth=this._width;this.axes[aq]._plotHeight=this._height}this.title._plotWidth=this._width;if(this.textColor){this.target.css("color",this.textColor)}if(this.fontFamily){this.target.css("font-family",this.fontFamily)}if(this.fontSize){this.target.css("font-size",this.fontSize)}this._sumy=0;this._sumx=0;this.computePlotData();for(var ao=0;ao<this.series.length;ao++){if(this.series[ao]._type==="line"&&this.series[ao].renderer.bands.show){this.series[ao].renderer.initBands.call(this.series[ao],this.series[ao].renderer.options,this)}this.series[ao]._plotDimensions=this._plotDimensions;this.series[ao].canvas._plotDimensions=this._plotDimensions;this._sumy+=this.series[ao]._sumy;this._sumx+=this.series[ao]._sumx}var am;for(var al=0;al<12;al++){am=U[al];var an=this.axes[am]._ticks;for(var ao=0;ao<an.length;ao++){var ap=an[ao]._elem;if(ap){if(L.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==u){window.G_vmlCanvasManager.uninitElement(ap.get(0))}ap.emptyForce();ap=null;an._elem=null}}an=null;this.axes[am]._plotDimensions=this._plotDimensions;this.axes[am]._ticks=[]}if(this.sortData){ah(this.series)}this.grid._axes=this.axes;this.legend._series=this.series};function ah(ap){var au,av,aw,al,at;for(var aq=0;aq<ap.length;aq++){var am;var ar=[ap[aq].data,ap[aq]._stackData,ap[aq]._plotData,ap[aq]._prevPlotData];for(var an=0;an<4;an++){am=true;au=ar[an];if(ap[aq]._stackAxis=="x"){for(var ao=0;ao<au.length;ao++){if(typeof(au[ao][1])!="number"){am=false;break}}if(am){au.sort(function(ay,ax){return ay[1]-ax[1]})}}else{for(var ao=0;ao<au.length;ao++){if(typeof(au[ao][0])!="number"){am=false;break}}if(am){au.sort(function(ay,ax){return ay[0]-ax[0]})}}}}}this.computePlotData=function(){this._plotData=[];this._stackData=[];var at,au,ao;for(au=0,ao=this.series.length;au<ao;au++){at=this.series[au];this._plotData.push([]);this._stackData.push([]);var am=at.data;this._plotData[au]=L.extend(true,[],am);this._stackData[au]=L.extend(true,[],am);at._plotData=this._plotData[au];at._stackData=this._stackData[au];var ax={x:[],y:[]};if(this.stackSeries&&!at.disableStack){at._stack=true;var av=(at._stackAxis==="x")?0:1;for(var ap=0,al=am.length;ap<al;ap++){var aw=am[ap][av];if(aw==null){aw=0}this._plotData[au][ap][av]=aw;this._stackData[au][ap][av]=aw;if(au>0){for(var aq=au;aq--;){var an=this._plotData[aq][ap][av];if(aw*an>=0){this._plotData[au][ap][av]+=an;this._stackData[au][ap][av]+=an;break}}}}}else{for(var ar=0;ar<at.data.length;ar++){ax.x.push(at.data[ar][0]);ax.y.push(at.data[ar][1])}this._stackData.push(at.data);this.series[au]._stackData=at.data;this._plotData.push(at.data);at._plotData=at.data;at._plotValues=ax}if(au>0){at._prevPlotData=this.series[au-1]._plotData}at._sumy=0;at._sumx=0;for(ar=at.data.length-1;ar>-1;ar--){at._sumy+=at.data[ar][1];at._sumx+=at.data[ar][0]}}};this.populatePlotData=function(au,av){this._plotData=[];this._stackData=[];au._stackData=[];


au._plotData=[];var ay={x:[],y:[]};if(this.stackSeries&&!au.disableStack){au._stack=true;var ax=(au._stackAxis==="x")?0:1;var az=L.extend(true,[],au.data);var aA=L.extend(true,[],au.data);var an,am,ao,aw,al;for(var ar=0;ar<av;ar++){var ap=this.series[ar].data;for(var aq=0;aq<ap.length;aq++){ao=ap[aq];an=(ao[0]!=null)?ao[0]:0;am=(ao[1]!=null)?ao[1]:0;az[aq][0]+=an;az[aq][1]+=am;aw=(ax)?am:an;if(au.data[aq][ax]*aw>=0){aA[aq][ax]+=aw}}}for(var at=0;at<aA.length;at++){ay.x.push(aA[at][0]);ay.y.push(aA[at][1])}this._plotData.push(aA);this._stackData.push(az);au._stackData=az;au._plotData=aA;au._plotValues=ay}else{for(var at=0;at<au.data.length;at++){ay.x.push(au.data[at][0]);ay.y.push(au.data[at][1])}this._stackData.push(au.data);this.series[av]._stackData=au.data;this._plotData.push(au.data);au._plotData=au.data;au._plotValues=ay}if(av>0){au._prevPlotData=this.series[av-1]._plotData}au._sumy=0;au._sumx=0;for(at=au.data.length-1;at>-1;at--){au._sumy+=au.data[at][1];au._sumx+=au.data[at][0]}};this.getNextSeriesColor=(function(am){var al=0;var an=am.seriesColors;return function(){if(al<an.length){return an[al++]}else{al=0;return an[al++]}}})(this);this.parseOptions=function(ay){for(var at=0;at<this.preParseOptionsHooks.hooks.length;at++){this.preParseOptionsHooks.hooks[at].call(this,ay)}for(var at=0;at<L.jqplot.preParseOptionsHooks.length;at++){L.jqplot.preParseOptionsHooks[at].call(this,ay)}this.options=L.extend(true,{},this.defaults,ay);var am=this.options;this.animate=am.animate;this.animateReplot=am.animateReplot;this.stackSeries=am.stackSeries;if(L.isPlainObject(am.fillBetween)){var ax=["series1","series2","color","baseSeries","fill"],au;for(var at=0,aq=ax.length;at<aq;at++){au=ax[at];if(am.fillBetween[au]!=null){this.fillBetween[au]=am.fillBetween[au]}}}if(am.seriesColors){this.seriesColors=am.seriesColors}if(am.negativeSeriesColors){this.negativeSeriesColors=am.negativeSeriesColors}if(am.captureRightClick){this.captureRightClick=am.captureRightClick}this.defaultAxisStart=(ay&&ay.defaultAxisStart!=null)?ay.defaultAxisStart:this.defaultAxisStart;this.colorGenerator.setColors(this.seriesColors);this.negativeColorGenerator.setColors(this.negativeSeriesColors);L.extend(true,this._gridPadding,am.gridPadding);this.sortData=(am.sortData!=null)?am.sortData:this.sortData;for(var at=0;at<12;at++){var an=U[at];var ap=this.axes[an];ap._options=L.extend(true,{},am.axesDefaults,am.axes[an]);L.extend(true,ap,am.axesDefaults,am.axes[an]);ap._plotWidth=this._width;ap._plotHeight=this._height}var aw=function(aD,aB,aE){var aA=[];var aC,az;aB=aB||"vertical";if(!L.isArray(aD[0])){for(aC=0,az=aD.length;aC<az;aC++){if(aB=="vertical"){aA.push([aE+aC,aD[aC]])}else{aA.push([aD[aC],aE+aC])}}}else{L.extend(true,aA,aD)}return aA};var av=0;this.series=[];for(var at=0;at<this.data.length;at++){var al=L.extend(true,{index:at},{seriesColors:this.seriesColors,negativeSeriesColors:this.negativeSeriesColors},this.options.seriesDefaults,this.options.series[at],{rendererOptions:{animation:{show:this.animate}}});var ax=new S(al);for(var ar=0;ar<L.jqplot.preParseSeriesOptionsHooks.length;ar++){L.jqplot.preParseSeriesOptionsHooks[ar].call(ax,this.options.seriesDefaults,this.options.series[at])}for(var ar=0;ar<this.preParseSeriesOptionsHooks.hooks.length;ar++){this.preParseSeriesOptionsHooks.hooks[ar].call(ax,this.options.seriesDefaults,this.options.series[at])}L.extend(true,ax,al);var ao="vertical";if(ax.renderer===L.jqplot.BarRenderer&&ax.rendererOptions&&ax.rendererOptions.barDirection=="horizontal"){ao="horizontal";ax._stackAxis="x";ax._primaryAxis="_yaxis"}ax.data=aw(this.data[at],ao,this.defaultAxisStart);switch(ax.xaxis){case"xaxis":ax._xaxis=this.axes.xaxis;break;case"x2axis":ax._xaxis=this.axes.x2axis;break;default:break}ax._yaxis=this.axes[ax.yaxis];ax._xaxis._series.push(ax);ax._yaxis._series.push(ax);if(ax.show){ax._xaxis.show=true;ax._yaxis.show=true}else{if(ax._xaxis.scaleToHiddenSeries){ax._xaxis.show=true}if(ax._yaxis.scaleToHiddenSeries){ax._yaxis.show=true}}if(!ax.label){ax.label="Series "+(at+1).toString()}this.series.push(ax);for(var ar=0;ar<L.jqplot.postParseSeriesOptionsHooks.length;ar++){L.jqplot.postParseSeriesOptionsHooks[ar].call(this.series[at],this.options.seriesDefaults,this.options.series[at])}for(var ar=0;ar<this.postParseSeriesOptionsHooks.hooks.length;ar++){this.postParseSeriesOptionsHooks.hooks[ar].call(this.series[at],this.options.seriesDefaults,this.options.series[at])}}L.extend(true,this.grid,this.options.grid);for(var at=0,aq=U.length;at<aq;at++){var an=U[at];var ap=this.axes[an];if(ap.borderWidth==null){ap.borderWidth=this.grid.borderWidth}}if(typeof this.options.title=="string"){this.title.text=this.options.title}else{if(typeof this.options.title=="object"){L.extend(true,this.title,this.options.title)}}this.title._plotWidth=this._width;this.legend.setOptions(this.options.legend);for(var at=0;at<L.jqplot.postParseOptionsHooks.length;at++){L.jqplot.postParseOptionsHooks[at].call(this,ay)}for(var at=0;at<this.postParseOptionsHooks.hooks.length;at++){this.postParseOptionsHooks.hooks[at].call(this,ay)}};this.destroy=function(){this.canvasManager.freeAllCanvases();if(this.eventCanvas&&this.eventCanvas._elem){this.eventCanvas._elem.unbind()}this.target.empty();this.target[0].innerHTML=""};this.replot=function(am){var an=am||{};var ap=an.data||null;var al=(an.clear===false)?false:true;var ao=an.resetAxes||false;delete an.data;delete an.clear;delete an.resetAxes;this.target.trigger("jqplotPreReplot");if(al){this.destroy()}if(ap||!L.isEmptyObject(an)){this.reInitialize(ap,an)}else{this.quickInit()}if(ao){this.resetAxesScale(ao,an.axes)}this.draw();this.target.trigger("jqplotPostReplot")};this.redraw=function(al){al=(al!=null)?al:true;this.target.trigger("jqplotPreRedraw");if(al){this.canvasManager.freeAllCanvases();this.eventCanvas._elem.unbind();this.target.empty()}for(var an in this.axes){this.axes[an]._ticks=[]}this.computePlotData();this._sumy=0;this._sumx=0;for(var am=0,ao=this.series.length;am<ao;am++){this._sumy+=this.series[am]._sumy;this._sumx+=this.series[am]._sumx}this.draw();this.target.trigger("jqplotPostRedraw")};this.draw=function(){if(this.drawIfHidden||this.target.is(":visible")){this.target.trigger("jqplotPreDraw");var aH,aF,aE,ao;for(aH=0,aE=L.jqplot.preDrawHooks.length;aH<aE;aH++){L.jqplot.preDrawHooks[aH].call(this)}for(aH=0,aE=this.preDrawHooks.hooks.length;aH<aE;aH++){this.preDrawHooks.hooks[aH].apply(this,this.preDrawSeriesHooks.args[aH])}this.target.append(this.baseCanvas.createElement({left:0,right:0,top:0,bottom:0},"jqplot-base-canvas",null,this));this.baseCanvas.setContext();this.target.append(this.title.draw());this.title.pack({top:0,left:0});var aL=this.legend.draw({},this);var al={top:0,left:0,bottom:0,right:0};if(this.legend.placement=="outsideGrid"){this.target.append(aL);switch(this.legend.location){case"n":al.top+=this.legend.getHeight();break;case"s":al.bottom+=this.legend.getHeight();break;case"ne":case"e":case"se":al.right+=this.legend.getWidth();break;case"nw":case"w":case"sw":al.left+=this.legend.getWidth();break;default:al.right+=this.legend.getWidth();break}aL=aL.detach()}var ar=this.axes;var aM;for(aH=0;aH<12;aH++){aM=U[aH];this.target.append(ar[aM].draw(this.baseCanvas._ctx,this));ar[aM].set()}if(ar.yaxis.show){al.left+=ar.yaxis.getWidth()}var aG=["y2axis","y3axis","y4axis","y5axis","y6axis","y7axis","y8axis","y9axis"];var az=[0,0,0,0,0,0,0,0];var aC=0;var aB;for(aB=0;aB<8;aB++){if(ar[aG[aB]].show){aC+=ar[aG[aB]].getWidth();az[aB]=aC}}al.right+=aC;if(ar.x2axis.show){al.top+=ar.x2axis.getHeight()}if(this.title.show){al.top+=this.title.getHeight()}if(ar.xaxis.show){al.bottom+=ar.xaxis.getHeight()}if(this.options.gridDimensions&&L.isPlainObject(this.options.gridDimensions)){var at=parseInt(this.options.gridDimensions.width,10)||0;var aI=parseInt(this.options.gridDimensions.height,10)||0;var an=(this._width-al.left-al.right-at)/2;var aK=(this._height-al.top-al.bottom-aI)/2;if(aK>=0&&an>=0){al.top+=aK;al.bottom+=aK;al.left+=an;al.right+=an}}var am=["top","bottom","left","right"];for(var aB in am){if(this._gridPadding[am[aB]]==null&&al[am[aB]]>0){this._gridPadding[am[aB]]=al[am[aB]]}else{if(this._gridPadding[am[aB]]==null){this._gridPadding[am[aB]]=this._defaultGridPadding[am[aB]]}}}var aA=this._gridPadding;if(this.legend.placement==="outsideGrid"){aA={top:this.title.getHeight(),left:0,right:0,bottom:0};if(this.legend.location==="s"){aA.left=this._gridPadding.left;aA.right=this._gridPadding.right}}ar.xaxis.pack({position:"absolute",bottom:this._gridPadding.bottom-ar.xaxis.getHeight(),left:0,width:this._width},{min:this._gridPadding.left,max:this._width-this._gridPadding.right});ar.yaxis.pack({position:"absolute",top:0,left:this._gridPadding.left-ar.yaxis.getWidth(),height:this._height},{min:this._height-this._gridPadding.bottom,max:this._gridPadding.top});ar.x2axis.pack({position:"absolute",top:this._gridPadding.top-ar.x2axis.getHeight(),left:0,width:this._width},{min:this._gridPadding.left,max:this._width-this._gridPadding.right});for(aH=8;aH>0;aH--){ar[aG[aH-1]].pack({position:"absolute",top:0,right:this._gridPadding.right-az[aH-1]},{min:this._height-this._gridPadding.bottom,max:this._gridPadding.top})}var au=(this._width-this._gridPadding.left-this._gridPadding.right)/2+this._gridPadding.left-ar.yMidAxis.getWidth()/2;ar.yMidAxis.pack({position:"absolute",top:0,left:au,zIndex:9,textAlign:"center"},{min:this._height-this._gridPadding.bottom,max:this._gridPadding.top});this.target.append(this.grid.createElement(this._gridPadding,this));this.grid.draw();var aq=this.series;var aJ=aq.length;for(aH=0,aE=aJ;aH<aE;aH++){aF=this.seriesStack[aH];this.target.append(aq[aF].shadowCanvas.createElement(this._gridPadding,"jqplot-series-shadowCanvas",null,this));aq[aF].shadowCanvas.setContext();aq[aF].shadowCanvas._elem.data("seriesIndex",aF)}for(aH=0,aE=aJ;aH<aE;aH++){aF=this.seriesStack[aH];this.target.append(aq[aF].canvas.createElement(this._gridPadding,"jqplot-series-canvas",null,this));aq[aF].canvas.setContext();aq[aF].canvas._elem.data("seriesIndex",aF)}this.target.append(this.eventCanvas.createElement(this._gridPadding,"jqplot-event-canvas",null,this));this.eventCanvas.setContext();this.eventCanvas._ctx.fillStyle="rgba(0,0,0,0)";this.eventCanvas._ctx.fillRect(0,0,this.eventCanvas._ctx.canvas.width,this.eventCanvas._ctx.canvas.height);this.bindCustomEvents();if(this.legend.preDraw){this.eventCanvas._elem.before(aL);this.legend.pack(aA);if(this.legend._elem){this.drawSeries({legendInfo:{location:this.legend.location,placement:this.legend.placement,width:this.legend.getWidth(),height:this.legend.getHeight(),xoffset:this.legend.xoffset,yoffset:this.legend.yoffset}})}else{this.drawSeries()}}else{this.drawSeries();if(aJ){L(aq[aJ-1].canvas._elem).after(aL)}this.legend.pack(aA)}for(var aH=0,aE=L.jqplot.eventListenerHooks.length;aH<aE;aH++){this.eventCanvas._elem.bind(L.jqplot.eventListenerHooks[aH][0],{plot:this},L.jqplot.eventListenerHooks[aH][1])}for(var aH=0,aE=this.eventListenerHooks.hooks.length;aH<aE;aH++){this.eventCanvas._elem.bind(this.eventListenerHooks.hooks[aH][0],{plot:this},this.eventListenerHooks.hooks[aH][1])}var ay=this.fillBetween;if(ay.fill&&ay.series1!==ay.series2&&ay.series1<aJ&&ay.series2<aJ&&aq[ay.series1]._type==="line"&&aq[ay.series2]._type==="line"){this.doFillBetweenLines()}for(var aH=0,aE=L.jqplot.postDrawHooks.length;aH<aE;aH++){L.jqplot.postDrawHooks[aH].call(this)}for(var aH=0,aE=this.postDrawHooks.hooks.length;aH<aE;aH++){this.postDrawHooks.hooks[aH].apply(this,this.postDrawHooks.args[aH])}if(this.target.is(":visible")){this._drawCount+=1}var av,aw,aD,ap;for(aH=0,aE=aJ;aH<aE;aH++){av=aq[aH];aw=av.renderer;aD=".jqplot-point-label.jqplot-series-"+aH;if(aw.animation&&aw.animation._supported&&aw.animation.show&&(this._drawCount<2||this.animateReplot)){ap=this.target.find(aD);ap.stop(true,true).hide();av.canvas._elem.stop(true,true).hide();av.shadowCanvas._elem.stop(true,true).hide();av.canvas._elem.jqplotEffect("blind",{mode:"show",direction:aw.animation.direction},aw.animation.speed);av.shadowCanvas._elem.jqplotEffect("blind",{mode:"show",direction:aw.animation.direction},aw.animation.speed);ap.fadeIn(aw.animation.speed*0.8)}}ap=null;this.target.trigger("jqplotPostDraw",[this])}};R.prototype.doFillBetweenLines=function(){var an=this.fillBetween;var ax=an.series1;var av=an.series2;var aw=(ax<av)?ax:av;var au=(av>ax)?av:ax;var ar=this.series[aw];var aq=this.series[au];if(aq.renderer.smooth){var ap=aq.renderer._smoothedData.slice(0).reverse()}else{var ap=aq.gridData.slice(0).reverse()}if(ar.renderer.smooth){var at=ar.renderer._smoothedData.concat(ap)}else{var at=ar.gridData.concat(ap)}var ao=(an.color!==null)?an.color:this.series[ax].fillColor;var ay=(an.baseSeries!==null)?an.baseSeries:aw;var am=this.series[ay].renderer.shapeRenderer;var al={fillStyle:ao,fill:true,closePath:true};am.draw(ar.shadowCanvas._ctx,at,al)};this.bindCustomEvents=function(){this.eventCanvas._elem.bind("click",{plot:this},this.onClick);this.eventCanvas._elem.bind("dblclick",{plot:this},this.onDblClick);this.eventCanvas._elem.bind("mousedown",{plot:this},this.onMouseDown);this.eventCanvas._elem.bind("mousemove",{plot:this},this.onMouseMove);this.eventCanvas._elem.bind("mouseenter",{plot:this},this.onMouseEnter);this.eventCanvas._elem.bind("mouseleave",{plot:this},this.onMouseLeave);if(this.captureRightClick){this.eventCanvas._elem.bind("mouseup",{plot:this},this.onRightClick);this.eventCanvas._elem.get(0).oncontextmenu=function(){return false}}else{this.eventCanvas._elem.bind("mouseup",{plot:this},this.onMouseUp)}};function ai(av){var au=av.data.plot;var ap=au.eventCanvas._elem.offset();var at={x:av.pageX-ap.left,y:av.pageY-ap.top};var aq={xaxis:null,yaxis:null,x2axis:null,y2axis:null,y3axis:null,y4axis:null,y5axis:null,y6axis:null,y7axis:null,y8axis:null,y9axis:null,yMidAxis:null};var ar=["xaxis","yaxis","x2axis","y2axis","y3axis","y4axis","y5axis","y6axis","y7axis","y8axis","y9axis","yMidAxis"];var al=au.axes;var am,ao;for(am=11;am>0;am--){ao=ar[am-1];if(al[ao].show){aq[ao]=al[ao].series_p2u(at[ao.charAt(0)])}}return{offsets:ap,gridPos:at,dataPos:aq}}function ak(al,am){var aq=am.series;var aW,aU,aT,aO,aP,aJ,aI,aw,au,az,aA,aK;var aS,aX,aQ,ar,aH,aM,aV;var an,aN;for(aT=am.seriesStack.length-1;aT>=0;aT--){aW=am.seriesStack[aT];aO=aq[aW];aV=aO._highlightThreshold;switch(aO.renderer.constructor){case L.jqplot.BarRenderer:aJ=al.x;aI=al.y;for(aU=0;aU<aO._barPoints.length;aU++){aH=aO._barPoints[aU];aQ=aO.gridData[aU];if(aJ>aH[0][0]&&aJ<aH[2][0]&&aI>aH[2][1]&&aI<aH[0][1]){return{seriesIndex:aO.index,pointIndex:aU,gridData:aQ,data:aO.data[aU],points:aO._barPoints[aU]}}}break;case L.jqplot.PyramidRenderer:aJ=al.x;aI=al.y;for(aU=0;aU<aO._barPoints.length;aU++){aH=aO._barPoints[aU];aQ=aO.gridData[aU];if(aJ>aH[0][0]+aV[0][0]&&aJ<aH[2][0]+aV[2][0]&&aI>aH[2][1]&&aI<aH[0][1]){return{seriesIndex:aO.index,pointIndex:aU,gridData:aQ,data:aO.data[aU],points:aO._barPoints[aU]}}}break;case L.jqplot.DonutRenderer:az=aO.startAngle/180*Math.PI;aJ=al.x-aO._center[0];aI=al.y-aO._center[1];aP=Math.sqrt(Math.pow(aJ,2)+Math.pow(aI,2));if(aJ>0&&-aI>=0){aw=2*Math.PI-Math.atan(-aI/aJ)}else{if(aJ>0&&-aI<0){aw=-Math.atan(-aI/aJ)}else{if(aJ<0){aw=Math.PI-Math.atan(-aI/aJ)}else{if(aJ==0&&-aI>0){aw=3*Math.PI/2}else{if(aJ==0&&-aI<0){aw=Math.PI/2}else{if(aJ==0&&aI==0){aw=0}}}}}}if(az){aw-=az;if(aw<0){aw+=2*Math.PI}else{if(aw>2*Math.PI){aw-=2*Math.PI}}}au=aO.sliceMargin/180*Math.PI;if(aP<aO._radius&&aP>aO._innerRadius){for(aU=0;aU<aO.gridData.length;aU++){aA=(aU>0)?aO.gridData[aU-1][1]+au:au;aK=aO.gridData[aU][1];if(aw>aA&&aw<aK){return{seriesIndex:aO.index,pointIndex:aU,gridData:aO.gridData[aU],data:aO.data[aU]}}}}break;case L.jqplot.PieRenderer:az=aO.startAngle/180*Math.PI;aJ=al.x-aO._center[0];aI=al.y-aO._center[1];aP=Math.sqrt(Math.pow(aJ,2)+Math.pow(aI,2));if(aJ>0&&-aI>=0){aw=2*Math.PI-Math.atan(-aI/aJ)}else{if(aJ>0&&-aI<0){aw=-Math.atan(-aI/aJ)}else{if(aJ<0){aw=Math.PI-Math.atan(-aI/aJ)}else{if(aJ==0&&-aI>0){aw=3*Math.PI/2}else{if(aJ==0&&-aI<0){aw=Math.PI/2}else{if(aJ==0&&aI==0){aw=0}}}}}}if(az){aw-=az;if(aw<0){aw+=2*Math.PI}else{if(aw>2*Math.PI){aw-=2*Math.PI}}}au=aO.sliceMargin/180*Math.PI;if(aP<aO._radius){for(aU=0;aU<aO.gridData.length;aU++){aA=(aU>0)?aO.gridData[aU-1][1]+au:au;aK=aO.gridData[aU][1];if(aw>aA&&aw<aK){return{seriesIndex:aO.index,pointIndex:aU,gridData:aO.gridData[aU],data:aO.data[aU]}}}}break;case L.jqplot.BubbleRenderer:aJ=al.x;aI=al.y;var aF=null;if(aO.show){for(var aU=0;aU<aO.gridData.length;aU++){aQ=aO.gridData[aU];aX=Math.sqrt((aJ-aQ[0])*(aJ-aQ[0])+(aI-aQ[1])*(aI-aQ[1]));if(aX<=aQ[2]&&(aX<=aS||aS==null)){aS=aX;aF={seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}if(aF!=null){return aF}}break;case L.jqplot.FunnelRenderer:aJ=al.x;aI=al.y;var aL=aO._vertices,ap=aL[0],ao=aL[aL.length-1],at,aE,ay;function aR(a0,a2,a1){var aZ=(a2[1]-a1[1])/(a2[0]-a1[0]);var aY=a2[1]-aZ*a2[0];var a3=a0+a2[1];return[(a3-aY)/aZ,a3]}at=aR(aI,ap[0],ao[3]);aE=aR(aI,ap[1],ao[2]);for(aU=0;aU<aL.length;aU++){ay=aL[aU];if(aI>=ay[0][1]&&aI<=ay[3][1]&&aJ>=at[0]&&aJ<=aE[0]){return{seriesIndex:aO.index,pointIndex:aU,gridData:null,data:aO.data[aU]}}}break;case L.jqplot.LineRenderer:aJ=al.x;aI=al.y;aP=aO.renderer;if(aO.show){if((aO.fill||(aO.renderer.bands.show&&aO.renderer.bands.fill))&&(!am.plugins.highlighter||!am.plugins.highlighter.show)){var ax=false;if(aJ>aO._boundingBox[0][0]&&aJ<aO._boundingBox[1][0]&&aI>aO._boundingBox[1][1]&&aI<aO._boundingBox[0][1]){var aD=aO._areaPoints.length;var aG;var aU=aD-1;for(var aG=0;aG<aD;aG++){var aC=[aO._areaPoints[aG][0],aO._areaPoints[aG][1]];var aB=[aO._areaPoints[aU][0],aO._areaPoints[aU][1]];if(aC[1]<aI&&aB[1]>=aI||aB[1]<aI&&aC[1]>=aI){if(aC[0]+(aI-aC[1])/(aB[1]-aC[1])*(aB[0]-aC[0])<aJ){ax=!ax}}aU=aG}}if(ax){return{seriesIndex:aW,pointIndex:null,gridData:aO.gridData,data:aO.data,points:aO._areaPoints}}break}else{aN=aO.markerRenderer.size/2+aO.neighborThreshold;an=(aN>0)?aN:0;for(var aU=0;aU<aO.gridData.length;aU++){aQ=aO.gridData[aU];if(aP.constructor==L.jqplot.OHLCRenderer){if(aP.candleStick){var av=aO._yaxis.series_u2p;if(aJ>=aQ[0]-aP._bodyWidth/2&&aJ<=aQ[0]+aP._bodyWidth/2&&aI>=av(aO.data[aU][2])&&aI<=av(aO.data[aU][3])){return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}else{if(!aP.hlc){var av=aO._yaxis.series_u2p;if(aJ>=aQ[0]-aP._tickLength&&aJ<=aQ[0]+aP._tickLength&&aI>=av(aO.data[aU][2])&&aI<=av(aO.data[aU][3])){return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}else{var av=aO._yaxis.series_u2p;if(aJ>=aQ[0]-aP._tickLength&&aJ<=aQ[0]+aP._tickLength&&aI>=av(aO.data[aU][1])&&aI<=av(aO.data[aU][2])){return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}}}else{if(aQ[0]!=null&&aQ[1]!=null){aX=Math.sqrt((aJ-aQ[0])*(aJ-aQ[0])+(aI-aQ[1])*(aI-aQ[1]));if(aX<=an&&(aX<=aS||aS==null)){aS=aX;return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}}}}}break;default:aJ=al.x;aI=al.y;aP=aO.renderer;if(aO.show){aN=aO.markerRenderer.size/2+aO.neighborThreshold;an=(aN>0)?aN:0;for(var aU=0;aU<aO.gridData.length;aU++){aQ=aO.gridData[aU];if(aP.constructor==L.jqplot.OHLCRenderer){if(aP.candleStick){var av=aO._yaxis.series_u2p;if(aJ>=aQ[0]-aP._bodyWidth/2&&aJ<=aQ[0]+aP._bodyWidth/2&&aI>=av(aO.data[aU][2])&&aI<=av(aO.data[aU][3])){return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}else{if(!aP.hlc){var av=aO._yaxis.series_u2p;if(aJ>=aQ[0]-aP._tickLength&&aJ<=aQ[0]+aP._tickLength&&aI>=av(aO.data[aU][2])&&aI<=av(aO.data[aU][3])){return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}else{var av=aO._yaxis.series_u2p;if(aJ>=aQ[0]-aP._tickLength&&aJ<=aQ[0]+aP._tickLength&&aI>=av(aO.data[aU][1])&&aI<=av(aO.data[aU][2])){return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}}}else{aX=Math.sqrt((aJ-aQ[0])*(aJ-aQ[0])+(aI-aQ[1])*(aI-aQ[1]));if(aX<=an&&(aX<=aS||aS==null)){aS=aX;return{seriesIndex:aW,pointIndex:aU,gridData:aQ,data:aO.data[aU]}}}}}break}}return null}this.onClick=function(an){var am=ai(an);


var ap=an.data.plot;var ao=ak(am.gridPos,ap);var al=L.Event("jqplotClick");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,ao,ap])};this.onDblClick=function(an){var am=ai(an);var ap=an.data.plot;var ao=ak(am.gridPos,ap);var al=L.Event("jqplotDblClick");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,ao,ap])};this.onMouseDown=function(an){var am=ai(an);var ap=an.data.plot;var ao=ak(am.gridPos,ap);var al=L.Event("jqplotMouseDown");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,ao,ap])};this.onMouseUp=function(an){var am=ai(an);var al=L.Event("jqplotMouseUp");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,null,an.data.plot])};this.onRightClick=function(an){var am=ai(an);var ap=an.data.plot;var ao=ak(am.gridPos,ap);if(ap.captureRightClick){if(an.which==3){var al=L.Event("jqplotRightClick");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,ao,ap])}else{var al=L.Event("jqplotMouseUp");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,ao,ap])}}};this.onMouseMove=function(an){var am=ai(an);var ap=an.data.plot;var ao=ak(am.gridPos,ap);var al=L.Event("jqplotMouseMove");al.pageX=an.pageX;al.pageY=an.pageY;L(this).trigger(al,[am.gridPos,am.dataPos,ao,ap])};this.onMouseEnter=function(an){var am=ai(an);var ao=an.data.plot;var al=L.Event("jqplotMouseEnter");al.pageX=an.pageX;al.pageY=an.pageY;al.relatedTarget=an.relatedTarget;L(this).trigger(al,[am.gridPos,am.dataPos,null,ao])};this.onMouseLeave=function(an){var am=ai(an);var ao=an.data.plot;var al=L.Event("jqplotMouseLeave");al.pageX=an.pageX;al.pageY=an.pageY;al.relatedTarget=an.relatedTarget;L(this).trigger(al,[am.gridPos,am.dataPos,null,ao])};this.drawSeries=function(an,al){var ap,ao,am;al=(typeof(an)==="number"&&al==null)?an:al;an=(typeof(an)==="object")?an:{};if(al!=u){ao=this.series[al];am=ao.shadowCanvas._ctx;am.clearRect(0,0,am.canvas.width,am.canvas.height);ao.drawShadow(am,an,this);am=ao.canvas._ctx;am.clearRect(0,0,am.canvas.width,am.canvas.height);ao.draw(am,an,this);if(ao.renderer.constructor==L.jqplot.BezierCurveRenderer){if(al<this.series.length-1){this.drawSeries(al+1)}}}else{for(ap=0;ap<this.series.length;ap++){ao=this.series[ap];am=ao.shadowCanvas._ctx;am.clearRect(0,0,am.canvas.width,am.canvas.height);ao.drawShadow(am,an,this);am=ao.canvas._ctx;am.clearRect(0,0,am.canvas.width,am.canvas.height);ao.draw(am,an,this)}}an=al=ap=ao=am=null};this.moveSeriesToFront=function(am){am=parseInt(am,10);var ap=L.inArray(am,this.seriesStack);if(ap==-1){return}if(ap==this.seriesStack.length-1){this.previousSeriesStack=this.seriesStack.slice(0);return}var al=this.seriesStack[this.seriesStack.length-1];var ao=this.series[am].canvas._elem.detach();var an=this.series[am].shadowCanvas._elem.detach();this.series[al].shadowCanvas._elem.after(an);this.series[al].canvas._elem.after(ao);this.previousSeriesStack=this.seriesStack.slice(0);this.seriesStack.splice(ap,1);this.seriesStack.push(am)};this.moveSeriesToBack=function(am){am=parseInt(am,10);var ap=L.inArray(am,this.seriesStack);if(ap==0||ap==-1){return}var al=this.seriesStack[0];var ao=this.series[am].canvas._elem.detach();var an=this.series[am].shadowCanvas._elem.detach();this.series[al].shadowCanvas._elem.before(an);this.series[al].canvas._elem.before(ao);this.previousSeriesStack=this.seriesStack.slice(0);this.seriesStack.splice(ap,1);this.seriesStack.unshift(am)};this.restorePreviousSeriesOrder=function(){var ar,aq,ap,ao,an,al,am;if(this.seriesStack==this.previousSeriesStack){return}for(ar=1;ar<this.previousSeriesStack.length;ar++){al=this.previousSeriesStack[ar];am=this.previousSeriesStack[ar-1];ap=this.series[al].canvas._elem.detach();ao=this.series[al].shadowCanvas._elem.detach();this.series[am].shadowCanvas._elem.after(ao);this.series[am].canvas._elem.after(ap)}an=this.seriesStack.slice(0);this.seriesStack=this.previousSeriesStack.slice(0);this.previousSeriesStack=an};this.restoreOriginalSeriesOrder=function(){var ap,ao,al=[],an,am;for(ap=0;ap<this.series.length;ap++){al.push(ap)}if(this.seriesStack==al){return}this.previousSeriesStack=this.seriesStack.slice(0);this.seriesStack=al;for(ap=1;ap<this.seriesStack.length;ap++){an=this.series[ap].canvas._elem.detach();am=this.series[ap].shadowCanvas._elem.detach();this.series[ap-1].shadowCanvas._elem.after(am);this.series[ap-1].canvas._elem.after(an)}};this.activateTheme=function(al){this.themeEngine.activate(this,al)}}L.jqplot.computeHighlightColors=function(ai){var ak;if(L.isArray(ai)){ak=[];for(var am=0;am<ai.length;am++){var al=L.jqplot.getColorComponents(ai[am]);var ah=[al[0],al[1],al[2]];var an=ah[0]+ah[1]+ah[2];for(var aj=0;aj<3;aj++){ah[aj]=(an>660)?ah[aj]*0.85:0.73*ah[aj]+90;ah[aj]=parseInt(ah[aj],10);(ah[aj]>255)?255:ah[aj]}ah[3]=0.3+0.35*al[3];ak.push("rgba("+ah[0]+","+ah[1]+","+ah[2]+","+ah[3]+")")}}else{var al=L.jqplot.getColorComponents(ai);var ah=[al[0],al[1],al[2]];var an=ah[0]+ah[1]+ah[2];for(var aj=0;aj<3;aj++){ah[aj]=(an>660)?ah[aj]*0.85:0.73*ah[aj]+90;ah[aj]=parseInt(ah[aj],10);(ah[aj]>255)?255:ah[aj]}ah[3]=0.3+0.35*al[3];ak="rgba("+ah[0]+","+ah[1]+","+ah[2]+","+ah[3]+")"}return ak};L.jqplot.ColorGenerator=function(ai){ai=ai||L.jqplot.config.defaultColors;var ah=0;this.next=function(){if(ah<ai.length){return ai[ah++]}else{ah=0;return ai[ah++]}};this.previous=function(){if(ah>0){return ai[ah--]}else{ah=ai.length-1;return ai[ah]}};this.get=function(ak){var aj=ak-ai.length*Math.floor(ak/ai.length);return ai[aj]};this.setColors=function(aj){ai=aj};this.reset=function(){ah=0};this.getIndex=function(){return ah};this.setIndex=function(aj){ah=aj}};L.jqplot.hex2rgb=function(aj,ah){aj=aj.replace("#","");if(aj.length==3){aj=aj.charAt(0)+aj.charAt(0)+aj.charAt(1)+aj.charAt(1)+aj.charAt(2)+aj.charAt(2)}var ai;ai="rgba("+parseInt(aj.slice(0,2),16)+", "+parseInt(aj.slice(2,4),16)+", "+parseInt(aj.slice(4,6),16);if(ah){ai+=", "+ah}ai+=")";return ai};L.jqplot.rgb2hex=function(am){var aj=/rgba?\( *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *(?:, *[0-9.]*)?\)/;var ah=am.match(aj);var al="#";for(var ak=1;ak<4;ak++){var ai;if(ah[ak].search(/%/)!=-1){ai=parseInt(255*ah[ak]/100,10).toString(16);if(ai.length==1){ai="0"+ai}}else{ai=parseInt(ah[ak],10).toString(16);if(ai.length==1){ai="0"+ai}}al+=ai}return al};L.jqplot.normalize2rgb=function(ai,ah){if(ai.search(/^ *rgba?\(/)!=-1){return ai}else{if(ai.search(/^ *#?[0-9a-fA-F]?[0-9a-fA-F]/)!=-1){return L.jqplot.hex2rgb(ai,ah)}else{throw"invalid color spec"}}};L.jqplot.getColorComponents=function(am){am=L.jqplot.colorKeywordMap[am]||am;var ak=L.jqplot.normalize2rgb(am);var aj=/rgba?\( *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *,? *([0-9.]* *)?\)/;var ah=ak.match(aj);var ai=[];for(var al=1;al<4;al++){if(ah[al].search(/%/)!=-1){ai[al-1]=parseInt(255*ah[al]/100,10)}else{ai[al-1]=parseInt(ah[al],10)}}ai[3]=parseFloat(ah[4])?parseFloat(ah[4]):1;return ai};L.jqplot.colorKeywordMap={aliceblue:"rgb(240, 248, 255)",antiquewhite:"rgb(250, 235, 215)",aqua:"rgb( 0, 255, 255)",aquamarine:"rgb(127, 255, 212)",azure:"rgb(240, 255, 255)",beige:"rgb(245, 245, 220)",bisque:"rgb(255, 228, 196)",black:"rgb( 0, 0, 0)",blanchedalmond:"rgb(255, 235, 205)",blue:"rgb( 0, 0, 255)",blueviolet:"rgb(138, 43, 226)",brown:"rgb(165, 42, 42)",burlywood:"rgb(222, 184, 135)",cadetblue:"rgb( 95, 158, 160)",chartreuse:"rgb(127, 255, 0)",chocolate:"rgb(210, 105, 30)",coral:"rgb(255, 127, 80)",cornflowerblue:"rgb(100, 149, 237)",cornsilk:"rgb(255, 248, 220)",crimson:"rgb(220, 20, 60)",cyan:"rgb( 0, 255, 255)",darkblue:"rgb( 0, 0, 139)",darkcyan:"rgb( 0, 139, 139)",darkgoldenrod:"rgb(184, 134, 11)",darkgray:"rgb(169, 169, 169)",darkgreen:"rgb( 0, 100, 0)",darkgrey:"rgb(169, 169, 169)",darkkhaki:"rgb(189, 183, 107)",darkmagenta:"rgb(139, 0, 139)",darkolivegreen:"rgb( 85, 107, 47)",darkorange:"rgb(255, 140, 0)",darkorchid:"rgb(153, 50, 204)",darkred:"rgb(139, 0, 0)",darksalmon:"rgb(233, 150, 122)",darkseagreen:"rgb(143, 188, 143)",darkslateblue:"rgb( 72, 61, 139)",darkslategray:"rgb( 47, 79, 79)",darkslategrey:"rgb( 47, 79, 79)",darkturquoise:"rgb( 0, 206, 209)",darkviolet:"rgb(148, 0, 211)",deeppink:"rgb(255, 20, 147)",deepskyblue:"rgb( 0, 191, 255)",dimgray:"rgb(105, 105, 105)",dimgrey:"rgb(105, 105, 105)",dodgerblue:"rgb( 30, 144, 255)",firebrick:"rgb(178, 34, 34)",floralwhite:"rgb(255, 250, 240)",forestgreen:"rgb( 34, 139, 34)",fuchsia:"rgb(255, 0, 255)",gainsboro:"rgb(220, 220, 220)",ghostwhite:"rgb(248, 248, 255)",gold:"rgb(255, 215, 0)",goldenrod:"rgb(218, 165, 32)",gray:"rgb(128, 128, 128)",grey:"rgb(128, 128, 128)",green:"rgb( 0, 128, 0)",greenyellow:"rgb(173, 255, 47)",honeydew:"rgb(240, 255, 240)",hotpink:"rgb(255, 105, 180)",indianred:"rgb(205, 92, 92)",indigo:"rgb( 75, 0, 130)",ivory:"rgb(255, 255, 240)",khaki:"rgb(240, 230, 140)",lavender:"rgb(230, 230, 250)",lavenderblush:"rgb(255, 240, 245)",lawngreen:"rgb(124, 252, 0)",lemonchiffon:"rgb(255, 250, 205)",lightblue:"rgb(173, 216, 230)",lightcoral:"rgb(240, 128, 128)",lightcyan:"rgb(224, 255, 255)",lightgoldenrodyellow:"rgb(250, 250, 210)",lightgray:"rgb(211, 211, 211)",lightgreen:"rgb(144, 238, 144)",lightgrey:"rgb(211, 211, 211)",lightpink:"rgb(255, 182, 193)",lightsalmon:"rgb(255, 160, 122)",lightseagreen:"rgb( 32, 178, 170)",lightskyblue:"rgb(135, 206, 250)",lightslategray:"rgb(119, 136, 153)",lightslategrey:"rgb(119, 136, 153)",lightsteelblue:"rgb(176, 196, 222)",lightyellow:"rgb(255, 255, 224)",lime:"rgb( 0, 255, 0)",limegreen:"rgb( 50, 205, 50)",linen:"rgb(250, 240, 230)",magenta:"rgb(255, 0, 255)",maroon:"rgb(128, 0, 0)",mediumaquamarine:"rgb(102, 205, 170)",mediumblue:"rgb( 0, 0, 205)",mediumorchid:"rgb(186, 85, 211)",mediumpurple:"rgb(147, 112, 219)",mediumseagreen:"rgb( 60, 179, 113)",mediumslateblue:"rgb(123, 104, 238)",mediumspringgreen:"rgb( 0, 250, 154)",mediumturquoise:"rgb( 72, 209, 204)",mediumvioletred:"rgb(199, 21, 133)",midnightblue:"rgb( 25, 25, 112)",mintcream:"rgb(245, 255, 250)",mistyrose:"rgb(255, 228, 225)",moccasin:"rgb(255, 228, 181)",navajowhite:"rgb(255, 222, 173)",navy:"rgb( 0, 0, 128)",oldlace:"rgb(253, 245, 230)",olive:"rgb(128, 128, 0)",olivedrab:"rgb(107, 142, 35)",orange:"rgb(255, 165, 0)",orangered:"rgb(255, 69, 0)",orchid:"rgb(218, 112, 214)",palegoldenrod:"rgb(238, 232, 170)",palegreen:"rgb(152, 251, 152)",paleturquoise:"rgb(175, 238, 238)",palevioletred:"rgb(219, 112, 147)",papayawhip:"rgb(255, 239, 213)",peachpuff:"rgb(255, 218, 185)",peru:"rgb(205, 133, 63)",pink:"rgb(255, 192, 203)",plum:"rgb(221, 160, 221)",powderblue:"rgb(176, 224, 230)",purple:"rgb(128, 0, 128)",red:"rgb(255, 0, 0)",rosybrown:"rgb(188, 143, 143)",royalblue:"rgb( 65, 105, 225)",saddlebrown:"rgb(139, 69, 19)",salmon:"rgb(250, 128, 114)",sandybrown:"rgb(244, 164, 96)",seagreen:"rgb( 46, 139, 87)",seashell:"rgb(255, 245, 238)",sienna:"rgb(160, 82, 45)",silver:"rgb(192, 192, 192)",skyblue:"rgb(135, 206, 235)",slateblue:"rgb(106, 90, 205)",slategray:"rgb(112, 128, 144)",slategrey:"rgb(112, 128, 144)",snow:"rgb(255, 250, 250)",springgreen:"rgb( 0, 255, 127)",steelblue:"rgb( 70, 130, 180)",tan:"rgb(210, 180, 140)",teal:"rgb( 0, 128, 128)",thistle:"rgb(216, 191, 216)",tomato:"rgb(255, 99, 71)",turquoise:"rgb( 64, 224, 208)",violet:"rgb(238, 130, 238)",wheat:"rgb(245, 222, 179)",white:"rgb(255, 255, 255)",whitesmoke:"rgb(245, 245, 245)",yellow:"rgb(255, 255, 0)",yellowgreen:"rgb(154, 205, 50)"};L.jqplot.AxisLabelRenderer=function(ah){L.jqplot.ElemContainer.call(this);this.axis;this.show=true;this.label="";this.fontFamily=null;this.fontSize=null;this.textColor=null;this._elem;this.escapeHTML=false;L.extend(true,this,ah)};L.jqplot.AxisLabelRenderer.prototype=new L.jqplot.ElemContainer();L.jqplot.AxisLabelRenderer.prototype.constructor=L.jqplot.AxisLabelRenderer;L.jqplot.AxisLabelRenderer.prototype.init=function(ah){L.extend(true,this,ah)};L.jqplot.AxisLabelRenderer.prototype.draw=function(ah,ai){if(this._elem){this._elem.emptyForce();this._elem=null}this._elem=L('<div style="position:absolute;" class="jqplot-'+this.axis+'-label"></div>');if(Number(this.label)){this._elem.css("white-space","nowrap")}if(!this.escapeHTML){this._elem.html(this.label)}else{this._elem.text(this.label)}if(this.fontFamily){this._elem.css("font-family",this.fontFamily)}if(this.fontSize){this._elem.css("font-size",this.fontSize)}if(this.textColor){this._elem.css("color",this.textColor)}return this._elem};L.jqplot.AxisLabelRenderer.prototype.pack=function(){};L.jqplot.AxisTickRenderer=function(ah){L.jqplot.ElemContainer.call(this);this.mark="outside";this.axis;this.showMark=true;this.showGridline=true;this.isMinorTick=false;this.size=4;this.markSize=6;this.show=true;this.showLabel=true;this.label=null;this.value=null;this._styles={};this.formatter=L.jqplot.DefaultTickFormatter;this.prefix="";this.suffix="";this.formatString="";this.fontFamily;this.fontSize;this.textColor;this.escapeHTML=false;this._elem;this._breakTick=false;L.extend(true,this,ah)};L.jqplot.AxisTickRenderer.prototype.init=function(ah){L.extend(true,this,ah)};L.jqplot.AxisTickRenderer.prototype=new L.jqplot.ElemContainer();L.jqplot.AxisTickRenderer.prototype.constructor=L.jqplot.AxisTickRenderer;L.jqplot.AxisTickRenderer.prototype.setTick=function(ah,aj,ai){this.value=ah;this.axis=aj;if(ai){this.isMinorTick=true}return this};L.jqplot.AxisTickRenderer.prototype.draw=function(){if(this.label===null){this.label=this.prefix+this.formatter(this.formatString,this.value)+this.suffix}var ai={position:"absolute"};if(Number(this.label)){ai.whitSpace="nowrap"}if(this._elem){this._elem.emptyForce();this._elem=null}this._elem=L(document.createElement("div"));this._elem.addClass("jqplot-"+this.axis+"-tick");if(!this.escapeHTML){this._elem.html(this.label)}else{this._elem.text(this.label)}this._elem.css(ai);for(var ah in this._styles){this._elem.css(ah,this._styles[ah])}if(this.fontFamily){this._elem.css("font-family",this.fontFamily)}if(this.fontSize){this._elem.css("font-size",this.fontSize)}if(this.textColor){this._elem.css("color",this.textColor)}if(this._breakTick){this._elem.addClass("jqplot-breakTick")}return this._elem};L.jqplot.DefaultTickFormatter=function(ah,ai){if(typeof ai=="number"){if(!ah){ah=L.jqplot.config.defaultTickFormatString}return L.jqplot.sprintf(ah,ai)}else{return String(ai)}};L.jqplot.PercentTickFormatter=function(ah,ai){if(typeof ai=="number"){ai=100*ai;if(!ah){ah=L.jqplot.config.defaultTickFormatString}return L.jqplot.sprintf(ah,ai)}else{return String(ai)}};L.jqplot.AxisTickRenderer.prototype.pack=function(){};L.jqplot.CanvasGridRenderer=function(){this.shadowRenderer=new L.jqplot.ShadowRenderer()};L.jqplot.CanvasGridRenderer.prototype.init=function(ai){this._ctx;L.extend(true,this,ai);var ah={lineJoin:"miter",lineCap:"round",fill:false,isarc:false,angle:this.shadowAngle,offset:this.shadowOffset,alpha:this.shadowAlpha,depth:this.shadowDepth,lineWidth:this.shadowWidth,closePath:false,strokeStyle:this.shadowColor};this.renderer.shadowRenderer.init(ah)};L.jqplot.CanvasGridRenderer.prototype.createElement=function(ak){var aj;if(this._elem){if(L.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==u){aj=this._elem.get(0);window.G_vmlCanvasManager.uninitElement(aj);aj=null}this._elem.emptyForce();this._elem=null}aj=ak.canvasManager.getCanvas();var ah=this._plotDimensions.width;var ai=this._plotDimensions.height;aj.width=ah;aj.height=ai;this._elem=L(aj);this._elem.addClass("jqplot-grid-canvas");this._elem.css({position:"absolute",left:0,top:0});aj=ak.canvasManager.initCanvas(aj);this._top=this._offsets.top;this._bottom=ai-this._offsets.bottom;this._left=this._offsets.left;this._right=ah-this._offsets.right;this._width=this._right-this._left;this._height=this._bottom-this._top;aj=null;return this._elem};L.jqplot.CanvasGridRenderer.prototype.draw=function(){this._ctx=this._elem.get(0).getContext("2d");var at=this._ctx;var aw=this._axes;at.save();at.clearRect(0,0,this._plotDimensions.width,this._plotDimensions.height);at.fillStyle=this.backgroundColor||this.background;at.fillRect(this._left,this._top,this._width,this._height);at.save();at.lineJoin="miter";at.lineCap="butt";at.lineWidth=this.gridLineWidth;at.strokeStyle=this.gridLineColor;var aA,az,ap,aq;var am=["xaxis","yaxis","x2axis","y2axis"];for(var ay=4;ay>0;ay--){var aD=am[ay-1];var ah=aw[aD];var aB=ah._ticks;var ar=aB.length;if(ah.show){if(ah.drawBaseline){var aC={};if(ah.baselineWidth!==null){aC.lineWidth=ah.baselineWidth}if(ah.baselineColor!==null){aC.strokeStyle=ah.baselineColor}switch(aD){case"xaxis":ao(this._left,this._bottom,this._right,this._bottom,aC);break;case"yaxis":ao(this._left,this._bottom,this._left,this._top,aC);break;case"x2axis":ao(this._left,this._bottom,this._right,this._bottom,aC);break;case"y2axis":ao(this._right,this._bottom,this._right,this._top,aC);break}}for(var au=ar;au>0;au--){var an=aB[au-1];if(an.show){var ak=Math.round(ah.u2p(an.value))+0.5;switch(aD){case"xaxis":if(an.showGridline&&this.drawGridlines&&((!an.isMinorTick&&ah.drawMajorGridlines)||(an.isMinorTick&&ah.drawMinorGridlines))){ao(ak,this._top,ak,this._bottom)}if(an.showMark&&an.mark&&((!an.isMinorTick&&ah.drawMajorTickMarks)||(an.isMinorTick&&ah.drawMinorTickMarks))){ap=an.markSize;aq=an.mark;var ak=Math.round(ah.u2p(an.value))+0.5;switch(aq){case"outside":aA=this._bottom;az=this._bottom+ap;break;case"inside":aA=this._bottom-ap;az=this._bottom;break;case"cross":aA=this._bottom-ap;az=this._bottom+ap;break;default:aA=this._bottom;az=this._bottom+ap;break}if(this.shadow){this.renderer.shadowRenderer.draw(at,[[ak,aA],[ak,az]],{lineCap:"butt",lineWidth:this.gridLineWidth,offset:this.gridLineWidth*0.75,depth:2,fill:false,closePath:false})}ao(ak,aA,ak,az)}break;case"yaxis":if(an.showGridline&&this.drawGridlines&&((!an.isMinorTick&&ah.drawMajorGridlines)||(an.isMinorTick&&ah.drawMinorGridlines))){ao(this._right,ak,this._left,ak)}if(an.showMark&&an.mark&&((!an.isMinorTick&&ah.drawMajorTickMarks)||(an.isMinorTick&&ah.drawMinorTickMarks))){ap=an.markSize;aq=an.mark;var ak=Math.round(ah.u2p(an.value))+0.5;switch(aq){case"outside":aA=this._left-ap;az=this._left;break;case"inside":aA=this._left;az=this._left+ap;break;case"cross":aA=this._left-ap;az=this._left+ap;break;default:aA=this._left-ap;az=this._left;break}if(this.shadow){this.renderer.shadowRenderer.draw(at,[[aA,ak],[az,ak]],{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}ao(aA,ak,az,ak,{strokeStyle:ah.borderColor})}break;case"x2axis":if(an.showGridline&&this.drawGridlines&&((!an.isMinorTick&&ah.drawMajorGridlines)||(an.isMinorTick&&ah.drawMinorGridlines))){ao(ak,this._bottom,ak,this._top)}if(an.showMark&&an.mark&&((!an.isMinorTick&&ah.drawMajorTickMarks)||(an.isMinorTick&&ah.drawMinorTickMarks))){ap=an.markSize;aq=an.mark;var ak=Math.round(ah.u2p(an.value))+0.5;switch(aq){case"outside":aA=this._top-ap;az=this._top;


break;case"inside":aA=this._top;az=this._top+ap;break;case"cross":aA=this._top-ap;az=this._top+ap;break;default:aA=this._top-ap;az=this._top;break}if(this.shadow){this.renderer.shadowRenderer.draw(at,[[ak,aA],[ak,az]],{lineCap:"butt",lineWidth:this.gridLineWidth,offset:this.gridLineWidth*0.75,depth:2,fill:false,closePath:false})}ao(ak,aA,ak,az)}break;case"y2axis":if(an.showGridline&&this.drawGridlines&&((!an.isMinorTick&&ah.drawMajorGridlines)||(an.isMinorTick&&ah.drawMinorGridlines))){ao(this._left,ak,this._right,ak)}if(an.showMark&&an.mark&&((!an.isMinorTick&&ah.drawMajorTickMarks)||(an.isMinorTick&&ah.drawMinorTickMarks))){ap=an.markSize;aq=an.mark;var ak=Math.round(ah.u2p(an.value))+0.5;switch(aq){case"outside":aA=this._right;az=this._right+ap;break;case"inside":aA=this._right-ap;az=this._right;break;case"cross":aA=this._right-ap;az=this._right+ap;break;default:aA=this._right;az=this._right+ap;break}if(this.shadow){this.renderer.shadowRenderer.draw(at,[[aA,ak],[az,ak]],{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}ao(aA,ak,az,ak,{strokeStyle:ah.borderColor})}break;default:break}}}an=null}ah=null;aB=null}am=["y3axis","y4axis","y5axis","y6axis","y7axis","y8axis","y9axis","yMidAxis"];for(var ay=7;ay>0;ay--){var ah=aw[am[ay-1]];var aB=ah._ticks;if(ah.show){var ai=aB[ah.numberTicks-1];var al=aB[0];var aj=ah.getLeft();var av=[[aj,ai.getTop()+ai.getHeight()/2],[aj,al.getTop()+al.getHeight()/2+1]];if(this.shadow){this.renderer.shadowRenderer.draw(at,av,{lineCap:"butt",fill:false,closePath:false})}ao(av[0][0],av[0][1],av[1][0],av[1][1],{lineCap:"butt",strokeStyle:ah.borderColor,lineWidth:ah.borderWidth});for(var au=aB.length;au>0;au--){var an=aB[au-1];ap=an.markSize;aq=an.mark;var ak=Math.round(ah.u2p(an.value))+0.5;if(an.showMark&&an.mark){switch(aq){case"outside":aA=aj;az=aj+ap;break;case"inside":aA=aj-ap;az=aj;break;case"cross":aA=aj-ap;az=aj+ap;break;default:aA=aj;az=aj+ap;break}av=[[aA,ak],[az,ak]];if(this.shadow){this.renderer.shadowRenderer.draw(at,av,{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}ao(aA,ak,az,ak,{strokeStyle:ah.borderColor})}an=null}al=null}ah=null;aB=null}at.restore();function ao(aH,aG,aE,ax,aF){at.save();aF=aF||{};if(aF.lineWidth==null||aF.lineWidth!=0){L.extend(true,at,aF);at.beginPath();at.moveTo(aH,aG);at.lineTo(aE,ax);at.stroke();at.restore()}}if(this.shadow){var av=[[this._left,this._bottom],[this._right,this._bottom],[this._right,this._top]];this.renderer.shadowRenderer.draw(at,av)}if(this.borderWidth!=0&&this.drawBorder){ao(this._left,this._top,this._right,this._top,{lineCap:"round",strokeStyle:aw.x2axis.borderColor,lineWidth:aw.x2axis.borderWidth});ao(this._right,this._top,this._right,this._bottom,{lineCap:"round",strokeStyle:aw.y2axis.borderColor,lineWidth:aw.y2axis.borderWidth});ao(this._right,this._bottom,this._left,this._bottom,{lineCap:"round",strokeStyle:aw.xaxis.borderColor,lineWidth:aw.xaxis.borderWidth});ao(this._left,this._bottom,this._left,this._top,{lineCap:"round",strokeStyle:aw.yaxis.borderColor,lineWidth:aw.yaxis.borderWidth})}at.restore();at=null;aw=null};L.jqplot.DivTitleRenderer=function(){};L.jqplot.DivTitleRenderer.prototype.init=function(ah){L.extend(true,this,ah)};L.jqplot.DivTitleRenderer.prototype.draw=function(){if(this._elem){this._elem.emptyForce();this._elem=null}var ak=this.renderer;var aj=document.createElement("div");this._elem=L(aj);this._elem.addClass("jqplot-title");if(!this.text){this.show=false;this._elem.height(0);this._elem.width(0)}else{if(this.text){var ah;if(this.color){ah=this.color}else{if(this.textColor){ah=this.textColor}}var ai={position:"absolute",top:"0px",left:"0px"};if(this._plotWidth){ai.width=this._plotWidth+"px"}if(this.fontSize){ai.fontSize=this.fontSize}if(typeof this.textAlign==="string"){ai.textAlign=this.textAlign}else{ai.textAlign="center"}if(ah){ai.color=ah}if(this.paddingBottom){ai.paddingBottom=this.paddingBottom}if(this.fontFamily){ai.fontFamily=this.fontFamily}this._elem.css(ai);if(this.escapeHtml){this._elem.text(this.text)}else{this._elem.html(this.text)}}}aj=null;return this._elem};L.jqplot.DivTitleRenderer.prototype.pack=function(){};var r=0.1;L.jqplot.LinePattern=function(aw,aq){var ap={dotted:[r,L.jqplot.config.dotGapLength],dashed:[L.jqplot.config.dashLength,L.jqplot.config.gapLength],solid:null};if(typeof aq==="string"){if(aq[0]==="."||aq[0]==="-"){var ax=aq;aq=[];for(var ao=0,al=ax.length;ao<al;ao++){if(ax[ao]==="."){aq.push(r)}else{if(ax[ao]==="-"){aq.push(L.jqplot.config.dashLength)}else{continue}}aq.push(L.jqplot.config.gapLength)}}else{aq=ap[aq]}}if(!(aq&&aq.length)){return aw}var ak=0;var ar=aq[0];var au=0;var at=0;var an=0;var ah=0;var av=function(ay,az){aw.moveTo(ay,az);au=ay;at=az;an=ay;ah=az};var aj=function(ay,aE){var aC=aw.lineWidth;var aA=ay-au;var az=aE-at;var aB=Math.sqrt(aA*aA+az*az);if((aB>0)&&(aC>0)){aA/=aB;az/=aB;while(true){var aD=aC*ar;if(aD<aB){au+=aD*aA;at+=aD*az;if((ak&1)==0){aw.lineTo(au,at)}else{aw.moveTo(au,at)}aB-=aD;ak++;if(ak>=aq.length){ak=0}ar=aq[ak]}else{au=ay;at=aE;if((ak&1)==0){aw.lineTo(au,at)}else{aw.moveTo(au,at)}ar-=aB/aC;break}}}};var ai=function(){aw.beginPath()};var am=function(){aj(an,ah)};return{moveTo:av,lineTo:aj,beginPath:ai,closePath:am}};L.jqplot.LineRenderer=function(){this.shapeRenderer=new L.jqplot.ShapeRenderer();this.shadowRenderer=new L.jqplot.ShadowRenderer()};L.jqplot.LineRenderer.prototype.init=function(ai,an){ai=ai||{};this._type="line";this.renderer.animation={show:false,direction:"left",speed:2500,_supported:true};this.renderer.smooth=false;this.renderer.tension=null;this.renderer.constrainSmoothing=true;this.renderer._smoothedData=[];this.renderer._smoothedPlotData=[];this.renderer._hiBandGridData=[];this.renderer._lowBandGridData=[];this.renderer._hiBandSmoothedData=[];this.renderer._lowBandSmoothedData=[];this.renderer.bandData=[];this.renderer.bands={show:false,hiData:[],lowData:[],color:this.color,showLines:false,fill:true,fillColor:null,_min:null,_max:null,interval:"3%"};var al={highlightMouseOver:ai.highlightMouseOver,highlightMouseDown:ai.highlightMouseDown,highlightColor:ai.highlightColor};delete (ai.highlightMouseOver);delete (ai.highlightMouseDown);delete (ai.highlightColor);L.extend(true,this.renderer,ai);this.renderer.options=ai;if(this.renderer.bandData.length>1&&(!ai.bands||ai.bands.show==null)){this.renderer.bands.show=true}else{if(ai.bands&&ai.bands.show==null&&ai.bands.interval!=null){this.renderer.bands.show=true}}if(this.fill){this.renderer.bands.show=false}if(this.renderer.bands.show){this.renderer.initBands.call(this,this.renderer.options,an)}if(this._stack){this.renderer.smooth=false}var am={lineJoin:this.lineJoin,lineCap:this.lineCap,fill:this.fill,isarc:false,strokeStyle:this.color,fillStyle:this.fillColor,lineWidth:this.lineWidth,linePattern:this.linePattern,closePath:this.fill};this.renderer.shapeRenderer.init(am);var aj=ai.shadowOffset;if(aj==null){if(this.lineWidth>2.5){aj=1.25*(1+(Math.atan((this.lineWidth/2.5))/0.785398163-1)*0.6)}else{aj=1.25*Math.atan((this.lineWidth/2.5))/0.785398163}}var ah={lineJoin:this.lineJoin,lineCap:this.lineCap,fill:this.fill,isarc:false,angle:this.shadowAngle,offset:aj,alpha:this.shadowAlpha,depth:this.shadowDepth,lineWidth:this.lineWidth,linePattern:this.linePattern,closePath:this.fill};this.renderer.shadowRenderer.init(ah);this._areaPoints=[];this._boundingBox=[[],[]];if(!this.isTrendline&&this.fill||this.renderer.bands.show){this.highlightMouseOver=true;this.highlightMouseDown=false;this.highlightColor=null;if(al.highlightMouseDown&&al.highlightMouseOver==null){al.highlightMouseOver=false}L.extend(true,this,{highlightMouseOver:al.highlightMouseOver,highlightMouseDown:al.highlightMouseDown,highlightColor:al.highlightColor});if(!this.highlightColor){var ak=(this.renderer.bands.show)?this.renderer.bands.fillColor:this.fillColor;this.highlightColor=L.jqplot.computeHighlightColors(ak)}if(this.highlighter){this.highlighter.show=false}}if(!this.isTrendline&&an){an.plugins.lineRenderer={};an.postInitHooks.addOnce(z);an.postDrawHooks.addOnce(af);an.eventListenerHooks.addOnce("jqplotMouseMove",h);an.eventListenerHooks.addOnce("jqplotMouseDown",e);an.eventListenerHooks.addOnce("jqplotMouseUp",ad);an.eventListenerHooks.addOnce("jqplotClick",g);an.eventListenerHooks.addOnce("jqplotRightClick",s)}};L.jqplot.LineRenderer.prototype.initBands=function(ak,av){var al=ak.bandData||[];var an=this.renderer.bands;an.hiData=[];an.lowData=[];var aB=this.data;an._max=null;an._min=null;if(al.length==2){if(L.isArray(al[0][0])){var ao;var ah=0,ar=0;for(var aw=0,at=al[0].length;aw<at;aw++){ao=al[0][aw];if((ao[1]!=null&&ao[1]>an._max)||an._max==null){an._max=ao[1]}if((ao[1]!=null&&ao[1]<an._min)||an._min==null){an._min=ao[1]}}for(var aw=0,at=al[1].length;aw<at;aw++){ao=al[1][aw];if((ao[1]!=null&&ao[1]>an._max)||an._max==null){an._max=ao[1];ar=1}if((ao[1]!=null&&ao[1]<an._min)||an._min==null){an._min=ao[1];ah=1}}if(ar===ah){an.show=false}an.hiData=al[ar];an.lowData=al[ah]}else{if(al[0].length===aB.length&&al[1].length===aB.length){var aj=(al[0][0]>al[1][0])?0:1;var aC=(aj)?0:1;for(var aw=0,at=aB.length;aw<at;aw++){an.hiData.push([aB[aw][0],al[aj][aw]]);an.lowData.push([aB[aw][0],al[aC][aw]])}}else{an.show=false}}}else{if(al.length>2&&!L.isArray(al[0][0])){var aj=(al[0][0]>al[0][1])?0:1;var aC=(aj)?0:1;for(var aw=0,at=al.length;aw<at;aw++){an.hiData.push([aB[aw][0],al[aw][aj]]);an.lowData.push([aB[aw][0],al[aw][aC]])}}else{var aq=an.interval;var aA=null;var az=null;var ai=null;var au=null;if(L.isArray(aq)){aA=aq[0];az=aq[1]}else{aA=aq}if(isNaN(aA)){if(aA.charAt(aA.length-1)==="%"){ai="multiply";aA=parseFloat(aA)/100+1}}else{aA=parseFloat(aA);ai="add"}if(az!==null&&isNaN(az)){if(az.charAt(az.length-1)==="%"){au="multiply";az=parseFloat(az)/100+1}}else{if(az!==null){az=parseFloat(az);au="add"}}if(aA!==null){if(az===null){az=-aA;au=ai;if(au==="multiply"){az+=2}}if(aA<az){var ax=aA;aA=az;az=ax;ax=ai;ai=au;au=ax}for(var aw=0,at=aB.length;aw<at;aw++){switch(ai){case"add":an.hiData.push([aB[aw][0],aB[aw][1]+aA]);break;case"multiply":an.hiData.push([aB[aw][0],aB[aw][1]*aA]);break}switch(au){case"add":an.lowData.push([aB[aw][0],aB[aw][1]+az]);break;case"multiply":an.lowData.push([aB[aw][0],aB[aw][1]*az]);break}}}else{an.show=false}}}var am=an.hiData;var ap=an.lowData;for(var aw=0,at=am.length;aw<at;aw++){if((am[aw][1]!=null&&am[aw][1]>an._max)||an._max==null){an._max=am[aw][1]}}for(var aw=0,at=ap.length;aw<at;aw++){if((ap[aw][1]!=null&&ap[aw][1]<an._min)||an._min==null){an._min=ap[aw][1]}}if(an.fillColor===null){var ay=L.jqplot.getColorComponents(an.color);ay[3]=ay[3]*0.5;an.fillColor="rgba("+ay[0]+", "+ay[1]+", "+ay[2]+", "+ay[3]+")"}};function K(ai,ah){return(3.4182054+ah)*Math.pow(ai,-0.3534992)}function n(aj,ai){var ah=Math.sqrt(Math.pow((ai[0]-aj[0]),2)+Math.pow((ai[1]-aj[1]),2));return 5.7648*Math.log(ah)+7.4456}function A(ah){var ai=(Math.exp(2*ah)-1)/(Math.exp(2*ah)+1);return ai}function J(aJ){var at=this.renderer.smooth;var aD=this.canvas.getWidth();var an=this._xaxis.series_p2u;var aG=this._yaxis.series_p2u;var aF=null;var am=null;var az=aJ.length/aD;var aj=[];var ay=[];if(!isNaN(parseFloat(at))){aF=parseFloat(at)}else{aF=K(az,0.5)}var aw=[];var ak=[];for(var aE=0,aA=aJ.length;aE<aA;aE++){aw.push(aJ[aE][1]);ak.push(aJ[aE][0])}function av(aK,aL){if(aK-aL==0){return Math.pow(10,10)}else{return aK-aL}}var ax,ar,aq,ap;var ah=aJ.length-1;for(var al=1,aB=aJ.length;al<aB;al++){var ai=[];var au=[];for(var aC=0;aC<2;aC++){var aE=al-1+aC;if(aE==0||aE==ah){ai[aC]=Math.pow(10,10)}else{if(aw[aE+1]-aw[aE]==0||aw[aE]-aw[aE-1]==0){ai[aC]=0}else{if(((ak[aE+1]-ak[aE])/(aw[aE+1]-aw[aE])+(ak[aE]-ak[aE-1])/(aw[aE]-aw[aE-1]))==0){ai[aC]=0}else{if((aw[aE+1]-aw[aE])*(aw[aE]-aw[aE-1])<0){ai[aC]=0}else{ai[aC]=2/(av(ak[aE+1],ak[aE])/(aw[aE+1]-aw[aE])+av(ak[aE],ak[aE-1])/(aw[aE]-aw[aE-1]))}}}}}if(al==1){ai[0]=3/2*(aw[1]-aw[0])/av(ak[1],ak[0])-ai[1]/2}else{if(al==ah){ai[1]=3/2*(aw[ah]-aw[ah-1])/av(ak[ah],ak[ah-1])-ai[0]/2}}au[0]=-2*(ai[1]+2*ai[0])/av(ak[al],ak[al-1])+6*(aw[al]-aw[al-1])/Math.pow(av(ak[al],ak[al-1]),2);au[1]=2*(2*ai[1]+ai[0])/av(ak[al],ak[al-1])-6*(aw[al]-aw[al-1])/Math.pow(av(ak[al],ak[al-1]),2);ap=1/6*(au[1]-au[0])/av(ak[al],ak[al-1]);aq=1/2*(ak[al]*au[0]-ak[al-1]*au[1])/av(ak[al],ak[al-1]);ar=(aw[al]-aw[al-1]-aq*(Math.pow(ak[al],2)-Math.pow(ak[al-1],2))-ap*(Math.pow(ak[al],3)-Math.pow(ak[al-1],3)))/av(ak[al],ak[al-1]);ax=aw[al-1]-ar*ak[al-1]-aq*Math.pow(ak[al-1],2)-ap*Math.pow(ak[al-1],3);var aI=(ak[al]-ak[al-1])/aF;var aH,ao;for(var aC=0,aA=aF;aC<aA;aC++){aH=[];ao=ak[al-1]+aC*aI;aH.push(ao);aH.push(ax+ar*ao+aq*Math.pow(ao,2)+ap*Math.pow(ao,3));aj.push(aH);ay.push([an(aH[0]),aG(aH[1])])}}aj.push(aJ[aE]);ay.push([an(aJ[aE][0]),aG(aJ[aE][1])]);return[aj,ay]}function F(ap){var ao=this.renderer.smooth;var aU=this.renderer.tension;var ah=this.canvas.getWidth();var aH=this._xaxis.series_p2u;var aq=this._yaxis.series_p2u;var aI=null;var aJ=null;var aT=null;var aO=null;var aM=null;var at=null;var aR=null;var am=null;var aK,aL,aD,aC,aA,ay;var ak,ai,av,au;var aB,az,aN;var aw=[];var aj=[];var al=ap.length/ah;var aS,ax,aF,aG,aE;var ar=[];var an=[];if(!isNaN(parseFloat(ao))){aI=parseFloat(ao)}else{aI=K(al,0.5)}if(!isNaN(parseFloat(aU))){aU=parseFloat(aU)}for(var aQ=0,aP=ap.length-1;aQ<aP;aQ++){if(aU===null){at=Math.abs((ap[aQ+1][1]-ap[aQ][1])/(ap[aQ+1][0]-ap[aQ][0]));aS=0.3;ax=0.6;aF=(ax-aS)/2;aG=2.5;aE=-1.4;am=at/aG+aE;aO=aF*A(am)-aF*A(aE)+aS;if(aQ>0){aR=Math.abs((ap[aQ][1]-ap[aQ-1][1])/(ap[aQ][0]-ap[aQ-1][0]))}am=aR/aG+aE;aM=aF*A(am)-aF*A(aE)+aS;aT=(aO+aM)/2}else{aT=aU}for(aK=0;aK<aI;aK++){aL=aK/aI;aD=(1+2*aL)*Math.pow((1-aL),2);aC=aL*Math.pow((1-aL),2);aA=Math.pow(aL,2)*(3-2*aL);ay=Math.pow(aL,2)*(aL-1);if(ap[aQ-1]){ak=aT*(ap[aQ+1][0]-ap[aQ-1][0]);ai=aT*(ap[aQ+1][1]-ap[aQ-1][1])}else{ak=aT*(ap[aQ+1][0]-ap[aQ][0]);ai=aT*(ap[aQ+1][1]-ap[aQ][1])}if(ap[aQ+2]){av=aT*(ap[aQ+2][0]-ap[aQ][0]);au=aT*(ap[aQ+2][1]-ap[aQ][1])}else{av=aT*(ap[aQ+1][0]-ap[aQ][0]);au=aT*(ap[aQ+1][1]-ap[aQ][1])}aB=aD*ap[aQ][0]+aA*ap[aQ+1][0]+aC*ak+ay*av;az=aD*ap[aQ][1]+aA*ap[aQ+1][1]+aC*ai+ay*au;aN=[aB,az];ar.push(aN);an.push([aH(aB),aq(az)])}}ar.push(ap[aP]);an.push([aH(ap[aP][0]),aq(ap[aP][1])]);return[ar,an]}L.jqplot.LineRenderer.prototype.setGridData=function(ap){var al=this._xaxis.series_u2p;var ah=this._yaxis.series_u2p;var am=this._plotData;var aq=this._prevPlotData;this.gridData=[];this._prevGridData=[];this.renderer._smoothedData=[];this.renderer._smoothedPlotData=[];this.renderer._hiBandGridData=[];this.renderer._lowBandGridData=[];this.renderer._hiBandSmoothedData=[];this.renderer._lowBandSmoothedData=[];var ak=this.renderer.bands;var ai=false;for(var an=0,aj=am.length;an<aj;an++){if(am[an][0]!=null&&am[an][1]!=null){this.gridData.push([al.call(this._xaxis,am[an][0]),ah.call(this._yaxis,am[an][1])])}else{if(am[an][0]==null){ai=true;this.gridData.push([null,ah.call(this._yaxis,am[an][1])])}else{if(am[an][1]==null){ai=true;this.gridData.push([al.call(this._xaxis,am[an][0]),null])}}}if(aq[an]!=null&&aq[an][0]!=null&&aq[an][1]!=null){this._prevGridData.push([al.call(this._xaxis,aq[an][0]),ah.call(this._yaxis,aq[an][1])])}else{if(aq[an]!=null&&aq[an][0]==null){this._prevGridData.push([null,ah.call(this._yaxis,aq[an][1])])}else{if(aq[an]!=null&&aq[an][0]!=null&&aq[an][1]==null){this._prevGridData.push([al.call(this._xaxis,aq[an][0]),null])}}}}if(ai){this.renderer.smooth=false;if(this._type==="line"){ak.show=false}}if(this._type==="line"&&ak.show){for(var an=0,aj=ak.hiData.length;an<aj;an++){this.renderer._hiBandGridData.push([al.call(this._xaxis,ak.hiData[an][0]),ah.call(this._yaxis,ak.hiData[an][1])])}for(var an=0,aj=ak.lowData.length;an<aj;an++){this.renderer._lowBandGridData.push([al.call(this._xaxis,ak.lowData[an][0]),ah.call(this._yaxis,ak.lowData[an][1])])}}if(this._type==="line"&&this.renderer.smooth&&this.gridData.length>2){var ao;if(this.renderer.constrainSmoothing){ao=J.call(this,this.gridData);this.renderer._smoothedData=ao[0];this.renderer._smoothedPlotData=ao[1];if(ak.show){ao=J.call(this,this.renderer._hiBandGridData);this.renderer._hiBandSmoothedData=ao[0];ao=J.call(this,this.renderer._lowBandGridData);this.renderer._lowBandSmoothedData=ao[0]}ao=null}else{ao=F.call(this,this.gridData);this.renderer._smoothedData=ao[0];this.renderer._smoothedPlotData=ao[1];if(ak.show){ao=F.call(this,this.renderer._hiBandGridData);this.renderer._hiBandSmoothedData=ao[0];ao=F.call(this,this.renderer._lowBandGridData);this.renderer._lowBandSmoothedData=ao[0]}ao=null}}};L.jqplot.LineRenderer.prototype.makeGridData=function(ao,aq){var am=this._xaxis.series_u2p;var ah=this._yaxis.series_u2p;var ar=[];var aj=[];this.renderer._smoothedData=[];this.renderer._smoothedPlotData=[];this.renderer._hiBandGridData=[];this.renderer._lowBandGridData=[];this.renderer._hiBandSmoothedData=[];this.renderer._lowBandSmoothedData=[];var al=this.renderer.bands;var ai=false;for(var an=0;an<ao.length;an++){if(ao[an][0]!=null&&ao[an][1]!=null){ar.push([am.call(this._xaxis,ao[an][0]),ah.call(this._yaxis,ao[an][1])])}else{if(ao[an][0]==null){ai=true;ar.push([null,ah.call(this._yaxis,ao[an][1])])}else{if(ao[an][1]==null){ai=true;ar.push([am.call(this._xaxis,ao[an][0]),null])}}}}if(ai){this.renderer.smooth=false;if(this._type==="line"){al.show=false}}if(this._type==="line"&&al.show){for(var an=0,ak=al.hiData.length;an<ak;an++){this.renderer._hiBandGridData.push([am.call(this._xaxis,al.hiData[an][0]),ah.call(this._yaxis,al.hiData[an][1])])}for(var an=0,ak=al.lowData.length;an<ak;an++){this.renderer._lowBandGridData.push([am.call(this._xaxis,al.lowData[an][0]),ah.call(this._yaxis,al.lowData[an][1])])}}if(this._type==="line"&&this.renderer.smooth&&ar.length>2){var ap;if(this.renderer.constrainSmoothing){ap=J.call(this,ar);this.renderer._smoothedData=ap[0];this.renderer._smoothedPlotData=ap[1];if(al.show){ap=J.call(this,this.renderer._hiBandGridData);this.renderer._hiBandSmoothedData=ap[0];ap=J.call(this,this.renderer._lowBandGridData);this.renderer._lowBandSmoothedData=ap[0]}ap=null}else{ap=F.call(this,ar);



this.renderer._smoothedData=ap[0];this.renderer._smoothedPlotData=ap[1];if(al.show){ap=F.call(this,this.renderer._hiBandGridData);this.renderer._hiBandSmoothedData=ap[0];ap=F.call(this,this.renderer._lowBandGridData);this.renderer._lowBandSmoothedData=ap[0]}ap=null}}return ar};L.jqplot.LineRenderer.prototype.draw=function(ax,aI,ai,aB){var aC;var aq=L.extend(true,{},ai);var ak=(aq.shadow!=u)?aq.shadow:this.shadow;var aJ=(aq.showLine!=u)?aq.showLine:this.showLine;var aA=(aq.fill!=u)?aq.fill:this.fill;var ah=(aq.fillAndStroke!=u)?aq.fillAndStroke:this.fillAndStroke;var ar,ay,av,aE;ax.save();if(aI.length){if(aJ){if(aA){if(this.fillToZero){var aF=this.negativeColor;if(!this.useNegativeColors){aF=aq.fillStyle}var ao=false;var ap=aq.fillStyle;if(ah){var aH=aI.slice(0)}if(this.index==0||!this._stack){var aw=[];var aL=(this.renderer.smooth)?this.renderer._smoothedPlotData:this._plotData;this._areaPoints=[];var aG=this._yaxis.series_u2p(this.fillToValue);var aj=this._xaxis.series_u2p(this.fillToValue);aq.closePath=true;if(this.fillAxis=="y"){aw.push([aI[0][0],aG]);this._areaPoints.push([aI[0][0],aG]);for(var aC=0;aC<aI.length-1;aC++){aw.push(aI[aC]);this._areaPoints.push(aI[aC]);if(aL[aC][1]*aL[aC+1][1]<0){if(aL[aC][1]<0){ao=true;aq.fillStyle=aF}else{ao=false;aq.fillStyle=ap}var an=aI[aC][0]+(aI[aC+1][0]-aI[aC][0])*(aG-aI[aC][1])/(aI[aC+1][1]-aI[aC][1]);aw.push([an,aG]);this._areaPoints.push([an,aG]);if(ak){this.renderer.shadowRenderer.draw(ax,aw,aq)}this.renderer.shapeRenderer.draw(ax,aw,aq);aw=[[an,aG]]}}if(aL[aI.length-1][1]<0){ao=true;aq.fillStyle=aF}else{ao=false;aq.fillStyle=ap}aw.push(aI[aI.length-1]);this._areaPoints.push(aI[aI.length-1]);aw.push([aI[aI.length-1][0],aG]);this._areaPoints.push([aI[aI.length-1][0],aG])}if(ak){this.renderer.shadowRenderer.draw(ax,aw,aq)}this.renderer.shapeRenderer.draw(ax,aw,aq)}else{var au=this._prevGridData;for(var aC=au.length;aC>0;aC--){aI.push(au[aC-1])}if(ak){this.renderer.shadowRenderer.draw(ax,aI,aq)}this._areaPoints=aI;this.renderer.shapeRenderer.draw(ax,aI,aq)}}else{if(ah){var aH=aI.slice(0)}if(this.index==0||!this._stack){var al=ax.canvas.height;aI.unshift([aI[0][0],al]);var aD=aI.length;aI.push([aI[aD-1][0],al])}else{var au=this._prevGridData;for(var aC=au.length;aC>0;aC--){aI.push(au[aC-1])}}this._areaPoints=aI;if(ak){this.renderer.shadowRenderer.draw(ax,aI,aq)}this.renderer.shapeRenderer.draw(ax,aI,aq)}if(ah){var az=L.extend(true,{},aq,{fill:false,closePath:false});this.renderer.shapeRenderer.draw(ax,aH,az);if(this.markerRenderer.show){if(this.renderer.smooth){aH=this.gridData}for(aC=0;aC<aH.length;aC++){this.markerRenderer.draw(aH[aC][0],aH[aC][1],ax,aq.markerOptions)}}}}else{if(this.renderer.bands.show){var am;var aK=L.extend(true,{},aq);if(this.renderer.bands.showLines){am=(this.renderer.smooth)?this.renderer._hiBandSmoothedData:this.renderer._hiBandGridData;this.renderer.shapeRenderer.draw(ax,am,aq);am=(this.renderer.smooth)?this.renderer._lowBandSmoothedData:this.renderer._lowBandGridData;this.renderer.shapeRenderer.draw(ax,am,aK)}if(this.renderer.bands.fill){if(this.renderer.smooth){am=this.renderer._hiBandSmoothedData.concat(this.renderer._lowBandSmoothedData.reverse())}else{am=this.renderer._hiBandGridData.concat(this.renderer._lowBandGridData.reverse())}this._areaPoints=am;aK.closePath=true;aK.fill=true;aK.fillStyle=this.renderer.bands.fillColor;this.renderer.shapeRenderer.draw(ax,am,aK)}}if(ak){this.renderer.shadowRenderer.draw(ax,aI,aq)}this.renderer.shapeRenderer.draw(ax,aI,aq)}}var ar=av=ay=aE=null;for(aC=0;aC<this._areaPoints.length;aC++){var at=this._areaPoints[aC];if(ar>at[0]||ar==null){ar=at[0]}if(aE<at[1]||aE==null){aE=at[1]}if(av<at[0]||av==null){av=at[0]}if(ay>at[1]||ay==null){ay=at[1]}}if(this.type==="line"&&this.renderer.bands.show){aE=this._yaxis.series_u2p(this.renderer.bands._min);ay=this._yaxis.series_u2p(this.renderer.bands._max)}this._boundingBox=[[ar,aE],[av,ay]];if(this.markerRenderer.show&&!aA){if(this.renderer.smooth){aI=this.gridData}for(aC=0;aC<aI.length;aC++){if(aI[aC][0]!=null&&aI[aC][1]!=null){this.markerRenderer.draw(aI[aC][0],aI[aC][1],ax,aq.markerOptions)}}}}ax.restore()};L.jqplot.LineRenderer.prototype.drawShadow=function(ah,aj,ai){};function z(ak,aj,ah){for(var ai=0;ai<this.series.length;ai++){if(this.series[ai].renderer.constructor==L.jqplot.LineRenderer){if(this.series[ai].highlightMouseOver){this.series[ai].highlightMouseDown=false}}}}function af(){if(this.plugins.lineRenderer&&this.plugins.lineRenderer.highlightCanvas){this.plugins.lineRenderer.highlightCanvas.resetCanvas();this.plugins.lineRenderer.highlightCanvas=null}this.plugins.lineRenderer.highlightedSeriesIndex=null;this.plugins.lineRenderer.highlightCanvas=new L.jqplot.GenericCanvas();this.eventCanvas._elem.before(this.plugins.lineRenderer.highlightCanvas.createElement(this._gridPadding,"jqplot-lineRenderer-highlight-canvas",this._plotDimensions,this));this.plugins.lineRenderer.highlightCanvas.setContext();this.eventCanvas._elem.bind("mouseleave",{plot:this},function(ah){aa(ah.data.plot)})}function ac(an,am,ak,aj){var ai=an.series[am];var ah=an.plugins.lineRenderer.highlightCanvas;ah._ctx.clearRect(0,0,ah._ctx.canvas.width,ah._ctx.canvas.height);ai._highlightedPoint=ak;an.plugins.lineRenderer.highlightedSeriesIndex=am;var al={fillStyle:ai.highlightColor};if(ai.type==="line"&&ai.renderer.bands.show){al.fill=true;al.closePath=true}ai.renderer.shapeRenderer.draw(ah._ctx,aj,al);ah=null}function aa(aj){var ah=aj.plugins.lineRenderer.highlightCanvas;ah._ctx.clearRect(0,0,ah._ctx.canvas.width,ah._ctx.canvas.height);for(var ai=0;ai<aj.series.length;ai++){aj.series[ai]._highlightedPoint=null}aj.plugins.lineRenderer.highlightedSeriesIndex=null;aj.target.trigger("jqplotDataUnhighlight");ah=null}function h(al,ak,ao,an,am){if(an){var aj=[an.seriesIndex,an.pointIndex,an.data];var ai=jQuery.Event("jqplotDataMouseOver");ai.pageX=al.pageX;ai.pageY=al.pageY;am.target.trigger(ai,aj);if(am.series[aj[0]].highlightMouseOver&&!(aj[0]==am.plugins.lineRenderer.highlightedSeriesIndex)){var ah=jQuery.Event("jqplotDataHighlight");ah.which=al.which;ah.pageX=al.pageX;ah.pageY=al.pageY;am.target.trigger(ah,aj);ac(am,an.seriesIndex,an.pointIndex,an.points)}}else{if(an==null){aa(am)}}}function e(ak,aj,an,am,al){if(am){var ai=[am.seriesIndex,am.pointIndex,am.data];if(al.series[ai[0]].highlightMouseDown&&!(ai[0]==al.plugins.lineRenderer.highlightedSeriesIndex)){var ah=jQuery.Event("jqplotDataHighlight");ah.which=ak.which;ah.pageX=ak.pageX;ah.pageY=ak.pageY;al.target.trigger(ah,ai);ac(al,am.seriesIndex,am.pointIndex,am.points)}}else{if(am==null){aa(al)}}}function ad(aj,ai,am,al,ak){var ah=ak.plugins.lineRenderer.highlightedSeriesIndex;if(ah!=null&&ak.series[ah].highlightMouseDown){aa(ak)}}function g(ak,aj,an,am,al){if(am){var ai=[am.seriesIndex,am.pointIndex,am.data];var ah=jQuery.Event("jqplotDataClick");ah.which=ak.which;ah.pageX=ak.pageX;ah.pageY=ak.pageY;al.target.trigger(ah,ai)}}function s(al,ak,ao,an,am){if(an){var aj=[an.seriesIndex,an.pointIndex,an.data];var ah=am.plugins.lineRenderer.highlightedSeriesIndex;if(ah!=null&&am.series[ah].highlightMouseDown){aa(am)}var ai=jQuery.Event("jqplotDataRightClick");ai.which=al.which;ai.pageX=al.pageX;ai.pageY=al.pageY;am.target.trigger(ai,aj)}}L.jqplot.LinearAxisRenderer=function(){};L.jqplot.LinearAxisRenderer.prototype.init=function(ah){this.breakPoints=null;this.breakTickLabel="&asymp;";this.drawBaseline=true;this.baselineWidth=null;this.baselineColor=null;this.forceTickAt0=false;this.forceTickAt100=false;this.tickInset=0;this.minorTicks=0;this.alignTicks=false;this._autoFormatString="";this._overrideFormatString=false;this._scalefact=1;L.extend(true,this,ah);if(this.breakPoints){if(!L.isArray(this.breakPoints)){this.breakPoints=null}else{if(this.breakPoints.length<2||this.breakPoints[1]<=this.breakPoints[0]){this.breakPoints=null}}}if(this.numberTicks!=null&&this.numberTicks<2){this.numberTicks=2}this.resetDataBounds()};L.jqplot.LinearAxisRenderer.prototype.draw=function(ah,ao){if(this.show){this.renderer.createTicks.call(this,ao);var an=0;var ai;if(this._elem){this._elem.emptyForce();this._elem=null}this._elem=L(document.createElement("div"));this._elem.addClass("jqplot-axis jqplot-"+this.name);this._elem.css("position","absolute");if(this.name=="xaxis"||this.name=="x2axis"){this._elem.width(this._plotDimensions.width)}else{this._elem.height(this._plotDimensions.height)}this.labelOptions.axis=this.name;this._label=new this.labelRenderer(this.labelOptions);if(this._label.show){var am=this._label.draw(ah,ao);am.appendTo(this._elem);am=null}var al=this._ticks;var ak;for(var aj=0;aj<al.length;aj++){ak=al[aj];if(ak.show&&ak.showLabel&&(!ak.isMinorTick||this.showMinorTicks)){this._elem.append(ak.draw(ah,ao))}}ak=null;al=null}return this._elem};L.jqplot.LinearAxisRenderer.prototype.reset=function(){this.min=this._options.min;this.max=this._options.max;this.tickInterval=this._options.tickInterval;this.numberTicks=this._options.numberTicks;this._autoFormatString="";if(this._overrideFormatString&&this.tickOptions&&this.tickOptions.formatString){this.tickOptions.formatString=""}};L.jqplot.LinearAxisRenderer.prototype.set=function(){var ao=0;var aj;var ai=0;var an=0;var ah=(this._label==null)?false:this._label.show;if(this.show){var am=this._ticks;var al;for(var ak=0;ak<am.length;ak++){al=am[ak];if(!al._breakTick&&al.show&&al.showLabel&&(!al.isMinorTick||this.showMinorTicks)){if(this.name=="xaxis"||this.name=="x2axis"){aj=al._elem.outerHeight(true)}else{aj=al._elem.outerWidth(true)}if(aj>ao){ao=aj}}}al=null;am=null;if(ah){ai=this._label._elem.outerWidth(true);an=this._label._elem.outerHeight(true)}if(this.name=="xaxis"){ao=ao+an;this._elem.css({height:ao+"px",left:"0px",bottom:"0px"})}else{if(this.name=="x2axis"){ao=ao+an;this._elem.css({height:ao+"px",left:"0px",top:"0px"})}else{if(this.name=="yaxis"){ao=ao+ai;this._elem.css({width:ao+"px",left:"0px",top:"0px"});if(ah&&this._label.constructor==L.jqplot.AxisLabelRenderer){this._label._elem.css("width",ai+"px")}}else{ao=ao+ai;this._elem.css({width:ao+"px",right:"0px",top:"0px"});if(ah&&this._label.constructor==L.jqplot.AxisLabelRenderer){this._label._elem.css("width",ai+"px")}}}}}};L.jqplot.LinearAxisRenderer.prototype.createTicks=function(aj){var aT=this._ticks;var aK=this.ticks;var az=this.name;var aB=this._dataBounds;var ah=(this.name.charAt(0)==="x")?this._plotDimensions.width:this._plotDimensions.height;var an;var a6,aI;var ap,ao;var a4,a0;var aH=this.min;var a5=this.max;var aW=this.numberTicks;var ba=this.tickInterval;var am=30;this._scalefact=(Math.max(ah,am+1)-am)/300;if(aK.length){for(a0=0;a0<aK.length;a0++){var aO=aK[a0];var aU=new this.tickRenderer(this.tickOptions);if(L.isArray(aO)){aU.value=aO[0];if(this.breakPoints){if(aO[0]==this.breakPoints[0]){aU.label=this.breakTickLabel;aU._breakTick=true;aU.showGridline=false;aU.showMark=false}else{if(aO[0]>this.breakPoints[0]&&aO[0]<=this.breakPoints[1]){aU.show=false;aU.showGridline=false;aU.label=aO[1]}else{aU.label=aO[1]}}}else{aU.label=aO[1]}aU.setTick(aO[0],this.name);this._ticks.push(aU)}else{if(L.isPlainObject(aO)){L.extend(true,aU,aO);aU.axis=this.name;this._ticks.push(aU)}else{aU.value=aO;if(this.breakPoints){if(aO==this.breakPoints[0]){aU.label=this.breakTickLabel;aU._breakTick=true;aU.showGridline=false;aU.showMark=false}else{if(aO>this.breakPoints[0]&&aO<=this.breakPoints[1]){aU.show=false;aU.showGridline=false}}}aU.setTick(aO,this.name);this._ticks.push(aU)}}}this.numberTicks=aK.length;this.min=this._ticks[0].value;this.max=this._ticks[this.numberTicks-1].value;this.tickInterval=(this.max-this.min)/(this.numberTicks-1)}else{if(az=="xaxis"||az=="x2axis"){ah=this._plotDimensions.width}else{ah=this._plotDimensions.height}var ax=this.numberTicks;if(this.alignTicks){if(this.name==="x2axis"&&aj.axes.xaxis.show){ax=aj.axes.xaxis.numberTicks}else{if(this.name.charAt(0)==="y"&&this.name!=="yaxis"&&this.name!=="yMidAxis"&&aj.axes.yaxis.show){ax=aj.axes.yaxis.numberTicks}}}a6=((this.min!=null)?this.min:aB.min);aI=((this.max!=null)?this.max:aB.max);var av=aI-a6;var aS,ay;var at;if(this.tickOptions==null||!this.tickOptions.formatString){this._overrideFormatString=true}if(this.min==null||this.max==null&&this.tickInterval==null&&!this.autoscale){if(this.forceTickAt0){if(a6>0){a6=0}if(aI<0){aI=0}}if(this.forceTickAt100){if(a6>100){a6=100}if(aI<100){aI=100}}var aE=false,a1=false;if(this.min!=null){aE=true}else{if(this.max!=null){a1=true}}var aP=L.jqplot.LinearTickGenerator(a6,aI,this._scalefact,ax,aE,a1);var aw=(this.min!=null)?a6:a6+av*(this.padMin-1);var aQ=(this.max!=null)?aI:aI-av*(this.padMax-1);if(a6<aw||aI>aQ){aw=(this.min!=null)?a6:a6-av*(this.padMin-1);aQ=(this.max!=null)?aI:aI+av*(this.padMax-1);aP=L.jqplot.LinearTickGenerator(aw,aQ,this._scalefact,ax,aE,a1)}this.min=aP[0];this.max=aP[1];this.numberTicks=aP[2];this._autoFormatString=aP[3];this.tickInterval=aP[4]}else{if(a6==aI){var ai=0.05;if(a6>0){ai=Math.max(Math.log(a6)/Math.LN10,0.05)}a6-=ai;aI+=ai}if(this.autoscale&&this.min==null&&this.max==null){var ak,al,ar;var aC=false;var aN=false;var aA={min:null,max:null,average:null,stddev:null};for(var a0=0;a0<this._series.length;a0++){var aV=this._series[a0];var aD=(aV.fillAxis=="x")?aV._xaxis.name:aV._yaxis.name;if(this.name==aD){var aR=aV._plotValues[aV.fillAxis];var aG=aR[0];var a2=aR[0];for(var aZ=1;aZ<aR.length;aZ++){if(aR[aZ]<aG){aG=aR[aZ]}else{if(aR[aZ]>a2){a2=aR[aZ]}}}var au=(a2-aG)/a2;if(aV.renderer.constructor==L.jqplot.BarRenderer){if(aG>=0&&(aV.fillToZero||au>0.1)){aC=true}else{aC=false;if(aV.fill&&aV.fillToZero&&aG<0&&a2>0){aN=true}else{aN=false}}}else{if(aV.fill){if(aG>=0&&(aV.fillToZero||au>0.1)){aC=true}else{if(aG<0&&a2>0&&aV.fillToZero){aC=false;aN=true}else{aC=false;aN=false}}}else{if(aG<0){aC=false}}}}}if(aC){this.numberTicks=2+Math.ceil((ah-(this.tickSpacing-1))/this.tickSpacing);this.min=0;aH=0;al=aI/(this.numberTicks-1);at=Math.pow(10,Math.abs(Math.floor(Math.log(al)/Math.LN10)));if(al/at==parseInt(al/at,10)){al+=at}this.tickInterval=Math.ceil(al/at)*at;this.max=this.tickInterval*(this.numberTicks-1)}else{if(aN){this.numberTicks=2+Math.ceil((ah-(this.tickSpacing-1))/this.tickSpacing);var aJ=Math.ceil(Math.abs(a6)/av*(this.numberTicks-1));var a9=this.numberTicks-1-aJ;al=Math.max(Math.abs(a6/aJ),Math.abs(aI/a9));at=Math.pow(10,Math.abs(Math.floor(Math.log(al)/Math.LN10)));this.tickInterval=Math.ceil(al/at)*at;this.max=this.tickInterval*a9;this.min=-this.tickInterval*aJ}else{if(this.numberTicks==null){if(this.tickInterval){this.numberTicks=3+Math.ceil(av/this.tickInterval)}else{this.numberTicks=2+Math.ceil((ah-(this.tickSpacing-1))/this.tickSpacing)}}if(this.tickInterval==null){al=av/(this.numberTicks-1);if(al<1){at=Math.pow(10,Math.abs(Math.floor(Math.log(al)/Math.LN10)))}else{at=1}this.tickInterval=Math.ceil(al*at*this.pad)/at}else{at=1/this.tickInterval}ak=this.tickInterval*(this.numberTicks-1);ar=(ak-av)/2;if(this.min==null){this.min=Math.floor(at*(a6-ar))/at}if(this.max==null){this.max=this.min+ak}}}var aF=L.jqplot.getSignificantFigures(this.tickInterval);var aM;if(aF.digitsLeft>=aF.significantDigits){aM="%d"}else{var at=Math.max(0,5-aF.digitsLeft);at=Math.min(at,aF.digitsRight);aM="%."+at+"f"}this._autoFormatString=aM}else{aS=(this.min!=null)?this.min:a6-av*(this.padMin-1);ay=(this.max!=null)?this.max:aI+av*(this.padMax-1);av=ay-aS;if(this.numberTicks==null){if(this.tickInterval!=null){this.numberTicks=Math.ceil((ay-aS)/this.tickInterval)+1}else{if(ah>100){this.numberTicks=parseInt(3+(ah-100)/75,10)}else{this.numberTicks=2}}}if(this.tickInterval==null){this.tickInterval=av/(this.numberTicks-1)}if(this.max==null){ay=aS+this.tickInterval*(this.numberTicks-1)}if(this.min==null){aS=ay-this.tickInterval*(this.numberTicks-1)}var aF=L.jqplot.getSignificantFigures(this.tickInterval);var aM;if(aF.digitsLeft>=aF.significantDigits){aM="%d"}else{var at=Math.max(0,5-aF.digitsLeft);at=Math.min(at,aF.digitsRight);aM="%."+at+"f"}this._autoFormatString=aM;this.min=aS;this.max=ay}if(this.renderer.constructor==L.jqplot.LinearAxisRenderer&&this._autoFormatString==""){av=this.max-this.min;var a7=new this.tickRenderer(this.tickOptions);var aL=a7.formatString||L.jqplot.config.defaultTickFormatString;var aL=aL.match(L.jqplot.sprintf.regex)[0];var a3=0;if(aL){if(aL.search(/[fFeEgGpP]/)>-1){var aY=aL.match(/\%\.(\d{0,})?[eEfFgGpP]/);if(aY){a3=parseInt(aY[1],10)}else{a3=6}}else{if(aL.search(/[di]/)>-1){a3=0}}var aq=Math.pow(10,-a3);if(this.tickInterval<aq){if(aW==null&&ba==null){this.tickInterval=aq;if(a5==null&&aH==null){this.min=Math.floor(this._dataBounds.min/aq)*aq;if(this.min==this._dataBounds.min){this.min=this._dataBounds.min-this.tickInterval}this.max=Math.ceil(this._dataBounds.max/aq)*aq;if(this.max==this._dataBounds.max){this.max=this._dataBounds.max+this.tickInterval}var aX=(this.max-this.min)/this.tickInterval;aX=aX.toFixed(11);aX=Math.ceil(aX);this.numberTicks=aX+1}else{if(a5==null){var aX=(this._dataBounds.max-this.min)/this.tickInterval;aX=aX.toFixed(11);this.numberTicks=Math.ceil(aX)+2;this.max=this.min+this.tickInterval*(this.numberTicks-1)}else{if(aH==null){var aX=(this.max-this._dataBounds.min)/this.tickInterval;aX=aX.toFixed(11);this.numberTicks=Math.ceil(aX)+2;this.min=this.max-this.tickInterval*(this.numberTicks-1)}else{this.numberTicks=Math.ceil((a5-aH)/this.tickInterval)+1;this.min=Math.floor(aH*Math.pow(10,a3))/Math.pow(10,a3);this.max=Math.ceil(a5*Math.pow(10,a3))/Math.pow(10,a3);this.numberTicks=Math.ceil((this.max-this.min)/this.tickInterval)+1}}}}}}}}if(this._overrideFormatString&&this._autoFormatString!=""){this.tickOptions=this.tickOptions||{};this.tickOptions.formatString=this._autoFormatString}var aU,a8;for(var a0=0;a0<this.numberTicks;a0++){a4=this.min+a0*this.tickInterval;aU=new this.tickRenderer(this.tickOptions);aU.setTick(a4,this.name);this._ticks.push(aU);if(a0<this.numberTicks-1){for(var aZ=0;aZ<this.minorTicks;aZ++){a4+=this.tickInterval/(this.minorTicks+1);a8=L.extend(true,{},this.tickOptions,{name:this.name,value:a4,label:"",isMinorTick:true});aU=new this.tickRenderer(a8);this._ticks.push(aU)}}aU=null}}if(this.tickInset){this.min=this.min-this.tickInset*this.tickInterval;this.max=this.max+this.tickInset*this.tickInterval}aT=null};


L.jqplot.LinearAxisRenderer.prototype.resetTickValues=function(aj){if(L.isArray(aj)&&aj.length==this._ticks.length){var ai;for(var ah=0;ah<aj.length;ah++){ai=this._ticks[ah];ai.value=aj[ah];ai.label=ai.formatter(ai.formatString,aj[ah]);ai.label=ai.prefix+ai.label;ai._elem.html(ai.label)}ai=null;this.min=L.jqplot.arrayMin(aj);this.max=L.jqplot.arrayMax(aj);this.pack()}};L.jqplot.LinearAxisRenderer.prototype.pack=function(aj,ai){aj=aj||{};ai=ai||this._offsets;var ay=this._ticks;var au=this.max;var at=this.min;var ao=ai.max;var am=ai.min;var aq=(this._label==null)?false:this._label.show;for(var ar in aj){this._elem.css(ar,aj[ar])}this._offsets=ai;var ak=ao-am;var al=au-at;if(this.breakPoints){al=al-this.breakPoints[1]+this.breakPoints[0];this.p2u=function(aA){return(aA-am)*al/ak+at};this.u2p=function(aA){if(aA>this.breakPoints[0]&&aA<this.breakPoints[1]){aA=this.breakPoints[0]}if(aA<=this.breakPoints[0]){return(aA-at)*ak/al+am}else{return(aA-this.breakPoints[1]+this.breakPoints[0]-at)*ak/al+am}};if(this.name.charAt(0)=="x"){this.series_u2p=function(aA){if(aA>this.breakPoints[0]&&aA<this.breakPoints[1]){aA=this.breakPoints[0]}if(aA<=this.breakPoints[0]){return(aA-at)*ak/al}else{return(aA-this.breakPoints[1]+this.breakPoints[0]-at)*ak/al}};this.series_p2u=function(aA){return aA*al/ak+at}}else{this.series_u2p=function(aA){if(aA>this.breakPoints[0]&&aA<this.breakPoints[1]){aA=this.breakPoints[0]}if(aA>=this.breakPoints[1]){return(aA-au)*ak/al}else{return(aA+this.breakPoints[1]-this.breakPoints[0]-au)*ak/al}};this.series_p2u=function(aA){return aA*al/ak+au}}}else{this.p2u=function(aA){return(aA-am)*al/ak+at};this.u2p=function(aA){return(aA-at)*ak/al+am};if(this.name=="xaxis"||this.name=="x2axis"){this.series_u2p=function(aA){return(aA-at)*ak/al};this.series_p2u=function(aA){return aA*al/ak+at}}else{this.series_u2p=function(aA){return(aA-au)*ak/al};this.series_p2u=function(aA){return aA*al/ak+au}}}if(this.show){if(this.name=="xaxis"||this.name=="x2axis"){for(var av=0;av<ay.length;av++){var ap=ay[av];if(ap.show&&ap.showLabel){var ah;if(ap.constructor==L.jqplot.CanvasAxisTickRenderer&&ap.angle){var ax=(this.name=="xaxis")?1:-1;switch(ap.labelPosition){case"auto":if(ax*ap.angle<0){ah=-ap.getWidth()+ap._textRenderer.height*Math.sin(-ap._textRenderer.angle)/2}else{ah=-ap._textRenderer.height*Math.sin(ap._textRenderer.angle)/2}break;case"end":ah=-ap.getWidth()+ap._textRenderer.height*Math.sin(-ap._textRenderer.angle)/2;break;case"start":ah=-ap._textRenderer.height*Math.sin(ap._textRenderer.angle)/2;break;case"middle":ah=-ap.getWidth()/2+ap._textRenderer.height*Math.sin(-ap._textRenderer.angle)/2;break;default:ah=-ap.getWidth()/2+ap._textRenderer.height*Math.sin(-ap._textRenderer.angle)/2;break}}else{ah=-ap.getWidth()/2}var az=this.u2p(ap.value)+ah+"px";ap._elem.css("left",az);ap.pack()}}if(aq){var an=this._label._elem.outerWidth(true);this._label._elem.css("left",am+ak/2-an/2+"px");if(this.name=="xaxis"){this._label._elem.css("bottom","0px")}else{this._label._elem.css("top","0px")}this._label.pack()}}else{for(var av=0;av<ay.length;av++){var ap=ay[av];if(ap.show&&ap.showLabel){var ah;if(ap.constructor==L.jqplot.CanvasAxisTickRenderer&&ap.angle){var ax=(this.name=="yaxis")?1:-1;switch(ap.labelPosition){case"auto":case"end":if(ax*ap.angle<0){ah=-ap._textRenderer.height*Math.cos(-ap._textRenderer.angle)/2}else{ah=-ap.getHeight()+ap._textRenderer.height*Math.cos(ap._textRenderer.angle)/2}break;case"start":if(ap.angle>0){ah=-ap._textRenderer.height*Math.cos(-ap._textRenderer.angle)/2}else{ah=-ap.getHeight()+ap._textRenderer.height*Math.cos(ap._textRenderer.angle)/2}break;case"middle":ah=-ap.getHeight()/2;break;default:ah=-ap.getHeight()/2;break}}else{ah=-ap.getHeight()/2}var az=this.u2p(ap.value)+ah+"px";ap._elem.css("top",az);ap.pack()}}if(aq){var aw=this._label._elem.outerHeight(true);this._label._elem.css("top",ao-ak/2-aw/2+"px");if(this.name=="yaxis"){this._label._elem.css("left","0px")}else{this._label._elem.css("right","0px")}this._label.pack()}}}ay=null};function i(ai){var ah;ai=Math.abs(ai);if(ai>=10){ah="%d"}else{if(ai>1){if(ai===parseInt(ai,10)){ah="%d"}else{ah="%.1f"}}else{var aj=-Math.floor(Math.log(ai)/Math.LN10);ah="%."+aj+"f"}}return ah}var b=[0.1,0.2,0.3,0.4,0.5,0.8,1,2,3,4,5];var c=function(ai){var ah=b.indexOf(ai);if(ah>0){return b[ah-1]}else{return b[b.length-1]/100}};var k=function(ai){var ah=b.indexOf(ai);if(ah<b.length-1){return b[ah+1]}else{return b[0]*100}};function d(al,au,at){var aq=Math.floor(at/2);var ai=Math.ceil(at*1.5);var ak=Number.MAX_VALUE;var ah=(au-al);var ax;var ap;var ar;var ay=L.jqplot.getSignificantFigures;var aw;var an;var ao;var av;for(var am=0,aj=ai-aq+1;am<aj;am++){ao=aq+am;ax=ah/(ao-1);ap=ay(ax);ax=Math.abs(at-ao)+ap.digitsRight;if(ax<ak){ak=ax;ar=ao;av=ap.digitsRight}else{if(ax===ak){if(ap.digitsRight<av){ar=ao;av=ap.digitsRight}}}}aw=Math.max(av,Math.max(ay(al).digitsRight,ay(au).digitsRight));if(aw===0){an="%d"}else{an="%."+aw+"f"}ax=ah/(ar-1);return[al,au,ar,an,ax]}function W(ai,al){al=al||7;var ak=ai/(al-1);var aj=Math.pow(10,Math.floor(Math.log(ak)/Math.LN10));var am=ak/aj;var ah;if(aj<1){if(am>5){ah=10*aj}else{if(am>2){ah=5*aj}else{if(am>1){ah=2*aj}else{ah=aj}}}}else{if(am>5){ah=10*aj}else{if(am>4){ah=5*aj}else{if(am>3){ah=4*aj}else{if(am>2){ah=3*aj}else{if(am>1){ah=2*aj}else{ah=aj}}}}}}return ah}function Q(ai,ah){ah=ah||1;var ak=Math.floor(Math.log(ai)/Math.LN10);var am=Math.pow(10,ak);var al=ai/am;var aj;al=al/ah;if(al<=0.38){aj=0.1}else{if(al<=1.6){aj=0.2}else{if(al<=4){aj=0.5}else{if(al<=8){aj=1}else{if(al<=16){aj=2}else{aj=5}}}}}return aj*am}function x(aj,ai){var al=Math.floor(Math.log(aj)/Math.LN10);var an=Math.pow(10,al);var am=aj/an;var ah;var ak;am=am/ai;if(am<=0.38){ak=0.1}else{if(am<=1.6){ak=0.2}else{if(am<=4){ak=0.5}else{if(am<=8){ak=1}else{if(am<=16){ak=2}else{ak=5}}}}}ah=ak*an;return[ah,ak,an]}L.jqplot.LinearTickGenerator=function(an,aq,aj,ak,ao,ar){ao=(ao===null)?false:ao;ar=(ar===null||ao)?false:ar;if(an===aq){aq=(aq)?0:1}aj=aj||1;if(aq<an){var at=aq;aq=an;an=at}var ai=[];var aw=Q(aq-an,aj);var av=L.jqplot.getSignificantFigures;if(ak==null){if(!ao&&!ar){ai[0]=Math.floor(an/aw)*aw;ai[1]=Math.ceil(aq/aw)*aw;ai[2]=Math.round((ai[1]-ai[0])/aw+1);ai[3]=i(aw);ai[4]=aw}else{if(ao){ai[0]=an;ai[2]=Math.ceil((aq-an)/aw+1);ai[1]=an+(ai[2]-1)*aw;var au=av(an).digitsRight;var ap=av(aw).digitsRight;if(au<ap){ai[3]=i(aw)}else{ai[3]="%."+au+"f"}ai[4]=aw}else{if(ar){ai[1]=aq;ai[2]=Math.ceil((aq-an)/aw+1);ai[0]=aq-(ai[2]-1)*aw;var al=av(aq).digitsRight;var ap=av(aw).digitsRight;if(al<ap){ai[3]=i(aw)}else{ai[3]="%."+al+"f"}ai[4]=aw}}}}else{var am=[];am[0]=Math.floor(an/aw)*aw;am[1]=Math.ceil(aq/aw)*aw;am[2]=Math.round((am[1]-am[0])/aw+1);am[3]=i(aw);am[4]=aw;if(am[2]===ak){ai=am}else{var ah=W(am[1]-am[0],ak);ai[0]=am[0];ai[2]=ak;ai[4]=ah;ai[3]=i(ah);ai[1]=ai[0]+(ai[2]-1)*ai[4]}}return ai};L.jqplot.LinearTickGenerator.bestLinearInterval=Q;L.jqplot.LinearTickGenerator.bestInterval=W;L.jqplot.LinearTickGenerator.bestLinearComponents=x;L.jqplot.LinearTickGenerator.bestConstrainedInterval=d;L.jqplot.MarkerRenderer=function(ah){this.show=true;this.style="filledCircle";this.lineWidth=2;this.size=9;this.color="#666666";this.shadow=true;this.shadowAngle=45;this.shadowOffset=1;this.shadowDepth=3;this.shadowAlpha="0.07";this.shadowRenderer=new L.jqplot.ShadowRenderer();this.shapeRenderer=new L.jqplot.ShapeRenderer();L.extend(true,this,ah)};L.jqplot.MarkerRenderer.prototype.init=function(ah){L.extend(true,this,ah);var aj={angle:this.shadowAngle,offset:this.shadowOffset,alpha:this.shadowAlpha,lineWidth:this.lineWidth,depth:this.shadowDepth,closePath:true};if(this.style.indexOf("filled")!=-1){aj.fill=true}if(this.style.indexOf("ircle")!=-1){aj.isarc=true;aj.closePath=false}this.shadowRenderer.init(aj);var ai={fill:false,isarc:false,strokeStyle:this.color,fillStyle:this.color,lineWidth:this.lineWidth,closePath:true};if(this.style.indexOf("filled")!=-1){ai.fill=true}if(this.style.indexOf("ircle")!=-1){ai.isarc=true;ai.closePath=false}this.shapeRenderer.init(ai)};L.jqplot.MarkerRenderer.prototype.drawDiamond=function(aj,ai,am,al,ao){var ah=1.2;var ap=this.size/2/ah;var an=this.size/2*ah;var ak=[[aj-ap,ai],[aj,ai+an],[aj+ap,ai],[aj,ai-an]];if(this.shadow){this.shadowRenderer.draw(am,ak)}this.shapeRenderer.draw(am,ak,ao)};L.jqplot.MarkerRenderer.prototype.drawPlus=function(ak,aj,an,am,aq){var ai=1;var ar=this.size/2*ai;var ao=this.size/2*ai;var ap=[[ak,aj-ao],[ak,aj+ao]];var al=[[ak+ar,aj],[ak-ar,aj]];var ah=L.extend(true,{},this.options,{closePath:false});if(this.shadow){this.shadowRenderer.draw(an,ap,{closePath:false});this.shadowRenderer.draw(an,al,{closePath:false})}this.shapeRenderer.draw(an,ap,ah);this.shapeRenderer.draw(an,al,ah)};L.jqplot.MarkerRenderer.prototype.drawX=function(ak,aj,an,am,aq){var ai=1;var ar=this.size/2*ai;var ao=this.size/2*ai;var ah=L.extend(true,{},this.options,{closePath:false});var ap=[[ak-ar,aj-ao],[ak+ar,aj+ao]];var al=[[ak-ar,aj+ao],[ak+ar,aj-ao]];if(this.shadow){this.shadowRenderer.draw(an,ap,{closePath:false});this.shadowRenderer.draw(an,al,{closePath:false})}this.shapeRenderer.draw(an,ap,ah);this.shapeRenderer.draw(an,al,ah)};L.jqplot.MarkerRenderer.prototype.drawDash=function(aj,ai,am,al,ao){var ah=1;var ap=this.size/2*ah;var an=this.size/2*ah;var ak=[[aj-ap,ai],[aj+ap,ai]];if(this.shadow){this.shadowRenderer.draw(am,ak)}this.shapeRenderer.draw(am,ak,ao)};L.jqplot.MarkerRenderer.prototype.drawLine=function(am,al,ah,ak,ai){var aj=[am,al];if(this.shadow){this.shadowRenderer.draw(ah,aj)}this.shapeRenderer.draw(ah,aj,ai)};L.jqplot.MarkerRenderer.prototype.drawSquare=function(aj,ai,am,al,ao){var ah=1;var ap=this.size/2/ah;var an=this.size/2*ah;var ak=[[aj-ap,ai-an],[aj-ap,ai+an],[aj+ap,ai+an],[aj+ap,ai-an]];if(this.shadow){this.shadowRenderer.draw(am,ak)}this.shapeRenderer.draw(am,ak,ao)};L.jqplot.MarkerRenderer.prototype.drawCircle=function(ai,ao,ak,an,al){var ah=this.size/2;var aj=2*Math.PI;var am=[ai,ao,ah,0,aj,true];if(this.shadow){this.shadowRenderer.draw(ak,am)}this.shapeRenderer.draw(ak,am,al)};L.jqplot.MarkerRenderer.prototype.draw=function(ah,ak,ai,aj){aj=aj||{};if(aj.show==null||aj.show!=false){if(aj.color&&!aj.fillStyle){aj.fillStyle=aj.color}if(aj.color&&!aj.strokeStyle){aj.strokeStyle=aj.color}switch(this.style){case"diamond":this.drawDiamond(ah,ak,ai,false,aj);break;case"filledDiamond":this.drawDiamond(ah,ak,ai,true,aj);break;case"circle":this.drawCircle(ah,ak,ai,false,aj);break;case"filledCircle":this.drawCircle(ah,ak,ai,true,aj);break;case"square":this.drawSquare(ah,ak,ai,false,aj);break;case"filledSquare":this.drawSquare(ah,ak,ai,true,aj);break;case"x":this.drawX(ah,ak,ai,true,aj);break;case"plus":this.drawPlus(ah,ak,ai,true,aj);break;case"dash":this.drawDash(ah,ak,ai,true,aj);break;case"line":this.drawLine(ah,ak,ai,false,aj);break;default:this.drawDiamond(ah,ak,ai,false,aj);break}}};L.jqplot.ShadowRenderer=function(ah){this.angle=45;this.offset=1;this.alpha=0.07;this.lineWidth=1.5;this.lineJoin="miter";this.lineCap="round";this.closePath=false;this.fill=false;this.depth=3;this.strokeStyle="rgba(0,0,0,0.1)";this.isarc=false;L.extend(true,this,ah)};L.jqplot.ShadowRenderer.prototype.init=function(ah){L.extend(true,this,ah)};L.jqplot.ShadowRenderer.prototype.draw=function(av,at,ax){av.save();var ah=(ax!=null)?ax:{};var au=(ah.fill!=null)?ah.fill:this.fill;var ap=(ah.fillRect!=null)?ah.fillRect:this.fillRect;var ao=(ah.closePath!=null)?ah.closePath:this.closePath;var al=(ah.offset!=null)?ah.offset:this.offset;var aj=(ah.alpha!=null)?ah.alpha:this.alpha;var an=(ah.depth!=null)?ah.depth:this.depth;var aw=(ah.isarc!=null)?ah.isarc:this.isarc;var aq=(ah.linePattern!=null)?ah.linePattern:this.linePattern;av.lineWidth=(ah.lineWidth!=null)?ah.lineWidth:this.lineWidth;av.lineJoin=(ah.lineJoin!=null)?ah.lineJoin:this.lineJoin;av.lineCap=(ah.lineCap!=null)?ah.lineCap:this.lineCap;av.strokeStyle=ah.strokeStyle||this.strokeStyle||"rgba(0,0,0,"+aj+")";av.fillStyle=ah.fillStyle||this.fillStyle||"rgba(0,0,0,"+aj+")";for(var ak=0;ak<an;ak++){var ar=L.jqplot.LinePattern(av,aq);av.translate(Math.cos(this.angle*Math.PI/180)*al,Math.sin(this.angle*Math.PI/180)*al);ar.beginPath();if(aw){av.arc(at[0],at[1],at[2],at[3],at[4],true)}else{if(ap){if(ap){av.fillRect(at[0],at[1],at[2],at[3])}}else{if(at&&at.length){var ai=true;for(var am=0;am<at.length;am++){if(at[am][0]!=null&&at[am][1]!=null){if(ai){ar.moveTo(at[am][0],at[am][1]);ai=false}else{ar.lineTo(at[am][0],at[am][1])}}else{ai=true}}}}}if(ao){ar.closePath()}if(au){av.fill()}else{av.stroke()}}av.restore()};L.jqplot.ShapeRenderer=function(ah){this.lineWidth=1.5;this.linePattern="solid";this.lineJoin="miter";this.lineCap="round";this.closePath=false;this.fill=false;this.isarc=false;this.fillRect=false;this.strokeRect=false;this.clearRect=false;this.strokeStyle="#999999";this.fillStyle="#999999";L.extend(true,this,ah)};L.jqplot.ShapeRenderer.prototype.init=function(ah){L.extend(true,this,ah)};L.jqplot.ShapeRenderer.prototype.draw=function(at,aq,av){at.save();var ah=(av!=null)?av:{};var ar=(ah.fill!=null)?ah.fill:this.fill;var am=(ah.closePath!=null)?ah.closePath:this.closePath;var an=(ah.fillRect!=null)?ah.fillRect:this.fillRect;var ak=(ah.strokeRect!=null)?ah.strokeRect:this.strokeRect;var ai=(ah.clearRect!=null)?ah.clearRect:this.clearRect;var au=(ah.isarc!=null)?ah.isarc:this.isarc;var ao=(ah.linePattern!=null)?ah.linePattern:this.linePattern;var ap=L.jqplot.LinePattern(at,ao);at.lineWidth=ah.lineWidth||this.lineWidth;at.lineJoin=ah.lineJoin||this.lineJoin;at.lineCap=ah.lineCap||this.lineCap;at.strokeStyle=(ah.strokeStyle||ah.color)||this.strokeStyle;at.fillStyle=ah.fillStyle||this.fillStyle;at.beginPath();if(au){at.arc(aq[0],aq[1],aq[2],aq[3],aq[4],true);if(am){at.closePath()}if(ar){at.fill()}else{at.stroke()}at.restore();return}else{if(ai){at.clearRect(aq[0],aq[1],aq[2],aq[3]);at.restore();return}else{if(an||ak){if(an){at.fillRect(aq[0],aq[1],aq[2],aq[3])}if(ak){at.strokeRect(aq[0],aq[1],aq[2],aq[3]);at.restore();return}}else{if(aq&&aq.length){var aj=true;for(var al=0;al<aq.length;al++){if(aq[al][0]!=null&&aq[al][1]!=null){if(aj){ap.moveTo(aq[al][0],aq[al][1]);aj=false}else{ap.lineTo(aq[al][0],aq[al][1])}}else{aj=true}}if(am){ap.closePath()}if(ar){at.fill()}else{at.stroke()}}}}}at.restore()};L.jqplot.TableLegendRenderer=function(){};L.jqplot.TableLegendRenderer.prototype.init=function(ah){L.extend(true,this,ah)};L.jqplot.TableLegendRenderer.prototype.addrow=function(aq,ak,ah,ao){var al=(ah)?this.rowSpacing+"px":"0px";var ap;var aj;var ai;var an;var am;ai=document.createElement("tr");ap=L(ai);ap.addClass("jqplot-table-legend");ai=null;if(ao){ap.prependTo(this._elem)}else{ap.appendTo(this._elem)}if(this.showSwatches){aj=L(document.createElement("td"));aj.addClass("jqplot-table-legend jqplot-table-legend-swatch");aj.css({textAlign:"center",paddingTop:al});an=L(document.createElement("div"));an.addClass("jqplot-table-legend-swatch-outline");am=L(document.createElement("div"));am.addClass("jqplot-table-legend-swatch");am.css({backgroundColor:ak,borderColor:ak});ap.append(aj.append(an.append(am)))}if(this.showLabels){aj=L(document.createElement("td"));aj.addClass("jqplot-table-legend jqplot-table-legend-label");aj.css("paddingTop",al);ap.append(aj);if(this.escapeHtml){aj.text(aq)}else{aj.html(aq)}}aj=null;an=null;am=null;ap=null;ai=null};L.jqplot.TableLegendRenderer.prototype.draw=function(){if(this._elem){this._elem.emptyForce();this._elem=null}if(this.show){var am=this._series;var ai=document.createElement("table");this._elem=L(ai);this._elem.addClass("jqplot-table-legend");var ar={position:"absolute"};if(this.background){ar.background=this.background}if(this.border){ar.border=this.border}if(this.fontSize){ar.fontSize=this.fontSize}if(this.fontFamily){ar.fontFamily=this.fontFamily}if(this.textColor){ar.textColor=this.textColor}if(this.marginTop!=null){ar.marginTop=this.marginTop}if(this.marginBottom!=null){ar.marginBottom=this.marginBottom}if(this.marginLeft!=null){ar.marginLeft=this.marginLeft}if(this.marginRight!=null){ar.marginRight=this.marginRight}var ah=false,ao=false,aq;for(var an=0;an<am.length;an++){aq=am[an];if(aq._stack||aq.renderer.constructor==L.jqplot.BezierCurveRenderer){ao=true}if(aq.show&&aq.showLabel){var al=this.labels[an]||aq.label.toString();if(al){var aj=aq.color;if(ao&&an<am.length-1){ah=true}else{if(ao&&an==am.length-1){ah=false}}this.renderer.addrow.call(this,al,aj,ah,ao);ah=true}for(var ak=0;ak<L.jqplot.addLegendRowHooks.length;ak++){var ap=L.jqplot.addLegendRowHooks[ak].call(this,aq);if(ap){this.renderer.addrow.call(this,ap.label,ap.color,ah);ah=true}}al=null}}}return this._elem};L.jqplot.TableLegendRenderer.prototype.pack=function(aj){if(this.show){if(this.placement=="insideGrid"){switch(this.location){case"nw":var ai=aj.left;var ah=aj.top;this._elem.css("left",ai);this._elem.css("top",ah);break;case"n":var ai=(aj.left+(this._plotDimensions.width-aj.right))/2-this.getWidth()/2;var ah=aj.top;this._elem.css("left",ai);this._elem.css("top",ah);break;case"ne":var ai=aj.right;var ah=aj.top;this._elem.css({right:ai,top:ah});break;case"e":var ai=aj.right;var ah=(aj.top+(this._plotDimensions.height-aj.bottom))/2-this.getHeight()/2;this._elem.css({right:ai,top:ah});break;case"se":var ai=aj.right;var ah=aj.bottom;this._elem.css({right:ai,bottom:ah});break;case"s":var ai=(aj.left+(this._plotDimensions.width-aj.right))/2-this.getWidth()/2;var ah=aj.bottom;this._elem.css({left:ai,bottom:ah});break;case"sw":var ai=aj.left;var ah=aj.bottom;this._elem.css({left:ai,bottom:ah});break;case"w":var ai=aj.left;var ah=(aj.top+(this._plotDimensions.height-aj.bottom))/2-this.getHeight()/2;this._elem.css({left:ai,top:ah});break;default:var ai=aj.right;var ah=aj.bottom;this._elem.css({right:ai,bottom:ah});break}}else{if(this.placement=="outside"){switch(this.location){case"nw":var ai=this._plotDimensions.width-aj.left;var ah=aj.top;this._elem.css("right",ai);this._elem.css("top",ah);break;case"n":var ai=(aj.left+(this._plotDimensions.width-aj.right))/2-this.getWidth()/2;var ah=this._plotDimensions.height-aj.top;this._elem.css("left",ai);this._elem.css("bottom",ah);break;case"ne":var ai=this._plotDimensions.width-aj.right;var ah=aj.top;this._elem.css({left:ai,top:ah});break;case"e":var ai=this._plotDimensions.width-aj.right;var ah=(aj.top+(this._plotDimensions.height-aj.bottom))/2-this.getHeight()/2;this._elem.css({left:ai,top:ah});break;case"se":var ai=this._plotDimensions.width-aj.right;var ah=aj.bottom;this._elem.css({left:ai,bottom:ah});break;


case"s":var ai=(aj.left+(this._plotDimensions.width-aj.right))/2-this.getWidth()/2;var ah=this._plotDimensions.height-aj.bottom;this._elem.css({left:ai,top:ah});break;case"sw":var ai=this._plotDimensions.width-aj.left;var ah=aj.bottom;this._elem.css({right:ai,bottom:ah});break;case"w":var ai=this._plotDimensions.width-aj.left;var ah=(aj.top+(this._plotDimensions.height-aj.bottom))/2-this.getHeight()/2;this._elem.css({right:ai,top:ah});break;default:var ai=aj.right;var ah=aj.bottom;this._elem.css({right:ai,bottom:ah});break}}else{switch(this.location){case"nw":this._elem.css({left:0,top:aj.top});break;case"n":var ai=(aj.left+(this._plotDimensions.width-aj.right))/2-this.getWidth()/2;this._elem.css({left:ai,top:aj.top});break;case"ne":this._elem.css({right:0,top:aj.top});break;case"e":var ah=(aj.top+(this._plotDimensions.height-aj.bottom))/2-this.getHeight()/2;this._elem.css({right:aj.right,top:ah});break;case"se":this._elem.css({right:aj.right,bottom:aj.bottom});break;case"s":var ai=(aj.left+(this._plotDimensions.width-aj.right))/2-this.getWidth()/2;this._elem.css({left:ai,bottom:aj.bottom});break;case"sw":this._elem.css({left:aj.left,bottom:aj.bottom});break;case"w":var ah=(aj.top+(this._plotDimensions.height-aj.bottom))/2-this.getHeight()/2;this._elem.css({left:aj.left,top:ah});break;default:this._elem.css({right:aj.right,bottom:aj.bottom});break}}}}};L.jqplot.ThemeEngine=function(){this.themes={};this.activeTheme=null};L.jqplot.ThemeEngine.prototype.init=function(){var ak=new L.jqplot.Theme({_name:"Default"});var an,ai,am;for(an in ak.target){if(an=="textColor"){ak.target[an]=this.target.css("color")}else{ak.target[an]=this.target.css(an)}}if(this.title.show&&this.title._elem){for(an in ak.title){if(an=="textColor"){ak.title[an]=this.title._elem.css("color")}else{ak.title[an]=this.title._elem.css(an)}}}for(an in ak.grid){ak.grid[an]=this.grid[an]}if(ak.grid.backgroundColor==null&&this.grid.background!=null){ak.grid.backgroundColor=this.grid.background}if(this.legend.show&&this.legend._elem){for(an in ak.legend){if(an=="textColor"){ak.legend[an]=this.legend._elem.css("color")}else{ak.legend[an]=this.legend._elem.css(an)}}}var aj;for(ai=0;ai<this.series.length;ai++){aj=this.series[ai];if(aj.renderer.constructor==L.jqplot.LineRenderer){ak.series.push(new p())}else{if(aj.renderer.constructor==L.jqplot.BarRenderer){ak.series.push(new T())}else{if(aj.renderer.constructor==L.jqplot.PieRenderer){ak.series.push(new f())}else{if(aj.renderer.constructor==L.jqplot.DonutRenderer){ak.series.push(new G())}else{if(aj.renderer.constructor==L.jqplot.FunnelRenderer){ak.series.push(new Z())}else{if(aj.renderer.constructor==L.jqplot.MeterGaugeRenderer){ak.series.push(new D())}else{ak.series.push({})}}}}}}for(an in ak.series[ai]){ak.series[ai][an]=aj[an]}}var ah,al;for(an in this.axes){al=this.axes[an];ah=ak.axes[an]=new P();ah.borderColor=al.borderColor;ah.borderWidth=al.borderWidth;if(al._ticks&&al._ticks[0]){for(am in ah.ticks){if(al._ticks[0].hasOwnProperty(am)){ah.ticks[am]=al._ticks[0][am]}else{if(al._ticks[0]._elem){ah.ticks[am]=al._ticks[0]._elem.css(am)}}}}if(al._label&&al._label.show){for(am in ah.label){if(al._label[am]){ah.label[am]=al._label[am]}else{if(al._label._elem){if(am=="textColor"){ah.label[am]=al._label._elem.css("color")}else{ah.label[am]=al._label._elem.css(am)}}}}}}this.themeEngine._add(ak);this.themeEngine.activeTheme=this.themeEngine.themes[ak._name]};L.jqplot.ThemeEngine.prototype.get=function(ah){if(!ah){return this.activeTheme}else{return this.themes[ah]}};function O(ai,ah){return ai-ah}L.jqplot.ThemeEngine.prototype.getThemeNames=function(){var ah=[];for(var ai in this.themes){ah.push(ai)}return ah.sort(O)};L.jqplot.ThemeEngine.prototype.getThemes=function(){var ai=[];var ah=[];for(var ak in this.themes){ai.push(ak)}ai.sort(O);for(var aj=0;aj<ai.length;aj++){ah.push(this.themes[ai[aj]])}return ah};L.jqplot.ThemeEngine.prototype.activate=function(av,aB){var ah=false;if(!aB&&this.activeTheme&&this.activeTheme._name){aB=this.activeTheme._name}if(!this.themes.hasOwnProperty(aB)){throw new Error("No theme of that name")}else{var am=this.themes[aB];this.activeTheme=am;var aA,at=false,ar=false;var ai=["xaxis","x2axis","yaxis","y2axis"];for(aw=0;aw<ai.length;aw++){var an=ai[aw];if(am.axesStyles.borderColor!=null){av.axes[an].borderColor=am.axesStyles.borderColor}if(am.axesStyles.borderWidth!=null){av.axes[an].borderWidth=am.axesStyles.borderWidth}}for(var az in av.axes){var ak=av.axes[az];if(ak.show){var aq=am.axes[az]||{};var ao=am.axesStyles;var al=L.jqplot.extend(true,{},aq,ao);aA=(am.axesStyles.borderColor!=null)?am.axesStyles.borderColor:al.borderColor;if(al.borderColor!=null){ak.borderColor=al.borderColor;ah=true}aA=(am.axesStyles.borderWidth!=null)?am.axesStyles.borderWidth:al.borderWidth;if(al.borderWidth!=null){ak.borderWidth=al.borderWidth;ah=true}if(ak._ticks&&ak._ticks[0]){for(var aj in al.ticks){aA=al.ticks[aj];if(aA!=null){ak.tickOptions[aj]=aA;ak._ticks=[];ah=true}}}if(ak._label&&ak._label.show){for(var aj in al.label){aA=al.label[aj];if(aA!=null){ak.labelOptions[aj]=aA;ah=true}}}}}for(var au in am.grid){if(am.grid[au]!=null){av.grid[au]=am.grid[au]}}if(!ah){av.grid.draw()}if(av.legend.show){for(au in am.legend){if(am.legend[au]!=null){av.legend[au]=am.legend[au]}}}if(av.title.show){for(au in am.title){if(am.title[au]!=null){av.title[au]=am.title[au]}}}var aw;for(aw=0;aw<am.series.length;aw++){var ap={};var ay=false;for(au in am.series[aw]){aA=(am.seriesStyles[au]!=null)?am.seriesStyles[au]:am.series[aw][au];if(aA!=null){ap[au]=aA;if(au=="color"){av.series[aw].renderer.shapeRenderer.fillStyle=aA;av.series[aw].renderer.shapeRenderer.strokeStyle=aA;av.series[aw][au]=aA}else{if((au=="lineWidth")||(au=="linePattern")){av.series[aw].renderer.shapeRenderer[au]=aA;av.series[aw][au]=aA}else{if(au=="markerOptions"){V(av.series[aw].markerOptions,aA);V(av.series[aw].markerRenderer,aA)}else{av.series[aw][au]=aA}}}ah=true}}}if(ah){av.target.empty();av.draw()}for(au in am.target){if(am.target[au]!=null){av.target.css(au,am.target[au])}}}};L.jqplot.ThemeEngine.prototype._add=function(ai,ah){if(ah){ai._name=ah}if(!ai._name){ai._name=Date.parse(new Date())}if(!this.themes.hasOwnProperty(ai._name)){this.themes[ai._name]=ai}else{throw new Error("jqplot.ThemeEngine Error: Theme already in use")}};L.jqplot.ThemeEngine.prototype.remove=function(ah){if(ah=="Default"){return false}return delete this.themes[ah]};L.jqplot.ThemeEngine.prototype.newTheme=function(ah,aj){if(typeof(ah)=="object"){aj=aj||ah;ah=null}if(aj&&aj._name){ah=aj._name}else{ah=ah||Date.parse(new Date())}var ai=this.copy(this.themes.Default._name,ah);L.jqplot.extend(ai,aj);return ai};function B(aj){if(aj==null||typeof(aj)!="object"){return aj}var ah=new aj.constructor();for(var ai in aj){ah[ai]=B(aj[ai])}return ah}L.jqplot.clone=B;function V(aj,ai){if(ai==null||typeof(ai)!="object"){return}for(var ah in ai){if(ah=="highlightColors"){aj[ah]=B(ai[ah])}if(ai[ah]!=null&&typeof(ai[ah])=="object"){if(!aj.hasOwnProperty(ah)){aj[ah]={}}V(aj[ah],ai[ah])}else{aj[ah]=ai[ah]}}}L.jqplot.merge=V;L.jqplot.extend=function(){var am=arguments[0]||{},ak=1,al=arguments.length,ah=false,aj;if(typeof am==="boolean"){ah=am;am=arguments[1]||{};ak=2}if(typeof am!=="object"&&!toString.call(am)==="[object Function]"){am={}}for(;ak<al;ak++){if((aj=arguments[ak])!=null){for(var ai in aj){var an=am[ai],ao=aj[ai];if(am===ao){continue}if(ah&&ao&&typeof ao==="object"&&!ao.nodeType){am[ai]=L.jqplot.extend(ah,an||(ao.length!=null?[]:{}),ao)}else{if(ao!==u){am[ai]=ao}}}}}return am};L.jqplot.ThemeEngine.prototype.rename=function(ai,ah){if(ai=="Default"||ah=="Default"){throw new Error("jqplot.ThemeEngine Error: Cannot rename from/to Default")}if(this.themes.hasOwnProperty(ah)){throw new Error("jqplot.ThemeEngine Error: New name already in use.")}else{if(this.themes.hasOwnProperty(ai)){var aj=this.copy(ai,ah);this.remove(ai);return aj}}throw new Error("jqplot.ThemeEngine Error: Old name or new name invalid")};L.jqplot.ThemeEngine.prototype.copy=function(ah,aj,al){if(aj=="Default"){throw new Error("jqplot.ThemeEngine Error: Cannot copy over Default theme")}if(!this.themes.hasOwnProperty(ah)){var ai="jqplot.ThemeEngine Error: Source name invalid";throw new Error(ai)}if(this.themes.hasOwnProperty(aj)){var ai="jqplot.ThemeEngine Error: Target name invalid";throw new Error(ai)}else{var ak=B(this.themes[ah]);ak._name=aj;L.jqplot.extend(true,ak,al);this._add(ak);return ak}};L.jqplot.Theme=function(ah,ai){if(typeof(ah)=="object"){ai=ai||ah;ah=null}ah=ah||Date.parse(new Date());this._name=ah;this.target={backgroundColor:null};this.legend={textColor:null,fontFamily:null,fontSize:null,border:null,background:null};this.title={textColor:null,fontFamily:null,fontSize:null,textAlign:null};this.seriesStyles={};this.series=[];this.grid={drawGridlines:null,gridLineColor:null,gridLineWidth:null,backgroundColor:null,borderColor:null,borderWidth:null,shadow:null};this.axesStyles={label:{},ticks:{}};this.axes={};if(typeof(ai)=="string"){this._name=ai}else{if(typeof(ai)=="object"){L.jqplot.extend(true,this,ai)}}};var P=function(){this.borderColor=null;this.borderWidth=null;this.ticks=new o();this.label=new t()};var o=function(){this.show=null;this.showGridline=null;this.showLabel=null;this.showMark=null;this.size=null;this.textColor=null;this.whiteSpace=null;this.fontSize=null;this.fontFamily=null};var t=function(){this.textColor=null;this.whiteSpace=null;this.fontSize=null;this.fontFamily=null;this.fontWeight=null};var p=function(){this.color=null;this.lineWidth=null;this.linePattern=null;this.shadow=null;this.fillColor=null;this.showMarker=null;this.markerOptions=new I()};var I=function(){this.show=null;this.style=null;this.lineWidth=null;this.size=null;this.color=null;this.shadow=null};var T=function(){this.color=null;this.seriesColors=null;this.lineWidth=null;this.shadow=null;this.barPadding=null;this.barMargin=null;this.barWidth=null;this.highlightColors=null};var f=function(){this.seriesColors=null;this.padding=null;this.sliceMargin=null;this.fill=null;this.shadow=null;this.startAngle=null;this.lineWidth=null;this.highlightColors=null};var G=function(){this.seriesColors=null;this.padding=null;this.sliceMargin=null;this.fill=null;this.shadow=null;this.startAngle=null;this.lineWidth=null;this.innerDiameter=null;this.thickness=null;this.ringMargin=null;this.highlightColors=null};var Z=function(){this.color=null;this.lineWidth=null;this.shadow=null;this.padding=null;this.sectionMargin=null;this.seriesColors=null;this.highlightColors=null};var D=function(){this.padding=null;this.backgroundColor=null;this.ringColor=null;this.tickColor=null;this.ringWidth=null;this.intervalColors=null;this.intervalInnerRadius=null;this.intervalOuterRadius=null;this.hubRadius=null;this.needleThickness=null;this.needlePad=null};L.fn.jqplotChildText=function(){return L(this).contents().filter(function(){return this.nodeType==3}).text()};L.fn.jqplotGetComputedFontStyle=function(){var ak=window.getComputedStyle?window.getComputedStyle(this[0],""):this[0].currentStyle;var ai=ak["font-style"]?["font-style","font-weight","font-size","font-family"]:["fontStyle","fontWeight","fontSize","fontFamily"];var al=[];for(var aj=0;aj<ai.length;++aj){var ah=String(ak[ai[aj]]);if(ah&&ah!="normal"){al.push(ah)}}return al.join(" ")};L.fn.jqplotToImageCanvas=function(aj){aj=aj||{};var av=(aj.x_offset==null)?0:aj.x_offset;var ax=(aj.y_offset==null)?0:aj.y_offset;var al=(aj.backgroundColor==null)?"rgb(255,255,255)":aj.backgroundColor;if(L(this).width()==0||L(this).height()==0){return null}if(L.jqplot.use_excanvas){return null}var an=document.createElement("canvas");var aA=L(this).outerHeight(true);var at=L(this).outerWidth(true);var am=L(this).offset();var ao=am.left;var aq=am.top;var au=0,ar=0;var ay=["jqplot-table-legend","jqplot-xaxis-tick","jqplot-x2axis-tick","jqplot-yaxis-tick","jqplot-y2axis-tick","jqplot-y3axis-tick","jqplot-y4axis-tick","jqplot-y5axis-tick","jqplot-y6axis-tick","jqplot-y7axis-tick","jqplot-y8axis-tick","jqplot-y9axis-tick","jqplot-xaxis-label","jqplot-x2axis-label","jqplot-yaxis-label","jqplot-y2axis-label","jqplot-y3axis-label","jqplot-y4axis-label","jqplot-y5axis-label","jqplot-y6axis-label","jqplot-y7axis-label","jqplot-y8axis-label","jqplot-y9axis-label"];var ap,ah,ai,aB;for(var az=0;az<ay.length;az++){L(this).find("."+ay[az]).each(function(){ap=L(this).offset().top-aq;ah=L(this).offset().left-ao;aB=ah+L(this).outerWidth(true)+au;ai=ap+L(this).outerHeight(true)+ar;if(ah<-au){at=at-au-ah;au=-ah}if(ap<-ar){aA=aA-ar-ap;ar=-ap}if(aB>at){at=aB}if(ai>aA){aA=ai}})}an.width=at+Number(av);an.height=aA+Number(ax);var ak=an.getContext("2d");ak.save();ak.fillStyle=al;ak.fillRect(0,0,an.width,an.height);ak.restore();ak.translate(au,ar);ak.textAlign="left";ak.textBaseline="top";function aC(aE){var aF=parseInt(L(aE).css("line-height"),10);if(isNaN(aF)){aF=parseInt(L(aE).css("font-size"),10)*1.2}return aF}function aD(aF,aE,aS,aG,aO,aH){var aQ=aC(aF);var aK=L(aF).innerWidth();var aL=L(aF).innerHeight();var aN=aS.split(/\s+/);var aR=aN.length;var aP="";var aM=[];var aU=aO;var aT=aG;for(var aJ=0;aJ<aR;aJ++){aP+=aN[aJ];if(aE.measureText(aP).width>aK){aM.push(aJ);aP="";aJ--}}if(aM.length===0){if(L(aF).css("textAlign")==="center"){aT=aG+(aH-aE.measureText(aP).width)/2-au}aE.fillText(aS,aT,aO)}else{aP=aN.slice(0,aM[0]).join(" ");if(L(aF).css("textAlign")==="center"){aT=aG+(aH-aE.measureText(aP).width)/2-au}aE.fillText(aP,aT,aU);aU+=aQ;for(var aJ=1,aI=aM.length;aJ<aI;aJ++){aP=aN.slice(aM[aJ-1],aM[aJ]).join(" ");if(L(aF).css("textAlign")==="center"){aT=aG+(aH-aE.measureText(aP).width)/2-au}aE.fillText(aP,aT,aU);aU+=aQ}aP=aN.slice(aM[aJ-1],aN.length).join(" ");if(L(aF).css("textAlign")==="center"){aT=aG+(aH-aE.measureText(aP).width)/2-au}aE.fillText(aP,aT,aU)}}function aw(aG,aJ,aE){var aN=aG.tagName.toLowerCase();var aF=L(aG).position();var aK=window.getComputedStyle?window.getComputedStyle(aG,""):aG.currentStyle;var aI=aJ+aF.left+parseInt(aK.marginLeft,10)+parseInt(aK.borderLeftWidth,10)+parseInt(aK.paddingLeft,10);var aL=aE+aF.top+parseInt(aK.marginTop,10)+parseInt(aK.borderTopWidth,10)+parseInt(aK.paddingTop,10);var aM=an.width;if((aN=="div"||aN=="span")&&!L(aG).hasClass("jqplot-highlighter-tooltip")){L(aG).children().each(function(){aw(this,aI,aL)});var aO=L(aG).jqplotChildText();if(aO){ak.font=L(aG).jqplotGetComputedFontStyle();ak.fillStyle=L(aG).css("color");aD(aG,ak,aO,aI,aL,aM)}}else{if(aN==="table"&&L(aG).hasClass("jqplot-table-legend")){ak.strokeStyle=L(aG).css("border-top-color");ak.fillStyle=L(aG).css("background-color");ak.fillRect(aI,aL,L(aG).innerWidth(),L(aG).innerHeight());if(parseInt(L(aG).css("border-top-width"),10)>0){ak.strokeRect(aI,aL,L(aG).innerWidth(),L(aG).innerHeight())}L(aG).find("div.jqplot-table-legend-swatch-outline").each(function(){var aU=L(this);ak.strokeStyle=aU.css("border-top-color");var aQ=aI+aU.position().left;var aR=aL+aU.position().top;ak.strokeRect(aQ,aR,aU.innerWidth(),aU.innerHeight());aQ+=parseInt(aU.css("padding-left"),10);aR+=parseInt(aU.css("padding-top"),10);var aT=aU.innerHeight()-2*parseInt(aU.css("padding-top"),10);var aP=aU.innerWidth()-2*parseInt(aU.css("padding-left"),10);var aS=aU.children("div.jqplot-table-legend-swatch");ak.fillStyle=aS.css("background-color");ak.fillRect(aQ,aR,aP,aT)});L(aG).find("td.jqplot-table-legend-label").each(function(){var aR=L(this);var aP=aI+aR.position().left;var aQ=aL+aR.position().top+parseInt(aR.css("padding-top"),10);ak.font=aR.jqplotGetComputedFontStyle();ak.fillStyle=aR.css("color");aD(aR,ak,aR.text(),aP,aQ,aM)});var aH=null}else{if(aN=="canvas"){ak.drawImage(aG,aI,aL)}}}}L(this).children().each(function(){aw(this,av,ax)});return an};L.fn.jqplotToImageStr=function(ai){var ah=L(this).jqplotToImageCanvas(ai);if(ah){return ah.toDataURL("image/png")}else{return null}};L.fn.jqplotToImageElem=function(ah){var ai=document.createElement("img");var aj=L(this).jqplotToImageStr(ah);ai.src=aj;return ai};L.fn.jqplotToImageElemStr=function(ah){var ai="<img src="+L(this).jqplotToImageStr(ah)+" />";return ai};L.fn.jqplotSaveImage=function(){var ah=L(this).jqplotToImageStr({});if(ah){window.location.href=ah.replace("image/png","image/octet-stream")}};L.fn.jqplotViewImage=function(){var ai=L(this).jqplotToImageElemStr({});var aj=L(this).jqplotToImageStr({});if(ai){var ah=window.open("");ah.document.open("image/png");ah.document.write(ai);ah.document.close();ah=null}};var ag=function(){this.syntax=ag.config.syntax;this._type="jsDate";this.proxy=new Date();this.options={};this.locale=ag.regional.getLocale();this.formatString="";this.defaultCentury=ag.config.defaultCentury;switch(arguments.length){case 0:break;case 1:if(l(arguments[0])=="[object Object]"&&arguments[0]._type!="jsDate"){var aj=this.options=arguments[0];this.syntax=aj.syntax||this.syntax;this.defaultCentury=aj.defaultCentury||this.defaultCentury;this.proxy=ag.createDate(aj.date)}else{this.proxy=ag.createDate(arguments[0])}break;default:var ah=[];for(var ai=0;ai<arguments.length;ai++){ah.push(arguments[ai])}this.proxy=new Date();this.proxy.setFullYear.apply(this.proxy,ah.slice(0,3));if(ah.slice(3).length){this.proxy.setHours.apply(this.proxy,ah.slice(3))}break}};ag.config={defaultLocale:"en",syntax:"perl",defaultCentury:1900};ag.prototype.add=function(aj,ai){var ah=E[ai]||E.day;if(typeof ah=="number"){this.proxy.setTime(this.proxy.getTime()+(ah*aj))}else{ah.add(this,aj)}return this};ag.prototype.clone=function(){return new ag(this.proxy.getTime())};ag.prototype.getUtcOffset=function(){return this.proxy.getTimezoneOffset()*60000};ag.prototype.diff=function(ai,al,ah){ai=new ag(ai);if(ai===null){return null}var aj=E[al]||E.day;if(typeof aj=="number"){var ak=(this.proxy.getTime()-ai.proxy.getTime())/aj}else{var ak=aj.diff(this.proxy,ai.proxy)}return(ah?ak:Math[ak>0?"floor":"ceil"](ak))};ag.prototype.getAbbrDayName=function(){return ag.regional[this.locale]["dayNamesShort"][this.proxy.getDay()]};ag.prototype.getAbbrMonthName=function(){return ag.regional[this.locale]["monthNamesShort"][this.proxy.getMonth()]};ag.prototype.getAMPM=function(){return this.proxy.getHours()>=12?"PM":"AM"};ag.prototype.getAmPm=function(){return this.proxy.getHours()>=12?"pm":"am"};ag.prototype.getCentury=function(){return parseInt(this.proxy.getFullYear()/100,10)};ag.prototype.getDate=function(){return this.proxy.getDate()};ag.prototype.getDay=function(){return this.proxy.getDay()};ag.prototype.getDayOfWeek=function(){var ah=this.proxy.getDay();return ah===0?7:ah};ag.prototype.getDayOfYear=function(){var ai=this.proxy;var ah=ai-new Date(""+ai.getFullYear()+"/1/1 GMT");ah+=ai.getTimezoneOffset()*60000;


ai=null;return parseInt(ah/60000/60/24,10)+1};ag.prototype.getDayName=function(){return ag.regional[this.locale]["dayNames"][this.proxy.getDay()]};ag.prototype.getFullWeekOfYear=function(){var ak=this.proxy;var ah=this.getDayOfYear();var aj=6-ak.getDay();var ai=parseInt((ah+aj)/7,10);return ai};ag.prototype.getFullYear=function(){return this.proxy.getFullYear()};ag.prototype.getGmtOffset=function(){var ah=this.proxy.getTimezoneOffset()/60;var ai=ah<0?"+":"-";ah=Math.abs(ah);return ai+N(Math.floor(ah),2)+":"+N((ah%1)*60,2)};ag.prototype.getHours=function(){return this.proxy.getHours()};ag.prototype.getHours12=function(){var ah=this.proxy.getHours();return ah>12?ah-12:(ah==0?12:ah)};ag.prototype.getIsoWeek=function(){var ak=this.proxy;var aj=this.getWeekOfYear();var ah=(new Date(""+ak.getFullYear()+"/1/1")).getDay();var ai=aj+(ah>4||ah<=1?0:1);if(ai==53&&(new Date(""+ak.getFullYear()+"/12/31")).getDay()<4){ai=1}else{if(ai===0){ak=new ag(new Date(""+(ak.getFullYear()-1)+"/12/31"));ai=ak.getIsoWeek()}}ak=null;return ai};ag.prototype.getMilliseconds=function(){return this.proxy.getMilliseconds()};ag.prototype.getMinutes=function(){return this.proxy.getMinutes()};ag.prototype.getMonth=function(){return this.proxy.getMonth()};ag.prototype.getMonthName=function(){return ag.regional[this.locale]["monthNames"][this.proxy.getMonth()]};ag.prototype.getMonthNumber=function(){return this.proxy.getMonth()+1};ag.prototype.getSeconds=function(){return this.proxy.getSeconds()};ag.prototype.getShortYear=function(){return this.proxy.getYear()%100};ag.prototype.getTime=function(){return this.proxy.getTime()};ag.prototype.getTimezoneAbbr=function(){return this.proxy.toString().replace(/^.*\(([^)]+)\)$/,"$1")};ag.prototype.getTimezoneName=function(){var ah=/(?:\((.+)\)$| ([A-Z]{3}) )/.exec(this.toString());return ah[1]||ah[2]||"GMT"+this.getGmtOffset()};ag.prototype.getTimezoneOffset=function(){return this.proxy.getTimezoneOffset()};ag.prototype.getWeekOfYear=function(){var ah=this.getDayOfYear();var aj=7-this.getDayOfWeek();var ai=parseInt((ah+aj)/7,10);return ai};ag.prototype.getUnix=function(){return Math.round(this.proxy.getTime()/1000,0)};ag.prototype.getYear=function(){return this.proxy.getYear()};ag.prototype.next=function(ah){ah=ah||"day";return this.clone().add(1,ah)};ag.prototype.set=function(){switch(arguments.length){case 0:this.proxy=new Date();break;case 1:if(l(arguments[0])=="[object Object]"&&arguments[0]._type!="jsDate"){var aj=this.options=arguments[0];this.syntax=aj.syntax||this.syntax;this.defaultCentury=aj.defaultCentury||this.defaultCentury;this.proxy=ag.createDate(aj.date)}else{this.proxy=ag.createDate(arguments[0])}break;default:var ah=[];for(var ai=0;ai<arguments.length;ai++){ah.push(arguments[ai])}this.proxy=new Date();this.proxy.setFullYear.apply(this.proxy,ah.slice(0,3));if(ah.slice(3).length){this.proxy.setHours.apply(this.proxy,ah.slice(3))}break}return this};ag.prototype.setDate=function(ah){this.proxy.setDate(ah);return this};ag.prototype.setFullYear=function(){this.proxy.setFullYear.apply(this.proxy,arguments);return this};ag.prototype.setHours=function(){this.proxy.setHours.apply(this.proxy,arguments);return this};ag.prototype.setMilliseconds=function(ah){this.proxy.setMilliseconds(ah);return this};ag.prototype.setMinutes=function(){this.proxy.setMinutes.apply(this.proxy,arguments);return this};ag.prototype.setMonth=function(){this.proxy.setMonth.apply(this.proxy,arguments);return this};ag.prototype.setSeconds=function(){this.proxy.setSeconds.apply(this.proxy,arguments);return this};ag.prototype.setTime=function(ah){this.proxy.setTime(ah);return this};ag.prototype.setYear=function(){this.proxy.setYear.apply(this.proxy,arguments);return this};ag.prototype.strftime=function(ah){ah=ah||this.formatString||ag.regional[this.locale]["formatString"];return ag.strftime(this,ah,this.syntax)};ag.prototype.toString=function(){return this.proxy.toString()};ag.prototype.toYmdInt=function(){return(this.proxy.getFullYear()*10000)+(this.getMonthNumber()*100)+this.proxy.getDate()};ag.regional={en:{monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],formatString:"%Y-%m-%d %H:%M:%S"},fr:{monthNames:["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],monthNamesShort:["Jan","Fév","Mar","Avr","Mai","Jun","Jul","Aoû","Sep","Oct","Nov","Déc"],dayNames:["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"],dayNamesShort:["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],formatString:"%Y-%m-%d %H:%M:%S"},de:{monthNames:["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"],monthNamesShort:["Jan","Feb","Mär","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"],dayNames:["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"],dayNamesShort:["So","Mo","Di","Mi","Do","Fr","Sa"],formatString:"%Y-%m-%d %H:%M:%S"},es:{monthNames:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],monthNamesShort:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],dayNames:["Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado"],dayNamesShort:["Dom","Lun","Mar","Mi&eacute;","Juv","Vie","S&aacute;b"],formatString:"%Y-%m-%d %H:%M:%S"},ru:{monthNames:["Январь","Февраль","Март","�?прель","Май","Июнь","Июль","�?вгу�?т","Сент�?брь","Окт�?брь","�?о�?брь","Декабрь"],monthNamesShort:["Янв","Фев","Мар","�?пр","Май","Июн","Июл","�?вг","Сен","Окт","�?о�?","Дек"],dayNames:["во�?кре�?енье","понедельник","вторник","�?реда","четверг","п�?тница","�?уббота"],dayNamesShort:["в�?к","пнд","втр","�?рд","чтв","птн","�?бт"],formatString:"%Y-%m-%d %H:%M:%S"},ar:{monthNames:["كانون الثاني","شباط","آذار","نيسان","آذار","حزيران","تموز","آب","أيلول","تشرين الأول","تشرين الثاني","كانون الأول"],monthNamesShort:["1","2","3","4","5","6","7","8","9","10","11","12"],dayNames:["السبت","الأحد","الاثنين","الثلاثاء","الأربعاء","الخميس","الجمعة"],dayNamesShort:["سبت","أحد","اثنين","ثلاثاء","أربعاء","خميس","جمعة"],formatString:"%Y-%m-%d %H:%M:%S"},pt:{monthNames:["Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],dayNames:["Domingo","Segunda-feira","Ter&ccedil;a-feira","Quarta-feira","Quinta-feira","Sexta-feira","S&aacute;bado"],dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","S&aacute;b"],formatString:"%Y-%m-%d %H:%M:%S"},"pt-BR":{monthNames:["Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],dayNames:["Domingo","Segunda-feira","Ter&ccedil;a-feira","Quarta-feira","Quinta-feira","Sexta-feira","S&aacute;bado"],dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","S&aacute;b"],formatString:"%Y-%m-%d %H:%M:%S"},pl:{monthNames:["Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec","Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień"],monthNamesShort:["Sty","Lut","Mar","Kwi","Maj","Cze","Lip","Sie","Wrz","Paź","Lis","Gru"],dayNames:["Niedziela","Poniedziałek","Wtorek","Środa","Czwartek","Piątek","Sobota"],dayNamesShort:["Ni","Pn","Wt","Śr","Cz","Pt","Sb"],formatString:"%Y-%m-%d %H:%M:%S"}};ag.regional["en-US"]=ag.regional["en-GB"]=ag.regional.en;ag.regional.getLocale=function(){var ah=ag.config.defaultLocale;if(document&&document.getElementsByTagName("html")&&document.getElementsByTagName("html")[0].lang){ah=document.getElementsByTagName("html")[0].lang;if(!ag.regional.hasOwnProperty(ah)){ah=ag.config.defaultLocale}}return ah};var C=24*60*60*1000;var N=function(ah,ak){ah=String(ah);var ai=ak-ah.length;var aj=String(Math.pow(10,ai)).slice(1);return aj.concat(ah)};var E={millisecond:1,second:1000,minute:60*1000,hour:60*60*1000,day:C,week:7*C,month:{add:function(aj,ah){E.year.add(aj,Math[ah>0?"floor":"ceil"](ah/12));var ai=aj.getMonth()+(ah%12);if(ai==12){ai=0;aj.setYear(aj.getFullYear()+1)}else{if(ai==-1){ai=11;aj.setYear(aj.getFullYear()-1)}}aj.setMonth(ai)},diff:function(al,aj){var ah=al.getFullYear()-aj.getFullYear();var ai=al.getMonth()-aj.getMonth()+(ah*12);var ak=al.getDate()-aj.getDate();return ai+(ak/30)}},year:{add:function(ai,ah){ai.setYear(ai.getFullYear()+Math[ah>0?"floor":"ceil"](ah))},diff:function(ai,ah){return E.month.diff(ai,ah)/12}}};for(var Y in E){if(Y.substring(Y.length-1)!="s"){E[Y+"s"]=E[Y]}}var H=function(al,ak,ai){if(ag.formats[ai]["shortcuts"][ak]){return ag.strftime(al,ag.formats[ai]["shortcuts"][ak],ai)}else{var ah=(ag.formats[ai]["codes"][ak]||"").split(".");var aj=al["get"+ah[0]]?al["get"+ah[0]]():"";if(ah[1]){aj=N(aj,ah[1])}return aj}};ag.strftime=function(an,ak,aj,ao){var ai="perl";var am=ag.regional.getLocale();if(aj&&ag.formats.hasOwnProperty(aj)){ai=aj}else{if(aj&&ag.regional.hasOwnProperty(aj)){am=aj}}if(ao&&ag.formats.hasOwnProperty(ao)){ai=ao}else{if(ao&&ag.regional.hasOwnProperty(ao)){am=ao}}if(l(an)!="[object Object]"||an._type!="jsDate"){an=new ag(an);an.locale=am}if(!ak){ak=an.formatString||ag.regional[am]["formatString"]}var ah=ak||"%Y-%m-%d",ap="",al;while(ah.length>0){if(al=ah.match(ag.formats[ai].codes.matcher)){ap+=ah.slice(0,al.index);ap+=(al[1]||"")+H(an,al[2],ai);ah=ah.slice(al.index+al[0].length)}else{ap+=ah;ah=""}}return ap};ag.formats={ISO:"%Y-%m-%dT%H:%M:%S.%N%G",SQL:"%Y-%m-%d %H:%M:%S"};ag.formats.perl={codes:{matcher:/()%(#?(%|[a-z]))/i,Y:"FullYear",y:"ShortYear.2",m:"MonthNumber.2","#m":"MonthNumber",B:"MonthName",b:"AbbrMonthName",d:"Date.2","#d":"Date",e:"Date",A:"DayName",a:"AbbrDayName",w:"Day",H:"Hours.2","#H":"Hours",I:"Hours12.2","#I":"Hours12",p:"AMPM",M:"Minutes.2","#M":"Minutes",S:"Seconds.2","#S":"Seconds",s:"Unix",N:"Milliseconds.3","#N":"Milliseconds",O:"TimezoneOffset",Z:"TimezoneName",G:"GmtOffset"},shortcuts:{F:"%Y-%m-%d",T:"%H:%M:%S",X:"%H:%M:%S",x:"%m/%d/%y",D:"%m/%d/%y","#c":"%a %b %e %H:%M:%S %Y",v:"%e-%b-%Y",R:"%H:%M",r:"%I:%M:%S %p",t:"\t",n:"\n","%":"%"}};ag.formats.php={codes:{matcher:/()%((%|[a-z]))/i,a:"AbbrDayName",A:"DayName",d:"Date.2",e:"Date",j:"DayOfYear.3",u:"DayOfWeek",w:"Day",U:"FullWeekOfYear.2",V:"IsoWeek.2",W:"WeekOfYear.2",b:"AbbrMonthName",B:"MonthName",m:"MonthNumber.2",h:"AbbrMonthName",C:"Century.2",y:"ShortYear.2",Y:"FullYear",H:"Hours.2",I:"Hours12.2",l:"Hours12",p:"AMPM",P:"AmPm",M:"Minutes.2",S:"Seconds.2",s:"Unix",O:"TimezoneOffset",z:"GmtOffset",Z:"TimezoneAbbr"},shortcuts:{D:"%m/%d/%y",F:"%Y-%m-%d",T:"%H:%M:%S",X:"%H:%M:%S",x:"%m/%d/%y",R:"%H:%M",r:"%I:%M:%S %p",t:"\t",n:"\n","%":"%"}};ag.createDate=function(aj){if(aj==null){return new Date()}if(aj instanceof Date){return aj}if(typeof aj=="number"){return new Date(aj)}var ao=String(aj).replace(/^\s*(.+)\s*$/g,"$1");ao=ao.replace(/^([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,4})/,"$1/$2/$3");ao=ao.replace(/^(3[01]|[0-2]?\d)[-\/]([a-z]{3,})[-\/](\d{4})/i,"$1 $2 $3");var an=ao.match(/^(3[01]|[0-2]?\d)[-\/]([a-z]{3,})[-\/](\d{2})\D*/i);if(an&&an.length>3){var at=parseFloat(an[3]);var am=ag.config.defaultCentury+at;am=String(am);ao=ao.replace(/^(3[01]|[0-2]?\d)[-\/]([a-z]{3,})[-\/](\d{2})\D*/i,an[1]+" "+an[2]+" "+am)}an=ao.match(/^([0-9]{1,2})[-\/]([0-9]{1,2})[-\/]([0-9]{1,2})[^0-9]/);function ar(ax,aw){var aC=parseFloat(aw[1]);var aB=parseFloat(aw[2]);var aA=parseFloat(aw[3]);var az=ag.config.defaultCentury;var av,au,aD,ay;if(aC>31){au=aA;aD=aB;av=az+aC}else{au=aB;aD=aC;av=az+aA}ay=aD+"/"+au+"/"+av;return ax.replace(/^([0-9]{1,2})[-\/]([0-9]{1,2})[-\/]([0-9]{1,2})/,ay)}if(an&&an.length>3){ao=ar(ao,an)}var an=ao.match(/^([0-9]{1,2})[-\/]([0-9]{1,2})[-\/]([0-9]{1,2})$/);if(an&&an.length>3){ao=ar(ao,an)}var al=0;var ai=ag.matchers.length;var aq,ah,ap=ao,ak;while(al<ai){ah=Date.parse(ap);if(!isNaN(ah)){return new Date(ah)}aq=ag.matchers[al];if(typeof aq=="function"){ak=aq.call(ag,ap);if(ak instanceof Date){return ak}}else{ap=ao.replace(aq[0],aq[1])}al++}return NaN};ag.daysInMonth=function(ah,ai){if(ai==2){return new Date(ah,1,29).getDate()==29?29:28}return[u,31,u,31,30,31,30,31,31,30,31,30,31][ai]};ag.matchers=[[/(3[01]|[0-2]\d)\s*\.\s*(1[0-2]|0\d)\s*\.\s*([1-9]\d{3})/,"$2/$1/$3"],[/([1-9]\d{3})\s*-\s*(1[0-2]|0\d)\s*-\s*(3[01]|[0-2]\d)/,"$2/$3/$1"],function(ak){var ai=ak.match(/^(?:(.+)\s+)?([012]?\d)(?:\s*\:\s*(\d\d))?(?:\s*\:\s*(\d\d(\.\d*)?))?\s*(am|pm)?\s*$/i);if(ai){if(ai[1]){var aj=this.createDate(ai[1]);if(isNaN(aj)){return}}else{var aj=new Date();aj.setMilliseconds(0)}var ah=parseFloat(ai[2]);if(ai[6]){ah=ai[6].toLowerCase()=="am"?(ah==12?0:ah):(ah==12?12:ah+12)}aj.setHours(ah,parseInt(ai[3]||0,10),parseInt(ai[4]||0,10),((parseFloat(ai[5]||0))||0)*1000);return aj}else{return ak}},function(ak){var ai=ak.match(/^(?:(.+))[T|\s+]([012]\d)(?:\:(\d\d))(?:\:(\d\d))(?:\.\d+)([\+\-]\d\d\:\d\d)$/i);if(ai){if(ai[1]){var aj=this.createDate(ai[1]);if(isNaN(aj)){return}}else{var aj=new Date();aj.setMilliseconds(0)}var ah=parseFloat(ai[2]);aj.setHours(ah,parseInt(ai[3],10),parseInt(ai[4],10),parseFloat(ai[5])*1000);return aj}else{return ak}},function(al){var aj=al.match(/^([0-3]?\d)\s*[-\/.\s]{1}\s*([a-zA-Z]{3,9})\s*[-\/.\s]{1}\s*([0-3]?\d)$/);if(aj){var ak=new Date();var am=ag.config.defaultCentury;var ao=parseFloat(aj[1]);var an=parseFloat(aj[3]);var ai,ah,ap;if(ao>31){ah=an;ai=am+ao}else{ah=ao;ai=am+an}var ap=ab(aj[2],ag.regional[ag.regional.getLocale()]["monthNamesShort"]);if(ap==-1){ap=ab(aj[2],ag.regional[ag.regional.getLocale()]["monthNames"])}ak.setFullYear(ai,ap,ah);ak.setHours(0,0,0,0);return ak}else{return al}}];function ab(aj,ak){if(ak.indexOf){return ak.indexOf(aj)}for(var ah=0,ai=ak.length;ah<ai;ah++){if(ak[ah]===aj){return ah}}return -1}function l(ah){if(ah===null){return"[object Null]"}return Object.prototype.toString.call(ah)}L.jsDate=ag;L.jqplot.sprintf=function(){function an(au,ap,aq,at){var ar=(au.length>=ap)?"":Array(1+ap-au.length>>>0).join(aq);return at?au+ar:ar+au}function ak(ar){var aq=new String(ar);for(var ap=10;ap>0;ap--){if(aq==(aq=aq.replace(/^(\d+)(\d{3})/,"$1"+L.jqplot.sprintf.thousandsSeparator+"$2"))){break}}return aq}function aj(av,au,ax,ar,at,aq){var aw=ar-av.length;if(aw>0){var ap=" ";if(aq){ap="&nbsp;"}if(ax||!at){av=an(av,ar,ap,ax)}else{av=av.slice(0,au.length)+an("",aw,"0",true)+av.slice(au.length)}}return av}function ao(ay,aq,aw,ar,ap,av,ax,au){var at=ay>>>0;aw=aw&&at&&{"2":"0b","8":"0","16":"0x"}[aq]||"";ay=aw+an(at.toString(aq),av||0,"0",false);return aj(ay,aw,ar,ap,ax,au)}function ah(au,av,ar,ap,at,aq){if(ap!=null){au=au.slice(0,ap)}return aj(au,"",av,ar,at,aq)}var ai=arguments,al=0,am=ai[al++];return am.replace(L.jqplot.sprintf.regex,function(aM,ax,ay,aB,aO,aJ,av){if(aM=="%%"){return"%"}var aD=false,az="",aA=false,aL=false,aw=false,au=false;for(var aI=0;ay&&aI<ay.length;aI++){switch(ay.charAt(aI)){case" ":az=" ";break;case"+":az="+";break;case"-":aD=true;break;case"0":aA=true;break;case"#":aL=true;break;case"&":aw=true;break;case"'":au=true;break}}if(!aB){aB=0}else{if(aB=="*"){aB=+ai[al++]}else{if(aB.charAt(0)=="*"){aB=+ai[aB.slice(1,-1)]}else{aB=+aB}}}if(aB<0){aB=-aB;aD=true}if(!isFinite(aB)){throw new Error("$.jqplot.sprintf: (minimum-)width must be finite")}if(!aJ){aJ="fFeE".indexOf(av)>-1?6:(av=="d")?0:void (0)}else{if(aJ=="*"){aJ=+ai[al++]}else{if(aJ.charAt(0)=="*"){aJ=+ai[aJ.slice(1,-1)]}else{aJ=+aJ}}}var aF=ax?ai[ax.slice(0,-1)]:ai[al++];switch(av){case"s":if(aF==null){return""}return ah(String(aF),aD,aB,aJ,aA,aw);case"c":return ah(String.fromCharCode(+aF),aD,aB,aJ,aA,aw);case"b":return ao(aF,2,aL,aD,aB,aJ,aA,aw);case"o":return ao(aF,8,aL,aD,aB,aJ,aA,aw);case"x":return ao(aF,16,aL,aD,aB,aJ,aA,aw);case"X":return ao(aF,16,aL,aD,aB,aJ,aA,aw).toUpperCase();case"u":return ao(aF,10,aL,aD,aB,aJ,aA,aw);case"i":var ar=parseInt(+aF,10);if(isNaN(ar)){return""}var aH=ar<0?"-":az;var aK=au?ak(String(Math.abs(ar))):String(Math.abs(ar));aF=aH+an(aK,aJ,"0",false);return aj(aF,aH,aD,aB,aA,aw);case"d":var ar=Math.round(+aF);if(isNaN(ar)){return""}var aH=ar<0?"-":az;var aK=au?ak(String(Math.abs(ar))):String(Math.abs(ar));aF=aH+an(aK,aJ,"0",false);return aj(aF,aH,aD,aB,aA,aw);case"e":case"E":case"f":case"F":case"g":case"G":var ar=+aF;if(isNaN(ar)){return""}var aH=ar<0?"-":az;var at=["toExponential","toFixed","toPrecision"]["efg".indexOf(av.toLowerCase())];var aN=["toString","toUpperCase"]["eEfFgG".indexOf(av)%2];var aK=Math.abs(ar)[at](aJ);var aE=aK.toString().split(".");aE[0]=au?ak(aE[0]):aE[0];aK=aE.join(L.jqplot.sprintf.decimalMark);aF=aH+aK;var aC=aj(aF,aH,aD,aB,aA,aw)[aN]();return aC;case"p":case"P":var ar=+aF;if(isNaN(ar)){return""}var aH=ar<0?"-":az;var aE=String(Number(Math.abs(ar)).toExponential()).split(/e|E/);var aq=(aE[0].indexOf(".")!=-1)?aE[0].length-1:String(ar).length;var aG=(aE[1]<0)?-aE[1]-1:0;


if(Math.abs(ar)<1){if(aq+aG<=aJ){aF=aH+Math.abs(ar).toPrecision(aq)}else{if(aq<=aJ-1){aF=aH+Math.abs(ar).toExponential(aq-1)}else{aF=aH+Math.abs(ar).toExponential(aJ-1)}}}else{var ap=(aq<=aJ)?aq:aJ;aF=aH+Math.abs(ar).toPrecision(ap)}var aN=["toString","toUpperCase"]["pP".indexOf(av)%2];return aj(aF,aH,aD,aB,aA,aw)[aN]();case"n":return"";default:return aM}})};L.jqplot.sprintf.thousandsSeparator=",";L.jqplot.sprintf.decimalMark=".";L.jqplot.sprintf.regex=/%%|%(\d+\$)?([-+#0&\' ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([nAscboxXuidfegpEGP])/g;L.jqplot.getSignificantFigures=function(al){var an=String(Number(Math.abs(al)).toExponential()).split(/e|E/);var am=(an[0].indexOf(".")!=-1)?an[0].length-1:an[0].length;var ai=(an[1]<0)?-an[1]-1:0;var ah=parseInt(an[1],10);var aj=(ah+1>0)?ah+1:0;var ak=(am<=aj)?0:am-ah-1;return{significantDigits:am,digitsLeft:aj,digitsRight:ak,zeros:ai,exponent:ah}};L.jqplot.getPrecision=function(ah){return L.jqplot.getSignificantFigures(ah).digitsRight};var X=L.uiBackCompat!==false;L.jqplot.effects={effect:{}};var m="jqplot.storage.";L.extend(L.jqplot.effects,{version:"1.9pre",save:function(ai,aj){for(var ah=0;ah<aj.length;ah++){if(aj[ah]!==null){ai.data(m+aj[ah],ai[0].style[aj[ah]])}}},restore:function(ai,aj){for(var ah=0;ah<aj.length;ah++){if(aj[ah]!==null){ai.css(aj[ah],ai.data(m+aj[ah]))}}},setMode:function(ah,ai){if(ai==="toggle"){ai=ah.is(":hidden")?"show":"hide"}return ai},createWrapper:function(ai){if(ai.parent().is(".ui-effects-wrapper")){return ai.parent()}var aj={width:ai.outerWidth(true),height:ai.outerHeight(true),"float":ai.css("float")},al=L("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),ah={width:ai.width(),height:ai.height()},ak=document.activeElement;ai.wrap(al);if(ai[0]===ak||L.contains(ai[0],ak)){L(ak).focus()}al=ai.parent();if(ai.css("position")==="static"){al.css({position:"relative"});ai.css({position:"relative"})}else{L.extend(aj,{position:ai.css("position"),zIndex:ai.css("z-index")});L.each(["top","left","bottom","right"],function(am,an){aj[an]=ai.css(an);if(isNaN(parseInt(aj[an],10))){aj[an]="auto"}});ai.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})}ai.css(ah);return al.css(aj).show()},removeWrapper:function(ah){var ai=document.activeElement;if(ah.parent().is(".ui-effects-wrapper")){ah.parent().replaceWith(ah);if(ah[0]===ai||L.contains(ah[0],ai)){L(ai).focus()}}return ah}});function j(ai,ah,aj,ak){if(L.isPlainObject(ai)){return ai}ai={effect:ai};if(ah===u){ah={}}if(L.isFunction(ah)){ak=ah;aj=null;ah={}}if(L.type(ah)==="number"||L.fx.speeds[ah]){ak=aj;aj=ah;ah={}}if(L.isFunction(aj)){ak=aj;aj=null}if(ah){L.extend(ai,ah)}aj=aj||ah.duration;ai.duration=L.fx.off?0:typeof aj==="number"?aj:aj in L.fx.speeds?L.fx.speeds[aj]:L.fx.speeds._default;ai.complete=ak||ah.complete;return ai}function ae(ah){if(!ah||typeof ah==="number"||L.fx.speeds[ah]){return true}if(typeof ah==="string"&&!L.jqplot.effects.effect[ah]){if(X&&L.jqplot.effects[ah]){return false}return true}return false}L.fn.extend({jqplotEffect:function(ap,aq,ai,ao){var an=j.apply(this,arguments),ak=an.mode,al=an.queue,am=L.jqplot.effects.effect[an.effect],ah=!am&&X&&L.jqplot.effects[an.effect];if(L.fx.off||!(am||ah)){if(ak){return this[ak](an.duration,an.complete)}else{return this.each(function(){if(an.complete){an.complete.call(this)}})}}function aj(au){var av=L(this),at=an.complete,aw=an.mode;function ar(){if(L.isFunction(at)){at.call(av[0])}if(L.isFunction(au)){au()}}if(av.is(":hidden")?aw==="hide":aw==="show"){ar()}else{am.call(av[0],an,ar)}}if(am){return al===false?this.each(aj):this.queue(al||"fx",aj)}else{return ah.call(this,{options:an,duration:an.duration,callback:an.complete,mode:an.mode})}}});var a=/up|down|vertical/,v=/up|left|vertical|horizontal/;L.jqplot.effects.effect.blind=function(aj,ao){var ak=L(this),ar=["position","top","bottom","left","right","height","width"],ap=L.jqplot.effects.setMode(ak,aj.mode||"hide"),au=aj.direction||"up",am=a.test(au),al=am?"height":"width",aq=am?"top":"left",aw=v.test(au),an={},av=ap==="show",ai,ah,at;if(ak.parent().is(".ui-effects-wrapper")){L.jqplot.effects.save(ak.parent(),ar)}else{L.jqplot.effects.save(ak,ar)}ak.show();at=parseInt(ak.css("top"),10);ai=L.jqplot.effects.createWrapper(ak).css({overflow:"hidden"});ah=am?ai[al]()+at:ai[al]();an[al]=av?String(ah):"0";if(!aw){ak.css(am?"bottom":"right",0).css(am?"top":"left","").css({position:"absolute"});an[aq]=av?"0":String(ah)}if(av){ai.css(al,0);if(!aw){ai.css(aq,ah)}}ai.animate(an,{duration:aj.duration,easing:aj.easing,queue:false,complete:function(){if(ap==="hide"){ak.hide()}L.jqplot.effects.restore(ak,ar);L.jqplot.effects.removeWrapper(ak);ao()}})}})(jQuery);
/*! jQuery UI - v1.9.2 - 2012-11-25
* http://jqueryui.com
* Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.mouse.js, jquery.ui.position.js, jquery.ui.draggable.js, jquery.ui.droppable.js, jquery.ui.resizable.js, jquery.ui.selectable.js, jquery.ui.sortable.js, jquery.ui.tooltip.js
* Copyright (c) 2012 jQuery Foundation and other contributors Licensed MIT */

(function( $, undefined ) {

var uuid = 0,
	runiqueId = /^ui-id-\d+$/;

// prevent duplicate loading
// this is only a problem because we proxy existing functions
// and we don't want to double proxy them
$.ui = $.ui || {};
if ( $.ui.version ) {
	return;
}

$.extend( $.ui, {
	version: "1.9.2",

	keyCode: {
		BACKSPACE: 8,
		COMMA: 188,
		DELETE: 46,
		DOWN: 40,
		END: 35,
		ENTER: 13,
		ESCAPE: 27,
		HOME: 36,
		LEFT: 37,
		NUMPAD_ADD: 107,
		NUMPAD_DECIMAL: 110,
		NUMPAD_DIVIDE: 111,
		NUMPAD_ENTER: 108,
		NUMPAD_MULTIPLY: 106,
		NUMPAD_SUBTRACT: 109,
		PAGE_DOWN: 34,
		PAGE_UP: 33,
		PERIOD: 190,
		RIGHT: 39,
		SPACE: 32,
		TAB: 9,
		UP: 38
	}
});

// plugins
$.fn.extend({
	_focus: $.fn.focus,
	focus: function( delay, fn ) {
		return typeof delay === "number" ?
			this.each(function() {
				var elem = this;
				setTimeout(function() {
					$( elem ).focus();
					if ( fn ) {
						fn.call( elem );
					}
				}, delay );
			}) :
			this._focus.apply( this, arguments );
	},

	scrollParent: function() {
		var scrollParent;
		if (($.ui.ie && (/(static|relative)/).test(this.css('position'))) || (/absolute/).test(this.css('position'))) {
			scrollParent = this.parents().filter(function() {
				return (/(relative|absolute|fixed)/).test($.css(this,'position')) && (/(auto|scroll)/).test($.css(this,'overflow')+$.css(this,'overflow-y')+$.css(this,'overflow-x'));
			}).eq(0);
		} else {
			scrollParent = this.parents().filter(function() {
				return (/(auto|scroll)/).test($.css(this,'overflow')+$.css(this,'overflow-y')+$.css(this,'overflow-x'));
			}).eq(0);
		}

		return (/fixed/).test(this.css('position')) || !scrollParent.length ? $(document) : scrollParent;
	},

	zIndex: function( zIndex ) {
		if ( zIndex !== undefined ) {
			return this.css( "zIndex", zIndex );
		}

		if ( this.length ) {
			var elem = $( this[ 0 ] ), position, value;
			while ( elem.length && elem[ 0 ] !== document ) {
				// Ignore z-index if position is set to a value where z-index is ignored by the browser
				// This makes behavior of this function consistent across browsers
				// WebKit always returns auto if the element is positioned
				position = elem.css( "position" );
				if ( position === "absolute" || position === "relative" || position === "fixed" ) {
					// IE returns 0 when zIndex is not specified
					// other browsers return a string
					// we ignore the case of nested elements with an explicit value of 0
					// <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
					value = parseInt( elem.css( "zIndex" ), 10 );
					if ( !isNaN( value ) && value !== 0 ) {
						return value;
					}
				}
				elem = elem.parent();
			}
		}

		return 0;
	},

	uniqueId: function() {
		return this.each(function() {
			if ( !this.id ) {
				this.id = "ui-id-" + (++uuid);
			}
		});
	},

	removeUniqueId: function() {
		return this.each(function() {
			if ( runiqueId.test( this.id ) ) {
				$( this ).removeAttr( "id" );
			}
		});
	}
});

// selectors
function focusable( element, isTabIndexNotNaN ) {
	var map, mapName, img,
		nodeName = element.nodeName.toLowerCase();
	if ( "area" === nodeName ) {
		map = element.parentNode;
		mapName = map.name;
		if ( !element.href || !mapName || map.nodeName.toLowerCase() !== "map" ) {
			return false;
		}
		img = $( "img[usemap=#" + mapName + "]" )[0];
		return !!img && visible( img );
	}
	return ( /input|select|textarea|button|object/.test( nodeName ) ?
		!element.disabled :
		"a" === nodeName ?
			element.href || isTabIndexNotNaN :
			isTabIndexNotNaN) &&
		// the element and all of its ancestors must be visible
		visible( element );
}

function visible( element ) {
	return $.expr.filters.visible( element ) &&
		!$( element ).parents().andSelf().filter(function() {
			return $.css( this, "visibility" ) === "hidden";
		}).length;
}

$.extend( $.expr[ ":" ], {
	data: $.expr.createPseudo ?
		$.expr.createPseudo(function( dataName ) {
			return function( elem ) {
				return !!$.data( elem, dataName );
			};
		}) :
		// support: jQuery <1.8
		function( elem, i, match ) {
			return !!$.data( elem, match[ 3 ] );
		},

	focusable: function( element ) {
		return focusable( element, !isNaN( $.attr( element, "tabindex" ) ) );
	},

	tabbable: function( element ) {
		var tabIndex = $.attr( element, "tabindex" ),
			isTabIndexNaN = isNaN( tabIndex );
		return ( isTabIndexNaN || tabIndex >= 0 ) && focusable( element, !isTabIndexNaN );
	}
});

// support
$(function() {
	var body = document.body,
		div = body.appendChild( div = document.createElement( "div" ) );

	// access offsetHeight before setting the style to prevent a layout bug
	// in IE 9 which causes the element to continue to take up space even
	// after it is removed from the DOM (#8026)
	div.offsetHeight;

	$.extend( div.style, {
		minHeight: "100px",
		height: "auto",
		padding: 0,
		borderWidth: 0
	});

	$.support.minHeight = div.offsetHeight === 100;
	$.support.selectstart = "onselectstart" in div;

	// set display to none to avoid a layout bug in IE
	// http://dev.jquery.com/ticket/4014
	body.removeChild( div ).style.display = "none";
});

// support: jQuery <1.8
if ( !$( "<a>" ).outerWidth( 1 ).jquery ) {
	$.each( [ "Width", "Height" ], function( i, name ) {
		var side = name === "Width" ? [ "Left", "Right" ] : [ "Top", "Bottom" ],
			type = name.toLowerCase(),
			orig = {
				innerWidth: $.fn.innerWidth,
				innerHeight: $.fn.innerHeight,
				outerWidth: $.fn.outerWidth,
				outerHeight: $.fn.outerHeight
			};

		function reduce( elem, size, border, margin ) {
			$.each( side, function() {
				size -= parseFloat( $.css( elem, "padding" + this ) ) || 0;
				if ( border ) {
					size -= parseFloat( $.css( elem, "border" + this + "Width" ) ) || 0;
				}
				if ( margin ) {
					size -= parseFloat( $.css( elem, "margin" + this ) ) || 0;
				}
			});
			return size;
		}

		$.fn[ "inner" + name ] = function( size ) {
			if ( size === undefined ) {
				return orig[ "inner" + name ].call( this );
			}

			return this.each(function() {
				$( this ).css( type, reduce( this, size ) + "px" );
			});
		};

		$.fn[ "outer" + name] = function( size, margin ) {
			if ( typeof size !== "number" ) {
				return orig[ "outer" + name ].call( this, size );
			}

			return this.each(function() {
				$( this).css( type, reduce( this, size, true, margin ) + "px" );
			});
		};
	});
}

// support: jQuery 1.6.1, 1.6.2 (http://bugs.jquery.com/ticket/9413)
if ( $( "<a>" ).data( "a-b", "a" ).removeData( "a-b" ).data( "a-b" ) ) {
	$.fn.removeData = (function( removeData ) {
		return function( key ) {
			if ( arguments.length ) {
				return removeData.call( this, $.camelCase( key ) );
			} else {
				return removeData.call( this );
			}
		};
	})( $.fn.removeData );
}





// deprecated

(function() {
	var uaMatch = /msie ([\w.]+)/.exec( navigator.userAgent.toLowerCase() ) || [];
	$.ui.ie = uaMatch.length ? true : false;
	$.ui.ie6 = parseFloat( uaMatch[ 1 ], 10 ) === 6;
})();

$.fn.extend({
	disableSelection: function() {
		return this.bind( ( $.support.selectstart ? "selectstart" : "mousedown" ) +
			".ui-disableSelection", function( event ) {
				event.preventDefault();
			});
	},

	enableSelection: function() {
		return this.unbind( ".ui-disableSelection" );
	}
});

$.extend( $.ui, {
	// $.ui.plugin is deprecated.  Use the proxy pattern instead.
	plugin: {
		add: function( module, option, set ) {
			var i,
				proto = $.ui[ module ].prototype;
			for ( i in set ) {
				proto.plugins[ i ] = proto.plugins[ i ] || [];
				proto.plugins[ i ].push( [ option, set[ i ] ] );
			}
		},
		call: function( instance, name, args ) {
			var i,
				set = instance.plugins[ name ];
			if ( !set || !instance.element[ 0 ].parentNode || instance.element[ 0 ].parentNode.nodeType === 11 ) {
				return;
			}

			for ( i = 0; i < set.length; i++ ) {
				if ( instance.options[ set[ i ][ 0 ] ] ) {
					set[ i ][ 1 ].apply( instance.element, args );
				}
			}
		}
	},

	contains: $.contains,

	// only used by resizable
	hasScroll: function( el, a ) {

		//If overflow is hidden, the element might have extra content, but the user wants to hide it
		if ( $( el ).css( "overflow" ) === "hidden") {
			return false;
		}

		var scroll = ( a && a === "left" ) ? "scrollLeft" : "scrollTop",
			has = false;

		if ( el[ scroll ] > 0 ) {
			return true;
		}

		// TODO: determine which cases actually cause this to happen
		// if the element doesn't have the scroll set, see if it's possible to
		// set the scroll
		el[ scroll ] = 1;
		has = ( el[ scroll ] > 0 );
		el[ scroll ] = 0;
		return has;
	},

	// these are odd functions, fix the API or move into individual plugins
	isOverAxis: function( x, reference, size ) {
		//Determines when x coordinate is over "b" element axis
		return ( x > reference ) && ( x < ( reference + size ) );
	},
	isOver: function( y, x, top, left, height, width ) {
		//Determines when x, y coordinates is over "b" element
		return $.ui.isOverAxis( y, top, height ) && $.ui.isOverAxis( x, left, width );
	}
});

})( jQuery );
(function( $, undefined ) {

var uuid = 0,
	slice = Array.prototype.slice,
	_cleanData = $.cleanData;
$.cleanData = function( elems ) {
	for ( var i = 0, elem; (elem = elems[i]) != null; i++ ) {
		try {
			$( elem ).triggerHandler( "remove" );
		// http://bugs.jquery.com/ticket/8235
		} catch( e ) {}
	}
	_cleanData( elems );
};

$.widget = function( name, base, prototype ) {
	var fullName, existingConstructor, constructor, basePrototype,
		namespace = name.split( "." )[ 0 ];

	name = name.split( "." )[ 1 ];
	fullName = namespace + "-" + name;

	if ( !prototype ) {
		prototype = base;
		base = $.Widget;
	}

	// create selector for plugin
	$.expr[ ":" ][ fullName.toLowerCase() ] = function( elem ) {
		return !!$.data( elem, fullName );
	};

	$[ namespace ] = $[ namespace ] || {};
	existingConstructor = $[ namespace ][ name ];
	constructor = $[ namespace ][ name ] = function( options, element ) {
		// allow instantiation without "new" keyword
		if ( !this._createWidget ) {
			return new constructor( options, element );
		}

		// allow instantiation without initializing for simple inheritance
		// must use "new" keyword (the code above always passes args)
		if ( arguments.length ) {
			this._createWidget( options, element );
		}
	};
	// extend with the existing constructor to carry over any static properties
	$.extend( constructor, existingConstructor, {
		version: prototype.version,
		// copy the object used to create the prototype in case we need to
		// redefine the widget later
		_proto: $.extend( {}, prototype ),
		// track widgets that inherit from this widget in case this widget is
		// redefined after a widget inherits from it
		_childConstructors: []
	});

	basePrototype = new base();
	// we need to make the options hash a property directly on the new instance
	// otherwise we'll modify the options hash on the prototype that we're
	// inheriting from
	basePrototype.options = $.widget.extend( {}, basePrototype.options );
	$.each( prototype, function( prop, value ) {
		if ( $.isFunction( value ) ) {
			prototype[ prop ] = (function() {
				var _super = function() {
						return base.prototype[ prop ].apply( this, arguments );
					},
					_superApply = function( args ) {
						return base.prototype[ prop ].apply( this, args );
					};
				return function() {
					var __super = this._super,
						__superApply = this._superApply,
						returnValue;

					this._super = _super;
					this._superApply = _superApply;

					returnValue = value.apply( this, arguments );

					this._super = __super;
					this._superApply = __superApply;

					return returnValue;
				};
			})();
		}
	});
	constructor.prototype = $.widget.extend( basePrototype, {
		// TODO: remove support for widgetEventPrefix
		// always use the name + a colon as the prefix, e.g., draggable:start
		// don't prefix for widgets that aren't DOM-based
		widgetEventPrefix: existingConstructor ? basePrototype.widgetEventPrefix : name
	}, prototype, {
		constructor: constructor,
		namespace: namespace,
		widgetName: name,
		// TODO remove widgetBaseClass, see #8155
		widgetBaseClass: fullName,
		widgetFullName: fullName
	});

	// If this widget is being redefined then we need to find all widgets that
	// are inheriting from it and redefine all of them so that they inherit from
	// the new version of this widget. We're essentially trying to replace one
	// level in the prototype chain.
	if ( existingConstructor ) {
		$.each( existingConstructor._childConstructors, function( i, child ) {
			var childPrototype = child.prototype;

			// redefine the child widget using the same prototype that was
			// originally used, but inherit from the new version of the base
			$.widget( childPrototype.namespace + "." + childPrototype.widgetName, constructor, child._proto );
		});
		// remove the list of existing child constructors from the old constructor
		// so the old child constructors can be garbage collected
		delete existingConstructor._childConstructors;
	} else {
		base._childConstructors.push( constructor );
	}

	$.widget.bridge( name, constructor );
};

$.widget.extend = function( target ) {
	var input = slice.call( arguments, 1 ),
		inputIndex = 0,
		inputLength = input.length,
		key,
		value;
	for ( ; inputIndex < inputLength; inputIndex++ ) {
		for ( key in input[ inputIndex ] ) {
			value = input[ inputIndex ][ key ];
			if ( input[ inputIndex ].hasOwnProperty( key ) && value !== undefined ) {
				// Clone objects
				if ( $.isPlainObject( value ) ) {
					target[ key ] = $.isPlainObject( target[ key ] ) ?
						$.widget.extend( {}, target[ key ], value ) :
						// Don't extend strings, arrays, etc. with objects
						$.widget.extend( {}, value );
				// Copy everything else by reference
				} else {
					target[ key ] = value;
				}
			}
		}
	}
	return target;
};

$.widget.bridge = function( name, object ) {
	var fullName = object.prototype.widgetFullName || name;
	$.fn[ name ] = function( options ) {
		var isMethodCall = typeof options === "string",
			args = slice.call( arguments, 1 ),
			returnValue = this;

		// allow multiple hashes to be passed on init
		options = !isMethodCall && args.length ?
			$.widget.extend.apply( null, [ options ].concat(args) ) :
			options;

		if ( isMethodCall ) {
			this.each(function() {
				var methodValue,
					instance = $.data( this, fullName );
				if ( !instance ) {
					return $.error( "cannot call methods on " + name + " prior to initialization; " +
						"attempted to call method '" + options + "'" );
				}
				if ( !$.isFunction( instance[options] ) || options.charAt( 0 ) === "_" ) {
					return $.error( "no such method '" + options + "' for " + name + " widget instance" );
				}
				methodValue = instance[ options ].apply( instance, args );
				if ( methodValue !== instance && methodValue !== undefined ) {
					returnValue = methodValue && methodValue.jquery ?
						returnValue.pushStack( methodValue.get() ) :
						methodValue;
					return false;
				}
			});
		} else {
			this.each(function() {
				var instance = $.data( this, fullName );
				if ( instance ) {
					instance.option( options || {} )._init();
				} else {
					$.data( this, fullName, new object( options, this ) );
				}
			});
		}

		return returnValue;
	};
};

$.Widget = function( /* options, element */ ) {};
$.Widget._childConstructors = [];

$.Widget.prototype = {
	widgetName: "widget",
	widgetEventPrefix: "",
	defaultElement: "<div>",
	options: {
		disabled: false,

		// callbacks
		create: null
	},
	_createWidget: function( options, element ) {
		element = $( element || this.defaultElement || this )[ 0 ];
		this.element = $( element );
		this.uuid = uuid++;
		this.eventNamespace = "." + this.widgetName + this.uuid;
		this.options = $.widget.extend( {},
			this.options,
			this._getCreateOptions(),
			options );

		this.bindings = $();
		this.hoverable = $();
		this.focusable = $();

		if ( element !== this ) {
			// 1.9 BC for #7810
			// TODO remove dual storage
			$.data( element, this.widgetName, this );
			$.data( element, this.widgetFullName, this );
			this._on( true, this.element, {
				remove: function( event ) {
					if ( event.target === element ) {
						this.destroy();
					}
				}
			});
			this.document = $( element.style ?
				// element within the document
				element.ownerDocument :
				// element is window or document
				element.document || element );
			this.window = $( this.document[0].defaultView || this.document[0].parentWindow );
		}

		this._create();
		this._trigger( "create", null, this._getCreateEventData() );
		this._init();
	},
	_getCreateOptions: $.noop,
	_getCreateEventData: $.noop,
	_create: $.noop,
	_init: $.noop,

	destroy: function() {
		this._destroy();
		// we can probably remove the unbind calls in 2.0
		// all event bindings should go through this._on()
		this.element
			.unbind( this.eventNamespace )
			// 1.9 BC for #7810
			// TODO remove dual storage
			.removeData( this.widgetName )
			.removeData( this.widgetFullName )
			// support: jquery <1.6.3
			// http://bugs.jquery.com/ticket/9413
			.removeData( $.camelCase( this.widgetFullName ) );
		this.widget()
			.unbind( this.eventNamespace )
			.removeAttr( "aria-disabled" )
			.removeClass(
				this.widgetFullName + "-disabled " +
				"ui-state-disabled" );

		// clean up events and states
		this.bindings.unbind( this.eventNamespace );
		this.hoverable.removeClass( "ui-state-hover" );
		this.focusable.removeClass( "ui-state-focus" );
	},
	_destroy: $.noop,

	widget: function() {
		return this.element;
	},

	option: function( key, value ) {
		var options = key,
			parts,
			curOption,
			i;

		if ( arguments.length === 0 ) {
			// don't return a reference to the internal hash
			return $.widget.extend( {}, this.options );
		}

		if ( typeof key === "string" ) {
			// handle nested keys, e.g., "foo.bar" => { foo: { bar: ___ } }
			options = {};
			parts = key.split( "." );
			key = parts.shift();
			if ( parts.length ) {
				curOption = options[ key ] = $.widget.extend( {}, this.options[ key ] );
				for ( i = 0; i < parts.length - 1; i++ ) {
					curOption[ parts[ i ] ] = curOption[ parts[ i ] ] || {};
					curOption = curOption[ parts[ i ] ];
				}
				key = parts.pop();
				if ( value === undefined ) {
					return curOption[ key ] === undefined ? null : curOption[ key ];
				}
				curOption[ key ] = value;
			} else {
				if ( value === undefined ) {
					return this.options[ key ] === undefined ? null : this.options[ key ];
				}
				options[ key ] = value;
			}
		}

		this._setOptions( options );

		return this;
	},
	_setOptions: function( options ) {
		var key;

		for ( key in options ) {
			this._setOption( key, options[ key ] );
		}

		return this;
	},
	_setOption: function( key, value ) {
		this.options[ key ] = value;

		if ( key === "disabled" ) {
			this.widget()
				.toggleClass( this.widgetFullName + "-disabled ui-state-disabled", !!value )
				.attr( "aria-disabled", value );
			this.hoverable.removeClass( "ui-state-hover" );
			this.focusable.removeClass( "ui-state-focus" );
		}

		return this;
	},

	enable: function() {
		return this._setOption( "disabled", false );
	},
	disable: function() {
		return this._setOption( "disabled", true );
	},

	_on: function( suppressDisabledCheck, element, handlers ) {
		var delegateElement,
			instance = this;

		// no suppressDisabledCheck flag, shuffle arguments
		if ( typeof suppressDisabledCheck !== "boolean" ) {
			handlers = element;
			element = suppressDisabledCheck;
			suppressDisabledCheck = false;
		}

		// no element argument, shuffle and use this.element
		if ( !handlers ) {
			handlers = element;
			element = this.element;
			delegateElement = this.widget();
		} else {
			// accept selectors, DOM elements
			element = delegateElement = $( element );
			this.bindings = this.bindings.add( element );
		}

		$.each( handlers, function( event, handler ) {
			function handlerProxy() {
				// allow widgets to customize the disabled handling
				// - disabled as an array instead of boolean
				// - disabled class as method for disabling individual parts
				if ( !suppressDisabledCheck &&
						( instance.options.disabled === true ||
							$( this ).hasClass( "ui-state-disabled" ) ) ) {
					return;
				}
				return ( typeof handler === "string" ? instance[ handler ] : handler )
					.apply( instance, arguments );
			}

			// copy the guid so direct unbinding works
			if ( typeof handler !== "string" ) {
				handlerProxy.guid = handler.guid =
					handler.guid || handlerProxy.guid || $.guid++;
			}

			var match = event.match( /^(\w+)\s*(.*)$/ ),
				eventName = match[1] + instance.eventNamespace,
				selector = match[2];
			if ( selector ) {
				delegateElement.delegate( selector, eventName, handlerProxy );
			} else {
				element.bind( eventName, handlerProxy );
			}
		});
	},

	_off: function( element, eventName ) {
		eventName = (eventName || "").split( " " ).join( this.eventNamespace + " " ) + this.eventNamespace;
		element.unbind( eventName ).undelegate( eventName );
	},

	_delay: function( handler, delay ) {
		function handlerProxy() {
			return ( typeof handler === "string" ? instance[ handler ] : handler )
				.apply( instance, arguments );
		}
		var instance = this;
		return setTimeout( handlerProxy, delay || 0 );
	},

	_hoverable: function( element ) {
		this.hoverable = this.hoverable.add( element );
		this._on( element, {
			mouseenter: function( event ) {
				$( event.currentTarget ).addClass( "ui-state-hover" );
			},
			mouseleave: function( event ) {
				$( event.currentTarget ).removeClass( "ui-state-hover" );
			}
		});
	},

	_focusable: function( element ) {
		this.focusable = this.focusable.add( element );
		this._on( element, {
			focusin: function( event ) {
				$( event.currentTarget ).addClass( "ui-state-focus" );
			},
			focusout: function( event ) {
				$( event.currentTarget ).removeClass( "ui-state-focus" );
			}
		});
	},

	_trigger: function( type, event, data ) {
		var prop, orig,
			callback = this.options[ type ];

		data = data || {};
		event = $.Event( event );
		event.type = ( type === this.widgetEventPrefix ?
			type :
			this.widgetEventPrefix + type ).toLowerCase();
		// the original event may come from any element
		// so we need to reset the target on the new event
		event.target = this.element[ 0 ];

		// copy original event properties over to the new event
		orig = event.originalEvent;
		if ( orig ) {
			for ( prop in orig ) {
				if ( !( prop in event ) ) {
					event[ prop ] = orig[ prop ];
				}
			}
		}

		this.element.trigger( event, data );
		return !( $.isFunction( callback ) &&
			callback.apply( this.element[0], [ event ].concat( data ) ) === false ||
			event.isDefaultPrevented() );
	}
};

$.each( { show: "fadeIn", hide: "fadeOut" }, function( method, defaultEffect ) {
	$.Widget.prototype[ "_" + method ] = function( element, options, callback ) {
		if ( typeof options === "string" ) {
			options = { effect: options };
		}
		var hasOptions,
			effectName = !options ?
				method :
				options === true || typeof options === "number" ?
					defaultEffect :
					options.effect || defaultEffect;
		options = options || {};
		if ( typeof options === "number" ) {
			options = { duration: options };
		}
		hasOptions = !$.isEmptyObject( options );
		options.complete = callback;
		if ( options.delay ) {
			element.delay( options.delay );
		}
		if ( hasOptions && $.effects && ( $.effects.effect[ effectName ] || $.uiBackCompat !== false && $.effects[ effectName ] ) ) {
			element[ method ]( options );
		} else if ( effectName !== method && element[ effectName ] ) {
			element[ effectName ]( options.duration, options.easing, callback );
		} else {
			element.queue(function( next ) {
				$( this )[ method ]();
				if ( callback ) {
					callback.call( element[ 0 ] );
				}
				next();
			});
		}
	};
});

// DEPRECATED
if ( $.uiBackCompat !== false ) {
	$.Widget.prototype._getCreateOptions = function() {
		return $.metadata && $.metadata.get( this.element[0] )[ this.widgetName ];
	};
}

})( jQuery );
(function( $, undefined ) {

var mouseHandled = false;
$( document ).mouseup( function( e ) {
	mouseHandled = false;
});

$.widget("ui.mouse", {
	version: "1.9.2",
	options: {
		cancel: 'input,textarea,button,select,option',
		distance: 1,
		delay: 0
	},
	_mouseInit: function() {
		var that = this;

		this.element
			.bind('mousedown.'+this.widgetName, function(event) {
				return that._mouseDown(event);
			})
			.bind('click.'+this.widgetName, function(event) {
				if (true === $.data(event.target, that.widgetName + '.preventClickEvent')) {
					$.removeData(event.target, that.widgetName + '.preventClickEvent');
					event.stopImmediatePropagation();
					return false;
				}
			});

		this.started = false;
	},

	// TODO: make sure destroying one instance of mouse doesn't mess with
	// other instances of mouse
	_mouseDestroy: function() {
		this.element.unbind('.'+this.widgetName);
		if ( this._mouseMoveDelegate ) {
			$(document)
				.unbind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
				.unbind('mouseup.'+this.widgetName, this._mouseUpDelegate);
		}
	},

	_mouseDown: function(event) {
		// don't let more than one widget handle mouseStart
		if( mouseHandled ) { return; }

		// we may have missed mouseup (out of window)
		(this._mouseStarted && this._mouseUp(event));

		this._mouseDownEvent = event;

		var that = this,
			btnIsLeft = (event.which === 1),
			// event.target.nodeName works around a bug in IE 8 with
			// disabled inputs (#7620)
			elIsCancel = (typeof this.options.cancel === "string" && event.target.nodeName ? $(event.target).closest(this.options.cancel).length : false);
		if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
			return true;
		}

		this.mouseDelayMet = !this.options.delay;
		if (!this.mouseDelayMet) {
			this._mouseDelayTimer = setTimeout(function() {
				that.mouseDelayMet = true;
			}, this.options.delay);
		}

		if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
			this._mouseStarted = (this._mouseStart(event) !== false);
			if (!this._mouseStarted) {
				event.preventDefault();
				return true;
			}
		}

		// Click event may never have fired (Gecko & Opera)
		if (true === $.data(event.target, this.widgetName + '.preventClickEvent')) {
			$.removeData(event.target, this.widgetName + '.preventClickEvent');
		}

		// these delegates are required to keep context
		this._mouseMoveDelegate = function(event) {
			return that._mouseMove(event);
		};
		this._mouseUpDelegate = function(event) {
			return that._mouseUp(event);
		};
		$(document)
			.bind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
			.bind('mouseup.'+this.widgetName, this._mouseUpDelegate);

		event.preventDefault();

		mouseHandled = true;
		return true;
	},

	_mouseMove: function(event) {
		// IE mouseup check - mouseup happened when mouse was out of window
		if ($.ui.ie && !(document.documentMode >= 9) && !event.button) {
			return this._mouseUp(event);
		}

		if (this._mouseStarted) {
			this._mouseDrag(event);
			return event.preventDefault();
		}

		if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
			this._mouseStarted =
				(this._mouseStart(this._mouseDownEvent, event) !== false);
			(this._mouseStarted ? this._mouseDrag(event) : this._mouseUp(event));
		}

		return !this._mouseStarted;
	},

	_mouseUp: function(event) {
		$(document)
			.unbind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
			.unbind('mouseup.'+this.widgetName, this._mouseUpDelegate);

		if (this._mouseStarted) {
			this._mouseStarted = false;

			if (event.target === this._mouseDownEvent.target) {
				$.data(event.target, this.widgetName + '.preventClickEvent', true);
			}

			this._mouseStop(event);
		}

		return false;
	},

	_mouseDistanceMet: function(event) {
		return (Math.max(
				Math.abs(this._mouseDownEvent.pageX - event.pageX),
				Math.abs(this._mouseDownEvent.pageY - event.pageY)
			) >= this.options.distance
		);
	},

	_mouseDelayMet: function(event) {
		return this.mouseDelayMet;
	},

	// These are placeholder methods, to be overriden by extending plugin
	_mouseStart: function(event) {},
	_mouseDrag: function(event) {},
	_mouseStop: function(event) {},
	_mouseCapture: function(event) { return true; }
});

})(jQuery);
(function( $, undefined ) {

$.ui = $.ui || {};

var cachedScrollbarWidth,
	max = Math.max,
	abs = Math.abs,
	round = Math.round,
	rhorizontal = /left|center|right/,
	rvertical = /top|center|bottom/,
	roffset = /[\+\-]\d+%?/,
	rposition = /^\w+/,
	rpercent = /%$/,
	_position = $.fn.position;

function getOffsets( offsets, width, height ) {
	return [
		parseInt( offsets[ 0 ], 10 ) * ( rpercent.test( offsets[ 0 ] ) ? width / 100 : 1 ),
		parseInt( offsets[ 1 ], 10 ) * ( rpercent.test( offsets[ 1 ] ) ? height / 100 : 1 )
	];
}
function parseCss( element, property ) {
	return parseInt( $.css( element, property ), 10 ) || 0;
}

$.position = {
	scrollbarWidth: function() {
		if ( cachedScrollbarWidth !== undefined ) {
			return cachedScrollbarWidth;
		}
		var w1, w2,
			div = $( "<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>" ),
			innerDiv = div.children()[0];

		$( "body" ).append( div );
		w1 = innerDiv.offsetWidth;
		div.css( "overflow", "scroll" );

		w2 = innerDiv.offsetWidth;

		if ( w1 === w2 ) {
			w2 = div[0].clientWidth;
		}

		div.remove();

		return (cachedScrollbarWidth = w1 - w2);
	},
	getScrollInfo: function( within ) {
		var overflowX = within.isWindow ? "" : within.element.css( "overflow-x" ),
			overflowY = within.isWindow ? "" : within.element.css( "overflow-y" ),
			hasOverflowX = overflowX === "scroll" ||
				( overflowX === "auto" && within.width < within.element[0].scrollWidth ),
			hasOverflowY = overflowY === "scroll" ||
				( overflowY === "auto" && within.height < within.element[0].scrollHeight );
		return {
			width: hasOverflowX ? $.position.scrollbarWidth() : 0,
			height: hasOverflowY ? $.position.scrollbarWidth() : 0
		};
	},
	getWithinInfo: function( element ) {
		var withinElement = $( element || window ),
			isWindow = $.isWindow( withinElement[0] );
		return {
			element: withinElement,
			isWindow: isWindow,
			offset: withinElement.offset() || { left: 0, top: 0 },
			scrollLeft: withinElement.scrollLeft(),
			scrollTop: withinElement.scrollTop(),
			width: isWindow ? withinElement.width() : withinElement.outerWidth(),
			height: isWindow ? withinElement.height() : withinElement.outerHeight()
		};
	}
};

$.fn.position = function( options ) {
	if ( !options || !options.of ) {
		return _position.apply( this, arguments );
	}

	// make a copy, we don't want to modify arguments
	options = $.extend( {}, options );

	var atOffset, targetWidth, targetHeight, targetOffset, basePosition,
		target = $( options.of ),
		within = $.position.getWithinInfo( options.within ),
		scrollInfo = $.position.getScrollInfo( within ),
		targetElem = target[0],
		collision = ( options.collision || "flip" ).split( " " ),
		offsets = {};

	if ( targetElem.nodeType === 9 ) {
		targetWidth = target.width();
		targetHeight = target.height();
		targetOffset = { top: 0, left: 0 };
	} else if ( $.isWindow( targetElem ) ) {
		targetWidth = target.width();
		targetHeight = target.height();
		targetOffset = { top: target.scrollTop(), left: target.scrollLeft() };
	} else if ( targetElem.preventDefault ) {
		// force left top to allow flipping
		options.at = "left top";
		targetWidth = targetHeight = 0;
		targetOffset = { top: targetElem.pageY, left: targetElem.pageX };
	} else {
		targetWidth = target.outerWidth();
		targetHeight = target.outerHeight();
		targetOffset = target.offset();
	}
	// clone to reuse original targetOffset later
	basePosition = $.extend( {}, targetOffset );

	// force my and at to have valid horizontal and vertical positions
	// if a value is missing or invalid, it will be converted to center
	$.each( [ "my", "at" ], function() {
		var pos = ( options[ this ] || "" ).split( " " ),
			horizontalOffset,
			verticalOffset;

		if ( pos.length === 1) {
			pos = rhorizontal.test( pos[ 0 ] ) ?
				pos.concat( [ "center" ] ) :
				rvertical.test( pos[ 0 ] ) ?
					[ "center" ].concat( pos ) :
					[ "center", "center" ];
		}
		pos[ 0 ] = rhorizontal.test( pos[ 0 ] ) ? pos[ 0 ] : "center";
		pos[ 1 ] = rvertical.test( pos[ 1 ] ) ? pos[ 1 ] : "center";

		// calculate offsets
		horizontalOffset = roffset.exec( pos[ 0 ] );
		verticalOffset = roffset.exec( pos[ 1 ] );
		offsets[ this ] = [
			horizontalOffset ? horizontalOffset[ 0 ] : 0,
			verticalOffset ? verticalOffset[ 0 ] : 0
		];

		// reduce to just the positions without the offsets
		options[ this ] = [
			rposition.exec( pos[ 0 ] )[ 0 ],
			rposition.exec( pos[ 1 ] )[ 0 ]
		];
	});

	// normalize collision option
	if ( collision.length === 1 ) {
		collision[ 1 ] = collision[ 0 ];
	}

	if ( options.at[ 0 ] === "right" ) {
		basePosition.left += targetWidth;
	} else if ( options.at[ 0 ] === "center" ) {
		basePosition.left += targetWidth / 2;
	}

	if ( options.at[ 1 ] === "bottom" ) {
		basePosition.top += targetHeight;
	} else if ( options.at[ 1 ] === "center" ) {
		basePosition.top += targetHeight / 2;
	}

	atOffset = getOffsets( offsets.at, targetWidth, targetHeight );
	basePosition.left += atOffset[ 0 ];
	basePosition.top += atOffset[ 1 ];

	return this.each(function() {
		var collisionPosition, using,
			elem = $( this ),
			elemWidth = elem.outerWidth(),
			elemHeight = elem.outerHeight(),
			marginLeft = parseCss( this, "marginLeft" ),
			marginTop = parseCss( this, "marginTop" ),
			collisionWidth = elemWidth + marginLeft + parseCss( this, "marginRight" ) + scrollInfo.width,
			collisionHeight = elemHeight + marginTop + parseCss( this, "marginBottom" ) + scrollInfo.height,
			position = $.extend( {}, basePosition ),
			myOffset = getOffsets( offsets.my, elem.outerWidth(), elem.outerHeight() );

		if ( options.my[ 0 ] === "right" ) {
			position.left -= elemWidth;
		} else if ( options.my[ 0 ] === "center" ) {
			position.left -= elemWidth / 2;
		}

		if ( options.my[ 1 ] === "bottom" ) {
			position.top -= elemHeight;
		} else if ( options.my[ 1 ] === "center" ) {
			position.top -= elemHeight / 2;
		}

		position.left += myOffset[ 0 ];
		position.top += myOffset[ 1 ];

		// if the browser doesn't support fractions, then round for consistent results
		if ( !$.support.offsetFractions ) {
			position.left = round( position.left );
			position.top = round( position.top );
		}

		collisionPosition = {
			marginLeft: marginLeft,
			marginTop: marginTop
		};

		$.each( [ "left", "top" ], function( i, dir ) {
			if ( $.ui.position[ collision[ i ] ] ) {
				$.ui.position[ collision[ i ] ][ dir ]( position, {
					targetWidth: targetWidth,
					targetHeight: targetHeight,
					elemWidth: elemWidth,
					elemHeight: elemHeight,
					collisionPosition: collisionPosition,
					collisionWidth: collisionWidth,
					collisionHeight: collisionHeight,
					offset: [ atOffset[ 0 ] + myOffset[ 0 ], atOffset [ 1 ] + myOffset[ 1 ] ],
					my: options.my,
					at: options.at,
					within: within,
					elem : elem
				});
			}
		});

		if ( $.fn.bgiframe ) {
			elem.bgiframe();
		}

		if ( options.using ) {
			// adds feedback as second argument to using callback, if present
			using = function( props ) {
				var left = targetOffset.left - position.left,
					right = left + targetWidth - elemWidth,
					top = targetOffset.top - position.top,
					bottom = top + targetHeight - elemHeight,
					feedback = {
						target: {
							element: target,
							left: targetOffset.left,
							top: targetOffset.top,
							width: targetWidth,
							height: targetHeight
						},
						element: {
							element: elem,
							left: position.left,
							top: position.top,
							width: elemWidth,
							height: elemHeight
						},
						horizontal: right < 0 ? "left" : left > 0 ? "right" : "center",
						vertical: bottom < 0 ? "top" : top > 0 ? "bottom" : "middle"
					};
				if ( targetWidth < elemWidth && abs( left + right ) < targetWidth ) {
					feedback.horizontal = "center";
				}
				if ( targetHeight < elemHeight && abs( top + bottom ) < targetHeight ) {
					feedback.vertical = "middle";
				}
				if ( max( abs( left ), abs( right ) ) > max( abs( top ), abs( bottom ) ) ) {
					feedback.important = "horizontal";
				} else {
					feedback.important = "vertical";
				}
				options.using.call( this, props, feedback );
			};
		}

		elem.offset( $.extend( position, { using: using } ) );
	});
};

$.ui.position = {
	fit: {
		left: function( position, data ) {
			var within = data.within,
				withinOffset = within.isWindow ? within.scrollLeft : within.offset.left,
				outerWidth = within.width,
				collisionPosLeft = position.left - data.collisionPosition.marginLeft,
				overLeft = withinOffset - collisionPosLeft,
				overRight = collisionPosLeft + data.collisionWidth - outerWidth - withinOffset,
				newOverRight;

			// element is wider than within
			if ( data.collisionWidth > outerWidth ) {
				// element is initially over the left side of within
				if ( overLeft > 0 && overRight <= 0 ) {
					newOverRight = position.left + overLeft + data.collisionWidth - outerWidth - withinOffset;
					position.left += overLeft - newOverRight;
				// element is initially over right side of within
				} else if ( overRight > 0 && overLeft <= 0 ) {
					position.left = withinOffset;
				// element is initially over both left and right sides of within
				} else {
					if ( overLeft > overRight ) {
						position.left = withinOffset + outerWidth - data.collisionWidth;
					} else {
						position.left = withinOffset;
					}
				}
			// too far left -> align with left edge
			} else if ( overLeft > 0 ) {
				position.left += overLeft;
			// too far right -> align with right edge
			} else if ( overRight > 0 ) {
				position.left -= overRight;
			// adjust based on position and margin
			} else {
				position.left = max( position.left - collisionPosLeft, position.left );
			}
		},
		top: function( position, data ) {
			var within = data.within,
				withinOffset = within.isWindow ? within.scrollTop : within.offset.top,
				outerHeight = data.within.height,
				collisionPosTop = position.top - data.collisionPosition.marginTop,
				overTop = withinOffset - collisionPosTop,
				overBottom = collisionPosTop + data.collisionHeight - outerHeight - withinOffset,
				newOverBottom;

			// element is taller than within
			if ( data.collisionHeight > outerHeight ) {
				// element is initially over the top of within
				if ( overTop > 0 && overBottom <= 0 ) {
					newOverBottom = position.top + overTop + data.collisionHeight - outerHeight - withinOffset;
					position.top += overTop - newOverBottom;
				// element is initially over bottom of within
				} else if ( overBottom > 0 && overTop <= 0 ) {
					position.top = withinOffset;
				// element is initially over both top and bottom of within
				} else {
					if ( overTop > overBottom ) {
						position.top = withinOffset + outerHeight - data.collisionHeight;
					} else {
						position.top = withinOffset;
					}
				}
			// too far up -> align with top
			} else if ( overTop > 0 ) {
				position.top += overTop;
			// too far down -> align with bottom edge
			} else if ( overBottom > 0 ) {
				position.top -= overBottom;
			// adjust based on position and margin
			} else {
				position.top = max( position.top - collisionPosTop, position.top );
			}
		}
	},
	flip: {
		left: function( position, data ) {
			var within = data.within,
				withinOffset = within.offset.left + within.scrollLeft,
				outerWidth = within.width,
				offsetLeft = within.isWindow ? within.scrollLeft : within.offset.left,
				collisionPosLeft = position.left - data.collisionPosition.marginLeft,
				overLeft = collisionPosLeft - offsetLeft,
				overRight = collisionPosLeft + data.collisionWidth - outerWidth - offsetLeft,
				myOffset = data.my[ 0 ] === "left" ?
					-data.elemWidth :
					data.my[ 0 ] === "right" ?
						data.elemWidth :
						0,
				atOffset = data.at[ 0 ] === "left" ?
					data.targetWidth :
					data.at[ 0 ] === "right" ?
						-data.targetWidth :
						0,
				offset = -2 * data.offset[ 0 ],
				newOverRight,
				newOverLeft;

			if ( overLeft < 0 ) {
				newOverRight = position.left + myOffset + atOffset + offset + data.collisionWidth - outerWidth - withinOffset;
				if ( newOverRight < 0 || newOverRight < abs( overLeft ) ) {
					position.left += myOffset + atOffset + offset;
				}
			}
			else if ( overRight > 0 ) {
				newOverLeft = position.left - data.collisionPosition.marginLeft + myOffset + atOffset + offset - offsetLeft;
				if ( newOverLeft > 0 || abs( newOverLeft ) < overRight ) {
					position.left += myOffset + atOffset + offset;
				}
			}
		},
		top: function( position, data ) {
			var within = data.within,
				withinOffset = within.offset.top + within.scrollTop,
				outerHeight = within.height,
				offsetTop = within.isWindow ? within.scrollTop : within.offset.top,
				collisionPosTop = position.top - data.collisionPosition.marginTop,
				overTop = collisionPosTop - offsetTop,
				overBottom = collisionPosTop + data.collisionHeight - outerHeight - offsetTop,
				top = data.my[ 1 ] === "top",
				myOffset = top ?
					-data.elemHeight :
					data.my[ 1 ] === "bottom" ?
						data.elemHeight :
						0,
				atOffset = data.at[ 1 ] === "top" ?
					data.targetHeight :
					data.at[ 1 ] === "bottom" ?
						-data.targetHeight :
						0,
				offset = -2 * data.offset[ 1 ],
				newOverTop,
				newOverBottom;
			if ( overTop < 0 ) {
				newOverBottom = position.top + myOffset + atOffset + offset + data.collisionHeight - outerHeight - withinOffset;
				if ( ( position.top + myOffset + atOffset + offset) > overTop && ( newOverBottom < 0 || newOverBottom < abs( overTop ) ) ) {
					position.top += myOffset + atOffset + offset;
				}
			}
			else if ( overBottom > 0 ) {
				newOverTop = position.top -  data.collisionPosition.marginTop + myOffset + atOffset + offset - offsetTop;
				if ( ( position.top + myOffset + atOffset + offset) > overBottom && ( newOverTop > 0 || abs( newOverTop ) < overBottom ) ) {
					position.top += myOffset + atOffset + offset;
				}
			}
		}
	},
	flipfit: {
		left: function() {
			$.ui.position.flip.left.apply( this, arguments );
			$.ui.position.fit.left.apply( this, arguments );
		},
		top: function() {
			$.ui.position.flip.top.apply( this, arguments );
			$.ui.position.fit.top.apply( this, arguments );
		}
	}
};

// fraction support test
(function () {
	var testElement, testElementParent, testElementStyle, offsetLeft, i,
		body = document.getElementsByTagName( "body" )[ 0 ],
		div = document.createElement( "div" );

	//Create a "fake body" for testing based on method used in jQuery.support
	testElement = document.createElement( body ? "div" : "body" );
	testElementStyle = {
		visibility: "hidden",
		width: 0,
		height: 0,
		border: 0,
		margin: 0,
		background: "none"
	};
	if ( body ) {
		$.extend( testElementStyle, {
			position: "absolute",
			left: "-1000px",
			top: "-1000px"
		});
	}
	for ( i in testElementStyle ) {
		testElement.style[ i ] = testElementStyle[ i ];
	}
	testElement.appendChild( div );
	testElementParent = body || document.documentElement;
	testElementParent.insertBefore( testElement, testElementParent.firstChild );

	div.style.cssText = "position: absolute; left: 10.7432222px;";

	offsetLeft = $( div ).offset().left;
	$.support.offsetFractions = offsetLeft > 10 && offsetLeft < 11;

	testElement.innerHTML = "";
	testElementParent.removeChild( testElement );
})();

// DEPRECATED
if ( $.uiBackCompat !== false ) {
	// offset option
	(function( $ ) {
		var _position = $.fn.position;
		$.fn.position = function( options ) {
			if ( !options || !options.offset ) {
				return _position.call( this, options );
			}
			var offset = options.offset.split( " " ),
				at = options.at.split( " " );
			if ( offset.length === 1 ) {
				offset[ 1 ] = offset[ 0 ];
			}
			if ( /^\d/.test( offset[ 0 ] ) ) {
				offset[ 0 ] = "+" + offset[ 0 ];
			}
			if ( /^\d/.test( offset[ 1 ] ) ) {
				offset[ 1 ] = "+" + offset[ 1 ];
			}
			if ( at.length === 1 ) {
				if ( /left|center|right/.test( at[ 0 ] ) ) {
					at[ 1 ] = "center";
				} else {
					at[ 1 ] = at[ 0 ];
					at[ 0 ] = "center";
				}
			}
			return _position.call( this, $.extend( options, {
				at: at[ 0 ] + offset[ 0 ] + " " + at[ 1 ] + offset[ 1 ],
				offset: undefined
			} ) );
		};
	}( jQuery ) );
}

}( jQuery ) );
(function( $, undefined ) {

$.widget("ui.draggable", $.ui.mouse, {
	version: "1.9.2",
	widgetEventPrefix: "drag",
	options: {
		addClasses: true,
		appendTo: "parent",
		axis: false,
		connectToSortable: false,
		containment: false,
		cursor: "auto",
		cursorAt: false,
		grid: false,
		handle: false,
		helper: "original",
		iframeFix: false,
		opacity: false,
		refreshPositions: false,
		revert: false,
		revertDuration: 500,
		scope: "default",
		scroll: true,
		scrollSensitivity: 20,
		scrollSpeed: 20,
		snap: false,
		snapMode: "both",
		snapTolerance: 20,
		stack: false,
		zIndex: false
	},
	_create: function() {

		if (this.options.helper == 'original' && !(/^(?:r|a|f)/).test(this.element.css("position")))
			this.element[0].style.position = 'relative';

		(this.options.addClasses && this.element.addClass("ui-draggable"));
		(this.options.disabled && this.element.addClass("ui-draggable-disabled"));

		this._mouseInit();

	},

	_destroy: function() {
		this.element.removeClass( "ui-draggable ui-draggable-dragging ui-draggable-disabled" );
		this._mouseDestroy();
	},

	_mouseCapture: function(event) {

		var o = this.options;

		// among others, prevent a drag on a resizable-handle
		if (this.helper || o.disabled || $(event.target).is('.ui-resizable-handle'))
			return false;

		//Quit if we're not on a valid handle
		this.handle = this._getHandle(event);
		if (!this.handle)
			return false;

		$(o.iframeFix === true ? "iframe" : o.iframeFix).each(function() {
			$('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>')
			.css({
				width: this.offsetWidth+"px", height: this.offsetHeight+"px",
				position: "absolute", opacity: "0.001", zIndex: 1000
			})
			.css($(this).offset())
			.appendTo("body");
		});

		return true;

	},

	_mouseStart: function(event) {

		var o = this.options;

		//Create and append the visible helper
		this.helper = this._createHelper(event);

		this.helper.addClass("ui-draggable-dragging");

		//Cache the helper size
		this._cacheHelperProportions();

		//If ddmanager is used for droppables, set the global draggable
		if($.ui.ddmanager)
			$.ui.ddmanager.current = this;

		/*
		 * - Position generation -
		 * This block generates everything position related - it's the core of draggables.
		 */

		//Cache the margins of the original element
		this._cacheMargins();

		//Store the helper's css position
		this.cssPosition = this.helper.css("position");
		this.scrollParent = this.helper.scrollParent();

		//The element's absolute position on the page minus margins
		this.offset = this.positionAbs = this.element.offset();
		this.offset = {
			top: this.offset.top - this.margins.top,
			left: this.offset.left - this.margins.left
		};

		$.extend(this.offset, {
			click: { //Where the click happened, relative to the element
				left: event.pageX - this.offset.left,
				top: event.pageY - this.offset.top
			},
			parent: this._getParentOffset(),
			relative: this._getRelativeOffset() //This is a relative to absolute position minus the actual position calculation - only used for relative positioned helper
		});

		//Generate the original position
		this.originalPosition = this.position = this._generatePosition(event);
		this.originalPageX = event.pageX;
		this.originalPageY = event.pageY;

		//Adjust the mouse offset relative to the helper if 'cursorAt' is supplied
		(o.cursorAt && this._adjustOffsetFromHelper(o.cursorAt));

		//Set a containment if given in the options
		if(o.containment)
			this._setContainment();

		//Trigger event + callbacks
		if(this._trigger("start", event) === false) {
			this._clear();
			return false;
		}

		//Recache the helper size
		this._cacheHelperProportions();

		//Prepare the droppable offsets
		if ($.ui.ddmanager && !o.dropBehaviour)
			$.ui.ddmanager.prepareOffsets(this, event);


		this._mouseDrag(event, true); //Execute the drag once - this causes the helper not to be visible before getting its correct position

		//If the ddmanager is used for droppables, inform the manager that dragging has started (see #5003)
		if ( $.ui.ddmanager ) $.ui.ddmanager.dragStart(this, event);

		return true;
	},

	_mouseDrag: function(event, noPropagation) {

		//Compute the helpers position
		this.position = this._generatePosition(event);
		this.positionAbs = this._convertPositionTo("absolute");

		//Call plugins and callbacks and use the resulting position if something is returned
		if (!noPropagation) {
			var ui = this._uiHash();
			if(this._trigger('drag', event, ui) === false) {
				this._mouseUp({});
				return false;
			}
			this.position = ui.position;
		}

		if(!this.options.axis || this.options.axis != "y") this.helper[0].style.left = this.position.left+'px';
		if(!this.options.axis || this.options.axis != "x") this.helper[0].style.top = this.position.top+'px';
		if($.ui.ddmanager) $.ui.ddmanager.drag(this, event);

		return false;
	},

	_mouseStop: function(event) {

		//If we are using droppables, inform the manager about the drop
		var dropped = false;
		if ($.ui.ddmanager && !this.options.dropBehaviour)
			dropped = $.ui.ddmanager.drop(this, event);

		//if a drop comes from outside (a sortable)
		if(this.dropped) {
			dropped = this.dropped;
			this.dropped = false;
		}

		//if the original element is no longer in the DOM don't bother to continue (see #8269)
		var element = this.element[0], elementInDom = false;
		while ( element && (element = element.parentNode) ) {
			if (element == document ) {
				elementInDom = true;
			}
		}
		if ( !elementInDom && this.options.helper === "original" )
			return false;

		if((this.options.revert == "invalid" && !dropped) || (this.options.revert == "valid" && dropped) || this.options.revert === true || ($.isFunction(this.options.revert) && this.options.revert.call(this.element, dropped))) {
			var that = this;
			$(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function() {
				if(that._trigger("stop", event) !== false) {
					that._clear();
				}
			});
		} else {
			if(this._trigger("stop", event) !== false) {
				this._clear();
			}
		}

		return false;
	},

	_mouseUp: function(event) {
		//Remove frame helpers
		$("div.ui-draggable-iframeFix").each(function() {
			this.parentNode.removeChild(this);
		});

		//If the ddmanager is used for droppables, inform the manager that dragging has stopped (see #5003)
		if( $.ui.ddmanager ) $.ui.ddmanager.dragStop(this, event);

		return $.ui.mouse.prototype._mouseUp.call(this, event);
	},

	cancel: function() {

		if(this.helper.is(".ui-draggable-dragging")) {
			this._mouseUp({});
		} else {
			this._clear();
		}

		return this;

	},

	_getHandle: function(event) {

		var handle = !this.options.handle || !$(this.options.handle, this.element).length ? true : false;
		$(this.options.handle, this.element)
			.find("*")
			.andSelf()
			.each(function() {
				if(this == event.target) handle = true;
			});

		return handle;

	},

	_createHelper: function(event) {

		var o = this.options;
		var helper = $.isFunction(o.helper) ? $(o.helper.apply(this.element[0], [event])) : (o.helper == 'clone' ? this.element.clone().removeAttr('id') : this.element);

		if(!helper.parents('body').length)
			helper.appendTo((o.appendTo == 'parent' ? this.element[0].parentNode : o.appendTo));

		if(helper[0] != this.element[0] && !(/(fixed|absolute)/).test(helper.css("position")))
			helper.css("position", "absolute");

		return helper;

	},

	_adjustOffsetFromHelper: function(obj) {
		if (typeof obj == 'string') {
			obj = obj.split(' ');
		}
		if ($.isArray(obj)) {
			obj = {left: +obj[0], top: +obj[1] || 0};
		}
		if ('left' in obj) {
			this.offset.click.left = obj.left + this.margins.left;
		}
		if ('right' in obj) {
			this.offset.click.left = this.helperProportions.width - obj.right + this.margins.left;
		}
		if ('top' in obj) {
			this.offset.click.top = obj.top + this.margins.top;
		}
		if ('bottom' in obj) {
			this.offset.click.top = this.helperProportions.height - obj.bottom + this.margins.top;
		}
	},

	_getParentOffset: function() {

		//Get the offsetParent and cache its position
		this.offsetParent = this.helper.offsetParent();
		var po = this.offsetParent.offset();

		// This is a special case where we need to modify a offset calculated on start, since the following happened:
		// 1. The position of the helper is absolute, so it's position is calculated based on the next positioned parent
		// 2. The actual offset parent is a child of the scroll parent, and the scroll parent isn't the document, which means that
		//    the scroll is included in the initial calculation of the offset of the parent, and never recalculated upon drag
		if(this.cssPosition == 'absolute' && this.scrollParent[0] != document && $.contains(this.scrollParent[0], this.offsetParent[0])) {
			po.left += this.scrollParent.scrollLeft();
			po.top += this.scrollParent.scrollTop();
		}

		if((this.offsetParent[0] == document.body) //This needs to be actually done for all browsers, since pageX/pageY includes this information
		|| (this.offsetParent[0].tagName && this.offsetParent[0].tagName.toLowerCase() == 'html' && $.ui.ie)) //Ugly IE fix
			po = { top: 0, left: 0 };

		return {
			top: po.top + (parseInt(this.offsetParent.css("borderTopWidth"),10) || 0),
			left: po.left + (parseInt(this.offsetParent.css("borderLeftWidth"),10) || 0)
		};

	},

	_getRelativeOffset: function() {

		if(this.cssPosition == "relative") {
			var p = this.element.position();
			return {
				top: p.top - (parseInt(this.helper.css("top"),10) || 0) + this.scrollParent.scrollTop(),
				left: p.left - (parseInt(this.helper.css("left"),10) || 0) + this.scrollParent.scrollLeft()
			};
		} else {
			return { top: 0, left: 0 };
		}

	},

	_cacheMargins: function() {
		this.margins = {
			left: (parseInt(this.element.css("marginLeft"),10) || 0),
			top: (parseInt(this.element.css("marginTop"),10) || 0),
			right: (parseInt(this.element.css("marginRight"),10) || 0),
			bottom: (parseInt(this.element.css("marginBottom"),10) || 0)
		};
	},

	_cacheHelperProportions: function() {
		this.helperProportions = {
			width: this.helper.outerWidth(),
			height: this.helper.outerHeight()
		};
	},

	_setContainment: function() {

		var o = this.options;
		if(o.containment == 'parent') o.containment = this.helper[0].parentNode;
		if(o.containment == 'document' || o.containment == 'window') this.containment = [
			o.containment == 'document' ? 0 : $(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left,
			o.containment == 'document' ? 0 : $(window).scrollTop() - this.offset.relative.top - this.offset.parent.top,
			(o.containment == 'document' ? 0 : $(window).scrollLeft()) + $(o.containment == 'document' ? document : window).width() - this.helperProportions.width - this.margins.left,
			(o.containment == 'document' ? 0 : $(window).scrollTop()) + ($(o.containment == 'document' ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top
		];

		if(!(/^(document|window|parent)$/).test(o.containment) && o.containment.constructor != Array) {
			var c = $(o.containment);
			var ce = c[0]; if(!ce) return;
			var co = c.offset();
			var over = ($(ce).css("overflow") != 'hidden');

			this.containment = [
				(parseInt($(ce).css("borderLeftWidth"),10) || 0) + (parseInt($(ce).css("paddingLeft"),10) || 0),
				(parseInt($(ce).css("borderTopWidth"),10) || 0) + (parseInt($(ce).css("paddingTop"),10) || 0),
				(over ? Math.max(ce.scrollWidth,ce.offsetWidth) : ce.offsetWidth) - (parseInt($(ce).css("borderLeftWidth"),10) || 0) - (parseInt($(ce).css("paddingRight"),10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right,
				(over ? Math.max(ce.scrollHeight,ce.offsetHeight) : ce.offsetHeight) - (parseInt($(ce).css("borderTopWidth"),10) || 0) - (parseInt($(ce).css("paddingBottom"),10) || 0) - this.helperProportions.height - this.margins.top  - this.margins.bottom
			];
			this.relative_container = c;

		} else if(o.containment.constructor == Array) {
			this.containment = o.containment;
		}

	},

	_convertPositionTo: function(d, pos) {

		if(!pos) pos = this.position;
		var mod = d == "absolute" ? 1 : -1;
		var o = this.options, scroll = this.cssPosition == 'absolute' && !(this.scrollParent[0] != document && $.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		return {
			top: (
				pos.top																	// The absolute mouse position
				+ this.offset.relative.top * mod										// Only for relative positioned nodes: Relative offset from element to offset parent
				+ this.offset.parent.top * mod											// The offsetParent's offset without borders (offset + border)
				- ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ) * mod)
			),
			left: (
				pos.left																// The absolute mouse position
				+ this.offset.relative.left * mod										// Only for relative positioned nodes: Relative offset from element to offset parent
				+ this.offset.parent.left * mod											// The offsetParent's offset without borders (offset + border)
				- ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ) * mod)
			)
		};

	},

	_generatePosition: function(event) {

		var o = this.options, scroll = this.cssPosition == 'absolute' && !(this.scrollParent[0] != document && $.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);
		var pageX = event.pageX;
		var pageY = event.pageY;

		/*
		 * - Position constraining -
		 * Constrain the position to a mix of grid, containment.
		 */

		if(this.originalPosition) { //If we are not dragging yet, we won't check for options
			var containment;
			if(this.containment) {
			if (this.relative_container){
				var co = this.relative_container.offset();
				containment = [ this.containment[0] + co.left,
					this.containment[1] + co.top,
					this.containment[2] + co.left,
					this.containment[3] + co.top ];
			}
			else {
				containment = this.containment;
			}

				if(event.pageX - this.offset.click.left < containment[0]) pageX = containment[0] + this.offset.click.left;
				if(event.pageY - this.offset.click.top < containment[1]) pageY = containment[1] + this.offset.click.top;
				if(event.pageX - this.offset.click.left > containment[2]) pageX = containment[2] + this.offset.click.left;
				if(event.pageY - this.offset.click.top > containment[3]) pageY = containment[3] + this.offset.click.top;
			}

			if(o.grid) {
				//Check for grid elements set to 0 to prevent divide by 0 error causing invalid argument errors in IE (see ticket #6950)
				var top = o.grid[1] ? this.originalPageY + Math.round((pageY - this.originalPageY) / o.grid[1]) * o.grid[1] : this.originalPageY;
				pageY = containment ? (!(top - this.offset.click.top < containment[1] || top - this.offset.click.top > containment[3]) ? top : (!(top - this.offset.click.top < containment[1]) ? top - o.grid[1] : top + o.grid[1])) : top;

				var left = o.grid[0] ? this.originalPageX + Math.round((pageX - this.originalPageX) / o.grid[0]) * o.grid[0] : this.originalPageX;
				pageX = containment ? (!(left - this.offset.click.left < containment[0] || left - this.offset.click.left > containment[2]) ? left : (!(left - this.offset.click.left < containment[0]) ? left - o.grid[0] : left + o.grid[0])) : left;
			}

		}

		return {
			top: (
				pageY																// The absolute mouse position
				- this.offset.click.top													// Click offset (relative to the element)
				- this.offset.relative.top												// Only for relative positioned nodes: Relative offset from element to offset parent
				- this.offset.parent.top												// The offsetParent's offset without borders (offset + border)
				+ ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ))
			),
			left: (
				pageX																// The absolute mouse position
				- this.offset.click.left												// Click offset (relative to the element)
				- this.offset.relative.left												// Only for relative positioned nodes: Relative offset from element to offset parent
				- this.offset.parent.left												// The offsetParent's offset without borders (offset + border)
				+ ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ))
			)
		};

	},

	_clear: function() {
		this.helper.removeClass("ui-draggable-dragging");
		if(this.helper[0] != this.element[0] && !this.cancelHelperRemoval) this.helper.remove();
		//if($.ui.ddmanager) $.ui.ddmanager.current = null;
		this.helper = null;
		this.cancelHelperRemoval = false;
	},

	// From now on bulk stuff - mainly helpers

	_trigger: function(type, event, ui) {
		ui = ui || this._uiHash();
		$.ui.plugin.call(this, type, [event, ui]);
		if(type == "drag") this.positionAbs = this._convertPositionTo("absolute"); //The absolute position has to be recalculated after plugins
		return $.Widget.prototype._trigger.call(this, type, event, ui);
	},

	plugins: {},

	_uiHash: function(event) {
		return {
			helper: this.helper,
			position: this.position,
			originalPosition: this.originalPosition,
			offset: this.positionAbs
		};
	}

});

$.ui.plugin.add("draggable", "connectToSortable", {
	start: function(event, ui) {

		var inst = $(this).data("draggable"), o = inst.options,
			uiSortable = $.extend({}, ui, { item: inst.element });
		inst.sortables = [];
		$(o.connectToSortable).each(function() {
			var sortable = $.data(this, 'sortable');
			if (sortable && !sortable.options.disabled) {
				inst.sortables.push({
					instance: sortable,
					shouldRevert: sortable.options.revert
				});
				sortable.refreshPositions();	// Call the sortable's refreshPositions at drag start to refresh the containerCache since the sortable container cache is used in drag and needs to be up to date (this will ensure it's initialised as well as being kept in step with any changes that might have happened on the page).
				sortable._trigger("activate", event, uiSortable);
			}
		});

	},
	stop: function(event, ui) {

		//If we are still over the sortable, we fake the stop event of the sortable, but also remove helper
		var inst = $(this).data("draggable"),
			uiSortable = $.extend({}, ui, { item: inst.element });

		$.each(inst.sortables, function() {
			if(this.instance.isOver) {

				this.instance.isOver = 0;

				inst.cancelHelperRemoval = true; //Don't remove the helper in the draggable instance
				this.instance.cancelHelperRemoval = false; //Remove it in the sortable instance (so sortable plugins like revert still work)

				//The sortable revert is supported, and we have to set a temporary dropped variable on the draggable to support revert: 'valid/invalid'
				if(this.shouldRevert) this.instance.options.revert = true;

				//Trigger the stop of the sortable
				this.instance._mouseStop(event);

				this.instance.options.helper = this.instance.options._helper;

				//If the helper has been the original item, restore properties in the sortable
				if(inst.options.helper == 'original')
					this.instance.currentItem.css({ top: 'auto', left: 'auto' });

			} else {
				this.instance.cancelHelperRemoval = false; //Remove the helper in the sortable instance
				this.instance._trigger("deactivate", event, uiSortable);
			}

		});

	},
	drag: function(event, ui) {

		var inst = $(this).data("draggable"), that = this;

		var checkPos = function(o) {
			var dyClick = this.offset.click.top, dxClick = this.offset.click.left;
			var helperTop = this.positionAbs.top, helperLeft = this.positionAbs.left;
			var itemHeight = o.height, itemWidth = o.width;
			var itemTop = o.top, itemLeft = o.left;

			return $.ui.isOver(helperTop + dyClick, helperLeft + dxClick, itemTop, itemLeft, itemHeight, itemWidth);
		};

		$.each(inst.sortables, function(i) {

			var innermostIntersecting = false;
			var thisSortable = this;
			//Copy over some variables to allow calling the sortable's native _intersectsWith
			this.instance.positionAbs = inst.positionAbs;
			this.instance.helperProportions = inst.helperProportions;
			this.instance.offset.click = inst.offset.click;

			if(this.instance._intersectsWith(this.instance.containerCache)) {
				innermostIntersecting = true;
				$.each(inst.sortables, function () {
					this.instance.positionAbs = inst.positionAbs;
					this.instance.helperProportions = inst.helperProportions;
					this.instance.offset.click = inst.offset.click;
					if  (this != thisSortable
						&& this.instance._intersectsWith(this.instance.containerCache)
						&& $.ui.contains(thisSortable.instance.element[0], this.instance.element[0]))
						innermostIntersecting = false;
						return innermostIntersecting;
				});
			}


			if(innermostIntersecting) {
				//If it intersects, we use a little isOver variable and set it once, so our move-in stuff gets fired only once
				if(!this.instance.isOver) {

					this.instance.isOver = 1;
					//Now we fake the start of dragging for the sortable instance,
					//by cloning the list group item, appending it to the sortable and using it as inst.currentItem
					//We can then fire the start event of the sortable with our passed browser event, and our own helper (so it doesn't create a new one)
					this.instance.currentItem = $(that).clone().removeAttr('id').appendTo(this.instance.element).data("sortable-item", true);
					this.instance.options._helper = this.instance.options.helper; //Store helper option to later restore it
					this.instance.options.helper = function() { return ui.helper[0]; };

					event.target = this.instance.currentItem[0];
					this.instance._mouseCapture(event, true);
					this.instance._mouseStart(event, true, true);

					//Because the browser event is way off the new appended portlet, we modify a couple of variables to reflect the changes
					this.instance.offset.click.top = inst.offset.click.top;
					this.instance.offset.click.left = inst.offset.click.left;
					this.instance.offset.parent.left -= inst.offset.parent.left - this.instance.offset.parent.left;
					this.instance.offset.parent.top -= inst.offset.parent.top - this.instance.offset.parent.top;

					inst._trigger("toSortable", event);
					inst.dropped = this.instance.element; //draggable revert needs that
					//hack so receive/update callbacks work (mostly)
					inst.currentItem = inst.element;
					this.instance.fromOutside = inst;

				}

				//Provided we did all the previous steps, we can fire the drag event of the sortable on every draggable drag, when it intersects with the sortable
				if(this.instance.currentItem) this.instance._mouseDrag(event);

			} else {

				//If it doesn't intersect with the sortable, and it intersected before,
				//we fake the drag stop of the sortable, but make sure it doesn't remove the helper by using cancelHelperRemoval
				if(this.instance.isOver) {

					this.instance.isOver = 0;
					this.instance.cancelHelperRemoval = true;

					//Prevent reverting on this forced stop
					this.instance.options.revert = false;

					// The out event needs to be triggered independently
					this.instance._trigger('out', event, this.instance._uiHash(this.instance));

					this.instance._mouseStop(event, true);
					this.instance.options.helper = this.instance.options._helper;

					//Now we remove our currentItem, the list group clone again, and the placeholder, and animate the helper back to it's original size
					this.instance.currentItem.remove();
					if(this.instance.placeholder) this.instance.placeholder.remove();

					inst._trigger("fromSortable", event);
					inst.dropped = false; //draggable revert needs that
				}

			};

		});

	}
});

$.ui.plugin.add("draggable", "cursor", {
	start: function(event, ui) {
		var t = $('body'), o = $(this).data('draggable').options;
		if (t.css("cursor")) o._cursor = t.css("cursor");
		t.css("cursor", o.cursor);
	},
	stop: function(event, ui) {
		var o = $(this).data('draggable').options;
		if (o._cursor) $('body').css("cursor", o._cursor);
	}
});

$.ui.plugin.add("draggable", "opacity", {
	start: function(event, ui) {
		var t = $(ui.helper), o = $(this).data('draggable').options;
		if(t.css("opacity")) o._opacity = t.css("opacity");
		t.css('opacity', o.opacity);
	},
	stop: function(event, ui) {
		var o = $(this).data('draggable').options;
		if(o._opacity) $(ui.helper).css('opacity', o._opacity);
	}
});

$.ui.plugin.add("draggable", "scroll", {
	start: function(event, ui) {
		var i = $(this).data("draggable");
		if(i.scrollParent[0] != document && i.scrollParent[0].tagName != 'HTML') i.overflowOffset = i.scrollParent.offset();
	},
	drag: function(event, ui) {

		var i = $(this).data("draggable"), o = i.options, scrolled = false;

		if(i.scrollParent[0] != document && i.scrollParent[0].tagName != 'HTML') {

			if(!o.axis || o.axis != 'x') {
				if((i.overflowOffset.top + i.scrollParent[0].offsetHeight) - event.pageY < o.scrollSensitivity)
					i.scrollParent[0].scrollTop = scrolled = i.scrollParent[0].scrollTop + o.scrollSpeed;
				else if(event.pageY - i.overflowOffset.top < o.scrollSensitivity)
					i.scrollParent[0].scrollTop = scrolled = i.scrollParent[0].scrollTop - o.scrollSpeed;
			}

			if(!o.axis || o.axis != 'y') {
				if((i.overflowOffset.left + i.scrollParent[0].offsetWidth) - event.pageX < o.scrollSensitivity)
					i.scrollParent[0].scrollLeft = scrolled = i.scrollParent[0].scrollLeft + o.scrollSpeed;
				else if(event.pageX - i.overflowOffset.left < o.scrollSensitivity)
					i.scrollParent[0].scrollLeft = scrolled = i.scrollParent[0].scrollLeft - o.scrollSpeed;
			}

		} else {

			if(!o.axis || o.axis != 'x') {
				if(event.pageY - $(document).scrollTop() < o.scrollSensitivity)
					scrolled = $(document).scrollTop($(document).scrollTop() - o.scrollSpeed);
				else if($(window).height() - (event.pageY - $(document).scrollTop()) < o.scrollSensitivity)
					scrolled = $(document).scrollTop($(document).scrollTop() + o.scrollSpeed);
			}

			if(!o.axis || o.axis != 'y') {
				if(event.pageX - $(document).scrollLeft() < o.scrollSensitivity)
					scrolled = $(document).scrollLeft($(document).scrollLeft() - o.scrollSpeed);
				else if($(window).width() - (event.pageX - $(document).scrollLeft()) < o.scrollSensitivity)
					scrolled = $(document).scrollLeft($(document).scrollLeft() + o.scrollSpeed);
			}

		}

		if(scrolled !== false && $.ui.ddmanager && !o.dropBehaviour)
			$.ui.ddmanager.prepareOffsets(i, event);

	}
});

$.ui.plugin.add("draggable", "snap", {
	start: function(event, ui) {

		var i = $(this).data("draggable"), o = i.options;
		i.snapElements = [];

		$(o.snap.constructor != String ? ( o.snap.items || ':data(draggable)' ) : o.snap).each(function() {
			var $t = $(this); var $o = $t.offset();
			if(this != i.element[0]) i.snapElements.push({
				item: this,
				width: $t.outerWidth(), height: $t.outerHeight(),
				top: $o.top, left: $o.left
			});
		});

	},
	drag: function(event, ui) {

		var inst = $(this).data("draggable"), o = inst.options;
		var d = o.snapTolerance;

		var x1 = ui.offset.left, x2 = x1 + inst.helperProportions.width,
			y1 = ui.offset.top, y2 = y1 + inst.helperProportions.height;

		for (var i = inst.snapElements.length - 1; i >= 0; i--){

			var l = inst.snapElements[i].left, r = l + inst.snapElements[i].width,
				t = inst.snapElements[i].top, b = t + inst.snapElements[i].height;

			//Yes, I know, this is insane ;)
			if(!((l-d < x1 && x1 < r+d && t-d < y1 && y1 < b+d) || (l-d < x1 && x1 < r+d && t-d < y2 && y2 < b+d) || (l-d < x2 && x2 < r+d && t-d < y1 && y1 < b+d) || (l-d < x2 && x2 < r+d && t-d < y2 && y2 < b+d))) {
				if(inst.snapElements[i].snapping) (inst.options.snap.release && inst.options.snap.release.call(inst.element, event, $.extend(inst._uiHash(), { snapItem: inst.snapElements[i].item })));
				inst.snapElements[i].snapping = false;
				continue;
			}

			if(o.snapMode != 'inner') {
				var ts = Math.abs(t - y2) <= d;
				var bs = Math.abs(b - y1) <= d;
				var ls = Math.abs(l - x2) <= d;
				var rs = Math.abs(r - x1) <= d;
				if(ts) ui.position.top = inst._convertPositionTo("relative", { top: t - inst.helperProportions.height, left: 0 }).top - inst.margins.top;
				if(bs) ui.position.top = inst._convertPositionTo("relative", { top: b, left: 0 }).top - inst.margins.top;
				if(ls) ui.position.left = inst._convertPositionTo("relative", { top: 0, left: l - inst.helperProportions.width }).left - inst.margins.left;
				if(rs) ui.position.left = inst._convertPositionTo("relative", { top: 0, left: r }).left - inst.margins.left;
			}

			var first = (ts || bs || ls || rs);

			if(o.snapMode != 'outer') {
				var ts = Math.abs(t - y1) <= d;
				var bs = Math.abs(b - y2) <= d;
				var ls = Math.abs(l - x1) <= d;
				var rs = Math.abs(r - x2) <= d;
				if(ts) ui.position.top = inst._convertPositionTo("relative", { top: t, left: 0 }).top - inst.margins.top;
				if(bs) ui.position.top = inst._convertPositionTo("relative", { top: b - inst.helperProportions.height, left: 0 }).top - inst.margins.top;
				if(ls) ui.position.left = inst._convertPositionTo("relative", { top: 0, left: l }).left - inst.margins.left;
				if(rs) ui.position.left = inst._convertPositionTo("relative", { top: 0, left: r - inst.helperProportions.width }).left - inst.margins.left;
			}

			if(!inst.snapElements[i].snapping && (ts || bs || ls || rs || first))
				(inst.options.snap.snap && inst.options.snap.snap.call(inst.element, event, $.extend(inst._uiHash(), { snapItem: inst.snapElements[i].item })));
			inst.snapElements[i].snapping = (ts || bs || ls || rs || first);

		};

	}
});

$.ui.plugin.add("draggable", "stack", {
	start: function(event, ui) {

		var o = $(this).data("draggable").options;

		var group = $.makeArray($(o.stack)).sort(function(a,b) {
			return (parseInt($(a).css("zIndex"),10) || 0) - (parseInt($(b).css("zIndex"),10) || 0);
		});
		if (!group.length) { return; }

		var min = parseInt(group[0].style.zIndex) || 0;
		$(group).each(function(i) {
			this.style.zIndex = min + i;
		});

		this[0].style.zIndex = min + group.length;

	}
});

$.ui.plugin.add("draggable", "zIndex", {
	start: function(event, ui) {
		var t = $(ui.helper), o = $(this).data("draggable").options;
		if(t.css("zIndex")) o._zIndex = t.css("zIndex");
		t.css('zIndex', o.zIndex);
	},
	stop: function(event, ui) {
		var o = $(this).data("draggable").options;
		if(o._zIndex) $(ui.helper).css('zIndex', o._zIndex);
	}
});

})(jQuery);
(function( $, undefined ) {

$.widget("ui.droppable", {
	version: "1.9.2",
	widgetEventPrefix: "drop",
	options: {
		accept: '*',
		activeClass: false,
		addClasses: true,
		greedy: false,
		hoverClass: false,
		scope: 'default',
		tolerance: 'intersect'
	},
	_create: function() {

		var o = this.options, accept = o.accept;
		this.isover = 0; this.isout = 1;

		this.accept = $.isFunction(accept) ? accept : function(d) {
			return d.is(accept);
		};

		//Store the droppable's proportions
		this.proportions = { width: this.element[0].offsetWidth, height: this.element[0].offsetHeight };

		// Add the reference and positions to the manager
		$.ui.ddmanager.droppables[o.scope] = $.ui.ddmanager.droppables[o.scope] || [];
		$.ui.ddmanager.droppables[o.scope].push(this);

		(o.addClasses && this.element.addClass("ui-droppable"));

	},

	_destroy: function() {
		var drop = $.ui.ddmanager.droppables[this.options.scope];
		for ( var i = 0; i < drop.length; i++ )
			if ( drop[i] == this )
				drop.splice(i, 1);

		this.element.removeClass("ui-droppable ui-droppable-disabled");
	},

	_setOption: function(key, value) {

		if(key == 'accept') {
			this.accept = $.isFunction(value) ? value : function(d) {
				return d.is(value);
			};
		}
		$.Widget.prototype._setOption.apply(this, arguments);
	},

	_activate: function(event) {
		var draggable = $.ui.ddmanager.current;
		if(this.options.activeClass) this.element.addClass(this.options.activeClass);
		(draggable && this._trigger('activate', event, this.ui(draggable)));
	},

	_deactivate: function(event) {
		var draggable = $.ui.ddmanager.current;
		if(this.options.activeClass) this.element.removeClass(this.options.activeClass);
		(draggable && this._trigger('deactivate', event, this.ui(draggable)));
	},

	_over: function(event) {

		var draggable = $.ui.ddmanager.current;
		if (!draggable || (draggable.currentItem || draggable.element)[0] == this.element[0]) return; // Bail if draggable and droppable are same element

		if (this.accept.call(this.element[0],(draggable.currentItem || draggable.element))) {
			if(this.options.hoverClass) this.element.addClass(this.options.hoverClass);
			this._trigger('over', event, this.ui(draggable));
		}

	},

	_out: function(event) {

		var draggable = $.ui.ddmanager.current;
		if (!draggable || (draggable.currentItem || draggable.element)[0] == this.element[0]) return; // Bail if draggable and droppable are same element

		if (this.accept.call(this.element[0],(draggable.currentItem || draggable.element))) {
			if(this.options.hoverClass) this.element.removeClass(this.options.hoverClass);
			this._trigger('out', event, this.ui(draggable));
		}

	},

	_drop: function(event,custom) {

		var draggable = custom || $.ui.ddmanager.current;
		if (!draggable || (draggable.currentItem || draggable.element)[0] == this.element[0]) return false; // Bail if draggable and droppable are same element

		var childrenIntersection = false;
		this.element.find(":data(droppable)").not(".ui-draggable-dragging").each(function() {
			var inst = $.data(this, 'droppable');
			if(
				inst.options.greedy
				&& !inst.options.disabled
				&& inst.options.scope == draggable.options.scope
				&& inst.accept.call(inst.element[0], (draggable.currentItem || draggable.element))
				&& $.ui.intersect(draggable, $.extend(inst, { offset: inst.element.offset() }), inst.options.tolerance)
			) { childrenIntersection = true; return false; }
		});
		if(childrenIntersection) return false;

		if(this.accept.call(this.element[0],(draggable.currentItem || draggable.element))) {
			if(this.options.activeClass) this.element.removeClass(this.options.activeClass);
			if(this.options.hoverClass) this.element.removeClass(this.options.hoverClass);
			this._trigger('drop', event, this.ui(draggable));
			return this.element;
		}

		return false;

	},

	ui: function(c) {
		return {
			draggable: (c.currentItem || c.element),
			helper: c.helper,
			position: c.position,
			offset: c.positionAbs
		};
	}

});

$.ui.intersect = function(draggable, droppable, toleranceMode) {

	if (!droppable.offset) return false;

	var x1 = (draggable.positionAbs || draggable.position.absolute).left, x2 = x1 + draggable.helperProportions.width,
		y1 = (draggable.positionAbs || draggable.position.absolute).top, y2 = y1 + draggable.helperProportions.height;
	var l = droppable.offset.left, r = l + droppable.proportions.width,
		t = droppable.offset.top, b = t + droppable.proportions.height;

	switch (toleranceMode) {
		case 'fit':
			return (l <= x1 && x2 <= r
				&& t <= y1 && y2 <= b);
			break;
		case 'intersect':
			return (l < x1 + (draggable.helperProportions.width / 2) // Right Half
				&& x2 - (draggable.helperProportions.width / 2) < r // Left Half
				&& t < y1 + (draggable.helperProportions.height / 2) // Bottom Half
				&& y2 - (draggable.helperProportions.height / 2) < b ); // Top Half
			break;
		case 'pointer':
			var draggableLeft = ((draggable.positionAbs || draggable.position.absolute).left + (draggable.clickOffset || draggable.offset.click).left),
				draggableTop = ((draggable.positionAbs || draggable.position.absolute).top + (draggable.clickOffset || draggable.offset.click).top),
				isOver = $.ui.isOver(draggableTop, draggableLeft, t, l, droppable.proportions.height, droppable.proportions.width);
			return isOver;
			break;
		case 'touch':
			return (
					(y1 >= t && y1 <= b) ||	// Top edge touching
					(y2 >= t && y2 <= b) ||	// Bottom edge touching
					(y1 < t && y2 > b)		// Surrounded vertically
				) && (
					(x1 >= l && x1 <= r) ||	// Left edge touching
					(x2 >= l && x2 <= r) ||	// Right edge touching
					(x1 < l && x2 > r)		// Surrounded horizontally
				);
			break;
		default:
			return false;
			break;
		}

};

/*
	This manager tracks offsets of draggables and droppables
*/
$.ui.ddmanager = {
	current: null,
	droppables: { 'default': [] },
	prepareOffsets: function(t, event) {

		var m = $.ui.ddmanager.droppables[t.options.scope] || [];
		var type = event ? event.type : null; // workaround for #2317
		var list = (t.currentItem || t.element).find(":data(droppable)").andSelf();

		droppablesLoop: for (var i = 0; i < m.length; i++) {

			if(m[i].options.disabled || (t && !m[i].accept.call(m[i].element[0],(t.currentItem || t.element)))) continue;	//No disabled and non-accepted
			for (var j=0; j < list.length; j++) { if(list[j] == m[i].element[0]) { m[i].proportions.height = 0; continue droppablesLoop; } }; //Filter out elements in the current dragged item
			m[i].visible = m[i].element.css("display") != "none"; if(!m[i].visible) continue; 									//If the element is not visible, continue

			if(type == "mousedown") m[i]._activate.call(m[i], event); //Activate the droppable if used directly from draggables

			m[i].offset = m[i].element.offset();
			m[i].proportions = { width: m[i].element[0].offsetWidth, height: m[i].element[0].offsetHeight };

		}

	},
	drop: function(draggable, event) {

		var dropped = false;
		$.each($.ui.ddmanager.droppables[draggable.options.scope] || [], function() {

			if(!this.options) return;
			if (!this.options.disabled && this.visible && $.ui.intersect(draggable, this, this.options.tolerance))
				dropped = this._drop.call(this, event) || dropped;

			if (!this.options.disabled && this.visible && this.accept.call(this.element[0],(draggable.currentItem || draggable.element))) {
				this.isout = 1; this.isover = 0;
				this._deactivate.call(this, event);
			}

		});
		return dropped;

	},
	dragStart: function( draggable, event ) {
		//Listen for scrolling so that if the dragging causes scrolling the position of the droppables can be recalculated (see #5003)
		draggable.element.parentsUntil( "body" ).bind( "scroll.droppable", function() {
			if( !draggable.options.refreshPositions ) $.ui.ddmanager.prepareOffsets( draggable, event );
		});
	},
	drag: function(draggable, event) {

		//If you have a highly dynamic page, you might try this option. It renders positions every time you move the mouse.
		if(draggable.options.refreshPositions) $.ui.ddmanager.prepareOffsets(draggable, event);

		//Run through all droppables and check their positions based on specific tolerance options
		$.each($.ui.ddmanager.droppables[draggable.options.scope] || [], function() {

			if(this.options.disabled || this.greedyChild || !this.visible) return;
			var intersects = $.ui.intersect(draggable, this, this.options.tolerance);

			var c = !intersects && this.isover == 1 ? 'isout' : (intersects && this.isover == 0 ? 'isover' : null);
			if(!c) return;

			var parentInstance;
			if (this.options.greedy) {
				// find droppable parents with same scope
				var scope = this.options.scope;
				var parent = this.element.parents(':data(droppable)').filter(function () {
					return $.data(this, 'droppable').options.scope === scope;
				});

				if (parent.length) {
					parentInstance = $.data(parent[0], 'droppable');
					parentInstance.greedyChild = (c == 'isover' ? 1 : 0);
				}
			}

			// we just moved into a greedy child
			if (parentInstance && c == 'isover') {
				parentInstance['isover'] = 0;
				parentInstance['isout'] = 1;
				parentInstance._out.call(parentInstance, event);
			}

			this[c] = 1; this[c == 'isout' ? 'isover' : 'isout'] = 0;
			this[c == "isover" ? "_over" : "_out"].call(this, event);

			// we just moved out of a greedy child
			if (parentInstance && c == 'isout') {
				parentInstance['isout'] = 0;
				parentInstance['isover'] = 1;
				parentInstance._over.call(parentInstance, event);
			}
		});

	},
	dragStop: function( draggable, event ) {
		draggable.element.parentsUntil( "body" ).unbind( "scroll.droppable" );
		//Call prepareOffsets one final time since IE does not fire return scroll events when overflow was caused by drag (see #5003)
		if( !draggable.options.refreshPositions ) $.ui.ddmanager.prepareOffsets( draggable, event );
	}
};

})(jQuery);
(function( $, undefined ) {

$.widget("ui.resizable", $.ui.mouse, {
	version: "1.9.2",
	widgetEventPrefix: "resize",
	options: {
		alsoResize: false,
		animate: false,
		animateDuration: "slow",
		animateEasing: "swing",
		aspectRatio: false,
		autoHide: false,
		containment: false,
		ghost: false,
		grid: false,
		handles: "e,s,se",
		helper: false,
		maxHeight: null,
		maxWidth: null,
		minHeight: 10,
		minWidth: 10,
		zIndex: 1000
	},
	_create: function() {

		var that = this, o = this.options;
		this.element.addClass("ui-resizable");

		$.extend(this, {
			_aspectRatio: !!(o.aspectRatio),
			aspectRatio: o.aspectRatio,
			originalElement: this.element,
			_proportionallyResizeElements: [],
			_helper: o.helper || o.ghost || o.animate ? o.helper || 'ui-resizable-helper' : null
		});

		//Wrap the element if it cannot hold child nodes
		if(this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)) {

			//Create a wrapper element and set the wrapper to the new current internal element
			this.element.wrap(
				$('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({
					position: this.element.css('position'),
					width: this.element.outerWidth(),
					height: this.element.outerHeight(),
					top: this.element.css('top'),
					left: this.element.css('left')
				})
			);

			//Overwrite the original this.element
			this.element = this.element.parent().data(
				"resizable", this.element.data('resizable')
			);

			this.elementIsWrapper = true;

			//Move margins to the wrapper
			this.element.css({ marginLeft: this.originalElement.css("marginLeft"), marginTop: this.originalElement.css("marginTop"), marginRight: this.originalElement.css("marginRight"), marginBottom: this.originalElement.css("marginBottom") });
			this.originalElement.css({ marginLeft: 0, marginTop: 0, marginRight: 0, marginBottom: 0});

			//Prevent Safari textarea resize
			this.originalResizeStyle = this.originalElement.css('resize');
			this.originalElement.css('resize', 'none');

			//Push the actual element to our proportionallyResize internal array
			this._proportionallyResizeElements.push(this.originalElement.css({ position: 'static', zoom: 1, display: 'block' }));

			// avoid IE jump (hard set the margin)
			this.originalElement.css({ margin: this.originalElement.css('margin') });

			// fix handlers offset
			this._proportionallyResize();

		}

		this.handles = o.handles || (!$('.ui-resizable-handle', this.element).length ? "e,s,se" : { n: '.ui-resizable-n', e: '.ui-resizable-e', s: '.ui-resizable-s', w: '.ui-resizable-w', se: '.ui-resizable-se', sw: '.ui-resizable-sw', ne: '.ui-resizable-ne', nw: '.ui-resizable-nw' });
		if(this.handles.constructor == String) {

			if(this.handles == 'all') this.handles = 'n,e,s,w,se,sw,ne,nw';
			var n = this.handles.split(","); this.handles = {};

			for(var i = 0; i < n.length; i++) {

				var handle = $.trim(n[i]), hname = 'ui-resizable-'+handle;
				var axis = $('<div class="ui-resizable-handle ' + hname + '"></div>');

				// Apply zIndex to all handles - see #7960
				axis.css({ zIndex: o.zIndex });

				//TODO : What's going on here?
				if ('se' == handle) {
					axis.addClass('ui-icon ui-icon-gripsmall-diagonal-se');
				};

				//Insert into internal handles object and append to element
				this.handles[handle] = '.ui-resizable-'+handle;
				this.element.append(axis);
			}

		}

		this._renderAxis = function(target) {

			target = target || this.element;

			for(var i in this.handles) {

				if(this.handles[i].constructor == String)
					this.handles[i] = $(this.handles[i], this.element).show();

				//Apply pad to wrapper element, needed to fix axis position (textarea, inputs, scrolls)
				if (this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i)) {

					var axis = $(this.handles[i], this.element), padWrapper = 0;

					//Checking the correct pad and border
					padWrapper = /sw|ne|nw|se|n|s/.test(i) ? axis.outerHeight() : axis.outerWidth();

					//The padding type i have to apply...
					var padPos = [ 'padding',
						/ne|nw|n/.test(i) ? 'Top' :
						/se|sw|s/.test(i) ? 'Bottom' :
						/^e$/.test(i) ? 'Right' : 'Left' ].join("");

					target.css(padPos, padWrapper);

					this._proportionallyResize();

				}

				//TODO: What's that good for? There's not anything to be executed left
				if(!$(this.handles[i]).length)
					continue;

			}
		};

		//TODO: make renderAxis a prototype function
		this._renderAxis(this.element);

		this._handles = $('.ui-resizable-handle', this.element)
			.disableSelection();

		//Matching axis name
		this._handles.mouseover(function() {
			if (!that.resizing) {
				if (this.className)
					var axis = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);
				//Axis, default = se
				that.axis = axis && axis[1] ? axis[1] : 'se';
			}
		});

		//If we want to auto hide the elements
		if (o.autoHide) {
			this._handles.hide();
			$(this.element)
				.addClass("ui-resizable-autohide")
				.mouseenter(function() {
					if (o.disabled) return;
					$(this).removeClass("ui-resizable-autohide");
					that._handles.show();
				})
				.mouseleave(function(){
					if (o.disabled) return;
					if (!that.resizing) {
						$(this).addClass("ui-resizable-autohide");
						that._handles.hide();
					}
				});
		}

		//Initialize the mouse interaction
		this._mouseInit();

	},

	_destroy: function() {

		this._mouseDestroy();

		var _destroy = function(exp) {
			$(exp).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing")
				.removeData("resizable").removeData("ui-resizable").unbind(".resizable").find('.ui-resizable-handle').remove();
		};

		//TODO: Unwrap at same DOM position
		if (this.elementIsWrapper) {
			_destroy(this.element);
			var wrapper = this.element;
			this.originalElement.css({
				position: wrapper.css('position'),
				width: wrapper.outerWidth(),
				height: wrapper.outerHeight(),
				top: wrapper.css('top'),
				left: wrapper.css('left')
			}).insertAfter( wrapper );
			wrapper.remove();
		}

		this.originalElement.css('resize', this.originalResizeStyle);
		_destroy(this.originalElement);

		return this;
	},

	_mouseCapture: function(event) {
		var handle = false;
		for (var i in this.handles) {
			if ($(this.handles[i])[0] == event.target) {
				handle = true;
			}
		}

		return !this.options.disabled && handle;
	},

	_mouseStart: function(event) {

		var o = this.options, iniPos = this.element.position(), el = this.element;

		this.resizing = true;
		this.documentScroll = { top: $(document).scrollTop(), left: $(document).scrollLeft() };

		// bugfix for http://dev.jquery.com/ticket/1749
		if (el.is('.ui-draggable') || (/absolute/).test(el.css('position'))) {
			el.css({ position: 'absolute', top: iniPos.top, left: iniPos.left });
		}

		this._renderProxy();

		var curleft = num(this.helper.css('left')), curtop = num(this.helper.css('top'));

		if (o.containment) {
			curleft += $(o.containment).scrollLeft() || 0;
			curtop += $(o.containment).scrollTop() || 0;
		}

		//Store needed variables
		this.offset = this.helper.offset();
		this.position = { left: curleft, top: curtop };
		this.size = this._helper ? { width: el.outerWidth(), height: el.outerHeight() } : { width: el.width(), height: el.height() };
		this.originalSize = this._helper ? { width: el.outerWidth(), height: el.outerHeight() } : { width: el.width(), height: el.height() };
		this.originalPosition = { left: curleft, top: curtop };
		this.sizeDiff = { width: el.outerWidth() - el.width(), height: el.outerHeight() - el.height() };
		this.originalMousePosition = { left: event.pageX, top: event.pageY };

		//Aspect Ratio
		this.aspectRatio = (typeof o.aspectRatio == 'number') ? o.aspectRatio : ((this.originalSize.width / this.originalSize.height) || 1);

		var cursor = $('.ui-resizable-' + this.axis).css('cursor');
		$('body').css('cursor', cursor == 'auto' ? this.axis + '-resize' : cursor);

		el.addClass("ui-resizable-resizing");
		this._propagate("start", event);
		return true;
	},

	_mouseDrag: function(event) {

		//Increase performance, avoid regex
		var el = this.helper, o = this.options, props = {},
			that = this, smp = this.originalMousePosition, a = this.axis;

		var dx = (event.pageX-smp.left)||0, dy = (event.pageY-smp.top)||0;
		var trigger = this._change[a];
		if (!trigger) return false;

		// Calculate the attrs that will be change
		var data = trigger.apply(this, [event, dx, dy]);

		// Put this in the mouseDrag handler since the user can start pressing shift while resizing
		this._updateVirtualBoundaries(event.shiftKey);
		if (this._aspectRatio || event.shiftKey)
			data = this._updateRatio(data, event);

		data = this._respectSize(data, event);

		// plugins callbacks need to be called first
		this._propagate("resize", event);

		el.css({
			top: this.position.top + "px", left: this.position.left + "px",
			width: this.size.width + "px", height: this.size.height + "px"
		});

		if (!this._helper && this._proportionallyResizeElements.length)
			this._proportionallyResize();

		this._updateCache(data);

		// calling the user callback at the end
		this._trigger('resize', event, this.ui());

		return false;
	},

	_mouseStop: function(event) {

		this.resizing = false;
		var o = this.options, that = this;

		if(this._helper) {
			var pr = this._proportionallyResizeElements, ista = pr.length && (/textarea/i).test(pr[0].nodeName),
				soffseth = ista && $.ui.hasScroll(pr[0], 'left') /* TODO - jump height */ ? 0 : that.sizeDiff.height,
				soffsetw = ista ? 0 : that.sizeDiff.width;

			var s = { width: (that.helper.width()  - soffsetw), height: (that.helper.height() - soffseth) },
				left = (parseInt(that.element.css('left'), 10) + (that.position.left - that.originalPosition.left)) || null,
				top = (parseInt(that.element.css('top'), 10) + (that.position.top - that.originalPosition.top)) || null;

			if (!o.animate)
				this.element.css($.extend(s, { top: top, left: left }));

			that.helper.height(that.size.height);
			that.helper.width(that.size.width);

			if (this._helper && !o.animate) this._proportionallyResize();
		}

		$('body').css('cursor', 'auto');

		this.element.removeClass("ui-resizable-resizing");

		this._propagate("stop", event);

		if (this._helper) this.helper.remove();
		return false;

	},

	_updateVirtualBoundaries: function(forceAspectRatio) {
		var o = this.options, pMinWidth, pMaxWidth, pMinHeight, pMaxHeight, b;

		b = {
			minWidth: isNumber(o.minWidth) ? o.minWidth : 0,
			maxWidth: isNumber(o.maxWidth) ? o.maxWidth : Infinity,
			minHeight: isNumber(o.minHeight) ? o.minHeight : 0,
			maxHeight: isNumber(o.maxHeight) ? o.maxHeight : Infinity
		};

		if(this._aspectRatio || forceAspectRatio) {
			// We want to create an enclosing box whose aspect ration is the requested one
			// First, compute the "projected" size for each dimension based on the aspect ratio and other dimension
			pMinWidth = b.minHeight * this.aspectRatio;
			pMinHeight = b.minWidth / this.aspectRatio;
			pMaxWidth = b.maxHeight * this.aspectRatio;
			pMaxHeight = b.maxWidth / this.aspectRatio;

			if(pMinWidth > b.minWidth) b.minWidth = pMinWidth;
			if(pMinHeight > b.minHeight) b.minHeight = pMinHeight;
			if(pMaxWidth < b.maxWidth) b.maxWidth = pMaxWidth;
			if(pMaxHeight < b.maxHeight) b.maxHeight = pMaxHeight;
		}
		this._vBoundaries = b;
	},

	_updateCache: function(data) {
		var o = this.options;
		this.offset = this.helper.offset();
		if (isNumber(data.left)) this.position.left = data.left;
		if (isNumber(data.top)) this.position.top = data.top;
		if (isNumber(data.height)) this.size.height = data.height;
		if (isNumber(data.width)) this.size.width = data.width;
	},

	_updateRatio: function(data, event) {

		var o = this.options, cpos = this.position, csize = this.size, a = this.axis;

		if (isNumber(data.height)) data.width = (data.height * this.aspectRatio);
		else if (isNumber(data.width)) data.height = (data.width / this.aspectRatio);

		if (a == 'sw') {
			data.left = cpos.left + (csize.width - data.width);
			data.top = null;
		}
		if (a == 'nw') {
			data.top = cpos.top + (csize.height - data.height);
			data.left = cpos.left + (csize.width - data.width);
		}

		return data;
	},

	_respectSize: function(data, event) {

		var el = this.helper, o = this._vBoundaries, pRatio = this._aspectRatio || event.shiftKey, a = this.axis,
				ismaxw = isNumber(data.width) && o.maxWidth && (o.maxWidth < data.width), ismaxh = isNumber(data.height) && o.maxHeight && (o.maxHeight < data.height),
					isminw = isNumber(data.width) && o.minWidth && (o.minWidth > data.width), isminh = isNumber(data.height) && o.minHeight && (o.minHeight > data.height);

		if (isminw) data.width = o.minWidth;
		if (isminh) data.height = o.minHeight;
		if (ismaxw) data.width = o.maxWidth;
		if (ismaxh) data.height = o.maxHeight;

		var dw = this.originalPosition.left + this.originalSize.width, dh = this.position.top + this.size.height;
		var cw = /sw|nw|w/.test(a), ch = /nw|ne|n/.test(a);

		if (isminw && cw) data.left = dw - o.minWidth;
		if (ismaxw && cw) data.left = dw - o.maxWidth;
		if (isminh && ch)	data.top = dh - o.minHeight;
		if (ismaxh && ch)	data.top = dh - o.maxHeight;

		// fixing jump error on top/left - bug #2330
		var isNotwh = !data.width && !data.height;
		if (isNotwh && !data.left && data.top) data.top = null;
		else if (isNotwh && !data.top && data.left) data.left = null;

		return data;
	},

	_proportionallyResize: function() {

		var o = this.options;
		if (!this._proportionallyResizeElements.length) return;
		var element = this.helper || this.element;

		for (var i=0; i < this._proportionallyResizeElements.length; i++) {

			var prel = this._proportionallyResizeElements[i];

			if (!this.borderDif) {
				var b = [prel.css('borderTopWidth'), prel.css('borderRightWidth'), prel.css('borderBottomWidth'), prel.css('borderLeftWidth')],
					p = [prel.css('paddingTop'), prel.css('paddingRight'), prel.css('paddingBottom'), prel.css('paddingLeft')];

				this.borderDif = $.map(b, function(v, i) {
					var border = parseInt(v,10)||0, padding = parseInt(p[i],10)||0;
					return border + padding;
				});
			}

			prel.css({
				height: (element.height() - this.borderDif[0] - this.borderDif[2]) || 0,
				width: (element.width() - this.borderDif[1] - this.borderDif[3]) || 0
			});

		};

	},

	_renderProxy: function() {

		var el = this.element, o = this.options;
		this.elementOffset = el.offset();

		if(this._helper) {

			this.helper = this.helper || $('<div style="overflow:hidden;"></div>');

			// fix ie6 offset TODO: This seems broken
			var ie6offset = ($.ui.ie6 ? 1 : 0),
			pxyoffset = ( $.ui.ie6 ? 2 : -1 );

			this.helper.addClass(this._helper).css({
				width: this.element.outerWidth() + pxyoffset,
				height: this.element.outerHeight() + pxyoffset,
				position: 'absolute',
				left: this.elementOffset.left - ie6offset +'px',
				top: this.elementOffset.top - ie6offset +'px',
				zIndex: ++o.zIndex //TODO: Don't modify option
			});

			this.helper
				.appendTo("body")
				.disableSelection();

		} else {
			this.helper = this.element;
		}

	},

	_change: {
		e: function(event, dx, dy) {
			return { width: this.originalSize.width + dx };
		},
		w: function(event, dx, dy) {
			var o = this.options, cs = this.originalSize, sp = this.originalPosition;
			return { left: sp.left + dx, width: cs.width - dx };
		},
		n: function(event, dx, dy) {
			var o = this.options, cs = this.originalSize, sp = this.originalPosition;
			return { top: sp.top + dy, height: cs.height - dy };
		},
		s: function(event, dx, dy) {
			return { height: this.originalSize.height + dy };
		},
		se: function(event, dx, dy) {
			return $.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [event, dx, dy]));
		},
		sw: function(event, dx, dy) {
			return $.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [event, dx, dy]));
		},
		ne: function(event, dx, dy) {
			return $.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [event, dx, dy]));
		},
		nw: function(event, dx, dy) {
			return $.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [event, dx, dy]));
		}
	},

	_propagate: function(n, event) {
		$.ui.plugin.call(this, n, [event, this.ui()]);
		(n != "resize" && this._trigger(n, event, this.ui()));
	},

	plugins: {},

	ui: function() {
		return {
			originalElement: this.originalElement,
			element: this.element,
			helper: this.helper,
			position: this.position,
			size: this.size,
			originalSize: this.originalSize,
			originalPosition: this.originalPosition
		};
	}

});

/*
 * Resizable Extensions
 */

$.ui.plugin.add("resizable", "alsoResize", {

	start: function (event, ui) {
		var that = $(this).data("resizable"), o = that.options;

		var _store = function (exp) {
			$(exp).each(function() {
				var el = $(this);
				el.data("resizable-alsoresize", {
					width: parseInt(el.width(), 10), height: parseInt(el.height(), 10),
					left: parseInt(el.css('left'), 10), top: parseInt(el.css('top'), 10)
				});
			});
		};

		if (typeof(o.alsoResize) == 'object' && !o.alsoResize.parentNode) {
			if (o.alsoResize.length) { o.alsoResize = o.alsoResize[0]; _store(o.alsoResize); }
			else { $.each(o.alsoResize, function (exp) { _store(exp); }); }
		}else{
			_store(o.alsoResize);
		}
	},

	resize: function (event, ui) {
		var that = $(this).data("resizable"), o = that.options, os = that.originalSize, op = that.originalPosition;

		var delta = {
			height: (that.size.height - os.height) || 0, width: (that.size.width - os.width) || 0,
			top: (that.position.top - op.top) || 0, left: (that.position.left - op.left) || 0
		},

		_alsoResize = function (exp, c) {
			$(exp).each(function() {
				var el = $(this), start = $(this).data("resizable-alsoresize"), style = {},
					css = c && c.length ? c : el.parents(ui.originalElement[0]).length ? ['width', 'height'] : ['width', 'height', 'top', 'left'];

				$.each(css, function (i, prop) {
					var sum = (start[prop]||0) + (delta[prop]||0);
					if (sum && sum >= 0)
						style[prop] = sum || null;
				});

				el.css(style);
			});
		};

		if (typeof(o.alsoResize) == 'object' && !o.alsoResize.nodeType) {
			$.each(o.alsoResize, function (exp, c) { _alsoResize(exp, c); });
		}else{
			_alsoResize(o.alsoResize);
		}
	},

	stop: function (event, ui) {
		$(this).removeData("resizable-alsoresize");
	}
});

$.ui.plugin.add("resizable", "animate", {

	stop: function(event, ui) {
		var that = $(this).data("resizable"), o = that.options;

		var pr = that._proportionallyResizeElements, ista = pr.length && (/textarea/i).test(pr[0].nodeName),
					soffseth = ista && $.ui.hasScroll(pr[0], 'left') /* TODO - jump height */ ? 0 : that.sizeDiff.height,
						soffsetw = ista ? 0 : that.sizeDiff.width;

		var style = { width: (that.size.width - soffsetw), height: (that.size.height - soffseth) },
					left = (parseInt(that.element.css('left'), 10) + (that.position.left - that.originalPosition.left)) || null,
						top = (parseInt(that.element.css('top'), 10) + (that.position.top - that.originalPosition.top)) || null;

		that.element.animate(
			$.extend(style, top && left ? { top: top, left: left } : {}), {
				duration: o.animateDuration,
				easing: o.animateEasing,
				step: function() {

					var data = {
						width: parseInt(that.element.css('width'), 10),
						height: parseInt(that.element.css('height'), 10),
						top: parseInt(that.element.css('top'), 10),
						left: parseInt(that.element.css('left'), 10)
					};

					if (pr && pr.length) $(pr[0]).css({ width: data.width, height: data.height });

					// propagating resize, and updating values for each animation step
					that._updateCache(data);
					that._propagate("resize", event);

				}
			}
		);
	}

});

$.ui.plugin.add("resizable", "containment", {

	start: function(event, ui) {
		var that = $(this).data("resizable"), o = that.options, el = that.element;
		var oc = o.containment,	ce = (oc instanceof $) ? oc.get(0) : (/parent/.test(oc)) ? el.parent().get(0) : oc;
		if (!ce) return;

		that.containerElement = $(ce);

		if (/document/.test(oc) || oc == document) {
			that.containerOffset = { left: 0, top: 0 };
			that.containerPosition = { left: 0, top: 0 };

			that.parentData = {
				element: $(document), left: 0, top: 0,
				width: $(document).width(), height: $(document).height() || document.body.parentNode.scrollHeight
			};
		}

		// i'm a node, so compute top, left, right, bottom
		else {
			var element = $(ce), p = [];
			$([ "Top", "Right", "Left", "Bottom" ]).each(function(i, name) { p[i] = num(element.css("padding" + name)); });

			that.containerOffset = element.offset();
			that.containerPosition = element.position();
			that.containerSize = { height: (element.innerHeight() - p[3]), width: (element.innerWidth() - p[1]) };

			var co = that.containerOffset, ch = that.containerSize.height,	cw = that.containerSize.width,
						width = ($.ui.hasScroll(ce, "left") ? ce.scrollWidth : cw ), height = ($.ui.hasScroll(ce) ? ce.scrollHeight : ch);

			that.parentData = {
				element: ce, left: co.left, top: co.top, width: width, height: height
			};
		}
	},

	resize: function(event, ui) {
		var that = $(this).data("resizable"), o = that.options,
				ps = that.containerSize, co = that.containerOffset, cs = that.size, cp = that.position,
				pRatio = that._aspectRatio || event.shiftKey, cop = { top:0, left:0 }, ce = that.containerElement;

		if (ce[0] != document && (/static/).test(ce.css('position'))) cop = co;

		if (cp.left < (that._helper ? co.left : 0)) {
			that.size.width = that.size.width + (that._helper ? (that.position.left - co.left) : (that.position.left - cop.left));
			if (pRatio) that.size.height = that.size.width / that.aspectRatio;
			that.position.left = o.helper ? co.left : 0;
		}

		if (cp.top < (that._helper ? co.top : 0)) {
			that.size.height = that.size.height + (that._helper ? (that.position.top - co.top) : that.position.top);
			if (pRatio) that.size.width = that.size.height * that.aspectRatio;
			that.position.top = that._helper ? co.top : 0;
		}

		that.offset.left = that.parentData.left+that.position.left;
		that.offset.top = that.parentData.top+that.position.top;

		var woset = Math.abs( (that._helper ? that.offset.left - cop.left : (that.offset.left - cop.left)) + that.sizeDiff.width ),
					hoset = Math.abs( (that._helper ? that.offset.top - cop.top : (that.offset.top - co.top)) + that.sizeDiff.height );

		var isParent = that.containerElement.get(0) == that.element.parent().get(0),
			isOffsetRelative = /relative|absolute/.test(that.containerElement.css('position'));

		if(isParent && isOffsetRelative) woset -= that.parentData.left;

		if (woset + that.size.width >= that.parentData.width) {
			that.size.width = that.parentData.width - woset;
			if (pRatio) that.size.height = that.size.width / that.aspectRatio;
		}

		if (hoset + that.size.height >= that.parentData.height) {
			that.size.height = that.parentData.height - hoset;
			if (pRatio) that.size.width = that.size.height * that.aspectRatio;
		}
	},

	stop: function(event, ui){
		var that = $(this).data("resizable"), o = that.options, cp = that.position,
				co = that.containerOffset, cop = that.containerPosition, ce = that.containerElement;

		var helper = $(that.helper), ho = helper.offset(), w = helper.outerWidth() - that.sizeDiff.width, h = helper.outerHeight() - that.sizeDiff.height;

		if (that._helper && !o.animate && (/relative/).test(ce.css('position')))
			$(this).css({ left: ho.left - cop.left - co.left, width: w, height: h });

		if (that._helper && !o.animate && (/static/).test(ce.css('position')))
			$(this).css({ left: ho.left - cop.left - co.left, width: w, height: h });

	}
});

$.ui.plugin.add("resizable", "ghost", {

	start: function(event, ui) {

		var that = $(this).data("resizable"), o = that.options, cs = that.size;

		that.ghost = that.originalElement.clone();
		that.ghost
			.css({ opacity: .25, display: 'block', position: 'relative', height: cs.height, width: cs.width, margin: 0, left: 0, top: 0 })
			.addClass('ui-resizable-ghost')
			.addClass(typeof o.ghost == 'string' ? o.ghost : '');

		that.ghost.appendTo(that.helper);

	},

	resize: function(event, ui){
		var that = $(this).data("resizable"), o = that.options;
		if (that.ghost) that.ghost.css({ position: 'relative', height: that.size.height, width: that.size.width });
	},

	stop: function(event, ui){
		var that = $(this).data("resizable"), o = that.options;
		if (that.ghost && that.helper) that.helper.get(0).removeChild(that.ghost.get(0));
	}

});

$.ui.plugin.add("resizable", "grid", {

	resize: function(event, ui) {
		var that = $(this).data("resizable"), o = that.options, cs = that.size, os = that.originalSize, op = that.originalPosition, a = that.axis, ratio = o._aspectRatio || event.shiftKey;
		o.grid = typeof o.grid == "number" ? [o.grid, o.grid] : o.grid;
		var ox = Math.round((cs.width - os.width) / (o.grid[0]||1)) * (o.grid[0]||1), oy = Math.round((cs.height - os.height) / (o.grid[1]||1)) * (o.grid[1]||1);

		if (/^(se|s|e)$/.test(a)) {
			that.size.width = os.width + ox;
			that.size.height = os.height + oy;
		}
		else if (/^(ne)$/.test(a)) {
			that.size.width = os.width + ox;
			that.size.height = os.height + oy;
			that.position.top = op.top - oy;
		}
		else if (/^(sw)$/.test(a)) {
			that.size.width = os.width + ox;
			that.size.height = os.height + oy;
			that.position.left = op.left - ox;
		}
		else {
			that.size.width = os.width + ox;
			that.size.height = os.height + oy;
			that.position.top = op.top - oy;
			that.position.left = op.left - ox;
		}
	}

});

var num = function(v) {
	return parseInt(v, 10) || 0;
};

var isNumber = function(value) {
	return !isNaN(parseInt(value, 10));
};

})(jQuery);
(function( $, undefined ) {

$.widget("ui.selectable", $.ui.mouse, {
	version: "1.9.2",
	options: {
		appendTo: 'body',
		autoRefresh: true,
		distance: 0,
		filter: '*',
		tolerance: 'touch'
	},
	_create: function() {
		var that = this;

		this.element.addClass("ui-selectable");

		this.dragged = false;

		// cache selectee children based on filter
		var selectees;
		this.refresh = function() {
			selectees = $(that.options.filter, that.element[0]);
			selectees.addClass("ui-selectee");
			selectees.each(function() {
				var $this = $(this);
				var pos = $this.offset();
				$.data(this, "selectable-item", {
					element: this,
					$element: $this,
					left: pos.left,
					top: pos.top,
					right: pos.left + $this.outerWidth(),
					bottom: pos.top + $this.outerHeight(),
					startselected: false,
					selected: $this.hasClass('ui-selected'),
					selecting: $this.hasClass('ui-selecting'),
					unselecting: $this.hasClass('ui-unselecting')
				});
			});
		};
		this.refresh();

		this.selectees = selectees.addClass("ui-selectee");

		this._mouseInit();

		this.helper = $("<div class='ui-selectable-helper'></div>");
	},

	_destroy: function() {
		this.selectees
			.removeClass("ui-selectee")
			.removeData("selectable-item");
		this.element
			.removeClass("ui-selectable ui-selectable-disabled");
		this._mouseDestroy();
	},

	_mouseStart: function(event) {
		var that = this;

		this.opos = [event.pageX, event.pageY];

		if (this.options.disabled)
			return;

		var options = this.options;

		this.selectees = $(options.filter, this.element[0]);

		this._trigger("start", event);

		$(options.appendTo).append(this.helper);
		// position helper (lasso)
		this.helper.css({
			"left": event.clientX,
			"top": event.clientY,
			"width": 0,
			"height": 0
		});

		if (options.autoRefresh) {
			this.refresh();
		}

		this.selectees.filter('.ui-selected').each(function() {
			var selectee = $.data(this, "selectable-item");
			selectee.startselected = true;
			if (!event.metaKey && !event.ctrlKey) {
				selectee.$element.removeClass('ui-selected');
				selectee.selected = false;
				selectee.$element.addClass('ui-unselecting');
				selectee.unselecting = true;
				// selectable UNSELECTING callback
				that._trigger("unselecting", event, {
					unselecting: selectee.element
				});
			}
		});

		$(event.target).parents().andSelf().each(function() {
			var selectee = $.data(this, "selectable-item");
			if (selectee) {
				var doSelect = (!event.metaKey && !event.ctrlKey) || !selectee.$element.hasClass('ui-selected');
				selectee.$element
					.removeClass(doSelect ? "ui-unselecting" : "ui-selected")
					.addClass(doSelect ? "ui-selecting" : "ui-unselecting");
				selectee.unselecting = !doSelect;
				selectee.selecting = doSelect;
				selectee.selected = doSelect;
				// selectable (UN)SELECTING callback
				if (doSelect) {
					that._trigger("selecting", event, {
						selecting: selectee.element
					});
				} else {
					that._trigger("unselecting", event, {
						unselecting: selectee.element
					});
				}
				return false;
			}
		});

	},

	_mouseDrag: function(event) {
		var that = this;
		this.dragged = true;

		if (this.options.disabled)
			return;

		var options = this.options;

		var x1 = this.opos[0], y1 = this.opos[1], x2 = event.pageX, y2 = event.pageY;
		if (x1 > x2) { var tmp = x2; x2 = x1; x1 = tmp; }
		if (y1 > y2) { var tmp = y2; y2 = y1; y1 = tmp; }
		this.helper.css({left: x1, top: y1, width: x2-x1, height: y2-y1});

		this.selectees.each(function() {
			var selectee = $.data(this, "selectable-item");
			//prevent helper from being selected if appendTo: selectable
			if (!selectee || selectee.element == that.element[0])
				return;
			var hit = false;
			if (options.tolerance == 'touch') {
				hit = ( !(selectee.left > x2 || selectee.right < x1 || selectee.top > y2 || selectee.bottom < y1) );
			} else if (options.tolerance == 'fit') {
				hit = (selectee.left > x1 && selectee.right < x2 && selectee.top > y1 && selectee.bottom < y2);
			}

			if (hit) {
				// SELECT
				if (selectee.selected) {
					selectee.$element.removeClass('ui-selected');
					selectee.selected = false;
				}
				if (selectee.unselecting) {
					selectee.$element.removeClass('ui-unselecting');
					selectee.unselecting = false;
				}
				if (!selectee.selecting) {
					selectee.$element.addClass('ui-selecting');
					selectee.selecting = true;
					// selectable SELECTING callback
					that._trigger("selecting", event, {
						selecting: selectee.element
					});
				}
			} else {
				// UNSELECT
				if (selectee.selecting) {
					if ((event.metaKey || event.ctrlKey) && selectee.startselected) {
						selectee.$element.removeClass('ui-selecting');
						selectee.selecting = false;
						selectee.$element.addClass('ui-selected');
						selectee.selected = true;
					} else {
						selectee.$element.removeClass('ui-selecting');
						selectee.selecting = false;
						if (selectee.startselected) {
							selectee.$element.addClass('ui-unselecting');
							selectee.unselecting = true;
						}
						// selectable UNSELECTING callback
						that._trigger("unselecting", event, {
							unselecting: selectee.element
						});
					}
				}
				if (selectee.selected) {
					if (!event.metaKey && !event.ctrlKey && !selectee.startselected) {
						selectee.$element.removeClass('ui-selected');
						selectee.selected = false;

						selectee.$element.addClass('ui-unselecting');
						selectee.unselecting = true;
						// selectable UNSELECTING callback
						that._trigger("unselecting", event, {
							unselecting: selectee.element
						});
					}
				}
			}
		});

		return false;
	},

	_mouseStop: function(event) {
		var that = this;

		this.dragged = false;

		var options = this.options;

		$('.ui-unselecting', this.element[0]).each(function() {
			var selectee = $.data(this, "selectable-item");
			selectee.$element.removeClass('ui-unselecting');
			selectee.unselecting = false;
			selectee.startselected = false;
			that._trigger("unselected", event, {
				unselected: selectee.element
			});
		});
		$('.ui-selecting', this.element[0]).each(function() {
			var selectee = $.data(this, "selectable-item");
			selectee.$element.removeClass('ui-selecting').addClass('ui-selected');
			selectee.selecting = false;
			selectee.selected = true;
			selectee.startselected = true;
			that._trigger("selected", event, {
				selected: selectee.element
			});
		});
		this._trigger("stop", event);

		this.helper.remove();

		return false;
	}

});

})(jQuery);
(function( $, undefined ) {

$.widget("ui.sortable", $.ui.mouse, {
	version: "1.9.2",
	widgetEventPrefix: "sort",
	ready: false,
	options: {
		appendTo: "parent",
		axis: false,
		connectWith: false,
		containment: false,
		cursor: 'auto',
		cursorAt: false,
		dropOnEmpty: true,
		forcePlaceholderSize: false,
		forceHelperSize: false,
		grid: false,
		handle: false,
		helper: "original",
		items: '> *',
		opacity: false,
		placeholder: false,
		revert: false,
		scroll: true,
		scrollSensitivity: 20,
		scrollSpeed: 20,
		scope: "default",
		tolerance: "intersect",
		zIndex: 1000
	},
	_create: function() {

		var o = this.options;
		this.containerCache = {};
		this.element.addClass("ui-sortable");

		//Get the items
		this.refresh();

		//Let's determine if the items are being displayed horizontally
		this.floating = this.items.length ? o.axis === 'x' || (/left|right/).test(this.items[0].item.css('float')) || (/inline|table-cell/).test(this.items[0].item.css('display')) : false;

		//Let's determine the parent's offset
		this.offset = this.element.offset();

		//Initialize mouse events for interaction
		this._mouseInit();

		//We're ready to go
		this.ready = true

	},

	_destroy: function() {
		this.element
			.removeClass("ui-sortable ui-sortable-disabled");
		this._mouseDestroy();

		for ( var i = this.items.length - 1; i >= 0; i-- )
			this.items[i].item.removeData(this.widgetName + "-item");

		return this;
	},

	_setOption: function(key, value){
		if ( key === "disabled" ) {
			this.options[ key ] = value;

			this.widget().toggleClass( "ui-sortable-disabled", !!value );
		} else {
			// Don't call widget base _setOption for disable as it adds ui-state-disabled class
			$.Widget.prototype._setOption.apply(this, arguments);
		}
	},

	_mouseCapture: function(event, overrideHandle) {
		var that = this;

		if (this.reverting) {
			return false;
		}

		if(this.options.disabled || this.options.type == 'static') return false;

		//We have to refresh the items data once first
		this._refreshItems(event);

		//Find out if the clicked node (or one of its parents) is a actual item in this.items
		var currentItem = null, nodes = $(event.target).parents().each(function() {
			if($.data(this, that.widgetName + '-item') == that) {
				currentItem = $(this);
				return false;
			}
		});
		if($.data(event.target, that.widgetName + '-item') == that) currentItem = $(event.target);

		if(!currentItem) return false;
		if(this.options.handle && !overrideHandle) {
			var validHandle = false;

			$(this.options.handle, currentItem).find("*").andSelf().each(function() { if(this == event.target) validHandle = true; });
			if(!validHandle) return false;
		}

		this.currentItem = currentItem;
		this._removeCurrentsFromItems();
		return true;

	},

	_mouseStart: function(event, overrideHandle, noActivation) {

		var o = this.options;
		this.currentContainer = this;

		//We only need to call refreshPositions, because the refreshItems call has been moved to mouseCapture
		this.refreshPositions();

		//Create and append the visible helper
		this.helper = this._createHelper(event);

		//Cache the helper size
		this._cacheHelperProportions();

		/*
		 * - Position generation -
		 * This block generates everything position related - it's the core of draggables.
		 */

		//Cache the margins of the original element
		this._cacheMargins();

		//Get the next scrolling parent
		this.scrollParent = this.helper.scrollParent();

		//The element's absolute position on the page minus margins
		this.offset = this.currentItem.offset();
		this.offset = {
			top: this.offset.top - this.margins.top,
			left: this.offset.left - this.margins.left
		};

		$.extend(this.offset, {
			click: { //Where the click happened, relative to the element
				left: event.pageX - this.offset.left,
				top: event.pageY - this.offset.top
			},
			parent: this._getParentOffset(),
			relative: this._getRelativeOffset() //This is a relative to absolute position minus the actual position calculation - only used for relative positioned helper
		});

		// Only after we got the offset, we can change the helper's position to absolute
		// TODO: Still need to figure out a way to make relative sorting possible
		this.helper.css("position", "absolute");
		this.cssPosition = this.helper.css("position");

		//Generate the original position
		this.originalPosition = this._generatePosition(event);
		this.originalPageX = event.pageX;
		this.originalPageY = event.pageY;

		//Adjust the mouse offset relative to the helper if 'cursorAt' is supplied
		(o.cursorAt && this._adjustOffsetFromHelper(o.cursorAt));

		//Cache the former DOM position
		this.domPosition = { prev: this.currentItem.prev()[0], parent: this.currentItem.parent()[0] };

		//If the helper is not the original, hide the original so it's not playing any role during the drag, won't cause anything bad this way
		if(this.helper[0] != this.currentItem[0]) {
			this.currentItem.hide();
		}

		//Create the placeholder
		this._createPlaceholder();

		//Set a containment if given in the options
		if(o.containment)
			this._setContainment();

		if(o.cursor) { // cursor option
			if ($('body').css("cursor")) this._storedCursor = $('body').css("cursor");
			$('body').css("cursor", o.cursor);
		}

		if(o.opacity) { // opacity option
			if (this.helper.css("opacity")) this._storedOpacity = this.helper.css("opacity");
			this.helper.css("opacity", o.opacity);
		}

		if(o.zIndex) { // zIndex option
			if (this.helper.css("zIndex")) this._storedZIndex = this.helper.css("zIndex");
			this.helper.css("zIndex", o.zIndex);
		}

		//Prepare scrolling
		if(this.scrollParent[0] != document && this.scrollParent[0].tagName != 'HTML')
			this.overflowOffset = this.scrollParent.offset();

		//Call callbacks
		this._trigger("start", event, this._uiHash());

		//Recache the helper size
		if(!this._preserveHelperProportions)
			this._cacheHelperProportions();


		//Post 'activate' events to possible containers
		if(!noActivation) {
			 for (var i = this.containers.length - 1; i >= 0; i--) { this.containers[i]._trigger("activate", event, this._uiHash(this)); }
		}

		//Prepare possible droppables
		if($.ui.ddmanager)
			$.ui.ddmanager.current = this;

		if ($.ui.ddmanager && !o.dropBehaviour)
			$.ui.ddmanager.prepareOffsets(this, event);

		this.dragging = true;

		this.helper.addClass("ui-sortable-helper");
		this._mouseDrag(event); //Execute the drag once - this causes the helper not to be visible before getting its correct position
		return true;

	},

	_mouseDrag: function(event) {

		//Compute the helpers position
		this.position = this._generatePosition(event);
		this.positionAbs = this._convertPositionTo("absolute");

		if (!this.lastPositionAbs) {
			this.lastPositionAbs = this.positionAbs;
		}

		//Do scrolling
		if(this.options.scroll) {
			var o = this.options, scrolled = false;
			if(this.scrollParent[0] != document && this.scrollParent[0].tagName != 'HTML') {

				if((this.overflowOffset.top + this.scrollParent[0].offsetHeight) - event.pageY < o.scrollSensitivity)
					this.scrollParent[0].scrollTop = scrolled = this.scrollParent[0].scrollTop + o.scrollSpeed;
				else if(event.pageY - this.overflowOffset.top < o.scrollSensitivity)
					this.scrollParent[0].scrollTop = scrolled = this.scrollParent[0].scrollTop - o.scrollSpeed;

				if((this.overflowOffset.left + this.scrollParent[0].offsetWidth) - event.pageX < o.scrollSensitivity)
					this.scrollParent[0].scrollLeft = scrolled = this.scrollParent[0].scrollLeft + o.scrollSpeed;
				else if(event.pageX - this.overflowOffset.left < o.scrollSensitivity)
					this.scrollParent[0].scrollLeft = scrolled = this.scrollParent[0].scrollLeft - o.scrollSpeed;

			} else {

				if(event.pageY - $(document).scrollTop() < o.scrollSensitivity)
					scrolled = $(document).scrollTop($(document).scrollTop() - o.scrollSpeed);
				else if($(window).height() - (event.pageY - $(document).scrollTop()) < o.scrollSensitivity)
					scrolled = $(document).scrollTop($(document).scrollTop() + o.scrollSpeed);

				if(event.pageX - $(document).scrollLeft() < o.scrollSensitivity)
					scrolled = $(document).scrollLeft($(document).scrollLeft() - o.scrollSpeed);
				else if($(window).width() - (event.pageX - $(document).scrollLeft()) < o.scrollSensitivity)
					scrolled = $(document).scrollLeft($(document).scrollLeft() + o.scrollSpeed);

			}

			if(scrolled !== false && $.ui.ddmanager && !o.dropBehaviour)
				$.ui.ddmanager.prepareOffsets(this, event);
		}

		//Regenerate the absolute position used for position checks
		this.positionAbs = this._convertPositionTo("absolute");

		//Set the helper position
		if(!this.options.axis || this.options.axis != "y") this.helper[0].style.left = this.position.left+'px';
		if(!this.options.axis || this.options.axis != "x") this.helper[0].style.top = this.position.top+'px';

		//Rearrange
		for (var i = this.items.length - 1; i >= 0; i--) {

			//Cache variables and intersection, continue if no intersection
			var item = this.items[i], itemElement = item.item[0], intersection = this._intersectsWithPointer(item);
			if (!intersection) continue;

			// Only put the placeholder inside the current Container, skip all
			// items form other containers. This works because when moving
			// an item from one container to another the
			// currentContainer is switched before the placeholder is moved.
			//
			// Without this moving items in "sub-sortables" can cause the placeholder to jitter
			// beetween the outer and inner container.
			if (item.instance !== this.currentContainer) continue;

			if (itemElement != this.currentItem[0] //cannot intersect with itself
				&&	this.placeholder[intersection == 1 ? "next" : "prev"]()[0] != itemElement //no useless actions that have been done before
				&&	!$.contains(this.placeholder[0], itemElement) //no action if the item moved is the parent of the item checked
				&& (this.options.type == 'semi-dynamic' ? !$.contains(this.element[0], itemElement) : true)
				//&& itemElement.parentNode == this.placeholder[0].parentNode // only rearrange items within the same container
			) {

				this.direction = intersection == 1 ? "down" : "up";

				if (this.options.tolerance == "pointer" || this._intersectsWithSides(item)) {
					this._rearrange(event, item);
				} else {
					break;
				}

				this._trigger("change", event, this._uiHash());
				break;
			}
		}

		//Post events to containers
		this._contactContainers(event);

		//Interconnect with droppables
		if($.ui.ddmanager) $.ui.ddmanager.drag(this, event);

		//Call callbacks
		this._trigger('sort', event, this._uiHash());

		this.lastPositionAbs = this.positionAbs;
		return false;

	},

	_mouseStop: function(event, noPropagation) {

		if(!event) return;

		//If we are using droppables, inform the manager about the drop
		if ($.ui.ddmanager && !this.options.dropBehaviour)
			$.ui.ddmanager.drop(this, event);

		if(this.options.revert) {
			var that = this;
			var cur = this.placeholder.offset();

			this.reverting = true;

			$(this.helper).animate({
				left: cur.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollLeft),
				top: cur.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollTop)
			}, parseInt(this.options.revert, 10) || 500, function() {
				that._clear(event);
			});
		} else {
			this._clear(event, noPropagation);
		}

		return false;

	},

	cancel: function() {

		if(this.dragging) {

			this._mouseUp({ target: null });

			if(this.options.helper == "original")
				this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
			else
				this.currentItem.show();

			//Post deactivating events to containers
			for (var i = this.containers.length - 1; i >= 0; i--){
				this.containers[i]._trigger("deactivate", null, this._uiHash(this));
				if(this.containers[i].containerCache.over) {
					this.containers[i]._trigger("out", null, this._uiHash(this));
					this.containers[i].containerCache.over = 0;
				}
			}

		}

		if (this.placeholder) {
			//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately, it unbinds ALL events from the original node!
			if(this.placeholder[0].parentNode) this.placeholder[0].parentNode.removeChild(this.placeholder[0]);
			if(this.options.helper != "original" && this.helper && this.helper[0].parentNode) this.helper.remove();

			$.extend(this, {
				helper: null,
				dragging: false,
				reverting: false,
				_noFinalSort: null
			});

			if(this.domPosition.prev) {
				$(this.domPosition.prev).after(this.currentItem);
			} else {
				$(this.domPosition.parent).prepend(this.currentItem);
			}
		}

		return this;

	},

	serialize: function(o) {

		var items = this._getItemsAsjQuery(o && o.connected);
		var str = []; o = o || {};

		$(items).each(function() {
			var res = ($(o.item || this).attr(o.attribute || 'id') || '').match(o.expression || (/(.+)[-=_](.+)/));
			if(res) str.push((o.key || res[1]+'[]')+'='+(o.key && o.expression ? res[1] : res[2]));
		});

		if(!str.length && o.key) {
			str.push(o.key + '=');
		}

		return str.join('&');

	},

	toArray: function(o) {

		var items = this._getItemsAsjQuery(o && o.connected);
		var ret = []; o = o || {};

		items.each(function() { ret.push($(o.item || this).attr(o.attribute || 'id') || ''); });
		return ret;

	},

	/* Be careful with the following core functions */
	_intersectsWith: function(item) {

		var x1 = this.positionAbs.left,
			x2 = x1 + this.helperProportions.width,
			y1 = this.positionAbs.top,
			y2 = y1 + this.helperProportions.height;

		var l = item.left,
			r = l + item.width,
			t = item.top,
			b = t + item.height;

		var dyClick = this.offset.click.top,
			dxClick = this.offset.click.left;

		var isOverElement = (y1 + dyClick) > t && (y1 + dyClick) < b && (x1 + dxClick) > l && (x1 + dxClick) < r;

		if(	   this.options.tolerance == "pointer"
			|| this.options.forcePointerForContainers
			|| (this.options.tolerance != "pointer" && this.helperProportions[this.floating ? 'width' : 'height'] > item[this.floating ? 'width' : 'height'])
		) {
			return isOverElement;
		} else {

			return (l < x1 + (this.helperProportions.width / 2) // Right Half
				&& x2 - (this.helperProportions.width / 2) < r // Left Half
				&& t < y1 + (this.helperProportions.height / 2) // Bottom Half
				&& y2 - (this.helperProportions.height / 2) < b ); // Top Half

		}
	},

	_intersectsWithPointer: function(item) {

		var isOverElementHeight = (this.options.axis === 'x') || $.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, item.top, item.height),
			isOverElementWidth = (this.options.axis === 'y') || $.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, item.left, item.width),
			isOverElement = isOverElementHeight && isOverElementWidth,
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if (!isOverElement)
			return false;

		return this.floating ?
			( ((horizontalDirection && horizontalDirection == "right") || verticalDirection == "down") ? 2 : 1 )
			: ( verticalDirection && (verticalDirection == "down" ? 2 : 1) );

	},

	_intersectsWithSides: function(item) {

		var isOverBottomHalf = $.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, item.top + (item.height/2), item.height),
			isOverRightHalf = $.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, item.left + (item.width/2), item.width),
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if (this.floating && horizontalDirection) {
			return ((horizontalDirection == "right" && isOverRightHalf) || (horizontalDirection == "left" && !isOverRightHalf));
		} else {
			return verticalDirection && ((verticalDirection == "down" && isOverBottomHalf) || (verticalDirection == "up" && !isOverBottomHalf));
		}

	},

	_getDragVerticalDirection: function() {
		var delta = this.positionAbs.top - this.lastPositionAbs.top;
		return delta != 0 && (delta > 0 ? "down" : "up");
	},

	_getDragHorizontalDirection: function() {
		var delta = this.positionAbs.left - this.lastPositionAbs.left;
		return delta != 0 && (delta > 0 ? "right" : "left");
	},

	refresh: function(event) {
		this._refreshItems(event);
		this.refreshPositions();
		return this;
	},

	_connectWith: function() {
		var options = this.options;
		return options.connectWith.constructor == String
			? [options.connectWith]
			: options.connectWith;
	},

	_getItemsAsjQuery: function(connected) {

		var items = [];
		var queries = [];
		var connectWith = this._connectWith();

		if(connectWith && connected) {
			for (var i = connectWith.length - 1; i >= 0; i--){
				var cur = $(connectWith[i]);
				for (var j = cur.length - 1; j >= 0; j--){
					var inst = $.data(cur[j], this.widgetName);
					if(inst && inst != this && !inst.options.disabled) {
						queries.push([$.isFunction(inst.options.items) ? inst.options.items.call(inst.element) : $(inst.options.items, inst.element).not(".ui-sortable-helper").not('.ui-sortable-placeholder'), inst]);
					}
				};
			};
		}

		queries.push([$.isFunction(this.options.items) ? this.options.items.call(this.element, null, { options: this.options, item: this.currentItem }) : $(this.options.items, this.element).not(".ui-sortable-helper").not('.ui-sortable-placeholder'), this]);

		for (var i = queries.length - 1; i >= 0; i--){
			queries[i][0].each(function() {
				items.push(this);
			});
		};

		return $(items);

	},

	_removeCurrentsFromItems: function() {

		var list = this.currentItem.find(":data(" + this.widgetName + "-item)");

		this.items = $.grep(this.items, function (item) {
			for (var j=0; j < list.length; j++) {
				if(list[j] == item.item[0])
					return false;
			};
			return true;
		});

	},

	_refreshItems: function(event) {

		this.items = [];
		this.containers = [this];
		var items = this.items;
		var queries = [[$.isFunction(this.options.items) ? this.options.items.call(this.element[0], event, { item: this.currentItem }) : $(this.options.items, this.element), this]];
		var connectWith = this._connectWith();

		if(connectWith && this.ready) { //Shouldn't be run the first time through due to massive slow-down
			for (var i = connectWith.length - 1; i >= 0; i--){
				var cur = $(connectWith[i]);
				for (var j = cur.length - 1; j >= 0; j--){
					var inst = $.data(cur[j], this.widgetName);
					if(inst && inst != this && !inst.options.disabled) {
						queries.push([$.isFunction(inst.options.items) ? inst.options.items.call(inst.element[0], event, { item: this.currentItem }) : $(inst.options.items, inst.element), inst]);
						this.containers.push(inst);
					}
				};
			};
		}

		for (var i = queries.length - 1; i >= 0; i--) {
			var targetData = queries[i][1];
			var _queries = queries[i][0];

			for (var j=0, queriesLength = _queries.length; j < queriesLength; j++) {
				var item = $(_queries[j]);

				item.data(this.widgetName + '-item', targetData); // Data for target checking (mouse manager)

				items.push({
					item: item,
					instance: targetData,
					width: 0, height: 0,
					left: 0, top: 0
				});
			};
		};

	},

	refreshPositions: function(fast) {

		//This has to be redone because due to the item being moved out/into the offsetParent, the offsetParent's position will change
		if(this.offsetParent && this.helper) {
			this.offset.parent = this._getParentOffset();
		}

		for (var i = this.items.length - 1; i >= 0; i--){
			var item = this.items[i];

			//We ignore calculating positions of all connected containers when we're not over them
			if(item.instance != this.currentContainer && this.currentContainer && item.item[0] != this.currentItem[0])
				continue;

			var t = this.options.toleranceElement ? $(this.options.toleranceElement, item.item) : item.item;

			if (!fast) {
				item.width = t.outerWidth();
				item.height = t.outerHeight();
			}

			var p = t.offset();
			item.left = p.left;
			item.top = p.top;
		};

		if(this.options.custom && this.options.custom.refreshContainers) {
			this.options.custom.refreshContainers.call(this);
		} else {
			for (var i = this.containers.length - 1; i >= 0; i--){
				var p = this.containers[i].element.offset();
				this.containers[i].containerCache.left = p.left;
				this.containers[i].containerCache.top = p.top;
				this.containers[i].containerCache.width	= this.containers[i].element.outerWidth();
				this.containers[i].containerCache.height = this.containers[i].element.outerHeight();
			};
		}

		return this;
	},

	_createPlaceholder: function(that) {
		that = that || this;
		var o = that.options;

		if(!o.placeholder || o.placeholder.constructor == String) {
			var className = o.placeholder;
			o.placeholder = {
				element: function() {

					var el = $(document.createElement(that.currentItem[0].nodeName))
						.addClass(className || that.currentItem[0].className+" ui-sortable-placeholder")
						.removeClass("ui-sortable-helper")[0];

					if(!className)
						el.style.visibility = "hidden";

					return el;
				},
				update: function(container, p) {

					// 1. If a className is set as 'placeholder option, we don't force sizes - the class is responsible for that
					// 2. The option 'forcePlaceholderSize can be enabled to force it even if a class name is specified
					if(className && !o.forcePlaceholderSize) return;

					//If the element doesn't have a actual height by itself (without styles coming from a stylesheet), it receives the inline height from the dragged item
					if(!p.height()) { p.height(that.currentItem.innerHeight() - parseInt(that.currentItem.css('paddingTop')||0, 10) - parseInt(that.currentItem.css('paddingBottom')||0, 10)); };
					if(!p.width()) { p.width(that.currentItem.innerWidth() - parseInt(that.currentItem.css('paddingLeft')||0, 10) - parseInt(that.currentItem.css('paddingRight')||0, 10)); };
				}
			};
		}

		//Create the placeholder
		that.placeholder = $(o.placeholder.element.call(that.element, that.currentItem));

		//Append it after the actual current item
		that.currentItem.after(that.placeholder);

		//Update the size of the placeholder (TODO: Logic to fuzzy, see line 316/317)
		o.placeholder.update(that, that.placeholder);

	},

	_contactContainers: function(event) {

		// get innermost container that intersects with item
		var innermostContainer = null, innermostIndex = null;


		for (var i = this.containers.length - 1; i >= 0; i--){

			// never consider a container that's located within the item itself
			if($.contains(this.currentItem[0], this.containers[i].element[0]))
				continue;

			if(this._intersectsWith(this.containers[i].containerCache)) {

				// if we've already found a container and it's more "inner" than this, then continue
				if(innermostContainer && $.contains(this.containers[i].element[0], innermostContainer.element[0]))
					continue;

				innermostContainer = this.containers[i];
				innermostIndex = i;

			} else {
				// container doesn't intersect. trigger "out" event if necessary
				if(this.containers[i].containerCache.over) {
					this.containers[i]._trigger("out", event, this._uiHash(this));
					this.containers[i].containerCache.over = 0;
				}
			}

		}

		// if no intersecting containers found, return
		if(!innermostContainer) return;

		// move the item into the container if it's not there already
		if(this.containers.length === 1) {
			this.containers[innermostIndex]._trigger("over", event, this._uiHash(this));
			this.containers[innermostIndex].containerCache.over = 1;
		} else {

			//When entering a new container, we will find the item with the least distance and append our item near it
			var dist = 10000; var itemWithLeastDistance = null;
			var posProperty = this.containers[innermostIndex].floating ? 'left' : 'top';
			var sizeProperty = this.containers[innermostIndex].floating ? 'width' : 'height';
			var base = this.positionAbs[posProperty] + this.offset.click[posProperty];
			for (var j = this.items.length - 1; j >= 0; j--) {
				if(!$.contains(this.containers[innermostIndex].element[0], this.items[j].item[0])) continue;
				if(this.items[j].item[0] == this.currentItem[0]) continue;
				var cur = this.items[j].item.offset()[posProperty];
				var nearBottom = false;
				if(Math.abs(cur - base) > Math.abs(cur + this.items[j][sizeProperty] - base)){
					nearBottom = true;
					cur += this.items[j][sizeProperty];
				}

				if(Math.abs(cur - base) < dist) {
					dist = Math.abs(cur - base); itemWithLeastDistance = this.items[j];
					this.direction = nearBottom ? "up": "down";
				}
			}

			if(!itemWithLeastDistance && !this.options.dropOnEmpty) //Check if dropOnEmpty is enabled
				return;

			this.currentContainer = this.containers[innermostIndex];
			itemWithLeastDistance ? this._rearrange(event, itemWithLeastDistance, null, true) : this._rearrange(event, null, this.containers[innermostIndex].element, true);
			this._trigger("change", event, this._uiHash());
			this.containers[innermostIndex]._trigger("change", event, this._uiHash(this));

			//Update the placeholder
			this.options.placeholder.update(this.currentContainer, this.placeholder);

			this.containers[innermostIndex]._trigger("over", event, this._uiHash(this));
			this.containers[innermostIndex].containerCache.over = 1;
		}


	},

	_createHelper: function(event) {

		var o = this.options;
		var helper = $.isFunction(o.helper) ? $(o.helper.apply(this.element[0], [event, this.currentItem])) : (o.helper == 'clone' ? this.currentItem.clone() : this.currentItem);

		if(!helper.parents('body').length) //Add the helper to the DOM if that didn't happen already
			$(o.appendTo != 'parent' ? o.appendTo : this.currentItem[0].parentNode)[0].appendChild(helper[0]);

		if(helper[0] == this.currentItem[0])
			this._storedCSS = { width: this.currentItem[0].style.width, height: this.currentItem[0].style.height, position: this.currentItem.css("position"), top: this.currentItem.css("top"), left: this.currentItem.css("left") };

		if(helper[0].style.width == '' || o.forceHelperSize) helper.width(this.currentItem.width());
		if(helper[0].style.height == '' || o.forceHelperSize) helper.height(this.currentItem.height());

		return helper;

	},

	_adjustOffsetFromHelper: function(obj) {
		if (typeof obj == 'string') {
			obj = obj.split(' ');
		}
		if ($.isArray(obj)) {
			obj = {left: +obj[0], top: +obj[1] || 0};
		}
		if ('left' in obj) {
			this.offset.click.left = obj.left + this.margins.left;
		}
		if ('right' in obj) {
			this.offset.click.left = this.helperProportions.width - obj.right + this.margins.left;
		}
		if ('top' in obj) {
			this.offset.click.top = obj.top + this.margins.top;
		}
		if ('bottom' in obj) {
			this.offset.click.top = this.helperProportions.height - obj.bottom + this.margins.top;
		}
	},

	_getParentOffset: function() {


		//Get the offsetParent and cache its position
		this.offsetParent = this.helper.offsetParent();
		var po = this.offsetParent.offset();

		// This is a special case where we need to modify a offset calculated on start, since the following happened:
		// 1. The position of the helper is absolute, so it's position is calculated based on the next positioned parent
		// 2. The actual offset parent is a child of the scroll parent, and the scroll parent isn't the document, which means that
		//    the scroll is included in the initial calculation of the offset of the parent, and never recalculated upon drag
		if(this.cssPosition == 'absolute' && this.scrollParent[0] != document && $.contains(this.scrollParent[0], this.offsetParent[0])) {
			po.left += this.scrollParent.scrollLeft();
			po.top += this.scrollParent.scrollTop();
		}

		if((this.offsetParent[0] == document.body) //This needs to be actually done for all browsers, since pageX/pageY includes this information
		|| (this.offsetParent[0].tagName && this.offsetParent[0].tagName.toLowerCase() == 'html' && $.ui.ie)) //Ugly IE fix
			po = { top: 0, left: 0 };

		return {
			top: po.top + (parseInt(this.offsetParent.css("borderTopWidth"),10) || 0),
			left: po.left + (parseInt(this.offsetParent.css("borderLeftWidth"),10) || 0)
		};

	},

	_getRelativeOffset: function() {

		if(this.cssPosition == "relative") {
			var p = this.currentItem.position();
			return {
				top: p.top - (parseInt(this.helper.css("top"),10) || 0) + this.scrollParent.scrollTop(),
				left: p.left - (parseInt(this.helper.css("left"),10) || 0) + this.scrollParent.scrollLeft()
			};
		} else {
			return { top: 0, left: 0 };
		}

	},

	_cacheMargins: function() {
		this.margins = {
			left: (parseInt(this.currentItem.css("marginLeft"),10) || 0),
			top: (parseInt(this.currentItem.css("marginTop"),10) || 0)
		};
	},

	_cacheHelperProportions: function() {
		this.helperProportions = {
			width: this.helper.outerWidth(),
			height: this.helper.outerHeight()
		};
	},

	_setContainment: function() {

		var o = this.options;
		if(o.containment == 'parent') o.containment = this.helper[0].parentNode;
		if(o.containment == 'document' || o.containment == 'window') this.containment = [
			0 - this.offset.relative.left - this.offset.parent.left,
			0 - this.offset.relative.top - this.offset.parent.top,
			$(o.containment == 'document' ? document : window).width() - this.helperProportions.width - this.margins.left,
			($(o.containment == 'document' ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top
		];

		if(!(/^(document|window|parent)$/).test(o.containment)) {
			var ce = $(o.containment)[0];
			var co = $(o.containment).offset();
			var over = ($(ce).css("overflow") != 'hidden');

			this.containment = [
				co.left + (parseInt($(ce).css("borderLeftWidth"),10) || 0) + (parseInt($(ce).css("paddingLeft"),10) || 0) - this.margins.left,
				co.top + (parseInt($(ce).css("borderTopWidth"),10) || 0) + (parseInt($(ce).css("paddingTop"),10) || 0) - this.margins.top,
				co.left+(over ? Math.max(ce.scrollWidth,ce.offsetWidth) : ce.offsetWidth) - (parseInt($(ce).css("borderLeftWidth"),10) || 0) - (parseInt($(ce).css("paddingRight"),10) || 0) - this.helperProportions.width - this.margins.left,
				co.top+(over ? Math.max(ce.scrollHeight,ce.offsetHeight) : ce.offsetHeight) - (parseInt($(ce).css("borderTopWidth"),10) || 0) - (parseInt($(ce).css("paddingBottom"),10) || 0) - this.helperProportions.height - this.margins.top
			];
		}

	},

	_convertPositionTo: function(d, pos) {

		if(!pos) pos = this.position;
		var mod = d == "absolute" ? 1 : -1;
		var o = this.options, scroll = this.cssPosition == 'absolute' && !(this.scrollParent[0] != document && $.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		return {
			top: (
				pos.top																	// The absolute mouse position
				+ this.offset.relative.top * mod										// Only for relative positioned nodes: Relative offset from element to offset parent
				+ this.offset.parent.top * mod											// The offsetParent's offset without borders (offset + border)
				- ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ) * mod)
			),
			left: (
				pos.left																// The absolute mouse position
				+ this.offset.relative.left * mod										// Only for relative positioned nodes: Relative offset from element to offset parent
				+ this.offset.parent.left * mod											// The offsetParent's offset without borders (offset + border)
				- ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ) * mod)
			)
		};

	},

	_generatePosition: function(event) {

		var o = this.options, scroll = this.cssPosition == 'absolute' && !(this.scrollParent[0] != document && $.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		// This is another very weird special case that only happens for relative elements:
		// 1. If the css position is relative
		// 2. and the scroll parent is the document or similar to the offset parent
		// we have to refresh the relative offset during the scroll so there are no jumps
		if(this.cssPosition == 'relative' && !(this.scrollParent[0] != document && this.scrollParent[0] != this.offsetParent[0])) {
			this.offset.relative = this._getRelativeOffset();
		}

		var pageX = event.pageX;
		var pageY = event.pageY;

		/*
		 * - Position constraining -
		 * Constrain the position to a mix of grid, containment.
		 */

		if(this.originalPosition) { //If we are not dragging yet, we won't check for options

			if(this.containment) {
				if(event.pageX - this.offset.click.left < this.containment[0]) pageX = this.containment[0] + this.offset.click.left;
				if(event.pageY - this.offset.click.top < this.containment[1]) pageY = this.containment[1] + this.offset.click.top;
				if(event.pageX - this.offset.click.left > this.containment[2]) pageX = this.containment[2] + this.offset.click.left;
				if(event.pageY - this.offset.click.top > this.containment[3]) pageY = this.containment[3] + this.offset.click.top;
			}

			if(o.grid) {
				var top = this.originalPageY + Math.round((pageY - this.originalPageY) / o.grid[1]) * o.grid[1];
				pageY = this.containment ? (!(top - this.offset.click.top < this.containment[1] || top - this.offset.click.top > this.containment[3]) ? top : (!(top - this.offset.click.top < this.containment[1]) ? top - o.grid[1] : top + o.grid[1])) : top;

				var left = this.originalPageX + Math.round((pageX - this.originalPageX) / o.grid[0]) * o.grid[0];
				pageX = this.containment ? (!(left - this.offset.click.left < this.containment[0] || left - this.offset.click.left > this.containment[2]) ? left : (!(left - this.offset.click.left < this.containment[0]) ? left - o.grid[0] : left + o.grid[0])) : left;
			}

		}

		return {
			top: (
				pageY																// The absolute mouse position
				- this.offset.click.top													// Click offset (relative to the element)
				- this.offset.relative.top												// Only for relative positioned nodes: Relative offset from element to offset parent
				- this.offset.parent.top												// The offsetParent's offset without borders (offset + border)
				+ ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ))
			),
			left: (
				pageX																// The absolute mouse position
				- this.offset.click.left												// Click offset (relative to the element)
				- this.offset.relative.left												// Only for relative positioned nodes: Relative offset from element to offset parent
				- this.offset.parent.left												// The offsetParent's offset without borders (offset + border)
				+ ( ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ))
			)
		};

	},

	_rearrange: function(event, i, a, hardRefresh) {

		a ? a[0].appendChild(this.placeholder[0]) : i.item[0].parentNode.insertBefore(this.placeholder[0], (this.direction == 'down' ? i.item[0] : i.item[0].nextSibling));

		//Various things done here to improve the performance:
		// 1. we create a setTimeout, that calls refreshPositions
		// 2. on the instance, we have a counter variable, that get's higher after every append
		// 3. on the local scope, we copy the counter variable, and check in the timeout, if it's still the same
		// 4. this lets only the last addition to the timeout stack through
		this.counter = this.counter ? ++this.counter : 1;
		var counter = this.counter;

		this._delay(function() {
			if(counter == this.counter) this.refreshPositions(!hardRefresh); //Precompute after each DOM insertion, NOT on mousemove
		});

	},

	_clear: function(event, noPropagation) {

		this.reverting = false;
		// We delay all events that have to be triggered to after the point where the placeholder has been removed and
		// everything else normalized again
		var delayedTriggers = [];

		// We first have to update the dom position of the actual currentItem
		// Note: don't do it if the current item is already removed (by a user), or it gets reappended (see #4088)
		if(!this._noFinalSort && this.currentItem.parent().length) this.placeholder.before(this.currentItem);
		this._noFinalSort = null;

		if(this.helper[0] == this.currentItem[0]) {
			for(var i in this._storedCSS) {
				if(this._storedCSS[i] == 'auto' || this._storedCSS[i] == 'static') this._storedCSS[i] = '';
			}
			this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
		} else {
			this.currentItem.show();
		}

		if(this.fromOutside && !noPropagation) delayedTriggers.push(function(event) { this._trigger("receive", event, this._uiHash(this.fromOutside)); });
		if((this.fromOutside || this.domPosition.prev != this.currentItem.prev().not(".ui-sortable-helper")[0] || this.domPosition.parent != this.currentItem.parent()[0]) && !noPropagation) delayedTriggers.push(function(event) { this._trigger("update", event, this._uiHash()); }); //Trigger update callback if the DOM position has changed

		// Check if the items Container has Changed and trigger appropriate
		// events.
		if (this !== this.currentContainer) {
			if(!noPropagation) {
				delayedTriggers.push(function(event) { this._trigger("remove", event, this._uiHash()); });
				delayedTriggers.push((function(c) { return function(event) { c._trigger("receive", event, this._uiHash(this)); };  }).call(this, this.currentContainer));
				delayedTriggers.push((function(c) { return function(event) { c._trigger("update", event, this._uiHash(this));  }; }).call(this, this.currentContainer));
			}
		}


		//Post events to containers
		for (var i = this.containers.length - 1; i >= 0; i--){
			if(!noPropagation) delayedTriggers.push((function(c) { return function(event) { c._trigger("deactivate", event, this._uiHash(this)); };  }).call(this, this.containers[i]));
			if(this.containers[i].containerCache.over) {
				delayedTriggers.push((function(c) { return function(event) { c._trigger("out", event, this._uiHash(this)); };  }).call(this, this.containers[i]));
				this.containers[i].containerCache.over = 0;
			}
		}

		//Do what was originally in plugins
		if(this._storedCursor) $('body').css("cursor", this._storedCursor); //Reset cursor
		if(this._storedOpacity) this.helper.css("opacity", this._storedOpacity); //Reset opacity
		if(this._storedZIndex) this.helper.css("zIndex", this._storedZIndex == 'auto' ? '' : this._storedZIndex); //Reset z-index

		this.dragging = false;
		if(this.cancelHelperRemoval) {
			if(!noPropagation) {
				this._trigger("beforeStop", event, this._uiHash());
				for (var i=0; i < delayedTriggers.length; i++) { delayedTriggers[i].call(this, event); }; //Trigger all delayed events
				this._trigger("stop", event, this._uiHash());
			}

			this.fromOutside = false;
			return false;
		}

		if(!noPropagation) this._trigger("beforeStop", event, this._uiHash());

		//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately, it unbinds ALL events from the original node!
		this.placeholder[0].parentNode.removeChild(this.placeholder[0]);

		if(this.helper[0] != this.currentItem[0]) this.helper.remove(); this.helper = null;

		if(!noPropagation) {
			for (var i=0; i < delayedTriggers.length; i++) { delayedTriggers[i].call(this, event); }; //Trigger all delayed events
			this._trigger("stop", event, this._uiHash());
		}

		this.fromOutside = false;
		return true;

	},

	_trigger: function() {
		if ($.Widget.prototype._trigger.apply(this, arguments) === false) {
			this.cancel();
		}
	},

	_uiHash: function(_inst) {
		var inst = _inst || this;
		return {
			helper: inst.helper,
			placeholder: inst.placeholder || $([]),
			position: inst.position,
			originalPosition: inst.originalPosition,
			offset: inst.positionAbs,
			item: inst.currentItem,
			sender: _inst ? _inst.element : null
		};
	}

});

})(jQuery);
(function( $ ) {

var increments = 0;

function addDescribedBy( elem, id ) {
	var describedby = (elem.attr( "aria-describedby" ) || "").split( /\s+/ );
	describedby.push( id );
	elem
		.data( "ui-tooltip-id", id )
		.attr( "aria-describedby", $.trim( describedby.join( " " ) ) );
}

function removeDescribedBy( elem ) {
	var id = elem.data( "ui-tooltip-id" ),
		describedby = (elem.attr( "aria-describedby" ) || "").split( /\s+/ ),
		index = $.inArray( id, describedby );
	if ( index !== -1 ) {
		describedby.splice( index, 1 );
	}

	elem.removeData( "ui-tooltip-id" );
	describedby = $.trim( describedby.join( " " ) );
	if ( describedby ) {
		elem.attr( "aria-describedby", describedby );
	} else {
		elem.removeAttr( "aria-describedby" );
	}
}

$.widget( "ui.tooltip", {
	version: "1.9.2",
	options: {
		content: function() {
			return $( this ).attr( "title" );
		},
		hide: true,
		// Disabled elements have inconsistent behavior across browsers (#8661)
		items: "[title]:not([disabled])",
		position: {
			my: "left top+15",
			at: "left bottom",
			collision: "flipfit flip"
		},
		show: true,
		tooltipClass: null,
		track: false,

		// callbacks
		close: null,
		open: null
	},

	_create: function() {
		this._on({
			mouseover: "open",
			focusin: "open"
		});

		// IDs of generated tooltips, needed for destroy
		this.tooltips = {};
		// IDs of parent tooltips where we removed the title attribute
		this.parents = {};

		if ( this.options.disabled ) {
			this._disable();
		}
	},

	_setOption: function( key, value ) {
		var that = this;

		if ( key === "disabled" ) {
			this[ value ? "_disable" : "_enable" ]();
			this.options[ key ] = value;
			// disable element style changes
			return;
		}

		this._super( key, value );

		if ( key === "content" ) {
			$.each( this.tooltips, function( id, element ) {
				that._updateContent( element );
			});
		}
	},

	_disable: function() {
		var that = this;

		// close open tooltips
		$.each( this.tooltips, function( id, element ) {
			var event = $.Event( "blur" );
			event.target = event.currentTarget = element[0];
			that.close( event, true );
		});

		// remove title attributes to prevent native tooltips
		this.element.find( this.options.items ).andSelf().each(function() {
			var element = $( this );
			if ( element.is( "[title]" ) ) {
				element
					.data( "ui-tooltip-title", element.attr( "title" ) )
					.attr( "title", "" );
			}
		});
	},

	_enable: function() {
		// restore title attributes
		this.element.find( this.options.items ).andSelf().each(function() {
			var element = $( this );
			if ( element.data( "ui-tooltip-title" ) ) {
				element.attr( "title", element.data( "ui-tooltip-title" ) );
			}
		});
	},

	open: function( event ) {
		var that = this,
			target = $( event ? event.target : this.element )
				// we need closest here due to mouseover bubbling,
				// but always pointing at the same event target
				.closest( this.options.items );

		// No element to show a tooltip for or the tooltip is already open
		if ( !target.length || target.data( "ui-tooltip-id" ) ) {
			return;
		}

		if ( target.attr( "title" ) ) {
			target.data( "ui-tooltip-title", target.attr( "title" ) );
		}

		target.data( "ui-tooltip-open", true );

		// kill parent tooltips, custom or native, for hover
		if ( event && event.type === "mouseover" ) {
			target.parents().each(function() {
				var parent = $( this ),
					blurEvent;
				if ( parent.data( "ui-tooltip-open" ) ) {
					blurEvent = $.Event( "blur" );
					blurEvent.target = blurEvent.currentTarget = this;
					that.close( blurEvent, true );
				}
				if ( parent.attr( "title" ) ) {
					parent.uniqueId();
					that.parents[ this.id ] = {
						element: this,
						title: parent.attr( "title" )
					};
					parent.attr( "title", "" );
				}
			});
		}

		this._updateContent( target, event );
	},

	_updateContent: function( target, event ) {
		var content,
			contentOption = this.options.content,
			that = this,
			eventType = event ? event.type : null;

		if ( typeof contentOption === "string" ) {
			return this._open( event, target, contentOption );
		}

		content = contentOption.call( target[0], function( response ) {
			// ignore async response if tooltip was closed already
			if ( !target.data( "ui-tooltip-open" ) ) {
				return;
			}
			// IE may instantly serve a cached response for ajax requests
			// delay this call to _open so the other call to _open runs first
			that._delay(function() {
				// jQuery creates a special event for focusin when it doesn't
				// exist natively. To improve performance, the native event
				// object is reused and the type is changed. Therefore, we can't
				// rely on the type being correct after the event finished
				// bubbling, so we set it back to the previous value. (#8740)
				if ( event ) {
					event.type = eventType;
				}
				this._open( event, target, response );
			});
		});
		if ( content ) {
			this._open( event, target, content );
		}
	},

	_open: function( event, target, content ) {
		var tooltip, events, delayedShow,
			positionOption = $.extend( {}, this.options.position );

		if ( !content ) {
			return;
		}

		// Content can be updated multiple times. If the tooltip already
		// exists, then just update the content and bail.
		tooltip = this._find( target );
		if ( tooltip.length ) {
			tooltip.find( ".ui-tooltip-content" ).html( content );
			return;
		}

		// if we have a title, clear it to prevent the native tooltip
		// we have to check first to avoid defining a title if none exists
		// (we don't want to cause an element to start matching [title])
		//
		// We use removeAttr only for key events, to allow IE to export the correct
		// accessible attributes. For mouse events, set to empty string to avoid
		// native tooltip showing up (happens only when removing inside mouseover).
		if ( target.is( "[title]" ) ) {
			if ( event && event.type === "mouseover" ) {
				target.attr( "title", "" );
			} else {
				target.removeAttr( "title" );
			}
		}

		tooltip = this._tooltip( target );
		addDescribedBy( target, tooltip.attr( "id" ) );
		tooltip.find( ".ui-tooltip-content" ).html( content );

		function position( event ) {
			positionOption.of = event;
			if ( tooltip.is( ":hidden" ) ) {
				return;
			}
			tooltip.position( positionOption );
		}
		if ( this.options.track && event && /^mouse/.test( event.type ) ) {
			this._on( this.document, {
				mousemove: position
			});
			// trigger once to override element-relative positioning
			position( event );
		} else {
			tooltip.position( $.extend({
				of: target
			}, this.options.position ) );
		}

		tooltip.hide();

		this._show( tooltip, this.options.show );
		// Handle tracking tooltips that are shown with a delay (#8644). As soon
		// as the tooltip is visible, position the tooltip using the most recent
		// event.
		if ( this.options.show && this.options.show.delay ) {
			delayedShow = setInterval(function() {
				if ( tooltip.is( ":visible" ) ) {
					position( positionOption.of );
					clearInterval( delayedShow );
				}
			}, $.fx.interval );
		}

		this._trigger( "open", event, { tooltip: tooltip } );

		events = {
			keyup: function( event ) {
				if ( event.keyCode === $.ui.keyCode.ESCAPE ) {
					var fakeEvent = $.Event(event);
					fakeEvent.currentTarget = target[0];
					this.close( fakeEvent, true );
				}
			},
			remove: function() {
				this._removeTooltip( tooltip );
			}
		};
		if ( !event || event.type === "mouseover" ) {
			events.mouseleave = "close";
		}
		if ( !event || event.type === "focusin" ) {
			events.focusout = "close";
		}
		this._on( true, target, events );
	},

	close: function( event ) {
		var that = this,
			target = $( event ? event.currentTarget : this.element ),
			tooltip = this._find( target );

		// disabling closes the tooltip, so we need to track when we're closing
		// to avoid an infinite loop in case the tooltip becomes disabled on close
		if ( this.closing ) {
			return;
		}

		// only set title if we had one before (see comment in _open())
		if ( target.data( "ui-tooltip-title" ) ) {
			target.attr( "title", target.data( "ui-tooltip-title" ) );
		}

		removeDescribedBy( target );

		tooltip.stop( true );
		this._hide( tooltip, this.options.hide, function() {
			that._removeTooltip( $( this ) );
		});

		target.removeData( "ui-tooltip-open" );
		this._off( target, "mouseleave focusout keyup" );
		// Remove 'remove' binding only on delegated targets
		if ( target[0] !== this.element[0] ) {
			this._off( target, "remove" );
		}
		this._off( this.document, "mousemove" );

		if ( event && event.type === "mouseleave" ) {
			$.each( this.parents, function( id, parent ) {
				$( parent.element ).attr( "title", parent.title );
				delete that.parents[ id ];
			});
		}

		this.closing = true;
		this._trigger( "close", event, { tooltip: tooltip } );
		this.closing = false;
	},

	_tooltip: function( element ) {
		var id = "ui-tooltip-" + increments++,
			tooltip = $( "<div>" )
				.attr({
					id: id,
					role: "tooltip"
				})
				.addClass( "ui-tooltip ui-widget ui-corner-all ui-widget-content " +
					( this.options.tooltipClass || "" ) );
		$( "<div>" )
			.addClass( "ui-tooltip-content" )
			.appendTo( tooltip );
		tooltip.appendTo( this.document[0].body );
		if ( $.fn.bgiframe ) {
			tooltip.bgiframe();
		}
		this.tooltips[ id ] = element;
		return tooltip;
	},

	_find: function( target ) {
		var id = target.data( "ui-tooltip-id" );
		return id ? $( "#" + id ) : $();
	},

	_removeTooltip: function( tooltip ) {
		tooltip.remove();
		delete this.tooltips[ tooltip.attr( "id" ) ];
	},

	_destroy: function() {
		var that = this;

		// close open tooltips
		$.each( this.tooltips, function( id, element ) {
			// Delegate to close method to handle common cleanup
			var event = $.Event( "blur" );
			event.target = event.currentTarget = element[0];
			that.close( event, true );

			// Remove immediately; destroying an open tooltip doesn't use the
			// hide animation
			$( "#" + id ).remove();

			// Restore the title
			if ( element.data( "ui-tooltip-title" ) ) {
				element.attr( "title", element.data( "ui-tooltip-title" ) );
				element.removeData( "ui-tooltip-title" );
			}
		});
	}
});

}( jQuery ) );
/*  Copyright Mihai Bazon, 2002-2005  |  www.bazon.net/mishoo
 * -----------------------------------------------------------
 *
 * The DHTML Calendar, version 1.0 "It is happening again"
 *
 * Details and latest version at:
 * www.dynarch.com/projects/calendar
 *
 * This script is developed by Dynarch.com.  Visit us at www.dynarch.com.
 *
 * This script is distributed under the GNU Lesser General Public License.
 * Read the entire license text here: http://www.gnu.org/licenses/lgpl.html
 */

// $Id: calendar.js,v 1.51 2005/03/07 16:44:31 mishoo Exp $

/** The Calendar object constructor. */
Calendar = function (firstDayOfWeek, dateStr, onSelected, onClose) {
	// member variables
	this.activeDiv = null;
	this.currentDateEl = null;
	this.getDateStatus = null;
	this.getDateToolTip = null;
	this.getDateText = null;
	this.timeout = null;
	this.onSelected = onSelected || null;
	this.onClose = onClose || null;
	this.dragging = false;
	this.hidden = false;
	this.minYear = 1970;
	this.maxYear = 2050;
	this.dateFormat = Calendar._TT["DEF_DATE_FORMAT"];
	this.ttDateFormat = Calendar._TT["TT_DATE_FORMAT"];
	this.isPopup = true;
	this.weekNumbers = true;
	this.firstDayOfWeek = typeof firstDayOfWeek == "number" ? firstDayOfWeek : Calendar._FD; // 0 for Sunday, 1 for Monday, etc.
	this.showsOtherMonths = false;
	this.dateStr = dateStr;
	this.ar_days = null;
	this.showsTime = false;
	this.time24 = true;
	this.yearStep = 2;
	this.hiliteToday = true;
	this.multiple = null;
	// HTML elements
	this.table = null;
	this.element = null;
	this.tbody = null;
	this.firstdayname = null;
	// Combo boxes
	this.monthsCombo = null;
	this.yearsCombo = null;
	this.hilitedMonth = null;
	this.activeMonth = null;
	this.hilitedYear = null;
	this.activeYear = null;
	// Information
	this.dateClicked = false;

	// one-time initializations
	if (typeof Calendar._SDN == "undefined") {
		// table of short day names
		if (typeof Calendar._SDN_len == "undefined")
			Calendar._SDN_len = 3;
		var ar = new Array();
		for (var i = 8; i > 0;) {
			ar[--i] = Calendar._DN[i].substr(0, Calendar._SDN_len);
		}
		Calendar._SDN = ar;
		// table of short month names
		if (typeof Calendar._SMN_len == "undefined")
			Calendar._SMN_len = 3;
		ar = new Array();
		for (var i = 12; i > 0;) {
			ar[--i] = Calendar._MN[i].substr(0, Calendar._SMN_len);
		}
		Calendar._SMN = ar;
	}
};

// ** constants

/// "static", needed for event handlers.
Calendar._C = null;

/// detect a special case of "web browser"
Calendar.is_ie = ( /msie/i.test(navigator.userAgent) &&
		   !/opera/i.test(navigator.userAgent) );

Calendar.is_ie5 = ( Calendar.is_ie && /msie 5\.0/i.test(navigator.userAgent) );

/// detect Opera browser
Calendar.is_opera = /opera/i.test(navigator.userAgent);

/// detect KHTML-based browsers
Calendar.is_khtml = /Konqueror|Safari|KHTML/i.test(navigator.userAgent);

// BEGIN: UTILITY FUNCTIONS; beware that these might be moved into a separate
//        library, at some point.

Calendar.getAbsolutePos = function(el) {
	var SL = 0, ST = 0;
	var is_div = /^div$/i.test(el.tagName);
	if (is_div && el.scrollLeft)
		SL = el.scrollLeft;
	if (is_div && el.scrollTop)
		ST = el.scrollTop;
	var r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
	if (el.offsetParent) {
		var tmp = this.getAbsolutePos(el.offsetParent);
		r.x += tmp.x;
		r.y += tmp.y;
	}
	return r;
};

Calendar.isRelated = function (el, evt) {
	var related = evt.relatedTarget;
	if (!related) {
		var type = evt.type;
		if (type == "mouseover") {
			related = evt.fromElement;
		} else if (type == "mouseout") {
			related = evt.toElement;
		}
	}
	while (related) {
		if (related == el) {
			return true;
		}
		related = related.parentNode;
	}
	return false;
};

Calendar.removeClass = function(el, className) {
	if (!(el && el.className)) {
		return;
	}
	var cls = el.className.split(" ");
	var ar = new Array();
	for (var i = cls.length; i > 0;) {
		if (cls[--i] != className) {
			ar[ar.length] = cls[i];
		}
	}
	el.className = ar.join(" ");
};

Calendar.addClass = function(el, className) {
	Calendar.removeClass(el, className);
	el.className += " " + className;
};

// FIXME: the following 2 functions totally suck, are useless and should be replaced immediately.
Calendar.getElement = function(ev) {
	var f = Calendar.is_ie ? window.event.srcElement : ev.currentTarget;
	while (f.nodeType != 1 || /^div$/i.test(f.tagName))
		f = f.parentNode;
	return f;
};

Calendar.getTargetElement = function(ev) {
	var f = Calendar.is_ie ? window.event.srcElement : ev.target;
	while (f.nodeType != 1)
		f = f.parentNode;
	return f;
};

Calendar.stopEvent = function(ev) {
	ev || (ev = window.event);
	if (Calendar.is_ie) {
		ev.cancelBubble = true;
		ev.returnValue = false;
	} else {
		ev.preventDefault();
		ev.stopPropagation();
	}
	return false;
};

Calendar.addEvent = function(el, evname, func) {
	if (el.attachEvent) { // IE
		el.attachEvent("on" + evname, func);
	} else if (el.addEventListener) { // Gecko / W3C
		el.addEventListener(evname, func, true);
	} else {
		el["on" + evname] = func;
	}
};

Calendar.removeEvent = function(el, evname, func) {
	if (el.detachEvent) { // IE
		el.detachEvent("on" + evname, func);
	} else if (el.removeEventListener) { // Gecko / W3C
		el.removeEventListener(evname, func, true);
	} else {
		el["on" + evname] = null;
	}
};

Calendar.createElement = function(type, parent) {
	var el = null;
	if (document.createElementNS) {
		// use the XHTML namespace; IE won't normally get here unless
		// _they_ "fix" the DOM2 implementation.
		el = document.createElementNS("http://www.w3.org/1999/xhtml", type);
	} else {
		el = document.createElement(type);
	}
	if (typeof parent != "undefined") {
		parent.appendChild(el);
	}
	return el;
};

// END: UTILITY FUNCTIONS

// BEGIN: CALENDAR STATIC FUNCTIONS

/** Internal -- adds a set of events to make some element behave like a button. */
Calendar._add_evs = function(el) {
	with (Calendar) {
		addEvent(el, "mouseover", dayMouseOver);
		addEvent(el, "mousedown", dayMouseDown);
		addEvent(el, "mouseout", dayMouseOut);
		if (is_ie) {
			addEvent(el, "dblclick", dayMouseDblClick);
			el.setAttribute("unselectable", true);
		}
	}
};

Calendar.findMonth = function(el) {
	if (typeof el.month != "undefined") {
		return el;
	} else if (typeof el.parentNode.month != "undefined") {
		return el.parentNode;
	}
	return null;
};

Calendar.findYear = function(el) {
	if (typeof el.year != "undefined") {
		return el;
	} else if (typeof el.parentNode.year != "undefined") {
		return el.parentNode;
	}
	return null;
};

Calendar.showMonthsCombo = function () {
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	var cal = cal;
	var cd = cal.activeDiv;
	var mc = cal.monthsCombo;
	if (cal.hilitedMonth) {
		Calendar.removeClass(cal.hilitedMonth, "hilite");
	}
	if (cal.activeMonth) {
		Calendar.removeClass(cal.activeMonth, "active");
	}
	var mon = cal.monthsCombo.getElementsByTagName("div")[cal.date.getMonth()];
	Calendar.addClass(mon, "active");
	cal.activeMonth = mon;
	var s = mc.style;
	s.display = "block";
	if (cd.navtype < 0)
		s.left = cd.offsetLeft + "px";
	else {
		var mcw = mc.offsetWidth;
		if (typeof mcw == "undefined")
			// Konqueror brain-dead techniques
			mcw = 50;
		s.left = (cd.offsetLeft + cd.offsetWidth - mcw) + "px";
	}
	s.top = (cd.offsetTop + cd.offsetHeight) + "px";
};

Calendar.showYearsCombo = function (fwd) {
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	var cal = cal;
	var cd = cal.activeDiv;
	var yc = cal.yearsCombo;
	if (cal.hilitedYear) {
		Calendar.removeClass(cal.hilitedYear, "hilite");
	}
	if (cal.activeYear) {
		Calendar.removeClass(cal.activeYear, "active");
	}
	cal.activeYear = null;
	var Y = cal.date.getFullYear() + (fwd ? 1 : -1);
	var yr = yc.firstChild;
	var show = false;
	for (var i = 12; i > 0; --i) {
		if (Y >= cal.minYear && Y <= cal.maxYear) {
			yr.innerHTML = Y;
			yr.year = Y;
			yr.style.display = "block";
			show = true;
		} else {
			yr.style.display = "none";
		}
		yr = yr.nextSibling;
		Y += fwd ? cal.yearStep : -cal.yearStep;
	}
	if (show) {
		var s = yc.style;
		s.display = "block";
		if (cd.navtype < 0)
			s.left = cd.offsetLeft + "px";
		else {
			var ycw = yc.offsetWidth;
			if (typeof ycw == "undefined")
				// Konqueror brain-dead techniques
				ycw = 50;
			s.left = (cd.offsetLeft + cd.offsetWidth - ycw) + "px";
		}
		s.top = (cd.offsetTop + cd.offsetHeight) + "px";
	}
};

// event handlers

Calendar.tableMouseUp = function(ev) {
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	if (cal.timeout) {
		clearTimeout(cal.timeout);
	}
	var el = cal.activeDiv;
	if (!el) {
		return false;
	}
	var target = Calendar.getTargetElement(ev);
	ev || (ev = window.event);
	Calendar.removeClass(el, "active");
	if (target == el || target.parentNode == el) {
		Calendar.cellClick(el, ev);
	}
	var mon = Calendar.findMonth(target);
	var date = null;
	if (mon) {
		date = new Date(cal.date);
		if (mon.month != date.getMonth()) {
			date.setMonth(mon.month);
			cal.setDate(date);
			cal.dateClicked = false;
			cal.callHandler();
		}
	} else {
		var year = Calendar.findYear(target);
		if (year) {
			date = new Date(cal.date);
			if (year.year != date.getFullYear()) {
				date.setFullYear(year.year);
				cal.setDate(date);
				cal.dateClicked = false;
				cal.callHandler();
			}
		}
	}
	with (Calendar) {
		removeEvent(document, "mouseup", tableMouseUp);
		removeEvent(document, "mouseover", tableMouseOver);
		removeEvent(document, "mousemove", tableMouseOver);
		cal._hideCombos();
		_C = null;
		return stopEvent(ev);
	}
};

Calendar.tableMouseOver = function (ev) {
	var cal = Calendar._C;
	if (!cal) {
		return;
	}
	var el = cal.activeDiv;
	var target = Calendar.getTargetElement(ev);
	if (target == el || target.parentNode == el) {
		Calendar.addClass(el, "hilite active");
		Calendar.addClass(el.parentNode, "rowhilite");
	} else {
		if (typeof el.navtype == "undefined" || (el.navtype != 50 && (el.navtype == 0 || Math.abs(el.navtype) > 2)))
			Calendar.removeClass(el, "active");
		Calendar.removeClass(el, "hilite");
		Calendar.removeClass(el.parentNode, "rowhilite");
	}
	ev || (ev = window.event);
	if (el.navtype == 50 && target != el) {
		var pos = Calendar.getAbsolutePos(el);
		var w = el.offsetWidth;
		var x = ev.clientX;
		var dx;
		var decrease = true;
		if (x > pos.x + w) {
			dx = x - pos.x - w;
			decrease = false;
		} else
			dx = pos.x - x;

		if (dx < 0) dx = 0;
		var range = el._range;
		var current = el._current;
		var count = Math.floor(dx / 10) % range.length;
		for (var i = range.length; --i >= 0;)
			if (range[i] == current)
				break;
		while (count-- > 0)
			if (decrease) {
				if (--i < 0)
					i = range.length - 1;
			} else if ( ++i >= range.length )
				i = 0;
		var newval = range[i];
		el.innerHTML = newval;

		cal.onUpdateTime();
	}
	var mon = Calendar.findMonth(target);
	if (mon) {
		if (mon.month != cal.date.getMonth()) {
			if (cal.hilitedMonth) {
				Calendar.removeClass(cal.hilitedMonth, "hilite");
			}
			Calendar.addClass(mon, "hilite");
			cal.hilitedMonth = mon;
		} else if (cal.hilitedMonth) {
			Calendar.removeClass(cal.hilitedMonth, "hilite");
		}
	} else {
		if (cal.hilitedMonth) {
			Calendar.removeClass(cal.hilitedMonth, "hilite");
		}
		var year = Calendar.findYear(target);
		if (year) {
			if (year.year != cal.date.getFullYear()) {
				if (cal.hilitedYear) {
					Calendar.removeClass(cal.hilitedYear, "hilite");
				}
				Calendar.addClass(year, "hilite");
				cal.hilitedYear = year;
			} else if (cal.hilitedYear) {
				Calendar.removeClass(cal.hilitedYear, "hilite");
			}
		} else if (cal.hilitedYear) {
			Calendar.removeClass(cal.hilitedYear, "hilite");
		}
	}
	return Calendar.stopEvent(ev);
};

Calendar.tableMouseDown = function (ev) {
	if (Calendar.getTargetElement(ev) == Calendar.getElement(ev)) {
		return Calendar.stopEvent(ev);
	}
};

Calendar.calDragIt = function (ev) {
	var cal = Calendar._C;
	if (!(cal && cal.dragging)) {
		return false;
	}
	var posX;
	var posY;
	if (Calendar.is_ie) {
		posY = window.event.clientY + document.body.scrollTop;
		posX = window.event.clientX + document.body.scrollLeft;
	} else {
		posX = ev.pageX;
		posY = ev.pageY;
	}
	cal.hideShowCovered();
	var st = cal.element.style;
	st.left = (posX - cal.xOffs) + "px";
	st.top = (posY - cal.yOffs) + "px";
	return Calendar.stopEvent(ev);
};

Calendar.calDragEnd = function (ev) {
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	cal.dragging = false;
	with (Calendar) {
		removeEvent(document, "mousemove", calDragIt);
		removeEvent(document, "mouseup", calDragEnd);
		tableMouseUp(ev);
	}
	cal.hideShowCovered();
};

Calendar.dayMouseDown = function(ev) {
	var el = Calendar.getElement(ev);
	if (el.disabled) {
		return false;
	}
	var cal = el.calendar;
	cal.activeDiv = el;
	Calendar._C = cal;
	if (el.navtype != 300) with (Calendar) {
		if (el.navtype == 50) {
			el._current = el.innerHTML;
			addEvent(document, "mousemove", tableMouseOver);
		} else
			addEvent(document, Calendar.is_ie5 ? "mousemove" : "mouseover", tableMouseOver);
		addClass(el, "hilite active");
		addEvent(document, "mouseup", tableMouseUp);
	} else if (cal.isPopup) {
		cal._dragStart(ev);
	}
	if (el.navtype == -1 || el.navtype == 1) {
		if (cal.timeout) clearTimeout(cal.timeout);
		cal.timeout = setTimeout("Calendar.showMonthsCombo()", 250);
	} else if (el.navtype == -2 || el.navtype == 2) {
		if (cal.timeout) clearTimeout(cal.timeout);
		cal.timeout = setTimeout((el.navtype > 0) ? "Calendar.showYearsCombo(true)" : "Calendar.showYearsCombo(false)", 250);
	} else {
		cal.timeout = null;
	}
	return Calendar.stopEvent(ev);
};

Calendar.dayMouseDblClick = function(ev) {
	Calendar.cellClick(Calendar.getElement(ev), ev || window.event);
	if (Calendar.is_ie) {
		document.selection.empty();
	}
};

Calendar.dayMouseOver = function(ev) {
	var el = Calendar.getElement(ev);
	if (Calendar.isRelated(el, ev) || Calendar._C || el.disabled) {
		return false;
	}
	if (el.ttip) {
		if (el.ttip.substr(0, 1) == "_") {
			el.ttip = el.caldate.print(el.calendar.ttDateFormat) + el.ttip.substr(1);
		}
		el.calendar.tooltips.innerHTML = el.ttip;
	}
	if (el.navtype != 300) {
		Calendar.addClass(el, "hilite");
		if (el.caldate) {
			Calendar.addClass(el.parentNode, "rowhilite");
		}
	}
	return Calendar.stopEvent(ev);
};

Calendar.dayMouseOut = function(ev) {
	with (Calendar) {
		var el = getElement(ev);
		if (isRelated(el, ev) || _C || el.disabled)
			return false;
		removeClass(el, "hilite");
		if (el.caldate)
			removeClass(el.parentNode, "rowhilite");
		if (el.calendar)
			el.calendar.tooltips.innerHTML = _TT["SEL_DATE"];
		return stopEvent(ev);
	}
};

/**
 *  A generic "click" handler :) handles all types of buttons defined in this
 *  calendar.
 */
Calendar.cellClick = function(el, ev) {
	var cal = el.calendar;
	var closing = false;
	var newdate = false;
	var date = null;
	if (typeof el.navtype == "undefined") {
		if (cal.currentDateEl) {
			Calendar.removeClass(cal.currentDateEl, "selected");
			Calendar.addClass(el, "selected");
			closing = (cal.currentDateEl == el);
			if (!closing) {
				cal.currentDateEl = el;
			}
		}
		cal.date.setDateOnly(el.caldate);
		date = cal.date;
		var other_month = !(cal.dateClicked = !el.otherMonth);
		if (!other_month && !cal.currentDateEl)
			cal._toggleMultipleDate(new Date(date));
		else
			newdate = !el.disabled;
		// a date was clicked
		if (other_month)
			cal._init(cal.firstDayOfWeek, date);
	} else {
		if (el.navtype == 200) {
			Calendar.removeClass(el, "hilite");
			cal.callCloseHandler();
			return;
		}
		date = new Date(cal.date);
		if (el.navtype == 0)
			date.setDateOnly(new Date()); // TODAY
		// unless "today" was clicked, we assume no date was clicked so
		// the selected handler will know not to close the calenar when
		// in single-click mode.
		// cal.dateClicked = (el.navtype == 0);
		cal.dateClicked = false;
		var year = date.getFullYear();
		var mon = date.getMonth();
		function setMonth(m) {
			var day = date.getDate();
			var max = date.getMonthDays(m);
			if (day > max) {
				date.setDate(max);
			}
			date.setMonth(m);
		};
		switch (el.navtype) {
		    case 400:
			Calendar.removeClass(el, "hilite");
			var text = Calendar._TT["ABOUT"];
			if (typeof text != "undefined") {
				text += cal.showsTime ? Calendar._TT["ABOUT_TIME"] : "";
			} else {
				// FIXME: this should be removed as soon as lang files get updated!
				text = "Help and about box text is not translated into this language.\n" +
					"If you know this language and you feel generous please update\n" +
					"the corresponding file in \"lang\" subdir to match calendar-en.js\n" +
					"and send it back to <mihai_bazon@yahoo.com> to get it into the distribution  ;-)\n\n" +
					"Thank you!\n" +
					"http://dynarch.com/mishoo/calendar.epl\n";
			}
			alert(text);
			return;
		    case -2:
			if (year > cal.minYear) {
				date.setFullYear(year - 1);
			}
			break;
		    case -1:
			if (mon > 0) {
				setMonth(mon - 1);
			} else if (year-- > cal.minYear) {
				date.setFullYear(year);
				setMonth(11);
			}
			break;
		    case 1:
			if (mon < 11) {
				setMonth(mon + 1);
			} else if (year < cal.maxYear) {
				date.setFullYear(year + 1);
				setMonth(0);
			}
			break;
		    case 2:
			if (year < cal.maxYear) {
				date.setFullYear(year + 1);
			}
			break;
		    case 100:
			cal.setFirstDayOfWeek(el.fdow);
			return;
		    case 50:
			var range = el._range;
			var current = el.innerHTML;
			for (var i = range.length; --i >= 0;)
				if (range[i] == current)
					break;
			if (ev && ev.shiftKey) {
				if (--i < 0)
					i = range.length - 1;
			} else if ( ++i >= range.length )
				i = 0;
			var newval = range[i];
			el.innerHTML = newval;
			cal.onUpdateTime();
			return;
		    case 0:
			// TODAY will bring us here
			if ((typeof cal.getDateStatus == "function") &&
			    cal.getDateStatus(date, date.getFullYear(), date.getMonth(), date.getDate())) {
				return false;
			}
			break;
		}
		if (!date.equalsTo(cal.date)) {
			cal.setDate(date);
			newdate = true;
		} else if (el.navtype == 0)
			newdate = closing = true;
	}
	if (newdate) {
		ev && cal.callHandler();
	}
	if (closing) {
		Calendar.removeClass(el, "hilite");
		ev && cal.callCloseHandler();
	}
};

// END: CALENDAR STATIC FUNCTIONS

// BEGIN: CALENDAR OBJECT FUNCTIONS

/**
 *  This function creates the calendar inside the given parent.  If _par is
 *  null than it creates a popup calendar inside the BODY element.  If _par is
 *  an element, be it BODY, then it creates a non-popup calendar (still
 *  hidden).  Some properties need to be set before calling this function.
 */
Calendar.prototype.create = function (_par) {
	var parent = null;
	if (! _par) {
		// default parent is the document body, in which case we create
		// a popup calendar.
		parent = document.getElementsByTagName("body")[0];
		this.isPopup = true;
	} else {
		parent = _par;
		this.isPopup = false;
	}
	this.date = this.dateStr ? new Date(this.dateStr) : new Date();

	var table = Calendar.createElement("table");
	this.table = table;
	table.cellSpacing = 0;
	table.cellPadding = 0;
	table.calendar = this;
	Calendar.addEvent(table, "mousedown", Calendar.tableMouseDown);

	var div = Calendar.createElement("div");
	this.element = div;
	div.className = "calendar";
	if (this.isPopup) {
		div.style.position = "absolute";
		div.style.display = "none";
	}
	div.appendChild(table);

	var thead = Calendar.createElement("thead", table);
	var cell = null;
	var row = null;

	var cal = this;
	var hh = function (text, cs, navtype) {
		cell = Calendar.createElement("td", row);
		cell.colSpan = cs;
		cell.className = "button";
		if (navtype != 0 && Math.abs(navtype) <= 2)
			cell.className += " nav";
		Calendar._add_evs(cell);
		cell.calendar = cal;
		cell.navtype = navtype;
		cell.innerHTML = "<div unselectable='on'>" + text + "</div>";
		return cell;
	};

	row = Calendar.createElement("tr", thead);
	var title_length = 6;
	(this.isPopup) && --title_length;
	(this.weekNumbers) && ++title_length;

	hh("?", 1, 400).ttip = Calendar._TT["INFO"];
	this.title = hh("", title_length, 300);
	this.title.className = "title";
	if (this.isPopup) {
		this.title.ttip = Calendar._TT["DRAG_TO_MOVE"];
		this.title.style.cursor = "move";
		hh("&#x00d7;", 1, 200).ttip = Calendar._TT["CLOSE"];
	}

	row = Calendar.createElement("tr", thead);
	row.className = "headrow";

	this._nav_py = hh("&#x00ab;", 1, -2);
	this._nav_py.ttip = Calendar._TT["PREV_YEAR"];

	this._nav_pm = hh("&#x2039;", 1, -1);
	this._nav_pm.ttip = Calendar._TT["PREV_MONTH"];

	this._nav_now = hh(Calendar._TT["TODAY"], this.weekNumbers ? 4 : 3, 0);
	this._nav_now.ttip = Calendar._TT["GO_TODAY"];

	this._nav_nm = hh("&#x203a;", 1, 1);
	this._nav_nm.ttip = Calendar._TT["NEXT_MONTH"];

	this._nav_ny = hh("&#x00bb;", 1, 2);
	this._nav_ny.ttip = Calendar._TT["NEXT_YEAR"];

	// day names
	row = Calendar.createElement("tr", thead);
	row.className = "daynames";
	if (this.weekNumbers) {
		cell = Calendar.createElement("td", row);
		cell.className = "name wn";
		cell.innerHTML = Calendar._TT["WK"];
	}
	for (var i = 7; i > 0; --i) {
		cell = Calendar.createElement("td", row);
		if (!i) {
			cell.navtype = 100;
			cell.calendar = this;
			Calendar._add_evs(cell);
		}
	}
	this.firstdayname = (this.weekNumbers) ? row.firstChild.nextSibling : row.firstChild;
	this._displayWeekdays();

	var tbody = Calendar.createElement("tbody", table);
	this.tbody = tbody;

	for (i = 6; i > 0; --i) {
		row = Calendar.createElement("tr", tbody);
		if (this.weekNumbers) {
			cell = Calendar.createElement("td", row);
		}
		for (var j = 7; j > 0; --j) {
			cell = Calendar.createElement("td", row);
			cell.calendar = this;
			Calendar._add_evs(cell);
		}
	}

	if (this.showsTime) {
		row = Calendar.createElement("tr", tbody);
		row.className = "time";

		cell = Calendar.createElement("td", row);
		cell.className = "time";
		cell.colSpan = 2;
		cell.innerHTML = Calendar._TT["TIME"] || "&nbsp;";

		cell = Calendar.createElement("td", row);
		cell.className = "time";
		cell.colSpan = this.weekNumbers ? 4 : 3;

		(function(){
			function makeTimePart(className, init, range_start, range_end) {
				var part = Calendar.createElement("span", cell);
				part.className = className;
				part.innerHTML = init;
				part.calendar = cal;
				part.ttip = Calendar._TT["TIME_PART"];
				part.navtype = 50;
				part._range = [];
				if (typeof range_start != "number")
					part._range = range_start;
				else {
					for (var i = range_start; i <= range_end; ++i) {
						var txt;
						if (i < 10 && range_end >= 10) txt = '0' + i;
						else txt = '' + i;
						part._range[part._range.length] = txt;
					}
				}
				Calendar._add_evs(part);
				return part;
			};
			var hrs = cal.date.getHours();
			var mins = cal.date.getMinutes();
			var t12 = !cal.time24;
			var pm = (hrs > 12);
			if (t12 && pm) hrs -= 12;
			var H = makeTimePart("hour", hrs, t12 ? 1 : 0, t12 ? 12 : 23);
			var span = Calendar.createElement("span", cell);
			span.innerHTML = ":";
			span.className = "colon";
			var M = makeTimePart("minute", mins, 0, 59);
			var AP = null;
			cell = Calendar.createElement("td", row);
			cell.className = "time";
			cell.colSpan = 2;
			if (t12)
				AP = makeTimePart("ampm", pm ? "pm" : "am", ["am", "pm"]);
			else
				cell.innerHTML = "&nbsp;";

			cal.onSetTime = function() {
				var pm, hrs = this.date.getHours(),
					mins = this.date.getMinutes();
				if (t12) {
					pm = (hrs >= 12);
					if (pm) hrs -= 12;
					if (hrs == 0) hrs = 12;
					AP.innerHTML = pm ? "pm" : "am";
				}
				H.innerHTML = (hrs < 10) ? ("0" + hrs) : hrs;
				M.innerHTML = (mins < 10) ? ("0" + mins) : mins;
			};

			cal.onUpdateTime = function() {
				var date = this.date;
				var h = parseInt(H.innerHTML, 10);
				if (t12) {
					if (/pm/i.test(AP.innerHTML) && h < 12)
						h += 12;
					else if (/am/i.test(AP.innerHTML) && h == 12)
						h = 0;
				}
				var d = date.getDate();
				var m = date.getMonth();
				var y = date.getFullYear();
				date.setHours(h);
				date.setMinutes(parseInt(M.innerHTML, 10));
				date.setFullYear(y);
				date.setMonth(m);
				date.setDate(d);
				this.dateClicked = false;
				this.callHandler();
			};
		})();
	} else {
		this.onSetTime = this.onUpdateTime = function() {};
	}

	var tfoot = Calendar.createElement("tfoot", table);

	row = Calendar.createElement("tr", tfoot);
	row.className = "footrow";

	cell = hh(Calendar._TT["SEL_DATE"], this.weekNumbers ? 8 : 7, 300);
	cell.className = "ttip";
	if (this.isPopup) {
		cell.ttip = Calendar._TT["DRAG_TO_MOVE"];
		cell.style.cursor = "move";
	}
	this.tooltips = cell;

	div = Calendar.createElement("div", this.element);
	this.monthsCombo = div;
	div.className = "combo";
	for (i = 0; i < Calendar._MN.length; ++i) {
		var mn = Calendar.createElement("div");
		mn.className = Calendar.is_ie ? "label-IEfix" : "label";
		mn.month = i;
		mn.innerHTML = Calendar._SMN[i];
		div.appendChild(mn);
	}

	div = Calendar.createElement("div", this.element);
	this.yearsCombo = div;
	div.className = "combo";
	for (i = 12; i > 0; --i) {
		var yr = Calendar.createElement("div");
		yr.className = Calendar.is_ie ? "label-IEfix" : "label";
		div.appendChild(yr);
	}

	this._init(this.firstDayOfWeek, this.date);
	parent.appendChild(this.element);
};

/** keyboard navigation, only for popup calendars */
Calendar._keyEvent = function(ev) {
	var cal = window._dynarch_popupCalendar;
	if (!cal || cal.multiple)
		return false;
	(Calendar.is_ie) && (ev = window.event);
	var act = (Calendar.is_ie || ev.type == "keypress"),
		K = ev.keyCode;
	if (ev.ctrlKey) {
		switch (K) {
		    case 37: // KEY left
			act && Calendar.cellClick(cal._nav_pm);
			break;
		    case 38: // KEY up
			act && Calendar.cellClick(cal._nav_py);
			break;
		    case 39: // KEY right
			act && Calendar.cellClick(cal._nav_nm);
			break;
		    case 40: // KEY down
			act && Calendar.cellClick(cal._nav_ny);
			break;
		    default:
			return false;
		}
	} else switch (K) {
	    case 32: // KEY space (now)
		Calendar.cellClick(cal._nav_now);
		break;
	    case 27: // KEY esc
		act && cal.callCloseHandler();
		break;
	    case 37: // KEY left
	    case 38: // KEY up
	    case 39: // KEY right
	    case 40: // KEY down
		if (act) {
			var prev, x, y, ne, el, step;
			prev = K == 37 || K == 38;
			step = (K == 37 || K == 39) ? 1 : 7;
			function setVars() {
				el = cal.currentDateEl;
				var p = el.pos;
				x = p & 15;
				y = p >> 4;
				ne = cal.ar_days[y][x];
			};setVars();
			function prevMonth() {
				var date = new Date(cal.date);
				date.setDate(date.getDate() - step);
				cal.setDate(date);
			};
			function nextMonth() {
				var date = new Date(cal.date);
				date.setDate(date.getDate() + step);
				cal.setDate(date);
			};
			while (1) {
				switch (K) {
				    case 37: // KEY left
					if (--x >= 0)
						ne = cal.ar_days[y][x];
					else {
						x = 6;
						K = 38;
						continue;
					}
					break;
				    case 38: // KEY up
					if (--y >= 0)
						ne = cal.ar_days[y][x];
					else {
						prevMonth();
						setVars();
					}
					break;
				    case 39: // KEY right
					if (++x < 7)
						ne = cal.ar_days[y][x];
					else {
						x = 0;
						K = 40;
						continue;
					}
					break;
				    case 40: // KEY down
					if (++y < cal.ar_days.length)
						ne = cal.ar_days[y][x];
					else {
						nextMonth();
						setVars();
					}
					break;
				}
				break;
			}
			if (ne) {
				if (!ne.disabled)
					Calendar.cellClick(ne);
				else if (prev)
					prevMonth();
				else
					nextMonth();
			}
		}
		break;
	    case 13: // KEY enter
		if (act)
			Calendar.cellClick(cal.currentDateEl, ev);
		break;
	    default:
		return false;
	}
	return Calendar.stopEvent(ev);
};

/**
 *  (RE)Initializes the calendar to the given date and firstDayOfWeek
 */
Calendar.prototype._init = function (firstDayOfWeek, date) {
	var today = new Date(),
		TY = today.getFullYear(),
		TM = today.getMonth(),
		TD = today.getDate();
	this.table.style.visibility = "hidden";
	var year = date.getFullYear();
	if (year < this.minYear) {
		year = this.minYear;
		date.setFullYear(year);
	} else if (year > this.maxYear) {
		year = this.maxYear;
		date.setFullYear(year);
	}
	this.firstDayOfWeek = firstDayOfWeek;
	this.date = new Date(date);
	var month = date.getMonth();
	var mday = date.getDate();
	var no_days = date.getMonthDays();

	// calendar voodoo for computing the first day that would actually be
	// displayed in the calendar, even if it's from the previous month.
	// WARNING: this is magic. ;-)
	date.setDate(1);
	var day1 = (date.getDay() - this.firstDayOfWeek) % 7;
	if (day1 < 0)
		day1 += 7;
	date.setDate(-day1);
	date.setDate(date.getDate() + 1);

	var row = this.tbody.firstChild;
	var MN = Calendar._SMN[month];
	var ar_days = this.ar_days = new Array();
	var weekend = Calendar._TT["WEEKEND"];
	var dates = this.multiple ? (this.datesCells = {}) : null;
	for (var i = 0; i < 6; ++i, row = row.nextSibling) {
		var cell = row.firstChild;
		if (this.weekNumbers) {
			cell.className = "day wn";
			cell.innerHTML = date.getWeekNumber();
			cell = cell.nextSibling;
		}
		row.className = "daysrow";
		var hasdays = false, iday, dpos = ar_days[i] = [];
		for (var j = 0; j < 7; ++j, cell = cell.nextSibling, date.setDate(iday + 1)) {
			iday = date.getDate();
			var wday = date.getDay();
			cell.className = "day";
			cell.pos = i << 4 | j;
			dpos[j] = cell;
			var current_month = (date.getMonth() == month);
			if (!current_month) {
				if (this.showsOtherMonths) {
					cell.className += " othermonth";
					cell.otherMonth = true;
				} else {
					cell.className = "emptycell";
					cell.innerHTML = "&nbsp;";
					cell.disabled = true;
					continue;
				}
			} else {
				cell.otherMonth = false;
				hasdays = true;
			}
			cell.disabled = false;
			cell.innerHTML = this.getDateText ? this.getDateText(date, iday) : iday;
			if (dates)
				dates[date.print("%Y%m%d")] = cell;
			if (this.getDateStatus) {
				var status = this.getDateStatus(date, year, month, iday);
				if (this.getDateToolTip) {
					var toolTip = this.getDateToolTip(date, year, month, iday);
					if (toolTip)
						cell.title = toolTip;
				}
				if (status === true) {
					cell.className += " disabled";
					cell.disabled = true;
				} else {
					if (/disabled/i.test(status))
						cell.disabled = true;
					cell.className += " " + status;
				}
			}
			if (!cell.disabled) {
				cell.caldate = new Date(date);
				cell.ttip = "_";
				if (!this.multiple && current_month
				    && iday == mday && this.hiliteToday) {
					cell.className += " selected";
					this.currentDateEl = cell;
				}
				if (date.getFullYear() == TY &&
				    date.getMonth() == TM &&
				    iday == TD) {
					cell.className += " today";
					cell.ttip += Calendar._TT["PART_TODAY"];
				}
				if (weekend.indexOf(wday.toString()) != -1)
					cell.className += cell.otherMonth ? " oweekend" : " weekend";
			}
		}
		if (!(hasdays || this.showsOtherMonths))
			row.className = "emptyrow";
	}
	this.title.innerHTML = Calendar._MN[month] + ", " + year;
	this.onSetTime();
	this.table.style.visibility = "visible";
	this._initMultipleDates();
	// PROFILE
	// this.tooltips.innerHTML = "Generated in " + ((new Date()) - today) + " ms";
};

Calendar.prototype._initMultipleDates = function() {
	if (this.multiple) {
		for (var i in this.multiple) {
			var cell = this.datesCells[i];
			var d = this.multiple[i];
			if (!d)
				continue;
			if (cell)
				cell.className += " selected";
		}
	}
};

Calendar.prototype._toggleMultipleDate = function(date) {
	if (this.multiple) {
		var ds = date.print("%Y%m%d");
		var cell = this.datesCells[ds];
		if (cell) {
			var d = this.multiple[ds];
			if (!d) {
				Calendar.addClass(cell, "selected");
				this.multiple[ds] = date;
			} else {
				Calendar.removeClass(cell, "selected");
				delete this.multiple[ds];
			}
		}
	}
};

Calendar.prototype.setDateToolTipHandler = function (unaryFunction) {
	this.getDateToolTip = unaryFunction;
};

/**
 *  Calls _init function above for going to a certain date (but only if the
 *  date is different than the currently selected one).
 */
Calendar.prototype.setDate = function (date) {
	if (!date.equalsTo(this.date)) {
		this._init(this.firstDayOfWeek, date);
	}
};

/**
 *  Refreshes the calendar.  Useful if the "disabledHandler" function is
 *  dynamic, meaning that the list of disabled date can change at runtime.
 *  Just * call this function if you think that the list of disabled dates
 *  should * change.
 */
Calendar.prototype.refresh = function () {
	this._init(this.firstDayOfWeek, this.date);
};

/** Modifies the "firstDayOfWeek" parameter (pass 0 for Synday, 1 for Monday, etc.). */
Calendar.prototype.setFirstDayOfWeek = function (firstDayOfWeek) {
	this._init(firstDayOfWeek, this.date);
	this._displayWeekdays();
};

/**
 *  Allows customization of what dates are enabled.  The "unaryFunction"
 *  parameter must be a function object that receives the date (as a JS Date
 *  object) and returns a boolean value.  If the returned value is true then
 *  the passed date will be marked as disabled.
 */
Calendar.prototype.setDateStatusHandler = Calendar.prototype.setDisabledHandler = function (unaryFunction) {
	this.getDateStatus = unaryFunction;
};

/** Customization of allowed year range for the calendar. */
Calendar.prototype.setRange = function (a, z) {
	this.minYear = a;
	this.maxYear = z;
};

/** Calls the first user handler (selectedHandler). */
Calendar.prototype.callHandler = function () {
	if (this.onSelected) {
		this.onSelected(this, this.date.print(this.dateFormat));
	}
};

/** Calls the second user handler (closeHandler). */
Calendar.prototype.callCloseHandler = function () {
	if (this.onClose) {
		this.onClose(this);
	}
	this.hideShowCovered();
};

/** Removes the calendar object from the DOM tree and destroys it. */
Calendar.prototype.destroy = function () {
	var el = this.element.parentNode;
	el.removeChild(this.element);
	Calendar._C = null;
	window._dynarch_popupCalendar = null;
};

/**
 *  Moves the calendar element to a different section in the DOM tree (changes
 *  its parent).
 */
Calendar.prototype.reparent = function (new_parent) {
	var el = this.element;
	el.parentNode.removeChild(el);
	new_parent.appendChild(el);
};

// This gets called when the user presses a mouse button anywhere in the
// document, if the calendar is shown.  If the click was outside the open
// calendar this function closes it.
Calendar._checkCalendar = function(ev) {
	var calendar = window._dynarch_popupCalendar;
	if (!calendar) {
		return false;
	}
	var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
	for (; el != null && el != calendar.element; el = el.parentNode);
	if (el == null) {
		// calls closeHandler which should hide the calendar.
		window._dynarch_popupCalendar.callCloseHandler();
		return Calendar.stopEvent(ev);
	}
};

/** Shows the calendar. */
Calendar.prototype.show = function () {
	var rows = this.table.getElementsByTagName("tr");
	for (var i = rows.length; i > 0;) {
		var row = rows[--i];
		Calendar.removeClass(row, "rowhilite");
		var cells = row.getElementsByTagName("td");
		for (var j = cells.length; j > 0;) {
			var cell = cells[--j];
			Calendar.removeClass(cell, "hilite");
			Calendar.removeClass(cell, "active");
		}
	}
	this.element.style.display = "block";
	this.hidden = false;
	if (this.isPopup) {
		window._dynarch_popupCalendar = this;
		Calendar.addEvent(document, "keydown", Calendar._keyEvent);
		Calendar.addEvent(document, "keypress", Calendar._keyEvent);
		Calendar.addEvent(document, "mousedown", Calendar._checkCalendar);
	}
	this.hideShowCovered();
};

/**
 *  Hides the calendar.  Also removes any "hilite" from the class of any TD
 *  element.
 */
Calendar.prototype.hide = function () {
	if (this.isPopup) {
		Calendar.removeEvent(document, "keydown", Calendar._keyEvent);
		Calendar.removeEvent(document, "keypress", Calendar._keyEvent);
		Calendar.removeEvent(document, "mousedown", Calendar._checkCalendar);
	}
	this.element.style.display = "none";
	this.hidden = true;
	this.hideShowCovered();
};

/**
 *  Shows the calendar at a given absolute position (beware that, depending on
 *  the calendar element style -- position property -- this might be relative
 *  to the parent's containing rectangle).
 */
Calendar.prototype.showAt = function (x, y) {
	var s = this.element.style;
	s.left = x + "px";
	s.top = y + "px";
	this.show();
};

/** Shows the calendar near a given element. */
Calendar.prototype.showAtElement = function (el, opts) {
	var self = this;
	var p = Calendar.getAbsolutePos(el);
	if (!opts || typeof opts != "string") {
		this.showAt(p.x, p.y + el.offsetHeight);
		return true;
	}
	function fixPosition(box) {
		if (box.x < 0)
			box.x = 0;
		if (box.y < 0)
			box.y = 0;
		var cp = document.createElement("div");
		var s = cp.style;
		s.position = "absolute";
		s.right = s.bottom = s.width = s.height = "0px";
		document.body.appendChild(cp);
		var br = Calendar.getAbsolutePos(cp);
		document.body.removeChild(cp);
		if (Calendar.is_ie) {
			br.y += document.body.scrollTop;
			br.x += document.body.scrollLeft;
		} else {
			br.y += window.scrollY;
			br.x += window.scrollX;
		}
		var tmp = box.x + box.width - br.x;
		if (tmp > 0) box.x -= tmp;
		tmp = box.y + box.height - br.y;
		if (tmp > 0) box.y -= tmp;
	};
	this.element.style.display = "block";
	Calendar.continuation_for_the_fucking_khtml_browser = function() {
		var w = self.element.offsetWidth;
		var h = self.element.offsetHeight;
		self.element.style.display = "none";
		var valign = opts.substr(0, 1);
		var halign = "l";
		if (opts.length > 1) {
			halign = opts.substr(1, 1);
		}
		// vertical alignment
		switch (valign) {
		    case "T": p.y -= h; break;
		    case "B": p.y += el.offsetHeight; break;
		    case "C": p.y += (el.offsetHeight - h) / 2; break;
		    case "t": p.y += el.offsetHeight - h; break;
		    case "b": break; // already there
		}
		// horizontal alignment
		switch (halign) {
		    case "L": p.x -= w; break;
		    case "R": p.x += el.offsetWidth; break;
		    case "C": p.x += (el.offsetWidth - w) / 2; break;
		    case "l": p.x += el.offsetWidth - w; break;
		    case "r": break; // already there
		}
		p.width = w;
		p.height = h + 40;
		self.monthsCombo.style.display = "none";
		fixPosition(p);
		self.showAt(p.x, p.y);
	};
	if (Calendar.is_khtml)
		setTimeout("Calendar.continuation_for_the_fucking_khtml_browser()", 10);
	else
		Calendar.continuation_for_the_fucking_khtml_browser();
};

/** Customizes the date format. */
Calendar.prototype.setDateFormat = function (str) {
	this.dateFormat = str;
};

/** Customizes the tooltip date format. */
Calendar.prototype.setTtDateFormat = function (str) {
	this.ttDateFormat = str;
};

/**
 *  Tries to identify the date represented in a string.  If successful it also
 *  calls this.setDate which moves the calendar to the given date.
 */
Calendar.prototype.parseDate = function(str, fmt) {
	if (!fmt)
		fmt = this.dateFormat;
	this.setDate(Date.parseDate(str, fmt));
};

Calendar.prototype.hideShowCovered = function () {
	if (!Calendar.is_ie && !Calendar.is_opera)
		return;
	function getVisib(obj){
		var value = obj.style.visibility;
		if (!value) {
			if (document.defaultView && typeof (document.defaultView.getComputedStyle) == "function") { // Gecko, W3C
				if (!Calendar.is_khtml)
					value = document.defaultView.
						getComputedStyle(obj, "").getPropertyValue("visibility");
				else
					value = '';
			} else if (obj.currentStyle) { // IE
				value = obj.currentStyle.visibility;
			} else
				value = '';
		}
		return value;
	};

	var tags = new Array("applet", "iframe", "select");
	var el = this.element;

	var p = Calendar.getAbsolutePos(el);
	var EX1 = p.x;
	var EX2 = el.offsetWidth + EX1;
	var EY1 = p.y;
	var EY2 = el.offsetHeight + EY1;

	for (var k = tags.length; k > 0; ) {
		var ar = document.getElementsByTagName(tags[--k]);
		var cc = null;

		for (var i = ar.length; i > 0;) {
			cc = ar[--i];

			p = Calendar.getAbsolutePos(cc);
			var CX1 = p.x;
			var CX2 = cc.offsetWidth + CX1;
			var CY1 = p.y;
			var CY2 = cc.offsetHeight + CY1;

			if (this.hidden || (CX1 > EX2) || (CX2 < EX1) || (CY1 > EY2) || (CY2 < EY1)) {
				if (!cc.__msh_save_visibility) {
					cc.__msh_save_visibility = getVisib(cc);
				}
				cc.style.visibility = cc.__msh_save_visibility;
			} else {
				if (!cc.__msh_save_visibility) {
					cc.__msh_save_visibility = getVisib(cc);
				}
				cc.style.visibility = "hidden";
			}
		}
	}
};

/** Internal function; it displays the bar with the names of the weekday. */
Calendar.prototype._displayWeekdays = function () {
	var fdow = this.firstDayOfWeek;
	var cell = this.firstdayname;
	var weekend = Calendar._TT["WEEKEND"];
	for (var i = 0; i < 7; ++i) {
		cell.className = "day name";
		var realday = (i + fdow) % 7;
		if (i) {
			cell.ttip = Calendar._TT["DAY_FIRST"].replace("%s", Calendar._DN[realday]);
			cell.navtype = 100;
			cell.calendar = this;
			cell.fdow = realday;
			Calendar._add_evs(cell);
		}
		if (weekend.indexOf(realday.toString()) != -1) {
			Calendar.addClass(cell, "weekend");
		}
		cell.innerHTML = Calendar._SDN[(i + fdow) % 7];
		cell = cell.nextSibling;
	}
};

/** Internal function.  Hides all combo boxes that might be displayed. */
Calendar.prototype._hideCombos = function () {
	this.monthsCombo.style.display = "none";
	this.yearsCombo.style.display = "none";
};

/** Internal function.  Starts dragging the element. */
Calendar.prototype._dragStart = function (ev) {
	if (this.dragging) {
		return;
	}
	this.dragging = true;
	var posX;
	var posY;
	if (Calendar.is_ie) {
		posY = window.event.clientY + document.body.scrollTop;
		posX = window.event.clientX + document.body.scrollLeft;
	} else {
		posY = ev.clientY + window.scrollY;
		posX = ev.clientX + window.scrollX;
	}
	var st = this.element.style;
	this.xOffs = posX - parseInt(st.left);
	this.yOffs = posY - parseInt(st.top);
	with (Calendar) {
		addEvent(document, "mousemove", calDragIt);
		addEvent(document, "mouseup", calDragEnd);
	}
};

// BEGIN: DATE OBJECT PATCHES

/** Adds the number of days array to the Date object. */
Date._MD = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

/** Constants used for time computations */
Date.SECOND = 1000 /* milliseconds */;
Date.MINUTE = 60 * Date.SECOND;
Date.HOUR   = 60 * Date.MINUTE;
Date.DAY    = 24 * Date.HOUR;
Date.WEEK   =  7 * Date.DAY;

Date.parseDate = function(str, fmt) {
	var today = new Date();
	var y = 0;
	var m = -1;
	var d = 0;
	var a = str.split(/\W+/);
	var b = fmt.match(/%./g);
	var i = 0, j = 0;
	var hr = 0;
	var min = 0;
	for (i = 0; i < a.length; ++i) {
		if (!a[i])
			continue;
		switch (b[i]) {
		    case "%d":
		    case "%e":
			d = parseInt(a[i], 10);
			break;

		    case "%m":
			m = parseInt(a[i], 10) - 1;
			break;

		    case "%Y":
		    case "%y":
			y = parseInt(a[i], 10);
			(y < 100) && (y += (y > 29) ? 1900 : 2000);
			break;

		    case "%b":
		    case "%B":
			for (j = 0; j < 12; ++j) {
				if (Calendar._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase()) { m = j; break; }
			}
			break;

		    case "%H":
		    case "%I":
		    case "%k":
		    case "%l":
			hr = parseInt(a[i], 10);
			break;

		    case "%P":
		    case "%p":
			if (/pm/i.test(a[i]) && hr < 12)
				hr += 12;
			else if (/am/i.test(a[i]) && hr >= 12)
				hr -= 12;
			break;

		    case "%M":
			min = parseInt(a[i], 10);
			break;
		}
	}
	if (isNaN(y)) y = today.getFullYear();
	if (isNaN(m)) m = today.getMonth();
	if (isNaN(d)) d = today.getDate();
	if (isNaN(hr)) hr = today.getHours();
	if (isNaN(min)) min = today.getMinutes();
	if (y != 0 && m != -1 && d != 0)
		return new Date(y, m, d, hr, min, 0);
	y = 0; m = -1; d = 0;
	for (i = 0; i < a.length; ++i) {
		if (a[i].search(/[a-zA-Z]+/) != -1) {
			var t = -1;
			for (j = 0; j < 12; ++j) {
				if (Calendar._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase()) { t = j; break; }
			}
			if (t != -1) {
				if (m != -1) {
					d = m+1;
				}
				m = t;
			}
		} else if (parseInt(a[i], 10) <= 12 && m == -1) {
			m = a[i]-1;
		} else if (parseInt(a[i], 10) > 31 && y == 0) {
			y = parseInt(a[i], 10);
			(y < 100) && (y += (y > 29) ? 1900 : 2000);
		} else if (d == 0) {
			d = a[i];
		}
	}
	if (y == 0)
		y = today.getFullYear();
	if (m != -1 && d != 0)
		return new Date(y, m, d, hr, min, 0);
	return today;
};

/** Returns the number of days in the current month */
Date.prototype.getMonthDays = function(month) {
	var year = this.getFullYear();
	if (typeof month == "undefined") {
		month = this.getMonth();
	}
	if (((0 == (year%4)) && ( (0 != (year%100)) || (0 == (year%400)))) && month == 1) {
		return 29;
	} else {
		return Date._MD[month];
	}
};

/** Returns the number of day in the year. */
Date.prototype.getDayOfYear = function() {
	var now = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
	var then = new Date(this.getFullYear(), 0, 0, 0, 0, 0);
	var time = now - then;
	return Math.floor(time / Date.DAY);
};

/** Returns the number of the week in year, as defined in ISO 8601. */
Date.prototype.getWeekNumber = function() {
	var d = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
	var DoW = d.getDay();
	d.setDate(d.getDate() - (DoW + 6) % 7 + 3); // Nearest Thu
	var ms = d.valueOf(); // GMT
	d.setMonth(0);
	d.setDate(4); // Thu in Week 1
	return Math.round((ms - d.valueOf()) / (7 * 864e5)) + 1;
};

/** Checks date and time equality */
Date.prototype.equalsTo = function(date) {
	return ((this.getFullYear() == date.getFullYear()) &&
		(this.getMonth() == date.getMonth()) &&
		(this.getDate() == date.getDate()) &&
		(this.getHours() == date.getHours()) &&
		(this.getMinutes() == date.getMinutes()));
};

/** Set only the year, month, date parts (keep existing time) */
Date.prototype.setDateOnly = function(date) {
	var tmp = new Date(date);
	this.setDate(1);
	this.setFullYear(tmp.getFullYear());
	this.setMonth(tmp.getMonth());
	this.setDate(tmp.getDate());
};

/** Prints the date in a string according to the given format. */
Date.prototype.print = function (str) {
	var m = this.getMonth();
	var d = this.getDate();
	var y = this.getFullYear();
	var wn = this.getWeekNumber();
	var w = this.getDay();
	var s = {};
	var hr = this.getHours();
	var pm = (hr >= 12);
	var ir = (pm) ? (hr - 12) : hr;
	var dy = this.getDayOfYear();
	if (ir == 0)
		ir = 12;
	var min = this.getMinutes();
	var sec = this.getSeconds();
	s["%a"] = Calendar._SDN[w]; // abbreviated weekday name [FIXME: I18N]
	s["%A"] = Calendar._DN[w]; // full weekday name
	s["%b"] = Calendar._SMN[m]; // abbreviated month name [FIXME: I18N]
	s["%B"] = Calendar._MN[m]; // full month name
	// FIXME: %c : preferred date and time representation for the current locale
	s["%C"] = 1 + Math.floor(y / 100); // the century number
	s["%d"] = (d < 10) ? ("0" + d) : d; // the day of the month (range 01 to 31)
	s["%e"] = d; // the day of the month (range 1 to 31)
	// FIXME: %D : american date style: %m/%d/%y
	// FIXME: %E, %F, %G, %g, %h (man strftime)
	s["%H"] = (hr < 10) ? ("0" + hr) : hr; // hour, range 00 to 23 (24h format)
	s["%I"] = (ir < 10) ? ("0" + ir) : ir; // hour, range 01 to 12 (12h format)
	s["%j"] = (dy < 100) ? ((dy < 10) ? ("00" + dy) : ("0" + dy)) : dy; // day of the year (range 001 to 366)
	s["%k"] = hr;		// hour, range 0 to 23 (24h format)
	s["%l"] = ir;		// hour, range 1 to 12 (12h format)
	s["%m"] = (m < 9) ? ("0" + (1+m)) : (1+m); // month, range 01 to 12
	s["%M"] = (min < 10) ? ("0" + min) : min; // minute, range 00 to 59
	s["%n"] = "\n";		// a newline character
	s["%p"] = pm ? "PM" : "AM";
	s["%P"] = pm ? "pm" : "am";
	// FIXME: %r : the time in am/pm notation %I:%M:%S %p
	// FIXME: %R : the time in 24-hour notation %H:%M
	s["%s"] = Math.floor(this.getTime() / 1000);
	s["%S"] = (sec < 10) ? ("0" + sec) : sec; // seconds, range 00 to 59
	s["%t"] = "\t";		// a tab character
	// FIXME: %T : the time in 24-hour notation (%H:%M:%S)
	s["%U"] = s["%W"] = s["%V"] = (wn < 10) ? ("0" + wn) : wn;
	s["%u"] = w + 1;	// the day of the week (range 1 to 7, 1 = MON)
	s["%w"] = w;		// the day of the week (range 0 to 6, 0 = SUN)
	// FIXME: %x : preferred date representation for the current locale without the time
	// FIXME: %X : preferred time representation for the current locale without the date
	s["%y"] = ('' + y).substr(2, 2); // year without the century (range 00 to 99)
	s["%Y"] = y;		// year with the century
	s["%%"] = "%";		// a literal '%' character

	var re = /%./g;
	if (!Calendar.is_ie5 && !Calendar.is_khtml)
		return str.replace(re, function (par) { return s[par] || par; });

	var a = str.match(re);
	for (var i = 0; i < a.length; i++) {
		var tmp = s[a[i]];
		if (tmp) {
			re = new RegExp(a[i], 'g');
			str = str.replace(re, tmp);
		}
	}

	return str;
};

Date.prototype.__msh_oldSetFullYear = Date.prototype.setFullYear;
Date.prototype.setFullYear = function(y) {
	var d = new Date(this);
	d.__msh_oldSetFullYear(y);
	if (d.getMonth() != this.getMonth())
		this.setDate(28);
	this.__msh_oldSetFullYear(y);
};

// END: DATE OBJECT PATCHES


// global object that remembers the calendar
window._dynarch_popupCalendar = null;
// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Sunday",
 "Monday",
 "Tuesday",
 "Wednesday",
 "Thursday",
 "Friday",
 "Saturday",
 "Sunday");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Sun",
 "Mon",
 "Tue",
 "Wed",
 "Thu",
 "Fri",
 "Sat",
 "Sun");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("January",
 "February",
 "March",
 "April",
 "May",
 "June",
 "July",
 "August",
 "September",
 "October",
 "November",
 "December");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "May",
 "Jun",
 "Jul",
 "Aug",
 "Sep",
 "Oct",
 "Nov",
 "Dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "About the calendar";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "Prev. year (hold for menu)";
Calendar._TT["PREV_MONTH"] = "Prev. month (hold for menu)";
Calendar._TT["GO_TODAY"] = "Go Today";
Calendar._TT["NEXT_MONTH"] = "Next month (hold for menu)";
Calendar._TT["NEXT_YEAR"] = "Next year (hold for menu)";
Calendar._TT["SEL_DATE"] = "Select date";
Calendar._TT["DRAG_TO_MOVE"] = "Drag to move";
Calendar._TT["PART_TODAY"] = " (today)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Display %s first";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Close";
Calendar._TT["TODAY"] = "Today";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Time:";
/*  Copyright Mihai Bazon, 2002, 2003  |  http://dynarch.com/mishoo/
 * ---------------------------------------------------------------------------
 *
 * The DHTML Calendar
 *
 * Details and latest version at:
 * http://dynarch.com/mishoo/calendar.epl
 *
 * This script is distributed under the GNU Lesser General Public License.
 * Read the entire license text here: http://www.gnu.org/licenses/lgpl.html
 *
 * This file defines helper functions for setting up the calendar.  They are
 * intended to help non-programmers get a working calendar on their site
 * quickly.  This script should not be seen as part of the calendar.  It just
 * shows you what one can do with the calendar, while in the same time
 * providing a quick and simple method for setting it up.  If you need
 * exhaustive customization of the calendar creation process feel free to
 * modify this code to suit your needs (this is recommended and much better
 * than modifying calendar.js itself).
 */

// $Id: calendar-setup.js,v 1.25 2005/03/07 09:51:33 mishoo Exp $

/**
 *  This function "patches" an input field (or other element) to use a calendar
 *  widget for date selection.
 *
 *  The "params" is a single object that can have the following properties:
 *
 *    prop. name   | description
 *  -------------------------------------------------------------------------------------------------
 *   inputField    | the ID of an input field to store the date
 *   displayArea   | the ID of a DIV or other element to show the date
 *   button        | ID of a button or other element that will trigger the calendar
 *   eventName     | event that will trigger the calendar, without the "on" prefix (default: "click")
 *   ifFormat      | date format that will be stored in the input field
 *   daFormat      | the date format that will be used to display the date in displayArea
 *   singleClick   | (true/false) wether the calendar is in single click mode or not (default: true)
 *   firstDay      | numeric: 0 to 6.  "0" means display Sunday first, "1" means display Monday first, etc.
 *   align         | alignment (default: "Br"); if you don't know what's this see the calendar documentation
 *   range         | array with 2 elements.  Default: [1900, 2999] -- the range of years available
 *   weekNumbers   | (true/false) if it's true (default) the calendar will display week numbers
 *   flat          | null or element ID; if not null the calendar will be a flat calendar having the parent with the given ID
 *   flatCallback  | function that receives a JS Date object and returns an URL to point the browser to (for flat calendar)
 *   disableFunc   | function that receives a JS Date object and should return true if that date has to be disabled in the calendar
 *   onSelect      | function that gets called when a date is selected.  You don't _have_ to supply this (the default is generally okay)
 *   onClose       | function that gets called when the calendar is closed.  [default]
 *   onUpdate      | function that gets called after the date is updated in the input field.  Receives a reference to the calendar.
 *   date          | the date that the calendar will be initially displayed to
 *   showsTime     | default: false; if true the calendar will include a time selector
 *   timeFormat    | the time format; can be "12" or "24", default is "12"
 *   electric      | if true (default) then given fields/date areas are updated for each move; otherwise they're updated only on close
 *   step          | configures the step of the years in drop-down boxes; default: 2
 *   position      | configures the calendar absolute position; default: null
 *   cache         | if "true" (but default: "false") it will reuse the same calendar object, where possible
 *   showOthers    | if "true" (but default: "false") it will show days from other months too
 *
 *  None of them is required, they all have default values.  However, if you
 *  pass none of "inputField", "displayArea" or "button" you'll get a warning
 *  saying "nothing to setup".
 */
Calendar.setup = function (params) {
	function param_default(pname, def) { if (typeof params[pname] == "undefined") { params[pname] = def; } };

	param_default("inputField",     null);
	param_default("displayArea",    null);
	param_default("button",         null);
	param_default("eventName",      "click");
	param_default("ifFormat",       "%Y/%m/%d");
	param_default("daFormat",       "%Y/%m/%d");
	param_default("singleClick",    true);
	param_default("disableFunc",    null);
	param_default("dateStatusFunc", params["disableFunc"]);	// takes precedence if both are defined
	param_default("dateText",       null);
	param_default("firstDay",       null);
	param_default("align",          "Br");
	param_default("range",          [1900, 2999]);
	param_default("weekNumbers",    true);
	param_default("flat",           null);
	param_default("flatCallback",   null);
	param_default("onSelect",       null);
	param_default("onClose",        null);
	param_default("onUpdate",       null);
	param_default("date",           null);
	param_default("showsTime",      false);
	param_default("timeFormat",     "24");
	param_default("electric",       true);
	param_default("step",           2);
	param_default("position",       null);
	param_default("cache",          false);
	param_default("showOthers",     false);
	param_default("multiple",       null);

	var tmp = ["inputField", "displayArea", "button"];
	for (var i in tmp) {
		if (typeof params[tmp[i]] == "string") {
			params[tmp[i]] = document.getElementById(params[tmp[i]]);
		}
	}
	if (!(params.flat || params.multiple || params.inputField || params.displayArea || params.button)) {
		alert("Calendar.setup:\n  Nothing to setup (no fields found).  Please check your code");
		return false;
	}

	function onSelect(cal) {
		var p = cal.params;
		var update = (cal.dateClicked || p.electric);

    // added by alla
    p.inputField.style.color = "#333333";

		if (update && p.inputField) {
			p.inputField.value = cal.date.print(p.ifFormat);
			if (typeof p.inputField.onchange == "function")
				p.inputField.onchange();
		}
		if (update && p.displayArea)
			p.displayArea.innerHTML = cal.date.print(p.daFormat);
		if (update && typeof p.onUpdate == "function")
			p.onUpdate(cal);
		if (update && p.flat) {
			if (typeof p.flatCallback == "function")
				p.flatCallback(cal);
		}
		if (update && p.singleClick && cal.dateClicked)
			cal.callCloseHandler();
	};

	if (params.flat != null) {
		if (typeof params.flat == "string")
			params.flat = document.getElementById(params.flat);
		if (!params.flat) {
			alert("Calendar.setup:\n  Flat specified but can't find parent.");
			return false;
		}
		var cal = new Calendar(params.firstDay, params.date, params.onSelect || onSelect);
		cal.showsOtherMonths = params.showOthers;
		cal.showsTime = params.showsTime;
		cal.time24 = (params.timeFormat == "24");
		cal.params = params;
		cal.weekNumbers = params.weekNumbers;
		cal.setRange(params.range[0], params.range[1]);
		cal.setDateStatusHandler(params.dateStatusFunc);
		cal.getDateText = params.dateText;
		if (params.ifFormat) {
			cal.setDateFormat(params.ifFormat);
		}
		if (params.inputField && typeof params.inputField.value == "string") {
			cal.parseDate(params.inputField.value);
		}
		cal.create(params.flat);
		cal.show();
		return false;
	}

	var triggerEl = params.button || params.displayArea || params.inputField;
	triggerEl["on" + params.eventName] = function() {
		var dateEl = params.inputField || params.displayArea;
		var dateFmt = params.inputField ? params.ifFormat : params.daFormat;
		var mustCreate = false;
		var cal = window.calendar;
		if (dateEl)
			params.date = Date.parseDate(dateEl.value || dateEl.innerHTML, dateFmt);
		if (!(cal && params.cache)) {
			window.calendar = cal = new Calendar(params.firstDay,
							     params.date,
							     params.onSelect || onSelect,
							     params.onClose || function(cal) { cal.hide(); });
			cal.showsTime = params.showsTime;
			cal.time24 = (params.timeFormat == "24");
			cal.weekNumbers = params.weekNumbers;
			mustCreate = true;
		} else {
			if (params.date)
				cal.setDate(params.date);
			cal.hide();
		}
		if (params.multiple) {
			cal.multiple = {};
			for (var i = params.multiple.length; --i >= 0;) {
				var d = params.multiple[i];
				var ds = d.print("%Y%m%d");
				cal.multiple[ds] = d;
			}
		}
		cal.showsOtherMonths = params.showOthers;
		cal.yearStep = params.step;
		cal.setRange(params.range[0], params.range[1]);
		cal.params = params;
		cal.setDateStatusHandler(params.dateStatusFunc);
		cal.getDateText = params.dateText;
		cal.setDateFormat(dateFmt);
		if (mustCreate)
			cal.create();
		cal.refresh();
		if (!params.position)
			cal.showAtElement(params.button || params.displayArea || params.inputField, params.align);
		else
			cal.showAt(params.position[0], params.position[1]);
		return false;
	};

	return cal;
};
/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(d){d.jqplot.BarRenderer=function(){d.jqplot.LineRenderer.call(this)};d.jqplot.BarRenderer.prototype=new d.jqplot.LineRenderer();d.jqplot.BarRenderer.prototype.constructor=d.jqplot.BarRenderer;d.jqplot.BarRenderer.prototype.init=function(o,q){this.barPadding=8;this.barMargin=10;this.barDirection="vertical";this.barWidth=null;this.shadowOffset=2;this.shadowDepth=5;this.shadowAlpha=0.08;this.waterfall=false;this.groups=1;this.varyBarColor=false;this.highlightMouseOver=true;this.highlightMouseDown=false;this.highlightColors=[];this.transposedData=true;this.renderer.animation={show:false,direction:"down",speed:3000,_supported:true};this._type="bar";if(o.highlightMouseDown&&o.highlightMouseOver==null){o.highlightMouseOver=false}d.extend(true,this,o);d.extend(true,this.renderer,o);this.fill=true;if(this.barDirection==="horizontal"&&this.rendererOptions.animation&&this.rendererOptions.animation.direction==null){this.renderer.animation.direction="left"}if(this.waterfall){this.fillToZero=false;this.disableStack=true}if(this.barDirection=="vertical"){this._primaryAxis="_xaxis";this._stackAxis="y";this.fillAxis="y"}else{this._primaryAxis="_yaxis";this._stackAxis="x";this.fillAxis="x"}this._highlightedPoint=null;this._plotSeriesInfo=null;this._dataColors=[];this._barPoints=[];var p={lineJoin:"miter",lineCap:"round",fill:true,isarc:false,strokeStyle:this.color,fillStyle:this.color,closePath:this.fill};this.renderer.shapeRenderer.init(p);var n={lineJoin:"miter",lineCap:"round",fill:true,isarc:false,angle:this.shadowAngle,offset:this.shadowOffset,alpha:this.shadowAlpha,depth:this.shadowDepth,closePath:this.fill};this.renderer.shadowRenderer.init(n);q.postInitHooks.addOnce(h);q.postDrawHooks.addOnce(j);q.eventListenerHooks.addOnce("jqplotMouseMove",b);q.eventListenerHooks.addOnce("jqplotMouseDown",a);q.eventListenerHooks.addOnce("jqplotMouseUp",l);q.eventListenerHooks.addOnce("jqplotClick",e);q.eventListenerHooks.addOnce("jqplotRightClick",m)};function g(t,p,o,w){if(this.rendererOptions.barDirection=="horizontal"){this._stackAxis="x";this._primaryAxis="_yaxis"}if(this.rendererOptions.waterfall==true){this._data=d.extend(true,[],this.data);var s=0;var u=(!this.rendererOptions.barDirection||this.rendererOptions.barDirection==="vertical"||this.transposedData===false)?1:0;for(var q=0;q<this.data.length;q++){s+=this.data[q][u];if(q>0){this.data[q][u]+=this.data[q-1][u]}}this.data[this.data.length]=(u==1)?[this.data.length+1,s]:[s,this.data.length+1];this._data[this._data.length]=(u==1)?[this._data.length+1,s]:[s,this._data.length+1]}if(this.rendererOptions.groups>1){this.breakOnNull=true;var n=this.data.length;var v=parseInt(n/this.rendererOptions.groups,10);var r=0;for(var q=v;q<n;q+=v){this.data.splice(q+r,0,[null,null]);this._plotData.splice(q+r,0,[null,null]);this._stackData.splice(q+r,0,[null,null]);r++}for(q=0;q<this.data.length;q++){if(this._primaryAxis=="_xaxis"){this.data[q][0]=q+1;this._plotData[q][0]=q+1;this._stackData[q][0]=q+1}else{this.data[q][1]=q+1;this._plotData[q][1]=q+1;this._stackData[q][1]=q+1}}}}d.jqplot.preSeriesInitHooks.push(g);d.jqplot.BarRenderer.prototype.calcSeriesNumbers=function(){var r=0;var t=0;var q=this[this._primaryAxis];var p,o,u;for(var n=0;n<q._series.length;n++){o=q._series[n];if(o===this){u=n}if(o.renderer.constructor==d.jqplot.BarRenderer){r+=o.data.length;t+=1}}return[r,t,u]};d.jqplot.BarRenderer.prototype.setBarWidth=function(){var q;var n=0;var o=0;var t=this[this._primaryAxis];var x,r,v;var w=this._plotSeriesInfo=this.renderer.calcSeriesNumbers.call(this);n=w[0];o=w[1];var u=t.numberTicks;var p=(u-1)/2;if(t.name=="xaxis"||t.name=="x2axis"){if(this._stack){this.barWidth=(t._offsets.max-t._offsets.min)/n*o-this.barMargin}else{this.barWidth=((t._offsets.max-t._offsets.min)/p-this.barPadding*(o-1)-this.barMargin*2)/o}}else{if(this._stack){this.barWidth=(t._offsets.min-t._offsets.max)/n*o-this.barMargin}else{this.barWidth=((t._offsets.min-t._offsets.max)/p-this.barPadding*(o-1)-this.barMargin*2)/o}}return[n,o]};function f(o){var q=[];for(var s=0;s<o.length;s++){var r=d.jqplot.getColorComponents(o[s]);var n=[r[0],r[1],r[2]];var t=n[0]+n[1]+n[2];for(var p=0;p<3;p++){n[p]=(t>570)?n[p]*0.8:n[p]+0.3*(255-n[p]);n[p]=parseInt(n[p],10)}q.push("rgb("+n[0]+","+n[1]+","+n[2]+")")}return q}function i(v,u,s,t,o){var q=v,w=v-1,n,p,r=(o==="x")?0:1;if(q>0){p=t.series[w]._plotData[u][r];if((s*p)<0){n=i(w,u,s,t,o)}else{n=t.series[w].gridData[u][r]}}else{n=(r===0)?t.series[q]._xaxis.series_u2p(0):t.series[q]._yaxis.series_u2p(0)}return n}d.jqplot.BarRenderer.prototype.draw=function(E,L,q,G){var I;var A=d.extend({},q);var w=(A.shadow!=undefined)?A.shadow:this.shadow;var O=(A.showLine!=undefined)?A.showLine:this.showLine;var F=(A.fill!=undefined)?A.fill:this.fill;var p=this.xaxis;var J=this.yaxis;var y=this._xaxis.series_u2p;var K=this._yaxis.series_u2p;var D,C;this._dataColors=[];this._barPoints=[];if(this.barWidth==null){this.renderer.setBarWidth.call(this)}var N=this._plotSeriesInfo=this.renderer.calcSeriesNumbers.call(this);var x=N[0];var v=N[1];var s=N[2];var H=[];if(this._stack){this._barNudge=0}else{this._barNudge=(-Math.abs(v/2-0.5)+s)*(this.barWidth+this.barPadding)}if(O){var u=new d.jqplot.ColorGenerator(this.negativeSeriesColors);var B=new d.jqplot.ColorGenerator(this.seriesColors);var M=u.get(this.index);if(!this.useNegativeColors){M=A.fillStyle}var t=A.fillStyle;var r;var P;var o;if(this.barDirection=="vertical"){for(var I=0;I<L.length;I++){if(!this._stack&&this.data[I][1]==null){continue}H=[];r=L[I][0]+this._barNudge;if(this._stack&&this._prevGridData.length){o=i(this.index,I,this._plotData[I][1],G,"y")}else{if(this.fillToZero){o=this._yaxis.series_u2p(0)}else{if(this.waterfall&&I>0&&I<this.gridData.length-1){o=this.gridData[I-1][1]}else{if(this.waterfall&&I==0&&I<this.gridData.length-1){if(this._yaxis.min<=0&&this._yaxis.max>=0){o=this._yaxis.series_u2p(0)}else{if(this._yaxis.min>0){o=E.canvas.height}else{o=0}}}else{if(this.waterfall&&I==this.gridData.length-1){if(this._yaxis.min<=0&&this._yaxis.max>=0){o=this._yaxis.series_u2p(0)}else{if(this._yaxis.min>0){o=E.canvas.height}else{o=0}}}else{o=E.canvas.height}}}}}if((this.fillToZero&&this._plotData[I][1]<0)||(this.waterfall&&this._data[I][1]<0)){if(this.varyBarColor&&!this._stack){if(this.useNegativeColors){A.fillStyle=u.next()}else{A.fillStyle=B.next()}}else{A.fillStyle=M}}else{if(this.varyBarColor&&!this._stack){A.fillStyle=B.next()}else{A.fillStyle=t}}if(!this.fillToZero||this._plotData[I][1]>=0){H.push([r-this.barWidth/2,o]);H.push([r-this.barWidth/2,L[I][1]]);H.push([r+this.barWidth/2,L[I][1]]);H.push([r+this.barWidth/2,o])}else{H.push([r-this.barWidth/2,L[I][1]]);H.push([r-this.barWidth/2,o]);H.push([r+this.barWidth/2,o]);H.push([r+this.barWidth/2,L[I][1]])}this._barPoints.push(H);if(w&&!this._stack){var z=d.extend(true,{},A);delete z.fillStyle;this.renderer.shadowRenderer.draw(E,H,z)}var n=A.fillStyle||this.color;this._dataColors.push(n);this.renderer.shapeRenderer.draw(E,H,A)}}else{if(this.barDirection=="horizontal"){for(var I=0;I<L.length;I++){if(!this._stack&&this.data[I][0]==null){continue}H=[];r=L[I][1]-this._barNudge;P;if(this._stack&&this._prevGridData.length){P=i(this.index,I,this._plotData[I][0],G,"x")}else{if(this.fillToZero){P=this._xaxis.series_u2p(0)}else{if(this.waterfall&&I>0&&I<this.gridData.length-1){P=this.gridData[I-1][0]}else{if(this.waterfall&&I==0&&I<this.gridData.length-1){if(this._xaxis.min<=0&&this._xaxis.max>=0){P=this._xaxis.series_u2p(0)}else{if(this._xaxis.min>0){P=0}else{P=0}}}else{if(this.waterfall&&I==this.gridData.length-1){if(this._xaxis.min<=0&&this._xaxis.max>=0){P=this._xaxis.series_u2p(0)}else{if(this._xaxis.min>0){P=0}else{P=E.canvas.width}}}else{P=0}}}}}if((this.fillToZero&&this._plotData[I][0]<0)||(this.waterfall&&this._data[I][0]<0)){if(this.varyBarColor&&!this._stack){if(this.useNegativeColors){A.fillStyle=u.next()}else{A.fillStyle=B.next()}}else{A.fillStyle=M}}else{if(this.varyBarColor&&!this._stack){A.fillStyle=B.next()}else{A.fillStyle=t}}if(!this.fillToZero||this._plotData[I][0]>=0){H.push([P,r+this.barWidth/2]);H.push([P,r-this.barWidth/2]);H.push([L[I][0],r-this.barWidth/2]);H.push([L[I][0],r+this.barWidth/2])}else{H.push([L[I][0],r+this.barWidth/2]);H.push([L[I][0],r-this.barWidth/2]);H.push([P,r-this.barWidth/2]);H.push([P,r+this.barWidth/2])}this._barPoints.push(H);if(w&&!this._stack){var z=d.extend(true,{},A);delete z.fillStyle;this.renderer.shadowRenderer.draw(E,H,z)}var n=A.fillStyle||this.color;this._dataColors.push(n);this.renderer.shapeRenderer.draw(E,H,A)}}}}if(this.highlightColors.length==0){this.highlightColors=d.jqplot.computeHighlightColors(this._dataColors)}else{if(typeof(this.highlightColors)=="string"){var N=this.highlightColors;this.highlightColors=[];for(var I=0;I<this._dataColors.length;I++){this.highlightColors.push(N)}}}};d.jqplot.BarRenderer.prototype.drawShadow=function(z,G,p,B){var D;var w=(p!=undefined)?p:{};var t=(w.shadow!=undefined)?w.shadow:this.shadow;var I=(w.showLine!=undefined)?w.showLine:this.showLine;var A=(w.fill!=undefined)?w.fill:this.fill;var o=this.xaxis;var E=this.yaxis;var v=this._xaxis.series_u2p;var F=this._yaxis.series_u2p;var y,C,x,u,s,r;if(this._stack&&this.shadow){if(this.barWidth==null){this.renderer.setBarWidth.call(this)}var H=this._plotSeriesInfo=this.renderer.calcSeriesNumbers.call(this);u=H[0];s=H[1];r=H[2];if(this._stack){this._barNudge=0}else{this._barNudge=(-Math.abs(s/2-0.5)+r)*(this.barWidth+this.barPadding)}if(I){if(this.barDirection=="vertical"){for(var D=0;D<G.length;D++){if(this.data[D][1]==null){continue}C=[];var q=G[D][0]+this._barNudge;var n;if(this._stack&&this._prevGridData.length){n=i(this.index,D,this._plotData[D][1],B,"y")}else{if(this.fillToZero){n=this._yaxis.series_u2p(0)}else{n=z.canvas.height}}C.push([q-this.barWidth/2,n]);C.push([q-this.barWidth/2,G[D][1]]);C.push([q+this.barWidth/2,G[D][1]]);C.push([q+this.barWidth/2,n]);this.renderer.shadowRenderer.draw(z,C,w)}}else{if(this.barDirection=="horizontal"){for(var D=0;D<G.length;D++){if(this.data[D][0]==null){continue}C=[];var q=G[D][1]-this._barNudge;var J;if(this._stack&&this._prevGridData.length){J=i(this.index,D,this._plotData[D][0],B,"x")}else{if(this.fillToZero){J=this._xaxis.series_u2p(0)}else{J=0}}C.push([J,q+this.barWidth/2]);C.push([G[D][0],q+this.barWidth/2]);C.push([G[D][0],q-this.barWidth/2]);C.push([J,q-this.barWidth/2]);this.renderer.shadowRenderer.draw(z,C,w)}}}}}};function h(q,p,n){for(var o=0;o<this.series.length;o++){if(this.series[o].renderer.constructor==d.jqplot.BarRenderer){if(this.series[o].highlightMouseOver){this.series[o].highlightMouseDown=false}}}}function j(){if(this.plugins.barRenderer&&this.plugins.barRenderer.highlightCanvas){this.plugins.barRenderer.highlightCanvas.resetCanvas();this.plugins.barRenderer.highlightCanvas=null}this.plugins.barRenderer={highlightedSeriesIndex:null};this.plugins.barRenderer.highlightCanvas=new d.jqplot.GenericCanvas();this.eventCanvas._elem.before(this.plugins.barRenderer.highlightCanvas.createElement(this._gridPadding,"jqplot-barRenderer-highlight-canvas",this._plotDimensions,this));this.plugins.barRenderer.highlightCanvas.setContext();this.eventCanvas._elem.bind("mouseleave",{plot:this},function(n){k(n.data.plot)})}function c(u,t,q,p){var o=u.series[t];var n=u.plugins.barRenderer.highlightCanvas;n._ctx.clearRect(0,0,n._ctx.canvas.width,n._ctx.canvas.height);o._highlightedPoint=q;u.plugins.barRenderer.highlightedSeriesIndex=t;var r={fillStyle:o.highlightColors[q]};o.renderer.shapeRenderer.draw(n._ctx,p,r);n=null}function k(p){var n=p.plugins.barRenderer.highlightCanvas;n._ctx.clearRect(0,0,n._ctx.canvas.width,n._ctx.canvas.height);for(var o=0;o<p.series.length;o++){p.series[o]._highlightedPoint=null}p.plugins.barRenderer.highlightedSeriesIndex=null;p.target.trigger("jqplotDataUnhighlight");n=null}function b(r,q,u,t,s){if(t){var p=[t.seriesIndex,t.pointIndex,t.data];var o=jQuery.Event("jqplotDataMouseOver");o.pageX=r.pageX;o.pageY=r.pageY;s.target.trigger(o,p);if(s.series[p[0]].highlightMouseOver&&!(p[0]==s.plugins.barRenderer.highlightedSeriesIndex&&p[1]==s.series[p[0]]._highlightedPoint)){var n=jQuery.Event("jqplotDataHighlight");n.which=r.which;n.pageX=r.pageX;n.pageY=r.pageY;s.target.trigger(n,p);c(s,t.seriesIndex,t.pointIndex,t.points)}}else{if(t==null){k(s)}}}function a(q,p,t,s,r){if(s){var o=[s.seriesIndex,s.pointIndex,s.data];if(r.series[o[0]].highlightMouseDown&&!(o[0]==r.plugins.barRenderer.highlightedSeriesIndex&&o[1]==r.series[o[0]]._highlightedPoint)){var n=jQuery.Event("jqplotDataHighlight");n.which=q.which;n.pageX=q.pageX;n.pageY=q.pageY;r.target.trigger(n,o);c(r,s.seriesIndex,s.pointIndex,s.points)}}else{if(s==null){k(r)}}}function l(p,o,s,r,q){var n=q.plugins.barRenderer.highlightedSeriesIndex;if(n!=null&&q.series[n].highlightMouseDown){k(q)}}function e(q,p,t,s,r){if(s){var o=[s.seriesIndex,s.pointIndex,s.data];var n=jQuery.Event("jqplotDataClick");n.which=q.which;n.pageX=q.pageX;n.pageY=q.pageY;r.target.trigger(n,o)}}function m(r,q,u,t,s){if(t){var p=[t.seriesIndex,t.pointIndex,t.data];var n=s.plugins.barRenderer.highlightedSeriesIndex;if(n!=null&&s.series[n].highlightMouseDown){k(s)}var o=jQuery.Event("jqplotDataRightClick");o.which=r.which;o.pageX=r.pageX;o.pageY=r.pageY;s.target.trigger(o,p)}}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(a){a.jqplot.CanvasAxisLabelRenderer=function(b){this.angle=0;this.axis;this.show=true;this.showLabel=true;this.label="";this.fontFamily='"Trebuchet MS", Arial, Helvetica, sans-serif';this.fontSize="11pt";this.fontWeight="normal";this.fontStretch=1;this.textColor="#666666";this.enableFontSupport=true;this.pt2px=null;this._elem;this._ctx;this._plotWidth;this._plotHeight;this._plotDimensions={height:null,width:null};a.extend(true,this,b);if(b.angle==null&&this.axis!="xaxis"&&this.axis!="x2axis"){this.angle=-90}var c={fontSize:this.fontSize,fontWeight:this.fontWeight,fontStretch:this.fontStretch,fillStyle:this.textColor,angle:this.getAngleRad(),fontFamily:this.fontFamily};if(this.pt2px){c.pt2px=this.pt2px}if(this.enableFontSupport){if(a.jqplot.support_canvas_text()){this._textRenderer=new a.jqplot.CanvasFontRenderer(c)}else{this._textRenderer=new a.jqplot.CanvasTextRenderer(c)}}else{this._textRenderer=new a.jqplot.CanvasTextRenderer(c)}};a.jqplot.CanvasAxisLabelRenderer.prototype.init=function(b){a.extend(true,this,b);this._textRenderer.init({fontSize:this.fontSize,fontWeight:this.fontWeight,fontStretch:this.fontStretch,fillStyle:this.textColor,angle:this.getAngleRad(),fontFamily:this.fontFamily})};a.jqplot.CanvasAxisLabelRenderer.prototype.getWidth=function(d){if(this._elem){return this._elem.outerWidth(true)}else{var f=this._textRenderer;var c=f.getWidth(d);var e=f.getHeight(d);var b=Math.abs(Math.sin(f.angle)*e)+Math.abs(Math.cos(f.angle)*c);return b}};a.jqplot.CanvasAxisLabelRenderer.prototype.getHeight=function(d){if(this._elem){return this._elem.outerHeight(true)}else{var f=this._textRenderer;var c=f.getWidth(d);var e=f.getHeight(d);var b=Math.abs(Math.cos(f.angle)*e)+Math.abs(Math.sin(f.angle)*c);return b}};a.jqplot.CanvasAxisLabelRenderer.prototype.getAngleRad=function(){var b=this.angle*Math.PI/180;return b};a.jqplot.CanvasAxisLabelRenderer.prototype.draw=function(c,f){if(this._elem){if(a.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==undefined){window.G_vmlCanvasManager.uninitElement(this._elem.get(0))}this._elem.emptyForce();this._elem=null}var e=f.canvasManager.getCanvas();this._textRenderer.setText(this.label,c);var b=this.getWidth(c);var d=this.getHeight(c);e.width=b;e.height=d;e.style.width=b;e.style.height=d;e=f.canvasManager.initCanvas(e);this._elem=a(e);this._elem.css({position:"absolute"});this._elem.addClass("jqplot-"+this.axis+"-label");e=null;return this._elem};a.jqplot.CanvasAxisLabelRenderer.prototype.pack=function(){this._textRenderer.draw(this._elem.get(0).getContext("2d"),this.label)}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(a){a.jqplot.CanvasAxisTickRenderer=function(b){this.mark="outside";this.showMark=true;this.showGridline=true;this.isMinorTick=false;this.angle=0;this.markSize=4;this.show=true;this.showLabel=true;this.labelPosition="auto";this.label="";this.value=null;this._styles={};this.formatter=a.jqplot.DefaultTickFormatter;this.formatString="";this.prefix="";this.fontFamily='"Trebuchet MS", Arial, Helvetica, sans-serif';this.fontSize="10pt";this.fontWeight="normal";this.fontStretch=1;this.textColor="#666666";this.enableFontSupport=true;this.pt2px=null;this._elem;this._ctx;this._plotWidth;this._plotHeight;this._plotDimensions={height:null,width:null};a.extend(true,this,b);var c={fontSize:this.fontSize,fontWeight:this.fontWeight,fontStretch:this.fontStretch,fillStyle:this.textColor,angle:this.getAngleRad(),fontFamily:this.fontFamily};if(this.pt2px){c.pt2px=this.pt2px}if(this.enableFontSupport){if(a.jqplot.support_canvas_text()){this._textRenderer=new a.jqplot.CanvasFontRenderer(c)}else{this._textRenderer=new a.jqplot.CanvasTextRenderer(c)}}else{this._textRenderer=new a.jqplot.CanvasTextRenderer(c)}};a.jqplot.CanvasAxisTickRenderer.prototype.init=function(b){a.extend(true,this,b);this._textRenderer.init({fontSize:this.fontSize,fontWeight:this.fontWeight,fontStretch:this.fontStretch,fillStyle:this.textColor,angle:this.getAngleRad(),fontFamily:this.fontFamily})};a.jqplot.CanvasAxisTickRenderer.prototype.getWidth=function(d){if(this._elem){return this._elem.outerWidth(true)}else{var f=this._textRenderer;var c=f.getWidth(d);var e=f.getHeight(d);var b=Math.abs(Math.sin(f.angle)*e)+Math.abs(Math.cos(f.angle)*c);return b}};a.jqplot.CanvasAxisTickRenderer.prototype.getHeight=function(d){if(this._elem){return this._elem.outerHeight(true)}else{var f=this._textRenderer;var c=f.getWidth(d);var e=f.getHeight(d);var b=Math.abs(Math.cos(f.angle)*e)+Math.abs(Math.sin(f.angle)*c);return b}};a.jqplot.CanvasAxisTickRenderer.prototype.getTop=function(b){if(this._elem){return this._elem.position().top}else{return null}};a.jqplot.CanvasAxisTickRenderer.prototype.getAngleRad=function(){var b=this.angle*Math.PI/180;return b};a.jqplot.CanvasAxisTickRenderer.prototype.setTick=function(b,d,c){this.value=b;if(c){this.isMinorTick=true}return this};a.jqplot.CanvasAxisTickRenderer.prototype.draw=function(c,f){if(!this.label){this.label=this.prefix+this.formatter(this.formatString,this.value)}if(this._elem){if(a.jqplot.use_excanvas&&window.G_vmlCanvasManager.uninitElement!==undefined){window.G_vmlCanvasManager.uninitElement(this._elem.get(0))}this._elem.emptyForce();this._elem=null}var e=f.canvasManager.getCanvas();this._textRenderer.setText(this.label,c);var b=this.getWidth(c);var d=this.getHeight(c);e.width=b;e.height=d;e.style.width=b;e.style.height=d;e.style.textAlign="left";e.style.position="absolute";e=f.canvasManager.initCanvas(e);this._elem=a(e);this._elem.css(this._styles);this._elem.addClass("jqplot-"+this.axis+"-tick");e=null;return this._elem};a.jqplot.CanvasAxisTickRenderer.prototype.pack=function(){this._textRenderer.draw(this._elem.get(0).getContext("2d"),this.label)}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(a){a.jqplot.CanvasTextRenderer=function(b){this.fontStyle="normal";this.fontVariant="normal";this.fontWeight="normal";this.fontSize="10px";this.fontFamily="sans-serif";this.fontStretch=1;this.fillStyle="#666666";this.angle=0;this.textAlign="start";this.textBaseline="alphabetic";this.text;this.width;this.height;this.pt2px=1.28;a.extend(true,this,b);this.normalizedFontSize=this.normalizeFontSize(this.fontSize);this.setHeight()};a.jqplot.CanvasTextRenderer.prototype.init=function(b){a.extend(true,this,b);this.normalizedFontSize=this.normalizeFontSize(this.fontSize);this.setHeight()};a.jqplot.CanvasTextRenderer.prototype.normalizeFontSize=function(b){b=String(b);var c=parseFloat(b);if(b.indexOf("px")>-1){return c/this.pt2px}else{if(b.indexOf("pt")>-1){return c}else{if(b.indexOf("em")>-1){return c*12}else{if(b.indexOf("%")>-1){return c*12/100}else{return c/this.pt2px}}}}};a.jqplot.CanvasTextRenderer.prototype.fontWeight2Float=function(b){if(Number(b)){return b/400}else{switch(b){case"normal":return 1;break;case"bold":return 1.75;break;case"bolder":return 2.25;break;case"lighter":return 0.75;break;default:return 1;break}}};a.jqplot.CanvasTextRenderer.prototype.getText=function(){return this.text};a.jqplot.CanvasTextRenderer.prototype.setText=function(c,b){this.text=c;this.setWidth(b);return this};a.jqplot.CanvasTextRenderer.prototype.getWidth=function(b){return this.width};a.jqplot.CanvasTextRenderer.prototype.setWidth=function(c,b){if(!b){this.width=this.measure(c,this.text)}else{this.width=b}return this};a.jqplot.CanvasTextRenderer.prototype.getHeight=function(b){return this.height};a.jqplot.CanvasTextRenderer.prototype.setHeight=function(b){if(!b){this.height=this.normalizedFontSize*this.pt2px}else{this.height=b}return this};a.jqplot.CanvasTextRenderer.prototype.letter=function(b){return this.letters[b]};a.jqplot.CanvasTextRenderer.prototype.ascent=function(){return this.normalizedFontSize};a.jqplot.CanvasTextRenderer.prototype.descent=function(){return 7*this.normalizedFontSize/25};a.jqplot.CanvasTextRenderer.prototype.measure=function(d,g){var f=0;var b=g.length;for(var e=0;e<b;e++){var h=this.letter(g.charAt(e));if(h){f+=h.width*this.normalizedFontSize/25*this.fontStretch}}return f};a.jqplot.CanvasTextRenderer.prototype.draw=function(s,n){var r=0;var o=this.height*0.72;var p=0;var l=n.length;var k=this.normalizedFontSize/25;s.save();var h,f;if((-Math.PI/2<=this.angle&&this.angle<=0)||(Math.PI*3/2<=this.angle&&this.angle<=Math.PI*2)){h=0;f=-Math.sin(this.angle)*this.width}else{if((0<this.angle&&this.angle<=Math.PI/2)||(-Math.PI*2<=this.angle&&this.angle<=-Math.PI*3/2)){h=Math.sin(this.angle)*this.height;f=0}else{if((-Math.PI<this.angle&&this.angle<-Math.PI/2)||(Math.PI<=this.angle&&this.angle<=Math.PI*3/2)){h=-Math.cos(this.angle)*this.width;f=-Math.sin(this.angle)*this.width-Math.cos(this.angle)*this.height}else{if((-Math.PI*3/2<this.angle&&this.angle<Math.PI)||(Math.PI/2<this.angle&&this.angle<Math.PI)){h=Math.sin(this.angle)*this.height-Math.cos(this.angle)*this.width;f=-Math.cos(this.angle)*this.height}}}}s.strokeStyle=this.fillStyle;s.fillStyle=this.fillStyle;s.translate(h,f);s.rotate(this.angle);s.lineCap="round";var t=(this.normalizedFontSize>30)?2:2+(30-this.normalizedFontSize)/20;s.lineWidth=t*k*this.fontWeight2Float(this.fontWeight);for(var g=0;g<l;g++){var m=this.letter(n.charAt(g));if(!m){continue}s.beginPath();var e=1;var b=0;for(var d=0;d<m.points.length;d++){var q=m.points[d];if(q[0]==-1&&q[1]==-1){e=1;continue}if(e){s.moveTo(r+q[0]*k*this.fontStretch,o-q[1]*k);e=false}else{s.lineTo(r+q[0]*k*this.fontStretch,o-q[1]*k)}}s.stroke();r+=m.width*k*this.fontStretch}s.restore();return p};a.jqplot.CanvasTextRenderer.prototype.letters={" ":{width:16,points:[]},"!":{width:10,points:[[5,21],[5,7],[-1,-1],[5,2],[4,1],[5,0],[6,1],[5,2]]},'"':{width:16,points:[[4,21],[4,14],[-1,-1],[12,21],[12,14]]},"#":{width:21,points:[[11,25],[4,-7],[-1,-1],[17,25],[10,-7],[-1,-1],[4,12],[18,12],[-1,-1],[3,6],[17,6]]},"$":{width:20,points:[[8,25],[8,-4],[-1,-1],[12,25],[12,-4],[-1,-1],[17,18],[15,20],[12,21],[8,21],[5,20],[3,18],[3,16],[4,14],[5,13],[7,12],[13,10],[15,9],[16,8],[17,6],[17,3],[15,1],[12,0],[8,0],[5,1],[3,3]]},"%":{width:24,points:[[21,21],[3,0],[-1,-1],[8,21],[10,19],[10,17],[9,15],[7,14],[5,14],[3,16],[3,18],[4,20],[6,21],[8,21],[10,20],[13,19],[16,19],[19,20],[21,21],[-1,-1],[17,7],[15,6],[14,4],[14,2],[16,0],[18,0],[20,1],[21,3],[21,5],[19,7],[17,7]]},"&":{width:26,points:[[23,12],[23,13],[22,14],[21,14],[20,13],[19,11],[17,6],[15,3],[13,1],[11,0],[7,0],[5,1],[4,2],[3,4],[3,6],[4,8],[5,9],[12,13],[13,14],[14,16],[14,18],[13,20],[11,21],[9,20],[8,18],[8,16],[9,13],[11,10],[16,3],[18,1],[20,0],[22,0],[23,1],[23,2]]},"'":{width:10,points:[[5,19],[4,20],[5,21],[6,20],[6,18],[5,16],[4,15]]},"(":{width:14,points:[[11,25],[9,23],[7,20],[5,16],[4,11],[4,7],[5,2],[7,-2],[9,-5],[11,-7]]},")":{width:14,points:[[3,25],[5,23],[7,20],[9,16],[10,11],[10,7],[9,2],[7,-2],[5,-5],[3,-7]]},"*":{width:16,points:[[8,21],[8,9],[-1,-1],[3,18],[13,12],[-1,-1],[13,18],[3,12]]},"+":{width:26,points:[[13,18],[13,0],[-1,-1],[4,9],[22,9]]},",":{width:10,points:[[6,1],[5,0],[4,1],[5,2],[6,1],[6,-1],[5,-3],[4,-4]]},"-":{width:18,points:[[6,9],[12,9]]},".":{width:10,points:[[5,2],[4,1],[5,0],[6,1],[5,2]]},"/":{width:22,points:[[20,25],[2,-7]]},"0":{width:20,points:[[9,21],[6,20],[4,17],[3,12],[3,9],[4,4],[6,1],[9,0],[11,0],[14,1],[16,4],[17,9],[17,12],[16,17],[14,20],[11,21],[9,21]]},"1":{width:20,points:[[6,17],[8,18],[11,21],[11,0]]},"2":{width:20,points:[[4,16],[4,17],[5,19],[6,20],[8,21],[12,21],[14,20],[15,19],[16,17],[16,15],[15,13],[13,10],[3,0],[17,0]]},"3":{width:20,points:[[5,21],[16,21],[10,13],[13,13],[15,12],[16,11],[17,8],[17,6],[16,3],[14,1],[11,0],[8,0],[5,1],[4,2],[3,4]]},"4":{width:20,points:[[13,21],[3,7],[18,7],[-1,-1],[13,21],[13,0]]},"5":{width:20,points:[[15,21],[5,21],[4,12],[5,13],[8,14],[11,14],[14,13],[16,11],[17,8],[17,6],[16,3],[14,1],[11,0],[8,0],[5,1],[4,2],[3,4]]},"6":{width:20,points:[[16,18],[15,20],[12,21],[10,21],[7,20],[5,17],[4,12],[4,7],[5,3],[7,1],[10,0],[11,0],[14,1],[16,3],[17,6],[17,7],[16,10],[14,12],[11,13],[10,13],[7,12],[5,10],[4,7]]},"7":{width:20,points:[[17,21],[7,0],[-1,-1],[3,21],[17,21]]},"8":{width:20,points:[[8,21],[5,20],[4,18],[4,16],[5,14],[7,13],[11,12],[14,11],[16,9],[17,7],[17,4],[16,2],[15,1],[12,0],[8,0],[5,1],[4,2],[3,4],[3,7],[4,9],[6,11],[9,12],[13,13],[15,14],[16,16],[16,18],[15,20],[12,21],[8,21]]},"9":{width:20,points:[[16,14],[15,11],[13,9],[10,8],[9,8],[6,9],[4,11],[3,14],[3,15],[4,18],[6,20],[9,21],[10,21],[13,20],[15,18],[16,14],[16,9],[15,4],[13,1],[10,0],[8,0],[5,1],[4,3]]},":":{width:10,points:[[5,14],[4,13],[5,12],[6,13],[5,14],[-1,-1],[5,2],[4,1],[5,0],[6,1],[5,2]]},";":{width:10,points:[[5,14],[4,13],[5,12],[6,13],[5,14],[-1,-1],[6,1],[5,0],[4,1],[5,2],[6,1],[6,-1],[5,-3],[4,-4]]},"<":{width:24,points:[[20,18],[4,9],[20,0]]},"=":{width:26,points:[[4,12],[22,12],[-1,-1],[4,6],[22,6]]},">":{width:24,points:[[4,18],[20,9],[4,0]]},"?":{width:18,points:[[3,16],[3,17],[4,19],[5,20],[7,21],[11,21],[13,20],[14,19],[15,17],[15,15],[14,13],[13,12],[9,10],[9,7],[-1,-1],[9,2],[8,1],[9,0],[10,1],[9,2]]},"@":{width:27,points:[[18,13],[17,15],[15,16],[12,16],[10,15],[9,14],[8,11],[8,8],[9,6],[11,5],[14,5],[16,6],[17,8],[-1,-1],[12,16],[10,14],[9,11],[9,8],[10,6],[11,5],[-1,-1],[18,16],[17,8],[17,6],[19,5],[21,5],[23,7],[24,10],[24,12],[23,15],[22,17],[20,19],[18,20],[15,21],[12,21],[9,20],[7,19],[5,17],[4,15],[3,12],[3,9],[4,6],[5,4],[7,2],[9,1],[12,0],[15,0],[18,1],[20,2],[21,3],[-1,-1],[19,16],[18,8],[18,6],[19,5]]},A:{width:18,points:[[9,21],[1,0],[-1,-1],[9,21],[17,0],[-1,-1],[4,7],[14,7]]},B:{width:21,points:[[4,21],[4,0],[-1,-1],[4,21],[13,21],[16,20],[17,19],[18,17],[18,15],[17,13],[16,12],[13,11],[-1,-1],[4,11],[13,11],[16,10],[17,9],[18,7],[18,4],[17,2],[16,1],[13,0],[4,0]]},C:{width:21,points:[[18,16],[17,18],[15,20],[13,21],[9,21],[7,20],[5,18],[4,16],[3,13],[3,8],[4,5],[5,3],[7,1],[9,0],[13,0],[15,1],[17,3],[18,5]]},D:{width:21,points:[[4,21],[4,0],[-1,-1],[4,21],[11,21],[14,20],[16,18],[17,16],[18,13],[18,8],[17,5],[16,3],[14,1],[11,0],[4,0]]},E:{width:19,points:[[4,21],[4,0],[-1,-1],[4,21],[17,21],[-1,-1],[4,11],[12,11],[-1,-1],[4,0],[17,0]]},F:{width:18,points:[[4,21],[4,0],[-1,-1],[4,21],[17,21],[-1,-1],[4,11],[12,11]]},G:{width:21,points:[[18,16],[17,18],[15,20],[13,21],[9,21],[7,20],[5,18],[4,16],[3,13],[3,8],[4,5],[5,3],[7,1],[9,0],[13,0],[15,1],[17,3],[18,5],[18,8],[-1,-1],[13,8],[18,8]]},H:{width:22,points:[[4,21],[4,0],[-1,-1],[18,21],[18,0],[-1,-1],[4,11],[18,11]]},I:{width:8,points:[[4,21],[4,0]]},J:{width:16,points:[[12,21],[12,5],[11,2],[10,1],[8,0],[6,0],[4,1],[3,2],[2,5],[2,7]]},K:{width:21,points:[[4,21],[4,0],[-1,-1],[18,21],[4,7],[-1,-1],[9,12],[18,0]]},L:{width:17,points:[[4,21],[4,0],[-1,-1],[4,0],[16,0]]},M:{width:24,points:[[4,21],[4,0],[-1,-1],[4,21],[12,0],[-1,-1],[20,21],[12,0],[-1,-1],[20,21],[20,0]]},N:{width:22,points:[[4,21],[4,0],[-1,-1],[4,21],[18,0],[-1,-1],[18,21],[18,0]]},O:{width:22,points:[[9,21],[7,20],[5,18],[4,16],[3,13],[3,8],[4,5],[5,3],[7,1],[9,0],[13,0],[15,1],[17,3],[18,5],[19,8],[19,13],[18,16],[17,18],[15,20],[13,21],[9,21]]},P:{width:21,points:[[4,21],[4,0],[-1,-1],[4,21],[13,21],[16,20],[17,19],[18,17],[18,14],[17,12],[16,11],[13,10],[4,10]]},Q:{width:22,points:[[9,21],[7,20],[5,18],[4,16],[3,13],[3,8],[4,5],[5,3],[7,1],[9,0],[13,0],[15,1],[17,3],[18,5],[19,8],[19,13],[18,16],[17,18],[15,20],[13,21],[9,21],[-1,-1],[12,4],[18,-2]]},R:{width:21,points:[[4,21],[4,0],[-1,-1],[4,21],[13,21],[16,20],[17,19],[18,17],[18,15],[17,13],[16,12],[13,11],[4,11],[-1,-1],[11,11],[18,0]]},S:{width:20,points:[[17,18],[15,20],[12,21],[8,21],[5,20],[3,18],[3,16],[4,14],[5,13],[7,12],[13,10],[15,9],[16,8],[17,6],[17,3],[15,1],[12,0],[8,0],[5,1],[3,3]]},T:{width:16,points:[[8,21],[8,0],[-1,-1],[1,21],[15,21]]},U:{width:22,points:[[4,21],[4,6],[5,3],[7,1],[10,0],[12,0],[15,1],[17,3],[18,6],[18,21]]},V:{width:18,points:[[1,21],[9,0],[-1,-1],[17,21],[9,0]]},W:{width:24,points:[[2,21],[7,0],[-1,-1],[12,21],[7,0],[-1,-1],[12,21],[17,0],[-1,-1],[22,21],[17,0]]},X:{width:20,points:[[3,21],[17,0],[-1,-1],[17,21],[3,0]]},Y:{width:18,points:[[1,21],[9,11],[9,0],[-1,-1],[17,21],[9,11]]},Z:{width:20,points:[[17,21],[3,0],[-1,-1],[3,21],[17,21],[-1,-1],[3,0],[17,0]]},"[":{width:14,points:[[4,25],[4,-7],[-1,-1],[5,25],[5,-7],[-1,-1],[4,25],[11,25],[-1,-1],[4,-7],[11,-7]]},"\\":{width:14,points:[[0,21],[14,-3]]},"]":{width:14,points:[[9,25],[9,-7],[-1,-1],[10,25],[10,-7],[-1,-1],[3,25],[10,25],[-1,-1],[3,-7],[10,-7]]},"^":{width:16,points:[[6,15],[8,18],[10,15],[-1,-1],[3,12],[8,17],[13,12],[-1,-1],[8,17],[8,0]]},_:{width:16,points:[[0,-2],[16,-2]]},"`":{width:10,points:[[6,21],[5,20],[4,18],[4,16],[5,15],[6,16],[5,17]]},a:{width:19,points:[[15,14],[15,0],[-1,-1],[15,11],[13,13],[11,14],[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3]]},b:{width:19,points:[[4,21],[4,0],[-1,-1],[4,11],[6,13],[8,14],[11,14],[13,13],[15,11],[16,8],[16,6],[15,3],[13,1],[11,0],[8,0],[6,1],[4,3]]},c:{width:18,points:[[15,11],[13,13],[11,14],[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3]]},d:{width:19,points:[[15,21],[15,0],[-1,-1],[15,11],[13,13],[11,14],[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3]]},e:{width:18,points:[[3,8],[15,8],[15,10],[14,12],[13,13],[11,14],[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3]]},f:{width:12,points:[[10,21],[8,21],[6,20],[5,17],[5,0],[-1,-1],[2,14],[9,14]]},g:{width:19,points:[[15,14],[15,-2],[14,-5],[13,-6],[11,-7],[8,-7],[6,-6],[-1,-1],[15,11],[13,13],[11,14],[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3]]},h:{width:19,points:[[4,21],[4,0],[-1,-1],[4,10],[7,13],[9,14],[12,14],[14,13],[15,10],[15,0]]},i:{width:8,points:[[3,21],[4,20],[5,21],[4,22],[3,21],[-1,-1],[4,14],[4,0]]},j:{width:10,points:[[5,21],[6,20],[7,21],[6,22],[5,21],[-1,-1],[6,14],[6,-3],[5,-6],[3,-7],[1,-7]]},k:{width:17,points:[[4,21],[4,0],[-1,-1],[14,14],[4,4],[-1,-1],[8,8],[15,0]]},l:{width:8,points:[[4,21],[4,0]]},m:{width:30,points:[[4,14],[4,0],[-1,-1],[4,10],[7,13],[9,14],[12,14],[14,13],[15,10],[15,0],[-1,-1],[15,10],[18,13],[20,14],[23,14],[25,13],[26,10],[26,0]]},n:{width:19,points:[[4,14],[4,0],[-1,-1],[4,10],[7,13],[9,14],[12,14],[14,13],[15,10],[15,0]]},o:{width:19,points:[[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3],[16,6],[16,8],[15,11],[13,13],[11,14],[8,14]]},p:{width:19,points:[[4,14],[4,-7],[-1,-1],[4,11],[6,13],[8,14],[11,14],[13,13],[15,11],[16,8],[16,6],[15,3],[13,1],[11,0],[8,0],[6,1],[4,3]]},q:{width:19,points:[[15,14],[15,-7],[-1,-1],[15,11],[13,13],[11,14],[8,14],[6,13],[4,11],[3,8],[3,6],[4,3],[6,1],[8,0],[11,0],[13,1],[15,3]]},r:{width:13,points:[[4,14],[4,0],[-1,-1],[4,8],[5,11],[7,13],[9,14],[12,14]]},s:{width:17,points:[[14,11],[13,13],[10,14],[7,14],[4,13],[3,11],[4,9],[6,8],[11,7],[13,6],[14,4],[14,3],[13,1],[10,0],[7,0],[4,1],[3,3]]},t:{width:12,points:[[5,21],[5,4],[6,1],[8,0],[10,0],[-1,-1],[2,14],[9,14]]},u:{width:19,points:[[4,14],[4,4],[5,1],[7,0],[10,0],[12,1],[15,4],[-1,-1],[15,14],[15,0]]},v:{width:16,points:[[2,14],[8,0],[-1,-1],[14,14],[8,0]]},w:{width:22,points:[[3,14],[7,0],[-1,-1],[11,14],[7,0],[-1,-1],[11,14],[15,0],[-1,-1],[19,14],[15,0]]},x:{width:17,points:[[3,14],[14,0],[-1,-1],[14,14],[3,0]]},y:{width:16,points:[[2,14],[8,0],[-1,-1],[14,14],[8,0],[6,-4],[4,-6],[2,-7],[1,-7]]},z:{width:17,points:[[14,14],[3,0],[-1,-1],[3,14],[14,14],[-1,-1],[3,0],[14,0]]},"{":{width:14,points:[[9,25],[7,24],[6,23],[5,21],[5,19],[6,17],[7,16],[8,14],[8,12],[6,10],[-1,-1],[7,24],[6,22],[6,20],[7,18],[8,17],[9,15],[9,13],[8,11],[4,9],[8,7],[9,5],[9,3],[8,1],[7,0],[6,-2],[6,-4],[7,-6],[-1,-1],[6,8],[8,6],[8,4],[7,2],[6,1],[5,-1],[5,-3],[6,-5],[7,-6],[9,-7]]},"|":{width:8,points:[[4,25],[4,-7]]},"}":{width:14,points:[[5,25],[7,24],[8,23],[9,21],[9,19],[8,17],[7,16],[6,14],[6,12],[8,10],[-1,-1],[7,24],[8,22],[8,20],[7,18],[6,17],[5,15],[5,13],[6,11],[10,9],[6,7],[5,5],[5,3],[6,1],[7,0],[8,-2],[8,-4],[7,-6],[-1,-1],[8,8],[6,6],[6,4],[7,2],[8,1],[9,-1],[9,-3],[8,-5],[7,-6],[5,-7]]},"~":{width:24,points:[[3,6],[3,8],[4,11],[6,12],[8,12],[10,11],[14,8],[16,7],[18,7],[20,8],[21,10],[-1,-1],[3,8],[4,10],[6,11],[8,11],[10,10],[14,7],[16,6],[18,6],[20,7],[21,10],[21,12]]}};a.jqplot.CanvasFontRenderer=function(b){b=b||{};if(!b.pt2px){b.pt2px=1.5}a.jqplot.CanvasTextRenderer.call(this,b)};a.jqplot.CanvasFontRenderer.prototype=new a.jqplot.CanvasTextRenderer({});a.jqplot.CanvasFontRenderer.prototype.constructor=a.jqplot.CanvasFontRenderer;a.jqplot.CanvasFontRenderer.prototype.measure=function(c,e){var d=this.fontSize+" "+this.fontFamily;c.save();c.font=d;var b=c.measureText(e).width;c.restore();return b};a.jqplot.CanvasFontRenderer.prototype.draw=function(e,g){var c=0;var h=this.height*0.72;e.save();var d,b;if((-Math.PI/2<=this.angle&&this.angle<=0)||(Math.PI*3/2<=this.angle&&this.angle<=Math.PI*2)){d=0;b=-Math.sin(this.angle)*this.width}else{if((0<this.angle&&this.angle<=Math.PI/2)||(-Math.PI*2<=this.angle&&this.angle<=-Math.PI*3/2)){d=Math.sin(this.angle)*this.height;b=0}else{if((-Math.PI<this.angle&&this.angle<-Math.PI/2)||(Math.PI<=this.angle&&this.angle<=Math.PI*3/2)){d=-Math.cos(this.angle)*this.width;b=-Math.sin(this.angle)*this.width-Math.cos(this.angle)*this.height}else{if((-Math.PI*3/2<this.angle&&this.angle<Math.PI)||(Math.PI/2<this.angle&&this.angle<Math.PI)){d=Math.sin(this.angle)*this.height-Math.cos(this.angle)*this.width;b=-Math.cos(this.angle)*this.height}}}}e.strokeStyle=this.fillStyle;e.fillStyle=this.fillStyle;var f=this.fontSize+" "+this.fontFamily;e.font=f;e.translate(d,b);e.rotate(this.angle);e.fillText(g,c,h);e.restore()}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(j){j.jqplot.Cursor=function(q){this.style="crosshair";this.previousCursor="auto";this.show=j.jqplot.config.enablePlugins;this.showTooltip=true;this.followMouse=false;this.tooltipLocation="se";this.tooltipOffset=6;this.showTooltipGridPosition=false;this.showTooltipUnitPosition=true;this.showTooltipDataPosition=false;this.tooltipFormatString="%.4P, %.4P";this.useAxesFormatters=true;this.tooltipAxisGroups=[];this.zoom=false;this.zoomProxy=false;this.zoomTarget=false;this.looseZoom=true;this.clickReset=false;this.dblClickReset=true;this.showVerticalLine=false;this.showHorizontalLine=false;this.constrainZoomTo="none";this.shapeRenderer=new j.jqplot.ShapeRenderer();this._zoom={start:[],end:[],started:false,zooming:false,isZoomed:false,axes:{start:{},end:{}},gridpos:{},datapos:{}};this._tooltipElem;this.zoomCanvas;this.cursorCanvas;this.intersectionThreshold=2;this.showCursorLegend=false;this.cursorLegendFormatString=j.jqplot.Cursor.cursorLegendFormatString;this._oldHandlers={onselectstart:null,ondrag:null,onmousedown:null};this.constrainOutsideZoom=true;this.showTooltipOutsideZoom=false;this.onGrid=false;j.extend(true,this,q)};j.jqplot.Cursor.cursorLegendFormatString="%s x:%s, y:%s";j.jqplot.Cursor.init=function(t,s,r){var q=r||{};this.plugins.cursor=new j.jqplot.Cursor(q.cursor);var u=this.plugins.cursor;if(u.show){j.jqplot.eventListenerHooks.push(["jqplotMouseEnter",b]);j.jqplot.eventListenerHooks.push(["jqplotMouseLeave",f]);j.jqplot.eventListenerHooks.push(["jqplotMouseMove",i]);if(u.showCursorLegend){r.legend=r.legend||{};r.legend.renderer=j.jqplot.CursorLegendRenderer;r.legend.formatString=this.plugins.cursor.cursorLegendFormatString;r.legend.show=true}if(u.zoom){j.jqplot.eventListenerHooks.push(["jqplotMouseDown",a]);if(u.clickReset){j.jqplot.eventListenerHooks.push(["jqplotClick",k])}if(u.dblClickReset){j.jqplot.eventListenerHooks.push(["jqplotDblClick",c])}}this.resetZoom=function(){var x=this.axes;if(!u.zoomProxy){for(var w in x){x[w].reset();x[w]._ticks=[];if(u._zoom.axes[w]!==undefined){x[w]._autoFormatString=u._zoom.axes[w].tickFormatString}}this.redraw()}else{var v=this.plugins.cursor.zoomCanvas._ctx;v.clearRect(0,0,v.canvas.width,v.canvas.height);v=null}this.plugins.cursor._zoom.isZoomed=false;this.target.trigger("jqplotResetZoom",[this,this.plugins.cursor])};if(u.showTooltipDataPosition){u.showTooltipUnitPosition=false;u.showTooltipGridPosition=false;if(q.cursor.tooltipFormatString==undefined){u.tooltipFormatString=j.jqplot.Cursor.cursorLegendFormatString}}}};j.jqplot.Cursor.postDraw=function(){var x=this.plugins.cursor;if(x.zoomCanvas){x.zoomCanvas.resetCanvas();x.zoomCanvas=null}if(x.cursorCanvas){x.cursorCanvas.resetCanvas();x.cursorCanvas=null}if(x._tooltipElem){x._tooltipElem.emptyForce();x._tooltipElem=null}if(x.zoom){x.zoomCanvas=new j.jqplot.GenericCanvas();this.eventCanvas._elem.before(x.zoomCanvas.createElement(this._gridPadding,"jqplot-zoom-canvas",this._plotDimensions,this));x.zoomCanvas.setContext()}var v=document.createElement("div");x._tooltipElem=j(v);v=null;x._tooltipElem.addClass("jqplot-cursor-tooltip");x._tooltipElem.css({position:"absolute",display:"none"});if(x.zoomCanvas){x.zoomCanvas._elem.before(x._tooltipElem)}else{this.eventCanvas._elem.before(x._tooltipElem)}if(x.showVerticalLine||x.showHorizontalLine){x.cursorCanvas=new j.jqplot.GenericCanvas();this.eventCanvas._elem.before(x.cursorCanvas.createElement(this._gridPadding,"jqplot-cursor-canvas",this._plotDimensions,this));x.cursorCanvas.setContext()}if(x.showTooltipUnitPosition){if(x.tooltipAxisGroups.length===0){var t=this.series;var u;var q=[];for(var r=0;r<t.length;r++){u=t[r];var w=u.xaxis+","+u.yaxis;if(j.inArray(w,q)==-1){q.push(w)}}for(var r=0;r<q.length;r++){x.tooltipAxisGroups.push(q[r].split(","))}}}};j.jqplot.Cursor.zoomProxy=function(v,r){var q=v.plugins.cursor;var u=r.plugins.cursor;q.zoomTarget=true;q.zoom=true;q.style="auto";q.dblClickReset=false;u.zoom=true;u.zoomProxy=true;r.target.bind("jqplotZoom",t);r.target.bind("jqplotResetZoom",s);function t(x,w,z,y,A){q.doZoom(w,z,v,A)}function s(w,x,y){v.resetZoom()}};j.jqplot.Cursor.prototype.resetZoom=function(u,v){var t=u.axes;var s=v._zoom.axes;if(!u.plugins.cursor.zoomProxy&&v._zoom.isZoomed){for(var r in t){t[r].reset();t[r]._ticks=[];t[r]._autoFormatString=s[r].tickFormatString}u.redraw();v._zoom.isZoomed=false}else{var q=v.zoomCanvas._ctx;q.clearRect(0,0,q.canvas.width,q.canvas.height);q=null}u.target.trigger("jqplotResetZoom",[u,v])};j.jqplot.Cursor.resetZoom=function(q){q.resetZoom()};j.jqplot.Cursor.prototype.doZoom=function(G,t,C,u){var I=u;var F=C.axes;var r=I._zoom.axes;var w=r.start;var s=r.end;var B,E,z,D,v,x,q,H,J;var A=C.plugins.cursor.zoomCanvas._ctx;if((I.constrainZoomTo=="none"&&Math.abs(G.x-I._zoom.start[0])>6&&Math.abs(G.y-I._zoom.start[1])>6)||(I.constrainZoomTo=="x"&&Math.abs(G.x-I._zoom.start[0])>6)||(I.constrainZoomTo=="y"&&Math.abs(G.y-I._zoom.start[1])>6)){if(!C.plugins.cursor.zoomProxy){for(var y in t){if(I._zoom.axes[y]==undefined){I._zoom.axes[y]={};I._zoom.axes[y].numberTicks=F[y].numberTicks;I._zoom.axes[y].tickInterval=F[y].tickInterval;I._zoom.axes[y].daTickInterval=F[y].daTickInterval;I._zoom.axes[y].min=F[y].min;I._zoom.axes[y].max=F[y].max;I._zoom.axes[y].tickFormatString=(F[y].tickOptions!=null)?F[y].tickOptions.formatString:""}if((I.constrainZoomTo=="none")||(I.constrainZoomTo=="x"&&y.charAt(0)=="x")||(I.constrainZoomTo=="y"&&y.charAt(0)=="y")){z=t[y];if(z!=null){if(z>w[y]){v=w[y];x=z}else{D=w[y]-z;v=z;x=w[y]}q=F[y];H=null;if(q.alignTicks){if(q.name==="x2axis"&&C.axes.xaxis.show){H=C.axes.xaxis.numberTicks}else{if(q.name.charAt(0)==="y"&&q.name!=="yaxis"&&q.name!=="yMidAxis"&&C.axes.yaxis.show){H=C.axes.yaxis.numberTicks}}}if(this.looseZoom&&(F[y].renderer.constructor===j.jqplot.LinearAxisRenderer||F[y].renderer.constructor===j.jqplot.LogAxisRenderer)){J=j.jqplot.LinearTickGenerator(v,x,q._scalefact,H);if(F[y].tickInset&&J[0]<F[y].min+F[y].tickInset*F[y].tickInterval){J[0]+=J[4];J[2]-=1}if(F[y].tickInset&&J[1]>F[y].max-F[y].tickInset*F[y].tickInterval){J[1]-=J[4];J[2]-=1}if(F[y].renderer.constructor===j.jqplot.LogAxisRenderer&&J[0]<F[y].min){J[0]+=J[4];J[2]-=1}F[y].min=J[0];F[y].max=J[1];F[y]._autoFormatString=J[3];F[y].numberTicks=J[2];F[y].tickInterval=J[4];F[y].daTickInterval=[J[4]/1000,"seconds"]}else{F[y].min=v;F[y].max=x;F[y].tickInterval=null;F[y].numberTicks=null;F[y].daTickInterval=null}F[y]._ticks=[]}}}A.clearRect(0,0,A.canvas.width,A.canvas.height);C.redraw();I._zoom.isZoomed=true;A=null}C.target.trigger("jqplotZoom",[G,t,C,u])}};j.jqplot.preInitHooks.push(j.jqplot.Cursor.init);j.jqplot.postDrawHooks.push(j.jqplot.Cursor.postDraw);function e(G,r,C){var J=C.plugins.cursor;var w="";var N=false;if(J.showTooltipGridPosition){w=G.x+", "+G.y;N=true}if(J.showTooltipUnitPosition){var F;for(var E=0;E<J.tooltipAxisGroups.length;E++){F=J.tooltipAxisGroups[E];if(N){w+="<br />"}if(J.useAxesFormatters){for(var D=0;D<F.length;D++){if(D){w+=", "}var H=C.axes[F[D]]._ticks[0].formatter;var B=C.axes[F[D]]._ticks[0].formatString;w+=H(B,r[F[D]])}}else{w+=j.jqplot.sprintf(J.tooltipFormatString,r[F[0]],r[F[1]])}N=true}}if(J.showTooltipDataPosition){var u=C.series;var M=d(C,G.x,G.y);var N=false;for(var E=0;E<u.length;E++){if(u[E].show){var y=u[E].index;var t=u[E].label.toString();var I=j.inArray(y,M.indices);var z=undefined;var x=undefined;if(I!=-1){var L=M.data[I].data;if(J.useAxesFormatters){var A=u[E]._xaxis._ticks[0].formatter;var q=u[E]._yaxis._ticks[0].formatter;var K=u[E]._xaxis._ticks[0].formatString;var v=u[E]._yaxis._ticks[0].formatString;z=A(K,L[0]);x=q(v,L[1])}else{z=L[0];x=L[1]}if(N){w+="<br />"}w+=j.jqplot.sprintf(J.tooltipFormatString,t,z,x);N=true}}}}J._tooltipElem.html(w)}function g(C,A){var E=A.plugins.cursor;var z=E.cursorCanvas._ctx;z.clearRect(0,0,z.canvas.width,z.canvas.height);if(E.showVerticalLine){E.shapeRenderer.draw(z,[[C.x,0],[C.x,z.canvas.height]])}if(E.showHorizontalLine){E.shapeRenderer.draw(z,[[0,C.y],[z.canvas.width,C.y]])}var G=d(A,C.x,C.y);if(E.showCursorLegend){var r=j(A.targetId+" td.jqplot-cursor-legend-label");for(var B=0;B<r.length;B++){var v=j(r[B]).data("seriesIndex");var t=A.series[v];var s=t.label.toString();var D=j.inArray(v,G.indices);var x=undefined;var w=undefined;if(D!=-1){var H=G.data[D].data;if(E.useAxesFormatters){var y=t._xaxis._ticks[0].formatter;var q=t._yaxis._ticks[0].formatter;var F=t._xaxis._ticks[0].formatString;var u=t._yaxis._ticks[0].formatString;x=y(F,H[0]);w=q(u,H[1])}else{x=H[0];w=H[1]}}if(A.legend.escapeHtml){j(r[B]).text(j.jqplot.sprintf(E.cursorLegendFormatString,s,x,w))}else{j(r[B]).html(j.jqplot.sprintf(E.cursorLegendFormatString,s,x,w))}}}z=null}function d(A,F,E){var B={indices:[],data:[]};var G,w,u,C,v,q,t;var z;var D=A.plugins.cursor;for(var w=0;w<A.series.length;w++){G=A.series[w];q=G.renderer;if(G.show){z=D.intersectionThreshold;if(G.showMarker){z+=G.markerRenderer.size/2}for(var v=0;v<G.gridData.length;v++){t=G.gridData[v];if(D.showVerticalLine){if(Math.abs(F-t[0])<=z){B.indices.push(w);B.data.push({seriesIndex:w,pointIndex:v,gridData:t,data:G.data[v]})}}}}}return B}function n(r,t){var v=t.plugins.cursor;var s=v._tooltipElem;switch(v.tooltipLocation){case"nw":var q=r.x+t._gridPadding.left-s.outerWidth(true)-v.tooltipOffset;var u=r.y+t._gridPadding.top-v.tooltipOffset-s.outerHeight(true);break;case"n":var q=r.x+t._gridPadding.left-s.outerWidth(true)/2;var u=r.y+t._gridPadding.top-v.tooltipOffset-s.outerHeight(true);break;case"ne":var q=r.x+t._gridPadding.left+v.tooltipOffset;var u=r.y+t._gridPadding.top-v.tooltipOffset-s.outerHeight(true);break;case"e":var q=r.x+t._gridPadding.left+v.tooltipOffset;var u=r.y+t._gridPadding.top-s.outerHeight(true)/2;break;case"se":var q=r.x+t._gridPadding.left+v.tooltipOffset;var u=r.y+t._gridPadding.top+v.tooltipOffset;break;case"s":var q=r.x+t._gridPadding.left-s.outerWidth(true)/2;var u=r.y+t._gridPadding.top+v.tooltipOffset;break;case"sw":var q=r.x+t._gridPadding.left-s.outerWidth(true)-v.tooltipOffset;var u=r.y+t._gridPadding.top+v.tooltipOffset;break;case"w":var q=r.x+t._gridPadding.left-s.outerWidth(true)-v.tooltipOffset;var u=r.y+t._gridPadding.top-s.outerHeight(true)/2;break;default:var q=r.x+t._gridPadding.left+v.tooltipOffset;var u=r.y+t._gridPadding.top+v.tooltipOffset;break}s.css("left",q);s.css("top",u);s=null}function m(u){var s=u._gridPadding;var v=u.plugins.cursor;var t=v._tooltipElem;switch(v.tooltipLocation){case"nw":var r=s.left+v.tooltipOffset;var q=s.top+v.tooltipOffset;t.css("left",r);t.css("top",q);break;case"n":var r=(s.left+(u._plotDimensions.width-s.right))/2-t.outerWidth(true)/2;var q=s.top+v.tooltipOffset;t.css("left",r);t.css("top",q);break;case"ne":var r=s.right+v.tooltipOffset;var q=s.top+v.tooltipOffset;t.css({right:r,top:q});break;case"e":var r=s.right+v.tooltipOffset;var q=(s.top+(u._plotDimensions.height-s.bottom))/2-t.outerHeight(true)/2;t.css({right:r,top:q});break;case"se":var r=s.right+v.tooltipOffset;var q=s.bottom+v.tooltipOffset;t.css({right:r,bottom:q});break;case"s":var r=(s.left+(u._plotDimensions.width-s.right))/2-t.outerWidth(true)/2;var q=s.bottom+v.tooltipOffset;t.css({left:r,bottom:q});break;case"sw":var r=s.left+v.tooltipOffset;var q=s.bottom+v.tooltipOffset;t.css({left:r,bottom:q});break;case"w":var r=s.left+v.tooltipOffset;var q=(s.top+(u._plotDimensions.height-s.bottom))/2-t.outerHeight(true)/2;t.css({left:r,top:q});break;default:var r=s.right-v.tooltipOffset;var q=s.bottom+v.tooltipOffset;t.css({right:r,bottom:q});break}t=null}function k(r,q,v,u,t){r.preventDefault();r.stopImmediatePropagation();var w=t.plugins.cursor;if(w.clickReset){w.resetZoom(t,w)}var s=window.getSelection;if(document.selection&&document.selection.empty){document.selection.empty()}else{if(s&&!s().isCollapsed){s().collapse()}}return false}function c(r,q,v,u,t){r.preventDefault();r.stopImmediatePropagation();var w=t.plugins.cursor;if(w.dblClickReset){w.resetZoom(t,w)}var s=window.getSelection;if(document.selection&&document.selection.empty){document.selection.empty()}else{if(s&&!s().isCollapsed){s().collapse()}}return false}function f(w,t,q,z,u){var v=u.plugins.cursor;v.onGrid=false;if(v.show){j(w.target).css("cursor",v.previousCursor);if(v.showTooltip&&!(v._zoom.zooming&&v.showTooltipOutsideZoom&&!v.constrainOutsideZoom)){v._tooltipElem.empty();v._tooltipElem.hide()}if(v.zoom){v._zoom.gridpos=t;v._zoom.datapos=q}if(v.showVerticalLine||v.showHorizontalLine){var B=v.cursorCanvas._ctx;B.clearRect(0,0,B.canvas.width,B.canvas.height);B=null}if(v.showCursorLegend){var A=j(u.targetId+" td.jqplot-cursor-legend-label");for(var s=0;s<A.length;s++){var y=j(A[s]).data("seriesIndex");var r=u.series[y];var x=r.label.toString();if(u.legend.escapeHtml){j(A[s]).text(j.jqplot.sprintf(v.cursorLegendFormatString,x,undefined,undefined))}else{j(A[s]).html(j.jqplot.sprintf(v.cursorLegendFormatString,x,undefined,undefined))}}}}}function b(r,q,u,t,s){var v=s.plugins.cursor;v.onGrid=true;if(v.show){v.previousCursor=r.target.style.cursor;r.target.style.cursor=v.style;if(v.showTooltip){e(q,u,s);if(v.followMouse){n(q,s)}else{m(s)}v._tooltipElem.show()}if(v.showVerticalLine||v.showHorizontalLine){g(q,s)}}}function i(r,q,u,t,s){var v=s.plugins.cursor;if(v.show){if(v.showTooltip){e(q,u,s);if(v.followMouse){n(q,s)}}if(v.showVerticalLine||v.showHorizontalLine){g(q,s)}}}function o(y){var x=y.data.plot;var t=x.eventCanvas._elem.offset();var w={x:y.pageX-t.left,y:y.pageY-t.top};var u={xaxis:null,yaxis:null,x2axis:null,y2axis:null,y3axis:null,y4axis:null,y5axis:null,y6axis:null,y7axis:null,y8axis:null,y9axis:null,yMidAxis:null};var v=["xaxis","yaxis","x2axis","y2axis","y3axis","y4axis","y5axis","y6axis","y7axis","y8axis","y9axis","yMidAxis"];var q=x.axes;var r,s;for(r=11;r>0;r--){s=v[r-1];if(q[s].show){u[s]=q[s].series_p2u(w[s.charAt(0)])}}return{offsets:t,gridPos:w,dataPos:u}}function h(z){var x=z.data.plot;var y=x.plugins.cursor;if(y.show&&y.zoom&&y._zoom.started&&!y.zoomTarget){z.preventDefault();var B=y.zoomCanvas._ctx;var v=o(z);var w=v.gridPos;var t=v.dataPos;y._zoom.gridpos=w;y._zoom.datapos=t;y._zoom.zooming=true;var u=w.x;var s=w.y;var A=B.canvas.height;var q=B.canvas.width;if(y.showTooltip&&!y.onGrid&&y.showTooltipOutsideZoom){e(w,t,x);if(y.followMouse){n(w,x)}}if(y.constrainZoomTo=="x"){y._zoom.end=[u,A]}else{if(y.constrainZoomTo=="y"){y._zoom.end=[q,s]}else{y._zoom.end=[u,s]}}var r=window.getSelection;if(document.selection&&document.selection.empty){document.selection.empty()}else{if(r&&!r().isCollapsed){r().collapse()}}l.call(y);B=null}}function a(w,s,r,x,t){var v=t.plugins.cursor;if(t.plugins.mobile){j(document).one("vmouseup.jqplot_cursor",{plot:t},p)}else{j(document).one("mouseup.jqplot_cursor",{plot:t},p)}var u=t.axes;if(document.onselectstart!=undefined){v._oldHandlers.onselectstart=document.onselectstart;document.onselectstart=function(){return false}}if(document.ondrag!=undefined){v._oldHandlers.ondrag=document.ondrag;document.ondrag=function(){return false}}if(document.onmousedown!=undefined){v._oldHandlers.onmousedown=document.onmousedown;document.onmousedown=function(){return false}}if(v.zoom){if(!v.zoomProxy){var y=v.zoomCanvas._ctx;y.clearRect(0,0,y.canvas.width,y.canvas.height);y=null}if(v.constrainZoomTo=="x"){v._zoom.start=[s.x,0]}else{if(v.constrainZoomTo=="y"){v._zoom.start=[0,s.y]}else{v._zoom.start=[s.x,s.y]}}v._zoom.started=true;for(var q in r){v._zoom.axes.start[q]=r[q]}if(t.plugins.mobile){j(document).bind("vmousemove.jqplotCursor",{plot:t},h)}else{j(document).bind("mousemove.jqplotCursor",{plot:t},h)}}}function p(y){var v=y.data.plot;var x=v.plugins.cursor;if(x.zoom&&x._zoom.zooming&&!x.zoomTarget){var u=x._zoom.gridpos.x;var r=x._zoom.gridpos.y;var t=x._zoom.datapos;var z=x.zoomCanvas._ctx.canvas.height;var q=x.zoomCanvas._ctx.canvas.width;var w=v.axes;if(x.constrainOutsideZoom&&!x.onGrid){if(u<0){u=0}else{if(u>q){u=q}}if(r<0){r=0}else{if(r>z){r=z}}for(var s in t){if(t[s]){if(s.charAt(0)=="x"){t[s]=w[s].series_p2u(u)}else{t[s]=w[s].series_p2u(r)}}}}if(x.constrainZoomTo=="x"){r=z}else{if(x.constrainZoomTo=="y"){u=q}}x._zoom.end=[u,r];x._zoom.gridpos={x:u,y:r};x.doZoom(x._zoom.gridpos,t,v,x)}x._zoom.started=false;x._zoom.zooming=false;j(document).unbind("mousemove.jqplotCursor",h);if(document.onselectstart!=undefined&&x._oldHandlers.onselectstart!=null){document.onselectstart=x._oldHandlers.onselectstart;x._oldHandlers.onselectstart=null}if(document.ondrag!=undefined&&x._oldHandlers.ondrag!=null){document.ondrag=x._oldHandlers.ondrag;x._oldHandlers.ondrag=null}if(document.onmousedown!=undefined&&x._oldHandlers.onmousedown!=null){document.onmousedown=x._oldHandlers.onmousedown;x._oldHandlers.onmousedown=null}}function l(){var y=this._zoom.start;var u=this._zoom.end;var s=this.zoomCanvas._ctx;var r,v,x,q;if(u[0]>y[0]){r=y[0];q=u[0]-y[0]}else{r=u[0];q=y[0]-u[0]}if(u[1]>y[1]){v=y[1];x=u[1]-y[1]}else{v=u[1];x=y[1]-u[1]}s.fillStyle="rgba(0,0,0,0.2)";s.strokeStyle="#999999";s.lineWidth=1;s.clearRect(0,0,s.canvas.width,s.canvas.height);s.fillRect(0,0,s.canvas.width,s.canvas.height);s.clearRect(r,v,q,x);s.strokeRect(r,v,q,x);s=null}j.jqplot.CursorLegendRenderer=function(q){j.jqplot.TableLegendRenderer.call(this,q);this.formatString="%s"};j.jqplot.CursorLegendRenderer.prototype=new j.jqplot.TableLegendRenderer();j.jqplot.CursorLegendRenderer.prototype.constructor=j.jqplot.CursorLegendRenderer;j.jqplot.CursorLegendRenderer.prototype.draw=function(){if(this._elem){this._elem.emptyForce();this._elem=null}if(this.show){var w=this._series,A;var r=document.createElement("div");this._elem=j(r);r=null;this._elem.addClass("jqplot-legend jqplot-cursor-legend");this._elem.css("position","absolute");var q=false;for(var x=0;x<w.length;x++){A=w[x];if(A.show&&A.showLabel){var v=j.jqplot.sprintf(this.formatString,A.label.toString());if(v){var t=A.color;if(A._stack&&!A.fill){t=""}z.call(this,v,t,q,x);q=true}for(var u=0;u<j.jqplot.addLegendRowHooks.length;u++){var y=j.jqplot.addLegendRowHooks[u].call(this,A);if(y){z.call(this,y.label,y.color,q);q=true}}}}w=A=null;delete w;delete A}function z(D,C,F,s){var B=(F)?this.rowSpacing:"0";var E=j('<tr class="jqplot-legend jqplot-cursor-legend"></tr>').appendTo(this._elem);E.data("seriesIndex",s);j('<td class="jqplot-legend jqplot-cursor-legend-swatch" style="padding-top:'+B+';"><div style="border:1px solid #cccccc;padding:0.2em;"><div class="jqplot-cursor-legend-swatch" style="background-color:'+C+';"></div></div></td>').appendTo(E);var G=j('<td class="jqplot-legend jqplot-cursor-legend-label" style="vertical-align:middle;padding-top:'+B+';"></td>');G.appendTo(E);G.data("seriesIndex",s);if(this.escapeHtml){G.text(D)}else{G.html(D)}E=null;G=null}return this._elem}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(h){h.jqplot.DateAxisRenderer=function(){h.jqplot.LinearAxisRenderer.call(this);this.date=new h.jsDate()};var c=1000;var e=60*c;var f=60*e;var l=24*f;var b=7*l;var j=30.4368499*l;var k=365.242199*l;var g=[31,28,31,30,31,30,31,30,31,30,31,30];var i=["%M:%S.%#N","%M:%S.%#N","%M:%S.%#N","%M:%S","%M:%S","%M:%S","%M:%S","%H:%M:%S","%H:%M:%S","%H:%M","%H:%M","%H:%M","%H:%M","%H:%M","%H:%M","%a %H:%M","%a %H:%M","%b %e %H:%M","%b %e %H:%M","%b %e %H:%M","%b %e %H:%M","%v","%v","%v","%v","%v","%v","%v"];var m=[0.1*c,0.2*c,0.5*c,c,2*c,5*c,10*c,15*c,30*c,e,2*e,5*e,10*e,15*e,30*e,f,2*f,4*f,6*f,8*f,12*f,l,2*l,3*l,4*l,5*l,b,2*b];var d=[];function a(p,s,t){var o=Number.MAX_VALUE;var u,r,v;for(var q=0,n=m.length;q<n;q++){u=Math.abs(t-m[q]);if(u<o){o=u;r=m[q];v=i[q]}}return[r,v]}h.jqplot.DateAxisRenderer.prototype=new h.jqplot.LinearAxisRenderer();h.jqplot.DateAxisRenderer.prototype.constructor=h.jqplot.DateAxisRenderer;h.jqplot.DateTickFormatter=function(n,o){if(!n){n="%Y/%m/%d"}return h.jsDate.strftime(o,n)};h.jqplot.DateAxisRenderer.prototype.init=function(E){this.tickOptions.formatter=h.jqplot.DateTickFormatter;this.tickInset=0;this.drawBaseline=true;this.baselineWidth=null;this.baselineColor=null;this.daTickInterval=null;this._daTickInterval=null;h.extend(true,this,E);var C=this._dataBounds,u,x,D,y,A,z,o;for(var t=0;t<this._series.length;t++){u={intervals:[],frequencies:{},sortedIntervals:[],min:null,max:null,mean:null};x=0;D=this._series[t];y=D.data;A=D._plotData;z=D._stackData;o=0;for(var r=0;r<y.length;r++){if(this.name=="xaxis"||this.name=="x2axis"){y[r][0]=new h.jsDate(y[r][0]).getTime();A[r][0]=new h.jsDate(y[r][0]).getTime();z[r][0]=new h.jsDate(y[r][0]).getTime();if((y[r][0]!=null&&y[r][0]<C.min)||C.min==null){C.min=y[r][0]}if((y[r][0]!=null&&y[r][0]>C.max)||C.max==null){C.max=y[r][0]}if(r>0){o=Math.abs(y[r][0]-y[r-1][0]);u.intervals.push(o);if(u.frequencies.hasOwnProperty(o)){u.frequencies[o]+=1}else{u.frequencies[o]=1}}x+=o}else{y[r][1]=new h.jsDate(y[r][1]).getTime();A[r][1]=new h.jsDate(y[r][1]).getTime();z[r][1]=new h.jsDate(y[r][1]).getTime();if((y[r][1]!=null&&y[r][1]<C.min)||C.min==null){C.min=y[r][1]}if((y[r][1]!=null&&y[r][1]>C.max)||C.max==null){C.max=y[r][1]}if(r>0){o=Math.abs(y[r][1]-y[r-1][1]);u.intervals.push(o);if(u.frequencies.hasOwnProperty(o)){u.frequencies[o]+=1}else{u.frequencies[o]=1}}}x+=o}if(D.renderer.bands){if(D.renderer.bands.hiData.length){var w=D.renderer.bands.hiData;for(var r=0,q=w.length;r<q;r++){if(this.name==="xaxis"||this.name==="x2axis"){w[r][0]=new h.jsDate(w[r][0]).getTime();if((w[r][0]!=null&&w[r][0]>C.max)||C.max==null){C.max=w[r][0]}}else{w[r][1]=new h.jsDate(w[r][1]).getTime();if((w[r][1]!=null&&w[r][1]>C.max)||C.max==null){C.max=w[r][1]}}}}if(D.renderer.bands.lowData.length){var w=D.renderer.bands.lowData;for(var r=0,q=w.length;r<q;r++){if(this.name==="xaxis"||this.name==="x2axis"){w[r][0]=new h.jsDate(w[r][0]).getTime();if((w[r][0]!=null&&w[r][0]<C.min)||C.min==null){C.min=w[r][0]}}else{w[r][1]=new h.jsDate(w[r][1]).getTime();if((w[r][1]!=null&&w[r][1]<C.min)||C.min==null){C.min=w[r][1]}}}}}var B=0,v=0;for(var p in u.frequencies){u.sortedIntervals.push({interval:p,frequency:u.frequencies[p]})}u.sortedIntervals.sort(function(s,n){return n.frequency-s.frequency});u.min=h.jqplot.arrayMin(u.intervals);u.max=h.jqplot.arrayMax(u.intervals);u.mean=x/y.length;this._intervalStats.push(u);u=x=D=y=A=z=null}C=null};h.jqplot.DateAxisRenderer.prototype.reset=function(){this.min=this._options.min;this.max=this._options.max;this.tickInterval=this._options.tickInterval;this.numberTicks=this._options.numberTicks;this._autoFormatString="";if(this._overrideFormatString&&this.tickOptions&&this.tickOptions.formatString){this.tickOptions.formatString=""}this.daTickInterval=this._daTickInterval};h.jqplot.DateAxisRenderer.prototype.createTicks=function(p){var X=this._ticks;var L=this.ticks;var F=this.name;var H=this._dataBounds;var M=this._intervalStats;var n=(this.name.charAt(0)==="x")?this._plotDimensions.width:this._plotDimensions.height;var w;var ae,J;var y,x;var ad,aa;var s=30;var O=1;var U=null;if(this.tickInterval!=null){if(Number(this.tickInterval)){U=[Number(this.tickInterval),"seconds"]}else{if(typeof this.tickInterval=="string"){var ac=this.tickInterval.split(" ");if(ac.length==1){U=[1,ac[0]]}else{if(ac.length==2){U=[ac[0],ac[1]]}}}}}var v=this.tickInterval;ae=new h.jsDate((this.min!=null)?this.min:H.min).getTime();J=new h.jsDate((this.max!=null)?this.max:H.max).getTime();var A=p.plugins.cursor;if(A&&A._zoom&&A._zoom.zooming){this.min=null;this.max=null}var B=J-ae;if(this.tickOptions==null||!this.tickOptions.formatString){this._overrideFormatString=true}if(L.length){for(aa=0;aa<L.length;aa++){var P=L[aa];var Y=new this.tickRenderer(this.tickOptions);if(P.constructor==Array){Y.value=new h.jsDate(P[0]).getTime();Y.label=P[1];if(!this.showTicks){Y.showLabel=false;Y.showMark=false}else{if(!this.showTickMarks){Y.showMark=false}}Y.setTick(Y.value,this.name);this._ticks.push(Y)}else{Y.value=new h.jsDate(P).getTime();if(!this.showTicks){Y.showLabel=false;Y.showMark=false}else{if(!this.showTickMarks){Y.showMark=false}}Y.setTick(Y.value,this.name);this._ticks.push(Y)}}this.numberTicks=L.length;this.min=this._ticks[0].value;this.max=this._ticks[this.numberTicks-1].value;this.daTickInterval=[(this.max-this.min)/(this.numberTicks-1)/1000,"seconds"]}else{if(this.min==null&&this.max==null&&H.min==H.max){var E=h.extend(true,{},this.tickOptions,{name:this.name,value:null});var T=300000;this.min=H.min-T;this.max=H.max+T;this.numberTicks=3;for(var aa=this.min;aa<=this.max;aa+=T){E.value=aa;var Y=new this.tickRenderer(E);if(this._overrideFormatString&&this._autoFormatString!=""){Y.formatString=this._autoFormatString}Y.showLabel=false;Y.showMark=false;this._ticks.push(Y)}if(this.showTicks){this._ticks[1].showLabel=true}if(this.showTickMarks){this._ticks[1].showTickMarks=true}}else{if(this.min==null&&this.max==null){var N=h.extend(true,{},this.tickOptions,{name:this.name,value:null});var ab,I;if(!this.tickInterval&&!this.numberTicks){var R=Math.max(n,s+1);var Z=115;if(this.tickRenderer===h.jqplot.CanvasAxisTickRenderer&&this.tickOptions.angle){Z=115-40*Math.abs(Math.sin(this.tickOptions.angle/180*Math.PI))}ab=Math.ceil((R-s)/Z+1);I=(J-ae)/(ab-1)}else{if(this.tickInterval){I=new h.jsDate(0).add(U[0],U[1]).getTime()}else{if(this.numberTicks){ab=this.numberTicks;I=(J-ae)/(ab-1)}}}if(I<=19*l){var Q=a(ae,J,I);var r=Q[0];this._autoFormatString=Q[1];ae=new h.jsDate(ae);ae=Math.floor((ae.getTime()-ae.getUtcOffset())/r)*r+ae.getUtcOffset();ab=Math.ceil((J-ae)/r)+1;this.min=ae;this.max=ae+(ab-1)*r;if(this.max<J){this.max+=r;ab+=1}this.tickInterval=r;this.numberTicks=ab;for(var aa=0;aa<ab;aa++){N.value=this.min+aa*r;Y=new this.tickRenderer(N);if(this._overrideFormatString&&this._autoFormatString!=""){Y.formatString=this._autoFormatString}if(!this.showTicks){Y.showLabel=false;Y.showMark=false}else{if(!this.showTickMarks){Y.showMark=false}}this._ticks.push(Y)}O=this.tickInterval}else{if(I<=9*j){this._autoFormatString="%v";var D=Math.round(I/j);if(D<1){D=1}else{if(D>6){D=6}}var V=new h.jsDate(ae).setDate(1).setHours(0,0,0,0);var q=new h.jsDate(J);var z=new h.jsDate(J).setDate(1).setHours(0,0,0,0);if(q.getTime()!==z.getTime()){z=z.add(1,"month")}var S=z.diff(V,"month");ab=Math.ceil(S/D)+1;this.min=V.getTime();this.max=V.clone().add((ab-1)*D,"month").getTime();this.numberTicks=ab;for(var aa=0;aa<ab;aa++){if(aa===0){N.value=V.getTime()}else{N.value=V.add(D,"month").getTime()}Y=new this.tickRenderer(N);if(this._overrideFormatString&&this._autoFormatString!=""){Y.formatString=this._autoFormatString}if(!this.showTicks){Y.showLabel=false;Y.showMark=false}else{if(!this.showTickMarks){Y.showMark=false}}this._ticks.push(Y)}O=D*j}else{this._autoFormatString="%v";var D=Math.round(I/k);if(D<1){D=1}var V=new h.jsDate(ae).setMonth(0,1).setHours(0,0,0,0);var z=new h.jsDate(J).add(1,"year").setMonth(0,1).setHours(0,0,0,0);var K=z.diff(V,"year");ab=Math.ceil(K/D)+1;this.min=V.getTime();this.max=V.clone().add((ab-1)*D,"year").getTime();this.numberTicks=ab;for(var aa=0;aa<ab;aa++){if(aa===0){N.value=V.getTime()}else{N.value=V.add(D,"year").getTime()}Y=new this.tickRenderer(N);if(this._overrideFormatString&&this._autoFormatString!=""){Y.formatString=this._autoFormatString}if(!this.showTicks){Y.showLabel=false;Y.showMark=false}else{if(!this.showTickMarks){Y.showMark=false}}this._ticks.push(Y)}O=D*k}}}else{if(F=="xaxis"||F=="x2axis"){n=this._plotDimensions.width}else{n=this._plotDimensions.height}if(this.min!=null&&this.max!=null&&this.numberTicks!=null){this.tickInterval=null}if(this.tickInterval!=null&&U!=null){this.daTickInterval=U}if(ae==J){var o=24*60*60*500;ae-=o;J+=o}B=J-ae;var G=2+parseInt(Math.max(0,n-100)/100,10);var W,C;W=(this.min!=null)?new h.jsDate(this.min).getTime():ae-B/2*(this.padMin-1);C=(this.max!=null)?new h.jsDate(this.max).getTime():J+B/2*(this.padMax-1);this.min=W;this.max=C;B=this.max-this.min;if(this.numberTicks==null){if(this.daTickInterval!=null){var u=new h.jsDate(this.max).diff(this.min,this.daTickInterval[1],true);this.numberTicks=Math.ceil(u/this.daTickInterval[0])+1;this.max=new h.jsDate(this.min).add((this.numberTicks-1)*this.daTickInterval[0],this.daTickInterval[1]).getTime()}else{if(n>200){this.numberTicks=parseInt(3+(n-200)/100,10)}else{this.numberTicks=2}}}O=B/(this.numberTicks-1)/1000;if(this.daTickInterval==null){this.daTickInterval=[O,"seconds"]}for(var aa=0;aa<this.numberTicks;aa++){var ae=new h.jsDate(this.min);ad=ae.add(aa*this.daTickInterval[0],this.daTickInterval[1]).getTime();var Y=new this.tickRenderer(this.tickOptions);if(!this.showTicks){Y.showLabel=false;Y.showMark=false}else{if(!this.showTickMarks){Y.showMark=false}}Y.setTick(ad,this.name);this._ticks.push(Y)}}}}if(this.tickInset){this.min=this.min-this.tickInset*O;this.max=this.max+this.tickInset*O}if(this._daTickInterval==null){this._daTickInterval=this.daTickInterval}X=null}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(a){a.jqplot.LogAxisRenderer=function(){a.jqplot.LinearAxisRenderer.call(this);this.axisDefaults={base:10,tickDistribution:"power"}};a.jqplot.LogAxisRenderer.prototype=new a.jqplot.LinearAxisRenderer();a.jqplot.LogAxisRenderer.prototype.constructor=a.jqplot.LogAxisRenderer;a.jqplot.LogAxisRenderer.prototype.init=function(b){this.drawBaseline=true;this.minorTicks="auto";this._scalefact=1;a.extend(true,this,b);this._autoFormatString="%d";this._overrideFormatString=false;for(var c in this.renderer.axisDefaults){if(this[c]==null){this[c]=this.renderer.axisDefaults[c]}}this.resetDataBounds()};a.jqplot.LogAxisRenderer.prototype.createTicks=function(d){var G=this._ticks;var w=this.ticks;var s=this.name;var u=this._dataBounds;var b=(this.name.charAt(0)==="x")?this._plotDimensions.width:this._plotDimensions.height;var k;var N,v;var m,l;var M,K;var g=30;this._scalefact=(Math.max(b,g+1)-g)/300;if(w.length){for(K=0;K<w.length;K++){var A=w[K];var H=new this.tickRenderer(this.tickOptions);if(A.constructor==Array){H.value=A[0];H.label=A[1];if(!this.showTicks){H.showLabel=false;H.showMark=false}else{if(!this.showTickMarks){H.showMark=false}}H.setTick(A[0],this.name);this._ticks.push(H)}else{if(a.isPlainObject(A)){a.extend(true,H,A);H.axis=this.name;this._ticks.push(H)}else{H.value=A;if(!this.showTicks){H.showLabel=false;H.showMark=false}else{if(!this.showTickMarks){H.showMark=false}}H.setTick(A,this.name);this._ticks.push(H)}}}this.numberTicks=w.length;this.min=this._ticks[0].value;this.max=this._ticks[this.numberTicks-1].value}else{if(this.min==null&&this.max==null){N=u.min*(2-this.padMin);v=u.max*this.padMax;if(N==v){var c=0.05;N=N*(1-c);v=v*(1+c)}if(this.min!=null&&this.min<=0){throw ("log axis minimum must be greater than 0")}if(this.max!=null&&this.max<=0){throw ("log axis maximum must be greater than 0")}function f(j){var i=Math.pow(10,Math.floor(Math.log(j)/Math.LN10));return Math.ceil(j/i)*i}function x(j){var i=Math.pow(10,Math.floor(Math.log(j)/Math.LN10));return Math.floor(j/i)*i}var F,r;F=Math.pow(this.base,Math.floor(Math.log(N)/Math.log(this.base)));r=Math.pow(this.base,Math.ceil(Math.log(v)/Math.log(this.base)));if(F===0){F=1}var E=Math.round(Math.log(F)/Math.LN10);if(this.tickOptions==null||!this.tickOptions.formatString){this._overrideFormatString=true}this.min=F;this.max=r;var q=this.max-this.min;var C=(this.minorTicks==="auto")?0:this.minorTicks;var h;if(this.numberTicks==null){if(b>140){h=Math.round(Math.log(this.max/this.min)/Math.log(this.base)+1);if(h<2){h=2}if(C===0){var o=b/(h-1);if(o<100){C=0}else{if(o<190){C=1}else{if(o<250){C=3}else{if(o<600){C=4}else{C=9}}}}}}else{h=2;if(C===0){C=1}C=0}}else{h=this.numberTicks}if(E>=0&&C!==3){this._autoFormatString="%d"}else{if(E<=0&&C===3){var o=-(E-1);this._autoFormatString="%."+Math.abs(E-1)+"f"}else{if(E<0){var o=-E;this._autoFormatString="%."+Math.abs(E)+"f"}else{this._autoFormatString="%d"}}}var O,H,z,p,n,k;for(var K=0;K<h;K++){M=Math.pow(this.base,K-h+1)*this.max;H=new this.tickRenderer(this.tickOptions);if(this._overrideFormatString){H.formatString=this._autoFormatString}if(!this.showTicks){H.showLabel=false;H.showMark=false}else{if(!this.showTickMarks){H.showMark=false}}H.setTick(M,this.name);this._ticks.push(H);if(C&&K<h-1){p=Math.pow(this.base,K-h+2)*this.max;n=p-M;k=p/(C+1);for(var J=C-1;J>=0;J--){z=p-k*(J+1);H=new this.tickRenderer(this.tickOptions);if(this._overrideFormatString&&this._autoFormatString!=""){H.formatString=this._autoFormatString}if(!this.showTicks){H.showLabel=false;H.showMark=false}else{if(!this.showTickMarks){H.showMark=false}}H.setTick(z,this.name);this._ticks.push(H)}}}}else{if(this.min!=null&&this.max!=null){var y=a.extend(true,{},this.tickOptions,{name:this.name,value:null});var I,e;if(this.numberTicks==null&&this.tickInterval==null){var D=Math.max(b,g+1);var L=Math.ceil((D-g)/35+1);var B=a.jqplot.LinearTickGenerator.bestConstrainedInterval(this.min,this.max,L);this._autoFormatString=B[3];I=B[2];e=B[4];for(var K=0;K<I;K++){y.value=this.min+K*e;H=new this.tickRenderer(y);if(this._overrideFormatString&&this._autoFormatString!=""){H.formatString=this._autoFormatString}if(!this.showTicks){H.showLabel=false;H.showMark=false}else{if(!this.showTickMarks){H.showMark=false}}this._ticks.push(H)}}else{if(this.numberTicks!=null&&this.tickInterval!=null){I=this.numberTicks;for(var K=0;K<I;K++){y.value=this.min+K*this.tickInterval;H=new this.tickRenderer(y);if(this._overrideFormatString&&this._autoFormatString!=""){H.formatString=this._autoFormatString}if(!this.showTicks){H.showLabel=false;H.showMark=false}else{if(!this.showTickMarks){H.showMark=false}}this._ticks.push(H)}}}}}}};a.jqplot.LogAxisRenderer.prototype.pack=function(f,e){var r=parseInt(this.base,10);var y=this._ticks;var d=function(h){return Math.log(h)/Math.log(r)};var b=function(h){return Math.pow(Math.E,(Math.log(r)*h))};var u=d(this.max);var s=d(this.min);var m=e.max;var k=e.min;var o=(this._label==null)?false:this._label.show;for(var q in f){this._elem.css(q,f[q])}this._offsets=e;var g=m-k;var j=u-s;this.p2u=function(h){return b((h-k)*j/g+s)};this.u2p=function(h){return(d(h)-s)*g/j+k};if(this.name=="xaxis"||this.name=="x2axis"){this.series_u2p=function(h){return(d(h)-s)*g/j};this.series_p2u=function(h){return b(h*j/g+s)}}else{this.series_u2p=function(h){return(d(h)-u)*g/j};this.series_p2u=function(h){return b(h*j/g+u)}}if(this.show){if(this.name=="xaxis"||this.name=="x2axis"){for(var v=0;v<y.length;v++){var n=y[v];if(n.show&&n.showLabel){var c;if(n.constructor==a.jqplot.CanvasAxisTickRenderer&&n.angle){switch(n.labelPosition){case"auto":if(n.angle<0){c=-n.getWidth()+n._textRenderer.height*Math.sin(-n._textRenderer.angle)/2}else{c=-n._textRenderer.height*Math.sin(n._textRenderer.angle)/2}break;case"end":c=-n.getWidth()+n._textRenderer.height*Math.sin(-n._textRenderer.angle)/2;break;case"start":c=-n._textRenderer.height*Math.sin(n._textRenderer.angle)/2;break;case"middle":c=-n.getWidth()/2+n._textRenderer.height*Math.sin(-n._textRenderer.angle)/2;break;default:c=-n.getWidth()/2+n._textRenderer.height*Math.sin(-n._textRenderer.angle)/2;break}}else{c=-n.getWidth()/2}var z=this.u2p(n.value)+c+"px";n._elem.css("left",z);n.pack()}}if(o){var l=this._label._elem.outerWidth(true);this._label._elem.css("left",k+g/2-l/2+"px");if(this.name=="xaxis"){this._label._elem.css("bottom","0px")}else{this._label._elem.css("top","0px")}this._label.pack()}}else{for(var v=0;v<y.length;v++){var n=y[v];if(n.show&&n.showLabel){var c;if(n.constructor==a.jqplot.CanvasAxisTickRenderer&&n.angle){switch(n.labelPosition){case"auto":case"end":if(n.angle<0){c=-n._textRenderer.height*Math.cos(-n._textRenderer.angle)/2}else{c=-n.getHeight()+n._textRenderer.height*Math.cos(n._textRenderer.angle)/2}break;case"start":if(n.angle>0){c=-n._textRenderer.height*Math.cos(-n._textRenderer.angle)/2}else{c=-n.getHeight()+n._textRenderer.height*Math.cos(n._textRenderer.angle)/2}break;case"middle":c=-n.getHeight()/2;break;default:c=-n.getHeight()/2;break}}else{c=-n.getHeight()/2}var z=this.u2p(n.value)+c+"px";n._elem.css("top",z);n.pack()}}if(o){var x=this._label._elem.outerHeight(true);this._label._elem.css("top",m-g/2-x/2+"px");if(this.name=="yaxis"){this._label._elem.css("left","0px")}else{this._label._elem.css("right","0px")}this._label.pack()}}}}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(a){a.jqplot.OHLCRenderer=function(){a.jqplot.LineRenderer.call(this);this.candleStick=false;this.tickLength="auto";this.bodyWidth="auto";this.openColor=null;this.closeColor=null;this.wickColor=null;this.fillUpBody=false;this.fillDownBody=true;this.upBodyColor=null;this.downBodyColor=null;this.hlc=false;this.lineWidth=1.5;this._tickLength;this._bodyWidth};a.jqplot.OHLCRenderer.prototype=new a.jqplot.LineRenderer();a.jqplot.OHLCRenderer.prototype.constructor=a.jqplot.OHLCRenderer;a.jqplot.OHLCRenderer.prototype.init=function(e){e=e||{};this.lineWidth=e.lineWidth||1.5;a.jqplot.LineRenderer.prototype.init.call(this,e);this._type="ohlc";var b=this._yaxis._dataBounds;var f=this._plotData;if(f[0].length<5){this.renderer.hlc=true;for(var c=0;c<f.length;c++){if(f[c][2]<b.min||b.min==null){b.min=f[c][2]}if(f[c][1]>b.max||b.max==null){b.max=f[c][1]}}}else{for(var c=0;c<f.length;c++){if(f[c][3]<b.min||b.min==null){b.min=f[c][3]}if(f[c][2]>b.max||b.max==null){b.max=f[c][2]}}}};a.jqplot.OHLCRenderer.prototype.draw=function(A,N,j){var J=this.data;var v=this._xaxis.min;var z=this._xaxis.max;var l=0;var K=J.length;var p=this._xaxis.series_u2p;var G=this._yaxis.series_u2p;var D,E,f,M,F,n,O,C;var y;var u=this.renderer;var s=(j!=undefined)?j:{};var k=(s.shadow!=undefined)?s.shadow:this.shadow;var B=(s.fill!=undefined)?s.fill:this.fill;var c=(s.fillAndStroke!=undefined)?s.fillAndStroke:this.fillAndStroke;u.bodyWidth=(s.bodyWidth!=undefined)?s.bodyWidth:u.bodyWidth;u.tickLength=(s.tickLength!=undefined)?s.tickLength:u.tickLength;A.save();if(this.show){var m,q,g,Q,t;for(var D=0;D<J.length;D++){if(J[D][0]<v){l=D}else{if(J[D][0]<z){K=D+1}}}var I=this.gridData[K-1][0]-this.gridData[l][0];var L=K-l;try{var P=Math.abs(this._xaxis.series_u2p(parseInt(this._xaxis._intervalStats[0].sortedIntervals[0].interval,10))-this._xaxis.series_u2p(0))}catch(H){var P=I/L}if(u.candleStick){if(typeof(u.bodyWidth)=="number"){u._bodyWidth=u.bodyWidth}else{u._bodyWidth=Math.min(20,P/1.65)}}else{if(typeof(u.tickLength)=="number"){u._tickLength=u.tickLength}else{u._tickLength=Math.min(10,P/3.5)}}for(var D=l;D<K;D++){m=p(J[D][0]);if(u.hlc){q=null;g=G(J[D][1]);Q=G(J[D][2]);t=G(J[D][3])}else{q=G(J[D][1]);g=G(J[D][2]);Q=G(J[D][3]);t=G(J[D][4])}y={};if(u.candleStick&&!u.hlc){n=u._bodyWidth;O=m-n/2;if(t<q){if(u.wickColor){y.color=u.wickColor}else{if(u.downBodyColor){y.color=u.upBodyColor}}f=a.extend(true,{},s,y);u.shapeRenderer.draw(A,[[m,g],[m,t]],f);u.shapeRenderer.draw(A,[[m,q],[m,Q]],f);y={};M=t;F=q-t;if(u.fillUpBody){y.fillRect=true}else{y.strokeRect=true;n=n-this.lineWidth;O=m-n/2}if(u.upBodyColor){y.color=u.upBodyColor;y.fillStyle=u.upBodyColor}C=[O,M,n,F]}else{if(t>q){if(u.wickColor){y.color=u.wickColor}else{if(u.downBodyColor){y.color=u.downBodyColor}}f=a.extend(true,{},s,y);u.shapeRenderer.draw(A,[[m,g],[m,q]],f);u.shapeRenderer.draw(A,[[m,t],[m,Q]],f);y={};M=q;F=t-q;if(u.fillDownBody){y.fillRect=true}else{y.strokeRect=true;n=n-this.lineWidth;O=m-n/2}if(u.downBodyColor){y.color=u.downBodyColor;y.fillStyle=u.downBodyColor}C=[O,M,n,F]}else{if(u.wickColor){y.color=u.wickColor}f=a.extend(true,{},s,y);u.shapeRenderer.draw(A,[[m,g],[m,Q]],f);y={};y.fillRect=false;y.strokeRect=false;O=[m-n/2,q];M=[m+n/2,t];n=null;F=null;C=[O,M]}}f=a.extend(true,{},s,y);u.shapeRenderer.draw(A,C,f)}else{E=s.color;if(u.openColor){s.color=u.openColor}if(!u.hlc){u.shapeRenderer.draw(A,[[m-u._tickLength,q],[m,q]],s)}s.color=E;if(u.wickColor){s.color=u.wickColor}u.shapeRenderer.draw(A,[[m,g],[m,Q]],s);s.color=E;if(u.closeColor){s.color=u.closeColor}u.shapeRenderer.draw(A,[[m,t],[m+u._tickLength,t]],s);s.color=E}}}A.restore()};a.jqplot.OHLCRenderer.prototype.drawShadow=function(b,d,c){};a.jqplot.OHLCRenderer.checkOptions=function(d,c,b){if(!b.highlighter){b.highlighter={showMarker:false,tooltipAxes:"y",yvalues:4,formatString:'<table class="jqplot-highlighter"><tr><td>date:</td><td>%s</td></tr><tr><td>open:</td><td>%s</td></tr><tr><td>hi:</td><td>%s</td></tr><tr><td>low:</td><td>%s</td></tr><tr><td>close:</td><td>%s</td></tr></table>'}}}})(jQuery);/* jqPlot 1.0.7r1224 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(c){c.jqplot.PointLabels=function(e){this.show=c.jqplot.config.enablePlugins;this.location="n";this.labelsFromSeries=false;this.seriesLabelIndex=null;this.labels=[];this._labels=[];this.stackedValue=false;this.ypadding=6;this.xpadding=6;this.escapeHTML=true;this.edgeTolerance=-5;this.formatter=c.jqplot.DefaultTickFormatter;this.formatString="";this.hideZeros=false;this._elems=[];c.extend(true,this,e)};var a=["nw","n","ne","e","se","s","sw","w"];var d={nw:0,n:1,ne:2,e:3,se:4,s:5,sw:6,w:7};var b=["se","s","sw","w","nw","n","ne","e"];c.jqplot.PointLabels.init=function(j,h,f,g,i){var e=c.extend(true,{},f,g);e.pointLabels=e.pointLabels||{};if(this.renderer.constructor===c.jqplot.BarRenderer&&this.barDirection==="horizontal"&&!e.pointLabels.location){e.pointLabels.location="e"}this.plugins.pointLabels=new c.jqplot.PointLabels(e.pointLabels);this.plugins.pointLabels.setLabels.call(this)};c.jqplot.PointLabels.prototype.setLabels=function(){var f=this.plugins.pointLabels;var h;if(f.seriesLabelIndex!=null){h=f.seriesLabelIndex}else{if(this.renderer.constructor===c.jqplot.BarRenderer&&this.barDirection==="horizontal"){h=(this._plotData[0].length<3)?0:this._plotData[0].length-1}else{h=(this._plotData.length===0)?0:this._plotData[0].length-1}}f._labels=[];if(f.labels.length===0||f.labelsFromSeries){if(f.stackedValue){if(this._plotData.length&&this._plotData[0].length){for(var e=0;e<this._plotData.length;e++){f._labels.push(this._plotData[e][h])}}}else{var g=this.data;if(this.renderer.constructor===c.jqplot.BarRenderer&&this.waterfall){g=this._data}if(g.length&&g[0].length){for(var e=0;e<g.length;e++){f._labels.push(g[e][h])}}g=null}}else{if(f.labels.length){f._labels=f.labels}}};c.jqplot.PointLabels.prototype.xOffset=function(f,e,g){e=e||this.location;g=g||this.xpadding;var h;switch(e){case"nw":h=-f.outerWidth(true)-this.xpadding;break;case"n":h=-f.outerWidth(true)/2;break;case"ne":h=this.xpadding;break;case"e":h=this.xpadding;break;case"se":h=this.xpadding;break;case"s":h=-f.outerWidth(true)/2;break;case"sw":h=-f.outerWidth(true)-this.xpadding;break;case"w":h=-f.outerWidth(true)-this.xpadding;break;default:h=-f.outerWidth(true)-this.xpadding;break}return h};c.jqplot.PointLabels.prototype.yOffset=function(f,e,g){e=e||this.location;g=g||this.xpadding;var h;switch(e){case"nw":h=-f.outerHeight(true)-this.ypadding;break;case"n":h=-f.outerHeight(true)-this.ypadding;break;case"ne":h=-f.outerHeight(true)-this.ypadding;break;case"e":h=-f.outerHeight(true)/2;break;case"se":h=this.ypadding;break;case"s":h=this.ypadding;break;case"sw":h=this.ypadding;break;case"w":h=-f.outerHeight(true)/2;break;default:h=-f.outerHeight(true)-this.ypadding;break}return h};c.jqplot.PointLabels.draw=function(x,j,v){var t=this.plugins.pointLabels;t.setLabels.call(this);for(var w=0;w<t._elems.length;w++){t._elems[w].emptyForce()}t._elems.splice(0,t._elems.length);if(t.show){var r="_"+this._stackAxis+"axis";if(!t.formatString){t.formatString=this[r]._ticks[0].formatString;t.formatter=this[r]._ticks[0].formatter}var E=this._plotData;var D=this._prevPlotData;var A=this._xaxis;var q=this._yaxis;var z,f;for(var w=0,u=t._labels.length;w<u;w++){var o=t._labels[w];if(o==null||(t.hideZeros&&parseInt(o,10)==0)){continue}o=t.formatter(t.formatString,o);f=document.createElement("div");t._elems[w]=c(f);z=t._elems[w];z.addClass("jqplot-point-label jqplot-series-"+this.index+" jqplot-point-"+w);z.css("position","absolute");z.insertAfter(x.canvas);if(t.escapeHTML){z.text(o)}else{z.html(o)}var g=t.location;if((this.fillToZero&&E[w][1]<0)||(this.fillToZero&&this._type==="bar"&&this.barDirection==="horizontal"&&E[w][0]<0)||(this.waterfall&&parseInt(o,10))<0){g=b[d[g]]}var n=A.u2p(E[w][0])+t.xOffset(z,g);var h=q.u2p(E[w][1])+t.yOffset(z,g);if(this._stack&&!t.stackedValue){if(this.barDirection==="vertical"){h=(this._barPoints[w][0][1]+this._barPoints[w][1][1])/2+v._gridPadding.top-0.5*z.outerHeight(true)}else{n=(this._barPoints[w][2][0]+this._barPoints[w][0][0])/2+v._gridPadding.left-0.5*z.outerWidth(true)}}if(this.renderer.constructor==c.jqplot.BarRenderer){if(this.barDirection=="vertical"){n+=this._barNudge}else{h-=this._barNudge}}z.css("left",n);z.css("top",h);var k=n+z.width();var s=h+z.height();var C=t.edgeTolerance;var e=c(x.canvas).position().left;var y=c(x.canvas).position().top;var B=x.canvas.width+e;var m=x.canvas.height+y;if(n-C<e||h-C<y||k+C>B||s+C>m){z.remove()}z=null;f=null}}};c.jqplot.postSeriesInitHooks.push(c.jqplot.PointLabels.init);c.jqplot.postDrawSeriesHooks.push(c.jqplot.PointLabels.draw)})(jQuery);// jQuery File Tree Plugin
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// Visit http://abeautifulsite.net/notebook.php?article=58 for more information
//
// Usage: $('.fileTreeDemo').fileTree( options, callback )
//
// Options:  root           - root folder to display; default = /
//           script         - location of the serverside AJAX file to use; default = jqueryFileTree.php
//           folderEvent    - event to trigger expand/collapse; default = click
//           expandSpeed    - default = 500 (ms); use -1 for no animation
//           collapseSpeed  - default = 500 (ms); use -1 for no animation
//           expandEasing   - easing function to use on expand (optional)
//           collapseEasing - easing function to use on collapse (optional)
//           multiFolder    - whether or not to limit the browser to one subfolder at a time
//           loadMessage    - Message to display while initial tree loads (can be HTML)
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// TERMS OF USE
// 
// jQuery File Tree is licensed under a Creative Commons License and is copyrighted (C)2008 by Cory S.N. LaViska.
// For details, visit http://creativecommons.org/licenses/by/3.0/us/
//
var bots = Array();
if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			// Defaults
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '';
			if( o.target == undefined ) o.target = '';
			if( o.script == undefined ) o.script = 'jqueryFileTree.php';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					$.post(o.script, { dir: t }, function(data) {
						$(c).find('.start').html('');
						$(c).removeClass('wait').append(data);
						if( o.root == t ) 
              $(c).find('UL:hidden').show(); 
            else 
              $(c).find('UL:hidden').slideDown({ duration: o.expandSpeed, easing: o.expandEasing });

						bindTree(c);

            // This allows us to loop through each directory to a default target
            bits = o.target.split("/");
            bots.push(bits.shift());
            o.target = bits.join("/");
            path = bots.join("/");
            $('a[rel="'+path+'/"]','#jftTreeTD').click();
            $('a[rel="'+path+'"]','#jftTreeTD').click();
					});
				}
				
				function bindTree(t) {
					$(t).find('LI A').bind(o.folderEvent, function() {

            $(".highlighted").removeClass('highlighted'); // added by alla
            $(this).addClass('highlighted');            // added by alla
						if( $(this).parent().hasClass('directory') ) {
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand
								if( !o.multiFolder ) {
									$(this).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
									$(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
								}
								$(this).parent().find('UL').remove(); // cleanup
								showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
								$(this).parent().removeClass('collapsed').addClass('expanded');
							} else {
								// Collapse
								$(this).parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
								$(this).parent().removeClass('expanded').addClass('collapsed');
							}
							h($(this).attr('rel'), false); // do the callBack function
						} else {
							h($(this).attr('rel'), true); // do the callBack function (true signals that it was a file not a dir)
						}
						return false;
					});

					// Prevent A from triggering the # on non-click events
					if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
				}

				// Loading message
				$(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
				// Get the initial file list
				showTree( $(this), escape(o.root) );
			});
		}
	});
	
})(jQuery);
// ----------------------------------------------------------------------------
// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.x
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2007-2012 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
(function($) {
	$.fn.markItUp = function(settings, extraSettings) {
		var method, params, options, ctrlKey, shiftKey, altKey; ctrlKey = shiftKey = altKey = false;

		if (typeof settings == 'string') {
			method = settings;
			params = extraSettings;
		} 

		options = {	id:						'',
					nameSpace:				'',
					root:					'',
					previewHandler:			false,
					previewInWindow:		'', // 'width=800, height=600, resizable=yes, scrollbars=yes'
					previewInElement:		'',
					previewAutoRefresh:		true,
					previewPosition:		'after',
					previewTemplatePath:	'~/templates/preview.html',
					previewParser:			false,
					previewParserPath:		'',
					previewParserVar:		'data',
					resizeHandle:			true,
					beforeInsert:			'',
					afterInsert:			'',
					onEnter:				{},
					onShiftEnter:			{},
					onCtrlEnter:			{},
					onTab:					{},
					markupSet:			[	{ /* set */ } ]
				};
		$.extend(options, settings, extraSettings);

		// compute markItUp! path
		if (!options.root) {
			$('script').each(function(a, tag) {
				miuScript = $(tag).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.js$/);
				if (miuScript !== null) {
					options.root = miuScript[1];
				}
			});
		}

		// Quick patch to keep compatibility with jQuery 1.9
		var uaMatch = function(ua) {
			ua = ua.toLowerCase();

			var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
				/(webkit)[ \/]([\w.]+)/.exec(ua) ||
				/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
				/(msie) ([\w.]+)/.exec(ua) ||
				ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
				[];

			return {
				browser: match[ 1 ] || "",
				version: match[ 2 ] || "0"
			};
		};
		var matched = uaMatch( navigator.userAgent );
		var browser = {};

		if (matched.browser) {
			browser[matched.browser] = true;
			browser.version = matched.version;
		}
		if (browser.chrome) {
			browser.webkit = true;
		} else if (browser.webkit) {
			browser.safari = true;
		}

		return this.each(function() {
			var $$, textarea, levels, scrollPosition, caretPosition, caretOffset,
				clicked, hash, header, footer, previewWindow, template, iFrame, abort;
			$$ = $(this);
			textarea = this;
			levels = [];
			abort = false;
			scrollPosition = caretPosition = 0;
			caretOffset = -1;

			options.previewParserPath = localize(options.previewParserPath);
			options.previewTemplatePath = localize(options.previewTemplatePath);

			if (method) {
				switch(method) {
					case 'remove':
						remove();
					break;
					case 'insert':
						markup(params);
					break;
					default: 
						$.error('Method ' +  method + ' does not exist on jQuery.markItUp');
				}
				return;
			}

			// apply the computed path to ~/
			function localize(data, inText) {
				if (inText) {
					return 	data.replace(/("|')~\//g, "$1"+options.root);
				}
				return 	data.replace(/^~\//, options.root);
			}

			// init and build editor
			function init() {
				id = ''; nameSpace = '';
				if (options.id) {
					id = 'id="'+options.id+'"';
				} else if ($$.attr("id")) {
					id = 'id="markItUp'+($$.attr("id").substr(0, 1).toUpperCase())+($$.attr("id").substr(1))+'"';

				}
				if (options.nameSpace) {
					nameSpace = 'class="'+options.nameSpace+'"';
				}
				$$.wrap('<div '+nameSpace+'></div>');
				$$.wrap('<div '+id+' class="markItUp"></div>');
				$$.wrap('<div class="markItUpContainer"></div>');
				$$.addClass("markItUpEditor");

				// add the header before the textarea
				header = $('<div class="markItUpHeader"></div>').insertBefore($$);
				$(dropMenus(options.markupSet)).appendTo(header);

				// add the footer after the textarea
				footer = $('<div class="markItUpFooter"></div>').insertAfter($$);

				// add the resize handle after textarea
				if (options.resizeHandle === true && browser.safari !== true) {
					resizeHandle = $('<div class="markItUpResizeHandle"></div>')
						.insertAfter($$)
						.bind("mousedown.markItUp", function(e) {
							var h = $$.height(), y = e.clientY, mouseMove, mouseUp;
							mouseMove = function(e) {
								$$.css("height", Math.max(20, e.clientY+h-y)+"px");
								return false;
							};
							mouseUp = function(e) {
								$("html").unbind("mousemove.markItUp", mouseMove).unbind("mouseup.markItUp", mouseUp);
								return false;
							};
							$("html").bind("mousemove.markItUp", mouseMove).bind("mouseup.markItUp", mouseUp);
					});
					footer.append(resizeHandle);
				}

				// listen key events
				$$.bind('keydown.markItUp', keyPressed).bind('keyup', keyPressed);
				
				// bind an event to catch external calls
				$$.bind("insertion.markItUp", function(e, settings) {
					if (settings.target !== false) {
						get();
					}
					if (textarea === $.markItUp.focused) {
						markup(settings);
					}
				});

				// remember the last focus
				$$.bind('focus.markItUp', function() {
					$.markItUp.focused = this;
				});

				if (options.previewInElement) {
					refreshPreview();
				}
			}

			// recursively build header with dropMenus from markupset
			function dropMenus(markupSet) {
				var ul = $('<ul></ul>'), i = 0;
				$('li:hover > ul', ul).css('display', 'block');
				$.each(markupSet, function() {
					var button = this, t = '', title, li, j;
					title = (button.key) ? (button.name||'')+' [Ctrl+'+button.key+']' : (button.name||'');
					key   = (button.key) ? 'accesskey="'+button.key+'"' : '';
					if (button.separator) {
						li = $('<li class="markItUpSeparator">'+(button.separator||'')+'</li>').appendTo(ul);
					} else {
						i++;
						for (j = levels.length -1; j >= 0; j--) {
							t += levels[j]+"-";
						}
						li = $('<li class="markItUpButton markItUpButton'+t+(i)+' '+(button.className||'')+'"><a href="" '+key+' title="'+title+'">'+(button.name||'')+'</a></li>')
						.bind("contextmenu.markItUp", function() { // prevent contextmenu on mac and allow ctrl+click
							return false;
						}).bind('click.markItUp', function(e) {
							e.preventDefault();
						}).bind("focusin.markItUp", function(){
                            $$.focus();
						}).bind('mouseup', function() {
							if (button.call) {
								eval(button.call)();
							}
							setTimeout(function() { markup(button) },1);
							return false;
						}).bind('mouseenter.markItUp', function() {
								$('> ul', this).show();
								$(document).one('click', function() { // close dropmenu if click outside
										$('ul ul', header).hide();
									}
								);
						}).bind('mouseleave.markItUp', function() {
								$('> ul', this).hide();
						}).appendTo(ul);
						if (button.dropMenu) {
							levels.push(i);
							$(li).addClass('markItUpDropMenu').append(dropMenus(button.dropMenu));
						}
					}
				}); 
				levels.pop();
				return ul;
			}

			// markItUp! markups
			function magicMarkups(string) {
				if (string) {
					string = string.toString();
					string = string.replace(/\(\!\(([\s\S]*?)\)\!\)/g,
						function(x, a) {
							var b = a.split('|!|');
							if (altKey === true) {
								return (b[1] !== undefined) ? b[1] : b[0];
							} else {
								return (b[1] === undefined) ? "" : b[0];
							}
						}
					);
					// [![prompt]!], [![prompt:!:value]!]
					string = string.replace(/\[\!\[([\s\S]*?)\]\!\]/g,
						function(x, a) {
							var b = a.split(':!:');
							if (abort === true) {
								return false;
							}
							value = prompt(b[0], (b[1]) ? b[1] : '');
							if (value === null) {
								abort = true;
							}
							return value;
						}
					);
					return string;
				}
				return "";
			}

			// prepare action
			function prepare(action) {
				if ($.isFunction(action)) {
					action = action(hash);
				}
				return magicMarkups(action);
			}

			// build block to insert
			function build(string) {
				var openWith 			= prepare(clicked.openWith);
				var placeHolder 		= prepare(clicked.placeHolder);
				var replaceWith 		= prepare(clicked.replaceWith);
				var closeWith 			= prepare(clicked.closeWith);
				var openBlockWith 		= prepare(clicked.openBlockWith);
				var closeBlockWith 		= prepare(clicked.closeBlockWith);
				var multiline 			= clicked.multiline;
				
				if (replaceWith !== "") {
					block = openWith + replaceWith + closeWith;
				} else if (selection === '' && placeHolder !== '') {
					block = openWith + placeHolder + closeWith;
				} else {
					string = string || selection;

					var lines = [string], blocks = [];
					
					if (multiline === true) {
						lines = string.split(/\r?\n/);
					}
					
					for (var l = 0; l < lines.length; l++) {
						line = lines[l];
						var trailingSpaces;
						if (trailingSpaces = line.match(/ *$/)) {
							blocks.push(openWith + line.replace(/ *$/g, '') + closeWith + trailingSpaces);
						} else {
							blocks.push(openWith + line + closeWith);
						}
					}
					
					block = blocks.join("\n");
				}

				block = openBlockWith + block + closeBlockWith;

				return {	block:block, 
							openBlockWith:openBlockWith,
							openWith:openWith, 
							replaceWith:replaceWith, 
							placeHolder:placeHolder,
							closeWith:closeWith,
							closeBlockWith:closeBlockWith
					};
			}

			// define markup to insert
			function markup(button) {
				var len, j, n, i;
				hash = clicked = button;
				get();
				$.extend(hash, {	line:"", 
						 			root:options.root,
									textarea:textarea, 
									selection:(selection||''), 
									caretPosition:caretPosition,
									ctrlKey:ctrlKey, 
									shiftKey:shiftKey, 
									altKey:altKey
								}
							);
				// callbacks before insertion
				prepare(options.beforeInsert);
				prepare(clicked.beforeInsert);
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.beforeMultiInsert);
				}			
				$.extend(hash, { line:1 });

				if ((ctrlKey === true && shiftKey === true)) {
					lines = selection.split(/\r?\n/);
					for (j = 0, n = lines.length, i = 0; i < n; i++) {
						if ($.trim(lines[i]) !== '') {
							$.extend(hash, { line:++j, selection:lines[i] } );
							lines[i] = build(lines[i]).block;
						} else {
							lines[i] = "";
						}
					}

					string = { block:lines.join('\n')};
					start = caretPosition;
					len = string.block.length + ((browser.opera) ? n-1 : 0);
				} else if (ctrlKey === true) {
					string = build(selection);
					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;
					len = len - (string.block.match(/ $/) ? 1 : 0);
					len -= fixIeBug(string.block);
				} else if (shiftKey === true) {
					string = build(selection);
					start = caretPosition;
					len = string.block.length;
					len -= fixIeBug(string.block);
				} else {
					string = build(selection);
					start = caretPosition + string.block.length ;
					len = 0;
					start -= fixIeBug(string.block);
				}
				if ((selection === '' && string.replaceWith === '')) {
					caretOffset += fixOperaBug(string.block);
					
					start = caretPosition + string.openBlockWith.length + string.openWith.length;
					len = string.block.length - string.openBlockWith.length - string.openWith.length - string.closeWith.length - string.closeBlockWith.length;

					caretOffset = $$.val().substring(caretPosition,  $$.val().length).length;
					caretOffset -= fixOperaBug($$.val().substring(0, caretPosition));
				}
				$.extend(hash, { caretPosition:caretPosition, scrollPosition:scrollPosition } );

				if (string.block !== selection && abort === false) {
					insert(string.block);
					set(start, len);
				} else {
					caretOffset = -1;
				}
				get();

				$.extend(hash, { line:'', selection:selection });

				// callbacks after insertion
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.afterMultiInsert);
				}
				prepare(clicked.afterInsert);
				prepare(options.afterInsert);

				// refresh preview if opened
				if (previewWindow && options.previewAutoRefresh) {
					refreshPreview(); 
				}
																									
				// reinit keyevent
				shiftKey = altKey = ctrlKey = abort = false;
			}

			// Substract linefeed in Opera
			function fixOperaBug(string) {
				if (browser.opera) {
					return string.length - string.replace(/\n*/g, '').length;
				}
				return 0;
			}
			// Substract linefeed in IE
			function fixIeBug(string) {
				if (browser.msie) {
					return string.length - string.replace(/\r*/g, '').length;
				}
				return 0;
			}
				
			// add markup
			function insert(block) {	
				if (document.selection) {
					var newSelection = document.selection.createRange();
					newSelection.text = block;
				} else {
					textarea.value =  textarea.value.substring(0, caretPosition)  + block + textarea.value.substring(caretPosition + selection.length, textarea.value.length);
				}
			}

			// set a selection
			function set(start, len) {
				if (textarea.createTextRange){
					// quick fix to make it work on Opera 9.5
					if (browser.opera && browser.version >= 9.5 && len == 0) {
						return false;
					}
					range = textarea.createTextRange();
					range.collapse(true);
					range.moveStart('character', start); 
					range.moveEnd('character', len); 
					range.select();
				} else if (textarea.setSelectionRange ){
					textarea.setSelectionRange(start, start + len);
				}
				textarea.scrollTop = scrollPosition;
				textarea.focus();
			}

			// get the selection
			function get() {
				textarea.focus();

				scrollPosition = textarea.scrollTop;
				if (document.selection) {
					selection = document.selection.createRange().text;
					if (browser.msie) { // ie
						var range = document.selection.createRange(), rangeCopy = range.duplicate();
						rangeCopy.moveToElementText(textarea);
						caretPosition = -1;
						while(rangeCopy.inRange(range)) {
							rangeCopy.moveStart('character');
							caretPosition ++;
						}
					} else { // opera
						caretPosition = textarea.selectionStart;
					}
				} else { // gecko & webkit
					caretPosition = textarea.selectionStart;

					selection = textarea.value.substring(caretPosition, textarea.selectionEnd);
				} 
				return selection;
			}

			// open preview window
			function preview() {
				if (typeof options.previewHandler === 'function') {
					previewWindow = true;
				} else if (options.previewInElement) {
					previewWindow = $(options.previewInElement);
				} else if (!previewWindow || previewWindow.closed) {
					if (options.previewInWindow) {
						previewWindow = window.open('', 'preview', options.previewInWindow);
						$(window).unload(function() {
							previewWindow.close();
						});
					} else {
						iFrame = $('<iframe class="markItUpPreviewFrame"></iframe>');
						if (options.previewPosition == 'after') {
							iFrame.insertAfter(footer);
						} else {
							iFrame.insertBefore(header);
						}	
						previewWindow = iFrame[iFrame.length - 1].contentWindow || frame[iFrame.length - 1];
					}
				} else if (altKey === true) {
					if (iFrame) {
						iFrame.remove();
					} else {
						previewWindow.close();
					}
					previewWindow = iFrame = false;
				}
				if (!options.previewAutoRefresh) {
					refreshPreview(); 
				}
				if (options.previewInWindow) {
					previewWindow.focus();
				}
			}

			// refresh Preview window
			function refreshPreview() {
 				renderPreview();
			}

			function renderPreview() {
				var phtml;
				if (options.previewHandler && typeof options.previewHandler === 'function') {
					options.previewHandler( $$.val() );
				} else if (options.previewParser && typeof options.previewParser === 'function') {
					var data = options.previewParser( $$.val() );
					writeInPreview(localize(data, 1) ); 
				} else if (options.previewParserPath !== '') {
					$.ajax({
						type: 'POST',
						dataType: 'text',
						global: false,
						url: options.previewParserPath,
						data: options.previewParserVar+'='+encodeURIComponent($$.val()),
						success: function(data) {
							writeInPreview( localize(data, 1) ); 
						}
					});
				} else {
					if (!template) {
						$.ajax({
							url: options.previewTemplatePath,
							dataType: 'text',
							global: false,
							success: function(data) {
								writeInPreview( localize(data, 1).replace(/<!-- content -->/g, $$.val()) );
							}
						});
					}
				}
				return false;
			}
			
			function writeInPreview(data) {
				if (options.previewInElement) {
					$(options.previewInElement).html(data);
				} else if (previewWindow && previewWindow.document) {			
					try {
						sp = previewWindow.document.documentElement.scrollTop
					} catch(e) {
						sp = 0;
					}	
					previewWindow.document.open();
					previewWindow.document.write(data);
					previewWindow.document.close();
					previewWindow.document.documentElement.scrollTop = sp;
				}
			}
			
			// set keys pressed
			function keyPressed(e) { 
				shiftKey = e.shiftKey;
				altKey = e.altKey;
				ctrlKey = (!(e.altKey && e.ctrlKey)) ? (e.ctrlKey || e.metaKey) : false;

				if (e.type === 'keydown') {
					if (ctrlKey === true) {
						li = $('a[accesskey="'+((e.keyCode == 13) ? '\\n' : String.fromCharCode(e.keyCode))+'"]', header).parent('li');
						if (li.length !== 0) {
							ctrlKey = false;
							setTimeout(function() {
								li.triggerHandler('mouseup');
							},1);
							return false;
						}
					}
					if (e.keyCode === 13 || e.keyCode === 10) { // Enter key
						if (ctrlKey === true) {  // Enter + Ctrl
							ctrlKey = false;
							markup(options.onCtrlEnter);
							return options.onCtrlEnter.keepDefault;
						} else if (shiftKey === true) { // Enter + Shift
							shiftKey = false;
							markup(options.onShiftEnter);
							return options.onShiftEnter.keepDefault;
						} else { // only Enter
							markup(options.onEnter);
							return options.onEnter.keepDefault;
						}
					}
					if (e.keyCode === 9) { // Tab key
						if (shiftKey == true || ctrlKey == true || altKey == true) {
							return false; 
						}
						if (caretOffset !== -1) {
							get();
							caretOffset = $$.val().length - caretOffset;
							set(caretOffset, 0);
							caretOffset = -1;
							return false;
						} else {
							markup(options.onTab);
							return options.onTab.keepDefault;
						}
					}
				}
			}

			function remove() {
				$$.unbind(".markItUp").removeClass('markItUpEditor');
				$$.parent('div').parent('div.markItUp').parent('div').replaceWith($$);
				$$.data('markItUp', null);
			}

			init();
		});
	};

	$.fn.markItUpRemove = function() {
		return this.each(function() {
				$(this).markItUp('remove');
			}
		);
	};

	$.markItUp = function(settings) {
		var options = { target:false };
		$.extend(options, settings);
		if (options.target) {
			return $(options.target).each(function() {
				$(this).focus();
				$(this).trigger('insertion', [options]);
			});
		} else {
			$('textarea').trigger('insertion', [options]);
		}
	};
})(jQuery);
// -------------------------------------------------------------------
// markItUp!
// -------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// -------------------------------------------------------------------
// MarkDown tags example
// http://en.wikipedia.org/wiki/Markdown
// http://daringfireball.net/projects/markdown/
// -------------------------------------------------------------------
// Feel free to add more tags
// -------------------------------------------------------------------
var mySettings = {
	//previewParserPath:	'',
	onShiftEnter:		{keepDefault:false, openWith:'\n\n'},
	markupSet: [
		{name:'First Level Heading', key:'1', placeHolder:'Your title here...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '=') } },
		{name:'Second Level Heading', key:'2', placeHolder:'Your title here...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '-') } },
		{name:'Heading 3', key:'3', openWith:'### ', placeHolder:'Your title here...' },
		{name:'Heading 4', key:'4', openWith:'#### ', placeHolder:'Your title here...' },
		{name:'Heading 5', key:'5', openWith:'##### ', placeHolder:'Your title here...' },
		{name:'Heading 6', key:'6', openWith:'###### ', placeHolder:'Your title here...' },
		{separator:'---------------' },		
		{name:'Bold', key:'B', openWith:'**', closeWith:'**'},
		{name:'Italic', key:'I', openWith:'_', closeWith:'_'},
		{separator:'---------------' },
		{name:'Bulleted List', openWith:'- ' },
		{name:'Numeric List', openWith:function(markItUp) {
			return markItUp.line+'. ';
		}},
		{separator:'---------------' },
		{name:'Picture', key:'P', replaceWith:'![[![Alternative text]!]]([![Url:!:http://]!] "[![Title]!]")'},
		{name:'Link', key:'L', openWith:'[', closeWith:']([![Url:!:http://]!] "[![Title]!]")', placeHolder:'Your text to link here...' },
		{separator:'---------------'},	
		{name:'Quotes', openWith:'> '},
		{name:'Code Block / Code', openWith:'(!(    |!|`)!)', closeWith:'(!(`)!)'},
		{separator:'---------------'},
		{name:'Preview', call:'preview', className:"preview"}
	]
}

// mIu nameSpace to avoid conflict.
miu = {
	markdownTitle: function(markItUp, char) {
		heading = '';
		n = $.trim(markItUp.selection||markItUp.placeHolder).length;
		for(i = 0; i < n; i++) {
			heading += char;
		}
		return '\n'+heading;
	}
}
var selectn_unique_select_id_counter = 1;
;(function($) {
  var methods = {

    // Convert multi-selects into checkboxes
    init : function(options) { 
      this.each(function() {

        // The <select> dropdown
        var select = $(this);
        var select_id = select.attr("id");

        // Give the <select> dropdown an id, if it is missing one
        if (!select_id) {
          select.attr("id","selectn-id-"+selectn_unique_select_id_counter++);
        }

        // First make a label for the closed dropdown list
        var label = $("<span/>",{"class":"selectn-label", "data-select-id":select.attr("id"), "tabindex":"0"});

        // Default to the natural width of the select - unless the select has a width attribute
        if (!select.attr("width")) {
          label.width(select.width());
        } else {
          label.width(select.attr("width"));
        }

        // Put the selected options into the label span and add the label's icon
        methods.set_label(label, select);
        select.parent().append(label);

        // Extra buttons for: all, none, and invert, and a search field which doubles as a new entry input
        var op_all = $("<button/>",{"name":"all",   "type":"button","class":"selectn-button"}).text("all").outerHTML();
        var op_non = $("<button/>",{"name":"none",  "type":"button","class":"selectn-button"}).text("none").outerHTML();
        var op_inv = $("<button/>",{"name":"invert","type":"button","class":"selectn-button"}).text("toggle").outerHTML();
        var op_sea = $("<input/>", {"name":"search","type":"text",  "class":"selectn-search"}).outerHTML();
        var op_new = '';
        if (select.attr("data-selectn-new")) {
          var op_new = $("<button/>",{"name":"new",   "type":"button","class":"selectn-button"}).text("new").outerHTML();
        }

        // Gather up all the options from the <select> dropdown
        var dropdown_ops = [];
        dropdown_ops[dropdown_ops.length] = "<div class='selectn-buttons'>"+op_all+" "+op_non+" "+op_inv+" "+op_sea+" "+op_new+"</div>";
        select.find("option").each(function(i, option){ 
          dropdown_ops[dropdown_ops.length] = methods.make_item($(option).val(), $(option).html().trim(), $(option).prop("selected"));
        });
      
        // Create a dropdown box, that has selectable checkboxes in it
        var dropdown = $("<span/>",{ "class": "selectn-dropdown" });
        dropdown.css("min-width",label.width());

        // Create the checkboxes
        dropdown.append(dropdown_ops);
        select.parent().append(dropdown);

        // Open the dropdown when the label is clicked
        label.click(function(e) {
          methods.toggle_open(this, dropdown);
        });
        // Open the dropdown when the element has focus and enter is pressed
        label.keypress(function(e) {
          if (e.which == 13) {
            e.preventDefault();
            e.stopPropagation();
            methods.toggle_open(this, dropdown);
          }
        });
        // Move the item focus up and down the options when the arrows are used
        $(".selectn-cb",dropdown).keydown(function(e){
          // Enter pressed, hide the dropdown
          if (e.which == 13) {
            e.preventDefault();
            e.stopPropagation();
            methods.toggle_open(label, dropdown);
          // Down arrow, move focus down an item
          } else if (e.which == 40) {
            e.preventDefault();
            e.stopPropagation();
            var next_label = $(this).parent().nextAll("label:visible").first();
            if (next_label.length) {
              $("label",dropdown).removeClass("hover");
              next_label.addClass("hover");
              $(".selectn-cb",next_label).focus();
            }
          // Up arrow, move focus up an item
          } else if (e.which == 38) {
            e.preventDefault();
            e.stopPropagation();
            var prev_label = $(this).parent().prevAll("label:visible").first();
            if (prev_label.length) {
              $("label",dropdown).removeClass("hover");
              prev_label.addClass("hover");
              $(".selectn-cb",prev_label).focus();

            // If we can't go any further up focus the search box
            } else {
              $("label",dropdown).removeClass("hover");
              $(".selectn-search",dropdown).focus();
            }
          }
        });

        // Listen for the all, none, invert or new, buttons to be pressed
        $(".selectn-button",dropdown).click(function(){
          if ($(this).attr("name") == "all") {
            $(".selectn-cb:visible",dropdown).each(function(){
              $(this).prop("checked",true);
              $(this).trigger('change');
            });
          } else if ($(this).attr("name") == "none") {
            $(".selectn-cb:visible",dropdown).each(function(){
              $(this).prop("checked",false);
              $(this).trigger('change');
            });
          } else if ($(this).attr("name") == "invert") {
            $(".selectn-cb:visible",dropdown).each(function(){
              $(this).prop("checked",!$(this).is(':checked'));
              $(this).trigger('change');
            });
          } else if ($(this).attr("name") == "new") {
            var new_item = $(".selectn-search",dropdown).val();
            if (new_item) {
              select.append($('<option>', {value:new_item, text:new_item}));
              dropdown.append(methods.make_item(new_item, new_item, 0));
              $(".selectn-search",dropdown).val("").focus();
              $("label",dropdown).show();
              $(".selectn-cb[value='"+new_item+"']",dropdown).trigger("change");
            }
          }
        });

        // Listen BEFORE text typed into the search input
        $(".selectn-search",dropdown).keydown(function(e){
          // If enter is pressed on the search input, close the dropdown, this makes
          // the dropdown function the same as regular dropdown, which toggle on enter
          if (e.which == 13) {
            e.preventDefault();
            e.stopPropagation();
            if ($(this).val().length > 0) {
              $(".selectn-button[name='new']",dropdown).click();
            } else {
              methods.toggle_open(label, dropdown);
            }
            return false;

          // Down arrow, move to first checkbox
          } else if (e.which == 40) {
            e.preventDefault();
            e.stopPropagation();
            $(this).blur();
            $(".selectn-cb:visible:first",dropdown).focus().parent().addClass("hover");
          }
        });
 
        // Listen AFTER text typed into the search input
        $(".selectn-search",dropdown).keyup(function(e){
          var needle = $(this).val();

          // Give a little hint that the new button can be used
          var new_button_css = needle.length > 0 ? {"background-color":"#e7eff9"} : {"background-color":"white"};
          $(".selectn-button[name='new']",dropdown).css(new_button_css);

          if (needle.substring(0,1) == "!") { // negate the pattern if leading !
            needle = needle.substring(1, needle.length);
            var negate = 1;
          }
          if (needle == "") {
            $("label",dropdown).show();
          } else {
            $("label",dropdown).each(function(){
              if ($(this).children("span").html().toLowerCase().indexOf(needle.toLowerCase()) >= 0) {
                negate? $(this).hide() : $(this).show();
              } else {
                negate? $(this).show() : $(this).hide();
              }
            });
          }
        });

        // Add event listener for checkboxes, this updates the <select> (which is hidden, but still submitted).
        var timeout;
        $(dropdown).on("change",$(".select-cb"),function(e){
          if (timeout) {
            clearTimeout(timeout);
          }
          timeout = setTimeout(function(){
            var ops = [];
            $(".selectn-cb",dropdown).each(function(){
              if ($(this).is(":checked")) {
                $(this).parent().addClass("selectn-cb-selected");
                ops.push($(this).val());
              } else {
                $(this).parent().removeClass("selectn-cb-selected");
              }
            });
            select.val(ops);
            methods.set_label(label,select);
          },20);
        });


        // Hide the original <select> dropdown.
        select.hide(); 
      });        

      return this;
    },

    // Return a checkbox selection entry for the dropdown
    make_item : function(value, text, selected) {
      var cb_ops = {"type":"checkbox", "value":value, "class":"selectn-cb", "checked":selected};
      return "<label class='"+(selected?"selectn-cb-selected":"")+"'>"+
             $("<input/>",cb_ops).outerHTML()+" <span>"+text+"</span></label>";
    },

    // Update the text inside the label when the selected options are changed
    set_label : function (label, select) {
      var label_str = '', comma = '';
      select.find("option").each(function(i, option){ 
        if ($(option).is(":selected")) {
          label_str += comma+$(option).text().trim(); 
          comma = ", ";
        }
      });
      // Put the selected options into the label span and add the label's icon
      label.text(label_str);
      label.append($("<img/>",{"src":"../images/selectn.gif"}));
    },

    toggle_open : function(label, dropdown){
      var p = $(label).position();
      dropdown.css({"position":"absolute", "left": p.left+"px", "top":p.top+$(label).height()+1+"px" });
      if (dropdown.is(":visible")) {
        methods.close_all();
      } else {
        methods.close_all();
        $(label).addClass("selectn-active");
        dropdown.addClass("selectn-active");
        dropdown.show();
        $(".selectn-search",dropdown).focus();
      }
    },

    // Close all dropdowns
    close_all : function() {
      $(".selectn-label.selectn-active").each(function(){
        var select = $("#"+$(this).attr("data-select-id"));
        var dropdown = $(this).next(".selectn-dropdown.selectn-active");
        $(this).removeClass("selectn-active"); 
        dropdown.removeClass("selectn-active");
        dropdown.hide();
        $("label",dropdown).show(); // restore entries that might have been hidden by a search
        $(".selectn-search",dropdown).val('');
        $(this).trigger("selectn-closed",{"label":$(this), "dropdown":dropdown, "select":select});
      });
    },

    // Nuke the selectors, useful if you want to redraw
    destroy : function(context) { 
      $(".selectn-label",context).remove();
      $(".selectn-dropdown",context).remove();
    }
  };
    
  // jQuery module stuff
  $.fn.selectn = function(method) {
    if (methods[method]) {
      return methods[method].apply(this,Array.prototype.slice.call(arguments,1));
    } else if (typeof method === 'object' || !method) {
      return methods["init"].apply(this,arguments);
    } else {
      $.error('Method '+method+' does not exist on jQuery.selectn');
    }
  }

})(jQuery);


$("html").click(function(e) {
  if (!$(e.target).is('.selectn-dropdown *') && !$(e.target).is('.selectn-label') && !$(e.target).is('.selectn-label *')) {
    $(document).selectn("close_all");
  }
});

$("html").on('keydown', function(e) {
  if (e.keyCode === 27 ) { // ESC
    $(document).selectn("close_all");
  }
});

jQuery.fn.outerHTML = function(s) {
  return s
    ? this.before(s).remove()
    : jQuery("<p>").append(this.eq(0).clone()).html();
};

// Stupid jQuery table plugin.

// Call on a table
// sortFns: Sort functions for your datatypes.
(function($) {

  $.fn.stupidtable = function(sortFns) {
    return this.each(function() {
      var $table = $(this);
      sortFns = sortFns || {};

      // Some browsers automatically add <thead>, <tbody> and <tfoot>, nuke them all...
      $("thead",this).replaceWith($("thead",this).contents());
      $("tbody",this).replaceWith($("tbody",this).contents());

      // change <tfoot><tr> into <tr class="tfoot">
      $("tfoot",this).find("tr").addClass("tfoot");
      $("tfoot",this).replaceWith($("tfoot",this).contents());
  
      // Add <thead>, <tbody> and <tfoot> again
      $('tr:first',this).wrapAll("<thead/>");
      $('tr',this).not(':first').not('tr.tfoot').wrapAll("<tbody/>");
      $('tr.tfoot',this).wrapAll("<tfoot/>").removeClass("tfoot");

      // ==================================================== //
      //                  Utility functions                   //
      // ==================================================== //

      // Merge sort functions with some default sort functions.
      sortFns = $.extend({}, $.fn.stupidtable.default_sort_fns, sortFns);

      // Return the resulting indexes of a sort so we can apply
      // this result elsewhere. This returns an array of index numbers.
      // return[0] = x means "arr's 0th element is now at x"
      var sort_map = function(arr, sort_function, reverse_column) {
        var map = [];
        var index = 0;
        if (reverse_column) {
          for (var i = arr.length-1; i >= 0; i--) {
            map.push(i);
          }
        }
        else {
          var sorted = arr.slice(0).sort(sort_function);
          for (var i=0; i<arr.length; i++) {
            index = $.inArray(arr[i], sorted);

            // If this index is already in the map, look for the next index.
            // This handles the case of duplicate entries.
            while ($.inArray(index, map) != -1) {
              index++;
            }
            map.push(index);
          }
        }
        return map;
      };

      // Apply a sort map to the array.
      var apply_sort_map = function(arr, map) {
        var clone = arr.slice(0),
            newIndex = 0;
        for (var i=0; i<map.length; i++) {
          newIndex = map[i];
          clone[newIndex] = arr[i];
        }
        return clone;
      };

      // ==================================================== //
      //                  Begin execution!                    //
      // ==================================================== //

      // Do sorting when THs are clicked
      $table.on("click", "th", function() {
        var trs = $table.children("tbody").children("tr");
        var $this = $(this);
        var th_index = 0;
        var dir = $.fn.stupidtable.dir;

        $table.find("th").slice(0, $this.index()).each(function() {
          var cols = $(this).attr("colspan") || 1;
          th_index += parseInt(cols,10);
        });

        // Determine (and/or reverse) sorting direction, default `asc`
        var sort_dir = $this.data("sort-dir") === dir.ASC ? dir.DESC : dir.ASC;

        // Choose appropriate sorting function. If we're sorting descending, check
        // for a `data-sort-desc` attribute.
        if ( sort_dir == dir.DESC )
          var type = $this.data("sort-desc") || $this.data("sort") || null;
        else
          var type = $this.data("sort") || null;

        // Prevent sorting if no type defined
        if (type == "none") {
          return;
        }
        // Default to case-insensitive string sort
        if (type === null) {
          type = "string-ins";
        }

        // Trigger `beforetablesort` event that calling scripts can hook into;
        // pass parameters for sorted column index and sorting direction
        $table.trigger("beforetablesort", {column: th_index, direction: sort_dir});
        // More reliable method of forcing a redraw
        $table.css("display");

        // Run sorting asynchronously on a timout to force browser redraw after
        // `beforetablesort` callback. Also avoids locking up the browser too much.
        setTimeout(function() {
          // Gather the elements for this column
          var column = [];
          var sortMethod = sortFns[type];

          // Push either the value of the `data-sort-value` attribute if specified
          // or just the text() value in this column to column[] for comparison.
          trs.each(function(index,tr) {
            var $e = $(tr).children().eq(th_index);
            var sort_val = $e.data("sort-value");
            var order_by = typeof(sort_val) !== "undefined" ? sort_val : $e.text();
            column.push(order_by);
          });
          // Create the sort map. This column having a sort-dir implies it was
          // the last column sorted. As long as no data-sort-desc is specified,
          // we're free to just reverse the column.
          var reverse_column = !!$this.data("sort-dir") && !$this.data("sort-desc");
          var theMap = sort_map(column, sortMethod, reverse_column);

          // Reset siblings
          $table.find("th").data("sort-dir", null).removeClass("sorting-desc sorting-asc");
          $this.data("sort-dir", sort_dir).addClass("sorting-"+sort_dir);

          // Replace the content of tbody with the sortedTRs. Strangely (and
          // conveniently!) enough, .append accomplishes this for us.
          var sortedTRs = $(apply_sort_map(trs, theMap));
          $table.children("tbody").append(sortedTRs);

          // Trigger `aftertablesort` event. Similar to `beforetablesort`
          $table.trigger("aftertablesort", {column: th_index, direction: sort_dir});
          // More reliable method of forcing a redraw
          $table.css("display");
        }, 10);
      });
    });
  };

  // Enum containing sorting directions
  $.fn.stupidtable.dir = {ASC: "asc", DESC: "desc"};

  $.fn.stupidtable.default_sort_fns = {
    "int": function(a, b) {
      return parseInt(a, 10) - parseInt(b, 10);
    },
    "float": function(a, b) {
      return parseFloat(a) - parseFloat(b);
    },
    "num": function(a, b) {
      return Number(a) - Number(b);
    },
    "string": function(a, b) {
      if (a < b) return -1;
      if (a > b) return +1;
      return 0;
    },
    "string-ins": function(a, b) {
      a = (a+"").toLowerCase();
      b = (b+"").toLowerCase();
      if (a < b) return -1;
      if (a > b) return +1;
      return 0;
    }
  };

})(jQuery);
/* 
	jQuery TextAreaResizer plugin
	Created on 17th January 2008 by Ryan O'Dell 
	Version 1.0.4
	
	Converted from Drupal -> textarea.js
	Found source: http://plugins.jquery.com/misc/textarea.js
	$Id: textarea.js,v 1.11.2.1 2007/04/18 02:41:19 drumm Exp $

	1.0.1 Updates to missing global 'var', added extra global variables, fixed multiple instances, improved iFrame support
	1.0.2 Updates according to textarea.focus
	1.0.3 Further updates including removing the textarea.focus and moving private variables to top
	1.0.4 Re-instated the blur/focus events, according to information supplied by dec

	
*/
(function($) {
	/* private variable "oHover" used to determine if you're still hovering over the same element */
	var textarea, staticOffset;  // added the var declaration for 'staticOffset' thanks to issue logged by dec.
	var iLastMousePos = 0;
	var iMin = 32;
	var grip;
	/* TextAreaResizer plugin */
	$.fn.TextAreaResizer = function() {
		return this.each(function() {
		    textarea = $(this).addClass('processed'), staticOffset = null;

			// 18-01-08 jQuery bind to pass data element rather than direct mousedown - Ryan O'Dell
		    // When wrapping the text area, work around an IE margin bug.  See:
		    // http://jaspan.com/ie-inherited-margin-bug-form-elements-and-haslayout
		    $(this).wrap('<div class="resizable-textarea"><span></span></div>')
		      .parent().append($('<div class="grippie"></div>').bind("mousedown",{el: this} , startDrag));

		    var grippie = $('div.grippie', $(this).parent())[0];
		    grippie.style.marginRight = (grippie.offsetWidth - $(this)[0].offsetWidth) +'px';

		});
	};
	/* private functions */
	function startDrag(e) {
		textarea = $(e.data.el);
		textarea.blur();
		iLastMousePos = mousePosition(e).y;
		staticOffset = textarea.height() - iLastMousePos;
		//textarea.css('opacity', 0.25);
		$(document).mousemove(performDrag).mouseup(endDrag);
		return false;
	}

	function performDrag(e) {
		var iThisMousePos = mousePosition(e).y;
		var iMousePos = staticOffset + iThisMousePos;
		if (iLastMousePos >= (iThisMousePos)) {
			iMousePos -= 5;
		}
		iLastMousePos = iThisMousePos;
		iMousePos = Math.max(iMin, iMousePos);
		textarea.height(iMousePos + 'px');
		if (iMousePos < iMin) {
			endDrag(e);
		}
		return false;
	}

	function endDrag(e) {
		$(document).unbind('mousemove', performDrag).unbind('mouseup', endDrag);
		//textarea.css('opacity', 1);
		textarea.focus();
		textarea = null;
		staticOffset = null;
		iLastMousePos = 0;
	}

	function mousePosition(e) {
		return { x: e.clientX + document.documentElement.scrollLeft, y: e.clientY + document.documentElement.scrollTop };
	};
})(jQuery);

function redraw_multiple_selects(container) {
  var c = container;
  if (typeof container == 'string') {
    c = '#' + container;
  }
  $('select[multiple]', c).selectn('destroy', c);
  $('select[multiple]', c).selectn();
}

// Make the XML request thing, specify the callback function
function refreshProjectList(radiobutton) {
  url = get_alloc_var('url') + 'task/updateProjectList.php?projectType=' + radiobutton.value;
  makeAjaxRequest(url, 'projectListDropdown', '', 1);
}

function add_gst(value) {
  var tax_percent = get_alloc_var('tax_percent');
  return Math.round(value * (tax_percent / 100 + 1) * 100) / 100;
}

function deduct_gst(value) {
  var tax_percent = get_alloc_var('tax_percent');
  return Math.round(value / (tax_percent / 100 + 1) * 100) / 100;
}

function toggle_view_edit(reset) {
  $('.view').toggle();
  $('.edit').toggle();
  if (reset) {
    $(this).parents('form').reset();
  }
  redraw_multiple_selects();
  return false;
}

function makeAjaxRequest(url, entityid, extra_fields, redraw) {
  $('#' + entityid).html('<img class="ticker" src="../images/ticker2.gif" alt="Updating field..." title="Updating field...">');
  jQuery.get(url, extra_fields, function(data) {
    $('#' + entityid).hide();
    $('#' + entityid).html(data);
    $('#' + entityid).fadeIn('fast');

    // We may need to redraw the select widgets
    if (redraw) {
      redraw_multiple_selects(entityid);
    }
  });
}

// This is a generic show/hide for anything
function set_grow_shrink(id, id_to_hide, use_classes_instead_of_ids) {
  // toggle the other div - if any
  if (use_classes_instead_of_ids && id_to_hide) {
    $('.' + id_to_hide).slideToggle('fast');
  } else if (id_to_hide) {
    $('#' + id_to_hide).slideToggle('fast');
  }
  // hide or show the actual div
  if (use_classes_instead_of_ids) {
    $('.' + id).slideToggle('fast');
  } else {
    $('#' + id).slideToggle('fast');
  }
  return false;
}

function sidebyside_activate(id, redraw) {
  var arr = [];
  var i;
  $.each($('.sidebyside'), function(k, v) {
    arr[arr.length] = v.id.replace('sbs_link_', '');
  });

  if (!id) {
    id = get_alloc_var('side_by_side_link');
  }
  if (!id) {
    id = arr[0];
  }

  if (id == 'sbsAll') {
    for (i = 0; i < arr.length; i++) {
      if (arr[i] != 'sbsAll') {
        $('#' + arr[i]).show();
        $('#sbs_link_' + arr[i]).removeClass('sidebyside_active').addClass('sidebyside');
      }
    }
    $('#sbs_link_' + id).addClass('sidebyside_active');

  } else {
    for (i = 0; i < arr.length; i++) {
      if (arr[i] != 'sbsAll') {
        $('#' + arr[i]).hide();
      }
      $('#sbs_link_' + arr[i]).removeClass('sidebyside_active').addClass('sidebyside');
    }
    $('#sbs_link_' + id).addClass('sidebyside_active');
    $('#' + id).show();
  }

  // allows us to target particular pages for redraw_multiple_selects();
  redraw_multiple_selects();
}

function help_text_on(id, str) {
  $('#main').append("<div id='helper' class='corner' style='display:none'></div>");
  $('#helper').html(str).hide();
  var offset = $('#' + id).position();
  var x = offset.left - 420;
  if (x < 0) {
    x = x + 400;
  }
  var y = offset.top + 20;
  if ((y + $('#helper').height() + 40) > $(document).height()) {
    y = y - $('#helper').height() - 40;
  }
  $('#helper').css('left', x);
  $('#helper').css('top', y);
  $('#helper').fadeIn('normal');
}
function help_text_off(id) {
  $('#helper').fadeOut('normal');
  $('#helper').remove();
}
function preload_field(element, text) {
  $(document).ready(function() {
    $(element).bind('focus', function(e) {
      if (this.value == text) {
        this.style.color = '#333333';
        this.value = '';
      }
    });
    $(element).each(function() {
      if (this.value == '') {
        this.style.color = '#bbbbbb';
        this.value = text;
      }
    });
    $('form').submit(function() {
      $(element).each(function() {
        if (this.value == text) {
          this.value = '';
        }
      });
    });
  });
}

function save_recipients(data) {
  var p = data.select.parent();
  var commentID = p.find('input[name=commentID]').val();
  var values = data.select.val();
  var label;

  jQuery.post('../comment/updateRecipients.php', {
    'commentID': commentID,
    'comment_recipients': values
  }, function(data) {
    p.parent().hide();
    if (data == 'external') {
      label = '<em class="faint warn">[ External Conversation ]</em>';
      p.parents('.panel').addClass('loud');
    } else {
      label = '<em class="faint">[ Internal Conversation ]</em>';
      p.parents('.panel').removeClass('loud');
    }
    p.parent().parent().find('a.recipient_editor_link').html(label).show();
  });
}

calendar_counter = 0;
function refresh_calendars() {
  $('.datefield').each(function() {
    calendar_counter = calendar_counter + 1;
    var calendar_id = 'calendar_' + calendar_counter;
    var button_id = 'calendar_button_' + calendar_counter;
    $(this).attr('id', calendar_id);
    $(this).next('img').attr('id', button_id);

    var settings = {};
    settings.inputField = calendar_id;
    settings.ifFormat = '%Y-%m-%d';
    settings.button = button_id;
    settings.showOthers = 1;
    settings.align = 'Bl';
    settings.firstDay = get_alloc_var('cal_first_day');
    settings.step = 1;
    settings.weekNumbers = 0;
    settings.date = $(this).attr('value');
    Calendar.setup(settings);
  });
}

// Preload mouseover images
if (document.images) {
  pic5 = new Image(119, 13);
  pic5.src = '../images/ticker2.gif';
  pic6 = new Image(16, 16);
  pic6.src = '../images/spinner.gif';
}


// When the document has loaded...
$(document).ready(function() {

  // Focus the login form fields
  if ($('#login_form').length) {
    if (!$('#username').val()) {
      $('#username').focus();
    } else {
      $('#password').focus();
    }
  }

  // Enable the dynamic table sorting
  $('table.sortable').each(function() {
    var t = $(this).stupidtable();
    var arr = $("<i class='arrow icon-circle-arrow-up' style='font-size:95%'></i>").css('visibility', 'hidden');
    $('thead > tr > th', this).not(function() {
      return $(this).data('sort') == 'none';
    }).append(arr).css({
      'cursor': 'pointer'
    });

    t.bind('aftertablesort', function(event, data) {
      var th = $(this).find('th');
      th.find('.arrow').css('visibility', 'hidden');
      th.eq(data.column).find('.arrow').removeClass('icon-circle-arrow-down icon-circle-arrow-up').addClass(data.direction === 'asc' ? 'icon-circle-arrow-up' : 'icon-circle-arrow-down').css('visibility', 'visible');
      $('tr:nth-child(even)', this).removeClass('even odd').addClass('even');
      $('tr:nth-child(odd)', this).removeClass('even odd').addClass('odd');
    });
  });

  // Give the tables alternating stripes
  $('.list tr:nth-child(even)').addClass('even');
  $('.list tr:nth-child(odd)').addClass('odd');
  $('.delete_button').on('click', function(e) {
    return confirm('Click OK to confirm deletion.');
  });
  $('.confirm_button').bind('click', function(e) {
    return confirm('Click OK to confirm.');
  });
  $('input.datefield').bind('dblclick', function(e) {
    var now = new Date();
    this.value = now.getFullYear() + '-' + ((1e2 + now.getMonth() + 1 + '').substr(1)) + '-' + (1e2 + now.getDate() + '').substr(1);
  });

  $('tr.clickrow').bind('click', function(e) {
    var id = this.id.split('_')[1]; // clickrow_43242
    if (id && !$(e.target).is('input:checkbox') && !$(e.target).is('a') && !$(e.target).is('b')) {
      $('#checkbox_' + id).attr('checked', !$('#checkbox_' + id).attr('checked'));
    }
  });

  // This loads up certain textboxes with faint help text that vanishes upon focus
  preload_field('#menu_form_needle', 'Enter search text or ID...');

  $('#search_action').change(function() {
    var bits = $(this).val().split('_');
    if (bits[0] == 'create' || bits[0] == 'history') {
      $('#form_search').submit();
    }
  });

  // Add resize grippies to all textareas
  $('textarea:not(.processed)').TextAreaResizer();

  // Add toggles for filters
  $('.toggleFilter').click(function() {
    var l;
    var d = $('.filter').css('display');
    if (d == 'table') {
      l = 'Show Filter';
      d = 'none';
    } else {
      l = 'Hide Filter';
      d = 'table';
    }
    $('.filter').css('display', d);
    $(this).text(l);
    redraw_multiple_selects();
    return false;
  });

  // Activate user preference for displaying filters
  if (get_alloc_var('show_filters') != 'yes') {
    $('.toggleFilter').trigger('click');
  }

  $('td.calendar_day img').hide();
  $('.alloc_calendar').on('click', 'td.calendar_day', function(e) {
    $('img', this).toggle();
    $(this).toggleClass('selected');
    return true;
  });

  $('input.toggler').click(function() {
    return $('.task_checkboxes').each(function() {
      this.checked = !this.checked;
    });
  });

  // Activate side by side links/tabs, if any
  $('.sidebyside').click(function(e) {
    var redraw = $(this).attr('data-sbs-redraw');
    sidebyside_activate(e.target.id.replace('sbs_link_', ''), redraw);
    return false;
  });
  sidebyside_activate();

  $('.edit').hide();

  // Mark up the date fields with dynamic calendars
  refresh_calendars();

  // Interested Parties recipients editor
  $('.recipient_editor_link').click(function() {
    var commentID = this.id.split('_')[2];
    $(this).toggle();
    $('#recipient_dropdown_' + commentID).slideToggle('fast');
    redraw_multiple_selects('recipient_dropdown_' + commentID);
    return false;
  });

  $('.commentreply').click(function(e) {
    $('#interested_parties_selector').hide();
    $(this).parents('.pcomment').append($('#id_new_comment').css({
      'display': 'inline-table'
    }));
    $('#id_new_comment').find('input[name=entity]').val('comment');
    $('#id_new_comment').find('input[name=entityID]').val($(this).parents('.pcomment').attr('data-comment-id'));
    return false;
  });

  $('.commentnew').click(function(e) {
    $('#interested_parties_selector').show();
    $('#id_new_comment').find('input[name=entity]').val($('#id_new_comment').find('input[name=commentMaster]').val());
    $('#id_new_comment').find('input[name=entityID]').val($('#id_new_comment').find('input[name=commentMasterID]').val());
    $('#new_comment_container').append($('#id_new_comment').css({
      'display': 'inline-table'
    }));
    return false;
  });

  $('a.star').on('click', function() {
    if ($('b', $(this)).hasClass('icon-star-empty')) {
      $('b', $(this)).removeClass('icon-star-empty').addClass('icon-star');
      $(this).addClass('hot');
      $(this).closest('td').attr({
        'data-sort-value': '1'
      });
    } else {
      $('b', $(this)).removeClass('icon-star hot').addClass('icon-star-empty');
      $(this).removeClass('hot');
      $(this).closest('td').attr({
        'data-sort-value': '2'
      });
    }
    $.get($(this).attr('href'));
    return false;
  });


  // This duplicates a button with class=default and puts it first,
  // so that the web browser treats it as the default submission button.
  $('form').each(function() {
    var thisform = $(this);
    thisform.prepend(thisform.find('button.default').clone().css({
      position: 'absolute',
      left: '-999px',
      top: '-999px',
      height: 0,
      width: 0
    }));
  });


  $('.config-link').click(function() {

    var elem = $('.' + $(this).attr('id')).css({
      'position': 'absolute',
      'top': 10,
      'right': 0,
      'display': 'inline'
    }).addClass('corner').addClass('shadow');

    // config boxes that have class "lazy" are lazy-loading, i.e. dynamically populated via ajax
    var i = $(this).attr('id');
    if ($('.' + i).hasClass('lazy')) {
      $('.' + i).html('<img class="ticker" src="../images/ticker2.gif" alt="Updating field..." title="Updating field...">');
      $.get(get_alloc_var('url') + 'home/' + i + '.php', function(data) {
        $('.' + i).html(data);
        redraw_multiple_selects();
      });
    }

    $(this).parent().append(elem);
    redraw_multiple_selects($(this).parent());
    return false;
  });

  // This is mainly used for the edit comment recipient logic on the comments screens
  // The data-callback is most likely save_recipients(); Go there.
  $('html').bind('selectn-closed', function(e, data) {
    if (data.select.attr('data-callback')) {
      window[data.select.attr('data-callback')](data);
    }
  });

});


