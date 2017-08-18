webpackJsonp([3],{

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/common/restPassword.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vuex__ = __webpack_require__("./node_modules/_vuex@2.3.1@vuex/dist/vuex.esm.js");
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
        var _this = this;

        var validatePassword = function validatePassword(rule, value, callback) {
            if (value !== '') {
                if (value !== _this.formPassword.password) {
                    callback(new Error("两次输入的密码不一致"));
                } else {
                    callback();
                }
            }
        };
        var validateCheckPassword = function validateCheckPassword(rule, value, callback) {
            if (value !== '') {
                axios.post('user/check_password', { password: value }).then(function (res) {
                    if (res.data.sta === 0) {
                        callback(new Error(res.data.msg));
                    } else {
                        callback();
                    }
                });
            }
        };
        return {
            loading: false,
            formPassword: {
                old_password: '',
                password: '',
                passwordCheck: ''
            },
            ruleFormPassword: {
                old_password: [{ required: true, message: '请填写原始密码', trigger: 'blur' }, { validator: validateCheckPassword, trigger: 'blur' }],
                password: [{ required: true, message: '请填写密码', trigger: 'blur' }, { min: 6, max: 15, message: '密码长度6-15位', trigger: 'change' }],
                passwordCheck: [{ required: true, message: '请输入确认密码', trigger: 'blur' }, { validator: validatePassword, trigger: 'blur' }]
            }
        };
    },

    computed: Object(__WEBPACK_IMPORTED_MODULE_0_vuex__["b" /* mapState */])(['userInfo', 'choice_cmap']),
    methods: {
        rest_password: function rest_password(name) {
            var _this2 = this;

            this.loading = true;
            this.$refs[name].validate(function (valid) {
                if (valid) {
                    axios.post('user/update_password', { password: _this2.formPassword.password }).then(function (res) {
                        if (res.data.sta === 0) {
                            _this2.$Message.error(res.data.msg);
                        } else {
                            _this2.$Message.success(res.data.msg);
                            _this2.formPassword.old_password = '';
                            _this2.formPassword.password = '';
                            _this2.formPassword.passwordCheck = '';
                        }
                        _this2.loading = false;
                    }).catch(function (err) {
                        _this2.loading = false;
                        _this2.$Message.error('好像什么东西错了，请联系管理员');
                    });
                } else {
                    _this2.loading = false;
                    _this2.$Message.error('表单验证失败!');
                }
            });
        }
    }
});

/***/ }),

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/user/Setting.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue_avatar_cropper__ = __webpack_require__("./node_modules/_vue-avatar-cropper@0.0.13@vue-avatar-cropper/dist/index.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue_avatar_cropper___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue_avatar_cropper__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__common_restPassword_vue__ = __webpack_require__("./resources/assets/js/components/common/restPassword.vue");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__common_restPassword_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__common_restPassword_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_vuex__ = __webpack_require__("./node_modules/_vuex@2.3.1@vuex/dist/vuex.esm.js");
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
            formItem: {
                avatar: '',
                name: '',
                camp: '',
                info: ''
            },
            is_camp: false,
            ruleValidate: {
                name: [{ required: true, message: '昵称不能为空', trigger: 'blur' }],
                info: [{ type: 'string', max: 255, message: '介绍不能多于255个字', trigger: 'change' }]
            },
            csrfToken: window.Laravel.csrfToken
        };
    },

    methods: {
        check_is_camp: function check_is_camp() {
            var _this = this;

            axios.get('user/check_is_camp').then(function (res) {
                if (res.data.sta === 1) {
                    _this.is_camp = true;
                } else {
                    _this.is_camp = false;
                }
            });
        },
        updateUserAvatar: function updateUserAvatar(res) {
            if (res.sta === 1) {
                this.$Message.success('头像更新成功');
                this.formItem.avatar = res.info.avatar;
                this.$store.commit('change_userInfo', res.info);
            } else {
                this.$Message.error(res.msg);
            }
        },
        handleSubmit: function handleSubmit(name) {
            var _this2 = this;

            this.$refs[name].validate(function (valid) {
                if (valid) {
                    axios.post('user/update', { name: _this2.formItem.name, info: _this2.formItem.info, camp: _this2.formItem.camp }).then(function (res) {
                        if (res.data.sta === 1) {
                            _this2.check_is_camp();
                            _this2.$Message.success('信息更新成功');
                            _this2.$store.commit('change_userInfo', res.data.info);
                        } else {
                            _this2.$Message.error(res.data.msg);
                        }
                    });
                } else {
                    _this2.$Message.error('表单验证失败!');
                }
            });
        }
    },
    computed: Object(__WEBPACK_IMPORTED_MODULE_2_vuex__["b" /* mapState */])(['userInfo', 'choice_cmap']),
    mounted: function mounted() {
        var _this3 = this;

        setTimeout(function () {
            _this3.formItem.avatar = _this3.userInfo.avatar;
            _this3.formItem.name = _this3.userInfo.name;
            _this3.formItem.camp = _this3.userInfo.camp + '';
            _this3.formItem.info = _this3.userInfo.info;
        }, 300);
        this.check_is_camp();
    },

    components: {
        'avatar-cropper': __WEBPACK_IMPORTED_MODULE_0_vue_avatar_cropper___default.a,
        'rest-password': __WEBPACK_IMPORTED_MODULE_1__common_restPassword_vue___default.a
    }
});

/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-c617b260\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Setting.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "\n.user_setting #set-avatar[data-v-c617b260] {\n  width: 80px;\n  height: 80px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-f1d4bd02\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/restPassword.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "", ""]);

// exports


/***/ }),

