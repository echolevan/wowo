<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>网站管理</Breadcrumb-item>
            <Breadcrumb-item>等级管理</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formS" inline>
            <Form-item>
                <Input v-model.trim="formS.name" placeholder="搜索分类"></Input>
            </Form-item>

            <Button type="ghost" @click="rest">
                <span>重置</span>
            </Button>

            <Button type="primary" :loading="loading_s" @click="toS">
                <span>搜索</span>
            </Button>

            <Button type="primary" class="pull-right" @click="create_lv">添加等级</Button>
        </Form>

        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th style="width: 10%">等级名称</th>
                <th style="width: 10%">等级</th>
                <th style="width: 10%">升级条件(元)</th>
                <th style="width: 10%">赠送比例(%)</th>
                <th style="width: 10%">操作</th>
            </tr>
            </thead>
            <tbody v-if="list.length > 0">
            <tr v-for="(v , k) in list" :key="k">
                <td>{{v.name}}</td>
                <td>{{arr_list[v.money]}}</td>
                <td>{{v.money}}</td>
                <td>{{v.giving}}</td>
                <td>
                    <Button type="ghost" size="small" @click="edit_lv(v)">编辑</Button>
                    <Poptip
                            confirm
                            title="确定要删除此等级吗？"
                            @on-ok="del_ok(v.id)">
                        <Button type="error" size="small">删除</Button>
                    </Poptip>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td style="text-align: center;font-size: 16px" colspan="5">
                    暂无数据
                </td>
            </tr>
            </tbody>
        </table>

        <div class="page pull-right">
            <Page :total="total" size="small" show-elevator show-sizer show-total @on-change="page_c"
                  @on-page-size-change="size_c" :key="total"></Page>
        </div>

        <Modal
                v-model="modal_edit"
                :title="model_title"
                @on-ok="ok('formItem')">
            <Form :model="formItem" :label-width="120" class="div_center form_main" ref="formItem"
                  :rules="ruleValidate">
                <Form-item label="名称" prop="name">
                    <Input v-model="formItem.name" placeholder="请输入"></Input>
                </Form-item>
                <Form-item label="升级条件(元)" prop="money">
                    <Input-number :min="0" v-model="formItem.money" @on-change="change_other"></Input-number>
                </Form-item>
                <Form-item label="赠送比例(%)" prop="giving">
                    <Input-number :max="100" :min="0" v-model="formItem.giving" @on-change="change_giving"></Input-number>
                </Form-item>
            </Form>

            <div slot="footer">
                <Button type="primary" :loading="loading_edit" @click="ok('formItem')" class="pull-right">
                    <span>确定</span>
                </Button>
                <div style="clear: both"></div>
            </div>
        </Modal>

    </div>
</template>

<script>
    export default {
        data() {
            const validateName = (rule, value, callback) => {
                if (value !== '' && this.formItem.id === '') {
                    axios.get(`/admin/tool/lv/check_name/${value}/${this.formItem.id === '' ? 0 : this.formItem.id}`).then(res=>{
                        if(res.data.sta === 0){
                            callback(new Error('名称已经存在'));
                        }else{
                            callback();
                        }
                    })
                } else {
                    callback();
                }
            };
            const validateMoney = (rule, value, callback) => {
                if (value !== '') {
                    axios.get(`/admin/tool/lv/check_money/${value}/${this.formItem.id === '' ? 0 : this.formItem.id}`).then(res=>{
                        if(res.data.sta === 0){
                            callback(new Error('金额已经存在'));
                        }else{
                            callback();
                        }
                    })
                } else {
                    callback();
                }
            };
            return {
                page: 1,
                page_size: 10,
                total: 0,
                list: [],
                arr_list: [],
                formS: {
                    name: ''
                },
                loading_s: false,
                modal_edit: false,
                loading_edit: false,
                model_title: '添加等级',
                formItem: {
                    id: '',
                    name: '',
                    money: 0,
                    giving: 0,
                },
                ruleValidate: {
                    name: [
                        {required: true, message: '名称不能为空', trigger: 'blur'},
                        {max: 30, message: '名称最长30字符', trigger: 'change'},
                        {validator: validateName, trigger: 'blur'},
                    ],
                    money: [
                        {validator: validateMoney, trigger: 'blur'},
                    ]
                }
            }
        },
        mounted() {
            this.search()
        },
        methods: {
            change_other() {
                if (!(/^\d+$/.test(this.formItem.money))) {
                    this.formItem.money = Math.round(this.formItem.money)
                }
            },
            change_giving(){
                if (!(/^\d+$/.test(this.formItem.giving))) {
                    this.formItem.giving = Math.round(this.formItem.giving)
                }
            },
            edit_lv(v){
                this.$refs['formItem'].resetFields();
                this.formItem.id = v.id
                this.formItem.name = v.name
                this.formItem.money = parseInt(v.money)
                this.formItem.giving = parseInt(v.giving)
                this.modal_edit = true
                this.model_title = '编辑等级'
            },
            del_ok(v){
                axios.delete(`/admin/tool/lv/del_lv/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.search()
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            ok(name) {
                this.loading_edit = true
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        if (!this.formItem.id) {
                            //cerate
                            axios.put('/admin/tool/lv/create', {data: this.formItem}).then(res => {
                                if(res.data.sta === 1){
                                    this.search()
                                    this.$Message.success(res.data.msg)
                                    this.modal_edit = false
                                }else{
                                    this.$Message.error(res.data.msg)
                                }
                            })
                        } else {
                            axios.put(`/admin/tool/lv/update/${this.formItem.id}`, {data: this.formItem}).then(res => {
                                if(res.data.sta === 1){
                                    this.search()
                                    this.$Message.success(res.data.msg)
                                    this.modal_edit = false
                                }else{
                                    this.$Message.error(res.data.msg)
                                }
                            })
                        }
                    } else {
                        this.$Message.error('操作失败');
                    }
                })
                this.loading_edit = false

            },
            create_lv() {
                this.$refs['formItem'].resetFields();
                this.formItem.id = ''
                this.formItem.name = ''
                this.formItem.money = 0
                this.formItem.giving = 0
                this.modal_edit = true
                this.model_title = '添加等级'
            },
            toS() {
                this.page = 1
                this.loading_s = true
                this.search()
            },
            search() {
                axios.post(`/admin/tool/lv/list/${this.page}/${this.page_size}`, {search: this.formS}).then(res => {
                    if (res.data.sta === 1) {
                        this.total = res.data.count
                        this.list = res.data.list
                        this.arr_list = res.data.arr
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
            rest() {
                this.formS.name = ''
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
