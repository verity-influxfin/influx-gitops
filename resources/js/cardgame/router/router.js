import question from "../pages/question";
import turntable from "../pages/turntable";

let routers = [
    { path: '*', redirect: '/question' },
    { path: '/question', component: question },
    { path: '/turntable', component: turntable },
];

export default routers;