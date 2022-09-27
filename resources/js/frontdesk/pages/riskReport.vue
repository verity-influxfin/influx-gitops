<template>
  <main class="risk-report">
    <section class="banner"></section>
    <template v-if="isLogin">
      <section class="title">
        <div class="block-content">
          <div class="title-group">
            <div class="title-m">{{ renderDate.m }}</div>
            <div class="title-2">
              {{ renderDate.mtext + ' ' + renderDate.y }}
            </div>
            <div class="title-1">月 借貸媒合風險報告書</div>
          </div>
          <div class="hint">以下資料統計至{{ renderDate.ctext }}</div>
        </div>
      </section>
      <section class="anniversary">
        <h1 class="h1">累計至本月之平均年化報酬率</h1>
        <div class="block-content">
          <div class="value-group">
            <span class="value-1">{{ yearRateOfReturnRender.num }}</span>
            <span class="value-2" v-if="yearRateOfReturnRender.fractional">
              .{{ yearRateOfReturnRender.fractional }}％
            </span>
            <span class="value-2">％</span>
          </div>
        </div>
      </section>
      <section class="apply-cases">
        <div class="block-content">
          <div class="d-sm-block d-flex justify-content-center align-baseline">
            <h1 class="h1">本月申貸案件數</h1>
            <div class="apply-case-value">
              <span>{{ format(reportData.this_month_apply.all) }}</span>
              <span class="h1">件</span>
            </div>
          </div>
          <div>
            <h1 class="h1 apply-case-info">
              <div>
                <div>學生貸申貸案件</div>
                <div>{{ format(reportData.this_month_apply.student) }}件</div>
              </div>
              <div>+</div>
              <div>
                <div>上班族申貸案件數</div>
                <div>{{ format(reportData.this_month_apply.work) }}件</div>
              </div>
            </h1>
            <div class="apply-case-bar-group" v-if="monthApplyPercent">
              <div
                class="apply-case-bar-left"
                :style="{
                  width: 'calc(' + monthApplyPercent.student + '% + 100px)',
                }"
              >
                <span class="apply-case-bar-text">
                  {{ format(monthApplyPercent.student) }}%
                </span>
              </div>
              <div
                class="apply-case-bar-right"
                :style="{ width: monthApplyPercent.work + '%' }"
              >
                <span class="apply-case-bar-text">
                  {{ format(monthApplyPercent.work) }}%
                </span>
              </div>
            </div>
            <div class="apply-case-bar-group" v-else>
              <div class="apply-case-bar-left col-6"></div>
              <div class="apply-case-bar-right col-6"></div>
            </div>
          </div>
        </div>
      </section>
      <section class="success-cases">
        <div class="block-content">
          <div class="success-case-item">
            <h1 class="h1">累計媒合成功件數</h1>
            <div class="success-case-value">
              <span class="em">{{
                format(reportData.total_apply.success)
              }}</span>
              <span class="h1">件</span>
            </div>
          </div>
          <div class="success-case-item">
            <h1 class="h1">累積金額</h1>
            <div class="success-case-value">
              {{ format(reportData.total_apply.amount) }}
            </div>
            <div class="h1">元</div>
          </div>
          <div class="success-case-item">
            <h1 class="h1">累積筆數</h1>
            <div class="success-case-value">
              {{ format(reportData.total_apply.count) }}
            </div>
            <div class="h1">筆</div>
          </div>
        </div>
      </section>
      <section class="index-cases">
        <div class="block-content">
          <h1 class="h1">
            累計效益與指標：平均每筆投資金額
            <span class="em">
              {{ format(reportData.total_apply.avg_invest) }} </span
            >元
          </h1>
          <h2 class="h2">
            學生貸媒合成功均額:
            <span class="em">
              {{ format(reportData.total_apply.avg_invest_student) }} </span
            >元/件均 上班族貸媒合成功均額:
            <span class="em">
              {{ format(reportData.total_apply.avg_invest_work) }}
            </span>
            元/件均
          </h2>
          <h3 class="h3">
            <div>
              逾期概況：已累計回收逾期金額
              <span class="em">
                {{ format(reportData.total_apply.delay_return_amount) }}
              </span>
              元
            </div>
            <div class="sm">
              <div>
                當月逾期人數:
                {{ format(reportData.this_month_apply.delay_users_count) }} 人
              </div>
              <div>
                當月逾期筆數:
                {{ format(reportData.this_month_apply.delay_loans_count) }} 筆
              </div>
            </div>
          </h3>
        </div>
      </section>
      <section class="repay">
        <h1 class="h1">
          準時還款率 <span class="sm">(1~9級依會員信評等級)</span>
        </h1>
        <div class="block-content">
          <div class="risk-rank" :style="{ '--bg1': 'url(' + rankBg[0] + ')' }">
            <img
              src="@/asset/images/risk/risk-rank-1.png"
              class="img-fluid risk-rank-img"
            />
            <div class="risk-rank-text">
              {{ format(reportData.on_time.level1) }}%
            </div>
          </div>
          <div class="risk-rank" :style="{ '--bg1': 'url(' + rankBg[1] + ')' }">
            <img
              src="@/asset/images/risk/risk-rank-2.png"
              class="img-fluid risk-rank-img"
            />
            <div class="risk-rank-text">
              {{ format(reportData.on_time.level4) }}%
            </div>
          </div>
          <div class="risk-rank" :style="{ '--bg1': 'url(' + rankBg[2] + ')' }">
            <img
              src="@/asset/images/risk/risk-rank-3.png"
              class="img-fluid risk-rank-img"
            />
            <div class="risk-rank-text">
              {{ format(reportData.on_time.level7) }}%
            </div>
          </div>
        </div>
      </section>
      <section class="analysis">
        <h1 class="h1">重要指標分析</h1>
        <div class="block-content">
          <div class="block-text">
            本月媒合金額，較去年同月<span class="em">
              ↑增長 {{ formatPercent(reportData.growth.amount) }}%
            </span>
          </div>
          <div class="block-text">
            本月學生貸申請數，較去年同月<span class="em">
              ↑增長 {{ formatPercent(reportData.growth.student) }}%
            </span>
          </div>
          <div class="block-text">
            本月上班族貸申請數，較去年同月
            <span class="em">
              ↑增長 {{ formatPercent(reportData.growth.work) }}%
            </span>
          </div>
          <div class="hint">＊與去年同月份比較</div>
        </div>
      </section>
      <section class="functions">
        <div class="block-content">
          <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
              <!-- Slides -->
              <div
                class="swiper-slide"
                v-for="(item, index) in renderList"
                :key="index"
              >
                <a
                  href="javascript:;"
                  v-for="x in item"
                  :key="x.month"
                  @click="getRisk(x.year, x.month)"
                >
                  <alesis-button class="month-btn">
                    {{ x.year }} {{ x.month }}月
                  </alesis-button>
                </a>
              </div>
            </div>
            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
          <div class="mt-5 d-flex justify-content-center">
            <a href="/investLink" target="_blank">
              <button class="btn btn-invest">立即投資</button>
            </a>
          </div>
        </div>
      </section>
    </template>
  </main>
