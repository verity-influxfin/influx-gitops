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
          "type": "Object"
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
          "type": "Object"
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
    "url": "/order/amount",
    "title": "Repayment Total Amount",
    "group": "Order",
    "version": "0.1.0",
    "name": "GetOrderAmount",
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
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "5000-300000",
            "optional": false,
            "field": "amount",
            "description": "<p>Amount</p>"
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
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "principal",
            "description": "<p>Total Principal</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "interest",
            "description": "<p>Total Interest</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "total_payment",
            "description": "<p>Total Amount</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"list\": [\n  \t{\n  \t\t\"instalment\": 3,\n  \t\t\"principal\": 20000,\n  \t\t\"interest\": 434,\n  \t\t\"total_payment\": 20434\n  \t},\n  \t{\n  \t\t\"instalment\": 6,\n  \t\t\"principal\": 20000,\n  \t\t\"interest\": 691,\n  \t\t\"total_payment\": 20691\n  \t},\n  \t{\n  \t\t\"instalment\": 12,\n  \t\t\"principal\": 20000,\n  \t\t\"interest\": 1210,\n  \t\t\"total_payment\": 21210\n  \t},\n  \t{\n  \t\t\"instalment\": 18,\n  \t\t\"principal\": 20000,\n  \t\t\"interest\": 1732,\n  \t\t\"total_payment\": 21732\n  \t},\n  \t{\n  \t\t\"instalment\": 24,\n  \t\t\"principal\": 20000,\n  \t\t\"interest\": 2270,\n  \t\t\"total_payment\": 22270\n  \t}\n  \t]\n  }\n }",
          "type": "Object"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "ArgumentError",
            "description": "<p>Insert Error.</p>"
          },
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          "title": "ArgumentError",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"ArgumentError\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "merchant_order_no",
            "description": "<p>Custom Order NO</p>"
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
            "field": "merchant_order_no",
            "description": "<p>Custom Order NO</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>User Phone Number</p>"
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
            "description": "<p>Amount</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>Order Status 0:Pendding 1:Success 2:Verifying 8:Cancel 9:Fail</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>Created Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"merchant_order_no\": \"2017545465159489\",\n  \t\"phone\": \"0977254651\",\n  \t\"product_id\": 2,\n  \t\"amount\": 6000,\n  \t\"instalment\": 3,\n  \t\"status\": 0,\n  \t\"created_at\": 1547558418\n  }\n}",
          "type": "Object"
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
            "field": "OrderNotFound",
            "description": "<p>Merchant Order NO Not Found.</p>"
          },
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
          "title": "OrderNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"OrderNotFound\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
    "description": "<p>Dates In 90-Days Range.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "start_date",
            "defaultValue": "today",
            "description": "<p>Start Date (YYYY-mm-dd)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "end_date",
            "defaultValue": "today",
            "description": "<p>End Date (YYYY-mm-dd)</p>"
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
            "field": "merchant_order_no",
            "description": "<p>Custom Order NO</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>User Phone Number</p>"
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
            "description": "<p>Amount</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "status",
            "description": "<p>Order Status 0:Pendding 1:Success 2:Verifying 8:Cancel 9:Fail</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "created_at",
            "description": "<p>Created Unix Timestamp</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "    HTTP/1.1 200 OK\n   {\n     \"result\": \"SUCCESS\",\n     \"data\": {\n\t\t\t\"list\":[\n\t\t\t\t{\n     \t\t\t\"merchant_order_no\": \"2017545465159489\",\n     \t\t\t\"phone\": \"0977254651\",\n     \t\t\t\"product_id\": 2,\n     \t\t\t\"amount\": 6000,\n     \t\t\t\"instalment\": 3,\n     \t\t\t\"status\": 0,\n     \t\t\t\"created_at\": 1547558418\n\t\t\t\t}\n\t\t\t]\n     }\n   }",
          "type": "Object"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "ArgumentError",
            "description": "<p>Insert Error.</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "AuthorizationRequired",
            "description": "<p>Authorization Required.</p>"
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          "title": "ArgumentError",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"ArgumentError\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
    },
    "filename": "/var/www/html/p2plending/application/controllers/cooperation/Order.php",
    "groupTitle": "Order"
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
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "SUCCESS",
          "content": "{\n\t\t\"result\":\"SUCCESS\",\n\t\t\"data\":{\n\t\t\t\"list\":[\n\t\t\t\t{\n\t\t\t\t\"id\":\"1\",\n\t\t\t\t\"name\":\"學生區\",\n\t\t\t\t\"description\":\"學生區\",\n\t\t\t\t\"loan_range_s\":\"5000\",\n\t\t\t\t\"loan_range_e\":\"120000\",\n\t\t\t\t\"interest_rate_s\":\"12\",\n\t\t\t\t\"interest_rate_e\":\"20\",\n\t\t\t\t\"charge_platform\":\"3\",\n\t\t\t\t\"charge_platform_min\":\"500\",\n\t\t\t\t\"instalment\": [\n\t\t\t\t\t3,\n\t\t\t\t    6,\n\t\t\t\t    12,\n\t\t\t\t    18,\n\t\t\t\t    24\n\t\t\t\t  ]\n\t\t\t\t}\n\t\t\t]\n\t\t}\n}",
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "5000-300000",
            "optional": false,
            "field": "amount",
            "description": "<p>Amount</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
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
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule",
            "description": "<p>Amortization Schedule</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.amount",
            "description": "<p>Loan Amount</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.instalment",
            "description": "<p>Number Of Instalment</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.rate",
            "description": "<p>Annual Rate Of Interest.(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.date",
            "description": "<p>Start Date</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total_payment",
            "description": "<p>PMT</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "amortization_schedule.leap_year",
            "description": "<p>Leap Year</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.year_days",
            "description": "<p>Days This Year</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.XIRR",
            "description": "<p>XIRR(%)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule.schedule",
            "description": "<p>Repayment Schedule</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.instalment",
            "description": "<p>Current Instalment</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amortization_schedule.schedule.repayment_date",
            "description": "<p>Repayment Due Date</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.days",
            "description": "<p>Current Days</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.remaining_principal",
            "description": "<p>Remaining Principal</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.principal",
            "description": "<p>Principal</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.interest",
            "description": "<p>Interest</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.schedule.total_payment",
            "description": "<p>Current Repayment Amount</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "amortization_schedule.total",
            "description": "<p>Total</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.principal",
            "description": "<p>Total Principal</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.interest",
            "description": "<p>Total Interest</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amortization_schedule.total.total_payment",
            "description": "<p>Total Amount</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\",\n  \"data\": {\n  \t\"amortization_schedule\": {\n  \t\t\"amount\": 20000,\n  \t\t\"instalment\": 6,\n  \t\t\"rate\": 10,\n  \t\t\"date\": \"2019-01-21\",\n  \t\t\"total_payment\": 3432,\n  \t\t\"leap_year\": false,\n  \t\t\"year_days\": 365,\n  \t\t\"XIRR\": 10.47,\n  \t\t\"schedule\": {\n  \t\t\t\"1\": {\n  \t\t\t\t\"instalment\": 1,\n  \t\t\t\t\"repayment_date\": \"2019-03-10\",\n  \t\t\t\t\"days\": 48,\n  \t\t\t\t\"remaining_principal\": 20000,\n  \t\t\t\t\"principal\": 3169,\n  \t\t\t\t\"interest\": 263,\n  \t\t\t\t\"total_payment\": 3432\n  \t\t\t},\n  \t\t\t\"2\": {\n  \t\t\t\t\"instalment\": 2,\n  \t\t\t\t\"repayment_date\": \"2019-04-10\",\n  \t\t\t\t\"days\": 31,\n  \t\t\t\t\"remaining_principal\": 16831,\n  \t\t\t\t\"principal\": 3289,\n  \t\t\t\t\"interest\": 143,\n  \t\t\t\t\"total_payment\": 3432\n  \t\t\t},\n  \t\t\t\"3\": {\n  \t\t\t\t\"instalment\": 3,\n  \t\t\t\t\"repayment_date\": \"2019-05-10\",\n  \t\t\t\t\"days\": 30,\n  \t\t\t\t\"remaining_principal\": 13542,\n  \t\t\t\t\"principal\": 3321,\n  \t\t\t\t\"interest\": 111,\n  \t\t\t\t\"total_payment\": 3432\n  \t\t\t},\n  \t\t\t\"4\": {\n  \t\t\t\t\"instalment\": 4,\n  \t\t\t\t\"repayment_date\": \"2019-06-10\",\n  \t\t\t\t\"days\": 31,\n  \t\t\t\t\"remaining_principal\": 10221,\n  \t\t\t\t\"principal\": 3345,\n  \t\t\t\t\"interest\": 87,\n  \t\t\t\t\"total_payment\": 3432\n  \t\t\t},\n  \t\t\t\"5\": {\n  \t\t\t\t\"instalment\": 5,\n  \t\t\t\t\"repayment_date\": \"2019-07-10\",\n  \t\t\t\t\"days\": 30,\n  \t\t\t\t\"remaining_principal\": 6876,\n  \t\t\t\t\"principal\": 3375,\n  \t\t\t\t\"interest\": 57,\n  \t\t\t\t\"total_payment\": 3432\n  \t\t\t},\n  \t\t\t\"6\": {\n  \t\t\t\t\"instalment\": 6,\n  \t\t\t\t\"repayment_date\": \"2019-08-10\",\n  \t\t\t\t\"days\": 31,\n  \t\t\t\t\"remaining_principal\": 3501,\n  \t\t\t\t\"principal\": 3501,\n  \t\t\t\t\"interest\": 30,\n  \t\t\t\t\"total_payment\": 3531\n  \t\t\t}\n  \t\t},\n  \t\t\"total\": {\n  \t\t\t\"principal\": 20000,\n  \t\t\t\"interest\": 691,\n  \t\t\t\"total_payment\": 20691\n  \t\t}\n  \t}\n\t}\n}",
          "type": "Object"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "ArgumentError",
            "description": "<p>Insert Error.</p>"
          },
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          "title": "ArgumentError",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"ArgumentError\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
  },
  {
    "type": "post",
    "url": "/order/cancel",
    "title": "Cancel Order",
    "group": "Order",
    "version": "0.1.0",
    "name": "PostOrderCancel",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Bearer MD5(SHA1(CooperationID + merchant_order_no + Timestamp) + CooperationKey)</p>"
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
    "description": "<p>Only Status In Pendding</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "merchant_order_no",
            "description": "<p>Custom Order NO</p>"
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
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"result\": \"SUCCESS\"\n}",
          "type": "Object"
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
            "field": "OrderNotFound",
            "description": "<p>Merchant Order NO Not Found.</p>"
          },
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
          "title": "OrderNotFound",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"OrderNotFound\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
            "description": "<p>Bearer MD5(SHA1(CooperationID + amount + instalment + item_count + item_name + item_price + merchant_order_no + phone + product_id + Timestamp) + CooperationKey)</p>"
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
            "optional": false,
            "field": "product_id",
            "description": "<p>Product ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "10",
            "optional": false,
            "field": "phone",
            "description": "<p>User Phone Number</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "5000-300000",
            "optional": false,
            "field": "amount",
            "description": "<p>Amount</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instalment",
            "description": "<p>Number Of Instalment</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "8..32",
            "optional": false,
            "field": "merchant_order_no",
            "description": "<p>Custom Order NO</p>"
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
            "type": "Object",
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
          "type": "Object"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "ArgumentError",
            "description": "<p>Insert Error.</p>"
          },
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
          },
          {
            "group": "401",
            "optional": false,
            "field": "IllegalIP",
            "description": "<p>Illegal IP Address.</p>"
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
          },
          {
            "group": "409",
            "optional": false,
            "field": "OrderExists",
            "description": "<p>Merchant Order NO Exists.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ArgumentError",
          "content": "HTTP/1.1 400 Not Found\n{\n  \"error\": \"ArgumentError\"\n}",
          "type": "json"
        },
        {
          "title": "InsertError",
          "content": "HTTP/1.1 409 Not Found\n{\n  \"error\": \"InsertError\"\n}",
          "type": "json"
        },
        {
          "title": "OrderExists",
          "content": "HTTP/1.1 409 Not Found\n{\n  \"error\": \"OrderExists\"\n}",
          "type": "json"
        },
        {
          "title": "AuthorizationRequired",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"AuthorizationRequired\"\n}",
          "type": "json"
        },
        {
          "title": "IllegalIP",
          "content": "HTTP/1.1 401 Not Found\n{\n  \"error\": \"IllegalIP\"\n}",
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
