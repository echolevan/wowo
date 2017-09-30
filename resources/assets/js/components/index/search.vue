<template>
    <div class="search">
        <Alert type="success">结果: 找到 <span style="color: rgb(209, 48, 48)">"{{$route.params.keyword}}"</span>
            相关内容 <span style="color: rgb(209, 48, 48)">{{plugs_count}}</span> 个
        </Alert>
        <div class="content">
            <ul>
                <li v-for="v in list">
                    <p class="title">
                    <router-link :to="{'name': 'plug.info' , params:{'id': v.id}}">
                        {{v.title}}
                    </router-link>
                    </p>
                    <p class="num">
                        <Icon type="ios-cloud-download-outline"></Icon>
                        <span>{{v.d_n}}</span>
                        &nbsp;&nbsp;
                        <Icon type="ios-clock-outline"></Icon>
                        <span>{{v.updated_at}}</span>
                        &nbsp;&nbsp;
                        <Icon type="ios-star-outline"></Icon>
                        <span>{{v.collect_num}}</span>
                        &nbsp;&nbsp;
                        <i><img src="/images/p07.png" alt=""></i>&nbsp;<span>{{v.like_num}}</span>
                        &nbsp;&nbsp;
                        <span
                                class="normal_font"
                                :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                v-if="v.is_free === 0">免费</span>
                        <span style="padding-right:0" v-else>
                                  <span class="gold_class normal_font"
                                        :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                        style="font-size: 16px;padding-right:0"
                                  >
                                <span v-if="v.is_pay" class="my_gold"><s>{{v.gold}}</s></span>
                                <span v-else class="my_gold">{{v.gold}}</span>
                            </span>
                            </span>
                        <span v-if="v.is_pay" style="color: rgb(209, 48, 48);">[已购买]</span>
                    </p>
                    <div class="info" v-html="v.info">
                    </div>
                    <p class="tool"><span class="time">{{v.created_at}}</span> &nbsp;&nbsp;<span
                            class="other">{{v.name}}-
                        <router-link :to="{name: 'watmw.index' , params:{type: configUrl[v.type_name]}}">
                                    {{v.type_name}}
                                </router-link>
                        -
                        <router-link :to="{name:'watmw.index' , params:{'type':configUrl[v.type_name] , 'active': v.tag_one.id , 'active_pid': 0}}">{{v.tag_one ? v.tag_one.name : ''}}</router-link>
                        <router-link :to="{name:'watmw.index' , params:{'type':configUrl[v.type_name] , 'active': v.tag_two.id , 'active_pid': v.tag_one.id}}" v-if="v.tag_two">- {{v.tag_two ? v.tag_two.name : ''}}</router-link>
                    </span></p>
                </li>

            </ul>
            <Page :page-size="page_size"
                  :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                  :total="plugs_count" size="small" @on-change="change_page" style="float:right" show-total
                  :key="plugs_count"></Page>
            <div style="clear: both"></div>
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
                page_size: 10,
                configUrl: {
                    'TMW': 'tmw',
                    'WA': 'wa',
                    '游戏插件': 'addons'
                },
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'tools'
        ]),
        mounted() {
            this._init()
        },
        watch: {
            '$route'(to, from) {
                this._init()
            }
        },
        methods: {
            _init() {
                axios.post('plug_search', {
                    keyword: this.$route.params.keyword,
                    page: this.this_page,
                    size: this.page_size
                }).then(res => {
                    console.log(res)
                    this.list = res.data.list
                    this.plugs_count = res.data.count
                })
            },
            change_page(p) {
                this.this_page = p
                this._init()
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .search
        .content
            ul
                li
                    margin-bottom 30px
                    .title
                        font-size 16px
                        font-weight bold
                    .num
                    .info
                        max-height: 50px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        margin-bottom 5px
                    .tool
                        .time
                            color #42b983
                        .other
                            color #928B85


</style>
