<template>
  <div class="news-wrapper">
    <div class="news-bg">
      <div class="projector">
        <div class="row" ref="projector">
          <div
            class="slide-item"
            v-for="(item, index) in fixedTopData"
            :key="index"
          >
            <a :href="item.link" class="img">
              <img :src="item.image_url" class="img-fluid" />
            </a>
          </div>
        </div>
      </div>
      <div class="header">
        <h1 class="float-left">最新消息</h1>
        <form class="input-custom float-right" @submit.prevent>
          <i class="fas fa-search"></i>
          <input
            type="text"
            class="form-control"
            placeholder="請輸入關鍵字"
            autocomplete="off"
            v-model="filter"
          />
          <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
        </form>
      </div>
    </div>
    <div class="hr"></div>
    <div class="news-cnt" id="news-cnt">
      <template v-if="filterNews.length === 0">
        <div class="empty">
          <div class="empty-img">
            <img src="../asset/images/empty.svg" class="img-fluid" />
          </div>
          <h3>沒有結果</h3>
          <p>根據您的搜索，我們似乎找不到結果</p>
        </div>
      </template>
      <template v-else>
        <ul class="news-content" ref="content"></ul>
        <div class="pagination" ref="pagination"></div>
      </template>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'

let newsRow = Vue.extend({
  props: ["item", "index"],
  template: `
      <li class="news-card" data-aos="zoom-in" :data-aos-delay="100 * index">
        <a :href="item.link">
          <div class="img"><img :src="getImageUrl(item.image_url)" class="img-custom" /></div>
          <div class="cnt">
            <span class="date" v-if="item.post_date">{{ formatDate(item.post_date) }}</span>
            <p class="title">{{ removeHtmlTags(item.post_title) }}</p>
          </div>
          <div class="read">Read more+</div>
        </a>
      </li>
  `,
  methods: {
    getImageUrl(imageUrl) {
      const baseURL = 'https://influx-website.s3.ap-northeast-1.amazonaws.com/';
      const fullURL = new URL(imageUrl, baseURL).toString();
      return fullURL;
    },
    formatDate(date) {
      return date ? date.substr(0, 10) : '';
    },
    removeHtmlTags(text) {
      return text ? text.replace(/<br\s*[\/]?>/gi, '') : '';
    }
  },
  data() {
    return {
      S3_BUCKET_URL: process.env.S3_BUCKET_URL
    };
  }
});


