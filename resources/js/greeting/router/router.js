import make from "../pages/make";
import show from "../pages/show";

let routers = [
    { path: '*', redirect: '/make' },
    { path: '/make', component: make },
    { path: '/show', component: show },
];

export default routers;