<template>
    <div>
        <div class="main content">
            <transition mode="out-in" enter-active-class="animated fadeInLeft"
                        leave-active-class="animated fadeOutRight">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {}
        },
        mounted () {

        },
        watch: {

        },
        methods: {

        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .main.content
        width 1240px
        margin 20px auto

</style>
