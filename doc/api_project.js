define({
  "name": "P2p lending API Doccument",
  "version": "0.0.0",
  "description": "P2p lending 前台 API，先做登入，若會員不存在則進行註冊流程<br>其他 API 皆需於 header 帶入 request_token",
  "title": "P2p lending API Doccument",
  "url": "http://p2p-api.clockin.com.tw/api",
  "order": [
    "User",
    "PostUserRegisterphone",
    "PostUserRegister",
    "PostUserLogin",
    "GetUserInfo",
    "PostUserBind",
    "PostUserSociallogin",
    "PostUserBankaccount",
    "GetUserBankaccount",
    "Certification",
    "GetCertificationList",
    "PostCertificationHealthcard",
    "GetCertificationHealthcard",
    "Agreement",
    "GetAgreementList",
    "GetAgreementInfoAlias"
  ],
  "sampleUrl": false,
  "defaultVersion": "0.0.0",
  "apidoc": "0.3.0",
  "generator": {
    "name": "apidoc",
    "time": "2018-02-22T12:20:17.312Z",
    "url": "http://apidocjs.com",
    "version": "0.17.6"
  }
});
