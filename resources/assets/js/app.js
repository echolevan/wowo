
/**
 * First we will load all of store.state project's JavaScript dependencies which
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

import {tagType, statusType, isForUser, yesOrNo, camp, isLogin, plugType, checkType, bmDownloadType, bmType, payType, payStatus,isActive,withdrawStatus} from './components/common/config'
import {my_dialog} from '../common/dialog.js'
import {close_my_dialog} from '../common/dialog.js'
global.configTagType = tagType;
global.configStatusType = statusType;
global.configIsForUser = isForUser;
global.configYesOrNo = yesOrNo;
global.configCamp = camp;
global.configIsLogin = isLogin;
global.configPlugType = plugType;
global.configCheckType = checkType;
global.configBmDownloadType = bmDownloadType;
global.configBmType = bmType;
global.myDialog = my_dialog;
global.clodeMyDialog = close_my_dialog;
global.configPayType = payType;
global.configPayStatus = payStatus;
global.configIsActive = isActive;
global.configWithdrawStatus = withdrawStatus;

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
        choice_cmap: '',
        change_s_tag: '',
        tools: {
            'bm': 0,
            'notice': '',
            'bm_notice': ''
        },
        lv: {
            'name': '新手','money':0,'giving':0
        }
    },
    mutations: {
        change_userInfo (state , user) {
            state.userInfo = user
        },
        choice_camp (state , camp) {
            state.choice_cmap = camp
        },
        change_tools (state, tools) {
            state.tools.bm = tools['bm'] ? tools['bm'].value : 1
            state.tools.notice = tools['notice'] ? tools['notice'].value : ''
            state.tools.bm_notice = tools['bm_notice'] ? tools['bm_notice'].value : ''
        },
        change_tag (state, tools) {
            state.change_s_tag = Math.random()
        },
        change_lv (state, lv) {
            state.lv = lv
        }
    }
})

axios.get('/user/info').then(res => {
    store.commit('change_tools', res.data.tools)
    if (res.data.sta === '1') {
        console.log(res)
        store.commit('change_userInfo', res.data.info)
        store.commit('change_lv', res.data.lv)
        sessionStorage.setItem('loginUserInfoId',[res.data.info.id , res.data.info.is_active])
        if (res.data.info.is_active === 0) {
            if(res.data.info.camp === 1){
                iView.Notice.info({
                    title: '您的安全邮箱未验证',
                    desc: '已发送验证邮件到您邮箱，<a target="_blank" href=' + res.data.email + ' style="font-weight: bold;color:#266ec1">点击验证</a>。',
                    duration: 0
                });
            }else{
                iView.Notice.error({
                    title: '您的安全邮箱未验证',
                    desc: '已发送验证邮件到您邮箱，<a target="_blank" href=' + res.data.email + ' style="font-weight: bold;color:#d13030">点击验证</a>。',
                    duration: 0
                });
            }
        }
    } else {
        sessionStorage.setItem('loginUserInfoId','')
        store.state.userInfo = '';
    }
})


RouterConfig.beforeEach((to,from,next) => {
    if(to.name === 'bm.index' || to.matched[0].name === 'user.index'){
        let userInfo = sessionStorage.getItem('loginUserInfoId')
        if(!userInfo){
            myDialog(`请先 <a href="/register" class="${(store.state.userInfo && store.state.userInfo.camp && store.state.userInfo.camp === 2 ) || (!store.state.userInfo && store.state.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">注册</a>
                     <a href="/login"  class="${(store.state.userInfo && store.state.userInfo.camp && store.state.userInfo.camp === 2 ) || (!store.state.userInfo && store.state.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">登录</a>`
                , (store.state.userInfo && store.state.userInfo.camp && store.state.userInfo.camp === 2 ) || (!store.state.userInfo && store.state.choice_cmap === '2') ? 'bl_button_color' : '')
            RouterConfig.push('/home')
            return false
        }
    }
    iView.LoadingBar.start();
    next()
});

RouterConfig.afterEach(() => {
    iView.LoadingBar.finish();
});



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to store.state application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('v-index', require('./components/Index.vue'));
Vue.component('v-admin', require('./components/admin/Index.vue'));

const app = new Vue({
    el: '#app',
    store,
    router: RouterConfig
});
