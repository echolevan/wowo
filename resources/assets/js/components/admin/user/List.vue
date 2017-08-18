<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>用户管理</Breadcrumb-item>
            <Breadcrumb-item>用户列表</Breadcrumb-item>
        </Breadcrumb>


        <Form :model="formS" inline>
            <Form-item>
                <Input v-model.trim="formS.id" placeholder="搜索Id"></Input>
            </Form-item>
            <Form-item>
                <Input v-model.trim="formS.name" placeholder="搜索昵称"></Input>
            </Form-item>
            <Form-item>
                <Select v-model="formS.camp" clearable  placeholder="阵营" style="width: 100px;">
                    <Option v-for="(v , k) in configCamp" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.is_active" clearable  placeholder="是否激活" style="width: 100px;">
                    <Option v-for="(v , k) in configYesOrNo" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.status" clearable  placeholder="能否登陆" style="width: 100px;">
                    <Option v-for="(v , k) in configIsLogin" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.is_admin" clearable placeholder="是否是管理员" style="width: 200px;">
                    <Option v-for="(v , k) in configYesOrNo" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>

            <Button type="ghost" @click="rest">
                <span>重置</span>
            </Button>

            <Button type="primary" :loading="loading_s" @click="toS">
                <span>搜索</span>
            </Button>

            <router-link to="/admin/tag/create">
                <Button type="primary" class="pull-right">添加分类</Button>
            </router-link>
        </Form>

        <div style="padding: 10px 0">
            是否显示签名
            <i-switch v-model="show_info">
                <Icon type="android-done" slot="open"></Icon>
                <Icon type="android-close" slot="close"></Icon>
            </i-switch>
            是否显示邮箱
            <i-switch v-model="show_email">
                <Icon type="android-done" slot="open"></Icon>
                <Icon type="android-close" slot="close"></Icon>
            </i-switch>
            是否显示注册时间
            <i-switch v-model="show_c_at">
                <Icon type="android-done" slot="open"></Icon>
                <Icon type="android-close" slot="close"></Icon>
            </i-switch>
            是否显示登陆时间
            <i-switch v-model="show_l_at">
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
                <th style="width: 8%" v-show="show_email">邮箱</th>
                <th style="width: 5%">阵营</th>
                <th style="width: 5%">金币</th>
                <th style="width: 5%">发布插件数</th>
                <th style="width: 10%" v-show="show_c_at">注册时间</th>
                <th style="width: 10%" v-show="show_l_at">最近登陆</th>
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
                <td>
                    <img-view :img="v.avatar"></img-view>
                </td>
                <td v-show="show_info" class="hover_hand">
                    <Tooltip placement="bottom-start">
                        <span class="toolTip" v-html="v.info"></span>
                        <div slot="content">
                            <p v-html="v.info"></p>
                        </div>
                    </Tooltip>
                </td>
                <td>{{v.tel === 0 ? '暂无手机号' : v.tel}}</td>
                <td v-show="show_email">{{v.email}}</td>
                <td>{{configCamp[v.camp]}}</td>
                <td>{{v.wwb}}</td>
                <td>{{v.plugs.length}}</td>
                <td v-show="show_c_at">{{v.created_at}}</td>
                <td v-show="show_l_at">{{v.login_at}}</td>
                <td>
                    <Tag type="border" :color="v.is_active === 1 ? 'blue' : 'red'">{{configYesOrNo[v.is_active]}}</Tag>
                </td>
                <td>
                    <Tag type="dot" :color="v.status === 1 ? 'blue' : 'red'"
                         @click.native="change_status(v.status === 1 ? 0 : 1 , v.id, k)">{{configIsLogin[v.status]}}
                    </Tag>
                </td>
                <td>
                    <Tag type="dot" :color="v.is_admin === 1 ? 'blue' : 'red'"
                         @click.native="change_is_admin(v.is_admin === 1 ? 0 : 1 , v.id, k)">{{configYesOrNo[v.is_admin]}}
                    </Tag>
                </td>
                <td>
                    <Button type="ghost" size="small" @click="edit(v,k)">编辑</Button>
                    <Button type="ghost" size="small">充值记录</Button>
                    <Button type="ghost" size="small">提现记录</Button>
                    <Button type="ghost" size="small">插件记录</Button>
                </td>
            </tr>
            </tbody>
        </table>

        <v-user-edit ref="userEdit" v-on:subEdit="edit_ok"></v-user-edit>
        <div class="page pull-right">
            <Page :total="total" size="small" show-elevator show-sizer show-total @on-change="page_c"
                  @on-page-size-change="size_c" :key="total"></Page>
        </div>
    </div>
