<template>
  <div class="blog-wrapper" id="blog-wrapper">
    <div class="header">
      <h2>AI金融科技新知</h2>
      <div class="input-custom">
        <form autocomplete="off" onsubmit="return false">
            <i class="fas fa-search"></i>
            <input
              type="text"
              class="form-control"
              placeholder="Search"
              autocomplete="new-password"
              autofill="off"
              name="blog_articlesearch"
              v-model="filter"
            />
            <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
        </form>
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
        <a :href="item.link">
          <div class="top" v-if="item.order === 1"><img src="/images/tag.svg" class="img-fluid"></div>
          <div class="img"><img :src="item.media_link ? item.media_link : '/images/default-image.png'"></div>
          <div class="chunk">
            <p class="title">{{item.post_title}}</p>
            <p class="date">{{item.post_date.substr(0,10)}}</p>
            <p class="cnt">{{item.post_content}}</p>
            <div class="link">閱讀更多<img src="/images/a_arrow.png"></a>
          </div>
        </a>
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
          .substr(0, 50)}...`;
      });
      return $this.$store.getters.KnowledgeData;
    },
  },
  created() {
    $("title").text(`AI金融科技新知 - inFlux普匯金融科技`);
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
          pageNumber: $cookies.get("page") ? $cookies.get("page") : 1,
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
          afterPageOnClick() {
            $cookies.set("page", $(".paginationjs-page.active").attr("data-num"));
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
    padding: 10px 20px;
    border-radius: 40px;
    box-shadow: 0 2px 5px 0 #6ab0f2;
    background-color: #ffffff;

    h2 {
      font-weight: bolder;
      color: #061164;
      margin: 0px;
    }

    .input-custom {
      width: 300px;
      position: absolute;
      top: 50%;
      right: 25px;
      transform: translate(0px, -50%);

      .form-control {
        padding: 5px 35px;
        border: 0px;
        border-bottom: 1px solid #061164;
        border-radius: 0px;
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
      background: #ffffff;
      box-shadow: 0 2px 5px 0 #6ab0f2;
      position: relative;

      &:hover {
        .img {
          img {
            filter: brightness(0.5);
            transition-duration: 0.5s;
          }
        }
      }

      .top {
        width: 15px;
        position: absolute;
        top: -10px;
        left: 10px;
        z-index: 2;
        filter: drop-shadow(2px 2px 1px black);
      }

      .img {
        width: 100%;
        height: 250px;
        overflow: hidden;
        text-align: center;
        padding-bottom: 10px;

        img {
          height: 250px;
          position: relative;
        }
      }

      .chunk {
        padding: 10px;

        .title {
          font-size: 17px;
          color: #061164;
          font-weight: 900;
          height: 60px;
        }

        .date {
          font-size: 14px;
          font-weight: initial;
          color: #9b9b9b;
        }

        .cnt {
          font-size: 15px;
          font-weight: 500;
          line-height: 1.8;
          color: #797979;
          height: 80px;
        }

        .link {
          display: block;
          font-weight: bolder;
          text-align: center;
          color: #8629a5;
        }
      }

      h6 {
        padding: 0px 10px;
        font-size: 15px;
        height: 35px;
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
    margin: 2rem auto 0px auto;
    width: fit-content;
  }

  @media (max-width: 767px) {
    padding: 10px;

    .header {
      width: 100%;
      box-shadow: 0 0 black;

      h2 {
        text-align: center;
      }

      .input-custom {
        position: relative;
        width: initial;
        margin: 10px auto;
        top: 0;
        right: 0px;
        transform: initial;
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
