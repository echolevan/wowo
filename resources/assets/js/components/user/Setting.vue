<template>
    <div class="user_setting">
        <Tabs value="1" :class="{'bl_tab_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
            <Tab-pane label="基本信息" name="1">
                <Form :model="formItem" :label-width="80" ref="formValidate" :rules="ruleValidate" style="width: 500px">
                    <Form-item label="头像">
                        <img :src="formItem.avatar"  class="img-circle hover_hand" id="set-avatar">
                    </Form-item>
                    <Form-item label="昵称" prop="nickname">
                        <Input v-model="formItem.nickname" placeholder="请输入"></Input>
                    </Form-item>
                    <Form-item label="阵营" >
                        <Select v-model="formItem.camp" :disabled="!is_camp" placeholder="请选择阵营">
                            <Option value="1">联盟</Option>
                            <Option value="2">部落</Option>
                        </Select>
                        <p v-if="!is_camp" class="normal_font" :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">阵营修改间隔时间为 30 天</p>
                    </Form-item>
                    <Form-item label="性别" >
                        <Select v-model="formItem.sex"  placeholder="请选择性别">
                            <Option value="0">保密</Option>
                            <Option value="1">男</Option>
                            <Option value="2">女</Option>
                        </Select>
                    </Form-item>
                    <Form-item label="生日" >
                        <Date-picker type="date" v-model="formItem.birthday" placeholder="选择日期" style="width: 200px"></Date-picker>
                    </Form-item>
                    <Form-item label="出生地" >
                        <v-distpicker :province="formItem.birthplace.province" :city="formItem.birthplace.city" :area="formItem.birthplace.area"
                                      @province="onProvinceBirthplace"
                                      @city="onCityBbirthplace"
                                      @area="onAreaBirthplace"
                                      class="my_address"></v-distpicker>
                    </Form-item>
                    <Form-item label="居住地" >
                        <v-distpicker :province="formItem.habitably.province" :city="formItem.habitably.city" :area="formItem.habitably.area"
                                      @province="onProvinceHabitably"
                                      @city="onCityBHabitably"
                                      @area="onAreaHabitably"
                                      class="my_address"></v-distpicker>
                    </Form-item>
                    <Form-item label="个人简介" prop="info">
                        <Input v-model="formItem.info" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入"></Input>
                    </Form-item>
                    <Form-item>
                        <Button type="primary" @click="handleSubmit('formValidate')" class="pull-right"  :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">确定</Button>
                    </Form-item>
                </Form>
                <avatar-cropper
                        :upload-headers='{ "X-CSRF-TOKEN" : csrfToken}'
                        trigger="#set-avatar"
                        upload-url="/user/upload_avatar"
                        :uploaded="updateUserAvatar"
                ></avatar-cropper>
            </Tab-pane>
            <Tab-pane label="修改密码" name="2">
                <rest-password></rest-password>
            </Tab-pane>
            <Tab-pane label="修改邮箱" name="3">标签三的内容</Tab-pane>
            <Tab-pane label="修改手机" name="4">标签三的内容</Tab-pane>
        </Tabs>

    </div>
</template>

<script>
    import AvatarCropper from 'vue-avatar-cropper'
    import restPassword from '../common/restPassword.vue'
    import {mapState} from 'vuex'
    import VDistpicker from 'v-distpicker'

    export default {
        data() {
            const validateName = (rule, value, callback) => {
                if (value !== '') {
                    axios.get(`/user/check_nickname/${value}`).then(res => {
                        if(res.data === 0){
                            callback(new Error('昵称已存在或违规'));
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
                    avatar: '',
                    nickname: '',
                    camp: '',
                    info: '',
                    sex: '',
                    birthday: '',
                    birthplace: {
                        province: '省',
                        city: '市',
                        area: '区',
                    },
                    habitably: {
                        province: '省',
                        city: '市',
                        area: '区',
                    }
                },
                is_camp: false,
                ruleValidate: {
                    nickname: [
                        { required: true, message: '昵称不能为空', trigger: 'blur' },
                        {validator: validateName, trigger: 'blur'}
                    ],
                    info: [
                        { type: 'string', max: 255, message: '简介内容不能超过255个字', trigger: 'change' }
                    ]
                },
                csrfToken : window.Laravel.csrfToken,
            }
        },
        methods: {
            onProvinceBirthplace(data) {
                this.formItem.birthplace.province = data.value
            },
            onCityBbirthplace(data) {
                this.formItem.birthplace.city = data.value
            },
            onAreaBirthplace(data) {
                console.log(data)
                this.formItem.birthplace.area = data.value
            },

            onProvinceHabitably(data) {
                this.formItem.habitably.province = data.value
            },
            onCityBHabitably(data) {
                this.formItem.habitably.city = data.value
            },
            onAreaHabitably(data) {
                this.formItem.habitably.area = data.value
            },

            check_is_camp() {
              axios.get('user/check_is_camp').then(res=>{
                  if(res.data.sta === 1){
                      this.is_camp = true
                  }else{
                      this.is_camp = false
                  }
              })
            },
            put_v(){
                this.formItem.avatar = this.userInfo.avatar
                this.formItem.nickname = this.userInfo.nickname
                this.formItem.birthday = this.userInfo.birthday
                this.formItem.camp = this.userInfo.camp + ''
                this.formItem.birthplace = this.userInfo.birthplace ? this.userInfo.birthplace : {
                    province: '',
                    city: '',
                    area: '',
                }
                this.formItem.habitably = this.userInfo.habitably ? this.userInfo.habitably : {
                    province: '',
                    city: '',
                    area: '',
                }
                this.formItem.sex = this.userInfo.sex + ''
                this.formItem.info = this.userInfo.info
            },
            updateUserAvatar(res) {
                if(res.sta === 1){
                    myDialog('头像更新成功')
                    this.formItem.avatar = res.info.avatar
                    this.$store.commit('change_userInfo',res.info)
                }else {
                    myDialog(res.msg)
                }
            },
            handleSubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.post('user/update',{
                            nickname:this.formItem.nickname,
                            info:this.formItem.info,
                            camp:this.formItem.camp,
                            sex:this.formItem.sex,
                            birthday:this.formItem.birthday,
                            birthplace:this.formItem.birthplace,
                            habitably:this.formItem.habitably,
                        }).then(res=>{
                            if(res.data.sta === 1){
                                this.check_is_camp()
                                myDialog('信息更新成功')
                                this.$store.commit('change_userInfo',res.data.info)
                            }else{
                                myDialog(res.data.msg)
                            }
                        })
                    }
                })
            },
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        watch: {
          userInfo() {
              this.put_v()
            }
        },
        mounted(){
            if(this.userInfo){
                this.put_v()
            }
           this.check_is_camp()
        },
        components: {
            'avatar-cropper': AvatarCropper,
            'rest-password': restPassword,
            'v-distpicker': VDistpicker
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .user_setting
        #set-avatar
            width 80px
            height 80px
</style>
