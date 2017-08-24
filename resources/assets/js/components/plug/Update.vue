<template>
    <div>
        <Form :model="formItem" :label-width="100" ref="formItem" :rules="ruleValidate">
            <Form-item label="插件标题" prop="title">
                <Input v-model="formItem.title" placeholder="请输入"></Input>
            </Form-item>

            <Form-item label="插件分类" prop="type">
                <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="formItem.type"  @on-change="on_sel"></Cascader>
            </Form-item>

            <Form-item label="插件字符串" v-show="formItem.type[0] === 1 || formItem.type[0] === 2" prop="content">
                <Input v-model="formItem.content" type="textarea" :autosize="{minRows: 2}" placeholder="请输入..." v-on:input="keyUp"></Input>
            </Form-item>

            <Form-item label="上传插件" v-show="formItem.type[0] === 3" prop="plug_url">
                <Upload action="/upload_plug_info_plug"
                        :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                        :on-success="handlePlugSuccess"
                        :before-upload="handlePlugUpload"
                        :on-remove="removePlug"
                >
                    <Button type="ghost" icon="ios-cloud-upload-outline">上传文件</Button>
                </Upload>
                <span @click="del_plug" class="hover_hand" v-show="del_plug_sta === 1 && this.formItem.plug_url">删除上传的文件重新上传</span>
            </Form-item>

            <Form-item label="更新说明" prop="updated_info">
                <Input v-model="formItem.updated_info" type="textarea" :autosize="{minRows: 2}" placeholder="请输入..."></Input>
            </Form-item>

            <Form-item label="游戏版本号" prop="game_version">
                <Input v-model="formItem.game_version" placeholder="请输入"></Input>
            </Form-item>

            <Form-item label="是否收费" v-show="formItem.type[0] < 3">
                <i-Switch v-model="formItem.is_free" size="large" @on-change="swi">
                    <span slot="open">是</span>
                    <span slot="close">否</span>
                </i-Switch>
            </Form-item>

            <Form-item label="价格" v-show="formItem.is_free"  prop="gold">
                <Input-number
                        :min="1"
                        v-model="formItem.gold"
                        @on-change="change_other"></Input-number>
            </Form-item>


            <Form-item label="上传截图" prop="uploadList">
                <div class="demo-upload-list" v-for="(item , k) in formItem.uploadList" :class="`img_viewer_${k}`">
                        <img :src="item.url">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-eye-outline" @click.native="handleView(item.url)"></Icon>
                            <Icon type="ios-trash-outline" @click.native="handleRemove(k)"></Icon>
                        </div>
                </div>
                <Upload
                        ref="upload"
                        :show-upload-list="false"
                        :on-success="handleSuccess"
                        multiple
                        type="drag"
                        :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                        action="/upload_plug_screen_img"
                        style="display: inline-block;width:58px;">
                    <div style="width: 58px;height:58px;line-height: 58px;">
                        <Icon type="camera" size="20"></Icon>
                    </div>
                </Upload>
            </Form-item>

            <Form-item label="功能简介" prop="info">
                <vue-editor v-model="formItem.info" useCustomImageHandler @imageAdded="handleImageAdded"></vue-editor>
            </Form-item>


            <Button type="primary" :loading="loading" @click="toLoading('formItem')" class="pull-right">
                <span v-if="!loading">提交</span>
                <span v-else>Loading...</span>
            </Button>
        <div style="clear: both"></div>
        </Form>

        <Modal title="查看图片" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>

    </div>
</template>

