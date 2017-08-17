webpackJsonp([31],{

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/admin/user/List.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__common_imgView_vue__ = __webpack_require__("./resources/assets/js/components/common/imgView.vue");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__common_imgView_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__common_imgView_vue__);
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
//


/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            page: 0,
            page_size: 10,
            total: 0,
            list: [],
            show_info: false,
            formS: {}
        };
    },
    mounted: function mounted() {
        this.search();
    },

    methods: {
        search: function search() {
            var _this = this;

            axios.post('admin/user/list/' + this.page + '/' + this.page_size, { search: this.formS }).then(function (res) {
                console.log(res);
                if (res.data.sta === 1) {
                    _this.total = res.data.count;
                    _this.list = res.data.users;
                }
                _this.$Loading.finish();
                _this.loading_s = false;
            });
        },
        page_c: function page_c(p) {
            this.page = p;
            this.search();
            this.$Loading.start();
        },
        size_c: function size_c(s) {
            this.page_size = s;
            this.search();
            this.$Loading.start();
        }
    },
    components: {
        'img-view': __WEBPACK_IMPORTED_MODULE_0__common_imgView_vue___default.a
    }
});

/***/ }),

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/common/imgView.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
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
            imgName: '',
            visible: false
        };
    },

    props: ['img'],
    methods: {
        handleView: function handleView(imgName) {
            this.imgName = imgName;
            this.visible = true;
        }
    }
});

/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-34c7acc8\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/imgView.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "", ""]);

// exports


/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-99988ce4\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/user/List.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "", ""]);

// exports


/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-34c7acc8\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/common/imgView.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('img', {
    staticClass: "view_img",
    attrs: {
      "src": _vm.img,
      "alt": ""
    },
    on: {
      "click": function($event) {
        _vm.handleView(_vm.img)
      }
    }
  }), _vm._v(" "), _c('Modal', {
    attrs: {
      "title": "查看图片"
    },
    model: {
      value: (_vm.visible),
      callback: function($$v) {
        _vm.visible = $$v
      },
      expression: "visible"
    }
  }, [(_vm.visible) ? _c('img', {
    staticStyle: {
      "width": "100%"
    },
    attrs: {
      "src": _vm.imgName
    }
  }) : _vm._e()])], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-34c7acc8", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-99988ce4\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/admin/user/List.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('Breadcrumb', {
    staticStyle: {
      "margin-bottom": "15px",
      "font-size": "12px"
    }
  }, [_c('Breadcrumb-item', [_vm._v("主页")]), _vm._v(" "), _c('Breadcrumb-item', [_vm._v("分类管理")]), _vm._v(" "), _c('Breadcrumb-item', [_vm._v("用户列表")])], 1), _vm._v(" "), _c('i-switch', {
    attrs: {
      "v_model": "show_info"
    }
  }, [_c('span', {
    slot: "open"
  }, [_vm._v("显示签名")]), _vm._v(" "), _c('span', {
    slot: "close"
  }, [_vm._v("隐藏签名")])]), _vm._v(" "), _c('table', {
    staticClass: "table table-bordered my_admin_table"
  }, [_c('thead', [_c('tr', [_c('th', {
    staticStyle: {
      "width": "10%"
    }
  }, [_vm._v("昵称")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("头像")]), _vm._v(" "), _c('th', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.show_info),
      expression: "show_info"
    }],
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("签名")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "10%"
    }
  }, [_vm._v("手机号")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "10%"
    }
  }, [_vm._v("邮箱")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("阵营")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("金币")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("发布插件数")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "10%"
    }
  }, [_vm._v("注册时间")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "10%"
    }
  }, [_vm._v("最近登陆")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("是否激活")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "5%"
    }
  }, [_vm._v("能否登陆")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "10%"
    }
  }, [_vm._v("是否是管理员")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "width": "15%"
    }
  }, [_vm._v("操作")])])]), _vm._v(" "), _c('tbody', _vm._l((_vm.list), function(v, k) {
    return _c('tr', [_c('td', [_vm._v(_vm._s(v.name))]), _vm._v(" "), _c('td', [_c('img-view', {
      attrs: {
        "img": v.avatar
      }
    })], 1), _vm._v(" "), _c('td', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.show_info),
        expression: "show_info"
      }]
    }, [_vm._v(_vm._s(v.info))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.tel))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.email))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.camp))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.wwb))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.plugs.length))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.created_at))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.login_at))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.is_active))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.status))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.is_admin))]), _vm._v(" "), _c('td', [_vm._v("1")])])
  }))]), _vm._v(" "), _c('div', {
    staticClass: "page pull-right"
  }, [_c('Page', {
    key: _vm.total,
    attrs: {
      "total": _vm.total,
      "size": "small",
      "show-elevator": "",
      "show-sizer": "",
      "show-total": ""
    },
    on: {
      "on-change": _vm.page_c,
      "on-page-size-change": _vm.size_c
    }
  })], 1)], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-99988ce4", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-34c7acc8\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/imgView.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-34c7acc8\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/imgView.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("22fa21c0", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-34c7acc8\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./imgView.vue", function() {
     var newContent = require("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-34c7acc8\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./imgView.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-99988ce4\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/user/List.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-99988ce4\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/user/List.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("b2dd32c6", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-99988ce4\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./List.vue", function() {
     var newContent = require("!!../../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-99988ce4\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./List.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/admin/user/List.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-99988ce4\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/user/List.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/admin/user/List.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-99988ce4\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/admin/user/List.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-99988ce4",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\admin\\user\\List.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] List.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-99988ce4", Component.options)
  } else {
    hotAPI.reload("data-v-99988ce4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ "./resources/assets/js/components/common/imgView.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-34c7acc8\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/common/imgView.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/common/imgView.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-34c7acc8\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/common/imgView.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-34c7acc8",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\common\\imgView.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] imgView.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-34c7acc8", Component.options)
  } else {
    hotAPI.reload("data-v-34c7acc8", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});