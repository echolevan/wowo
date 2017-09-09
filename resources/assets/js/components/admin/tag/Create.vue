<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>分类管理</Breadcrumb-item>
            <Breadcrumb-item>添加分类</Breadcrumb-item>
        </Breadcrumb>
        <Form :model="formItem" :label-width="100" class="div_center from_main" style="width: 500px" ref="formItem" :rules="ruleValidate">
            <Form-item label="名称" prop="name">
                <Input v-model="formItem.name" placeholder="请输入"></Input>
            </Form-item>
            <Form-item label="类型" prop="type">
                <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="formItem.type" change-on-select  @on-change="on_sel"></Cascader>
            </Form-item>
            <Form-item label="标签图标" prop="thumb" v-show="formItem.type.length === 1">
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


            <Form-item label="用户可维护">
                <i-Switch v-model="formItem.is_for_user" size="large">
                    <span slot="open">可</span>
                    <span slot="close">否</span>
                </i-Switch>
            </Form-item>

            <Button type="primary" :loading="loading" @click="add_to('formItem')" class="pull-right">
                <span v-if="!loading">确定</span>
                <span v-else>Loading...</span>
            </Button>
        </Form>

        <Modal title="查看图标" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    export default {
        data() {
            const validateType = (rule, value, callback) => {
                if (value.length === 0) {
                    callback(new Error('插件分类不能为空'));
                } else {
                    callback();
                }
            };
            const validateName = (rule, value, callback) => {
                if (value !== '') {
                    axios.post('/admin/check_tag_name',{name: value, pid: this.formItem.type[1]}).then(res =>{
                        if(res.data.sta === 0){
                            callback(new Error('名称已存在'));
                        }else{
                            callback();
                        }
                    })
                } else {
                    callback();
                }
            };
            return {
                formItem: {
                    name: '',
                    type: [],
                    thumb: '',
                    is_for_user: true,
                },
                imgName: '',
                visible: false,
                loading: false,
                csrfToken : window.Laravel.csrfToken,
                plug_tags: [],
                ruleValidate: {
                    name: [
                        {required: true, message: '名称不能为空', trigger: 'blur'},
                        {max: 30, message: '标题最长30字符', trigger: 'change'},
                        {validator: validateName, required: true, trigger: 'blur'}
                    ],
                    type: [
                        {validator: validateType, required: true, trigger: 'change'}
                    ]
                }

            }
        },
        mounted() {
            axios.get('/admin/plug_all_info').then(res=>{
                this.plug_tags = res.data.res
            })
        },
        methods:{
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
                console.log(v)
                this.formItem.type = v
                console.log(this.formItem.type)
            },
            add_to(name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put('/admin/tag/create',{data:this.formItem}).then(res=>{
                            this.$Message.success('添加成功');
                            this.formItem.name = ''
                            this.formItem.type = []
                            this.formItem.thumb = ''
                            this.formItem.is_for_user = true
                            axios.get('/admin/plug_all_info').then(res=>{
                                this.plug_tags = res.data.res
                            })
                        })
                    }
                    this.loading = false;
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

    .div_center
        margin 50px auto

</style>
