<template>
    <div>
        <Row>
            <iCol span="4">
                <div class="tool_plug">
                    <ul>
                        <li :class="{'active':tag_active === 0 , 'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                            @click="change_tag(0, 0)">全部插件
                        </li>
                        <div v-if="tags.length > 0" v-for="tag in tags">
                            <li class='down'
                                :class="{'active':tag_active === tag.id,  'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                @click="change_tag(tag.id , tag.pid)">
                                <img :src="tag.thumb" alt="">
                                {{tag.name}}
                            </li>
                            <div class="child" v-if="tag.tags.length > 0">
                                <li v-for="child in tag.tags "
                                    :class="{'active':tag_active === child.id, 'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                    @click="change_tag(child.id , child.pid)">{{child.name}}
                                </li>
                            </div>
                        </div>
                        <div class="my_btn_wrapper"
                             @click="upload_plug($route.params.type)" v-if="$route.params.type !== 'plug'"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">字符串分享</div>
                        </div>
                        <div v-else>
                            <div class="my_btn_wrapper"
                                 @click="upload_plug($route.params.type , '整合界面')"
                                 :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                                <svg height="45" width="150">
                                    <rect class="button_one" height="45" width="150"></rect>
                                </svg>
                                <div class="button_one_text">整合界面分享</div>
                            </div>
                            <div class="my_btn_wrapper"
                                 @click="upload_plug($route.params.type ,'原创插件')"
                                 style="margin-top:5px"
                                 :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                                <svg height="45" width="150">
                                    <rect class="button_one" height="45" width="150"></rect>
                                </svg>
                                <div class="button_one_text">原创插件分享</div>
                            </div>
                        </div>
                    </ul>
                </div>
            </iCol>
            <iCol span="14">
                <div class="plug_content">
                    <div class="sel">
                        排序：
                        <Select v-model="orderBy" size="small" style="width:100px" @on-change="change_order"
                                :class="{'bl_sel_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <Option v-for="item in orderByList" :value="item.value" :key="item.value">{{ item.label }}
                            </Option>
                        </Select>
                        <Page v-if="plugs.length > 0" ref="pageThis"
                              :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                              :total="plugs_count" size="small" @on-change="change_page" style="float:right" show-total
                              :key="plugs_count"></Page>
                    </div>

                    <div class="content" v-if="plugs.length > 0" v-for="(plug,k) in plugs">
                        <div class="img_view">
                            <img :src="plug.thumbs.length > 0 ? plug.thumbs[0].thumb : ''" alt="">
                        </div>
                        <div class="content_main">
                            <router-link
                                    :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                    :to="{name:'plug.info' , params:{id: plug.id}}">
                                <strong class="my_a_style">{{plug.title}}</strong>
                            </router-link>
                            <span class="pull-right hover_hand" @click="download(plug.id,k)"
                                  style="padding: 5px 15px;background: #266ec1;color:#fff;border-radius:5px"
                                  :class="{'bl_nav_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                            >{{$route.params.type === 'plug' ? '下载 ' : '获取'}}</span>
                            <br>
                            <Icon type="ios-cloud-download-outline"></Icon>
                            <span>{{plug.download_num}}</span>
                            <Icon type="ios-clock-outline"></Icon>
                            <span>{{plug.created_at}}</span>
                            <Icon type="ios-star-outline"></Icon>
                            <span>{{plug.collect_num}}</span>
                            <i><img src="/images/p07.png" alt=""></i><span>{{plug.like_num}}</span>
                            <span
                                    class="normal_font"
                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                    v-if="plug.is_free === 0">免费</span>
                            <span style="padding-right:0" v-else>
                                  <span class="gold_class normal_font"
                                        :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                        style="font-size: 16px;padding-right:0"
                                  >
                                <span v-if="plug.is_pay" class="my_gold"><s>{{plug.gold}}</s></span>
                                <span v-else class="my_gold">{{plug.gold}}</span>
                            </span>
                            </span>
                            <span v-if="plug.is_pay" style="color: rgb(209, 48, 48);">[已购买]</span>
                            <div style="width:600px;max-height: 100px;" class="over_div" v-html="plug.info"></div>
                        </div>
                    </div>
                    <div class="content tt_center" v-show="plugs_count === 0"><strong
                            style="margin:0 auto;font-size: 16px">暂无内容</strong></div>

                    <div class="sel sel_bottom">
                        <Page v-if="plugs.length > 0" ref="pageTwo"
                              :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                              :total="plugs_count" size="small" @on-change="change_page" style="float:right" show-total
                              :key="plugs_count"></Page>
                    </div>

                </div>
            </iCol>
            <iCol span="6">
                <div class="tool_right">
                    <div class="search">
                        <Input v-model="keyword" @keyup.enter.native="to_search" placeholder="搜索标题" icon="search"
                               @on-click="to_search"></Input>
                    </div>
                    <v-rank></v-rank>
                </div>
            </iCol>
        </Row>

        <Modal v-model="download_model" width="720">
            <p slot="header" class="model_title"
               style="text-align:center">
                <span
                        class="normal_font"
                        :class="{'bl_model_span_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >{{down_plug.title}}</span>
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
            <p slot="header" class="model_title "
               style="text-align:center">
                <span class="normal_font"
                      :class="{'bl_model_span_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >{{down_plug.title}}</span>
            </p>
            <div style="text-align:left">
                <ul>
                    <li>资源售价
                        <span class="gold_class normal_font my_gold" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{down_plug.gold}}</span>
                    </li>
                    <li>提示：此非实物交易，购买后不退款，请考虑好再购买</li>
                    <li style="padding-top: 15px" v-if="!userInfo">
                        <a class="gold_class normal_font"
                           :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                           href="javascript:void(0)" @click="login">请先登录</a>
                    </li>
                    <li style="padding-top: 15px" v-else>
                        您的金币余额：
                        <span class="gold_class normal_font" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{userInfo.gold}}</span>
                        <br>
                        <span v-if="userInfo.gold >= down_plug.gold">
                             支付成功后余额：
                            <span class="gold_class normal_font" style="font-size: 16px"
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
                        <span class="gold_class normal_font" style="font-size: 16px"
                              :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <span v-if="pay_amount > 0">{{ pay_amount }}</span>
                            <span v-else>{{pay_amount_other}}</span>
                        </span>
                        元
                        将会获得
                        <span class="gold_class normal_font" style="font-size: 16px"
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
                <Poptip
                        confirm
                        title="您确认购买吗？"
                        @on-ok="toLoading(plug_id)">
                    <Button type="primary" :loading="loading"
                            :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                        <span>购买</span>
                    </Button>
                </Poptip>

            </div>

        </Modal>

        <Modal v-model="wechat_scan" class="download_pay_model" width="500" style="text-align: center">
            <p slot="header" class="model_title">
                <span>请扫描二维码</span>
            </p>
            <div style="text-align:center">
                <img :src="wechat_scan_qr" alt="">
            </div>
            <div slot="footer">
                <Button type="primary"
                        @click="wechat_scan = false">
                    <span>关闭</span>
                </Button>
            </div>

        </Modal>
    </div>
