<template>
  <div class="campuspartner-wrapper">
    <div class="header-card">
      <h1>2021</h1>
      <h2>普匯校園大使計畫</h2>
      <h4>CAMPUS AMBASSADOR PROGRAM</h4>
    </div>
    <div class="pic-card">
      <div class="bg-img"><img class="img-fluid" :src="'/images/people.jpg'" /></div>
      <p>成為校園代言人，寫下你的熱血青春新頁</p>
      <a href="/campusJoin" target="_blank">我要報名</a>
    </div>
    <div class="desc-card">
      <div class="bg-img"><img class="img-fluid" :src="'/images/bg.jpg'" /></div>
      <div class="content">
        <h3>普匯 X Fintech</h3>
        <p>
          這絕對不是在打雜<br />
          我們會給你足夠的空間與資源<br />
          讓你盡情發揮實力<br />
          完成屬於自己的校園專案<br /><br />
          如果你對FinTech有興趣<br />
          如果你苦無展大身手的舞台<br />
          如果你想擁有更有意義的大學生活<br />
          Join Us！
        </p>
      </div>
    </div>
    <div class="job-card">
      <h3>大使任務</h3>
      <p class="sub">成為普匯校園大使，你可以...</p>
      <Splide class="box" :options="boxOptions">
        <SplideSlide class="item">
          <div class="img"><img class="img-fluid" :src="'/images/group.jpg'" /></div>
          <h5>校園情報</h5>
          <p>校園關係蒐集<br />情報調查與分析</p>
        </SplideSlide>
        <SplideSlide class="item">
          <div class="img"><img class="img-fluid" :src="'/images/project.jpg'" /></div>
          <h5>專案規劃</h5>
          <p>校園專案規劃與執行<br />擴大普匯APP使用人數</p>
        </SplideSlide>
        <SplideSlide class="item">
          <div class="img"><img class="img-fluid" :src="'/images/promote.jpg'" /></div>
          <h5>品牌推廣</h5>
          <p>推廣普匯<br />提升品牌黏著度</p>
        </SplideSlide>
      </Splide>
    </div>
    <div class="method-card">
      <h3>計畫辦法</h3>
      <div class="row">
        <div class="title">大使任期　　❱</div>
        <div class="content">2021 / 2 / 25 - 2021 / 6 / 30</div>
      </div>
      <div class="row">
        <div class="title">大使資格　　❱</div>
        <div class="content">
          <ul>
            <li>全國大專院校在學學生(大學部與碩士班，不含在職專班)</li>
            <li>不限學校、科系</li>
            <li>大使計劃期間須持續具備在學學生身分</li>
            <li>採個人或團隊報名(限同校，一組至多3人)</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="bonus-card">
      <div class="bg-img"><img class="img-fluid" :src="'/images/bg.jpg'" /></div>
      <div class="title">加分條件</div>
      <div class="content">
        <div class="text t-l">活潑外向，喜歡接觸人群</div>
        <div class="text t-r">具社團領導能力，喜愛挑戰</div>
        <div class="text t-l">善於團隊合作及溝通表達</div>
        <div class="text t-r">對金融科技有興趣並持續關注</div>
      </div>
    </div>
    <div class="conditions-card">
      <h3>大使福利</h3>
      <p>成為普匯校園大使，你的好康有...</p>
      <div class="box">
        <div class="item">
          <div class="img">
            <img class="img-fluid" :src="'/images/certificate.jpg'" />
          </div>
          <span>校園大使證書<br /><br /></span>
        </div>
        <div class="item">
          <div class="img"><img class="img-fluid" :src="'/images/ceo.jpg'" /></div>
          <span>CEO面對面提案<br /><br /></span>
        </div>
        <div class="item">
          <div class="img"><img class="img-fluid" :src="'/images/job.jpg'" /></div>
          <span>表現優異者<br />可獲得實習機會</span>
        </div>
        <div class="item">
          <div class="img"><img class="img-fluid" :src="'/images/money.jpg'" /></div>
          <span>專案報酬<br /><br /></span>
        </div>
        <div class="item">
          <div class="img"><img class="img-fluid" :src="'/images/member.jpg'" /></div>
          <span>AI金融科技協會<br />會員優惠待遇</span>
        </div>
        <div class="item">
          <div class="img"><img class="img-fluid" :src="'/images/recommend.jpg'" /></div>
          <span>表現優異者<br />可獲得推薦函</span>
        </div>
      </div>
    </div>
    <div class="event-card">
      <div class="bg-img"><img class="img-fluid" :src="'/images/bg.jpg'" /></div>
      <h3>活動時程</h3>
      <Splide class="box" :options="eventOptions">
        <SplideSlide class="item"
          ><div class="text">
            <div>2020/1/31(日)<br />報名截止</div>
          </div></SplideSlide
        >
        <SplideSlide class="item"
          ><div class="text">
            <div>2020/2/2(三)<br />寄送面試通知</div>
          </div></SplideSlide
        >
        <SplideSlide class="item"
          ><div class="text">
            <div>
              2020/2/4(四)~<br />
              2020/2/10(三)<br />面試
            </div>
          </div>
        </SplideSlide>
        <SplideSlide class="item"
          ><div class="text">
            <div>2020/2/15(一)<br />公布錄取名單</div>
          </div></SplideSlide
        >
      </Splide>
    </div>
  </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";

