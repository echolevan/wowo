<template>
    <div>
        <Row>
            <!--快捷分享-->
            <iCol span="24" style="padding:0 0 15px 0">
                <div class="tool_user_child child">
                    <iCol span="19" style="padding-right: 15px">
                        <Input  v-model="content" type="textarea" :rows="8" placeholder="请输入字符串" class="w_input" ></Input>
                        <p class="pull-right "
                        >共 <span class="normal_font"
                                 :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                 style="font-weight:bold"
                        >{{content.length}}</span> 字符 </p>
                    </iCol>
                    <iCol span="5">
                        <div  style="width: 250px;margin:0 auto;height: 193px;position: relative">
                            <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="type"
                                      @on-change="on_sel" placeholder="请选择职业分类" class="w_input"></Cascader>

                            <Button type="ghost" class="pull-right"
                                    style="margin-top:15px;font-size: 15px;"
                                    @click="quick_share"
                            >快速分享</Button>
                            <div style="clear: both"></div>
                            <Button type="ghost"
                                    style="position:absolute;left: 0;bottom: 0;font-size: 15px;"
                                    @click="quick_share_plug('addons' , '整合界面')"
                            >整合界面分享</Button>
                            <Button type="ghost"
                                    style="position:absolute;right: 0;bottom: 0;font-size: 15px;"
                                    @click="quick_share_plug('addons' , '原创插件')"
                            >原创插件分享</Button>
                        </div>

                    </iCol>
                    <div style="clear: both"></div>
                </div>
            </iCol>
            <!--最新主题-->
            <iCol span="19" style="padding-right: 15px">
                <div class="div_block my_card_hover" style="margin-left: 0">
                    <div class="tool_user title">
                        <strong>最新主题</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul class="strong_big">
                            <li v-for="v in recent_plugs">
                                <router-link :title="v.title"
                                             class="my_a_style"
                                             :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class=" normal_font_hover"
                                            :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                            style="padding-left: 10px;">{{v.title}}</strong>
                                </router-link>
                                <span class="pull-right">{{v.user.nickname}} - {{v.d_n}}次下载 - <span :style="todd_time === v.created_at ? 'color:#d13030' : ''">{{v.created_at}}</span></span>
                                <div style="clear:both"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--网站公告-->
            <iCol span="5">
                <div class="div_block my_card_hover" style="width: 250px;margin:0 auto">
                    <div class="tool_user title tool_title">
                        <strong class="hover_hand"
                                :class="{'title_hover': is_title_hover === 1,'bl_border_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                v-on:mouseenter="is_title_hover = 1"
                        >网站公告</strong>
                        <strong class="hover_hand"
                                :class="{'title_hover': is_title_hover === 2,'bl_border_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                v-on:mouseenter="is_title_hover = 2"
                        >网站统计</strong>
                    </div>
                    <div class="tool_user_child child" style="height: 100px">
                        <span v-if="is_title_hover === 1">{{tools.notice}}</span>
                        <ul v-else>
                            <div class="col-md-6" style="padding: 0">
                                <li>WA资源：{{census.was_count}}</li>
                                <li>ElvUI资源：{{census.elvui_count}}</li>
                            </div>
                            <div class="col-md-6" style="padding: 0">
                                <li>TMW资源：{{census.tmws_count}}</li>
                                <li>魔兽插件：{{census.youxi_count}}</li>
                            </div>
                            <div class="col-md-12" style="padding: 0">
                                <li>资源总数：<span style="color: rgb(209, 48, 48);font-weight: bold">{{census.plugs_count}}</span></li>
                            </div>
                            <div style="clear: both"></div>
                        </ul>
                    </div>
                </div>

                <div class="div_block my_card_hover" style="width: 250px;margin:15px auto;">
                    <div class="tool_user title tool_title">
                        <strong>用户统计</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <div class="col-md-6">
                                <li>用户总数：{{census.useCount}}</li>
                                <li>联盟用户：{{census.lm_count}}</li>
                            </div>
                            <div class="col-md-6">
                                <li>今日访问：{{total_person}}</li>
                                <li>部落用户：{{census.bl_count}}</li>
                            </div>
                            <div class="col-md-12">
                                <li>欢迎新会员：{{new_user}}</li>
                            </div>
                            <div style="clear: both"></div>
                        </ul>
                    </div>
                </div>
                <div class="div_block zf_div hover_hand" @click="modal_zj = true" style="margin-left: 0">
                    <img src="/images/pay/paypal.png" alt="">
                </div>
                <a v-if="ads[2]" :href="ads[2].link ? ads[2].link : 'javascript:void(0)'" target="_blank" >
                    <img  :src="ads[2].url[0].url" :style="{'width': ads[2].width + 'px' , 'height': ads[2].height + 'px'}" alt="">
                </a>
            </iCol>
            <div style="clear: both"></div>
            <!--WeakAuras-->
            <iCol span="9">
                <div class="div_block my_card_hover" style="margin-left: 0">
                    <div class="tool_user title">
                        <strong>WeakAuras</strong>
                        <router-link to="/resources/wa" class="pull-right my_a_style"
                                     style="padding-right: 10px;font-size: 12px;width: 40px">更多
                        </router-link>
                    </div>
                    <div class="tool_user_child child">
                        <ul class="strong_mid">
                            <li v-for="v in was">
                                <router-link :title="v.title"
                                             class="my_a_style"
                                             :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="normal_font_hover"
                                            :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                            style="padding-left: 10px;">{{v.title}}</strong>
                                </router-link>
                                <span class="pull-right">{{v.d_n}}次下载 -  <span :style="todd_time === v.created_at ? 'color:#d13030' : ''">{{v.created_at}}</span></span>
                                <div style="clear: both"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--TellMeWhen-->
            <iCol span="10" style="padding-right: 15px">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>TellMeWhen</strong>
                        <router-link to="/resources/tmw" class="pull-right my_a_style"
                                     style="padding-right: 10px;font-size: 12px;width: 40px">更多
                        </router-link>
                    </div>
                    <div class="tool_user_child child">
                        <ul class="strong_mid">
                            <li v-for="v in tmws">
                                <router-link :title="v.title"
                                             class="my_a_style"
                                             :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class=" normal_font_hover"
                                            :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                            style="padding-left: 10px;">{{v.title}}</strong>
                                </router-link>
                                <span class="pull-right">{{v.d_n}}次下载 -  <span :style="todd_time === v.created_at ? 'color:#d13030' : ''">{{v.created_at}}</span></span>
                                <div style="clear: both"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--总下载量排行-->
            <iCol span="5">
                <div class="div_block my_card_hover" style="width: 250px;margin:0 auto">
                    <div class="tool_user title">
                        <strong>总下载量排行</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul class="strong_small">
                            <li v-for="v in download_plugs">
                                <router-link :title="v.title"
                                             class="my_a_style"
                                             :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class=" normal_font_hover"
                                            :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                            style="padding-left: 10px;">{{v.title}}</strong>
                                </router-link>
                                <span class="pull-right"  style="color: #000000">{{v.d_n}}</span>
                                <div style="clear: both"></div>
                                <!--<span class="pull-right">{{v.created_at}}</span>-->
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <div style="clear: both"></div>
            <iCol span="9">
            <div class="div_block my_card_hover" style="margin-left: 0">
                <div class="tool_user title">
                    <strong>ElvUI</strong>
                    <router-link to="/resources/elvui" class="pull-right my_a_style"
                                 style="padding-right: 10px;font-size: 12px;width:40px">更多
                    </router-link>
                </div>
                <div class="tool_user_child child">
                    <ul class="strong_mid">
                        <li v-for="v in elvuis">
                            <router-link :title="v.title"
                                         class="my_a_style"
                                         :to="{name:'plug.info' , params:{id: v.id}}">
                                <Icon type="arrow-right-b"></Icon>
                                <strong class=" normal_font_hover"
                                        :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                        style="padding-left: 10px;">{{v.title}}</strong>
                            </router-link>
                            <span class="pull-right">{{v.d_n}}次下载 -  <span :style="todd_time === v.created_at ? 'color:#d13030' : ''">{{v.created_at}}</span></span>
                            <div style="clear: both"></div>

                        </li>
                    </ul>
                </div>
            </div>
            </iCol>
            <!--魔兽插件-->
            <iCol span="10" style="padding-right: 15px">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>魔兽插件</strong>
                        <router-link to="/resources/addons" class="pull-right my_a_style"
                                     style="padding-right: 10px;font-size: 12px;width:40px">更多
                        </router-link>
                    </div>
                    <div class="tool_user_child child">
                        <ul class="strong_mid">
                            <li v-for="v in plugs">
                                <router-link :title="v.title"
                                             class="my_a_style"
                                             :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class=" normal_font_hover"
                                            :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                            style="padding-left: 10px;">{{v.title}}</strong>
                                </router-link>
                                <span class="pull-right">{{v.d_n}}次下载 -  <span :style="todd_time === v.created_at ? 'color:#d13030' : ''">{{v.created_at}}</span></span>
                                <div style="clear: both"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--月下载量排行-->
            <iCol span="5">
                <div class="div_block my_card_hover" style="width: 250px;margin:0 auto">
                    <div class="tool_user title">
                        <strong>月下载量排行</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul class="strong_small">
                            <li v-for="v in download_plugs_this_mouth">
                                <router-link :title="v.title" class="my_a_style"
                                             :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="normal_font_hover"
                                            :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                            style="padding-left: 10px;">{{v.title}}</strong>
                                    <!--<span class="pull-right">{{v.created_at}}</span>-->
                                </router-link>

                                <span class="pull-right" style="color: #000000">{{v.num}}</span>
                                <div style="clear: both"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <div style="clear: both"></div>
        </Row>

        <div class="jz_div" v-show="modal_zj" @click="modal_zj = false">
            <img src="/images/pay/juanzeng.jpg"   class="jz_img" alt="">
        </div>
    </div>
