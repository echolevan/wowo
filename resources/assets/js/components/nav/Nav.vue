<template>
    <div>
        <div id="nav" :class="{'bl_nav_color': (userInfo && userInfo.camp && userInfo.camp === 2) || (!userInfo &&choice_cmap === '2') }">
            <div class="title">
                <div class="logo">
                    <router-link to="/" class="my_a_style" v-if="!userInfo">嘿市</router-link>
                    <router-link to="/home" class="my_a_style" v-else>嘿市</router-link>
                </div>
                <div class="login">
                    <div v-if="!userInfo">
                        <span>
                            <a href="javascript:void(0)" class="my_a_style" @click="login">登录</a>
                        </span>
                        <span>|</span>
                        <span>
                        <a href="javascript:void(0)" class="my_a_style" @click="register">注册</a>
                    </span>
                    </div>
                    <div v-else>
                         <span>
                            <a href="javascript:void(0);">欢迎回来</a>&nbsp;&nbsp;&nbsp;
                            <router-link to="/userInfo" class="my_a_style">{{userInfo.nickname}}</router-link>
                        </span>
                        <span>|</span>
                        <span><a href="javascript:void(0);" class="my_a_style" @click="logout">退出登录</a></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav menu">
            <div class="underline">

                <ul class="modern-menu theme2">
                    <li>
                        <router-link to="/home" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span class="no_active">首页</span></router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'waTmw.index' , params:{'type':'wa'}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span class="no_active">WA</span></router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'waTmw.index' , params:{'type':'twm'}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span class="no_active">TMW</span></router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'waTmw.index' , params:{'type':'plug'}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span class="no_active">游戏插件</span>
                        </router-link>
                    </li>
                    <li>
                        <a href="javascript:void(0)" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span>易游</span></a>
                    </li>
                    <li v-if="tools.bm === '1'">
                        <router-link to="/bm" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span class="no_active">黑市</span>
                        </router-link>
                    </li>
                    <li>
                        <a href="https://bbs.iwowcn.com" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span>潘达利亚</span></a>
                    </li>
                </ul>
            </div>
            <div style="clear: both"></div>
        </div>
    </div>
</template>

<script>
    import {myNav} from '../../../common/nav.js'
    import { mapState } from 'vuex'
    export default {
        data() {
            return {
            }
        },
        computed: mapState([
            'userInfo' , 'choice_cmap' , 'tools'
        ]),
        mounted() {
            $(".modern-menu").modernMenu();
        },
        methods: {
            logout() {
                axios.get('user/logout').then(res=>{
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo','')
                    }
                })
            },
            login(){
                localStorage.setItem('redirect',this.$route.path)
                window.location.href="/login"
            },
            register(){
                localStorage.setItem('redirect',this.$route.path)
                window.location.href="/register"
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../common/nav.css"
    #nav
        width 100%
        height 35px
        line-height 35px
        background-color #266ec1
        .title
            margin 0 auto
            width 1240px
            .logo
                float left
                a
                    color #fff
            .login
                float right
                span
                    color #fff
                    padding 0 7px
                a
                    color #fff
                    padding 0 7px

    .nav.menu
        padding 15px
        margin 0 auto
        width 1240px
        background-color #fff
        border-bottom 1px solid #f5f5f5
</style>
