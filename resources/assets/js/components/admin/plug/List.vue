<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>资源管理</Breadcrumb-item>
            <Breadcrumb-item>资源列表</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formS" inline>
            <Form-item  v-show="formS.plug_id">
                <Input v-model.trim="formS.plug_id" placeholder="插件唯一ID"></Input>
            </Form-item>
            <Form-item>
                <Input v-model.trim="formS.name" placeholder="插件名称"></Input>
            </Form-item>
            <Form-item>
                <Input v-model.trim="formS.user_name" placeholder="用户名"></Input>
            </Form-item>
            <Form-item>
                <Input v-model.trim="formS.user_id" placeholder="嘿市ID"></Input>
            </Form-item>
            <Form-item>
                <Select v-model="formS.gold" clearable  placeholder="是否免费"  style="width: 100px;">
                    <Option value="1">免费</Option>
                    <Option value="2">收费</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.tagType" clearable placeholder="资源类型"  style="width: 100px;">
                    <Option v-for="(v , k) in configPlugType" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.status" clearable placeholder="状态"  style="width: 80px;">
                    <Option v-for="(v , k) in configStatusType" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Form-item>
                <Select v-model="formS.is_check" clearable placeholder="审核状态"  style="width: 100px;">
                    <Option v-for="(v , k) in configCheckType" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>

            <Form-item>
                <Select v-model="formS.orderBySome" clearable placeholder="排序条件"  style="width: 100px;">
                    <Option value="gold">售价</Option>
                    <Option value="rank">推荐</Option>
                    <Option value="download_num">下载</Option>
                    <Option value="collect_num">收藏</Option>
                    <Option value="created_at">上传时间</Option>
                </Select>
            </Form-item>

            <Form-item>
                <Select v-model="formS.orderByF" clearable  placeholder="排序规则"  style="width: 80px;">
                    <Option value="asc">升序</Option>
                    <Option value="desc">降序</Option>
                </Select>
            </Form-item>

            <Form-item>
                <Select v-model="formS.is_new" clearable  placeholder="含有历史"  style="width: 100px;">
                    <Option value="0">含有历史</Option>
                    <Option value="1">不包含历史</Option>
                </Select>
            </Form-item>

            <Button type="ghost" @click="rest">
                <span>重置</span>
            </Button>

            <Button type="primary" :loading="loading_s" @click="toS">
                <span>搜索</span>
            </Button>

            <router-link to="/admin/plug/create">
                <Button type="primary" class="pull-right">添加插件</Button>
            </router-link>
        </Form>

        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th>插件名称</th>
                <th>作者</th>
                <th>售价</th>
                <th>分类</th>
                <th>资源内容</th>
                <th>是否为最新</th>
                <th>下载次数</th>
                <th>推荐次数</th>
                <th>收藏次数</th>
                <th>状态</th>
                <th>审核</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody v-if="list.length > 0">
            <tr v-for="(v, k) in list">
                <td class="hover_hand">
                    <Tooltip placement="bottom-start">
                        <a class="toolTip" :href="`/#/info/${v.id}`" target="_blank">
                            <span v-html="v.title"></span>
                        </a>
                        <div slot="content">
                            <p v-html="v.title"></p>
                        </div>
                    </Tooltip>
                </td>
                <td>{{v.user.name}}</td>
                <td>{{v.gold === 0 ? '免费' : v.gold}}</td>
                <td class="hover_hand">
                    <Tooltip placement="bottom-start">
                        <span class="toolTip" v-html="configPlugType[v.type] + (v.tag_one ? '/'+v.tag_one.name : '' ) + (v.tag_two ? '/'+v.tag_two.name : '')"></span>
                        <div slot="content">
                            <p v-html="configPlugType[v.type] + (v.tag_one ? '/'+v.tag_one.name : '' ) + (v.tag_two ? '/'+v.tag_two.name : '')"></p>
                        </div>
                    </Tooltip>
                </td>
                <td>
                    <Button type="text" size="small" v-if="v.type < 3" class="clipboard" :data-clipboard-text="v.content" @click="clipboard">复制</Button>
                    <Button type="text" size="small" v-else>
                        <a :href="v.content" target="_blank">下载</a>
                    </Button>
                </td>
                <td>
                    <Tag type="border" :color="v.is_new === 1 ? 'blue' : 'red'">{{configYesOrNo[v.is_new]}}</Tag>
                </td>
                <td>{{v.download_num}}</td>
                <td>{{v.like_num}}</td>
                <td>{{v.collect_num}}</td>
                <td style="width: 8%">
                    <Tag type="dot" :color="v.status === 1 ? 'blue' : 'red'" @click.native="change_status(v.status === 1 ? 0 : 1 , v.id, k)">{{configStatusType[v.status]}}</Tag>
                </td>
                <td style="width: 8%">
                    <Tag type="dot" :color="v.is_check === 1 ? 'blue' : 'red'" @click.native="change_is_check(v.is_check === 1 ? 0 : 1 , v.id, k)">{{configCheckType[v.is_check]}}</Tag>
                </td>
                <td>
                    <Input-number style="width: 50px" :max="99" :min="0" v-model="v.rank" @on-change="change_other(k)" :disabled="is_disabled === k ? false : true"></Input-number>
                </td>
                <td>
                    <Button type="ghost" size="small">
                        <router-link :to="{name: 'admin.plug.update' , params:{id: v.id}}">编辑</router-link>
                    </Button>
                    <Button type="ghost" size="small" @click="search_his(v.plug_id)">历史版本</Button>
                    <Button :type="is_disabled === k ? 'success' : 'ghost'" size="small" @click="c_rank(v.id, k)">{{ is_disabled === k ? '确定':'推荐' }}</Button>
                </td>
            </tr>
            </tbody>
            <tbody  v-else>
            <tr>
                <td style="text-align: center;font-size: 16px" colspan="15">
                    暂无数据
                </td>
            </tr>
            </tbody>

        </table>

        <div class="page pull-right">
            <Page :total="total" size="small" show-elevator show-sizer show-total @on-change="page_c"
                  @on-page-size-change="size_c" :key="total"></Page>
        </div>
    </div>
