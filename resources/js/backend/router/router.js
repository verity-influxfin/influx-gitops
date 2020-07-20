import index from "../pages/index";
import knowledge from "../pages/knowledge";
import video from "../pages/video";
import market from "../pages/market";
import milestone from "../pages/milestone";
import partner from "../pages/partner";
import media from "../pages/media";
import feedback from "../pages/feedback";

let routers = [
    { path: '*', redirect: '/index' },
    { path: '/index', component: index },
    { path: '/knowledge', component: knowledge },
    { path: '/video', component: video },
    { path: '/market', component: market },
    { path: '/milestone', component: milestone },
    { path: '/media', component: media },
    { path: '/partner', component: partner },
    { path: '/feedback', component: feedback }
];

export default routers;