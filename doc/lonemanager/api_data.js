define({
  "api": [
    {
      "type": "post",
      "url": "lonemanager/api/user/login",
      "title": "使用者 登入",
      "version": "0.1.0",
      "name": "PostUserLogin",
      "group": "User",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "String",
              "size": "6..50",
              "optional": false,
              "field": "email",
              "description": "<p>帳號</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "6..50",
              "optional": false,
              "field": "password",
              "description": "<p>密碼</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "optional": true,
              "field": "device_id",
              "description": "<p>裝置ID</p>"
            }
          ]
        }
      },
      "success": {
        "fields": {
          "Success 200": [
            {
              "group": "Success 200",
              "type": "Object",
              "optional": false,
              "field": "result",
              "description": "<p>SUCCESS</p>"
            },
            {
              "group": "Success 200",
              "type": "String",
              "optional": false,
              "field": "token",
              "description": "<p>request_token</p>"
            },
            {
              "group": "Success 200",
              "type": "Number",
              "optional": false,
              "field": "expiry_time",
              "description": "<p>token時效</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "",
            "type": "Object"
          }
        ]
      },
      "error": {
        "fields": {
          "Error 4xx": [
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "101",
              "description": "<p>帳號已黑名單</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "200",
              "description": "<p>參數錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "302",
              "description": "<p>使用者不存在</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "304",
              "description": "<p>密碼錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "304 - remind_count",
              "description": "<p>剩餘錯誤次數</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "312",
              "description": "<p>密碼長度錯誤</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "101",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
            "type": "Object"
          },
          {
            "title": "200",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
            "type": "Object"
          },
          {
            "title": "302",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
            "type": "Object"
          },
          {
            "title": "304",
            "content": "",
            "type": "Object"
          },
          {
            "title": "312",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
            "type": "Object"
          }
        ]
      },
      "filename": "application/controllers/lonemanager/api/User.php",
      "groupTitle": "User",
      "sampleRequest": [
        {
          "url": "../../lonemanager/api/user/login"
        }
      ]
    },
    {
      "type": "post",
      "url": "lonemanager/api/user/editpw",
      "title": "使用者 修改密碼",
      "version": "0.1.0",
      "name": "PostUserEditpw",
      "group": "User",
      "header": {
        "fields": {
          "Header": [
            {
              "group": "Header",
              "type": "String",
              "optional": false,
              "field": "request_token",
              "description": "<p>登入後取得的 Request Token</p>"
            }
          ]
        }
      },
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "String",
              "optional": false,
              "field": "password",
              "description": "<p>原密碼</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "6..50",
              "optional": false,
              "field": "new_password",
              "description": "<p>新密碼</p>"
            }
          ]
        }
      },
      "success": {
        "fields": {
          "Success 200": [
            {
              "group": "Success 200",
              "type": "Object",
              "optional": false,
              "field": "result",
              "description": "<p>SUCCESS</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n  \"result\": \"SUCCESS\"\n}",
            "type": "Object"
          }
        ]
      },
      "error": {
        "fields": {
          "Error 4xx": [
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "100",
              "description": "<p>Token錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "101",
              "description": "<p>帳戶已黑名單</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "200",
              "description": "<p>參數錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "304",
              "description": "<p>密碼錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "312",
              "description": "<p>密碼長度錯誤</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "100",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
            "type": "Object"
          },
          {
            "title": "101",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
            "type": "Object"
          },
          {
            "title": "200",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
            "type": "Object"
          },
          {
            "title": "304",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
            "type": "Object"
          },
          {
            "title": "312",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
            "type": "Object"
          }
        ]
      },
      "filename": "application/controllers/lonemanager/api/User.php",
      "groupTitle": "User",
      "sampleRequest": [
        {
          "url": "lonemanager/api/user/editpw"
        }
      ]
    },
    {
      "type": "post",
      "url": "lonemanager/api/user/editpw",
      "title": "經銷商 修改密碼",
      "version": "0.1.0",
      "name": "PostUserEditpw",
      "group": "User",
      "header": {
        "fields": {
          "Header": [
            {
              "group": "Header",
              "type": "String",
              "optional": false,
              "field": "request_token",
              "description": "<p>登入後取得的 Request Token</p>"
            }
          ]
        }
      },
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "String",
              "optional": false,
              "field": "password",
              "description": "<p>原密碼</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "6..50",
              "optional": false,
              "field": "new_password",
              "description": "<p>新密碼</p>"
            }
          ]
        }
      },
      "success": {
        "fields": {
          "Success 200": [
            {
              "group": "Success 200",
              "type": "Object",
              "optional": false,
              "field": "result",
              "description": "<p>SUCCESS</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n  \"result\": \"SUCCESS\"\n}",
            "type": "Object"
          }
        ]
      },
      "error": {
        "fields": {
          "Error 4xx": [
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "100",
              "description": "<p>Token錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "101",
              "description": "<p>帳戶已黑名單</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "200",
              "description": "<p>參數錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "304",
              "description": "<p>密碼錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "312",
              "description": "<p>密碼長度錯誤</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "100",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
            "type": "Object"
          },
          {
            "title": "101",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
            "type": "Object"
          },
          {
            "title": "200",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
            "type": "Object"
          },
          {
            "title": "304",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
            "type": "Object"
          },
          {
            "title": "312",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
            "type": "Object"
          }
        ]
      },
      "filename": "application/controllers/lonemanager/api/User.php",
      "groupTitle": "User",
      "sampleRequest": [
        {
          "url": "lonemanager/api/user/editpw"
        }
      ]
    },
    {
      "type": "get",
      "url": "lonemanager/api/user/info",
      "title": "使用者 個人資訊",
      "version": "0.1.0",
      "name": "GetUserInfo",
      "group": "User",
      "header": {
        "fields": {
          "Header": [
            {
              "group": "Header",
              "type": "String",
              "optional": false,
              "field": "request_token",
              "description": "<p>登入後取得的 Request Token</p>"
            }
          ]
        }
      },
      "success": {
        "fields": {
          "Success 200": [
            {
              "group": "Success 200",
              "type": "Object",
              "optional": false,
              "field": "result",
              "description": "<p>SUCCESS</p>"
            },
            {
              "group": "Success 200",
              "type": "Number",
              "optional": false,
              "field": "id",
              "description": "<p>公司ID</p>"
            },
            {
              "group": "Success 200",
              "type": "String",
              "optional": false,
              "field": "name",
              "description": "<p>公司名稱</p>"
            },
            {
              "group": "Success 200",
              "type": "Number",
              "optional": false,
              "field": "tax_id",
              "description": "<p>統一編號</p>"
            },
            {
              "group": "Success 200",
              "type": "String",
              "optional": false,
              "field": "contact",
              "description": "<p>聯絡人</p>"
            },
            {
              "group": "Success 200",
              "type": "String",
              "optional": false,
              "field": "phone",
              "description": "<p>聯絡電話</p>"
            },
            {
              "group": "Success 200",
              "type": "String",
              "optional": false,
              "field": "address",
              "description": "<p>店家地址</p>"
            },
            {
              "group": "Success 200",
              "type": "Number",
              "optional": false,
              "field": "expiry_time",
              "description": "<p>token時效</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "",
            "type": "Object"
          }
        ]
      },
      "filename": "application/controllers/lonemanager/api/v2/User.php",
      "groupTitle": "User",
      "sampleRequest": [
        {
          "url": "lonemanager/api/user/info"
        }
      ],
      "error": {
        "fields": {
          "Error 4xx": [
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "100",
              "description": "<p>Token錯誤</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "101",
              "description": "<p>帳戶已黑名單</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "100",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
            "type": "Object"
          },
          {
            "title": "101",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
            "type": "Object"
          }
        ]
      }
    }
  ]
})