</template>

<script>
    import Rank from '../common/Rank.vue'
    import Clipboard from 'clipboard'
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                orderByList: [
                    {
                        value: '1',
                        label: '更新时间',
                    },
                    {
                        value: '2',
                        label: '下载量',
                    },
                    {
                        value: '3',
                        label: '推荐度',
                    }
                ],
                tags: [],
                orderBy: '1',
                keyword: '',
                tag_active: 0,
                tag_active_pid: 0,
                plugs: [],
                plugs_count: -1,
                this_page: 1,
                download_model: false,
                download_pay_model: false,
                loading: false,
                pay_loding: false,
                down_plug: {
                    title: '',
                    content: ''
                },
                plug_id: '',
                plug_key: '',
                pay_type: 1,
                pay_amount: 10,
                pay_amount_other: 1,
                wechat_scan: false,
                wechat_scan_qr: ''
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'lv'
        ]),
        watch: {
            '$route'(to, from) {
                this._init()
            }
        },
        mounted() {
            $(document).on("click", ".down", function () {
                $(this).siblings(".child").toggle(300).parent().siblings().children(".child").hide();
            })
            this._init()
        },
        methods: {
            to_search() {
                this.get_plugs();
            },
            _init() {
                axios.get(`tag/${this.$route.params.type}`).then(res => {
                    this.tags = res.data
                })
                $(`#${this.$route.params.type}`).removeClass('active')
                $(".cd-morph-dropdown").removeClass('is-dropdown-visible')
                this.tag_active = this.$route.params.active ? this.$route.params.active : 0
                this.tag_active_pid = this.$route.params.active_pid ? this.$route.params.active_pid : 0
                this.get_plugs()
            },
            change_tag(id, pid) {
                let old_tag_id = this.tag_active
                this.tag_active = id
                this.tag_active_pid = pid

                if (old_tag_id !== id) {
                    this.get_plugs();
                }
            },
            // 更改排序
            change_order() {
                this.get_plugs();
            },
            // 更改分页
            change_page(p) {
                this.$refs.pageThis.currentPage = p
                this.$refs.pageTwo.currentPage = p
                this.this_page = p
                this.get_plugs();
            },
            // 得到插件数据
            get_plugs() {
                axios.post(`plug/${this.$route.params.type}`, {
                    orderBy: this.orderBy,
                    tag_active: this.tag_active,
                    tag_active_pid: this.tag_active_pid,
                    page: this.this_page,
                    keyword: this.keyword
                }).then(res => {
                    this.plugs = res.data.plugs
                    this.plugs_count = res.data.count
                    this.this_page = 1
                })
                setTimeout(() => {
                    $('li.active').parent('.child').show('300')
                }, 600)
            },

            upload_plug(type, tag_name = 0) {
                if (this.$store.state.userInfo) {
                    localStorage.setItem('upload_type', type)
                    if (tag_name !== 0) {
                        localStorage.setItem('upload_type_name', tag_name)
                    }
                    this.$router.push("/upload")
                } else {
                    myDialog(`请先 <a href="/register" class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">注册</a>
                     <a href="/login"  class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">登录</a>`
                        , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                }
            },
            download(id, k) {
                axios.get(`download/plug/${id}`).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg)
                    } else {
                        this.plug_id = id
                        this.plug_key = k
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
                        this.plugs[this.plug_key].is_pay = 1
                        this.download(id, this.plug_key)
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
                        if(res.data.type === 'alipay') {
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
                        }else {
                            let bbb = setInterval(()=>{
                                axios.get(`find_wechat/${res.data.out_trade_no}`).then(res => {
                                    if(res.data.sta === 1){
                                        clodeMyDialog()
                                        myDialog('支付成功')
                                        this.$store.commit('change_userInfo', res.data.info)
                                        clearInterval(bbb)
                                        this.wechat_scan = false
                                    }
                                })
                            },1000)
                            this.wechat_scan = true
                            this.wechat_scan_qr = res.data.url
                        }
                    }
                })
                this.pay_loding = false

            },
            change_other() {
                if (!(/^\d+$/.test(this.pay_amount_other))) {
                    this.pay_amount_other = Math.round(this.pay_amount_other)
                }
            },
        },
        components: {
            'v-rank': Rank
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .tool_plug
        padding-right 15px
        /*border 1px solid #f2f2f2*/
        h3
            font-size 18px
            padding 15px 0
        img
            background-color: #333;
            width 25px
            height 25px
        ul
            width 100% !important
            li
                padding 8px 24px
                &:hover
                    cursor pointer
                    color #266ec1
                &.active
                    color #266ec1
                    border-right 2px solid #266ec1
            .child
                display none
                li
                    padding-left 48px !important

    .plug_content
        padding 0 15px
        .sel
            margin-bottom 15px
        .content
            width 100%
            padding 15px 0
            display flex
            border-bottom 1px solid #ddd
            a
                color #555
                &:hover
                    color #266ec1
            .img_view
                width 60px
                height 60px
                margin-right 15px
                img
                    width 60px
                    height 60px
            .content_main
                flex 1
                strong
                i
                    margin 5px 0
                    font-size 16px
                    padding-right 5px
                span
                    padding-right 15px
                button
                    float right
                p
                    width 100%
                    word-wrap break-word
                    word-break break-all
        .sel_bottom
            margin-top 15px

    .tool_right
        padding-left 15px
        .search
            margin-bottom 15px
</style>
