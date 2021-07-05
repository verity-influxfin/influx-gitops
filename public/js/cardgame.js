/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@splidejs/splide/dist/css/themes/splide-default.min.css":
/*!******************************************************************************!*\
  !*** ./node_modules/@splidejs/splide/dist/css/themes/splide-default.min.css ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../css-loader??ref--9-1!../../../../../postcss-loader/src??ref--9-2!./splide-default.min.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/@splidejs/splide/dist/css/themes/splide-default.min.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/question.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      process: false,
      randkeys: [],
      faceNum: ['H', 'A', 'P', 'P', 'Y', 'N', 'E', 'W', 'Y', 'E', 'A', 'R'],
      imgs: {
        1: {
          question: '普匯目前主要<br />業務項目為何?',
          selection: ['借貸/投資', '保險']
        },
        2: {
          question: 'p2p借貸的<br />英文縮寫為何?',
          selection: ['peer to peer', 'pika to pika']
        },
        3: {
          question: '普匯今年<br />舉辦什麼活動?',
          selection: ['AI金融科技創新創意競賽', '國外旅遊']
        },
        4: {
          question: '普匯為下列<br />何種類型公司?',
          selection: ['金融科技', '科技金融']
        },
        5: {
          question: '普匯是媒合<br />誰與誰的平台?',
          selection: ['借款人與投資人', '你和我']
        },
        6: {
          question: '普匯的吉祥物叫?',
          selection: ['小普', '來福']
        },
        7: {
          question: '普匯的代表色為何?',
          selection: ['黑色', '藍色']
        },
        8: {
          question: '普匯主打的服務是?',
          selection: ['AI線上無人化', '見面對保狂call你']
        },
        9: {
          question: '普匯第一個<br />上線的產品是?',
          selection: ['學生貸', '房貸']
        },
        10: {
          question: '普匯投資是<br />多少元起投?',
          selection: ['1000元', '1000萬']
        },
        11: {
          question: '普匯創立於<br />哪一年份?',
          selection: ['2017年', '2025年']
        },
        12: {
          question: '普匯APP於<br />哪一年上線?',
          selection: ['2019年', '尚未上線']
        }
      }
    };
  },
  mounted: function mounted() {
    this.randkeys = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

    for (var i = this.randkeys.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var _ref = [this.randkeys[j], this.randkeys[i]];
      this.randkeys[i] = _ref[0];
      this.randkeys[j] = _ref[1];
    }

    $('.countdown').hide();
    $(document).off("click", ".cardA:not(.done):not(.active),.cardB:not(.done):not(.active)").on("click", ".cardA:not(.done):not(.active),.cardB:not(.done):not(.active)", function (e, t) {
      $('.cardA,.cardB').hide();
      $(this).addClass('active').show();
      $('.countdown').show();
      $('.countdown span').text('10');
    });
    var data = {
      user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"] : {}
    };
    axios.post("/getData", data).then(function (res) {
      if (res.data) {
        alert('您已參加過遊戲囉!!');
        location.replace('/');
      } else {
        alert('遊戲流程：\n' + '◆從12張卡牌中，挑選喜愛的3個生肖回答普匯相關題目\n' + '◆每題限時10秒，30秒即可完成遊戲\n' + '◆答錯可重新遊戲直到全對');
      }
    })["catch"](function (err) {
      console.error(err);
    });
  },
  methods: {
    timer: function timer() {
      var my = this;
      this.time = setInterval(function () {
        var time = $('.countdown span');

        if (time.text() > 0) {
          time.text('0' + (time.text() - 1));
        } else if (time.text() == 0) {
          $('.countdown').hide();
          time.text('*');
          alert('時間到囉~請重新挑戰');
          location.replace('/cardgame');
        }
      }, 1000);
    },
    setTime: function setTime() {
      this.timer();
    },
    stopTime: function stopTime() {
      if (this.time) {
        clearInterval(this.time);
      }
    },
    ans: function ans(event) {
      var _this = this;

      $('.countdown').hide();

      if (!this.process) {
        this.process = true;
        var data = {
          qnum: event.target.parentElement.dataset.id,
          qans: event.target.dataset.ans
        };
        axios.post("/getAns", data).then(function (res) {
          if (res.data.ans == 1) {
            $('.active:not(.done)').addClass('done');
            $('.cardA,.cardB').show();
            $('.active').removeClass('active');
            $('[data-id=' + data.qnum + '] .cardAns:not([data-ans=' + data.qans + '])').remove();
            _this.countDown = false;

            if ($('.done').length > 2) {
              alert('恭喜全部答對，請前往抽獎');
              window.location.href = "/cardgame/turntable";
            } else {
              alert('恭喜您答對了!! 下一題~');
            }
          } else {
            alert('答錯囉~再讓我們玩一次吧！');
            window.location.href = "/cardgame";
          }

          _this.process = false;
        })["catch"](function (err) {
          console.error(err);
        });
      } else {
        console.log('duplicate!!');
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/turntable.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      process: false,
      randkeys: [],
      faceNum: ['H', 'A', 'P', 'P', 'Y', 'N', 'E', 'W', 'Y', 'E', 'A', 'R'],
      picTop: ['40', '50', '50', '41', '49', '41', '43', '50', '56', '43', '49', '49'],
      picLeft: ['4', '8', '4', '5', '5', '3', '1', '7', '6', '2', '6', '2', '6']
    };
  },
  mounted: function mounted() {
    //todo random
    this.randkeys = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    $(document).off("click", ".cardA,.cardB").on("click", ".cardA,.cardB", function (e, t) {
      $(this).addClass('active');
    });
    var data = {
      user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"] : {}
    };
    axios.post("/getData", data).then(function (res) {
      if (res.data) {
        alert('您已參加過遊戲囉!!');
        location.replace('/');
      } else {
        var email = prompt('請輸入您的email以利中獎通知：');

        if (email == null || email == '') {
          var semail = prompt('請輸入您的email以利中獎通知，若未輸入將放棄轉盤遊戲：');

          if (semail == null || semail == '') {
            alert('將跳回首頁');
            location.replace('/');
          }
        } else {
          localStorage.setItem('email', email);
        }
      }
    })["catch"](function (err) {
      console.error(err);
    });
  },
  methods: {
    turn: function turn(event) {
      var _this = this;

      if (!this.process) {
        this.process = true;
        var data = {
          user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"] : {},
          email: localStorage.getItem("email")
        };
        axios.post("/setGamePrize", data).then(function (res) {
          if (res.data.prize != undefined) {
            var prize = res.data.prize;
            var rotate = res.data.rotate * 1 + 3000 * 1;
            _this.process = false;
            $('.turntable .disk').css('transform', 'rotate(' + rotate + 'deg)');
            setTimeout(function () {
              alert("恭喜抽中" + prize + "！\n\n※中獎後會由公司寄發中獎信件通知");
              location.replace('/');
            }, 11000);
          } else {
            alert("已經玩過遊戲囉!" + (res.data != '' ? '抽中的是->' + res.data : ''));
            location.replace('/');
          }
        })["catch"](function (err) {
          console.error(err);
        });
      } else {
        console.log('duplicate!!');
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--10-2!./node_modules/sass-loader/dist/cjs.js??ref--10-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".cardgame-wrapper .page-header {\n  z-index: 11;\n  background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);\n}\n.cardgame-wrapper .page-header .web-logo {\n  width: 140px;\n  margin: 0px;\n}\n.cardgame-wrapper .cards {\n  background-color: #ffd186;\n  text-align: center;\n  height: 1040px;\n}\n.cardgame-wrapper .cards .countdown {\n  font-size: 28px;\n  font-weight: bold;\n  letter-spacing: 2px;\n  color: red;\n}\n.cardgame-wrapper .cards .cardA, .cardgame-wrapper .cards .cardB {\n  width: 110px;\n  height: 150px;\n  display: inline-table;\n  border-radius: 22px;\n  border-width: 6px;\n  border-style: solid;\n  padding: 8px 5px;\n  font-weight: bold;\n  color: white;\n  margin: 15px 5px 0;\n  position: relative;\n  transition: transform 1s;\n  transform-style: preserve-3d;\n  background-repeat: no-repeat;\n}\n.cardgame-wrapper .cards .cardA.active, .cardgame-wrapper .cards .cardB.active {\n  transform: rotateY(180deg);\n  -moz-transform: rotateY(180deg);\n  -webkit-transform: rotateY(180deg);\n  -o-transform: rotateY(180deg);\n  -ms-transform: rotateY(180deg);\n  position: absolute;\n  border-radius: 22px;\n  width: 100%;\n  height: 100%;\n  margin: 0 0 0 0;\n  border: 0;\n  z-index: 999;\n  left: 0;\n  top: 86px;\n  background-color: transparent;\n}\n.cardgame-wrapper .cards .cardA.active .card-front, .cardgame-wrapper .cards .cardB.active .card-front {\n  display: none;\n}\n.cardgame-wrapper .cards .cardA.active .cardQuestion, .cardgame-wrapper .cards .cardB.active .cardQuestion {\n  font-size: 34px;\n}\n.cardgame-wrapper .cards .cardA.active .cardFlip, .cardgame-wrapper .cards .cardB.active .cardFlip {\n  -webkit-backface-visibility: visible;\n  backface-visibility: visible;\n}\n.cardgame-wrapper .cards .cardA.done, .cardgame-wrapper .cards .cardB.done {\n  transform: rotateY(180deg);\n  -moz-transform: rotateY(180deg);\n  -webkit-transform: rotateY(180deg);\n  -o-transform: rotateY(180deg);\n  -ms-transform: rotateY(180deg);\n}\n.cardgame-wrapper .cards .cardA.done .card-front, .cardgame-wrapper .cards .cardB.done .card-front {\n  display: none;\n}\n.cardgame-wrapper .cards .cardA.done .card-back, .cardgame-wrapper .cards .cardB.done .card-back {\n  padding: 0px !important;\n}\n.cardgame-wrapper .cards .cardA.done .cardQuestion, .cardgame-wrapper .cards .cardB.done .cardQuestion {\n  font-size: 12px;\n}\n.cardgame-wrapper .cards .cardA.done .cardAns, .cardgame-wrapper .cards .cardB.done .cardAns {\n  font-size: 12px;\n}\n.cardgame-wrapper .cards .cardA.done .cardFlip, .cardgame-wrapper .cards .cardB.done .cardFlip {\n  -webkit-backface-visibility: visible;\n  backface-visibility: visible;\n  position: absolute;\n}\n.cardgame-wrapper .cards .cardA .cardFace, .cardgame-wrapper .cards .cardB .cardFace {\n  margin: 36px 0px 0 11px;\n  width: 75px;\n  position: relative;\n}\n.cardgame-wrapper .cards .cardA .cardNum, .cardgame-wrapper .cards .cardB .cardNum {\n  font-size: 24px;\n  margin: -8px 0 0 4px;\n  position: absolute;\n}\n.cardgame-wrapper .cards .cardA .cardQuestion, .cardgame-wrapper .cards .cardB .cardQuestion {\n  font-size: 12px;\n  padding: 0 5px;\n  display: block;\n}\n.cardgame-wrapper .cards .cardA .cardAns, .cardgame-wrapper .cards .cardB .cardAns {\n  display: block;\n  font-size: 26px;\n  width: 100%;\n  text-align: center;\n}\n.cardgame-wrapper .cards .cardA .cardFlip, .cardgame-wrapper .cards .cardB .cardFlip {\n  position: absolute;\n  -webkit-backface-visibility: hidden;\n          backface-visibility: hidden;\n  left: 0px;\n  padding: 0 5px;\n}\n.cardgame-wrapper .cards .cardA .cardFlip.card-back, .cardgame-wrapper .cards .cardB .cardFlip.card-back {\n  transform: rotateY(180deg);\n  -moz-transform: rotateY(180deg);\n  -webkit-transform: rotateY(180deg);\n  -o-transform: rotateY(180deg);\n  -ms-transform: rotateY(180deg);\n  width: 100%;\n  height: 100%;\n  background-repeat: no-repeat;\n  padding: 76px 32px;\n}\n.cardgame-wrapper .cards .cardA {\n  background-color: #012060;\n  border-color: #b40b12;\n}\n.cardgame-wrapper .cards .cardA.active .card-back {\n  background-image: url(/images/cardGameRedLine.svg);\n}\n.cardgame-wrapper .cards .cardB {\n  background-color: #b40b12;\n  border-color: #012060;\n}\n.cardgame-wrapper .cards .cardB.active .card-back {\n  background-image: url(/images/cardGameBlueLine.svg);\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--10-2!./node_modules/sass-loader/dist/cjs.js??ref--10-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".turntable-wrapper .page-header {\n  z-index: 11;\n  background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);\n}\n.turntable-wrapper .page-header .web-logo {\n  width: 140px;\n  margin: 0px;\n}\n.turntable-wrapper .block {\n  background-color: #ffd186;\n  width: 100%;\n  height: 100%;\n  padding: 10px 0 300px;\n  text-align: center;\n}\n.turntable-wrapper .block ul {\n  font-weight: bold;\n  font-size: 18px;\n  margin: 10px 0 15px;\n  text-align: left;\n}\n.turntable-wrapper .block li {\n  font-weight: 400;\n  font-size: 14px;\n}\n.turntable-wrapper .block .turntable {\n  background-color: #ffd186;\n  display: inline-block;\n}\n.turntable-wrapper .block .turntable .disk {\n  background-image: url(/images/turntable.png);\n  background-repeat: no-repeat;\n  background-size: 100%;\n  background-position: 0 0px;\n  background-color: transparent;\n  width: 350px;\n  height: 350px;\n  transition: 10s cubic-bezier(0.1, 0.46, 0, 0.94);\n  transform-style: preserve-3d;\n  transform: rotate(0deg);\n  display: inline-block;\n}\n.turntable-wrapper .block .turntable .arrow {\n  background-image: url(/images/turntableArrow.png);\n  background-repeat: no-repeat;\n  background-size: 100%;\n  z-index: 99;\n  width: 70px;\n  height: 105px;\n  display: inline-block;\n  position: absolute;\n  margin: 120px 0 0 142px;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/@splidejs/splide/dist/css/themes/splide-default.min.css":
/*!**************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--9-1!./node_modules/postcss-loader/src??ref--9-2!./node_modules/@splidejs/splide/dist/css/themes/splide-default.min.css ***!
  \**************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "@-webkit-keyframes splide-loading{0%{transform:rotate(0)}to{transform:rotate(1turn)}}@keyframes splide-loading{0%{transform:rotate(0)}to{transform:rotate(1turn)}}.splide__container{position:relative;box-sizing:border-box}.splide__list{margin:0!important;padding:0!important;width:-webkit-max-content;width:-moz-max-content;width:max-content;will-change:transform}.splide.is-active .splide__list{display:flex}.splide__pagination{display:inline-flex;align-items:center;width:95%;flex-wrap:wrap;justify-content:center;margin:0}.splide__pagination li{list-style-type:none;display:inline-block;line-height:1;margin:0}.splide{visibility:hidden}.splide,.splide__slide{position:relative;outline:none}.splide__slide{box-sizing:border-box;list-style-type:none!important;margin:0;flex-shrink:0}.splide__slide img{vertical-align:bottom}.splide__slider{position:relative}.splide__spinner{position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;display:inline-block;width:20px;height:20px;border-radius:50%;border:2px solid #999;border-left-color:transparent;-webkit-animation:splide-loading 1s linear infinite;animation:splide-loading 1s linear infinite}.splide__track{position:relative;z-index:0;overflow:hidden}.splide--draggable>.splide__track>.splide__list>.splide__slide{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.splide--fade>.splide__track>.splide__list{display:block}.splide--fade>.splide__track>.splide__list>.splide__slide{position:absolute;top:0;left:0;z-index:0;opacity:0}.splide--fade>.splide__track>.splide__list>.splide__slide.is-active{position:relative;z-index:1;opacity:1}.splide--rtl{direction:rtl}.splide--ttb>.splide__track>.splide__list{display:block}.splide--ttb>.splide__pagination{width:auto}.splide__arrow{position:absolute;z-index:1;top:50%;transform:translateY(-50%);width:2em;height:2em;border-radius:50%;display:flex;align-items:center;justify-content:center;border:none;padding:0;opacity:.7;background:#ccc}.splide__arrow svg{width:1.2em;height:1.2em}.splide__arrow:hover{cursor:pointer;opacity:.9}.splide__arrow:focus{outline:none}.splide__arrow--prev{left:1em}.splide__arrow--prev svg{transform:scaleX(-1)}.splide__arrow--next{right:1em}.splide__pagination{position:absolute;z-index:1;bottom:.5em;left:50%;transform:translateX(-50%);padding:0}.splide__pagination__page{display:inline-block;width:8px;height:8px;background:#ccc;border-radius:50%;margin:3px;padding:0;transition:transform .2s linear;border:none;opacity:.7}.splide__pagination__page.is-active{transform:scale(1.4);background:#fff}.splide__pagination__page:hover{cursor:pointer;opacity:.9}.splide__pagination__page:focus{outline:none}.splide__progress__bar{width:0;height:3px;background:#ccc}.splide--nav>.splide__track>.splide__list>.splide__slide{border:3px solid transparent}.splide--nav>.splide__track>.splide__list>.splide__slide.is-active{border-color:#000}.splide--nav>.splide__track>.splide__list>.splide__slide:focus{outline:none}.splide--rtl>.splide__arrows .splide__arrow--prev,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--prev{right:1em;left:auto}.splide--rtl>.splide__arrows .splide__arrow--prev svg,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--prev svg{transform:scaleX(1)}.splide--rtl>.splide__arrows .splide__arrow--next,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--next{left:1em;right:auto}.splide--rtl>.splide__arrows .splide__arrow--next svg,.splide--rtl>.splide__track>.splide__arrows .splide__arrow--next svg{transform:scaleX(-1)}.splide--ttb>.splide__arrows .splide__arrow,.splide--ttb>.splide__track>.splide__arrows .splide__arrow{left:50%;transform:translate(-50%)}.splide--ttb>.splide__arrows .splide__arrow--prev,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--prev{top:1em}.splide--ttb>.splide__arrows .splide__arrow--prev svg,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--prev svg{transform:rotate(-90deg)}.splide--ttb>.splide__arrows .splide__arrow--next,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--next{top:auto;bottom:1em}.splide--ttb>.splide__arrows .splide__arrow--next svg,.splide--ttb>.splide__track>.splide__arrows .splide__arrow--next svg{transform:rotate(90deg)}.splide--ttb>.splide__pagination{display:flex;flex-direction:column;bottom:50%;left:auto;right:.5em;transform:translateY(50%)}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/lib/css-base.js":
/*!*************************************************!*\
  !*** ./node_modules/css-loader/lib/css-base.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--10-2!./node_modules/sass-loader/dist/cjs.js??ref--10-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--10-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--10-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./question.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--10-2!./node_modules/sass-loader/dist/cjs.js??ref--10-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--10-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--10-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./turntable.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/lib/addStyles.js":
/*!****************************************************!*\
  !*** ./node_modules/style-loader/lib/addStyles.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/

var stylesInDom = {};

var	memoize = function (fn) {
	var memo;

	return function () {
		if (typeof memo === "undefined") memo = fn.apply(this, arguments);
		return memo;
	};
};

var isOldIE = memoize(function () {
	// Test for IE <= 9 as proposed by Browserhacks
	// @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
	// Tests for existence of standard globals is to allow style-loader
	// to operate correctly into non-standard environments
	// @see https://github.com/webpack-contrib/style-loader/issues/177
	return window && document && document.all && !window.atob;
});

var getTarget = function (target, parent) {
  if (parent){
    return parent.querySelector(target);
  }
  return document.querySelector(target);
};

var getElement = (function (fn) {
	var memo = {};

	return function(target, parent) {
                // If passing function in options, then use it for resolve "head" element.
                // Useful for Shadow Root style i.e
                // {
                //   insertInto: function () { return document.querySelector("#foo").shadowRoot }
                // }
                if (typeof target === 'function') {
                        return target();
                }
                if (typeof memo[target] === "undefined") {
			var styleTarget = getTarget.call(this, target, parent);
			// Special case to return head of iframe instead of iframe itself
			if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
				try {
					// This will throw an exception if access to iframe is blocked
					// due to cross-origin restrictions
					styleTarget = styleTarget.contentDocument.head;
				} catch(e) {
					styleTarget = null;
				}
			}
			memo[target] = styleTarget;
		}
		return memo[target]
	};
})();

var singleton = null;
var	singletonCounter = 0;
var	stylesInsertedAtTop = [];

var	fixUrls = __webpack_require__(/*! ./urls */ "./node_modules/style-loader/lib/urls.js");

