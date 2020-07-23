<template>
  <div class="market-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">分期超市</li>
      </ol>
    </nav>
    <div class="phone-card">
      <div class="action-bar">
        <button class="btn btn-primary float-left" @click="create()">
          <i class="fas fa-plus"></i>
          <span>新增</span>
        </button>

        <div class="input-group float-right" style="width: 300px;">
          <input type="text" class="form-control" placeholder="手機名稱" v-model="filter.name" />
          <div class="input-group-append">
            <span class="input-group-text">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
      </div>
      <div class="phone-list">
        <div class="phone-tabletitle">
          <div class="name">手機名稱</div>
          <div class="price">價錢</div>
          <div class="phone_img">圖片</div>
          <div class="status">狀態</div>
          <div class="action-row">操作</div>
        </div>
        <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
        <div v-else>
          <ul class="phone-container" ref="container"></ul>
          <div class="pagination" ref="pagination"></div>
        </div>
      </div>
    </div>

    <div
      class="phone-modal modal fade"
      ref="phoneModal"
      tabindex="-1"
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
                <span class="input-group-text">手機名稱</span>
              </div>
              <input type="text" class="form-control" placeholder="手機名稱" v-model="name" />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">手機圖片</span>
              </div>
              <div style="display: grid;">
                <img :src="upLoadImg" class="img-fluid" style="width: 300px;" />
                <input type="file" @change="fileChange" />
              </div>
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">手機價錢</span>
              </div>
              <input type="text" class="form-control" placeholder="手機價錢" v-model="price" />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">上架</span>
              </div>
              <select class="custom-select" v-model="status">
                <option value="on">公開</option>
                <option value="off">不公開</option>
              </select>
            </div>
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
let phoneRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="phone-row">
        <div class="name">{{item.name}}</div>
        <div class="price">{{item.price}}</div>
        <div class="phone_img"><img :src="'./'+item.phone_img" class="img-fluid"></div>
        <div class="status">{{item.status === 'on' ? '上架': '下架'}}</div>
        <div class="action-row">
          <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
          <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
        </div>
    </li>
  `
});

export default {
  data: () => ({
    ID: "",
    name: "",
    price: "",
    status: "on",
    upLoadImg: "",
    actionType: "",
    message: "",
    rawData: [],
    filtedData: [],
    filter: {
      name: ""
    },
    imageData: new FormData()
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getPhoneData();
  },
  watch: {
    "filter.name"(newVal) {
      this.filtedData = [];
      this.rawData.forEach((row, index) => {
        if (row.name.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          this.filtedData.push(row);
        }
      });
      this.pagination();
    }
  },
  methods: {
    getPhoneData() {
      axios
        .post("getPhoneData")
        .then(res => {
          this.rawData = res.data;
          this.filtedData = res.data;
          this.pagination();
        })
        .catch(error => {
          console.log("getPhone 發生錯誤，請稍後再試");
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
              let component = new phoneRow({
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
      let res = await axios.post("uploadPhoneFile", this.imageData);
      this.upLoadImg = `./upload/phone/${res.data}`;
    },
    create() {
      this.name = "";
      this.status = "on";
      this.price = "";
      this.ID = "";
      this.upLoadImg = "./images/default-image.png";
      this.actionType = "insert";

      $(this.$refs.phoneModal).modal("show");
    },
    edit(item) {
      this.name = item.name;
      this.status = item.status;
      this.price = item.price;
      this.ID = item.ID;
      this.upLoadImg = item.phone_img
        ? item.phone_img
        : "./images/default-image.png";
      this.actionType = "update";

      $(this.$refs.phoneModal).modal("show");
    },
    delete(item) {
      axios
        .post("deletePhoneData", {
          ID: item.ID
        })
        .then(res => {
          this.message = `刪除成功`;
          this.getPhoneData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      axios
        .post("modifyPhoneData", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            name: this.name,
            status: this.status,
            price: this.price,
            phone_img: this.upLoadImg
          }
        })
        .then(res => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getPhoneData();
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
      $(this.$refs.phoneModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    }
  }
};
</script>

<style lang="scss">
.market-wrapper {
  overflow: hidden;
  padding: 10px;

  .phone-card {
    margin: 15px 5px;
    padding: 10px;
    background: #f5f4ff;
    border-radius: 10px;
    box-shadow: 0 0 4px black;

    .empty {
      padding: 10px;
      text-align: center;
    }

    .action-bar {
      position: relative;
      overflow: auto;
    }

    .phone-list {
      margin: 15px 0px;
      padding: 10px;
      box-shadow: 0 0 2px black;

      .phone-tabletitle {
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

      .phone-container {
        margin-bottom: 20px;
        padding: 0px;
        list-style: none;

        .phone-row {
          display: flex;
          height: 100px;
          line-height: 100px;

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

        .phone-row:not(:last-child) {
          border-bottom: 1px solid #b1b1b1;
        }
      }

      .name {
        width: 60%;
        padding: 0px 10px;
      }

      .price {
        width: 10%;
        padding: 0px 10px;
      }

      .phone_img {
        width: 15%;
        padding: 10px;
        overflow: hidden;
      }

      .status {
        width: 5%;
        padding: 0px 10px;
      }

      .action-row {
        width: 10%;
        padding: 0px 10px;
      }
    }
  }

  .phone-modal {
    @media (min-width: 576px) {
      .modal-dialog {
        max-width: 70%;
        margin: 1.75rem auto;
      }
    }

    .input-group {
      margin-bottom: 10px;
    }
  }

  .pagination {
    width: fit-content;
    margin: 0px auto;
  }

  .btn-close {
    float: right;
    font-size: 27px;
    z-index: 1;
  }
}
</style>