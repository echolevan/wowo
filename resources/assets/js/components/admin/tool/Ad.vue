<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>网站管理</Breadcrumb-item>
            <Breadcrumb-item>广告管理</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formS" inline>

            <Button type="primary" class="pull-right" style="margin-bottom: 15px" @click="add_ad">
                <span>添加广告</span>
            </Button>
        </Form>

        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th style="width: 10%">广告位置</th>
                <th style="width: 10%">广告图片</th>
                <th style="width: 10%">图片宽度(像素)</th>
                <th style="width: 10%">图片高度(像素)</th>
                <th style="width: 10%">广告链接</th>
                <th style="width: 10%">广告状态</th>
                <th style="width: 10%">到期时间</th>
                <th style="width: 10%">商户信息</th>
                <th style="width: 10%">商户邮箱</th>
                <th style="width: 10%">操作</th>
            </tr>
            </thead>
            <tbody v-if="list.length > 0">
                <tr v-for="(v, k) in list">
                    <td>{{v.position_name}}</td>
                    <td>
                        <img  v-for="img in v.url" :src="img.url" @click="handleView(img.url)" style="width: 30px;height: 30px" alt="">
                    </td>
                    <td>{{v.width}}</td>
                    <td>{{v.height}}</td>
                    <td>
                        <a :href="v.link" target="_blank" :title="v.link"><Tag type="border" color="red">点击访问</Tag></a>
                    </td>
                    <td>
                        <Tag type="dot" :color="v.is_show === 1 ? 'blue' : 'red'"
                             @click.native="change_status(v.is_show === 1 ? 0 : 1 , v.id, k)">{{status_type[v.is_show]}}
                        </Tag>
                    </td>
                    <td>{{v.end_at}} (剩余<span style="color: #d13030;font-size:14px;font-weight:bold"> {{v.last_time}} </span>天) </td>
                    <td>
                        <Tooltip placement="bottom-start">
                            <span class="toolTip" v-html="v.info"></span>
                            <div slot="content">
                                <p v-html="v.info"></p>
                            </div>
                        </Tooltip>
                    </td>
                    <td>{{v.email}}</td>
                    <td>
                        <Button type="ghost" size="small" @click="edit(v,k)">编辑</Button>
                        <Poptip
                                confirm
                                title="您确定删除这条记录吗？"
                                @on-ok="del_this(v.id)">
                            <Button size="small" type="error">删除</Button>
                        </Poptip>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td style="text-align: center;font-size: 16px" colspan="11">
                    暂无数据
                </td>
            </tr>
            </tbody>
        </table>


        <Modal
                v-model="modal_add"
                :mask-closable="false"
                :title="modal_title">
            <Form :model="formItem" :label-width="100" ref="formItem" :rules="ruleValidate">
                <FormItem label="广告位置" prop="position">
                    <Select v-model="formItem.position" placeholder="请选择" @on-change="change_po">
                        <Option value="beijing" v-for="(v, k) in ad_position" :value="k" :key="k">{{v}}</Option>
                    </Select>
                </FormItem>

                <Form-item label="广告图片" prop="uploadList">
                    <div class="small-upload-list" v-for="(item , k) in formItem.uploadList">
                        <template v-if="item.status === 'finished'">
                            <img :src="item.url">
                            <div class="small-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleView(item.url)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                            </div>
                        </template>
                        <template v-else>
                            <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                        </template>
                    </div>
                    <Upload
                            ref="upload"
                            :default-file-list="defaultList"
                            :show-upload-list="false"
                            :before-upload="handleBeforeUpload"
                            :on-success="handleSuccess"
                            multiple
                            type="drag"
                            :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                            action="/admin/upload_ad_img"
                            style="display: inline-block;width:58px;"
                    >
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="camera" size="20"></Icon>
                        </div>
                    </Upload>
                </Form-item>

                <FormItem label="广告宽度" prop="width">
                    <Input v-model="formItem.width" placeholder="请输入"></Input>
                </FormItem>
                <FormItem label="广告高度" prop="height">
                    <Input v-model="formItem.height" placeholder="请输入"></Input>
                </FormItem>
                <FormItem label="广告链接" prop="link">
                    <Input v-model="formItem.link" placeholder="请输入"></Input>
                </FormItem>
                <FormItem label="到期时间" prop="end_at">
                    <DatePicker type="date" placeholder="选择日期"  :options="options3" v-model="formItem.end_at" style="width: 200px"></DatePicker>
                </FormItem>
                <FormItem label="商户信息" prop="info">
                    <Input v-model="formItem.info" type="textarea" :rows="4" placeholder="请输入..."></Input>
                </FormItem>
                <FormItem label="商户邮箱" prop="email">
                    <Input v-model="formItem.email" placeholder="请输入"></Input>
                </FormItem>
                <FormItem  label="是否启用">
                    <iSwitch size="large" v-model="formItem.is_show">
                        <span slot="open">开启</span>
                        <span slot="close">关闭</span>
                    </iSwitch>
                </FormItem>

            </Form>
            <div slot="footer">
                <Button type="primary" class="pull-right" :loading="loading_edit" @click="sub_ok('formItem')" >
                    <span>确定</span>
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
    export default {
        data() {
            const validateUploadList = (rule, value, callback) => {
                setTimeout(() => {
                    if (value.length === 0) {
                        callback(new Error('请上传图片'));
                    } else {
                        callback();
                    }
                }, 10);
            };
            const validateEndAt = (rule, value, callback) => {
                setTimeout(() => {
                    if (!value) {
                        callback(new Error('到期时间不能为空'));
                    } else {
                        callback();
                    }
                }, 10);
            };
            return {
                status_type: {
                    '1': '开启',
                    '0': '关闭',
                },
                modal_title: '添加广告',
                formS: {},
                list: [],
                loading_edit: false,
                modal_add: false,
                formItem: {
                    id: '',
                    position: '',
                    width: '',
                    height: '',
                    uploadList: [],
                    is_show: true,
                    link: '',
                    end_at: '',
                    info: '',
                    email: '',
                },
                ad_position: [],
                ad_default_wh: [],
                ruleValidate: {
                    position: [
                        {required: true, message: '广告位置不能为空', trigger: 'change'}
                    ],
                    uploadList: [
                        {validator: validateUploadList, required: true, trigger: 'change'}
                    ],
                    width: [
                        {required: true, message: '图片宽度不能为空', trigger: 'blur'},
                    ],
                    height: [
                        {required: true, message: '图片高度不能为空', trigger: 'blur'},
                    ],
                    link: [
                        {required: true, message: '广告链接不能为空', trigger: 'blur'},
                    ],
                    end_at: [
                        {validator: validateEndAt, required: true, trigger: 'change'},
                    ],
                    info: [
                        {required: true, message: '商户信息不能为空', trigger: 'blur'},
                    ],
                    email: [
                        {required: true, message: '邮箱不能为空', trigger: 'blur'},
                        { type: 'email', message: '邮箱格式不正确', trigger: 'blur' }
                    ],
                },
                imgName: '',
                visible: false,
                defaultList: [

                ],
                csrfToken: window.Laravel.csrfToken,
                options3: {
                    disabledDate (date) {
                        return date && date.valueOf() < Date.now() - 86400000;
                    }
                },
            }
        },
        mounted() {
            this.___init()
        },
        watch: {
            defaultList(v) {
                for(let i =0 ;i <v.length;i++){
                    this.formItem.uploadList.push(v[i])
                }
            }
        },
        methods: {
            ___init(){
                axios.get('/admin/ad/index').then(res => {
                    this.list = res.data.ads
                })
            },
            change_po(v){
                if(parseInt(v) !== 0){
                    this.formItem.width = this.ad_default_wh[v] ? this.ad_default_wh[v][0] +'' : ''
                    this.formItem.height = this.ad_default_wh[v] ? this.ad_default_wh[v][1] +'' : ''
                }
            },
            add_ad() {
                this.formItem.id = ''
                this.modal_title = '添加广告'
                this.$refs['formItem'].resetFields();
                this.$refs.upload.clearFiles()
                axios.get('/admin/ad/create_info').then(res => {
                    this.ad_position = res.data.ad_position
                    this.ad_default_wh = res.data.ad_default_wh
                })
                this.modal_add = true
                this.formItem.uploadList = this.$refs.upload.fileList;
            },
            edit(v,k) {
                this.modal_title = '编辑广告'
                this.$refs['formItem'].resetFields();
                this.$refs.upload.clearFiles()
                this.defaultList = v.url
                this.formItem.uploadList = this.$refs.upload.fileList;
                this.formItem.position = v.position + ''

                axios.get(`/admin/ad/update_info/${v.id}`).then(res => {
                    this.ad_position = res.data.ad_position
                    this.ad_default_wh = res.data.ad_default_wh

                    this.formItem.width = v.width
                    this.formItem.height = v.height
                    this.formItem.id = v.id
                    this.formItem.link = v.link
                    this.formItem.end_at = v.end_at
                    this.formItem.info = v.info
                    this.formItem.email = v.email

                    this.formItem.is_show = v.is_show === 1 ? true : false
                    this.modal_add = true
                })


            },
            sub_ok(name){
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        if(this.formItem.uploadList.length > 1){
                            this.$Message.error('请先删除一张图片');
                            return false
                        }
                        if(this.formItem.id){
                            axios.post('/admin/ad/update' , {data: this.formItem}).then(res => {
                                if(res.data.sta === 1){
                                    this.$refs['formItem'].resetFields()
                                    this.$refs.upload.clearFiles()
                                    this.modal_add = false
                                    this.$Message.success('更新成功');
                                    this.___init()
                                }else{
                                    this.$Message.error('更新失败');
                                }
                            })
                        }else{
                            axios.post('/admin/ad/create' , {data: this.formItem}).then(res => {
                                if(res.data.sta === 1){
                                    this.$refs['formItem'].resetFields()
                                    this.$refs.upload.clearFiles()
                                    this.modal_add = false
                                    this.___init()
                                    this.$Message.success('添加成功');
                                }else{
                                    this.$Message.error('添加失败');
                                }
                            })
                        }

                    } else {
                    }
                })
            },
            _rest(){
            },
            handleView(name) {
                this.imgName = name;
                this.visible = true;
            },
            handleBeforeUpload() {
                const check = this.$refs.upload.fileList.length < 1;
                if (!check) {
                    this.$Message.error(`最多只能上传 1 张图片`);
                }
                return check;
            },
            handleSuccess(res, file, fileList) {
                if (res.sta === 0) {
                    this.$Message.error(res.msg);
                    this.$Loading.error()
                } else {
                    file.url = res.url
                    file.width = res.width
                    file.height = res.height
                    this.formItem.uploadList = fileList;
                    this.$Loading.finish()
                }
            },
            handleRemove(file) {
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)
                this.formItem.uploadList = this.$refs.upload.fileList
            },
            change_status(v, id, k) {
                axios.get(`/admin/ad/change_status/${id}/${v}`).then(res => {
                    if (res.data.sta === 1) {
                        this.list[k].is_show = v
                        this.$Message.success(res.data.msg)
                    } else {
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            del_this(v){
                axios.delete(`/admin/ad/delete/${v}`).then(res => {
                    this.$Message.success('删除成功')
                    this.___init()
                })
            }
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
