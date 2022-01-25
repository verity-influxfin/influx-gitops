<template>
  <div>
    <!-- 標頭 -->
    <alesis-loan-header
      :header-img="require('@/asset/images/3S-title.webp')"
      header-img-class="img-title"
      content="學生貸款史上最低利3%~5%<br>最高額度18萬，點先繳息不還本<br>最高額度 15 萬、最低利率 5%<br>鼓勵名校同學創業、投資、自我成長"
      :image="require('@/asset/images/3S-banner.webp')"
    ></alesis-loan-header>
    <!-- 標頭 -->
    <!-- 適用對象 -->
    <div class="適用對象">
      <alesis-space size="medium"></alesis-space>
      <alesis-header>
        <div class="標題">適用對象</div>
        <div class="點"></div>
        <div class="點"></div>
        <div class="suit-info">
          <div>
            滿20歲且符合申貸資格之名校在校生，準備雙證件、學生證、金融卡
          </div>
          <div>只需手機APP拍照上傳即可申請</div>
        </div>
      </alesis-header>
      <alesis-section :secondary="true">
        <alesis-space size="large"></alesis-space>
        <div class="項目列表">
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-student.svg" />
            <div class="標籤">學生身分</div>
          </div>
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-id.svg" />
            <div class="標籤">雙證件</div>
          </div>
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-creditcard.svg" />
            <div class="標籤">金融帳號</div>
          </div>
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-email.svg" />
            <div class="標籤">電子信箱</div>
          </div>
        </div>
        <div class="download-button">
          <router-link to="/borrowLink" target="_blank">
            <button class="btn orange-download-btn">點我下載普匯inFlux</button>
          </router-link>
        </div>
        <div class="download-info">
          下載APP註冊登入，瞭解更多符合申請資格之學校名單
        </div>
      </alesis-section>
    </div>
    <!-- / 適用對象 -->

    <!-- 為何選擇 -->
    <div class="為何選擇">
      <alesis-header>
        <div class="標題">為什麼選擇3S名校貸</div>
      </alesis-header>
      <alesis-section>
        <alesis-space size="medium"></alesis-space>
        <div class="choose-grid">
          <div class="choose-item">
            <div class="choose-title">學生最低利</div>
            <div class="choose-info">利率最低3%~最高5%</div>
          </div>
          <div class="choose-item">
            <div class="choose-title">還款更靈活</div>
            <div class="choose-info">可每月先繳息，到期再還本金</div>
          </div>
          <div class="choose-item">
            <div class="choose-title">額度再升級</div>
            <div class="choose-info">最高額度可達18萬</div>
          </div>
          <div class="choose-item">
            <div class="choose-title">核貸率極高</div>
            <div class="choose-info">核貸率高達90%以上</div>
          </div>
        </div>
        <alesis-space size="medium"></alesis-space>
      </alesis-section>
    </div>
    <!-- / 為何選擇 -->

    <!-- 步驟說明 -->
    <div class="步驟說明">
      <alesis-header>
        <div class="標題">申貸四步驟</div>
      </alesis-header>
      <alesis-section :secondary="true">
        <alesis-space size="medium"></alesis-space>
        <div class="swiper-container steps-container">
          <div class="swiper-wrapper">
            <!-- 步驟一 -->
            <div class="swiper-slide">
              <div class="步驟一">
                <div class="左邊"></div>
                <div class="中間">
                  <img
                    src="/images/alesis-collegeloan-step1.svg"
                    class="圖片"
                  />
                </div>
                <div class="右邊">
                  <div class="標題">Step 1. 選擇額度</div>
                  <div class="內容">
                    提供完整資訊<br />
                    有助提高額度
                  </div>
                </div>
              </div>
            </div>
            <!-- / 步驟一 -->

            <!-- 步驟二 -->
            <div class="swiper-slide">
              <div class="步驟二">
                <div class="左邊"></div>
                <div class="中間">
                  <img
                    src="/images/alesis-collegeloan-step2.svg"
                    class="圖片"
                  />
                </div>
                <div class="右邊">
                  <div class="標題">Step 2. 上傳資料</div>
                  <div class="內容">
                    AI數據分析審核<br />
                    全程無人打擾
                  </div>
                </div>
              </div>
            </div>
            <!-- / 步驟二 -->

            <!-- 步驟三 -->
            <div class="swiper-slide">
              <div class="步驟三">
                <div class="左邊"></div>
                <div class="中間">
                  <img
                    src="/images/alesis-collegeloan-step3.svg"
                    class="圖片"
                  />
                </div>
                <div class="右邊">
                  <div class="標題">Step 3. 持證自拍</div>
                  <div class="內容">
                    審核成功後<br />
                    立即上架幫您媒合投資人
                  </div>
                </div>
              </div>
            </div>
            <!-- / 步驟三 -->

            <!-- 步驟四 -->
            <div class="swiper-slide">
              <div class="步驟四">
                <div class="左邊"></div>
                <div class="中間">
                  <img
                    src="/images/alesis-collegeloan-step4.svg"
                    class="圖片"
                  />
                </div>
                <div class="右邊">
                  <div class="標題">Step 4. 上架媒合</div>
                  <div class="內容">24小時收到款項</div>
                </div>
              </div>
            </div>
            <!-- / 步驟四 -->
          </div>

          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
        <alesis-space size="medium"></alesis-space>
      </alesis-section>
    </div>
    <!-- / 步驟說明 -->

    <!-- 應用程式推薦 -->
    <alesis-app-recommendation action="我想借款"></alesis-app-recommendation>
    <!-- / 應用程式推薦 -->
  </div>
