<template>
    <div class="admin_content">
        <Menu theme="dark"  class="my_menu" :active-name="$route.name" accordion>
            <div class="admin_login">
                <a href="/#/home" style="color: #fff">熊猫人</a>
            </div>
            <router-link to="/admin" class="ivu-menu-submenu" >
                <Menu-item name="admin.index" style="padding-left: 24px">
                    <Icon type="ios-paper"></Icon>
                    主页
                </Menu-item>
            </router-link>
            <Submenu name="4">
                <template slot="title">
                    <Icon type="ios-people"></Icon>
                    网站管理
                </template>
                <router-link to="/admin/tool/setting">
                    <Menu-item name="admin.tool.setting">页面管理</Menu-item>
                </router-link>
                <router-link to="/admin/notice/setting">
                    <Menu-item name="admin.notice.setting">公告管理</Menu-item>
                </router-link>
                <router-link to="/admin/ad/setting">
                    <Menu-item name="admin.ad.setting">广告管理</Menu-item>
                </router-link>
                <router-link to="/admin/fc/setting">
                    <Menu-item name="admin.fc.setting">分成管理</Menu-item>
                </router-link>
            </Submenu>
            <Submenu name="1">
                <template slot="title">
                    <Icon type="ios-paper"></Icon>
                    用户管理
                </template>
                <router-link to="/admin/user/list">
                    <Menu-item name="admin.user.list">用户管理</Menu-item>
                </router-link>
                <router-link to="/admin/lv/setting">
                    <Menu-item name="admin.lv.setting">等级管理</Menu-item>
                </router-link>
                <router-link to="/admin/nickname/setting/">
                    <Menu-item name="admin.nickname.setting">昵称管理</Menu-item>
                </router-link>
            </Submenu>
            <Submenu name="5">
                <template slot="title">
                    <Icon type="ios-paper"></Icon>
                    充值管理
                </template>
                <router-link to="/admin/recharge/list">
                    <Menu-item name="admin.recharge.list">充值列表</Menu-item>
                </router-link>
                <router-link to="/admin/withdraw/list">
                    <Menu-item name="admin.withdraw.list">提现列表</Menu-item>
                </router-link>
            </Submenu>
            <Submenu name="2">
                <template slot="title">
                    <Icon type="ios-people"></Icon>
                    资源管理
                </template>
                <router-link to="/admin/plug/list">
                    <Menu-item name="admin.plug.list">资源列表</Menu-item>
                </router-link>
                <router-link to="/admin/plug/create">
                    <Menu-item name="admin.plug.create">添加资源</Menu-item>
                </router-link>
                <router-link to="/admin/bm/list">
                    <Menu-item name="admin.bm.list">黑市资源</Menu-item>
                </router-link>
                <router-link to="/admin/game_version/setting/">
                    <Menu-item name="admin.game_version.setting">游戏版本管理</Menu-item>
                </router-link>
            </Submenu>
            <Submenu name="3">
                <template slot="title">
                    <Icon type="ios-people"></Icon>
                    分类管理
                </template>
                <router-link to="/admin/tag/list">
                    <Menu-item name="admin.tag.list">分类列表</Menu-item>
                </router-link>
                <router-link to="/admin/tag/create">
                    <Menu-item name="admin.tag.create">添加分类</Menu-item>
                </router-link>
            </Submenu>
            <!--<Submenu name="3">-->
                <!--<template slot="title">-->
                    <!--<Icon type="stats-bars"></Icon>-->
                    <!--统计分析-->
                <!--</template>-->
                <!--<Menu-group title="使用">-->
                    <!--<Menu-item name="3-1">新增和启动</Menu-item>-->
                    <!--<Menu-item name="3-2">活跃分析</Menu-item>-->
                    <!--<Menu-item name="3-3">时段分析</Menu-item>-->
                <!--</Menu-group>-->
                <!--<Menu-group title="留存">-->
                    <!--<Menu-item name="3-4">留存用户</Menu-item>-->
                    <!--<Menu-item name="3-5">流失用户</Menu-item>-->
                <!--</Menu-group>-->
            <!--</Submenu>-->
        </Menu>
        <div class="main content">
            <div class="head_title">
                <div class="pull-right">
                    <Dropdown>
                        <a class="user_name" href="javascript:void(0)">
                            {{userInfo.nickname}}
                            <Icon type="arrow-down-b"></Icon>
                        </a>
                        <Dropdown-menu slot="list">
                            <Dropdown-item ><a href="/#/userInfo/setting/2" target="_blank">修改密码</a></Dropdown-item>
                            <Dropdown-item ><span @click="logout">退出登陆</span></Dropdown-item>
                        </Dropdown-menu>
                    </Dropdown>
                </div>
            </div>
            <transition mode="out-in" enter-active-class="animated fadeInLeft"
                        leave-active-class="animated fadeOutRight">
                <router-view class="r_main"></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    export default {
        data() {
            return {
                active: 0
            }
        },
        computed: mapState([
            'userInfo'
        ]),
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get('/user/info').then(res => {
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo',res.data.info)
                        if(res.data.info.is_active === 0){
                            this.$Notice.open({
                                title: '请验证您的安全邮箱',
                                desc: '已发送验证邮件到您邮箱，<a target="_blank" href=' + res.data.email + '>点击验证</a>。',
                                duration: 0
                            });
                        }
                    }else{
                        this.userInfo = '';
                    }
                })
            },
            logout() {
                axios.get('/user/logout').then(res=>{
                    if (res.data.sta === '1') {
                        this.$store.commit('change_userInfo','')
                        window.location.href='/'
                    }
                })
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .admin_content
        .my_menu
            width 200px !important
            height 100%
            position absolute
            left 0
            top 0
            z-index 3
            overflow-y: auto
            .admin_login
                height 40px
                line-height 40px
                color #fff
                text-align center
        .main.content
            position absolute
            width 100%
            height 100%
            z-index 2
            overflow-y: auto
            .head_title
                position absolute
                top 0
                left 0
                width 100%
                height 40px
                line-height 40px
                padding 0 30px
                background-color #495060
                z-index 10
                .user_name
                    color #fff
            .r_main
                padding 55px 15px 15px 215px
                background-color #fff
                height 100%
                overflow auto
</style>

