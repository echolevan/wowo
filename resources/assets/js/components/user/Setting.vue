<template>
    <div class="user_setting">
        <Tabs value="1" :class="{'bl_tab_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
            <Tab-pane label="基本信息" name="1">
                <Form :model="formItem" :label-width="80" ref="formValidate" :rules="ruleValidate" style="width: 500px">
                    <Form-item label="头像">
                        <img :src="formItem.avatar"  class="img-circle" id="set-avatar">
                    </Form-item>
                    <Form-item label="昵称" prop="name">
                        <Input v-model="formItem.name" placeholder="请输入"></Input>
                    </Form-item>
                    <Form-item label="阵营" >
                        <Select v-model="formItem.camp" :disabled="!is_camp" placeholder="请选择阵营">
                            <Option value="1">联盟</Option>
                            <Option value="2">部落</Option>
                        </Select>
                        <p v-if="!is_camp" class="normal_font" :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">30天内不能再次修改阵营</p>
                    </Form-item>
                    <Form-item label="介绍" prop="info">
                        <Input v-model="formItem.info" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入..."></Input>
                    </Form-item>
                    <Form-item>
                        <Button type="primary" @click="handleSubmit('formValidate')" class="pull-right"  :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">提交</Button>
                    </Form-item>
                </Form>
                <avatar-cropper
                        :upload-headers='{ "X-CSRF-TOKEN" : csrfToken}'
                        trigger="#set-avatar"
                        upload-url="/user/upload_avatar"
                        :uploaded="updateUserAvatar"
                ></avatar-cropper>
            </Tab-pane>
            <Tab-pane label="密码修改" name="2">
                <rest-password></rest-password>
            </Tab-pane>
            <Tab-pane label="邮箱修改" name="3">标签三的内容</Tab-pane>
            <Tab-pane label="手机号修改" name="4">标签三的内容</Tab-pane>
        </Tabs>

    </div>
</template>

<script>
    import AvatarCropper from 'vue-avatar-cropper'
    import restPassword from '../common/restPassword.vue'
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                formItem: {
                    avatar: '',
                    name: '',
                    camp: '',
                    info: ''
                },
                is_camp: false,
                ruleValidate: {
                    name: [
                        { required: true, message: '昵称不能为空', trigger: 'blur' }
                    ],
                    info: [
                        { type: 'string', max: 255, message: '介绍不能多于255个字', trigger: 'change' }
                    ]
                },
                csrfToken : window.Laravel.csrfToken
            }
        },
        methods: {
            check_is_camp() {
              axios.get('user/check_is_camp').then(res=>{
                  if(res.data.sta === 1){
                      this.is_camp = true
                  }else{
                      this.is_camp = false
                  }
              })
            },
            updateUserAvatar(res) {
                if(res.sta === 1){
                    this.$Message.success('头像更新成功')
                    this.formItem.avatar = res.info.avatar
                    this.$store.commit('change_userInfo',res.info)
                }else {
                    this.$Message.error(res.msg)
                }
            },
            handleSubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.post('user/update',{name:this.formItem.name,info:this.formItem.info,camp:this.formItem.camp}).then(res=>{
                            if(res.data.sta === 1){
                                this.check_is_camp()
                                this.$Message.success('信息更新成功')
                                this.$store.commit('change_userInfo',res.data.info)
                            }else{
                                this.$Message.error(res.data.msg)
                            }
                        })
                    } else {
                        this.$Message.error('表单验证失败!');
                    }
                })
            },
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        mounted(){
           setTimeout(()=>{
               this.formItem.avatar = this.userInfo.avatar
               this.formItem.name = this.userInfo.name
               this.formItem.camp = this.userInfo.camp + ''
               this.formItem.info = this.userInfo.info
           },300);
           this.check_is_camp()
        },
        components: {
            'avatar-cropper': AvatarCropper,
            'rest-password': restPassword
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .user_setting
        #set-avatar
            width 80px
            height 80px
</style>
