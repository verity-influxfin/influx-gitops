<template>
  <div class="bank-wrapper" id="bank-wrapper">
      <div class="content-top skbank-top">
        <a class="btn send-btn skbank-send-btn skbank-banner-btn" href="https://reurl.cc/7r0XQ9" target="_blank">立即申請</a>
    </div>
    <div class="form">
        <div class="event-form">
            <div class="input-group "  style="margin: 3vw auto 3vw auto;">
                <img class="skbank-bottom-img" src="../asset/images/skBankTopContent.svg">
            </div>
            <div class="input-group group-text " style="margin: 3vw auto 4px auto;">
                <span style="color:#036EB7;">前三期利率</span>
                <span style="color:#E50012;">0.68</span>
                <span style="color:#036EB7;">起</span>
            </div>
            <div class="input-group skbank-blue-link" style="height:0px;">
            </div>
            <div class="input-group" style="width:70%; margin: 3vw auto 3vw auto;">
                <img class="group-img" src="../asset/images/skBankMidContent.svg">
                <img class="group-img" src="../asset/images/skBankMidContent1.svg">
                <img class="group-img" src="../asset/images/skBankMidContent2.svg">
                <img class="group-img" src="../asset/images/skBankMidContent3.svg">
            </div>
            <div class="input-group group-text " style="margin: 3vw auto 4px auto;">
                <span style="color:#036EB7;">客製化您的貸款方案</span>
            </div>
            <div class="input-group skbank-blue-link" style="height:0px;">
            </div>
            <div class="input-group" style=" margin: 3vw auto 3vw auto;">
                <img class="skbank-bottom-img" src="../asset/images/skBankBottomContent.svg">
            </div>
            <div class="input-group" style=" margin: 3vw auto 3vw auto;">
                <img class="skbank-bottom-img" src="../asset/images/skBankBottomContent1.svg">
            </div>
            <div class="input-group " style="margin: 3vw auto 3vw auto;">
              <a class="btn send-btn skbank-send-btn" href="https://reurl.cc/7r0XQ9" target="_blank" style="display: flex;justify-content: center; margin: 0px auto;">立即申請</a>
            </div>
        </div>
    </div>
    <div class="skbank-content-bottom">
        <img src= "../asset/images/skBankBottom.svg">
    </div>

    <!-- Modal -->
    <div class="modal fade" id="survey-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body 評估問卷">
                    <form class="表單" ref="borrowReport" v-if="!formCalculated">
                        <input type="hidden" name="identity" value="2" />
                        <div class="列">
                            <div class="標籤">1.我的教育程度：</div>
                            <div class="輸入欄位">
                                <select v-model="formGraduate" name="educational_level">
                                    <option selected disabled value="">-請選擇-</option>
                                    <option value="phD">博士</option>
                                    <option value="master">碩士</option>
                                    <option value="bachelor">學士</option>
                                    <option value="below">學士以下</option>
                                </select>
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">2.我的職業屬於：</div>
                            <div class="輸入欄位">
                                <select name="job">
                                    <option selected disabled>-請選擇-</option>
                                    <option :disabled="item.disabled" v-for="item, index in flattenWorkCategories" :key="index" :value="item.value">{{ item.title }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">3.任職公司是否屬於上市櫃、金融機構或公家機關：</div>
                            <div class="輸入欄位">
                                <select v-model="formCompany" name="is_top_enterprises">
                                    <option selected disabled value="">-請選擇-</option>
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">4.我的投保月薪約為：</div>
                            <div class="輸入欄位">
                                <input type="number" min="0" step="100" v-model="formSalary" name="insurance_salary">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">5.我在銀行的貸款餘額約為：</div>
                            <div class="輸入欄位">
                                <input type="number" min="0" step="100" v-model="formLoan" name="debt_amount">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">6.每月需攤還多少金額：</div>
                            <div class="輸入欄位">
                                <input type="number" min="0" step="100" v-model="formReturn" name="monthly_repayment">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">7.信用卡總額度約為：</div>
                            <div class="輸入欄位">
                                <input type="number" min="0" step="100" v-model="formCredit" name="creditcard_quota">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">8.近一個月信用卡帳單總金額約為：</div>
                            <div class="輸入欄位">
                                <input type="number" min="0" step="100" v-model="formTotal" name="creditcard_bill">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">暱稱：</div>
                            <div class="輸入欄位">
                                <input type="text" name="name">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤">E-mail：</div>
                            <div class="輸入欄位">
                                <input type="email" name="email">
                            </div>
                        </div>
                        <div class="列">
                            <div class="標籤"></div>
                            <div class="輸入欄位">
                                <button @click="calculateForm" type="button" :disabled="!isFormValid">取得報告</button>
                            </div>
                        </div>
                    </form>
                    <div class="結果" v-if="formCalculated">
                        <div class="展示區塊">
                            <img src="/images/alesis-phone-and-cash.svg" class="圖片">
                        </div>
                        <div class="內容">
                            <div class="標題">親愛的用戶您好：</div>
                            <div class="段落">
                                感謝您使用普匯的上班族貸款額度利率評估服務，<br>
                                經系統自動評估後，符合您的額度及利率區間如下：
                            </div>
                            <div class="數值">
                                <div class="列">
                                    <div class="標籤">1. 可借款額度：</div>
                                    <div class="值">{{borrowReportResult.amount | amount}}</div>
                                </div>
                                <div class="列">
                                    <div class="標籤">2. 借款利率區間：</div>
                                    <div class="值">{{borrowReportResult.rate}}</div>
                                </div>
                                <div class="列">
                                    <div class="標籤">3. 手續費金額：</div>
                                    <div class="值">{{borrowReportResult.platform_fee | amount}}</div>
                                </div>
                                <div class="列">
                                    <div class="標籤">4. 每期攤還金額約：</div>
                                    <div class="值">{{borrowReportResult.repayment}}</div>
                                </div>
                            </div>
                            <div class="說明 red">
                                ►申請普匯上班族貸不留任何信用紀錄，不佔銀行額度，不影響銀行信用評估結果。
                            </div>
                            <div class="說明 yellow">
                                ►僅為初步評估，實際貸款條件依照您真實提供的資料而定。
                            </div>
                            <div class="列">
                                <button class="btn btn-primary" type="button" @click="formCalculated=false">返回</button>
                                <a class="btn btn-primary" href="https://servicedesk.skbank.com.tw/CloudDesk/AuthOTP/SMSOTPForm3/36?CMPN_ID=20201214100035&CMPN_REF=inFlux_apply&CMPN_SRC=zOTHER" target="_target">
                                    前往銀行申請頁面
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
function viewport_convert(px = 0, vw = 0, vh = 0){
  if(px != 0){
      if(vw){
          return (100 * px / window.innerWidth);
      } else {
          return (100 * px / window.innerHeight);
      }
  } else if(vw != 0 && vh != 0){
      var w_h_arr = [];
      w_h_arr["width"] = Math.ceil((window.innerWidth * vw / 100));
      w_h_arr["height"] = Math.ceil((window.innerHeight * vh / 100));
      return w_h_arr;
  } else if(vw != 0){
      return Math.ceil((window.innerWidth * vw / 100));
  } else if(vh != 0){
      return Math.ceil((window.innerHeight * vh / 100));
  }
}

$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    let move = urlParams.get("move");
    let screen_width = screen.width;
    let move_range;
    if(move){
        if(screen_width > 767){
            move_range = 49;
        }else{
            move_range = 130;
        }
        let height_range = viewport_convert(0,move_range);
        $("html, body").animate({ scrollTop: height_range }, 2000);
    }
});

