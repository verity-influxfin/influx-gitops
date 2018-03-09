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
            "description": "<p>(required) 代號</p>"
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
            "description": "<p>(required) 姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_number",
            "description": "<p>(required) 身分證字號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_card_date",
            "description": "<p>(required) 發證日期(民國) ex:1060707</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_card_place",
            "description": "<p>(required) 發證地點</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "birthday",
            "description": "<p>(required) 生日(民國) ex:1020101</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>(required) 地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "front_image",
            "description": "<p>身分證正面照</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "back_image",
            "description": "<p>身分證背面照</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "person_image",
            "description": "<p>本人照</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"3\",\n  \t\"front_image\": \"https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg\",    \n  \t\"back_image\": \"https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg\",    \n  \t\"person_image\": \"https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg\",    \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\",     \n  \t\"name\": \"toy\",\n  \t\"id_number\": \"G121111111\",\n  \t\"id_card_date\": \"1060707\",\n  \t\"id_card_place\": \"北市\",\n  \t\"birthday\": \"1020101\",\n  \t\"address\": \"全家就是我家\"\n  }\n}",
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
            "description": "<p>用戶認證狀態：0:未完成 1:已完成</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"身分證認證\",\n\t\t\t\t\"description\":\"身分證認證\",\n\t\t\t\t\"alias\":\"id_card\",\n\t\t\t\t\"user_status\":1,\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"健保卡認證\",\n\t\t\t\t\"description\":\"健保卡認證\",\n\t\t\t\t\"user_status\":1,\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
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
    "type": "get",
    "url": "/certification/student",
    "title": "認證 學生證認證資料",
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"certification_id\": \"3\",\n  \t\"school\": \"國立宜蘭大學\",\n  \t\"department\": \"電機工程學系\",\n  \t\"student_id\": \"1496B032\",\n  \t\"front_image\": \"https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg\",    \n  \t\"back_image\": \"https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg\",    \n  \t\"status\": \"0\",     \n  \t\"created_at\": \"1518598432\",     \n  \t\"updated_at\": \"1518598432\"     \n  }\n}",
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
    "name": "GetCertificationStudent"
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
            "field": "200",
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationHealthcard"
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
            "optional": false,
            "field": "name",
            "description": "<p>(required) 姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_number",
            "description": "<p>(required) 身分證字號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_card_date",
            "description": "<p>(required) 發證日期(民國) ex:1060707</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_card_place",
            "description": "<p>(required) 發證地點</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "birthday",
            "description": "<p>(required) 生日(民國) ex:1020101</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>(required) 地址</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "front_image",
            "description": "<p>(required) 身分證正面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "back_image",
            "description": "<p>(required) 身分證背面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "person_image",
            "description": "<p>(required) 本人照</p>"
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
            "field": "200",
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
          "title": "504",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"504\"\n}",
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
    "url": "/certification/student",
    "title": "認證 學生證認證",
    "group": "Certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "school",
            "description": "<p>(required) 學校名稱</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "department",
            "description": "<p>(required) 系所</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "student_id",
            "description": "<p>(required) 學號</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "front_image",
            "description": "<p>(required) 學生證正面照</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "back_image",
            "description": "<p>(required) 學生證背面照</p>"
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
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Certification.php",
    "groupTitle": "Certification",
    "name": "PostCertificationStudent"
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
            "description": "<p>(required) 代號</p>"
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
            "description": "<p>Token錯誤.</p>"
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
    "type": "get",
    "url": "/product/applyinfo/{ID}",
    "title": "借款方產品 申請借款資訊",
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
            "description": "<p>申請額度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核可額度</p>"
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
            "field": "bank_account",
            "description": "<p>借款人收款帳號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "virtual_account",
            "description": "<p>還款虛擬帳號</p>"
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
            "description": "<p>狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 5:已結案 9:申請失敗</p>"
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"id\":\"1\",\n\t\t\t\"product_id\":\"2\",\n\t\t\t\"product\":{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\"alias\":\"FA\"\n\t\t\t},\n\t\t\t\"user_id\":\"1\",\n\t\t\t\"amount\":\"5000\",\n\t\t\t\"loan_amount\":\"\",\n\t\t\t\"interest_rate\":\"\",\n\t\t\t\"total_interest\":\"\",\n\t\t\t\"instalment\":\"3\",\n\t\t\t\"bank_account\":\"\",\n\t\t\t\"virtual_account\":\"\",\n\t\t\t\"remark\":\"\",\n\t\t\t\"status\":\"0\",\n\t\t\t\"created_at\":\"1520421572\"\n\t\t}\n   }",
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
            "description": "<p>Token錯誤.</p>"
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
    "title": "借款方產品 申請借款列表",
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
            "description": "<p>申請額度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "loan_amount",
            "description": "<p>核可額度</p>"
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
            "field": "remark",
            "description": "<p>備註</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 8:已取消 9:申請失敗 10:已結案</p>"
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
          "content": "   {\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"product_id\":\"2\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\t\"alias\":\"FA\"\n\t\t\t\t},\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"interest_rate\":\"0,\n\t\t\t\t\"total_interest\":\"\",\n\t\t\t\t\"instalment\":\"3\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t},\n\t\t\t{\n\t\t\t\t\"id\":\"2\",\n\t\t\t\t\"product_id\":\"2\",\n\t\t\t\t\"product\":{\n\t\t\t\t\t\"id\":\"2\",\n\t\t\t\t\t\"name\":\"輕鬆學貸\",\n\t\t\t\t\t\"description\":\"輕鬆學貸\",\n\t\t\t\t\t\"alias\":\"FA\"\n\t\t\t\t},\n\t\t\t\t\"user_id\":\"1\",\n\t\t\t\t\"amount\":\"5000\",\n\t\t\t\t\"loan_amount\":\"\",\n\t\t\t\t\"interest_rate\":\"\",\n\t\t\t\t\"total_interest\":\"\",\n\t\t\t\t\"instalment\":\"3\",\n\t\t\t\t\"remark\":\"\",\n\t\t\t\t\"status\":\"0\",\n\t\t\t\t\"created_at\":\"1520421572\"\n\t\t\t}\n\t\t\t]\n\t\t}\n   }",
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
    "type": "get",
    "url": "/product/category",
    "title": "借款方產品 取得分類列表",
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
    "title": "借款方產品 取得產品資訊",
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
            "field": "certifications",
            "description": "<p>需完成的認證列表</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "ratings",
            "description": "<p>評級方式資訊</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "instalment",
            "description": "<p>可申請期數</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"product\":\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"alias\":\"FT\",\n\t\t\t\t\"category\":\"3\",\n\t\t\t\t\"parent_id\":\"0\",\n\t\t\t\t\"rank\":\"0\",\n\t\t\t\t\"loan_range_s\":\"12222\",\n\t\t\t\t\"loan_range_e\":\"14333333\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"14\",\n\t\t\t\t\"charge_platform\":\"0\",\n\t\t\t\t\"charge_platform_min\":\"0\",\n\t\t\t\t\"charge_overdue\":\"0\",\n\t\t\t\t\"charge_sub_loan\":\"0\",\n\t\t\t\t\"charge_prepayment\":\"0\",\n\t\t\t\t\"certifications\":[{\"id\":\"1\",\"name\":\"身分證認證\",\"description\":\"身分證認證\",\"alias\":\"id_card\"},{\"id\":\"2\",\"name\":\"學生證認證\",\"description\":\"學生證認證\",\"alias\":\"student\"}],\n\t\t\t\t\"ratings\":{\"1\":{\"id\":\"1\",\"status\":1,\"value\":0},\"2\":{\"id\":\"2\",\"status\":1,\"value\":\"123\"},\"3\":{\"id\":\"3\",\"status\":1,\"value\":0}},\n\t\t\t\t\"instalment\": [3,6,12,18]\n\t\t\t}\n\t\t}\n}",
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
    "title": "借款方產品 取得產品列表",
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
            "field": "instalment",
            "description": "<p>可申請期數</p>"
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
            "field": "category",
            "description": "<p>分類資訊</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"category\": {\n\t\t\t\t\"id\": \"1\",\n\t\t\t\t\"name\": \"學生區\",\n\t\t\t\t\"description\": \"學生區啊啊啊啊啊啊啊\",\n\t\t\t\t\"parent_id\": \"0\",\n\t\t\t\t\"rank\": \"0\",\n\t\t\t\t\"loan_range_s\":\"12222\",\n\t\t\t\t\"loan_range_e\":\"14333333\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"14\",\n\t\t\t\t\"charge_platform\":\"0\",\n\t\t\t\t\"charge_platform_min\":\"0\",\n\t\t\t\t\"instalment\": \"[3,6,12,18]\"\n\t\t\t},\n\t\t\t\"list\":[\n\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"parent_id\":\"0\",\n\t\t\t\t\"rank\":\"0\",\n\t\t\t\t\"loan_range_s\":\"12222\",\n\t\t\t\t\"loan_range_e\":\"14333333\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"14\",\n\t\t\t\t\"charge_platform\":\"0\",\n\t\t\t\t\"charge_platform_min\":\"0\",\n\t\t\t\t\"instalment\": \"[3,6,12,18]\"\n\t\t\t}\n\t\t\t]\n\t\t}\n}",
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
    "title": "借款方產品 申請借款",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "product_id",
            "description": "<p>(required) 產品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "amount",
            "description": "<p>(required) 借款金額</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "instalment",
            "description": "<p>(required) 申請期數</p>"
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
            "field": "302",
            "description": "<p>會員不存在</p>"
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
            "field": "200",
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
          "title": "302",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"302\"\n}",
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
    "url": "/product/applyedit",
    "title": "借款方產品 申請借款修改",
    "group": "Product",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>(required) Targets ID</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "action",
            "description": "<p>(required) 動作 contract：確認合約 cancel：取消申請</p>"
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
            "field": "406",
            "description": "<p>此動作不存在</p>"
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
            "description": "<p>參數錯誤.</p>"
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
          "title": "406",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"406\"\n}",
          "type": "json"
        },
        {
          "title": "406",
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/Product.php",
    "groupTitle": "Product",
    "name": "PostProductApplyedit"
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
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "front_image",
            "description": "<p>金融卡正面照</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "back_image",
            "description": "<p>金融卡背面照</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"user_id\": \"1\",\n  \t\"bank_code\": \"882\",\n  \t\"bank_account\": \"2147483647\",     \n  \t\"front_image\": \"xxxxxxxxxxxxxxxxxx\",    \n  \t\"back_image\": \"xxxxxxxxxxxxxxxxxx\"     \n  }\n}",
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
            "description": "<p>姓名（空值則代表未完成身份驗證）</p>"
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
            "field": "status",
            "description": "<p>用戶狀態</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "block_status",
            "description": "<p>是否為黑名單</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id_number",
            "description": "<p>身分證字號（空值則代表未完成身份驗證）</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"id\": \"1\",\n  \t\"name\": \"\",\n  \t\"phone\": \"0912345678\",\n  \t\"status\": \"1\",\n  \t\"id_number\": null,\n  \t\"investor\": 1,\n  \t\"block_status\": \"0\"     \n  }\n}",
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
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserBankaccount",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "200",
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
            "field": "200",
            "description": "<p>參數錯誤.</p>"
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
          "title": "200",
          "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"200\"\n}",
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
            "description": "<p>(required) 內容</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "image1",
            "description": "<p>附圖1</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "image2",
            "description": "<p>附圖2</p>"
          },
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
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
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
        }
      ]
    }
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
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端 default:0</p>"
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n\t\t\t\"first_time\": 1,\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\"\n     }\n   }",
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
            "description": "<p>參數錯誤.</p>"
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
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端 default:0</p>"
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
            "field": "200",
            "description": "<p>參數錯誤.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "201",
            "description": "<p>新增時發生錯誤.</p>"
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
            "field": "200",
            "description": "<p>參數錯誤.</p>"
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
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "investor",
            "description": "<p>1:投資端 0:借款端 default:0</p>"
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
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n\t\t\t\"first_time\": 1,\n     \t\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE\"\n     }\n   }",
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
            "description": "<p>參數錯誤.</p>"
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
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/controllers/api/User.php",
    "groupTitle": "User",
    "name": "PostUserSociallogin"
  }
] });
