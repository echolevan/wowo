<template>
    <div class="bm">
        <div class="search pull-right">
            <Input v-model="formS.title" @keyup.enter.native="to_search"  placeholder="搜索标题"  icon="search" @on-click="to_search"></Input>
        </div>
        <div style="clear: both"></div>
        <ul>
            <li v-for="v in list"><strong>
                <a :href="v.url" target="_blank"><span style="padding-right: 2px">[{{configBtType[v.type]}}]</span>{{v.title}}</a>
            </strong> <span class="pull-right" style="padding-left: 5px">{{v.created_at}}</span> <span class="pull-right">{{v.user.name}}</span></li>
        </ul>

        <div class="sel sel_bottom">
            <Page :page-size="page_size" :class="{'bl_page_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}" :total="plugs_count"  size="small" @on-change="change_page" style="float:right" show-total  :key="plugs_count"></Page>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    export default {
        data() {
            return {
                list: [],
                plugs_count: 0,
                this_page: 1,
                page_size: 20,
                configBtType: configBtType,
                formS: {
                    title: '',
                    type: '',
                    status: '',
                    orderBySome: 'created_at',
                    orderByF: 'desc'
                },
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        mounted() {
            setTimeout(()=>{
                if(!this.userInfo){
                    this.$Message.error('请先登录')
                    this.$router.push('/home')
                }
            },200)
            this.get_plugs()
        },
        methods: {
            change_page (p) {
                this.this_page = p
                this.get_plugs()
            },
            get_plugs () {
                axios.post(`/bm/list/${this.this_page}/${this.page_size}` , {search: this.formS}).then(res => {
                    this.plugs_count = res.data.count
                    this.list = res.data.list
                })
            },
            to_search(){
                this.get_plugs();
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .bm
        .search
            width 300px
        ul
            li
                width 100%
                padding 10px 0
                border-bottom 1px solid #f2f2f2
</style>
