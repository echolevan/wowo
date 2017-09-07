import Index from './components/index/Index.vue'
import Home from './components/home/Index.vue'
import watmw from './components/plug/waTmw.vue'
import Bm from './components/plug/Bm.vue'
import Info from './components/plug/Info.vue'
import updatePlug from './components/plug/update.vue'
import userIndex from './components/user/Index.vue'
import userInfo from './components/user/Info.vue'
import userSetting from './components/user/Setting.vue'
import userOrders from './components/user/Order.vue'
import userCollect from './components/user/Collect.vue'
import userUpload from './components/user/Upload.vue'
import userPay from './components/user/Pay.vue'
import uploadPlug from './components/plug/Upload.vue'

import AdminTagList from './components/admin/tag/List.vue'
import AdminTagCreate from './components/admin/tag/Create.vue'
import AdminIndex from './components/admin/index/Index.vue'
import AdminUserList from './components/admin/user/List.vue'
import AdminPlugList from './components/admin/plug/List.vue'
import AdminPlugUpdate from './components/admin/plug/Update.vue'
import AdminPlugCreate from './components/admin/plug/Upload.vue'
import AdminBmList from './components/admin/bm/List.vue'

import AdminToolSetting from './components/admin/tool/Setting.vue'
import AdminNoticeSetting from './components/admin/tool/Notice.vue'
import AdminLvSetting from './components/admin/tool/Lv.vue'
import AdminNicknameSetting from './components/admin/tool/Nickname.vue'
import AdminGameVersionSetting from './components/admin/tool/GameVersion.vue'

import AdminRechargeList from './components/admin/recharge/List.vue'
import AdminWithdrawList from './components/admin/withdraw/List.vue'

export default [
    { path: '/',name:'index' , component:Index},
    { path: '/home',name:'home.index' , component:Home},
    { path: '/watmw/:type/:active?/:active_pid?', name:'watmw.index' , component:watmw},
    { path: '/bm', name:'bm.index' , component:Bm},
    { path: '/info/:id', name:'plug.info' , component:Info},
    { path: '/upload/:id?', name:'upload.plug' , component:uploadPlug},
    { path: '/update/:id', name:'update.plug' , component:updatePlug},
    { path: '/userInfo', name:'user.index', redirect:"/userInfo/info" , component:userIndex , children:[
        { path: '/userInfo/info', name:'user.info' , component:userInfo},
        { path: '/userInfo/setting/:name?', name:'user.setting' , component:userSetting},
        { path: '/userInfo/orders', name:'user.orders' , component:userOrders},
        { path: '/userInfo/collect', name:'user.collect' , component:userCollect},
        { path: '/userInfo/upload', name:'user.upload' , component:userUpload},
        { path: '/userInfo/pay', name:'user.pay' , component:userPay},
    ]},

    //admin
    { path: '/admin',name:'admin.index' , component:AdminIndex},
    { path: '/admin/tag/list',name:'admin.tag.list' , component:AdminTagList},
    { path: '/admin/tag/create',name:'admin.tag.create' , component:AdminTagCreate},

    { path: '/admin/user/list',name:'admin.user.list' , component:AdminUserList},

    { path: '/admin/plug/list',name:'admin.plug.list' , component:AdminPlugList},
    { path: '/admin/plug/update/:id',name:'admin.plug.update' , component:AdminPlugUpdate},
    { path: '/admin/plug/create',name:'admin.plug.create' , component:AdminPlugCreate},


    { path: '/admin/bm/list',name:'admin.bm.list' , component:AdminBmList},

    { path: '/admin/tool/setting',name:'admin.tool.setting' , component:AdminToolSetting},
    { path: '/admin/notice/setting',name:'admin.notice.setting' , component:AdminNoticeSetting},
    { path: '/admin/lv/setting',name:'admin.lv.setting' , component:AdminLvSetting},
    { path: '/admin/nickname/setting',name:'admin.nickname.setting' , component:AdminNicknameSetting},
    { path: '/admin/game_version/setting',name:'admin.game_version.setting' , component:AdminGameVersionSetting},

    { path: '/admin/recharge/list',name:'admin.recharge.list' , component:AdminRechargeList},
    { path: '/admin/withdraw/list',name:'admin.withdraw.list' , component:AdminWithdrawList},

]