<template>
    <div>
        <Row>
            <iCol span="4">
                <div class="tool_plug">
                    <ul>
                        <li :class="{'active':tag_active === 0 , 'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" @click="change_tag(0, 0)">全部插件</li>
                        <div  v-if="tags.length > 0"  v-for="tag in tags">
                            <li class="down" :class="{'active':tag_active === tag.id , 'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"  @click="change_tag(tag.id , tag.pid)">
                                <img :src="tag.thumb" alt="">
                                {{tag.name}}
                            </li>
                            <div class="child" v-if="tag.tags.length > 0">
                                <li  v-for="child in tag.tags " :class="{'active':tag_active === child.id, 'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"  @click="change_tag(child.id , child.pid)" >{{child.name}}</li>
                            </div>
                        </div>
                        <div class="my_btn_wrapper"
                             @click="upload_plug" v-if="$route.params.type !== 'plug'"
                             :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <svg height="45" width="150">
                                <rect class="button_one" height="45" width="150"></rect>
                            </svg>
                            <div class="button_one_text">分享字符串</div>
                        </div>
                        <div  v-else>
                            <div class="my_btn_wrapper"
                                 @click="upload_plug"
                                 :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                                <svg height="45" width="150">
                                    <rect class="button_one" height="45" width="150"></rect>
                                </svg>
                                <div class="button_one_text">整合界面分享</div>
                            </div>
                            <div class="my_btn_wrapper"
                                 @click="upload_plug"
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
                        <Select v-model="orderBy" size="small" style="width:100px" @on-change="change_order" :class="{'bl_sel_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <Option v-for="item in orderByList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                        </Select>
                    </div>

                    <div class="content"  v-if="plugs.length > 0" v-for="plug in plugs">
                        <div class="img_view">
                            <img :src="plug.thumbs.length > 0 ? plug.thumbs[0].thumb : ''" alt="">
                        </div>
                        <div class="content_main">
                            <router-link :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: plug.id}}">
                                <strong class="my_a_style">{{plug.title}}</strong>
                            </router-link>
                            <br>
                            <Icon type="ios-cloud-download-outline"></Icon><span>{{plug.download_num}}</span>
                            <Icon type="ios-clock-outline"></Icon><span>{{plug.created_at}}</span>
                            <Icon type="ios-star-outline"></Icon><span>{{plug.collect_num}}</span>
                            <i><img src="/images/p07.png" alt=""></i><span>{{plug.like_num}}</span>
                            <div style="width:600px;max-height: 100px;" class="over_div" v-html="plug.info"></div>
                        </div>
                    </div>
                    <div class="sel sel_bottom">
                        <Page  :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                              :total="plugs_count"  size="small" @on-change="change_page" style="float:right" show-total  :key="plugs_count"></Page>
                    </div>

                </div>
            </iCol>
            <iCol span="6">
                <div class="tool_right">
                    <div class="search">
                        <Input v-model="keyword" @keyup.enter.native="to_search"  placeholder="搜索标题"  icon="search" @on-click="to_search"></Input>
                    </div>
                    <v-rank></v-rank>
                </div>
            </iCol>
        </Row>
    </div>
</template>

<script>
    import Rank from '../common/Rank.vue'
    import { mapState } from 'vuex'
    export default {
        data () {
            return {
                orderByList:[
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
                tags:[],
                orderBy: '1',
                keyword: '',
                tag_active: 0,
                tag_active_pid: 0,
                plugs: [],
                plugs_count: 0,
                this_page: 1
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        watch: {
            '$route' (to, from) {
                this._init()
                this.tag_active = 0
                this.tag_active_pid = 0
            }
        },
        mounted () {
            $(document).on("click" , ".down" , function () {
                $(this).siblings(".child").show('300').parent().siblings().children(".child").hide();
            })
          this._init()
        },
        methods: {
            to_search(){
                this.get_plugs();
            },
            _init() {
                axios.get(`tag/${this.$route.params.type}`).then(res=>{
                    this.tags = res.data
                })
               this.get_plugs()
            },
            change_tag(id , pid) {
                let old_tag_id = this.tag_active
                this.tag_active = id
                this.tag_active_pid = pid

                if(old_tag_id !== id){
                    this.get_plugs();
                }
            },
            // 更改排序
            change_order() {
                this.get_plugs();
            },
            // 更改分页
            change_page (p) {
                this.this_page = p
                this.get_plugs();
            },

            // 得到插件数据
            get_plugs(){
                axios.post(`plug/${this.$route.params.type}` , {orderBy:this.orderBy , tag_active:this.tag_active , tag_active_pid: this.tag_active_pid , page:this.this_page , keyword:this.keyword} ).then(res=>{
                    this.plugs = res.data.plugs
                    this.plugs_count = res.data.count
                    this.this_page = 1
                })
            },

            upload_plug(){
                if(this.$store.state.userInfo){
                    this.$router.push("/upload")
                }else{
                    myDialog(`请先 <a href="/register" class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">注册</a>
                     <a href="/login"  class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">登录</a>`
                        , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                }
            }
        },
        components:{
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
                padding 12px 24px
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
