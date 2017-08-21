<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>插件管理</Breadcrumb-item>
            <Breadcrumb-item>嘿市列表</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formS" inline>
            <Form-item>
            <Input v-model.trim="formS.title" placeholder="搜索标题"></Input>
            </Form-item>
            <Form-item>
            <Select v-model="formS.status" clearable  placeholder="状态" style="width: 100px;">
                <Option v-for="(v , k) in configStatusType" :value="k" :key="k">{{v}}</Option>
            </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.type" clearable  placeholder="类型" style="width: 100px;">
                    <Option v-for="(v , k) in configBtType" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.orderBySome" clearable placeholder="排序条件"  style="width: 100px;">
                    <Option value="download_num">下载</Option>
                    <Option value="created_at">上传时间</Option>
                </Select>
            </Form-item>

            <Form-item>
                <Select v-model="formS.orderByF" clearable  placeholder="排序规则"  style="width: 80px;">
                    <Option value="asc">正序</Option>
                    <Option value="desc">倒序</Option>
                </Select>
            </Form-item>

            <Button type="ghost" @click="rest">
                <span>重置</span>
            </Button>

            <Button type="primary" :loading="loading_s" @click="toS">
                <span>搜索</span>
            </Button>
            <Button type="primary" class="pull-right" @click="create_bm">添加插件</Button>
        </Form>

        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th style="width: 10%">标题</th>
                <th style="width: 10%">作者</th>
                <th style="width: 10%">类型</th>
                <th style="width: 10%">地址</th>
                <th style="width: 10%">下载次数</th>
                <th style="width: 5%">状态</th>
                <th style="width: 5%">排序</th>
                <th style="width: 10%">操作</th>
            </tr>
            </thead>
            <tbody v-if="list.length > 0">
            <tr v-for="(v, k) in list">
                <td>
                    <Tooltip placement="bottom-start">
                        <span class="toolTip">{{v.title}}</span>
                        <div slot="content">
                            <p v-html="v.title"></p>
                        </div>
                    </Tooltip>
                </td>
                <td>{{v.user.name}}</td>
                <td>{{configBtType[v.type]}}</td>
                <td><a :href="v.url" target="_blank">点我下载</a></td>
                <td>{{v.download_num}}</td>
                <td>
                    <Tag type="dot" :color="v.status === 1 ? 'blue' : 'red'" @click.native="change_status(v.status === 1 ? 0 : 1 , v.id, k)">{{configStatusType[v.status]}}</Tag>
                </td>
                <td>
                    <Input-number style="width: 50px" :max="99" :min="0" v-model="v.rank" @on-change="change_other(k)" :disabled="is_disabled === k ? false : true"></Input-number>
                </td>
                <td>
                    <Button type="ghost" size="small" @click="edit(v,k)">编辑</Button>
                    <Button :type="is_disabled === k ? 'success' : 'ghost'" size="small" @click="c_rank(v.id, k)">{{ is_disabled === k ? '提交':'推荐' }}</Button>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td style="text-align: center;font-size: 16px" colspan="8">
                    暂无数据
                </td>
            </tr>
            </tbody>
        </table>

        <div class="page pull-right">
            <Page :total="total" size="small" show-elevator show-sizer show-total @on-change="page_c"
                  @on-page-size-change="size_c" :key="total"></Page>
        </div>


        <bm-common ref="bmCreate" v-on:success="success" v-on:edit_success="edit_success"></bm-common>

    </div>
</template>

<script>
    import BmCommon from './Common.vue'

    export default {
        data() {
            return {
                page: 1,
                page_size: 10,
                total: 0,
                loading_s: false,
                list: [],
                formS: {
                    title: '',
                    type: '',
                    status: '',
                    orderBySome: 'created_at',
                    orderByF: 'desc'
                },
                configBtType: configBtType,
                configStatusType: configStatusType,
                is_disabled: ''
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
                this.formS.title = ''
                this.formS.type = ''
                this.formS.status = ''
                this.formS.orderBySome = 'created_at    '
                this.formS.orderByF = 'desc'
            },
            c_rank(id, k){
                if(this.is_disabled === k){
                    // 提交
                    if(this.list[k].rank === 0){
                        this.$Message.error('请输入大于0小于99的数字')
                        return false
                    }
                    axios.get(`admin/bm/change_rank/${id}/${this.list[k].rank}`).then(res => {
                        if(res.data.sta === 1){
                            this.$Message.success(res.data.msg)
                            this.is_disabled = ''
                        }else{
                            this.$Message.error(res.data.msg)
                        }
                    })
                } else {
                    this.is_disabled = k
                }
            },
            change_other(k) {
                if (!(/^\d+$/.test(this.list[k].rank))) {
                    this.list[k].rank = Math.round(this.list[k].rank)
                }
            },
            edit(v , k) {
                this.$refs.bmCreate.clear_from('formItem')
                this.$refs.bmCreate.model_title = '编辑插件'
                this.$refs.bmCreate.modal_edit = true
                this.$refs.bmCreate.formItem.id = v.id
                this.$refs.bmCreate.formItem.title = v.title
                this.$refs.bmCreate.formItem.type = v.type + ''
                this.$refs.bmCreate.formItem.url = v.type === 1 ? '' : v.url
                this.$refs.bmCreate.formItem.bm_url = v.type === 2 ? '' : v.url
            },
            edit_success() {
                axios.put(`admin/bm/update/${this.$refs.bmCreate.formItem.id}`,{data: this.$refs.bmCreate.formItem}).then(res => {
                    if (res.data.sta === 1) {
                        this.$Message.success(res.data.msg)
                        this.$refs.bmCreate.modal_edit = false
                        this.search()
                    } else {
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            create_bm() {
                this.$refs.bmCreate.formItem.id = ''
                this.$refs.bmCreate.formItem.title = ''
                this.$refs.bmCreate.formItem.type = ''
                this.$refs.bmCreate.formItem.url = ''
                this.$refs.bmCreate.formItem.bm_url = ''

                this.$refs.bmCreate.clear_from('formItem')
                this.$refs.bmCreate.model_title = '新增插件'
                this.$refs.bmCreate.modal_edit = true
            },
            success() {
                axios.put('admin/bm/create',{data: this.$refs.bmCreate.formItem}).then(res => {
                    if (res.data.sta === 1) {
                        this.$Message.success(res.data.msg)
                        this.$refs.bmCreate.modal_edit = false
                        this.search()
                    } else {
                        this.$Message.error(res.data.msg)
                    }
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
                axios.get(`admin/bm/change_status/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].status = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            search() {
                axios.post(`admin/bm/list/${this.page}/${this.page_size}`, {search: this.formS}).then(res => {
                    if (res.data.sta === 1) {
                        this.total = res.data.count
                        this.list = res.data.list
                    }
                    this.$Loading.finish()
                    this.loading_s = false
                })
            }
        },
        components: {
            'bm-common': BmCommon
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
