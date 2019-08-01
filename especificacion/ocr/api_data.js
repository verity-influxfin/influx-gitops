define({
  "api": [
    {
      "type": "post",
      "url": "api/user/login",
      "title": "經銷商 登入",
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
              "field": "account",
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
            "content": "{\n" +
                "    \"result\": \"SUCCESS\",\n" +
                "    \"data\": {\n" +
                "        \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjb21wYW55X2lkIjoiMyIsImNvbXBhbnlfYWNjb3VudCI6IjA5MzYwMDAwMDAiLCJyb2xlX2lkIjoiMCIsImNvb3BlcmF0aW9uX2lkIjoiQ082ODU2Njg4MiIsImNvb3BlcmF0aW9uX2tleSI6ImFlMmQyMDhkNmM0Y2FjMGVmMWMxMDgwYjMzODkyMGMwIiwiaXRlbV9saXN0IjoiNyIsImF1dGhfb3RwIjoiMDAwMDAwIiwiZXhwaXJ5X3RpbWUiOjE1NTcyNjU0ODJ9.dlVs2XxRr_24FF5IycsZ2VjITabDwu92Nf_weTQQpZ0\",\n" +
                "        \"expiry_time\": 1557265482\n" +
                "    }\n" +
                "}",
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
              "description": "<p>帳戶已黑名單</p>"
            },
            {
              "group": "Error 4xx",
              "optional": false,
              "field": "200",
              "description": "<p>參數錯誤</p>"
            },            {
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
            "content": "{\n" +
                "  \"result\": \"ERROR\",\n" +
                "  \"error\": 304,\n" +
                "  \"data\": {\n" +
                "    \"remind_count\": 0\n" +
                "  }\n" +
                "}",
            "type": "Object"
          },
          {
            "title": "312",
            "content": "{\n  \"result\": \"ERROR\",\n  \"error\": \"312\"\n}",
            "type": "Object"
          }
        ]
      },
      "filename": "application/controllers/api/User.php",
      "groupTitle": "User",
      "sampleRequest": [
        {
          "url": "/api/user/login"
        }
      ]
    }
  ]
})