module.exports = function(list, options) {
	if (typeof DEBUG !== "undefined" && DEBUG) {
		if (typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
	}

	options = options || {};

	options.attrs = typeof options.attrs === "object" ? options.attrs : {};

	// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
	// tags it will allow on a page
	if (!options.singleton && typeof options.singleton !== "boolean") options.singleton = isOldIE();

	// By default, add <style> tags to the <head> element
        if (!options.insertInto) options.insertInto = "head";

	// By default, add <style> tags to the bottom of the target
	if (!options.insertAt) options.insertAt = "bottom";

	var styles = listToStyles(list, options);

	addStylesToDom(styles, options);

	return function update (newList) {
		var mayRemove = [];

		for (var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];

			domStyle.refs--;
			mayRemove.push(domStyle);
		}

		if(newList) {
			var newStyles = listToStyles(newList, options);
			addStylesToDom(newStyles, options);
		}

		for (var i = 0; i < mayRemove.length; i++) {
			var domStyle = mayRemove[i];

			if(domStyle.refs === 0) {
				for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j]();

				delete stylesInDom[domStyle.id];
			}
		}
	};
};

function addStylesToDom (styles, options) {
	for (var i = 0; i < styles.length; i++) {
		var item = styles[i];
		var domStyle = stylesInDom[item.id];

		if(domStyle) {
			domStyle.refs++;

			for(var j = 0; j < domStyle.parts.length; j++) {
				domStyle.parts[j](item.parts[j]);
			}

			for(; j < item.parts.length; j++) {
				domStyle.parts.push(addStyle(item.parts[j], options));
			}
		} else {
			var parts = [];

			for(var j = 0; j < item.parts.length; j++) {
				parts.push(addStyle(item.parts[j], options));
			}

			stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
		}
	}
}

