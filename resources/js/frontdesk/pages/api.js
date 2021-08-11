import superagent from "superagent";

var host = "https://dev-deus-news.influxfin.com/api/v2"

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
export const getExperiencesData = async b => (await superagent.post(host + "getExperiencesData").send(b)).body;
export const getCase = async b => (await superagent.post(host + "getCase").send(b)).body;