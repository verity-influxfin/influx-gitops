!function(e){var t={};function r(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)r.d(n,a,function(t){return e[t]}.bind(null,a));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/",r(r.s=3)}({3:function(e,t,r){e.exports=r("YHQZ")},YHQZ:function(e,t){$((function(){if((new Date).getDate()>=new Date("2021/01/31 00:00:00").getDate())return alert("報名已截止，詳情請見官網"),void location.replace("/campuspartner");new Vue({el:"#campusJoin_wrapper",data:function(){return{isSingup:!1,teamName:"",memberList:[{name:"",school:"",department:"",grade:"",mobile:"",email:"",selfIntro:"",resume:"",proposal:"",portfolio:""}]}},created:function(){},methods:{checked:function(e){$(e.target).val()?$(e.target).removeClass("empty").attr("placeholder",""):$(e.target).addClass("empty").attr("placeholder","請填寫這個欄位")},uploadFile:function(e,t){var r=this,n=new FormData;n.append("file",e.target.files[0]),n.append("fileType",e.target.name),axios.post("/campusUploadFile",n).then((function(n){r.memberList[t][e.target.name]=n.data,alert("上傳成功！")})).catch((function(t){var r=t.response.data;$(e.target).val(""),alert(r)}))},createMember:function(){this.memberList.length<3&&this.memberList.push({name:"",school:"",department:"",grade:"",mobile:"",email:"",selfIntro:"",resume:"",proposal:"",portfolio:""})},deleteRow:function(e){confirm("確定移除此隊員?")&&this.memberList.splice(e,1)},submit:function(){var e=this,t=this.memberList,r=this.teamName;0===$(".empty").length?axios.post("/campusSignup",{memberList:t,teamName:r}).then((function(t){e.isSingup=!0})).catch((function(e){var t=e.response.data;Object.keys(t.errors).forEach((function(e){"index"in t?$("#".concat(e,"_").concat(t.index)).attr("placeholder",t.errors[e]).addClass("empty"):$("#".concat(e)).attr("placeholder",t.errors[e]).addClass("empty")}))})):alert("有欄位未輸入")}}})}))}});