function listToStyles (list, options) {
	var styles = [];
	var newStyles = {};

	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var id = options.base ? item[0] + options.base : item[0];
		var css = item[1];
		var media = item[2];
		var sourceMap = item[3];
		var part = {css: css, media: media, sourceMap: sourceMap};

		if(!newStyles[id]) styles.push(newStyles[id] = {id: id, parts: [part]});
		else newStyles[id].parts.push(part);
	}

	return styles;
}

function insertStyleElement (options, style) {
	var target = getElement(options.insertInto)

	if (!target) {
		throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
	}

	var lastStyleElementInsertedAtTop = stylesInsertedAtTop[stylesInsertedAtTop.length - 1];

	if (options.insertAt === "top") {
		if (!lastStyleElementInsertedAtTop) {
			target.insertBefore(style, target.firstChild);
		} else if (lastStyleElementInsertedAtTop.nextSibling) {
			target.insertBefore(style, lastStyleElementInsertedAtTop.nextSibling);
		} else {
			target.appendChild(style);
		}
		stylesInsertedAtTop.push(style);
	} else if (options.insertAt === "bottom") {
		target.appendChild(style);
	} else if (typeof options.insertAt === "object" && options.insertAt.before) {
		var nextSibling = getElement(options.insertAt.before, target);
		target.insertBefore(style, nextSibling);
	} else {
		throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
	}
}

