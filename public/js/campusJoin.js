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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/campusJoin.js":
/*!************************************!*\
  !*** ./resources/js/campusJoin.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  if (new Date().getDate() >= new Date('2021/01/31 00:00:00').getDate()) {
    alert('報名已截止，詳情請見官網');
    location.replace('/campuspartner');
    return;
  }

  var vue = new Vue({
    el: '#campusJoin_wrapper',
    data: function data() {
      return {
        isSingup: false,
        teamName: "",
        memberList: [{
          name: "",
          school: "",
          department: "",
          grade: "",
          mobile: "",
          email: "",
          selfIntro: "",
          resume: "",
          proposal: "",
          portfolio: ""
        }]
      };
    },
    created: function created() {},
    methods: {
      checked: function checked($evt) {
        if (!$($evt.target).val()) {
          $($evt.target).addClass("empty").attr('placeholder', '請填寫這個欄位');
        } else {
          $($evt.target).removeClass("empty").attr('placeholder', '');
        }
      },
      uploadFile: function uploadFile($evt, index) {
        var _this = this;

        var upploadFile = new FormData();
        upploadFile.append("file", $evt.target.files[0]);
        upploadFile.append("fileType", $evt.target.name);
        axios.post("/campusUploadFile", upploadFile).then(function (res) {
          _this.memberList[index][$evt.target.name] = res.data;
          alert("上傳成功！");
        })["catch"](function (error) {
          var errorsData = error.response.data;
          $($evt.target).val("");
          alert(errorsData);
        });
      },
      createMember: function createMember() {
        if (this.memberList.length < 3) {
          this.memberList.push({
            name: "",
            school: "",
            department: "",
            grade: "",
            mobile: "",
            email: "",
            selfIntro: "",
            resume: "",
            proposal: "",
            portfolio: ""
          });
        }
      },
      deleteRow: function deleteRow(index) {
        if (confirm("確定移除此隊員?")) {
          this.memberList.splice(index, 1);
        }
      },
      submit: function submit() {
        var _this2 = this;

        var memberList = this.memberList,
            teamName = this.teamName;

        if ($('.empty').length !== 0) {
          alert('有欄位未輸入');
          return;
        }

        axios.post("/campusSignup", {
          memberList: memberList,
          teamName: teamName
        }).then(function (res) {
          _this2.isSingup = true;
        })["catch"](function (error) {
          var errorsData = error.response.data;
          Object.keys(errorsData.errors).forEach(function (key) {
            if ('index' in errorsData) {
              $("#".concat(key, "_").concat(errorsData.index)).attr('placeholder', errorsData.errors[key]).addClass("empty");
            } else {
              $("#".concat(key)).attr('placeholder', errorsData.errors[key]).addClass("empty");
            }
          });
        });
      }
    }
  });
});

/***/ }),

/***/ 2:
/*!******************************************!*\
  !*** multi ./resources/js/campusJoin.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /mnt/c/Users/YamiOdymel/workspace/website/resources/js/campusJoin.js */"./resources/js/campusJoin.js");


/***/ })

/******/ });