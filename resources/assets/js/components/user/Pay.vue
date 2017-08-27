<template>
    <div class="user_pay">
        <Tabs value="1" @on-click="get_orders_history"
              :class="{'bl_tab_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
            <Tab-pane label="充值金币" name="1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        金币余额 : <span style="font-size: 16px" class="normal_font"
                                     :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{userInfo.gold}}</span>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="choice_type">
                            <Radio-group v-model="type">
                                <Radio label="1">
                                    <a href="javascript:void(0);" @click="choice_type(1)">
                                        <i class="zfb"></i>
                                    </a>
                                </Radio>
                                <Radio label="2">
                                    <a href="javascript:void(0);" @click="choice_type(2)">
                                        <i class="wx"></i>
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
                        当金币数量等同于200人民币时即可申请提现(新注册用户30日内不能申请提现)
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
        </Tabs>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

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
                lv: {}
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
                    myDialog('请选择充值金额')
                } else if (!this.type) {
                    myDialog('请选择充值方式')
                } else {
                    axios.post('user/recharge', {
                        recharge_type: this.type,
                        recharge_amount: money,
                        recharge_amount_other: recharge_amount_other
                    }).then(res => {
                        if (res.data.sta === 0) {
                            myDialog(res.data.msg)
                        } else {
                            this.$store.commit('change_userInfo', res.data.info)
                            myDialog(res.data.msg)
                        }
                    })
                }
            },
            change_page(p) {
                this.page = p
                this.get_orders_history(2);
            },
            get_orders_history(name) {
                if (name === '1') {
                    return false
                }
                axios.post(`user/get_orders_history/${this.page}/${this.size}`).then(res => {
                    this.count = res.data.count
                    this.orders_history = res.data.res
                })
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ])
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
</style>
