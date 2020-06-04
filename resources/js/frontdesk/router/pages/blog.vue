<template>
  <div class="blog-wrapper">
    <ul class="blog-content" ref="content"></ul>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
let postRow = Vue.extend({
  props: ["item"],
  template: `
    <li class="card">
        <img :src="item.media_link ? item.media_link : './Image/default-image.png'" class="img-custom">
        <h5>{{item.post_title}}</h5>
        <span>{{item.post_modified}}</span>
        <p class="gray" v-html="item.post_content"></p>
        <a :href="'#'+item.link">閱讀更多》</a>
    </li>
  `
});

export default {
  data: () => ({
    pageHtml: ""
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
  watch: {
    knowledge() {
      this.pagination();
    }
  },
  methods: {
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.knowledge,
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
  padding: 30px;
  overflow: auto;

  .blog-content {
    width: 75%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .card {
      margin: 10px;
      width: 48%;
      padding: 20px;
      float: left;

      .img-custom {
        height: 300px;
        max-width: 100%;
      }
    }

    .gray {
      color: #9a9a9a;
    }

    a {
      color: green;

      &:hover {
        text-decoration: none;
      }
    }
  }

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  @media (max-width: 1023px) {
    .blog-content {
      width: 95%;

      .card {
        width: 46%;
      }
    }
  }

  @media (max-width: 767px) {
    .blog-content {
      width: 100%;

      .card {
        width: 96%;
      }
    }
  }
}
</style>