function removeStyleElement (style) {
	if (style.parentNode === null) return false;
	style.parentNode.removeChild(style);

	var idx = stylesInsertedAtTop.indexOf(style);
	if(idx >= 0) {
		stylesInsertedAtTop.splice(idx, 1);
	}
}

function createStyleElement (options) {
	var style = document.createElement("style");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}

	if(options.attrs.nonce === undefined) {
		var nonce = getNonce();
		if (nonce) {
			options.attrs.nonce = nonce;
		}
	}

	addAttrs(style, options.attrs);
	insertStyleElement(options, style);

	return style;
}

function createLinkElement (options) {
	var link = document.createElement("link");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}
	options.attrs.rel = "stylesheet";

	addAttrs(link, options.attrs);
	insertStyleElement(options, link);

	return link;
}

function addAttrs (el, attrs) {
	Object.keys(attrs).forEach(function (key) {
		el.setAttribute(key, attrs[key]);
	});
}

function getNonce() {
	if (false) {}

	return __webpack_require__.nc;
}

function addStyle (obj, options) {
	var style, update, remove, result;

	// If a transform function was defined, run it on the css
	if (options.transform && obj.css) {
	    result = typeof options.transform === 'function'
		 ? options.transform(obj.css) 
		 : options.transform.default(obj.css);

	    if (result) {
	    	// If transform returns a value, use that instead of the original css.
	    	// This allows running runtime transformations on the css.
	    	obj.css = result;
	    } else {
	    	// If the transform function returns a falsy value, don't add this css.
	    	// This allows conditional loading of css
	    	return function() {
	    		// noop
	    	};
	    }
	}

	if (options.singleton) {
		var styleIndex = singletonCounter++;

		style = singleton || (singleton = createStyleElement(options));

		update = applyToSingletonTag.bind(null, style, styleIndex, false);
		remove = applyToSingletonTag.bind(null, style, styleIndex, true);

	} else if (
		obj.sourceMap &&
		typeof URL === "function" &&
		typeof URL.createObjectURL === "function" &&
		typeof URL.revokeObjectURL === "function" &&
		typeof Blob === "function" &&
		typeof btoa === "function"
	) {
		style = createLinkElement(options);
		update = updateLink.bind(null, style, options);
		remove = function () {
			removeStyleElement(style);

			if(style.href) URL.revokeObjectURL(style.href);
		};
	} else {
		style = createStyleElement(options);
		update = applyToTag.bind(null, style);
		remove = function () {
			removeStyleElement(style);
		};
	}

	update(obj);

	return function updateStyle (newObj) {
		if (newObj) {
			if (
				newObj.css === obj.css &&
				newObj.media === obj.media &&
				newObj.sourceMap === obj.sourceMap
			) {
				return;
			}

			update(obj = newObj);
		} else {
			remove();
		}
	};
}

