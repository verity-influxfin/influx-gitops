!function(e){var t={};function r(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)r.d(n,a,function(t){return e[t]}.bind(null,a));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/",r(r.s=4)}({4:function(e,t,r){e.exports=r("nXa9")},"4I1t":function(e,t,r){var n=r("bkDy");"string"==typeof n&&(n=[[e.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(n,a);n.locals&&(e.exports=n.locals)},"5Q0L":function(e,t,r){var n=r("JqrV");"string"==typeof n&&(n=[[e.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(n,a);n.locals&&(e.exports=n.locals)},"9tPo":function(e,t){e.exports=function(e){var t="undefined"!=typeof window&&window.location;if(!t)throw new Error("fixUrls requires window.location");if(!e||"string"!=typeof e)return e;var r=t.protocol+"//"+t.host,n=r+t.pathname.replace(/\/[^\/]*$/,"/");return e.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,(function(e,t){var a,i=t.trim().replace(/^"(.*)"$/,(function(e,t){return t})).replace(/^'(.*)'$/,(function(e,t){return t}));return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(i)?e:(a=0===i.indexOf("//")?i:0===i.indexOf("/")?r+i:n+i.replace(/^\.\//,""),"url("+JSON.stringify(a)+")")}))}},I1BE:function(e,t){e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var r=function(e,t){var r=e[1]||"",n=e[3];if(!n)return r;if(t&&"function"==typeof btoa){var a=(o=n,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */"),i=n.sources.map((function(e){return"/*# sourceURL="+n.sourceRoot+e+" */"}));return[r].concat(i).concat([a]).join("\n")}var o;return[r].join("\n")}(t,e);return t[2]?"@media "+t[2]+"{"+r+"}":r})).join("")},t.i=function(e,r){"string"==typeof e&&(e=[[null,e,""]]);for(var n={},a=0;a<this.length;a++){var i=this[a][0];"number"==typeof i&&(n[i]=!0)}for(a=0;a<e.length;a++){var o=e[a];"number"==typeof o[0]&&n[o[0]]||(r&&!o[2]?o[2]=r:r&&(o[2]="("+o[2]+") and ("+r+")"),t.push(o))}},t}},JqrV:function(e,t,r){(e.exports=r("I1BE")(!1)).push([e.i,"@-webkit-keyframes splide-loading{0%{transform:rotate(0)}to{transform:rotate(1turn)}}@keyframes splide-loading{0%{transform:rotate(0)}to{transform:rotate(1turn)}}.splide__container{position:relative;box-sizing:border-box}.splide__list{margin:0!important;padding:0!important;width:-webkit-max-content;width:-moz-max-content;width:max-content;will-change:transform}.splide.is-active .splide__list{display:flex}.splide__pagination{display:inline-flex;align-items:center;width:95%;flex-wrap:wrap;justify-content:center;margin:0}.splide__pagination li{list-style-type:none;display:inline-block;line-height:1;margin:0}.splide{visibility:hidden}.splide,.splide__slide{position:relative;outline:none}.splide__slide{box-sizing:border-box;list-style-type:none!important;margin:0;flex-shrink:0}.splide__slide img{vertical-align:bottom}.splide__slider{position:relative}.splide__spinner{position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;display:inline-block;width:20px;height:20px;border-radius:50%;border:2px solid #999;border-left-color:transparent;-webkit-animation:splide-loading 1s linear infinite;animation:splide-loading 1s linear infinite}.splide__track{position:relative;z-index:0;overflow:hidden}.splide--draggable>.splide__track>.splide__list>.splide__slide{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.splide--fade>.splide__track>.splide__list{display:block}.splide--fade>.splide__track>.splide__list>.splide__slide{position:absolute;top:0;left:0;z-index:0;opacity:0}.splide--fade>.splide__track>.splide__list>.splide__slide.is-active{position:relative;z-index:1;opacity:1}.splide--rtl{direction:rtl}.splide--ttb>.splide__track>.splide__list{display:block}.splide--ttb>.splide__pagination{width:auto}.splide__arrow{position:absolute;z-index:1;top:50%;transform:translateY(-50%);width:2em;height:2em;border-radius:50%;display:flex;align-items:center;justify-content:center;border:none;padding:0;opacity:.7;background:#ccc}.splide__arrow svg{width:1.2em;height:1.2em}.splide__arrow:hover{cursor:pointer;opacity:.9}.splide__arrow:focus{outline:none}.splide__arrow--prev{left:1em}.splide__arrow--prev svg{transform:scaleX(-1)}.splide__arrow--next{right:1em}.splide__pagination{position:absolute;z-index:1;bottom:.5em;left:50%;transform:translateX(-50%);padding:0}.splide__pagination__page{display:inline-block;width:8px;height:8px;background:#ccc;border-radius:50%;margin:3px;padding:0;transition:transform .2s linear;border:none;opacity:.7}.splide__pagination__page.is-active{transform:scale(1.4);background:#fff}.splide__pagination__page:hover{cursor:pointer;opacity:.9}.splide__pagination__page:focus{outline:none}.splide__progress__bar{width:0;height:3px;background:#ccc}.splide--nav>.splide__track>.splide__list>.splide__slide{border:3px solid transparent}.splide--nav>.splide__track>.splide__list>.splide__slide.is-active{border-color:#000}.splide--nav>.splide__track>.splide__list>.splide__slide:focus{outline:none}.splide--rtl>.splide__arrows .splide__arrow--prev,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--prev{right:1em;left:auto}.splide--rtl>.splide__arrows .splide__arrow--prev svg,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--prev svg{transform:scaleX(1)}.splide--rtl>.splide__arrows .splide__arrow--next,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--next{left:1em;right:auto}.splide--rtl>.splide__arrows .splide__arrow--next svg,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--next svg{transform:scaleX(-1)}.splide--ttb>.splide__arrows .splide__arrow,.splide--ttb>.splide__track>.splide__arrows .splide__arrow{left:50%;transform:translate(-50%)}.splide--ttb>.splide__arrows .splide__arrow--prev,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--prev{top:1em}.splide--ttb>.splide__arrows .splide__arrow--prev svg,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--prev svg{transform:rotate(-90deg)}.splide--ttb>.splide__arrows .splide__arrow--next,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--next{top:auto;bottom:1em}.splide--ttb>.splide__arrows .splide__arrow--next svg,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--next svg{transform:rotate(90deg)}.splide--ttb>.splide__pagination{display:flex;flex-direction:column;bottom:50%;left:auto;right:.5em;transform:translateY(50%)}",""])},"KHd+":function(e,t,r){"use strict";function n(e,t,r,n,a,i,o,s){var d,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=r,c._compiled=!0),n&&(c.functional=!0),i&&(c._scopeId="data-v-"+i),o?(d=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),a&&a.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(o)},c._ssrRegister=d):a&&(d=s?function(){a.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:a),d)if(c.functional){c._injectStyles=d;var l=c.render;c.render=function(e,t){return d.call(t),l(e,t)}}else{var p=c.beforeCreate;c.beforeCreate=p?[].concat(p,d):[d]}return{exports:e,options:c}}r.d(t,"a",(function(){return n}))},OM3N:function(e,t,r){"use strict";r("4I1t")},"aET+":function(e,t,r){var n,a,i={},o=(n=function(){return window&&document&&document.all&&!window.atob},function(){return void 0===a&&(a=n.apply(this,arguments)),a}),s=function(e,t){return t?t.querySelector(e):document.querySelector(e)},d=function(e){var t={};return function(e,r){if("function"==typeof e)return e();if(void 0===t[e]){var n=s.call(this,e,r);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(e){n=null}t[e]=n}return t[e]}}(),c=null,l=0,p=[],u=r("9tPo");function f(e,t){for(var r=0;r<e.length;r++){var n=e[r],a=i[n.id];if(a){a.refs++;for(var o=0;o<a.parts.length;o++)a.parts[o](n.parts[o]);for(;o<n.parts.length;o++)a.parts.push(h(n.parts[o],t))}else{var s=[];for(o=0;o<n.parts.length;o++)s.push(h(n.parts[o],t));i[n.id]={id:n.id,refs:1,parts:s}}}}function g(e,t){for(var r=[],n={},a=0;a<e.length;a++){var i=e[a],o=t.base?i[0]+t.base:i[0],s={css:i[1],media:i[2],sourceMap:i[3]};n[o]?n[o].parts.push(s):r.push(n[o]={id:o,parts:[s]})}return r}function _(e,t){var r=d(e.insertInto);if(!r)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var n=p[p.length-1];if("top"===e.insertAt)n?n.nextSibling?r.insertBefore(t,n.nextSibling):r.appendChild(t):r.insertBefore(t,r.firstChild),p.push(t);else if("bottom"===e.insertAt)r.appendChild(t);else{if("object"!=typeof e.insertAt||!e.insertAt.before)throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");var a=d(e.insertAt.before,r);r.insertBefore(t,a)}}function m(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e);var t=p.indexOf(e);t>=0&&p.splice(t,1)}function b(e){var t=document.createElement("style");if(void 0===e.attrs.type&&(e.attrs.type="text/css"),void 0===e.attrs.nonce){var n=function(){0;return r.nc}();n&&(e.attrs.nonce=n)}return v(t,e.attrs),_(e,t),t}function v(e,t){Object.keys(t).forEach((function(r){e.setAttribute(r,t[r])}))}function h(e,t){var r,n,a,i;if(t.transform&&e.css){if(!(i="function"==typeof t.transform?t.transform(e.css):t.transform.default(e.css)))return function(){};e.css=i}if(t.singleton){var o=l++;r=c||(c=b(t)),n=k.bind(null,r,o,!1),a=k.bind(null,r,o,!0)}else e.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(r=function(e){var t=document.createElement("link");return void 0===e.attrs.type&&(e.attrs.type="text/css"),e.attrs.rel="stylesheet",v(t,e.attrs),_(e,t),t}(t),n=A.bind(null,r,t),a=function(){m(r),r.href&&URL.revokeObjectURL(r.href)}):(r=b(t),n=y.bind(null,r),a=function(){m(r)});return n(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;n(e=t)}else a()}}e.exports=function(e,t){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(t=t||{}).attrs="object"==typeof t.attrs?t.attrs:{},t.singleton||"boolean"==typeof t.singleton||(t.singleton=o()),t.insertInto||(t.insertInto="head"),t.insertAt||(t.insertAt="bottom");var r=g(e,t);return f(r,t),function(e){for(var n=[],a=0;a<r.length;a++){var o=r[a];(s=i[o.id]).refs--,n.push(s)}e&&f(g(e,t),t);for(a=0;a<n.length;a++){var s;if(0===(s=n[a]).refs){for(var d=0;d<s.parts.length;d++)s.parts[d]();delete i[s.id]}}}};var w,x=(w=[],function(e,t){return w[e]=t,w.filter(Boolean).join("\n")});function k(e,t,r,n){var a=r?"":n.css;if(e.styleSheet)e.styleSheet.cssText=x(t,a);else{var i=document.createTextNode(a),o=e.childNodes;o[t]&&e.removeChild(o[t]),o.length?e.insertBefore(i,o[t]):e.appendChild(i)}}function y(e,t){var r=t.css,n=t.media;if(n&&e.setAttribute("media",n),e.styleSheet)e.styleSheet.cssText=r;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(r))}}function A(e,t,r){var n=r.css,a=r.sourceMap,i=void 0===t.convertToAbsoluteUrls&&a;(t.convertToAbsoluteUrls||i)&&(n=u(n)),a&&(n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(a))))+" */");var o=new Blob([n],{type:"text/css"}),s=e.href;e.href=URL.createObjectURL(o),s&&URL.revokeObjectURL(s)}},bkDy:function(e,t,r){(e.exports=r("I1BE")(!1)).push([e.i,".cardgame-wrapper .page-header {\n  z-index: 11;\n  background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);\n}\n.cardgame-wrapper .page-header .web-logo {\n  width: 140px;\n  margin: 0px;\n}\n.cardgame-wrapper .cards {\n  background-color: #ffd186;\n  text-align: center;\n  height: 1040px;\n}\n.cardgame-wrapper .cards .cardA, .cardgame-wrapper .cards .cardB {\n  width: 110px;\n  height: 150px;\n  display: inline-table;\n  border-radius: 22px;\n  border-width: 6px;\n  border-style: solid;\n  padding: 8px 5px;\n  font-weight: bold;\n  color: white;\n  margin: 15px 5px 0;\n  position: relative;\n  transition: transform 1s;\n  transform-style: preserve-3d;\n  background-repeat: no-repeat;\n}\n.cardgame-wrapper .cards .cardA.active, .cardgame-wrapper .cards .cardB.active {\n  transform: rotateY(180deg);\n  position: absolute;\n  border-radius: 22px;\n  width: 100%;\n  height: 100%;\n  margin: 0 0 0 0;\n  border: 0;\n  z-index: 999;\n  left: 0;\n  top: 86px;\n  background-color: transparent;\n}\n.cardgame-wrapper .cards .cardA.active .card-front, .cardgame-wrapper .cards .cardB.active .card-front {\n  display: none;\n}\n.cardgame-wrapper .cards .cardA.active .cardQuestion, .cardgame-wrapper .cards .cardB.active .cardQuestion {\n  font-size: 34px;\n}\n.cardgame-wrapper .cards .cardA.done, .cardgame-wrapper .cards .cardB.done {\n  transform: rotateY(180deg);\n}\n.cardgame-wrapper .cards .cardA.done .card-front, .cardgame-wrapper .cards .cardB.done .card-front {\n  display: none;\n}\n.cardgame-wrapper .cards .cardA.done .card-back, .cardgame-wrapper .cards .cardB.done .card-back {\n  padding: 0px 5px !important;\n}\n.cardgame-wrapper .cards .cardA.done .cardQuestion, .cardgame-wrapper .cards .cardB.done .cardQuestion {\n  font-size: 12px;\n}\n.cardgame-wrapper .cards .cardA.done .cardAns, .cardgame-wrapper .cards .cardB.done .cardAns {\n  font-size: 12px;\n}\n.cardgame-wrapper .cards .cardA .cardFace, .cardgame-wrapper .cards .cardB .cardFace {\n  margin: 45px 0px 0 8px;\n  width: 75px;\n  position: relative;\n}\n.cardgame-wrapper .cards .cardA .cardNum, .cardgame-wrapper .cards .cardB .cardNum {\n  font-size: 24px;\n  margin: -8px 0 0 4px;\n  position: absolute;\n}\n.cardgame-wrapper .cards .cardA .cardQuestion, .cardgame-wrapper .cards .cardB .cardQuestion {\n  font-size: 12px;\n  padding: 0 5px;\n  display: block;\n  text-align: left;\n}\n.cardgame-wrapper .cards .cardA .cardAns, .cardgame-wrapper .cards .cardB .cardAns {\n  display: block;\n  font-size: 18px;\n  height: 36px;\n}\n.cardgame-wrapper .cards .cardA .cardFlip, .cardgame-wrapper .cards .cardB .cardFlip {\n  position: absolute;\n  -webkit-backface-visibility: hidden;\n          backface-visibility: hidden;\n  left: 6px;\n}\n.cardgame-wrapper .cards .cardA .cardFlip.card-back, .cardgame-wrapper .cards .cardB .cardFlip.card-back {\n  transform: rotateY(180deg);\n  height: 100%;\n  background-repeat: no-repeat;\n  padding: 76px 32px;\n}\n.cardgame-wrapper .cards .cardA {\n  background-color: #012060;\n  border-color: #b40b12;\n}\n.cardgame-wrapper .cards .cardA.active .card-back {\n  background-image: url(/images/cardGameRedLine.svg);\n}\n.cardgame-wrapper .cards .cardB {\n  background-color: #b40b12;\n  border-color: #012060;\n}\n.cardgame-wrapper .cards .cardB.active .card-back {\n  background-image: url(/images/cardGameBlueLine.svg);\n}",""])},lm7J:function(e,t,r){"use strict";r("sAlr")},nXa9:function(e,t,r){"use strict";r.r(t);var n={data:function(){return{process:!1,randkeys:[],faceNum:["H","A","P","P","Y","N","E","W","Y","E","A","R"],imgs:{1:{question:"普匯目前主要業務項目為何?",selection:["借貸/投資","保險"]},2:{question:"p2p借貸的英文縮寫?",selection:["peer to peer","pika to pika"]},3:{question:"普匯今年舉辦什麼活動?",selection:["AI金融科技創新創意競賽","國外旅遊"]},4:{question:"普匯為下列何種類型公司?",selection:["金融科技","科技金融"]},5:{question:"普匯是媒合誰與誰的平台?",selection:["借款人與投資人","你和我"]},6:{question:"普匯的吉祥物叫?",selection:["小普","來福"]},7:{question:"普匯的代表色為何?",selection:["黑色","藍色"]},8:{question:"普匯主打的服務是?",selection:["AI線上無人化","見面對保狂call你"]},9:{question:"普匯第一個上線的產品是?",selection:["學生貸","房貸"]},10:{question:"普匯投資是多少元起投?",selection:["1000元","1000萬"]},11:{question:"普匯創立於哪一年份??",selection:["2017年","2025年"]},12:{question:"普匯APP於哪一年上線?",selection:["2019年","尚未上線"]}}}},mounted:function(){this.randkeys=[1,2,3,4,5,6,7,8,9,10,11,12];for(var e=this.randkeys.length-1;e>0;e--){var t=Math.floor(Math.random()*(e+1)),r=[this.randkeys[t],this.randkeys[e]];this.randkeys[e]=r[0],this.randkeys[t]=r[1]}$(document).off("click",".cardA:not(.done),.cardB:not(.done)").on("click",".cardA:not(.done),.cardB:not(.done)",(function(e,t){$(".cardA,.cardB").hide(),$(this).addClass("active").show()}));var n={user_id:localStorage.getItem("userData")?JSON.parse(localStorage.getItem("userData")).id:{}};axios.post("/getData",n).then((function(e){e.data&&(alert("您已參加過遊戲囉!!"),location.replace("/"))})).catch((function(e){console.error(e)}))},methods:{ans:function(e){var t=this;if(this.process)console.log("duplicate!!");else{this.process=!0;var r={qnum:e.target.parentElement.dataset.id,qans:e.target.dataset.ans};axios.post("/getAns",r).then((function(e){1==e.data.ans?($(".active:not(.done)").addClass("done"),$(".cardA,.cardB").show(),$(".active").removeClass("active"),$("[data-id="+r.qnum+"] .cardAns:not([data-ans="+r.qans+"])").remove(),$(".done").length>2?(alert("恭喜全部答對，請前往抽獎"),window.location.href="/cardgame/turntable"):alert("恭喜您答對了!! 下一題~")):(alert("答錯囉~再讓我們玩一次吧！"),window.location.href="/cardgame"),t.process=!1})).catch((function(e){console.error(e)}))}}}},a=(r("OM3N"),r("KHd+")),i=Object(a.a)(n,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"cardgame-wrapper"},[r("nav",{staticClass:"page-header navbar navbar-expand-lg"},[r("div",{staticClass:"web-logo"},[r("router-link",{attrs:{to:"index"}},[r("img",{staticClass:"img-fluid",attrs:{src:"/images/logo_new.png"}})])],1)]),e._v(" "),r("div",{staticClass:"cards"},[e._l(e.randkeys,(function(t,n){return r("span",{class:"card"+(n%2==0?"B":"A")},[r("span",{staticClass:"cardFlip card-front"},[r("span",{staticClass:"cardNum"},[e._v(e._s(e.faceNum[n]))]),e._v(" "),r("img",{staticClass:"cardFace",attrs:{src:"/images/cardGame"+(n+1)+".png"}})]),e._v(" "),r("span",{staticClass:"cardFlip card-back"},[r("span",{staticClass:"cardQuestion",attrs:{"data-id":t}},[e._v("\n            "+e._s(e.imgs[t].question)),r("br"),r("br"),e._v(" "),r("span",{staticClass:"cardAns",attrs:{"data-ans":"A"},on:{"~click":function(t){return e.ans(t)}}},[e._v("(A)"+e._s(e.imgs[t].selection[0]))]),e._v(" "),r("span",{staticClass:"cardAns",attrs:{"data-ans":"B"},on:{"~click":function(t){return e.ans(t)}}},[e._v("(B)"+e._s(e.imgs[t].selection[1]))])])])])}))],2)])}),[],!1,null,null,null).exports,o={data:function(){return{process:!1,randkeys:[],faceNum:["H","A","P","P","Y","N","E","W","Y","E","A","R"],picTop:["40","50","50","41","49","41","43","50","56","43","49","49"],picLeft:["4","8","4","5","5","3","1","7","6","2","6","2","6"]}},mounted:function(){this.randkeys=[1,2,3,4,5,6,7,8,9,10,11,12],$(document).off("click",".cardA,.cardB").on("click",".cardA,.cardB",(function(e,t){$(this).addClass("active")}));var e={user_id:localStorage.getItem("userData")?JSON.parse(localStorage.getItem("userData")).id:{}};axios.post("/getData",e).then((function(e){e.data&&(alert("您已參加過遊戲囉!!"),location.replace("/"))})).catch((function(e){console.error(e)}))},methods:{turn:function(e){var t=this;if(this.process)console.log("duplicate!!");else{this.process=!0;var r={user_id:localStorage.getItem("userData")?JSON.parse(localStorage.getItem("userData")).id:{}};axios.post("/setGamePrize",r).then((function(e){if(null!=e.data.prize){var r=e.data.prize,n=1*e.data.rotate+3e3;t.process=!1,$(".turntable .disk").css("transform","rotate("+n+"deg)"),setTimeout((function(){alert("恭喜抽中"+r+"！<br /><br />※活動截止後由公司寄發中獎簡訊通知"),location.replace("/")}),11e3)}else alert("已經玩過遊戲囉!"+(""!=e.data?"抽中的是->"+e.data:"")),location.replace("/")})).catch((function(e){console.error(e)}))}}}},s=(r("lm7J"),[{path:"*",redirect:"/question"},{path:"/question",component:i},{path:"/turntable",component:Object(a.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"turntable-wrapper",on:{"~click":function(t){return e.turn()}}},[r("nav",{staticClass:"page-header navbar navbar-expand-lg"},[r("div",{staticClass:"web-logo"},[r("router-link",{attrs:{to:"index"}},[r("img",{staticClass:"img-fluid",attrs:{src:"/images/logo_new.png"}})])],1)]),e._v(" "),e._m(0)])}),[function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"block"},[r("ul",[e._v("抽獎辦法：\n      "),r("li",[e._v("每用戶只可抽獎一次")]),e._v(" "),r("li",[e._v("活動截止後由公司寄發中獎簡訊通知")]),e._v(" "),r("li",[e._v("本公司保有隨時修改本活動之權利，如有任何變更內容或詳細注意事項將公布於官網")])]),e._v(" "),r("div",{staticClass:"turntable"},[r("div",{staticClass:"arrow"}),e._v(" "),r("div",{staticClass:"disk"})])])}],!1,null,null,null).exports}]);r("5Q0L");$((function(){var e=new Date,t=new Date("2021-02-01 00:00:00"),r=new Date("2021-03-10 00:00:00");if(t>=e||e>r)return alert("敬請期待！"),void location.replace("/index");if("login"!=localStorage.getItem("flag")&&(alert("請登入會員"),location.replace("/index")),window.innerWidth>=767)return alert("請使用行動裝置瀏覽"),void location.replace("/index");var n=new VueRouter({routes:s,mode:"history",base:"cardgame"});n.beforeEach((function(e,t,r){"/"===e.path?(gtag("config","UA-117279688-9",{page_path:"/make"}),r("/make")):(gtag("config","UA-117279688-9",{page_path:e.path}),r())}));new Vue({el:"#cardgame",router:n,computed:{isInvestor:function(){return localStorage.getItem("userData")?JSON.parse(localStorage.getItem("userData")).investor:"0"},flag:function(){return localStorage.getItem("flag")?localStorage.getItem("flag"):""},userData:function(){return localStorage.getItem("userData")?JSON.parse(localStorage.getItem("userData")):{}}},created:function(){}})}))},sAlr:function(e,t,r){var n=r("vBHl");"string"==typeof n&&(n=[[e.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(n,a);n.locals&&(e.exports=n.locals)},vBHl:function(e,t,r){(e.exports=r("I1BE")(!1)).push([e.i,".turntable-wrapper .page-header {\n  z-index: 11;\n  background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);\n}\n.turntable-wrapper .page-header .web-logo {\n  width: 140px;\n  margin: 0px;\n}\n.turntable-wrapper .block {\n  background-color: #ffd186;\n  width: 100%;\n  height: 100%;\n  padding: 10px 0 300px;\n  text-align: center;\n}\n.turntable-wrapper .block ul {\n  font-weight: bold;\n  font-size: 18px;\n  margin: 10px 0 15px;\n  text-align: left;\n}\n.turntable-wrapper .block li {\n  font-weight: 400;\n  font-size: 14px;\n}\n.turntable-wrapper .block .turntable {\n  background-color: #ffd186;\n  display: inline-block;\n}\n.turntable-wrapper .block .turntable .disk {\n  background-image: url(/images/turntable.png);\n  background-repeat: no-repeat;\n  background-size: 100%;\n  background-position: 0 0px;\n  background-color: transparent;\n  width: 350px;\n  height: 350px;\n  transition: 10s cubic-bezier(0.1, 0.46, 0, 0.94);\n  transform-style: preserve-3d;\n  transform: rotate(0deg);\n  display: inline-block;\n}\n.turntable-wrapper .block .turntable .arrow {\n  background-image: url(/images/turntableArrow.png);\n  background-repeat: no-repeat;\n  background-size: 100%;\n  z-index: 99;\n  width: 70px;\n  height: 105px;\n  display: inline-block;\n  position: absolute;\n  margin: 120px 0 0 142px;\n}",""])}});