</template>

<script>
// Alesis 元件
import AlesisAppRecommendation from "../component/alesis/AlesisAppRecommendation";
import AlesisBullet from "../component/alesis/AlesisBullet";
import AlesisButton from "../component/alesis/AlesisButton";
import AlesisCounter from "../component/alesis/AlesisCounter";
import AlesisHeader from "../component/alesis/AlesisHeader";
import AlesisHorizontalRoadmap from "../component/alesis/AlesisHorizontalRoadmap";
import AlesisHuman from "../component/alesis/AlesisHuman";
import AlesisLoanHeader from "../component/alesis/AlesisLoanHeader";
import AlesisMoon from "../component/alesis/AlesisMoon";
import AlesisPlan from "../component/alesis/AlesisPlan";
import AlesisProject from "../component/alesis/AlesisProject";
import AlesisSection from "../component/alesis/AlesisSection";
import AlesisSuggestionReviews from "../component/alesis/AlesisSuggestionReviews";
import AlesisSymcard from "../component/alesis/AlesisSymcard";
import AlesisTaiwanMap from "../component/alesis/AlesisTaiwanMap";
import AlesisVerticalRoadmap from "../component/alesis/AlesisVerticalRoadmap";
import AlesisSpace from "../component/alesis/AlesisSpace";
// 遠端資料
import CollegeSchools from "../data/college_schools"
import StudentDone from "../data/projects_student_done"

import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

