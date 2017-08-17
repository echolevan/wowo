<template>
    <div class="admin_content">
        <Menu theme="dark"  class="my_menu" accordion>
            <router-link to="/admin">
                <Menu-item name="0-1">
                    <Icon type="ios-paper"></Icon>
                    主页
                </Menu-item>
            </router-link>
            <Submenu name="1">
                <template slot="title">
                    <Icon type="ios-paper"></Icon>
                    用户管理
                </template>
                <Menu-item name="1-1">用户管理</Menu-item>
            </Submenu>
            <Submenu name="2">
                <template slot="title">
                    <Icon type="ios-people"></Icon>
                    插件管理
                </template>
                <Menu-item name="2-1">WA</Menu-item>
                <Menu-item name="2-2">TWM</Menu-item>
                <Menu-item name="2-3">魔兽插件</Menu-item>
                <Menu-item name="2-4">黑市</Menu-item>
            </Submenu>
            <Submenu name="3">
                <template slot="title">
                    <Icon type="ios-people"></Icon>
                    分类管理
                </template>
                <router-link to="/admin/tag/list">
                    <Menu-item name="3-1">分类列表</Menu-item>
                </router-link>
                <router-link to="/admin/tag/create">
                    <Menu-item name="3-2">添加分类</Menu-item>
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
                    <!--<Menu-item name="3-4">用户留存</Menu-item>-->
                    <!--<Menu-item name="3-5">流失用户</Menu-item>-->
                <!--</Menu-group>-->
            <!--</Submenu>-->
        </Menu>
        <div class="main content">
            <div class="head_title">
                <div class="pull-right">
                    <Dropdown>
                        <a class="user_name" href="javascript:void(0)">
                            hover 触发
                            <Icon type="arrow-down-b"></Icon>
                        </a>
                        <Dropdown-menu slot="list">
                            <Dropdown-item>修改信息</Dropdown-item>
                            <Dropdown-item>退出登陆</Dropdown-item>
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
    export default {
        data() {
            return {
                active: 0
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .admin_content
        .router-link-exact-active.router-link-active
            li
                background: #2d8cf0 !important;
                color #fff
        .my_menu
            width 200px !important
            height 100%
            position absolute
            left 0
            top 0
            z-index 3
            overflow-y: auto
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

