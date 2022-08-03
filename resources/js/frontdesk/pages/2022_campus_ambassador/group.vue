<template>
  <div class="personal">
    <!-- top -->
    <div class="page-header">
      <div class="page-header-title">
        <div class="page-header-title-text">團體組</div>
        <div class="page-header-title-underline"></div>
      </div>
      <div class="step-back" v-if="currentStep > 1 && currentStep < 6">
        <button
          class="btn btn-link"
          @click="
            currentStep -= 1
            error = false
          "
        >
          <img src="@/asset/images/2022_campus_ambassador/Arrow 4_2.svg" />
        </button>
      </div>
    </div>
    <!-- step-intro -->
    <div class="step-intro" v-show="currentStep === 0">
      <div class="intro-text">
        <div>準備好成為普匯金融科技校園大使了嗎？</div>
        <div>快召集給力隊友，讓普匯更了解你們吧！</div>
      </div>
      <div class="intro-hint">3~4人為一組不可跨校</div>
      <div class="rating-table-title">徵選評分項目</div>
      <div class="rating-table">
        <div class="rating-row">
          <div class="rating-item">項目</div>
          <div class="rating-content">評選內容</div>
          <div class="rating-percent">佔比</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">生活照</div>
          <div class="rating-content">個人形象</div>
          <div class="rating-percent">10%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">自我介紹</div>
          <div class="rating-content">
            <div>200~500字</div>
            <div>一句話行銷自己</div>
          </div>
          <div class="rating-percent">15%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">個人社群</div>
          <div class="rating-content">
            <div>FB/IG/部落格/其它</div>
            <div>(需設定公開)</div>
          </div>
          <div class="rating-percent">15%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">普匯知多少</div>
          <div class="rating-content">普匯相關問題</div>
          <div class="rating-percent">15%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">
            <div>校園推廣</div>
            <div>企劃提案</div>
          </div>
          <div class="rating-content">
            <div>活動目的、活動內容、</div>
            <div>執行方式、人力&預算</div>
            <div>
              <a
                class="btn-link"
                href="https://www.influxfin.com/upload/%E6%A0%A1%E5%9C%92%E5%A4%A7%E4%BD%BF%E4%BC%81%E5%8A%83%E6%8E%A8%E5%BB%A3%E7%AF%84%E4%BE%8B.pdf"
                target="_blank"
              >
                點此下載範例
              </a>
            </div>
          </div>
          <div class="rating-percent">25%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">作品集</div>
          <div class="rating-content">
            <div>圖文作品/影音作品</div>
            <div>活動企劃/其他作品集</div>
          </div>
          <div class="rating-percent">20%</div>
        </div>
      </div>
      <form @submit.prevent="currentStep = 1">
        <div class="rating-check">
          <label>
            <input type="checkbox" required />
            本人已閱讀並同意詳情活動辦法及說明
          </label>
        </div>
        <div class="rating-start">
          <button class="btn btn-campus-primary">開始報名</button>
        </div>
      </form>
    </div>
    <!-- step-choose -->
    <div class="step-choose" v-show="currentStep === 1">
      <div class="step-title">選擇你是誰?</div>
      <div class="intro-text text-center">
        <div>*每隊需先推派組長人選</div>
        <div>由組長統一提供團隊提案與作品集</div>
      </div>
      <div class="mt-4">
        <button
          class="btn btn-block btn-choose-primary"
          @click="
            leader = 1
            currentStep = 2
          "
        >
          我是組長
        </button>
        <button
          class="btn btn-block btn-choose-secondary mt-4"
          @click="
            leader = 0
            currentStep = 2
          "
        >
          我是組員
        </button>
      </div>
    </div>
    <!-- step-header -->
    <div class="step-header" v-show="currentStep > 1">
      <div class="num-grid">
        <div class="step-header-num" :class="{ active: currentStep > 1 }">
          1
        </div>
        <div class="step-header-arrow">
          <img
            src="@/asset/images/2022_campus_ambassador/Arrow 1.svg"
            class="img-fluid"
          />
        </div>
        <div class="step-header-num" :class="{ active: currentStep > 2 }">
          2
        </div>
        <div class="step-header-arrow">
          <img
            src="@/asset/images/2022_campus_ambassador/Arrow 1.svg"
            class="img-fluid"
          />
        </div>
        <div class="step-header-num" :class="{ active: currentStep > 3 }">
          3
        </div>
        <div class="step-header-arrow">
          <img
            src="@/asset/images/2022_campus_ambassador/Arrow 1.svg"
            class="img-fluid"
          />
        </div>
        <div class="step-header-num" :class="{ active: currentStep > 4 }">
          4
        </div>
      </div>
      <div class="title-grid">
        <div class="step-header-text" :class="{ active: currentStep > 1 }">
          基本資料
        </div>
        <div class="step-header-text" :class="{ active: currentStep > 2 }">
          推廣你自己
        </div>
        <div class="step-header-text" :class="{ active: currentStep > 3 }">
          普匯知多少
        </div>
        <div class="step-header-text" :class="{ active: currentStep > 4 }">
          提案&作品
        </div>
      </div>
    </div>
    <!-- step-1 -->
    <form
      @submit.prevent="currentStep = 3"
      class="form"
      ref="step1"
      v-show="currentStep === 2"
    >
      <div class="step-1">
        <div class="step-title">基本資料</div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">團隊名稱:</span>
              <input
                type="text"
                class="item-input"
                name="group_name"
                placeholder="請輸入您的團隊名稱"
                required
                maxlength="20"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">就讀學校:</span>
              <select
                class="item-input"
                name="school"
                v-model="formSchool"
                placeholder="請選擇您的學校"
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
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">學校所在地:</span>
              <select
                class="item-input"
                name="school_city"
                placeholder="請選擇您學校所在縣市"
                required
              >
                <option selected disabled value="">-請選擇-</option>
                <option value="臺北市">臺北市</option>
                <option value="新北市">新北市</option>
                <option value="桃園市">桃園市</option>
                <option value="臺中市">臺中市</option>
                <option value="臺南市">臺南市</option>
                <option value="高雄市">高雄市</option>
                <option value="新竹縣">新竹縣</option>
                <option value="苗栗縣">苗栗縣</option>
                <option value="彰化縣">彰化縣</option>
                <option value="南投縣">南投縣</option>
                <option value="雲林縣">雲林縣</option>
                <option value="嘉義縣">嘉義縣</option>
                <option value="屏東縣">屏東縣</option>
                <option value="宜蘭縣">宜蘭縣</option>
                <option value="花蓮縣">花蓮縣</option>
                <option value="臺東縣">臺東縣</option>
                <option value="澎湖縣">澎湖縣</option>
                <option value="金門縣">金門縣</option>
                <option value="金門縣">連江縣</option>
                <option value="基隆市">基隆市</option>
                <option value="新竹市">新竹市</option>
                <option value="嘉義市">嘉義市</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">下一步</button>
        </div>
      </div>
    </form>
    <!-- step-2 -->
    <form
      class="form"
      @submit.prevent="currentStep = 4"
      ref="step2"
      v-show="currentStep === 3"
    >
      <!-- step-2 -->
      <div class="step-2">
        <div class="step-title">推廣你自己</div>
        <div class="row no-gutters">
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">科系:</span>
              <select
                class="item-input"
                name="major"
                placeholder="請選擇您的科系"
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
              <span class="item required">年級:</span>
              <select
                class="item-input"
                name="grade"
                placeholder="請選擇您的年級"
                required
              >
                <option selected disabled value="">-請選擇-</option>
                <option value="1">大一</option>
                <option value="2" class="">大二</option>
                <option value="3" class="">大三</option>
                <option value="4" class="">大四</option>
                <option value="5" class="">延畢</option>
                <option value="6" class="">研究所</option>
                <option value="7" class="">其他</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的姓名:</span>
              <input
                type="text"
                class="item-input"
                name="name"
                placeholder="請輸入您的姓名"
                required
                maxlength="20"
              />
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
              <span class="item required">你的生日:</span>
              <input type="date" class="item-input" name="birthday" required />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的電話:</span>
              <input
                type="text"
                class="item-input"
                name="phone"
                placeholder="請輸入您的電話"
                required
                maxlength="10"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的 Email:</span>
              <input
                type="email"
                class="item-input"
                name="email"
                placeholder="請輸入您的Email"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">
                你的社群連結(instagram/ Facebook):
              </span>
              <input
                type="url"
                class="item-input"
                name="social"
                placeholder="請輸入您的社群連結"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的生活照(3MB內JPG檔):</span>
              <input
                type="file"
                class="item-input"
                name="photo"
                accept=".jpeg"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">一句話行銷自己:</span>
              <input
                type="text"
                class="item-input"
                name="introduction_brief"
                placeholder="請用一句話描述自己"
                maxlength="20"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">自我介紹:</span>
              <textarea
                class="item-input"
                name="introduction"
                placeholder="請進行 200-500字的自我介紹"
                style="min-height: 300px"
                required
              >
              </textarea>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">下一步</button>
        </div>
      </div>
    </form>
    <!-- step-3 -->
    <form
      class="form"
      @submit.prevent="currentStep = 5"
      ref="step3"
      v-show="currentStep === 4"
    >
      <div class="step-3">
        <div class="step-title">普匯知多少</div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex flex-column mb-md-4 mb-2">
              <span class="item required">
                1.下列關於普匯的敘述何者正確？
              </span>
              <div class="radio-custom">
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_1"
                    class="radio"
                    value="1"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  普匯的定義：普惠金融，匯集人才
                </label>
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_1"
                    class="radio"
                    value="2"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  台灣唯一榮獲金控創投投資的金融科技公司
                </label>
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_1"
                    class="radio"
                    value="3"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  以上皆是
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex flex-column mb-md-4 mb-2">
              <span class="item required">
                2. 關於普匯學生貸，以下敘述何者正確？
              </span>
              <div class="radio-custom">
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_2"
                    class="radio"
                    value="1"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  學生貸額度最高15萬，利率最低4%
                </label>
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_2"
                    class="radio"
                    value="2"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  準備學生證、雙證件、金融帳號，使用手機APP即可申請
                </label>
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_2"
                    class="radio"
                    value="3"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  以上皆是
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex flex-column mb-md-4 mb-2">
              <span class="item required">
                3.普匯近期榮登新北市經濟發展局辦理的哪一論壇
                <div class="d-sm-block d-none">分享金融科技趨勢？</div>
                <div class="d-inline d-sm-none">分享金融科技趨勢？</div>
              </span>
              <div class="radio-custom">
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_3"
                    class="radio"
                    value="1"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  新北產業發展論壇
                </label>
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_3"
                    class="radio"
                    value="2"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  足球論壇
                </label>
                <label class="w-100">
                  <input
                    type="radio"
                    name="qa_3"
                    class="radio"
                    value="3"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>
                  歌唱論壇
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">下一步</button>
        </div>
      </div>
    </form>
    <!-- step-4 -->
    <form
      class="form"
      @submit.prevent="doSubmit"
      ref="step4"
      v-show="currentStep === 5"
    >
      <div class="step-4" v-if="leader === 1">
        <div class="step-title">提案&作品</div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item"
                >校園推廣企劃提案:
                <a
                  class="btn-link"
                  href="https://www.influxfin.com/upload/%E6%A0%A1%E5%9C%92%E5%A4%A7%E4%BD%BF%E4%BC%81%E5%8A%83%E6%8E%A8%E5%BB%A3%E7%AF%84%E4%BE%8B.pdf"
                  target="_blank"
                >
                  點此下載範例
                </a>
              </span>
              <input
                type="file"
                accept=".pdf"
                class="item-input"
                name="proposal"
                placeholder="僅接受PDF檔，10MB內"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item">作品集(5MB內PDF檔):</span>
              <input
                type="file"
                class="item-input"
                name="portfolio"
                accept=".pdf"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item">影片上傳(30MB內MP4檔):</span>
              <input
                type="file"
                class="item-input"
                name="video"
                placeholder="僅接受MP4檔，30MB內"
                accept=".mp4"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item">影片連結(如影片過大，請提供連結):</span>
              <input type="url" class="item-input" name="video_link" />
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">確認送出</button>
        </div>
        <div
          v-show="error"
          class="alert alert-danger alert-dismissible fade show mt-4"
          role="alert"
        >
          <button type="button" class="close" @click="error = false">
            <span aria-hidden="true">&times;</span>
          </button>
          <div v-html="errorMsg"></div>
        </div>
      </div>
      <div class="step-4" v-else>
        <div class="step-text">由組長統一負責提供</div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">確認送出</button>
        </div>
        <div
          v-show="error"
          class="alert alert-danger alert-dismissible fade show mt-4"
          role="alert"
        >
          <button type="button" class="close" @click="error = false">
            <span aria-hidden="true">&times;</span>
          </button>
          <div v-html="errorMsg"></div>
        </div>
      </div>
    </form>
    <!-- step-finished -->
    <div class="success-apply" v-show="currentStep === 6">
      <div class="name-tag">{{ groupName }}</div>
      <div>
        <img
          src="@/asset/images/2022_campus_ambassador/2022-campus-success-party.png"
        />
      </div>
      <div class="name-tag-2">{{ name }}</div>
      <div class="success-apply-text">報名成功!</div>
    </div>
  </div>
