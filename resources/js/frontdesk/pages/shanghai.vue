<template>
  <div class="shanghai-wrapper">
    <div class="banner">
      <a
        href="https://apply.scsb.com.tw/openaccount/client?activeCode=&salesCode=8500INFLUX9Q&openExternalBrowser=1"
        target="_balnk"
        onClick="ga('send', 'event', 'Link', 'Click', 'shanghai');"
      >
        <img class="img-fluid" src="../asset/images/shanghai_banner.jpg" />
      </a>
    </div>
    <div class="cnt">
      <h3>普匯金融科技X上海商業銀行 重磅合作!</h3>
      <p class="h-t">線上開戶急速體驗！萬元獎品等你拿！</p>
      <p>
        普匯金融科技與上海商銀為台灣具潛力金融科技明日之星與台灣資深民營銀行，<br />
        攜手合作共同為年輕人帶來更快速、更有效率的金融服務！<br />
        首推超急速「Cloud
        Bank數位帳戶」線上開戶！現在，只要三步驟，帶你體驗秒速線上開戶快感！<br />
        再把Airpods、JBL音響、711禮券，總價值超過萬元獎品，帶～回～家！<br />
      </p>

      <table class="table">
        <tr>
          <th>活動辦法：</th>
          <th>獎項：</th>
        </tr>
        <tr>
          <td>
            1. 點擊
            <a
              style="color: #ff0000"
              href="https://apply.scsb.com.tw/openaccount/client?activeCode=&salesCode=8500INFLUX9Q&openExternalBrowser=1"
              target="_balnk"
              onClick="ga('send', 'event', 'Link', 'Click', 'shanghai');"
            >
              連結<i class="fas fa-external-link-alt"></i>
            </a>
            完成開戶。
          </td>
          <td>頭獎. AirPods 2 搭配有線充電盒(價值５,000元) 一名</td>
        </tr>
        <tr>
          <td>2. 截圖開卡成功頁面。</td>
          <td>二獎. JBL Tuner FM 便攜式FM藍牙喇叭(價值4,680元) 一名</td>
        </tr>
        <tr>
          <td>
            3. 回傳開卡成功截圖頁面至<a
              style="color: #ff0000"
              href="https://forms.gle/wR6XAA6QyESvuRkk8"
              >問卷<i class="fas fa-external-link-alt"></i></a
            >。
          </td>
          <td>三獎. 711商品禮券面額 500 元 兩名</td>
        </tr>
        <tr>
          <td>立即享有抽獎資格</td>
          <td>四獎. 711商品禮券面額 100 元 五名</td>
        </tr>
      </table>

      <ul>
        <li>11/30前完成開戶並線上開卡成功，回傳開卡成功頁面，即可參加抽獎。</li>
        <li>活動將於2020/12/15抽出得獎名單，名單將公佈於普匯官網。</li>
        <li>
          中獎人經領取獎項者，若折合市價價持超過新台幣1000元，應於次年一月依所得稅法，併入中獎人各人之年度綜合所得稅申報。
        </li>
        <li>
          主辦單位保有隨時修改及終止本活動之權利，如有任何變更內容或詳細注意事項將公布於本網頁，恕不另行通知。
        </li>
      </ul>
    </div>
    <div class="discount">
      <a
        href="https://apply.scsb.com.tw/openaccount/client?activeCode=&salesCode=8500INFLUX9Q&openExternalBrowser=1"
        target="_balnk"
        onClick="ga('send', 'event', 'Link', 'Click', 'shanghai');"
      >
        <img class="img-fluid" src="../asset/images/shanghai_discount.jpg" />
      </a>
      <span>上開備註之詳細說明或活動相關限制，悉依上海商銀官網為準</span>
    </div>
    <h2>
      立即擁有
      <a
        style="color: #ff6600"
        href="https://apply.scsb.com.tw/openaccount/client?activeCode=&salesCode=8500INFLUX9Q&openExternalBrowser=1"
        target="_balnk"
        onClick="ga('send', 'event', 'Link', 'Click', 'shanghai');"
        >Cloud Bank 數位帳戶<i class="fas fa-external-link-alt"></i
      ></a>
    </h2>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss">
.shanghai-wrapper {
  padding: 3rem;
  background-image: url("../asset/images/article.png");
  background-position: 50% 50%;
  background-repeat: no-repeat;
  background-size: 100% 100%;

  .banner {
    width: 100%;
    text-align: center;
  }

  .cnt {
    padding: 3rem;
    width: fit-content;
    margin: 0px auto;

    h3 {
      color: #00278c;
      text-align: center;
      font-weight: 700;
      margin-bottom: 2rem;
    }

    .h-t {
      color: #f28d00;
      text-align: center;
      font-weight: 700;
      margin-bottom: 2rem;
    }

    p {
      margin-bottom: 2rem;
    }
  }

  .discount {
    display: flex;
    flex-direction: column;
    width: 65%;
    margin: 0px auto;
    text-align: center;
    margin: 10px auto;
  }

  h2 {
    text-align: center;
    margin-top: 30px;
  }
}

@media screen and (max-width: 767px) {
  .shanghai-wrapper {
    padding: 10px;

    .cnt {
      padding: 1rem 0px;

      h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
      }

      .h-t,
      p {
        margin-bottom: 1rem;
      }

      ul {
        padding-left: 25px;
      }
    }

    .discount {
      width: 100%;

      span {
        font-size: 12px;
      }
    }

    h2 {
      font-size: 23px;
      margin-top: 20px;
    }
  }
}
</style>