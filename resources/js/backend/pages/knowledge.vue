<template>
  <div class="bk-knowledge-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">AI金融科技新知</li>
      </ol>
    </nav>
    <div class="action-bar">
      <button class="btn btn-primary float-left" @click="create()">
        <i class="fas fa-plus"></i>
        <span>新增</span>
      </button>
      <div class="input-group float-right" style="width: 300px;">
        <select class="form-control" v-model="filter.category">
          <option value>無</option>
          <option value="investtonic">債權轉讓</option>
        </select>
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-stream"></i>
          </span>
        </div>
      </div>
      <div class="input-group float-right" style="width: 300px;">
        <input type="text" class="form-control" placeholder="文章標題" v-model="filter.title" />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
    </div>
    <div class="article-block">
      <div class="article-tabletitle">
        <div class="post-title">文章標題</div>
        <div class="status">狀態</div>
        <div class="category">發布至</div>
        <div class="order">置頂</div>
        <div class="post_date">刊登時間</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="article-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>
    <div
      class="article-modal modal fade"
      ref="articleModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn btn-close" data-dismiss="modal">
              <i class="far fa-times-circle"></i>
            </button>
            <div class="input-group" style="width: 95%;">
              <div class="input-group-prepend">
                <span class="input-group-text">文章標題</span>
              </div>
              <input type="text" class="form-control" placeholder="文章標題" v-model="postTitle" />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">文章進版圖</span>
              </div>
              <div style="display: grid;">
                <img :src="upLoadImg" class="img-fluid" style="width: 300px;" />
                <input type="file" @change="fileChange" />
              </div>
            </div>
            <div class="input-group" style="width: 15%;">
              <div class="input-group-prepend">
                <span class="input-group-text">是否公開</span>
              </div>
              <select class="custom-select" v-model="status">
                <option value="publish">公開</option>
                <option value="inherit">不公開</option>
              </select>
            </div>
            <div class="input-group" style="width: 15%;">
              <div class="input-group-prepend">
                <span class="input-group-text">發布至</span>
              </div>
              <select class="custom-select" v-model="category">
                <option value>無</option>
                <option value="investtonic">債權轉讓</option>
              </select>
            </div>
            <div class="input-group" style="width: 15%;">
              <div class="input-group-prepend">
                <span class="input-group-text">是否置頂</span>
              </div>
              <select class="custom-select" v-model="order">
                <option value="1">是</option>
                <option value="0">否</option>
              </select>
            </div>
            <ckeditor v-model="postContent" :config="editorConfig"></ckeditor>
          </div>
          <div class="modal-footer" style="display:block;">
            <button class="btn btn-secondary float-left" data-dismiss="modal">取消</button>
            <button class="btn btn-success float-right" @click="submit">送出</button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="messageModal-modal modal fade"
      ref="messageModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">{{message}}</div>
          <div class="modal-footer" style="display:block;">
            <button class="btn btn-success float-right" @click="close">確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let articleRow = Vue.extend({
  props: ["item", "vm"],
  data: () => ({
    categoryList: {
      investtonic: "債權轉讓"
    }
  }),
  template: `
    <li class="article-row">
        <div class="post-title">{{item.post_title}}</div>
        <div class="status">{{item.status === 'publish' ? '公開': '不公開'}}</div>
        <div class="category">{{changeToText(item.category)}}</div>
        <div class="order">{{item.order === 0 ? '否': '是'}}</div>
        <div class="post_date">{{item.post_date}}</div>
        <div class="action-row">
          <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
          <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
        </div>
    </li>
  `,
  methods: {
    changeToText(category) {
      let { categoryList } = this;
      return categoryList[category] ? categoryList[category] : "無";
    }
  }
});

