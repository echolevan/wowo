<template>
    <div class="user_pay">
        <Tabs value="1" @on-click="get_orders_history"
              :class="{'bl_tab_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
            <Tab-pane label="充值金币" name="1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        金币余额 : <span style="font-size: 16px" class="normal_font"
                                     :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{userInfo.gold}}</span>
                        <v-withdraw style="float: right"></v-withdraw>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="choice_type">
                            <Radio-group v-model="type">
                                <Radio label="2">
                                    <a href="javascript:void(0);" @click="choice_type(2)">
                                        <i class="wx"></i>
                                    </a>
                                </Radio>
                                <Radio label="1">
                                    <a href="javascript:void(0);" @click="choice_type(1)">
                                        <i class="zfb"></i>
                                    </a>
                                </Radio>
                            </Radio-group>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default choice_money">
                    <div class="panel-body" style="padding: 30px 0 0 0">

                        <div class="my_btn_wrapper"
                             @click="pay(30)"
                             @mouseenter="hover(30)"
                             @mouseleave="hover(1)"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">30元</div>
                        </div>


                        <div class="my_btn_wrapper"
                             @click="pay(50)"
                             @mouseenter="hover(50)"
                             @mouseleave="hover(1)"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">50元</div>
                        </div>


                        <div class="my_btn_wrapper"
                             @click="pay(100)"
                             @mouseenter="hover(100)"
                             @mouseleave="hover(1)"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">100元</div>
                        </div>

                    </div>
                    <div class="panel-body">

                        <div class="my_btn_wrapper">
                            <span v-if="is_money === 30" class="normal_big_font"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">= 300 金币 <span
                                    v-if="lv">+ {{lv.giving * 300 / 100}} 金币</span></span>
                            <span v-else>= 300 金币 <span v-if="lv">+ {{Math.floor(lv.giving * 300 / 100)}} 金币</span> </span>
                        </div>

                        <div class="my_btn_wrapper">
                            <span v-if="is_money === 50" class="normal_big_font"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">= 500 金币 <span
                                    v-if="lv">+ {{lv.giving * 500 / 100}} 金币</span></span>
                            <span v-else>= 500 金币 <span v-if="lv">+ {{Math.floor(lv.giving * 500 / 100)}} 金币</span></span>
                        </div>

                        <div class="my_btn_wrapper">
                            <span v-if="is_money === 100" class="normal_big_font"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">= 1000 金币 <span
                                    v-if="lv">+ {{lv.giving * 1000 / 100}} 金币</span></span>
                            <span v-else>= 1000 金币 <span v-if="lv">+ {{Math.floor(lv.giving * 1000 / 100)}} 金币</span></span>
                        </div>

                    </div>

                    <div class="panel-body">

                        <div class="my_btn_wrapper"
                             @click="pay(200)"
                             @mouseenter="hover(200)"
                             @mouseleave="hover(1)"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">200元</div>
                        </div>

                        <div class="my_btn_wrapper">
                            <Input-number
                                    :min="1"
                                    v-model="pay_amount_other" style="width: 100px;margin-top: 15px;"
                                    @on-change="change_other"></Input-number>
                        </div>

                        <div class="my_btn_wrapper"
                             @click="pay(pay_amount_other , 0)"
                             @mouseenter="hover(0)"
                             @mouseleave="hover(1)"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">充值其他金额</div>
                        </div>
                    </div>

                    <div class="panel-body" style="padding: 0 0 30px 0">

                        <div class="my_btn_wrapper">
                            <div class="my_btn_wrapper">
                                <span v-if="is_money === 200" class="normal_big_font"
                                      :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">= 2000 金币 <span
                                        v-if="lv">+ {{lv.giving * 2000 / 100}} 金币</span></span>
                                <span v-else>= 2000 金币 <span
                                        v-if="lv">+ {{lv.giving * 2000 / 100}} 金币</span></span>
                            </div>
                        </div>

                        <div class="my_btn_wrapper">
                            <div class="my_btn_wrapper">
                                <span v-if="is_money === 0" class="normal_big_font"
                                      :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">= {{pay_amount_other * 10}} 金币 <span
                                        v-if="lv && pay_amount_other >= 0">+ {{Math.floor(lv.giving * pay_amount_other * 10 / 100)}} 金币</span></span>
                                <span v-else>= {{pay_amount_other * 10}} 金币 <span
                                        v-if="lv && pay_amount_other >= 0">+ {{Math.floor(lv.giving * pay_amount_other * 10 / 100)}} 金币</span></span>
                            </div>
                        </div>

                        <div class="my_btn_wrapper">

                        </div>

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-body">
                        充值比例：1元 = 10金币
                        <br>
                        金币充值即时到帐，永久有效
                        <br>
                        当用户等级达到Lv2(随机)充值可获赠金币
                        <br>
                        当金币数量等同于200人民币时可申请提现(新注册用户30日内不能提现)
                    </div>
                </div>
            </Tab-pane>
            <Tab-pane label="充值记录" name="2" class="pay_his" style="padding: 0 15px">

                <ul class="list" v-if="count > 0">
                    <li class="my_a_style" v-for="v in orders_history">
                        <strong>充值
                            <span class="normal_font"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{v.recharge_amount}}
                                </span> 元 ,
                            获得金币 <span class="normal_font"
                                       :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{v.recharge_gold}}
                                </span> +
                            <span class="normal_font"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{v.giving_gold}}
                                </span>,
                            支付方式 <span class="normal_font"
                                       :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{v.recharge_type === 1 ? '支付宝' : '微信'}}
                                </span>

                        </strong>
                        <span class="time">{{v.created_at}}</span>
                    </li>
                </ul>

                <p class="normal_font"
                   :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                   v-else>暂无记录</p>

                <Page v-show="count > 0" :total="count" size="small" @on-change="change_page" show-total :key="count"
                      style="float: right;margin-top: 30px"
                      :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"></Page>

            </Tab-pane>
            <Tab-pane label="提现记录" name="3" class="pay_his" style="padding: 0 15px">

                <ul class="list" v-if="count > 0">
                    <li  v-for="v in withdraws">
                        <strong>提现
                            <span class="normal_font"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{v.money}}
                                </span> 元
                        </strong>
                        <span v-if="v.status === 9" style="color: #d13030">(提现成功)</span>
                        <span v-else style="color: #d13030">(等待转账)</span>
                        <span class="time">{{v.created_at}}</span>
                    </li>
                </ul>

                <p class="normal_font"
                   :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                   v-else>暂无记录</p>

                <Page v-show="count > 0" :total="count" size="small" @on-change="change_draws_page" show-total :key="count"
                      style="float: right;margin-top: 30px"
                      :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"></Page>
            </Tab-pane>
        </Tabs>

        <div class="wechat_pay" v-show="is_wechat_pay" @click="is_wechat_pay = false">
            <div class="wechat_all">
                <div class="top">
                    <img src="/images/pay/zy1.png" style="margin-bottom: 30px" alt="">
                    <div class="wechat_qr">
                        <img :src="wechat_scan_qr" alt="">
                    </div>
                </div>
                <div class="wechat_logo">
                    <p><a href="https://www.iwowcn.com" target="_blank" style="font-size: 24px;font-weight: bold">嘿市</a></p>
                    <p style="font-size: 14px;color:#ABABAB;font-weight: bold">陕西熊猫人网络科技有限公司</p>
                </div>
            </div>
        </div>

        <Modal v-model="wechat_scan" class="download_pay_model" width="500" style="text-align: center">
            <p slot="header" class="model_title">
                <span>请扫描二维码</span>
            </p>
            <div style="text-align:center">
                <img :src="wechat_scan_qr" alt="">
            </div>
            <div slot="footer" style="text-align: center">
                <span style="font-size: 16px;margin-left: 15px;font-weight: bold;">熊猫人 - 嘿市</span>
                <Button type="primary"
                        class="pull-right"
                        @click="wechat_scan = false">
                    <span>关闭</span>
                </Button>
                <div style="clear: both"></div>
            </div>

        </Modal>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import VWithDraw from './Withdraw.vue'

    export default {
        data() {
            return {
                type: '',
                money: '',
                pay_amount_other: 1,
                is_money: '',
                orders_history: [],
                page: 1,
                size: 10,
                count: 0,
                lv: {},
                withdraws: [],
                wechat_scan: false,
                wechat_scan_qr: '',
                is_wechat_pay: false
            }
        },
        mounted() {
            this.get_lv()
        },
        methods: {
            get_lv() {
                axios.get('user/lv').then(res => {
                    this.lv = res.data.info
                })
            },
            choice_type(num) {
                this.type = num
            },
            change_other() {
                if (!(/^\d+$/.test(this.pay_amount_other))) {
                    this.pay_amount_other = Math.round(this.pay_amount_other)
                }
            },
            hover(num) {
                if (num === 1) {
                    this.is_money = ''
                } else {
                    this.is_money = num
                }
            },
            pay(money, recharge_amount_other = 1) {
                if (!isNaN(money) && !(/^\d+$/.test(this.pay_amount_other))) {
                    myDialog('请选择充值金额' , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                } else if (!this.type) {
                    myDialog('请选择支付方式', (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                } else {
                    axios.post('user/recharge', {
                        recharge_type: this.type,
                        recharge_amount: money,
                        recharge_amount_other: recharge_amount_other
                    }).then(res => {
                        if (res.data.sta === 0) {
                            myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                        } else {
                            if(res.data.type === 'alipay') {
                                myDialog('请在新窗口支付',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                clearInterval(aaa)
                                let aaa = setInterval(()=>{
                                    axios.get(`user/is_pay_ok/${res.data.out_trade_no}`).then(res => {
                                        if(res.data.sta === 1){
                                            clodeMyDialog()
                                            myDialog('支付成功',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                            this.$store.commit('change_userInfo', res.data.info)
                                            clearInterval(aaa)
                                        }
                                    })
                                },1000)
                                window.open(res.data.url);
                            }else {
                                clearInterval(bbb)
                                let bbb = setInterval(()=>{
                                    axios.get(`find_wechat/${res.data.out_trade_no}`).then(res => {
                                        if(res.data.sta === 1){
                                            clodeMyDialog()
                                            myDialog('支付成功',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                            this.$store.commit('change_userInfo', res.data.info)
                                            clearInterval(bbb)
                                            this.is_wechat_pay = false
                                        }
                                    })
                                },1000)
                                this.is_wechat_pay = true
                                this.wechat_scan_qr = res.data.url
                            }
                        }
                    })
                }
            },
            change_page(p) {
                this.page = p
                axios.post(`user/get_orders_history/${this.page}/${this.size}`).then(res => {
                    this.count = res.data.count
                    this.orders_history = res.data.res
                })
            },
            change_draws_page(p){
                this.page = p
                axios.post(`user/get_withdraws/${this.page}/${this.size}`).then(res => {
                    this.count = res.data.count
                    this.withdraws = res.data.res
                })
            },
            get_orders_history(name) {
                if (name === '1') {
                    return false
                }
                if (name === '2') {
                    this.page = 1
                    this.size = 10
                    axios.post(`user/get_orders_history/${this.page}/${this.size}`).then(res => {
                        this.count = res.data.count
                        this.orders_history = res.data.res
                    })
                }
                if (name === '3') {
                    this.page = 1
                    this.size = 10
                    axios.post(`user/get_withdraws/${this.page}/${this.size}`).then(res => {
                        this.count = res.data.count
                        this.withdraws = res.data.res
                    })
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        components: {
            'v-withdraw': VWithDraw
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .user_pay
        .choice_type
            a
                padding: 0 40px 0 105px;
                position relative
                height 50px
                vertical-align: middle;
                display: inline-block;
                .zfb
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    background: url('/images/pay/002.jpg');
                    left: 0;
                .wx
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    background: url('/images/pay/001.jpg');
                    left: 0;
                .wx_font
                    color #5D6A70
                    font-size 16px
                    position absolute
                    top 50%
                    left: 70px;
                    margin-top: -12px;
        .choice_money
            .panel-body
                padding 0
                display flex
                justify-content center
                &:nth-child(2n+1)
                    padding 0
                .my_btn_wrapper
                    width 300px
        .pay_his
            .list
                min-height 320px
                li
                    padding 5px 0
                    width 100%
                    font-size 14px
                    border-bottom 1px solid #f2f2f2
                    .time
                        float right
                        font-size 14px

    .wechat_pay
        position absolute
        z-index 99999
        height 100%
        width 100%
        left 0
        top 0
        display flex
        align-items center
        justify-content center
        .wechat_all
            border-radius 15px
            text-align center
            width 400px
            -webkit-box-shadow 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)
            box-shadow 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)
            -webkit-transition all .2s ease-in-out
            .top
                border-top-right-radius 15px
                border-top-left-radius 15px
                background #23ac38
                padding 30px 0
                .wechat_close
                    position absolute
                    top 8px
                    right 8px
                    color #fff
                    font-size 16px
                .title
                    font-size 18px
                    color #fff
                    letter-spacing 2px
                    margin-bottom 30px
                    span
                        font-weight bold
                .wechat_qr
                    width 310px
                    margin 0 auto
                    background #fff
                    padding 5px
                    img
                        width 300px
                        height 300px
            .wechat_logo
                width 100%
                padding 15px 0
                border-bottom-right-radius 15px
                border-bottom-left-radius 15px
                background-color #fff



</style>
