<template>
    <div>
        <Form :model="formItem" :label-width="80">
            <Form-item label="名称">
                <Input v-model="formItem.name" placeholder="请输入"></Input>
            </Form-item>
            <Form-item label="类型">
                <Cascader v-if="plug_tags.length > 0" :data="plug_tags" v-model="formItem.type" change-on-select  @on-change="on_sel"></Cascader>
            </Form-item>
            <Form-item label="分享图片" prop="share_img">
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

            <Form-item>
                <Button type="primary" @click="add_to">提交</Button>
                <Button type="ghost" style="margin-left: 8px">取消</Button>
            </Form-item>
        </Form>

        <Modal title="查看图片" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                formItem: {
                    type: [],
                    thumb: '',
                },
                imgName: '',
                visible: false,
                csrfToken : window.Laravel.csrfToken,
                plug_tags: []
            }
        },
        mounted() {
            axios.get('admin/plug_all_info').then(res=>{
                console.log(res)
                this.plug_tags = res.data
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
                this.formItem.type = v
                console.log(this.formItem.type)
            },
            add_to() {
                axios.put('admin/tag/create',{data:this.formItem}).then(res=>{
                    console.log(res)
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
