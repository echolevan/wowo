const Index = resolve => require(['./components/index/Index.vue'], resolve)
const Home = resolve => require(['./components/home/Index.vue'], resolve)
const WaTmw = resolve => require(['./components/plug/WaTmw.vue'], resolve)
const Info = resolve => require(['./components/plug/Info.vue'], resolve)
const updatePlug = resolve => require(['./components/plug/update.vue'], resolve)
const userIndex = resolve => require(['./components/user/Index.vue'], resolve)
const userInfo = resolve => require(['./components/user/Info.vue'], resolve)
const userSetting = resolve => require(['./components/user/Setting.vue'], resolve)
const userOrders = resolve => require(['./components/user/Order.vue'], resolve)
const userCollect = resolve => require(['./components/user/Collect.vue'], resolve)
const userUpload = resolve => require(['./components/user/Upload.vue'], resolve)
const userPay = resolve => require(['./components/user/Pay.vue'], resolve)
const uploadPlug = resolve => require(['./components/plug/Upload.vue'], resolve)


const AdminTagList = resolve => require(['./components/admin/tag/List.vue'], resolve)
const AdminTagCreate = resolve => require(['./components/admin/tag/Create.vue'], resolve)
const AdminIndex = resolve => require(['./components/admin/index/Index.vue'], resolve)

export default [
    { path: '/',name:'index' , component:Index},
    { path: '/home',name:'home.index' , component:Home},
    { path: '/waTmw/:type', name:'waTmw.index' , component:WaTmw},
    { path: '/info/:id', name:'plug.info' , component:Info},
    { path: '/upload/:id?', name:'upload.plug' , component:uploadPlug},
    { path: '/update/:id', name:'update.plug' , component:updatePlug},
    { path: '/userInfo', name:'user.index', redirect:"/userInfo/info" , component:userIndex , children:[
        { path: '/userInfo/info', name:'user.info' , component:userInfo},
        { path: '/userInfo/setting', name:'user.setting' , component:userSetting},
        { path: '/userInfo/orders', name:'user.orders' , component:userOrders},
        { path: '/userInfo/collect', name:'user.collect' , component:userCollect},
        { path: '/userInfo/upload', name:'user.upload' , component:userUpload},
        { path: '/userInfo/pay', name:'user.pay' , component:userPay},
    ]},

    //admin
    { path: '/admin',name:'admin.index' , component:AdminIndex},
    { path: '/admin/tag/list',name:'admin.tag.list' , component:AdminTagList},
    { path: '/admin/tag/create',name:'admin.tag.create' , component:AdminTagCreate},

]