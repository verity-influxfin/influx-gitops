define({ "api": [
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
