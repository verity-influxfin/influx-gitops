<template>
  <div class="campus-2022">
    <div class="cover">
      <img
        src="@/asset/images/2022_campus_ambassador/2022-campus-cover.png"
        alt="2022普匯校園大使"
        style="min-height: 700px"
        class="d-sm-block d-none img-fluid"
      />
      <img
        src="@/asset/images/2022_campus_ambassador/2022-campus-cover.png"
        alt="2022普匯校園大使"
        class="d-sm-none d-block img-fluid"
      />
      <div class="cover-join">
        <button class="btn btn-campus-primary" @click="scrollToJoin">
          我要報名
        </button>
      </div>
    </div>
    <div class="intro">
      <div class="block-content">
        <h1 class="h1">
          <div class="d-md-block d-none">還不認識普匯金融科技嗎？</div>
          <div class="d-block d-md-none">還不認識我們嗎？</div>
        </h1>
        <div class="row no-gutters">
          <div class="feature-grid">
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/index/handshake2.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">
                  <div>唯一榮獲新光</div>
                  <div>金控創投入資</div>
                </div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/index/link-1.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">
                  <div>榮獲最多家</div>
                  <div>銀行搶先合作</div>
                </div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/index/dating-app-1.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">
                  <div>大學生最愛</div>
                  <div>借貸媒合平台</div>
                </div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/index/planet-earth-1.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">
                  <div>致力公益活動</div>
                  <div>ESG金融科技平台</div>
                </div>
                <div class="card-divider"></div>
              </div>
            </div>
          </div>
        </div>
        <h2 class="h2">我們要找的就是你！</h2>
        <div class="intro-text d-md-block d-none">
          <div>喜歡交朋友、熱愛分享、喜歡活動、有高度執行力</div>
          <div>對經營社群有興趣、有業務洽談或推廣能力的你</div>
          <div class="mt-3">快加入普匯校園大使</div>
          <div>成為赤手可熱的<span class="em">Fintech人才</span></div>
        </div>
        <div class="intro-text d-block d-md-none">
          <div>喜歡交朋友、熱愛分享、</div>
          <div>喜歡活動、有高度執行力</div>
          <div>對經營社群有興趣、</div>
          <div>有業務洽談或推廣能力的你</div>
          <div class="mt-3">快加入普匯校園大使</div>
          <div>成為赤手可熱的<span class="em">Fintech人才</span></div>
        </div>
      </div>
    </div>
    <div class="benefit">
      <div class="card" data-aos="fade-up">
        <div class="card-body">
          <div class="card-title">
            <div class="card-title-text">好康福利</div>
            <div class="card-title-underline"></div>
          </div>
          <div class="card-sub">成為普匯校園大使，以下是你可以獲得的 !</div>
          <div class="row no-gutters">
            <div class="col-md-auto col-12 text-center">
              <img
                src="@/asset/images/2022_campus_ambassador/2022-campus-benefit-1.png"
              />
            </div>
            <div class="col-md col-12">
              <div class="card-row-title">職涯大加分</div>
              <div class="card-row-text">
                <div>•普匯校園大使結業證書</div>
                <div>•履歷健檢了解優劣勢</div>
                <div>•績優組別可獲執行長推薦函</div>
              </div>
            </div>
          </div>
          <div class="row no-gutters card-row-gap">
            <div class="col-md-auto col-12 text-center">
              <img
                src="@/asset/images/2022_campus_ambassador/2022-campus-benefit-2.png"
              />
            </div>
            <div class="col-md col-12">
              <div class="card-row-title">大使培訓計畫</div>
              <div class="card-row-text">
                <div>•普匯校園大使結業證書</div>
                <div>•履歷健檢了解優劣勢</div>
                <div>•績優組別可獲執行長推薦函</div>
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-md-auto col-12 text-center">
              <img
                src="@/asset/images/2022_campus_ambassador/2022-campus-benefit-3.png"
              />
            </div>
            <div class="col-md col-12">
              <div class="card-row-title">額外價值</div>
              <div class="card-row-text">
                <div>•透過普匯品牌建立滿滿人脈</div>
                <div>•達標獎金拿不完</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="info">
      <div class="card card-1" data-aos="fade-up">
        <div class="card-body">
          <div class="card-title">
            <div class="card-title-text">大使任務</div>
            <div class="card-title-underline"></div>
          </div>
          <div class="card-sub">身為普匯校園大使，邀請你與普匯一起</div>
          <div class="card-grid">
            <div class="card-grid-item">
              <div class="card-grid-item-img">
                <img
                  src="@/asset/images/2022_campus_ambassador/2022-campus-mission-1.png"
                />
              </div>
              <div class="card-grid-item-text">
                <div>了解市場、成為</div>
                <div>校園產品代言人</div>
              </div>
            </div>
            <div class="card-grid-item">
              <div class="card-grid-item-img">
                <img
                  src="@/asset/images/2022_campus_ambassador/2022-campus-mission-2.png"
                />
              </div>
              <div class="card-grid-item-text">
                <div>成為普匯品牌與</div>
                <div>學生間的專屬橋樑</div>
              </div>
            </div>
            <div class="card-grid-item">
              <div class="card-grid-item-img">
                <img
                  src="@/asset/images/2022_campus_ambassador/2022-campus-mission-3.png"
                />
              </div>
              <div class="card-grid-item-text">
                <div>提升普匯校園</div>
                <div>知名度與口碑</div>
              </div>
            </div>
            <div class="card-grid-item">
              <div class="card-grid-item-img">
                <img
                  src="@/asset/images/2022_campus_ambassador/2022-campus-mission-4.png"
                />
              </div>
              <div class="card-grid-item-text">社群建立與經營</div>
            </div>
            <div class="card-grid-item">
              <div class="card-grid-item-img">
                <img
                  src="@/asset/images/2022_campus_ambassador/2022-campus-mission-5.png"
                />
              </div>
              <div class="card-grid-item-text">
                <div>校園品牌活動提案</div>
                <div>推廣與實際執行</div>
              </div>
            </div>
            <div class="card-grid-item">
              <div class="card-grid-item-img">
                <img
                  src="@/asset/images/2022_campus_ambassador/2022-campus-mission-6.png"
                />
              </div>
              <div class="card-grid-item-text">
                <div>成效回報</div>
                <div>檢視自我成長</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-2" data-aos="fade-up" id="join-btn">
        <div class="card-body">
          <div class="card-title">
            <div class="card-title-text">資格&時程</div>
            <div class="card-title-underline"></div>
          </div>
          <div class="card-sub">
            <div>我們將尋找活潑外向的夥伴，不管你是團隊還是個人</div>
            <div>只要你符合以下條件都可以來報名普匯校園大使!</div>
          </div>
          <div class="card-condition required">
            不分國立 or 私立；大學 or 科大，大二(含)以上至碩士在學生
          </div>
          <div class="card-condition required">
            團體報名：自行組隊，3-4人一組 (不可跨校)
          </div>
          <div class="card-condition required">
            個人報名：錄取後由普匯分隊，一組3-4人以上
          </div>
          <div class="card-condition-title">加分項目</div>
          <div class="card-condition">學校社團經驗</div>
          <div class="card-condition">活動企劃經驗</div>
          <div class="card-condition">圖文製作&文案撰寫作品</div>
          <div class="card-condition">影片/短片相關企劃與作品</div>
          <div class="card-condition">還有我們不知道的嗎，快分享給我們！</div>
          <div class="join">
            <button class="btn btn-campus-primary" @click="goApply('group')">
              團體報名
            </button>
            <button
              class="btn btn-campus-secondary"
              @click="goApply('personal')"
            >
              個人報名
            </button>
          </div>
          <div class="row no-gutters justify-content-center mb-2">
            <button class="btn btn-link btn-text" @click="showRating">
              更多評分辦法>>>
            </button>
          </div>
          <div class="time">
            <div class="time-title">活動時程</div>
            <div class="time-row gray">
              <div>報名期間</div>
              <div class="time-row-date">2022/07/19 (二) - 2022/07/28 (四)</div>
            </div>
            <div class="time-row">
              <div>徵選期間</div>
              <div class="time-row-date">2022/07/29 (五) - 2022/08/02 (二)</div>
            </div>
            <div class="time-row gray">
              <div>名單公布</div>
              <div class="time-row-date">2022/08/04 (四)</div>
            </div>
            <div class="time-row">
              <div>大使任期</div>
              <div class="time-row-date">2022/08/10 (三) - 2023/01/28 (五)</div>
            </div>
            <div class="time-row blue">
              <div>教育訓練</div>
              <div class="time-row-date">2022/08/10 (三)、2022/08/12(五)</div>
            </div>
            <div class="time-hint mt-4">
              ＊主辦單位保留變更、修改本專案內容之權利
            </div>
            <div class="time-hint">
              ＊主辦單位有權就本專案作出解釋，任何人均不得異議
            </div>
            <button class="btn btn-link btn-text" @click="showDetail">
              更多活動詳情請見>>>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="history">
      <div class="block-content">
        <div class="history-title">
          <div class="history-title-text">歷任校園大使</div>
          <div class="history-title-underline"></div>
        </div>
        <div class="swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="slide-content">
                <div>
                  <img
                    src="@/asset/images/2022_campus_ambassador/2022-campus-history-1.png"
                    alt=""
                    class="img-fluid history-img"
                  />
                </div>
                <div class="card">
                  <div class="card-body">
                    <div class="card-title">黃稔桐/銘傳大學</div>
                    <div class="card-text">
                      我是銘傳大學國企系的黃稔桐，當初會想加入的原因是想增加自己的見聞，想增加一些對fintech
                      的知識。認識了一班積極的人，又為我未來發展提供了許多有用的意見！
                    </div>
                  </div>
                </div>
              </div>
              <div class="slide-content slide-content-2">
                <div class="card">
                  <div class="card-body">
                    <div class="card-title">鄭裕庭/海洋大學</div>
                    <div class="card-text">
                      我是鄭裕庭，大學就讀位於基隆的海洋大學，科系是電機系。我對金融以及科技的應用都十分感興趣，因此我在大四的時候加入了普匯校園大使的大家庭。
                    </div>
                  </div>
                </div>
                <div>
                  <img
                    src="@/asset/images/2022_campus_ambassador/2022-campus-history-2.png"
                    alt=""
                    class="img-fluid history-img"
                  />
                </div>
              </div>
            </div>
            <!-- <div class="swiper-slide">
              <div class="slide-content">
                <div class="card">
                  <div class="card-body">
                    <div class="card-title">鄭裕庭/海洋大學</div>
                    <div class="card-text">
                      我是鄭裕庭，大學就讀位於基隆的海洋大學，科系是電機系。我對金融以及科技的應用都十分感興趣，因此我在大四的時候加入了普匯校園大使的大家庭。
                    </div>
                  </div>
                </div>
                <div>
                  <img
                    src="@/asset/images/2022_campus_ambassador/2022-campus-history-2.png"
                    alt=""
                    class="img-fluid"
                  />
                </div>
              </div>
            </div> -->
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
    <div class="modal" id="detail-modal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h1 class="h1">活動詳情</h1>
            <h2 class="h2">舉辦目的</h2>
            <div class="modal-text d-md-block d-none">
              <div>
                培養各校對Fintech領域有興趣的同學，從活動提案執行、社群經營、業務合作洽談等項目
              </div>
              <div>
                激盪創意和增進提案落地執行的能力，從校園開始讓學生更認識普匯與金融科技產品，提升品牌知名度
              </div>
              <div>
                透過定期舉辦的金融科技知識講座提升年輕學子對於金融科技未來趨勢與內容的了解，為培育台灣Fintech人才盡一份心力
              </div>
            </div>
            <div class="modal-text text-left d-md-none d-block">
              培養各校對Fintech領域有興趣的同學，從活動提案執行、社群經營、業務合作洽談等項目
              激盪創意和增進提案落地執行的能力，從校園開始讓學生更認識普匯與金融科技產品，提升品牌知名度
              透過定期舉辦的金融科技知識講座提升年輕學子對於金融科技未來趨勢與內容的了解，為培育台灣Fintech人才盡一份心力
            </div>
            <h2 class="h2">招募期間</h2>
            <div class="modal-text">
              <div>7月：任期8月-1月</div>
              <div>1月：任期2月-7月</div>
              <div>招募期間以外，報名管道將持續開放，報名資料將入資料庫</div>
              <div>
                若期間因擴大招募入選會以Email通知，任期即從入選後始算6個月
              </div>
            </div>
            <h2 class="h2">大使任務</h2>
            <p class="modal-text">
              活動提案執行 x 社群經營 x 會員推廣 x 業務合作
            </p>
            <div class="join">
              <button class="btn btn-campus-primary" @click="goApply('group')">
                團體報名
              </button>
              <button
                class="btn btn-campus-secondary"
                @click="goApply('personal')"
              >
                個人報名
              </button>
            </div>
            <div class="modal-hint">
              <div>注意事項:</div>
              <ul>
                <li>校園大使報名者須詳閱活動辦法等並遵守其相關規範。</li>
                <li>
                  校園大使報名者上傳作品集限本人創作，不得抄襲他人或妨害他人著作權及另重複參加投稿。報名者如冒用或盜用第三人資料參賽、涉嫌抄襲、侵害他人智慧財產或涉及暴力、色情、譭謗、侵犯個資等違反善良風俗者，一經發覺，將立即取消資格。其因侵害他人所生之民、刑事責任由參賽者自行負責，與主辦單位無涉。
                </li>
                <li>
                  主辦單位保留活動辦法之異動權利，活動相關訊息以本網站公告內容為準，不另行通知。
                </li>
                <li>
                  主辦單位保留活動辦法之異動權利，活動相關訊息以本網站公告內容為準，不另行通知。
                </li>
                <li>
                  凡參與活動者即同意接受活動辦法與注意事項內容，如有不符活動規定或違反本活動之事項，主辦單位得以取消其資格，並對於破壞本活動之行為保留法律追溯權。
                </li>
                <li>若有任何未盡事宜，由主辦單位解釋。</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="rating-modal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h1 class="h1">評選項目與評分辦法</h1>
            <div class="modal-text">
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
                <div class="hint">
                  *團體報名者，每隊需先訂定團隊名稱並選任組長，報名時填上同一團隊名稱，並由組長統一彙整提供『校園推廣企劃案』、『作品集』兩項資料
                </div>
                <div class="hint">
                  *校園推廣企劃案請以『推廣普匯進入校園』為主題
                </div>
              </div>
            </div>
            <div class="modal-hint">
              <div>注意事項:</div>
              <ul>
                <li>校園大使報名者須詳閱活動辦法等並遵守其相關規範。</li>
                <li>
                  校園大使報名者上傳作品集限本人創作，不得抄襲他人或妨害他人著作權及另重複參加投稿。報名者如冒用或盜用第三人資料參賽、涉嫌抄襲、侵害他人智慧財產或涉及暴力、色情、譭謗、侵犯個資等違反善良風俗者，一經發覺，將立即取消資格。其因侵害他人所生之民、刑事責任由參賽者自行負責，與主辦單位無涉。
                </li>
                <li>
                  主辦單位保留活動辦法之異動權利，活動相關訊息以本網站公告內容為準，不另行通知。
                </li>
                <li>
                  主辦單位保留活動辦法之異動權利，活動相關訊息以本網站公告內容為準，不另行通知。
                </li>
                <li>
                  凡參與活動者即同意接受活動辦法與注意事項內容，如有不符活動規定或違反本活動之事項，主辦單位得以取消其資格，並對於破壞本活動之行為保留法律追溯權。
                </li>
                <li>若有任何未盡事宜，由主辦單位解釋。</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import 'swiper/swiper.scss'