</template>

<script>
    import Clipboard from 'clipboard';
    export default {
        data() {
            return {
                page: 1,
                page_size: 10,
                total: 0,
                list: [],
                formS:{
                    name: '',
                    plug_id: '',
                    user_name: '',
                    user_id: '',
                    gold: '',
                    tagType: '',
                    status: '',
                    is_check: '',
                    orderBySome: 'created_at',
                    orderByF: 'desc',
                    is_new: '0',
                },
                loading_s: false,
                configPlugType: configPlugType,
                configYesOrNo: configYesOrNo,
                configStatusType: configStatusType,
                configCheckType: configCheckType,
                configTagType: configTagType,
                is_disabled: ''
            }
        },
        mounted() {
            let zy_jl_id = localStorage.getItem('zy_jl_id')
            if(zy_jl_id){
                this.formS.user_id = zy_jl_id
            }
            localStorage.removeItem('zy_jl_id')
            this.search()
        },
        methods: {
            toS() {
                this.page = 1
                this.loading_s = true
                this.search()
            },
            search_his(id) {
                this.page = 1
                this.formS.plug_id = id
                this.formS.is_new = '0'
                this.search()
            },
            rest() {
                this.formS.name = ''
                this.formS.user_name = ''
                this.formS.plug_id = ''
                this.formS.user_id = ''
                this.formS.gold = ''
                this.formS.status = ''
                this.formS.is_check = ''
                this.formS.tagType = ''
                this.formS.orderBySome = 'created_at    '
                this.formS.orderByF = 'desc'
                this.formS.is_new = '0'
            },
            c_rank(id, k){
                if(this.is_disabled === k){
                    // 确定
                    if(this.list[k].rank === 0){
                        this.$Message.error('请输入数字(1~99)')
                        return false
                    }
                    axios.get(`/admin/plug/change_rank/${id}/${this.list[k].rank}`).then(res => {
                        if(res.data.sta === 1){
                            this.$Message.success(res.data.msg)
                            this.is_disabled = ''
                        }else{
                            this.$Message.error(res.data.msg)
                        }
                    })
                } else {
                    this.is_disabled = k
                }
            },
            clipboard(){
                let clipboard = new Clipboard('.clipboard');
                clipboard.on('success', function(e) {
                });
                this.$Message.success('复制成功');
            },
            change_status(v, id, k) {
                axios.get(`/admin/plug/change_status/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].status = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            change_is_check(v, id, k) {
                axios.get(`/admin/plug/change_is_check/${id}/${v}`).then(res => {
                    if(res.data.sta === 1){
                        this.list[k].is_check = v
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            search() {
                axios.post(`/admin/plug/list/${this.page}/${this.page_size}`, {search: this.formS}).then(res => {
                    if (res.data.sta === 1) {
                        this.total = res.data.count
                        this.list = res.data.list
                    }
                    this.$Loading.finish();
                    this.loading_s = false
                })
            },
            page_c(p) {
                this.page = p
                this.search()
                this.$Loading.start()
            },
            size_c(s) {
                this.page_size = s;
                this.search()
                this.$Loading.start();
            },
            change_other(k) {
                if (!(/^\d+$/.test(this.list[k].rank))) {
                    this.list[k].rank = Math.round(this.list[k].rank)
                }
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
