webpackJsonp([2],{

/***/ "./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/admin/plug/List.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_clipboard__ = __webpack_require__("./node_modules/_clipboard@1.7.1@clipboard/lib/clipboard.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_clipboard___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_clipboard__);
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
            page: 1,
            page_size: 10,
            total: 0,
            list: [],
            formS: {
                name: '',
                plug_id: '',
                user_name: '',
                user_id: '',
                wwb: '',
                status: '',
                is_check: '',
                orderBySome: 'created_at',
                orderByF: 'desc',
                is_new: '0'
            },
            loading_s: false,
            configPlugType: configPlugType,
            configYesOrNo: configYesOrNo,
            configStatusType: configStatusType,
            configCheckType: configCheckType,
            is_disabled: ''
        };
    },
    mounted: function mounted() {
        this.search();
    },

    methods: {
        toS: function toS() {
            this.page = 1;
            this.loading_s = true;
            this.search();
        },
        search_his: function search_his(id) {
            this.page = 1;
            this.formS.plug_id = id;
            this.formS.is_new = '0';
            this.search();
        },
        rest: function rest() {
            this.formS.name = '';
            this.formS.user_name = '';
            this.formS.plug_id = '';
            this.formS.user_id = '';
            this.formS.wwb = '';
            this.formS.status = '';
            this.formS.is_check = '';
            this.formS.orderBySome = 'created_at    ';
            this.formS.orderByF = 'desc';
            this.formS.is_new = '0';
        },
        c_rank: function c_rank(id, k) {
            var _this = this;

            if (this.is_disabled === k) {
                // 提交
                if (this.list[k].rank === 0) {
                    this.$Message.error('请输入大于0小于99的数字');
                    return false;
                }
                axios.get('/admin/plug/change_rank/' + id + '/' + this.list[k].rank).then(function (res) {
                    if (res.data.sta === 1) {
                        _this.$Message.success(res.data.msg);
                        _this.is_disabled = '';
                    } else {
                        _this.$Message.error(res.data.msg);
                    }
                });
            } else {
                this.is_disabled = k;
            }
        },
        clipboard: function clipboard() {
            var clipboard = new __WEBPACK_IMPORTED_MODULE_0_clipboard___default.a('.clipboard');
            clipboard.on('success', function (e) {});
            this.$Message.success('复制成功');
        },
        change_status: function change_status(v, id, k) {
            var _this2 = this;

            axios.get('/admin/plug/change_status/' + id + '/' + v).then(function (res) {
                if (res.data.sta === 1) {
                    _this2.list[k].status = v;
                    _this2.$Message.success(res.data.msg);
                } else {
                    _this2.$Message.error(res.data.msg);
                }
            });
        },
        change_is_check: function change_is_check(v, id, k) {
            var _this3 = this;

            axios.get('/admin/plug/change_is_check/' + id + '/' + v).then(function (res) {
                if (res.data.sta === 1) {
                    _this3.list[k].is_check = v;
                    _this3.$Message.success(res.data.msg);
                } else {
                    _this3.$Message.error(res.data.msg);
                }
            });
        },
        search: function search() {
            var _this4 = this;

            axios.post('/admin/plug/list/' + this.page + '/' + this.page_size, { search: this.formS }).then(function (res) {
                if (res.data.sta === 1) {
                    _this4.total = res.data.count;
                    _this4.list = res.data.list;
                }
                _this4.$Loading.finish();
                _this4.loading_s = false;
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
        },
        change_other: function change_other(k) {
            if (!/^\d+$/.test(this.list[k].rank)) {
                this.list[k].rank = Math.round(this.list[k].rank);
            }
        }
    }
});

/***/ }),

