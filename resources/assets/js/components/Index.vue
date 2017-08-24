<template>
    <div>
        <v-nav v-if="$route.name !== 'index'"></v-nav>
        <div class="main content" :style="$route.name !== 'index' ? 'background-color: #fff' : '' ">
            <!--mode="out-in" enter-active-class="animated fadeIn" leave-active-class="animated zoomOutDown"-->
            <transition>
                <router-view></router-view>
            </transition>
        </div>
        <div class="footer" v-if="$route.name !== 'index'">
            <div class="foot">
                <ul>
                    <li>关于我们</li>
                    <li>|</li>
                    <li>加入我们</li>
                    <li>|</li>
                    <li>商务合作</li>
                </ul>
                Copyright © 2017 陕西熊猫人网络科技有限公司 嘿市网 版权所有
                <br>
                陕ICP备17015228号-1
            </div>
        </div>
    </div>
</template>

<script>
    import Nav from './nav/Nav.vue'

    export default {
        data() {
            return {}
        },
        mounted() {
            let redirect = localStorage.getItem('redirect');
            if (redirect && redirect !== "/") {
                localStorage.setItem('redirect', "/")
                this.$router.push(redirect)
            }
            this._init()
            this.choice_camp()
        },
        watch: {
            '$route'(to, from) {
                this.choice_camp()
            }
        },
        methods: {
            choice_camp() {
                let choice_camp = window.localStorage.getItem('choice_camp')
                this.$store.commit('choice_camp', choice_camp)
            },
            _init() {
                axios.get('user/info').then(res => {
                    this.$store.commit('change_tools', res.data.tools)
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo', res.data.info)
                        if (res.data.info.is_active === 0) {
                            if(res.data.info.camp === 1){
                                this.$Notice.info({
                                    title: '您的帐号还未激活',
                                    desc: '已经发送了一封邮件到您的邮箱，<a target="_blank" href=' + res.data.email + '>点我请去验证</a>。',
                                    duration: 0
                                });
                            }else{
                                this.$Notice.error({
                                    title: '您的帐号还未激活',
                                    desc: '已经发送了一封邮件到您的邮箱，<a target="_blank" href=' + res.data.email + '>点我请去验证</a>。',
                                    duration: 0
                                });
                            }

                        }
                    } else {
                        this.userInfo = '';
                    }
                })
            },
        },
        components: {
            'v-nav': Nav
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .main.content
        width 1240px
        padding: 15px
        margin 0 auto

    .footer
        border-top 1px solid #f5f5f5
        width 1240px
        padding: 15px
        margin 0 auto
        background-color #fff
        text-align center
        display flex
        justify-content center
        .foot
            ul
                margin 0 auto
                li
                    padding 0 5px
                    display inline-block
</style>
