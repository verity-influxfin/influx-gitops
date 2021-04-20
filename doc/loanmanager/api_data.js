define(
    {
        "api": [{
            "type": "post",
            "url": "Loanmanager/api/user/login",
            "title": "使用者 登入",
            "version": "0.1.0",
            "name": "PostUserLogin",
            "group": "User",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "size": "6..50",
                        "optional": false,
                        "field": "email",
                        "description": "<p>帳號</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "size": "6..50",
                        "optional": false,
                        "field": "password",
                        "description": "<p>密碼</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": true,
                        "field": "device_id",
                        "description": "<p>裝置ID</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }, {
                        "group": "Success 200",
                        "type": "String",
                        "optional": false,
                        "field": "token",
                        "description": "<p>request_token</p>"
                    }, {
                        "group": "Success 200",
                        "type": "Number",
                        "optional": false,
                        "field": "expiry_time",
                        "description": "<p>token時效</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "101",
                        "description": "<p>帳號已黑名單</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "200",
                        "description": "<p>參數錯誤</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "302",
                        "description": "<p>使用者不存在</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "304",
                        "description": "<p>密碼錯誤</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "304 - remind_count",
                        "description": "<p>剩餘錯誤次數</p>"
                    }, {"group": "Error 4xx", "optional": false, "field": "312", "description": "<p>密碼長度錯誤</p>"}]
                },
                "examples": [{
                    "title": "101",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
                    "type": "Object"
                }, {
                    "title": "200",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
                    "type": "Object"
                }, {
                    "title": "302",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
                    "type": "Object"
                }, {"title": "304", "content": "", "type": "Object"}, {
                    "title": "312",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
                    "type": "Object"
                }]
            },
            "filename": "application/controllers/loanmanager/api/User.php",
            "groupTitle": "User",
            "sampleRequest": [{"url": "../../Loanmanager/api/user/login"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/user/info",
            "title": "使用者 個人資訊",
            "version": "0.1.0",
            "name": "GetUserInfo",
            "group": "User",
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "filename": "application/controllers/loanmanager/api/v2/User.php",
            "groupTitle": "User",
            "sampleRequest": [{"url": "../../loanmanager/api/user/info"}],
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                },
                "examples": [{
                    "title": "100",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
                    "type": "Object"
                }, {
                    "title": "101",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
                    "type": "Object"
                }]
            }
        }, {
            "type": "post",
            "url": "loanmanager/api/user/editpw",
            "title": "使用者 修改密碼",
            "version": "0.1.0",
            "name": "PostUserEditpw",
            "group": "User",
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "password",
                        "description": "<p>原密碼</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "size": "6..50",
                        "optional": false,
                        "field": "new_password",
                        "description": "<p>新密碼</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "{\n  \"result\": \"SUCCESS\"\n}", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "101",
                        "description": "<p>帳戶已黑名單</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "200",
                        "description": "<p>參數錯誤</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "304",
                        "description": "<p>密碼錯誤</p>"
                    }, {"group": "Error 4xx", "optional": false, "field": "312", "description": "<p>密碼長度錯誤</p>"}]
                },
                "examples": [{
                    "title": "100",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
                    "type": "Object"
                }, {
                    "title": "101",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
                    "type": "Object"
                }, {
                    "title": "200",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
                    "type": "Object"
                }, {
                    "title": "304",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
                    "type": "Object"
                }, {
                    "title": "312",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
                    "type": "Object"
                }]
            },
            "filename": "application/controllers/loanmanager/api/User.php",
            "groupTitle": "User",
            "sampleRequest": [{"url": "../../loanmanager/api/user/editpw"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/user/add",
            "title": "使用者 新增",
            "version": "0.1.0",
            "name": "PostUserAdd",
            "group": "User",
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "size": "6..50",
                        "optional": false,
                        "field": "email",
                        "description": "<p>信箱(帳號)</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "size": "2",
                        "optional": false,
                        "field": "role_id",
                        "description": "<p>權限</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "size": "25",
                        "optional": false,
                        "field": "name",
                        "description": "<p>姓名</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "size": "10",
                        "optional": false,
                        "field": "phone",
                        "description": "<p>連絡電話</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "filename": "application/controllers/loanmanager/api/User.php",
            "groupTitle": "User",
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "101",
                        "description": "<p>帳戶已黑名單</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "200",
                        "description": "<p>參數錯誤</p>"
                    }, {
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "304",
                        "description": "<p>密碼錯誤</p>"
                    }, {"group": "Error 4xx", "optional": false, "field": "312", "description": "<p>密碼長度錯誤</p>"}]
                },
                "examples": [{
                    "title": "100",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
                    "type": "Object"
                }, {
                    "title": "101",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
                    "type": "Object"
                }, {
                    "title": "200",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
                    "type": "Object"
                }, {
                    "title": "304",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
                    "type": "Object"
                }, {
                    "title": "312",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
                    "type": "Object"
                }]
            },
            "sampleRequest": [{"url": "../../Loanmanager/api/user/add"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/user/list",
            "title": "使用者 列表",
            "version": "0.1.0",
            "name": "PostUserList",
            "group": "User",
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                },
                "examples": [{
                    "title": "100",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
                    "type": "Object"
                }, {
                    "title": "101",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
                    "type": "Object"
                }, {
                    "title": "200",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
                    "type": "Object"
                }, {
                    "title": "304",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
                    "type": "Object"
                }, {
                    "title": "312",
                    "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
                    "type": "Object"
                }]
            },
            "filename": "application/controllers/loanmanager/api/User.php",
            "groupTitle": "User",
            "sampleRequest": [{"url": "../../loanmanager/api/user/list"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/role/list",
            "title": "權限 列表",
            "version": "0.1.0",
            "name": "GetRoleList",
            "group": "Role",
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Role.php",
            "groupTitle": "Role",
            "sampleRequest": [{"url": "../../loanmanager/api/role/list"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/target/list",
            "title": "案件 列表",
            "version": "0.1.0",
            "name": "GetTargetList",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "size": "6..50",
                        "optional": false,
                        "field": "search",
                        "description": "<p>查詢參數->使用者編號/姓名/身分證字號/案號</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": true,
                        "field": "status",
                        "description": "<p>0:催收列表 / 1:產轉協商 / 2:法催執行 </p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": true,
                        "field": "delay",
                        "description": "<p>0:未設定(所有案件) / 1:觀察期 / 2:逾期</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": true,
                        "field": "datefrom",
                        "description": "<p>YYYY-MM-DD</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": true,
                        "field": "dateto",
                        "description": "<p>YYYY-MM-DD</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": true,
                        "field": "excel",
                        "description": "<p>0 list / 1 excel download</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/list"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/target/userpassbook",
            "title": "案件 貸戶虛擬帳戶明細",
            "version": "0.1.0",
            "name": "GetTargetUserPassbook",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "account",
                        "description": "<p>貸戶virtual_account</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/userpassbook"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/target/userinfo",
            "title": "案件 貸戶資訊",
            "version": "0.1.0",
            "name": "GetTargetUserInfo",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/userinfo"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/userinfo",
            "title": "案件 新稱/更新 貸戶資訊",
            "version": "0.1.0",
            "name": "PostTargetUserInfo",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_identity",
                        "description": "<p>身分狀態 0:身分待確認 / 1:已畢業 / 2:失聯 / 3:工作中</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "user_status",
                        "description": "<p>催收狀態 0:待確認 / 1:有望催回 / 2:不穩定 / 3:無望催回</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "status",
                        "description": "<p>狀態 0:催收列表 / 1:產轉協商 / 2:法催執行</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/userinfo"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/userinfo",
            "title": "案件 新稱/更新 貸戶資訊",
            "version": "0.1.0",
            "name": "PostTargetUserInfo",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_identity",
                        "description": "<p>身分狀態 0:身分待確認 / 1:已畢業 / 2:失聯 / 3:工作中</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "user_status",
                        "description": "<p>催收狀態 0:待確認 / 1:有望催回 / 2:不穩定 / 3:無望催回</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "status",
                        "description": "<p>狀態 0:催收列表 / 1:產轉協商 / 2:法催執行</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/userinfo"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/userinfo",
            "title": "案件 新稱/更新 貸戶資訊",
            "version": "0.1.0",
            "name": "PostTargetUserInfo",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_identity",
                        "description": "<p>身分狀態 0:身分待確認 / 1:已畢業 / 2:失聯 / 3:工作中</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "user_status",
                        "description": "<p>催收狀態 0:待確認 / 1:有望催回 / 2:不穩定 / 3:無望催回</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "status",
                        "description": "<p>狀態 0:催收列表 / 1:產轉協商 / 2:法催執行</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/userinfo"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/addpushcontact",
            "title": "案件 新增貸戶通訊方式",
            "version": "0.1.0",
            "name": "PostTargetAddPushContact",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_by",
                        "description": "<p>聯絡工具 3:緊急聯絡人 / 6:電話 / 7:簡訊</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "relationship",
                        "description": "<p>關係 0:本人 / 1:親人 / 2:朋友</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "info",
                        "description": "<p>內容 ex:電話、帳號</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "remark",
                        "description": "<p>聯絡工具 0:其它 / 1:LINE / 2:Facebook / 3:緊急聯絡人 / 5:instgram / 6:電話 / 7:簡訊</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/addpushcontact"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/pushcontactlist",
            "title": "案件 貸戶通訊列表",
            "version": "0.1.0",
            "name": "PostTargetPushContactList",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_by",
                        "description": "<p>聯絡工具 0:其它 / 1:LINE / 2:Facebook / 3:緊急聯絡人 / 5:instgram / 6:電話 / 7:簡訊</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/pushcontactlist"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/pushuser",
            "title": "案件 預約/傳送 信息",
            "version": "0.1.0",
            "name": "PostTargetPushUser",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_by",
                        "description": "<p>聯絡工具 0:其它 / 1:LINE / 2:Facebook / 3:緊急聯絡人 / 4:系統訊息(App/E-mail) / 5:instgram / 6:電話 / 7:簡訊 / 8:E-mail</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "push_type",
                        "description": "<p>聯絡類型 0:提醒 / 1:協商 / 2:催收</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "message",
                        "description": "<p>內容</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "result",
                        "description": "<p>0:未更新狀態 1:已發送 2:發送失敗 3:接通 4:未接通 5:已面談</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "start_time",
                        "description": "<p>YYYY-MM-DD HH:mm:ss</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "field": "end_time",
                        "description": "<p>YYYY-MM-DD HH:mm:ss</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/pushuser"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/target/servicelog",
            "title": "案件 貸戶服務紀錄",
            "version": "0.1.0",
            "name": "GetTargetServiceLog",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "type",
                        "description": "<p>紀錄類型 0:全部 1:匯款紀錄 2:更新認證 3:系統通知 4:用戶登入 5:客服紀錄 6:面談紀錄</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/servicelog"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/pushuserupdate",
            "title": "案件 更新備註",
            "version": "0.1.0",
            "name": "PostTargetPushUserUpdate",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "push_id",
                        "description": "<p>訊息ID</p>"
                    }, {
                        "group": "Parameter", "type": "String", "field": "start_time", "description": "<p>開始時間</p>"
                    }, {
                        "group": "Parameter", "type": "String", "field": "end_time", "description": "<p>結束時間</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/pushuserupdate"}]
        }, {
            "type": "post",
            "url": "loanmanager/api/target/depositletter",
            "title": "案件 發送存證信函",
            "version": "0.1.0",
            "name": "PostTargetDepositLetter",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶ID</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "type",
                        "description": "<p>1:電子存證信函 2:紙本存證信函</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/depositletter"}]
        }, {
            "type": "get",
            "url": "loanmanager/api/target/depositletter",
            "title": "案件 預覽存證信函",
            "version": "0.1.0",
            "name": "GetTargetDepositLetter",
            "group": "Target",
            "parameter": {
                "fields": {
                    "Parameter": [{
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "user_id",
                        "description": "<p>貸戶userId</p>"
                    }, {
                        "group": "Parameter",
                        "type": "String",
                        "optional": false,
                        "field": "type",
                        "description": "<p>1:電子存證信函 2:紙本存證信函</p>"
                    }]
                }
            },
            "header": {
                "fields": {
                    "Header": [{
                        "group": "Header",
                        "type": "String",
                        "optional": false,
                        "field": "request_token",
                        "description": "<p>登入後取得的 Request Token</p>"
                    }]
                }
            },
            "success": {
                "fields": {
                    "Success 200": [{
                        "group": "Success 200",
                        "type": "Object",
                        "optional": false,
                        "field": "result",
                        "description": "<p>SUCCESS</p>"
                    }]
                }, "examples": [{"title": "SUCCESS", "content": "", "type": "Object"}]
            },
            "error": {
                "fields": {
                    "Error 4xx": [{
                        "group": "Error 4xx",
                        "optional": false,
                        "field": "100",
                        "description": "<p>Token錯誤</p>"
                    }]
                }, "examples": []
            },
            "filename": "application/controllers/loanmanager/api/Target.php",
            "groupTitle": "Target",
            "sampleRequest": [{"url": "../../loanmanager/api/target/depositletter"}]
        }]
    }
)