var replaceText = (function () {
	var textStore = [];

	return function (index, replacement) {
		textStore[index] = replacement;

		return textStore.filter(Boolean).join('\n');
	};
})();

function applyToSingletonTag (style, index, remove, obj) {
	var css = remove ? "" : obj.css;

	if (style.styleSheet) {
		style.styleSheet.cssText = replaceText(index, css);
	} else {
		var cssNode = document.createTextNode(css);
		var childNodes = style.childNodes;

		if (childNodes[index]) style.removeChild(childNodes[index]);

		if (childNodes.length) {
			style.insertBefore(cssNode, childNodes[index]);
		} else {
			style.appendChild(cssNode);
		}
	}
}

function applyToTag (style, obj) {
	var css = obj.css;
	var media = obj.media;

	if(media) {
		style.setAttribute("media", media)
	}

	if(style.styleSheet) {
		style.styleSheet.cssText = css;
	} else {
		while(style.firstChild) {
			style.removeChild(style.firstChild);
		}

		style.appendChild(document.createTextNode(css));
	}
}

function updateLink (link, options, obj) {
	var css = obj.css;
	var sourceMap = obj.sourceMap;

	/*
		If convertToAbsoluteUrls isn't defined, but sourcemaps are enabled
		and there is no publicPath defined then lets turn convertToAbsoluteUrls
		on by default.  Otherwise default to the convertToAbsoluteUrls option
		directly
	*/
	var autoFixUrls = options.convertToAbsoluteUrls === undefined && sourceMap;

	if (options.convertToAbsoluteUrls || autoFixUrls) {
		css = fixUrls(css);
	}

	if (sourceMap) {
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	var blob = new Blob([css], { type: "text/css" });

	var oldSrc = link.href;

	link.href = URL.createObjectURL(blob);

	if(oldSrc) URL.revokeObjectURL(oldSrc);
}


/***/ }),

