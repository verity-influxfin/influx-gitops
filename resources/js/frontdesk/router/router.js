import freshGraduateLoan from "../pages/freshGraduateLoan";
import engineerLoan from "../pages/engineerLoan";
import transfer from "../pages/transfer";
import mobileLoan from "../pages/mobileLoan";
import qa from "../pages/qa";
import company from "../pages/company";
import news from "../pages/news";
import blog from "../pages/blog";
import vlog from "../pages/vlog";
import videoPage from '../pages/videoPage';
import articlePage from '../pages/articlePage';
import userTerms from '../pages/userTerms';
import privacyTerms from '../pages/privacyTerms';
import loanerTerms from '../pages/loanerTerms';
import recruiting from '../pages/recruiting';
import campusPartner from '../pages/campusPartner';
import clubCooperation from '../pages/clubCooperation';
import firmCooperation from '../pages/firmCooperation';
import companyCooperation from '../pages/companyCooperation';
import register from '../pages/register';
import myInvestment from '../pages/myInvestment';
import debt from '../pages/debt';
import closedcase from '../pages/closedcase';
import detail from '../pages/detail';
import myLoan from '../pages/myLoan';
import notification from '../pages/notification';
import myrepayment from '../pages/myrepayment';
import feedback from '../pages/feedback';
import event from '../pages/event';
import scsbank from '../pages/scsbank';
import skbank from '../pages/skbank';
import obank from '../pages/obank';

import index from "../pages/alesis-index";
import borrow from '../pages/alesis-borrow';
import invest from '../pages/alesis-invest';
import faq from '../pages/alesis-faq';
import risk from '../pages/alesis-risk';
import projects from '../pages/alesis-projects';
import workLoan from '../pages/alesis-workLoan';
import collegeLoan from "../pages/alesis-collegeLoan";
import investReport from "../pages/investReport"

let routers = [
    { path: '*', redirect: '/index' },
    { path: '/index', component: index },
    { path: '/borrow', component: borrow },

    { path: '/workLoan', component: workLoan },
    { path: '/collegeLoan', component: collegeLoan },
    { path: '/faq', component: faq },
    { path: '/risk', component: risk },
    { path: '/projects', component: projects },


    { path: '/freshGraduateLoan', component: freshGraduateLoan },
    { path: '/mobileLoan', component: mobileLoan },
    { path: '/engineerLoan', component: engineerLoan },
    { path: '/investment', component: invest },
    { path: '/transfer', component: transfer },
    { path: '/company', component: company },
    { path: '/news', component: news },
    { path: '/blog', component: blog },
    { path: '/vlog', component: vlog },
    { path: '/invest', component: invest },
    { path: '/qa', component: qa },
    { path: '/videopage', component: videoPage },
    { path: '/articlepage', component: articlePage },
    { path: '/userTerms', component: userTerms },
    { path: '/privacyTerms', component: privacyTerms },
    { path: '/loanerTerms', component: loanerTerms },
    { path: '/register', component: register },
    {
        path: '/myloan', component: myLoan, children: [
            { path: '/loannotification', component: notification, name: 'loan-notification' },
            { path: '/myrepayment', component: myrepayment }
        ]
    },
    {
        path: '/myinvestment', component: myInvestment, children: [
            { path: '/investnotification', component: notification, name: 'invest-notification' },
            { path: '/debt', component: debt },
            { path: '/closedcase', component: closedcase },
            { path: '/detail', component: detail },
            { path: '/invest-report', component: investReport }
        ]
    },
    { path: '/recruiting', component: recruiting },
    { path: '/campuspartner', component: campusPartner },
    { path: '/clubcooperation', component: clubCooperation },
    { path: '/firmcooperation', component: firmCooperation },
    { path: '/companycooperation', component: companyCooperation },
    { path: '/feedback', component: feedback },
    { path: '/event', component: event },
	{ path: '/scsbank', component: scsbank },
    { path: '/skbank', component: skbank },
    { path: '/obank', component: obank },
];

export default routers;
