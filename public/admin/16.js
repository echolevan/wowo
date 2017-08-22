webpackJsonp([16],{

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/user/Info.vue":
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
            rest_time: 60,
            is_dis: false,
            lv: {}
        };
    },
    mounted: function mounted() {
        this.get_lv();
    },

    computed: Object(__WEBPACK_IMPORTED_MODULE_0_vuex__["b" /* mapState */])(['userInfo', 'choice_cmap']),
    methods: {
        send_mail: function send_mail() {
            var _this = this;

            this.rest_time = 60;
            this.is_dis = true;
            this.sub_time();
            axios.get('user/send_mail').then(function (res) {
                if (res.data.sta === 0) {
                    _this.$Message.error(res.data.msg);
                    if (res.data.timeOut) {
                        _this.rest_time = res.data.timeOut;
                    }
                }
            });
        },
        sub_time: function sub_time() {
            var _this2 = this;

            setInterval(function () {
                _this2.rest_time--;
                _this2.check_is_out();
            }, 1000);
        },
        check_is_out: function check_is_out() {
            if (this.rest_time <= 0) {
                this.is_dis = false;
            }
        },
        get_lv: function get_lv() {
            var _this3 = this;

            axios.get('user/lv').then(function (res) {
                _this3.lv = res.data.info;
            });
        }
    }
});

/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-18a10b5e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Info.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "\n.user_info .info_list li[data-v-18a10b5e] {\n  width: 100%;\n  padding: 10px 0;\n}\n.user_info .info_list li .title[data-v-18a10b5e] {\n  font-size: 16px;\n  width: 8%;\n  float: left;\n  font-weight: bold;\n}\n.user_info .info_list li .val[data-v-18a10b5e] {\n  width: 92%;\n  float: left;\n  font-size: 12px;\n  word-wrap: break-word;\n}\n.user_info .user_info_card[data-v-18a10b5e] {\n  position: relative;\n  width: 960px;\n  height: 540px;\n  border: 1px solid #ddd;\n}\n.user_info .zd[data-v-18a10b5e] {\n  width: 960px;\n  height: 540px;\n  position: absolute;\n  z-index: 1;\n  filter: blur(10px);\n  background: rgba(88,87,86,0.3);\n}\n.user_info img[data-v-18a10b5e] {\n  width: 960px;\n  height: 540px;\n}\n.user_info .info[data-v-18a10b5e] {\n  position: absolute;\n  left: 300px;\n  top: 100px;\n  font-size: 30px;\n  color: #266ec1;\n  z-index: 2;\n}\n.user_info .bac_img_lm[data-v-18a10b5e] {\n  position: absolute;\n  right: 0;\n  top: 0;\n  width: 300px;\n  height: 540px;\n  background: url(\"/images/c1_hover.png\") no-repeat center;\n  background-size: contain;\n}\n.user_info .bac_img_bl[data-v-18a10b5e] {\n  position: absolute;\n  right: 0;\n  top: 0;\n  width: 300px;\n  height: 540px;\n  background: url(\"/images/c2_hover.png\") no-repeat center;\n  background-size: contain;\n}\n.user_info .user_avatar[data-v-18a10b5e] {\n  position: absolute;\n  right: 80px;\n  top: 200px;\n  z-index: 2;\n}\n.user_info .user_avatar img[data-v-18a10b5e] {\n  width: 150px;\n  height: 150px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-18a10b5e\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/user/Info.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "user_info"
  }, [_c('ul', {
    staticClass: "info_list"
  }, [_c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("姓名")]), _c('span', {
    staticClass: "val"
  }, [_vm._v(_vm._s(_vm.userInfo.name))])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "clear": "both"
    }
  }), _vm._v(" "), _c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("等级")]), _vm._v(" "), (_vm.lv) ? _c('Poptip', {
    attrs: {
      "trigger": "hover",
      "content": ("充值金币将会赠送" + (_vm.lv.giving) + "%的金币"),
      "placement": "bottom"
    }
  }, [_c('span', {
    staticClass: "hover_hand"
  }, [_vm._v(_vm._s(_vm.lv.name))])]) : _vm._e()], 1), _vm._v(" "), _c('div', {
    staticStyle: {
      "clear": "both"
    }
  }), _vm._v(" "), _c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("阵营")]), (_vm.userInfo.camp === 1) ? _c('span', {
    staticClass: "val"
  }, [_vm._v("联盟")]) : _c('span', {
    staticClass: "val"
  }, [_vm._v("部落")])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "clear": "both"
    }
  }), _vm._v(" "), _c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("简介")]), (_vm.userInfo.info) ? _c('span', {
    staticClass: "val"
  }, [_vm._v(_vm._s(_vm.userInfo.info))]) : (_vm.userInfo.camp === 1) ? _c('span', {
    staticClass: "val"
  }, [_vm._v("为了联盟")]) : _c('span', {
    staticClass: "val"
  }, [_vm._v("为了部落")])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "clear": "both"
    }
  }), _vm._v(" "), _c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("金币")]), _c('span', {
    staticClass: "val"
  }, [_vm._v(_vm._s(_vm.userInfo.wwb))])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "clear": "both"
    }
  }), _vm._v(" "), _c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("邮箱")]), _c('span', {
    staticClass: "val"
  }, [(_vm.userInfo.is_active === 1) ? _c('Button', {
    attrs: {
      "type": "success",
      "size": "small"
    }
  }, [_c('Tooltip', {
    attrs: {
      "content": "邮箱已经激活"
    }
  }, [_vm._v("\n                    " + _vm._s(_vm.userInfo.email) + "\n                ")])], 1) : _c('Button', {
    attrs: {
      "type": "warning",
      "disabled": _vm.is_dis,
      "size": "small"
    },
    on: {
      "click": _vm.send_mail
    }
  }, [_c('Tooltip', [_c('div', {
    slot: "content"
  }, [_c('p', [_vm._v("邮箱还未激活")]), _vm._v(" "), _c('p', [_c('i', [_vm._v("点击重新发送邮件")])])]), _vm._v(" "), (!_vm.is_dis) ? _c('span', [_vm._v(_vm._s(_vm.userInfo.email))]) : _c('span', [_vm._v("邮件已经发送，" + _vm._s(_vm.rest_time) + " S后可以再次发送")])])], 1)], 1)]), _vm._v(" "), _c('div', {
    staticStyle: {
      "clear": "both"
    }
  }), _vm._v(" "), _c('li', [_c('span', {
    staticClass: "title"
  }, [_vm._v("手机号")]), _vm._v(" "), (_vm.userInfo.tel === 0) ? _c('span', {
    staticClass: "val"
  }, [_vm._v("还没有绑定手机号，点击立即绑定")]) : _c('span', {
    staticClass: "val"
  }, [_vm._v(_vm._s(_vm.userInfo.tel))])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-18a10b5e", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-18a10b5e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Info.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-18a10b5e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Info.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("0ee968eb", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-18a10b5e\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Info.vue", function() {
     var newContent = require("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-18a10b5e\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Info.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/user/Info.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-18a10b5e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Info.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/user/Info.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-18a10b5e\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/user/Info.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-18a10b5e",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\user\\Info.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Info.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-18a10b5e", Component.options)
  } else {
    hotAPI.reload("data-v-18a10b5e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});