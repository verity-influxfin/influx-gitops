import superagent from "superagent";
import dataAlesisCompanyIntroductions from "./../data/api/alesisCompanyIntroductions";
import dataAlesisIndexHumans from "./../data/api/alesisIndexHumans";
import dataAlesisIndexBanners from "./../data/api/alesisIndexBanners";
import dataAlesisIndexCounter from "./../data/api/alesisIndexCounter";
import dataAlesisCollegeHumans from "./../data/api/alesisCollegeHumans";
import dataAlesisWorkHumans from "./../data/api/alesisWorkHumans";
import dataAlesisBorrowHumans from "../data/api/alesisBorrowHumans";
import dataAlesisCollegeCounter from "./../data/api/alesisCollegeCounter";
import dataAlesisCollegeCases from "./../data/api/alesisCollegeCases";
import dataAlesisCollegeForm from "./../data/api/alesisCollegeForm";
import dataAlesisWorkForm from "./../data/api/alesisWorkForm";

var host = "/"

export const doLogin = async b => (await superagent.post(host + "doLogin").send(b)).body;
export const doRegister = async b => (await superagent.post(host + "doRegister").send(b)).body;
export const resetPassword = async b => (await superagent.post(host + "resetPassword").send(b)).body;
export const getCaptcha = async b => (await superagent.post(host + "getCaptcha").send(b)).body;
export const getCount = async b => (await superagent.post(host + "getCount").send(b)).body;
export const getListData = async b => (await superagent.post(host + "getListData").send(b)).body;
export const getMilestoneData = async b => (await superagent.post(host + "getMilestoneData").send(b)).body;
export const getIndexBanner = async b => (await superagent.post(host + "getIndexBanner").send(b)).body;
export const sendQuestion = async b => (await superagent.post(host + "sendQuestion").send(b)).body;
export const sendBankEvent = async b => (await superagent.post(host + "sendBankEvent").send(b)).body;
export const getExperiencesData = async b => (await superagent.post(host + "getExperiencesData").send(b)).body;
export const getBorrowReport = async b => (await superagent.post(host + "getBorrowReport").send(b)).body;
export const getCase = async b => (await superagent.post(host + "getCase").send(b)).body;

function faker(data) {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve(data);
    }, 500);
  });
}

// DATA IS FORMDATA as PHP Legacy
// (await superagent.post(host + "getMilestoneData").send(b)).body;


// alesisCompanyIntroductions 是首頁 - 公司簡介
// * dataAlesisCompanyIntroductions
// * /getMilestoneData
export const alesisCompanyIntroductions = async b => (await superagent.post(host + "getMilestoneData").send(b)).body;
// alesisIndexHumans 是首頁 - 用戶分享區
export const alesisIndexHumans = async b => (await faker(dataAlesisIndexHumans));
// alesisIndexBanners 是首頁 - Banner
export const alesisIndexBanners = async b => (await faker(dataAlesisIndexBanners));
// alesisIndexCounter 是首頁 - 累積註冊用戶
// * dataAlesisIndexCounter
// * /getCount
export const alesisIndexCounter = async b => (await superagent.get(host + "getCount").send(b)).body;
// alesisCollegeHumans 是學生貸 - 用戶分享區
export const alesisCollegeHumans = async b => (await faker(dataAlesisCollegeHumans));
// alesisWorkHumans 是工作貸 - 用戶分享區
export const alesisWorkHumans = async b => (await faker(dataAlesisWorkHumans));
// alesisBorrowHumans 是我要借款 - 用戶分享區
export const alesisBorrowHumans = async b => (await faker(dataAlesisBorrowHumans));
// alesisCollegeCounter 是學生貸 - 累積註冊用戶
export const alesisCollegeCounter = async b => (await faker(dataAlesisCollegeCounter));
// alesisCollegeCases 是學生貸 - 服務範圍
export const alesisCollegeCases = async b => (await faker(dataAlesisCollegeCases));
// alesisCollegeForm 是學生貸 - 表單
// export const alesisCollegeForm = async b => (await faker(dataAlesisCollegeForm));
// alesisWorkForm 是工作貸 - 表單
// export const alesisWorkForm = async b => (await faker(dataAlesisWorkForm));

// 全站搜尋？
