<template>
    <div>
        <v-nav v-if="$route.name !== 'index'"></v-nav>
        <div class="main content" :style="$route.name !== 'index' ? 'background-color: #fff' : '' ">
            <!--mode="out-in" enter-active-class="animated fadeIn" leave-active-class="animated zoomOutDown"-->
            <transition>
                <router-view></router-view>
            </transition>
        </div>
        <div class="footer" v-if="$route.name !== 'index'">
            <div class="foot">
                <ul>
                    <li>关于我们</li>
                    <li>|</li>
                    <li>加入我们</li>
                    <li>|</li>
                    <li>商务合作</li>
                </ul>
                Copyright © 2017 陕西熊猫人网络科技有限公司 嘿市网 版权所有
                <br>
                陕ICP备17015228号-1
            </div>
        </div>
        <div class="tool_bottom">
            <div class="feedback hover_hand" @click="feedback_model = true">
                意见<br>反馈
            </div>
            <div class="goTop hover_hand">
                返回<br>顶部
            </div>
        </div>

        <Modal v-model="feedback_model">
            <p slot="header" class="model_title">
             意见反馈
            </p>
            <div>
                <Form ref="formCustom" :model="formCustom" :rules="ruleCustom" :label-width="80">
                    <Form-item label="建议" prop="feedback">
                        <Input v-model="formCustom.feedback" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入您的建议"></Input>
                    </Form-item>
                    <Form-item label="昵称" prop="name">
                        <Input type="text" v-model="formCustom.name"></Input>
                    </Form-item>
                    <Form-item label="邮箱" prop="tel">
                        <Input type="text" v-model="formCustom.tel"></Input>
                    </Form-item>
                </Form>
            </div>
            <div slot="footer">
                <Button @click="go_feedback('formCustom')">确定</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import Nav from './nav/Nav.vue'

    export default {
        data() {
            return {
                feedback_model: false,
                formCustom: {
                    feedback: '',
                    name: '',
                    tel: '',
                },
                ruleCustom: {
                    feedback: [
                        {required: true, message: '建议不能为空', trigger: 'blur'},
                        {max: 180, message: '建议最长180字符', trigger: 'blur'},
                        {max: 180, message: '建议最长180字符', trigger: 'blur'},
                    ]
                }
            }
        },
        mounted() {
            let redirect = localStorage.getItem('redirect');
            if (redirect && redirect !== "/") {
                localStorage.setItem('redirect', "/")
                this.$router.push(redirect)
            }
            this._init()
            this.choice_camp()

            $('.goTop').css('opacity',0);
            $(window).on('scroll', function() {
                if ($('body').scrollTop() > 100) { /* 返回顶部按钮将在用户向下滚动100像素后出现 */
                    $('.goTop').css('opacity',1);
                } else {
                    $('.goTop').css('opacity',0);
                }
            });

            $('.goTop').click(function() {
                $("html, body").animate({scrollTop: 0}, 500);
            })

        },
        watch: {
            '$route'(to, from) {
                this.choice_camp()
            }
        },
        methods: {
            go_feedback(name) {
                console.log(name)
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put('/feedback',{data: this.formCustom}).then(res => {
                            if(res.data.sta === 1){
                                myDialog(res.data.msg)
                            }else{
                                myDialog('感谢您的建议')
                            }
                            this.feedback_model = false
                            this.formCustom.feedback = ''
                            this.formCustom.name = ''
                            this.formCustom.tel = ''
                        }).catch(err => {
                            myDialog('感谢您的建议')
                            this.feedback_model = false
                            this.formCustom.feedback = ''
                            this.formCustom.name = ''
                            this.formCustom.tel = ''
                        })
                    }
                })

            },
            choice_camp() {
                let choice_camp = window.localStorage.getItem('choice_camp')
                this.$store.commit('choice_camp', choice_camp)
            },
            _init() {
                axios.get('user/info').then(res => {
                    this.$store.commit('change_tools', res.data.tools)
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo', res.data.info)
                        if (res.data.info.is_active === 0) {
                            if(res.data.info.camp === 1){
                                this.$Notice.info({
                                    title: '您的帐号还未激活',
                                    desc: '已发送激活邮件到您邮箱，<a target="_blank" href=' + res.data.email + '>点击激活</a>。',
                                    duration: 0
                                });
                            }else{
                                this.$Notice.error({
                                    title: '您的帐号还未激活',
                                    desc: '已发送激活邮件到您邮箱，<a target="_blank" href=' + res.data.email + '>点击激活</a>。',
                                    duration: 0
                                });
                            }

                        }
                    } else {
                        this.userInfo = '';
                    }
                })
            },
        },
        components: {
            'v-nav': Nav
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .main.content
        width 1240px
        padding: 15px
        margin 0 auto

    .footer
        border-top 1px solid #f5f5f5
        width 1240px
        padding: 15px
        margin 0 auto
        background-color #fff
        text-align center
        display flex
        justify-content center
        .foot
            ul
                margin 0 auto
                li
                    padding 0 5px
                    display inline-block

    .tool_bottom
        position fixed
        bottom 30px
        right 0
        div
            background rgba(0,0,0,0.5)
            color #fff
            text-align center
            margin-bottom 2px
            padding 5px
</style>