</template>

<script>
    import imgView from '../../common/imgView.vue'
    import Edit from './edit.vue'

    export default {
        data() {
            return {
                page: 1,
                page_size: 10,
                total: 0,
                list: [],
                show_info: false,
                show_email: false,
                show_c_at: false,
                show_l_at: false,
                loading_s: false,
                formS: {
                    id: '',
                    name: '',
                    camp: '',
                    is_active: '',
                    status: '',
                    is_admin: ''
                },
                configCamp: configCamp,
                configIsLogin: configIsLogin,
                configYesOrNo: configYesOrNo,
                this_user_info: {},
                edit_k: 0
            }
        },
        mounted() {
            this.search()
        },
        methods: {
            toS() {
                this.page = 1
                this.loading_s = true
                this.search()
            },
            rest() {
                this.formS.id = ''
                this.formS.name = ''
                this.formS.camp = ''
                this.formS.is_active = ''
                this.formS.status = ''
                this.formS.is_admin = ''
            },
            edit(v, k) {
                this.$refs.userEdit.formItem.id = v.id
                this.$refs.userEdit.formItem.name = v.name
                this.$refs.userEdit.formItem.email = v.email
                this.$refs.userEdit.formItem.tel = v.tel === 0 ? '' : v.tel
                this.$refs.userEdit.formItem.avatar = v.avatar
                this.$refs.userEdit.formItem.camp = v.camp + ''
                this.$refs.userEdit.is_disabled = true
                this.$refs.userEdit.model_edit = true
                this.edit_k = k
            },
            edit_ok() {
                axios.put(`admin/user/update/${this.$refs.userEdit.formItem.id}`,{data:this.$refs.userEdit.formItem}).then(res => {
                    if (res.data.sta === 1) {
                        this.list[this.edit_k].name = this.$refs.userEdit.formItem.name
                        this.list[this.edit_k].email = this.$refs.userEdit.formItem.email
                        this.list[this.edit_k].avatar = this.$refs.userEdit.formItem.avatar
                        this.list[this.edit_k].camp = this.$refs.userEdit.formItem.camp
                        this.list[this.edit_k].tel = this.$refs.userEdit.formItem.tel === '' ? 0 : this.$refs.userEdit.formItem.tel
                        this.$Message.success(res.data.msg)
                        this.$refs.userEdit.model_edit = false
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            search() {
                axios.post(`admin/user/list/${this.page}/${this.page_size}`, {search: this.formS}).then(res => {
                    if (res.data.sta === 1) {
                        this.total = res.data.count
                        this.list = res.data.users
                    }
                    this.$Loading.finish();
                    this.loading_s = false
                })
            },
            page_c(p) {
                this.page = p
                this.search()
                this.$Loading.start()
            },
            size_c(s) {
                this.page_size = s;
                this.search()
                this.$Loading.start();
            },
            change_status(v, id, k) {
                axios.get(`admin/user/change_status/${id}/${v}`).then(res => {
                    if (res.data.sta === 1) {
                        this.list[k].status = v
                        this.$Message.success(res.data.msg)
                    } else {
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            change_is_admin(v, id, k) {
                axios.get(`admin/user/change_is_admin/${id}/${v}`).then(res => {
                    if (res.data.sta === 1) {
                        this.list[k].is_admin = v
                        this.$Message.success(res.data.msg)
                    } else {
                        this.$Message.error(res.data.msg)
                    }
                })
            },
        },
        components: {
            'img-view': imgView,
            'v-user-edit': Edit
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
