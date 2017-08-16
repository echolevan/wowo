<template>
    <div>
        <Row>
            <!--快捷分享-->
            <iCol span="24">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>快捷分享</strong>
                    </div>
                    <div class="tool_user_child child">
                        <iCol span="16">
                            <Input v-model="content" type="textarea" :rows="8" placeholder="请输入"></Input>
                        </iCol>
                        <iCol span="1">
                            &nbsp;
                        </iCol>
                        <iCol span="7">
                            <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="type"
                                      @on-change="on_sel" placeholder="请输入插件分类"></Cascader>
                            <a href="javascript:void(0)" class="pull-right my_a_style" style="padding: 15px">提交</a>
                        </iCol>
                        <div style="clear: both"></div>
                    </div>
                </div>
            </iCol>

            <!--最新主题-->
            <iCol span="16">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>最新主题</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li v-for="v in recent_plugs">
                                <router-link :title="v.title" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="my_a_style" style="padding-left: 10px;">{{v.title.substring(0,60)}}</strong>
                                    <span class="pull-right">{{v.created_at}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--网站公告-->
            <iCol span="8">
                <div class="div_block my_card_hover">
                    <div class="tool_user title tool_title">
                        <strong class="hover_hand" :class="{'title_hover': is_title_hover === 1,'bl_border_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                v-on:mouseenter="is_title_hover = 1"
                        >网站公告</strong>
                        <strong class="hover_hand" :class="{'title_hover': is_title_hover === 2,'bl_border_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                v-on:mouseenter="is_title_hover = 2"
                        >网站统计</strong>
                    </div>
                    <div class="tool_user_child child">
                       <span v-if="is_title_hover === 1">23232</span>
                        <ul v-else>
                            <li>资源总数：{{census.plugs_count}}</li>
                            <li>WA资源：{{census.was_count}}</li>
                            <li>TMW资源：{{census.tmws_count}}</li>
                            <li>今日更新：{{census.today_count}}</li>
                            <li>最近更新：{{census.last_time}}</li>
                        </ul>
                    </div>
                </div>

                <div class="div_block my_card_hover">
                    <div class="tool_user title tool_title">
                        <strong>用户统计</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li>用户总数：{{census.user_count}}</li>
                            <li>联盟用户：{{census.lm_count}}</li>
                            <li>部落用户：{{census.bl_count}}</li>
                        </ul>
                    </div>
                </div>
                <div class="div_block zf_div">
                    <img src="/images/pay/paypal.png" alt="">
                </div>
            </iCol>
            <div style="clear: both"></div>
            <!--魔兽插件-->
            <iCol span="8">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>魔兽插件</strong>
                        <router-link to="/waTmw/plug" class="pull-right my_a_style" style="padding-right: 10px;font-size: 12px">更多</router-link>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li v-for="v in plugs">
                                <router-link :title="v.title" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="my_a_style" style="padding-left: 10px;">{{v.title.substring(0,20)}}</strong>
                                    <span class="pull-right">{{v.created_at}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <iCol span="8">
                &nbsp;
            </iCol>
            <!--总下载量排行-->
            <iCol span="8">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>总下载量排行</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li v-for="v in download_plugs">
                                <router-link :title="v.title" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="my_a_style" style="padding-left: 10px;">{{v.title.substring(0,20)}}</strong>
                                    <span class="pull-right">{{v.created_at}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <div style="clear: both"></div>
            <!--WeakAuras-->
            <iCol span="8">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>WeakAuras</strong>
                        <router-link to="/waTmw/wa" class="pull-right my_a_style" style="padding-right: 10px;font-size: 12px">更多</router-link>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li v-for="v in was">
                                <router-link :title="v.title" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="my_a_style" style="padding-left: 10px;">{{v.title.substring(0,20)}}</strong>
                                    <span class="pull-right">{{v.created_at}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--TellMeWhen-->
            <iCol span="8">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>TellMeWhen</strong>
                        <router-link to="/waTmw/twm" class="pull-right my_a_style" style="padding-right: 10px;font-size: 12px">更多</router-link>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li v-for="v in twms">
                                <router-link :title="v.title" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="my_a_style" style="padding-left: 10px;">{{v.title.substring(0,20)}}</strong>
                                    <span class="pull-right">{{v.created_at}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </iCol>
            <!--月下载量排行-->
            <iCol span="8">
                <div class="div_block my_card_hover">
                    <div class="tool_user title">
                        <strong>月下载量排行</strong>
                    </div>
                    <div class="tool_user_child child">
                        <ul>
                            <li v-for="v in download_plugs_this_mouth">
                                <router-link :title="v.title" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                                    <Icon type="arrow-right-b"></Icon>
                                    <strong class="my_a_style" style="padding-left: 10px;">{{v.title.substring(0,20)}}</strong>
                                    <span class="pull-right">{{v.created_at}}</span>
                                </router-link>
                            </li>
                        </ul>
                    </div>
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
                was: [],
                census: {},
                twms: [],
                plugs: [],
                recent_plugs: [],
                download_plugs: [],
                download_plugs_this_mouth: [],
                plugs_count: 0,
                this_page: 1,
                content: '',
                plug_tags: [],
                type: [],
                is_title_hover: 1
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
            on_sel(v) {
                this.type = v
            },
            _init() {
                axios.get('plug_all_info_no_login').then(res => {
                    this.plug_tags = res.data
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
            // 得到插件数据
            get_plugs(){
                axios.get(`plug_index`).then(res=>{
                    this.plugs = res.data.plugs
                    this.was = res.data.was
                    this.twms = res.data.twms
                    this.recent_plugs = res.data.recent_plugs
                    this.download_plugs = res.data.download_plugs
                    this.download_plugs_this_mouth = res.data.download_plugs_this_mouth
                    this.census = res.data.census
                })
            },

            login(){
                localStorage.setItem('redirect',this.$route.path)
                window.location.href="/login"
            },
        },
        components:{
            'v-rank': Rank
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .div_block
        margin-left  15px
        margin-bottom 15px
        &.zf_div
            text-align center
            img
                margin 0 auto
        .title
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
</style>
