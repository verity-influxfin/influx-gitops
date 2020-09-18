<template>
  <div class="blog-wrapper" id="blog-wrapper">
    <div class="header">
      <h2>金融小學堂</h2>
      <div class="hr"></div>
      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
    </div>
    <template v-if="filterKnowledge.length === 0">
      <div class="empty">
        <div class="empty-img">
          <img src="../asset/images/empty.svg" class="img-fluid" />
        </div>
        <h3>沒有結果</h3>
        <p>根據您的搜索，我們似乎找不到結果</p>
      </div>
    </template>
    <template v-else>
      <ul class="blog-content" ref="content"></ul>
      <div class="pagination" ref="pagination"></div>
    </template>
  </div>
</template>

<script>
let postRow = Vue.extend({
  props: ["item"],
  template: `
    <li class="article">
        <div class="img"><img class="img-fluid" :src="item.media_link ? item.media_link : '/images/default-image.png'"></div>
        <p>{{item.post_modified.substr(0,10)}}</p>
        <a class="link" :href="item.link">{{item.post_title}}</a>
    </li>
  `,
});

export default {
  data: () => ({
    filter: "",
    pageHtml: "",
    filterKnowledge: [],
  }),
  computed: {
    knowledge() {
      let $this = this;
      $.each($this.$store.getters.KnowledgeData, (index, row) => {
        $this.$store.getters.KnowledgeData[
          index
        ].post_content = `${row.post_content
          .replace(/(<([^>]+)>)/gi, "")
          .substr(0, 80)}...`;
      });
      return $this.$store.getters.KnowledgeData;
    },
  },
  created() {
    $("title").text(`influx 小學堂 - inFlux普匯金融科技`);
    this.$store.dispatch("getKnowledgeData");
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
      particlesJS.load("blog-wrapper", "data/mobile.json");
    });
  },
  watch: {
    knowledge(newVal) {
      this.filterKnowledge = newVal;
      this.pagination();
    },
    filter(newVal) {
      this.filterKnowledge = [];
      this.knowledge.forEach((row, index) => {
        if (row.post_title.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          this.filterKnowledge.push(row);
        }
      });
    },
    filterKnowledge() {
      window.dispatchEvent(new Event("resize"));
      this.pagination();
    },
  },
  methods: {
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filterKnowledge,
          pageSize: 9,
          callback(data, pagination) {
            $($this.$refs.content).html("");
            data.forEach((item, index) => {
              let component = new postRow({
                propsData: {
                  item,
                },
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          },
        });

        window.dispatchEvent(new Event("resize"));
      });
    },
  },
};
</script>

<style lang="scss">
.blog-wrapper {
  padding: 30px;
  overflow: hidden;
  position: relative;

  .particles-js-canvas-el {
    position: absolute;
    top: 0;
    z-index: -1;
  }

  .header {
    width: 80%;
    margin: 20px auto;
    position: relative;

    h2 {
      font-weight: bolder;
      text-align: center;
      color: #061164;
    }

    .hr {
      width: 130px;
      height: 2px;
      background-image: linear-gradient(to right, #71008b, #ffffff);
      margin: 0px auto;
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
        color: #083a6e;
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

  .blog-content {
    width: 80%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .article {
      margin: 10px;
      float: left;
      width: calc(100% / 3 - 20px);
      list-style: none;
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
      background: #ffffff;

      p {
        text-align: right;
        padding: 0px 10px;
        margin-bottom: 5px;
      }

      .img {
        width: 100%;
        height: 300px;
        text-align: center;
        padding-bottom: 10px;

        &:hover {
          img {
            filter: brightness(0.5);
            transition-duration: 0.5s;
          }
        }
      }

      h6 {
        padding: 0px 10px;
        font-size: 15px;
        height: 35px;
      }

      .link {
        display: block;
        font-weight: bolder;
        transition-duration: 0.5s;
        text-align: justify;
        margin: 10px;
        height: 48px;

        &:hover {
          color: #9c9c9c;
          text-decoration: none;
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

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  @media (max-width: 767px) {
    padding: 10px;

    .header {
      width: 100%;

      .input-custom {
        position: relative;
        width: initial;
        margin: 10px auto;
      }
    }

    .blog-content {
      width: 100%;

      .article {
        width: calc(100% - 2px);
        margin: 10px 1px;

        .img {
          height: auto;
        }
      }
    }
  }
}
</style>