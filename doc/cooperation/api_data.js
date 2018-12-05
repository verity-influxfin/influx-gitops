define({ "api": [
  {
    "type": "get",
    "url": "/member/info",
    "title": "合作商 個人資訊",
    "group": "Member",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "cooperation_id",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "time",
            "description": "<p>Unix Timestamp</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "cooperation_token",
            "description": "<p>SHA1(MD5(cooperation_id=xxxx+time=1543831102)+CooperationKEY)</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"id\": \"1\",\n  \t\"name\": \"\",\n  \t\"picture\": \"https://graph.facebook.com/2495004840516393/picture?type=large\",\n  \t\"nickname\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"investor_status\": \"1\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",\n  \t\"id_number\": null,\n  \t\"transaction_password\": true,\n  \t\"investor\": 1,  \n  \t\"created_at\": \"1522651818\",     \n  \t\"updated_at\": \"1522653939\",     \n  \t\"expiry_time\": \"1522675539\"     \n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Member.php",
    "groupTitle": "Member",
    "name": "GetMemberInfo"
  },
  {
    "type": "post",
    "url": "/member/contact",
    "title": "合作商 聯絡我們",
    "group": "Member",
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
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Member.php",
    "groupTitle": "Member",
    "name": "PostMemberContact"
  },
  {
    "type": "post",
    "url": "/member/register",
    "title": "合作商 註冊",
    "group": "Member",
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Member.php",
    "groupTitle": "Member",
    "name": "PostMemberRegister"
  }
] });
