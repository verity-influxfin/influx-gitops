<template>
  <div class="news-wrapper">
    <div class="projector">
      <div class="row" ref="projector">
        <div class="slide-item" v-for="(item,index) in fixedTopData" :key="index">
          <a :href="item.url.indexOf('influxfin') !== -1 ? '#'+item.link : item.url" class="img">
            <img :src="item.image_url" />
          </a>
        </div>
      </div>
    </div>

    <div class="header">
      <h1>最新消息</h1>

      <div class="progress">
        <div
          class="progress-bar"
          role="progressbar"
          style="width: 75%"
          aria-valuenow="75"
          aria-valuemin="0"
          aria-valuemax="100"
        ></div>
      </div>

      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
    </div>

    <ul class="news-content" ref="content"></ul>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
let newsRow = Vue.extend({
  props: ["item"],
  template: `
      <li class="news-card hvr-rotate">
        <a :href="item.url.indexOf('influxfin') !== -1 ? '#'+item.link : item.url">
          <div class="img"><img :src="item.image_url" class="img-custom" /></div>
          <div class="cnt">
            <span class="date">{{item.updated_at}}</span><br>
            <span class="title">{{item.title}}</span>
          </div>
        </a>
      </li>
  `
});

export default {
  data: () => ({
    filter: "",
    filterNews: [],
    fixedTopData: []
  }),
  computed: {
    news() {
      let $this = this;
      $.each($this.$store.getters.NewsData, (index, row) => {
        $this.$store.getters.NewsData[index].content = `${row.content
          .replace(/<[^>]*>/g, "")
          .substr(0, 20)}...`;
      });
      return $this.$store.getters.NewsData;
    }
  },
  created() {
    this.$store.dispatch("getNewsData");
    $("title").text(`最新消息 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      this.pagination();
      AOS.init();
    });
  },
  watch: {
    news(newValue) {
      this.filterNews = newValue;
      this.fixedTopData = [];
      newValue.forEach((item, index) => {
        if (item.rank == 8 || item.rank == 45) {
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
    }
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
                  item
                }
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          }
        });
      });
    },
    createTopSlider() {
      $(this.$refs.projector).slick({
        infinite: true,
        centerMode: true,
        autoplay: true,
        centerPadding: "50px",
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<img src="./Images/icon_pre.svg" class="pre">',
        nextArrow: '<img src="./Images/icon_next.svg" class="next">',
        responsive: [
          {
            breakpoint: 768,
            settings: {
              centerPadding: "0px"
            }
          }
        ]
      });
    }
  }
};
</script>

<style lang="scss">
.news-wrapper {
  width: 100%;
  padding: 30px;
  overflow: auto;

  .projector {
    width: 80%;
    margin: 10px auto;
    padding: 10px;

    .row {
      width: 60%;
      margin: 10px auto;
      padding: 10px;
      position: relative;

      .slick-arrow {
        position: absolute;
        z-index: 1;
        width: 50px;
        top: 50%;
        transform: translate(0px, -50%);
        cursor: pointer;

        &.pre {
          left: 4%;
        }

        &.next {
          right: 4%;
        }
      }

      .slick-track {
        padding: 10px;
      }

      .slide-item {
        margin: 10px 25px 10px 0px;
        padding: 10px;
        filter: contrast(0.5);
        transition-duration: 0.5s;

        &.slick-current {
          filter: initial;
          transform: scale(1.1);
        }

        .img {
          height: 300px;
          overflow: hidden;
          display: block;
          border: 5px solid #163a74;
          border-radius: 20px;

          img {
            width: 100%;
            border-radius: 20px;
          }
        }
      }
    }
  }

  .progress {
    height: 4px;
  }

  .header {
    position: relative;
    overflow: hidden;
    width: 80%;
    margin: 0px auto;

    h1 {
      font-weight: bolder;
      margin-bottom: 10px;
    }
    .input-custom {
      width: 300px;
      position: absolute;
      top: 0;
      right: 0;

      .form-control {
        padding: 5px 35px;
      }

      %iStyle {
        position: absolute;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 20px;
        color: #002bff;
        text-shadow: 0 0 4px #002bff;
      }

      .fa-search {
        @extend %iStyle;
        left: 10px;
      }

      .fa-times {
        @extend %iStyle;
        right: 10px;
        cursor: pointer;
      }
    }
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
      width: 31%;
      min-height: 350px;
      padding: 20px;
      float: left;
      list-style: none;
      box-shadow: 0 0 5px black;

      a {
        display: block;
        color: #113673;
        text-decoration: underline;

        &:hover {
          text-decoration: none;
        }

        .img {
          max-height: 260px;
          overflow: hidden;
        }
      }
    }
  }

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  @media (max-width: 767px) {
    padding: 10px;

    .projector {
      width: 100%;
      margin: 0px auto;

      .row {
        width: initial;
        margin: 0px auto;

        .slick-arrow {
          &.pre {
            left: -2%;
          }

          &.next {
            right: -2%;
          }
        }

        .slide-item {
          .img {
            height: 150px;
          }
        }
      }
    }

    .header {
      width: 100%;

      .input-custom {
        margin: 10px 0px;
        position: relative;
        width: 100%;
      }
    }

    .news-content {
      width: 100%;

      .news-card {
        width: -webkit-fill-available;
        min-height: fit-content;

        a {
          display: flex;

          .img {
            width: 100px;
            max-height: 100px;
          }

          .cnt {
            margin-left: 10px;
          }
        }
      }
    }
  }
}
</style>

