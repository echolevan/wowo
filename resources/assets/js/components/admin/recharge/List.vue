<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>消费管理</Breadcrumb-item>
            <Breadcrumb-item>充值列表</Breadcrumb-item>
        </Breadcrumb>

        <Form :model="formS" inline>
            <Form-item>
                <Input v-model.trim="formS.name" placeholder="用户名"></Input>
            </Form-item>
            <Form-item>
                <Input v-model.trim="formS.user_id" placeholder="嘿市ID"></Input>
            </Form-item>
            <Form-item>
                <Select v-model="formS.status" clearable  placeholder="状态" style="width: 100px;">
                    <Option v-for="(v , k) in configPayStatus" :value="k" :key="k">{{v}}</Option>
                </Select>
            </Form-item>
            <Button type="ghost" @click="rest">
                <span>重置</span>
            </Button>

            <Button type="primary" :loading="loading_s" @click="toS">
                <span>搜索</span>
            </Button>
        </Form>

        <table class="table table-bordered my_admin_table">
            <thead>
            <tr>
                <th style="width: 10%">嘿市ID</th>
                <th style="width: 10%">用户名</th>
                <th style="width: 10%">充值类型</th>
                <th style="width: 10%">充值金额</th>
                <th style="width: 10%">赠送的金币</th>
                <th style="width: 10%">总共获得的金币</th>
                <th style="width: 10%">状态</th>
                <th style="width: 10%">充值时间</th>
            </tr>
            </thead>
            <tbody v-if="list.length > 0">
            <tr v-for="v in list">
                <td>{{v.user.id}}</td>
                <td>{{v.user.name}}</td>
                <td>{{configPayType[v.recharge_type]}}</td>
                <td>{{v.recharge_amount}}</td>
                <td>{{v.giving_gold}}</td>
                <td>{{v.recharge_gold + v.giving_gold}}</td>
                <td>{{configPayStatus[v.status]}}</td>
                <td>{{v.created_at}}</td>
            </tr>
            </tbody>
            <tbody  v-else>
            <tr>
                <td style="text-align: center;font-size: 16px" colspan="8">
                    暂无数据
                </td>
            </tr>
            </tbody>
        </table>

        <div class="page pull-right">
            <Page :total="total" size="small"  show-elevator show-sizer show-total @on-change="page_c" @on-page-size-change="size_c"  :key="total"></Page>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                page: 1,
                page_size: 10,
                total: 0,
                formS: {
                    name: '',
                    user_id: '',
                    status: ''
                },
                list: [],
                configPayType: configPayType,
                configPayStatus: configPayStatus,
                loading_s: false
            }
        },
        mounted() {
            let cz_jl_id = localStorage.getItem('cz_jl_id')
            if(cz_jl_id){
                this.formS.user_id = cz_jl_id
            }
            localStorage.removeItem('cz_jl_id')
            this.search()
        },
        methods: {
            toS() {
                this.page = 1
                this.loading_s = true
                this.search()
            },
            rest() {
                this.formS.name = ''
                this.formS.user_id = ''
                this.formS.status = ''
            },
            page_c(p){
                this.page = p
                this.search()
                this.$Loading.start()
            },
            size_c(s){
                this.page_size = s;
                this.search()
                this.$Loading.start();
            },
            search(){
                axios.post(`/admin/recharge/list/${this.page}/${this.page_size}`,{search:this.formS}).then(res => {
                    if(res.data.sta === 1){
                        this.total = res.data.count
                        this.list = res.data.list
                    }
                    this.$Loading.finish();
                    this.loading_s = false
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
