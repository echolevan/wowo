<template>
    <div style="height:40px">
        <header class="cd-morph-dropdown">
            <a href="#0" class="nav-trigger">Open Nav<span aria-hidden="true"></span></a>

            <nav class="main-nav">
                <ul>
                    <li>
                        <router-link class="nav_link" to="/home"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            首页
                        </router-link>
                    </li>

                    <li class="has-dropdown links" data-content="wa">
                        <router-link class="nav_link" :to="{name:'watmw.index' , params:{'type':'wa'}}"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            WA
                        </router-link>
                    </li>

                    <li class="has-dropdown links" data-content="tmw">
                        <router-link class="nav_link" :to="{name:'watmw.index' , params:{'type':'tmw'}}"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            TMW
                        </router-link>
                    </li>

                    <li class="has-dropdown links" data-content="elvui">
                        <router-link class="nav_link" :to="{name:'watmw.index' , params:{'type':'elvui'}}"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            ElvUi
                        </router-link>
                    </li>

                    <li class="has-dropdown links" data-content="addons">
                        <router-link class="nav_link" :to="{name:'watmw.index' , params:{'type':'addons'}}"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            游戏插件
                        </router-link>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav_link"
                           :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span>易游</span></a>
                    </li>
                    <li v-if="tools.bm === '1'">
                        <router-link class="nav_link" to="/market"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            黑市
                        </router-link>
                    </li>
                    <li>
                        <a href="https://bbs.iwowcn.com" class="nav_link"
                           :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span>潘达利亚</span></a>
                    </li>
                    <li class="pull-right" style="margin: 5px 15px 0 0" v-show="$route.name === 'search' || $route.name === 'home.index'">
                        <Input v-model="keyword" @keyup.enter.native="to_search" style="width: 250px" placeholder="搜索资源" icon="search"
                               @on-click="to_search"></Input>
                    </li>
                </ul>
            </nav>

            <div class="morph-dropdown-wrapper">
                <div class="dropdown-list"
                     :class="{'bl_dropdown_list': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >
                    <ul>

                        <li id="wa" class="dropdown links">
                            <div class="content">
                                <ul v-for="v in nav_tags" v-if="v.label === 'WA'">
                                    <li v-for="vv in v.children">
                                        <h2 class="hover_hand normal_font"
                                            :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                        >
                                            <router-link
                                                    class="normal_font_import my_a_style"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'wa' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <ul class="links-list">
                                            <li class="hover_hand" v-for="vvv in vv.children">
                                                <router-link
                                                        class="my_a_style"
                                                        :to="{name:'watmw.index' , params:{'type':'wa' , 'active': vvv.value , 'active_pid': vv.value}}">
                                                    {{vvv.label}}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li id="tmw" class="dropdown links">
                            <div class="content">
                                <ul v-for="v in nav_tags" v-if="v.label === 'TMW'">
                                    <li v-for="vv in v.children">
                                        <h2
                                        >
                                            <router-link
                                                    class="normal_font_import my_a_style"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'tmw' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <ul class="links-list">
                                            <li class="hover_hand" v-for="vvv in vv.children">
                                                <router-link
                                                        class="my_a_style"
                                                        :to="{name:'watmw.index' , params:{'type':'tmw' , 'active': vvv.value , 'active_pid': vv.value}}">
                                                    {{vvv.label}}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li id="elvui" class="dropdown links">
                            <div class="content">
                                <ul v-for="v in nav_tags" v-if="v.label === 'ElvUI'">
                                    <li v-for="vv in v.children">
                                        <h2
                                        >
                                            <router-link
                                                    class="normal_font_import my_a_style"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'elvui' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <ul class="links-list">
                                            <li class="hover_hand" v-for="vvv in vv.children">
                                                <router-link
                                                        class="my_a_style"
                                                        :to="{name:'watmw.index' , params:{'type':'elvui' , 'active': vvv.value , 'active_pid': vv.value}}">
                                                    {{vvv.label}}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li id="addons" class="dropdown links">
                            <div class="content">
                                <ul v-for="v in nav_tags" v-if="v.label === '游戏插件'">
                                    <li v-for="vv in v.children">
                                        <h2 class="hover_hand normal_font"
                                            :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                        >
                                            <router-link
                                                    class="normal_font_import my_a_style"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'addons' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <!--<ul class="links-list">-->
                                            <!--<li class="hover_hand" v-for="vvv in vv.children">-->
                                                <!--<router-link-->
                                                        <!--class="my_a_style"-->
                                                        <!--:to="{name:'watmw.index' , params:{'type':'addons' , 'active': vvv.value , 'active_pid': vv.value}}">-->
                                                    <!--{{vvv.label}}-->
                                                <!--</router-link>-->
                                            <!--</li>-->
                                        <!--</ul>-->
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>

                    <div class="bg-layer" aria-hidden="true"></div>
                </div> <!-- dropdown-list -->
            </div> <!-- morph-dropdown-wrapper -->
        </header>
        <main class="cd-main-content">

        </main>
    </div>
</template>

<script>
    import {myMain} from '../../../common/new/main.js'
    import {myModernizr} from '../../../common/new/modernizr-custom.js'
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                nav_tags: [],
                configUrl: {
                    'TMW': 'tmw',
                    'WA': 'wa',
                    '游戏插件': 'plug'
                },
                keyword: ''
            }
        },
        mounted() {
            axios.get('plug_all_info_nav').then(res => {
                this.nav_tags = res.data.res
            })
        },
        methods: {
            to_search(){
                if(this.keyword === ''){
                    myDialog('请先输入关键词',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    return false
                }
                let d = new Date()
                let time_now = d.getMinutes()
                let last_time = JSON.parse(localStorage.getItem('search_time'))

                if(!last_time || parseInt(last_time[0]) !== parseInt(time_now)){
                    this.go_to_search()
                    localStorage.setItem('search_time',JSON.stringify([time_now , 0]))
                    return false
                }else{
                    let num = parseInt(last_time[1])
                    if(num >= 15){
                        myDialog('站点设置每分钟系统最多响应搜索请求 15 次，请稍候再试',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                        return false
                    }
                    localStorage.setItem('search_time',JSON.stringify([time_now , num++]))
                    this.go_to_search()
                    return false
                }
            },
            go_to_search(){
                this.$router.push({'name':'search',params:{'keyword':this.keyword}})
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'tools'
        ])
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../common/new/style.css"
    .nav_link
        margin-right 5px

    .nav_link.router-link-active
        background #266ec1
        color #fff
</style>