<script>
    import {VueEditor} from 'vue2-editor'

    export default {
        data() {
            const validateUploadList = (rule, value, callback) => {
                setTimeout(() =>  {
                    if (value.length === 0) {
                        callback(new Error('请上传插件截图'));
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
            const validategold = (rule, value, callback) => {
                if (value.length === 0) {
                    if(this.formItem.is_free === true){
                        callback(new Error('插件收费不能为空'));
                    }else{
                        callback();
                    }
                } else {
                    callback();
                }
            };
            const validateContent = (rule, value, callback) => {
                if (value === '') {
                    if(this.formItem.type[0] === 1 || this.formItem.type[0] === 2){
                        callback(new Error('插件内容不能为空'));
                    }else{
                        callback();
                    }
                } else {
                    callback();
                }
            };
            const validateContentUrl = (rule, value, callback) => {
                setTimeout(() =>  {
                    if (this.formItem.plug_url === '') {
                        if(this.formItem.type[0] === 3){
                            callback(new Error('插件内容不能为空'));
                        }else{
                            callback();
                        }
                    } else {
                        callback();
                    }
                }, 10);
            };
            return {
                plug_tags:[],
                formItem:{
                    title: '',
                    type: [],
                    content: '',
                    info: '',
                    updated_info: '',
                    game_version: '',
                    is_free: false,
                    gold: 1,
                    uploadList: [],
                    plug_url: '',
                },
                imgName: '',
                visible: false,
                del_plug_sta: 1,
                loading: false,
                csrfToken : window.Laravel.csrfToken,
                ruleValidate: {
                    title: [
                        {required: true, message: '插件标题不能为空', trigger: 'blur'},
                        {max: 120, message: '插件标题最长120', trigger: 'change'}
                    ],
                    type: [
                        {validator: validateType, required: true, trigger: 'change'}
                    ],
                    content: [
                        {validator: validateContent, trigger: 'blur'}
                    ],
                    plug_url: [
                        {validator: validateContentUrl, trigger: 'change'}
                    ],
                    info: [
                        {required: true, message: '插件详情不能为空'}
                    ],
                    updated_info: [
                        {required: true, message: '插件更新详情不能为空', trigger: 'blur'},
                        {max: 150, message: '插件更新详情最长150', trigger: 'change'},
                        {max: 150, message: '插件更新详情最长150', trigger: 'blur'},
                    ],
                    uploadList: [
                        {validator: validateUploadList, required: true, trigger: 'change'},
                    ],
                    game_version: [
                        {required: true, message: '插件对应游戏版本号不能为空', trigger: 'blur'}
                    ],
                    gold: [
                        {validator: validategold, trigger: 'change'}
                    ]
                }
            }
        },
        mounted(){
            this._init()
        },
        watch: {
            formItem(){
              this.keyUp()
            },
            '$route'(to, from) {
                this.$router.go(-1)
            }
        },
        methods: {
            keyUp() {
                this.formItem.content = this.formItem.content.replace(/[^\w\.\/]/ig,'')
            },
            toLoading (name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put(`update_plug/${this.$route.params.id}` , {data:this.formItem}).then(res=>{
                            if(res.data.sta === 0){
                                myDialog(res.data.msg)
                            }else{
                                myDialog(res.data.msg)
                                if(this.$route.name === 'admin.plug.create'){
                                    this.$router.push('/admin/plug/list')
                                }else{
                                    this.$router.push('/userInfo/upload')
                                }
                            }
                        })
                    } else {
                        myDialog('表单验证失败!')
                    }
                    this.loading = false;
                })
            },
            swi() {
              this.formItem.gold = 0
            },
            on_sel(v) {
              this.formItem.type = v
              this.formItem.content = ''
              this.formItem.is_free = false
            },
            _init(){
                axios.get(`update_plugInfo/${this.$route.params.id}`).then(res=>{
                    if(res.data.sta === 0){
                        this.$router.go(-1)
                    }
                    this.formItem.title = res.data.plug.title
                    this.formItem.type = res.data.plug.type
                    this.formItem.content = res.data.plug.content
                    this.formItem.info = res.data.plug.info
                    this.formItem.updated_info = res.data.plug.updated_info
                    this.formItem.game_version = res.data.plug.game_version
                    this.formItem.is_free = res.data.plug.is_free
                    this.formItem.gold = res.data.plug.gold
                    this.formItem.plug_url = res.data.plug.content
                    this.formItem.uploadList = res.data.plug.thumbs
                }).catch(error=>{
                    history.go(-1)
                })
              axios.get('plug_all_info').then(res=>{
                  this.plug_tags = res.data
              })
            },
            del_plug(){
                this.formItem.plug_url = ''
                this.del_plug_sta = 0
            },
            handleImageAdded: function(file, Editor, cursorLocation) {
                let formData = new FormData();
                formData.append('image', file)

                axios({
                    url: '/upload_plug_info_img',
                    method: 'POST',
                    data: formData
                })
                .then((result) => {
                    if(result.data.sta === 0){
                        myDialog(result.data.msg)
                    }else{
                        let url = result.data.url
                        Editor.insertEmbed(cursorLocation, 'image', url);
                    }

                })
                .catch((err) => {
                    console.log(err);
                })
            },

            handleView (name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove (k) {
                this.formItem.uploadList.splice(k, 1)
            },
            handleSuccess (res, file) {
                if(res.sta === 0){
                    myDialog(res.msg)
                }else{
                    this.formItem.uploadList.push({
                        url: res.url,
                        width: res.width,
                        height: res.height,
                    });
                }
            },
            handlePlugSuccess(res, file) {
                if(res.sta === 0){
                    myDialog(res.msg)
                }else{
                   this.formItem.plug_url = res.url
                }
            },
            handlePlugUpload(){
                if(this.formItem.plug_url !== ''){
                    myDialog('您已经上传了文件，请先删除')
                    return false;
                }
            },
            removePlug(){
                this.formItem.plug_url = ''
            },
            change_other() {
                if (!(/^\d+$/.test(this.formItem.gold))) {
                    this.formItem.gold = Math.round(this.formItem.gold)
                }
            }
        },
        components: {
            VueEditor
        },
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .demo-upload-list{
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
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