/***/ "./node_modules/_vue-avatar-cropper@0.0.13@vue-avatar-cropper/dist/index.js":
/***/ (function(module, exports, __webpack_require__) {

!function(t,e){ true?module.exports=e():"function"==typeof define&&define.amd?define("vue-avatar-cropper",[],e):"object"==typeof exports?exports["vue-avatar-cropper"]=e():t["vue-avatar-cropper"]=e()}(this,function(){return function(t){function e(r){if(o[r])return o[r].exports;var a=o[r]={i:r,l:!1,exports:{}};return t[r].call(a.exports,a,a.exports,e),a.l=!0,a.exports}var o={};return e.m=t,e.c=o,e.i=function(t){return t},e.d=function(t,o,r){e.o(t,o)||Object.defineProperty(t,o,{configurable:!1,enumerable:!0,get:r})},e.n=function(t){var o=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(o,"a",o),o},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=4)}([function(t,e){t.exports=function(){var t=[];return t.toString=function(){for(var t=[],e=0;e<this.length;e++){var o=this[e];o[2]?t.push("@media "+o[2]+"{"+o[1]+"}"):t.push(o[1])}return t.join("")},t.i=function(e,o){"string"==typeof e&&(e=[[null,e,""]]);for(var r={},a=0;a<this.length;a++){var i=this[a][0];"number"==typeof i&&(r[i]=!0)}for(a=0;a<e.length;a++){var n=e[a];"number"==typeof n[0]&&r[n[0]]||(o&&!n[2]?n[2]=o:o&&(n[2]="("+n[2]+") and ("+o+")"),t.push(n))}},t}},function(t,e,o){function r(t,e){for(var o=0;o<t.length;o++){var r=t[o],a=f[r.id];if(a){a.refs++;for(var i=0;i<a.parts.length;i++)a.parts[i](r.parts[i]);for(;i<r.parts.length;i++)a.parts.push(l(r.parts[i],e))}else{for(var n=[],i=0;i<r.parts.length;i++)n.push(l(r.parts[i],e));f[r.id]={id:r.id,refs:1,parts:n}}}}function a(t){for(var e=[],o={},r=0;r<t.length;r++){var a=t[r],i=a[0],n=a[1],p=a[2],c=a[3],s={css:n,media:p,sourceMap:c};o[i]?o[i].parts.push(s):e.push(o[i]={id:i,parts:[s]})}return e}function i(t,e){var o=g(t.insertInto);if(!o)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var r=w[w.length-1];if("top"===t.insertAt)r?r.nextSibling?o.insertBefore(e,r.nextSibling):o.appendChild(e):o.insertBefore(e,o.firstChild),w.push(e);else{if("bottom"!==t.insertAt)throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");o.appendChild(e)}}function n(t){t.parentNode.removeChild(t);var e=w.indexOf(t);e>=0&&w.splice(e,1)}function p(t){var e=document.createElement("style");return t.attrs.type="text/css",s(e,t.attrs),i(t,e),e}function c(t){var e=document.createElement("link");return t.attrs.type="text/css",t.attrs.rel="stylesheet",s(e,t.attrs),i(t,e),e}function s(t,e){Object.keys(e).forEach(function(o){t.setAttribute(o,e[o])})}function l(t,e){var o,r,a;if(e.singleton){var i=b++;o=v||(v=p(e)),r=d.bind(null,o,i,!1),a=d.bind(null,o,i,!0)}else t.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(o=c(e),r=u.bind(null,o,e),a=function(){n(o),o.href&&URL.revokeObjectURL(o.href)}):(o=p(e),r=h.bind(null,o),a=function(){n(o)});return r(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;r(t=e)}else a()}}function d(t,e,o,r){var a=o?"":r.css;if(t.styleSheet)t.styleSheet.cssText=y(e,a);else{var i=document.createTextNode(a),n=t.childNodes;n[e]&&t.removeChild(n[e]),n.length?t.insertBefore(i,n[e]):t.appendChild(i)}}function h(t,e){var o=e.css,r=e.media;if(r&&t.setAttribute("media",r),t.styleSheet)t.styleSheet.cssText=o;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(o))}}function u(t,e,o){var r=o.css,a=o.sourceMap,i=void 0===e.convertToAbsoluteUrls&&a;(e.convertToAbsoluteUrls||i)&&(r=x(r)),a&&(r+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(a))))+" */");var n=new Blob([r],{type:"text/css"}),p=t.href;t.href=URL.createObjectURL(n),p&&URL.revokeObjectURL(p)}var f={},m=function(t){var e;return function(){return void 0===e&&(e=t.apply(this,arguments)),e}}(function(){return/msie [6-9]\b/.test(self.navigator.userAgent.toLowerCase())}),g=function(t){var e={};return function(o){return void 0===e[o]&&(e[o]=t.call(this,o)),e[o]}}(function(t){return document.querySelector(t)}),v=null,b=0,w=[],x=o(8);t.exports=function(t,e){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");e=e||{},e.attrs="object"==typeof e.attrs?e.attrs:{},void 0===e.singleton&&(e.singleton=m()),void 0===e.insertInto&&(e.insertInto="head"),void 0===e.insertAt&&(e.insertAt="bottom");var o=a(t);return r(o,e),function(t){for(var i=[],n=0;n<o.length;n++){var p=o[n],c=f[p.id];c.refs--,i.push(c)}if(t){r(a(t),e)}for(var n=0;n<i.length;n++){var c=i[n];if(0===c.refs){for(var s=0;s<c.parts.length;s++)c.parts[s]();delete f[c.id]}}}};var y=function(){var t=[];return function(e,o){return t[e]=o,t.filter(Boolean).join("\n")}}()},function(t,e,o){o(10);var r=o(11)(o(3),o(12),null,null);t.exports=r.exports},function(t,e,o){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=o(9),a=(o.n(r),o(5)),i=o.n(a),n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};e.default={props:{trigger:{type:[String,Element],required:!0},uploadHandler:{type:Function},uploadUrl:{type:String},uploadHeaders:{type:Object},uploadFormName:{type:String,default:"file"},uploadFormData:{type:Object,default:function(){return{}}},uploaded:{type:Function,required:!0},mimes:{type:String,default:"image/png, image/gif, image/jpeg, image/bmp, image/x-icon"}},data:function(){return{cropper:void 0,dataUrl:void 0}},methods:{destroy:function(){this.cropper.destroy(),this.dataUrl=void 0},submit:function(){if(this.uploadUrl)this.uploadImage(this.uploaded);else{if(!this.uploadHandler)throw new Error("No upload handler found.");this.uploadHandler(this.cropper)}this.destroy()},pickImage:function(){var t=this,e="avatar-img-input",o=document.querySelector("input#"+e+"[type=file]");null==o&&(o=document.createElement("input"),o.id=e,o.setAttribute("type","file"),o.setAttribute("accept",this.mimes),o.addEventListener("change",function(){if(null!=o.files&&null!=o.files[0]){var e=new FileReader;e.onload=function(e){t.dataUrl=e.target.result},e.readAsDataURL(o.files[0])}})),o.click()},createCropper:function(){var t=document.querySelector(".avatar-cropper-image-container img");this.cropper=new i.a(t,{aspectRatio:1,autoCropArea:1,viewMode:1,movable:!1,zoomable:!1})},uploadImage:function(t){var e=this;this.cropper.getCroppedCanvas().toBlob(function(o){var r=new FormData,a=new XMLHttpRequest,i=Object.assign({},e.uploadFormData);i[e.uploadFormName]=o;for(var n in i)r.append(n,i[n]);a.open("POST",e.uploadUrl,!0);for(var p in e.uploadHeaders)a.setRequestHeader(p,e.uploadHeaders[p]);a.onreadystatechange=function(){if(4===a.readyState){var e=JSON.parse(a.responseText);if(200!==a.status)throw new Error("Image upload fail.",a);t(e)}},a.send(r)})}},mounted:function(){var t="object"==n(this.trigger)?this.trigger:document.querySelector(this.trigger);if(!t)throw new Error("No avatar make trigger found.");t.addEventListener("click",this.pickImage)}}},function(t,e,o){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=o(2),a=o.n(r);e.default=a.a},function(t,e,o){!function(e,o){t.exports=o()}(0,function(){"use strict";function t(t){return it.call(t).slice(8,-1).toLowerCase()}function e(t){return"number"==typeof t&&!isNaN(t)}function o(t){return void 0===t}function r(t){return"object"===(void 0===t?"undefined":_(t))&&null!==t}function a(t){if(!r(t))return!1;try{var e=t.constructor,o=e.prototype;return e&&o&&nt.call(o,"isPrototypeOf")}catch(t){return!1}}function i(e){return"function"===t(e)}function n(e){return Array.isArray?Array.isArray(e):"array"===t(e)}function p(t){return"string"==typeof t&&(t=t.trim?t.trim():t.replace(tt,"$1")),t}function c(t,o){if(t&&i(o)){var a=void 0;if(n(t)||e(t.length)){var p=t.length;for(a=0;a<p&&!1!==o.call(t,t[a],a,t);a++);}else r(t)&&Object.keys(t).forEach(function(e){o.call(t,t[e],e,t)})}return t}function s(t){for(var e=arguments.length,o=Array(e>1?e-1:0),a=1;a<e;a++)o[a-1]=arguments[a];if(r(t)&&o.length>0){if(Object.assign)return Object.assign.apply(Object,[t].concat(o));o.forEach(function(e){r(e)&&Object.keys(e).forEach(function(o){t[o]=e[o]})})}return t}function l(t,e){for(var o=arguments.length,r=Array(o>2?o-2:0),a=2;a<o;a++)r[a-2]=arguments[a];return function(){for(var o=arguments.length,a=Array(o),i=0;i<o;i++)a[i]=arguments[i];return t.apply(e,r.concat(a))}}function d(t,o){var r=t.style;c(o,function(t,o){K.test(o)&&e(t)&&(t+="px"),r[o]=t})}function h(t,e){return t.classList?t.classList.contains(e):t.className.indexOf(e)>-1}function u(t,o){if(o){if(e(t.length))return void c(t,function(t){u(t,o)});if(t.classList)return void t.classList.add(o);var r=p(t.className);r?r.indexOf(o)<0&&(t.className=r+" "+o):t.className=o}}function f(t,o){if(o)return e(t.length)?void c(t,function(t){f(t,o)}):t.classList?void t.classList.remove(o):void(t.className.indexOf(o)>=0&&(t.className=t.className.replace(o,"")))}function m(t,o,r){if(o)return e(t.length)?void c(t,function(t){m(t,o,r)}):void(r?u(t,o):f(t,o))}function g(t){return t.replace(G,"$1-$2").toLowerCase()}function v(t,e){return r(t[e])?t[e]:t.dataset?t.dataset[e]:t.getAttribute("data-"+g(e))}function b(t,e,o){r(o)?t[e]=o:t.dataset?t.dataset[e]=o:t.setAttribute("data-"+g(e),o)}function w(t,e){if(r(t[e]))delete t[e];else if(t.dataset)try{delete t.dataset[e]}catch(o){t.dataset[e]=null}else t.removeAttribute("data-"+g(e))}function x(t,e,o){var r=p(e).split(V);if(r.length>1)return void c(r,function(e){x(t,e,o)});t.removeEventListener?t.removeEventListener(e,o,!1):t.detachEvent&&t.detachEvent("on"+e,o)}function y(t,e,o,r){var a=p(e).split(V),i=o;if(a.length>1)return void c(a,function(e){y(t,e,o)});r&&(o=function(){for(var r=arguments.length,a=Array(r),n=0;n<r;n++)a[n]=arguments[n];return x(t,e,o),i.apply(t,a)}),t.addEventListener?t.addEventListener(e,o,!1):t.attachEvent&&t.attachEvent("on"+e,o)}function C(t,e,r){if(t.dispatchEvent){var a=void 0;return i(Event)&&i(CustomEvent)?a=o(r)?new Event(e,{bubbles:!0,cancelable:!0}):new CustomEvent(e,{detail:r,bubbles:!0,cancelable:!0}):o(r)?(a=document.createEvent("Event"),a.initEvent(e,!0,!0)):(a=document.createEvent("CustomEvent"),a.initCustomEvent(e,!0,!0,r)),t.dispatchEvent(a)}return!t.fireEvent||t.fireEvent("on"+e)}function M(t){var o=t||window.event;if(o.target||(o.target=o.srcElement||document),!e(o.pageX)&&e(o.clientX)){var r=t.target.ownerDocument||document,a=r.documentElement,i=r.body;o.pageX=o.clientX+((a&&a.scrollLeft||i&&i.scrollLeft||0)-(a&&a.clientLeft||i&&i.clientLeft||0)),o.pageY=o.clientY+((a&&a.scrollTop||i&&i.scrollTop||0)-(a&&a.clientTop||i&&i.clientTop||0))}return o}function D(t){var e=document.documentElement,o=t.getBoundingClientRect();return{left:o.left+((window.scrollX||e&&e.scrollLeft||0)-(e&&e.clientLeft||0)),top:o.top+((window.scrollY||e&&e.scrollTop||0)-(e&&e.clientTop||0))}}function k(t,e){return t.getElementsByTagName(e)}function A(t,e){return t.getElementsByClassName?t.getElementsByClassName(e):t.querySelectorAll("."+e)}function B(t){return document.createElement(t)}function T(t,e){t.appendChild(e)}function E(t){t.parentNode&&t.parentNode.removeChild(t)}function L(t){for(;t.firstChild;)t.removeChild(t.firstChild)}function O(t){var e=t.match(J);return e&&(e[1]!==location.protocol||e[2]!==location.hostname||e[3]!==location.port)}function S(t){var e="timestamp="+(new Date).getTime();return t+(-1===t.indexOf("?")?"?":"&")+e}function H(t,e){if(t.naturalWidth&&!rt)return void e(t.naturalWidth,t.naturalHeight);var o=B("img");o.onload=function(){e(this.width,this.height)},o.src=t.src}function R(t){var o=[],r=t.translateX,a=t.translateY,i=t.rotate,n=t.scaleX,p=t.scaleY;e(r)&&0!==r&&o.push("translateX("+r+"px)"),e(a)&&0!==a&&o.push("translateY("+a+"px)"),e(i)&&0!==i&&o.push("rotate("+i+"deg)"),e(n)&&1!==n&&o.push("scaleX("+n+")"),e(p)&&1!==p&&o.push("scaleY("+p+")");var c=o.length?o.join(" "):"none";return{WebkitTransform:c,msTransform:c,transform:c}}function N(t,e){var o=Math.abs(t.degree)%180,r=(o>90?180-o:o)*Math.PI/180,a=Math.sin(r),i=Math.cos(r),n=t.width,p=t.height,c=t.aspectRatio,s=void 0,l=void 0;return e?(s=n/(i+a/c),l=s/c):(s=n*i+p*a,l=n*a+p*i),{width:s,height:l}}function z(t,o,r){var a=B("canvas"),i=a.getContext("2d"),n=0,p=0,c=o.naturalWidth,s=o.naturalHeight,l=o.rotate,d=o.scaleX,h=o.scaleY,u=e(d)&&e(h)&&(1!==d||1!==h),f=e(l)&&0!==l,m=f||u,g=c*Math.abs(d||1),v=s*Math.abs(h||1),b=void 0,w=void 0,x=void 0;return u&&(b=g/2,w=v/2),f&&(x=N({width:g,height:v,degree:l}),g=x.width,v=x.height,b=g/2,w=v/2),a.width=g,a.height=v,r.fillColor&&(i.fillStyle=r.fillColor,i.fillRect(0,0,g,v)),m&&(n=-c/2,p=-s/2,i.save(),i.translate(b,w)),f&&i.rotate(l*Math.PI/180),u&&i.scale(d,h),i.imageSmoothingEnabled=!!r.imageSmoothingEnabled,r.imageSmoothingQuality&&(i.imageSmoothingQuality=r.imageSmoothingQuality),i.drawImage(t,Math.floor(n),Math.floor(p),Math.floor(c),Math.floor(s)),m&&i.restore(),a}function X(t,e,o){var r="",a=e;for(o+=e;a<o;a++)r+=pt(t.getUint8(a));return r}function W(t){var e=new DataView(t),o=e.byteLength,r=void 0,a=void 0,i=void 0,n=void 0,p=void 0,c=void 0,s=void 0,l=void 0,d=void 0,h=void 0;if(255===e.getUint8(0)&&216===e.getUint8(1))for(d=2;d<o;){if(255===e.getUint8(d)&&225===e.getUint8(d+1)){s=d;break}d++}if(s&&(a=s+4,i=s+10,"Exif"===X(e,a,4)&&(c=e.getUint16(i),((p=18761===c)||19789===c)&&42===e.getUint16(i+2,p)&&(n=e.getUint32(i+4,p))>=8&&(l=i+n))),l)for(o=e.getUint16(l,p),h=0;h<o;h++)if(d=l+12*h+2,274===e.getUint16(d,p)){d+=8,r=e.getUint16(d,p),rt&&e.setUint16(d,1,p);break}return r}function U(t){var e=t.replace(Z,""),o=atob(e),r=o.length,a=new ArrayBuffer(r),i=new Uint8Array(a),n=void 0;for(n=0;n<r;n++)i[n]=o.charCodeAt(n);return a}function j(t){var e=new Uint8Array(t),o=e.length,r="",a=void 0;for(a=0;a<o;a++)r+=pt(e[a]);return"data:image/jpeg;base64,"+btoa(r)}function Y(t,e){var o=t.pageX,r=t.pageY,a={endX:o,endY:r};return e?a:s({startX:o,startY:r},a)}function I(t){var e=s({},t),o=[];return c(t,function(t,r){delete e[r],c(e,function(e){var r=Math.abs(t.startX-e.startX),a=Math.abs(t.startY-e.startY),i=Math.abs(t.endX-e.endX),n=Math.abs(t.endY-e.endY),p=Math.sqrt(r*r+a*a),c=Math.sqrt(i*i+n*n),s=(c-p)/p;o.push(s)})}),o.sort(function(t,e){return Math.abs(t)<Math.abs(e)}),o[0]}function P(t){var e=0,o=0,r=0;return c(t,function(t){var a=t.startX,i=t.startY;e+=a,o+=i,r+=1}),e/=r,o/=r,{pageX:e,pageY:o}}var F={viewMode:0,dragMode:"crop",aspectRatio:NaN,data:null,preview:"",responsive:!0,restore:!0,checkCrossOrigin:!0,checkOrientation:!0,modal:!0,guides:!0,center:!0,highlight:!0,background:!0,autoCrop:!0,autoCropArea:.8,movable:!0,rotatable:!0,scalable:!0,zoomable:!0,zoomOnTouch:!0,zoomOnWheel:!0,wheelZoomRatio:.1,cropBoxMovable:!0,cropBoxResizable:!0,toggleDragModeOnDblclick:!0,minCanvasWidth:0,minCanvasHeight:0,minCropBoxWidth:0,minCropBoxHeight:0,minContainerWidth:200,minContainerHeight:100,ready:null,cropstart:null,cropmove:null,cropend:null,crop:null,zoom:null},_="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},q=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")},Q=function(){function t(t,e){for(var o=0;o<e.length;o++){var r=e[o];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}return function(e,o,r){return o&&t(e.prototype,o),r&&t(e,r),e}}(),$=function(t){if(Array.isArray(t)){for(var e=0,o=Array(t.length);e<t.length;e++)o[e]=t[e];return o}return Array.from(t)},Z=/^data:.*,/,G=/([a-z\d])([A-Z])/g,J=/^(https?:)\/\/([^:\/?#]+):?(\d*)/i,V=/\s+/,K=/^(width|height|left|top|marginLeft|marginTop)$/,tt=/^\s+(.*)\s+$/,et=/(Macintosh|iPhone|iPod|iPad).*AppleWebKit/i,ot="undefined"!=typeof window?window.navigator:null,rt=ot&&et.test(ot.userAgent),at=Object.prototype,it=at.toString,nt=at.hasOwnProperty,pt=(Array.prototype.slice,String.fromCharCode),ct={render:function(){var t=this;t.initContainer(),t.initCanvas(),t.initCropBox(),t.renderCanvas(),t.cropped&&t.renderCropBox()},initContainer:function(){var t=this,e=t.options,o=t.element,r=t.container,a=t.cropper,i="cropper-hidden",n=void 0;u(a,i),f(o,i),t.containerData=n={width:Math.max(r.offsetWidth,Number(e.minContainerWidth)||200),height:Math.max(r.offsetHeight,Number(e.minContainerHeight)||100)},d(a,{width:n.width,height:n.height}),u(o,i),f(a,i)},initCanvas:function(){var t=this,e=t.options.viewMode,o=t.containerData,r=t.imageData,a=Math.abs(r.rotate)%180==90,i=a?r.naturalHeight:r.naturalWidth,n=a?r.naturalWidth:r.naturalHeight,p=i/n,c=o.width,l=o.height;o.height*p>o.width?3===e?c=o.height*p:l=o.width/p:3===e?l=o.width/p:c=o.height*p;var d={naturalWidth:i,naturalHeight:n,aspectRatio:p,width:c,height:l};d.oldLeft=d.left=(o.width-c)/2,d.oldTop=d.top=(o.height-l)/2,t.canvasData=d,t.limited=1===e||2===e,t.limitCanvas(!0,!0),t.initialImageData=s({},r),t.initialCanvasData=s({},d)},limitCanvas:function(t,e){var o=this,r=o.options,a=r.viewMode,i=o.containerData,n=o.canvasData,p=n.aspectRatio,c=o.cropBoxData,s=o.cropped&&c;if(t){var l=Number(r.minCanvasWidth)||0,d=Number(r.minCanvasHeight)||0;a>1?(l=Math.max(l,i.width),d=Math.max(d,i.height),3===a&&(d*p>l?l=d*p:d=l/p)):a>0&&(l?l=Math.max(l,s?c.width:0):d?d=Math.max(d,s?c.height:0):s&&(l=c.width,d=c.height,d*p>l?l=d*p:d=l/p)),l&&d?d*p>l?d=l/p:l=d*p:l?d=l/p:d&&(l=d*p),n.minWidth=l,n.minHeight=d,n.maxWidth=1/0,n.maxHeight=1/0}if(e)if(a){var h=i.width-n.width,u=i.height-n.height;n.minLeft=Math.min(0,h),n.minTop=Math.min(0,u),n.maxLeft=Math.max(0,h),n.maxTop=Math.max(0,u),s&&o.limited&&(n.minLeft=Math.min(c.left,c.left+(c.width-n.width)),n.minTop=Math.min(c.top,c.top+(c.height-n.height)),n.maxLeft=c.left,n.maxTop=c.top,2===a&&(n.width>=i.width&&(n.minLeft=Math.min(0,h),n.maxLeft=Math.max(0,h)),n.height>=i.height&&(n.minTop=Math.min(0,u),n.maxTop=Math.max(0,u))))}else n.minLeft=-n.width,n.minTop=-n.height,n.maxLeft=i.width,n.maxTop=i.height},renderCanvas:function(t){var e=this,o=e.canvasData,r=e.imageData,a=r.rotate;if(e.rotated){e.rotated=!1;var i=N({width:r.width,height:r.height,degree:a}),n=i.width/i.height,p=1===r.aspectRatio;if(p||n!==o.aspectRatio){if(o.left-=(i.width-o.width)/2,o.top-=(i.height-o.height)/2,o.width=i.width,o.height=i.height,o.aspectRatio=n,o.naturalWidth=r.naturalWidth,o.naturalHeight=r.naturalHeight,p&&a%90||a%180){var c=N({width:r.naturalWidth,height:r.naturalHeight,degree:a});o.naturalWidth=c.width,o.naturalHeight=c.height}e.limitCanvas(!0,!1)}}(o.width>o.maxWidth||o.width<o.minWidth)&&(o.left=o.oldLeft),(o.height>o.maxHeight||o.height<o.minHeight)&&(o.top=o.oldTop),o.width=Math.min(Math.max(o.width,o.minWidth),o.maxWidth),o.height=Math.min(Math.max(o.height,o.minHeight),o.maxHeight),e.limitCanvas(!1,!0),o.oldLeft=o.left=Math.min(Math.max(o.left,o.minLeft),o.maxLeft),o.oldTop=o.top=Math.min(Math.max(o.top,o.minTop),o.maxTop),d(e.canvas,s({width:o.width,height:o.height},R({translateX:o.left,translateY:o.top}))),e.renderImage(),e.cropped&&e.limited&&e.limitCropBox(!0,!0),t&&e.output()},renderImage:function(t){var e=this,o=e.canvasData,r=e.imageData,a=void 0,i=void 0,n=void 0,p=void 0;r.rotate&&(i=N({width:o.width,height:o.height,degree:r.rotate,aspectRatio:r.aspectRatio},!0),n=i.width,p=i.height,a={width:n,height:p,left:(o.width-n)/2,top:(o.height-p)/2}),s(r,a||{width:o.width,height:o.height,left:0,top:0}),d(e.image,s({width:r.width,height:r.height},R(s({translateX:r.left,translateY:r.top},r)))),t&&e.output()},initCropBox:function(){var t=this,e=t.options,o=e.aspectRatio,r=Number(e.autoCropArea)||.8,a=t.canvasData,i={width:a.width,height:a.height};o&&(a.height*o>a.width?i.height=i.width/o:i.width=i.height*o),t.cropBoxData=i,t.limitCropBox(!0,!0),i.width=Math.min(Math.max(i.width,i.minWidth),i.maxWidth),i.height=Math.min(Math.max(i.height,i.minHeight),i.maxHeight),i.width=Math.max(i.minWidth,i.width*r),i.height=Math.max(i.minHeight,i.height*r),i.oldLeft=i.left=a.left+(a.width-i.width)/2,i.oldTop=i.top=a.top+(a.height-i.height)/2,t.initialCropBoxData=s({},i)},limitCropBox:function(t,e){var o=this,r=o.options,a=r.aspectRatio,i=o.containerData,n=o.canvasData,p=o.cropBoxData,c=o.limited;if(t){var s=Number(r.minCropBoxWidth)||0,l=Number(r.minCropBoxHeight)||0,d=Math.min(i.width,c?n.width:i.width),h=Math.min(i.height,c?n.height:i.height);s=Math.min(s,i.width),l=Math.min(l,i.height),a&&(s&&l?l*a>s?l=s/a:s=l*a:s?l=s/a:l&&(s=l*a),h*a>d?h=d/a:d=h*a),p.minWidth=Math.min(s,d),p.minHeight=Math.min(l,h),p.maxWidth=d,p.maxHeight=h}e&&(c?(p.minLeft=Math.max(0,n.left),p.minTop=Math.max(0,n.top),p.maxLeft=Math.min(i.width,n.left+n.width)-p.width,p.maxTop=Math.min(i.height,n.top+n.height)-p.height):(p.minLeft=0,p.minTop=0,p.maxLeft=i.width-p.width,p.maxTop=i.height-p.height))},renderCropBox:function(){var t=this,e=t.options,o=t.containerData,r=t.cropBoxData;(r.width>r.maxWidth||r.width<r.minWidth)&&(r.left=r.oldLeft),(r.height>r.maxHeight||r.height<r.minHeight)&&(r.top=r.oldTop),r.width=Math.min(Math.max(r.width,r.minWidth),r.maxWidth),r.height=Math.min(Math.max(r.height,r.minHeight),r.maxHeight),t.limitCropBox(!1,!0),r.oldLeft=r.left=Math.min(Math.max(r.left,r.minLeft),r.maxLeft),r.oldTop=r.top=Math.min(Math.max(r.top,r.minTop),r.maxTop),e.movable&&e.cropBoxMovable&&b(t.face,"action",r.width===o.width&&r.height===o.height?"move":"all"),d(t.cropBox,s({width:r.width,height:r.height},R({translateX:r.left,translateY:r.top}))),t.cropped&&t.limited&&t.limitCanvas(!0,!0),t.disabled||t.output()},output:function(){var t=this;t.preview(),t.complete&&C(t.element,"crop",t.getData())}},st={initPreview:function(){var t=this,e=t.options.preview,o=B("img"),r=t.crossOrigin,a=r?t.crossOriginUrl:t.url;if(r&&(o.crossOrigin=r),o.src=a,T(t.viewBox,o),t.image2=o,e){var i=e.querySelector?[e]:document.querySelectorAll(e);t.previews=i,c(i,function(t){var e=B("img");b(t,"preview",{width:t.offsetWidth,height:t.offsetHeight,html:t.innerHTML}),r&&(e.crossOrigin=r),e.src=a,e.style.cssText='display:block;width:100%;height:auto;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;image-orientation:0deg!important;"',L(t),T(t,e)})}},resetPreview:function(){c(this.previews,function(t){var e=v(t,"preview");d(t,{width:e.width,height:e.height}),t.innerHTML=e.html,w(t,"preview")})},preview:function(){var t=this,e=t.imageData,o=t.canvasData,r=t.cropBoxData,a=r.width,i=r.height,n=e.width,p=e.height,l=r.left-o.left-e.left,h=r.top-o.top-e.top;t.cropped&&!t.disabled&&(d(t.image2,s({width:n,height:p},R(s({translateX:-l,translateY:-h},e)))),c(t.previews,function(t){var o=v(t,"preview"),r=o.width,c=o.height,u=r,f=c,m=1;a&&(m=r/a,f=i*m),i&&f>c&&(m=c/i,u=a*m,f=c),d(t,{width:u,height:f}),d(k(t,"img")[0],s({width:n*m,height:p*m},R(s({translateX:-l*m,translateY:-h*m},e))))}))}},lt="undefined"!=typeof window?window.PointerEvent:null,dt=lt?"pointerdown":"touchstart mousedown",ht=lt?"pointermove":"touchmove mousemove",ut=lt?" pointerup pointercancel":"touchend touchcancel mouseup",ft={bind:function(){var t=this,e=t.options,o=t.element,r=t.cropper;i(e.cropstart)&&y(o,"cropstart",e.cropstart),i(e.cropmove)&&y(o,"cropmove",e.cropmove),i(e.cropend)&&y(o,"cropend",e.cropend),i(e.crop)&&y(o,"crop",e.crop),i(e.zoom)&&y(o,"zoom",e.zoom),y(r,dt,t.onCropStart=l(t.cropStart,t)),e.zoomable&&e.zoomOnWheel&&y(r,"wheel mousewheel DOMMouseScroll",t.onWheel=l(t.wheel,t)),e.toggleDragModeOnDblclick&&y(r,"dblclick",t.onDblclick=l(t.dblclick,t)),y(document,ht,t.onCropMove=l(t.cropMove,t)),y(document,ut,t.onCropEnd=l(t.cropEnd,t)),e.responsive&&y(window,"resize",t.onResize=l(t.resize,t))},unbind:function(){var t=this,e=t.options,o=t.element,r=t.cropper;i(e.cropstart)&&x(o,"cropstart",e.cropstart),i(e.cropmove)&&x(o,"cropmove",e.cropmove),i(e.cropend)&&x(o,"cropend",e.cropend),i(e.crop)&&x(o,"crop",e.crop),i(e.zoom)&&x(o,"zoom",e.zoom),x(r,dt,t.onCropStart),e.zoomable&&e.zoomOnWheel&&x(r,"wheel mousewheel DOMMouseScroll",t.onWheel),e.toggleDragModeOnDblclick&&x(r,"dblclick",t.onDblclick),x(document,ht,t.onCropMove),x(document,ut,t.onCropEnd),e.responsive&&x(window,"resize",t.onResize)}},mt=/^(e|w|s|n|se|sw|ne|nw|all|crop|move|zoom)$/,gt={resize:function(){var t=this,e=t.options,o=t.container,r=t.containerData,a=Number(e.minContainerWidth)||200,i=Number(e.minContainerHeight)||100;if(!t.disabled&&r.width!==a&&r.height!==i){var n=o.offsetWidth/r.width;1===n&&o.offsetHeight===r.height||function(){var o=void 0,r=void 0;e.restore&&(o=t.getCanvasData(),r=t.getCropBoxData()),t.render(),e.restore&&(t.setCanvasData(c(o,function(t,e){o[e]=t*n})),t.setCropBoxData(c(r,function(t,e){r[e]=t*n})))}()}},dblclick:function(){var t=this;t.disabled||"none"===t.options.dragMode||t.setDragMode(h(t.dragBox,"cropper-crop")?"move":"crop")},wheel:function(t){var e=this,o=M(t),r=Number(e.options.wheelZoomRatio)||.1,a=1;e.disabled||(o.preventDefault(),e.wheeling||(e.wheeling=!0,setTimeout(function(){e.wheeling=!1},50),o.deltaY?a=o.deltaY>0?1:-1:o.wheelDelta?a=-o.wheelDelta/120:o.detail&&(a=o.detail>0?1:-1),e.zoom(-a*r,o)))},cropStart:function(t){var e=this;if(!e.disabled){var o=e.options,r=e.pointers,a=M(t),i=void 0;a.changedTouches?c(a.changedTouches,function(t){r[t.identifier]=Y(t)}):r[a.pointerId||0]=Y(a),i=Object.keys(r).length>1&&o.zoomable&&o.zoomOnTouch?"zoom":v(a.target,"action"),mt.test(i)&&!1!==C(e.element,"cropstart",{originalEvent:a,action:i})&&(a.preventDefault(),e.action=i,e.cropping=!1,"crop"===i&&(e.cropping=!0,u(e.dragBox,"cropper-modal")))}},cropMove:function(t){var e=this,o=e.action;if(!e.disabled&&o){var r=e.pointers,a=M(t);a.preventDefault(),!1!==C(e.element,"cropmove",{originalEvent:a,action:o})&&(a.changedTouches?c(a.changedTouches,function(t){s(r[t.identifier],Y(t,!0))}):s(r[a.pointerId||0],Y(a,!0)),e.change(a))}},cropEnd:function(t){var e=this;if(!e.disabled){var o=e.action,r=e.pointers,a=M(t);a.changedTouches?c(a.changedTouches,function(t){delete r[t.identifier]}):delete r[a.pointerId||0],o&&(a.preventDefault(),Object.keys(r).length||(e.action=""),e.cropping&&(e.cropping=!1,m(e.dragBox,"cropper-modal",e.cropped&&this.options.modal)),C(e.element,"cropend",{originalEvent:a,action:o}))}}},vt={change:function(t){var e=this,o=e.options,r=e.containerData,a=e.canvasData,i=e.cropBoxData,n=o.aspectRatio,p=e.action,s=i.width,l=i.height,d=i.left,h=i.top,u=d+s,m=h+l,g=0,v=0,b=r.width,w=r.height,x=!0,y=void 0;!n&&t.shiftKey&&(n=s&&l?s/l:1),e.limited&&(g=i.minLeft,v=i.minTop,b=g+Math.min(r.width,a.width,a.left+a.width),w=v+Math.min(r.height,a.height,a.top+a.height));var C=e.pointers,M=C[Object.keys(C)[0]],k={x:M.endX-M.startX,y:M.endY-M.startY};switch(n&&(k.X=k.y*n,k.Y=k.x/n),p){case"all":d+=k.x,h+=k.y;break;case"e":if(k.x>=0&&(u>=b||n&&(h<=v||m>=w))){x=!1;break}s+=k.x,n&&(l=s/n,h-=k.Y/2),s<0&&(p="w",s=0);break;case"n":if(k.y<=0&&(h<=v||n&&(d<=g||u>=b))){x=!1;break}l-=k.y,h+=k.y,n&&(s=l*n,d+=k.X/2),l<0&&(p="s",l=0);break;case"w":if(k.x<=0&&(d<=g||n&&(h<=v||m>=w))){x=!1;break}s-=k.x,d+=k.x,n&&(l=s/n,h+=k.Y/2),s<0&&(p="e",s=0);break;case"s":if(k.y>=0&&(m>=w||n&&(d<=g||u>=b))){x=!1;break}l+=k.y,n&&(s=l*n,d-=k.X/2),l<0&&(p="n",l=0);break;case"ne":if(n){if(k.y<=0&&(h<=v||u>=b)){x=!1;break}l-=k.y,h+=k.y,s=l*n}else k.x>=0?u<b?s+=k.x:k.y<=0&&h<=v&&(x=!1):s+=k.x,k.y<=0?h>v&&(l-=k.y,h+=k.y):(l-=k.y,h+=k.y);s<0&&l<0?(p="sw",l=0,s=0):s<0?(p="nw",s=0):l<0&&(p="se",l=0);break;case"nw":if(n){if(k.y<=0&&(h<=v||d<=g)){x=!1;break}l-=k.y,h+=k.y,s=l*n,d+=k.X}else k.x<=0?d>g?(s-=k.x,d+=k.x):k.y<=0&&h<=v&&(x=!1):(s-=k.x,d+=k.x),k.y<=0?h>v&&(l-=k.y,h+=k.y):(l-=k.y,h+=k.y);s<0&&l<0?(p="se",l=0,s=0):s<0?(p="ne",s=0):l<0&&(p="sw",l=0);break;case"sw":if(n){if(k.x<=0&&(d<=g||m>=w)){x=!1;break}s-=k.x,d+=k.x,l=s/n}else k.x<=0?d>g?(s-=k.x,d+=k.x):k.y>=0&&m>=w&&(x=!1):(s-=k.x,d+=k.x),k.y>=0?m<w&&(l+=k.y):l+=k.y;s<0&&l<0?(p="ne",l=0,s=0):s<0?(p="se",s=0):l<0&&(p="nw",l=0);break;case"se":if(n){if(k.x>=0&&(u>=b||m>=w)){x=!1;break}s+=k.x,l=s/n}else k.x>=0?u<b?s+=k.x:k.y>=0&&m>=w&&(x=!1):s+=k.x,k.y>=0?m<w&&(l+=k.y):l+=k.y;s<0&&l<0?(p="nw",l=0,s=0):s<0?(p="sw",s=0):l<0&&(p="ne",l=0);break;case"move":e.move(k.x,k.y),x=!1;break;case"zoom":e.zoom(I(C),t),x=!1;break;case"crop":if(!k.x||!k.y){x=!1;break}y=D(e.cropper),d=M.startX-y.left,h=M.startY-y.top,s=i.minWidth,l=i.minHeight,k.x>0?p=k.y>0?"se":"ne":k.x<0&&(d-=s,p=k.y>0?"sw":"nw"),k.y<0&&(h-=l),e.cropped||(f(e.cropBox,"cropper-hidden"),e.cropped=!0,e.limited&&e.limitCropBox(!0,!0))}x&&(i.width=s,i.height=l,i.left=d,i.top=h,e.action=p,e.renderCropBox()),c(C,function(t){t.startX=t.endX,t.startY=t.endY})}},bt={crop:function(){var t=this;return t.ready&&!t.disabled&&(t.cropped||(t.cropped=!0,t.limitCropBox(!0,!0),t.options.modal&&u(t.dragBox,"cropper-modal"),f(t.cropBox,"cropper-hidden")),t.setCropBoxData(t.initialCropBoxData)),t},reset:function(){var t=this;return t.ready&&!t.disabled&&(t.imageData=s({},t.initialImageData),t.canvasData=s({},t.initialCanvasData),t.cropBoxData=s({},t.initialCropBoxData),t.renderCanvas(),t.cropped&&t.renderCropBox()),t},clear:function(){var t=this;return t.cropped&&!t.disabled&&(s(t.cropBoxData,{left:0,top:0,width:0,height:0}),t.cropped=!1,t.renderCropBox(),t.limitCanvas(),t.renderCanvas(),f(t.dragBox,"cropper-modal"),u(t.cropBox,"cropper-hidden")),t},replace:function(t,e){var o=this;return!o.disabled&&t&&(o.isImg&&(o.element.src=t),e?(o.url=t,o.image.src=t,o.ready&&(o.image2.src=t,c(o.previews,function(e){k(e,"img")[0].src=t}))):(o.isImg&&(o.replaced=!0),o.options.data=null,o.load(t))),o},enable:function(){var t=this;return t.ready&&(t.disabled=!1,f(t.cropper,"cropper-disabled")),t},disable:function(){var t=this;return t.ready&&(t.disabled=!0,u(t.cropper,"cropper-disabled")),t},destroy:function(){var t=this,e=t.element,o=t.image;return t.loaded?(t.isImg&&t.replaced&&(e.src=t.originalUrl),t.unbuild(),f(e,"cropper-hidden")):t.isImg?x(e,"load",t.onStart):o&&E(o),w(e,"cropper"),t},move:function(t,e){var r=this,a=r.canvasData;return r.moveTo(o(t)?t:a.left+Number(t),o(e)?e:a.top+Number(e))},moveTo:function(t,r){var a=this,i=a.canvasData,n=!1;return o(r)&&(r=t),t=Number(t),r=Number(r),a.ready&&!a.disabled&&a.options.movable&&(e(t)&&(i.left=t,n=!0),e(r)&&(i.top=r,n=!0),n&&a.renderCanvas(!0)),a},zoom:function(t,e){var o=this,r=o.canvasData;return t=Number(t),t=t<0?1/(1-t):1+t,o.zoomTo(r.width*t/r.naturalWidth,e)},zoomTo:function(t,e){var o=this,r=o.options,a=o.canvasData,i=a.width,n=a.height,p=a.naturalWidth,c=a.naturalHeight;if((t=Number(t))>=0&&o.ready&&!o.disabled&&r.zoomable){var s=p*t,l=c*t;if(!1===C(o.element,"zoom",{originalEvent:e,oldRatio:i/p,ratio:s/p}))return o;if(e){var d=o.pointers,h=D(o.cropper),u=d&&Object.keys(d).length?P(d):{pageX:e.pageX,pageY:e.pageY};a.left-=(s-i)*((u.pageX-h.left-a.left)/i),a.top-=(l-n)*((u.pageY-h.top-a.top)/n)}else a.left-=(s-i)/2,a.top-=(l-n)/2;a.width=s,a.height=l,o.renderCanvas(!0)}return o},rotate:function(t){var e=this;return e.rotateTo((e.imageData.rotate||0)+Number(t))},rotateTo:function(t){var o=this;return t=Number(t),e(t)&&o.ready&&!o.disabled&&o.options.rotatable&&(o.imageData.rotate=t%360,o.rotated=!0,o.renderCanvas(!0)),o},scale:function(t,r){var a=this,i=a.imageData,n=!1;return o(r)&&(r=t),t=Number(t),r=Number(r),a.ready&&!a.disabled&&a.options.scalable&&(e(t)&&(i.scaleX=t,n=!0),e(r)&&(i.scaleY=r,n=!0),n&&a.renderImage(!0)),a},scaleX:function(t){var o=this,r=o.imageData.scaleY;return o.scale(t,e(r)?r:1)},scaleY:function(t){var o=this,r=o.imageData.scaleX;return o.scale(e(r)?r:1,t)},getData:function(t){var e=this,o=e.options,r=e.imageData,a=e.canvasData,i=e.cropBoxData,n=void 0,p=void 0;return e.ready&&e.cropped?(p={x:i.left-a.left,y:i.top-a.top,width:i.width,height:i.height},n=r.width/r.naturalWidth,c(p,function(e,o){e/=n,p[o]=t?Math.round(e):e})):p={x:0,y:0,width:0,height:0},o.rotatable&&(p.rotate=r.rotate||0),o.scalable&&(p.scaleX=r.scaleX||1,p.scaleY=r.scaleY||1),p},setData:function(t){var o=this,r=o.options,n=o.imageData,p=o.canvasData,c={},s=void 0,l=void 0,d=void 0;return i(t)&&(t=t.call(o.element)),o.ready&&!o.disabled&&a(t)&&(r.rotatable&&e(t.rotate)&&t.rotate!==n.rotate&&(n.rotate=t.rotate,o.rotated=s=!0),r.scalable&&(e(t.scaleX)&&t.scaleX!==n.scaleX&&(n.scaleX=t.scaleX,l=!0),e(t.scaleY)&&t.scaleY!==n.scaleY&&(n.scaleY=t.scaleY,l=!0)),s?o.renderCanvas():l&&o.renderImage(),d=n.width/n.naturalWidth,e(t.x)&&(c.left=t.x*d+p.left),e(t.y)&&(c.top=t.y*d+p.top),e(t.width)&&(c.width=t.width*d),e(t.height)&&(c.height=t.height*d),o.setCropBoxData(c)),o},getContainerData:function(){var t=this;return t.ready?t.containerData:{}},getImageData:function(){var t=this;return t.loaded?t.imageData:{}},getCanvasData:function(){var t=this,e=t.canvasData,o={};return t.ready&&c(["left","top","width","height","naturalWidth","naturalHeight"],function(t){o[t]=e[t]}),o},setCanvasData:function(t){var o=this,r=o.canvasData,n=r.aspectRatio;return i(t)&&(t=t.call(o.element)),o.ready&&!o.disabled&&a(t)&&(e(t.left)&&(r.left=t.left),e(t.top)&&(r.top=t.top),e(t.width)?(r.width=t.width,r.height=t.width/n):e(t.height)&&(r.height=t.height,r.width=t.height*n),o.renderCanvas(!0)),o},getCropBoxData:function(){var t=this,e=t.cropBoxData,o=void 0;return t.ready&&t.cropped&&(o={left:e.left,top:e.top,width:e.width,height:e.height}),o||{}},setCropBoxData:function(t){var o=this,r=o.cropBoxData,n=o.options.aspectRatio,p=void 0,c=void 0;return i(t)&&(t=t.call(o.element)),o.ready&&o.cropped&&!o.disabled&&a(t)&&(e(t.left)&&(r.left=t.left),e(t.top)&&(r.top=t.top),e(t.width)&&t.width!==r.width&&(p=!0,r.width=t.width),e(t.height)&&t.height!==r.height&&(c=!0,r.height=t.height),n&&(p?r.height=r.width/n:c&&(r.width=r.height*n)),o.renderCropBox()),o},getCroppedCanvas:function(t){var e=this;if(!e.ready||!window.HTMLCanvasElement)return null;if(a(t)||(t={}),!e.cropped)return z(e.image,e.imageData,t);var o=e.getData(),r=o.width,i=o.height,n=r/i,p=void 0,c=void 0,s=void 0;a(t)&&(p=t.width,c=t.height,p?(c=p/n,s=p/r):c&&(p=c*n,s=c/i));var l=Math.floor(p||r),d=Math.floor(c||i),h=B("canvas"),u=h.getContext("2d");h.width=l,h.height=d,t.fillColor&&(u.fillStyle=t.fillColor,u.fillRect(0,0,l,d));var f=function(){var a=z(e.image,e.imageData,t),n=a.width,p=a.height,c=e.canvasData,l=[a],d=o.x+c.naturalWidth*(Math.abs(o.scaleX||1)-1)/2,h=o.y+c.naturalHeight*(Math.abs(o.scaleY||1)-1)/2,u=void 0,f=void 0,m=void 0,g=void 0,v=void 0,b=void 0;return d<=-r||d>n?d=u=m=v=0:d<=0?(m=-d,d=0,u=v=Math.min(n,r+d)):d<=n&&(m=0,u=v=Math.min(r,n-d)),u<=0||h<=-i||h>p?h=f=g=b=0:h<=0?(g=-h,h=0,f=b=Math.min(p,i+h)):h<=p&&(g=0,f=b=Math.min(i,p-h)),l.push(Math.floor(d),Math.floor(h),Math.floor(u),Math.floor(f)),s&&(m*=s,g*=s,v*=s,b*=s),v>0&&b>0&&l.push(Math.floor(m),Math.floor(g),Math.floor(v),Math.floor(b)),l}();return u.imageSmoothingEnabled=!!t.imageSmoothingEnabled,t.imageSmoothingQuality&&(u.imageSmoothingQuality=t.imageSmoothingQuality),u.drawImage.apply(u,$(f)),h},setAspectRatio:function(t){var e=this,r=e.options;return e.disabled||o(t)||(r.aspectRatio=Math.max(0,t)||NaN,e.ready&&(e.initCropBox(),e.cropped&&e.renderCropBox())),e},setDragMode:function(t){var e=this,o=e.options,r=e.dragBox,a=e.face,i=void 0,n=void 0;return e.loaded&&!e.disabled&&(i="crop"===t,n=o.movable&&"move"===t,t=i||n?t:"none",b(r,"action",t),m(r,"cropper-crop",i),m(r,"cropper-move",n),o.cropBoxMovable||(b(a,"action",t),m(a,"cropper-crop",i),m(a,"cropper-move",n))),e}},wt=/^data:/,xt=/^data:image\/jpeg;base64,/,yt=void 0,Ct=function(){function t(e,o){q(this,t);var r=this;r.element=e,r.options=s({},F,a(o)&&o),r.loaded=!1,r.ready=!1,r.complete=!1,r.rotated=!1,r.cropped=!1,r.disabled=!1,r.replaced=!1,r.limited=!1,r.wheeling=!1,r.isImg=!1,r.originalUrl="",r.canvasData=null,r.cropBoxData=null,r.previews=null,r.pointers={},r.init()}return Q(t,[{key:"init",value:function(){var t=this,e=t.element,o=e.tagName.toLowerCase(),r=void 0;if(!v(e,"cropper")){if(b(e,"cropper",t),"img"===o){if(t.isImg=!0,t.originalUrl=r=e.getAttribute("src"),!r)return;r=e.src}else"canvas"===o&&window.HTMLCanvasElement&&(r=e.toDataURL());t.load(r)}}},{key:"load",value:function(t){var e=this,o=e.options,r=e.element;if(t){if(e.url=t,e.imageData={},!o.checkOrientation||!window.ArrayBuffer)return void e.clone();if(wt.test(t))return void(xt.test(t)?e.read(U(t)):e.clone());var a=new XMLHttpRequest;a.onerror=a.onabort=function(){e.clone()},a.onload=function(){e.read(a.response)},o.checkCrossOrigin&&O(t)&&r.crossOrigin&&(t=S(t)),a.open("get",t),a.responseType="arraybuffer",a.withCredentials="use-credentials"===r.crossOrigin,a.send()}}},{key:"read",value:function(t){var e=this,o=e.options,r=W(t),a=e.imageData,i=0,n=1,p=1;if(r>1)switch(e.url=j(t),r){case 2:n=-1;break;case 3:i=-180;break;case 4:p=-1;break;case 5:i=90,p=-1;break;case 6:i=90;break;case 7:i=90,n=-1;break;case 8:i=-90}o.rotatable&&(a.rotate=i),o.scalable&&(a.scaleX=n,a.scaleY=p),e.clone()}},{key:"clone",value:function(){var t=this,e=t.element,o=t.url,r=void 0,a=void 0,i=void 0,n=void 0;t.options.checkCrossOrigin&&O(o)&&(r=e.crossOrigin,r?a=o:(r="anonymous",a=S(o))),t.crossOrigin=r,t.crossOriginUrl=a;var p=B("img");r&&(p.crossOrigin=r),p.src=a||o,t.image=p,t.onStart=i=l(t.start,t),t.onStop=n=l(t.stop,t),t.isImg?e.complete?t.start():y(e,"load",i):(y(p,"load",i),y(p,"error",n),u(p,"cropper-hide"),e.parentNode.insertBefore(p,e.nextSibling))}},{key:"start",value:function(t){var e=this,o=e.isImg?e.element:e.image;t&&(x(o,"load",e.onStart),x(o,"error",e.onStop)),H(o,function(t,o){s(e.imageData,{naturalWidth:t,naturalHeight:o,aspectRatio:t/o}),e.loaded=!0,e.build()})}},{key:"stop",value:function(){var t=this,e=t.image;x(e,"load",t.onStart),x(e,"error",t.onStop),E(e),t.image=null}},{key:"build",value:function(){var t=this,e=t.options,o=t.element,r=t.image,a=void 0,n=void 0,p=void 0,c=void 0,s=void 0,l=void 0;if(t.loaded){t.ready&&t.unbuild();var d=B("div");d.innerHTML='<div class="cropper-container"><div class="cropper-wrap-box"><div class="cropper-canvas"></div></div><div class="cropper-drag-box"></div><div class="cropper-crop-box"><span class="cropper-view-box"></span><span class="cropper-dashed dashed-h"></span><span class="cropper-dashed dashed-v"></span><span class="cropper-center"></span><span class="cropper-face"></span><span class="cropper-line line-e" data-action="e"></span><span class="cropper-line line-n" data-action="n"></span><span class="cropper-line line-w" data-action="w"></span><span class="cropper-line line-s" data-action="s"></span><span class="cropper-point point-e" data-action="e"></span><span class="cropper-point point-n" data-action="n"></span><span class="cropper-point point-w" data-action="w"></span><span class="cropper-point point-s" data-action="s"></span><span class="cropper-point point-ne" data-action="ne"></span><span class="cropper-point point-nw" data-action="nw"></span><span class="cropper-point point-sw" data-action="sw"></span><span class="cropper-point point-se" data-action="se"></span></div></div>',t.container=a=o.parentNode,t.cropper=n=A(d,"cropper-container")[0],t.canvas=p=A(n,"cropper-canvas")[0],t.dragBox=c=A(n,"cropper-drag-box")[0],t.cropBox=s=A(n,"cropper-crop-box")[0],t.viewBox=A(n,"cropper-view-box")[0],t.face=l=A(s,"cropper-face")[0],T(p,r),u(o,"cropper-hidden"),a.insertBefore(n,o.nextSibling),t.isImg||f(r,"cropper-hide"),t.initPreview(),t.bind(),e.aspectRatio=Math.max(0,e.aspectRatio)||NaN,e.viewMode=Math.max(0,Math.min(3,Math.round(e.viewMode)))||0,t.cropped=e.autoCrop,e.autoCrop?e.modal&&u(c,"cropper-modal"):u(s,"cropper-hidden"),e.guides||u(A(s,"cropper-dashed"),"cropper-hidden"),e.center||u(A(s,"cropper-center"),"cropper-hidden"),e.background&&u(n,"cropper-bg"),e.highlight||u(l,"cropper-invisible"),e.cropBoxMovable&&(u(l,"cropper-move"),b(l,"action","all")),e.cropBoxResizable||(u(A(s,"cropper-line"),"cropper-hidden"),u(A(s,"cropper-point"),"cropper-hidden")),t.setDragMode(e.dragMode),t.render(),t.ready=!0,t.setData(e.data),t.completing=setTimeout(function(){i(e.ready)&&y(o,"ready",e.ready,!0),C(o,"ready"),C(o,"crop",t.getData()),t.complete=!0},0)}}},{key:"unbuild",value:function(){var t=this;t.ready&&(t.complete||clearTimeout(t.completing),t.ready=!1,t.complete=!1,t.initialImageData=null,t.initialCanvasData=null,t.initialCropBoxData=null,t.containerData=null,t.canvasData=null,t.cropBoxData=null,t.unbind(),t.resetPreview(),t.previews=null,t.viewBox=null,t.cropBox=null,t.dragBox=null,t.canvas=null,t.container=null,E(t.cropper),t.cropper=null)}}],[{key:"noConflict",value:function(){return window.Cropper=yt,t}},{key:"setDefaults",value:function(t){s(F,a(t)&&t)}}]),t}();return s(Ct.prototype,ct),s(Ct.prototype,st),s(Ct.prototype,ft),s(Ct.prototype,gt),s(Ct.prototype,vt),s(Ct.prototype,bt),"undefined"!=typeof window&&(yt=window.Cropper,window.Cropper=Ct),Ct})},function(t,e,o){e=t.exports=o(0)(),e.push([t.i,'/*!\n * Cropper.js v1.0.0-rc.3\n * https://github.com/fengyuanchen/cropperjs\n *\n * Copyright (c) 2017 Fengyuan Chen\n * Released under the MIT license\n *\n * Date: 2017-07-07T12:56:42.462Z\n */.cropper-container{font-size:0;line-height:0;position:relative;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;direction:ltr;-ms-touch-action:none;touch-action:none}.cropper-container img{display:block;min-width:0!important;max-width:none!important;min-height:0!important;max-height:none!important;width:100%;height:100%;image-orientation:0deg}.cropper-canvas,.cropper-crop-box,.cropper-drag-box,.cropper-modal,.cropper-wrap-box{position:absolute;top:0;right:0;bottom:0;left:0}.cropper-wrap-box{overflow:hidden}.cropper-drag-box{opacity:0;background-color:#fff}.cropper-modal{opacity:.5;background-color:#000}.cropper-view-box{display:block;overflow:hidden;width:100%;height:100%;outline:1px solid #39f;outline-color:rgba(51,153,255,.75)}.cropper-dashed{position:absolute;display:block;opacity:.5;border:0 dashed #eee}.cropper-dashed.dashed-h{top:33.33333%;left:0;width:100%;height:33.33333%;border-top-width:1px;border-bottom-width:1px}.cropper-dashed.dashed-v{top:0;left:33.33333%;width:33.33333%;height:100%;border-right-width:1px;border-left-width:1px}.cropper-center{position:absolute;top:50%;left:50%;display:block;width:0;height:0;opacity:.75}.cropper-center:after,.cropper-center:before{position:absolute;display:block;content:" ";background-color:#eee}.cropper-center:before{top:0;left:-3px;width:7px;height:1px}.cropper-center:after{top:-3px;left:0;width:1px;height:7px}.cropper-face,.cropper-line,.cropper-point{position:absolute;display:block;width:100%;height:100%;opacity:.1}.cropper-face{top:0;left:0;background-color:#fff}.cropper-line{background-color:#39f}.cropper-line.line-e{top:0;right:-3px;width:5px;cursor:e-resize}.cropper-line.line-n{top:-3px;left:0;height:5px;cursor:n-resize}.cropper-line.line-w{top:0;left:-3px;width:5px;cursor:w-resize}.cropper-line.line-s{bottom:-3px;left:0;height:5px;cursor:s-resize}.cropper-point{width:5px;height:5px;opacity:.75;background-color:#39f}.cropper-point.point-e{top:50%;right:-3px;margin-top:-3px;cursor:e-resize}.cropper-point.point-n{top:-3px;left:50%;margin-left:-3px;cursor:n-resize}.cropper-point.point-w{top:50%;left:-3px;margin-top:-3px;cursor:w-resize}.cropper-point.point-s{bottom:-3px;left:50%;margin-left:-3px;cursor:s-resize}.cropper-point.point-ne{top:-3px;right:-3px;cursor:ne-resize}.cropper-point.point-nw{top:-3px;left:-3px;cursor:nw-resize}.cropper-point.point-sw{bottom:-3px;left:-3px;cursor:sw-resize}.cropper-point.point-se{right:-3px;bottom:-3px;width:20px;height:20px;cursor:se-resize;opacity:1}@media (min-width:768px){.cropper-point.point-se{width:15px;height:15px}}@media (min-width:992px){.cropper-point.point-se{width:10px;height:10px}}@media (min-width:1200px){.cropper-point.point-se{width:5px;height:5px;opacity:.75}}.cropper-point.point-se:before{position:absolute;right:-50%;bottom:-50%;display:block;width:200%;height:200%;content:" ";opacity:0;background-color:#39f}.cropper-invisible{opacity:0}.cropper-bg{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAAA3NCSVQICAjb4U/gAAAABlBMVEXMzMz////TjRV2AAAACXBIWXMAAArrAAAK6wGCiw1aAAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAABFJREFUCJlj+M/AgBVhF/0PAH6/D/HkDxOGAAAAAElFTkSuQmCC")}.cropper-hide{position:absolute;display:block;width:0;height:0}.cropper-hidden{display:none!important}.cropper-move{cursor:move}.cropper-crop{cursor:crosshair}.cropper-disabled .cropper-drag-box,.cropper-disabled .cropper-face,.cropper-disabled .cropper-line,.cropper-disabled .cropper-point{cursor:not-allowed}',""])},function(t,e,o){e=t.exports=o(0)(),e.push([t.i,".avatar-cropper{text-align:center;display:flex;align-items:center;justify-content:center;position:fixed;top:0;left:0;right:0;bottom:0;z-index:99999}.avatar-cropper .avatar-cropper-close{float:right;padding:20px;font-size:3rem;color:#fff;font-weight:100;text-shadow:0 1px rgba(40,40,40,.3)}.avatar-cropper .avatar-cropper-mark{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.1)}.avatar-cropper .avatar-cropper-container{background:#fff;z-index:999;box-shadow:1px 1px 5px hsla(0,0%,39%,.14)}.avatar-cropper .avatar-cropper-container .avatar-cropper-image-container{position:relative;max-width:400px;height:300px}.avatar-cropper .avatar-cropper-container img{max-width:100%;height:100%}.avatar-cropper .avatar-cropper-container .avatar-cropper-footer{display:flex;align-items:stretch;align-content:stretch;justify-content:space-between}.avatar-cropper .avatar-cropper-container .avatar-cropper-footer .avatar-cropper-btn{width:50%;padding:15px 0;cursor:pointer;border:none;background:transparent;outline:none}.avatar-cropper .avatar-cropper-container .avatar-cropper-footer .avatar-cropper-btn:hover{background-color:#2aabd2;color:#fff}",""])},function(t,e){t.exports=function(t){var e="undefined"!=typeof window&&window.location;if(!e)throw new Error("fixUrls requires window.location");if(!t||"string"!=typeof t)return t;var o=e.protocol+"//"+e.host,r=o+e.pathname.replace(/\/[^\/]*$/,"/");return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,function(t,e){var a=e.trim().replace(/^"(.*)"$/,function(t,e){return e}).replace(/^'(.*)'$/,function(t,e){return e});if(/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(a))return t;var i;return i=0===a.indexOf("//")?a:0===a.indexOf("/")?o+a:r+a.replace(/^\.\//,""),"url("+JSON.stringify(i)+")"})}},function(t,e,o){var r=o(6);"string"==typeof r&&(r=[[t.i,r,""]]);o(1)(r,{});r.locals&&(t.exports=r.locals)},function(t,e,o){var r=o(7);"string"==typeof r&&(r=[[t.i,r,""]]);o(1)(r,{});r.locals&&(t.exports=r.locals)},function(t,e){t.exports=function(t,e,o,r){var a,i=t=t||{},n=typeof t.default;"object"!==n&&"function"!==n||(a=t,i=t.default);var p="function"==typeof i?i.options:i;if(e&&(p.render=e.render,p.staticRenderFns=e.staticRenderFns),o&&(p._scopeId=o),r){var c=Object.create(p.computed||null);Object.keys(r).forEach(function(t){var e=r[t];c[t]=function(){return e}}),p.computed=c}return{esModule:a,exports:i,options:p}}},function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,o=t._self._c||e;return t.dataUrl?o("div",{staticClass:"avatar-cropper"},[o("div",{staticClass:"avatar-cropper-mark"},[o("a",{staticClass:"avatar-cropper-close",attrs:{href:"javascript:;"},on:{click:t.destroy}},[t._v("×")])]),t._v(" "),o("div",{staticClass:"avatar-cropper-container"},[o("div",{staticClass:"avatar-cropper-image-container"},[o("img",{attrs:{src:t.dataUrl,alt:""},on:{load:function(e){e.stopPropagation(),t.createCropper(e)}}})]),t._v(" "),o("div",{staticClass:"avatar-cropper-footer"},[o("button",{staticClass:"avatar-cropper-btn",on:{click:t.destroy}},[t._v("取消")]),t._v(" "),o("button",{staticClass:"avatar-cropper-btn",on:{click:t.submit}},[t._v("提交")])])])]):t._e()},staticRenderFns:[]}}])});

/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-c617b260\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/user/Setting.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "user_setting"
  }, [_c('Tabs', {
    class: {
      'bl_tab_color': (_vm.userInfo && _vm.userInfo.camp && _vm.userInfo.camp === 2) || (!_vm.userInfo && _vm.choice_cmap === '2')
    },
    attrs: {
      "value": "1"
    }
  }, [_c('Tab-pane', {
    attrs: {
      "label": "基本信息",
      "name": "1"
    }
  }, [_c('Form', {
    ref: "formValidate",
    staticStyle: {
      "width": "500px"
    },
    attrs: {
      "model": _vm.formItem,
      "label-width": 80,
      "rules": _vm.ruleValidate
    }
  }, [_c('Form-item', {
    attrs: {
      "label": "头像"
    }
  }, [_c('img', {
    staticClass: "img-circle",
    attrs: {
      "src": _vm.formItem.avatar,
      "id": "set-avatar"
    }
  })]), _vm._v(" "), _c('Form-item', {
    attrs: {
      "label": "昵称",
      "prop": "name"
    }
  }, [_c('Input', {
    attrs: {
      "placeholder": "请输入"
    },
    model: {
      value: (_vm.formItem.name),
      callback: function($$v) {
        _vm.formItem.name = $$v
      },
      expression: "formItem.name"
    }
  })], 1), _vm._v(" "), _c('Form-item', {
    attrs: {
      "label": "阵营"
    }
  }, [_c('Select', {
    attrs: {
      "disabled": !_vm.is_camp,
      "placeholder": "请选择阵营"
    },
    model: {
      value: (_vm.formItem.camp),
      callback: function($$v) {
        _vm.formItem.camp = $$v
      },
      expression: "formItem.camp"
    }
  }, [_c('Option', {
    attrs: {
      "value": "1"
    }
  }, [_vm._v("联盟")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "2"
    }
  }, [_vm._v("部落")])], 1), _vm._v(" "), (!_vm.is_camp) ? _c('p', {
    staticClass: "normal_font",
    class: {
      'bl_font_color': (_vm.userInfo && _vm.userInfo.camp && _vm.userInfo.camp === 2) || (!_vm.userInfo && _vm.choice_cmap === '2')
    }
  }, [_vm._v("30天内不能再次修改阵营")]) : _vm._e()], 1), _vm._v(" "), _c('Form-item', {
    attrs: {
      "label": "介绍",
      "prop": "info"
    }
  }, [_c('Input', {
    attrs: {
      "type": "textarea",
      "autosize": {
        minRows: 2,
        maxRows: 5
      },
      "placeholder": "请输入..."
    },
    model: {
      value: (_vm.formItem.info),
      callback: function($$v) {
        _vm.formItem.info = $$v
      },
      expression: "formItem.info"
    }
  })], 1), _vm._v(" "), _c('Form-item', [_c('Button', {
    staticClass: "pull-right",
    class: {
      'bl_button_color': (_vm.userInfo && _vm.userInfo.camp && _vm.userInfo.camp === 2) || (!_vm.userInfo && _vm.choice_cmap === '2')
    },
    attrs: {
      "type": "primary"
    },
    on: {
      "click": function($event) {
        _vm.handleSubmit('formValidate')
      }
    }
  }, [_vm._v("提交")])], 1)], 1), _vm._v(" "), _c('avatar-cropper', {
    attrs: {
      "upload-headers": {
        "X-CSRF-TOKEN": _vm.csrfToken
      },
      "trigger": "#set-avatar",
      "upload-url": "/user/upload_avatar",
      "uploaded": _vm.updateUserAvatar
    }
  })], 1), _vm._v(" "), _c('Tab-pane', {
    attrs: {
      "label": "密码修改",
      "name": "2"
    }
  }, [_c('rest-password')], 1), _vm._v(" "), _c('Tab-pane', {
    attrs: {
      "label": "邮箱修改",
      "name": "3"
    }
  }, [_vm._v("标签三的内容")]), _vm._v(" "), _c('Tab-pane', {
    attrs: {
      "label": "手机号修改",
      "name": "4"
    }
  }, [_vm._v("标签三的内容")])], 1)], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-c617b260", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-f1d4bd02\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/common/restPassword.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "width": "500px"
    }
  }, [_c('Form', {
    ref: "formPassword",
    attrs: {
      "model": _vm.formPassword,
      "rules": _vm.ruleFormPassword,
      "label-position": "left",
      "label-width": 100
    }
  }, [_c('Form-item', {
    attrs: {
      "label": "原始密码",
      "prop": "old_password"
    }
  }, [_c('Input', {
    attrs: {
      "type": "password"
    },
    model: {
      value: (_vm.formPassword.old_password),
      callback: function($$v) {
        _vm.formPassword.old_password = $$v
      },
      expression: "formPassword.old_password"
    }
  })], 1), _vm._v(" "), _c('Form-item', {
    attrs: {
      "label": "新的密码",
      "prop": "password"
    }
  }, [_c('Input', {
    attrs: {
      "type": "password"
    },
    model: {
      value: (_vm.formPassword.password),
      callback: function($$v) {
        _vm.formPassword.password = $$v
      },
      expression: "formPassword.password"
    }
  })], 1), _vm._v(" "), _c('Form-item', {
    attrs: {
      "label": "确认密码",
      "prop": "passwordCheck"
    }
  }, [_c('Input', {
    attrs: {
      "type": "password"
    },
    nativeOn: {
      "keyup": function($event) {
        if (!('button' in $event) && $event.keyCode !== 13) { return null; }
        _vm.rest_password('formPassword')
      }
    },
    model: {
      value: (_vm.formPassword.passwordCheck),
      callback: function($$v) {
        _vm.formPassword.passwordCheck = $$v
      },
      expression: "formPassword.passwordCheck"
    }
  })], 1)], 1), _vm._v(" "), _c('Button', {
    staticClass: "pull-right",
    class: {
      'bl_button_color': (_vm.userInfo && _vm.userInfo.camp && _vm.userInfo.camp === 2) || (!_vm.userInfo && _vm.choice_cmap === '2')
    },
    attrs: {
      "type": "primary",
      "loading": _vm.loading
    },
    on: {
      "click": function($event) {
        _vm.rest_password('formPassword')
      }
    }
  }, [(!_vm.loading) ? _c('span', [_vm._v("提交")]) : _c('span', [_vm._v("Loading...")])])], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-f1d4bd02", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-c617b260\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Setting.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-c617b260\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Setting.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("59272441", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-c617b260\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Setting.vue", function() {
     var newContent = require("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-c617b260\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Setting.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-f1d4bd02\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/restPassword.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-f1d4bd02\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/restPassword.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("e77bb560", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-f1d4bd02\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./restPassword.vue", function() {
     var newContent = require("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-f1d4bd02\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./restPassword.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/common/restPassword.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-f1d4bd02\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/restPassword.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/common/restPassword.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-f1d4bd02\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/common/restPassword.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-f1d4bd02",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\common\\restPassword.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] restPassword.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-f1d4bd02", Component.options)
  } else {
    hotAPI.reload("data-v-f1d4bd02", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ "./resources/assets/js/components/user/Setting.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-c617b260\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Setting.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/user/Setting.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-c617b260\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/user/Setting.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-c617b260",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\user\\Setting.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Setting.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-c617b260", Component.options)
  } else {
    hotAPI.reload("data-v-c617b260", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});