<template>
    <div>
        <div class="about">
            <Row>
                <iCol span="3">
                    <div class="about_title">
                        <ul>
                            <li @click="is_active = 1" class="hover_hand" :class="{'active': is_active === 1}">关于我们</li>
                            <li @click="is_active = 2" class="hover_hand" :class="{'active': is_active === 2}">广告合作</li>
                            <li @click="is_active = 3" class="hover_hand" :class="{'active': is_active === 3}">版权声明</li>
                        </ul>
                    </div>
                </iCol>
                <iCol span="21">
                    <div class="about_content">
                        <div v-show="is_active === 1">
                            <div class="header">
                                关于我们
                            </div>
                            <div class="title">
                                魔兽情缘
                            </div>
                            <ol>
                                <li>
                                    嘿市（www.iwowcn.com），源于暴雪旗下网络游戏魔兽世界内“黑市”一词，为符合网站备案要求故改称“嘿市”，隶属陕西熊猫人网络科技有限公司旗下网站，公司名称也源于魔兽世界中立种族“熊猫人”。
                                </li>
                                <li>
                                    2009年跟朋友合租时，他是魔兽玩家，第一次接触魔兽世界。
                                </li>
                                <li>
                                    2012年熊猫人之谜版本时正式被一帮损友带入WOW，种族熊猫人，阵营联盟，角色名司马清源，虽然一直感觉不是暴雪dad亲生的，依然坚守，家里至今还放着一套未拆封熊猫人之谜典藏版实体客户端，时至今日，数年已逝，嫣然已成胖大叔。
                                </li>
                                <li>
                                    2014年开始琢磨WeakAuras，虽然不是码农出身，但出于对WOW的热爱，被魔兽世界的高自由度吸引，研究分享界面UI、插件的时间远远大于任务副本时间，混迹于某知名论坛。铁炉堡站岗几十个小时以上，以国外WA初代大神magicaldandruff(感谢前辈奉献)效果为基础，加以优化修改，增加技能效果，后来发现优化修改的时间远远大于重做，于是有了思路和想法就开始自己重制，半年时间出了7职业全天赋效果并免费分享至某论坛，期间也优化修改过部分实用单体插件。
                                </li>
                                <li>
                                    2015年下半年由于工作原因AFK。
                                </li>
                                <li>
                                    2017年初开始回归，在某论坛分享整合界面、优化修改后的单体插件，不想却引来了不必要的麻烦，遂退出某论坛，暂时AFK。群友建议做个自己的分享站(在此感谢群友支持)，不用再看别人脸色，于是有了一些想法，成为了本站的雏形；6月下旬开始着手筹备，因为不想使用WordPress将就，故找人定制网站，历时3个多月，于2017年10月1日正式上线。
                                </li>
                            </ol>
                        </div>
                        <div v-show="is_active === 2">
                            <div class="header">
                                广告合作
                            </div>
                            <div class="title">
                                广告投放说明，请详细阅读：
                            </div>
                            <ol>
                                <li>1、所有价格单位：元/月，3个月起租(1个月=30天)</li>
                                <li>2. 先付款并款到帐后开始投放广告（支付方式基本上都是当天到帐）！</li>
                                <li>3. 虚拟主机或其它经营性网站的广告，原则上需要公司性质才可投放，但个人经营也可接受投放！</li>
                                <li>4. 不接受六合彩、邮件营销、放置木马及淫秽激情类站点的广告！不接弹窗、插件、捆绑类广告！</li>
                                <li>5. 广告图片请自行准备好发至shangwu@iwowcn.com，具体广告图片可以加QQ（200827029）详谈，请注明来意！</li>
                                <li>6. 广告位租出后 不给予退同时不能更换到其它位置！</li>
                                <li>7. 广告续费请提前三天付款！未能及时续费本站将有权利预定给其他公司！</li>
                                <li>8. 广告图片必须通过我们的审核才可投放（如文件大小以及图片质量），广告图片为JPEG或GIF格式！</li>
                                <li>9. 广告商投放的广告如有虚假，或者有人举报为欺诈或欺诈公司，如广告商没办法做出有效答复或解释，本站有权利去除广告并不给予退款！</li>
                                <li>10. 注意：现有的广告本站有权预定给其它公司或个人，如果您已和本站口头约定要投放某个广告位，但您没有付款，那我们也有权预定给其它公司或个人！</li>
                            </ol>

                            <div class="title">
                                广告位位置、大小及价格：
                            </div>
                            <ol>
                                <li>
                                    阵营选择底部位置 400元RMB/月 750*90静态或动态图片
                                </li>
                                <li>
                                    首页导航菜单下方 600元RMB/月 750*90静态或动态图片
                                </li>
                                <li>
                                    首页捐赠支持下方 600元RMB/月 750*90静态或动态图片
                                </li>
                                <li>
                                    首页内容底部位置 300元RMB/月 750*90静态或动态图片
                                </li>
                                <li>
                                    全站侧边栏顶部位置 500元RMB/月 300*250静态或动态图片
                                </li>
                                <li>
                                    全站侧边栏中部位置 400元RMB/月 300*250静态或动态图片
                                </li>
                                <li>
                                    主题内容页底部位置 600元RMB/月 300*250静态或动态图片
                                </li>
                                <li>
                                    WA/TMW/游戏插件页底部位置 600元RMB/月 300*250静态或动态图片
                                </li>
                            </ol>
                        </div>
                        <div v-show="is_active === 3">
                            <div class="header">
                                版权声明
                            </div>
                            <ol>
                              <li>
                                  嘿市（www.iwowcn.com）网站以下简称“本网站”。本网站作为游戏周边资源分享下载网站，目的在于为魔兽世界游戏玩家提供更方便快捷的资源分享服务。未经本网站的书面许可，任何其他个人或组织均不得以任何形式将本网站的原创资源转载、复制、编辑或发布使用于其他任何场合，不得把这些信息在其他的服务器或文档中作镜像复制或保存，若有意转载本站信息资料、资源，必需取得本网站书面授权并注明出处。
                              </li>
                                <li>
                                    本网站保留网站的其它所有权利。所有与本网站链接的网站及其内容和版权由相应的提供者与拥有者负责，本网站不对其内容或形式或质素担负任何直接或间接的商业或法律责任。
                                </li>
                                <li>
                                    本网站所有资源均为学习交流之用，所有资料均为互联网上收集整理以及网友上传，软件和资料版权归原开发公司或原作者所有，请于下载后24小时内删除，禁止用于任何商业行为！若因私自散布造成法律问题，本站概不负责。本网站不负责转载部分造成的所有责任。其它用户上传软件或资料与本网站无关。
                                </li>
                                <li>
                                    若任何单位或个人认为本站所提供的软件或资料内容可能涉嫌侵犯其合法权益，应该及时向本站书面反馈，并提供身份证明、权属证明及详细侵权情况证明，我们在收到上述法律文件后，将会尽快移除被控侵权内容。
                                </li>
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
        min-height 645px
        border: 1px solid #f2f2f2
        .about_title
            background-color #f9f9f9
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
                border-bottom 1px solid #f2f2f2
            .title
                margin-top 15px
                font-size 16px
                font-weight bold
            ol
                li
                    font-size 14px
                    padding 5px
                    list-style-position:outside;

</style>
