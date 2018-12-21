define({ "api": [
  {
    "type": "get",
    "url": "/member/info",
    "title": "公司資訊",
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
            "description": "<p>MD5(SHA1(cooperation_id &amp; time) + CooperationKEY)</p>"
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
            "field": "company",
            "description": "<p>公司名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "tax_id",
            "description": "<p>統一編號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>負責人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>負責人電話</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "my_promote_code",
            "description": "<p>邀請碼</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"company\": \"普匯金融科技股份有限公司\",\n  \t\"tax_id\": \"68566881\",\n  \t\"name\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",  \n  }\n}",
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
    "title": "回報問題",
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
            "description": "<p>MD5(SHA1(content &amp; cooperation_id &amp; time)+ CooperationKEY)</p>"
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
    "type": "get",
    "url": "/order/info",
    "title": "訂單資訊",
    "group": "Order",
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
            "description": "<p>MD5(SHA1(cooperation_id &amp; time) + CooperationKEY)</p>"
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
            "field": "company",
            "description": "<p>公司名稱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "tax_id",
            "description": "<p>統一編號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>負責人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>負責人電話</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "my_promote_code",
            "description": "<p>邀請碼</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"company\": \"普匯金融科技股份有限公司\",\n  \t\"tax_id\": \"68566881\",\n  \t\"name\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",  \n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "name": "GetOrderInfo"
  },
  {
    "type": "get",
    "url": "/order/schedule",
    "title": "還款計畫",
    "group": "Order",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "amount",
            "description": "<p>總金額</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "merchant_order_no",
            "description": "<p>自訂編號</p>"
          },
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
            "description": "<p>MD5(SHA1(amount &amp; instalment &amp; cooperation_id &amp; time)+ CooperationKEY)</p>"
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
            "field": "request_token",
            "description": "<p>Request Token</p>"
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
          "content": "{\n  \"result\": \"SUCCESS\",\n  \"amortization_schedule\": {\n      \"amount\": \"12000\",\n      \"instalment\": \"6\",\n      \"rate\": \"9\",\n      \"date\": \"2018-04-17\",\n      \"total_payment\": 2053,\n      \"leap_year\": false,\n      \"year_days\": 365,\n      \"XIRR\": 0.0939,\n      \"schedule\": {\n            \"1\": {\n              \"instalment\": 1,\n              \"repayment_date\": \"2018-06-10\",\n              \"days\": 54,\n              \"remaining_principal\": \"12000\",\n              \"principal\": 1893,\n              \"interest\": 160,\n              \"total_payment\": 2053\n          },\n          \"2\": {\n               \"instalment\": 2,\n              \"repayment_date\": \"2018-07-10\",\n              \"days\": 30,\n               \"remaining_principal\": 10107,\n               \"principal\": 1978,\n               \"interest\": 75,\n                \"total_payment\": 2053\n           },\n          \"3\": {\n                \"instalment\": 3,\n                \"repayment_date\": \"2018-08-10\",\n                \"days\": 31,\n                \"remaining_principal\": 8129,\n               \"principal\": 1991,\n               \"interest\": 62,\n                \"total_payment\": 2053\n            }\n        },\n       \"total\": {\n            \"principal\": 12000,\n            \"interest\": 391,\n            \"total_payment\": 12391\n        }\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "name": "GetOrderSchedule"
  },
  {
    "type": "post",
    "url": "/order/add",
    "title": "新增訂單",
    "group": "Order",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "amount",
            "description": "<p>總金額</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "merchant_order_no",
            "description": "<p>自訂編號</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名稱，多項商品時，以逗號分隔</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "item_count",
            "description": "<p>商品數量，多項商品時，以逗號分隔</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "item_price",
            "description": "<p>商品單價，多項商品時，以逗號分隔</p>"
          },
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
            "description": "<p>MD5(SHA1(amount &amp; instalment &amp; merchant_order_no &amp; item_name &amp; item_count &amp; item_price &amp; cooperation_id &amp; time)+ CooperationKEY)</p>"
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
            "field": "merchant_order_no",
            "description": "<p>廠商自訂編號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order_no",
            "description": "<p>訂單單號</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "request_token",
            "description": "<p>RequestToken</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "   {\n     \"result\": \"SUCCESS\",\n\t\t\"merchant_order_no\": \"A123456789\",\n\t\t\"order_no\": \"20180405113558632\",\n     \"request_token\": \"fcea920f7412b5da7be0cf42b8c93759\"\n   }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "name": "PostOrderAdd"
  }
] });
