<template>
    <div class="user_info">
        <ul class="info_list">
            <li><span class="title">嘿市ID</span><span class="val">{{userInfo.id}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">用户名</span><span class="val">{{userInfo.name}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">昵称</span><span class="val">{{userInfo.nickname}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">性别</span><span class="val">{{userInfo.sex === 0 ? '保密' : userInfo.sex === 1 ? '男' : '女'}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">等级</span>
                <Poptip v-if="lv" trigger="hover" :content="`充值将获赠送${lv.giving}% 金币`" placement="bottom">
                    <span class="hover_hand">{{lv.name}}</span>
                </Poptip>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">阵营</span><span class="val"  v-if="userInfo.camp === 1">联盟</span><span class="val" v-else>部落</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">简介</span><span class="val" v-if="userInfo.info">{{userInfo.info}}</span><span class="val" v-else-if="userInfo.camp === 1">为了联盟</span><span class="val" v-else>为了部落</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">金币</span><span style="font-size: 14px;font-weight: bold" class="val normal_font normal_font_hover"
               :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
            >{{userInfo.gold}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">邮箱</span><span class="val">
                <Button type="success"  size="small" v-if="userInfo.is_active === 1">
                    <Tooltip content="邮箱已经激活">
                        {{userInfo.email}}
                    </Tooltip>
                </Button>
                   <Button type="warning" :disabled="is_dis"  size="small" @click="send_mail" v-else>
                        <Tooltip>
                              <div slot="content">
                                <p>邮箱未激活</p>
                                <p><i>点击重新发送邮件</i></p>
                            </div>
                            <span v-if="!is_dis">{{userInfo.email}}</span>
                            <span v-else>邮件已经发送，{{rest_time}} s后可再次发送</span>
                        </Tooltip>
                    </Button>
                </span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">手机</span>
                <span class="val hover_hand" v-if="userInfo.tel === '0'" @click="to_setting">点击立即绑定</span>
                <span class="val" v-else>{{userInfo.tel}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">生日</span>
                <span class="val hover_hand" v-if="!userInfo.birthday" @click="to_setting">点击立即设置</span>
                <span class="val" v-else>{{userInfo.birthday}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">出生地</span>
                <span class="val hover_hand" v-if="!userInfo.birthplace || userInfo.birthplace.province === ''" @click="to_setting">点击立即设置</span>
                <span class="val" v-else>{{(userInfo.birthplace.province ? userInfo.birthplace.province : '未知省') + '-' + (userInfo.birthplace.city ? userInfo.birthplace.city : '未知市') + '-' + (userInfo.birthplace.area ? userInfo.birthplace.area : '未知区')}}</span>
                <div style="clear: both"></div>
            </li>
            <li><span class="title">居住地</span>
                <span class="val hover_hand" v-if="!userInfo.habitably || userInfo.habitably.province === ''" @click="to_setting">点击立即设置</span>
                <span class="val" v-else>{{(userInfo.habitably.province ? userInfo.habitably.province : '未知省') + '-' + (userInfo.habitably.city ? userInfo.habitably.city : '未知市') + '-' + (userInfo.habitably.area ? userInfo.habitably.area : '未知区')}}</span>
                <div style="clear: both"></div>
            </li>
        </ul>
        <!--<div class="user_info_card">-->
        <!--<div class="zd"></div>-->
        <!--<div class="info">-->
        <!--<h1 :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{userInfo.name}}</h1>-->
        <!--<p :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">嘿市ID：{{userInfo.id + 100000}}</p>-->
        <!--<p :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" v-if="userInfo.camp === 1">联盟</p>-->
        <!--<p :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" v-else>部落</p>-->
        <!--</div>-->
        <!--<div class="bac_img_lm" :class="{'bac_img_bl': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">-->
        <!--</div>-->
        <!--<div class="user_avatar">-->
        <!--<img :src="userInfo.avatar" class="img-circle" alt="">-->
        <!--</div>-->
        <!--<img v-if="(userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')" src="/images/blcard.png" alt="">-->
        <!--<img src="/images/lmcard.png" alt=""  v-else>-->
        <!--</div>-->
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                rest_time: 60,
                is_dis: false,
                lv: {}
            }
        },
        mounted() {
          this.get_lv()
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        methods: {
            to_setting() {
              this.$router.push('/userInfo/setting')
            },
            send_mail() {
                this.rest_time = 60
                this.is_dis = true
                this.sub_time()
                axios.get('user/send_mail').then(res=>{
                    if(res.data.sta === 0){
                        myDialog(res.data.msg)
                        if(res.data.timeOut){
                            this.rest_time = res.data.timeOut
                        }
                    }
                })
            },
            sub_time(){
                setInterval(()=>{
                    this.rest_time --
                    this.check_is_out()
                },1000)
            },
            check_is_out(){
                if(this.rest_time <= 0){
                    this.is_dis = false
                }
            },
            get_lv(){
                axios.get('user/lv').then(res => {
                    this.lv = res.data.info
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .user_info
        .info_list
            li
                width 100%
                padding 10px 0
                .title
                    font-size 16px
                    width 8%
                    float left
                    font-weight bold
                 .val
                     width 92%
                     float left
                     font-size 12px
                     word-wrap:break-word
        .user_info_card
            position relative
            width 960px
            height 540px
            border 1px solid #ddd
        .zd
            width 960px
            height 540px
            position absolute
            z-index 1
            filter: blur(10px)
            background: rgba(88, 87, 86, .3)
        img
            width 960px
            height 540px
        .info
            position absolute
            left 300px
            top 100px
            font-size 30px
            color #266ec1
            z-index 2
        .bac_img_lm
            position absolute
            right 0
            top 0
            width 300px
            height 540px
            background url("/images/c1_hover.png") no-repeat center
            background-size contain
        .bac_img_bl
            position absolute
            right 0
            top 0
            width 300px
            height 540px
            background url("/images/c2_hover.png") no-repeat center
            background-size contain
        .user_avatar
            position absolute
            right 80px
            top 200px
            z-index 2
            img
                width 150px
                height 150px
</style>
