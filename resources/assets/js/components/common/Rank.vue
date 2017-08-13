<template>
    <div>
        <div class="download_rank">
            <div class="title">
                <strong>下载排行榜</strong>
                <!--<span>More</span>-->
            </div>
            <ul>
                <li v-for="(v , k) in rank_download">
                    <span :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" class="num">{{  k+1 }}</span>
                    <router-link :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                        <span style="color: #333 !important;background-color: #fff !important;"  class="tit my_a_style">{{v.title}}</span>
                    </router-link>
                    <span class="dig">{{v.simple_info}}</span>
                    <span class="size">{{v.download_num}}</span>
                </li>
            </ul>
        </div>

        <div class="start_rank">
            <div class="title">
                <strong>评分排行榜</strong>
                <!--<span>More</span>-->
            </div>
            <ul>
                <li v-for="(v , k) in rank_score">
                    <span :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" class="num">{{  k+1 }}</span>
                    <router-link :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                        <span style="color: #333 !important;background-color: #fff !important;"  class="tit my_a_style">{{v.title}}</span>
                    </router-link>
                    <span class="dig">{{v.simple_info}}</span>
                    <span class="score" :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" >{{v.score}}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        data() {
            return {
                rank_download:[],
                rank_score:[],
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        watch: {
            '$route' (to, from) {
                const toDepth = to.path.split('/').length
                this._init()
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get(`plugRank/${this.$route.params.type}`).then(res=>{
                    this.rank_download = res.data.rank_download
                    this.rank_score = res.data.rank_score
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .download_rank
        border-bottom none !important

    .download_rank, .start_rank
        border 1px solid #ddd
        div.title
            color #333
            padding 5px 15px
            font-size 14px
            border-bottom 1px solid #ddd
            span
                float: right
                color #999999
        ul
            li
                height: 56px;
                position: relative;
                a
                    color #333
                    &:hover
                        color #266ec1
                &:after
                    content: "";
                    height: 24px;
                    width: 2px;
                    position: absolute;
                    top: 16px;
                    left: 0;
                    display: none;
                .num
                    position: absolute;
                    top: 0;
                    left: 2px;
                    width: 30px;
                    text-align: center;
                    height: 100%;
                    line-height: 56px;
                    color: #266ec1;
            .tit
                display: block;
                position: relative;
                left 30px
                top: 10px;
            .dig
                color: #999999;
                display: block;
                position: relative;
                top: 10px;
                left 30px
                width: 210px;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
            .size
                color: #808080;
                position: absolute;
                top: 10px;
                right: 10px;
            .score
                font-size: 20px;
                color: #266ec1;
                position: absolute;
                right: 10px;
                top: 0;
                height: 100%;
                display: block;
                line-height: 56px;
</style>
