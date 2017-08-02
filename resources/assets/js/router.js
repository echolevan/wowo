const Index = resolve => require(['./components/index/Index.vue'], resolve)
const Home = resolve => require(['./components/home/Index.vue'], resolve)
const WaTmw = resolve => require(['./components/plug/WaTmw.vue'], resolve)
const Info = resolve => require(['./components/plug/Info.vue'], resolve)
const userInfo = resolve => require(['./components/user/Info.vue'], resolve)
const uploadPlug = resolve => require(['./components/plug/Upload.vue'], resolve)

export default [
    { path: '/',name:'index' , component:Index},
    { path: '/home',name:'home.index' , component:Home},
    { path: '/waTmw/:type', name:'waTmw.index' , component:WaTmw},
    { path: '/info/:id', name:'plug.info' , component:Info},
    { path: '/userInfo', name:'user.info' , component:userInfo},
    { path: '/upload', name:'upload.plug' , component:uploadPlug}
]