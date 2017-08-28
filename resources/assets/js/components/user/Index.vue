<template>
    <div class="user_card">
        <div class="single-member effect-1 card">
            <div class="member-image"
                 :class="{'bl_border_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                <img :src="userInfo.avatar" alt="Member">
            </div>
            <div class="member-info">
                <p class="nickname">{{userInfo.nickname}}</p>
                <p class="camp" v-if="userInfo.camp === 1">阵营：联盟</p>
                <p class="camp" v-else>阵营：部落</p>
                <p v-if="userInfo.info">{{userInfo.info}}</p>
                <div v-else>
                    <p v-if="userInfo.camp === 1">为了联盟</p>
                    <p v-else>为了部落</p>
                </div>
            </div>

            <div class="user_tool">
                <ul>
                    <li>
                        <router-link to="/userInfo/info" class="r-l my_a_style" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">个人信息</router-link>
                    </li>
                    <li>
                        <router-link to="/userInfo/setting" class="r-l my_a_style" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">修改资料</router-link>
                    </li>
                    <li>
                        <router-link to="/userInfo/orders" class="r-l my_a_style" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">购买记录</router-link>
                    </li>
                    <li>
                        <router-link to="/userInfo/pay" class="r-l my_a_style" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">充值中心</router-link>
                    </li>
                    <li>
                        <router-link to="/userInfo/collect" class="r-l my_a_style" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">已收藏资源</router-link>

                    </li>
                    <li>
                        <router-link to="/userInfo/upload" class="r-l my_a_style" :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">已分享资源</router-link>
                    </li>
                    <li>
                        <a href="/admin/#/admin" class="r-l my_a_style"
                           v-show="userInfo.is_admin === 1"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">进入后台管理</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="info">
            <transition>
                <router-view></router-view>
            </transition>
        </div>
        <div style="clear: both"></div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {}
        },
        watch: {
            userInfo(){
                if(!this.userInfo){
                    this.$router.push("/home")
                }
            }
        },
        mounted(){
            setTimeout(()=>{
                if(!this.userInfo){
                    this.$router.push("/home")
                }
            },500)
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../common/card.css"
    .user_card
        min-height 700px
        .card
            .member-image
                img
                    width 150px;
                    height 150px
        .nickname
            font-size 16px
            font-weight 600
        .camp
            font-size 14px
        .user_tool
            padding-left 50px
            margin-top 50px
            text-align left
            ul
                li
                    font-size 14px
                    padding 5px 10px
        .info
            width 80%
            float left
            padding 0 15px
</style>
