<template>
  <div class="bk-cooperation-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">合作訊息</li>
      </ol>
    </nav>

    <div class="action-bar">
      <div class="input-group float-right" style="width: 300px">
        <input
          type="text"
          class="form-control"
          placeholder="名字"
          v-model="filter.name"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-user"></i>
          </span>
        </div>
      </div>
      <div class="input-group float-right" style="width: 300px; margin-right: 15px">
        <select class="form-control" v-model="filter.type">
          <option value>無</option>
          <option value="campus">校園大使</option>
          <option value="club">校園社團贊助</option>
          <option value="company">企業合作</option>
          <option value="firm">商行合作</option>
        </select>
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
    </div>
    
    <div class="cooperation-block">
      <div class="cooperation-tabletitle">
        <div class="name">填寫人姓名</div>
        <div class="email">填寫人信箱</div>
        <div class="phone">電話</div>
        <div class="type">合作類型</div>
        <div class="message">訊息內容</div>
        <div class="date">填寫時間</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="cooperation-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="messageModal-modal modal fade"
      ref="messageModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
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
let cooperationRow = Vue.extend({
  props: ["item", "vm"],
  data: () => ({
    type: {
      campus: "校園大使",
      club: "校園社團贊助",
      company: "企業合作",
      firm: "商行合作",
    },
  }),
  template: `
    <li class="cooperation-row">
        <div class="name">{{item.name}}</div>
        <div class="email">{{item.email}}</div>
        <div class="phone">{{item.phone}}</div>
        <div class="type">{{type[item.type]}}</div>
        <div class="message" @click="showContent(item)">{{item.message.substr(0, 80)}}...</div>
        <div class="date">{{item.datetime.substr(0,10)}}</div>
        <div class="action-row">
            <button class="btn btn-warning btn-sm" style="margin-right:20px"v-if="item.isRead === '0'">未讀</button>
            <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
        </div>
    </li>
  `,
  methods: {
    showContent(item) {
      this.vm.message = item.message;
      this.vm.read(item);
      $(this.vm.$refs.messageModal).modal("show");
    },
  },
});

export default {
  data: () => ({
    pageNumber:1,
    message: "",
    rawData: [],
    filtedData: [],
    filter: {
      name: "",
      type: "",
    },
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getCooperationData();
  },
  methods: {
    async getCooperationData() {
      let res = await axios.get("getCooperationData");

      this.rawData = res.data;
      this.filtedData = res.data;
      this.pagination();
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filtedData,
          pageSize: 8,
          pageNumber:this.pageNumber,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new cooperationRow({
                propsData: {
                  item,
                  vm: $this,
                },
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          },
          afterPageOnClick() {
            this.pageNumber = $(".paginationjs-page.active").attr("data-num");
          },
        });
      });
    },
    read(item) {
      axios
        .post("readCooperationData", {
          ID: item.ID,
          data: {
            isRead: 1,
          },
        })
        .then((res) => {
          this.getCooperationData();
        })
        .catch((error) => {
          alert(`發生錯誤，請稍後再試`);
        });
    },
    delete(item) {
      axios
        .post("deleteCooperationData", {
          ID: item.ID,
        })
        .then((res) => {
          this.message = `刪除成功`;
          this.getCooperationData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    close() {
      $(this.$refs.messageModal).modal("hide");
    },
  },
};
</script>

<style lang="scss">
.bk-cooperation-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .cooperation-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .cooperation-tabletitle {
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

    .cooperation-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .cooperation-row {
        display: flex;

        &:hover {
          background: oldlace;
        }

        div {
          padding: 5px;
          text-align: center;

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .cooperation-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }
    .name {
      width: 7%;
    }

    .email {
      width: 15%;
    }

    .phone {
      width: 8%;
    }

    .type {
      width: 8%;
    }

    .message {
      width: 40%;
      cursor: pointer;
    }

    .date {
      width: 8%;
    }

    .action-row {
      width: 15%;
    }
  }

  .cooperation-modal {
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
