<template>
    <div>
        <div class="about">
            <Row>
                <iCol span="3">
                    <div class="about_title">
                        <ul>
                            <li @click="is_active = 1" class="hover_hand" :class="{'active': is_active === 1}">关于我们</li>
                            <li @click="is_active = 2" class="hover_hand" :class="{'active': is_active === 2}">广告合作</li>
                            <li @click="is_active = 3" class="hover_hand" :class="{'active': is_active === 3}">版权申明</li>
                        </ul>
                    </div>
                </iCol>
                <iCol span="21">
                    <div class="about_content">
                        <div v-show="is_active === 1">
                            <div class="header">
                                关于我们
                            </div>
                            <ol>
                                <li>1、关于我们关于我们关于我们</li>
                                <li>1、关于我们关于我们关于我们</li>
                                <li>1、关于我们关于我们关于我们</li>
                                <li>1、关于我们关于我们关于我们</li>
                                <li>1、关于我们关于我们关于我们</li>
                                <li>1、关于我们关于我们关于我们</li>
                            </ol>
                        </div>
                        <div v-show="is_active === 2">
                            <div class="header">
                                广告合作
                            </div>
                            <ol>
                                <li>1、广告合作</li>
                                <li>1、广告合作</li>
                                <li>1、广告合作</li>
                                <li>1、广告合作广告合作广告合作广告合作</li>
                                <li>1、关于我们关于我们关于我们</li>
                                <li>1、关于我们关于我们关于我们</li>
                            </ol>
                        </div>
                        <div v-show="is_active === 3">
                            <div class="header">
                                版权申明
                            </div>
                            <ol>
                                <li>1、版权申明</li>
                                <li>1、版权申明</li>
                                <li>1、版权申明</li>
                                <li>1、版权申明</li>
                                <li>1、版权申明</li>
                                <li>1、版权申明</li>
                            </ol>
                        </div>
                    </div>
                </iCol>
            </Row>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                is_active: 1
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .about
        border: 1px solid #f2f2f2
        .about_title
            background-color #EEE9E9
            ul
                li
                    text-align center
                    padding 15px 0
                    font-size 14px
                    &.active
                        color #266ec1
                        border-left 1px solid #266ec1
        .about_content
            padding 15px
            .header
                font-size 20px
                font-weight bold
                margin-bottom 5px
                border-bottom 1px solid #F2F2F2
            ol
                li
                    padding 5px
                    list-style-position:outside;

</style>
