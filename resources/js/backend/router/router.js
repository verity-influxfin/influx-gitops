import index from "../pages/index";
import knowledge from "../pages/knowledge";
import video from "../pages/video";
import market from "../pages/market";

let routers = [
    { path: '*', redirect: '/index' },
    { path: '/index', component: index },
    { path: '/knowledge', component: knowledge },
    { path: '/video', component: video },
    { path: '/market', component: market }
];

export default routers;