/***/ "./node_modules/_clipboard@1.7.1@clipboard/lib/clipboard-action.js":
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
    if (true) {
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [module, __webpack_require__("./node_modules/_select@1.1.2@select/src/select.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if (typeof exports !== "undefined") {
        factory(module, require('select'));
    } else {
        var mod = {
            exports: {}
        };
        factory(mod, global.select);
        global.clipboardAction = mod.exports;
    }
})(this, function (module, _select) {
    'use strict';

    var _select2 = _interopRequireDefault(_select);

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {
            default: obj
        };
    }

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
        return typeof obj;
    } : function (obj) {
        return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };

    function _classCallCheck(instance, Constructor) {
        if (!(instance instanceof Constructor)) {
            throw new TypeError("Cannot call a class as a function");
        }
    }

    var _createClass = function () {
        function defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        return function (Constructor, protoProps, staticProps) {
            if (protoProps) defineProperties(Constructor.prototype, protoProps);
            if (staticProps) defineProperties(Constructor, staticProps);
            return Constructor;
        };
    }();

    var ClipboardAction = function () {
        /**
         * @param {Object} options
         */
        function ClipboardAction(options) {
            _classCallCheck(this, ClipboardAction);

            this.resolveOptions(options);
            this.initSelection();
        }

        /**
         * Defines base properties passed from constructor.
         * @param {Object} options
         */


        _createClass(ClipboardAction, [{
            key: 'resolveOptions',
            value: function resolveOptions() {
                var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

                this.action = options.action;
                this.container = options.container;
                this.emitter = options.emitter;
                this.target = options.target;
                this.text = options.text;
                this.trigger = options.trigger;

                this.selectedText = '';
            }
        }, {
            key: 'initSelection',
            value: function initSelection() {
                if (this.text) {
                    this.selectFake();
                } else if (this.target) {
                    this.selectTarget();
                }
            }
        }, {
            key: 'selectFake',
            value: function selectFake() {
                var _this = this;

                var isRTL = document.documentElement.getAttribute('dir') == 'rtl';

                this.removeFake();

                this.fakeHandlerCallback = function () {
                    return _this.removeFake();
                };
                this.fakeHandler = this.container.addEventListener('click', this.fakeHandlerCallback) || true;

                this.fakeElem = document.createElement('textarea');
                // Prevent zooming on iOS
                this.fakeElem.style.fontSize = '12pt';
                // Reset box model
                this.fakeElem.style.border = '0';
                this.fakeElem.style.padding = '0';
                this.fakeElem.style.margin = '0';
                // Move element out of screen horizontally
                this.fakeElem.style.position = 'absolute';
                this.fakeElem.style[isRTL ? 'right' : 'left'] = '-9999px';
                // Move element to the same position vertically
                var yPosition = window.pageYOffset || document.documentElement.scrollTop;
                this.fakeElem.style.top = yPosition + 'px';

                this.fakeElem.setAttribute('readonly', '');
                this.fakeElem.value = this.text;

                this.container.appendChild(this.fakeElem);

                this.selectedText = (0, _select2.default)(this.fakeElem);
                this.copyText();
            }
        }, {
            key: 'removeFake',
            value: function removeFake() {
                if (this.fakeHandler) {
                    this.container.removeEventListener('click', this.fakeHandlerCallback);
                    this.fakeHandler = null;
                    this.fakeHandlerCallback = null;
                }

                if (this.fakeElem) {
                    this.container.removeChild(this.fakeElem);
                    this.fakeElem = null;
                }
            }
        }, {
            key: 'selectTarget',
            value: function selectTarget() {
                this.selectedText = (0, _select2.default)(this.target);
                this.copyText();
            }
        }, {
            key: 'copyText',
            value: function copyText() {
                var succeeded = void 0;

                try {
                    succeeded = document.execCommand(this.action);
                } catch (err) {
                    succeeded = false;
                }

                this.handleResult(succeeded);
            }
        }, {
            key: 'handleResult',
            value: function handleResult(succeeded) {
                this.emitter.emit(succeeded ? 'success' : 'error', {
                    action: this.action,
                    text: this.selectedText,
                    trigger: this.trigger,
                    clearSelection: this.clearSelection.bind(this)
                });
            }
        }, {
            key: 'clearSelection',
            value: function clearSelection() {
                if (this.trigger) {
                    this.trigger.focus();
                }

                window.getSelection().removeAllRanges();
            }
        }, {
            key: 'destroy',
            value: function destroy() {
                this.removeFake();
            }
        }, {
            key: 'action',
            set: function set() {
                var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'copy';

                this._action = action;

                if (this._action !== 'copy' && this._action !== 'cut') {
                    throw new Error('Invalid "action" value, use either "copy" or "cut"');
                }
            },
            get: function get() {
                return this._action;
            }
        }, {
            key: 'target',
            set: function set(target) {
                if (target !== undefined) {
                    if (target && (typeof target === 'undefined' ? 'undefined' : _typeof(target)) === 'object' && target.nodeType === 1) {
                        if (this.action === 'copy' && target.hasAttribute('disabled')) {
                            throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');
                        }

                        if (this.action === 'cut' && (target.hasAttribute('readonly') || target.hasAttribute('disabled'))) {
                            throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');
                        }

                        this._target = target;
                    } else {
                        throw new Error('Invalid "target" value, use a valid Element');
                    }
                }
            },
            get: function get() {
                return this._target;
            }
        }]);

        return ClipboardAction;
    }();

    module.exports = ClipboardAction;
});

/***/ }),

