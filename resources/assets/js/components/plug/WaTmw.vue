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
                            <div class="child" :class="{'child_active': tag_active === tag.id || (tag_active_pid !== 0 && tag_active_pid === tag.id)}"   v-if="tag.tags.length > 0">
                                <li v-for="child in tag.tags "
                                    :class="{'active':tag_active === child.id, 'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                    @click="change_tag(child.id , child.pid)">{{child.name}}
                                </li>
                            </div>
                        </div>
                        <div class="my_btn_wrapper"
                             @click="upload_plug($route.params.type)" v-if="$route.params.type !== 'addons'"
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
                            <div class="my_btn_wrapper"
                                 @click="upload_plug($route.params.type ,'怀旧插件')"
                                 style="margin-top:5px"
                                 :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                                <svg height="45" width="150">
                                    <rect class="button_one" height="45" width="150"></rect>
                                </svg>
                                <div class="button_one_text">怀旧插件分享</div>
                            </div>
                        </div>
                    </ul>
                </div>
            </iCol>
            <iCol span="14">
                <div class="plug_content">
                    <a v-if="ads[6]"  :href="ads[6].link ? ads[6].link : 'javascript:void(0)'" target="_blank">
                        <img :src="ads[6].url[0].url" :style="{'width': ads[6].width + 'px' , 'height': ads[6].height + 'px'}" alt=""  style="margin-bottom: 15px">
                    </a>
                    <div class="sel">
                        排序：
                        <Select v-model="orderBy" size="small" style="width:100px" @on-change="change_order"
                                :class="{'bl_sel_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <Option v-for="item in orderByList" :value="item.value" :key="item.value">{{ item.label }}
                            </Option>
                        </Select>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <!--游戏版本：-->
                        <Select v-model="serBy" size="small" style="width:100px" clearable placeholder="所有版本" @on-change="change_order"
                                :class="{'bl_sel_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <Option value="">所有版本</Option>
                            <Option v-for="item in game_version" :value="item" :key="item">{{ item }}
                            </Option>
                        </Select>
                        <Page v-if="plugs.length > 0" ref="pageThis"
                              :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                              :total="plugs_count" size="small" @on-change="change_page" style="float:right" show-total
                              :key="plugs_count"></Page>
                    </div>

                    <div class="content" v-if="plugs.length > 0" v-for="(plug,k) in plugs">
                        <div class="img_view">
                            <img :src="plug.thumbs.length > 0 ? plug.thumbs[0].thumb : '/images/preview.jpg'" alt="">
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
                            >{{$route.params.type === 'addons' ? '下载 ' : '获取'}}</span>
                            <br>
                            <Icon type="ios-cloud-download-outline"></Icon>
                            <span>{{plug.d_n}}</span>
                            <Icon type="ios-clock-outline"></Icon>
                            <span><span :style="today_time === plug.created_at ? 'color:#d13030' : ''">{{plug.created_at}}</span></span>
                            <Icon type="ios-star-outline"></Icon>
                            <span>{{plug.collect_num}}</span>

                            <i><img src="/images/p07.png" alt=""></i><span>{{plug.like_num}}</span>
                            <Icon type="ios-game-controller-b-outline"></Icon>
                            <span>{{plug.game_version}}</span>
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
                            <div style="width:600px;max-height: 100px;" class="over_div" v-html="plug.n_h_c"></div>
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
                        <Input v-model="keyword" @keyup.enter.native="to_search" placeholder="搜索标题、作者" icon="search"
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
                        :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >{{down_plug.title}}</span>
            </p>
            <div>
                <p class="plug_info info_hh" v-html="down_plug.content"></p>
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
                        <Radio label="2" style="height:56px;padding-top: 1.5px;"><img src="/images/pay/001.jpg" alt=""></Radio>
                        <Radio label="1" style="height:56px;padding-top: 1.5px;"><img src="/images/pay/002.jpg" alt=""></Radio>
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
                        title="您确定购买吗？"
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
                serBy: '',
                tag_active: 0,
                tag_active_pid: 0,
                plugs: [],
                game_version: [],
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
                pay_type: 2,
                pay_amount: 30,
                pay_amount_other: 1,
                wechat_scan: false,
                wechat_scan_qr: '',
                hc: 0,
                is_wechat_pay: false,
                today_time: ''
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'lv' ,'ads'
        ]),
        watch: {
            '$route'(to, from) {
                this.___init()
            }
        },
        mounted() {
            this.___init()
        },
        methods: {
            to_search() {
                this.get_plugs();
            },
            ___init() {
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
                if(this.tag_active === id){
                    this.tag_active = ''
                    this.tag_active_pid = ''
                    this.get_plugs();
                    return false
                }
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
                    keyword: this.keyword,
                    serBy: this.serBy
                }).then(res => {
                    this.plugs = res.data.plugs
                    this.plugs_count = res.data.count
                    this.game_version = res.data.game_version
                    this.this_page = 1
                    this.today_time = res.data.today_time
                })
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
                        myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
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
                myDialog('复制成功',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
            },
            login() {
                localStorage.setItem('redirect', this.$route.path)
                window.location.href = "/login"
            },
            toLoading(id) {
                this.loading = true
                axios.post('to_pay_plug', {id: id}).then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
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
                        myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    } else {
                        if(res.data.type === 'alipay') {
                            myDialog('请在新窗口支付<p style=";margin-top: 5px;">(<span style="color: #ed3f14">注意新窗口可能被拦截</span>)</p>',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            clearInterval(this.alipay_inteval)
                            this.alipay_inteval = setInterval(()=>{
                                axios.get(`user/is_pay_ok/${res.data.out_trade_no}`).then(res => {
                                    if(res.data.sta === 1){
                                        clodeMyDialog()
                                        myDialog('支付成功',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                        this.$store.commit('change_userInfo', res.data.info)
                                        clearInterval(this.alipay_inteval)
                                    }
                                })
                            },1000)
                            window.open(res.data.url);
                        }else {
                            clearInterval(this.wechat_inteval)
                            this.wechat_inteval = setInterval(()=>{
                                axios.get(`find_wechat/${res.data.out_trade_no}`).then(res => {
                                    if(res.data.sta === 1){
                                        clodeMyDialog()
                                        myDialog('支付成功',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                        this.$store.commit('change_userInfo', res.data.info)
                                        clearInterval(this.wechat_inteval)
                                        this.is_wechat_pay = false
                                    }
                                })
                            },1000)
                            this.is_wechat_pay = true
                            this.wechat_scan_qr = res.data.url
                        }
                    }
                    this.pay_loding = false
                })
            },
            change_other() {
                if (!(/^\d+$/.test(this.pay_amount_other))) {
                    this.pay_amount_other = Math.round(this.pay_amount_other)
                }
            },
        },
        components: {
            'v-rank': Rank
        },
        beforeDestroy: function () {
            clearInterval(this.alipay_inteval)
            clearInterval(this.wechat_inteval)
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
                &.child_active
                    display block
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
