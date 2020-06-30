<template>
  <div class="blog-wrapper">
    <div class="header">
      <h2>金融小學堂</h2>
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
    <ul class="blog-content" ref="content"></ul>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
let postRow = Vue.extend({
  props: ["item"],
  template: `
    <li class="article">
        <div class="img"><img :src="item.media_link ? item.media_link : './Images/default-image.png'"></div>
        <p>{{item.post_modified.substr(0,10)}}</p>
        <h6>{{item.post_title}}</h6>
        <a class="btn link" :href="'#'+item.link">閱讀內容</a>
    </li>
  `
});

export default {
  data: () => ({
    filter: "",
    pageHtml: "",
    filterKnowledge: []
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
    }
  },
  created() {
    $("title").text(`influx 小學堂 - inFlux普匯金融科技`);
    this.$store.dispatch("getKnowledgeData");
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      AOS.init();
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
      this.pagination();
    }
  },
  methods: {
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filterKnowledge,
          pageSize: 8,
          callback(data, pagination) {
            $($this.$refs.content).html("");
            data.forEach((item, index) => {
              let component = new postRow({
                propsData: {
                  item
                }
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          }
        });
      });
    }
  }
};
</script>

<style lang="scss">
.blog-wrapper {
  width: 100%;
  padding: 30px;
  overflow: auto;

  .progress {
    height: 4px;
  }

  .header {
    width: 80%;
    margin: 20px auto;
    position: relative;

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

  .blog-content {
    width: 75%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .article {
      border: 1px solid;
      margin: 10px;
      float: left;
      width: 48%;
      list-style: none;

      p {
        text-align: right;
        padding: 0px 10px;
        margin-bottom: 5px;
      }

      .img {
        width: 100%;
        height: 300px;
        text-align: center;
        padding: 10px;

        img {
          width: 100%;
          height: 100%;
        }
      }

      h6 {
        padding: 0px 10px;
        font-size: 18px;
      }

      .link {
        margin-bottom: 10px;
        width: 100%;
        background: #0072ff;
        border-radius: 0px;
        color: #ffffff;
        font-weight: bolder;
        transition-duration: 0.5s;

        &:hover {
          background: #ff7818;
          text-decoration: none;
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
        width: initial;
      }
    }
  }
}
</style>