/***/ "./node_modules/_clipboard@1.7.1@clipboard/lib/clipboard.js":
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
    if (true) {
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [module, __webpack_require__("./node_modules/_clipboard@1.7.1@clipboard/lib/clipboard-action.js"), __webpack_require__("./node_modules/_tiny-emitter@2.0.1@tiny-emitter/index.js"), __webpack_require__("./node_modules/_good-listener@1.2.2@good-listener/src/listen.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if (typeof exports !== "undefined") {
        factory(module, require('./clipboard-action'), require('tiny-emitter'), require('good-listener'));
    } else {
        var mod = {
            exports: {}
        };
        factory(mod, global.clipboardAction, global.tinyEmitter, global.goodListener);
        global.clipboard = mod.exports;
    }
})(this, function (module, _clipboardAction, _tinyEmitter, _goodListener) {
    'use strict';

    var _clipboardAction2 = _interopRequireDefault(_clipboardAction);

    var _tinyEmitter2 = _interopRequireDefault(_tinyEmitter);

    var _goodListener2 = _interopRequireDefault(_goodListener);

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {
            default: obj
        };
    }

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
        return typeof obj;
    } : function (obj) {
        return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };

    function _classCallCheck(instance, Constructor) {
        if (!(instance instanceof Constructor)) {
            throw new TypeError("Cannot call a class as a function");
        }
    }

    var _createClass = function () {
        function defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        return function (Constructor, protoProps, staticProps) {
            if (protoProps) defineProperties(Constructor.prototype, protoProps);
            if (staticProps) defineProperties(Constructor, staticProps);
            return Constructor;
        };
    }();

    function _possibleConstructorReturn(self, call) {
        if (!self) {
            throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
        }

        return call && (typeof call === "object" || typeof call === "function") ? call : self;
    }

    function _inherits(subClass, superClass) {
        if (typeof superClass !== "function" && superClass !== null) {
            throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
        }

        subClass.prototype = Object.create(superClass && superClass.prototype, {
            constructor: {
                value: subClass,
                enumerable: false,
                writable: true,
                configurable: true
            }
        });
        if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
    }

    var Clipboard = function (_Emitter) {
        _inherits(Clipboard, _Emitter);

        /**
         * @param {String|HTMLElement|HTMLCollection|NodeList} trigger
         * @param {Object} options
         */
        function Clipboard(trigger, options) {
            _classCallCheck(this, Clipboard);

            var _this = _possibleConstructorReturn(this, (Clipboard.__proto__ || Object.getPrototypeOf(Clipboard)).call(this));

            _this.resolveOptions(options);
            _this.listenClick(trigger);
            return _this;
        }

        /**
         * Defines if attributes would be resolved using internal setter functions
         * or custom functions that were passed in the constructor.
         * @param {Object} options
         */


        _createClass(Clipboard, [{
            key: 'resolveOptions',
            value: function resolveOptions() {
                var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

                this.action = typeof options.action === 'function' ? options.action : this.defaultAction;
                this.target = typeof options.target === 'function' ? options.target : this.defaultTarget;
                this.text = typeof options.text === 'function' ? options.text : this.defaultText;
                this.container = _typeof(options.container) === 'object' ? options.container : document.body;
            }
        }, {
            key: 'listenClick',
            value: function listenClick(trigger) {
                var _this2 = this;

                this.listener = (0, _goodListener2.default)(trigger, 'click', function (e) {
                    return _this2.onClick(e);
                });
            }
        }, {
            key: 'onClick',
            value: function onClick(e) {
                var trigger = e.delegateTarget || e.currentTarget;

                if (this.clipboardAction) {
                    this.clipboardAction = null;
                }

                this.clipboardAction = new _clipboardAction2.default({
                    action: this.action(trigger),
                    target: this.target(trigger),
                    text: this.text(trigger),
                    container: this.container,
                    trigger: trigger,
                    emitter: this
                });
            }
        }, {
            key: 'defaultAction',
            value: function defaultAction(trigger) {
                return getAttributeValue('action', trigger);
            }
        }, {
            key: 'defaultTarget',
            value: function defaultTarget(trigger) {
                var selector = getAttributeValue('target', trigger);

                if (selector) {
                    return document.querySelector(selector);
                }
            }
        }, {
            key: 'defaultText',
            value: function defaultText(trigger) {
                return getAttributeValue('text', trigger);
            }
        }, {
            key: 'destroy',
            value: function destroy() {
                this.listener.destroy();

                if (this.clipboardAction) {
                    this.clipboardAction.destroy();
                    this.clipboardAction = null;
                }
            }
        }], [{
            key: 'isSupported',
            value: function isSupported() {
                var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : ['copy', 'cut'];

                var actions = typeof action === 'string' ? [action] : action;
                var support = !!document.queryCommandSupported;

                actions.forEach(function (action) {
                    support = support && !!document.queryCommandSupported(action);
                });

                return support;
            }
        }]);

        return Clipboard;
    }(_tinyEmitter2.default);

    /**
     * Helper function to retrieve attribute value.
     * @param {String} suffix
     * @param {Element} element
     */
    function getAttributeValue(suffix, element) {
        var attribute = 'data-clipboard-' + suffix;

        if (!element.hasAttribute(attribute)) {
            return;
        }

        return element.getAttribute(attribute);
    }

    module.exports = Clipboard;
});

