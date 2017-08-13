webpackJsonp([20],{

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/user/Upload.vue":
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


/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            page: 1,
            size: 10,
            count: 0,
            orders: []
        };
    },

    computed: Object(__WEBPACK_IMPORTED_MODULE_0_vuex__["b" /* mapState */])(['userInfo', 'choice_cmap']),
    mounted: function mounted() {
        this.get_orders();
    },

    methods: {
        // 更改分页
        change_page: function change_page(p) {
            this.this_page = p;
            this.get_orders();
        },
        get_orders: function get_orders() {
            var _this = this;

            axios.post('user/orders/upload/' + this.page + '/' + this.size).then(function (res) {
                _this.count = res.data.count;
                _this.orders = res.data.res;
            });
        },
        show: function show() {
            $(".tr_1").toggle('300');
        }
    }
});

/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5493259e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Upload.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "\n.user_orders .tool a[data-v-5493259e] {\n  padding: 0 15px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-5493259e\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/user/Upload.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "user_orders"
  }, [(_vm.orders.length > 0) ? _c('table', {
    staticClass: "table table-bordered"
  }, [_vm._m(0), _vm._v(" "), _c('tbody', _vm._l((_vm.orders), function(v) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(v.title))]), _vm._v(" "), (v.wwb === 0) ? _c('td', [_vm._v("免费")]) : _c('td', [_vm._v(_vm._s(v.wwb))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.version))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.game_version))]), _vm._v(" "), _c('td', {
      staticClass: "tool"
    }, [_c('router-link', {
      staticClass: "my_a_style",
      attrs: {
        "to": {
          name: 'plug.info',
          params: {
            id: v.id
          }
        }
      }
    }, [_vm._v("\n                    查看详情\n                ")]), _vm._v(" "), _c('router-link', {
      staticClass: "my_a_style",
      attrs: {
        "to": {
          name: 'update.plug',
          params: {
            id: v.id
          }
        }
      }
    }, [_vm._v("\n                    编辑\n                ")]), _vm._v(" "), _c('router-link', {
      staticClass: "my_a_style",
      attrs: {
        "to": {
          name: 'upload.plug',
          params: {
            id: v.plug_id
          }
        }
      }
    }, [_vm._v("\n                    升级版本\n                ")]), _vm._v(" "), _c('a', {
      staticClass: "my_a_style",
      attrs: {
        "href": "javascript:void(0)"
      }
    }, [_vm._v("查看历史版本")])], 1)])
  }))]) : _c('p', {
    staticClass: "normal_font",
    class: {
      'bl_font_color': (_vm.userInfo && _vm.userInfo.camp && _vm.userInfo.camp === 2) || (!_vm.userInfo && _vm.choice_cmap === '2')
    }
  }, [_vm._v("暂无记录")]), _vm._v(" "), _c('Page', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.count > 0),
      expression: "count > 0"
    }],
    key: _vm.count,
    class: {
      'bl_page_color': (_vm.userInfo && _vm.userInfo.camp && _vm.userInfo.camp === 2) || (!_vm.userInfo && _vm.choice_cmap === '2')
    },
    staticStyle: {
      "float": "right",
      "margin-top": "30px"
    },
    attrs: {
      "total": _vm.count,
      "size": "small",
      "show-total": ""
    },
    on: {
      "on-change": _vm.change_page
    }
  })], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', {
    attrs: {
      "width": "40"
    }
  }, [_vm._v("插件名称")]), _vm._v(" "), _c('th', {
    attrs: {
      "width": "10"
    }
  }, [_vm._v("收费")]), _vm._v(" "), _c('th', {
    attrs: {
      "width": "10"
    }
  }, [_vm._v("插件版本")]), _vm._v(" "), _c('th', {
    attrs: {
      "width": "10"
    }
  }, [_vm._v("游戏版本")]), _vm._v(" "), _c('th', {
    attrs: {
      "width": "30"
    }
  }, [_vm._v("操作")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-5493259e", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5493259e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Upload.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5493259e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Upload.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("ccbc86b2", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5493259e\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Upload.vue", function() {
     var newContent = require("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5493259e\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Upload.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/user/Upload.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5493259e\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/user/Upload.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/user/Upload.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-5493259e\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/user/Upload.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-5493259e",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\user\\Upload.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Upload.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5493259e", Component.options)
  } else {
    hotAPI.reload("data-v-5493259e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});