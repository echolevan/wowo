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

                    <li class="has-dropdown links" data-content="plug">
                        <router-link class="nav_link" :to="{name:'watmw.index' , params:{'type':'plug'}}"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            游戏插件
                        </router-link>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav_link"
                           :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span>易游</span></a>
                    </li>
                    <li v-if="tools.bm === '1'">
                        <router-link class="nav_link" to="/bm"
                                     :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
                            黑市
                        </router-link>
                    </li>
                    <li>
                        <a href="https://bbs.iwowcn.com" class="nav_link"
                           :class="{'bl_active_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"><span>潘达利亚</span></a>
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
                                                    class="normal_font_import"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'wa' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <ul class="links-list">
                                            <li class="hover_hand" v-for="vvv in vv.children">
                                                <router-link
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
                                                    class="normal_font_import"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'tmw' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <ul class="links-list">
                                            <li class="hover_hand" v-for="vvv in vv.children">
                                                <router-link
                                                        :to="{name:'watmw.index' , params:{'type':'tmw' , 'active': vvv.value , 'active_pid': vv.value}}">
                                                    {{vvv.label}}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li id="plug" class="dropdown links">
                            <div class="content">
                                <ul v-for="v in nav_tags" v-if="v.label === '游戏插件'">
                                    <li v-for="vv in v.children">
                                        <h2 class="hover_hand normal_font"
                                            :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                        >
                                            <router-link
                                                    class="normal_font_import"
                                                    :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                                                    :to="{name:'watmw.index' , params:{'type':'plug' , 'active': vv.value , 'active_pid': 0}}">
                                                {{vv.label}}
                                            </router-link>
                                        </h2>
                                        <ul class="links-list">
                                            <li class="hover_hand" v-for="vvv in vv.children">
                                                <router-link
                                                        :to="{name:'watmw.index' , params:{'type':'plug' , 'active': vvv.value , 'active_pid': vv.value}}">
                                                    {{vvv.label}}
                                                </router-link>
                                            </li>
                                        </ul>
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
                }
            }
        },
        mounted() {
            axios.get('plug_all_info_nav').then(res => {
                console.log(res)
                this.nav_tags = res.data.res
            })
        },
        computed: mapState([
            'userInfo', 'choice_cmap', 'tools'
        ])
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../common/new/style.css"
    .nav_link
        margin 0 5px

    .nav_link.router-link-active
        background #266ec1
        color #fff
</style>
