define({ "api": [
  {
    "type": "get",
    "url": "/certification/healthcard",
    "title": "認證 健保卡認證資料",
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
            "field": "front_image",
            "description": "<p>健保卡正面照</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:等待驗證 1:驗證成功 2:驗證失敗</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"3\",\n  \t\"front_image\": \"https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg\",    \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\"     \n  }\n}",
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
            "description": "<p>Token錯誤.</p>"
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationHealthcard"
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"身分證認證\",\n\t\t\t\t\"description\":\"身分證認證\",\n\t\t\t\t\"alias\":\"id_card\",\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"健保卡認證\",\n\t\t\t\t\"description\":\"健保卡認證\",\n\t\t\t\t\"alias\":\"health_card\",\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "GetCertificationList"
  },
  {
    "type": "post",
    "url": "/certification/healthcard",
    "title": "認證 健保卡認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "front_image",
            "description": "<p>(required) 健保卡正面照</p>"
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
            "field": "303",
            "description": "<p>新增時發生錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "100",
            "description": "<p>Token錯誤.</p>"
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
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationHealthcard"
  },
  {
    "type": "get",
    "url": "/product/category",
    "title": "貸款產品 取得分類列表",
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
            "field": "parent_id",
            "description": "<p>父層級</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "rank",
            "description": "<p>排序</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"parent_id\":\"0\",\n\t\t\t\t\"rank\":\"0\"\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"房屋方案\",\n\t\t\t\t\"description\":\"房屋方案\",\n\t\t\t\t\"parent_id\":\"0\",\n\t\t\t\t\"rank\":\"0\"\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "GetProductCategory"
  },
  {
    "type": "get",
    "url": "/product/info/{ID}",
    "title": "貸款產品 取得產品資訊",
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
            "field": "alias",
            "description": "<p>產品</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "category",
            "description": "<p>分類ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "parent_id",
            "description": "<p>父層產品</p>"
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
            "type": "String",
            "optional": false,
            "field": "charge_overdue",
            "description": "<p>逾期管理費(%/天)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "charge_sub_loan",
            "description": "<p>轉貸服務費(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "charge_prepayment",
            "description": "<p>提還手續費(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "ratings",
            "description": "<p>評級方式資訊</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"product\":\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"alias\":\"FT\",\n\t\t\t\t\"category\":\"3\",\n\t\t\t\t\"parent_id\":\"0\",\n\t\t\t\t\"rank\":\"0\",\n\t\t\t\t\"loan_range_s\":\"12222\",\n\t\t\t\t\"loan_range_e\":\"14333333\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"14\",\n\t\t\t\t\"charge_platform\":\"0\",\n\t\t\t\t\"charge_platform_min\":\"0\",\n\t\t\t\t\"charge_overdue\":\"0\",\n\t\t\t\t\"charge_sub_loan\":\"0\",\n\t\t\t\t\"charge_prepayment\":\"0\",\n\t\t\t\t\"ratings\":\"{\"1\":{\"id\":\"1\",\"status\":1,\"value\":0},\"2\":{\"id\":\"2\",\"status\":1,\"value\":\"123\"},\"3\":{\"id\":\"3\",\"status\":1,\"value\":0}}\"\n\t\t\t}\n\t\t}\n}",
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
          }
        ]
      },
      "examples": [
        {
          "title": "401",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"401\"\n}",
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
    "title": "貸款產品 取得產品列表",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "category",
            "description": "<p>產品分類ID</p>"
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
            "field": "parent_id",
            "description": "<p>父層產品</p>"
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
            "field": "category",
            "description": "<p>分類資訊</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"category\": {\n\t\t\t\t\"id\": \"1\",\n\t\t\t\t\"name\": \"學生區\",\n\t\t\t\t\"description\": \"學生區啊啊啊啊啊啊啊\",\n\t\t\t\t\"parent_id\": \"0\",\n\t\t\t\t\"rank\": \"0\"\n\t\t\t},\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"parent_id\":\"0\",\n\t\t\t\t\"rank\":\"0\"\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
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
    "type": "get",
    "url": "/user/bankaccount",
    "title": "會員 取得金融帳號",
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
            "field": "user_id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_code",
            "description": "<p>銀行代碼</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bank_account",
            "description": "<p>銀行帳號</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"bank_code\": \"882\",\n  \t\"bank_account\": \"2147483647\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "GetUserBankaccount",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "100",
            "description": "<p>Token錯誤.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "100",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"100\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/user/bankaccount",
    "title": "會員 綁定金融帳號",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "bank_code",
            "description": "<p>(required) 銀行代碼</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "bank_account",
            "description": "<p>(required) 銀行帳號</p>"
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
            "field": "303",
            "description": "<p>新增時發生錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "100",
            "description": "<p>Token錯誤.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "303",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"303\"\n}",
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserBankaccount"
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
            "optional": false,
            "field": "type",
            "description": "<p>(required) 登入類型（&quot;facebook&quot;）</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "access_token",
            "description": "<p>(required) access_token</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\"\n  }\n}",
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
            "field": "306",
            "description": "<p>access_token錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "307",
            "description": "<p>此種類型已綁定過了</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "100",
            "description": "<p>Token錯誤.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "306",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"306\"\n}",
          "type": "json"
        },
        {
          "title": "307",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"307\"\n}",
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
            "description": "<p>(required) 手機號碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>(required) 密碼</p>"
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\"\n  }\n}",
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
            "field": "304",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "305",
            "description": "<p>密碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "json"
        },
        {
          "title": "305",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"305\"\n}",
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
    "name": "PostUserLogin"
  },
  {
    "type": "post",
    "url": "/user/register",
    "title": "會員 註冊（簡訊驗證）",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>(required) 手機號碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>(required) 設定密碼</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>(required) 收到的驗證碼</p>"
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
            "field": "302",
            "description": "<p>驗證碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "303",
            "description": "<p>新增時發生錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
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
    "title": "會員 發送驗證簡訊",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>(required) 手機號碼</p>"
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
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
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
          "title": "201",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"201\"\n}",
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
    "url": "/user/sociallogin",
    "title": "會員 第三方登入",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>(required) 登入類型（&quot;facebook&quot;）</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "access_token",
            "description": "<p>(required) access_token</p>"
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\"\n  }\n}",
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
            "field": "304",
            "description": "<p>會員不存在</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "305",
            "description": "<p>密碼錯誤</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>欄位錯誤.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "304",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"304\"\n}",
          "type": "json"
        },
        {
          "title": "305",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"305\"\n}",
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
    "name": "PostUserSociallogin"
  }
] });
