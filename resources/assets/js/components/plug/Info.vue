<template>
    <div>
        <div class="plug_info">
            <Row style="border-bottom: 1px solid #ddd">
                <iCol span="16" class="title">
                    <div class="img">
                        <img :src="plug.thumbs.length > 0 ? plug.thumbs[0].thumb : ''" alt="">
                    </div>
                    <div class="info">
                        <strong>{{plug.title}}</strong>
                        <p>
                            <span>
                                <router-link :to="{name: 'waTmw.index' , params:{type: configUrl[plug.type_name]}}">
                                    {{plug.type_name}}
                                </router-link>
                            </span>
                            <span class="hover_hand" @click="to_waTmw(plug.tag_one.id)">{{plug.tag_one ? plug.tag_one.name : ''}}</span>
                            <span v-if="plug.tag_two"> > </span><span  class="hover_hand" @click="to_waTmw(plug.tag_two.id,plug.tag_one.id)">{{plug.tag_two ? plug.tag_two.name : ''}}</span>
                            <span>版本号： {{plug.version}}</span>
                            <span v-if="plug.is_free === 0">免费</span><span v-else>需消耗
                            <span class="gold_class"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                  style="font-size: 16px">
                                <span v-if="plug.is_pay"><s>{{plug.gold}}</s></span>
                                <span v-else>{{plug.gold}}</span>
                            </span>
                            金币</span>
                            <span v-if="plug.is_pay">[已购买]</span>
                        </p>
                    </div>
                </iCol>
                <iCol span="8" class="score_down">
                    <strong>下载次数： <span
                            :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{plug.download_num}}</span></strong>
                    <div class="my_btn_wrapper" @click="download(plug.id)"
                         :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                        <svg height="45" width="150">
                            <rect class="button_one" height="45" width="150"></rect>
                        </svg>
                        <div class="button_one_text">下载</div>
                    </div>
                </iCol>
            </Row>

            <div class="thumb_view">
                <div class="title"
                     :class="{'bl_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                    截图预览
                </div>
                <div style="clear:both"></div>
                <img class="preview-img" v-for="(item, index) in list" :src="item.src" width="100" height="100"
                     @click="$preview.open(index, list)">
            </div>


            <div class="info_content">
                <Row>
                    <iCol span="18">
                        <Tabs value="1"
                              :class="{'bl_tab_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <Tab-pane label="功能简介" name="1" class="plug_info_div" v-html="plug.info"></Tab-pane>
                            <Tab-pane label="更新日志" name="2" style="padding: 15px">
                                <ul>
                                    <li v-for="v in updated_infos">
                                        <p class="time" style="font-size:14px;color:#333">
                                            <strong>{{v.created_at}}</strong></p>
                                        <p v-html="v.updated_info"></p>
                                    </li>
                                </ul>
                            </Tab-pane>
                            <Tab-pane label="所有版本" name="3">
                                <table class="table">
                                    <thead>
                                    <tr class="table_tr"
                                        :class="{'bl_nav_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                                        <th>历史版本</th>
                                        <th>版本号</th>
                                        <th>游戏版本</th>
                                        <th>更新日期</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="v in plug.historys">
                                        <td>
                                            <router-link :to="{name:'plug.info' , params:{id: v.id}}">
                                                <a href="" class="link_a">{{v.type}} - {{ v.version }}</a>
                                            </router-link>
                                        </td>
                                        <td>{{ v.version }}</td>
                                        <td>{{ v.game_version }}</td>
                                        <td>{{ v.created_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </Tab-pane>
                        </Tabs>
                    </iCol>
                    <iCol span="6">
                        <div class="tool">
                            <div class="num">
                                <span @click="collect_this(plug.plug_id)"
                                      v-if="plug.collect_plug === 0">收藏： {{plug.collect_num}}</span>
                                <span v-else @click="no_collect_this(plug.plug_id)">已收藏： {{plug.collect_num}}</span>

                                <span @click="like_this(plug.plug_id)"
                                      v-if="plug.like_plug === 0">推荐： {{plug.like_num}}</span>
                                <span v-else>已推荐： {{plug.like_num}}</span>
                            </div>
                        </div>
                        <div class="plug_sim_info">
                            <p>最后更新：<span>{{plug.created_at}}</span></p>
                            <p>最新版本：<span v-if="plug.historys">{{plug.historys[0].version}}</span></p>
                            <div v-if="plug.author">
                                <p>插件作者：<span>{{plug.author}}</span></p>
                                <p>上传者：<span>{{plug.user.nickname}}</span></p>
                            </div>
                            <div v-else>
                                <p>插件作者：<span>{{plug.user.nickname}}</span></p>
                                <p>联系作者：<span><a :href="'mailto:'+plug.user.email">{{plug.user.email}}</a></span></p>
                            </div>
                        </div>
                        <v-rank></v-rank>
                    </iCol>
                </Row>

            </div>
        </div>

        <Modal v-model="download_model" width="720">
            <p slot="header" class="model_title"
               :class="{'bl_model_span_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
               style="color:#f60;text-align:center">
                <span>{{down_plug.title}}</span>
            </p>
            <div>
                <p class="plug_info" v-html="down_plug.content"></p>
            </div>
            <div slot="footer">
                <div class="my_btn_wrapper clipboard"
                     :data-clipboard-text="down_plug.content"
                     @click="clipboard"
                     style="width: 100%"
                     :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                    <svg height="45" width="150">
                        <rect class="button_one" height="45" width="150"></rect>
                    </svg>
                    <div class="button_one_text">复制</div>
                </div>
                <div style="clear: both"></div>
            </div>
        </Modal>


        <Modal v-model="download_pay_model" class="download_pay_model" width="720">
            <p slot="header" class="model_title"
               :class="{'bl_model_span_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
               style="color:#f60;text-align:center">
                <span>{{down_plug.title}}</span>
            </p>
            <div style="text-align:left">
                <div class="title">资源购买</div>
                <ul>
                    <li>此资源售价
                        <span class="gold_class" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{down_plug.gold}}</span>
                        金币
                    </li>
                    <li>提示：此非实物交易，购买后不退款，请考虑好再购买</li>
                    <li style="padding-top: 15px" v-if="!userInfo">
                        <a class="gold_class"
                           :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                           href="javascript:void(0)" @click="login">请先登录</a>
                    </li>
                    <li style="padding-top: 15px" v-else>
                        您的金币余额：
                        <span class="gold_class" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{userInfo.gold}}</span>
                        <br>
                        <span v-if="userInfo.gold >= down_plug.gold">
                             支付成功后余额：
                            <span class="gold_class" style="font-size: 16px"
                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                                {{userInfo.gold - down_plug.gold}}
                            </span>
                        </span>
                        <span v-else>您的金币不足,请先充值：</span>
                    </li>
                </ul>

                <div v-show="userInfo && userInfo.gold < down_plug.gold" style="margin-top: 15px">
                    <Radio-group v-model="pay_type" type="button"
                                 :class="{'bl_radio_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                        <Radio label="1" style="height:56px"><img src="/images/pay/002.jpg" alt=""></Radio>
                        <Radio label="2" style="height:56px"><img src="/images/pay/001.jpg" alt=""></Radio>
                    </Radio-group>

                    <p></p>

                    <Radio-group v-model="pay_amount" type="button" style="margin-top: 15px"
                                 :class="{'bl_radio_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                        <Radio label="30">￥30 --- 300金币</Radio>
                        <Radio label="50">￥50 --- 500金币</Radio>
                        <Radio label="100">￥100 --- 1000金币</Radio>
                        <Radio label="200">￥200 --- 2000金币</Radio>
                        <br>
                        <Radio label="0" style="border-left: 1px solid #dddee1;margin: 15px 15px 0 0">其他
                            <!--<span v-show="pay_amount <= 0">：请在左边输入其他金额</span>-->
                        </Radio>
                        <Input-number
                                :min="1"
                                v-model="pay_amount_other" style="width: 100px;margin-top: 15px;"
                                v-show="pay_amount <= 0" @on-change="change_other"></Input-number>
                    </Radio-group>

                    <p style="margin-top: 15px">您需要花费
                        <span class="gold_class" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span v-if="pay_amount > 0">{{ pay_amount }}</span>
                            <span v-else>{{pay_amount_other}}</span>
                        </span>
                        元
                        将会获得
                        <span class="gold_class" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span v-if="pay_amount > 0">{{ pay_amount * 10 }} <span
                                    v-if="lv">+ {{lv.giving * pay_amount * 10 / 100}}</span></span>
                            <span v-else>{{pay_amount_other * 10}} <span
                                    v-if="lv && pay_amount_other >= 1">+ {{Math.floor(lv.giving * pay_amount_other * 10 / 100)}}</span></span>
                        </span>
                        金币
                    </p>

                    <Button type="primary" :loading="pay_loding"
                            :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                            @click="toPay">
                        <span>点击充值</span>
                    </Button>
                </div>

            </div>
            <div slot="footer" v-show="userInfo && userInfo.gold >= down_plug.gold">
                <Button type="primary" :loading="loading"
                        :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                        @click="toLoading(plug.id)">
                    <span>购买</span>
                </Button>
            </div>

        </Modal>

    </div>
