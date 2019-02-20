define({ "api": [
  {
    "type": "get",
    "url": "/v2/agreement/info/:alias",
    "title": "協議 協議書",
    "version": "0.2.0",
    "name": "GetAgreementInfo",
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
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
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
          "content": "{\n\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"name\":\"用戶協議\",\n\t\t\t\"content\":\"用戶協議\",\n\t\t\t\"alias\":\"user\",\n\t\t}\n}",
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
            "field": "701",
            "description": "<p>此協議書不存在</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "701",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"701\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Agreement.php",
    "groupTitle": "Agreement",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/agreement/info/:alias"
      }
    ]
  },
  {
    "type": "get",
    "url": "/agreement/info/:alias",
    "title": "協議 協議書",
    "version": "0.1.0",
    "name": "GetAgreementInfo",
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
            "type": "Object",
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
            "field": "701",
            "description": "<p>此協議書不存在</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "701",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"701\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Agreement.php",
    "groupTitle": "Agreement",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/agreement/info/:alias"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/agreement/list",
    "title": "協議 協議列表",
    "version": "0.2.0",
    "name": "GetAgreementList",
    "group": "Agreement",
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
            "field": "name",
            "description": "<p>名稱</p>"
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
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"name\":\"用戶協議\",\n\t\t\t\t\"alias\":\"user\",\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"name\":\"投資人協議\",\n\t\t\t\t\"alias\":\"investor\",\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Agreement.php",
    "groupTitle": "Agreement",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/agreement/list"
      }
    ]
  },
  {
    "type": "get",
    "url": "/agreement/list",
    "title": "協議 協議列表",
    "version": "0.1.0",
    "name": "GetAgreementList",
    "group": "Agreement",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Agreement.php",
    "groupTitle": "Agreement",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/agreement/list"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/article/event",
    "title": "文章 最新活動",
    "version": "0.2.0",
    "name": "GetArticleEvent",
    "group": "Article",
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
            "field": "type",
            "description": "<p>類別</p>"
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
            "field": "image_url",
            "description": "<p>圖片連結</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>連結</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "rank",
            "description": "<p>排序（由大至小）</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最後更新時間</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"type\": \"event\",\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"url\": \"https://dev-api.influxfin.com\",\n\t\t\t\t\"title\": \"event\",\n\t\t\t\t\"content\": \"<p>event event</p>\",\n\t\t\t\t\"image_url\": \"https://d3imllwf4as09k.cloudfront.net/img/admin/post1550664784915.jpg\",\n\t\t\t\t\"rank\": 59,\n\t\t\t\t\"updated_at\": 1550667400\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"url\": \"https://dev-api.influxfin.com\",\n\t\t\t\t\"title\": \"event2\",\n\t\t\t\t\"content\": \"<p>Event</p>\",\n\t\t\t\t\"image_url\": \"\",\n\t\t\t\t\"rank\": 55,\n\t\t\t\t\"updated_at\": 1550667092\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Article.php",
    "groupTitle": "Article",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/article/event"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/article/news",
    "title": "文章 最新消息",
    "version": "0.2.0",
    "name": "GetArticleNews",
    "group": "Article",
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
            "field": "type",
            "description": "<p>類別</p>"
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
            "field": "image_url",
            "description": "<p>圖片連結</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>連結</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "rank",
            "description": "<p>排序（由大至小）</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "updated_at",
            "description": "<p>最後更新時間</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"type\": \"news\",\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"url\": \"https://dev-api.influxfin.com\",\n\t\t\t\t\"title\": \"News\",\n\t\t\t\t\"content\": \"<p>News News</p>\",\n\t\t\t\t\"image_url\": \"https://d3imllwf4as09k.cloudfront.net/img/admin/post1550664784915.jpg\",\n\t\t\t\t\"rank\": 59,\n\t\t\t\t\"updated_at\": 1550667400\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"url\": \"https://dev-api.influxfin.com\",\n\t\t\t\t\"title\": \"News2\",\n\t\t\t\t\"content\": \"<p>News</p>\",\n\t\t\t\t\"image_url\": \"\",\n\t\t\t\t\"rank\": 55,\n\t\t\t\t\"updated_at\": 1550667092\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Article.php",
    "groupTitle": "Article",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/article/news"
      }
    ]
  },
  {
    "type": "get",
    "url": "/certification/debitcard",
    "title": "認證 金融帳號認證資料",
    "version": "0.1.0",
    "name": "GetCertificationDebitcard",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/debitcard"
      }
    ]
  },
  {
    "type": "get",
    "url": "/certification/email",
    "title": "認證 常用電子信箱資料",
    "version": "0.1.0",
    "name": "GetCertificationEmail",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/email"
      }
    ]
  },
  {
    "type": "get",
    "url": "/certification/emergency",
    "title": "認證 緊急聯絡人資料",
    "version": "0.1.0",
    "name": "GetCertificationEmergency",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/emergency"
      }
    ]
  },
  {
    "type": "get",
    "url": "/certification/financial",
    "title": "認證 財務訊息認證資料",
    "version": "0.1.0",
    "name": "GetCertificationFinancial",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/financial"
      }
    ]
  },
  {
    "type": "get",
    "url": "/certification/idcard",
    "title": "認證 實名認證資料",
    "version": "0.1.0",
    "name": "GetCertificationIdcard",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/idcard"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/certification/:alias",
    "title": "認證 取得認證資料",
    "version": "0.2.0",
    "name": "GetCertificationIndex",
    "group": "Certification",
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
            "type": "String",
            "optional": false,
            "field": "alias",
            "description": "<p>認證代號</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"alias\": \"debitcard\",\n  \t\"certification_id\": \"3\", \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\",     \n  \t\"name\": \"toy\",\n  \t\"id_number\": \"G121111111\",\n  \t\"id_card_date\": \"1060707\",\n  \t\"id_card_place\": \"北市\",\n  \t\"birthday\": \"1020101\",\n  \t\"address\": \"全家就是我家\"\n  }\n}",
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
            "field": "501",
            "description": "<p>此驗證尚未啟用</p>"
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
            "field": "213",
            "description": "<p>非法人負責人</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/:alias"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/certification/list",
    "title": "認證 認證列表",
    "version": "0.2.0",
    "name": "GetCertificationList",
    "group": "Certification",
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
            "description": "<p>認證代號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/list"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "213",
            "description": "<p>非法人負責人</p>"
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/certification/list",
    "title": "認證 認證列表",
    "version": "0.1.0",
    "name": "GetCertificationList",
    "group": "Certification",
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
            "type": "Number",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/list"
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
  },
  {
    "type": "get",
    "url": "/certification/social",
    "title": "認證 社交認證資料",
    "version": "0.1.0",
    "name": "GetCertificationSocial",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/social"
      }
    ]
  },
  {
    "type": "get",
    "url": "/certification/student",
    "title": "認證 學生身份認證資料",
    "version": "0.1.0",
    "name": "GetCertificationStudent",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "503",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"503\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/student"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/debitcard",
    "title": "認證 金融帳號認證",
    "version": "0.2.0",
    "name": "PostCertificationDebitcard",
    "group": "Certification",
    "description": "<p>法人登入時，只有負責人情況下可操作。</p>",
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
            "type": "Number",
            "optional": false,
            "field": "front_image",
            "description": "<p>金融卡正面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "back_image",
            "description": "<p>金融卡背面照 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "213",
            "description": "<p>非法人負責人</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "506",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"506\"\n}",
          "type": "Object"
        },
        {
          "title": "507",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"507\"\n}",
          "type": "Object"
        },
        {
          "title": "508",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"508\"\n}",
          "type": "Object"
        },
        {
          "title": "509",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"509\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/debitcard"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/debitcard",
    "title": "認證 金融帳號認證",
    "version": "0.1.0",
    "name": "PostCertificationDebitcard",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "506",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"506\"\n}",
          "type": "Object"
        },
        {
          "title": "507",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"507\"\n}",
          "type": "Object"
        },
        {
          "title": "508",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"508\"\n}",
          "type": "Object"
        },
        {
          "title": "509",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"509\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/debitcard"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/diploma",
    "title": "認證 最高學歷認證",
    "version": "0.2.0",
    "name": "PostCertificationDiploma",
    "group": "Certification",
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
            "type": "Number",
            "optional": false,
            "field": "diploma_image",
            "description": "<p>畢業證書照 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "510",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"510\"\n}",
          "type": "Object"
        },
        {
          "title": "511",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"511\"\n}",
          "type": "Object"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/diploma"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/email",
    "title": "認證 常用電子信箱",
    "version": "0.2.0",
    "name": "PostCertificationEmail",
    "group": "Certification",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/email"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/email",
    "title": "認證 常用電子信箱",
    "version": "0.1.0",
    "name": "PostCertificationEmail",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/email"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/emergency",
    "title": "認證 緊急聯絡人",
    "version": "0.2.0",
    "name": "PostCertificationEmergency",
    "group": "Certification",
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
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "household_image",
            "description": "<p>戶口名簿 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/emergency"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/emergency",
    "title": "認證 緊急聯絡人",
    "version": "0.1.0",
    "name": "PostCertificationEmergency",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/emergency"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/financial",
    "title": "認證 財務訊息認證",
    "version": "0.2.0",
    "name": "PostCertificationFinancial",
    "group": "Certification",
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
            "type": "Number",
            "optional": true,
            "field": "creditcard_image",
            "description": "<p>信用卡帳單照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "passbook_image",
            "description": "<p>存摺內頁照 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/financial"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/financial",
    "title": "認證 財務訊息認證",
    "version": "0.1.0",
    "name": "PostCertificationFinancial",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/financial"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/idcard",
    "title": "認證 實名認證",
    "version": "0.2.0",
    "name": "PostCertificationIdcard",
    "group": "Certification",
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
            "type": "Number",
            "optional": false,
            "field": "front_image",
            "description": "<p>身分證正面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "back_image",
            "description": "<p>身分證背面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "person_image",
            "description": "<p>本人照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "healthcard_image",
            "description": "<p>健保卡照 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "504",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"504\"\n}",
          "type": "Object"
        },
        {
          "title": "505",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"505\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/idcard"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/idcard",
    "title": "認證 實名認證",
    "version": "0.1.0",
    "name": "PostCertificationIdcard",
    "group": "Certification",
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
            "size": "2..15",
            "optional": false,
            "field": "name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "10",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "504",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"504\"\n}",
          "type": "Object"
        },
        {
          "title": "505",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"505\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/idcard"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/investigation",
    "title": "認證 聯合徵信認證",
    "version": "0.2.0",
    "name": "PostCertificationInvestigation",
    "group": "Certification",
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
            "allowedValues": [
              "0",
              "1",
              "2"
            ],
            "optional": true,
            "field": "return_type",
            "defaultValue": "0",
            "description": "<p>回寄方式 0:不需寄回 1:Email</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "postal_image",
            "description": "<p>郵遞回單照 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/investigation"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/job",
    "title": "認證 工作認證",
    "version": "0.2.0",
    "name": "PostCertificationJob",
    "group": "Certification",
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
            "field": "tax_id",
            "description": "<p>公司統一編號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "company",
            "description": "<p>公司名稱</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "A-S"
            ],
            "optional": false,
            "field": "industry",
            "description": "<p>公司類型 <br>A：農、林、漁、牧業 <br>B：礦業及土石採取業 <br>C：製造業 <br>D：電力及燃氣供應業 <br>E：用水供應及污染整治業 <br>F：營建工程業 <br>G：批發及零售業 <br>H：運輸及倉儲業 <br>I：住宿及餐飲業 <br>J：出版、影音製作、傳播及資通訊服務業 <br>K：金融及保險業 <br>L：不動產業 <br>M：專業、科學及技術服務業 <br>N：支援服務業 <br>O：公共行政及國防；強制性社會安全 <br>P：教育業 <br>Q：醫療保健及社會工作服務業 <br>R：藝術、娛樂及休閒服務業 <br>S：其他服務業</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "0",
              "1",
              "2",
              "3",
              "4",
              "5",
              "6"
            ],
            "optional": false,
            "field": "employee",
            "defaultValue": "0",
            "description": "<p>企業規模\t <br>0：1~20（含） <br>1：20~50（含） <br>2：50~100（含） <br>3：100~500（含） <br>4：500~1000（含） <br>5：1000~5000（含） <br>6：5000以上</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "0",
              "1",
              "2",
              "3"
            ],
            "optional": false,
            "field": "position",
            "defaultValue": "0",
            "description": "<p>職位 <br>0：一般員工 <br>1：初級管理 <br>2：中級管理 <br>3：高級管理</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "0",
              "1"
            ],
            "optional": false,
            "field": "type",
            "defaultValue": "0",
            "description": "<p>職務性質 <br>0：外勤 <br>1：内勤</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "0",
              "1",
              "2",
              "3",
              "4"
            ],
            "optional": false,
            "field": "seniority",
            "defaultValue": "0",
            "description": "<p>畢業以來的工作期間 <br>0：三個月以内（含） <br>1：三個月至半年（含） <br>2：半年至一年（含） <br>3：一年至三年（含） <br>4：三年以上</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "0",
              "1",
              "2",
              "3",
              "4"
            ],
            "optional": false,
            "field": "job_seniority",
            "defaultValue": "0",
            "description": "<p>本公司工作期間 <br>0：三個月以内（含） <br>1：三個月至半年（含） <br>2：半年至一年（含） <br>3：一年至三年（含） <br>4：三年以上</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "salary",
            "description": "<p>月薪</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "business_image",
            "description": "<p>名片/工作證明 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "license_image",
            "description": "<p>專業證照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "labor_image",
            "description": "<p>勞健保卡 ( 圖片IDs 以逗號隔開，最多三個)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "passbook_image",
            "description": "<p>存摺內頁照 ( 圖片IDs 以逗號隔開，最多三個)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "auxiliary_image",
            "description": "<p>收入輔助證明 ( 圖片IDs 以逗號隔開，最多三個)</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/job"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/social",
    "title": "認證 社交認證",
    "version": "0.2.0",
    "name": "PostCertificationSocial",
    "group": "Certification",
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
            "allowedValues": [
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
            "description": "<p>Instagram AccessToken</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/social"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/social",
    "title": "認證 社交認證",
    "version": "0.1.0",
    "name": "PostCertificationSocial",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/social"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/student",
    "title": "認證 學生身份認證",
    "version": "0.2.0",
    "name": "PostCertificationStudent",
    "group": "Certification",
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
            "type": "Number",
            "optional": false,
            "field": "front_image",
            "description": "<p>學生證正面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "back_image",
            "description": "<p>學生證背面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "sip_account",
            "description": "<p>SIP帳號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "sip_password",
            "description": "<p>SIP密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "transcript_image",
            "description": "<p>成績單 ( 圖片ID )</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "501",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"501\"\n}",
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "510",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"510\"\n}",
          "type": "Object"
        },
        {
          "title": "511",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"511\"\n}",
          "type": "Object"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/student"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/student",
    "title": "認證 學生身份認證",
    "version": "0.1.0",
    "name": "PostCertificationStudent",
    "group": "Certification",
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
          "type": "Object"
        },
        {
          "title": "502",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"502\"\n}",
          "type": "Object"
        },
        {
          "title": "510",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"510\"\n}",
          "type": "Object"
        },
        {
          "title": "511",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"511\"\n}",
          "type": "Object"
        },
        {
          "title": "204",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"204\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/student"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/certification/verifyemail",
    "title": "認證 認證電子信箱(學生身份、常用電子信箱)",
    "version": "0.2.0",
    "name": "PostCertificationVerifyemail",
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/certification/verifyemail"
      }
    ]
  },
  {
    "type": "post",
    "url": "/certification/verifyemail",
    "title": "認證 認證電子信箱(學生身份、常用電子信箱)",
    "version": "0.1.0",
    "name": "PostCertificationVerifyemail",
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/certification/verifyemail"
      }
    ]
  },
  {
    "type": "delete",
    "url": "/v2/judicialperson/agent/:user_id",
    "title": "法人代理 刪除代理人",
    "version": "0.2.0",
    "name": "DeleteJudicialpersonAgent",
    "group": "Judicialperson",
    "description": "<p>只有負責人登入法人帳號情況下可操作。</p>",
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
            "field": "user_id",
            "description": "<p>代理人UserID</p>"
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
            "field": "302",
            "description": "<p>會員不存在</p>"
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
            "field": "213",
            "description": "<p>非法人負責人</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "217",
            "description": "<p>限法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        },
        {
          "title": "217",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"217\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/agent/:user_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/judicialperson/agent",
    "title": "法人代理 代理人名單",
    "version": "0.2.0",
    "name": "GetJudicialpersonAgent",
    "group": "Judicialperson",
    "description": "<p>只有負責人登入法人帳號情況下可操作。</p>",
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
            "field": "user_id",
            "description": "<p>代理人UserID</p>"
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
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>新增日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"user_id\":1,\n\t\t\t\t\"name\": \"曾志偉\",\n\t\t\t\t\"id_number\":\"A1234*****\",\n\t\t\t\t\"created_at\":1520421572\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/agent"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "213",
            "description": "<p>非法人負責人</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "217",
            "description": "<p>限法人帳號使用</p>"
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        },
        {
          "title": "217",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"217\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v2/judicialperson/cooperation",
    "title": "法人經銷 查詢經銷申請",
    "version": "0.2.0",
    "name": "GetJudicialpersonCooperation",
    "group": "Judicialperson",
    "description": "<p>只有負責人登入法人帳號情況下可操作。</p>",
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
            "type": "String",
            "optional": false,
            "field": "server_ip",
            "description": "<p>綁定伺服器IP</p>"
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
            "description": "<p>狀態 0:未開通 1:已開通 2:審核中</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"server_ip\": \"192.168.0.1\",\n\t\t\t\"remark\":\"\",\n\t\t\t\"status\": \"1\"\n\t\t}\n   }",
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
            "field": "315",
            "description": "<p>未申請過經銷商</p>"
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
            "field": "213",
            "description": "<p>非法人負責人</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "217",
            "description": "<p>限法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "315",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"315\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        },
        {
          "title": "217",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"217\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/cooperation"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/judicialperson/list",
    "title": "法人會員 已申請法人列表",
    "version": "0.2.0",
    "name": "GetJudicialpersonList",
    "group": "Judicialperson",
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
            "field": "company_type",
            "description": "<p>公司類型 1:獨資 2:合夥,3:有限公司 4:股份有限公司</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "company",
            "description": "<p>公司名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "tax_id",
            "description": "<p>公司統一編號</p>"
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
            "type": "Number",
            "optional": false,
            "field": "cooperation",
            "description": "<p>經銷商功能 0:未開通 1:已開通 2:審核中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:審核中 1:審核通過 2:審核失敗</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"company_type\":4,\n\t\t\t\t\"company\":\"普匯金融科技股份有限公司\",\n\t\t\t\t\"tax_id\":\"68566881\",\n\t\t\t\t\"remark\":\"盡快與您聯絡\",\n\t\t\t\t\"status\":1,\n\t\t\t\t\"created_at\":1520421572\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/list"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v2/judicialperson/agent",
    "title": "法人代理 新增代理人",
    "version": "0.2.0",
    "name": "PostJudicialpersonAgent",
    "group": "Judicialperson",
    "description": "<p>只有負責人登入法人帳號情況下可操作。</p>",
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
            "size": "10",
            "optional": false,
            "field": "id_number",
            "description": "<p>代理人身分證字號</p>"
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
            "field": "302",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "313",
            "description": "<p>代理人已存在</p>"
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
            "field": "213",
            "description": "<p>非法人負責人</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "217",
            "description": "<p>限法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
          "type": "Object"
        },
        {
          "title": "313",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"313\"\n}",
          "type": "Object"
        },
        {
          "title": "504",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"504\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        },
        {
          "title": "217",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"217\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/agent"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/judicialperson/apply",
    "title": "法人會員 申請法人身份",
    "version": "0.2.0",
    "name": "PostJudicialpersonApply",
    "group": "Judicialperson",
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
            "type": "Number",
            "allowedValues": [
              "1",
              "2",
              "3",
              "4"
            ],
            "optional": false,
            "field": "company_type",
            "description": "<p>公司類型 1:獨資 2:合夥,3:有限公司 4:股份有限公司</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "8",
            "optional": false,
            "field": "tax_id",
            "description": "<p>公司統一編號</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "0",
              "1"
            ],
            "optional": true,
            "field": "cooperation",
            "defaultValue": "0",
            "description": "<p>0:法人帳號 1:法人經銷商帳號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "server_ip",
            "description": "<p>綁定伺服器IP，多組時，以逗號分隔(經銷商必填)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "facade_image",
            "description": "<p>店門正面照(經銷商必填)( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "store_image",
            "description": "<p>店內正面照(經銷商必填)( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "front_image",
            "description": "<p>銀行流水帳正面(經銷商必填)( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "passbook_image",
            "description": "<p>銀行流水帳內頁(經銷商必填)( 圖片ID )</p>"
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
            "field": "202",
            "description": "<p>未通過所需的驗證(實名驗證)</p>"
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
            "field": "212",
            "description": "<p>未通過所需的驗證(Email)</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "214",
            "description": "<p>此公司已申請過</p>"
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
            "field": "213",
            "description": "<p>非法人負責人</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "214",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"214\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/judicialperson/cooperation",
    "title": "法人經銷 申請為經銷商",
    "version": "0.2.0",
    "name": "PostJudicialpersonCooperation",
    "group": "Judicialperson",
    "description": "<p>只有負責人登入法人帳號情況下可操作。</p>",
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
            "field": "server_ip",
            "description": "<p>綁定伺服器IP，多組時，以逗號分隔</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "facade_image",
            "description": "<p>店門正面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "store_image",
            "description": "<p>店內正面照 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "front_image",
            "description": "<p>銀行流水帳正面 ( 圖片ID )</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "passbook_image",
            "description": "<p>銀行流水帳內頁 ( 圖片ID )</p>"
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
            "field": "314",
            "description": "<p>已申請過經銷商</p>"
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
            "field": "213",
            "description": "<p>非法人負責人</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "217",
            "description": "<p>限法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "314",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"314\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "213",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"213\"\n}",
          "type": "Object"
        },
        {
          "title": "217",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"217\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/cooperation"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/judicialperson/login",
    "title": "法人會員 用戶登入",
    "version": "0.2.0",
    "name": "PostJudicialpersonLogin",
    "group": "Judicialperson",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "size": "8",
            "optional": false,
            "field": "tax_id",
            "description": "<p>公司統一編號</p>"
          },
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
            "type": "Number",
            "allowedValues": [
              "0",
              "1"
            ],
            "optional": true,
            "field": "investor",
            "defaultValue": "1",
            "description": "<p>1:投資端</p>"
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
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\": 1\t\t\n     }\n   }",
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
          "type": "Object"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Judicialperson.php",
    "groupTitle": "Judicialperson",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/judicialperson/login"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/notification/info/:id",
    "title": "消息 消息內容（已讀）",
    "version": "0.2.0",
    "name": "GetNotificationInfo",
    "group": "Notification",
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
            "description": "<p>Notification ID</p>"
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
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1:未讀 2:已讀</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\": 224,\n\t\t\t\"title\":\"用戶資料認證未通過\",\n\t\t\t\"content\":\"您好！ 您的資料認證未通過，請重新認證。\",\n\t\t\t\"status\": 1,\n\t\t\t\"created_at\": 1548133390\n\t\t}\n}",
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
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/v2/Notification.php",
    "groupTitle": "Notification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/notification/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/notification/info/:id",
    "title": "消息 消息內容（已讀）",
    "version": "0.1.0",
    "name": "GetNotificationInfo",
    "group": "Notification",
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
            "type": "Object",
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
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Notification.php",
    "groupTitle": "Notification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/notification/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/notification/list",
    "title": "消息 消息列表",
    "version": "0.2.0",
    "name": "GetNotificationList",
    "group": "Notification",
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
            "description": "<p>Notification ID</p>"
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
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>1:未讀 2:已讀</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>創建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\": 1,\n\t\t\t\t\"title\":\"用戶資料認證未通過\",\n\t\t\t\t\"content\":\"您好！ 您的資料認證未通過，請重新認證。\",\n\t\t\t\t\"status\": 1,\n\t\t\t\t\"created_at\":\"1519635711\"\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\": 241,\n\t\t\t\t\"title\": \"【會員】 交易密碼設置成功\",\n\t\t\t\t\"content\": \"您好！\\r\\n\\t\\t\\t\\t\\t您的交易密碼設置成功。\",\n\t\t\t\t\"status\": 1,\n\t\t\t\t\"created_at\": 1548303563\n\t\t\t},\n\t\t\t]\n\t\t}\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Notification.php",
    "groupTitle": "Notification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/notification/list"
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
  },
  {
    "type": "get",
    "url": "/notification/list",
    "title": "消息 消息列表",
    "version": "0.1.0",
    "name": "GetNotificationList",
    "group": "Notification",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Notification.php",
    "groupTitle": "Notification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/notification/list"
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
  },
  {
    "type": "get",
    "url": "/v2/notification/readall",
    "title": "消息 一鍵已讀",
    "version": "0.2.0",
    "name": "GetNotificationReadall",
    "group": "Notification",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\":\"SUCCESS\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Notification.php",
    "groupTitle": "Notification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/notification/readall"
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
  },
  {
    "type": "get",
    "url": "/notification/readall",
    "title": "消息 一鍵已讀",
    "version": "0.1.0",
    "name": "GetNotificationReadall",
    "group": "Notification",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\":\"SUCCESS\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Notification.php",
    "groupTitle": "Notification",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/notification/readall"
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
  },
  {
    "type": "get",
    "url": "/v2/product/applyinfo/:id",
    "title": "借款方 申請紀錄資訊",
    "version": "0.2.0",
    "name": "GetProductApplyinfo",
    "group": "Product",
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
    "description": "<p>預計還款計畫欄位只在待簽約時出現。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "platform_fee",
            "description": "<p>平台服務費</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>核可利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數 0:其他</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式 1:等額本息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
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
            "type": "Object",
            "optional": false,
            "field": "certification",
            "description": "<p>認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\"product_id\":\"1\",\n\t\t\t\"user_id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\"platform_fee\":\"1500\",\n\t\t\t\"interest_rate\":\"9\",\n\t\t\t\"instalment\":\"3\",\n\t\t\t\"repayment\":\"1\",\n\t\t\t\"reason\":\"\",\n\t\t\t\"remark\":\"\",\n\t\t\t\"delay\":\"0\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"sub_status\":\"0\",\n\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\"contract\":\"我是合約\",\n\t\t\t\"credit\":{\n\t\t\t\t\"level\":\"1\",\n\t\t\t\t\"points\":\"1985\",\n\t\t\t\t\"amount\":\"45000\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t},\n\t         \"certification\": [\n          \t{\n          \t     \"id\": \"1\",\n          \t     \"name\": \"身分證認證\",\n          \t     \"description\": \"身分證認證\",\n          \t     \"alias\": \"id_card\",\n           \t    \"user_status\": \"1\"\n          \t},\n          \t{\n          \t    \"id\": \"2\",\n           \t    \"name\": \"學生證認證\",\n          \t    \"description\": \"學生證認證\",\n           \t   \"alias\": \"student\",\n           \t   \"user_status\": \"1\"\n          \t}\n          ],\n      \"amortization_schedule\": {\n          \"amount\": \"12000\",\n          \"instalment\": \"6\",\n          \"rate\": \"9\",\n          \"date\": \"2018-04-17\",\n          \"total_payment\": 2053,\n          \"leap_year\": false,\n          \"year_days\": 365,\n          \"XIRR\": 0.0939,\n          \"schedule\": {\n               \"1\": {\n                 \"instalment\": 1,\n                 \"repayment_date\": \"2018-06-10\",\n                 \"days\": 54,\n                 \"remaining_principal\": \"12000\",\n                 \"principal\": 1893,\n                 \"interest\": 160,\n                 \"total_payment\": 2053\n             },\n             \"2\": {\n                  \"instalment\": 2,\n                 \"repayment_date\": \"2018-07-10\",\n                 \"days\": 30,\n                  \"remaining_principal\": 10107,\n                  \"principal\": 1978,\n                  \"interest\": 75,\n                   \"total_payment\": 2053\n              },\n             \"3\": {\n                   \"instalment\": 3,\n                   \"repayment_date\": \"2018-08-10\",\n                   \"days\": 31,\n                   \"remaining_principal\": 8129,\n                  \"principal\": 1991,\n                  \"interest\": 62,\n                   \"total_payment\": 2053\n               }\n           },\n          \"total\": {\n               \"principal\": 12000,\n               \"interest\": 391,\n               \"total_payment\": 12391\n           }\n       }\n\t\t}\n   }",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/applyinfo/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/product/applyinfo/:id",
    "title": "借款方 申請紀錄資訊",
    "version": "0.1.0",
    "name": "GetProductApplyinfo",
    "group": "Product",
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
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "product",
            "description": "<p>產品資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "certification",
            "description": "<p>認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/applyinfo/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/product/order",
    "title": "借款方 分期訂單列表",
    "version": "0.2.0",
    "name": "GetProductApplylist",
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
    "group": "Product",
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
            "field": "order_no",
            "description": "<p>訂單編號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "company",
            "description": "<p>經銷商名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merchant_order_no",
            "description": "<p>經銷商訂單編號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數 0:其他</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item_count",
            "description": "<p>商品數量</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"order_no\": \"29-2019013116565856678\",\n\t\t\t\t\"company\": \"普匯金融科技股份有限公司\",\n\t\t\t\t\"merchant_order_no\": \"toytoytoy123\",\n\t\t\t\t\"product_id\": 2,\n\t\t\t\t\"total\": 20619,\n\t\t\t\t\"instalment\": 3,\n\t\t\t\t\"item_name\": [\n\t\t\t\t\t\"小雞\",\n\t\t\t\t\t\"'丫丫'\"\n\t\t\t\t],\n\t\t\t\t\"item_count\": [\n\t\t\t\t\t1,\n\t\t\t\t\t2\n\t\t\t\t],\n\t\t\t\t\"created_at\": 1548925018\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/order"
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
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v2/product/applylist",
    "title": "借款方 申請紀錄列表",
    "version": "0.2.0",
    "name": "GetProductApplylist",
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
    "group": "Product",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "platform_fee",
            "description": "<p>平台服務費</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數 0:其他</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式 1:等額本息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\": 5,\n\t\t\t\t\"target_no\": \"STN2019010484186\",\n\t\t\t\t\"product_id\": 1,\n\t\t\t\t\"user_id\": 1,\n\t\t\t\t\"amount\": 5000,\n\t\t\t\t\"loan_amount\": 0,\n\t\t\t\t\"platform_fee\": 500,\n\t\t\t\t\"interest_rate\": 0,\n\t\t\t\t\"instalment\": 3,\n\t\t\t\t\"repayment\": 1,\n\t\t\t\t\"reason\": \"\",\n\t\t\t\t\"remark\": \"系統自動取消\",\n\t\t\t\t\"delay\": 0,\n\t\t\t\t\"status\": 9,\n\t\t\t\t\"sub_status\": 0,\n\t\t\t\t\"created_at\": 1546591486\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/applylist"
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
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/product/applylist",
    "title": "借款方 申請紀錄列表",
    "version": "0.1.0",
    "name": "GetProductApplylist",
    "group": "Product",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/applylist"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v2/product/cancel/:id",
    "title": "借款方 取消申請",
    "version": "0.2.0",
    "name": "GetProductCancel",
    "group": "Product",
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
            "type": "Number",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/cancel/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/product/cancel/:id",
    "title": "借款方 取消申請",
    "version": "0.1.0",
    "name": "GetProductCancel",
    "group": "Product",
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
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/cancel/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/product/info/:id",
    "title": "借款方 取得產品資訊",
    "version": "0.2.0",
    "name": "GetProductInfo",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
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
            "field": "type",
            "description": "<p>類型 1:信用貸款 2:分期付款</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "identity",
            "description": "<p>身份 1:學生 2:社會新鮮人</p>"
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>可選期數 0:其他</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "repayment",
            "description": "<p>可選還款方式 1:等額本息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\": \"SUCCESS\",\n\t\t\"data\": {\n\t\t\t\"id\": 1,\n\t\t\t\"type\": 1,\n\t\t\t\"identity\": 1,\n\t\t\t\"name\": \"學生貸\",\n\t\t\t\"description\": \"\\r\\n普匯學生貸\\r\\n計畫留學、創業或者實現更多理想嗎？\\r\\n需要資金卻無法向銀行聲請借款嗎？\\r\\n普匯陪你一起實現夢想\",\n\t\t\t\"loan_range_s\": 5000,\n\t\t\t\"loan_range_e\": 120000,\n\t\t\t\"interest_rate_s\": 5,\n\t\t\t\"interest_rate_e\": 20,\n\t\t\t\"charge_platform\": 3,\n\t\t\t\"charge_platform_min\": 500,\n\t\t\t\"instalment\": [\n\t\t\t\t3,\n\t\t\t\t6,\n\t\t\t\t12,\n\t\t\t\t18,\n\t\t\t\t24\n\t\t\t],\n\t\t\t\"repayment\": [\n\t\t\t\t1\n\t\t\t]\n\t\t}\n}",
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
            "field": "401",
            "description": "<p>產品不存在</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/product/info/:id",
    "title": "借款方 取得產品資訊",
    "version": "0.1.0",
    "name": "GetProductInfo",
    "group": "Product",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>可申請期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "field": "401",
            "description": "<p>產品不存在</p>"
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
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/product/list",
    "title": "借款方 取得產品列表",
    "version": "0.2.0",
    "name": "GetProductList",
    "group": "Product",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": true,
            "field": "request_token",
            "description": "<p>登入後取得的 Request Token</p>"
          }
        ]
      }
    },
    "description": "<p>登入狀態下，若已申請產品且申請狀態為未簽約，則提供申請資訊欄位及認證完成資訊。</p>",
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
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "type",
            "description": "<p>類型 1:信用貸款 2:分期付款</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "identity",
            "description": "<p>身份 1:學生 2:社會新鮮人</p>"
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>可選期數 0:其他</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "repayment",
            "description": "<p>可選還款方式 1:等額本息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_range_s",
            "description": "<p>最低借款額度(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_range_e",
            "description": "<p>最高借款額度(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_s",
            "description": "<p>年利率下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_e",
            "description": "<p>年利率上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "charge_platform",
            "description": "<p>平台服務費(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "charge_platform_min",
            "description": "<p>平台最低服務費(元)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>申請資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "certification",
            "description": "<p>認證完成資訊</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":1,\n\t\t\t\t\"type\":1,\n\t\t\t\t\"identity\":1,\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"loan_range_s\":12222,\n\t\t\t\t\"loan_range_e\":14333333,\n\t\t\t\t\"interest_rate_s\":12,\n\t\t\t\t\"interest_rate_e\":14,\n\t\t\t\t\"charge_platform\":0,\n\t\t\t\t\"charge_platform_min\":0,\n\t\t\t\t\"instalment\": [\n\t\t\t\t\t3,\n\t\t\t\t    6,\n\t\t\t\t    12,\n\t\t\t\t    18,\n\t\t\t\t    24\n\t\t\t\t  ]\n\t\t\t\t\"repayment\": [\n\t\t\t\t\t1\n\t\t\t\t  ],\n\t\t\t\t\"target\":{\n\t\t\t\t\t\"id\":1,\n\t\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\t\"amount\":5000,\n\t\t\t\t\t\"loan_amount\":0,\n\t\t\t\t\t\"status\":0,\n\t\t\t\t\t\"instalment\":3,\n\t\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t\t},\n\t\t\t\t\"certification\":[\n\t\t\t\t\t{\n\t\t\t\t\t\t\"id\":1,\n\t\t\t\t\t\t\"name\": \"實名認證\",\n\t\t\t\t\t\t\"description\":\"實名認證\",\n\t\t\t\t\t\t\"alias\":\"id_card\",\n\t\t\t\t\t\t\"user_status\":1\n\t\t\t\t\t},\n\t\t\t\t\t{\n\t\t\t\t\t\t\"id\":2,\n\t\t\t\t\t\t\"name\": \"學生身份認證\",\n\t\t\t\t\t\t\"description\":\"學生身份認證\",\n\t\t\t\t\t\t\"alias\":\"student\",\n\t\t\t\t\t\t\"user_status\":1\n\t\t\t\t\t}\n\t\t\t\t]\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/list"
      }
    ]
  },
  {
    "type": "get",
    "url": "/product/list",
    "title": "借款方 取得產品列表",
    "version": "0.1.0",
    "name": "GetProductList",
    "group": "Product",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": true,
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>可申請期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>申請資訊（未簽約）</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/list"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/product/apply",
    "title": "借款方 申請借款",
    "version": "0.2.0",
    "name": "PostProductApply",
    "group": "Product",
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
    "description": "<p>此API只支援信用貸款類型產品。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>申請期數</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "0..128",
            "optional": true,
            "field": "reason",
            "description": "<p>借款原因</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "0..16",
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
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target_id",
            "description": "<p>Targets ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"target_id\": 1\n\t\t}\n}",
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
            "field": "410",
            "description": "<p>產品類型錯誤</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "Object"
        },
        {
          "title": "402",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"402\"\n}",
          "type": "Object"
        },
        {
          "title": "403",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"403\"\n}",
          "type": "Object"
        },
        {
          "title": "408",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"408\"\n}",
          "type": "Object"
        },
        {
          "title": "410",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"410\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/product/apply",
    "title": "借款方 申請產品",
    "version": "0.1.0",
    "name": "PostProductApply",
    "group": "Product",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "402",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"402\"\n}",
          "type": "Object"
        },
        {
          "title": "403",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"403\"\n}",
          "type": "Object"
        },
        {
          "title": "408",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"408\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/product/order",
    "title": "借款方 申請分期",
    "version": "0.2.0",
    "name": "PostProductOrder",
    "group": "Product",
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
            "field": "order_no",
            "description": "<p>訂單編號</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/order"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/product/signing",
    "title": "借款方 申請簽約",
    "version": "0.2.0",
    "name": "PostProductSigning",
    "group": "Product",
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
    "description": "<p>此API只支援信用貸款類型產品。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
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
            "field": "410",
            "description": "<p>產品類型錯誤</p>"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
          "type": "Object"
        },
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "410",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"410\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "206",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"206\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/product/signing"
      }
    ]
  },
  {
    "type": "post",
    "url": "/product/signing",
    "title": "借款方 申請簽約",
    "version": "0.1.0",
    "name": "PostProductSigning",
    "group": "Product",
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
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "206",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"206\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/product/signing"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/recoveries/dashboard",
    "title": "出借方 我的帳戶",
    "version": "0.2.0",
    "name": "GetRecoveriesDashboard",
    "group": "Recoveries",
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
            "field": "payable",
            "description": "<p>待匯款</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable.principal",
            "description": "<p>應收本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable.interest",
            "description": "<p>應收利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable.delay_interest",
            "description": "<p>應收延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "income",
            "description": "<p>收入</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.interest",
            "description": "<p>已收利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.delay_interest",
            "description": "<p>已收延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.other",
            "description": "<p>已收補貼</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "funds",
            "description": "<p>資金資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "funds.frozen",
            "description": "<p>待交易餘額</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"payable\": \"50000\",\n\t\t\t\"accounts_receivable\": {\n\t\t\t\t\"principal\": 40000,\n\t\t\t\t\"interest\": 1280,\n\t\t\t\t\"delay_interest\": 0\n\t\t\t},\n\t\t\t\"income\": {\n\t\t\t\t\"interest\": 0,\n\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\"other\": 0\n\t\t\t},\n\t\t\t\"funds\": {\n\t\t\t\t\"total\": 960000,\n\t\t\t\t\"last_recharge_date\": \"2019-01-14 14:12:10\",\n\t\t\t\t\"frozen\": 0\n\t\t\t},\n\t\t\t\"bank_account\": {\n\t\t\t\t\"bank_code\": \"004\",\n\t\t\t\t\"branch_code\": \"0037\",\n\t\t\t\t\"bank_account\": \"123123123132\"\n\t\t\t},\n\t\t\t\"virtual_account\": {\n\t\t\t\t\"bank_code\": \"013\",\n\t\t\t\t\"branch_code\": \"0154\",\n\t\t\t\t\"bank_name\": \"國泰世華商業銀行\",\n\t\t\t\t\"branch_name\": \"信義分行\",\n\t\t\t\t\"virtual_account\": \"56639164278638\"\n\t\t\t}\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/dashboard"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/recoveries/dashboard",
    "title": "出借方 我的帳戶",
    "version": "0.1.0",
    "name": "GetRecoveriesDashboard",
    "group": "Recoveries",
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
            "type": "Object",
            "optional": false,
            "field": "principal_level",
            "description": "<p>標的等級應收帳款 1~5:正常 6:觀察 7:次級 8:不良</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/dashboard"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v2/recoveries/finish",
    "title": "出借方 已結案債權列表",
    "version": "0.2.0",
    "name": "GetRecoveriesFinish",
    "group": "Recoveries",
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
            "description": "<p>債權金額</p>"
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
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>標的案號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>標的金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_date",
            "description": "<p>放款日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "income",
            "description": "<p>收入</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.principal",
            "description": "<p>已收本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.interest",
            "description": "<p>已收利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.delay_interest",
            "description": "<p>已收延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.other",
            "description": "<p>已收補貼</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "income.transfer",
            "description": "<p>債轉價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "invest",
            "description": "<p>投資</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "invest.start_date",
            "description": "<p>開始投資日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "invest.end_date",
            "description": "<p>結束投資日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "invest.amount",
            "description": "<p>投資金額</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\":\"50000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"status\":\"3\",\n\t\t\t\t\"transfer_status\":\"0\",\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": 9,\n\t\t\t\t\t\"target_no\": \"STN2019011414213\",\n\t\t\t\t\t\"product_id\": 1,\n\t\t\t\t\t\"user_id\": 19,\n\t\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\t\"credit_level\": 3,\n\t\t\t\t\t\"interest_rate\": 7,\n\t\t\t\t\t\"instalment\": 3,\n\t\t\t\t\t\"repayment\": 1,\n\t\t\t\t\t\"delay\": 0,\n\t\t\t\t\t\"delay_days\": 0,\n\t\t\t\t\t\"loan_date\": \"2019-01-14\",\n\t\t\t\t\t\"status\": 5,\n\t\t\t\t\t\"sub_status\": 0\n\t\t\t\t},\n\t\t\t\t\"income\": {\n\t\t\t\t\t\"principal\": 0,\n\t\t\t\t\t\"interest\": 0,\n\t\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\t\"other\": 0,\n\t\t\t\t\t\"transfer\": \"5003\"\n\t\t\t\t},\n\t\t\t\t\"invest\": {\n\t\t\t\t\t\"start_date\": \"2019-01-05\",\n\t\t\t\t\t\"end_date\": \"2019-01-17\",\n\t\t\t\t\t\"amount\": \"5000\"\n\t\t\t\t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/finish"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v2/recoveries/info/:id",
    "title": "出借方 已出借資訊",
    "version": "0.2.0",
    "name": "GetRecoveriesInfo",
    "group": "Recoveries",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "description": "<p>Investments ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>出借金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer_status",
            "description": "<p>債權轉讓狀態 0:無 1:已申請 2:移轉成功</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
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
            "type": "Object",
            "optional": false,
            "field": "transfer",
            "description": "<p>債轉資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.amount",
            "description": "<p>債權轉讓價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.transfer_fee",
            "description": "<p>債權轉讓手續費</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.interest",
            "description": "<p>已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.delay_interest",
            "description": "<p>已發生延滯利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "transfer.bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.combination",
            "description": "<p>Combination ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.accounts_receivable",
            "description": "<p>應收帳款</p>"
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
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "target.delay",
            "description": "<p>狀態 0:無 1:逾期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_date",
            "description": "<p>放款日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target.user",
            "description": "<p>借款人基本資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.sex",
            "description": "<p>性別 F/M</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.company_name",
            "description": "<p>單位名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "field": "amortization_schedule.list",
            "description": "<p>回款計畫</p>"
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
            "field": "amortization_schedule.list.days",
            "description": "<p>本期日數</p>"
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n   \t\"data\": {\n   \t\t\"id\": 10,\n   \t\t\"loan_amount\": 5000,\n   \t\t\"status\": 3,\n   \t\t\"transfer_status\": 0,\n   \t\t\"created_at\": 1547446446,\n   \t\t\"contract\": \"借貸契約\",\n   \t\t\"transfer\": {\n   \t\t\t\"amount\": 5120,\n   \t\t\t\"transfer_fee\": 25,\n   \t\t\t\"principal\": 5000,\n   \t\t\t\"interest\": 10,\n   \t\t\t\"delay_interest\": 0,\n   \t\t\t\"bargain_rate\": 2.2,\n   \t\t\t\"instalment\": 18,\n   \t\t\t\"accounts_receivable\": 5398,\n   \t\t\t\"transfer_at\": \"2019-01-21\",\n   \t\t\t\"contract\": \"應收帳款債權買賣\"\n   \t\t},\n   \t\t\"target\": {\n   \t\t\t\"id\": 15,\n   \t\t\t\"target_no\": \"STN2019011452727\",\n   \t\t\t\"product_id\": 1,\n   \t\t\t\"user_id\": 19,\n   \t\t\t\"user\": {\n   \t\t\t\t\"name\": \"你**\",\n   \t\t\t\t\"id_number\": \"A1085*****\",\n   \t\t\t\t\"sex\": \"M\",\n   \t\t\t\t\"age\": 30,\n   \t\t\t\t\"company_name\": \"國立政治大學\"\n   \t\t\t},\n   \t\t\t\"loan_amount\": 5000,\n   \t\t\t\"credit_level\": 3,\n   \t\t\t\"interest_rate\": 8,\n   \t\t\t\"reason\": \"\",\n   \t\t\t\"remark\": \"\",\n   \t\t\t\"instalment\": 12,\n   \t\t\t\"repayment\": 1,\n   \t\t\t\"delay\": 0,\n   \t\t\t\"delay_days\": 0,\n   \t\t\t\"loan_date\": \"2019-01-14\",\n   \t\t\t\"status\": 5,\n   \t\t\t\"sub_status\": 0,\n   \t\t\t\"created_at\": 1547445312\n   \t\t},\n   \t\t\"amortization_schedule\": {\n   \t\t\t\"amount\": 5000,\n   \t\t\t\"instalment\": 12,\n   \t\t\t\"rate\": 8,\n   \t\t\t\"total_payment\": 5249,\n   \t\t\t\"XIRR\": 8.28,\n   \t\t\t\"date\": \"2019-01-14\",\n   \t\t\t\"remaining_principal\": 5000,\n   \t\t\t\"list\": {\n   \t\t\t\t\"1\": {\n   \t\t\t\t\t\"instalment\": \"1\",\n   \t\t\t\t\t\"total_payment\": 435,\n   \t\t\t\t\t\"repayment\": 0,\n   \t\t\t\t\t\"interest\": 60,\n   \t\t\t\t\t\"principal\": 375,\n   \t\t\t\t\t\"delay_interest\": 0,\n   \t\t\t\t\t\"days\": 55,\n   \t\t\t\t\t\"remaining_principal\": \"5000\",\n   \t\t\t\t\t\"repayment_date\": \"2019-03-10\",\n   \t\t\t\t\t\"ar_fees\": 4\n   \t\t\t\t},\n   \t\t\t\t\"2\": {\n   \t\t\t\t\t\"instalment\": \"2\",\n   \t\t\t\t\t\"total_payment\": 435,\n   \t\t\t\t\t\"repayment\": 0,\n   \t\t\t\t\t\"interest\": 31,\n   \t\t\t\t\t\"principal\": 404,\n   \t\t\t\t\t\"delay_interest\": 0,\n   \t\t\t\t\t\"days\": 31,\n   \t\t\t\t\t\"remaining_principal\": 4625,\n   \t\t\t\t\t\"repayment_date\": \"2019-04-10\",\n   \t\t\t\t\t\"ar_fees\": 4\n   \t\t\t\t},\n   \t\t\t\t\"3\": {\n   \t\t\t\t\t\"instalment\": \"3\",\n   \t\t\t\t\t\"total_payment\": 435,\n   \t\t\t\t\t\"repayment\": 0,\n   \t\t\t\t\t\"interest\": 28,\n   \t\t\t\t\t\"principal\": 407,\n   \t\t\t\t\t\"delay_interest\": 0,\n   \t\t\t\t\t\"days\": 30,\n   \t\t\t\t\t\"remaining_principal\": 4221,\n   \t\t\t\t\t\"repayment_date\": \"2019-05-10\",\n   \t\t\t\t\t\"ar_fees\": 4\n   \t\t\t\t}\n   \t\t\t}\n   \t\t}\n   \t}\n   }",
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
          "type": "Object"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/recoveries/info/:id",
    "title": "出借方 已出借資訊",
    "version": "0.1.0",
    "name": "GetRecoveriesInfo",
    "group": "Recoveries",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/recoveries/list",
    "title": "出借方 還款中債權列表",
    "version": "0.2.0",
    "name": "GetRecoveriesList",
    "group": "Recoveries",
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
            "description": "<p>債權金額</p>"
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
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>標的案號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>標的金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.loan_date",
            "description": "<p>放款日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "next_repayment",
            "description": "<p>最近一期還款</p>"
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
            "type": "Object",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable.principal",
            "description": "<p>應收本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable.interest",
            "description": "<p>應收利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable.delay_interest",
            "description": "<p>應收延滯息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\":\"50000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"status\":\"3\",\n\t\t\t\t\"transfer_status\":\"0\",\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": 9,\n\t\t\t\t\t\"target_no\": \"STN2019011414213\",\n\t\t\t\t\t\"product_id\": 1,\n\t\t\t\t\t\"user_id\": 19,\n\t\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\t\"credit_level\": 3,\n\t\t\t\t\t\"interest_rate\": 7,\n\t\t\t\t\t\"instalment\": 3,\n\t\t\t\t\t\"repayment\": 1,\n\t\t\t\t\t\"delay\": 0,\n\t\t\t\t\t\"delay_days\": 0,\n\t\t\t\t\t\"loan_date\": \"2019-01-14\",\n\t\t\t\t\t\"status\": 5,\n\t\t\t\t\t\"sub_status\": 0\n\t\t\t\t},\n\t\t\t\t\"next_repayment\": {\n\t\t\t\t\t\"date\": \"2019-03-10\",\n\t\t\t\t\t\"instalment\": 1,\n\t\t\t\t\t\"amount\": 1687\n\t\t\t\t},\n\t\t\t\t\"accounts_receivable\": {\n\t\t\t\t\t\"principal\": 5000,\n\t\t\t\t\t\"interest\": 83,\n\t\t\t\t\t\"delay_interest\": 0\n\t\t\t\t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/list"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/recoveries/list",
    "title": "出借方 已出借列表",
    "version": "0.1.0",
    "name": "GetRecoveriesList",
    "group": "Recoveries",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/list"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v2/recoveries/passbook",
    "title": "出借方 虛擬帳戶明細",
    "version": "0.2.0",
    "name": "GetRecoveriesPassbook",
    "group": "Recoveries",
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
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>入帳時間</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t\t{\n\t\t\t\t\t\"amount\": 1000000,\n\t\t\t\t\t\"bank_amount\": 1000000,\n\t\t\t\t\t\"remark\": \"平台代收\",\n\t\t\t\t\t\"tx_datetime\": \"2019-01-14 14:12:10\",\n\t\t\t\t\t\"created_at\": 1547446336\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"amount\": -5000,\n\t\t\t\t\t\"bank_amount\": 995000,\n\t\t\t\t\t\"remark\": \"出借款\",\n\t\t\t\t\t\"tx_datetime\": \"2019-01-14 14:13:00\",\n\t\t\t\t\t\"created_at\": 1547446380\n\t\t\t\t}\n\t\t\t]\n\t\t}\n   }",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/passbook"
      }
    ]
  },
  {
    "type": "get",
    "url": "/recoveries/passbook",
    "title": "出借方 虛擬帳戶明細",
    "version": "0.1.0",
    "name": "GetRecoveriesPassbook",
    "group": "Recoveries",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/passbook"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/recoveries/pretransfer",
    "title": "出借方 我要轉讓",
    "version": "0.2.0",
    "name": "PostRecoveriesPretransfer",
    "group": "Recoveries",
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
            "field": "ids",
            "description": "<p>Investments IDs ex: 1,3,10,21</p>"
          },
          {
            "group": "Parameter",
            "type": "Float",
            "size": "-20.0~20.0",
            "optional": true,
            "field": "bargain_rate",
            "defaultValue": "0",
            "description": "<p>增減價比率(%)</p>"
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
            "field": "count",
            "description": "<p>筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>轉讓價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest",
            "description": "<p>已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_interest",
            "description": "<p>已發生延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "fee",
            "description": "<p>預計轉讓手續費</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小剩餘期數</p>"
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
            "type": "Float",
            "optional": false,
            "field": "bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>平均年表利率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "contract",
            "description": "<p>轉讓合約(多份)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \t\"data\": {\n          \"count\": 4,\n          \"amount\": 15015,\n          \"principal\": 15000,\n          \"interest\": 15,\n          \"delay_interest\": 0,\n          \"fee\": 75,\n          \"max_instalment\": 12,\n          \"min_instalment\": 3,\n          \"settlement_date\": \"2019-01-19\",\n          \"bargain_rate\": 5.1,\n          \"interest_rate\": 8.38,\n          \"accounts_receivable\": 15477,\n          \"contract\": [\n          \t\"我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約\",\n          \t\"我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約\"\n          ]\n      }\n}",
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
            "field": "813",
            "description": "<p>價金過高</p>"
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
          "type": "Object"
        },
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "Object"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "Object"
        },
        {
          "title": "808",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"808\"\n}",
          "type": "Object"
        },
        {
          "title": "813",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"813\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/pretransfer"
      }
    ]
  },
  {
    "type": "post",
    "url": "/recoveries/pretransfer",
    "title": "出借方 我要轉讓",
    "version": "0.1.0",
    "name": "PostRecoveriesPretransfer",
    "group": "Recoveries",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "Object"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "Object"
        },
        {
          "title": "808",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"808\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/pretransfer"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/recoveries/transfer",
    "title": "出借方 轉讓申請",
    "version": "0.2.0",
    "name": "PostRecoveriesTransfer",
    "group": "Recoveries",
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
            "field": "ids",
            "description": "<p>Investments IDs (複選使用逗號隔開1,3,10,21)</p>"
          },
          {
            "group": "Parameter",
            "type": "Float",
            "size": "-20..20",
            "optional": true,
            "field": "bargain_rate",
            "defaultValue": "0",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "0,1",
            "optional": true,
            "field": "combination",
            "defaultValue": "0",
            "description": "<p>是否整包</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "4,12",
            "optional": true,
            "field": "password",
            "description": "<p>設置密碼</p>"
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
            "field": "813",
            "description": "<p>價金過高</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "814",
            "description": "<p>整包狀態不一致</p>"
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
          "type": "Object"
        },
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "Object"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "Object"
        },
        {
          "title": "808",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"808\"\n}",
          "type": "Object"
        },
        {
          "title": "813",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"813\"\n}",
          "type": "Object"
        },
        {
          "title": "814",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"814\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/transfer"
      }
    ]
  },
  {
    "type": "post",
    "url": "/recoveries/transfer",
    "title": "出借方 轉讓申請",
    "version": "0.1.0",
    "name": "PostRecoveriesTransfer",
    "group": "Recoveries",
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
          "type": "Object"
        },
        {
          "title": "806",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"806\"\n}",
          "type": "Object"
        },
        {
          "title": "805",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"805\"\n}",
          "type": "Object"
        },
        {
          "title": "808",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"808\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/transfer"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/recoveries/withdraw",
    "title": "出借方 提領申請",
    "version": "0.2.0",
    "name": "PostRecoveriesWithdraw",
    "group": "Recoveries",
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
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "210",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"210\"\n}",
          "type": "Object"
        },
        {
          "title": "211",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"211\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/recoveries/withdraw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/recoveries/withdraw",
    "title": "出借方 提領申請",
    "version": "0.1.0",
    "name": "PostRecoveriesWithdraw",
    "group": "Recoveries",
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
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "210",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"210\"\n}",
          "type": "Object"
        },
        {
          "title": "211",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"211\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Recoveries.php",
    "groupTitle": "Recoveries",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/recoveries/withdraw"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/repayment/contract/:id",
    "title": "借款方 合約列表",
    "name": "GetRepaymentContract",
    "version": "0.2.0",
    "group": "Repayment",
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
            "type": "Number",
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
            "type": "Object",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/contract/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/repayment/contract/:id",
    "title": "借款方 合約列表",
    "version": "0.1.0",
    "name": "GetRepaymentContract",
    "group": "Repayment",
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
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/repayment/contract/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/repayment/dashboard",
    "title": "借款端 我的還款",
    "version": "0.2.0",
    "name": "GetRepaymentDashboard",
    "group": "Repayment",
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
            "type": "Object",
            "optional": false,
            "field": "next_repayment",
            "description": "<p>當期待還</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.date",
            "description": "<p>還款日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.amount",
            "description": "<p>還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "accounts_payable",
            "description": "<p>應付帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_payable.principal",
            "description": "<p>應付本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_payable.interest",
            "description": "<p>應付利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_payable.delay_interest",
            "description": "<p>應付延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_payable.liquidated_damages",
            "description": "<p>應付違約金</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "funds",
            "description": "<p>資金資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "funds.frozen",
            "description": "<p>待交易餘額</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\": {\n\t\t\t\"accounts_payable\": {\n\t\t\t\t\"principal\": 39000,\n\t\t\t\t\"interest\": 1236,\n\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\"liquidated_damages\": 0\n\t\t\t},\n\t\t\t\"next_repayment\": {\n\t\t\t\t\"date\": \"2019-03-10\",\n\t\t\t\t\"amount\": 8849\n\t\t\t},\n\t\t\t\"funds\": {\n\t\t\t\t\"total\": 494745,\n\t\t\t\t\"last_recharge_date\": \"2019-01-17 19:57:50\",\n\t\t\t\t\"frozen\": 500\n\t\t\t},\n\t\t\t\t\"bank_account\": {\n\t\t\t\t\"bank_code\": \"005\",\n\t\t\t\t\"branch_code\": \"0027\",\n\t\t\t\t\"bank_account\": \"45645645645645\"\n\t\t\t},\n\t\t\t\t\"virtual_account\": {\n\t\t\t\t\"bank_code\": \"013\",\n\t\t\t\t\"branch_code\": \"0154\",\n\t\t\t\t\"bank_name\": \"國泰世華商業銀行\",\n\t\t\t\t\"branch_name\": \"信義分行\",\n\t\t\t\t\"virtual_account\": \"56631108557856\"\n\t\t\t}\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/dashboard"
      }
    ],
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/repayment/dashboard",
    "title": "借款端 我的還款",
    "version": "0.1.0",
    "name": "GetRepaymentDashboard",
    "group": "Repayment",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/repayment/dashboard"
      }
    ],
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
          "type": "Object"
        },
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
  },
  {
    "type": "get",
    "url": "/v2/repayment/info/:id",
    "title": "借款方 我的還款資訊",
    "version": "0.2.0",
    "name": "GetRepaymentInfo",
    "group": "Repayment",
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
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "platform_fee",
            "description": "<p>平台手續費</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "damage_rate",
            "description": "<p>違約金率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Number",
            "optional": false,
            "field": "next_repayment.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.principal",
            "description": "<p>本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.interest",
            "description": "<p>利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.delay_interest",
            "description": "<p>延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.liquidated_damages",
            "description": "<p>違約金</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\": 9,\n\t\t\t\"target_no\": \"STN2019011414213\",\n\t\t\t\"product_id\": 1,\n\t\t\t\"user_id\": 19,\n\t\t\t\"amount\": 5000,\n\t\t\t\"loan_amount\": 5000,\n\t\t\t\"platform_fee\": 500,\n\t\t\t\"credit_level\": 3,\n\t\t\t\"interest_rate\": 7,\n\t\t\t\"damage_rate\": 5,\n\t\t\t\"reason\": \"\",\n\t\t\t\"remark\": \"\",\n\t\t\t\"instalment\": 3,\n\t\t\t\"repayment\": 1,\n\t\t\t\"delay\": 0,\n\t\t\t\"delay_days\": 0,\n\t\t\t\"status\": 5,\n\t\t\t\"sub_status\": 0,\n\t\t\t\"created_at\": 1547444954,\n\t\t\t\"next_repayment\": {\n\t\t\t\t\"date\": \"2019-03-10\",\n\t\t\t\t\"instalment\": 1,\n\t\t\t\t\"amount\": 1687,\n\t\t\t\t\"principal\": 1634,\n\t\t\t\t\"interest\": 53,\n\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\"liquidated_damages\": 0\n\t\t\t},\n\t\t\t\"amortization_schedule\": {\n\t\t\t\t\"amount\": 5000,\n\t\t\t\t\"remaining_principal\": 5000,\n\t\t\t\t\"instalment\": 3,\n\t\t\t\t\"rate\": 7,\n\t\t\t\t\"total_payment\": 5083,\n\t\t\t\t\"date\": \"2019-01-14\",\n\t\t\t\t\"end_date\": \"2019-05-10\",\n\t\t\t\t\"sub_loan_fees\": 0,\n\t\t\t\t\"platform_fees\": 500,\n\t\t\t\t\"list\": {\n\t\t\t\t\t\"1\": {\n\t\t\t\t\t\t\"instalment\": 1,\n\t\t\t\t\t\t\"total_payment\": 1687,\n\t\t\t\t\t\t\"repayment\": 0,\n\t\t\t\t\t\t\"interest\": 53,\n\t\t\t\t\t\t\"principal\": 1634,\n\t\t\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\t\t\"liquidated_damages\": 0,\n\t\t\t\t\t\t\"days\": 55,\n\t\t\t\t\t\t\"remaining_principal\": 5000,\n\t\t\t\t\t\t\"repayment_date\": \"2019-03-10\"\n\t\t\t\t\t},\n\t\t\t\t\t\"2\": {\n\t\t\t\t\t\t\"instalment\": 2,\n\t\t\t\t\t\t\"total_payment\": 1687,\n\t\t\t\t\t\t\"repayment\": 0,\n\t\t\t\t\t\t\"interest\": 20,\n\t\t\t\t\t\t\"principal\": 1667,\n\t\t\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\t\t\"liquidated_damages\": 0,\n\t\t\t\t\t\t\"days\": 31,\n\t\t\t\t\t\t\"remaining_principal\": 3366,\n\t\t\t\t\t\t\"repayment_date\": \"2019-04-10\"\n\t\t\t\t\t},\n\t\t\t\t\t\"3\": {\n\t\t\t\t\t\t\"instalment\": 3,\n\t\t\t\t\t\t\"total_payment\": 1709,\n\t\t\t\t\t\t\"repayment\": 0,\n\t\t\t\t\t\t\"interest\": 10,\n\t\t\t\t\t\t\"principal\": 1699,\n\t\t\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\t\t\"liquidated_damages\": 0,\n\t\t\t\t\t\t\"days\": 30,\n\t\t\t\t\t\t\"remaining_principal\": 1699,\n\t\t\t\t\t\t\"repayment_date\": \"2019-05-10\"\n\t\t\t\t\t}\n\t\t\t\t}\n   \t\t}\n   \t}\n   }",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/repayment/info/:id",
    "title": "借款方 我的還款資訊",
    "version": "0.1.0",
    "name": "GetRepaymentInfo",
    "group": "Repayment",
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
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/repayment/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/repayment/list",
    "title": "借款方 我的還款列表",
    "version": "0.2.0",
    "name": "GetRepaymentList",
    "group": "Repayment",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>申請金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Number",
            "optional": false,
            "field": "next_repayment.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "next_repayment.amount",
            "description": "<p>金額</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\": 9,\n\t\t\t\t\"target_no\": \"STN2019011414213\",\n\t\t\t\t\"product_id\": 1,\n\t\t\t\t\"user_id\": 19,\n\t\t\t\t\"amount\": 5000,\n\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\"interest_rate\": 7,\n\t\t\t\t\"instalment\": 3,\n\t\t\t\t\"repayment\": 1,\n\t\t\t\t\"delay\": 0,\n\t\t\t\t\"delay_days\": 0,\n\t\t\t\t\"status\": 5,\n\t\t\t\t\"sub_status\": 0,\n\t\t\t\t\"created_at\": 1547444954,\n\t\t\t\t\"next_repayment\": {\n\t\t\t\t\t\"date\": \"2019-03-10\",\n\t\t\t\t\t\"instalment\": 1,\n\t\t\t\t\t\"amount\": 1687\n\t\t\t\t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/list"
      }
    ],
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/repayment/list",
    "title": "借款方 我的還款列表",
    "version": "0.1.0",
    "name": "GetRepaymentList",
    "group": "Repayment",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/repayment/list"
      }
    ],
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
          "type": "Object"
        },
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
  },
  {
    "type": "get",
    "url": "/v2/repayment/passbook",
    "title": "借款方 虛擬帳戶明細",
    "version": "0.2.0",
    "name": "GetRepaymentPassbook",
    "group": "Repayment",
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
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>入帳時間</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t\t{\n\t\t\t\t\t\"amount\": 500000,\n\t\t\t\t\t\"bank_amount\": 500000,\n\t\t\t\t\t\"remark\": \"平台代收\",\n\t\t\t\t\t\"tx_datetime\": \"2019-01-17 19:57:50\",\n\t\t\t\t\t\"created_at\": 1547726281\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"amount\": -5,\n\t\t\t\t\t\"bank_amount\": 499995,\n\t\t\t\t\t\"remark\": \"還款利息\",\n\t\t\t\t\t\"tx_datetime\": \"2019-01-17 19:59:24\",\n\t\t\t\t\t\"created_at\": 1547726364\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"amount\": -5000,\n\t\t\t\t\t\"bank_amount\": 494995,\n\t\t\t\t\t\"remark\": \"還款本金\",\n\t\t\t\t\t\"tx_datetime\": \"2019-01-17 19:59:24\",\n\t\t\t\t\t\"created_at\": 1547726364\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"amount\": -250,\n\t\t\t\t\t\"bank_amount\": 494745,\n\t\t\t\t\t\"remark\": \"提前還款違約金\",\n\t\t\t\t\t\"tx_datetime\": \"2019-01-17 19:59:25\",\n\t\t\t\t\t\"created_at\": 1547726365\n\t\t\t\t}\n\t\t\t]\n\t\t}\n   }",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/passbook"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/repayment/prepayment/:id",
    "title": "借款方 提前還款資訊",
    "version": "0.2.0",
    "name": "GetRepaymentPrepayment",
    "group": "Repayment",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
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
            "type": "Number",
            "optional": false,
            "field": "remaining_instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_payable",
            "description": "<p>應付利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "liquidated_damages",
            "description": "<p>違約金（提還違約金）</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "total",
            "description": "<p>合計</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\": \"SUCCESS\",\n\t\t\"data\": {\n\t\t\t\"settlement_date\": \"2019-01-25\",\n\t\t\t\"remaining_instalment\": 3,\n\t\t\t\"remaining_principal\": 5000,\n\t\t\t\"interest_payable\": 11,\n\t\t\t\"liquidated_damages\": 250,\n\t\t\t\"total\": 5261\n\t\t}\n}",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/prepayment/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/repayment/prepayment/:id",
    "title": "借款方 提前還款資訊",
    "version": "0.1.0",
    "name": "GetRepaymentPrepayment",
    "group": "Repayment",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/repayment/prepayment/:id"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/repayment/prepayment/:id",
    "title": "借款方 申請提前還款",
    "version": "0.2.0",
    "name": "PostRepaymentPrepayment",
    "group": "Repayment",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\"\n   }",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/prepayment/:id"
      }
    ]
  },
  {
    "type": "post",
    "url": "/repayment/prepayment/:id",
    "title": "借款方 申請提前還款",
    "version": "0.1.0",
    "name": "PostRepaymentPrepayment",
    "group": "Repayment",
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
            "type": "Number",
            "optional": false,
            "field": ":id",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\"\n   }",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/repayment/prepayment/:id"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/repayment/withdraw",
    "title": "借款方 提領申請",
    "version": "0.2.0",
    "name": "PostRepaymentWithdraw",
    "group": "Repayment",
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
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "210",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"210\"\n}",
          "type": "Object"
        },
        {
          "title": "211",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"211\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Repayment.php",
    "groupTitle": "Repayment",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/repayment/withdraw"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/subloan/applyinfo/:id",
    "title": "借款方 產品轉換紀錄資訊",
    "version": "0.2.0",
    "name": "GetSubloanApplyinfo",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"target_id\":\"1\",\n\t\t\t\"amount\":\"53651\",\n\t\t\t\"instalment\":\"3期\",\n\t\t\t\"repayment\":\"先息後本\",\n\t\t\t\"settlement_date\":\"2018-05-26\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"created_at\":\"1527151277\",\n\t\t\t\"subloan_target\": {\n\t\t\t\t\"id\":\"35\",\n\t\t\t\t\"target_no\": \"1805247784\",\n\t\t\t\t\"product_id\":\"1\",\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"amount\":\"53651\",\n\t\t\t\t\"loan_amount\":\"53651\",\n\t\t\t\t\"platform_fee\":\"1610\",\n\t\t\t\t\"interest_rate\":\"9\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"先息後本\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\":\"0\",\n\t\t\t\t\"status\":\"1\",\n\t\t\t\t\"sub_status\":\"8\",\n\t\t\t\t\"created_at\":\"1520421572\",\n      \t\t\"amortization_schedule\": {\n          \t\t\"amount\": \"12000\",\n          \t\t\"instalment\": \"6\",\n          \t\t\"rate\": \"9\",\n          \t\t\"date\": \"2018-04-17\",\n          \t\t\"total_payment\": 2053,\n          \t\t\"leap_year\": false,\n          \t\t\"year_days\": 365,\n          \t\t\"XIRR\": 0.0939,\n          \t\t\"schedule\": {\n               \t\t\"1\": {\n                 \t\t\"instalment\": 1,\n                 \t\t\"repayment_date\": \"2018-06-10\",\n                 \t\t\"days\": 54,\n                 \t\t\"remaining_principal\": \"12000\",\n                 \t\t\"principal\": 1893,\n                 \t\t\"interest\": 160,\n                 \t\t\"total_payment\": 2053\n             \t\t},\n             \t\t\"2\": {\n                  \t\t\"instalment\": 2,\n                 \t\t\"repayment_date\": \"2018-07-10\",\n                 \t\t\"days\": 30,\n                  \t\t\"remaining_principal\": 10107,\n                  \t\t\"principal\": 1978,\n                  \t\t\"interest\": 75,\n                   \t\t\"total_payment\": 2053\n              \t\t},\n             \t\t\"3\": {\n                   \t\t\"instalment\": 3,\n                   \t\t\"repayment_date\": \"2018-08-10\",\n                   \t\t\"days\": 31,\n                   \t\t\"remaining_principal\": 8129,\n                  \t\t\"principal\": 1991,\n                  \t\t\"interest\": 62,\n                   \t\t\"total_payment\": 2053\n               \t\t}\n           \t\t},\n          \t\t\"total\": {\n               \t\t\"principal\": 12000,\n               \t\t\"interest\": 391,\n               \t\t\"total_payment\": 12391\n           \t\t}\n       \t\t}\n\t\t\t}\n\t\t}\n   }",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/subloan/applyinfo/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/subloan/applyinfo/:id",
    "title": "借款方 產品轉換紀錄資訊",
    "version": "0.1.0",
    "name": "GetSubloanApplyinfo",
    "group": "Subloan",
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
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/subloan/applyinfo/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/subloan/cancel/:id",
    "title": "借款方 取消產品轉換",
    "version": "0.2.0",
    "name": "GetSubloanCancel",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/subloan/cancel/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/subloan/cancel/:id",
    "title": "借款方 取消產品轉換",
    "version": "0.1.0",
    "name": "GetSubloanCancel",
    "group": "Subloan",
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
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/subloan/cancel/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/subloan/preapply/:id",
    "title": "借款方 產品轉換前資訊",
    "version": "0.2.0",
    "name": "GetSubloanPreapply",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/subloan/preapply/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/subloan/preapply/:id",
    "title": "借款方 產品轉換前資訊",
    "version": "0.1.0",
    "name": "GetSubloanPreapply",
    "group": "Subloan",
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
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/subloan/preapply/:id"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/subloan/apply/",
    "title": "借款方 產品轉換申請",
    "version": "0.2.0",
    "name": "PostSubloanApply",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "target_id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>申請期數</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\"\n   }",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "403",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"403\"\n}",
          "type": "Object"
        },
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "409",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"409\"\n}",
          "type": "Object"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/subloan/apply/"
      }
    ]
  },
  {
    "type": "post",
    "url": "/subloan/apply/",
    "title": "借款方 產品轉換申請",
    "version": "0.1.0",
    "name": "PostSubloanApply",
    "group": "Subloan",
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
            "type": "Number",
            "optional": false,
            "field": "target_id",
            "description": "<p>Target ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>申請期數</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\"\n   }",
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
          "type": "Object"
        },
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "409",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"409\"\n}",
          "type": "Object"
        },
        {
          "title": "903",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"903\"\n}",
          "type": "Object"
        },
        {
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
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
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/subloan/apply/"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/subloan/signing",
    "title": "借款方 產品轉換簽約",
    "version": "0.2.0",
    "name": "PostSubloanSigning",
    "group": "Subloan",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "404",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"404\"\n}",
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "206",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"206\"\n}",
          "type": "Object"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        },
        {
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/subloan/signing"
      }
    ]
  },
  {
    "type": "post",
    "url": "/subloan/signing",
    "title": "借款方 產品轉換簽約",
    "version": "0.1.0",
    "name": "PostSubloanSigning",
    "group": "Subloan",
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
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "405",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"405\"\n}",
          "type": "Object"
        },
        {
          "title": "407",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"407\"\n}",
          "type": "Object"
        },
        {
          "title": "206",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"206\"\n}",
          "type": "Object"
        },
        {
          "title": "904",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"904\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "207",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"207\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Subloan.php",
    "groupTitle": "Subloan",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/subloan/signing"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/target/batchpreapply",
    "title": "出借方 批次查詢資訊",
    "version": "0.2.0",
    "name": "GetBatchPreTargetApply",
    "group": "Target",
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
            "type": "Number",
            "optional": false,
            "field": "target_ids",
            "description": "<p>產品IDs IDs ex: 1,3,10,21</p>"
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
            "type": "Object",
            "optional": false,
            "field": "target_ids",
            "description": "<p>Target IDs</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>預計還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule.total",
            "description": "<p>還款總計</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.principal",
            "description": "<p>本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.interest",
            "description": "<p>利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.total_payment",
            "description": "<p>加總</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule.schedule",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.key",
            "description": "<p>還款日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "contracts",
            "description": "<p>借貸合約列表</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 10000,\n\t\t\t\"total_count\": 2,\n\t\t\t\"max_instalment\": 6,\n\t\t\t\"min_instalment\": 3,\n\t\t\t\"XIRR\": 7.67,\n\t\t\t\"target_ids\": [\n\t\t\t\t23,\n\t\t\t\t24\n\t\t\t],\n\t\t\t\"amortization_schedule\": {\n\t\t\t\t\"total\": {\n\t\t\t\t\t\"principal\": 10000,\n\t\t\t\t\t\"interest\": 194,\n\t\t\t\t\t\"total_payment\": 10194\n\t\t\t\t},\n\t\t\t\t\"schedule\": {\n\t\t\t\t\t\"2019-03-10\": {\n\t\t\t\t\t\t\"principal\": 1650,\n\t\t\t\t\t\t\"interest\": 37,\n\t\t\t\t\t\t\"total_payment\": 1687\n\t\t\t\t\t},\n\t\t\t\t\t\"2019-04-10\": {\n\t\t\t\t\t\t\"principal\": 1667,\n\t\t\t\t\t\t\"interest\": 20,\n\t\t\t\t\t\t\"total_payment\": 1687\n\t\t\t\t\t},\n\t\t\t\t\t\"2019-05-10\": {\n\t\t\t\t\t\t\"principal\": 1683,\n\t\t\t\t\t\t\"interest\": 10,\n\t\t\t\t\t\t\"total_payment\": 1693\n\t\t\t\t\t},\n\t\t\t\t\t\"2019-06-10\": {\n\t\t\t\t\t\t\"principal\": 836,\n\t\t\t\t\t\t\"interest\": 17,\n\t\t\t\t\t\t\"total_payment\": 853\n\t\t\t\t\t},\n\t\t\t\t\t\"2019-07-10\": {\n\t\t\t\t\t\t\"principal\": 842,\n\t\t\t\t\t\t\"interest\": 11,\n\t\t\t\t\t\t\"total_payment\": 853\n\t\t\t\t\t},\n\t\t\t\t\t\"2019-08-10\": {\n\t\t\t\t\t\t\"principal\": 856,\n\t\t\t\t\t\t\"interest\": 6,\n\t\t\t\t\t\t\"total_payment\": 862\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t},\n\t\t\t\"contracts\": [\n\t\t\t{\n\t\t\t\t\"title\": \"借貸契約\",\n\t\t\t\t\"content\": \"借貸契約\",\n\t\t\t\t\"created_at\": \"1547445331\"\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"title\": \"借貸契約\",\n\t\t\t\t\"content\": \"借貸契約\",\n\t\t\t\t\"created_at\": \"1547445358\"\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
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
            "field": "801",
            "description": "<p>標的不存在</p>"
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
          "title": "801",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"801\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/batchpreapply"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/target/applylist",
    "title": "出借方 申請紀錄列表",
    "version": "0.2.0",
    "name": "GetTargetApplylist",
    "group": "Target",
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
    "description": "<p>只顯示 待付款 待結標 待放款 狀態的申請</p>",
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
            "field": "amount",
            "description": "<p>投標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>得標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>標的案號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>標的金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"amount\": 5000,\n\t\t\t\t\"loan_amount\": 0,\n\t\t\t\t\"status\": 0,\n\t\t\t\t\"created_at\": 1547626406,\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": 18,\n\t\t\t\t\t\"target_no\": \"STN2019011430611\",\n\t\t\t\t\t\"product_id\": 1,\n\t\t\t\t\t\"user_id\": 19,\n\t\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\t\"credit_level\": 3,\n\t\t\t\t\t\"interest_rate\": 8,\n\t\t\t\t\t\"instalment\": 6,\n\t\t\t\t\t\"repayment\": 1,\n\t\t\t\t\t\"expire_time\": 1547618700,\n\t\t\t\t\t\"invested\": 0,\n\t\t\t\t\t\"status\": 3,\n\t\t\t\t\t\"sub_status\": 0\n\t\t\t\t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/applylist"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/target/applylist",
    "title": "出借方 申請紀錄列表",
    "version": "0.1.0",
    "name": "GetTargetApplylist",
    "group": "Target",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/target/applylist"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/target/batch",
    "title": "出借方 智能出借前次設定",
    "version": "0.2.0",
    "name": "GetTargetBatch",
    "group": "Target",
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
            "type": "String",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID 全部：all 複選使用逗號隔開</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_s",
            "description": "<p>利率區間下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_e",
            "description": "<p>利率區間上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment_s",
            "description": "<p>期數區間下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment_e",
            "description": "<p>期數區間上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等 全部：all 複選使用逗號隔開</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "section",
            "description": "<p>標的狀態 全部:all 全案:0 部分案:1</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "national",
            "description": "<p>信用評等 全部:all 私立:0 國立:1</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "system",
            "description": "<p>學制 全部:all 0:大學 1:碩士 2:博士</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sex",
            "description": "<p>性別 全部:all 女性:F 男性:M</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"product_id\": \"all\",\n\t\t\t\"credit_level\": \"all\",\n\t\t\t\"section\": \"all\",\n\t\t\t\"interest_rate_s\": 7,\n\t\t\t\"interest_rate_e\": 10,\n\t\t\t\"instalment_s\": 12,\n\t\t\t\"instalment_e\": 12,\n\t\t\t\"sex\": \"all\",\n\t\t\t\"system\": \"all\",\n\t\t\t\"national\": \"all\"\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/batch"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/target/batch",
    "title": "出借方 智能出借",
    "version": "0.1.0",
    "name": "GetTargetBatch",
    "group": "Target",
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
            "type": "Number",
            "optional": false,
            "field": "budget",
            "description": "<p>預算金額</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_s",
            "description": "<p>利率區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_e",
            "description": "<p>利率區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "instalment_s",
            "description": "<p>期數區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/target/batch"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/target/info/:id",
    "title": "出借方 取得標的資訊",
    "version": "0.2.0",
    "name": "GetTargetInfo",
    "group": "Target",
    "description": "<p>限架上案件</p>",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
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
            "type": "Object",
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
            "field": "user.id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.sex",
            "description": "<p>性別 F/M</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.company_name",
            "description": "<p>單位名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>預計還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.amount",
            "description": "<p>借款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.instalment",
            "description": "<p>借款期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total_payment",
            "description": "<p>每月還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "amortization_schedule.leap_year",
            "description": "<p>是否為閏年</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.year_days",
            "description": "<p>本年日數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.XIRR",
            "description": "<p>內部報酬率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule.schedule",
            "description": "<p>還款計畫</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.days",
            "description": "<p>本期日數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.remaining_principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.principal",
            "description": "<p>還款本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.interest",
            "description": "<p>還款利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.total_payment",
            "description": "<p>本期還款金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule.total",
            "description": "<p>還款總計</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.principal",
            "description": "<p>本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.interest",
            "description": "<p>利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.total_payment",
            "description": "<p>加總</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\"result\": \"SUCCESS\",\n\t\"data\": {\n\t\t\"id\": 24,\n\t\t\"target_no\": \"STN2019011487405\",\n\t\t\"product_id\": 1,\n\t\t\"user_id\": 19,\n\t\t\"loan_amount\": 5000,\n\t\t\"credit_level\": 3,\n\t\t\"interest_rate\": 7,\n\t\t\"reason\": \"\",\n\t\t\"remark\": \"\",\n\t\t\"instalment\": 3,\n\t\t\"repayment\": 1,\n\t\t\"expire_time\": 1548828283,\n\t\t\"invested\": 0,\n\t\t\"status\": 3,\n\t\t\"sub_status\": 0,\n\t\t\"created_at\": 1547445512,\n\t\t\"contract\": \"借貸契約\",\n\t\t\"user\": {\n\t\t\t\"name\": \"你**\",\n\t\t\t\"id_number\": \"A1085*****\",\n\t\t\t\"sex\": \"M\",\n\t\t\t\"age\": 30,\n\t\t\t\"company_name\": \"國立政治大學\"\n\t\t},\n\t\t\"amortization_schedule\": {\n\t\t\t\"amount\": 5000,\n\t\t\t\"instalment\": 3,\n\t\t\t\"rate\": 7,\n\t\t\t\"date\": \"2019-01-30\",\n\t\t\t\"total_payment\": 1687,\n\t\t\t\"leap_year\": false,\n\t\t\t\"year_days\": 365,\n\t\t\t\"XIRR\": 7.23,\n\t\t\t\"schedule\": {\n\t\t\t\t\"1\": {\n\t\t\t\t\t\"instalment\": 1,\n\t\t\t\t\t\"repayment_date\": \"2019-03-10\",\n\t\t\t\t\t\"days\": 39,\n\t\t\t\t\t\"remaining_principal\": 5000,\n\t\t\t\t\t\"principal\": 1650,\n\t\t\t\t\t\"interest\": 37,\n\t\t\t\t\t\"total_payment\": 1687\n\t\t\t\t},\n\t\t\t\t\"2\": {\n\t\t\t\t\t\"instalment\": 2,\n\t\t\t\t\t\"repayment_date\": \"2019-04-10\",\n\t\t\t\t\t\"days\": 31,\n\t\t\t\t\t\"remaining_principal\": 3350,\n\t\t\t\t\t\"principal\": 1667,\n\t\t\t\t\t\"interest\": 20,\n\t\t\t\t\t\"total_payment\": 1687\n\t\t\t\t},\n\t\t\t\t\"3\": {\n\t\t\t\t\t\"instalment\": 3,\n\t\t\t\t\t\"repayment_date\": \"2019-05-10\",\n\t\t\t\t\t\"days\": 30,\n\t\t\t\t\t\"remaining_principal\": 1683,\n\t\t\t\t\t\"principal\": 1683,\n\t\t\t\t\t\"interest\": 10,\n\t\t\t\t\t\"total_payment\": 1693\n\t\t\t\t}\n\t\t\t},\n\t\t\t\"total\": {\n\t\t\t\t\"principal\": 5000,\n\t\t\t\t\"interest\": 67,\n\t\t\t\t\"total_payment\": 5067\n\t\t\t}\n\t\t}\n\t}\n}",
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
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/target/info/:id",
    "title": "出借方 取得標的資訊",
    "version": "0.1.0",
    "name": "GetTargetInfo",
    "group": "Target",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "certification",
            "description": "<p>借款人認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/target/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/target/list",
    "title": "出借方 取得標的列表",
    "version": "0.2.0",
    "name": "GetTargetList",
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
            "type": "Number",
            "optional": false,
            "field": "product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "user",
            "description": "<p>借款人基本資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.sex",
            "description": "<p>性別 F/M</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.company_name",
            "description": "<p>單位名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "reason",
            "description": "<p>借款原因</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t\t{\n\t\t\t\t\t\"id\": 30,\n\t\t\t\t\t\"target_no\": \"STN2019011414457\",\n\t\t\t\t\t\"product_id\": 1,\n\t\t\t\t\t\"credit_level\": 6,\n\t\t\t\t\t\"user_id\": 1,\n\t\t\t\t\t\"user\": {\n\t\t\t\t\t\t\"sex\": \"M\",\n\t\t\t\t\t\t\"age\": 29,\n\t\t\t\t\t\t\"company_name\": \"國立宜蘭大學\"\n\t\t\t\t\t},\n\t\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\t\"interest_rate\": 10,\n\t\t\t\t\t\"instalment\": 3,\n\t\t\t\t\t\"repayment\": 1,\n\t\t\t\t\t\"expire_time\": 1547792055,\n\t\t\t\t\t\"invested\": 0,\n\t\t\t\t\t\"reason\": \"\",\n\t\t\t\t\t\"status\": 3,\n\t\t\t\t\t\"sub_status\": 0,\n\t\t\t\t\t\"created_at\": 1547455529\n\t\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/list"
      }
    ]
  },
  {
    "type": "get",
    "url": "/target/list",
    "title": "出借方 取得標的列表",
    "version": "0.1.0",
    "name": "GetTargetList",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/target/list"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/target/batchapply",
    "title": "出借方 批次申請出借",
    "version": "0.2.0",
    "name": "PostBatchTargetApply",
    "group": "Target",
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
            "type": "Number",
            "optional": false,
            "field": "target_ids",
            "description": "<p>產品IDs IDs ex: 1,3,10,21</p>"
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
            "field": "801",
            "description": "<p>標的不存在</p>"
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
          "type": "Object"
        },
        {
          "title": "803",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"803\"\n}",
          "type": "Object"
        },
        {
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/batchapply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/target/apply",
    "title": "出借方 單案申請出借",
    "version": "0.2.0",
    "name": "PostTargetApply",
    "group": "Target",
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
            "type": "Number",
            "optional": false,
            "field": "target_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "802",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"802\"\n}",
          "type": "Object"
        },
        {
          "title": "803",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"803\"\n}",
          "type": "Object"
        },
        {
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/target/apply",
    "title": "出借方 申請出借",
    "version": "0.1.0",
    "name": "PostTargetApply",
    "group": "Target",
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
            "type": "Number",
            "optional": false,
            "field": "target_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "802",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"802\"\n}",
          "type": "Object"
        },
        {
          "title": "803",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"803\"\n}",
          "type": "Object"
        },
        {
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/target/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/target/batch",
    "title": "出借方 智能出借",
    "version": "0.2.0",
    "name": "PostTargetBatch",
    "group": "Target",
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
            "optional": true,
            "field": "product_id",
            "defaultValue": "all",
            "description": "<p>產品ID 全部：all 複選使用逗號隔開1,2,3,4</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_s",
            "description": "<p>利率區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_e",
            "description": "<p>利率區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "instalment_s",
            "description": "<p>期數區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
            "description": "<p>信用評等 全部：all 複選使用逗號隔開1,2,3,4,5,6,7,8,9,10,11,12,13</p>"
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
            "field": "section",
            "defaultValue": "all",
            "description": "<p>標的狀態 全部:all 全案:0 部分案:1</p>"
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
            "field": "sex",
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
            "type": "Object",
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
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target_ids",
            "description": "<p>篩選出的Target ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 70000,\n\t\t\t\"total_count\": 4,\n\t\t\t\"max_instalment\": 12,\n\t\t\t\"min_instalment\": 12,\n\t\t\t\"XIRR\": 8,\n\t\t\t\"target_ids\": [\n\t\t\t\t17,\n\t\t\t\t19,\n\t\t\t\t21,\n\t\t\t\t22\n\t\t\t]\n\t\t}\n   }",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/target/batch"
      }
    ]
  },
  {
    "type": "post",
    "url": "/target/batch/:batch_id",
    "title": "出借方 智能出借確認",
    "version": "0.1.0",
    "name": "PostTargetBatch",
    "group": "Target",
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
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "812",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"812\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Target.php",
    "groupTitle": "Target",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/target/batch/:batch_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/transfer/applylist",
    "title": "出借方 債權申請紀錄列表",
    "version": "0.2.0",
    "name": "GetTransferApplylist",
    "group": "Transfer",
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
    "description": "<p>只顯示 待付款 待結標 待放款 狀態的申請</p>",
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
            "description": "<p>Transfer Investments ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>投標金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
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
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款 9:流標 10:移轉成功</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "transfer",
            "description": "<p>債轉標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.id",
            "description": "<p>Transfer ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.amount",
            "description": "<p>價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.interest",
            "description": "<p>已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.delay_interest",
            "description": "<p>已發生延滯利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "transfer.bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "transfer.accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target",
            "description": "<p>標的資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.target_no",
            "description": "<p>標的案號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>標的金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用評等</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>年化利率</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.invested",
            "description": "<p>目前投標量</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"contract\":\"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t\t\"transfer\": {\n\t\t\t\t\t\"id\": \"1\",\n\t\t\t\t\t\"amount\": \"1804233189\",\n\t\t\t\t\t\"instalment\": \"5000\",\n\t\t\t\t\t\"expire_time\": \"123456789\"\n\t\t\t\t},\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": \"19\",\n\t\t\t\t\t\"target_no\": \"1804233189\",\n\t\t\t\t\t\"invested\": \"5000\",\n\t\t\t\t\t\"expire_time\": \"123456789\",\n\t\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\t\"status\": \"5\"\n\t\t\t\t}\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/applylist"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/transfer/applylist",
    "title": "出借方 債權申請紀錄列表",
    "version": "0.1.0",
    "name": "GetTransferApplylist",
    "group": "Transfer",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/transfer/applylist"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/transfer/batch/",
    "title": "出借方 智能收購前次設定",
    "version": "0.2.0",
    "name": "GetTransferBatch",
    "group": "Transfer",
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
            "type": "Number",
            "optional": false,
            "field": "delay",
            "description": "<p>逾期標的 0:正常標的 1:逾期標的</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>指定使用者ID</p>"
          },
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
            "field": "product_id",
            "description": "<p>產品ID 全部：all 複選使用逗號隔開</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_s",
            "description": "<p>利率區間下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_e",
            "description": "<p>利率區間上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment_s",
            "description": "<p>期數區間下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment_e",
            "description": "<p>期數區間上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "bargain_rate_s",
            "description": "<p>增減價比率下限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "bargain_rate_e",
            "description": "<p>增減價比率上限(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "credit_level",
            "description": "<p>信用評等 全部：all 複選使用逗號隔開</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "section",
            "description": "<p>標的狀態 全部:all 全案:0 部分案:1</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "national",
            "description": "<p>信用評等 全部:all 私立:0 國立:1</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "system",
            "description": "<p>學制 全部:all 0:大學 1:碩士 2:博士</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "sex",
            "description": "<p>性別 全部:all 女性:F 男性:M</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"user_id\": \"\",\n\t\t\t\"delay\": 0,\n\t\t\t\"instalment_s\": 1,\n\t\t\t\"instalment_e\": 20,\n\t\t\t\"bargain_rate_s\": -18.9,\n\t\t\t\"bargain_rate_e\": 20,\n\t\t\t\"product_id\": \"1,2\",\n\t\t\t\"credit_level\": \"1,2,3,4,5,6,7,8,9,10,11,12,13\",\n\t\t\t\"section\": \"all\",\n\t\t\t\"interest_rate_s\": 1,\n\t\t\t\"interest_rate_e\": 20,\n\t\t\t\"sex\": \"M\",\n\t\t\t\"system\": \"2\",\n\t\t\t\"national\": \"1\"\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/batch/"
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
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        },
        {
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/transfer/batch",
    "title": "出借方 智能收購",
    "version": "0.1.0",
    "name": "GetTransferBatch",
    "group": "Transfer",
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
            "type": "Number",
            "optional": false,
            "field": "budget",
            "description": "<p>預算金額</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "delay",
            "defaultValue": "0",
            "description": "<p>逾期標的 0:正常標的 1:逾期標的 default:0</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "user_id",
            "description": "<p>指定使用者ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_s",
            "description": "<p>正常標的-利率區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_e",
            "description": "<p>正常標的-利率區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "instalment_s",
            "description": "<p>正常標的-剩餘期數區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "Object",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/transfer/batch"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/transfer/combination/:id",
    "title": "出借方 債權組合資訊",
    "version": "0.2.0",
    "name": "GetTransferCombination",
    "group": "Transfer",
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
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Combination ID</p>"
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
            "description": "<p>Combination ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "combination_no",
            "description": "<p>整包轉讓號</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "password",
            "description": "<p>是否需要密碼</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "count",
            "description": "<p>筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>整包轉讓價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "principal",
            "description": "<p>整包剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest",
            "description": "<p>整包已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_interest",
            "description": "<p>整包已發生延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "max_instalment",
            "description": "<p>最大剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "min_instalment",
            "description": "<p>最小剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "interest_rate",
            "description": "<p>平均年表利率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>整包應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "contracts",
            "description": "<p>債轉合約列表</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\": 9,\n\t\t\t\"combination_no\": \"PKG1550041591930747\",\n\t\t\t\"password\": false,\n\t\t\t\"count\": 3,\n\t\t\t\"amount\": 15102,\n\t\t\t\"principal\": 15000,\n\t\t\t\"interest\": 102,\n\t\t\t\"max_instalment\": 18,\n\t\t\t\"min_instalment\": 3,\n\t\t\t\"delay_interest\": 0,\n\t\t\t\"bargain_rate\": 0,\n\t\t\t\"interest_rate\": 8.56,\n\t\t\t\"accounts_receivable\": 15626,\n\t\t\t\"contracts\": [\n\t\t\t\t\"我是合約\",\n\t\t\t\t\"我是合約2\",\n\t\t\t\t\"我是合約3\"\n\t\t\t]\n\t\t}\n   }",
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
            "field": "816",
            "description": "<p>此組合不存在</p>"
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
          "title": "816",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"816\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/combination/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/transfer/info/:id",
    "title": "出借方 債權標的資訊",
    "version": "0.2.0",
    "name": "GetTransferInfo",
    "group": "Transfer",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "description": "<p>Transfer ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest",
            "description": "<p>已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_interest",
            "description": "<p>已發生延滯利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "contract",
            "description": "<p>債轉合約</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>核可利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target.user",
            "description": "<p>借款人基本資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.id_number",
            "description": "<p>身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.sex",
            "description": "<p>性別 F/M</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target.certification",
            "description": "<p>借款人認證完成資訊</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\": 3,\n\t\t\t\"amount\": 5002,\n\t\t\t\"principal\": 5000,\n\t\t\t\"interest\": 2,\n\t\t\t\"delay_interest\": 0,\n\t\t\t\"bargain_rate\": 0,\n\t\t\t\"instalment\": 3,\n\t\t\t\"combination\": 0,\n\t\t\t\"expire_time\": 1547654399,\n\t\t\t\"accounts_receivable\": 5145,\n\t\t\t\"contract\": \"我是合約\",\n\t\t\t\"target\": {\n\t\t\t\t\"id\": 9,\n\t\t\t\t\"target_no\": \"STN2019011414213\",\n\t\t\t\t\"product_id\": 1,\n\t\t\t\t\"credit_level\": 3,\n\t\t\t\t\"user_id\": 19,\n\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\"interest_rate\": 7,\n\t\t\t\t\"instalment\": 3,\n\t\t\t\t\"repayment\": 1,\n\t\t\t\t\"delay\": 0,\n\t\t\t\t\"delay_days\": 0,\n\t\t\t\t\"reason\": \"\",\n\t\t\t\t\"remark\": \"\",\n\t\t\t\t\"status\": 5,\n\t\t\t\t\"sub_status\": 0,\n\t\t\t\t\"created_at\": 1547444954,\n\t\t\t\t\"user\": {\n\t\t\t\t\t\"name\": \"你**\",\n\t\t\t\t\t\"id_number\": \"A1085*****\",\n\t\t\t\t\t\"sex\": \"M\",\n\t\t\t\t\t\"age\": 30,\n\t\t\t\t\t\"company_name\": \"國立政治大學\"\n\t\t\t\t}\n\t\t\t}\n\t\t}\n   }",
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
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/transfer/info/:id",
    "title": "出借方 取得債權標的資訊",
    "version": "0.1.0",
    "name": "GetTransferInfo",
    "group": "Transfer",
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
            "type": "Number",
            "optional": false,
            "field": "id",
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
            "type": "Object",
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
            "description": "<p>債權轉讓金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>債權剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "principal",
            "description": "<p>債權剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "interest",
            "description": "<p>債權已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "delay_interest",
            "description": "<p>債權已發生延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bargain_rate",
            "description": "<p>增減價百分比</p>"
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
            "type": "Object",
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
            "type": "Object",
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
            "type": "Object",
            "optional": false,
            "field": "certification",
            "description": "<p>借款人認證完成資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"instalment\":\"12\",\n\t\t\t\"principal\": \"5000\",\n\t\t\t\"interest\": \"36\",\n\t\t\t\"delay_interest\": \"0\",\n\t\t\t\"bargain_rate\": \"-5\",\n\t\t\t\"debt_transfer_contract\":\"我是合約！！\",\n\t\t\t\"expire_time\":\"1527865369\",\n\t\t\t\"target\":{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"target_no\": \"1803269743\",\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"loan_amount\":\"12000\",\n\t\t\t\t\"credit_level\":\"4\",\n\t\t\t\t\"interest_rate\":\"9\",\n\t\t\t\t\"instalment\":\"3期\",\n\t\t\t\t\"repayment\":\"等額本息\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"delay\": \"0\",\n\t\t\t\t\"delay_days\": \"0\",\n\t\t\t\t\"status\":\"4\",\n\t\t\t\t\"sub_status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\",\n\t\t\t},\n\t\t\t\"product\":{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"輕鬆學貸\"\n\t\t\t},\n\t        \"certification\": [\n          \t{\n          \t     \"id\": \"1\",\n          \t     \"name\": \"身分證認證\",\n          \t     \"description\": \"身分證認證\",\n          \t     \"alias\": \"id_card\",\n           \t    \"user_status\": \"1\"\n          \t},\n          \t{\n          \t    \"id\": \"2\",\n           \t    \"name\": \"學生證認證\",\n          \t    \"description\": \"學生證認證\",\n           \t   \"alias\": \"student\",\n           \t   \"user_status\": \"1\"\n          \t}\n          ],\n      \"user\": {\n         \"name\": \"陳XX\",\n          \"age\": 28,\n          \"school_name\": \"國立宜蘭大學\",\n          \"id_number\": \"G1231XXXXX\"\n      }\n\t\t}\n   }",
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
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/transfer/info/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/transfer/list",
    "title": "出借方 債權標的列表",
    "version": "0.2.0",
    "name": "GetTransferList",
    "group": "Transfer",
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
            "description": "<p>Transfer ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "principal",
            "description": "<p>剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest",
            "description": "<p>已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "delay_interest",
            "description": "<p>已發生延滯利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination",
            "description": "<p>Combination ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "expire_time",
            "description": "<p>流標時間</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "accounts_receivable",
            "description": "<p>應收帳款</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
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
            "type": "Number",
            "optional": false,
            "field": "target.product_id",
            "description": "<p>產品ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.credit_level",
            "description": "<p>信用指數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.loan_amount",
            "description": "<p>核准金額</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.interest_rate",
            "description": "<p>核可利率</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.repayment",
            "description": "<p>還款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay",
            "description": "<p>是否逾期 0:無 1:逾期中</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.delay_days",
            "description": "<p>逾期天數</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.reason",
            "description": "<p>借款原因</p>"
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
            "type": "Number",
            "optional": false,
            "field": "target.status",
            "description": "<p>狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.sub_status",
            "description": "<p>狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.created_at",
            "description": "<p>申請日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "target.user",
            "description": "<p>借款人基本資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "target.user.age",
            "description": "<p>年齡</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "target.user.sex",
            "description": "<p>性別 F/M</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "combination_list",
            "description": "<p>整包債權列表</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.id",
            "description": "<p>Combination ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "combination_list.combination_no",
            "description": "<p>整包轉讓號</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "combination_list.password",
            "description": "<p>是否需要密碼</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.count",
            "description": "<p>筆數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.amount",
            "description": "<p>整包轉讓價金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.principal",
            "description": "<p>整包剩餘本金</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.interest",
            "description": "<p>整包已發生利息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.delay_interest",
            "description": "<p>整包已發生延滯息</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.max_instalment",
            "description": "<p>最大剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.min_instalment",
            "description": "<p>最小剩餘期數</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "combination_list.bargain_rate",
            "description": "<p>增減價比率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Float",
            "optional": false,
            "field": "combination_list.interest_rate",
            "description": "<p>平均年表利率(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "combination_list.accounts_receivable",
            "description": "<p>整包應收帳款</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\": 17,\n\t\t\t\t\"amount\": 4010,\n\t\t\t\t\"principal\": 5000,\n\t\t\t\t\"interest\": 6,\n\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\"bargain_rate\": -19.9,\n\t\t\t\t\"instalment\": 18,\n\t\t\t\t\"combination\": 2,\n\t\t\t\t\"expire_time\": 1547913599,\n\t\t\t\t\"accounts_receivable\": 5398,\n\t\t\t\t\"target\": {\n\t\t\t\t\t\"id\": 9,\n\t\t\t\t\t\"target_no\": \"STN2019011414213\",\n\t\t\t\t\t\"product_id\": 1,\n\t\t\t\t\t\"credit_level\": 3,\n\t\t\t\t\t\"user_id\": 19,\n\t\t\t\t\t\"loan_amount\": 5000,\n\t\t\t\t\t\"interest_rate\": 7,\n\t\t\t\t\t\"instalment\": 3,\n\t\t\t\t\t\"repayment\": 1,\n\t\t\t\t\t\"delay\": 0,\n\t\t\t\t\t\"delay_days\": 0,\n\t\t\t\t\t\"reason\": \"\",\n\t\t\t\t\t\"remark\": \"\",\n\t\t\t\t\t\"status\": 5,\n\t\t\t\t\t\"sub_status\": 0,\n\t\t\t\t\t\"created_at\": 1547444954,\n\t\t\t\t\t\"user\": {\n\t\t\t\t\t\t\"sex\": \"M\",\n\t\t\t\t\t\t\"age\": 30\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t}\n\t\t\t],\n\t\t\t\"combination_list\": [\n\t\t\t{\n\t\t\t\t\"id\": 2,\n\t\t\t\t\"combination_no\": \"PKG1547810358209546\",\n\t\t\t\t\"password\": false,\n\t\t\t\t\"count\": 3,\n\t\t\t\t\"amount\": 12028,\n\t\t\t\t\"principal\": 15000,\n\t\t\t\t\"interest\": 16,\n\t\t\t\t\"max_instalment\": 18,\n\t\t\t\t\"min_instalment\": 3,\n\t\t\t\t\"delay_interest\": 0,\n\t\t\t\t\"bargain_rate\": -19.9,\n\t\t\t\t\"interest_rate\": 8.56,\n\t\t\t\t\"accounts_receivable\": 15626\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/list"
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
  },
  {
    "type": "get",
    "url": "/transfer/list",
    "title": "出借方 取得債權標的列表",
    "version": "0.1.0",
    "name": "GetTransferList",
    "group": "Transfer",
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
            "type": "Object",
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
            "description": "<p>債權轉讓金額</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "instalment",
            "description": "<p>債權剩餘期數</p>"
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
            "type": "Object",
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
            "type": "Object",
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
            "field": "target.credit_level",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/transfer/list"
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
  },
  {
    "type": "post",
    "url": "/v2/transfer/apply",
    "title": "出借方 申請債權收購",
    "version": "0.2.0",
    "name": "PostTransferApply",
    "group": "Transfer",
    "description": "<p>可收購多筆，若為整包債轉，一次只能單筆，否則回覆債權轉讓標的不存在</p>",
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
            "type": "Number",
            "optional": false,
            "field": "transfer_ids",
            "description": "<p>投資IDs IDs ex: 1,3,10,21</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "4,12",
            "optional": true,
            "field": "password",
            "description": "<p>整包債轉密碼</p>"
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
            "field": "804",
            "description": "<p>雙方不可同使用者</p>"
          },
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
            "field": "815",
            "description": "<p>整包債權密碼錯誤</p>"
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
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "Object"
        },
        {
          "title": "809",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"809\"\n}",
          "type": "Object"
        },
        {
          "title": "810",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"810\"\n}",
          "type": "Object"
        },
        {
          "title": "815",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"815\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/transfer/apply",
    "title": "出借方 申請債權收購",
    "version": "0.1.0",
    "name": "PostTransferApply",
    "group": "Transfer",
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
            "type": "Number",
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
          "type": "Object"
        },
        {
          "title": "810",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"810\"\n}",
          "type": "Object"
        },
        {
          "title": "804",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"804\"\n}",
          "type": "Object"
        },
        {
          "title": "202",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"202\"\n}",
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/transfer/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/transfer/batch",
    "title": "出借方 智能收購",
    "name": "PostTransferBatch",
    "version": "0.2.0",
    "group": "Transfer",
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
            "type": "Number",
            "optional": false,
            "field": "delay",
            "defaultValue": "0",
            "description": "<p>逾期標的 0:正常標的 1:逾期標的 default:0</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "user_id",
            "description": "<p>指定使用者ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "product_id",
            "defaultValue": "all",
            "description": "<p>產品ID 全部：all 複選使用逗號隔開1,2,3,4</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_s",
            "description": "<p>利率區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "interest_rate_e",
            "description": "<p>利率區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "instalment_s",
            "description": "<p>期數區間下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "instalment_e",
            "description": "<p>期數區間上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Float",
            "size": "-20..20",
            "optional": true,
            "field": "bargain_rate_s",
            "description": "<p>增減價比率下限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "Float",
            "size": "-20..20",
            "optional": true,
            "field": "bargain_rate_e",
            "description": "<p>增減價比率上限(%)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "credit_level",
            "defaultValue": "all",
            "description": "<p>信用評等 全部：all 複選使用逗號隔開1,2,3,4,5,6,7,8,9,10,11,12,13</p>"
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
            "field": "section",
            "defaultValue": "all",
            "description": "<p>標的狀態 全部:all 全案:0 部分案:1</p>"
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
            "field": "sex",
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
            "type": "Object",
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
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "transfer_ids",
            "description": "<p>篩選出的Transfer ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"total_amount\": 70000,\n\t\t\t\"total_amount\": 20000,\n\t\t\t\"total_count\": 4,\n\t\t\t\"max_instalment\": 12,\n\t\t\t\"min_instalment\": 12,\n\t\t\t\"XIRR\": 8,\n\t\t\t\"transfer_ids\": [\n\t\t\t\t\"33\"\n\t\t\t]\n\t\t}\n   }",
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
          "type": "Object"
        },
        {
          "title": "203",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"203\"\n}",
          "type": "Object"
        },
        {
          "title": "208",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"208\"\n}",
          "type": "Object"
        },
        {
          "title": "209",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"209\"\n}",
          "type": "Object"
        },
        {
          "title": "212",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"212\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/transfer/batch"
      }
    ]
  },
  {
    "type": "post",
    "url": "/transfer/batch/:batch_id",
    "title": "出借方 智能收購確認",
    "version": "0.1.0",
    "name": "PostTransferBatch",
    "group": "Transfer",
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
            "type": "Number",
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
            "type": "Object",
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
          "type": "Object"
        },
        {
          "title": "812",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"812\"\n}",
          "type": "Object"
        },
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
          "title": "205",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"205\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/Transfer.php",
    "groupTitle": "Transfer",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/transfer/batch/:batch_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/user/chagetoken",
    "title": "會員 交換Token",
    "version": "0.2.0",
    "name": "GetUserChagetoken",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n  \t\"expiry_time\": \"1522673418\"\n  }\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/chagetoken"
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
  },
  {
    "type": "get",
    "url": "/user/chagetoken",
    "title": "會員 交換Token",
    "version": "0.1.0",
    "name": "GetUserChagetoken",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n  \t\"expiry_time\": \"1522673418\"\n  }\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/chagetoken"
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
  },
  {
    "type": "get",
    "url": "/v2/user/editpwphone",
    "title": "會員 交易、修改密碼簡訊",
    "version": "0.2.0",
    "name": "GetUserEditpwphone",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/editpwphone"
      }
    ]
  },
  {
    "type": "get",
    "url": "/user/editpwphone",
    "title": "會員 發送驗證簡訊 （修改密碼、交易密碼）",
    "version": "0.1.0",
    "name": "GetUserEditpwphone",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
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
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/editpwphone"
      }
    ]
  },
  {
    "type": "get",
    "url": "/v2/user/info",
    "title": "會員 個人資訊",
    "version": "0.2.0",
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
            "type": "Number",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "company",
            "description": "<p>1:法人帳號 0:自然人帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "incharge",
            "description": "<p>1:負責人 0:代理人</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "agent",
            "description": "<p>代理人User ID</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"id\": \"1\",\n  \t\"name\": \"\",\n  \t\"picture\": \"https://graph.facebook.com/2495004840516393/picture?type=large\",\n  \t\"nickname\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"investor_status\": \"1\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",\n  \t\"id_number\": null,\n  \t\"transaction_password\": true,\n  \t\"investor\": 1,  \n  \t\"company\": 0,  \n  \t\"incharge\": 0,  \n  \t\"created_at\": \"1522651818\",     \n  \t\"updated_at\": \"1522653939\",     \n  \t\"expiry_time\": \"1522675539\"     \n  }\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/info"
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
  },
  {
    "type": "get",
    "url": "/user/info",
    "title": "會員 個人資訊",
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
            "type": "Number",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/info"
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
  },
  {
    "type": "get",
    "url": "/v2/user/promote",
    "title": "會員 推薦有獎",
    "version": "0.2.0",
    "name": "GetUserPromote",
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"promote_code\": \"D221BL0K\",\n  \t\"promote_url\": \"http://dev.influxfin.com?promote_code=D221BL0K\",\n  \t\"promote_qrcode\": \"http://chart.apis.google.com/chart?cht=qr&choe=UTF-8&chl=http%3A%2F%2Fdev.influxfin.com%3Fpromote_code%3DD221BL0K&chs=200x200\"\n  }\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/promote"
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/user/promote",
    "title": "會員 推薦有獎",
    "version": "0.1.0",
    "name": "GetUserPromote",
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
    "group": "User",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/promote"
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
  },
  {
    "type": "post",
    "url": "/v2/user/bind",
    "title": "會員 綁定第三方帳號",
    "version": "0.2.0",
    "name": "PostUserBind",
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
            "field": "access_token",
            "description": "<p>Facebook AccessToken</p>"
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
            "field": "305",
            "description": "<p>access_token錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "306",
            "description": "<p>已綁定過第三方帳號</p>"
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
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "305",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"305\"\n}",
          "type": "Object"
        },
        {
          "title": "306",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"306\"\n}",
          "type": "Object"
        },
        {
          "title": "308",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"308\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/bind"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/bind",
    "title": "會員 綁定第三方帳號",
    "version": "0.1.0",
    "name": "PostUserBind",
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
          "type": "Object"
        },
        {
          "title": "306",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"306\"\n}",
          "type": "Object"
        },
        {
          "title": "308",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"308\"\n}",
          "type": "Object"
        },
        {
          "title": "309",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"309\"\n}",
          "type": "Object"
        },
        {
          "title": "310",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"310\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/bind"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/contact",
    "title": "會員 投訴與建議",
    "version": "0.2.0",
    "name": "PostUserContact",
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
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/contact"
      }
    ],
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
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
  },
  {
    "type": "post",
    "url": "/user/contact",
    "title": "會員 投訴與建議",
    "version": "0.1.0",
    "name": "PostUserContact",
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
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/contact"
      }
    ],
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
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
  },
  {
    "type": "post",
    "url": "/user/credittest",
    "title": "會員 測一測",
    "version": "0.1.0",
    "name": "PostUserCredittest",
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/credittest"
      }
    ],
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
          "type": "Object"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v2/user/editpw",
    "title": "會員 修改密碼",
    "version": "0.2.0",
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
            "description": "<p>密碼長度錯誤</p>"
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
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
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
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/editpw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/editpw",
    "title": "會員 修改密碼",
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
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
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/editpw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/edittpw",
    "title": "會員 設置交易密碼",
    "version": "0.2.0",
    "name": "PostUserEdittpw",
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
            "size": "6..50",
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
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "216",
            "description": "<p>不支援法人帳號使用</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "311",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"311\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
          "title": "216",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"216\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/edittpw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/edittpw",
    "title": "會員 設置交易密碼",
    "version": "0.1.0",
    "name": "PostUserEdittpw",
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "311",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"311\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        },
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
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/edittpw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/forgotpw",
    "title": "會員 忘記密碼",
    "version": "0.2.0",
    "name": "PostUserForgotpw",
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
            "description": "<p>密碼長度錯誤</p>"
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/forgotpw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/forgotpw",
    "title": "會員 忘記密碼",
    "version": "0.1.0",
    "name": "PostUserForgotpw",
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/forgotpw"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/login",
    "title": "會員 用戶登入",
    "version": "0.2.0",
    "name": "PostUserLogin",
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
            "size": "6..50",
            "optional": false,
            "field": "password",
            "description": "<p>密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\": 1\t\t\n     }\n   }",
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
            "field": "312",
            "description": "<p>密碼長度錯誤</p>"
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
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/login"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/login",
    "title": "會員 用戶登入",
    "version": "0.1.0",
    "name": "PostUserLogin",
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
          "type": "Object"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/login"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/register",
    "title": "會員 註冊",
    "version": "0.2.0",
    "name": "PostUserRegister",
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
            "size": "6..50",
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
            "type": "String",
            "optional": true,
            "field": "access_token",
            "description": "<p>Facebook AccessToken</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
            "size": "0..16",
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
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\": 1\t\t\n     }\n   }",
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
            "description": "<p>密碼長度錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "305",
            "description": "<p>AccessToken無效</p>"
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "Object"
        },
        {
          "title": "305",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"305\"\n}",
          "type": "Object"
        },
        {
          "title": "308",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"308\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/register"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/register",
    "title": "會員 註冊",
    "version": "0.1.0",
    "name": "PostUserRegister",
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
          "type": "Object"
        },
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
          "type": "Object"
        },
        {
          "title": "312",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/register"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/registerphone",
    "title": "會員 註冊簡訊",
    "version": "0.2.0",
    "name": "PostUserRegisterphone",
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
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
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
          "type": "Object"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/registerphone"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/registerphone",
    "title": "會員 發送驗證簡訊 (註冊)",
    "version": "0.1.0",
    "name": "PostUserRegisterphone",
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
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
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
          "type": "Object"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/registerphone"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/smsloginphone",
    "title": "會員 忘記密碼簡訊",
    "version": "0.2.0",
    "name": "PostUserSmsloginphone",
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
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
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
          "type": "Object"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/smsloginphone"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/smsloginphone",
    "title": "會員 發送驗證簡訊 （忘記密碼）",
    "version": "0.1.0",
    "name": "PostUserSmsloginphone",
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
          "content": "{\n  \"result\": \"SUCCESS\",\n}",
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
          "type": "Object"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/smsloginphone"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/sociallogin",
    "title": "會員 第三方登入",
    "version": "0.2.0",
    "name": "PostUserSociallogin",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "access_token",
            "description": "<p>Facebook AccessToken</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\",\n     \t\"expiry_time\": \"1522673418\",\n\t\t\t\"first_time\": 1\t\t\n     }\n   }",
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
          "type": "Object"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/sociallogin"
      }
    ]
  },
  {
    "type": "post",
    "url": "/user/sociallogin",
    "title": "會員 第三方登入",
    "version": "0.1.0",
    "name": "PostUserSociallogin",
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
          "type": "Object"
        },
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "Object"
        },
        {
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
          "type": "Object"
        },
        {
          "title": "101",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"101\"\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/user/sociallogin"
      }
    ]
  },
  {
    "type": "post",
    "url": "/v2/user/upload",
    "title": "會員 上傳圖片",
    "version": "0.2.0",
    "name": "PostUserUpload",
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
            "type": "file",
            "allowedValues": [
              "\"*.jpg\"",
              "\"*.png\"",
              "\"*.gif\""
            ],
            "optional": false,
            "field": "image",
            "description": "<p>圖片檔</p>"
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
            "field": "image_id",
            "description": "<p>圖片ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"image_id\": 191\n  }\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "https://dev-api.influxfin.com/api/v2/user/upload"
      }
    ],
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
          "type": "Object"
        },
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
] });
