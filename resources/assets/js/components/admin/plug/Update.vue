<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px" v-show="$route.name === 'admin.plug.update'">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>资源管理</Breadcrumb-item>
            <Breadcrumb-item>资源编辑</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formItem" :label-width="100" ref="formItem" :rules="ruleValidate">
            <Form-item label="标题" prop="title">
                <Input v-model="formItem.title" placeholder="请输入"></Input>
            </Form-item>

            <Form-item label="分类" prop="type">
                <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="formItem.type"
                          @on-change="on_sel"></Cascader>
            </Form-item>

            <Form-item label="插件名称" prop="name" v-show="formItem.type[0] === 3">
                <Input v-model="formItem.name" placeholder="插件名称"></Input>
            </Form-item>

            <Form-item label="插件作者" prop="author" v-show="formItem.type[0] === 3">
                <Input v-model="formItem.author" placeholder="作者信息"></Input>
            </Form-item>

            <Form-item label="插件版本" prop="version" v-show="formItem.type[0] === 3">
                <Input v-model="formItem.version" placeholder="插件版本号"></Input>
            </Form-item>

            <Form-item label="游戏版本" prop="game_version">
                <Select v-model="formItem.game_version" style="width:200px" placeholder="请选择游戏版本号">
                    <Option v-for="item in game_versions" :value="item.value" :key="item.id">{{ item.value }}</Option>
                </Select>
            </Form-item>

            <Form-item label="是否收费" v-show="formItem.type[0] < 3">
                <i-Switch v-model="formItem.is_free" size="large" @on-change="swi">
                    <span slot="open">是</span>
                    <span slot="close">否</span>
                </i-Switch>
            </Form-item>

            <Form-item label="售价(金币)" v-show="formItem.is_free" prop="gold">
                <Input-number
                        :min="1"
                        v-model="formItem.gold"
                        @on-change="change_other"></Input-number>
                <span v-if="formItem.gold === 1">(您将获得 <span
                        class="normal_font"
                >{{tools.fc}}</span>% 的金币 , 即 <span
                        class="normal_font"
                >1</span> 金币)</span>
                <span v-else>(您将获得 <span
                        class="normal_font"
                >{{tools.fc}}</span>% 的金币 , 即 <span
                        class="normal_font"
                >{{ Math.floor(formItem.gold * tools.fc / 100)}}</span> 金币)</span>
            </Form-item>

            <Form-item label="字符串" v-show="formItem.type[0] === 1 || formItem.type[0] === 2" prop="content">
                <Input v-model="formItem.content" type="textarea" :rows="8" placeholder="请输入字符串(不能包含中文)"  v-on:input="keyUp"></Input>
                <p class="pull-right "
                >共 <span class="normal_font"
                         :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                         style="font-weight:bold"
                >{{formItem.content.length}}</span> 字符 </p>
            </Form-item>

            <Form-item label="上传插件" v-show="formItem.type[0] === 3" prop="plug_url">
                <Upload action="/upload_plug_info_plug"
                        :data="{'tag_one': selectedDataName}"
                        ref="uploadPlug"
                        :format="['rar','zip','7z']"
                        :max-size="selectedDataName === '整合界面' ? 307200 : 10240"
                        :on-exceeded-size="handleMaxSize"
                        :on-format-error="handleFormatError"
                        :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                        :on-success="handlePlugSuccess"
                        :before-upload="handlePlugUpload"
                        :on-remove="removePlug"
                >
                    <Button type="ghost" icon="ios-cloud-upload-outline">{{formItem.plug_url === '' ? '上传文件' : '重新上传'}}</Button>
                </Upload>
                <span v-if="formItem.plug_url"  style="color: #d13030">已上传</span>
            </Form-item>


            <Form-item label="上传截图" prop="uploadList">
                <div class="demo-upload-list" v-for="(item , k) in formItem.uploadList">
                    <template v-if="item.status === 'finished'">
                        <img :src="item.url">
                        <div class="demo-upload-list-cover">
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
                        :format="['jpg','jpeg','png','gif']"
                        :on-format-error="hFE"
                        :show-upload-list="false"
                        :before-upload="handleBeforeUpload"
                        :default-file-list="defaultList"
                        :on-success="handleSuccess"
                        multiple
                        type="drag"
                        :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                        action="/upload_plug_screen_img"
                        style="display: inline-block;width:150px;">
                    <div style="width: 150px;height:150px;padding-top:25px">
                        <i class="ivu-icon ivu-icon-ios-cloud-upload" style="font-size: 52px">
                        </i>
                        <p style="font-size:16px">
                            点击或将文件拖拽到这里上传(最多20张)
                        </p>
                    </div>
                </Upload>
            </Form-item>

            <Form-item label="更新日志" prop="updated_info">
                <Input v-model="formItem.updated_info" type="textarea" :autosize="{minRows: 2}"
                       placeholder="请输入更新日志"></Input>
                <p class="pull-right "
                >共 <span class="normal_font"
                         :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                         style="font-weight:bold"
                >{{formItem.updated_info.length}}</span> 字符 </p>
            </Form-item>


            <Form-item label="功能简介" prop="info">
                <vue-editor v-model="formItem.info" useCustomImageHandler @imageAdded="handleImageAdded"></vue-editor>
            </Form-item>



            <div class="my_ok_button">
                <Button type="primary" :loading="loading"  @click="toLoading('formItem')">
                    <span v-if="!loading">确定</span>
                    <span v-else>Loading...</span>
                </Button>
            </div>
            <div style="clear: both"></div>

        </Form>

        <Modal title="查看截图" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>

    </div>