// 遠端資料
import WorkCategories from "../data/work_categories"

export default {
    data: () => ({
      phone                 : "",
      email                 : "",
      name                  : "",
      flattenWorkCategories : [],
      formGraduate          : "",
      formCompany           : "",
      formSalary            : "",
      formLoan              : "",
      formReturn            : "",
      formCredit            : "",
      formTotal             : "",
      formCalculated        : false,
      formAnswerTotal       : 0,
      formAnswerSpan        : 0,
      formAnswerFee         : 0,
      formAnswerPer         : 0,
      isFormValid           : true,
      borrowReportResult    : {},
      workCategories        : WorkCategories
    }),
    created() {
      $("title").text(`普匯x新光商銀`);
    },
    mounted() {

        // 管理與財經
        this.workCategories.n = this.workCategories.n.map(v => {
            this.flattenWorkCategories.push({
                disabled: true,
                title: v.des,
                value: "",
            })

            // 經營幕僚
            v.n = v.n.map((j) => {
                j.des = `　　${j.des}`
                this.flattenWorkCategories.push({
                    disabled: true,
                    title: j.des,
                    value: "",
                })
                // 儲備幹部
                j.n = j.n.map((l, k) => {
                    l.des = `　　　　${l.des}`
                    this.flattenWorkCategories.push({
                        disabled: false,
                        title: l.des,
                        value: l.no,
                    })
                    return l
                })
                return j
            })
            return v
        });
    },
    watch: {
      phone(newdata) {
        this.phone = newdata.replace(/[^\d]/g, "");
      }
    },
    methods: {
        calculateForm() {
            this.isFormValid = false;
            let data = new FormData(this.$refs.borrowReport);

            try {

                let attrs = [
                    'identity',
                    'educational_level',
                    'job',
                    'is_top_enterprises',
                    'insurance_salary',
                    'debt_amount',
                    'monthly_repayment',
                    'creditcard_quota',
                    'creditcard_bill',
                    'name',
                    'email',
                ];

                attrs.forEach( attr => {
                    if ('' == data.get(attr)) {
                        throw new Error(`Invalid value ` + attr);
                    }
                });
                axios({
                    url: `${location.origin}/getBorrowReport`,
                    method: 'post',
                    data: data,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                    }
                }).then((res) => {
                    this.borrowReportResult.amount = res.data.amount
                    this.borrowReportResult.rate = res.data.rate
                    this.borrowReportResult.platform_fee = res.data.platform_fee
                    this.borrowReportResult.repayment = res.data.repayment
                    this.formCalculated = true
                    this.isFormValid = true
                })
                .catch((error) => {
                    console.error('getBorrowReport 發生錯誤，請稍後再試');
                });

            } catch (e) {
                this.isFormValid = true;
                alert('請提供正確資料');
            }
        },
    }
};

