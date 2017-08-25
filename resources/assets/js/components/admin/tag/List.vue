<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>分类管理</Breadcrumb-item>
            <Breadcrumb-item>标签列表</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formS" inline>
            <Form-item>
                <Input v-model.trim="formS.name" placeholder="搜索分类"></Input>
            </Form-item>
            <Form-item>
                <Select v-model="formS.status" clearable  placeholder="状态" style="width: 100px;">
                    <Option v-for="(v , k) in config_status_type" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.is_for_user" clearable placeholder="用户能否使用" style="width: 200px;">
                    <Option v-for="(v , k) in config_is_for_user" :value="k" :key="k">{{v}}</Option>
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


        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th style="width: 10%">标签名称</th>
                <th style="width: 10%">标签图标</th>
                <th style="width: 10%">所属类别</th>
                <th style="width: 10%">分类</th>
                <th style="width: 10%">状态</th>
                <th style="width: 10%">用户是否能使用</th>
                <th style="width: 10%">操作</th>
            </tr>
            </thead>
            <tbody v-if="list.length > 0">
            <tr v-for="(v, k) in list">
                <td>{{v.name}}</td>
                <td><img-view :img="v.thumb"></img-view></td>
                <td>{{v.parent ? v.parent.name : '顶级'}}</td>
                <td>{{tag_type(v.type)}}</td>
                <td>
                    <Tag type="dot" :color="v.status === 1 ? 'blue' : 'red'" @click.native="change_status(v.status === 1 ? 0 : 1 , v.id, k)">{{status_type(v.status)}}</Tag>
                </td>
                <td>
                    <Tag type="dot" :color="v.is_for_user === 1 ? 'blue' : 'red'" @click.native="change_is_for_user(v.is_for_user === 1 ? 0 : 1 , v.id, k)">{{is_for_user(v.is_for_user)}}</Tag>
                </td>
                <td>
                    <Button type="ghost" size="small" @click="edit(v,k)">编辑</Button>
                </td>
            </tr>
            </tbody>
            <tbody  v-else>
            <tr>
                <td style="text-align: center;font-size: 16px" colspan="7">
                    暂无数据
                </td>
            </tr>
            </tbody>
        </table>

        <div class="page pull-right">
            <Page :total="total" size="small"  show-elevator show-sizer show-total @on-change="page_c" @on-page-size-change="size_c"  :key="total"></Page>
        </div>

        <Modal
                v-model="modal_edit"
                title="编辑插件"
                @on-ok="ok('formItem')">
            <Form :model="formItem" :label-width="80" class="div_center form_main"ref="formItem" :rules="ruleValidate">
                <Form-item label="名称" prop="name">
                    <Input v-model="formItem.name" placeholder="请输入"></Input>
                </Form-item>
                <Form-item label="类型" prop="type">
                    <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="formItem.type" change-on-select  @on-change="on_sel"></Cascader>
                </Form-item>
                <Form-item label="分享图片" prop="thumb" v-show="formItem.type.length === 1">
                    <!--// see img-->
                    <div class="small-upload-list" v-show="formItem.thumb !== ''">
                        <img :src="formItem.thumb">
                        <div class="small-upload-list-cover">
                            <Icon type="ios-eye-outline" @click.native="handleView(formItem.thumb)"></Icon>
                            <Icon type="ios-trash-outline" @click.native="handleRemove(1)"></Icon>
                        </div>
                    </div>
                    <!--// upload-->
                    <Upload
                            ref="upload"
                            :show-upload-list="false"
                            :on-success="handleSuccess"
                            type="drag"
                            :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                            action="/admin/upload_tag_img"
                            style="display: inline-block;width:58px;" v-show="formItem.thumb === ''">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="camera" size="20"></Icon>
                        </div>
                    </Upload>
                </Form-item>
            </Form>

            <div slot="footer">
                <Button type="primary" :loading="loading_edit" @click="ok('formItem')" class="pull-right">
                    <span>提交</span>
                </Button>
                <div style="clear: both"></div>
            </div>
        </Modal>

        <Modal title="查看图片" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    import imgView from '../../common/imgView.vue'
    export default {
        data() {
            const validateUploadList = (rule, value, callback) => {
                setTimeout(() => {
                    if (this.formItem.type.length === 1) {
                        if(this.formItem.thumb === ''){
                            callback(new Error('请上传插件截图'));
                        }else{
                            callback();
                        }
                    } else {
                        callback();
                    }
                }, 10);
            };
            const validateType = (rule, value, callback) => {
                if (value.length === 0) {
                    callback(new Error('插件分类不能为空'));
                } else {
                    callback();
                }
            };
            return {
                page: 1,
                page_size: 10,
                total: 0,
                edit_k: 0,
                list: [],
                formS: {
                    name: '',
                    status: '',
                    is_for_user: '',
                },
                config_status_type: configStatusType,
                config_is_for_user: configIsForUser,
                loading_s: false,
                loading_edit: false,
                modal_edit: false,
                visible: false,
                csrfToken : window.Laravel.csrfToken,
                imgName: '',
                formItem: {
                    name: '',
                    type: [],
                    thumb: '',
                    id: '',
                },
                plug_tags: [],
                ruleValidate: {
                    name: [
                        {required: true, message: '标题不能为空', trigger: 'blur'},
                        {max: 30, message: '标题最长30', trigger: 'change'}
                    ],
                    type: [
                        {validator: validateType, required: true, trigger: 'change'}
                    ],
                    thumb: [
                        {validator: validateUploadList, required: true, trigger: 'change'},
                    ]
                }
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
                this.formS.name = ''
                this.formS.status = ''
                this.formS.is_for_user = ''
            },
            tag_type(v) {
                return configTagType[v]
            },
            status_type(v) {
                return configStatusType[v]
            },
            is_for_user(v) {
                return configIsForUser[v]
            },
            change_status(v, id, k) {
                axios.get(`/admin/tag/change_status/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].status = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            change_is_for_user(v, id, k) {
                axios.get(`/admin/tag/change_is_for_user/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].is_for_user = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
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
            search() {
                axios.post(`/admin/tag/list/${this.page}/${this.page_size}`,{search:this.formS}).then(res => {
                    if(res.data.sta === 1){
                        this.total = res.data.count
                        this.list = res.data.list
                    }
                    this.$Loading.finish();
                    this.loading_s = false
                })
            },
            edit(info, k) {
                this.edit_k = k
                axios.get('/admin/plug_all_info').then(res=>{
                    this.plug_tags = res.data
                })
                this.formItem.name = info.name
                this.formItem.thumb = info.thumb
                this.formItem.id = info.id
                if(info.type === 1) {
                    let dataStrArr = [1, info.pid]
                    let dataIntArr = [];
                    dataStrArr.forEach(function (data, index, arr) {
                        if(data > 0){
                            dataIntArr.push(+data);
                        }
                    });
                    this.formItem.type = dataIntArr
                }else{
                    this.formItem.type = [3]
                }
                this.modal_edit = true
            },
            handleSuccess (res, file) {
                this.formItem.thumb = res.url
            },
            handleView (name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove(){
                this.formItem.thumb = ''
            },
            on_sel(v) {
                this.formItem.type = v
            },
            ok(name) {
                this.loading_edit = true
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put(`/admin/tag/update/${this.formItem.id}`,{data:this.formItem}).then(res=>{
                            if(res.data.sta === 1){
                                this.$Message.success('编辑成功!');
                                this.formItem.name = ''
                                this.formItem.type = []
                                this.formItem.thumb = ''
                                this.formItem.id = ''
                                this.modal_edit = false
                                this.list[this.edit_k] = res.data.info
                            }
                        })
                    } else {
                        this.$Message.error('表单验证失败!');
                    }
                })
                this.loading_edit = false
            }
        },
        components: {
            'img-view': imgView
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .small-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .small-upload-list img{
        width: 100%;
        height: 100%;
    }
    .small-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .small-upload-list:hover .small-upload-list-cover{
        display: block;
    }
    .small-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }

</style>
