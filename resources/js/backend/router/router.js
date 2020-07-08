import index from "../pages/index";
import knowledge from "../pages/knowledge";
import video from "../pages/video";

let routers = [
    { path: '*', redirect: '/index' },
    { path: '/index', component: index },
    { path: '/knowledge', component: knowledge },
    { path: '/video', component: video },
];

export default routers;