!function(t){var e={};function r(n){if(e[n])return e[n].exports;var o=e[n]={i:n,l:!1,exports:{}};return t[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=t,r.c=e,r.d=function(t,e,n){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)r.d(n,o,function(e){return t[e]}.bind(null,o));return n},r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="/",r(r.s=261)}({1:function(t,e,r){t.exports=r(6)},10:function(t,e,r){"use strict";var n=[0,1,2,3,4,20,21,22,23,24,25],o=[5],a=[8,9,10];e.a={mutationExperiencesData:function(t,e){t.experiences=e},mutationKnowledgeData:function(t,e){$.each(e,(function(t,r){e[t].link="/articlepage?q=knowledge-".concat(r.ID)})),t.knowledge=e},mutationVideoData:function(t,e){$.each(e,(function(t,r){r.category?e[t].link="/vlog?q=".concat(r.category):e[t].link="/videopage?q=".concat(r.ID,"&category=share")})),t.video=e},mutationInterviewData:function(t,e){t.interview=e},mutationNewsData:function(t,e){$.each(e,(function(t,r){e[t].link="/articlepage?q=news-".concat(r.ID)})),t.news=e},mutationUserData:function(t,e){t.userData=e},mutationRepaymentData:function(t,e){var r=[],i=[],c=[];e.data.list.forEach((function(t,e){-1!==n.indexOf(t.status)&&8!==t.sub_status?r.push(t):-1!==o.indexOf(t.status)?(i.push(t),1===t.sub_status&&r.push(t)):-1!==a.indexOf(t.status)&&c.push(t)})),r=r.reverse(),i=i.reverse(),c=c.reverse(),t.applyList={applying:r,installment:i,done:c}},mutationInvestmentData:function(t,e){t.investAccountData=e.data}}},261:function(t,e,r){t.exports=r(262)},262:function(t,e,r){"use strict";r.r(e);var n=r(1),o=r.n(n),a=r(7),i=r(8),c=r(9),s=r(10);function u(t,e,r,n,o,a,i){try{var c=t[a](i),s=c.value}catch(t){return void r(t)}c.done?e(s):Promise.resolve(s).then(n,o)}function f(t){return function(){var e=this,r=arguments;return new Promise((function(n,o){var a=t.apply(e,r);function i(t){u(a,n,o,i,c,"next",t)}function c(t){u(a,n,o,i,c,"throw",t)}i(void 0)}))}}$((function(){var t=new Vuex.Store({state:a.a,getters:i.a,actions:c.a,mutations:s.a});new Vue({el:"#event_wrapper",store:t,data:{account:"",password:"",confirmPassword:"",code:"",message:"",registerMessage:"",alertText:"",termsTitle:"",termsContent:"",wheelDeg:0,prizeNumber:8,weights:0,investor:0,counter:180,dataCache:[],awards:{},userData:{},rolling:!1,disable:!0,isRegister:!1,isSended:!1,isAgree:!1,timer:null},created:function(){this.getData(),$("title").text("幸運轉盤 - inFlux普匯金融科技")},mounted:function(){$(".page-header").hide(),$(".page-footer").hide(),$(".back-top").hide()},computed:{prizeList:function(){return this.dataCache.slice(0,this.prizeNumber)}},watch:{phone:function(t){this.phone=t.replace(/[^\d]/g,"")},code:function(t){this.code=t.replace(/[^\d]/g,"")}},methods:{getData:function(){var t=this;return f(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,axios.get("getRotationData");case 3:e.sent.data.forEach((function(e,r){Vue.set(t.dataCache,r,e),t.weights+=e.weights})),gsap.to(t.$refs.wheel_content,.5,{delay:1,rotation:-45,opacity:1,translateX:0,translateY:0,ease:"back.out(1.7)"}),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(0),console.error("getRotationData 發生錯誤");case 11:t.$nextTick((function(){gsap.to(t.$refs.login,2,{delay:1.5,opacity:1,rotation:0}),gsap.to(t.$refs.lottery,2,{delay:1.5,opacity:1,rotation:0}),gsap.from(t.$refs.left_info,2,{delay:2,opacity:0}),gsap.from(t.$refs.right_info,2,{delay:2,opacity:0})}));case 12:case"end":return e.stop()}}),e,null,[[0,8]])})))()},rotate:function(){var t=this;return f(o.a.mark((function e(){var r,n;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!t.rolling){e.next=2;break}return e.abrupt("return");case 2:return r=t.userData,e.next=5,axios.post("ratate",r);case 5:n=e.sent,t.roll(n.data);case 7:case"end":return e.stop()}}),e)})))()},roll:function(t){var e=this;this.rolling=!0;var r=this.wheelDeg,n=this.prizeList;this.wheelDeg=r-r%360+2160+(360-360/n.length*t),setTimeout((function(){e.rolling=!1,e.showAwards(n[t])}),3e3)},transformHandler:function(t,e){var r=360/this.dataCache.length,n=r-90;return"prize"===e?"rotate(".concat(-r/2+t*r,"deg) skewY(").concat(n,"deg)"):"content"===e?"skewY(".concat(90-r,"deg) rotate(").concat(r/2,"deg)"):void 0},check:function(){var t=this;return f(o.a.mark((function e(){var r;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(0!==Object.keys(t.userData).length){e.next=4;break}t.alertText="請登入才能抽獎喔",e.next=9;break;case 4:return e.next=6,axios.post("checkStatus");case 6:r=e.sent,t.alertText=r.data.message,t.disable=r.data.status;case 9:t.alertText&&alert(t.alertText);case 10:case"end":return e.stop()}}),e)})))()},openLoginModal:function(){$(this.$refs.loginForm).modal("show")},doLogin:function(){var t=this,e={phone:this.account,password:this.password,investor:this.investor};axios.post("doLogin",e).then((function(e){t.userData=e.data,$(t.$refs.loginForm).modal("hide"),t.check()})).catch((function(e){var r=e.response.data;if(r.message){var n=[];$.each(r.errors,(function(t,e){e.forEach((function(t,e){n.push(t)}))})),t.message=n.join("、")}else t.message="".concat(t.$store.state.loginErrorCode[r.error],"\n                                                     ").concat(r.data?"剩餘錯誤次數(".concat(r.data.remind_count,")"):"")}))},getTerms:function(t){var e=this;axios.post("getTerms",{termsType:t}).then((function(t){var r=t.data.data,n=r.content,o=r.name;e.termsContent=n,e.termsTitle=o,$(e.$refs.termsForm).modal("show")})).catch((function(t){console.log("getTerms 發生錯誤，請稍後再試")}))},getCaptcha:function(t){var e=this,r=this.account;r?(this.counter=180,axios.post("getCaptcha",{phone:r,type:t}).then((function(t){e.isSended=!0,e.registerMessage="",e.timer=setInterval((function(){e.reciprocal()}),1e3)})).catch((function(t){var r=t.response.data;e.registerMessage="".concat(e.$store.state.smsErrorCode[r.error])}))):this.registerMessage="請輸入手機"},doRegister:function(){var t=this,e=this.account,r=this.password,n=this.confirmPassword,o=this.code;axios.post("doRegister",{phone:e,password:r,password_confirmation:n,code:o}).then((function(n){axios.post("doLogin",{phone:e,password:r,investor:t.investor}).then((function(e){t.userData=e.data,$(t.$refs.loginForm).modal("hide"),t.check()}))})).catch((function(e){var r=e.response.data;if(r.message){var n=[];$.each(r.errors,(function(t,e){e.forEach((function(t,e){n.push(t)}))})),t.message=n.join("、")}else t.message="".concat(t.$store.state.registerErrorCode[r.error])}))},reciprocal:function(){this.counter--,0===this.counter&&(clearInterval(this.timer),this.timer=null,alert("驗證碼失效，請重新申請"),location.reload())},showAwards:function(t){this.awards=t,this.disable=!0,gsap.to(this.$refs.awards_block,.5,{display:"block"})},hide:function(){$(this.$refs.awards_block).hide()}}})}))},6:function(t,e,r){var n=function(t){"use strict";var e=Object.prototype,r=e.hasOwnProperty,n="function"==typeof Symbol?Symbol:{},o=n.iterator||"@@iterator",a=n.asyncIterator||"@@asyncIterator",i=n.toStringTag||"@@toStringTag";function c(t,e,r,n){var o=e&&e.prototype instanceof f?e:f,a=Object.create(o.prototype),i=new D(n||[]);return a._invoke=function(t,e,r){var n="suspendedStart";return function(o,a){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw a;return L()}for(r.method=o,r.arg=a;;){var i=r.delegate;if(i){var c=w(i,r);if(c){if(c===u)continue;return c}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var f=s(t,e,r);if("normal"===f.type){if(n=r.done?"completed":"suspendedYield",f.arg===u)continue;return{value:f.arg,done:r.done}}"throw"===f.type&&(n="completed",r.method="throw",r.arg=f.arg)}}}(t,r,i),a}function s(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}t.wrap=c;var u={};function f(){}function l(){}function h(){}var p={};p[o]=function(){return this};var d=Object.getPrototypeOf,v=d&&d(d(k([])));v&&v!==e&&r.call(v,o)&&(p=v);var g=h.prototype=f.prototype=Object.create(p);function m(t){["next","throw","return"].forEach((function(e){t[e]=function(t){return this._invoke(e,t)}}))}function y(t,e){var n;this._invoke=function(o,a){function i(){return new e((function(n,i){!function n(o,a,i,c){var u=s(t[o],t,a);if("throw"!==u.type){var f=u.arg,l=f.value;return l&&"object"==typeof l&&r.call(l,"__await")?e.resolve(l.__await).then((function(t){n("next",t,i,c)}),(function(t){n("throw",t,i,c)})):e.resolve(l).then((function(t){f.value=t,i(f)}),(function(t){return n("throw",t,i,c)}))}c(u.arg)}(o,a,n,i)}))}return n=n?n.then(i,i):i()}}function w(t,e){var r=t.iterator[e.method];if(void 0===r){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=void 0,w(t,e),"throw"===e.method))return u;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return u}var n=s(r,t.iterator,e.arg);if("throw"===n.type)return e.method="throw",e.arg=n.arg,e.delegate=null,u;var o=n.arg;return o?o.done?(e[t.resultName]=o.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=void 0),e.delegate=null,u):o:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,u)}function x(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function b(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function D(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(x,this),this.reset(!0)}function k(t){if(t){var e=t[o];if(e)return e.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var n=-1,a=function e(){for(;++n<t.length;)if(r.call(t,n))return e.value=t[n],e.done=!1,e;return e.value=void 0,e.done=!0,e};return a.next=a}}return{next:L}}function L(){return{value:void 0,done:!0}}return l.prototype=g.constructor=h,h.constructor=l,h[i]=l.displayName="GeneratorFunction",t.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===l||"GeneratorFunction"===(e.displayName||e.name))},t.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,h):(t.__proto__=h,i in t||(t[i]="GeneratorFunction")),t.prototype=Object.create(g),t},t.awrap=function(t){return{__await:t}},m(y.prototype),y.prototype[a]=function(){return this},t.AsyncIterator=y,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var i=new y(c(e,r,n,o),a);return t.isGeneratorFunction(r)?i:i.next().then((function(t){return t.done?t.value:i.next()}))},m(g),g[i]="Generator",g[o]=function(){return this},g.toString=function(){return"[object Generator]"},t.keys=function(t){var e=[];for(var r in t)e.push(r);return e.reverse(),function r(){for(;e.length;){var n=e.pop();if(n in t)return r.value=n,r.done=!1,r}return r.done=!0,r}},t.values=k,D.prototype={constructor:D,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(b),!t)for(var e in this)"t"===e.charAt(0)&&r.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function n(r,n){return i.type="throw",i.arg=t,e.next=r,n&&(e.method="next",e.arg=void 0),!!n}for(var o=this.tryEntries.length-1;o>=0;--o){var a=this.tryEntries[o],i=a.completion;if("root"===a.tryLoc)return n("end");if(a.tryLoc<=this.prev){var c=r.call(a,"catchLoc"),s=r.call(a,"finallyLoc");if(c&&s){if(this.prev<a.catchLoc)return n(a.catchLoc,!0);if(this.prev<a.finallyLoc)return n(a.finallyLoc)}else if(c){if(this.prev<a.catchLoc)return n(a.catchLoc,!0)}else{if(!s)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return n(a.finallyLoc)}}}},abrupt:function(t,e){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===t||"continue"===t)&&a.tryLoc<=e&&e<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=t,i.arg=e,a?(this.method="next",this.next=a.finallyLoc,u):this.complete(i)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),u},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),b(r),u}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;b(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,r){return this.delegate={iterator:k(t),resultName:e,nextLoc:r},"next"===this.method&&(this.arg=void 0),u}},t}(t.exports);try{regeneratorRuntime=n}catch(t){Function("r","regeneratorRuntime = r")(n)}},7:function(t,e,r){"use strict";e.a={experiences:[],knowledge:[],video:[],interview:[],news:[],userData:{},applyList:{},investAccountData:{},loginErrorCode:{302:"會員不存在",304:"密碼錯誤",312:"密碼長度錯誤",200:"參數錯誤",121:"登入失敗3次，自動鎖定30分鐘，可風控提早解除",120:"登入失敗10次，自動永久鎖定，需風控解除",101:"帳戶已黑名單"},smsErrorCode:{301:"會員已存在",302:"會員不存在",307:"發送簡訊間隔過短",200:"參數錯誤"},pwdErrorCode:{302:"會員不存在",303:"驗證碼錯誤",312:"密碼長度錯誤",200:"參數錯誤",201:"新增時發生錯誤"},registerErrorCode:{301:"會員已存在",303:"驗證碼錯誤",312:"密碼長度錯誤",305:"AccessToken無效",308:"此FB帳號已綁定過",200:"參數錯誤",201:"新增時發生錯誤"}}},8:function(t,e,r){"use strict";e.a={ExperiencesData:function(t){return t.experiences},KnowledgeData:function(t,e){return t.knowledge},VideoData:function(t,e){return t.video},InterviewData:function(t,e){return t.interview},NewsData:function(t,e){return t.news},UserData:function(t,e){return t.userData},ApplyList:function(t,e){return t.applyList},InvestAccountData:function(t,e){return t.investAccountData}}},9:function(t,e,r){"use strict";var n=r(1),o=r.n(n);function a(t,e,r,n,o,a,i){try{var c=t[a](i),s=c.value}catch(t){return void r(t)}c.done?e(s):Promise.resolve(s).then(n,o)}function i(t){return function(){var e=this,r=arguments;return new Promise((function(n,o){var i=t.apply(e,r);function c(t){a(i,n,o,c,s,"next",t)}function s(t){a(i,n,o,c,s,"throw",t)}c(void 0)}))}}e.a={getExperiencesData:function(t){var e=arguments;return i(o.a.mark((function r(){var n,a,i;return o.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=t.commit,a=e.length>1&&void 0!==e[1]?e[1]:"",r.prev=2,r.next=5,axios.post("".concat(location.origin,"/getExperiencesData"),{type:a});case 5:i=r.sent,n("mutationExperiencesData",i.data),r.next=12;break;case 9:r.prev=9,r.t0=r.catch(2),console.error("getExperiencesData 發生錯誤");case 12:case"end":return r.stop()}}),r,null,[[2,9]])})))()},getKnowledgeData:function(t){return i(o.a.mark((function e(){var r,n;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=t.commit,e.prev=1,e.next=4,axios.post("".concat(location.origin,"/getKnowledgeData"));case 4:n=e.sent,r("mutationKnowledgeData",n.data),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),console.error("getKnowledgeData 發生錯誤");case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},getVideoData:function(t,e){return i(o.a.mark((function r(){var n,a;return o.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=t.commit,r.prev=1,r.next=4,axios.post("".concat(location.origin,"/getVideoData"),{filter:e.category});case 4:a=r.sent,n("mutationVideoData",a.data),r.next=11;break;case 8:r.prev=8,r.t0=r.catch(1),console.error("getVideoData 發生錯誤");case 11:case"end":return r.stop()}}),r,null,[[1,8]])})))()},getNewsData:function(t){return i(o.a.mark((function e(){var r,n;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=t.commit,e.prev=1,e.next=4,axios.post("".concat(location.origin,"/getNewsData"));case 4:n=e.sent,r("mutationNewsData",n.data),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),console.error("getNewsData 發生錯誤");case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},getRepaymentList:function(t){return i(o.a.mark((function e(){var r,n;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=t.commit,e.prev=1,e.next=4,axios.post("".concat(location.origin,"/getRepaymentList"));case 4:n=e.sent,r("mutationRepaymentData",n.data),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),console.error("getRepaymentList 發生錯誤");case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},getMyInvestment:function(t){return i(o.a.mark((function e(){var r,n;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=t.commit,e.prev=1,e.next=4,axios.get("".concat(location.origin,"/getMyInvestment"));case 4:n=e.sent,r("mutationInvestmentData",n.data),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),console.error("getMyInvestment 發生錯誤");case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()}}}});