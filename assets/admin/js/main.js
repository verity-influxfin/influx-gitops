!function(e){function t(t){for(var a,c,o=t[0],i=t[1],u=t[2],d=0,p=[];d<o.length;d++)c=o[d],Object.prototype.hasOwnProperty.call(r,c)&&r[c]&&p.push(r[c][0]),r[c]=0;for(a in i)Object.prototype.hasOwnProperty.call(i,a)&&(e[a]=i[a]);for(s&&s(t);p.length;)p.shift()();return l.push.apply(l,u||[]),n()}function n(){for(var e,t=0;t<l.length;t++){for(var n=l[t],a=!0,o=1;o<n.length;o++){var i=n[o];0!==r[i]&&(a=!1)}a&&(l.splice(t--,1),e=c(c.s=n[0]))}return e}var a={},r={main:0},l=[];function c(t){if(a[t])return a[t].exports;var n=a[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,c),n.l=!0,n.exports}c.m=e,c.c=a,c.d=function(e,t,n){c.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},c.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},c.t=function(e,t){if(1&t&&(e=c(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(c.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)c.d(n,a,function(t){return e[t]}.bind(null,a));return n},c.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return c.d(t,"a",t),t},c.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},c.p="";var o=window.webpackJsonp=window.webpackJsonp||[],i=o.push.bind(o);o.push=t,o=o.slice();for(var u=0;u<o.length;u++)t(o[u]);var s=i;l.push([60,"vendors.bundle"]),n()}({39:function(e,t,n){},60:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n.n(a),l=(t=n(7),a=n.n(t),t=n(11),n.n(t)),c=(t=n(8),n.n(t)),o=(t=n(12),n.n(t)),i=(t=n(27),n.n(t)),u=(t=n(28),n.n(t)),s=(t=n(10),n.n(t)),d=(t=n(29),n.n(t)),p=(t=n(30),n.n(t)),m=(t=n(17),n.n(t)),f=(t=(n(38),n(39),n(13)),n.n(t)),h=n(67),y=n(66),v=n(68),E=n(69);function b(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,a=m()(e);return a=t?(n=m()(this).constructor,Reflect.construct(a,arguments,n)):a.apply(this,arguments),p()(this,a)}}n=function(e){d()(p,e);var t,n,a=b(p);function p(e){return i()(this,p),(e=a.call(this,e)).state={rowData:[],mainProject:[],subProject:[],hitedReasult:{},selectedID:"",fakeData:{}},e.getAnitFraudData=e.getAnitFraudData.bind(s()(e)),e.setSelectedID=e.setSelectedID.bind(s()(e)),e.handleShow=e.handleShow.bind(s()(e)),e}return u()(p,[{key:"getAnitFraudData",value:(n=o()(c.a.mark((function e(){var t,n,a,r=this;return c.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,f.a.get("".concat(window.__env__.apiUrl,"admin/brookesia/get_all_rule_type"));case 3:t=(t=e.sent).data.response.results,n=[],a=[],t.forEach(function(){var e=o()(c.a.mark((function e(t){var l,o;return c.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n.push({typeId:t.typeId,description:t.description}),e.next=3,f.a.get("".concat(window.__env__.apiUrl,"admin/brookesia/get_rule_info_by_type?typeId=").concat(t.typeId));case 3:l=e.sent,o=l.data.response.results,t.rules.forEach((function(e,t){a.push({id:e.id,ruleType:e.ruleType,description:e.description,risk:e.risk,hitCounts:o[t].hitCount,efficiency:"".concat((100*o[t].efficiency).toFixed(2),"%"),overdueRate:"".concat((100*o[t].overdueRate).toFixed(2),"%"),overdueCount:o[t].overdueCount,overdueInfo:o[t].overdueInfo,show:!1})})),r.setState({subProject:a}),r.setState({mainProject:n});case 8:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()),this.setState({rowData:t}),this.setState({selectedID:t[0].typeId}),e.next=15;break;case 12:e.prev=12,e.t0=e.catch(0),alert("所有規則取得失敗，請洽工程師");case 15:case"end":return e.stop()}}),e,this,[[0,12]])}))),function(){return n.apply(this,arguments)})},{key:"componentDidMount",value:function(){this.getAnitFraudData()}},{key:"setSelectedID",value:function(e){e=e.target.value,this.setState({selectedID:e})}},{key:"handleShow",value:(t=o()(c.a.mark((function e(t,n){var a,r,l;return c.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return a=this.state.subProject,r=this.state.hitedReasult,e.next=4,f.a.get("".concat(window.__env__.apiUrl,"admin/brookesia/get_result_by_rule?typeId=").concat(a[t].ruleType,"&ruleId=").concat(a[t].id));case 4:l=e.sent,a[t].show=!a[t].show,r[t]=l.data.response,this.setState({hitedReasult:r}),this.setState({subProject:a});case 9:case"end":return e.stop()}}),e,this)}))),function(e,n){return t.apply(this,arguments)})},{key:"render",value:function(){function e(e){var n=t.state.hitedReasult[e.index],a=t.state.subProject[e.index].overdueInfo,c=Object.keys(n.columnMap).map((function(e,t){return r.a.createElement("th",{key:t.toString()},n.columnMap[e])})),o=n.results.map((function(e,t){var c;a.forEach((function(t){e.userId===t.userId&&(t.delayDays<=30?c="yellow":30<t.delayDays&&t.delayDays<=60?c="orange":60<t.delayDays&&(c="red"))}));var o=Object.keys(n.columnMap).map((function(t,n){return"object"!==l()(e[t])?"userId"===t?r.a.createElement("td",{key:n.toString()},r.a.createElement("a",{href:"".concat(location.origin,"/admin/user/edit?id=").concat(e[t]),target:"_blank"},e[t])):r.a.createElement("td",{key:n.toString()},e[t]):(t=e[t].map((function(e,t){var n="object"===l()(e)?Object.keys(e).map((function(t,n){return"relatedUserId"===t?r.a.createElement("p",{key:n.toString()},r.a.createElement("a",{href:"".concat(location.origin,"/admin/user/edit?id=").concat(e[t]),target:"_blank"},e[t])):r.a.createElement("p",{key:n.toString()},e[t])})):r.a.createElement("p",null,r.a.createElement("a",{href:"".concat(location.origin,"/admin/user/edit?id=").concat(e),target:"_blank"},e));return r.a.createElement("div",{className:"hited",key:t.toString()},n)})),t=r.a.createElement(h.a,null,r.a.createElement(h.a.Content,{className:"hited-cnt"},t)),r.a.createElement("td",{key:n.toString()},r.a.createElement(y.a,{trigger:"click",placement:"right",overlay:t},r.a.createElement(v.a,{variant:"secondary",size:"sm"},"顯示"))))}));return r.a.createElement("tr",{key:t.toString(),className:c},o)}));return r.a.createElement("table",{className:"table child"},r.a.createElement("thead",null,r.a.createElement("tr",null,r.a.createElement("th",{colSpan:"8",className:"title"},"反詐欺規則代碼-",e.id))),r.a.createElement("thead",null,r.a.createElement("tr",null,c)),r.a.createElement("tbody",null,o))}var t=this,n=this.state.mainProject.map((function(e,t){return r.a.createElement("option",{value:e.typeId,key:t.toString()},e.typeId," -",e.description)})),a=this.state.subProject.map((function(n,a){if(n.ruleType==t.state.selectedID)return r.a.createElement("tbody",{key:a.toString()},r.a.createElement("tr",null,r.a.createElement("td",null,n.risk),r.a.createElement("td",null,n.description),r.a.createElement("td",null,n.hitCounts),r.a.createElement("td",null,n.overdueRate,"(",n.overdueCount,"筆)"),r.a.createElement("td",null,n.efficiency),r.a.createElement("td",null,r.a.createElement(v.a,{variant:"primary",size:"sm",onClick:function(){return t.handleShow(a,n)}},"命中結果"))),r.a.createElement("tr",null,n.show?r.a.createElement("td",{colSpan:"8",className:"childtable-container"},r.a.createElement(e,{id:n.ruleType,index:a})):null))}));return r.a.createElement("div",null,r.a.createElement("div",{className:"filter"},r.a.createElement(E.a,null,r.a.createElement(E.a.Prepend,null,r.a.createElement(E.a.Text,null,"規則類別")),r.a.createElement("select",{className:"form-control",onChange:this.setSelectedID},n))),r.a.createElement("div",{className:"table-container"},r.a.createElement("table",{className:"table"},r.a.createElement("thead",null,r.a.createElement("tr",null,r.a.createElement("th",{colSpan:"8",className:"title"},"反詐欺子規則"))),r.a.createElement("thead",null,r.a.createElement("tr",null,r.a.createElement("th",null,"風險評級"),r.a.createElement("th",null,"邏輯"),r.a.createElement("th",null,"命中次數"),r.a.createElement("th",null,"逾期率"),r.a.createElement("th",null,"有效性"),r.a.createElement("th",null))),a)))}}]),p}(r.a.Component),a.a.render(r.a.createElement(n,null),document.getElementById("anti-fraud-app"))}});