/***/ }),

/***/ "./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b82739aa\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/plug/List.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/lib/css-base.js")(undefined);
// imports


// module
exports.push([module.i, "", ""]);

// exports


/***/ }),

/***/ "./node_modules/_delegate@3.1.3@delegate/src/closest.js":
/***/ (function(module, exports) {

var DOCUMENT_NODE_TYPE = 9;

/**
 * A polyfill for Element.matches()
 */
if (typeof Element !== 'undefined' && !Element.prototype.matches) {
    var proto = Element.prototype;

    proto.matches = proto.matchesSelector ||
                    proto.mozMatchesSelector ||
                    proto.msMatchesSelector ||
                    proto.oMatchesSelector ||
                    proto.webkitMatchesSelector;
}

/**
 * Finds the closest parent that matches a selector.
 *
 * @param {Element} element
 * @param {String} selector
 * @return {Function}
 */
function closest (element, selector) {
    while (element && element.nodeType !== DOCUMENT_NODE_TYPE) {
        if (typeof element.matches === 'function' &&
            element.matches(selector)) {
          return element;
        }
        element = element.parentNode;
    }
}

module.exports = closest;


/***/ }),

/***/ "./node_modules/_delegate@3.1.3@delegate/src/delegate.js":
/***/ (function(module, exports, __webpack_require__) {

var closest = __webpack_require__("./node_modules/_delegate@3.1.3@delegate/src/closest.js");

/**
 * Delegates event to a selector.
 *
 * @param {Element} element
 * @param {String} selector
 * @param {String} type
 * @param {Function} callback
 * @param {Boolean} useCapture
 * @return {Object}
 */
function delegate(element, selector, type, callback, useCapture) {
    var listenerFn = listener.apply(this, arguments);

    element.addEventListener(type, listenerFn, useCapture);

    return {
        destroy: function() {
            element.removeEventListener(type, listenerFn, useCapture);
        }
    }
}

/**
 * Finds closest match and invokes callback.
 *
 * @param {Element} element
 * @param {String} selector
 * @param {String} type
 * @param {Function} callback
 * @return {Function}
 */
function listener(element, selector, type, callback) {
    return function(e) {
        e.delegateTarget = closest(e.target, selector);

        if (e.delegateTarget) {
            callback.call(element, e);
        }
    }
}

module.exports = delegate;


/***/ }),

/***/ "./node_modules/_good-listener@1.2.2@good-listener/src/is.js":
/***/ (function(module, exports) {

/**
 * Check if argument is a HTML element.
 *
 * @param {Object} value
 * @return {Boolean}
 */
exports.node = function(value) {
    return value !== undefined
        && value instanceof HTMLElement
        && value.nodeType === 1;
};

/**
 * Check if argument is a list of HTML elements.
 *
 * @param {Object} value
 * @return {Boolean}
 */
exports.nodeList = function(value) {
    var type = Object.prototype.toString.call(value);

    return value !== undefined
        && (type === '[object NodeList]' || type === '[object HTMLCollection]')
        && ('length' in value)
        && (value.length === 0 || exports.node(value[0]));
};

/**
 * Check if argument is a string.
 *
 * @param {Object} value
 * @return {Boolean}
 */
exports.string = function(value) {
    return typeof value === 'string'
        || value instanceof String;
};

/**
 * Check if argument is a function.
 *
 * @param {Object} value
 * @return {Boolean}
 */
exports.fn = function(value) {
    var type = Object.prototype.toString.call(value);

    return type === '[object Function]';
};


/***/ }),

