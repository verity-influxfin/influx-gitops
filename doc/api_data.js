define({ "api": [
  {
    "type": "get",
    "url": "/agreement/info/{alias}",
    "title": "協議 協議書",
    "group": "Agreement",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "alias",
            "description": "<p>代號</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Agreement ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "alias",
            "description": "<p>代號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"name\":\"用戶協議\",\n\t\t\t\"content\":\"用戶協議\",\n\t\t\t\"alias\":\"user\",\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "701",
            "description": "<p>此協議書不存在</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "701",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"701\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Agreement.php",
    "groupTitle": "Agreement",
    "name": "GetAgreementInfoAlias"
  },
  {
    "type": "get",
    "url": "/agreement/list",
    "title": "協議 協議列表",
    "group": "Agreement",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Agreement ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "alias",
            "description": "<p>代號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"用戶協議\",\n\t\t\t\t\"content\":\"用戶協議\",\n\t\t\t\t\"alias\":\"user\",\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"投資人協議\",\n\t\t\t\t\"content\":\"投資人協議\",\n\t\t\t\t\"alias\":\"investor\",\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Agreement.php",
    "groupTitle": "Agreement",
    "name": "GetAgreementList"
  },
  {
    "type": "get",
    "url": "/certification/debitcard",
    "title": "認證 金融帳號認證資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_name",
            "description": "<p>User 姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "certification_id",
            "description": "<p>Certification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_code",
            "description": "<p>銀行代碼三碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "branch_code",
            "description": "<p>分支機構代號四碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account",
            "description": "<p>銀行帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最近更新日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"4\",\n  \t\"bank_code\": \"822\",\n  \t\"branch_code\": \"1234\",\n  \t\"bank_account\": \"149612222032\", \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationDebitcard"
  },
  {
    "type": "get",
    "url": "/certification/email",
    "title": "認證 常用電子信箱資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"8\",\n  \t\"certification_id\": \"6\",\n  \t\"email\": \"XXX\",\n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationEmail"
  },
  {
    "type": "get",
    "url": "/certification/emergency",
    "title": "認證 緊急聯絡人資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>緊急聯絡人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>緊急聯絡人電話</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "relationship",
            "description": "<p>緊急聯絡人關係</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"8\",\n  \t\"certification_id\": \"6\",\n  \t\"name\": \"XXX\",\n  \t\"phone\": \"0912345678\",\n  \t\"relationship\": \"配偶\", \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationEmergency"
  },
  {
    "type": "get",
    "url": "/certification/financial",
    "title": "認證 財務訊息認證資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "certification_id",
            "description": "<p>Certification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "parttime",
            "description": "<p>打工收入</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "allowance",
            "description": "<p>零用錢收入</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "scholarship",
            "description": "<p>獎學金收入</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "other_income",
            "description": "<p>其他收入</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "restaurant",
            "description": "<p>餐飲支出</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transportation",
            "description": "<p>交通支出</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "entertainment",
            "description": "<p>娛樂支出</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "other_expense",
            "description": "<p>其他支出</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最近更新日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"8\",\n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\",\n  \t\"parttime\": 100,\n  \t\"allowance\": 200,\n  \t\"scholarship\": 300,\n  \t\"other_income\": 400,\n  \t\"restaurant\": 0,\n  \t\"transportation\": 1,\n  \t\"entertainment\": 2,\n  \t\"other_expense\": 3     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationFinancial"
  },
  {
    "type": "get",
    "url": "/certification/idcard",
    "title": "認證 實名認證資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "certification_id",
            "description": "<p>Certification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_card_date",
            "description": "<p>發證日期(民國) ex:1060707</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_card_place",
            "description": "<p>發證地點</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "birthday",
            "description": "<p>生日(民國) ex:1020101</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最近更新日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"3\", \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\",     \n  \t\"name\": \"toy\",\n  \t\"id_number\": \"G121111111\",\n  \t\"id_card_date\": \"1060707\",\n  \t\"id_card_place\": \"北市\",\n  \t\"birthday\": \"1020101\",\n  \t\"address\": \"全家就是我家\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationIdcard"
  },
  {
    "type": "get",
    "url": "/certification/list",
    "title": "認證 認證列表",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Certification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>簡介</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "alias",
            "description": "<p>代號</p>"
          },
          {
            "group": "Success 200",
            "type": "number",
            "optional": false,
            "field": "user_status",
            "description": "<p>用戶認證狀態：null:尚未認證 0:認證中 1:已完成 2:認證失敗</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"實名認證\",\n\t\t\t\t\"description\":\"實名認證\",\n\t\t\t\t\"alias\":\"id_card\",\n\t\t\t\t\"user_status\":1,\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"金融帳號認證\",\n\t\t\t\t\"description\":\"金融帳號認證\",\n\t\t\t\t\"alias\":\"debit_card\",\n\t\t\t\t\"user_status\":1,\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationList",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/certification/social",
    "title": "認證 社交認證資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "certification_id",
            "description": "<p>Certification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>認證類型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最近更新日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"8\",\n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\",\n  \t\"parttime\": 100,\n  \t\"allowance\": 200,\n  \t\"scholarship\": 300,\n  \t\"other_income\": 400,\n  \t\"restaurant\": 0,\n  \t\"transportation\": 1,\n  \t\"entertainment\": 2,\n  \t\"other_expense\": 3     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationSocial"
  },
  {
    "type": "get",
    "url": "/certification/student",
    "title": "認證 學生身份認證資料",
    "group": "Certification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "certification_id",
            "description": "<p>Certification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "school",
            "description": "<p>學校名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "system",
            "description": "<p>學制 0:大學 1:碩士 2:博士</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "major",
            "description": "<p>學門</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "department",
            "description": "<p>系所</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "grade",
            "description": "<p>年級</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "student_id",
            "description": "<p>學號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>校內Email</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "front_image",
            "description": "<p>學生證正面照</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "back_image",
            "description": "<p>學生證背面照</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最近更新日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"3\",\n  \t\"school\": \"國立宜蘭大學\",\n  \t\"department\": \"電機工程學系\",\n  \t\"grade\": \"1\",\n  \t\"student_id\": \"1496B032\", \n  \t\"email\": \"xxxxx@xxx.edu.com.tw\",     \n  \t\"system\": \"0\",     \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "503",
            "description": "<p>尚未驗證過</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationStudent"
  },
  {
    "type": "post",
    "url": "/certification/debitcard",
    "title": "認證 金融帳號認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "size": "3",
            "optional": false,
            "field": "bank_code",
            "description": "<p>銀行代碼三碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "4",
            "optional": false,
            "field": "branch_code",
            "description": "<p>分支機構代號四碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "10..14",
            "optional": false,
            "field": "bank_account",
            "description": "<p>銀行帳號</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "front_image",
            "description": "<p>金融卡正面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "back_image",
            "description": "<p>金融卡背面照</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "506",
            "description": "<p>銀行代碼長度錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "507",
            "description": "<p>分支機構代號長度錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "508",
            "description": "<p>銀行帳號長度錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "509",
            "description": "<p>銀行帳號已存在</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "506",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"506\"\n}",
          "type": "json"
        },
        {
          "title": "507",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"507\"\n}",
          "type": "json"
        },
        {
          "title": "508",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"508\"\n}",
          "type": "json"
        },
        {
          "title": "509",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"509\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationDebitcard"
  },
  {
    "type": "post",
    "url": "/certification/email",
    "title": "認證 常用電子信箱",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "204",
            "description": "<p>Email格式錯誤</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationEmail"
  },
  {
    "type": "post",
    "url": "/certification/emergency",
    "title": "認證 緊急聯絡人",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "size": "2..15",
            "optional": false,
            "field": "name",
            "description": "<p>緊急聯絡人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>緊急聯絡人電話</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "relationship",
            "description": "<p>緊急聯絡人關係</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationEmergency"
  },
  {
    "type": "post",
    "url": "/certification/financial",
    "title": "認證 財務訊息認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "parttime",
            "description": "<p>打工收入</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "allowance",
            "description": "<p>零用錢收入</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "scholarship",
            "description": "<p>獎學金收入</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "other_income",
            "description": "<p>其他收入</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "restaurant",
            "description": "<p>餐飲支出</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "transportation",
            "description": "<p>交通支出</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "entertainment",
            "description": "<p>娛樂支出</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "other_expense",
            "description": "<p>其他支出</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": true,
            "field": "creditcard_image",
            "description": "<p>信用卡帳單照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": true,
            "field": "passbook_image",
            "description": "<p>存摺內頁照</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationFinancial"
  },
  {
    "type": "post",
    "url": "/certification/idcard",
    "title": "認證 實名認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "size": "2..15",
            "optional": false,
            "field": "name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_card_date",
            "description": "<p>發證日期(民國) ex:1060707</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_card_place",
            "description": "<p>發證地點</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "birthday",
            "description": "<p>生日(民國) ex:1020101</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>地址</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "front_image",
            "description": "<p>身分證正面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "back_image",
            "description": "<p>身分證背面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "person_image",
            "description": "<p>本人照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "healthcard_image",
            "description": "<p>健保卡照</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "504",
            "description": "<p>身分證字號格式錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "505",
            "description": "<p>身分證字號已存在</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "504",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"504\"\n}",
          "type": "json"
        },
        {
          "title": "505",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"505\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationIdcard"
  },
  {
    "type": "post",
    "url": "/certification/social",
    "title": "認證 社交認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "facebook",
              "instagram"
            ],
            "optional": false,
            "field": "type",
            "description": "<p>認證類型</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "access_token",
            "description": "<p>access_token</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationSocial"
  },
  {
    "type": "post",
    "url": "/certification/student",
    "title": "認證 學生身份認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "school",
            "description": "<p>學校名稱</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "0",
              "1",
              "2"
            ],
            "optional": true,
            "field": "system",
            "defaultValue": "0",
            "description": "<p>學制 0:大學 1:碩士 2:博士</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "major",
            "description": "<p>學門</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "department",
            "description": "<p>系所</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "grade",
            "description": "<p>年級</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "student_id",
            "description": "<p>學號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>校內電子信箱</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "front_image",
            "description": "<p>學生證正面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "back_image",
            "description": "<p>學生證背面照</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sip_account",
            "description": "<p>SIP帳號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sip_password",
            "description": "<p>SIP密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": true,
            "field": "transcript_image",
            "description": "<p>成績單</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "502",
            "description": "<p>此驗證已通過驗證</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "510",
            "description": "<p>此學號已被使用過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "511",
            "description": "<p>此學生Email已被使用過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "204",
            "description": "<p>Email格式錯誤</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "json"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "json"
        },
        {
          "title": "510",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"510\"\n}",
          "type": "json"
        },
        {
          "title": "511",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"511\"\n}",
          "type": "json"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationStudent"
  },
  {
    "type": "post",
    "url": "/certification/verifyemail",
    "title": "認證 認證電子信箱(學生身份、常用電子信箱)",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>認證Type</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>認證Code</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "204",
            "description": "<p>Email格式錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "303",
            "description": "<p>驗證碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "200",
            "description": "<p>參數錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "json"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationVerifyemail"
  },
  {
    "type": "get",
    "url": "/notification/info/{id}",
    "title": "消息 消息內容（已讀）",
    "group": "Notification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>代號</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Notification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端 2:共通</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>標題</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>1:未讀 2:已讀</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"investor\":\"1\",\n\t\t\t\"title\":\"用戶資料認證未通過\",\n\t\t\t\"content\":\"您好！ 您的資料認證未通過，請重新認證。\",\n\t\t\t\"status\":\"1\",\n\t\t\t\"created_at\":\"1519635711\"\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "601",
            "description": "<p>此消息不存在</p>"
          },
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
          "title": "601",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"601\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Notification.php",
    "groupTitle": "Notification",
    "name": "GetNotificationInfoId"
  },
  {
    "type": "get",
    "url": "/notification/list",
    "title": "消息 消息列表",
    "group": "Notification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Notification ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端 2:共通</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>標題</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>1:未讀 2:已讀</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"investor\":\"1\",\n\t\t\t\t\"title\":\"用戶資料認證未通過\",\n\t\t\t\t\"content\":\"您好！ 您的資料認證未通過，請重新認證。\",\n\t\t\t\t\"status\":\"1\",\n\t\t\t\t\"created_at\":\"1519635711\",\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"investor\":\"2\",\n\t\t\t\t\"title\":\"用戶實名認證通過\",\n\t\t\t\t\"content\":\"尊敬的用戶： 您好！ 您的實名認證已通過。\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"created_at\":\"1519635711\",\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Notification.php",
    "groupTitle": "Notification",
    "name": "GetNotificationList",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/notification/readall",
    "title": "消息 一鍵已讀",
    "group": "Notification",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\":\"SUCCESS\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Notification.php",
    "groupTitle": "Notification",
    "name": "GetNotificationReadall",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/product/applyinfo/{ID}",
    "title": "借款方 申請紀錄資訊",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "platform_fee",
            "description": "<p>平台服務費</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>核可利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>合約內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "certification",
            "description": "<p>認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "credit",
            "description": "<p>信用資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit.level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit.points",
            "description": "<p>信用分數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit.amount",
            "description": "<p>總信用額度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit.created_at",
            "description": "<p>核准日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>預計還款計畫(簽約後不出現)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.instalment",
            "description": "<p>借款期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.rate",
            "description": "<p>年利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.date",
            "description": "<p>起始時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total_payment",
            "description": "<p>每月還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.leap_year",
            "description": "<p>是否為閏年</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.year_days",
            "description": "<p>本年日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.XIRR",
            "description": "<p>XIRR</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.instalment",
            "description": "<p>第幾期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.repayment_date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.days",
            "description": "<p>本期日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total",
            "description": "<p>還款總計</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total.principal",
            "description": "<p>本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total.interest",
            "description": "<p>利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total.total_payment",
            "description": "<p>加總</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\"product_id\":\"1\",\n\t\t\t\"user_id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\"platform_fee\":\"1500\",\n\t\t\t\"interest_rate\":\"9\",\n\t\t\t\"instalment\":\"3期\",\n\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\"remark\":\"\",\n\t\t\t\"delay\":\"0\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"sub_status\":\"0\",\n\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\"contract\":\"我是合約\",\n\t\t\t\"product\":{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\"alias\":\"FA\"\n\t\t\t},\n\t\t\t\"credit\":{\n\t\t\t\t\"level\":\"1\",\n\t\t\t\t\"points\":\"1985\",\n\t\t\t\t\"amount\":\"45000\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t},\n\t         \"certification\": [\n          \t{\n          \t     \"id\": \"1\",\n          \t     \"name\": \"身分證認證\",\n          \t     \"description\": \"身分證認證\",\n          \t     \"alias\": \"id_card\",\n           \t    \"user_status\": \"1\"\n          \t},\n          \t{\n          \t    \"id\": \"2\",\n           \t    \"name\": \"學生證認證\",\n          \t    \"description\": \"學生證認證\",\n           \t   \"alias\": \"student\",\n           \t   \"user_status\": \"1\"\n          \t}\n          ],\n      \"amortization_schedule\": {\n          \"amount\": \"12000\",\n          \"instalment\": \"6\",\n          \"rate\": \"9\",\n          \"date\": \"2018-04-17\",\n          \"total_payment\": 2053,\n          \"leap_year\": false,\n          \"year_days\": 365,\n          \"XIRR\": 0.0939,\n          \"schedule\": {\n               \"1\": {\n                 \"instalment\": 1,\n                 \"repayment_date\": \"2018-06-10\",\n                 \"days\": 54,\n                 \"remaining_principal\": \"12000\",\n                 \"principal\": 1893,\n                 \"interest\": 160,\n                 \"total_payment\": 2053\n             },\n             \"2\": {\n                  \"instalment\": 2,\n                 \"repayment_date\": \"2018-07-10\",\n                 \"days\": 30,\n                  \"remaining_principal\": 10107,\n                  \"principal\": 1978,\n                  \"interest\": 75,\n                   \"total_payment\": 2053\n              },\n             \"3\": {\n                   \"instalment\": 3,\n                   \"repayment_date\": \"2018-08-10\",\n                   \"days\": 31,\n                   \"remaining_principal\": 8129,\n                  \"principal\": 1991,\n                  \"interest\": 62,\n                   \"total_payment\": 2053\n               }\n           },\n          \"total\": {\n               \"principal\": 12000,\n               \"interest\": 391,\n               \"total_payment\": 12391\n           }\n       }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "GetProductApplyinfoId"
  },
  {
    "type": "get",
    "url": "/product/applylist",
    "title": "借款方 申請紀錄列表",
    "group": "Product",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "platform_fee",
            "description": "<p>平台服務費</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\"product_id\":\"2\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\t\"alias\":\"FA\"\n\t\t\t\t},\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"platform_fee\":\"\",\n\t\t\t\t\"interest_rate\":\"0,\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\":\"0\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"sub_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"target_no\": \"1803269713\",\n\t\t\t\t\"product_id\":\"2\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\t\"alias\":\"FA\"\n\t\t\t\t},\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"platform_fee\":\"\",\n\t\t\t\t\"interest_rate\":\"\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\":\"0\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"sub_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "GetProductApplylist",
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/product/cancel/{ID}",
    "title": "借款方 取消申請",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作</p>"
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "GetProductCancelId"
  },
  {
    "type": "get",
    "url": "/product/info/{ID}",
    "title": "借款方 取得產品資訊",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>產品ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>簡介</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "rank",
            "description": "<p>排序</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_range_s",
            "description": "<p>最低借款額度(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_range_e",
            "description": "<p>最高借款額度(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate_s",
            "description": "<p>年利率下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate_e",
            "description": "<p>年利率上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "charge_platform",
            "description": "<p>平台服務費(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "charge_platform_min",
            "description": "<p>平台最低服務費(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "instalment",
            "description": "<p>可申請期數</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"product\":\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"rank\":\"0\",\n\t\t\t\t\"loan_range_s\":\"12222\",\n\t\t\t\t\"loan_range_e\":\"14333333\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"14\",\n\t\t\t\t\"charge_platform\":\"0\",\n\t\t\t\t\"charge_platform_min\":\"0\",\n\t\t\t\t\"instalment\": [\n\t\t\t\t{\n\t\t\t\t      \"name\": \"3期\",\n\t\t\t\t      \"value\": 3\n\t\t\t\t    },\n\t\t\t\t{\n\t\t\t\t      \"name\": \"12期\",\n\t\t\t\t      \"value\": 12\n\t\t\t\t    },\n\t\t\t\t{\n\t\t\t\t      \"name\": \"24期\",\n\t\t\t\t      \"value\": 24\n\t\t\t\t    },\n\t\t\t\t],\n\t\t\t\t\"repayment\": [\n\t\t\t\t{\n\t\t\t\t      \"name\": \"等額本息\",\n\t\t\t\t      \"value\": 1\n\t\t\t\t    }\n\t\t\t\t],\n\t\t\t}\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "401",
            "description": "<p>產品不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "408",
            "description": "<p>未完成預先申請</p>"
          },
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "json"
        },
        {
          "title": "408",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "GetProductInfoId"
  },
  {
    "type": "get",
    "url": "/product/list",
    "title": "借款方 取得產品列表",
    "group": "Product",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>簡介</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "rank",
            "description": "<p>排序</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "instalment",
            "description": "<p>可申請期數</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "repayment",
            "description": "<p>可選還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_range_s",
            "description": "<p>最低借款額度(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_range_e",
            "description": "<p>最高借款額度(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate_s",
            "description": "<p>年利率下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate_e",
            "description": "<p>年利率上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "charge_platform",
            "description": "<p>平台服務費(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "charge_platform_min",
            "description": "<p>平台最低服務費(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>申請資訊（未簽約）</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "certification",
            "description": "<p>認證完成資訊</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"rank\":\"0\",\n\t\t\t\t\"loan_range_s\":\"12222\",\n\t\t\t\t\"loan_range_e\":\"14333333\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"14\",\n\t\t\t\t\"charge_platform\":\"0\",\n\t\t\t\t\"charge_platform_min\":\"0\",\n\t\t\t\t\"instalment\": [\n\t\t\t\t\t{\n\t\t\t\t      \"name\": \"3期\",\n\t\t\t\t      \"value\": 3\n\t\t\t\t    },\n\t\t\t\t\t{\n\t\t\t\t      \"name\": \"12期\",\n\t\t\t\t      \"value\": 12\n\t\t\t\t    },\n\t\t\t\t\t{\n\t\t\t\t      \"name\": \"24期\",\n\t\t\t\t      \"value\": 24\n\t\t\t\t    },\n\t\t\t\t],\n\t\t\t\t\"repayment\": [\n\t\t\t\t\t{\n\t\t\t\t      \"name\": \"等額本息\",\n\t\t\t\t      \"value\": 1\n\t\t\t\t    }\n\t\t\t\t],\n\t\t\t\t\"target\":{\n\t\t\t\t\t\"id\":\"1\",\n\t\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\t\"status\":\"0\",\n\t\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t\t},\n\t\t\t\t\"certification\":[\n\t\t\t\t\t{\n\t\t\t\t\t\t\"id\":\"1\",\n\t\t\t\t\t\t\"name\": \"實名認證\",\n\t\t\t\t\t\t\"description\":\"實名認證\",\n\t\t\t\t\t\t\"alias\":\"id_card\",\n\t\t\t\t\t\t\"user_status\":\"1\"\n\t\t\t\t\t},\n\t\t\t\t\t{\n\t\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\t\"name\": \"學生身份認證\",\n\t\t\t\t\t\t\"description\":\"學生身份認證\",\n\t\t\t\t\t\t\"alias\":\"student\",\n\t\t\t\t\t\t\"user_status\":\"1\"\n\t\t\t\t\t}\n\t\t\t\t]\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "GetProductList"
  },
  {
    "type": "post",
    "url": "/product/apply",
    "title": "借款方 申請產品",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "instalment",
            "description": "<p>申請期數</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "promote_code",
            "description": "<p>邀請碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_id",
            "description": "<p>Targets ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"target_id\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "401",
            "description": "<p>產品不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "402",
            "description": "<p>超過此產品可申請額度</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "403",
            "description": "<p>不支援此期數</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "408",
            "description": "<p>重複申請</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "json"
        },
        {
          "title": "402",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"402\"\n}",
          "type": "json"
        },
        {
          "title": "403",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"403\"\n}",
          "type": "json"
        },
        {
          "title": "408",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"408\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "PostProductApply"
  },
  {
    "type": "post",
    "url": "/product/signing",
    "title": "借款方 申請簽約",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "target_id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "person_image",
            "description": "<p>本人照</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "401",
            "description": "<p>產品不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>未綁定金融帳號</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "206",
            "description": "<p>人臉辨識不通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲或大於35歲</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "json"
        },
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "206",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"206\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "PostProductSigning"
  },
  {
    "type": "get",
    "url": "/recoveries/dashboard",
    "title": "出借方 我的帳戶",
    "group": "Recoveries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remaining_principal",
            "description": "<p>持有債權</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_receivable",
            "description": "<p>應收利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest",
            "description": "<p>已收利息收入</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "other_income",
            "description": "<p>已收其他收入</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "principal_level",
            "description": "<p>標的等級應收帳款 1~5:正常 6:觀察 7:次級 8:不良</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "funds",
            "description": "<p>資金資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.total",
            "description": "<p>資金總額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.last_recharge_date",
            "description": "<p>最後一次匯入日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.frozen",
            "description": "<p>待交易餘額</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "bank_account",
            "description": "<p>綁定金融帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.bank_account",
            "description": "<p>銀行帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"remaining_principal\": \"50000\",\n\t\t\t\"interest\": \"0\",\n\t\t\t\"accounts_receivable\": \"50751\",\n\t\t\t\"interest_receivable\": \"751\",\n\t\t\t\"other_income\": \"0\",\n\t\t\t\"principal_level\": {\n\t\t\t\t  \"1\": 0,\n\t\t\t\t  \"2\": 500,\n\t\t\t\t  \"3\": 0,\n\t\t\t\t  \"4\": 50000,\n\t\t\t\t  \"5\": 0,\n\t\t\t\t  \"6\": 0,\n\t\t\t\t  \"7\": 0,\n\t\t\t\t  \"8\": 0\n\t\t\t},\n\t\t\t\"funds\": {\n\t\t\t\t \"total\": \"500\",\n\t\t\t\t \"last_recharge_date\": \"2018-05-03 19:15:42\",\n\t\t\t\t \"frozen\": \"0\"\n\t\t\t},\n\t        \"bank_account\": {\n\t            \"bank_code\": \"013\",\n\t            \"branch_code\": \"1234\",\n\t            \"bank_account\": \"12345678910\"\n\t        },\n\t        \"virtual_account\": {\n\t            \"bank_code\": \"013\",\n\t            \"branch_code\": \"0154\",\n\t            \"bank_name\": \"國泰世華商業銀行\",\n\t            \"branch_name\": \"信義分行\",\n\t            \"virtual_account\": \"56639100000001\"\n\t        }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "GetRecoveriesDashboard",
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/recoveries/info/{ID}",
    "title": "出借方 已出借資訊",
    "group": "Recoveries",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Investments ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Investments ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>出借金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>合約內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer_status",
            "description": "<p>債權轉讓狀態 0:無 1:已申請 2:移轉成功</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "transfer",
            "description": "<p>債轉資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.amount",
            "description": "<p>債權轉讓本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.transfer_fee",
            "description": "<p>債權轉讓手續費</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.contract",
            "description": "<p>債權轉讓合約</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.transfer_date",
            "description": "<p>債權轉讓日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>回款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.instalment",
            "description": "<p>借款期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.rate",
            "description": "<p>年利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.date",
            "description": "<p>起始時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total_payment",
            "description": "<p>每月還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule",
            "description": "<p>回款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.instalment",
            "description": "<p>第幾期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.repayment_date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.days",
            "description": "<p>本期日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.repayment",
            "description": "<p>已還款金額</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"amount\":\"50000\",\n\t\t\t\"loan_amount\":\"\",\n\t\t\t\"status\":\"3\",\n\t\t\t\"transfer_status\":\"1\",\n\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\"transfer\":{\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"transfer_fee\":\"25\",\n\t\t\t\t\"contract\":\"我是合約，我是合約\",\n\t\t\t\t\"transfer_date\": null\n\t\t\t},\n\t\t\t\"product\":{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t},\n\t\t\t\"target\": {\n\t\t\t\t\"id\": \"19\",\n\t\t\t\t\"target_no\": \"1804233189\",\n\t\t\t\t\"credit_level\": \"4\",\n\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\"delay_days\": \"0\",\n\t\t\t\t\"instalment\": \"3期\",\n\t\t\t\t\"repayment\": \"等額本息\",\n\t\t\t\t\"status\": \"5\"\n\t\t\t},\n      \t\"amortization_schedule\": {\n          \t\"amount\": \"12000\",\n          \t\"instalment\": \"3\",\n          \t\"rate\": \"9\",\n          \t\"date\": \"2018-04-17\",\n          \t\"total_payment\": 2053,\n          \t\"list\": {\n             \t\"1\": {\n                 \t\"instalment\": 1,\n                 \t\"repayment_date\": \"2018-06-10\",\n                 \t\"repayment\": 0,\n                 \t\"principal\": 1893,\n                 \t\"interest\": 160,\n                 \t\"total_payment\": 2053\n             \t},\n             \t\"2\": {\n                 \t\"instalment\": 2,\n                  \t\"repayment_date\": \"2018-07-10\",\n                  \t\"repayment\": 0,\n                  \t\"principal\": 1978,\n                  \t\"interest\": 75,\n                  \t\"total_payment\": 2053\n              \t},\n             \t\"3\": {\n                   \t\"instalment\": 3,\n                   \t\"repayment_date\": \"2018-08-10\",\n                   \t\"repayment\": 0,\n                   \t\"principal\": 1991,\n                   \t\"interest\": 62,\n                   \t\"total_payment\": 2053\n              \t}\n           \t}\n       \t}\n\t\t }\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "806",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "805",
            "description": "<p>對此申請無權限</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "json"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "GetRecoveriesInfoId"
  },
  {
    "type": "get",
    "url": "/recoveries/list",
    "title": "出借方 已出借列表",
    "group": "Recoveries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Investments ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>出借金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer_status",
            "description": "<p>債權轉讓狀態 0:無 1:已申請 2:移轉成功</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "next_repayment",
            "description": "<p>最近一期應還款</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.amount",
            "description": "<p>金額</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\":\"50000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"status\":\"3\",\n\t\t\t\t\"transfer_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t\t},\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": \"19\",\n\t\t\t\t\t\"target_no\": \"1804233189\",\n\t\t\t\t\t\"credit_level\": \"4\",\n\t\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\t\"delay_days\": \"0\",\n\t\t\t\t\t\"status\": \"5\",\n\t\t\t\t\t\"sub_status\": \"0\"\n\t\t\t\t},\n\t        \t\"next_repayment\": {\n\t            \t\"date\": \"2018-06-10\",\n\t            \t\"instalment\": \"1\",\n\t            \t\"amount\": \"16890\"\n\t        \t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "GetRecoveriesList",
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/recoveries/passbook",
    "title": "出借方 虛擬帳戶明細",
    "group": "Recoveries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_amount",
            "description": "<p>帳戶餘額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "tx_datetime",
            "description": "<p>交易時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>入帳時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "action",
            "description": "<p>debit:資產增加 credit:資產減少</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"amount\":\"50500\",\n\t\t\t\t\"bank_amount\":\"50500\",\n\t\t\t\t\"remark\":\"source:3\",\n\t\t\t\t\"tx_datetime\":\"2018-05-08 15:38:07\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t\t\"action\":\"debit\"\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"amount\":\"-50000\",\n\t\t\t\t\"bank_amount\":\"500\",\n\t\t\t\t\"remark\":\"source:3\",\n\t\t\t\t\"tx_datetime\":\"2018-04-20 17:55:51\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t\t\"action\":\"credit\"\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "GetRecoveriesPassbook"
  },
  {
    "type": "post",
    "url": "/recoveries/pretransfer",
    "title": "出借方 我要轉讓",
    "group": "Recoveries",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "ids",
            "description": "<p>Investments IDs ex: 1,3,10,21</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_principal",
            "description": "<p>轉讓價金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_fee",
            "description": "<p>預計轉讓費用</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "debt_transfer_contract",
            "description": "<p>轉讓合約(多份)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \t\"data\": {\n          \"total_principal\": 50000,\n          \"total_fee\": 250,\n          \"max_instalment\": 3,\n          \"min_instalment\": 3,\n          \"debt_transfer_contract\": [\"我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約\"]\n      }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "807",
            "description": "<p>此申請狀態不符</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "806",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "805",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "808",
            "description": "<p>已申請過債權轉出</p>"
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "807",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"807\"\n}",
          "type": "json"
        },
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "json"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "json"
        },
        {
          "title": "808",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"808\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "PostRecoveriesPretransfer"
  },
  {
    "type": "post",
    "url": "/recoveries/transfer",
    "title": "出借方 轉讓申請",
    "group": "Recoveries",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "ids",
            "description": "<p>Investments IDs (複選使用逗號隔開1,3,10,21)</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "807",
            "description": "<p>此申請狀態不符</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "806",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "805",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "808",
            "description": "<p>已申請過債權轉出</p>"
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "807",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"807\"\n}",
          "type": "json"
        },
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "json"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "json"
        },
        {
          "title": "808",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"808\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "PostRecoveriesTransfer"
  },
  {
    "type": "post",
    "url": "/recoveries/withdraw",
    "title": "出借方 提領申請",
    "group": "Recoveries",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "amount",
            "description": "<p>提領金額</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "transaction_password",
            "description": "<p>交易密碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_id",
            "description": "<p>Targets ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "210",
            "description": "<p>交易密碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "211",
            "description": "<p>可用餘額不足</p>"
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "210",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"210\"\n}",
          "type": "json"
        },
        {
          "title": "211",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"211\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "name": "PostRecoveriesWithdraw"
  },
  {
    "type": "get",
    "url": "/repayment/contract/{ID}",
    "title": "借款方 合約列表",
    "group": "Repayment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>合約標題</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>合約內容</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t\t{\n\t\t\t\t\t\"title\": \"借貸契約書\",\n\t\t\t\t\t\"contract\":\"我就是合約啊！！我就是合約啊！！我就是合約啊！！\"\n\t\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "name": "GetRepaymentContractId"
  },
  {
    "type": "get",
    "url": "/repayment/dashboard",
    "title": "借款端 我的還款",
    "group": "Repayment",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remaining_principal",
            "description": "<p>現欠本金餘額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment",
            "description": "<p>當期待還本息</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "user",
            "description": "<p>用戶資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.picture",
            "description": "<p>照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.nickname",
            "description": "<p>暱稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.sex",
            "description": "<p>性別</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.phone",
            "description": "<p>手機號碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.investor",
            "description": "<p>1:投資端 0:借款端</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.my_promote_code",
            "description": "<p>推廣碼</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "funds",
            "description": "<p>資金資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.total",
            "description": "<p>資金總額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.last_recharge_date",
            "description": "<p>最後一次匯入日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.frozen",
            "description": "<p>待交易餘額</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\t\"remaining_principal\": \"12345\",\n\t\t\t\t\"next_repayment_amount\": \"1588\",\n\t\t\t\t\"user\": {\n     \t\t\t\"id\": \"1\",\n     \t\t\t\"name\": \"\",\n     \t\t\t\"picture\": \"https://graph.facebook.com/2495004840516393/picture?type=large\",\n     \t\t\t\"nickname\": \"陳霈\",\n     \t\t\t\"phone\": \"0912345678\",\n     \t\t\t\"investor_status\": \"1\",\n     \t\t\t\"my_promote_code\": \"9JJ12CQ5\",\n     \t\t\t\"id_number\": null,\n     \t\t\t\"investor\": 1,  \n     \t\t\t\"created_at\": \"1522651818\",     \n     \t\t\t\"updated_at\": \"1522653939\"     \n\t\t\t\t},\n\t\t\t\t\"funds\": {\n\t\t\t\t \t\"total\": \"500\",\n\t\t\t\t \t\"last_recharge_date\": \"2018-05-03 19:15:42\",\n\t\t\t\t \t\"frozen\": \"0\"\n\t\t\t\t},\n\t        \t\"virtual_account\": {\n\t            \t\"bank_code\": \"013\",\n\t            \t\"branch_code\": \"0154\",\n\t            \t\"bank_name\": \"國泰世華商業銀行\",\n\t            \t\"branch_name\": \"信義分行\",\n\t            \t\"virtual_account\": \"56639100000001\"\n\t        \t}\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "name": "GetRepaymentDashboard",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/repayment/info/{ID}",
    "title": "借款方 我的還款資訊",
    "group": "Repayment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remaining_instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "fees",
            "description": "<p>費用資料</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "fees.sub_loan_fees",
            "description": "<p>產品轉換手續費%</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "fees.liquidated_damages",
            "description": "<p>違約金(提前還款)%</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "next_repayment",
            "description": "<p>最近一期應還款</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.list",
            "description": "<p>明細</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>還款專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.instalment",
            "description": "<p>借款期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.rate",
            "description": "<p>年利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.date",
            "description": "<p>起始時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total_payment",
            "description": "<p>每月還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.sub_loan_fees",
            "description": "<p>轉貸手續費用</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.platform_fees",
            "description": "<p>平台服務費用</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.instalment",
            "description": "<p>第幾期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.repayment_date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.repayment",
            "description": "<p>已還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.delay_interest",
            "description": "<p>延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.list.liquidated_damages",
            "description": "<p>違約金（提還費）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\"product_id\":\"1\",\n\t\t\t\"user_id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\"interest_rate\":\"9\",\n\t\t\t\"instalment\":\"3期\",\n\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\"remark\":\"\",\n\t\t\t\"delay\":\"0\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"sub_status\":\"0\",\n\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\"remaining_principal\":\"50000\",\n\t\t\t\"remaining_instalment\":\"3\",\n\t\t\"product\":{\n\t\t\t\"id\":\"2\",\n\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\"alias\":\"FA\"\n\t\t},\n\t     \"fees\": {\n\t         \"sub_loan_fees\": \"1\",\n\t         \"liquidated_damages\": \"2\"\n\t     },\n\t     \"next_repayment\": {\n\t         \"date\": \"2018-06-10\",\n\t         \"instalment\": \"1\",\n\t         \"amount\": \"16890\",\n\t         \"list\": {\n\t             \"11\": {\n\t                 \"amount\": 16539,\n\t                 \"source_name\": \"應付借款本金\"\n\t             },\n\t             \"13\": {\n\t                 \"amount\": 351,\n\t                 \"source_name\": \"應付借款利息\"\n\t             }\n\t         }\n\t     },\n\t     \"virtual_account\": {\n\t         \"bank_code\": \"013\",\n\t         \"branch_code\": \"0154\",\n\t         \"bank_name\": \"國泰世華商業銀行\",\n\t         \"branch_name\": \"信義分行\",\n\t         \"virtual_account\": \"56631803269743\"\n\t     },\n      \"amortization_schedule\": {\n          \"amount\": \"12000\",\n          \"instalment\": \"3\",\n          \"rate\": \"9\",\n          \"date\": \"2018-04-17\",\n          \"total_payment\": 2053,\n          \"sub_loan_fees\": 0,\n          \"platform_fees\": 1500,\n          \"schedule\": {\n             \"1\": {\n                \t\"instalment\": 1,\n                 \"repayment_date\": \"2018-06-10\",\n                 \"repayment\": 0,\n                 \"principal\": 1893,\n                 \"interest\": 160,\n                 \"total_payment\": 2053,\n                 \"delay_interest\": 0,\n                 \"liquidated_damages\": 0\n             },\n             \"2\": {\n                  \"instalment\": 2,\n                  \"repayment_date\": \"2018-07-10\",\n                  \"repayment\": 0,\n                  \"principal\": 1978,\n                  \"interest\": 75,\n                  \"total_payment\": 2053,\n                  \"delay_interest\": 0,\n                  \"liquidated_damages\": 0\n              },\n             \"3\": {\n                  \"instalment\": 3,\n                  \"repayment_date\": \"2018-08-10\",\n                  \"repayment\": 0,\n                  \"principal\": 1991,\n                  \"interest\": 62,\n                  \"total_payment\": 2053,\n                  \"delay_interest\": 0,\n                  \"liquidated_damages\": 0\n              }\n          }\n       }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "name": "GetRepaymentInfoId"
  },
  {
    "type": "get",
    "url": "/repayment/list",
    "title": "借款方 我的還款列表",
    "group": "Repayment",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "next_repayment",
            "description": "<p>最近一期應還款</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "next_repayment.amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>還款專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t\t},\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"loan_amount\":\"50000\",\n\t\t\t\t\"interest_rate\":\"8\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\":\"0\",\n\t\t\t\t\"delay_days\":\"0\",\n\t\t\t\t\"status\":\"5\",\n\t\t\t\t\"sub_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t        \t\"next_repayment\": {\n\t            \t\"date\": \"2018-06-10\",\n\t            \t\"instalment\": \"1\",\n\t            \t\"amount\": \"16890\"\n\t        \t},\n\t        \t\"virtual_account\": {\n\t            \t\"bank_code\": \"013\",\n\t            \t\"branch_code\": \"0154\",\n\t            \t\"bank_name\": \"國泰世華商業銀行\",\n\t            \t\"branch_name\": \"信義分行\",\n\t            \t\"virtual_account\": \"56631803269743\"\n\t        \t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "name": "GetRepaymentList",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/repayment/prepayment/{ID}",
    "title": "借款方 提前還款資訊",
    "group": "Repayment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "description": "<p>只有正常還款的狀態才可申請，逾期或寬限期內都將不通過</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "prepayment",
            "description": "<p>提前還款資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.remaining_instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.settlement_date",
            "description": "<p>結息日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.liquidated_damages",
            "description": "<p>違約金（提還費）</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.delay_interest_payable",
            "description": "<p>應付延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.interest_payable",
            "description": "<p>應付利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "prepayment.total",
            "description": "<p>合計</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>還款專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\"product_id\":\"1\",\n\t\t\t\"user_id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\"interest_rate\":\"9\",\n\t\t\t\"instalment\":\"3\",\n\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\"delay\":\"0\",\n\t\t\t\"delay_days\":\"0\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"created_at\":\"1520421572\",\n\t     \"prepayment\": {\n\t         \"remaining_principal\": \"50000\",\n\t         \"remaining_instalment\": \"3\",\n\t         \"settlement_date\": \"2018-05-19\",\n\t         \"liquidated_damages\": \"1000\",\n\t         \"delay_interest_payable\": \"0\",\n\t         \"interest_payable\": \"450\",\n\t         \"total\": \"51450\"\n\t     },\n\t     \"virtual_account\": {\n\t         \"bank_code\": \"013\",\n\t         \"branch_code\": \"0154\",\n\t         \"bank_name\": \"國泰世華商業銀行\",\n\t         \"branch_name\": \"信義分行\",\n\t         \"virtual_account\": \"56631803269743\"\n\t     }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "name": "GetRepaymentPrepaymentId"
  },
  {
    "type": "post",
    "url": "/repayment/prepayment/{ID}",
    "title": "借款方 申請提前還款",
    "group": "Repayment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "description": "<p>只有正常還款的狀態才可申請，逾期或寬限期內都將不通過</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\"\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "903",
            "description": "<p>已申請提前還款或產品轉換</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "name": "PostRepaymentPrepaymentId"
  },
  {
    "type": "get",
    "url": "/subloan/applyinfo/{ID}",
    "title": "借款方 產品轉換紀錄資訊",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>產品轉換金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "settlement_date",
            "description": "<p>結息日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>產品轉換狀態 0:待簽約 1:轉貸中 2:成功 8:已取消 9:申請失敗</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "subloan_target",
            "description": "<p>產品轉換產生的Target</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.platform_fee",
            "description": "<p>平台服務費</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.interest_rate",
            "description": "<p>核可利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.contract",
            "description": "<p>合約內容</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "subloan_target.product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "subloan_target.amortization_schedule",
            "description": "<p>預計還款計畫(簽約後不出現)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.instalment",
            "description": "<p>借款期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.rate",
            "description": "<p>年利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.date",
            "description": "<p>起始時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.total_payment",
            "description": "<p>每月還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.leap_year",
            "description": "<p>是否為閏年</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.year_days",
            "description": "<p>本年日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.XIRR",
            "description": "<p>XIRR</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.instalment",
            "description": "<p>第幾期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.repayment_date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.days",
            "description": "<p>本期日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.schedule.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.total",
            "description": "<p>還款總計</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.total.principal",
            "description": "<p>本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.total.interest",
            "description": "<p>利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subloan_target.amortization_schedule.total.total_payment",
            "description": "<p>加總</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"target_id\":\"1\",\n\t\t\t\"amount\":\"53651\",\n\t\t\t\"instalment\":\"3期\",\n\t\t\t\"repayment\":\"先息後本\",\n\t\t\t\"settlement_date\":\"2018-05-26\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"created_at\":\"1527151277\",\n\t\t\t\"subloan_target\": {\n\t\t\t\t\"id\":\"35\",\n\t\t\t\t\"target_no\": \"1805247784\",\n\t\t\t\t\"product_id\":\"1\",\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"amount\":\"53651\",\n\t\t\t\t\"loan_amount\":\"53651\",\n\t\t\t\t\"platform_fee\":\"1610\",\n\t\t\t\t\"interest_rate\":\"9\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"先息後本\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\":\"0\",\n\t\t\t\t\"status\":\"1\",\n\t\t\t\t\"sub_status\":\"8\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t\t},\n      \t\t\"amortization_schedule\": {\n          \t\t\"amount\": \"12000\",\n          \t\t\"instalment\": \"6\",\n          \t\t\"rate\": \"9\",\n          \t\t\"date\": \"2018-04-17\",\n          \t\t\"total_payment\": 2053,\n          \t\t\"leap_year\": false,\n          \t\t\"year_days\": 365,\n          \t\t\"XIRR\": 0.0939,\n          \t\t\"schedule\": {\n               \t\t\"1\": {\n                 \t\t\"instalment\": 1,\n                 \t\t\"repayment_date\": \"2018-06-10\",\n                 \t\t\"days\": 54,\n                 \t\t\"remaining_principal\": \"12000\",\n                 \t\t\"principal\": 1893,\n                 \t\t\"interest\": 160,\n                 \t\t\"total_payment\": 2053\n             \t\t},\n             \t\t\"2\": {\n                  \t\t\"instalment\": 2,\n                 \t\t\"repayment_date\": \"2018-07-10\",\n                 \t\t\"days\": 30,\n                  \t\t\"remaining_principal\": 10107,\n                  \t\t\"principal\": 1978,\n                  \t\t\"interest\": 75,\n                   \t\t\"total_payment\": 2053\n              \t\t},\n             \t\t\"3\": {\n                   \t\t\"instalment\": 3,\n                   \t\t\"repayment_date\": \"2018-08-10\",\n                   \t\t\"days\": 31,\n                   \t\t\"remaining_principal\": 8129,\n                  \t\t\"principal\": 1991,\n                  \t\t\"interest\": 62,\n                   \t\t\"total_payment\": 2053\n               \t\t}\n           \t\t},\n          \t\t\"total\": {\n               \t\t\"principal\": 12000,\n               \t\t\"interest\": 391,\n               \t\t\"total_payment\": 12391\n           \t\t}\n       \t\t}\n\t\t\t}\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "904",
            "description": "<p>尚未申請產品轉換</p>"
          },
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "name": "GetSubloanApplyinfoId"
  },
  {
    "type": "get",
    "url": "/subloan/cancel/{ID}",
    "title": "借款方 取消產品轉換",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "904",
            "description": "<p>尚未申請產品轉換</p>"
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "name": "GetSubloanCancelId"
  },
  {
    "type": "get",
    "url": "/subloan/preapply/{ID}",
    "title": "借款方 產品轉換前資訊",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>Targets ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t       \"amount\": 51493,\n\t       \"instalment\": [\n\t       \t{\n\t       \t\t\"name\": \"3期\",\n\t       \t\t\"value\": 3\n\t       \t},\n\t       \t{\n\t       \t\t\"name\": \"6期\",\n\t       \t\t\"value\": 6\n\t       \t},\n\t       \t{\n\t       \t\t\"name\": \"12期\",\n\t       \t\t\"value\": 12\n\t       \t},\n\t       \t{\n\t       \t\t\"name\": \"18期\",\n\t       \t\t\"value\": 18\n\t       \t},\n\t       \t{\n\t       \t\t\"name\": \"24期\",\n\t       \t\t\"value\": 24\n\t       \t}\n\t       ],\n\t       \"repayment\": {\n\t       \t\t\"1\": {\n\t       \t\t\t\"name\": \"等額本息\",\n\t       \t\t\t\"value\": 1\n\t       \t\t},\n\t       \t\t\"2\": {\n\t       \t\t\t\"name\": \"先息後本\",\n\t       \t\t\t\"value\": 2\n\t       \t\t}\n\t       }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作(需逾期且過寬限期)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "903",
            "description": "<p>已申請提前還款或產品轉換</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "name": "GetSubloanPreapplyId"
  },
  {
    "type": "post",
    "url": "/subloan/apply/",
    "title": "借款方 產品轉換申請",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "target_id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "instalment",
            "description": "<p>申請期數</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\"\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "403",
            "description": "<p>不支援此期數</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作(需逾期且過寬限期)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "409",
            "description": "<p>不支援此還款方式</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "903",
            "description": "<p>已申請提前還款或產品轉換</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "207",
            "description": "<p>非借款端登入</p>"
          },
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "403",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"403\"\n}",
          "type": "json"
        },
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "409",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"409\"\n}",
          "type": "json"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "name": "PostSubloanApply"
  },
  {
    "type": "post",
    "url": "/subloan/signing",
    "title": "借款方 產品轉換簽約",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "target_id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "person_image",
            "description": "<p>本人照</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "404",
            "description": "<p>此申請不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "405",
            "description": "<p>對此申請無權限</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "407",
            "description": "<p>目前狀態無法完成此動作</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "206",
            "description": "<p>人臉辨識不通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "904",
            "description": "<p>尚未申請產品轉換</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "207",
            "description": "<p>非借款端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "json"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "json"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "json"
        },
        {
          "title": "206",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"206\"\n}",
          "type": "json"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "name": "PostSubloanSigning"
  },
  {
    "type": "get",
    "url": "/target/applylist",
    "title": "出借方 申請紀錄列表",
    "group": "Target",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Investments ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>投標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>得標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>合約內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer_status",
            "description": "<p>債權轉讓狀態 0:無 1:已申請 2:移轉成功</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>標的金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.status",
            "description": "<p>標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "bank_account",
            "description": "<p>綁定金融帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.bank_account",
            "description": "<p>銀行帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "funds",
            "description": "<p>資金資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.total",
            "description": "<p>資金總額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.last_recharge_date",
            "description": "<p>最後一次匯入日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.frozen",
            "description": "<p>待交易餘額</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"contract\":\"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊\",\n\t\t\t\t\"status\":\"3\",\n\t\t\t\t\"transfer_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\t\"alias\":\"FA\"\n\t\t\t\t},\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": \"19\",\n\t\t\t\t\t\"target_no\": \"1804233189\",\n\t\t\t\t\t\"invested\": \"5000\",\n\t\t\t\t\t\"expire_time\": \"123456789\",\n\t\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\t\"status\": \"5\"\n\t\t\t\t}\n\t\t\t}\n\t\t\t],\n\t        \"bank_account\": {\n\t            \"bank_code\": \"013\",\n\t            \"branch_code\": \"1234\",\n\t            \"bank_account\": \"12345678910\"\n\t        },\n\t        \"virtual_account\": {\n\t            \"bank_code\": \"013\",\n\t            \"branch_code\": \"0154\",\n\t            \"bank_name\": \"國泰世華商業銀行\",\n\t            \"branch_name\": \"信義分行\",\n\t            \"virtual_account\": \"56639100000001\"\n\t        },\n\t        \"funds\": {\n\t            \"total\": 500,\n\t            \"last_recharge_date\": \"2018-05-03 19:15:42\",\n\t            \"frozen\": 0\n\t        }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "name": "GetTargetApplylist"
  },
  {
    "type": "get",
    "url": "/target/batch",
    "title": "出借方 智能出借",
    "group": "Target",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "budget",
            "description": "<p>預算金額</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "interest_rate_s",
            "description": "<p>利率區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "interest_rate_e",
            "description": "<p>利率區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "instalment_s",
            "description": "<p>期數區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "instalment_e",
            "description": "<p>期數區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "credit_level",
            "defaultValue": "all",
            "description": "<p>信用評等 全部：all 複選使用逗號隔開1,2,3,4,5,6,7,8</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "all",
              "0",
              "1"
            ],
            "optional": true,
            "field": "national",
            "defaultValue": "all",
            "description": "<p>信用評等 全部:all 私立:0 國立:1</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "all",
              "0",
              "1",
              "2"
            ],
            "optional": true,
            "field": "system",
            "defaultValue": "all",
            "description": "<p>學制 全部:all 0:大學 1:碩士 2:博士</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "all",
              "F",
              "M"
            ],
            "optional": true,
            "field": "gender",
            "defaultValue": "all",
            "description": "<p>性別 全部:all 女性:F 男性:M</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "batch_id",
            "description": "<p>智能出借ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_amount",
            "description": "<p>總金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_count",
            "description": "<p>總筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "XIRR",
            "description": "<p>平均年利率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "contract",
            "description": "<p>合約列表</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 70000,\n\t\t\t\"total_count\": 1,\n\t\t\t\"max_instalment\": \"12\",\n\t\t\t\"min_instalment\": \"12\",\n\t\t\t\"XIRR\": 10.47,\n\t\t\t\"batch_id\": 2,\n\t\t\t\"contract\": [\n\t\t\t\t\"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！\"\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "212",
            "description": "<p>未通過所需的驗證(Email)</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "name": "GetTargetBatch"
  },
  {
    "type": "get",
    "url": "/target/info/{ID}",
    "title": "出借方 取得標的資訊",
    "group": "Target",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>標的ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>標的號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_interest",
            "description": "<p>總利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>合約內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "certification",
            "description": "<p>借款人認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "user",
            "description": "<p>借款人基本資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.school_name",
            "description": "<p>學校名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>預計還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.instalment",
            "description": "<p>借款期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.date",
            "description": "<p>起始時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total_payment",
            "description": "<p>每月還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.leap_year",
            "description": "<p>是否為閏年</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.year_days",
            "description": "<p>本年日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.XIRR",
            "description": "<p>內部報酬率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.instalment",
            "description": "<p>第幾期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.repayment_date",
            "description": "<p>還款日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.days",
            "description": "<p>本期日數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total",
            "description": "<p>還款總計</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total.principal",
            "description": "<p>本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total.interest",
            "description": "<p>利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.total.total_payment",
            "description": "<p>加總</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\"user_id\":\"1\",\n\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\"credit_level\":\"4\",\n\t\t\t\"interest_rate\":\"9\",\n\t\t\t\"instalment\":\"3期\",\n\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\"contract\":\"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！\",\n\t\t\t\"remark\":\"\",\n\t\t\t\"delay\": \"0\",\n\t\t\t\"delay_days\": \"0\",\n\t\t\t\"expire_time\": \"1525449600\",\n\t\t\t\"invested\": \"50000\",\n\t\t\t\"status\":\"4\",\n\t\t\t\"sub_status\":\"0\",\n\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\"product\":{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t},\n\t        \"certification\": [\n          \t{\n          \t     \"id\": \"1\",\n          \t     \"name\": \"身分證認證\",\n          \t     \"description\": \"身分證認證\",\n          \t     \"alias\": \"id_card\",\n           \t    \"user_status\": \"1\"\n          \t},\n          \t{\n          \t    \"id\": \"2\",\n           \t    \"name\": \"學生證認證\",\n          \t    \"description\": \"學生證認證\",\n           \t   \"alias\": \"student\",\n           \t   \"user_status\": \"1\"\n          \t}\n          ],\n      \"user\": {\n         \"name\": \"陳XX\",\n          \"age\": 28,\n          \"school_name\": \"國立宜蘭大學\",\n          \"id_number\": \"G1231XXXXX\"\n      },\n      \"amortization_schedule\": {\n          \"amount\": \"12000\",\n          \"instalment\": \"6\",\n          \"rate\": \"9\",\n          \"date\": \"2018-04-17\",\n          \"total_payment\": 2053,\n          \"leap_year\": false,\n          \"year_days\": 365,\n          \"XIRR\": 9.39,\n          \"schedule\": {\n               \"1\": {\n                 \"instalment\": 1,\n                 \"repayment_date\": \"2018-06-10\",\n                 \"days\": 54,\n                 \"remaining_principal\": \"12000\",\n                 \"principal\": 1893,\n                 \"interest\": 160,\n                 \"total_payment\": 2053\n             },\n             \"2\": {\n                  \"instalment\": 2,\n                 \"repayment_date\": \"2018-07-10\",\n                 \"days\": 30,\n                  \"remaining_principal\": 10107,\n                  \"principal\": 1978,\n                  \"interest\": 75,\n                   \"total_payment\": 2053\n              },\n             \"3\": {\n                   \"instalment\": 3,\n                   \"repayment_date\": \"2018-08-10\",\n                   \"days\": 31,\n                   \"remaining_principal\": 8129,\n                  \"principal\": 1991,\n                  \"interest\": 62,\n                   \"total_payment\": 2053\n               }\n           },\n          \"total\": {\n               \"principal\": 12000,\n               \"interest\": 391,\n               \"total_payment\": 12391\n           }\n       }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "801",
            "description": "<p>標的不存在</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "801",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"801\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "name": "GetTargetInfoId"
  },
  {
    "type": "get",
    "url": "/target/list",
    "title": "出借方 取得標的列表",
    "group": "Target",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "credit_level",
              "instalment",
              "interest_rate"
            ],
            "optional": true,
            "field": "orderby",
            "defaultValue": "credit_level",
            "description": "<p>排序值</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "asc",
              "desc"
            ],
            "optional": true,
            "field": "sort",
            "defaultValue": "asc",
            "description": "<p>降序/升序</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Targets ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target_no",
            "description": "<p>標的號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t\t},\n\t\t\t\t\"credit_level\":\"4\",\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"loan_amount\":\"5000\",\n\t\t\t\t\"interest_rate\":\"12\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\"delay_days\": \"0\",\n\t\t\t\t\"expire_time\": \"1525449600\",\n\t\t\t\t\"invested\": \"50000\",\n\t\t\t\t\"status\":\"3\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "name": "GetTargetList"
  },
  {
    "type": "post",
    "url": "/target/apply",
    "title": "出借方 申請出借",
    "group": "Target",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "target_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "amount",
            "description": "<p>出借金額</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "801",
            "description": "<p>標的不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "802",
            "description": "<p>金額過高或過低</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "803",
            "description": "<p>已申請出借</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "804",
            "description": "<p>雙方不可同使用者</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "212",
            "description": "<p>未通過所需的驗證(Email)</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "801",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"801\"\n}",
          "type": "json"
        },
        {
          "title": "802",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"802\"\n}",
          "type": "json"
        },
        {
          "title": "803",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"803\"\n}",
          "type": "json"
        },
        {
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "json"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "name": "PostTargetApply"
  },
  {
    "type": "post",
    "url": "/target/batch/{batch_id}",
    "title": "出借方 智能出借確認",
    "group": "Target",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "batch_id",
            "description": "<p>智能出借ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_amount",
            "description": "<p>總金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_count",
            "description": "<p>總筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "XIRR",
            "description": "<p>平均年利率(%)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 50000,\n\t\t\t\"total_count\": 1,\n\t\t\t\"max_instalment\": \"12\",\n\t\t\t\"min_instalment\": \"12\",\n\t\t\t\"XIRR\": 10.47\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "811",
            "description": "<p>智能出借不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "812",
            "description": "<p>對此智能出借無權限</p>"
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "811",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"811\"\n}",
          "type": "json"
        },
        {
          "title": "812",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"812\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "name": "PostTargetBatchBatch_id"
  },
  {
    "type": "get",
    "url": "/transfer/applylist",
    "title": "出借方 債權申請紀錄列表",
    "group": "Transfer",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Transfer Investments ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>投標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>得標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>合約內容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款 9:流標 10:移轉成功</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "transfer",
            "description": "<p>債轉標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.id",
            "description": "<p>Transfer ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.amount",
            "description": "<p>借款轉讓金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.instalment",
            "description": "<p>借款剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transfer.expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>標的金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.status",
            "description": "<p>標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "bank_account",
            "description": "<p>綁定金融帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account.bank_account",
            "description": "<p>銀行帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>專屬虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_code",
            "description": "<p>分行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.bank_name",
            "description": "<p>銀行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.branch_name",
            "description": "<p>分行名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account.virtual_account",
            "description": "<p>虛擬帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "funds",
            "description": "<p>資金資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.total",
            "description": "<p>資金總額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.last_recharge_date",
            "description": "<p>最後一次匯入日</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "funds.frozen",
            "description": "<p>待交易餘額</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"contract\":\"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\t\"alias\":\"FA\"\n\t\t\t\t},\n\t\t\t\t\"transfer\": {\n\t\t\t\t\t\"id\": \"1\",\n\t\t\t\t\t\"amount\": \"1804233189\",\n\t\t\t\t\t\"instalment\": \"5000\",\n\t\t\t\t\t\"expire_time\": \"123456789\"\n\t\t\t\t},\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": \"19\",\n\t\t\t\t\t\"target_no\": \"1804233189\",\n\t\t\t\t\t\"invested\": \"5000\",\n\t\t\t\t\t\"expire_time\": \"123456789\",\n\t\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\t\"status\": \"5\"\n\t\t\t\t}\n\t\t\t}\n\t\t\t],\n\t        \"bank_account\": {\n\t            \"bank_code\": \"013\",\n\t            \"branch_code\": \"1234\",\n\t            \"bank_account\": \"12345678910\"\n\t        },\n\t        \"virtual_account\": {\n\t            \"bank_code\": \"013\",\n\t            \"branch_code\": \"0154\",\n\t            \"bank_name\": \"國泰世華商業銀行\",\n\t            \"branch_name\": \"信義分行\",\n\t            \"virtual_account\": \"56639100000001\"\n\t        },\n\t        \"funds\": {\n\t            \"total\": 500,\n\t            \"last_recharge_date\": \"2018-05-03 19:15:42\",\n\t            \"frozen\": 0\n\t        }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "name": "GetTransferApplylist"
  },
  {
    "type": "get",
    "url": "/transfer/batch",
    "title": "出借方 智能收購",
    "group": "Transfer",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "budget",
            "description": "<p>預算金額</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "delay",
            "defaultValue": "0",
            "description": "<p>逾期標的 0:正常標的 1:逾期標的 default:0</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "user_id",
            "description": "<p>指定使用者ID</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "interest_rate_s",
            "description": "<p>正常標的-利率區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "interest_rate_e",
            "description": "<p>正常標的-利率區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "instalment_s",
            "description": "<p>正常標的-剩餘期數區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": true,
            "field": "instalment_e",
            "description": "<p>正常標的-剩餘期數區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "credit_level",
            "defaultValue": "all",
            "description": "<p>逾期標的-信用評等 全部：all 複選使用逗號隔開6,7,8</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "batch_id",
            "description": "<p>智能收購ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_amount",
            "description": "<p>總金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_count",
            "description": "<p>總筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "XIRR",
            "description": "<p>平均內部報酬率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "debt_transfer_contract",
            "description": "<p>合約列表</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 70000,\n\t\t\t\"total_count\": 1,\n\t\t\t\"max_instalment\": \"12\",\n\t\t\t\"min_instalment\": \"12\",\n\t\t\t\"XIRR\": 10.47,\n\t\t\t\"batch_id\": 2,\n\t\t\t\"debt_transfer_contract\": [\n\t\t\t\t\"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！\"\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "212",
            "description": "<p>未通過所需的驗證(Email)</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "name": "GetTransferBatch"
  },
  {
    "type": "get",
    "url": "/transfer/info/{ID}",
    "title": "出借方 取得債權標的資訊",
    "group": "Transfer",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "ID",
            "description": "<p>投資ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Transfer ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>借款轉讓金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>借款剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "debt_transfer_contract",
            "description": "<p>債權轉讓合約</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>原案資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>標的號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.total_interest",
            "description": "<p>總利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "certification",
            "description": "<p>借款人認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "user",
            "description": "<p>借款人基本資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.school_name",
            "description": "<p>學校名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.id_number",
            "description": "<p>身分證字號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"instalment\":\"12\",\n\t\t\t\"debt_transfer_contract\":\"我是合約！！\",\n\t\t\t\"expire_time\":\"1527865369\",\n\t\t\t\"target\":{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\t\"credit_level\":\"4\",\n\t\t\t\t\"interest_rate\":\"9\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\"delay_days\": \"0\",\n\t\t\t\t\"status\":\"4\",\n\t\t\t\t\"sub_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t},\n\t\t\t\"product\":{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t},\n\t        \"certification\": [\n          \t{\n          \t     \"id\": \"1\",\n          \t     \"name\": \"身分證認證\",\n          \t     \"description\": \"身分證認證\",\n          \t     \"alias\": \"id_card\",\n           \t    \"user_status\": \"1\"\n          \t},\n          \t{\n          \t    \"id\": \"2\",\n           \t    \"name\": \"學生證認證\",\n          \t    \"description\": \"學生證認證\",\n           \t   \"alias\": \"student\",\n           \t   \"user_status\": \"1\"\n          \t}\n          ],\n      \"user\": {\n         \"name\": \"陳XX\",\n          \"age\": 28,\n          \"school_name\": \"國立宜蘭大學\",\n          \"id_number\": \"G1231XXXXX\"\n      }\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "801",
            "description": "<p>標的不存在</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "801",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"801\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "name": "GetTransferInfoId"
  },
  {
    "type": "get",
    "url": "/transfer/list",
    "title": "出借方 取得債權標的列表",
    "group": "Transfer",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "credit_level",
              "instalment",
              "interest_rate"
            ],
            "optional": true,
            "field": "orderby",
            "defaultValue": "credit_level",
            "description": "<p>排序值</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "asc",
              "desc"
            ],
            "optional": true,
            "field": "sort",
            "defaultValue": "asc",
            "description": "<p>降序/升序</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Transfer ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>借款轉讓金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>借款剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>產品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "target",
            "description": "<p>原案資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>案號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "ctarget.redit_level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>核可利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\": \"5000\",\n\t\t\t\t\"instalment\": \"12\",\n\t\t\t\t\"expire_time\": \"1527865369\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t\t},\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": \"2\",\n\t\t\t\t\t\"target_no\": \"1805281652\",\n\t\t\t\t\t\"credit_level\": \"4\",\n\t\t\t\t\t\"user_id\": \"2\",\n\t\t\t\t\t\"loan_amount\": \"5000\",\n\t\t\t\t\t\"interest_rate\": \"10\",\n\t\t\t\t\t\"instalment\": \"12期\",\n\t\t\t\t\t\"repayment\": \"等額本息\",\n\t\t\t\t\t\"delay\": \"1\",\n\t\t\t\t\t\"delay_days\": \"0\",\n\t\t\t\t\t\"status\": \"5\",\n\t\t\t\t\t\"sub_status\": \"3\",\n\t\t\t\t\t\"created_at\": \"1527490889\"\n\t\t\t\t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "name": "GetTransferList",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/transfer/apply",
    "title": "出借方 申請債權收購",
    "group": "Transfer",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "transfer_id",
            "description": "<p>投資ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "809",
            "description": "<p>債權轉讓標的不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "810",
            "description": "<p>已申請收購</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "804",
            "description": "<p>雙方不可同使用者</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "203",
            "description": "<p>金融帳號驗證尚未通過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "208",
            "description": "<p>未滿20歲</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "209",
            "description": "<p>未設置交易密碼</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "212",
            "description": "<p>未通過所需的驗證(Email)</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "809",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"809\"\n}",
          "type": "json"
        },
        {
          "title": "810",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"810\"\n}",
          "type": "json"
        },
        {
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "json"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "json"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "json"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "json"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "json"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "name": "PostTransferApply"
  },
  {
    "type": "post",
    "url": "/transfer/batch/{batch_id}",
    "title": "出借方 智能收購確認",
    "group": "Transfer",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "batch_id",
            "description": "<p>智能收購ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_amount",
            "description": "<p>總金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "total_count",
            "description": "<p>總筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "XIRR",
            "description": "<p>平均內部報酬率(%)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 50000,\n\t\t\t\"total_count\": 1,\n\t\t\t\"max_instalment\": \"12\",\n\t\t\t\"min_instalment\": \"12\",\n\t\t\t\"XIRR\": 10.47\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "811",
            "description": "<p>智能收購不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "812",
            "description": "<p>對此智能收購無權限</p>"
          },
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
            "field": "205",
            "description": "<p>非出借端登入</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "811",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"811\"\n}",
          "type": "json"
        },
        {
          "title": "812",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"812\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "name": "PostTransferBatchBatch_id"
  },
  {
    "type": "get",
    "url": "/user/chagetoken",
    "title": "會員 交換Token",
    "group": "User",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n  \t\"expiry_time\": \"1522673418\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "GetUserChagetoken",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/user/editpwphone",
    "title": "會員 發送驗證簡訊 （修改密碼、交易密碼）",
    "group": "User",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "307",
            "description": "<p>發送簡訊間隔過短</p>"
          },
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
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "GetUserEditpwphone"
  },
  {
    "type": "get",
    "url": "/user/info",
    "title": "會員 個人資訊",
    "group": "User",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "picture",
            "description": "<p>照片</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "nickname",
            "description": "<p>暱稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sex",
            "description": "<p>性別</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手機號碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "number",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "transaction_password",
            "description": "<p>是否設置交易密碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "my_promote_code",
            "description": "<p>推廣碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expiry_time",
            "description": "<p>token時效</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"id\": \"1\",\n  \t\"name\": \"\",\n  \t\"picture\": \"https://graph.facebook.com/2495004840516393/picture?type=large\",\n  \t\"nickname\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"investor_status\": \"1\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",\n  \t\"id_number\": null,\n  \t\"transaction_password\": true,\n  \t\"investor\": 1,  \n  \t\"created_at\": \"1522651818\",     \n  \t\"updated_at\": \"1522653939\",     \n  \t\"expiry_time\": \"1522675539\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "GetUserInfo",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/user/promote",
    "title": "會員 推薦有獎",
    "group": "User",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "promote_code",
            "description": "<p>推廣邀請碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "promote_url",
            "description": "<p>推廣連結</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "promote_qrcode",
            "description": "<p>推廣QR code</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "bonus_list",
            "description": "<p>獎勵列表(規劃中)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"promote_code\": \"D221BL0K\",\n  \t\"promote_url\": \"http://dev.influxfin.com?promote_code=D221BL0K\",\n  \t\"promote_qrcode\": \"http://chart.apis.google.com/chart?cht=qr&choe=UTF-8&chl=http%3A%2F%2Fdev.influxfin.com%3Fpromote_code%3DD221BL0K&chs=200x200\",\n  \t\"bonus_list\": []\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "GetUserPromote",
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
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/user/bind",
    "title": "會員 綁定第三方帳號",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "facebook",
              "instagram"
            ],
            "optional": false,
            "field": "type",
            "description": "<p>登入類型</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "access_token",
            "description": "<p>access_token</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "305",
            "description": "<p>access_token錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "306",
            "description": "<p>此種類型已綁定過了</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "308",
            "description": "<p>此FB帳號已綁定過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "309",
            "description": "<p>此IG帳號已綁定過</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "310",
            "description": "<p>此LINE帳號已綁定過</p>"
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
          "title": "305",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"305\"\n}",
          "type": "json"
        },
        {
          "title": "306",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"306\"\n}",
          "type": "json"
        },
        {
          "title": "308",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"308\"\n}",
          "type": "json"
        },
        {
          "title": "309",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"309\"\n}",
          "type": "json"
        },
        {
          "title": "310",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"310\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserBind"
  },
  {
    "type": "post",
    "url": "/user/contact",
    "title": "會員 投訴與建議",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>內容</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": true,
            "field": "image1",
            "description": "<p>附圖1</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": true,
            "field": "image2",
            "description": "<p>附圖2</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": true,
            "field": "image3",
            "description": "<p>附圖3</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserContact",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "200",
            "description": "<p>參數錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/user/credittest",
    "title": "會員 測一測",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "school",
            "description": "<p>學校名稱</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "0",
              "1",
              "2"
            ],
            "optional": true,
            "field": "system",
            "defaultValue": "0",
            "description": "<p>學制 0:大學 1:碩士 2:博士</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "department",
            "description": "<p>系所</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "grade",
            "description": "<p>年級</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>可貸款額度</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"amount\": 50000\n\t\t}\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserCredittest",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "200",
            "description": "<p>參數錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/user/editpw",
    "title": "會員 修改密碼",
    "group": "User",
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
            "size": "6..",
            "optional": false,
            "field": "new_password",
            "description": "<p>新密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>簡訊驗證碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "302",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "303",
            "description": "<p>驗證碼錯誤</p>"
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
            "description": "<p>密碼長度不足</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "json"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "json"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "json"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserEditpw"
  },
  {
    "type": "post",
    "url": "/user/edittpw",
    "title": "會員 設置交易密碼",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "size": "6..",
            "optional": false,
            "field": "new_password",
            "description": "<p>新交易密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>簡訊驗證碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "302",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "303",
            "description": "<p>驗證碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "311",
            "description": "<p>交易密碼長度不足</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          },
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
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "json"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "json"
        },
        {
          "title": "311",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"311\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        },
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserEdittpw"
  },
  {
    "type": "post",
    "url": "/user/forgotpw",
    "title": "會員 忘記密碼",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手機號碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>簡訊驗證碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "6..",
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
            "type": "json",
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
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "302",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "303",
            "description": "<p>驗證碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "312",
            "description": "<p>密碼長度不足</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "json"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "json"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserForgotpw"
  },
  {
    "type": "post",
    "url": "/user/login",
    "title": "會員 用戶登入",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手機號碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "6..",
            "optional": false,
            "field": "password",
            "description": "<p>密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "0",
              "1"
            ],
            "optional": true,
            "field": "investor",
            "defaultValue": "0",
            "description": "<p>1:投資端 0:借款端</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
            "type": "number",
            "optional": false,
            "field": "first_time",
            "description": "<p>是否首次本端</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expiry_time",
            "description": "<p>token時效</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\":1\t\t\n     }\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "302",
            "description": "<p>會員不存在</p>"
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
            "field": "200",
            "description": "<p>參數錯誤</p>"
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
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "json"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserLogin"
  },
  {
    "type": "post",
    "url": "/user/register",
    "title": "會員 註冊",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手機號碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "6..",
            "optional": false,
            "field": "password",
            "description": "<p>設定密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>簡訊驗證碼</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "0",
              "1"
            ],
            "optional": true,
            "field": "investor",
            "defaultValue": "0",
            "description": "<p>1:投資端 0:借款端</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "promote_code",
            "description": "<p>邀請碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
            "type": "number",
            "optional": false,
            "field": "first_time",
            "description": "<p>是否首次本端</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expiry_time",
            "description": "<p>token時效</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\":1\t\t\n     }\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "301",
            "description": "<p>會員已存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "303",
            "description": "<p>驗證碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "312",
            "description": "<p>密碼長度不足</p>"
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
            "field": "201",
            "description": "<p>新增時發生錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "301",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"301\"\n}",
          "type": "json"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "json"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserRegister"
  },
  {
    "type": "post",
    "url": "/user/registerphone",
    "title": "會員 發送驗證簡訊 (註冊)",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手機號碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "301",
            "description": "<p>會員已存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "307",
            "description": "<p>發送簡訊間隔過短</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "200",
            "description": "<p>參數錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "301",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"301\"\n}",
          "type": "json"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserRegisterphone"
  },
  {
    "type": "post",
    "url": "/user/smsloginphone",
    "title": "會員 發送驗證簡訊 （忘記密碼）",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>手機號碼</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "302",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "307",
            "description": "<p>發送簡訊間隔過短</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "200",
            "description": "<p>參數錯誤</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "json"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserSmsloginphone"
  },
  {
    "type": "post",
    "url": "/user/sociallogin",
    "title": "會員 第三方登入",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "facebook",
              "instagram"
            ],
            "optional": false,
            "field": "type",
            "description": "<p>登入類型</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "access_token",
            "description": "<p>access_token</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "0",
              "1"
            ],
            "optional": true,
            "field": "investor",
            "defaultValue": "0",
            "description": "<p>1:投資端 0:借款端</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
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
            "type": "number",
            "optional": false,
            "field": "first_time",
            "description": "<p>是否首次本端</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "expiry_time",
            "description": "<p>token時效</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\":1\t\t\n     }\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "302",
            "description": "<p>會員不存在</p>"
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
            "field": "200",
            "description": "<p>參數錯誤</p>"
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
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "json"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "json"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "json"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserSociallogin"
  }
] });
