import index from "./pages/index";
import collegeLoan from "./pages/collegeLoan";
import freshGraduateLoan from "./pages/freshGraduateLoan";
import engineerLoan from "./pages/engineerLoan";
import invest from "./pages/invest";
import transfer from "./pages/transfer";
import mobileLoan from "./pages/mobileLoan";
import qa from "./pages/qa";
import company from "./pages/company";
import news from "./pages/news";
import blog from "./pages/blog";
import vlog from "./pages/vlog";
import videoPage from './pages/videoPage';
import articlePage from './pages/articlePage';
import userTerms from './pages/userTerms';
import privacyTerms from './pages/privacyTerms';
import loanerTerms from './pages/loanerTerms';

let routers = [
    { path: '/index', component: index },
    { path: '/collegeLoan', component: collegeLoan },
    { path: '/freshGraduateLoan', component: freshGraduateLoan },
    { path: '/mobileLoan', component: mobileLoan },
    { path: '/engineerLoan', component: engineerLoan },
    { path: '/invest', component: invest },
    { path: '/transfer', component: transfer },
    { path: '/company', component: company },
    { path: '/news', component: news },
    { path: '/blog', component: blog },
    { path: '/vlog/:category', component: vlog},
    { path: '/qa', component: qa },
    { path: '/videopage/:id', component: videoPage },
    { path: '/articlepage/:id', component: articlePage },
    { path: '/userTerms', component: userTerms },
    { path: '/privacyTerms', component: privacyTerms },
    { path: '/loanerTerms', component: loanerTerms },
];

export default routers;