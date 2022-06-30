<template>
  <div class="college-loan">
    <div class="banner">
      <img
        src="@/asset/images/collegeLoan/college-cover.png"
        alt="普匯學生貸banner"
        class="img-fluid d-sm-block d-none"
      />
      <img
        src="@/asset/images/collegeLoan/college-cover-phone.png"
        alt="普匯學生貸手機banner"
        class="img-fluid d-block d-sm-none mx-auto w-100"
      />
    </div>
    <div class="intro">
      <div class="block-content">
        <h1 class="h1">全台大學生，資金需求找普匯</h1>
        <h2 class="h2">
          <div>最受年輕人喜歡的金融科技借貸平台</div>
          <div>立馬申請，立馬貸</div>
        </h2>
        <div class="row no-gutters feature-cards">
          <feature-card
            title="生活消費"
            :icon="require('@/asset/images/collegeLoan/exchange-icon.png')"
          >
            <div class="feature-card-sub">求學生活別為了資金煩惱</div>
            <ul class="feature-card-list">
              <li class="feature-card-item">
                「學習工具不能少」3C、筆電、手機… 通訊費，通通找普匯！
              </li>
              <li class="feature-card-item">
                「學生就是需要一台機車」不管上課、打工、郊遊、交朋友，都要騎上它，機車找普匯，準沒錯！(自拍機車照
                片、牌照，信用加分，額度加倍)
              </li>
            </ul>
          </feature-card>
          <feature-card
            title="進修學雜費"
            :icon="require('@/asset/images/collegeLoan/tuition-icon.png')"
          >
            <div class="feature-card-sub">你的自我進修，學習成長</div>
            <ul class="feature-card-list">
              <li class="feature-card-item">
                「學費、書本費、雜費、生活費」樣樣都是費，善用科技普匯金融立馬貸給你。
              </li>
              <li class="feature-card-item">
                考研、學程式語言、第二專長、興趣進修，普匯通通願意滿足你！
              </li>
            </ul>
          </feature-card>
          <feature-card
            title="資金需求"
            :icon="require('@/asset/images/collegeLoan/fund-icon.png')"
          >
            <div class="feature-card-sub">資金需求，交給普匯好朋友</div>
            <ul class="feature-card-list">
              <li class="feature-card-item">
                參加社團、社群活動，樣樣都要花錢，普匯隨時金援你！
              </li>
              <li class="feature-card-item">
                畢展、畢旅，不用跟家人伸手、不必麻煩別人，更不需要打工荒廢學業，普匯好朋友貸給你！
              </li>
            </ul>
          </feature-card>
        </div>
      </div>
    </div>
    <div class="apply-intro">
      <div class="block-content">
        <h1 class="h1">如何申請</h1>
        <h2 class="h2">準備您的學生證、雙證件、金融卡帳號，手機APP拍照上傳</h2>
        <div class="apply-icons">
          <div class="apply-icon-item">
            <img
              src="@/asset/images/collegeLoan/student-card-icon.png"
              alt="學生證"
              class="apply-icon-img"
            />
            <div class="apply-icon-text">學生證</div>
          </div>
          <div class="apply-icon-item">
            <img
              src="@/asset/images/collegeLoan/idcard-icon.png"
              alt="雙證件"
              class="apply-icon-img"
            />
            <div class="apply-icon-text">雙證件</div>
          </div>
          <div class="apply-icon-item">
            <img
              src="@/asset/images/collegeLoan/creadit-icon.png"
              alt="金融帳號"
              class="apply-icon-img"
            />
            <div class="apply-icon-text">金融帳號</div>
          </div>
          <div class="apply-icon-item">
            <img
              src="@/asset/images/collegeLoan/upload-college-icon.png"
              alt="拍照上傳"
              class="apply-icon-img"
            />
            <div class="apply-icon-text">拍照上傳</div>
          </div>
        </div>
        <div class="row no-gutters apply-slogan">
          <img
            src="@/asset/images/collegeLoan/apply-img.png"
            alt=""
            class="apply-slogan-img col-12 col-md-auto"
          />
          <div class="col d-md-block d-none apply-slogan-text">
            <div class="line-1">
              最高額度<span class="em">15</span>
              萬&emsp; 最低利率
              <span class="em">4</span>%
            </div>
            <div>期數可分<span class="em">3-24</span>期</div>
          </div>
          <div class="col-12 d-md-none d-block apply-slogan-text">
            <div>最高額度<span class="em">15</span>萬</div>
            <div>最低利率<span class="em">4</span>%</div>
            <div>期數可分<span class="em">3-24</span>期</div>
          </div>
        </div>
      </div>
    </div>
    <div class="step">
      <h1 class="h1">申貸簡單四步驟</h1>
      <h2 class="h2">
        <div>5分鐘申貸、10分鐘審核、1小時放款</div>
        <div>貸你完成夢想，美好人生</div>
      </h2>
      <div class="step-main">
        <div class="step-content step-content-1">
          <div class="step-content-title">步驟一</div>
          <div class="step-content-info">
            <div>選擇額度 -</div>
            <div>額度最高15萬，3-24期任你選</div>
          </div>
          <div class="step-content-img">
            <img
              class="img-fluid"
              src="@/asset/images/index/loan-step-1.png"
              alt=""
            />
          </div>
        </div>
        <div class="step-content step-content-2">
          <div class="step-content-title">步驟二</div>
          <div class="step-content-info">
            <div>上傳資料 -</div>
            <div>AI數據分析審核，全程無人打擾</div>
          </div>
          <div class="step-content-img">
            <img
              class="img-fluid"
              src="@/asset/images/index/loan-step-4.png"
              alt=""
            />
          </div>
        </div>
        <div class="step-content step-content-3">
          <div class="step-content-title">步驟三</div>
          <div class="step-content-info">
            <div>持證自拍 -</div>
            <div>審核成功後，立即上架幫您媒合投資人</div>
          </div>
          <div class="step-content-img">
            <img
              class="img-fluid"
              src="@/asset/images/index/loan-step-3.png"
              alt=""
            />
          </div>
        </div>
        <div class="step-content step-content-4">
          <div class="step-content-title">步驟四</div>
          <div class="step-content-info">
            <div>上架媒合 -</div>
            <div>24小時收到款項</div>
          </div>
          <div class="step-content-img">
            <img
              class="img-fluid"
              src="@/asset/images/index/loan-step-5.png"
              alt=""
            />
          </div>
        </div>
      </div>
    </div>
    <div class="reason">
      <h1 class="h1">為什麼選擇普匯</h1>
      <h2 class="h2 d-md-block d-none">
        全線上手機APP24小時不停機，AI風控自動審核
      </h2>
      <h2 class="h2 d-md-none d-block">
        <div>全線上手機APP24小時不停機，</div>
        <div>AI風控自動審核</div>
      </h2>
      <div class="block-content">
        <div class="row no-gutters reason-row">
          <div class="reason-intro col-md-auto col-12">
            <div class="reason-intro-title">全台學生貸市佔率最高</div>
            <div class="reason-intro-sub">幫助數萬名學生度過資金難關</div>
            <div class="reason-intro-list">
              <div class="reason-intro-item">全程使用APP，不跳轉，無人打擾</div>
              <div class="reason-intro-item">申辦免費、不核准免費</div>
              <div class="reason-intro-item">放款完全不受休假限制</div>
              <div class="reason-intro-item">核貸通過率超過90%</div>
              <div class="reason-intro-item">申貸利率完全公開透明不隱藏</div>
            </div>
          </div>
          <div class="col-md col-auto reason-image-group">
            <div class="reason-item reason-item-1">安全</div>
            <div class="reason-item reason-item-2">快速</div>
            <div class="reason-item reason-item-3">隱私</div>
            <div class="reason-item reason-item-4">簡單</div>
            <img
              src="@/asset/images/collegeLoan/logo-group.png"
              alt="普匯logo"
              class="img-logo"
            />
            <img
              src="@/asset/images/outline-ball.png"
              alt=""
              class="img-outline"
            />
          </div>
        </div>
      </div>
      <div class="bottom-title">普匯•就是快</div>
    </div>
    <div class="case">
      <h1 class="h1">普匯最有溫度，最安全</h1>
      <h2 class="h2">
        <div>全國市占最高，幫助超過156所大學生</div>
        <div>已幫助超過數萬名學生，不論是生活急需、補習進修、</div>
        <div>購買3C、機車，都在找普匯！</div>
      </h2>
      <div class="block-content">
        <div class="row no-gutters projects-row">
          <div class="project-title w-100">即時成功案件</div>
          <div class="col-md col-12 mx-auto case-col">
            <alesis-project
              v-for="item in collegePreviews"
              v-bind="item"
              :key="item.target_no"
            />
          </div>
          <div class="col-md-4 col-auto mx-auto">
            <alesis-taiwan-map />
          </div>
        </div>
      </div>
    </div>
    <div class="caculate">
      <h1 class="h1">試算你的普匯信用額度</h1>
      <div class="block-content">
        <form
          @submit.prevent="calculateForm"
          class="caculate-form"
          ref="borrowReport"
        >
          <div class="row no-gutters">
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
                <span class="item">*就讀學校：</span>
                <select
                  class="item-input item-select col"
                  v-model="formSchool"
                  name="school_name"
                  required
                >
                  <option selected disabled value="">-請選擇-</option>
                  <option
                    v-for="(item, index) in schools"
                    :key="index"
                    :value="item.title"
                  >
                    {{ item.title }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*是否有學貸：</span>
                <div class="radio-custom-college">
                  <label class="item-label">
                    是
                    <input
                      type="radio"
                      class="radio-inline"
                      name="is_student_loan"
                      value="True"
                      required
                    /><span class="college-input-outside"
                      ><span class="college-input-inside"></span
                    ></span>
                  </label>
                </div>
                <div class="radio-custom-college">
                  <label class="item-label">
                    否
                    <input
                      type="radio"
                      class="radio-inline"
                      name="is_student_loan"
                      value="False"
                      required
                    /><span class="college-input-outside"
                      ><span class="college-input-inside"></span
                    ></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*就讀科系：</span>
                <select
                  class="item-input item-select col"
                  name="department"
                  required
                >
                  <option selected disabled value="">-請選擇-</option>
                  <option
                    :disabled="item.disabled"
                    v-for="(item, index) in schoolDisciplines[formSchool]"
                    :key="index"
                    :value="item.value"
                  >
                    {{ item.title }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*打工或兼職：</span>
                <div class="radio-custom-college">
                  <label class="item-label">
                    是
                    <input
                      type="radio"
                      class="radio-inline"
                      name="is_part_time_job"
                      value="True"
                      required
                    /><span class="college-input-outside"
                      ><span class="college-input-inside"></span
                    ></span>
                  </label>
                </div>
                <div class="radio-custom-college">
                  <label class="item-label">
                    否
                    <input
                      type="radio"
                      class="radio-inline"
                      name="is_part_time_job"
                      value="False"
                      required
                    /><span class="college-input-outside"
                      ><span class="college-input-inside"></span
                    ></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*每月收入：</span>
                <select class="item-input col" name="monthly_economy" required>
                  <option selected disabled value="">-請選擇-</option>
                  <option value="3000">低於 5,000 元</option>
                  <option value="6000">5,000 元 - 10,000 元</option>
                  <option value="12500">10,000 元 - 15,000 元</option>
                  <option value="17000">15,000 元 - 20,000 元</option>
                  <option value="20200">20,000 以上</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*電子信箱：</span>
                <input class="item-input col" name="email" required />
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*手機號碼：</span>
                <input
                  class="item-input col"
                  name="phone"
                  maxlength="10"
                  required
                />
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">LINE帳號：</span>
                <input class="item-input col" name="line" />
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-md-6 col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">*用戶暱稱：</span>
                <input class="item-input col" name="name" required />
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-12">
              <div class="d-flex flex-wrap mb-md-4 mb-1">
                <span class="item">借款原因：</span>
                <textarea
                  class="item-input item-textarea col"
                  name="reason"
                ></textarea>
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-12">
              <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
                <span class="item">* 一對一專人聯繫服務：</span>
                <div class="radio-custom-college">
                  <label class="item-label">
                    有意願
                    <input
                      type="radio"
                      class="radio-inline"
                      name="is_contact"
                      value="True"
                      required
                    /><span class="college-input-outside"
                      ><span class="college-input-inside"></span
                    ></span>
                  </label>
                </div>
                <div class="radio-custom-college">
                  <label class="item-label">
                    無意願
                    <input
                      type="radio"
                      class="radio-inline"
                      name="is_contact"
                      value="Fales"
                      required
                    /><span class="college-input-outside"
                      ><span class="college-input-inside"></span
                    ></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row no-gutters justify-content-center">
            <div class="d-flex align-items-center flex-wrap col-md-8 col-12">
              <span class="item">方便聯繫時間：</span>
              <select type="text" class="item-input col" name="contact_time">
                <option selected disabled value="">-請選擇-</option>
                <option value="隨時">隨時</option>
                <option value="上午(09:00 ~ 12:00)">上午(09:00 ~ 12:00)</option>
                <option value="下午(13:30 ~ 18:00)">下午(13:30 ~ 18:00)</option>
              </select>
            </div>
            <input type="hidden" name="identity" value="1" />
            <button class="btn btn-form-submit col-md-auto col-12">
              取得報告
            </button>
          </div>
        </form>
        <div class="caculate-report row no-gutters" v-if="formCalculated">
          <div class="col-md-6 col-12 position-relative">
            <img
              src="@/asset/images/collegeLoan/caculate-img.png"
              class="caculate-img img-fluid"
            />
          </div>
          <div class="col">
            <div class="report-title">
              親愛的{{ borrowReportResult.name }}您好：
            </div>
            <div class="report-sub">
              <div>感謝您使用普匯的學生貸款額度利率評估服務，</div>
              <div>經系統自動評估後，符合您的額度及利率區間如下：</div>
            </div>
            <div class="report-info">
              <div class="report-info">
                <span class="report-info-title">1.可借款額度：</span>
                <span class="report-info-value">
                  {{ amount(borrowReportResult.amount) }}
                </span>
              </div>
              <div class="report-info">
                <span class="report-info-title">2.借款利率區間：</span>
                <span class="report-info-value">
                  {{ borrowReportResult.rate }}
                </span>
              </div>
              <div class="report-info">
                <span class="report-info-title">3.手續費金額：</span>
                <span class="report-info-value">
                  {{ amount(borrowReportResult.platform_fee) }}
                </span>
              </div>
              <div class="report-info">
                <span class="report-info-title">4.每期攤還金額約：</span>
                <span class="report-info-value">
                  {{ borrowReportResult.repayment }}
                </span>
              </div>
              <small class="report-info-hint">
                ►僅為初步評估，實際貸款條件依照您真實提供的資料而定。
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="review">
      <h1 class="h1">用戶案例採訪</h1>
      <div class="block-content">
        <alesis-suggestion-reviews type="college"></alesis-suggestion-reviews>
      </div>
    </div>
    <div class="member-info">
      <div class="block-content row no-gutters">
        <div class="col-md-6 col-12 text-col">
          <h1 class="h1">會員獨享資訊</h1>
          <h2 class="h2">
            <div>•全台信用良好大學</div>
            <div>•最愛借錢的學校</div>
            <div>•最愛借錢的科系</div>
          </h2>
          <div class="divider"></div>
          <div class="join-text">立即加入</div>
          <div class="join-btn">
            <div>
              <a
                href="https://www.influxfin.com/borrowLink"
                target="_blank"
                rel="noopener noreferrer"
              >
                <img src="/images/alesis-app-store.svg" class="download-ios" />
              </a>
            </div>
            <div>
              <a
                href="https://www.influxfin.com/borrowLink"
                target="_blank"
                rel="noopener noreferrer"
              >
                <img
                  src="/images/alesis-google-play.svg"
                  class="download-android"
                />
              </a>
            </div>
          </div>
        </div>
        <div class="col-md col-12">
          <img
            src="@/asset/images/collegeLoan/join-cover.png"
            class="join-cover img-fluid"
            alt="下載app"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import featureCard from '../component/index/featureCard'
import AlesisProject from '../component/alesis/AlesisProject'
import AlesisTaiwanMap from '../component/alesis/AlesisTaiwanMap'
import AlesisSuggestionReviews from "../component/alesis/AlesisSuggestionReviews"
import axios from 'axios'
import CollegeSchools from "../data/college_schools"
export default {
  components: {
    featureCard,
    AlesisProject,
    AlesisTaiwanMap,
    AlesisSuggestionReviews
  },
  data() {
    return {
      collegePreviews: [],
      schools: [],
      borrowReportResult: {
        name: '',
        amount: 0,
        rate: '',
        platform_fee: 0,
        repayment: ''
      },
      schoolDisciplines: {},
      originalSchools: CollegeSchools,
      collegePreviews: {},
      borrowReportResult: {},
      formSchool: '',
      formSchoolDiscipline: '',
      formCalculated: false
    }
  },
  mounted() {
    this.initSchools()
    axios.post(`/getCase`, { status: 10, product_id: 1 }).then((res) => {
      this.collegePreviews = res.data.slice(0, 4);
    }).catch((error) => {
      console.error('getCase 發生錯誤，請稍後再試');
    });
  },
  methods: {
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
    calculateForm() {
      let data = new FormData(this.$refs.borrowReport)
      this.formCalculated = false
      return axios({
        url: `/getBorrowReport`,
        method: 'post',
        data: data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Accept': 'application/json',
        }
      }).then((res) => {
        this.borrowReportResult.name = data.get('name')
        this.borrowReportResult.amount = res.data.amount
        this.borrowReportResult.rate = res.data.rate
        this.borrowReportResult.platform_fee = res.data.platform_fee
        this.borrowReportResult.repayment = res.data.repayment
        this.formCalculated = true
      })
        .catch((error) => {
          console.error('getBorrowReport 發生錯誤，請稍後再試');
        });

    },
    amount(value) {
      if (isNaN(Number(value))) {
        return value
      }
      return Number(value).toLocaleString('en')
    }
  },
}
</script>
<style lang="scss">
.radio-custom-college {
  display: contents;
  label {
    display: inline-block;
    position: relative;
    padding-left: 23px;
    cursor: pointer;
    width: auto;
    margin-right: 20px;
    line-height: 29px;
  }

  input {
    height: 1px;
    width: 1px;
    opacity: 0;
  }

  .college-input-outside {
    display: inline-block;
    position: absolute;
    left: 0%;
    top: 30%;
    margin-top: -10px;
    width: 40px;
    height: 40px;
    border: 2px solid #036eb7;
    border-radius: 12px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    background: #fff;
  }

  .college-input-inside {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    display: inline-block;
    border-radius: 12px;
    width: 30px;
    height: 30px;
    background: #036eb7;
    left: 3px;
    top: 3px;
    -webkit-transform: scale(0, 0);
    transform: scale(0, 0);
  }
  .no-transforms .college-input-inside {
    left: auto;
    top: auto;
    width: 0;
    height: 0;
  }

  input:checked + .college-input-outside .college-input-inside {
    -webkit-animation: radio-select 0.1s linear;
    animation: radio-select 0.1s linear;
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
  .no-transforms input:checked + .college-input-outside .college-input-inside {
    width: 10px;
    height: 10px;
  }
}
@media screen and (max-width: 767px) {
  .radio-custom-college label {
    margin-right: 10px;
  }
  .radio-custom-college .college-input-outside {
    margin-top: -6px;
    width: 22px;
    height: 22px;
  }
  .radio-custom-college .college-input-inside {
    width: 16px;
    height: 16px;
    top: 1px;
    left: 1px;
  }
}
</style>
<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
.h1 {
  text-align: center;
  font-style: normal;
  font-weight: 700;
  font-size: 56px;
  line-height: 1.4;
}
.h2 {
  text-align: center;
  font-style: normal;
  font-weight: 500;
  font-size: 40px;
  line-height: 1.4;
}
.block-content {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
}
.college-loan {
  .intro {
    padding: 100px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
  }
  .feature-cards {
    justify-content: center;
    margin-top: 80px;
    gap: 30px;
    .feature-card {
      &-sub {
        text-align: center;
        color: $color--primary;
        font-style: normal;
        font-weight: 400;
        opacity: 0.64;
        font-size: 20px;
        line-height: 1.4;
        margin-bottom: 16px;
      }
      &-list {
        opacity: 0.64;
        padding-inline-start: 20px;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 23px;
      }
    }
  }
  .apply-intro {
    padding: 50px 0 100px;
    background-color: $color__background--primary;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .apply-icons {
      display: flex;
      justify-content: space-between;
      max-width: 1037px;
      width: 100%;
      margin: 84px auto;
      .apply-icon {
        &-img {
          width: 110px;
          height: 110px;
        }
        &-text {
          text-align: center;
          margin-top: 28px;
          color: $color__text--primary;
          font-style: normal;
          font-weight: 500;
          font-size: 32px;
          line-height: 1.4;
        }
      }
    }
  }
  .apply-slogan {
    margin: 100px auto;
    max-width: 1220px;
    position: relative;
    &-img {
      position: absolute;
      width: 452px;
    }
    &-text {
      padding-top: 70px;
      font-style: normal;
      font-weight: 700;
      font-size: 40px;
      line-height: 1.4;
      text-align: right;
      color: $color--primary;
      .em {
        font-family: 'Arial';
        font-style: italic;
        font-weight: 700;
        font-size: 96px;
        line-height: 1.4;
        color: #f29500;
        margin: 0 10px;
      }
      .line-1 {
        margin-bottom: 36px;
      }
    }
  }
  .step {
    padding: 64px 0 100px;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .step-main {
      margin: 110px auto;
      position: relative;
      display: flex;
      width: 100%;
      max-width: 700px;
      flex-direction: column;
      align-items: flex-end;
    }
    .step-content {
      width: 275px;
      padding: 0 15px 10px 60px;
      border-left: 5px solid $color--primary;
      &:hover,
      &:focus-within {
        .step-content-title {
          color: $color__text--primary;
        }
        .step-content-info {
          opacity: 1;
        }
      }
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        color: $color--primary;
        &::before {
          background: $color--primary;
          border-radius: 50%;
          color: #fff;
          font-size: 36px;
          font-style: normal;
          font-weight: 500;
          position: absolute;
          right: 243px;
          text-align: center;
          width: 60px;
        }
      }
      &-info {
        margin-top: 8px;
        font-weight: 400;
        font-size: 20px;
        line-height: 23px;
        color: $color__text--primary;
        opacity: 0.56;
      }
      &-img {
        top: 0;
        left: 15px;
        position: absolute;
        transition-property: opacity;
        transition-duration: 1s;
        opacity: 0;
      }
      &-1 {
        .step-content-title::before {
          content: '1';
        }
        .step-content-img {
          opacity: 1;
          z-index: 3;
        }
      }
      &-2 {
        .step-content-title::before {
          content: '2';
        }
        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }
      &-3 {
        .step-content-title::before {
          content: '3';
        }
        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }
      &-4 {
        border-left: none;
        .step-content-title::before {
          content: '4';
        }
        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }
    }
  }
  .reason {
    padding: 50px 0;
    background-color: $color__background--primary;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .bottom-title {
      margin-top: 100px;
      text-align: center;
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      color: #f29500;
    }
    .reason-row {
      margin-top: 84px;
    }
    .reason-intro {
      padding-right: 120px;
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 48px;
        line-height: 1.6;
        color: $color--primary;
      }
      &-sub {
        font-style: normal;
        font-weight: 700;
        font-size: 30px;
        line-height: 1.6;
        color: $color--primary;
        opacity: 0.64;
        margin-bottom: 24px;
      }
      &-item {
        display: flex;
        font-style: normal;
        font-weight: 500;
        font-size: 32px;
        line-height: 1.4;
        color: $color__text--primary;
        margin-bottom: 24px;
        min-width: 580px;
        &::before {
          content: '';
          background-position: 0 5px;
          flex: 0 0 85px;
          background-image: url('~images/collegeLoan/list.png');
          background-repeat: no-repeat;
          height: 65px;
          display: block;
        }
      }
    }
    .reason-image-group {
      .reason-item {
        &-1 {
          left: -60px;
          top: 0;
        }
        &-2 {
          top: 90px;
          right: 60px;
        }
        &-3 {
          bottom: 85px;
          right: 115px;
        }
        &-4 {
          left: -115px;
          bottom: 200px;
        }
        position: absolute;
        font-style: normal;
        border: 2px solid #cde5f5;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        text-align: center;
        font-weight: 500;
        font-size: 36px;
        line-height: 120px;
        color: $color--primary;
      }
      .img-logo {
        position: absolute;
        left: -7.4px;
        top: 28.5px;
      }
      .img-outline {
        animation-duration: 60s;
        animation-iteration-count: infinite;
        animation-name: outline-img;
      }
      @keyframes outline-img {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }
    }
  }
  .case {
    padding: 50px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .projects-row {
      margin-top: 100px;
      .project-title {
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        line-height: 1.4;
        color: $color__text--primary;
        margin-bottom: 12px;
      }
      .case-col {
        display: grid;
        gap: 20px;
        grid-template-columns: auto 1fr;
      }
    }
  }
  .caculate {
    padding: 50px 0;
    background-color: $color__background--primary;
    .block-content {
      max-width: 1200px;
    }
    .h1 {
      color: $color--primary;
    }
    .caculate-form {
      margin-top: 120px;
      .item {
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 1.4;
        min-width: 195px;
        color: $color__text--primary;
      }
      .item-input {
        font-size: 24px;
        line-height: 1.4;
        border: 2px solid $color--primary;
        border-radius: 12px;
        margin-right: 25px;
      }
      .item-label {
        position: relative;
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 1.4;
        color: $color__text--primary;
        padding-left: 45px;
      }
      .btn-form-submit {
        padding: 0px 120px;
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 1.4;
        color: #ffffff;
        background-color: $color--primary;
        border-radius: 12px;
      }
    }
  }
  .caculate-report {
    margin-top: 160px;
    .caculate-img {
      position: absolute;
      top: 15px;
      left: 0;
    }
    .report-title {
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      color: $color--primary;
      margin-bottom: 12px;
    }
    .report-sub {
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 1.8;
      color: $color--primary;
      opacity: 0.64;
      margin-bottom: 24px;
    }
    .creadit {
      margin-bottom: 16px;
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 40px;
        line-height: 1.4;
        color: $color__text--primary;
      }
      &-level {
        font-style: normal;
        font-weight: 700;
        font-size: 40px;
        line-height: 1.4;
        color: #f29500;
      }
    }
    .report-info {
      margin-bottom: 15px;
    }
    .report-info-title {
      font-style: normal;
      font-weight: 500;
      font-size: 30px;
      line-height: 1.4;
      color: $color__text--primary;
      opacity: 0.64;
    }
    .report-info-value {
      font-style: normal;
      font-weight: 500;
      font-size: 30px;
      line-height: 1.4;
      color: $color__text--primary;
    }
    .report-info-hint {
      margin-top: 8px;
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 1.4;
      color: #f29500;
    }
  }
  .review {
    padding: 64px 0;
    .h1 {
      color: $color--primary;
      margin-bottom: 90px;
    }
  }
  .member-info {
    background-color: $color__background--primary;
    .text-col {
      padding: 64px 0;
      .h1 {
        color: $color--primary;
        text-align: left;
      }
      .h2 {
        color: $color--primary;
        opacity: 0.64;
        font-size: 32px;
        margin-top: 16px;
        text-align: left;
      }
      .divider {
        width: 256px;
        margin: 24px 0;
        border-bottom: 6px solid #fff;
        border-radius: 100px;
      }
      .join-text {
        padding-left: 110px;
        font-style: normal;
        font-weight: 700;
        font-size: 40px;
        line-height: 1.4;
        color: $color__text--primary;
        margin-bottom: 16px;
      }
      .join-btn {
        display: flex;
        gap: 24px;
        .download-android,
        .download-ios {
          height: 54px;
        }
      }
    }
  }
}
@media screen and (max-width: 767px) {
  .h1 {
    font-size: 28px;
  }
  .h2 {
    font-size: 18px;
  }
  .block-content {
    padding: 0 25px;
  }
  .college-loan {
    .intro {
      padding: 36px 0;
      .h2 {
        margin-top: 8px;
      }
    }
    .feature-cards {
      margin-top: 36px;
      gap: 15px;
      .feature-card {
        &-sub {
          font-size: 16px;
          margin-bottom: 12px;
        }
        &-list {
          padding-inline-start: 14px;
          font-size: 12px;
          line-height: 1.4;
        }
      }
    }
    .apply-intro {
      padding: 50px 0;
      background-color: $color__background--primary;
      .h1 {
        color: $color--primary;
      }
      .h2 {
        color: $color--primary;
        opacity: 0.64;
        margin-top: 24px;
      }
      .apply-icons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin: 35px auto;
        .apply-icon {
          &-item {
            display: flex;
            flex-direction: column;
            align-items: center;
          }
          &-img {
            width: 95px;
            height: 95px;
          }
          &-text {
            margin-top: 12px;
            font-size: 20px;
            text-align: left;
          }
        }
      }
    }
    .apply-slogan {
      margin: 60px auto;
      flex-direction: column-reverse;
      max-width: 1220px;
      position: relative;
      &-img {
        margin: 25px auto;
        position: relative;
        left: 0;
        width: 287px;
        top: 0;
      }
      &-text {
        padding-top: 64px;
        font-size: 32px;
        text-align: center;
        .em {
          font-size: 64px;
          margin: 0 10px;
        }
      }
    }
    .step {
      padding: 36px 0 80px;
      .h2 {
        margin-top: 8px;
      }
      .step-main {
        margin: 48px auto;
        position: relative;
        display: flex;
        width: 100%;
        max-width: 700px;
        flex-direction: column;
        align-items: flex-end;
      }
      .step-content {
        width: 200px;
        padding: 0 15px 10px 20px;
        border-left: 5px solid $color--primary;
        &:hover,
        &:focus-within {
          .step-content-title {
            color: $color__text--primary;
          }
          .step-content-info {
            opacity: 1;
          }
        }
        &-title {
          font-size: 18px;
          &::before {
            color: #fff;
            font-size: 18px;
            right: 184.5px;
            width: 27px;
          }
        }
        &-info {
          font-size: 12px;
          line-height: 1.4;
        }
        &-img {
          top: -85px;
          left: -50px;
          transform: scale(0.6);
        }
        &-1 {
          .step-content-title::before {
            content: '1';
          }
          .step-content-img {
            opacity: 1;
            z-index: 3;
          }
        }
        &-2 {
          .step-content-title::before {
            content: '2';
          }
          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }
        &-3 {
          .step-content-title::before {
            content: '3';
          }
          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }
        &-4 {
          .step-content-title::before {
            content: '4';
          }
          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }
      }
    }
    .reason {
      padding: 36px 0;
      .h2 {
        margin-top: 8px;
      }
      .bottom-title {
        margin-top: 24px;
        font-size: 28px;
      }
      .reason-row {
        margin-top: 24px;
      }
      .reason-intro {
        flex-direction: column;
        padding-right: 0;
        &-title {
          font-size: 20px;
          text-align: center;
        }
        &-sub {
          font-size: 14px;
          text-align: center;
          margin-bottom: 12px;
        }
        &-item {
          display: flex;
          font-size: 18px;
          line-height: 1.4;
          color: $color__text--primary;
          margin-bottom: 13px;
          &::before {
            content: '';
            flex: 0 0 32px;
            background-size: contain;
            background-position: center;
            height: 32px;
            margin-right: 15px;
          }
        }
      }
      .reason-image-group {
        margin: 20px auto;
        .reason-item {
          &-1 {
            left: -28px;
            top: 0;
          }
          &-2 {
            top: 40px;
            right: -50px;
          }
          &-3 {
            bottom: -20px;
            right: 0px;
          }
          &-4 {
            left: -50px;
            bottom: 40px;
          }
          width: 51px;
          height: 51px;
          font-size: 18px;
          line-height: 51px;
        }
        .img-logo {
          position: absolute;
          left: 17.5px;
          width: 200px;
          top: 36.5px;
        }
        .img-outline {
          width: 240px;
          animation-duration: 60s;
          animation-iteration-count: infinite;
          animation-name: outline-img;
        }
      }
    }
    .case {
      padding: 36px 0;
      .h1 {
        color: $color--primary;
      }
      .h2 {
        margin-top: 8px;
      }
      .projects-row {
        margin-top: 24px;
        .project-title {
          font-size: 20px;
          text-align: center;
          margin-bottom: 24px;
        }
        .case-col {
          display: flex;
          flex-direction: column;
          align-items: center;
          transform: scale(0.8);
          margin-top: -102px;
          gap: 16px;
        }
      }
    }
    .caculate {
      padding: 36px 0;
      .h1 {
        color: $color--primary;
      }
      .caculate-form {
        margin-top: 56px;
        .item {
          font-size: 16px;
          margin-bottom: 8px;
          flex: 0 1 100%;
        }
        .item-input {
          font-size: 16px;
          line-height: 1.4;
          border: 2px solid $color--primary;
          border-radius: 12px;
          margin-right: 25px;
        }
        .item-label {
          font-size: 16px;
          padding-left: 30px;
        }
        .btn-form-submit {
          padding: 8px 100px;
          font-size: 16px;
          margin-top: 28px;
        }
      }
    }
    .caculate-report {
      margin-top: 56px;
      flex-direction: column-reverse;
      .caculate-img {
        position: relative;
        top: 0;
        left: -25px;
        transform: scale(1.2);
      }
      .report-title {
        font-size: 28px;
        text-align: center;
        margin-bottom: 8px;
      }
      .report-sub {
        font-size: 18px;
        margin-bottom: 16px;
      }
      .creadit {
        margin-bottom: 16px;
        text-align: center;
        &-title {
          font-size: 24px;
        }
        &-level {
          font-size: 24px;
        }
      }
      .report-info {
        margin-bottom: 15px;
      }
      .report-info-title {
        font-size: 18px;
      }
      .report-info-value {
        font-size: 18px;
      }
      .report-info-hint {
        text-align: center;
        font-size: 14px;
      }
    }
    .review {
      padding: 36px 0;
      .h1 {
        color: $color--primary;
        margin-bottom: 30px;
      }
    }
    .member-info {
      background-color: $color__background--primary;
      .text-col {
        padding: 36px 0;
        .h1 {
          text-align: center;
        }
        .h2 {
          font-size: 18px;
          text-align: center;
        }
        .divider {
          width: 86px;
          margin: 12px auto;
          border-bottom: 4px solid #fff;
        }
        .join-text {
          font-size: 24px;
          margin-bottom: 16px;
          padding-left: 0;
          text-align: center;
        }
        .join-btn {
          display: flex;
          justify-content: center;
          gap: 15px;
          .download-android,
          .download-ios {
            height: 36px;
          }
        }
      }
    }
  }
}
</style>
