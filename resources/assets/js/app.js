
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import Routers from './router.js';       // 路由列表
import iView from 'iview';
import VuePreview from 'vue-preview'
import Vuex from 'vuex'

import {tagType, statusType, isForUser, yesOrNo, camp, isLogin} from './components/common/config'
global.configTagType = tagType;
global.configStatusType = statusType;
global.configIsForUser = isForUser;
global.configYesOrNo = yesOrNo;
global.configCamp = camp;
global.configIsLogin = isLogin;

Vue.use(Vuex)
Vue.use(VueRouter);
Vue.use(VuePreview);
Vue.use(iView);

// 路由配置
const RouterConfig = new VueRouter({
    routes: Routers
});

const store = new Vuex.Store({
    state: {
        userInfo: '',
        choice_cmap: ''
    },
    mutations: {
        change_userInfo (state , user) {
            state.userInfo = user
        },
        choice_camp (state , camp) {
            state.choice_cmap = camp
        }
    }
})


RouterConfig.beforeEach((to,from,next) => {
    iView.LoadingBar.start();
    next()
});

RouterConfig.afterEach(() => {
    iView.LoadingBar.finish();
});



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('v-index', require('./components/Index.vue'));
Vue.component('v-admin', require('./components/admin/Index.vue'));

const app = new Vue({
    el: '#app',
    store,
    router: RouterConfig
});
