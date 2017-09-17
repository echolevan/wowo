<template>
    <div class="bm">
        <div class="search pull-right">
            <Form :model="formS" inline>
                <Form-item>
                    <Select v-model="formS.type" clearable placeholder="下载方式" style="width: 100px;"
                            @on-change="to_search">
                        <Option v-for="(v , k) in configBmDownloadType" :value="k" :key="k">{{v}}</Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Select v-model="formS.zy_type" clearable placeholder="资源类型" style="width: 100px;"
                            @on-change="to_search">
                        <Option v-for="(v , k) in configBmType" :value="k" :key="k">{{v}}</Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Input v-model="formS.title" @keyup.enter.native="to_search" placeholder="搜索标题" icon="search"
                           @on-click="to_search"></Input>
                </Form-item>
            </Form>
        </div>
        <div style="clear: both"></div>
        <ul class="search_list" v-if="list.length > 0">
            <li  v-for="(v, k) in list" >
                <strong>
                <a href="javascript:void(0)" @click="down_bm(v.id, k)"
                ><span>[{{configBmDownloadType[v.type]}}]</span><span >[{{configBmType[v.zy_type]}}]</span>
                    {{v.title}}</a>
                -
                <span v-if="v.gold === 0" class="normal_font"
                      :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >[免费]</span>
                <span v-else>
                      <span v-if="v.order" class="normal_font "
                            :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                      >[<span class="my_gold"><s>{{v.gold}}</s></span>]</span>
                                <span class="normal_font"
                                      :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                      v-else>[<span class="my_gold">{{v.gold}}</span>]</span>
                                <span v-if="v.order" style="color: #d13030">[已购买]</span>
                </span>
            </strong>
                <span class="pull-right" style="padding-left: 5px">
                <span :style="today === v.created_at ? 'color:#d13030' : ''">{{v.created_at}}</span>
                </span> <span class="pull-right">{{v.user.name}} - {{v.download_num}}次下载 - </span>
                <br>
                <div style="clear:both"></div>
            </li>

        </ul>
        <div style="width:100%;text-align:center;font-size:16px" v-else>
            <strong >暂无数据</strong>
        </div>
        <div class="sel sel_bottom" style="margin-top:15px">
            <Page :page-size="page_size"
                  :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                  :total="plugs_count" size="small" @on-change="change_page" style="float:right" show-total
                  :key="plugs_count"></Page>
        </div>
        <div style="clear:both"></div>


        <div class="dialog_sb dialog--open" v-show="download_model">
            <div class="dialog__overlay"></div>
            <div class="dialog__content  animated fadeIn" style="border-radius: 2px">
                <h2 v-if="down_k"   class="normal_font"
                      style="margin-bottom: 5px;padding: 0"
                      :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >{{list[down_k].title}}</h2>
                <h2>确定购买?</h2>
                <div>
                    <button type="button" class="close_dialog" style="border-radius: 2px;background:#393d49;color:#fff;"
                            @click="download_model = false">取消
                    </button>
                    <button type="button" class="close_dialog ivu-btn-primary"
                            :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                            style="border-radius: 2px" @click="go_download(down_id, down_k)">确定
                    </button>
                </div>
            </div>
        </div>

        <div class="dialog_sb dialog--open" v-show="!is_show_this">
            <div class="dialog__overlay"></div>
            <div class="dialog__content dialog__tell_you animated fadeIn" style="border-radius: 2px">
                <div class="title_one">
                    公告
                </div>
                <div class="title_two" v-html="tools.bm_notice">

                </div>
                <div class="title_thr">
                    <div class="hover_hand ivu-btn-primary"
                         :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                         @click="no_show_this">不再显示</div>
                    <div class="i_know_button hover_hand" @click="i_know_this">我知道了</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                list: [],
                plugs_count: 0,
                this_page: 1,
                page_size: 20,
                configBmDownloadType: configBmDownloadType,
                configBmType: configBmType,
                formS: {
                    title: '',
                    type: '',
                    status: '',
                    zy_type: '',
                    orderBySome: 'created_at',
                    orderByF: 'desc'
                },
                download_model: false,
                down_id: 0,
                down_k: '',
                today: '',
                is_show_this: ''
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap' , 'tools'
        ]),
        watch: {
            userInfo() {
                if (!this.userInfo) {
                    myDialog(`请先 <a href="/register" class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">注册</a>
                     <a href="/login"  class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">登录</a>`
                        , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    this.$router.push('/home')
                }
            }
        },
        mounted() {
            this.is_show_this = localStorage.getItem('no_show_this')
            this.get_plugs()
        },
        methods: {
            no_show_this() {
              localStorage.setItem('no_show_this',1)
                this.is_show_this = 1
            },
            i_know_this() {
              this.is_show_this = 1
            },
            change_page(p) {
                this.this_page = p
                this.get_plugs()
            },
            get_plugs() {
                axios.post(`/bm/list/${this.this_page}/${this.page_size}`, {search: this.formS}).then(res => {
                    console.log(res)
                    this.plugs_count = res.data.count
                    this.list = res.data.list
                    this.today = res.data.today
                })
            },
            to_search() {
                this.get_plugs();
            },
            down_bm(id, k) {
                axios.get(`bm/check_download/${id}`).then(res => {
                    console.log(res)
                    if (res.data.sta === 1) {
                        this.go_download(id, k)
                    } else if (res.data.sta === 9) {
                        myDialog(`您的金币不足,请先<a href='/#/userInfo/pay' class='close_other_dialog ${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}'>充值</a>`
                            , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    } else if (res.data.sta === 2) {
                        this.down_id = id
                        this.down_k = k
                        this.download_model = true
                    } else {
                        myDialog(res.data.msg)
                    }
                })
            },
            go_download(id, k) {
                axios.get(`bm/download/${id}`).then(res => {
                    if (res.data.sta === 1) {
                        this.list[k].order = 1
                        window.open(res.data.url);
                    } else if (res.data.sta === 9) {
                        myDialog(`您的金币不足,请先<a href='/#/userInfo/pay' class='close_other_dialog ${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}'>充值</a>`
                            , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    } else {
                        myDialog(res.data.msg)
                    }
                    this.download_model = false
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .bm
        .search_list
            li
                width 100%
                padding 5px 0
                border-bottom 1px solid #f2f2f2

    .dialog__tell_you
        padding 0
        text-align left
        .title_one
            font-size: 14px
            padding 5px 15px
        .title_two
            color: #fff
            padding 15px
            font-size 14px
            background-color #393d49
        .title_thr
            padding 5px 0
            display flex
            justify-content center
            text-align center
            div
                width 100px
                height 30px
                font-size: 14px
                line-height 30px
                margin 0 5px
                border-radius 3px
                &.no_show_button
                    background-color #266ec1
                    color #fff
                &.i_know_button
                    background-color #393d49
                    color #fff


</style>
