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
                        <p><span>{{plug.type_name}}</span><span>{{plug.tag_one ? plug.tag_one.name : ''}}</span><span v-if="plug.tag_two"> > </span><span>{{plug.tag_two ? plug.tag_two.name : ''}}</span><span>版本号： {{plug.version}}</span></p>

                    </div>
                </iCol>
                <iCol span="8" class="score_down">
                    <strong>当前评分： <span :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">{{plug.score}}</span></strong>
                    <div class="my_btn_wrapper" @click="download(plug.id)" :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"> <svg height="45" width="150">
                        <rect class="button_one" height="45" width="150"></rect>
                    </svg>
                        <div class="button_one_text">下载</div>
                    </div>
                </iCol>
            </Row>

            <div class="thumb_view">
                <div class="title"  :class="{'bl_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                    软件截图
                </div>
                <div style="clear:both"></div>
                <img class="preview-img" v-for="(item, index) in list" :src="item.src" width="100" height="100" @click="$preview.open(index, list)">
            </div>


            <div class="info_content">
                <Row>
                    <iCol span="18">
                        <Tabs value="1" :class="{'bl_tab_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            <Tab-pane label="详情说明" name="1" v-html="plug.info"></Tab-pane>
                            <Tab-pane label="更新日志" name="2" style="padding: 15px">
                                <ul>
                                    <li v-for="v in updated_infos">
                                        <p class="time" style="font-size:14px;color:#333"><strong>{{v.created_at}}</strong></p>
                                        <p>{{v.updated_info}}</p>
                                    </li>
                                </ul>
                            </Tab-pane>
                            <Tab-pane label="历史版本" name="3">
                                <p v-if="history_plugs.length <= 0">暂无历史版本</p>
                                <table class="table" v-else>
                                    <thead>
                                    <tr style="background: #333;color:#fff">
                                        <th>版本链接</th>
                                        <th>版本号</th>
                                        <th>对应游戏版本</th>
                                        <th>更新日期</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr style="background: #fff" v-for="v in history_plugs">
                                        <td>
                                            <router-link :to="{name:'plug.info' , params:{id: v.id}}">
                                                <a href="" class="link_a">{{ v.version }}</a>
                                            </router-link>
                                        </td>
                                        <td>{{ v.version }}</td>
                                        <td>{{ v.game_version }}</td>
                                        <td>{{ v.create_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </Tab-pane>
                        </Tabs>
                    </iCol>
                    <iCol span="6">
                        <div class="tool">
                            <div class="num">
                                <span @click="collect_this(plug.id)" v-if="plug.collect_plug.length === 0">收藏： {{plug.collect_num}}</span>
                                <span v-else>已收藏： {{plug.collect_num}}</span>
                                <span @click="like_this(plug.id)"  v-if="plug.like_plug.length === 0">点赞： {{plug.like_num}}</span>
                                <span v-else>已点赞： {{plug.like_num}}</span>
                            </div>
                        </div>

                        <div class="plug_sim_info">
                            <p>最后更新：<span>{{plug.created_at}}</span></p>
                            <p>最新版本号：<span>{{plug.version}}</span></p>
                            <p>插件作者：<span>{{plug.user.name}}</span></p>
                        </div>
                        <v-rank></v-rank>
                    </iCol>
                </Row>

            </div>
        </div>

        <Modal v-model="download_model" width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <span>{{down_plug.title}}</span>
            </p>
            <div style="text-align:center">
                <p class="plug_info" v-html="down_plug.content"></p>
            </div>
            <div slot="footer">
                <div class="my_btn_wrapper clipboard"
                     :data-clipboard-text="down_plug.content"
                     @click="clipboard"
                     style="width: 100%" :class="{'bl_my_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"> <svg height="45" width="150">
                    <rect class="button_one" height="45" width="150"></rect>
                </svg>
                    <div class="button_one_text">点我复制</div>
                </div>
                <div style="clear: both"></div>
            </div>
        </Modal>

    </div>
</template>

<script>
    import Rank from '../common/Rank.vue'
    import { mapState } from 'vuex'
    import Clipboard from 'clipboard'
    export default {
        data() {
            return {
                list: [],
                plug:{
                    thumbs: [],
                    user: {},
                    collect_plug: [],
                    like_plug: [],
                },
                updated_infos:[],
                history_plugs:[],
                download_model: false,
                down_plug:{
                    title:'',
                    content:''
                },
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        watch: {
            '$route' (to, from) {
                this._init()
            }
        },
        mounted() {
          this._init()
        },
        methods: {
            _init() {
                this.download_model = false
                axios.get(`plugInfo/${this.$route.params.id}`).then(res=>{
                    this.plug = res.data.plug
                    this.list = res.data.thumb_list
                    this.updated_infos = res.data.updated_info
                    this.history_plugs = res.data.history
                })
            },
            collect_this(id){
                axios.get(`collect_this/${id}`).then(res=>{
                    if(res.data.sta === 0){
                        this.$Message.error(res.data.msg)
                    }else{
                        this.plug.collect_plug.push([1])
                        this.plug.collect_num++
                    }
                })
            },
            like_this(id){
                axios.get(`like_this/${id}`).then(res=>{
                    if(res.data.sta === 0){
                        this.$Message.error(res.data.msg)
                    }else{
                        this.plug.like_plug.push([1])
                        this.plug.like_num++
                    }
                })
            },
            download(id){
                axios.get(`download/plug/${id}`).then(res=>{
                    console.log(res)
                    if(res.data.sta === 0){
                        this.$Message.error(res.data.msg)
                    }else{
                        if(res.data.type === 1){
                            // 弹出model
                            this.down_plug = res.data.info
                            this.download_model = true
                        }else if (res.data.type === 2){
                            // 跳转 下载
                            window.location.href= res.data.info.content;
                        }else{
                            // 收费插件 跳转到支付页面
                        }
                        console.log(res)
                    }
                })
            },
            clipboard() {
                const clipboard = new Clipboard('.clipboard')
                clipboard.on('success', function(e) {
                })
                this.$Message.success('复制成功')
            }
        },
        components:{
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
</style>