</template>

<script>
    import {VueEditor} from 'vue2-editor'
    import { mapState } from 'vuex'

    export default {
        data() {
            const validateUploadList = (rule, value, callback) => {
                setTimeout(() =>  {
                    if (value.length === 0) {
                        callback(new Error('请上传截图'));
                    } else {
                        callback();
                    }
                }, 10);
            };
            const validateType = (rule, value, callback) => {
                if (value.length === 0) {
                    callback(new Error('分类不能为空'));
                } else {
                    callback();
                }
            };
            const validategold = (rule, value, callback) => {
                if (value.length === 0) {
                    if(this.formItem.is_free === true){
                        callback(new Error('金币不能为空'));
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
                        callback(new Error('字符串不能为空'));
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
                            callback(new Error('请上传插件'));
                        }else{
                            callback();
                        }
                    } else {
                        callback();
                    }
                }, 10);
            };
            const validateversion = (rule, value, callback) => {
                if (this.formItem.type[0] === 3) {
                    if (value === '') {
                        callback(new Error('插件版本号不能为空'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            const validatename = (rule, value, callback) => {
                if (this.formItem.type[0] === 3) {
                    if (value === '') {
                        callback(new Error('插件名称不能为空'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            return {
                game_versions:[],
                plug_tags:[],
                formItem:{
                    title: '',
                    type: [],
                    content: '',
                    info: '',
                    author: '',
                    updated_info: '',
                    version: '',
                    game_version: '',
                    is_free: false,
                    gold: 1,
                    uploadList: [],
                    plug_url: '',
                    name: ''
                },
                defaultList: [],
                selectedDataName: '',
                imgName: '',
                visible: false,
                del_plug_sta: 1,
                loading: false,
                csrfToken : window.Laravel.csrfToken,
                ruleValidate: {
                    title: [
                        {required: true, message: '标题不能为空', trigger: 'blur'},
                        {max: 120, message: '标题最长120字符', trigger: 'change'}
                    ],
                    type: [
                        {validator: validateType, required: true, trigger: 'change'}
                    ],
                    content: [
                        {validator: validateContent,required: true, trigger: 'blur'}
                    ],
                    plug_url: [
                        {validator: validateContentUrl,required: true, trigger: 'change'}
                    ],
                    info: [
                        {required: true, message: '简介不能为空'}
                    ],
                    updated_info: [
                        {required: true, message: '更新日志不能为空', trigger: 'blur'},
                        {max: 300, message: '更新日志最长300字符', trigger: 'change'},
                        {max: 300, message: '更新日志最长300字符', trigger: 'blur'},
                    ],
                    uploadList: [
                        {validator: validateUploadList, required: true, trigger: 'change'},
                    ],
                    game_version: [
                        {required: true, message: '游戏版本号不能为空', trigger: 'blur'}
                    ],
                    gold: [
                        {validator: validategold,required: true, trigger: 'change'}
                    ],
                    version: [
                        {validator: validateversion,required: true, trigger: 'blur'}
                    ],
                    name: [
                        {validator: validatename,required: true,  trigger: 'blur'},
                        {max: 30, message: '插件名称最长30字符', trigger: 'change'},
                        {max: 30, message: '插件名称最长30字符', trigger: 'blur'},
                    ],
                }
            }
        },
        mounted(){
            this._init()
            this.formItem.uploadList = this.$refs.upload.fileList;
        },
        watch: {
            formItem(){
                this.keyUp()
            },
            '$route'(to, from) {
                this.$router.go(-1)
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap' ,'tools'
        ]),
        methods: {
            keyUp() {
                this.formItem.content = this.formItem.content.replace(/[\u4E00-\u9FA5]/g,"")
//                this.formItem.content = this.formItem.content.replace(/[^\w\.\/]/ig,'')
            },
            toLoading (name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put(`/update_plug/${this.$route.params.id}` , {data:this.formItem}).then(res=>{
                            if(res.data.sta === 0){
                                this.$Message.error(res.data.msg)
                            }else{
                                this.$Message.success(res.data.msg)
                                this.$router.push('/admin/plug/list')
                            }
                        })
                    }
                    this.loading = false;
                })
            },
            swi() {
                this.formItem.gold = 1
            },
            handleFormatError(){
                this.$Message.error('请上传rar、zip、7z格式的文件')
            },
            on_sel(v, selectedData) {
                this.selectedDataName = selectedData[1].label
                this.formItem.type = v
                this.formItem.content = ''
                this.formItem.is_free = false
            },
            handleBeforeUpload(){
                const check = this.$refs.upload.fileList.length < 20;
                if (!check) {
                    this.$Message.error('最多只能上传 20 张图片。')
                }
                return check;
            },
            _init(){
                axios.get(`/update_plugInfo/${this.$route.params.id}`).then(res=>{
                    if(res.data.sta === 0){
                        this.$router.go(-1)
                    }
                    this.formItem.title = res.data.plug.title
                    this.formItem.type = res.data.plug.type
                    this.formItem.content = res.data.plug.content
                    this.formItem.info = res.data.plug.info
                    this.formItem.author = res.data.plug.author
                    this.formItem.updated_info = res.data.plug.updated_info
                    this.formItem.version = res.data.plug.version
                    this.formItem.game_version = res.data.plug.game_version
                    this.formItem.is_free = res.data.plug.is_free
                    this.formItem.gold = res.data.plug.gold
                    this.formItem.name = res.data.plug.name
                    this.formItem.plug_url = res.data.plug.content
                    this.formItem.uploadList = res.data.plug.thumbs
                    this.defaultList = res.data.plug.thumbs
                }).catch(error=>{
                    history.go(-1)
                })
                axios.get('/plug_all_info').then(res=>{
                    this.plug_tags = res.data.res
                    this.game_versions = res.data.game_versions
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
                            this.$Message.error(result.data.msg)
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
            handleRemove (file) {
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)
                this.formItem.uploadList = this.$refs.upload.fileList
            },
            handleSuccess (res, file , fileList) {
                if(res.sta === 0){
                    this.$Message.error(res.msg)
                }else{
                    file.url = res.url
                    file.width = res.width
                    file.height = res.height
                    this.formItem.uploadList = fileList;
                    this.$Loading.finish()
                }
            },
            handlePlugSuccess(res, file) {
                this.upload_status = false
                if(res.sta === 0){
                    this.$Message.error(res.msg)
                }else{
                    this.$refs.uploadPlug.clearFiles()
                    this.formItem.plug_url = res.url
                }
            },
            hFE() {
                this.$Message.error('请上传jpg、jepg、png、gif格式的文件')
            },
            handleMaxSize (file) {
                this.upload_status = false
                this.$Message.error('文件 ' + file.name + ' 已超过' + (this.selectedDataName === '整合界面' ? 300 : 100) + 'M限制')
            },
            handlePlugUpload(){
                if(!this.upload_status){
                    this.upload_status = true
                }else{
                    this.$Message.error('请等待上传完成')
                    return false
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
        width: 150px;
        height: 150px;
        text-align: center;
        line-height: 150px;
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
