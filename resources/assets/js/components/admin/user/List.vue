<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>分类管理</Breadcrumb-item>
            <Breadcrumb-item>用户列表</Breadcrumb-item>
        </Breadcrumb>


        <div style="padding: 10px 0">
            是否显示签名
            <i-switch v-model="show_info">
                <Icon type="android-done" slot="open"></Icon>
                <Icon type="android-close" slot="close"></Icon>
            </i-switch>
        </div>

        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 5%">昵称</th>
                <th style="width: 5%">头像</th>
                <th style="width: 10%" v-show="show_info">签名</th>
                <th style="width: 7%">手机号</th>
                <th style="width: 8%">邮箱</th>
                <th style="width: 5%">阵营</th>
                <th style="width: 5%">金币</th>
                <th style="width: 5%">发布插件数</th>
                <th style="width: 10%">注册时间</th>
                <th style="width: 10%">最近登陆</th>
                <th style="width: 5%">是否激活</th>
                <th style="width: 5%">能否登陆</th>
                <th style="width: 8%">是否是管理员</th>
                <th style="width: 12%">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(v,k) in list">
                <td class="hover_hand" title="点击复制ID">{{v.id}}</td>
                <td>{{v.name}}</td>
                <td><img-view :img="v.avatar"></img-view></td>
                <td  v-show="show_info" class="hover_hand">
                    <Tooltip placement="bottom-start">
                        <span class="toolTip" v-html="v.info"></span>
                        <div slot="content">
                            <p v-html="v.info"></p>
                        </div>
                    </Tooltip>
                </td>
                <td>{{v.tel}}</td>
                <td>{{v.email}}</td>
                <td>{{configCamp[v.camp]}}</td>
                <td>{{v.wwb}}</td>
                <td>{{v.plugs.length}}</td>
                <td>{{v.created_at}}</td>
                <td>{{v.login_at}}</td>
                <td>{{configYesOrNo[v.status]}}</td>
                <td>
                    <Tag type="dot" :color="v.status === 1 ? 'blue' : 'red'" @click.native="change_status(v.status === 1 ? 0 : 1 , v.id, k)">{{configIsLogin[v.status]}}</Tag>
                </td>
                <td>
                    <Tag type="dot" :color="v.is_admin === 1 ? 'blue' : 'red'" @click.native="change_is_admin(v.is_admin === 1 ? 0 : 1 , v.id, k)">{{configYesOrNo[v.is_admin]}}</Tag>
                </td>
                <td>
                    <Button type="ghost" size="small">编辑</Button>
                    <Button type="ghost" size="small">充值</Button>
                    <Button type="ghost" size="small">插件</Button>
                </td>
            </tr>
            </tbody>
        </table>


        <div class="page pull-right">
            <Page :total="total" size="small"  show-elevator show-sizer show-total @on-change="page_c" @on-page-size-change="size_c"  :key="total"></Page>
        </div>
    </div>
</template>

<script>
    import imgView from '../../common/imgView.vue'
    export default {
        data() {
            return {
                page: 0,
                page_size: 10,
                total: 0,
                list: [],
                show_info: false,
                formS: {

                },
                configCamp: configCamp,
                configIsLogin: configIsLogin,
                configYesOrNo: configYesOrNo,
            }
        },
        mounted() {
          this.search()
        },
        methods: {
            search() {
                axios.post(`admin/user/list/${this.page}/${this.page_size}`,{search:this.formS}).then(res => {
                    console.log(res)
                    if(res.data.sta === 1){
                        this.total = res.data.count
                        this.list = res.data.users
                    }
                    this.$Loading.finish();
                    this.loading_s = false
                })
            },
            page_c(p){
                this.page = p
                this.search()
                this.$Loading.start()
            },
            size_c(s){
                this.page_size = s;
                this.search()
                this.$Loading.start();
            },
            change_status(v, id, k) {
                axios.get(`admin/user/change_status/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].status = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            change_is_admin(v, id, k) {
                axios.get(`admin/user/change_is_admin/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].is_admin = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
        },
        components: {
            'img-view': imgView
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
