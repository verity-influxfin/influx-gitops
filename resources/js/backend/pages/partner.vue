<template>
  <div class="bk-partner-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">合作夥伴</li>
      </ol>
    </nav>

    <div class="action-bar">
      <button class="btn btn-primary float-left" @click="create()">
        <i class="fas fa-plus"></i>
        <span>新增</span>
      </button>
    </div>

    <div class="partner-block">
      <div class="partner-tabletitle">
        <div class="logo">校徽</div>
        <div class="name">名稱</div>
        <div class="link">網站連結</div>
        <div class="title">標題</div>
        <div class="desc">說明</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="rawData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="partner-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="partner-modal modal fade"
      ref="partnerModal"
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
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">校徽</span>
              </div>
              <div style="display: grid">
                <img :src="upLoadImg" class="img-fluid" style="width: 300px" />
                <input type="file" @change="fileChange" />
              </div>
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">校名</span>
              </div>
              <input type="text" class="form-control" placeholder="校名" v-model="name" />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">連結</span>
              </div>
              <input type="text" class="form-control" placeholder="連結" v-model="link" />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">標題</span>
              </div>
              <textarea
                type="text"
                class="form-control"
                placeholder="標題"
                style="height: 200px"
                v-model="title"
              />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">說明</span>
              </div>
              <textarea
                type="text"
                class="form-control"
                placeholder="標題"
                style="height: 200px"
                v-model="text"
              />
            </div>
          </div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-secondary float-left" data-dismiss="modal">
              取消
            </button>
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
          <div class="modal-body">{{ message }}</div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-success float-right" @click="close">確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let partnerRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="partner-row">
      <div class="logo"><div class="img"><img :src="item.imageSrc" class="img-fluid"></div></div>
      <div class="name">{{item.name}}</div>
      <div class="link"><a v-if="item.link" class="text-success" :href="item.link" target="_blank"><i class="fas fa-external-link-alt"></i></a></div>
      <div class="title">{{item.title}}</div>
      <div class="desc">{{item.text}}</div>
      <div class="action-row">
        <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
        <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
      </div>
    </li>
  `,
});

export default {
  data: () => ({
    pageNumber: 1,
    ID: "",
    imageSrc: "",
    name: "",
    link: "",
    title: "",
    text: "",
    upLoadImg: "./images/default-image.png",
    message: "",
    rawData: [],
    imageData: new FormData(),
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getPartnerData();
  },
  methods: {
    async getPartnerData() {
      let res = await axios.get("getPartnerData");

      this.rawData = res.data;
      this.pagination();
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.rawData,
          pageSize: 8,
          pageNumber: $this.pageNumber,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new partnerRow({
                propsData: {
                  item,
                  vm: $this,
                },
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
    async fileChange(e) {
      this.imageData.append("file", e.target.files[0]);
      let res = await axios.post("uploadPartnerImg", this.imageData);
      this.upLoadImg = `./upload/partner/${res.data}`;
    },
    create() {
      this.ID = "";
      this.imageSrc = "";
      this.name = "";
      this.link = "";
      this.title = "";
      this.text = "";
      this.upLoadImg = "./images/default-image.png";
      this.actionType = "insert";

      $(this.$refs.partnerModal).modal("show");
    },
    edit(item) {
      this.ID = item.ID;
      this.name = item.name;
      this.link = item.link;
      this.title = item.title;
      this.text = item.text;
      this.upLoadImg = item.imageSrc ? item.imageSrc : "./images/default-image.png";
      this.actionType = "update";

      $(this.$refs.partnerModal).modal("show");
    },
    delete(item) {
      axios
        .post("deletePartnerData", {
          ID: item.ID,
        })
        .then((res) => {
          this.message = `刪除成功`;
          this.getPartnerData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      axios
        .post("modifyPartnerData", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            ID: this.ID,
            name: this.name,
            link: this.link,
            title: this.title,
            text: this.text,
            imageSrc: this.upLoadImg,
          },
        })
        .then((res) => {
          this.message = `${this.actionType === "insert" ? "新增" : "更新"}成功`;

          this.getPartnerData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`${this.actionType === "insert" ? "新增" : "更新"}發生錯誤，請稍後再試`);
        });
    },
    close() {
      $(this.$refs.partnerModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    },
  },
};
</script>

<style lang="scss">
.bk-partner-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .partner-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .partner-tabletitle {
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

    .partner-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .partner-row {
        display: flex;

        div {
          padding: 5px;
          text-align: center;

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .partner-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    .logo {
      width: 8%;
    }
    .name {
      width: 15%;
    }
    .link {
      width: 8%;
    }
    .title {
      width: 23%;
    }
    .desc {
      width: 42%;
    }
    .action-row {
      width: 10%;
    }
  }

  .partner-modal {
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
