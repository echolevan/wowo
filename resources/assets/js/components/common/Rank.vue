<template>
    <div>
        <div class="download_rank">
            <div class="title">
                <strong>下载排行榜</strong>
                <!--<span>More</span>-->
            </div>
            <ul class="strong_small_rank">
                <li v-for="(v , k) in rank_download">
                    <span :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" class="num">{{  k+1 }}</span>
                    <span style="display:block;width:250px;height:18px">
                        <router-link class="my_a_style float_pos" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                        <strong style="background-color: #fff !important;"  class="tit  normal_font_hover" :title="v.title"
                                :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                        >{{v.title}}</strong>
                    </router-link>
                    </span>
                    <span class="dig" v-html="v.n_h_c"></span>
                    <span class="size">{{v.d_n}}</span>
                </li>
            </ul>
        </div>

        <div class="start_rank">
            <div class="title">
                <strong>推荐排行榜</strong>
                <!--<span>More</span>-->
            </div>
            <ul class="strong_small_rank">
                <li v-for="(v , k) in rank_score">
                    <span :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" class="num">{{  k+1 }}</span>
                    <span style="display:block;width:250px;height:18px">
                        <router-link class="my_a_style float_pos" :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :to="{name:'plug.info' , params:{id: v.id}}">
                        <strong style="background-color: #fff !important;"  class="tit  normal_font_hover" :title="v.title"
                                :class="{'bl_hover_line_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                        >{{v.title}}</strong>
                        </router-link>
                    </span>
                    <span class="dig" v-html="v.n_h_c"></span>
                    <span class="size">{{v.like_num}}</span>
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
                this.___init()
            }
        },
        mounted() {
            this.___init()
        },
        methods: {
            ___init() {
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
                max-width: 230px;
                white-space: nowrap;
                text-overflow: ellipsis;
            .dig
                color: #999999;
                display: block;
                position: relative;
                top: 10px;
                height: 20px;
                left 30px
                width: 210px;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
            .size
                color: #000000;
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


    .strong_small_rank
        li
            strong
                float left
                overflow: hidden;
                text-overflow: ellipsis
                white-space: nowrap
                max-width 188px
                display block

    .float_pos
        position: relative;
        left: 30px;
        top: 10px;

</style>