// $(document).ready(function () {
//   setTimeout(() => {
//     let toPoint = location.hash;
//     if (toPoint) {
//       document.getElementById("credit_report").scrollIntoView();
//     }
//   }, 3500)
// });
export default {
  components: {
    AlesisAppRecommendation,
    AlesisBullet,
    AlesisButton,
    AlesisCounter,
    AlesisHeader,
    AlesisHorizontalRoadmap,
    AlesisHuman,
    AlesisLoanHeader,
    AlesisMoon,
    AlesisPlan,
    AlesisProject,
    AlesisSection,
    AlesisSuggestionReviews,
    AlesisSymcard,
    AlesisTaiwanMap,
    AlesisVerticalRoadmap,
    AlesisSpace,
  },
  filters: {
    amount: (value) => {
      return Number(parseFloat(value).toFixed(3)).toLocaleString('en')
    }
  },
  data: () => ({
    formSchool: "",
    formSchoolDiscipline: "",
    formRank: "",
    formHasAward: "",
    name: "",
    email: "",
    isStudentLoan: null,
    isPartTimeJob: null,
    monthlyEconomy: null,
    formCalculated: false,
    schools: [],
    schoolDisciplines: {},
    originalSchools: CollegeSchools,
    collegePreviews: {},
    borrowReportResult: {},
    isFormValid: true,
  }),
  created() {
    $("title").text(`學生貸 - inFlux普匯金融科技`);
    this.getStudentCase();
  },
  mounted() {
    this.initSchools();
    this.initSteps();
  },
  methods: {
    // initSchools 會扁平化學校清單，並且展開其科系資料作為下拉式選單選項。
    initSchools() {
      // 遍歷整個學校，然後扁平化其結構。
      Object.keys(this.originalSchools).forEach(v => {
        // 將每個學校推入到學校清單中。
        this.schools.push({
          title: v,
          value: this.originalSchools[v].shortName
        })
        // 替這個學校建立對應的科系陣列。
        this.schoolDisciplines[v] = [];
        // 遍歷這個學校的科系陣列，並將期推到對應的科系清單裡，試圖扁平化。
        Object.keys(this.originalSchools[v].discipline).forEach(j => {
          this.schoolDisciplines[v].push({
            disabled: true,
            title: j,
            value: "",
          })
          // 扁平化這個科系下的子科系，但用全形空白當作 Indent。
          this.originalSchools[v].discipline[j].forEach(k => {
            this.schoolDisciplines[v].push({
              disabled: false,
              title: `　　${k}`,
              value: k,
            })
          })
        })
      })
    },
    // 學生案件
    getStudentCase() {
      let studenCase = new FormData();
      studenCase.append('status', 10);
      studenCase.append('product_id', 1);

      axios.post(
        `${location.origin}/getCase`,
        studenCase
      ).then((res) => {
        this.collegePreviews = res.data;
      })
        .catch((error) => {
          console.error('getCase 發生錯誤，請稍後再試');
        });
    },

    // initSteps 會初始化步驟教學幻燈片。
    initSteps() {
      SwiperCore.use([Navigation]);
      new Swiper('.swiper-container.steps-container', {
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
    },

    // calculateForm 會計算表單。
    calculateForm() {
      this.isFormValid = false
      let data = new FormData(this.$refs.borrowReport)

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

    },
  },
};
</script>

<style lang="scss">
@import '../component/alesis/alesis';
.img-title {
  width: 340px;
}

.alesis-steps {
  .carousel {
    .selector {
      @include rwd {
        min-height: 520px;
        align-items: initial;
      }
    }
  }
}
</style>

<style lang="scss" scoped>
@import '../component/alesis/alesis';
/**
 * 適用對象
 */

.適用對象 {
  position: relative;
  --alesis-xheader-offset: 20%;
}
.標題 {
  margin-bottom: 10px;
}
.suit-info {
  font-size: 26px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.77;
  letter-spacing: 2.6px;
  text-align: center;
  color: #404040;
}
.download-button {
  margin: 40px auto 20px;
  text-align: center;
  .orange-download-btn {
    padding: 13px 22px;
    font-size: 28px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: 3.6px;
    text-align: left;
    color: #fff;
    border-radius: 20px;
    box-shadow: 3px 6px 6px 0 rgba(0, 0, 0, 0.1);
    background-color: #f29600;
  }
}
.download-info {
  padding-bottom: 150px;
  font-size: 26px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.5;
  letter-spacing: 2px;
  text-align: center;
  color: #404040;
}
.choose-grid {
  display: grid;
  gap: 25px;
  grid-template-columns: 1fr 1fr;
  max-width: 900px;
  margin: 0 auto;
  .choose-item {
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .choose-title {
      width: fit-content;
      font-size: 32px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.44;
      letter-spacing: 3.6px;
      text-align: left;
      color: #fff;
      padding: 11px 22px;
      border-radius: 20px;
      background-color: #1e69aa;
    }
    .choose-info {
      padding: 10px 0;
      font-size: 26px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.2;
      letter-spacing: 2.6px;
      text-align: center;
      color: #404040;
    }
  }
}

.swiper-container.steps-container {
  width: 900px;
  height: 549px;

  @include rwd {
    width: 100%;
  }
}

button:disabled,
button[disabled] {
  cursor: no-drop;
}

.swiper-container.human-container {
  max-width: 1170px;
  padding-bottom: 3rem;
  padding-top: 3rem;

  .swiper-slide {
    .set {
      max-width: 90%;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 3rem;
      margin: 0 auto;
    }
  }
}

.swiper-button-next,
.swiper-button-prev {
  opacity: 0.6;
  transform: scale(0.7);
}

.swiper-button-disabled {
  display: none;
}

.alesis-humans {
  position: relative;
  --alesis-xheader-offset: -70%;
  --alesis-xsection-offset-top: 8rem;
}

/**
 * 步驟說明
 */

.步驟說明 {
  position: relative;

  --alesis-xsection-offset-top: 8rem;
}

.步驟說明 .步驟一,
.步驟說明 .步驟二,
.步驟說明 .步驟三,
.步驟說明 .步驟四 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 3rem;
  max-width: 900px;

  @include rwd {
    grid-template-columns: repeat(1, 1fr);
    align-items: center;
    justify-content: center;
  }
}

.步驟說明 .步驟一 .左邊,
.步驟說明 .步驟二 .左邊,
.步驟說明 .步驟三 .左邊,
.步驟說明 .步驟四 .左邊 {
  @include rwd {
    display: none;
  }
}

.步驟說明 .步驟一 .中間,
.步驟說明 .步驟二 .中間,
.步驟說明 .步驟三 .中間,
.步驟說明 .步驟四 .中間 {
  text-align: center;
}

.步驟說明 .步驟一 .中間 .圖片,
.步驟說明 .步驟二 .中間 .圖片,
.步驟說明 .步驟三 .中間 .圖片,
.步驟說明 .步驟四 .中間 .圖片 {
  @include rwd {
    width: 200px;
  }
}

.步驟說明 .步驟一 .右邊,
.步驟說明 .步驟二 .右邊,
.步驟說明 .步驟三 .右邊,
.步驟說明 .步驟四 .右邊 {
  display: flex;
  justify-content: center;
  flex-direction: column;

  @include rwd {
    margin: 0 auto;
    padding-left: 1rem;
  }
}

.步驟說明 .步驟一 .右邊 .標題,
.步驟說明 .步驟二 .右邊 .標題,
.步驟說明 .步驟三 .右邊 .標題,
.步驟說明 .步驟四 .右邊 .標題 {
  color: #112e53;
  font-size: 1.3rem;

  @include rwd {
    font-size: 1.1rem;
  }
}

.步驟說明 .步驟一 .右邊 .標題 .內容,
.步驟說明 .步驟二 .右邊 .標題 .內容,
.步驟說明 .步驟三 .右邊 .標題 .內容,
.步驟說明 .步驟四 .右邊 .標題 .內容 {
  color: #5d5555;
  font-size: 1.1rem;

  @include rwd {
    font-size: 1rem;
    padding-left: 1rem;
  }
}
.步驟說明 .swiper-button-next,
.步驟說明 .swiper-button-prev {
  @include rwd {
    margin-top: -7rem;
  }
}

.適用對象 .項目列表 {
  display: grid;
  max-width: 900px;
  margin: 0 auto;
  grid-template-columns: repeat(4, 1fr);

  @include rwd {
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem 0rem;
    max-width: 310px;
  }
}

.適用對象 .項目列表 .項目 {
  text-align: center;
}

.適用對象 .項目列表 .項目 .圖示 {
  width: 100px;

  @include rwd {
    width: 80px;
  }
}

.適用對象 .項目列表 .項目 .標籤 {
  margin-top: 1rem;
  color: #112e53;
  font-size: 1.3rem;

  @include rwd {
    font-size: 1rem;
    margin-top: 0.5rem;
  }
}

/**
 * 為何選擇
 */

.為何選擇 {
  position: relative;

  --alesis-xsection-offset-top: 7rem;
}

</style>
