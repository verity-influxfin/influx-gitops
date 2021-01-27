<template>
  <div class="bk-banner-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">頁面banner</li>
      </ol>
    </nav>

    <div class="action-bar">
      <button class="btn btn-primary float-left" @click="create()">
        <i class="fas fa-plus"></i>
        <span>新增</span>
      </button>
    </div>

    <div class="banner-block">
      <div class="banner-tabletitle">
        <div class="type">頁面</div>
        <div class="desktop">桌面板</div>
        <div class="mobile">手機板</div>
        <div class="link">連結</div>
        <div class="isActive">是否上架</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="rawData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="banner-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="banner-modal modal fade"
      ref="bannerModal"
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
                <span class="input-group-text">桌面板</span>
              </div>
              <div style="display: grid">
                <img
                  :src="'/upload/banner/' + desktop"
                  class="img-fluid"
                  style="width: 300px"
                />
                <input type="file" @change="fileChange($event, 'desktop')" />
              </div>
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">手機板</span>
              </div>
              <div style="display: grid">
                <img
                  :src="'/upload/banner/' + mobile"
                  class="img-fluid"
                  style="width: 300px"
                />
                <input type="file" @change="fileChange($event, 'mobile')" />
              </div>
            </div>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">連結</span>
              </div>
              <input type="text" class="form-control" placeholder="連結" v-model="link" />
            </div>

            <div class="input-group" style="width: 15%">
              <div class="input-group-prepend">
                <span class="input-group-text">是否公開</span>
              </div>
              <select class="custom-select" v-model="isActive">
                <option value="on">公開</option>
                <option value="off">不公開</option>
              </select>
            </div>

            <div class="input-group" style="width: 15%">
              <div class="input-group-prepend">
                <span class="input-group-text">頁面</span>
              </div>
              <select class="custom-select" v-model="type">
                <option value="index">首頁</option>
                <option value="college">學生貸款</option>
                <option value="freshGraduate">上班族貸款</option>
                <option value="engineer">資訊工程師專案</option>
                <option value="invest">債權投資</option>
                <option value="transfer">債權轉讓</option>
              </select>
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
let bannerRow = Vue.extend({
  props: ["item", "vm"],
  data: () => ({
    type: {
      transfer: "債權轉讓",
      invest: "債權投資",
      engineer: "資訊工程師專案",
      freshGraduate: "上班族貸款",
      college: "學生貸款",
      index: "首頁",
    },
  }),
  template: `
    <li class="banner-row">
      <div class="type">{{type[item.type]}}</div>
      <div class="desktop"><img class="img-fluid" :src="'upload/banner/'+item.desktop"></div>
      <div class="mobile"><img class="img-fluid" :src="'upload/banner/'+item.mobile"></div>
      <div class="link"><a v-if="item.link" target="_blank" :href="item.link"><i class="fas fa-external-link-alt"></i></a></div>
      <div class="isActive">{{item.isActive}}</div>
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
    desktop: "",
    mobile: "",
    type: "",
    link: "",
    message: "",
    isActive: "",
    rawData: [],
    imageData: new FormData(),
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getBannerData();
  },
  methods: {
    async getBannerData() {
      let res = await axios.get("bakGetIndexBanner");

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
              let component = new bannerRow({
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
    async fileChange(e, target) {
      this.imageData.append("file", e.target.files[0]);
      let res = await axios.post("uploadBannerImg", this.imageData);
      this[target] = res.data;
    },
    create() {
      this.ID = "";
      this.desktop = "";
      this.mobile = "";
      this.type = "";
      this.link = "";
      this.isActive = "";
      this.actionType = "insert";

      $(this.$refs.bannerModal).modal("show");
    },
    edit(item) {
      this.ID = item.ID;
      this.desktop = item.desktop ? item.desktop : "./images/default-image.png";
      this.mobile = item.mobile ? item.mobile : "./images/default-image.png";
      this.type = item.type;
      this.link = item.link;
      this.isActive = item.isActive;
      this.actionType = "update";

      $(this.$refs.bannerModal).modal("show");
    },
    delete(item) {
      axios
        .post("deleteBannerData", {
          ID: item.ID,
        })
        .then((res) => {
          this.message = `刪除成功`;
          this.getBannerData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      axios
        .post("modifyBannerData", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            ID: this.ID,
            desktop: this.desktop,
            mobile: this.mobile,
            type: this.type,
            link: this.link,
            isActive: this.isActive,
          },
        })
        .then((res) => {
          this.message = `${this.actionType === "insert" ? "新增" : "更新"}成功`;

          this.getBannerData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`${this.actionType === "insert" ? "新增" : "更新"}發生錯誤，請稍後再試`);
        });
    },
    close() {
      $(this.$refs.bannerModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    },
  },
};
</script>

<style lang="scss">
.bk-banner-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .banner-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .banner-tabletitle {
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

    .banner-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .banner-row {
        display: flex;

        div {
          padding: 5px;
          text-align: center;

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .banner-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }
    .desktop {
      width: 45%;
    }
    .mobile {
      width: 20%;
    }
    .link {
      width: 5%;
    }
    .type {
      width: 10%;
    }
    .isActive {
      width: 10%;
    }
    .action-row {
      width: 10%;
    }
  }

  .banner-modal {
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