import 'swiper/components/navigation/navigation.min.css'
import 'swiper/components/pagination/pagination.min.css'
import SwiperCore, { Navigation, Pagination } from 'swiper/core'
export default {
  mounted() {
    SwiperCore.use([Navigation, Pagination])
    const swiper = new Swiper('.swiper', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
      },
    })
  },
  methods: {
    scrollToJoin() {
      document.querySelector('#join-btn').scrollIntoView({ behavior: 'smooth' })
    },
    showDetail() {
      $('#detail-modal').modal('show')
    },
    showRating() {
      $('#rating-modal').modal('show')
    },
    goApply(type) {
      alert('2022普匯校園大使將於7/19(二)開放報名')
      // return
      // this.$router.push('/2022-campus-ambassador/' + type)
    }
  },
}
</script>

<style lang="scss" scoped>
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
.btn-campus-secondary {
  padding: 8px 39px;
  background-color: #f2b162;
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
  font-weight: 400;
  font-size: 13px;
  line-height: 1.4;
  text-decoration-line: underline;
  color: #0083e2;
  width: fit-content;
}
.join {
  display: flex;
  justify-content: space-evenly;
  margin: 48px 20px 60px;
}
.campus-2022 {
  .cover {
    position: relative;
    display: flex;
    justify-content: center;
    .cover-join {
      position: absolute;
      bottom: 40px;
    }
  }
  .intro {
    padding: 100px 0 250px;
    background: #036fb7;
    .h1 {
      color: #fff;
    }
    .h2 {
      color: #fff;
      margin-bottom: 24px;
    }
    .feature-grid {
      display: grid;
      margin: 64px auto;
      grid-template-columns: repeat(4, 260px);
      gap: 32px;
      .card {
        width: 100%;
        border: none;
        box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
        border-radius: 16px;
        .card-body {
          padding: 50px 32px 11px;
        }
        .card-cover {
          display: flex;
          width: 84px;
          margin: 0 auto 16px;
          padding: 14px;
          background: #cdeafe;
          border-radius: 6px;
        }
        .card-title {
          font-style: normal;
          font-weight: 700;
          font-size: 24px;
          text-align: center;
          color: #023d64;
        }
        .card-divider {
          width: 39px;
          margin: 8px auto;
          border-bottom: 3px solid #036fb7;
          border-radius: 100px;
        }
      }
    }
    .intro-text {
      font-style: normal;
      font-weight: 500;
      font-size: 28px;
      line-height: 1.4;
      text-align: center;
      letter-spacing: 4px;
      color: #ffffff;
      .em {
        color: #fff000;
      }
    }
  }
  .benefit {
    position: relative;
    top: -150px;
    display: flex;
    justify-content: center;
    .card {
      width: 800px;
      padding: 48px 140px;
      background: #ffffff;
      box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
      border-radius: 16px;
      border: none;
      background-image: url('~images/2022_campus_ambassador/2022-campus-benefit-card-bg-1.png'),
        url('~images/2022_campus_ambassador/2022-campus-benefit-card-bg-2.png');
      background-repeat: no-repeat;
      background-position: left top, right 15px bottom 15px;
      .card-title {
        position: relative;
        display: flex;
        justify-content: center;
        margin-bottom: 35px;
        &-text {
          position: relative;
          z-index: 3;
          font-style: normal;
          font-weight: 700;
          font-size: 56px;
          line-height: 1.4;
          color: #036fb7;
          transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
        }
        &-underline {
          z-index: 1;
          position: absolute;
          width: 194px;
          height: 16px;
          background: #b4e4ff;
          transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
          bottom: 4px;
        }
      }
      .card-sub {
        font-style: normal;
        font-weight: 400;
        font-size: 24px;
        line-height: 35px;
        text-align: center;
        color: #393939;
        margin-bottom: 52px;
      }
      .card-row-title {
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 1.4;
        color: $color--primary;
        margin-left: 36px;
        margin-bottom: 8px;
      }
      .card-row-text {
        margin-left: 36px;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 1.7;
        letter-spacing: 0.1em;
        color: #393939;
      }
    }
    .card-row-gap {
      margin: 56px 0;
    }
  }
  .info {
    padding: 64px 0;
    background-color: #fbe38e;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 1950px;
    background-image: url('~images/2022_campus_ambassador/2022-campus-mission-bg-1.png'),
      url('~images/2022_campus_ambassador/2022-campus-mission-bg-2.png');
    background-repeat: no-repeat;
    background-size: 450px, 235px;
    background-position: left top, right 30px bottom 30px;
    .card {
      width: 800px;
      background: #ffffff;
      box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
      border-radius: 16px;
      border: 2px solid #353535;
      &.card-1 {
        background-image: url('~images/2022_campus_ambassador/2022-campus-mission-card-bg-1.png'),
          url('~images/2022_campus_ambassador/2022-campus-mission-card-bg-2.png');
        background-repeat: no-repeat;
        background-position: right top, left 15px bottom 15px;
        padding: 48px 100px;
      }
      &.card-2 {
        margin: 36px 0;
        padding: 48px 80px;
      }
      .card-title {
        position: relative;
        display: flex;
        justify-content: center;
        margin-bottom: 35px;
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
          height: 16px;
          background: #ffc535;
          transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
          bottom: 4px;
        }
      }
      .card-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 20px;
        &-item {
          display: flex;
          flex-direction: column;
          align-items: center;
          &-text {
            font-style: normal;
            font-weight: 500;
            font-size: 20px;
            line-height: 1.4;
            text-align: center;
            color: #353535;
          }
        }
      }
      .card-sub {
        font-style: normal;
        font-weight: 400;
        font-size: 24px;
        line-height: 35px;
        text-align: center;
        color: #393939;
        margin-bottom: 52px;
      }
      .card-condition {
        display: flex;
        align-items: center;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 1.4;
        color: #353535;
        margin-bottom: 8px;
        &::before {
          content: '';
          display: inline-block;
          margin-right: 10px;
          background-image: url('~images/2022_campus_ambassador/2022-campus-check-square-orange.png');
          height: 24px;
          width: 24px;
        }
        &.required::before {
          content: '';
          background-image: url('~images/2022_campus_ambassador/2022-campus-check-square-blue.png');
        }
        &-title {
          margin: 24px 0 18px;
          font-style: normal;
          font-weight: 700;
          font-size: 20px;
          line-height: 1.4;
          color: #f4b362;
        }
      }
      .time {
        display: flex;
        background-color: #fefbf7;
        flex-direction: column;
        padding: 15px 50px;
        .time-title {
          font-style: normal;
          font-weight: 500;
          font-size: 20px;
          line-height: 1.4;
          text-align: center;
          color: #000000;
          margin-bottom: 15px;
        }
        .time-row {
          display: grid;
          grid-template-columns: 2fr 5fr;
          border-radius: 16px;
          font-style: normal;
          font-weight: 400;
          font-size: 20px;
          line-height: 1.4;
          color: #000000;
          padding: 10px 0 10px 43px;
          &.gray {
            background: #efefef;
          }
          &.blue {
            background: #cdeafe;
          }
        }
        .time-hint {
          font-style: normal;
          font-weight: 400;
          font-size: 14px;
          line-height: 20px;
          color: #000000;
        }
      }
    }
  }
  .history {
    padding-top: 240px;
    background: #e6f5ff;
    .history-title {
      position: relative;
      display: flex;
      justify-content: center;
      margin-bottom: 100px;
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
        width: 224px;
        height: 16px;
        background: #b4e4ff;
        transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
        bottom: 4px;
      }
    }
    .swiper {
      margin-top: 15px;
    }
    .slide-content {
      display: grid;
      gap: 110px;
      grid-template-columns: 1fr 1fr;
      width: fit-content;
      margin: 0 auto;
      padding-bottom: 40px;
    }
    .card {
      padding: 28px;
      background: #ffffff;
      border: 2px solid #afafaf;
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      border-radius: 40px;
      width: 470px;
      align-self: center;
      .card-title {
        font-style: normal;
        font-weight: 500;
        font-size: 26px;
        line-height: 1.4;
        color: #036fb7;
        margin-bottom: 14px;
      }
      .card-text {
        font-style: normal;
        font-weight: 400;
        font-size: 24px;
        line-height: 1.4;
        color: #000000;
      }
    }
  }
  .modal {
    .h1 {
      color: $color--primary;
      font-size: 36px;
    }
    .h2 {
      color: $color--primary;
      font-size: 20px;
    }
    .modal-body {
      padding: 0;
    }
    .modal-text {
      margin: 24px 0;
      font-style: normal;
      font-weight: 500;
      font-size: 16px;
      line-height: 1.4;
      text-align: center;
      color: #000000;
    }
    .join {
      margin: 20px 40px 20px;
    }
    .modal-hint {
      background-color: #e6f5ff;
      padding: 30px 60px;
      font-style: normal;
      font-weight: 300;
      font-size: 12px;
      line-height: 1.4;
      color: #000000;
    }
  }
  .rating-table {
    .rating-row {
      display: grid;
      padding: 8px 35px;
      max-width: 600px;
      margin: 0 auto;
      grid-template-columns: 1fr 3fr 1fr;
      justify-content: center;
      align-items: center;
      gap: 15px;
      border-radius: 16px;
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 1.4;
      color: #000000;
      &:nth-child(odd) {
        background-color: #efefef;
      }
      &:first-child {
        background-color: #71bcf9;
      }
    }
    .hint {
      max-width: 600px;
      margin: 8px auto;
      font-style: normal;
      font-weight: 400;
      font-size: 12px;
      line-height: 1.4;
      text-align: left;
      padding: 0 30px;
      color: #353535;
    }
  }
}
@media screen and (max-width: 768px) {
  .h1 {
    font-size: 32px;
  }
  .h2 {
    font-size: 28px;
  }
  .block-content {
    margin: 0 15px;
    width: auto;
  }
  .btn-campus-primary {
    padding: 6px 24px;
    font-size: 20px;
    line-height: 29px;
    border-radius: 10px;
  }
  .btn-campus-secondary {
    padding: 6px 24px;
    font-size: 20px;
    line-height: 29px;
    border-radius: 10px;
  }
  .btn-link {
    font-size: 13px;
  }
  .join {
    margin: 20px 0;
  }
  .campus-2022 {
    .cover .cover-join {
      bottom: 60px;
    }
    .intro {
      padding: 60px 0 200px;
      .h1 {
        color: #fff;
      }
      .h2 {
        color: #fff;
        margin-bottom: 24px;
      }
      .feature-grid {
        display: grid;
        margin: 45px auto;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        .card {
          .card-body {
            padding: 25px 20px 11px;
          }
          .card-cover {
            width: 42px;
            margin: 0 auto 7px;
            padding: 6px;
          }
          .card-title {
            font-size: 14px;
          }
          .card-divider {
            width: 22px;
            margin: 4px auto;
            border-bottom: 1.75px solid #036fb7;
          }
        }
      }
      .intro-text {
        font-size: 16px;
        line-height: 1.4;
      }
    }
    .benefit {
      position: relative;
      top: -150px;
      display: flex;
      justify-content: center;
      .card {
        width: 100%;
        margin: 0 20px;
        max-width: 500px;
        padding: 32px 25px;
        background-image: url('~images/2022_campus_ambassador/2022-campus-benefit-card-bg-1.png'),
          url('~images/2022_campus_ambassador/2022-campus-benefit-card-bg-2.png');
        background-repeat: no-repeat;
        background-position: left top, right 15px bottom 15px;
        background-size: 46px, 46px;
        .card-title {
          margin-bottom: 26px;
          &-text {
            font-size: 32px;
          }
          &-underline {
            z-index: 1;
            width: 131px;
            height: 12px;
          }
        }
        .card-sub {
          font-weight: 400;
          font-size: 16px;
          line-height: 1.4;
          margin-bottom: 22px;
        }
        .card-row-title {
          margin-left: 0;
          font-size: 20px;
          text-align: center;
          margin-bottom: 8px;
        }
        .card-row-text {
          margin-left: 0;
          font-size: 14px;
          line-height: 1.7;
          letter-spacing: 0.1em;
          color: #393939;
        }
      }
      .card-row-gap {
        margin: 24px 0;
      }
    }
    .info {
      padding: 32px 15px;
      height: 2000px;
      background-image: none;
      .card {
        width: 100%;
        max-width: 500px;
        margin: 0 25px;
        &.card-1 {
          background-image: none;
          padding: 34px 0;
        }
        &.card-2 {
          margin: 20px 0;
          padding: 34px 0;
        }
        .card-title {
          margin-bottom: 22px;
          &-text {
            font-size: 32px;
          }
          &-underline {
            width: 130px;
            height: 12px;
          }
        }
        .card-grid {
          grid-template-columns: 1fr 1fr;
          grid-template-rows: 1fr 1fr 1fr;
          gap: 24px;
          &-item-text {
            font-size: 15px;
          }
        }
        .card-sub {
          font-size: 16px;
          line-height: 1.4;
          text-align: center;
          color: #393939;
          margin-bottom: 42px;
        }
        .card-condition {
          font-size: 14px;
          margin-bottom: 8px;
          &::before {
            content: '';
            margin-right: 6px;
            background-image: url('~images/2022_campus_ambassador/2022-campus-check-square-orange.png');
            background-repeat: no-repeat;
            background-size: 14px;
            height: 14px;
            width: 16px;
          }
          &.required::before {
            content: '';
            background-image: url('~images/2022_campus_ambassador/2022-campus-check-square-blue.png');
            background-size: 14px;
          }
          &-title {
            margin: 32px 0 24px;
            text-align: center;
          }
        }
        .time {
          padding: 15px;
          .time-row {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr auto;
            border-radius: 16px;
            padding: 12px;
            &.gray {
              background: #efefef;
            }
            &.blue {
              background: #cdeafe;
            }
          }
          .time-row-date {
            font-size: 14px;
          }
          .time-hint {
            font-style: normal;
            font-weight: 400;
            font-size: 12px;
            line-height: 1.4;
            color: #000000;
          }
        }
      }
    }
    .history {
      // padding-top: 60px;
      .history-title {
        position: relative;
        display: flex;
        justify-content: center;
        margin-bottom: 50px;
        &-text {
          position: relative;
          font-size: 32px;
        }
        &-underline {
          width: 192px;
          height: 12px;
        }
      }
      .swiper {
        margin-top: 15px;
      }
      .slide-content {
        &-2 {
          grid-template-columns: 7fr 5fr;
        }
        display: grid;
        gap: 15px;
        grid-template-columns: 5fr 7fr;
        width: fit-content;
        margin: 0 auto;
        padding-bottom: 40px;
      }
      .history-img {
        transform: scale(1.25);
      }
      .card {
        background: #ffffff;
        width: auto;
        align-self: center;
        padding: 18px;

        .card-body {
          padding: 8px;
        }
        .card-title {
          font-size: 14px;
          margin-bottom: 8px;
        }
        .card-text {
          font-size: 12px;
        }
      }
    }
    .modal {
      .h1 {
        color: $color--primary;
        font-size: 28px;
      }
      .h2 {
        color: $color--primary;
        font-size: 16px;
      }
      .modal-body {
        padding: 0;
      }
      .modal-text {
        margin: 24px;
        font-size: 14px;
      }
      .join {
        margin: 20px 15px 20px;
      }
      .modal-hint {
        padding: 15px;
        font-size: 12px;
      }
    }
    .rating-table {
      .rating-row {
        display: grid;
        padding: 4px 10px;
        grid-template-columns: 7fr 15fr auto;
        gap: 15px;
        font-size: 14px;
      }
      .hint {
        padding: 0 20px;
      }
    }
  }
}
</style>