/***/ "./node_modules/style-loader/lib/urls.js":
/*!***********************************************!*\
  !*** ./node_modules/style-loader/lib/urls.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {


/**
 * When source maps are enabled, `style-loader` uses a link element with a data-uri to
 * embed the css on the page. This breaks all relative urls because now they are relative to a
 * bundle instead of the current page.
 *
 * One solution is to only use full urls, but that may be impossible.
 *
 * Instead, this function "fixes" the relative urls to be absolute according to the current page location.
 *
 * A rudimentary test suite is located at `test/fixUrls.js` and can be run via the `npm test` command.
 *
 */

module.exports = function (css) {
  // get current location
  var location = typeof window !== "undefined" && window.location;

  if (!location) {
    throw new Error("fixUrls requires window.location");
  }

	// blank or null?
	if (!css || typeof css !== "string") {
	  return css;
  }

  var baseUrl = location.protocol + "//" + location.host;
  var currentDir = baseUrl + location.pathname.replace(/\/[^\/]*$/, "/");

	// convert each url(...)
	/*
	This regular expression is just a way to recursively match brackets within
	a string.

	 /url\s*\(  = Match on the word "url" with any whitespace after it and then a parens
	   (  = Start a capturing group
	     (?:  = Start a non-capturing group
	         [^)(]  = Match anything that isn't a parentheses
	         |  = OR
	         \(  = Match a start parentheses
	             (?:  = Start another non-capturing groups
	                 [^)(]+  = Match anything that isn't a parentheses
	                 |  = OR
	                 \(  = Match a start parentheses
	                     [^)(]*  = Match anything that isn't a parentheses
	                 \)  = Match a end parentheses
	             )  = End Group
              *\) = Match anything and then a close parens
          )  = Close non-capturing group
          *  = Match anything
       )  = Close capturing group
	 \)  = Match a close parens

	 /gi  = Get all matches, not the first.  Be case insensitive.
	 */
	var fixedCss = css.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(fullMatch, origUrl) {
		// strip quotes (if they exist)
		var unquotedOrigUrl = origUrl
			.trim()
			.replace(/^"(.*)"$/, function(o, $1){ return $1; })
			.replace(/^'(.*)'$/, function(o, $1){ return $1; });

		// already a full url? no change
		if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(unquotedOrigUrl)) {
		  return fullMatch;
		}

		// convert the url to a full url
		var newUrl;

		if (unquotedOrigUrl.indexOf("//") === 0) {
		  	//TODO: should we add protocol?
			newUrl = unquotedOrigUrl;
		} else if (unquotedOrigUrl.indexOf("/") === 0) {
			// path should be relative to the base url
			newUrl = baseUrl + unquotedOrigUrl; // already starts with '/'
		} else {
			// path should be relative to current directory
			newUrl = currentDir + unquotedOrigUrl.replace(/^\.\//, ""); // Strip leading './'
		}

		// send back the fixed url(...)
		return "url(" + JSON.stringify(newUrl) + ")";
	});

	// send back the fixed css
	return fixedCss;
};


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=template&id=5c050920&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/question.vue?vue&type=template&id=5c050920& ***!
  \***************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "cardgame-wrapper" }, [
    _c("nav", { staticClass: "page-header navbar navbar-expand-lg" }, [
      _c(
        "div",
        { staticClass: "web-logo" },
        [
          _c("router-link", { attrs: { to: "index" } }, [
            _c("img", {
              staticClass: "img-fluid",
              attrs: { src: "/images/logo_new.png" }
            })
          ])
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "cards" },
      [
        _vm._m(0),
        _vm._v(" "),
        _vm._l(_vm.randkeys, function(d, index) {
          return _c(
            "span",
            { key: index, class: "card" + (index % 2 === 0 ? "B" : "A") },
            [
              _c(
                "span",
                {
                  staticClass: "cardFlip card-front",
                  on: {
                    "~click": function($event) {
                      return _vm.timer()
                    }
                  }
                },
                [
                  _c("span", { staticClass: "cardNum" }, [
                    _vm._v(_vm._s(_vm.faceNum[index]))
                  ]),
                  _vm._v(" "),
                  _c("img", {
                    staticClass: "cardFace",
                    attrs: { src: "/images/cardGame" + (index + 1) + ".png" }
                  })
                ]
              ),
              _vm._v(" "),
              _c("span", { staticClass: "cardFlip card-back" }, [
                _c(
                  "span",
                  { staticClass: "cardQuestion", attrs: { "data-id": d } },
                  [
                    _c("span", {
                      domProps: { innerHTML: _vm._s(_vm.imgs[d].question) }
                    }),
                    _c("br"),
                    _c("br"),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "cardAns",
                        attrs: { "data-ans": "A" },
                        on: {
                          "~click": function($event) {
                            return (function(e) {
                              _vm.ans(e)
                              _vm.stopTime()
                            })($event)
                          }
                        }
                      },
                      [_vm._v("(A)" + _vm._s(_vm.imgs[d].selection[0]))]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "cardAns",
                        attrs: { "data-ans": "B" },
                        on: {
                          "~click": function($event) {
                            return (function(e) {
                              _vm.ans(e)
                              _vm.stopTime()
                            })($event)
                          }
                        }
                      },
                      [_vm._v("(B)" + _vm._s(_vm.imgs[d].selection[1]))]
                    )
                  ]
                )
              ])
            ]
          )
        })
      ],
      2
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "countdown" }, [
      _vm._v("00:"),
      _c("span", [_vm._v("10")])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=template&id=142b21d7&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/cardgame/pages/turntable.vue?vue&type=template&id=142b21d7& ***!
  \****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "turntable-wrapper",
      on: {
        "~click": function($event) {
          return _vm.turn()
        }
      }
    },
    [
      _c("nav", { staticClass: "page-header navbar navbar-expand-lg" }, [
        _c(
          "div",
          { staticClass: "web-logo" },
          [
            _c("router-link", { attrs: { to: "index" } }, [
              _c("img", {
                staticClass: "img-fluid",
                attrs: { src: "/images/logo_new.png" }
              })
            ])
          ],
          1
        )
      ]),
      _vm._v(" "),
      _vm._m(0)
    ]
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "block" }, [
      _c("ul", [
        _vm._v("抽獎辦法：\n      "),
        _c("li", [_vm._v("每用戶只可抽獎一次")]),
        _vm._v(" "),
        _c("li", [_vm._v("中獎後會由公司寄發中獎信件通知")]),
        _vm._v(" "),
        _c("li", [
          _vm._v(
            "本公司保有隨時修改本活動之權利，如有任何變更內容或詳細注意事項將公布於官網"
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "turntable" }, [
        _c("div", { staticClass: "arrow" }),
        _vm._v(" "),
        _c("div", { staticClass: "disk" })
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/js/cardgame/layout.js":
/*!*****************************************!*\
  !*** ./resources/js/cardgame/layout.js ***!
  \*****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _router_router__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./router/router */ "./resources/js/cardgame/router/router.js");
/* harmony import */ var _splidejs_splide_dist_css_themes_splide_default_min_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @splidejs/splide/dist/css/themes/splide-default.min.css */ "./node_modules/@splidejs/splide/dist/css/themes/splide-default.min.css");
/* harmony import */ var _splidejs_splide_dist_css_themes_splide_default_min_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_splidejs_splide_dist_css_themes_splide_default_min_css__WEBPACK_IMPORTED_MODULE_1__);
//vue router


$(function () {
  var now = new Date();
  var startDate = new Date('2021-02-01 00:00:00');
  var endDate = new Date('2021-03-12 23:59:59');

  if (startDate >= now || now > endDate) {
    alert('敬請期待！');
    location.replace('/index');
    return;
  }

  if (localStorage.getItem("flag") != "login") {
    alert('請登入會員');
    location.replace('/index');
  }

  if (window.innerWidth >= 767) {
    alert('請使用行動裝置瀏覽');
    location.replace('/index');
    return;
  }

  var router = new VueRouter({
    routes: _router_router__WEBPACK_IMPORTED_MODULE_0__["default"],
    mode: 'history',
    base: "cardgame"
  });
  router.beforeEach(function (to, from, next) {
    if (to.path === "/") {
      gtag("config", "UA-117279688-9", {
        page_path: '/make'
      });
      next('/make');
    } else {
      gtag("config", "UA-117279688-9", {
        page_path: to.path
      });
      next();
    }
  });
  var vue = new Vue({
    el: '#cardgame',
    router: router,
    computed: {
      isInvestor: function isInvestor() {
        return localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData")).investor : "0";
      },
      flag: function flag() {
        return localStorage.getItem("flag") ? localStorage.getItem("flag") : '';
      },
      userData: function userData() {
        return localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData")) : {};
      }
    },
    created: function created() {}
  });
});

/***/ }),

/***/ "./resources/js/cardgame/pages/question.vue":
/*!**************************************************!*\
  !*** ./resources/js/cardgame/pages/question.vue ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _question_vue_vue_type_template_id_5c050920___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./question.vue?vue&type=template&id=5c050920& */ "./resources/js/cardgame/pages/question.vue?vue&type=template&id=5c050920&");