export default {
  components: {
    Splide,
    SplideSlide,
  },
  data: () => ({
    boxOptions: {
      type: "loop",
      autoplay: window.innerWidth > 767 ? false : true,
      perPage: 3,
      arrows: window.innerWidth > 767 ? false : true,
      pagination: false,
      gap: "17rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    eventOptions: {
      type: "loop",
      autoplay: window.innerWidth > 767 ? false : true,
      perPage: 4,
      arrows: window.innerWidth > 767 ? false : true,
      pagination: false,
      gap: "1rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
  }),
};
</script>

<style lang="scss">
.campuspartner-wrapper {
  %center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  %h3 {
    font-size: 80px;
    font-weight: 500;
    line-height: 1.49;
    text-align: center;
    color: #153a71;
  }

  %p {
    font-size: 36px;
    line-height: 1.5;
    text-align: center;
    color: #153a71;
  }

  .header-card {
    color: #153a71;

    h1 {
      font-size: 224px;
      line-height: 1.2;
      text-align: center;
    }

    h2 {
      font-size: 130px;
      font-weight: 600;
      line-height: 1.48;
      text-align: center;
    }

    h4 {
      font-size: 55px;
      line-height: 1.48;
      text-align: center;
    }
  }

  .pic-card {
    position: relative;

    p {
      position: absolute;
      top: 0px;
      left: 50%;
      transform: translate(-50%, 0px);
      font-size: 40px;
      font-weight: bold;
      text-align: center;
      width: fit-content;
      color: #000000;
    }

    a {
      @extend %center;
      border: solid 4px #184c91;
      padding: 5px 2rem;
      font-size: 36px;
      font-weight: bolder;
      color: #184c91;
      border-radius: 70px;

      &:hover {
        text-decoration: none;
        background: #184c91;
        color: #ffffff;
      }
    }
  }

  .desc-card {
    position: relative;

    .content {
      @extend %center;
      width: fit-content;

      h3 {
        @extend %h3;
        margin-bottom: 3rem;
      }

      p {
        @extend %p;
      }
    }
  }

  .job-card {
    padding: 2rem 7rem;
    overflow: hidden;

    h3 {
      @extend %h3;
    }

    .sub {
      @extend %p;
    }

    .box {
      padding: 4rem 0px;

      .item {
        .img {
          text-align: center;
          width: fit-content;
          margin: 0px auto;
          border: 5px solid #153a71;
          border-radius: 50%;
          overflow: hidden;
          padding: 2rem;
        }

        h5 {
          font-size: 50px;
          font-weight: bold;
          line-height: 1.48;
          text-align: center;
          color: #000000;
        }

        p {
          font-size: 23px;
          line-height: 1.5;
          text-align: center;
          color: #000000;
        }
      }
    }
  }

  .method-card {
    background: #153a71;
    padding: 4rem 0px;

    h3 {
      @extend %h3;
    }

    .row {
      width: 1300px;
      margin: 1rem auto;

      .title {
        font-size: 38px;
        font-weight: 500;
        line-height: 1.48;
        text-align: left;
        color: #ffffff;
      }

      .content {
        font-size: 36px;
        line-height: 1.5;
        text-align: left;
        color: #ffffff;
        margin-left: 2rem;
      }
    }
  }

  .bonus-card {
    position: relative;

    .title {
      @extend %center;
      font-size: 40px;
      border: solid 4px #153a71;
      padding: 20px;
      border-radius: 50%;
      width: 160px;
      height: 160px;
      text-align: center;
      font-weight: bold;
      color: #153a71;
    }

    .content {
      @extend %center;
      overflow: hidden;
      position: absolute;
      width: 80%;

      .text {
        margin: 4rem 0px;
        font-size: 40px;
        font-weight: bold;
        line-height: 1.48;
        color: #000000;
        float: left;
        width: calc(50% - 5rem);
      }

      .t-l {
        text-align: end;
        margin-right: 5rem;
      }

      .t-r {
        margin-left: 5rem;
      }
    }
  }

  .conditions-card {
    padding: 2rem;

    h3 {
      @extend %h3;
    }

    p {
      @extend %p;
    }

    .box {
      overflow: hidden;
      width: 1300px;
      margin: 0px auto;

      .item {
        margin: 1rem;
        width: calc(100% / 3 - 2rem);
        float: left;
        border-radius: 28px;
        border: solid 4px #707070;
        overflow: hidden;
        padding: 1rem;
        text-align: center;

        span {
          font-size: 36px;
          font-weight: 700;
          line-height: 1.48;
          color: #000000;
        }
      }
    }
  }

  .event-card {
    position: relative;

    h3 {
      @extend %h3;
      position: absolute;
      top: 20px;
      left: 50%;
      transform: translate(-50%, 0px);
    }

    .box {
      @extend %center;
      width: 100%;
      .item {
        .text {
          position: relative;
          background-color: #153a71;
          width: 300px;
          height: 300px;
          border-radius: 50%;
          margin: 0px auto;

          div {
            @extend %center;
            font-size: 34px;
            width: fit-content;
            text-align: center;
            color: #ffffff;
          }
        }
      }
    }
  }

  @media screen and (max-width: 767px) {
    .header-card {
      h1 {
        font-size: 110px;
      }

      h2 {
        font-size: 41px;
      }

      h4 {
        font-size: 19px;
      }
    }

    .pic-card {
      padding: 1rem;

      .bg-img {
        display: none;
      }

      %initial {
        position: initial;
        transform: initial;
      }

      p {
        @extend %initial;
        font-size: 18px;
        text-align: center;
        margin: 10px auto;
      }

      a {
        @extend %initial;
        margin: 0px auto;
        font-size: 20px;
        display: block;
        width: fit-content;
      }
    }

    .desc-card {
      padding: 1rem;
      background: #c9c9c9;

      .bg-img {
        display: none;
      }

      .content {
        position: initial;
        transform: initial;
        width: 100%;

        h3 {
          font-size: 45px;
          margin-bottom: 1rem;
        }

        p {
          font-size: 18px;
        }
      }
    }

    .job-card {
      padding: 1rem;

      h3 {
        font-size: 45px;
      }

      .sub {
        font-size: 18px;
      }

      .box {
        padding: 1rem 4rem;

        .item {
          h5 {
            font-size: 23px;
            margin: 10px 0px;
          }

          p {
            font-size: 20px;
          }
        }
      }
    }

    .method-card {
      padding: 1rem;

      h3 {
        font-size: 45px;
      }

      .row {
        width: 100%;

        .title,
        .content {
          font-size: 20px;
        }
      }
    }

    .bonus-card {
      padding: 1rem;
      background: #c9c9c9;

      .bg-img {
        display: none;
      }

      %initial {
        position: initial;
        transform: initial;
      }

      .title {
        @extend %initial;
        width: fit-content;
        height: auto;
        border-radius: 20px;
        padding: 0px 10px;
        margin: 0px auto;
        background: #ffffff;
      }

      .content {
        @extend %initial;
        width: 100%;

        .text {
          text-align: center;
          width: 100%;
          margin: 1rem auto;
          font-size: 25px;
        }
      }
    }

    .conditions-card {
      padding: 1rem;

      h3 {
        font-size: 45px;
      }

      p {
        font-size: 18px;
      }

      .box {
        width: 100%;

        .item {
          margin: 10px;
          padding: 5px;
          width: calc(50% - 20px);

          span {
            font-size: 18px;
          }
        }
      }
    }

    .event-card {
      padding: 1rem;
      background: #c9c9c9;

      .bg-img {
        display: none;
      }

      %initial {
        position: sticky;
        transform: initial;
      }

      h3 {
        @extend %initial;
        font-size: 45px;
      }

      .box {
        @extend %initial;

        .item {
          .text {
            width: 200px;
            height: 200px;

            div {
              font-size: 24px;
            }
          }
        }
      }
    }
  }
}
</style>
