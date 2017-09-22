<template>
    <div class="user_orders">
        <table class="table table-bordered" v-if="orders.length > 0">
            <div class="td_head_div">
                <div style="width: 50%">资源标题</div>
                <div style="width: 10%" class="tt_center">售价</div>
                <div style="width: 20%" class="tt_center">版本</div>
                <div style="width: 10%" class="tt_center">游戏版本</div>
                <div style="width: 10%" class="tt_center">操作</div>
            </div>
            <div class="td_div" v-for="v in orders">
                <div class="td_child hidden_div title" style="width: 50%">
                    <Poptip :content="v.title" trigger="hover"  placement="bottom">
                        {{v.title}}
                    </Poptip>
                </div>
                <div class="td_child hidden_div tt_center" style="width: 10%" v-if="v.gold === 0">免费</div>
                <div class="td_child tt_center" style="width: 10%" v-else>
                    <span class="my_gold normal_font"
                          :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                    >
                    {{v.gold}}
                </span>
                </div>
                <div class="td_child tt_center" style="width: 20%">{{v.version}}</div>
                <div class="td_child tt_center" style="width: 10%">{{v.game_version}}</div>
                <div class="td_child tool tt_center" style="width: 10%">
                    <router-link class="my_a_style" :to="{name:'plug.info' , params:{id: v.id}}">
                        查看详情
                    </router-link>
                </div>
            </div>
        </table>


        <p class="normal_font"  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" v-else>暂无记录</p>

        <Page  v-show="count > 0" :total="count" size="small"  @on-change="change_page" show-total  :key="count" style="float: right;margin-top: 30px" :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"></Page>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    export default {
        data() {
            return {
                page: 1,
                size: 10,
                count: 0,
                orders: []
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        mounted() {
            this.get_orders();
        },
        methods:{
            // 更改分页
            change_page (p) {
                this.page = p
                this.get_orders();
            },
            get_orders(){
                axios.post(`user/orders/collect/${this.page}/${this.size}`).then(res=>{
                    this.count = res.data.count
                    this.orders = res.data.res
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .user_orders
        .td_head_div
            width 100%
            div
                font-size 14px
                font-weight bold
                float left
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-right: 1px solid #ddd;
                border-bottom: 2px solid #ddd;
        .td_div
            width 100%
            vertical-align: inherit;
            border-color: inherit;
            .td_child
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                float left
                border-right: 1px solid #ddd;
                border-bottom: 1px solid #ddd;
                &.hidden_div
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    overflow: hidden;
                &.title:hover
                    cursor pointer
            .tool
                a
                    padding 0 15px

</style>
