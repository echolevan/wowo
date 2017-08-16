webpackJsonp([12],{

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/index/Index.vue":
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



/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            c1: '/images/c1.png',
            c2: '/images/c2.png'
        };
    },

    computed: Object(__WEBPACK_IMPORTED_MODULE_0_vuex__["b" /* mapState */])(['userInfo']),
    watch: {
        '$route': function $route(to, from) {
            var _this = this;

            axios.get('user/info').then(function (res) {
                if (res.data.sta === '1') {
                    _this.$router.push('/home');
                }
            });
        }
    },
    mounted: function mounted() {
        var _this2 = this;

        axios.get('user/info').then(function (res) {
            if (res.data.sta === '1') {
                _this2.$router.push('/home');
            }
        });
    },

    methods: {
        enter: function enter(k) {
            if (k === 1) {
                this.c1 = '/images/c1_hover.png';
            } else {
                this.c2 = '/images/c2_hover.png';
            }
        },
        leave: function leave(k) {
            if (k === 1) {
                this.c1 = '/images/c1.png';
            } else {
                this.c2 = '/images/c2.png';
            }
        },
        choice: function choice(k) {
            window.localStorage.setItem('choice_camp', k);
            this.$router.push('/home');
        }
    }
});

/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-113e6181\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/index/Index.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "\n.index .title[data-v-113e6181] {\n  font-size: 50px;\n  text-align: center;\n  margin: 60px 0;\n}\n.index .choice[data-v-113e6181] {\n  display: flex;\n}\n.index .choice div[data-v-113e6181] {\n  flex: 1;\n  text-align: center;\n}\n.index .choice div img[data-v-113e6181] {\n  margin: 0 auto;\n  width: 300px;\n}\n.index .choice div img[data-v-113e6181]:hover {\n  cursor: pointer;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-113e6181\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/index/Index.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "index"
  }, [(!_vm.userInfo) ? _c('div', {
    staticClass: "title"
  }, [_vm._v("\n        选择你的阵营\n    ")]) : _vm._e(), _vm._v(" "), (!_vm.userInfo) ? _c('div', {
    staticClass: "choice"
  }, [_c('div', {
    staticClass: "c_one"
  }, [_c('img', {
    attrs: {
      "src": _vm.c1,
      "alt": ""
    },
    on: {
      "mouseenter": function($event) {
        _vm.enter(1)
      },
      "mouseleave": function($event) {
        _vm.leave(1)
      },
      "click": function($event) {
        _vm.choice(1)
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "c_two"
  }, [_c('img', {
    attrs: {
      "src": _vm.c2,
      "alt": ""
    },
    on: {
      "mouseenter": function($event) {
        _vm.enter(2)
      },
      "mouseleave": function($event) {
        _vm.leave(2)
      },
      "click": function($event) {
        _vm.choice(2)
      }
    }
  })])]) : _vm._e()])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-113e6181", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-113e6181\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/index/Index.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-113e6181\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/index/Index.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("0904d59c", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-113e6181\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Index.vue", function() {
     var newContent = require("!!../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-113e6181\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./Index.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/index/Index.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-113e6181\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/index/Index.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/index/Index.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-113e6181\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/index/Index.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-113e6181",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\index\\Index.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Index.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-113e6181", Component.options)
  } else {
    hotAPI.reload("data-v-113e6181", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});