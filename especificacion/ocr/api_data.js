define({
  "api": [
    {
      "type": "POST",
      "url": "ocr/idcardfront",
      "title": "身分證正面",
      "version": "0.1.0",
      "name": "OcrIdcardFront",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "idcardfront_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.ON1",
              "description": "<p>身份證姓名</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON2",
              "description": "<p>身份證生日(民國) yyymmdd ex: 0900707、1010707</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON3",
              "description": "<p>身份證發證日期(民國) yyymmdd ex: 0900707、1010707</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "10",
              "field": "result.ON4",
              "description": "<p>身份證字號</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON7",
              "description": "<p>身份證發證地區</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.ON8",
              "description": "<p>身份證發證型態 0:初 1:換 2:補</p>",
              "allowedValues": [0,1,2]
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "10",
              "field": "result.ON15",
              "description": "<p>膠膜號碼 ex: null or 1234567890</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.ON31",
              "description": "<p>身份證字型大小/間距(是否與模型相符) 0:無法識別 1:是 2:否</p>",
              "allowedValues": [0,1,2]
            }
            ,
            {
              "group": "Parameter",
              "type": "float",
              "field": "result.ON23",
              "description": "<p>整張身分證圖像識別相似度(%)</p>"
            }
            ,
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.ON6",
              "description": "<p>身份證人臉(未被蓋住) 0:無人臉 1:有人臉</p>",
              "allowedValues": [0,1]
            }

          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"ON1\": \"王普匯\",\n" +
                "        \"ON2\": 0750218,\n" +
                "        \"ON3\": 1012318,\n" +
                "        \"ON4\": \"A1234567890\",\n" +
                "        \"ON7\": \"台北市\",\n" +
                "        \"ON8\": 0,\n" +
                "        \"ON15\": \"01234567890\",\n" +
                "        \"ON31\": 1,\n" +
                "        \"ON23\": 81,\n" +
                "        \"ON6\": 1,\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/idcardback",
      "title": "身分證反面",
      "version": "0.1.0",
      "name": "OcrIdcardBack",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "idcardback_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.ON9",
              "description": "<p>父姓名</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON10",
              "description": "<p>母姓名</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON11",
              "description": "<p>配偶姓名</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON12",
              "description": "<p>役別</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON13",
              "description": "<p>出生地</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON5",
              "description": "<p>身份證住址</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "10",
              "field": "result.ON25",
              "description": "<p>條碼資料</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "10",
              "field": "result.ON14",
              "description": "<p>綠色號碼</p>"
            }
            ,
            {
              "group": "Parameter",
              "type": "float",
              "field": "result.ON24",
              "description": "<p>整張身分證背面圖像識別相似度(%)</p>"
            }

          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"ON9\": \"王普匯\",\n" +
                "        \"ON10\": \"林秀蓮\",\n" +
                "        \"ON11\": \"呂志玲\",\n" +
                "        \"ON12\": \"常備\",\n" +
                "        \"ON13\": \"台北市\",\n" +
                "        \"ON5\": \"台北市中山區松江路111號11樓之2\",\n" +
                "        \"ON25\": \"A1234567890\",\n" +
                "        \"ON14\": \"1234567890\",\n" +
                "        \"ON24\": 92,\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/personimage",
      "title": "持證自拍",
      "version": "0.1.0",
      "name": "OcrPersonImage",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "person_image",
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
              "group": "Parameter",
              "type": "String",
              "size": "10",
              "field": "result.ON28",
              "description": "<p>身分證字號</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.ON27",
              "description": "<p>識別存在身分證 0:無法識別 1:是 2:否</p>",
              "allowedValues": [0,1,2]
            },
            {
              "group": "Parameter",
              "type": "float",
              "field": "result.ON26",
              "description": "<p>姿勢辨識(是否為本人持證)相似度(%)</p>",
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON16",
              "description": "<p>人臉資料建檔(身分證)儲存比對目標 base64</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "field": "result.ON17",
              "description": "<p>人臉資料建檔(本人人臉)儲存比對目標 base64</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"ON28\": \"A1234567890\",\n" +
                "        \"ON27\": 1,\n" +
                "        \"ON26\": 79,\n" +
                "        \"ON16\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVz2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLMjlE8HZdYZbGAE\",\n" +
                "        \"ON17\": \"yJpZCI6IjM3ODIiLCJwaG9uZSI6IjA5MzAwMTA5NTciLCJhdXRoX290cCI6IjAwMDAwMCIsImV4cGlyeV90aW1lIjoxNTY0NzQ5ODU0LCJpbnZlc3RvciI6MCwiY29tcGW50IjowfQ.Vzh_xjigIrXyJZIw0Mz-WUYDihrjT4EbPxCXST5ZyVk\",\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/healthcard",
      "title": "健保卡",
      "version": "0.1.0",
      "name": "OcrHealthCard",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "healthcard_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.ON18",
              "description": "<p>健保卡姓名</p>"
            },{
              "group": "Parameter",
              "type": "String",
              "field": "result.ON19",
              "description": "<p>健保卡生日(民國) yyymmdd ex: 0900707、1010707</p>"
            },{
              "group": "Parameter",
              "type": "String",
              "size": "10",
              "field": "result.ON20",
              "description": "<p>健保卡身分證字號</p>"
            },
            {
              "group": "Parameter",
              "type": "String",
              "size": "12",
              "field": "result.ON22",
              "description": "<p>健保卡卡號</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.ON30",
              "description": "<p>整張健保卡圖像識別 0:無法識別 1:是 2:否</p>",
              "allowedValues": [0,1,2]
            },
            {
              "group": "Parameter",
              "type": "String[]",
              "field": "result.ON29",
              "description": "<p>健保卡字型大小/間距(是否與模型相符) 0:無法識別 1:是 2:否</p>",
              "allowedValues": [0,1,2]
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.ON21",
              "description": "<p>人臉資料建檔(健保卡)儲存比對目標 base64</p>"}
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"ON18\": \"王普匯\",\n" +
                "        \"ON19\": \"0750218\",\n" +
                "        \"ON20\": \"A1234567890\",\n" +
                "        \"ON22\": \"123456789011\",\n" +
                "        \"ON30\": 1,\n" +
                "        \"ON29\": 1,\n" +
                "        \"ON17\": \"yJpZCI6IjM3ODIiLCJwaG9uZSI6IjA5MzAwMTA5NTciLCJhdXRoX290cCI6IjAwMDAwMCIsImV4cGlyeV90aW1lIjoxNTY0NzQ5ODU0LCJpbnZlc3RvciI6MCwiY29tcGW50IjowfQ.Vzh_xjigIrXyJZIw0Mz-WUYDihrjT4EbPxCXST5ZyVk\",\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/studentcard",
      "title": "學生證正反面",
      "version": "0.1.0",
      "name": "OcrStudentCard",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "studentcard_front_image",
              "description": "<p>圖片檔</p>"
            },{
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "studentcard_back_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.OS1",
              "description": "<p>學生證姓名</p>"
            },{
              "group": "Parameter",
              "type": "Number",
              "field": "result.OS2",
              "description": "<p>學生證在學學校</p>"
            },{
              "group": "Parameter",
              "type": "String",
              "field": "result.OS4",
              "description": "<p>學生證系所</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.OS5",
              "description": "<p>學生證學號</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "field": "result.OS6",
              "description": "<p>學制 3:五專 0:大學 1:碩士 2:博士</p>",
              "allowedValues": [0,1,2,3]
            },
            {
              "group": "Parameter",
              "type": "Number",
              "size": "16",
              "field": "result.OS8",
              "description": "<p>悠遊卡號(選)</p>"
            },
            {
              "group": "Parameter",
              "type": "Number",
              "size": "11",
              "field": "result.OS9",
              "description": "<p>一卡通卡號(選)</p>"
            },
            {
              "group": "Parameter",
              "type": "String[]",
              "field": "result.OS10",
              "description": "<p>註冊章(哪個學期)- 學期:0:上 1:下 ex:[2,1]</p>"
            },
            {
              "group": "Parameter",
              "type": "float",
              "field": "result.OS11",
              "description": "<p>整張學生證圖像正面識別->與該校學生證模型相似度(%)</p>"
            },
            {
              "group": "Parameter",
              "type": "float",
              "field": "result.OS12",
              "description": "<p>整張學生證圖像背面識別->與該校學生證模型相似度(%)</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"OS1\": \"王普匯\",\n" +
                "        \"OS2\": \"國立台灣大學\",\n" +
                "        \"OS4\": \"法律系\",\n" +
                "        \"OS5\": 123456789011,\n" +
                "        \"OS6\": 0,\n" +
                "        \"OS8\": 1234567890123456,\n" +
                "        \"OS9\": null,\n" +
                "        \"OS10\": [3,0],\n" +
                "        \"OS11\": 77,\n" +
                "        \"OS12\": 62,\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/debitcard",
      "title": "金融卡",
      "version": "0.1.0",
      "name": "OcrDebitCard",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "debitcard_front_image",
              "description": "<p>圖片檔</p>"
            },{
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "debitcard_back_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.OF1",
              "description": "<p>銀行名稱</p>"
            },{
              "group": "Parameter",
              "type": "String",
              "field": "result.OF6",
              "description": "<p>金融卡帳號</p>"
            },{
              "group": "Parameter",
              "type": "float",
              "field": "result.OF4",
              "description": "<p>整張金融卡正面->與該銀行卡模型相似度(%)</p>"
            },
            {
              "group": "Parameter",
              "type": "float",
              "field": "result.OF5",
              "description": "<p>整張金融卡背面->與該銀行卡模型相似度(%)</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"OF1\": \"國泰世華銀行\",\n" +
                "        \"OF6\": \"1234567890\",\n" +
                "        \"OF4\": 89,\n" +
                "        \"OF5\": 79,\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/bankbook",
      "title": "存摺封面",
      "version": "0.1.0",
      "name": "OcrBankBook",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "bankbook_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.OO30",
              "description": "<p>銀行名稱</p>"
            },{
              "group": "Parameter",
              "type": "String",
              "field": "result.OO31",
              "description": "<p>帳號</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"OO30\": \"國泰世華銀行\",\n" +
                "        \"OO31\": \"1234567890\",\n" +
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/passbook",
      "title": "存摺內頁影本",
      "version": "0.1.0",
      "name": "OcrPassBook",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "passbook_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.OO15",
              "description": "<p>日期/科目/存(提)/餘額</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"OO15\": {\n" +
                "           ['107/02/05','薪資','23000','23000'],\n" +
                "           ['107/02/06','提款','-1000','22000']\n" +
                "        },\n"+
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    },{
      "type": "POST",
      "url": "ocr/webpassbook",
      "title": "網銀截圖",
      "version": "0.1.0",
      "name": "OcrWebPassBook",
      "group": "OCR",
      "parameter": {
        "fields": {
          "Parameter": [
            {
              "group": "Parameter",
              "type": "file",
              "allowedValues": [
                "\"*.jpg\"",
                "\"*.png\""
              ],
              "optional": false,
              "field": "webpassbook_image",
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
              "group": "Parameter",
              "type": "String",
              "field": "result.OO32",
              "description": "<p>日期/科目/存(提)/餘額</p>"
            }
          ]
        },
        "examples": [
          {
            "title": "SUCCESS",
            "content": "{\n" +
                "  \"result\": \"SUCCESS\",\n" +
                "  \"data\": {\n" +
                "    \"list\": [\n" +
                "      {\n" +
                "        \"OO32\": {\n" +
                "           ['107/02/05','薪資','23000','23000'],\n" +
                "           ['107/02/06','提款','-1000','22000']\n" +
                "        },\n"+
                "      }\n" +
                "    ]\n" +
                "  }\n" +
                "}",
            "type": "Object"
          }
        ]
      },
      //"sampleRequest": [
      //  {
      //    "url": "ocr/idcardfront"
      //  }
      //],
      "groupTitle": "OCR",
    }
  ]
})