/* harmony import */ var _question_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./question.vue?vue&type=script&lang=js& */ "./resources/js/cardgame/pages/question.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _question_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./question.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _question_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _question_vue_vue_type_template_id_5c050920___WEBPACK_IMPORTED_MODULE_0__["render"],
  _question_vue_vue_type_template_id_5c050920___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/cardgame/pages/question.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/cardgame/pages/question.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/cardgame/pages/question.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./question.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss&":
/*!************************************************************************************!*\
  !*** ./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss& ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--10-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--10-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./question.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/cardgame/pages/question.vue?vue&type=template&id=5c050920&":
/*!*********************************************************************************!*\
  !*** ./resources/js/cardgame/pages/question.vue?vue&type=template&id=5c050920& ***!
  \*********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_template_id_5c050920___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./question.vue?vue&type=template&id=5c050920& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/question.vue?vue&type=template&id=5c050920&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_template_id_5c050920___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_question_vue_vue_type_template_id_5c050920___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/cardgame/pages/turntable.vue":
/*!***************************************************!*\
  !*** ./resources/js/cardgame/pages/turntable.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _turntable_vue_vue_type_template_id_142b21d7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./turntable.vue?vue&type=template&id=142b21d7& */ "./resources/js/cardgame/pages/turntable.vue?vue&type=template&id=142b21d7&");