/***/ "./node_modules/_good-listener@1.2.2@good-listener/src/listen.js":
/***/ (function(module, exports, __webpack_require__) {

var is = __webpack_require__("./node_modules/_good-listener@1.2.2@good-listener/src/is.js");
var delegate = __webpack_require__("./node_modules/_delegate@3.1.3@delegate/src/delegate.js");

/**
 * Validates all params and calls the right
 * listener function based on its target type.
 *
 * @param {String|HTMLElement|HTMLCollection|NodeList} target
 * @param {String} type
 * @param {Function} callback
 * @return {Object}
 */
function listen(target, type, callback) {
    if (!target && !type && !callback) {
        throw new Error('Missing required arguments');
    }

    if (!is.string(type)) {
        throw new TypeError('Second argument must be a String');
    }

    if (!is.fn(callback)) {
        throw new TypeError('Third argument must be a Function');
    }

    if (is.node(target)) {
        return listenNode(target, type, callback);
    }
    else if (is.nodeList(target)) {
        return listenNodeList(target, type, callback);
    }
    else if (is.string(target)) {
        return listenSelector(target, type, callback);
    }
    else {
        throw new TypeError('First argument must be a String, HTMLElement, HTMLCollection, or NodeList');
    }
}

/**
 * Adds an event listener to a HTML element
 * and returns a remove listener function.
 *
 * @param {HTMLElement} node
 * @param {String} type
 * @param {Function} callback
 * @return {Object}
 */
function listenNode(node, type, callback) {
    node.addEventListener(type, callback);

    return {
        destroy: function() {
            node.removeEventListener(type, callback);
        }
    }
}

/**
 * Add an event listener to a list of HTML elements
 * and returns a remove listener function.
 *
 * @param {NodeList|HTMLCollection} nodeList
 * @param {String} type
 * @param {Function} callback
 * @return {Object}
 */
function listenNodeList(nodeList, type, callback) {
    Array.prototype.forEach.call(nodeList, function(node) {
        node.addEventListener(type, callback);
    });

    return {
        destroy: function() {
            Array.prototype.forEach.call(nodeList, function(node) {
                node.removeEventListener(type, callback);
            });
        }
    }
}

/**
 * Add an event listener to a selector
 * and returns a remove listener function.
 *
 * @param {String} selector
 * @param {String} type
 * @param {Function} callback
 * @return {Object}
 */
function listenSelector(selector, type, callback) {
    return delegate(document.body, selector, type, callback);
}

module.exports = listen;


/***/ }),

/***/ "./node_modules/_select@1.1.2@select/src/select.js":
/***/ (function(module, exports) {

function select(element) {
    var selectedText;

    if (element.nodeName === 'SELECT') {
        element.focus();

        selectedText = element.value;
    }
    else if (element.nodeName === 'INPUT' || element.nodeName === 'TEXTAREA') {
        var isReadOnly = element.hasAttribute('readonly');

        if (!isReadOnly) {
            element.setAttribute('readonly', '');
        }

        element.select();
        element.setSelectionRange(0, element.value.length);

        if (!isReadOnly) {
            element.removeAttribute('readonly');
        }

        selectedText = element.value;
    }
    else {
        if (element.hasAttribute('contenteditable')) {
            element.focus();
        }

        var selection = window.getSelection();
        var range = document.createRange();

        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);

        selectedText = selection.toString();
    }

    return selectedText;
}

module.exports = select;


/***/ }),

/***/ "./node_modules/_tiny-emitter@2.0.1@tiny-emitter/index.js":
/***/ (function(module, exports) {

function E () {
  // Keep this empty so it's easier to inherit from
  // (via https://github.com/lipsmack from https://github.com/scottcorgan/tiny-emitter/issues/3)
}

E.prototype = {
  on: function (name, callback, ctx) {
    var e = this.e || (this.e = {});

    (e[name] || (e[name] = [])).push({
      fn: callback,
      ctx: ctx
    });

    return this;
  },

  once: function (name, callback, ctx) {
    var self = this;
    function listener () {
      self.off(name, listener);
      callback.apply(ctx, arguments);
    };

    listener._ = callback
    return this.on(name, listener, ctx);
  },

  emit: function (name) {
    var data = [].slice.call(arguments, 1);
    var evtArr = ((this.e || (this.e = {}))[name] || []).slice();
    var i = 0;
    var len = evtArr.length;

    for (i; i < len; i++) {
      evtArr[i].fn.apply(evtArr[i].ctx, data);
    }

    return this;
  },

  off: function (name, callback) {
    var e = this.e || (this.e = {});
    var evts = e[name];
    var liveEvents = [];

    if (evts && callback) {
      for (var i = 0, len = evts.length; i < len; i++) {
        if (evts[i].fn !== callback && evts[i].fn._ !== callback)
          liveEvents.push(evts[i]);
      }
    }

    // Remove event from queue to prevent memory leak
    // Suggested by https://github.com/lazd
    // Ref: https://github.com/scottcorgan/tiny-emitter/commit/c6ebfaa9bc973b33d110a84a307742b7cf94c953#commitcomment-5024910

    (liveEvents.length)
      ? e[name] = liveEvents
      : delete e[name];

    return this;
  }
};

module.exports = E;


/***/ }),

