<template>
  <div class="media-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">媒體報導</li>
      </ol>
    </nav>

    <div class="action-bar">
      <button class="btn btn-primary float-left" @click="create()">
        <i class="fas fa-plus"></i>
        <span>新增</span>
      </button>
    </div>

    <div class="media-block">
      <div class="media-tabletitle">
        <div class="media">媒體</div>
        <div class="date">刊登時間</div>
        <div class="title">報導標題</div>
        <div class="link">報導連結</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="rawData.length === 0">查無資料！</div>
      <div v-else>
      <ul class="media-container" ref="container"></ul>
      <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="media-modal modal fade"
      ref="mediaModal"
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
                <span class="input-group-text">媒體</span>
              </div>
              <input type="text" class="form-control" placeholder="媒體" v-model="media" />
            </div>

            <div class="input-group" style="width: 95%;">
              <div class="input-group-prepend">
                <span class="input-group-text">刊登日期</span>
              </div>
              <v-date-picker v-model="date" :popover="{ visibility: 'click' }" />
            </div>

            <div class="input-group" style="width: 95%;">
              <div class="input-group-prepend">
                <span class="input-group-text">報導標題</span>
              </div>
              <input type="text" class="form-control" placeholder="報導標題" v-model="title" />
            </div>

            <div class="input-group" style="width: 95%;">
              <div class="input-group-prepend">
                <span class="input-group-text">報導連結</span>
              </div>
              <input type="text" class="form-control" placeholder="報導連結" v-model="link" />
            </div>
            <ckeditor v-model="content" :config="editorConfig"></ckeditor>
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
let mediaRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="media-row">
      <div class="media">{{item.media}}</div>
      <div class="date">{{item.date}}</div>
      <div class="title">{{item.title}}</div>
      <div class="link"><a :href="item.link" target="_blank">點我<i class="fas fa-external-link-alt"></i></a></div>
      <div class="action-row">
        <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
        <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
      </div>
    </li>
  `
});

export default {
  data: () => ({
    pageNumber:1,
    ID: "",
    media: "",
    date: new Date(),
    title: "",
    link: "",
    content: "",
    message: "",
    actionType: "",
    rawData: [],
    editorConfig: {
      height: 500
    }
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getMediaData();
  },
  methods: {
    async getMediaData() {
      let res = await axios.get("getMediaData");

      this.rawData = res.data;
      this.pagination();
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.rawData,
          pageSize: 8,
          pageNumber:$this.pageNumber,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new mediaRow({
                propsData: {
                  item,
                  vm: $this
                }
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          },
          afterPageOnClick() {
            $this.pageNumber = $(".paginationjs-page.active").attr("data-num");
          },
        });
      });
    },
    create() {
      this.ID = "";
      this.media = "";
      this.date = new Date();
      this.title = "";
      this.link = "";
      this.content = "";
      this.actionType = "insert";

      $(this.$refs.mediaModal).modal("show");
    },
    edit(item) {
      this.ID = item.ID;
      this.media = item.media;
      this.date = new Date(item.date);
      this.title = item.title;
      this.link = item.link;
      this.content = item.content;
      this.actionType = "update";

      $(this.$refs.mediaModal).modal("show");
    },
    delete(item) {
      axios
        .post("deleteMediaData", {
          ID: item.ID
        })
        .then(res => {
          this.message = `刪除成功`;
          this.getMediaData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      let d = new Date(this.date);
      let date_item = {
        year: d.getFullYear(),
        month: (d.getMonth() + 1 < 10 ? "0" : "") + (d.getMonth() + 1),
        day: (d.getDate() < 10 ? "0" : "") + d.getDate()
      };

      axios
        .post("modifyMediaData", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            ID: this.ID,
            media: this.media,
            date: `${date_item.year}-${date_item.month}-${date_item.day}`,
            title: this.title,
            link: this.link,
            content: this.content
          }
        })
        .then(res => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getMediaData();
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
      $(this.$refs.mediaModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    }
  }
};
</script>

<style lang="scss">
.media-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .media-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .media-tabletitle {
      display: flex;
      overflow: auto;

      div {
        border-bottom: 2px solid #929292;
        text-align: center;
        padding: 5px;

        &:not(:last-child) {
          border-right: 1px solid #bbbbbb;
        }
      }
    }

    .media-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .media-row {
        display: flex;

        div {
          padding: 5px;
          text-align: center;

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .media-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    .media {
      width: 10%;
    }

    .date {
      width: 10%;
    }

    .title {
      width: 55%;
    }

    .link {
      width: 10%;
    }

    .action-row {
      width: 15%;
    }
  }

  .media-modal {
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