/* harmony import */ var _turntable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./turntable.vue?vue&type=script&lang=js& */ "./resources/js/cardgame/pages/turntable.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _turntable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./turntable.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _turntable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _turntable_vue_vue_type_template_id_142b21d7___WEBPACK_IMPORTED_MODULE_0__["render"],
  _turntable_vue_vue_type_template_id_142b21d7___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/cardgame/pages/turntable.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/cardgame/pages/turntable.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/cardgame/pages/turntable.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./turntable.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************!*\
  !*** ./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--10-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--10-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./turntable.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_10_2_node_modules_sass_loader_dist_cjs_js_ref_10_3_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/cardgame/pages/turntable.vue?vue&type=template&id=142b21d7&":
/*!**********************************************************************************!*\
  !*** ./resources/js/cardgame/pages/turntable.vue?vue&type=template&id=142b21d7& ***!
  \**********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_template_id_142b21d7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./turntable.vue?vue&type=template&id=142b21d7& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/cardgame/pages/turntable.vue?vue&type=template&id=142b21d7&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_template_id_142b21d7___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_turntable_vue_vue_type_template_id_142b21d7___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/cardgame/router/router.js":
/*!************************************************!*\
  !*** ./resources/js/cardgame/router/router.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _pages_question__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../pages/question */ "./resources/js/cardgame/pages/question.vue");
/* harmony import */ var _pages_turntable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../pages/turntable */ "./resources/js/cardgame/pages/turntable.vue");


var routers = [{
  path: '*',
  redirect: '/question'
}, {
  path: '/question',
  component: _pages_question__WEBPACK_IMPORTED_MODULE_0__["default"]
}, {
  path: '/turntable',
  component: _pages_turntable__WEBPACK_IMPORTED_MODULE_1__["default"]
}];
/* harmony default export */ __webpack_exports__["default"] = (routers);

/***/ }),

/***/ 4:
/*!***********************************************!*\
  !*** multi ./resources/js/cardgame/layout.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /mnt/c/Users/YamiOdymel/workspace/website/resources/js/cardgame/layout.js */"./resources/js/cardgame/layout.js");


/***/ })

/******/ });