</script>

<style lang="scss">
.bank-wrapper {
    overflow: hidden;
    width: 100%;
    position: relative;

    .content-top{
        background-repeat: no-repeat;
        position: relative;
        background-size: cover;
        width: 100%;
        height: 45vw;
    }

    .skbank-top{
        background-image: url("../asset/images/skBankTop.jpg");
        padding-top: 30vw;
        padding-left: 10vw;

        .skbank-banner-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width:40vw;
            font-size:3vw;
        }
    }

  .form {

    .event-form {
      max-width: 80%;
      margin: 0rem auto;
      overflow: hidden;
      padding: 0rem 4rem;
      z-index: 4;
      position: relative;
      background-size: cover;

      .input-group {
        width: 43vw;
        padding: 5px 10px;
        height:100%;

        span {
            display:flex;
            font-size: 3vw;
        }

        .skbank-bottom-img {
            width:100%;
        }
      }

      .skbank-blue-link {
          width: 24%;
          height: 1px;
          border-bottom: solid #036EB7 1px;
      }

      .group-text {
          align-items:center;
          justify-content:center;
      }

      .group-img {
          width: 50%;
          height:100%;
      }

      .form-control {
        width: 43vw;
        background-image: url("../asset/images/bankInput.svg");
          background-repeat: no-repeat;
        border: 0px;
        border-radius: 15px;
        font-size: 38px;
        font-weight: bold;
        padding: .375rem 1.75rem;
      }

      .btn-success {
        font-size: 27px;
        display: block;
        margin: 0px auto;
        font-weight: bold;
        width: 300px;
      }

      .btn-disable {
        width: 300px;
        font-size: 27px;
        cursor: default;
        display: block;
        margin: 0px auto;
        font-weight: bold;
        color: #495057;
      }

    }
  }
  .skbank-send-btn {
    background-image: url("../asset/images/skBankButton.svg");
    background-repeat: no-repeat;
    height: 7vw;
    border: 0px;
    border-radius: 15px;
    color: white;
    width: 100%;
    font-size: 3vw;
    font-weight: bold;
    align-items: center;
    width: 75vw;
    align-items:center;
  }
  .skbank-content-bottom{
      background-color: #EEEEEF;
      position: relative;
      background-size: cover;
      height: 10vw;
      display: flex;

      img {
          width: 25%;
          margin: auto;
          display: block;
      }
  }
}
@media screen and (max-width: 767px) {
  .bank-wrapper {
      .content-top {
          height: 117vw;
      }

      .skbank-top{
          background-image: url("../asset/images/skBankTopM.jpg");
          padding-top: 45vw;
          padding-left: 0vw;

          .skbank-banner-btn {
              width: 57%;
              height: 100%;
              align-items: inherit;
              font-size: 20px;
              margin: 0 auto;
          }
      }
      .form {
        padding: 5px;

        .event-form {
          padding: initial;
          max-width: 90%;

          .input-group {
            width: 100%;
              height: 100%;
            margin: 2px auto 2px auto;

            .group-img {
                width: 100%;
            }

            .form-control {
                background-size: contain;
                height: 16vw;
                font-weight: bold;
                left: initial;
            }

            .send-btn {
                height: 16vw;
            }

            .skbank-send-btn {
                font-size: 5.5vw;
                align-items: initial;
                width: 80%;
            }
            span {
                font-size: 7vw;
            }
            .skbank-bottom-img {
                height: 100%;
            }
          }

          .skbank-blue-link {
            width: 32%
          }

        }
      }
      .content-bottom {
          height: 48vw;
      }
  }
}
</style>
