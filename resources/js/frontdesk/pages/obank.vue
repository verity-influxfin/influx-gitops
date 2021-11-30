<template>
  <div class="obank-bank-wrapper" id="obank-bank-wrapper">
      <div class="content-top obank-top">
        <a class="btn send-btn obank-send-btn obank-banner-btn" href="https://www.o-bank.com/web/Event/personalloan/index.html?SourceCode=InFlux-Loan&CampaignCode=R-PL2021111501" target="_blank"></a>
      </div>
    <div class="obank-form">
        <div class="event-form">
            <div class="input-group " style="margin: 3vw auto;">
                <img src="/images/oBankTopContent.svg" class="obank-bottom-img">
            </div>
            <div class="input-group group-text " style="margin: 3vw auto 4vw auto;">
                <span class="obank-black-link"></span>
                <span>申貸只要</span>
                <span style="color:#ff8300;">3</span>
                <span>步驟</span>
                <span class="obank-black-link"></span>
            </div>
            <div class="input-group" style="width:70vw; margin: 3vw auto 4vw auto;">
                <img class="obank-group-img" src="../asset/images/oBankMidContent.svg">
                <img class="obank-group-img" src="../asset/images/oBankMidContent1.svg">
                <img class="obank-group-img" src="../asset/images/oBankMidContent2.svg">
            </div>
            <div class="input-group group-text" style="margin: 3vw auto 4vw auto;">
                <table class="obank-table">
                    <tbody>
                        <tr>
                            <td class="obank-title">
                                <div><span>貸款金額</span></div>
                            </td>
                            <td class="obank-content">
                                <div><span>最高NT500萬元</span></div>
                            </td>
                        </tr>
                        <tr class="color-light-grey">
                            <td class="obank-title">
                                <div><span>適用對象</span></div>
                            </td>
                            <td class="obank-content">
                                <div><span>1.本國人年齡20~65歲，有固定收入</span></div>
                                <div><span>2.年收入大於等於NT24萬元，小資也能輕鬆貸款</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="obank-title">
                                <div><span>貸款利率</span></div>
                            </td>
                            <td class="obank-content">
                                <div><span>二段式利率</span></div>
                                <div><span>第一段利率固定</span><span style="color:#ff8300;">0.08%</span></div>
                                <div><span>第二段利率最低2.98%起(機動計息)</span></div>
                            </td>
                        </tr>
                        <tr class="color-light-grey">
                            <td class="obank-title">
                                <div><span>貸款期間</span></div>
                            </td>
                            <td class="obank-content">
                                <div><span>最長七年</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="obank-title">
                                <div><span>開辦費</span></div>
                            </td>
                            <td class="obank-content">
                                <div>
                                    <span style="text-decoration:line-through;">NT7,800元 </span>
                                    <span>(</span>
                                    <span style="color:#ff8300;">特價NT
                                        <span style="color:red; font-size:34px;">88</span>
                                    元</span>
                                    <span>)</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="color-light-grey">
                            <td class="obank-title">
                                <div><span>動撥方式及還款方式</span></div>
                            </td>
                            <td class="obank-content">
                                <div><span>一次撥付本息攤還型</span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="input-group obank-down-btn" style="margin: 3vw auto 3vw auto;">
              <a class="btn send-btn obank-send-btn" href="https://www.o-bank.com/web/Event/personalloan/index.html?SourceCode=InFlux-Loan&CampaignCode=R-PL2021111501" target="_blank" style="display: flex;justify-content: center; margin: 0px auto;"></a>
            </div>
            <div class="input-group" style="margin: 3vw auto;border-radius: unset;">
                <a type="button" @click="click_notice()" class="obank-notice">
                <div>注意事項</div>
                <svg id="plus" style="margin-left: 10px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                <svg id="dash" style="margin-left: 10px;display:none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                  <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                </svg>
                </a>
                <div id="obank_notice_list" class="obank-notice-list" style="display:none">
                    <div><span>1.專案期間至110年12月31日(含)截止，以王道銀行(以下簡稱為本行)系統進件日為準。</span></div>
                    <div><span>2.信用貸款二段式(本息攤還型)總費用年百分率計算範例(計算基準日：民國110年8月23日：貸款金額NT30萬元，貸款期間5年(限制清償期最長為24期)，貸款年利率首期固定0.07%，第二期起2.98%~16%(機動計息)，各項相關費用總金額：NT88元/筆，</span><span style="color:red;">總費用年百分率：2.89%~15.42%。</span></div>
                    <div><span>3.上述第二期起機動計息係按本行一個月期定儲利率指數依計算基準日(i目前為0.80%)加碼2.18%~15.2%，調整時間為每月21日(調整日期倘遇假日，將順延至次一營業日，且適用期間之首日將隨調整日期變動而順延)。</span></div>
                    <div>
                        <span>
                        4.本專案總費用年百分率之計算基準日為110年6月21日，年百分率計算基準日及日後年百分率會依實際借款期間、利率調整等因素而變動。本廣告揭露之年百分率係按主管機關備查之標準計算範例予以計算，實際貸款條件，仍以本行提供之產品為準。且每一客戶實際之年百分率仍視其個別貸款產品及授信條件而有所不同。本總費用年百分率不等於貸款利率。
                        </span>
                    </div>
                    <div><span style="color:red;">5.違約金計收方式說明：</span></div>
                    <div style="margin-left: 4vw;"><span style="color:red;">-本專案24個月內提前「清償本金(即大額還款)」或「結清本貸款帳戶」時，需計收違約金。1~6期，提前清償本金之4%；7~24期，提前清償本金之3%。</span></div>
                    <div style="margin-left: 4vw;"><span style="color:red;">-本行另有「得隨時清償」之貸款專案，詳情請洽本行信貸專員。</span></div>
                    <div><span>6.本行保留最終核准核貸與否、貸款金額、貸款期間、利率、各項條件及解釋、變更或終止優惠活動內容之權利。本行將依客戶收入負債比等綜合評量審查決定核准與否。</span></div>
                    <div><span style="color:red;">7.本行未與任何代辦業者或行銷公司合作辦理貸款事宜，亦不受理代辦貸款業者之案件，提醒您，請勿透過代辦業者申貸或支付費用，以免損害自身權益。</span></div>
                    <div><span>8.上述貸款利率及開辦費優惠僅適用申辦信用貸款二段式專案，若因資金需求轉換為資金整合貸款專案者，將不適用本優惠專案。</span></div>
                    <div><span>9.各信用貸款專案內容及條件均有不同，如需了解更多，請洽O-Bank數位貸款團隊。</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="obank-content-bottom">
        <img src= "../asset/images/oBankBottom.svg">
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

export default {
    data: () => ({
    }),
    created() {
      $("title").text(`普匯x王道銀行`);
    },
    mounted() {
    },
    watch: {
    },
    methods: {
        click_notice(){
            $('#plus').toggle(function(i, v) { return !v; });
            $('#dash').toggle(function(i, v) { return !v; });
            $('#obank_notice_list').toggle(function(i, v) { return !v; });
         }
    }
};
</script>

<style lang="scss">
.obank-bank-wrapper {
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

    .obank-top{
        background-image: url("../asset/images/oBankBanner.jpg");
        padding-top: 31vw;
        padding-left: 57vw;

        .obank-banner-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width:40vw;
            font-size:3vw;
        }
    }

  .obank-form {

    .event-form {
      max-width: 80%;
      margin: 0rem auto;
      overflow: hidden;
      padding: 0rem 4rem;
      z-index: 4;
      position: relative;
      background-size: cover;

      .input-group {
        width: 70vw;
        padding: 5px 10px;
        height:100%;

        .obank-table {
          box-shadow: 0 0 10px 1px rgb(0 0 0 / 20%), 0 0 16px 0 rgb(0 0 0 / 15%);
          width: 100%;
          border-radius: 15px;

          .obank-title {
            width: 30%;
          }
          .obank-content {
            width: 70%;
          }

          div {
            margin: 20px 10px 10px 20px;
            white-space:nowrap;
          }

          span {
            display: initial;
            font-size: 22px;
          }
        }

        span {
            display:flex;
            font-size: 4vw;
            letter-spacing: 3px;
        }

        .obank-bottom-img {
            width:100%;
        }

        .obank-notice {
            display: flex;
            width: 100%;
            border: 1px solid;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            height: 50px;
            border-radius: unset;
        }

        .obank-notice-list {
            div {
              margin: 20px 10px 10px 20px;
            }

            span {
              display: initial;
              font-size: 18px;
            }
        }

      }

      .obank-down-btn {
          width:65%;
      }

      .color-light-grey {
        background-color: #eeeeef;
      }

      .obank-black-link {
          width: 13%;
          height: 1px;
          margin: 0px 1vw 0px 1vw;
          border-bottom: solid black 1px;
      }

      .group-text {
          align-items:center;
          justify-content:center;
      }

      .obank-group-img {
          width: 33%;
          height:100%;
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
  .obank-send-btn {
    background-image: url("../asset/images/oBankButton.svg");
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
  .obank-content-bottom{
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
  .obank-bank-wrapper {
      .content-top {
          height: 170vw;
      }

      .obank-top{
          background-image: url("../asset/images/oBankTopM.jpg");
          padding-top: 60vw;
          padding-left: 0vw;

          .obank-banner-btn {
              width: 57%;
              height: 100%;
              align-items: inherit;
              font-size: 20px;
              margin: 0 auto;
          }
      }
      .obank-form {
        padding: 5px;

        .event-form {
          padding: initial;
          max-width: 90%;

          .input-group {
            width: 100%;
              height: 100%;
            margin: 2px auto 2px auto;

            .obank-group-img {
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

            .obank-send-btn {
                font-size: 5.5vw;
                align-items: initial;
                width: 80%;
            }
            span {
                font-size: 7vw;
            }
            .obank-bottom-img {
                height: 100%;
            }
            .obank-table {
                div {
                  margin: 2px 10px 1px 10px;
                  white-space:initial;
                }

                span {
                  display: initial;
                  font-size: 10px;
                }
            }

            .obank-notice-list {

                span {
                  display: initial;
                  font-size: 10px;
                }
            }

          }

          .obank-blue-link {
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
