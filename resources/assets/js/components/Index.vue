<template>
    <div>
        <v-nav v-if="$route.name !== 'index'"></v-nav>
        <div class="main content">
            <transition mode="out-in" enter-active-class="animated fadeInLeft"
                        leave-active-class="animated fadeOutRight">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
    import Nav from './nav/Nav.vue'

    export default {
        data() {
            return {}
        },
        mounted () {
            this._init()
            this.choice_camp()
        },
        watch: {
            '$route' (to, from) {
                this.choice_camp()
            }
        },
        methods: {
            choice_camp() {
                let choice_camp = window.localStorage.getItem('choice_camp')
                this.$store.commit('choice_camp',choice_camp)
            },
            _init() {
                axios.get('user/info').then(res => {
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo',res.data.info)
                        if(res.data.info.is_active === 0){
                            this.$Notice.open({
                                title: '您的帐号还未激活',
                                desc: '已经发送了一封邮件到您的邮箱，请去验证。',
                                duration: 0
                            });
                        }
                    }else{
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
        margin 20px auto

</style>
