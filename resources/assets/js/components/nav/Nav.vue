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
                            <span>欢迎回来</span>&nbsp;&nbsp;&nbsp;
                            <router-link to="/userInfo" class="my_a_style">{{userInfo.nickname}}</router-link>
                        </span>
                        <span>|</span>
                        <span><a href="javascript:void(0);" class="my_a_style"
                                                            @click="logout">退出登录</a></span>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
        </div>
        <div class="nav menu">
            <v-new-nav></v-new-nav>
            <a v-if="$route.name === 'home.index' && ads[1]" :href="ads[1].link ? ads[1].link : 'javascript:void(0)'" target="_blank" >
                <img  :src="ads[1].url[0].url" :style="{'width': ads[1].width + 'px' , 'height': ads[1].height + 'px'}" style="margin-top: 15px" alt="">
            </a>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'
    import NewVue from './NewNav.vue'

    export default {
        data() {
            return {
                nav_tags: [],
                is_hover: 0,
                configUrl: {
                    'TMW': 'tmw',
                    'WA': 'wa',
                    '魔兽插件': 'addons'
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'tools', 'ads'
        ]),
        methods: {
            logout() {
                axios.get('user/logout').then(res => {
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo', '')
                        sessionStorage.removeItem('loginUserInfoId')
                        localStorage.setItem('redirect', "/")
                        window.location.href = "/"
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
            }
        },
        components: {
            'v-new-nav': NewVue
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    /*@import "../../../common/nav.css"*/
    /*@import "../../../common/demo.css"*/
    #nav
        width 100%
        height 35px
        line-height 35px
        background-color #266ec1
        border-bottom 1px solid #f5f5f5
        .title
            margin 0 auto
            width 1300px
            padding 0 27px 0 40px;
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