/***/ "./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-b82739aa\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/admin/plug/List.vue":
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('Breadcrumb', {
    staticStyle: {
      "margin-bottom": "15px",
      "font-size": "12px"
    }
  }, [_c('Breadcrumb-item', [_vm._v("主页")]), _vm._v(" "), _c('Breadcrumb-item', [_vm._v("插件管理")]), _vm._v(" "), _c('Breadcrumb-item', [_vm._v("插件列表")])], 1), _vm._v(" "), _c('Form', {
    attrs: {
      "model": _vm.formS,
      "inline": ""
    }
  }, [_c('Form-item', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.formS.plug_id),
      expression: "formS.plug_id"
    }]
  }, [_c('Input', {
    attrs: {
      "placeholder": "插件唯一ID"
    },
    model: {
      value: (_vm.formS.plug_id),
      callback: function($$v) {
        _vm.formS.plug_id = (typeof $$v === 'string' ? $$v.trim() : $$v)
      },
      expression: "formS.plug_id"
    }
  })], 1), _vm._v(" "), _c('Form-item', [_c('Input', {
    attrs: {
      "placeholder": "搜索插件名称"
    },
    model: {
      value: (_vm.formS.name),
      callback: function($$v) {
        _vm.formS.name = (typeof $$v === 'string' ? $$v.trim() : $$v)
      },
      expression: "formS.name"
    }
  })], 1), _vm._v(" "), _c('Form-item', [_c('Input', {
    attrs: {
      "placeholder": "用户名称"
    },
    model: {
      value: (_vm.formS.user_name),
      callback: function($$v) {
        _vm.formS.user_name = (typeof $$v === 'string' ? $$v.trim() : $$v)
      },
      expression: "formS.user_name"
    }
  })], 1), _vm._v(" "), _c('Form-item', [_c('Input', {
    attrs: {
      "placeholder": "用户ID"
    },
    model: {
      value: (_vm.formS.user_id),
      callback: function($$v) {
        _vm.formS.user_id = (typeof $$v === 'string' ? $$v.trim() : $$v)
      },
      expression: "formS.user_id"
    }
  })], 1), _vm._v(" "), _c('Form-item', [_c('Select', {
    staticStyle: {
      "width": "100px"
    },
    attrs: {
      "clearable": "",
      "placeholder": "是否免费"
    },
    model: {
      value: (_vm.formS.wwb),
      callback: function($$v) {
        _vm.formS.wwb = $$v
      },
      expression: "formS.wwb"
    }
  }, [_c('Option', {
    attrs: {
      "value": "1"
    }
  }, [_vm._v("免费")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "2"
    }
  }, [_vm._v("收费")])], 1)], 1), _vm._v(" "), _c('Form-item', [_c('Select', {
    staticStyle: {
      "width": "80px"
    },
    attrs: {
      "clearable": "",
      "placeholder": "状态"
    },
    model: {
      value: (_vm.formS.status),
      callback: function($$v) {
        _vm.formS.status = $$v
      },
      expression: "formS.status"
    }
  }, _vm._l((_vm.configStatusType), function(v, k) {
    return _c('Option', {
      key: k,
      attrs: {
        "value": k
      }
    }, [_vm._v(_vm._s(v))])
  }))], 1), _vm._v(" "), _c('Form-item', [_c('Select', {
    staticStyle: {
      "width": "100px"
    },
    attrs: {
      "clearable": "",
      "placeholder": "审核状态"
    },
    model: {
      value: (_vm.formS.is_check),
      callback: function($$v) {
        _vm.formS.is_check = $$v
      },
      expression: "formS.is_check"
    }
  }, _vm._l((_vm.configCheckType), function(v, k) {
    return _c('Option', {
      key: k,
      attrs: {
        "value": k
      }
    }, [_vm._v(_vm._s(v))])
  }))], 1), _vm._v(" "), _c('Form-item', [_c('Select', {
    staticStyle: {
      "width": "100px"
    },
    attrs: {
      "clearable": "",
      "placeholder": "排序条件"
    },
    model: {
      value: (_vm.formS.orderBySome),
      callback: function($$v) {
        _vm.formS.orderBySome = $$v
      },
      expression: "formS.orderBySome"
    }
  }, [_c('Option', {
    attrs: {
      "value": "wwb"
    }
  }, [_vm._v("金币")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "rank"
    }
  }, [_vm._v("排序")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "like_num"
    }
  }, [_vm._v("点赞")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "download_num"
    }
  }, [_vm._v("下载")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "collect_num"
    }
  }, [_vm._v("收藏")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "score"
    }
  }, [_vm._v("评分")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "created_at"
    }
  }, [_vm._v("上传时间")])], 1)], 1), _vm._v(" "), _c('Form-item', [_c('Select', {
    staticStyle: {
      "width": "80px"
    },
    attrs: {
      "clearable": "",
      "placeholder": "排序规则"
    },
    model: {
      value: (_vm.formS.orderByF),
      callback: function($$v) {
        _vm.formS.orderByF = $$v
      },
      expression: "formS.orderByF"
    }
  }, [_c('Option', {
    attrs: {
      "value": "asc"
    }
  }, [_vm._v("正序")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "desc"
    }
  }, [_vm._v("倒序")])], 1)], 1), _vm._v(" "), _c('Form-item', [_c('Select', {
    staticStyle: {
      "width": "100px"
    },
    attrs: {
      "clearable": "",
      "placeholder": "含有历史"
    },
    model: {
      value: (_vm.formS.is_new),
      callback: function($$v) {
        _vm.formS.is_new = $$v
      },
      expression: "formS.is_new"
    }
  }, [_c('Option', {
    attrs: {
      "value": "0"
    }
  }, [_vm._v("含有历史")]), _vm._v(" "), _c('Option', {
    attrs: {
      "value": "1"
    }
  }, [_vm._v("不包含历史")])], 1)], 1), _vm._v(" "), _c('Button', {
    attrs: {
      "type": "ghost"
    },
    on: {
      "click": _vm.rest
    }
  }, [_c('span', [_vm._v("重置")])]), _vm._v(" "), _c('Button', {
    attrs: {
      "type": "primary",
      "loading": _vm.loading_s
    },
    on: {
      "click": _vm.toS
    }
  }, [_c('span', [_vm._v("搜索")])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": "/admin/plug/create"
    }
  }, [_c('Button', {
    staticClass: "pull-right",
    attrs: {
      "type": "primary"
    }
  }, [_vm._v("添加插件")])], 1)], 1), _vm._v(" "), _c('table', {
    staticClass: "table table-bordered my_admin_table"
  }, [_vm._m(0), _vm._v(" "), (_vm.list.length > 0) ? _c('tbody', _vm._l((_vm.list), function(v, k) {
    return _c('tr', [_c('td', {
      staticClass: "hover_hand"
    }, [_c('Tooltip', {
      attrs: {
        "placement": "bottom-start"
      }
    }, [_c('a', {
      staticClass: "toolTip",
      attrs: {
        "href": ("/#/info/" + (v.id)),
        "target": "_blank"
      }
    }, [_c('span', {
      domProps: {
        "innerHTML": _vm._s(v.title)
      }
    })]), _vm._v(" "), _c('div', {
      slot: "content"
    }, [_c('p', {
      domProps: {
        "innerHTML": _vm._s(v.title)
      }
    })])])], 1), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.user.name))]), _vm._v(" "), _c('td', {
      staticClass: "hover_hand"
    }, [_c('Tooltip', {
      attrs: {
        "placement": "bottom-start"
      }
    }, [_c('span', {
      staticClass: "toolTip",
      domProps: {
        "innerHTML": _vm._s(v.simple_info)
      }
    }), _vm._v(" "), _c('div', {
      slot: "content"
    }, [_c('p', {
      domProps: {
        "innerHTML": _vm._s(v.simple_info)
      }
    })])])], 1), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.wwb === 0 ? '免费' : v.wwb))]), _vm._v(" "), _c('td', {
      staticClass: "hover_hand"
    }, [_c('Tooltip', {
      attrs: {
        "placement": "bottom-start"
      }
    }, [_c('span', {
      staticClass: "toolTip",
      domProps: {
        "innerHTML": _vm._s(_vm.configPlugType[v.type] + (v.tag_one ? '/' + v.tag_one.name : '') + (v.tag_two ? '/' + v.tag_two.name : ''))
      }
    }), _vm._v(" "), _c('div', {
      slot: "content"
    }, [_c('p', {
      domProps: {
        "innerHTML": _vm._s(_vm.configPlugType[v.type] + (v.tag_one ? '/' + v.tag_one.name : '') + (v.tag_two ? '/' + v.tag_two.name : ''))
      }
    })])])], 1), _vm._v(" "), _c('td', [(v.type < 3) ? _c('Button', {
      staticClass: "clipboard",
      attrs: {
        "type": "text",
        "size": "small",
        "data-clipboard-text": v.content
      },
      on: {
        "click": _vm.clipboard
      }
    }, [_vm._v("复制")]) : _c('Button', {
      attrs: {
        "type": "text",
        "size": "small"
      }
    }, [_c('a', {
      attrs: {
        "href": v.content,
        "target": "_blank"
      }
    }, [_vm._v("下载")])])], 1), _vm._v(" "), _c('td', [_c('Tag', {
      attrs: {
        "type": "border",
        "color": v.is_new === 1 ? 'blue' : 'red'
      }
    }, [_vm._v(_vm._s(_vm.configYesOrNo[v.is_new]))])], 1), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.download_num))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.like_num))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.collect_num))]), _vm._v(" "), _c('td', [_vm._v(_vm._s(v.score))]), _vm._v(" "), _c('td', {
      staticStyle: {
        "width": "8%"
      }
    }, [_c('Tag', {
      attrs: {
        "type": "dot",
        "color": v.status === 1 ? 'blue' : 'red'
      },
      nativeOn: {
        "click": function($event) {
          _vm.change_status(v.status === 1 ? 0 : 1, v.id, k)
        }
      }
    }, [_vm._v(_vm._s(_vm.configStatusType[v.status]))])], 1), _vm._v(" "), _c('td', {
      staticStyle: {
        "width": "8%"
      }
    }, [_c('Tag', {
      attrs: {
        "type": "dot",
        "color": v.is_check === 1 ? 'blue' : 'red'
      },
      nativeOn: {
        "click": function($event) {
          _vm.change_is_check(v.is_check === 1 ? 0 : 1, v.id, k)
        }
      }
    }, [_vm._v(_vm._s(_vm.configCheckType[v.is_check]))])], 1), _vm._v(" "), _c('td', [_c('Input-number', {
      staticStyle: {
        "width": "50px"
      },
      attrs: {
        "max": 99,
        "min": 0,
        "disabled": _vm.is_disabled === k ? false : true
      },
      on: {
        "on-change": function($event) {
          _vm.change_other(k)
        }
      },
      model: {
        value: (v.rank),
        callback: function($$v) {
          v.rank = $$v
        },
        expression: "v.rank"
      }
    })], 1), _vm._v(" "), _c('td', [_c('Button', {
      attrs: {
        "type": "ghost",
        "size": "small"
      }
    }, [_c('router-link', {
      attrs: {
        "to": {
          name: 'admin.plug.update',
          params: {
            id: v.id
          }
        }
      }
    }, [_vm._v("编辑")])], 1), _vm._v(" "), _c('Button', {
      attrs: {
        "type": "ghost",
        "size": "small"
      },
      on: {
        "click": function($event) {
          _vm.search_his(v.plug_id)
        }
      }
    }, [_vm._v("历史版本")]), _vm._v(" "), _c('Button', {
      attrs: {
        "type": _vm.is_disabled === k ? 'success' : 'ghost',
        "size": "small"
      },
      on: {
        "click": function($event) {
          _vm.c_rank(v.id, k)
        }
      }
    }, [_vm._v(_vm._s(_vm.is_disabled === k ? '提交' : '推荐'))])], 1)])
  })) : _c('tbody', [_vm._m(1)])]), _vm._v(" "), _c('div', {
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
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('thead', [_c('tr', [_c('th', [_vm._v("插件名称")]), _vm._v(" "), _c('th', [_vm._v("作者昵称")]), _vm._v(" "), _c('th', [_vm._v("插件简介")]), _vm._v(" "), _c('th', [_vm._v("收费金币")]), _vm._v(" "), _c('th', [_vm._v("插件分类")]), _vm._v(" "), _c('th', [_vm._v("插件内容")]), _vm._v(" "), _c('th', [_vm._v("是否为最新")]), _vm._v(" "), _c('th', [_vm._v("下载次数")]), _vm._v(" "), _c('th', [_vm._v("点赞次数")]), _vm._v(" "), _c('th', [_vm._v("收藏次数")]), _vm._v(" "), _c('th', [_vm._v("评分")]), _vm._v(" "), _c('th', [_vm._v("状态")]), _vm._v(" "), _c('th', [_vm._v("审核")]), _vm._v(" "), _c('th', [_vm._v("排序")]), _vm._v(" "), _c('th', [_vm._v("操作")])])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('tr', [_c('td', {
    staticStyle: {
      "text-align": "center",
      "font-size": "16px"
    },
    attrs: {
      "colspan": "15"
    }
  }, [_vm._v("\n                暂无数据\n            ")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-loader/node_modules/vue-hot-reload-api").rerender("data-v-b82739aa", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b82739aa\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/plug/List.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b82739aa\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/plug/List.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/lib/addStylesClient.js")("4640ef65", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b82739aa\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./List.vue", function() {
     var newContent = require("!!../../../../../../node_modules/_css-loader@0.28.4@css-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b82739aa\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../../node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!../../../../../../node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./List.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/admin/plug/List.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/_vue-style-loader@3.0.1@vue-style-loader/index.js!./node_modules/_css-loader@0.28.4@css-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b82739aa\",\"scoped\":true,\"hasInlineConfig\":true}!./node_modules/_stylus-loader@3.0.1@stylus-loader/index.js!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/admin/plug/List.vue")
}
var Component = __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/component-normalizer.js")(
  /* script */
  __webpack_require__("./node_modules/_babel-loader@7.1.1@babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]]}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/admin/plug/List.vue"),
  /* template */
  __webpack_require__("./node_modules/_vue-loader@12.2.2@vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-b82739aa\",\"hasScoped\":true}!./node_modules/_vue-loader@12.2.2@vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/admin/plug/List.vue"),
  /* styles */
  injectStyle,
  /* scopeId */
  "data-v-b82739aa",
  /* moduleIdentifier (server only) */
  null
)
Component.options.__file = "F:\\www\\wowo\\resources\\assets\\js\\components\\admin\\plug\\List.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] List.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b82739aa", Component.options)
  } else {
    hotAPI.reload("data-v-b82739aa", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});