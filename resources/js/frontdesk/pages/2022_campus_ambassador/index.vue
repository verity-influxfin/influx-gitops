<template>
  <div class="campus-2022">
    <div class="cover">
      <img
        src="@/asset/images/2022_campus_ambassador/2022-campus-cover.png"
        alt="2022普匯校園大使"
      />
      <div class="cover-join">
        <button class="btn btn-campus-primary" @click="scrollToJoin">
          我要報名
        </button>
      </div>
    </div>
    <div class="intro">
      <div class="block-content">
        <h1 class="h1">還不認識普匯金融科技嗎？</h1>
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
        <div class="intro-text">
          <div>喜歡交朋友、熱愛分享、喜歡活動、有高度執行力</div>
          <div>對經營社群有興趣、有業務洽談或推廣能力的你</div>
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
            <div class="col-auto">
              <img
                src="@/asset/images/2022_campus_ambassador/2022-campus-benefit-1.png"
              />
            </div>
            <div class="col">
              <div class="card-row-title">職涯大加分</div>
              <div class="card-row-text">
                <div>•普匯校園大使結業證書</div>
                <div>•履歷健檢了解優劣勢</div>
                <div>•績優組別可獲執行長推薦涵</div>
              </div>
            </div>
          </div>
          <div class="row no-gutters card-row-gap">
            <div class="col-auto">
              <img
                src="@/asset/images/2022_campus_ambassador/2022-campus-benefit-2.png"
              />
            </div>
            <div class="col">
              <div class="card-row-title">大使培訓計畫</div>
              <div class="card-row-text">
                <div>•普匯校園大使結業證書</div>
                <div>•履歷健檢了解優劣勢</div>
                <div>•績優組別可獲執行長推薦函</div>
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-auto">
              <img
                src="@/asset/images/2022_campus_ambassador/2022-campus-benefit-3.png"
              />
            </div>
            <div class="col">
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
            團體報名：自行組隊，3人一組 (不可跨校)
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
            <button class="btn btn-campus-primary">團體報名</button>
            <button class="btn btn-campus-secondary">個人報名</button>
          </div>
          <div class="time">
            <div class="time-title">活動時程</div>
            <div class="time-row gray">
              <div>報名期間</div>
              <div>2022/07/12 (二) - 2022/07/25 (一)</div>
            </div>
            <div class="time-row">
              <div>徵選期間</div>
              <div>2022/07/26 (二) - 2022/07/29 (五)</div>
            </div>
            <div class="time-row gray">
              <div>名單公布</div>
              <div>2022/08/03 (五)</div>
            </div>
            <div class="time-row">
              <div>大使任期</div>
              <div>2022/08/09 (二) - 2023/01/28 (五)</div>
            </div>
            <div class="time-row blue">
              <div>教育訓練</div>
              <div>2022/08/09 (二)、2022/08/11(四)</div>
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
                  />
                </div>
              </div>
            </div>
            <div class="swiper-slide">
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
                  />
                </div>
              </div>
            </div>
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
            <div class="modal-text">
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
            <h2 class="h2">招募期間</h2>
            <div class="modal-text">
              <div>7月：任期8月-1 月</div>
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
              <button class="btn btn-campus-primary">團體報名</button>
              <button class="btn btn-campus-secondary">個人報名</button>
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
        position: absolute;
        top: 880px;
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
  #detail-modal {
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
}
</style>