</template>

<script>
    import Rank from '../common/Rank.vue'
    import {mapState} from 'vuex'
    import Clipboard from 'clipboard'

    export default {
        data() {
            return {
                list: [],
                plug: {
                    thumbs: [],
                    user: {},
                    collect_plug: [],
                    like_plug: [],
                },
                updated_infos: [],
                download_model: false,
                download_pay_model: false,
                loading: false,
                pay_loding: false,
                down_plug: {
                    title: '',
                    content: ''
                },
                pay_type: 1,
                pay_amount: 10,
                pay_amount_other: 1,
                lv: {},
                configUrl: {
                    'TMW': 'tmw',
                    'WA': 'wa',
                    '游戏插件': 'plug'
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        watch: {
            '$route'(to, from) {
                this._init()
                setTimeout(() => {
                    $(".plug_info_div img").attr("style", "max-width:100%")
                }, 1000)
            }
        },
        mounted() {
            this._init();
            setTimeout(() => {
                $(".plug_info_div img").attr("style", "max-width:100%")
            }, 1000)
        },
        methods: {
            _init() {
                this.download_model = false
                axios.get(`plugInfo/${this.$route.params.id}`).then(res => {
                    this.plug = res.data.plug
                    this.list = res.data.thumb_list
                    this.updated_infos = res.data.updated_info
                })
                axios.get('user/lv').then(res => {
                    this.lv = res.data.info
                })
            },
            collect_this(id) {
                axios.get(`collect_this/${id}`).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        this.plug.collect_plug = 1
                        this.plug.collect_num++
                    }
                })
            },
            no_collect_this(id) {
                axios.get(`no_collect_this/${id}`).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        this.plug.collect_plug = 0
                        this.plug.collect_num--
                    }
                })
            },
            like_this(id) {
                axios.get(`like_this/${id}`).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        this.plug.like_plug = 1
                        this.plug.like_num++
                    }
                })
            },
            download(id) {
                axios.get(`download/plug/${id}`).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        if (res.data.type === 1) {
                            // 弹出model
                            this.down_plug = res.data.info
                            this.download_model = true
                        } else if (res.data.type === 2) {
                            // 跳转 下载
                            window.open(res.data.info.content);
                        } else {
                            this.down_plug = res.data.info
                            this.download_pay_model = true
                            // 收费插件 跳转到支付页面
                        }
                    }
                })
            },
            clipboard() {
                const clipboard = new Clipboard('.clipboard')
                clipboard.on('success', function (e) {
                })
                myDialog('复制成功')
            },
            login() {
                localStorage.setItem('redirect', this.$route.path)
                window.location.href = "/login"
            },
            toLoading(id) {
                this.loading = true
                axios.post('to_pay_plug', {id: id}).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        this.download_pay_model = false
                        this.plug.is_pay = 1
                        this.download(id)
                    }
                })
                this.loading = false
            },
            // pay
            toPay() {
                this.pay_loding = true
                axios.post('user/recharge', {
                    recharge_type: this.pay_type,
                    recharge_amount: this.pay_amount,
                    recharge_amount_other: this.pay_amount_other
                }).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        myDialog('请在新窗口支付')
                        let aaa = setInterval(()=>{
                            axios.get(`user/is_pay_ok/${res.data.out_trade_no}`).then(res => {
                                if(res.data.sta === 1){
                                    clodeMyDialog()
                                    myDialog('支付成功')
                                    this.$store.commit('change_userInfo', res.data.info)
                                    clearInterval(aaa)
                                }
                            })
                        },1000)
                        window.open(res.data.url);
                    }
                })
                this.pay_loding = false

            },
            change_other() {
                if (!(/^\d+$/.test(this.pay_amount_other))) {
                    this.pay_amount_other = Math.round(this.pay_amount_other)
                }
            },
            to_waTmw(id ,pid=''){
                localStorage.setItem('watmw_tag_id',id)
                localStorage.setItem('watmw_tag_pid',pid)
                this.$router.push({name: 'waTmw.index' , params:{type: this.configUrl[this.plug.type_name]}})
            }
        },
        components: {
            'v-rank': Rank
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .plug_info
        .title
            display flex
            padding-bottom 15px
            margin-bottom 15px
            .info
                flex 1
                display flex
                flex-direction: column;
                justify-content: space-between;
                strong
                    font-size 20px
                p
                    span
                        margin 0 5px
            .img
                margin-right 15px
                width 64px
                height 64px
                img
                    border 1px solid #f2f2f2
                    width 64px
                    height 64px
        .score_down
            display flex
            align-items center
            justify-content: space-between;
            strong
                span
                    color #266ec1
                    font-size 20px
            button
                padding 15px 60px
                float right
        .thumb_view
            padding 15px 0
            border-bottom 1px solid #ddd
            .title
                height 36px
                line-height 36px
                float left
                color #266ec1
                font-size 14px
                border-bottom 2px solid #266ec1
                clear left
        .info_content
            .tool
                width 100%
                height 36px
                line-height: 36px
                border-bottom 1px solid #ddd
                .num
                    float right
                    span
                        margin-left 15px
                        cursor pointer
            .plug_sim_info
                padding 15px 0
                border 1px solid #ddd
                border-bottom none
                border-top none
                p
                    padding 5px 15px
                span
                    float right

    .plug_info
        width 100%
        word-wrap break-word
        word-break break-all

    .gold_class
        color #266ec1

    .model_title
        span
            color #266ec1

    .table_tr
        background #266ec1
        color #fff
</style>