</template>

<script>
import AlesisButton from '@/component/alesis/AlesisButton';
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';
import Axios from 'axios';
export default {
  components: {
    AlesisButton,
  },
  mounted() {
    SwiperCore.use([Navigation]);
  },
  data() {
    return {
      reportData: {
        yearly_rate_of_return: 0,
        this_month_apply: {
          all: 0,
          student: 0,
          work: 0,
          delay_loans_count: 0,
          delay_users_count: 0,
        },
        total_apply: {
          success: 0,
          amount: 0,
          count: 0,
          avg_invest: 0,
          avg_invest_student: 0,
          avg_invest_work: 0,
          delay_return_amount: 0,
        },
        on_time: {
          level1: 0,
          level4: 0,
          level7: 0,
        },
        growth: {
          amount: 0,
          student: 0,
          work: 0,
        },
      },
      reportList: [],
      isLogin: false,
      currentDate: {}
    }
  },
  created() {
    $("title").text(`風險報告書 - inFlux普匯金融科技`);
    if (sessionStorage.length === 0 || sessionStorage.flag === 'logout') {
      this.$store.commit('mutationLogin')
      this.isLogin = false
    } else {
      const cert = this.checkCert()
      if (cert) {
        this.getList().then(({ year, month }) => {
          this.getRisk(year, month)
        })
      }
    }
  },
  methods: {
    getRisk(year = 2022, month = 9) {
      Axios.get(`/api/v1/risk_report/${year}/${month}`).then(({ data }) => {
        if (data.success) {
          this.reportData = data.data
          this.currentDate = new Date(year, month, 0)
          if (document.querySelector('.risk-report')) {
            document.querySelector('.risk-report').scrollIntoView({ behavior: 'smooth' })
          }
        }
      })
    },
    getList() {
      return Axios.get(`/api/v1/risk_report_list`).then(({ data }) => {
        if (data.success) {
          this.reportList = data.data
          // return first element
          return data.data[0]
        }
      })
    },
    format(n) {
      if (!Boolean(n)) {
        return '__'
      }
      return n.toLocaleString()
    },
    formatPercent(n, f = 1) {
      if (!Boolean(n)) {
        return '__'
      }
      return n.toFixed(f)
    },
    checkCert() {
      return Axios.get('/chk/cert/identity').then(({ data }) => {
        if (data.error && data.error == 2002) {
          alert('需通過實名認證才可查看本頁面')
          this.$router.back()
          return false
        } else {
          this.isLogin = true
          return true
        }
      })
    }
  },
  computed: {
    yearRateOfReturnRender() {
      const [num, fractional] = this.reportData.yearly_rate_of_return.toString(10).split('.')
      return { num, fractional }
    },
    monthApplyPercent() {
      const student = (this.reportData.this_month_apply.student / this.reportData.this_month_apply.all).toFixed(2) * 100
      if (isNaN(student)) {
        return false
      }
      return {
        student,
        work: 100 - student
      }
    },
    rankBg() {
      return [
        {
          k: this.reportData.on_time.level1,
          v: require('@/asset/images/risk/risk-rank-p1.svg')
        }, {
          k: this.reportData.on_time.level4,
          v: require('@/asset/images/risk/risk-rank-p2.svg')
        }, {
          k: this.reportData.on_time.level7,
          v: require('@/asset/images/risk/risk-rank-p3.svg')
        }
      ].sort((a, b) => { b.k - a.k }).map(x => x.v)
    },
    renderList() {
      const { reportList } = this
      // reportList.reverse()
      const ans = []
      for (let index = 0; index < reportList.length; index += 3) {
        ans.push(reportList.slice(index, index + 3))
      }
      return ans
    },
    renderDate() {
      const { currentDate } = this
      if (currentDate instanceof Date) {
        return {
          y: currentDate.getFullYear(),
          m: currentDate.getMonth() + 1,
          mtext: currentDate.toLocaleString('en-US', { month: 'long' }),
          ctext: `${currentDate.getFullYear()}年${currentDate.getMonth() + 1}月${currentDate.getDate()}日`
        }
      }
      return {
        y: '',
        m: '',
        mtext: '',
        ctext: ``
      }
    }
  },
  watch: {
    renderList() {
      this.$nextTick().then(() => {
        new Swiper('.swiper', {
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        })
      })
    }
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #f2b162;
$color__text--primary: #000000;
$color__text--secondary: #6b6b6b70;
$color__background--gradient: linear-gradient(180deg, #ffffff 0%, #f3f9fc 100%);
.align-baseline {
  align-items: baseline;
}
.block-content {
  width: 100%;
  max-width: 1400px;
  padding: 0 15px;
  margin: 0 auto;
}
.banner {
  background-image: url('~images/risk/risk-banner.png');
  background-position: top left;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
  height: 800px;
}
.title {
  background-image: url('~images/risk/risk-wave.svg');
  background-repeat: no-repeat;
  background-position: bottom center;
  background-size: cover;
  height: 280px;
  .block-content {
    bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .title-group {
    position: relative;
    display: grid;
    grid-template-columns: auto 1fr;
    grid-template-rows: 1fr 1fr;
    .title-m {
      font-style: normal;
      font-weight: 900;
      font-size: 128px;
      line-height: 1.4;
      margin-right: 5px;
      color: $color__text--primary;
      grid-area: 1 / 1 / 3 / 2;
      align-self: center;
    }
    .title-1 {
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 70px;
      color: #083a6e;
    }
    .title-2 {
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      color: #393939;
      padding-left: 12px;
      align-self: end;
    }
  }
  .hint {
    font-style: normal;
    font-weight: 400;
    font-size: 24px;
    line-height: 70px;
    color: $color__text--secondary;
  }
}
.anniversary {
  padding: 5px;
  min-height: 300px;
  background: url('~images/risk/risk-anniversary-bg1.png'),
    url('~images/risk/risk-anniversary-bg2.png');
  background-position: center center;
  background-repeat: no-repeat;
  .block-content {
    padding: 35px 15px 0;
  }
  .h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 32px;
    line-height: 51px;
    text-align: center;
    color: #393939;
  }
  .value-group {
    font-style: normal;
    font-weight: 700;
    text-align: center;
    color: #0f3560;
    width: 273px;
    height: 287px;
    padding-left: 29px;
    background-image: url('~images/risk/risk-anniversary-value-bg.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    margin: 0 auto;
    .value-1 {
      font-size: 100px;
      line-height: 273px;
      margin: 0;
    }
    .value-2 {
      margin: 0;
      font-size: 40px;
      line-height: 273px;
    }
  }
}
.apply-cases {
  background-image: url('~images/risk/risk-wave-up.svg');
  background-position: top center;
  background-repeat: no-repeat;
  background-size: cover;
  padding: 162px 0 105px;
  .block-content {
    display: grid;
    grid-template-columns: 4fr 7fr;
    .h1 {
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 51px;
      text-align: center;
      color: #393939;
    }
    .apply-case-info {
      display: flex;
      gap: 25px;
      justify-content: center;
    }
    .apply-case-value {
      text-align: center;
      font-style: normal;
      font-weight: 700;
      font-size: 96px;
      line-height: 110px;
      letter-spacing: -0.2px;
      color: $color--primary;
      .h1 {
        line-height: 110px;
      }
    }
    .apply-case-bar-group {
      display: flex;
      min-width: 720px;
      margin-top: 20px;
      .apply-case-bar-left,
      .apply-case-bar-right {
        border-radius: 35px;
        height: 50px;
        position: relative;
        display: flex;
        justify-content: center;
      }
      .apply-case-bar-text {
        color: $color__text--secondary;
        font-size: 40px;
        line-height: 50px;
        text-align: center;
        position: relative;
        top: 50px;
      }
      .apply-case-bar-left {
        background-color: $color--primary;
        z-index: 3;
        right: -40px;
      }
      .apply-case-bar-right {
        position: relative;
        background-color: #083a6e;
        z-index: 2;
      }
    }
  }
}
.success-cases {
  background: $color__background--gradient;
  padding: 100px 0 85px;
  .block-content {
    display: grid;
    grid-template-columns: 4fr 3fr 3fr;
    gap: 30px;
    .h1 {
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 1.4;
      text-align: center;
      color: #393939;
    }
    .success-case-value {
      text-align: center;
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      letter-spacing: -0.2px;
      color: $color__text--secondary;
      .em {
        color: $color--primary;
        font-size: 96px;
        line-height: 110px;
      }
      .h1 {
        line-height: 110px;
      }
    }
  }
}
.index-cases {
  padding: 43px 0;
  .block-content {
    .h1 {
      font-style: normal;
      font-weight: 700;
      font-size: 40px;
      line-height: 64px;
      color: $color__text--primary;
      text-align: center;
      .em {
        color: $color--primary;
        font-size: 64px;
        line-height: 1;
      }
    }
    .h2 {
      margin-top: 10px;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 50px;
      color: $color__text--secondary;
      text-align: center;
      .em {
        color: $color--primary;
        font-size: 40px;
        line-height: 50px;
      }
    }
    .h3 {
      display: flex;
      margin-top: 57px;
      gap: 57px;
      align-items: center;
      justify-content: center;
      font-style: normal;
      font-weight: 700;
      font-size: 40px;
      line-height: 50px;
      color: #083a6e;
      text-align: center;
      .em {
        color: $color--primary;
        font-size: 40px;
        line-height: 50px;
      }
      .sm {
        color: $color--primary;
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 27px;
        color: #393939;
      }
    }
  }
}
.repay {
  padding: 43px 0 60px;
  background-color: #f3f9fc;
  .h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 40px;
    line-height: 48px;
    color: #393939;
    text-align: center;
    .sm {
      color: $color__text--secondary;
      font-size: 32px;
      line-height: 48px;
    }
  }
  .block-content {
    display: flex;
    justify-content: space-evenly;
    .risk-rank {
      &::before {
        content: '';
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-image: var(--bg1);
        background-size: cover;
        background-position: center;
        z-index: 1;
      }
      position: relative;
      padding: 60px;
      z-index: 2;
      &-img {
        position: relative;
        right: 10px;
      }
      &-text {
        position: absolute;
        right: -120px;
        bottom: 0;
        font-weight: 700;
        text-align: left;
        font-size: 32px;
        line-height: 115%;
        color: $color__text--primary;
        width: 115px;
        &::before {
          border-top: 2px solid $color__text--primary;
          right: 120px;
          top: 0px;
          display: block;
          position: absolute;
          transform: rotate(35deg);
          content: '';
          width: 64px;
          height: 2px;
          z-index: 2;
        }
      }
    }
  }
}
.analysis {
  padding: 43px 0;
  .h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 48px;
    line-height: 1.4;
    text-align: center;
    color: $color__text--primary;
  }
  .block-content {
    margin-top: 50px;
  }
  .block-text {
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    line-height: 1.8;
    text-align: center;
    color: $color__text--primary;
    .em {
      @extend .block-text;
      color: $color--primary;
    }
  }
  .hint {
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 1.4;
    text-align: center;
    color: $color__text--secondary;
  }
}
.functions {
  padding: 100px 0;
  .swiper {
    max-width: 800px;
  }
  .swiper-slide {
    display: flex;
    gap: 30px;
    justify-content: center;
  }
  .month-btn {
    padding: 12px 42px;
    font-size: 28px;
  }
  .btn-invest {
    border-radius: 12px;
    padding: 10px;
    width: 250px;
    background-color: #083a6e;
    box-shadow: 4px 4px 2px rgb(0 0 0 / 10%);
    font-size: 28px;
    color: #fff;
  }
}
@media screen and (max-width: 767px) {
  .banner {
    background-image: url('~images/risk/risk-banner-phone.png');
    background-position: bottom center;
    height: 135vw;
  }
  .title {
    background-image: url('~images/risk/risk-wave-phone.svg');
    height: 240px;
    .block-content {
      flex-direction: column;
      align-items: center;
    }
    .title-group {
      position: relative;
      .title-m {
        font-size: 96px;
      }
      .title-1 {
        font-size: 24px;
        line-height: 38px;
      }
      .title-2 {
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 1.4;
        color: #393939;
        padding-left: 5px;
      }
    }
    .hint {
      margin-top: 5px;
      font-size: 16px;
      line-height: 25px;
    }
  }
  .anniversary {
    padding: 5px;
    background: url('~images/risk/risk-anniversary-bg-phone.png');
    background-position: center;
    background-repeat: no-repeat;
    background-size: 100%;
    .block-content {
      padding: 15px 15px 0;
    }
    .h1 {
      font-size: 20px;
      line-height: 32px;
    }
    .value-group {
      color: #0f3560;
      width: 172px;
      height: 182px;
      padding-left: 29px;
      background-image: url('~images/risk/risk-anniversary-value-bg-phone.png');
      margin: 0 auto;
      .value-1 {
        font-size: 64px;
        line-height: 182px;
        margin: 0;
      }
      .value-2 {
        margin: 0;
        font-size: 20px;
        line-height: 182px;
      }
    }
  }
  .apply-cases {
    background-image: url('~images/risk/risk-wave-up-phone.svg');
    background-position: top center;
    background-repeat: no-repeat;
    background-size: cover;
    padding: 80px 0 20px;
    .block-content {
      display: flex;
      flex-direction: column;
      padding: 40px 0;
      .h1 {
        font-size: 20px;
        line-height: 36px;
      }
      .apply-case-info {
        gap: 10px;
      }
      .apply-case-value {
        font-size: 36px;
        line-height: 36px;
        .h1 {
          line-height: 36px;
        }
      }
      .apply-case-bar-group {
        display: flex;
        min-width: initial;
        width: 100%;
        margin-top: 20px;
        .apply-case-bar-left,
        .apply-case-bar-right {
          border-radius: 35px;
          height: 30px;
        }
        .apply-case-bar-text {
          font-size: 24px;
          line-height: 40px;
          top: 30px;
        }
        .apply-case-bar-left {
          right: -20px;
        }
      }
    }
  }
  .success-cases {
    background: $color__background--gradient;
    padding: 30px 0;
    .block-content {
      display: flex;
      flex-direction: column;
      gap: 10px;
      .h1 {
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 1.4;
        text-align: center;
        color: #393939;
      }
      .success-case-item {
        display: flex;
        gap: 8px;
        align-items: baseline;
        justify-content: center;
      }
      .success-case-value {
        font-size: 20px;
        line-height: 1.4;
        .em {
          color: $color--primary;
          font-size: 36px;
          line-height: 50px;
        }
        .h1 {
          line-height: 50px;
        }
      }
    }
  }
  .index-cases {
    padding: 43px 0;
    .block-content {
      .h1 {
        font-size: 20px;
        line-height: 32px;
        .em {
          font-size: 32px;
        }
      }
      .h2 {
        font-size: 16px;
        line-height: 25px;
        .em {
          font-size: 16px;
          line-height: 25px;
        }
      }
      .h3 {
        display: block;
        margin-top: 20px;
        gap: 12px;
        font-size: 20px;
        line-height: 32px;
        .em {
          font-size: 32px;
          line-height: 1;
        }
        .sm {
          font-size: 15px;
          line-height: 1.4;
        }
      }
    }
  }
  .repay {
    padding: 43px 0;
    .h1 {
      font-size: 24px;
      line-height: 1.2;
      margin-bottom: 25px;
      .sm {
        display: block;
        color: $color__text--secondary;
        font-size: 20px;
        line-height: 1.2;
      }
    }
    .block-content {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-direction: column;
      justify-content: center;
      .risk-rank {
        width: fit-content;
        padding: 55px;
        position: relative;
        transform: scale(0.8);
        &-img {
          position: relative;
          right: 10px;
        }
        &-text {
          position: absolute;
          right: -55px;
          bottom: 10px;
          text-align: left;
          font-size: 20px;
          width: 75px;
          &::before {
            right: 80px;
            top: 0px;
            transform: rotate(35deg);
            content: '';
            width: 40px;
          }
        }
      }
    }
  }
  .analysis {
    padding: 43px 0;
    .h1 {
      font-size: 20px;
      line-height: 1.2;
    }
    .block-content {
      margin-top: 30px;
    }
    .block-text {
      font-size: 16px;
      line-height: 1.5;
      letter-spacing: -1px;
      .em {
        @extend .block-text;
        color: $color--primary;
      }
    }
  }
  .functions {
    padding: 60px 0;
    .swiper {
      max-width: 800px;
    }
    .swiper-slide {
      gap: 30px;
      align-items: center;
      flex-direction: column;
      justify-content: center;
    }
    .month-btn {
      padding: 10px;
      font-size: 20px;
      width: 250px;
    }
    .btn-invest {
      font-size: 20px;
    }
  }
}
</style>
