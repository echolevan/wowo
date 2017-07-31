<template>
    <div class="index">
        <div class="title"  v-if="!userInfo">
            选择你的阵营
        </div>
        <div class="choice"  v-if="!userInfo">
            <div class="c_one">
                <img :src="c1" @mouseenter="enter(1)" @mouseleave="leave(1)" @click="choice(1)" alt="">
            </div>
            <div class="c_two">
                <img :src="c2" @mouseenter="enter(2)" @mouseleave="leave(2)"  @click="choice(2)" alt="">
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        data() {
            return {
                c1: '/images/c1.png',
                c2: '/images/c2.png',
            }
        },
        computed: mapState([
            'userInfo'
        ]),
        watch: {
            '$route' (to, from) {
                axios.get('user/info').then(res => {
                    if (res.data.sta === '1') {
                        this.$router.push('/home')
                    }
                })
            }
        },
        mounted() {
            axios.get('user/info').then(res => {
                if (res.data.sta === '1') {
                    this.$router.push('/home')
                }
            })
        },
        methods: {
            enter(k) {
                if(k === 1) {
                    this.c1 = '/images/c1_hover.png'
                }else{
                    this.c2 = '/images/c2_hover.png'
                }
            },
            leave(k) {
                if(k === 1) {
                    this.c1 = '/images/c1.png'
                }else{
                    this.c2 = '/images/c2.png'
                }
            },
            choice(k) {
                window.localStorage.setItem('choice_camp', k);
                this.$router.push('/home')
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .index
        .title
            font-size 50px
            text-align center
            margin 60px 0
        .choice
            display flex
            div
                flex 1
                text-align center
                img
                    margin 0 auto
                    width 300px
                    &:hover
                        cursor pointer

</style>