</template>

<script>
import CollegeSchools from "@/data/college_schools"
import Axios from 'axios';
export default {
  data() {
    return {
      originalSchools: CollegeSchools,
      schoolDisciplines: {},
      schools: [],
      formSchool: '',
      leader: 0,
      currentStep: 0,
      groupName: '',
      name: '',
      error: false,
      errorMsg: ''
    }
  },
  mounted() {
    alert('2022普匯校園大使報名已於07/28 (四)截止，感謝您的支持！\n請不吝給予未來的校園大使們多多關注喔！')
    this.$router.back()
    return
    // this.initSchools();
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
    doSubmit() {
      const all = new FormData()
      const form1 = new FormData(this.$refs.step1)
      const form2 = new FormData(this.$refs.step2)
      const form3 = new FormData(this.$refs.step3)
      const form4 = new FormData(this.$refs.step4)
      for (e of form1) {
        all.append(e[0], e[1])
      }
      for (e of form2) {
        all.append(e[0], e[1])
      }
      for (e of form3) {
        if (e[1]) {
          // has value
          all.append(e[0], e[1])
        }
      }
      for (e of form4) {
        if (e[1]) {
          // has value
          all.append(e[0], e[1])
        }
      }
      // append agree
      all.append('agree', true)
      all.append('leader', this.leader)
      Axios({
        url: '/api/v1/campus-ambassador/sign-up/group',
        data: all,
        method: 'post',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Accept': 'application/json',
        }
      }).then(({ data }) => {
        if (data.success) {
          this.currentStep = 6
          // group_name
          this.groupName = data.data.group_name
          this.name = data.data.name
        } else {
          alert(data.msg)
        }
      }).catch(({ response }) => {
        const d = response.data.data
        this.errorMsg = ''
        for (const item in d) {
          this.errorMsg += d[item].toString() + '<br>'
          console.log(item)
        }
        this.error = true
      })
    }
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #1981db;
.btn-campus-primary {
  padding: 8px 39px;
  background-color: $color--primary;
  font-style: normal;
  font-weight: 500;
  font-size: 32px;
  line-height: 46px;
  text-align: center;
  color: #ffffff;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 26px;
}
.btn-link {
  transform: scale(1.5);
}
.btn-choose-primary {
  padding: 8px;
  border-radius: 8px;
  font-style: normal;
  font-weight: 700;
  font-size: 32px;
  text-align: center;
  color: #fff;
  background-color: $color--primary;
}
.btn-choose-secondary {
  padding: 8px;
  border-radius: 8px;
  font-style: normal;
  font-weight: 700;
  font-size: 32px;
  text-align: center;
  color: #353535;
  background-color: #b4e4ff;
}
.form {
  .item {
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 1.4;
    margin-bottom: 8px;
    color: #676767;
    &.required {
      &::before {
        content: '*';
        color: #e41a23;
        margin-right: 2px;
      }
    }
  }
  .item-input {
    font-size: 16px;
    padding: 4px 12px;
    line-height: 1.4;
    border: 1px solid #afafaf;
    border-radius: 8px;
    margin-right: 25px;
    min-height: 28.1px;
    width: 100%;
  }
  .item-label {
    position: relative;
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 1.4;
    color: #676767;
    padding-left: 30px;
  }
  .radio-custom {
    display: block;
    padding-left: 25px;
    .outside {
      border: 2px solid #767676;
    }
  }
}
.step-title {
  color: $color--primary;
  font-style: normal;
  font-weight: 700;
  font-size: 36px;
  line-height: 1.4;
  margin: 36px 0;
  width: 100%;
  text-align: center;
}
.personal {
  width: 800px;
  padding: 34px 120px;
  box-shadow: 0px 4px 10px rgba(25, 92, 160, 0.4);
  border-radius: 20px;
  background: #fff;
  .page-header {
    position: relative;
    width: 100%;
    margin-bottom: 32px;
    background: none;
    .page-header-title {
      position: relative;
      display: flex;
      justify-content: center;
      &-text {
        position: relative;
        z-index: 3;
        font-style: normal;
        font-weight: 700;
        font-size: 56px;
        line-height: 1.4;
        color: #393939;
        transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
      }
      &-underline {
        z-index: 1;
        position: absolute;
        width: 194px;
        height: 18px;
        background: $color--primary;
        transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
        bottom: 4px;
      }
    }
    .step-back {
      position: absolute;
      left: -100px;
      top: -15px;
    }
  }
  .step-intro {
    .intro-text {
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 1.4;
      text-align: center;
      color: #353535;
      margin: 24px 0;
    }
    .intro-hint {
      color: $color--primary;
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 1.4;
      text-align: center;
      margin: 24px 0;
    }
    .rating-table {
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 1.4;
        text-align: center;
        color: #000000;
        margin-bottom: 15px;
      }
      .rating-row {
        display: grid;
        padding: 8px 30px;
        max-width: 600px;
        margin: 0 auto;
        grid-template-columns: 3fr 5fr 2fr;
        justify-content: center;
        align-items: center;
        gap: 15px;
        border-radius: 16px;
        font-style: normal;
        font-weight: 400;
        font-size: 20px;
        line-height: 1.4;
        color: #000;
        &:nth-child(odd) {
          background-color: #efefef;
        }
        &:first-child {
          background-color: $color--primary;
          color: #fff;
        }
      }
    }
    .rating-check,
    .rating-start {
      display: flex;
      justify-content: center;
      margin: 35px 0;
    }
  }
  .step-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 36px;
    .num-grid {
      display: grid;
      gap: 16px;
      grid-template-columns: 50px 70px 50px 70px 50px 70px 50px;
      align-items: center;
      margin-bottom: 16px;
    }
    .title-grid {
      display: grid;
      grid-template-columns: repeat(4, 135px);
      gap: 16px;
      margin-bottom: 16px;
    }
    &-num {
      &.active {
        background-color: $color--primary;
      }
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 46px;
      color: #ffffff;
      height: 46px;
      border-radius: 100px;
      text-align: center;
      background-color: #caccce;
    }
    &-text {
      &.active {
        color: $color--primary;
      }
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 1.4;
      text-align: center;
      color: #caccce;
    }
  }
  .step-text {
    margin: 32px 0;
    font-style: normal;
    font-weight: 350;
    font-size: 32px;
    line-height: 1.4;
    text-align: center;
    color: #000000;
  }
  .success-apply {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 50px 0;
    gap: 64px;
    .name-tag {
      background: $color--primary;
      width: 300px;
      padding: 4px 0;
      color: #fff;
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 1.4;
      border-radius: 20px;
      text-align: center;
    }
    .name-tag-2 {
      padding: 3px 62px;
      font-style: normal;
      font-weight: 400;
      font-size: 30px;
      line-height: 1.4;
      color: #353535;
      border: 1px solid #afafaf;
      border-radius: 8px;
    }
    .success-apply-text {
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      color: $color--primary;
    }
  }
}
@media screen and (max-width: 767px) {
  .btn-campus-primary {
    padding: 5px 22px;
    font-size: 13px;
    line-height: 19px;
  }
  .btn-link {
    transform: scale(1);
  }
  .btn-choose-primary {
    padding: 5px;
    font-size: 16px;
  }
  .btn-choose-secondary {
    padding: 5px;
    font-size: 16px;
  }
  .form {
    .item {
      font-size: 15px;
      &.required {
        &::before {
          content: '*';
          color: #e41a23;
          margin-right: 2px;
        }
      }
    }
    .item-input {
      font-size: 13px;
      padding: 4px 12px;
      border: 1px solid #afafaf;
      margin-right: 12px;
      width: 100%;
    }
    .item-label {
      font-size: 12px;
      padding-left: 15px;
    }
    .radio-custom {
      display: block;
      padding-left: 15px;
      font-size: 12px;
      .outside {
        border: 2px solid #767676;
      }
    }
  }
  .step-title {
    font-size: 24px;
    margin: 24px 0;
  }
  .personal {
    width: 100%;
    padding: 18px 20px;
    margin: 0 20px;
    .page-header {
      position: relative;
      width: 100%;
      margin-bottom: 18px;
      min-height: 0;
      background: none;
      .page-header-title {
        &-text {
          font-size: 32px;
        }
        &-underline {
          width: 100px;
          height: 14px;
        }
      }
      .step-back {
        position: absolute;
        left: -20px;
        top: -20px;
      }
    }
    .step-intro {
      .intro-text {
        font-size: 13px;
        margin: 12px 0;
      }
      .intro-hint {
        font-size: 16px;
        margin: 12px 0;
      }
      .rating-table {
        &-title {
          font-size: 14px;
          margin-bottom: 8px;
        }
        .rating-row {
          display: grid;
          padding: 8px 15px;
          margin: 0 auto;
          grid-template-columns: 4fr 7fr auto;
          gap: 15px;
          font-size: 12px;
        }
      }
      .rating-check,
      .rating-start {
        display: flex;
        justify-content: center;
        margin: 20px 0;
      }
    }
    .step-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 24px;
      .num-grid {
        display: grid;
        gap: 8px;
        grid-template-columns: repeat(7, 32px);
        align-items: center;
        margin-bottom: 16px;
      }
      .title-grid {
        display: grid;
        grid-template-columns: repeat(4, 70px);
        gap: 8px;
        margin-bottom: 16px;
      }
      &-num {
        font-size: 16px;
        line-height: 28px;
        height: 28px;
      }
      &-text {
        font-size: 12px;
      }
    }
    .success-apply {
      padding: 20px 0;
      gap: 32px;
      .name-tag {
        font-size: 20px;
      }
      .name-tag-2 {
        padding: 3px 32px;
        font-size: 18px;
      }
      .success-apply-text {
        font-size: 30px;
      }
    }
  }
}
</style>