</template>

<script>
    import Rank from '../common/Rank.vue'
    import {mapState} from 'vuex'
    export default {
        data() {
            return {
                was: [],
                modal_zj: false,
                census: {
                    useCount: 0
                },
                tmws: [],
                plugs: [],
                elvuis: [],
                total_person: '',
                new_user: '',
                recent_plugs: [],
                download_plugs: [],
                download_plugs_this_mouth: [],
                plugs_count: 0,
                this_page: 1,
                content: '',
                plug_tags: [],
                type: [],
                is_title_hover: 1,
                todd_time: ''
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap' , 'tools', 'ads'
        ]),
        watch: {
            content(){
                this.keyUp()
            },
            '$route'(to, from) {
                this.___init()
                this.tag_active = 0
                this.tag_active_pid = 0
            }
        },
        mounted() {
            this.___init()
        },
        methods: {
            keyUp() {
//                this.content = this.content.replace(/[\u4E00-\u9FA5]/g,"")
            },
            quick_share() {
                if(!this.userInfo){
                    myDialog(`请先 <a href="/register" class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">注册</a>
                     <a href="/login"  class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">登录</a>`
                        , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    return false
                }
                if (this.content === '') {
                    myDialog('请输入字符串',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    return false
                }

                if (this.type.length === 0) {
                    myDialog('请选择职业分类',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    return false
                }
                localStorage.setItem('quick_share_content', this.content)
                localStorage.setItem('quick_share_type', this.type)
                this.$router.push('/upload')
            },
            quick_share_plug(type, tag_name = 0){
                if(!this.userInfo){
                    myDialog(`请先 <a href="/register" class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">注册</a>
                     <a href="/login"  class="${(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_font_color' : 'lm_font_color'}">登录</a>`
                        , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    return false
                }
                localStorage.setItem('upload_type',type)
                if(tag_name !== 0){
                    localStorage.setItem('upload_type_name',tag_name)
                }
                this.$router.push("/upload")
            },
            on_sel(v) {
                this.type = v
            },
            ___init() {
                axios.get('plug_all_info_no_login').then(res => {
                    this.plug_tags = res.data.res
                })
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
            // 获取插件数据
            get_plugs() {
                axios.get(`plug_index`).then(res => {
                    this.plugs = res.data.plugs
                    this.was = res.data.was
                    this.tmws = res.data.tmws
                    this.elvuis = res.data.elvuis
                    this.recent_plugs = res.data.recent_plugs
                    this.download_plugs = res.data.download_plugs
                    this.download_plugs_this_mouth = res.data.download_plugs_this_mouth
                    this.census = res.data.census
                    this.total_person = res.data.total_person
                    this.new_user = res.data.new_user
                    this.todd_time = res.data.today_time
                })
            },

            login() {
                localStorage.setItem('redirect', this.$route.path)
                window.location.href = "/login"
            },
        },
        components: {
            'v-rank': Rank
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .div_block
        margin-left 15px
        margin-bottom 15px
        background-color #fff
        &.zf_div
            text-align center
            img
                margin 0 auto
        .title
            background-color #f5f5f5
            padding 5px 10px
            width 100%
            border 1px solid #ddd
            font-size 14px
            &.tool_zz
                background #266ec1
                color #fff
        .child
            padding 5px 10px
            width 100%
            border 1px solid #ddd
            border-top none
            ul
                li
                    padding 5px 0
        .tool_title
            .title_hover
                border-bottom: 1px solid #266ec1
                padding-bottom: 8px

    .jz_div
        width 100%
        height 100%
        background rgba(255,255,255,0.6)
        position fixed
        left 0
        top 0
        z-index 9999
        display flex
        justify-content center
        align-items center
        .jz_img
            position fixed
            z-index 99999


    .strong_mid
        li
            .my_a_style
                &::before
                    margin-left 5px
                i
                    float left
                strong
                    float left
                    overflow: hidden;
                    text-overflow: ellipsis
                    white-space: nowrap
                    max-width 300px
                    display block
    .strong_small
        li
            .my_a_style
                &::before
                    margin-left 5px
                i
                    float left
                strong
                    float left
                    overflow: hidden;
                    text-overflow: ellipsis
                    white-space: nowrap
                    max-width 188px
                    display block

    .strong_big
        li
            .my_a_style
                &::before
                    margin-left 5px
                i
                    float left
                strong
                    float left
                    overflow: hidden;
                    text-overflow: ellipsis
                    white-space: nowrap
                    max-width 650px
                    display block
</style>