export default {
  data: () => ({
    filter: "",
    filterNews: [],
    fixedTopData: [],
  }),
  computed: {
    news() {
      let { $store } = this;
      $.each($store.getters.NewsData, (index, row) => {
        $store.getters.NewsData[
          index
        ].post_content = `${row.post_content
          .replace(/<[^>]*>/g, "")
          .substr(0, 20)}...`;
      });
      return $store.getters.NewsData;
    },
  },
  created() {
    this.$store.dispatch("getNewsData");
    $("title").text(`最新消息 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      this.pagination();
      particlesJS.load("news-cnt", "data/news.json");
      AOS.init();
    });
  },
  watch: {
    news(newValue) {
      this.filterNews = newValue;
      this.fixedTopData = [];
      newValue.forEach((item, index) => {
        if (item.order == 1) {
          this.fixedTopData.push(item);
        }
      });
      this.pagination();
    },
    fixedTopData() {
      this.$nextTick(() => {
        this.createTopSlider();
      });
    },
    filter(newVal) {
      this.filterNews = [];
      this.news.forEach((row, index) => {
        if (row.post_title.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          this.filterNews.push(row);
        }
      });
      this.pagination();
    },
  },
  methods: {
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          pageSize: 6,
          dataSource: $this.filterNews,
          callback(data, pagination) {
            $($this.$refs.content).html("");
            data.forEach((item, index) => {
              let component = new newsRow({
                propsData: {
                  item,
                  index,
                },
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          },
        });

        window.dispatchEvent(new Event("resize"));
      });
    },
    createTopSlider() {
      $(this.$refs.projector).slick({
        infinite: true,
        centerMode: true,
        autoplay: false,
        dots: true,
        speed: 1000,
        dotsClass: "custom-dots",
        customPaging(slider, i) {
          return '<div class="dots"></div>';
        },
        centerPadding: "5rem",
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<img src="/images/n_a_pre.svg" class="pre">',
        nextArrow: '<img src="/images/n_a_next.svg" class="next">',
        responsive: [
          {
            breakpoint: 768,
            settings: {
              centerPadding: "0px",
            },
          },
        ],
      });
    },
  },
};
</script>

<style lang="scss">
.news-wrapper {
  width: 100%;
  overflow: auto;

  h2 {
    font-weight: bolder;
    text-align: center;
    color: #083a6e;
  }

  .hr {
    border-top: 1px solid #eaeaea;
    margin: 0px auto;
    width: 100%;
  }

  .news-bg {
    background-image: url("../asset/images/news_banner.svg");
    background-position: 0 0;
    background-repeat: no-repeat;
    background-size: 100%;

    .projector {
      margin: 0px auto;
      padding: 10px;

      .row {
        width: 60%;
        margin: 10px auto;
        padding: 20px;
        position: relative;

        .slick-arrow {
          position: absolute;
          z-index: 1;
          width: 36px;
          top: 50%;
          transform: translate(0px, -50%);
          cursor: pointer;
          border-radius: 50%;
          background: #ffffff;
          padding: 7px;
          box-shadow: 0 0 15px 0 0 0 15px 0 rgb(152 152 152 / 50%);

          &.pre {
            left: 6%;
          }

          &.next {
            right: 6%;
          }
        }

        .slick-track {
          padding: 10px;
        }

        .slide-item {
          padding: 10px;
          filter: opacity(0.5);
          transition-duration: 0.5s;
          transform: translate(-10px, 0px) scale(0.9);
          position: relative;

          .img {
            height: 300px;
            width: 100%;
            overflow: hidden;
            display: block;
            margin: 0px auto;
            border: solid 5px #ffffff;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);

            img {
              width: 100%;
            }
          }

          &.slick-current {
            z-index: 1;
            transform: translate(-10px, 0px) scale(1.1);
            filter: initial;
          }
        }

        .custom-dots {
          display: none;
          width: fit-content;
          margin: 0px auto;
          padding: 0px;

          li {
            list-style: none;
            margin: 5px;

            .dots {
              width: 10px;
              height: 10px;
              border-radius: 50%;
              border: 3px solid #ffffff;
            }
          }

          .slick-active {
            .dots {
              background: #ffffff !important;
            }
          }
        }
      }
    }

    .header {
      overflow: hidden;
      width: 90%;
      margin: 20px auto;

      h1 {
        font-weight: bolder;
        margin: 0px;
        line-height: initial;
      }

      .input-custom {
        position: relative;

        .form-control {
          width: 380px;
          padding: 5px 45px;
          height: 53px;
          border-radius: 40px;
        }

        %iStyle {
          position: absolute;
          top: 50%;
          transform: translate(0, -50%);
          font-size: 20px;
          color: #083a6e;
        }

        .fa-search {
          @extend %iStyle;
          left: 20px;
        }

        .fa-times {
          @extend %iStyle;
          right: 20px;
          cursor: pointer;
        }
      }
    }
  }

  .empty {
    text-align: center;
    margin: 30px auto;

    .empty-img {
      width: 200px;
      margin: 20px auto;
    }

    h3 {
      font-weight: bold;
    }
  }

  .news-cnt {
    position: relative;
    overflow: auto;

    .particles-js-canvas-el {
      position: absolute;
      top: 0;
      z-index: -1;
    }

    .news-content {
      width: 80%;
      overflow: hidden;
      margin: 15px auto;
      padding: 0px;

      .img-custom {
        max-width: 100%;
      }

      .news-card {
        margin: 10px;
        width: calc(100% / 3 - 20px);
        min-height: 350px;
        padding: 20px;
        float: left;
        list-style: none;
        box-shadow: 0 0 2px 2px #b4b4b4;
        background: #ffffff;

        a {
          &:hover {
            text-decoration: none;
            color: #000000;

            .read {
              text-decoration: none;
            }
          }
        }

        .read {
          display: block;
          color: #8629a5;
          text-decoration: underline;
          float: right;
        }

        .img {
          height: 260px;
          overflow: hidden;
        }

        .cnt {
          margin: 10px 0px;

          .title {
            color: #8629a5;
            font-weight: bold;
            height: 48px;
          }
        }
      }
    }

    .pagination {
      margin: 20px auto;
      width: fit-content;
    }
  }
  @media screen and (max-width: 767px) {
    .news-bg {
      background-size: cover;

      .projector {
        .row {
          width: 100%;
          padding: 0px 5px;
          margin: 10px auto;

          .slick-arrow {
            &.pre,
            &.next {
              display: none !important;
            }
          }

          .slide-item {
            padding: 0px;

            .img {
              height: 150px;
            }

            &.slick-current {
              transform: translate(-10px, 0px) scale(1);
            }
          }

          .custom-dots {
            display: flex;
          }
        }
      }

      .header {
        margin: 0px auto;

        h1 {
          color: #ffffff;
          text-align: center;
          float: initial !important;
          margin: 10px auto;
          font-size: 2rem;
        }

        .input-custom {
          width: 100%;
          .form-control {
            width: 100%;
          }
        }
      }
    }
    .news-cnt {
      .news-content {
        width: 100%;

        .news-card {
          width: calc(100% - 10px);
          margin: 5px;
          min-height: initial;
          padding: 10px;
          transition-delay: 0s !important;

          .img {
            display: none;
          }

          .cnt {
            .title {
              height: initial;
            }
          }
        }
      }
    }
  }
}
</style>

