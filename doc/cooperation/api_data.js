define({ "api": [
  {
    "type": "get",
    "url": "/cooperation/info",
    "title": "Company Information",
    "group": "Cooperation",
    "version": "0.1.0",
    "name": "GetCooperationInformation",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "company",
            "description": "<p>Company</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "tax_id",
            "description": "<p>tax ID number</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"company\": \"普匯金融科技股份有限公司\",\n  \t\"tax_id\": \"68566881\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Cooperation.php",
    "groupTitle": "Cooperation",
    "error": {
      "fields": {
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/cooperation/contact",
    "title": "Contact Us",
    "group": "Cooperation",
    "version": "0.1.0",
    "name": "PostCooperationContact",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + content + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>Content</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "RequiredArguments",
            "description": "<p>Required Arguments.</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ],
        "409": [
          {
            "group": "409",
            "optional": false,
            "field": "InsertError",
            "description": "<p>Insert Error.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "InsertError",
          "content": "HTTP/1.1 409 Not Found\n{\n  \"error\": \"InsertError\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "RequiredArguments",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"RequiredArguments\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Cooperation.php",
    "groupTitle": "Cooperation"
  },
  {
    "type": "get",
    "url": "/order/info",
    "title": "Order Information",
    "group": "Order",
    "version": "0.1.0",
    "name": "GetOrderInfo",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
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
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"company\": \"普匯金融科技股份有限公司\",\n  \t\"tax_id\": \"68566881\",\n  \t\"name\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",  \n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "RequiredArguments",
            "description": "<p>Required Arguments.</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "RequiredArguments",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"RequiredArguments\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/order/list",
    "title": "Order List",
    "group": "Order",
    "version": "0.1.0",
    "name": "GetOrderList",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
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
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"company\": \"普匯金融科技股份有限公司\",\n  \t\"tax_id\": \"68566881\",\n  \t\"name\": \"陳霈\",\n  \t\"phone\": \"0912345678\",\n  \t\"my_promote_code\": \"9JJ12CQ5\",  \n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "RequiredArguments",
            "description": "<p>Required Arguments.</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "RequiredArguments",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"RequiredArguments\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/order/product",
    "title": "Product List",
    "group": "Order",
    "version": "0.1.0",
    "name": "GetOrderProduct",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
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
            "description": "<p>Product Name</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Description</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "rank",
            "description": "<p>Rank</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_range_s",
            "description": "<p>Minimum Loan Amount</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "loan_range_e",
            "description": "<p>Maximum Loan Amount</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_s",
            "description": "<p>Minimum Interest Rate(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest_rate_e",
            "description": "<p>Maximum Interest Rate(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "charge_platform",
            "description": "<p>Platform Fee Rate(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "charge_platform_min",
            "description": "<p>Minimum Platform Fee</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"rank\":\"0\",\n\t\t\t\t\"loan_range_s\":\"5000\",\n\t\t\t\t\"loan_range_e\":\"120000\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"20\",\n\t\t\t\t\"charge_platform\":\"3\",\n\t\t\t\t\"charge_platform_min\":\"500\",\n\t\t\t\t\"instalment\": [\n\t\t\t\t\t3,\n\t\t\t\t    6,\n\t\t\t\t    12,\n\t\t\t\t    18,\n\t\t\t\t    24\n\t\t\t\t  ]\n\t\t\t\t}\n\t\t\t]\n\t\t}\n}",
          "type": "Object"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "error": {
      "fields": {
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/order/schedule",
    "title": "Repayment Schedule",
    "group": "Order",
    "version": "0.1.0",
    "name": "GetOrderSchedule",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "size": "5000-300000",
            "optional": false,
            "field": "amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>期數</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result",
            "description": "<p>SUCCESS</p>"
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
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"amortization_schedule\": {\n      \t\"amount\": \"12000\",\n      \t\"instalment\": \"6\",\n      \t\"rate\": \"9\",\n      \t\"date\": \"2018-04-17\",\n     \t \t\"total_payment\": 2053,\n     \t \t\"leap_year\": false,\n      \t\"year_days\": 365,\n      \t\"XIRR\": 0.0939,\n      \t\"schedule\": {\n          \t\"1\": {\n              \t\"instalment\": 1,\n              \t\"repayment_date\": \"2018-06-10\",\n              \t\"days\": 54,\n              \t\"remaining_principal\": \"12000\",\n              \t\"principal\": 1893,\n              \t\"interest\": 160,\n              \t\"total_payment\": 2053\n          \t},\n          \t\"2\": {\n               \t\"instalment\": 2,\n              \t\"repayment_date\": \"2018-07-10\",\n              \t\"days\": 30,\n               \t\"remaining_principal\": 10107,\n               \t\"principal\": 1978,\n               \t\"interest\": 75,\n                \t\"total_payment\": 2053\n           \t},\n          \t\"3\": {\n                \t\"instalment\": 3,\n                \t\"repayment_date\": \"2018-08-10\",\n                \t\"days\": 31,\n                \t\"remaining_principal\": 8129,\n               \t\"principal\": 1991,\n               \t\"interest\": 62,\n                \t\"total_payment\": 2053\n            \t}\n        \t},\n       \t\"total\": {\n          \t\"principal\": 12000,\n            \t\"interest\": 391,\n            \t\"total_payment\": 12391\n        \t}\n  \t}\n\t}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order",
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "RequiredArguments",
            "description": "<p>Required Arguments.</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "RequiredArguments",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"RequiredArguments\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/order/create",
    "title": "Create Order",
    "group": "Order",
    "version": "0.1.0",
    "name": "PostOrderCreate",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)</p>"
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "CooperationID",
            "description": "<p>CooperationID</p>"
          },
          {
            "group": "Header",
            "type": "Number",
            "optional": false,
            "field": "Timestamp",
            "description": "<p>Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Authorization",
          "content": "Bearer fcea920f7412b5da7be0cf42b8c93759",
          "type": "String"
        },
        {
          "title": "CooperationID",
          "content": "CO12345678",
          "type": "String"
        },
        {
          "title": "Timestamp",
          "content": "1546932175",
          "type": "Number"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "size": "5000-300000",
            "optional": false,
            "field": "amount",
            "description": "<p>金額</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
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
            "type": "String",
            "optional": false,
            "field": "item_count",
            "description": "<p>商品數量，多項商品時，以逗號分隔</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "item_price",
            "description": "<p>商品單價，多項商品時，以逗號分隔</p>"
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
          "title": "Success-Response:",
          "content": "    HTTP/1.1 200 OK\n   {\n     \"result\": \"SUCCESS\",\n\t\t\"merchant_order_no\": \"123456789\",\n\t\t\"order_no\": \"20180405113558632\",\n     \"request_token\": \"fcea920f7412b5da7be0cf42b8c93759\"\n   }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "RequiredArguments",
            "description": "<p>Required Arguments.</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          }
        ],
        "403": [
          {
            "group": "403",
            "optional": false,
            "field": "TimeOut",
            "description": "<p>Time Out.</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "CooperationNotFound",
            "description": "<p>Cooperation not found.</p>"
          }
        ],
        "409": [
          {
            "group": "409",
            "optional": false,
            "field": "InsertError",
            "description": "<p>Insert Error.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "InsertError",
          "content": "HTTP/1.1 409 Not Found\n{\n  \"error\": \"InsertError\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "TimeOut",
          "content": "HTTP/1.1 403 Not Found\n{\n  \"error\": \"TimeOut\"\n}",
          "type": "json"
        },
        {
          "title": "RequiredArguments",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"RequiredArguments\"\n}",
          "type": "json"
        },
        {
          "title": "CooperationNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"CooperationNotFound\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order"
  }
] });
