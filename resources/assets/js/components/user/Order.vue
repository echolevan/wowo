<template>
    <div class="user_orders">
        <table class="table table-bordered" v-if="orders.length > 0">
            <div class="td_head_div">
                <div style="width: 40%">插件名称</div>
                <div style="width: 10%">花费</div>
                <div style="width: 10%">插件版本</div>
                <div style="width: 10%">游戏版本</div>
                <div style="width: 30%">操作</div>

            </div>
            <div class="td_div" v-for="(v,k) in orders">
                <div class="td_child title" style="width: 40%">
                    <Poptip :content="v.plug.title" trigger="hover"  placement="bottom">
                        {{v.plug.title}}
                    </Poptip>
                </div>
                <div class="td_child" style="width: 10%" v-if="v.plug.wwb === 0">免费</div>
                <div class="td_child" style="width: 10%" v-else>{{v.plug.wwb}} 金币</div>
                <div class="td_child" style="width: 10%">{{v.plug.version}}</div>
                <div class="td_child" style="width: 10%">{{v.plug.game_version}}</div>
                <div class="td_child tool" style="width: 30%;position: relative">
                    <router-link class="my_a_style" :to="{name:'plug.info' , params:{id: v.plug.id}}">
                        查看详情
                    </router-link>
                        <span>评分：</span>
                    <Rate allow-half v-model="score[k]" style="position: absolute;top: 2px" @on-change="rate_score(v.plug.plug_id, k)" v-if="v.score.length === 0"></Rate>
                    <span v-else>{{v.score[0].score}}</span>
                </div>
            </div>
        </table>

        <p class="normal_font"  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" v-else>暂无记录</p>
        <div style="clear: both"></div>
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
                orders: [],
                score: []
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
                axios.post(`user/orders/pay/${this.page}/${this.size}`).then(res=>{
                    console.log(res)
                    this.count = res.data.count
                    this.orders = res.data.res
                })
            },
            rate_score(id , k){
                axios.post(`rate_score/${id}`,{score:this.score[k]}).then(res=>{
                    if(res.data.sta === 0){
                        myDialog(res.data.msg)
                    }else{
                        myDialog(res.data.msg)
                        this.orders[k].score.push({score:this.score[k] * 2})
                    }
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
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                &.title:hover
                    cursor pointer
            .tool
                a
                    padding 0 15px
</style>
