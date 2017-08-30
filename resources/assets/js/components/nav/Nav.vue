<template>
    <div>
        <div id="nav"
             :class="{'bl_nav_color': (userInfo && userInfo.camp && userInfo.camp === 2) || (!userInfo &&choice_cmap === '2') }">
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
                        <span style="margin-right: 15px"><a href="javascript:void(0);" class="my_a_style"
                                                            @click="logout">退出登录</a></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav menu">
            <div class="head-v3">

                <div class="navigation-down" v-show="1 === 2">

                    <div v-if="nav_tags.length > 0" v-for="v in nav_tags" :id="v.lable" class="nav-down-menu menu-1"  :_t_nav="v.label"
                         v-on:mouseenter="is_hover = v.value" v-on:mouseleave="is_hover = 0"
                         v-show="is_hover === v.value">

                        <div class="navigation-down-inner">

                            <dl v-for=" child in v.children " :class="{'height_200':v.label !== '游戏插件'}">
                                <dt class="hover_hand" @click="to_go(v.label , child.value)" :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{child.label}}</dt>
                                <dd v-for="c in child.children">
                                    <a hotrep="hp.header.product.storage1"
                                       href="javascript:void(0)" @click="to_go(v.label , c.value,child.value)">{{c.label}}</a>
                                </dd>
                            </dl>

                        </div>

                    </div>

                </div>
            </div>
            <div class="underline">

            <ul class="modern-menu theme2">
            <li style="margin-left: 15px">
            <router-link to="/home" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span class="no_active">首页</span></router-link>
            </li>

            <li v-if="nav_tags.length > 0"  v-for="v in nav_tags"
                v-on:mouseenter="is_hover = v.value" v-on:mouseleave="is_hover = 0"
            >
                <router-link :to="{name:'waTmw.index' , params:{'type':configUrl[v.label]}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                    <span class="no_active">{{v.label}}</span></router-link>
            </li>
            <!--<li>-->
            <!--<router-link :to="{name:'waTmw.index' , params:{'type':'wa'}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">-->
            <!--<span class="no_active">WA</span></router-link>-->
            <!--</li>-->
            <!--<li>-->
            <!--<router-link :to="{name:'waTmw.index' , params:{'type':'tmw'}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">-->
            <!--<span class="no_active">TMW</span></router-link>-->
            <!--</li>-->
            <!--<li>-->
            <!--<router-link :to="{name:'waTmw.index' , params:{'type':'plug'}}" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">-->
            <!--<span class="no_active">游戏插件</span>-->
            <!--</router-link>-->
            <!--</li>-->
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

ready() {
console.log(1)<script>
    import {myNav} from '../../../common/nav.js'
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                nav_tags: [],
                is_hover: 0,
                configUrl: {
                    'TMW': 'tmw',
                    'WA': 'wa',
                    '游戏插件': 'plug'
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'tools'
        ]),
        mounted() {
            axios.get('plug_all_info_nav').then(res=>{
                this.nav_tags = res.data.res
            })
            setTimeout(()=>{
                $(".modern-menu").modernMenu();
            },1000)
        },
        methods: {
            logout() {
                axios.get('user/logout').then(res => {
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo', '')
                        sessionStorage.removeItem('loginUserInfoId')
                    }
                })
            },
            login() {
                localStorage.setItem('redirect', this.$route.path)
                window.location.href = "/login"
            },
            register() {
                localStorage.setItem('redirect', this.$route.path)
                window.location.href = "/register"
            },
            to_go(type , id ,pid = '') {
                localStorage.setItem('watmw_tag_id',id)
                localStorage.setItem('watmw_tag_pid',pid)
                this.$store.commit('change_tag')
                this.$router.push({name: 'waTmw.index' , params:{type: this.configUrl[type]}})
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../common/nav.css"
    @import "../../../common/demo.css"
    #nav
        width 100%
        height 35px
        line-height 35px
        background-color #266ec1
        border-bottom 1px solid #f5f5f5
        .title
            margin 0 auto
            width 1240px
            padding 0 30px
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
        background-color #fff
        margin 0 auto
        width 1240px
        border-bottom 1px solid #f5f5f5
</style>