export default {
  data: () => ({
    postTitle: "",
    status: "publish",
    order: "0",
    post_content: "",
    actionType: "",
    category: "",
    message: "",
    ID: "",
    type: "none",
    upLoadImg: "./images/default-image.png",
    rawData: [],
    filtedData: [],
    filter: {
      title: "",
      category: ""
    },
    imageData: new FormData(),
    editorConfig: {
      height: 500,
      filebrowserImageUploadUrl: "uploadKnowledgeImg",
      filebrowserUploadMethod: 'form',
      image_previewText: ""
    }
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getknowledgeData();
  },
  mounted() {},
  watch: {
    "filter.title"(newVal) {
      this.doFilter(newVal, this.filter.category);
    },
    "filter.category"(newVal) {
      this.doFilter(this.filter.title, newVal);
    }
  },
  methods: {
    doFilter(title, category) {
      this.filtedData = [];
      this.rawData.forEach((row, index) => {
        if (
          row.post_title.toLowerCase().indexOf(title.toLowerCase()) !== -1 &&
          row.category.toLowerCase().indexOf(category.toLowerCase()) !== -1
        ) {
          this.filtedData.push(row);
        }
      });
      this.pagination();
    },
    getknowledgeData() {
      axios
        .post("getKnowledge")
        .then(res => {
          this.rawData = res.data;
          this.filtedData = res.data;
          this.pagination();
        })
        .catch(error => {
          console.log("getknowledge 發生錯誤，請稍後再試");
        });
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filtedData,
          pageSize: 8,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new articleRow({
                propsData: {
                  item,
                  vm: $this
                }
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          }
        });
      });
    },
    async fileChange(e) {
      this.imageData.append("file", e.target.files[0]);
      let res = await axios.post("uploadKnowledgeIntroImg", this.imageData);
      this.upLoadImg = `./upload/article/${res.data}`;
    },
    create() {
      this.postTitle = "";
      this.status = "publish";
      this.order = "0";
      this.postContent = "";
      this.ID = "";
      this.actionType = "insert";
      this.upLoadImg = "./images/default-image.png";
      this.category = null;

      $(this.$refs.articleModal).modal("show");
    },
    edit(item) {
      this.postTitle = item.post_title;
      this.status = item.status;
      this.order = item.order;
      this.postContent = item.post_content;
      this.ID = item.ID;
      this.upLoadImg = item.media_link
        ? item.media_link
        : "./images/default-image.png";
      this.actionType = "update";
      this.category = item.category;

      $(this.$refs.articleModal).modal("show");
    },
    delete(item) {
      axios
        .post("deleteKonwledge", {
          ID: item.ID
        })
        .then(res => {
          this.message = `刪除成功`;
          this.getknowledgeData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      axios
        .post("modifyKnowledge", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            post_title: this.postTitle,
            post_content: this.postContent,
            status: this.status,
            order: this.order,
            media_link: this.upLoadImg,
            category: this.category
          }
        })
        .then(res => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getknowledgeData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(
            `${
              this.actionType === "insert" ? "新增" : "更新"
            }發生錯誤，請稍後再試`
          );
        });
    },
    close() {
      $(this.$refs.articleModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    }
  }
};
</script>

<style lang="scss">
.bk-knowledge-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .article-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .article-tabletitle {
      display: flex;
      overflow: auto;

      div {
        border-bottom: 2px solid #929292;
        text-align: center;

        &:not(:last-child) {
          border-right: 1px solid #bbbbbb;
        }
      }
    }

    .article-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .article-row {
        display: flex;

        .action-row {
          padding: 5px;
        }

        div {
          &:not(:first-child) {
            text-align: center;
          }

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .article-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    .post-title {
      width: 50%;
      padding: 10px;
      text-align: start;
    }

    .category {
      width: 10%;
      padding: 10px;
    }

    .order {
      width: 5%;
      padding: 10px;
    }

    .status {
      width: 5%;
      padding: 10px;
    }

    .post_date {
      width: 15%;
      padding: 10px;

      i {
        margin-left: 5px;
        cursor: pointer;
      }
    }

    .action-row {
      width: 15%;
      padding: 10px;
    }
  }

  .article-modal {
    @media (min-width: 576px) {
      .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
      }
    }

    .input-group {
      padding-bottom: 20px;
    }
  }

  .pagination {
    width: fit-content;
    margin: 0px auto;
  }

  .btn-close {
    position: absolute;
    top: -9px;
    right: -7px;
    font-size: 27px;
    z-index: 1;
  }
}
</style>