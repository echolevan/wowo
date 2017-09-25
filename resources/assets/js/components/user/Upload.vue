<template>
    <div class="user_orders">
        <table class="table table-bordered" v-if="orders.length > 0">
            <div class="td_head_div">
                <div style="width: 22%">资源标题</div>
                <div class="tt_center" style="width: 10%">售价</div>
                <div class="tt_center" style="width: 10%">状态</div>
                <div class="tt_center" style="width: 13%">版本</div>
                <div class="tt_center" style="width: 10%">游戏版本</div>
                <div class="tt_center" style="width: 35%">操作</div>
            </div>
            <div class="td_div" v-for="v in orders">
                <div class="td_child title" style="width: 22%">
                    <Poptip :content="v.title" trigger="hover"  placement="bottom">
                        <router-link :to="{name:'plug.info' , params:{id: v.id}}">
                            {{v.title}}
                        </router-link>
                    </Poptip>
                </div>
                <div class="td_child tt_center" style="width: 10%" v-if="v.gold === 0">免费</div>
                <div class="td_child tt_center" style="width: 10%" v-else>
                       <span class="my_gold normal_font"
                             :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                       >
                    {{v.gold}}
                </span>
                </div>
                <div class="td_child tt_center" style="width: 10%">{{v.is_check === 1 ? '已审核' : '待审核'}}</div>
                <div class="td_child hover_hand tt_center" style="width: 13%">
                    <Poptip :content="v.version" trigger="hover"  placement="bottom">
                        {{v.version}}
                    </Poptip>
                </div>
                <div class="td_child tt_center" style="width: 10%">{{v.game_version}}</div>
                <div class="td_child tool" style="width: 35%">

                    <router-link class="my_a_style" :to="{name:'update.plug' , params:{id: v.id}}">
                        编辑
                    </router-link>
                    <router-link class="my_a_style" :to="{name:'upload.plug' , params:{id: v.plug_id}}">
                        升级版本
                    </router-link>
                    <a href="javascript:void(0)" class="my_a_style normal_font"
                       :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" @click="p_no_del(v.plug_id)" v-if="v.is_del">
                        撤销删除 剩余<span>{{v.is_del.last_time}}</span>小时
                        </a>
                    <Poptip
                            confirm
                            title="您确定删除吗？"
                            @on-ok="p_del(v.plug_id)" v-else>
                        <a href="javascript:void(0)" class="my_a_style">申请删除</a>
                    </Poptip>
                    <!--<a href="javascript:void(0)" class="my_a_style" @click="show_his(v.id)" v-if="v.historys.length > 0">查看历史版本</a>-->
                    <!--<a href="javascript:void(0)" class="my_a_style" v-else>暂无历史版本</a>-->
                </div>
                <!--<transition-group-->
                        <!--mode="out-in" enter-active-class="animated fadeIn"-->
                <!--&gt;-->
                    <!--<div class="td_div td_div_child"  v-if="is_show_his === v.id" v-for="vv in v.historys" :key="vv.id">-->
                        <!--<div class="td_child title" style="width: 20%">-->
                            <!--<Poptip :content="vv.title" trigger="hover"  placement="bottom">-->
                                <!--{{vv.title}}-->
                            <!--</Poptip>-->
                        <!--</div>-->
                        <!--<div class="td_child tt_center" style="width: 10%" v-if="vv.gold === 0">免费</div>-->
                        <!--<div class="td_child tt_center" style="width: 10%" v-else>{{vv.gold}} 金币</div>-->
                        <!--<div class="td_child tt_center" style="width: 10%">{{vv.is_check === 1 ? '已审核' : '待审核'}}</div>-->
                        <!--<div class="td_child hover_hand tt_center" style="width: 10%">-->
                            <!--<Poptip :content="vv.version" trigger="hover"  placement="bottom">-->
                                <!--{{vv.version}}-->
                            <!--</Poptip>-->
                        <!--</div>-->
                        <!--<div class="td_child tt_center" style="width: 10%">{{vv.game_version}}</div>-->
                        <!--<div class="td_child tool" style="width: 40%">-->
                            <!--<router-link class="my_a_style" :to="{name:'plug.info' , params:{id: vv.id}}">-->
                                <!--查看详情-->
                            <!--</router-link>-->
                            <!--<router-link class="my_a_style" :to="{name:'update.plug' , params:{id: vv.id}}">-->
                                <!--编辑-->
                            <!--</router-link>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</transition-group>-->
            </div>
        </table>
        <p class="normal_font"
           :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
           v-else>暂无记录</p>
        <Page  v-show="count > 0" :total="count" size="small"  @on-change="change_page" show-total  :key="count" style="float: right;margin-top: 30px" :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"></Page>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                is_show_his: 0,
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
        methods: {
            // 更改分页
            change_page(p) {
                this.page = p
                this.get_orders();
            },
            get_orders() {
                axios.post(`user/orders/upload/${this.page}/${this.size}`).then(res => {
                    this.count = res.data.count
                    this.orders = res.data.res
                })
            },

            show_his(id){
                this.is_show_his = id
            },
            p_del(id){
                axios.get(`p_del/${id}`).then(res => {
                    myDialog('24小时内可撤销，过时将自动删除！',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    this.get_orders()
                })
            },
            p_no_del(id){
                axios.get(`p_no_del/${id}`).then(res => {
                    this.get_orders()
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
        .td_div_child
            .td_child
                background #ddd
                border-right: 1px solid #fff;
                border-bottom: 1px solid #fff;
</style>
