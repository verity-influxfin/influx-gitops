<template>
  <div class="income-detail-card">
    <div class="input-group">
      <v-date-picker
        mode="range"
        v-model="range"
        style="width: 65%;"
        :popover="{ visibility: 'click' }"
      />
      <button class="btn btn-custom" type="button" @click="search('range')">
        <i class="fas fa-search"></i>
      </button>

      <button
        class="btn btn-info btn-sm btn-rel"
        type="button"
        style="left: -13px;"
        @click="search('month')"
      >本月</button>
      <button class="btn btn-primary btn-sm btn-rel" type="button" @click="search('all')">全部</button>
    </div>
    <div class="no-passbook-table" v-if="passbook.length ===0">
      <div class="no-passbook">
        <img :src="'./Image/no_passbook.svg'" class="img-fluid" />
      </div>
    </div>
    <div class="passbook-table" v-else>
      <scrollingTable :syncHeaderScroll="false" :scrollHorizontal="false">
        <template slot="thead">
          <tr class="header-center">
            <th class="remark">科目</th>
            <th class="amount">現金流量</th>
            <th class="bank_amount">虛擬帳戶餘額</th>
          </tr>
        </template>
        <template slot="tbody">
          <tr v-for="(item,index) in passbook" :key="index">
            <td v-for="(text,colIndex) in item" :class="colIndex" v-html="text" :key="colIndex"></td>
          </tr>
        </template>
      </scrollingTable>
    </div>
  </div>
</template>

<script>
import scrollingTable from "./scrollingTableComponent";

export default {
  components: {
    scrollingTable
  },
  props: {
    list: {
      type: Array,
      default: []
    }
  },
  data: () => ({
    range: {
      start: new Date(),
      end: new Date()
    },
    passbook: []
  }),
  watch: {
    "$props.list"(newData) {
      let $this = this;

      let date = new Date();
      if ($this.type === "month") {
        $this.range.start = new Date(date.getFullYear(), date.getMonth(), 1);
        $this.range.end = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate()
        );
      }

      $this.list.forEach((row, index) => {
        $this.passbook.push({
          remark: `${row.remark}<br>${row.tx_datetime.substr(2)}`,
          amount: `<span style="color:${
            row.amount > 0 ? "#5192E5" : "#FF4758"
          }">${$this.format(row.amount)}</span>`,
          bank_amount: $this.format(row.bank_amount)
        });
        if ($this.type !== "all") {
          if (
            row.created_at + "000" >= $this.range.end.getTime() ||
            $this.range.start.getTime() >= row.created_at + "000"
          ) {
            $this.passbook.splice(-1, 1);
          }
        }
      });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    search(type) {
      this.type = type;
      this.$emit("searchDteail");
    }
  }
};
</script>

<style lang="scss">
.income-detail-card {
  padding: 10px;
  box-shadow: 0 0 5px #848484;
  border-radius: 10px;
  background: #efefef;
  width: 400px;
  height: 455px;

  .input-group {
    margin-bottom: 10px;
  }

  .btn-rel {
    position: relative;
  }

  .btn-custom {
    border: 0;
    background: none;
    padding: 2px 5px;
    margin-top: 2px;
    position: relative;
    left: -28px;
    margin-bottom: 0;
    border-radius: 3px;
  }

  .passbook-table {
    height: 385px;
    overflow: hidden;
    position: relative;
    padding: 5px;
    background: #545454;
    color: #ffffff;
    box-shadow: 0 0 5px #000000;
    border-radius: 12px;
  }

  .no-passbook-table {
    height: 385px;
    overflow: hidden;
    position: relative;

    .no-passbook {
      width: 60%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.3;
    }
  }

  .header-center {
    .remark,
    .amount,
    .bank_amount {
      text-align: center !important;
    }
  }

  .remark {
    width: 160px;
    max-width: 160px;
    min-width: 160px;
  }

  .amount {
    width: 90px;
    max-width: 90px;
    min-width: 90px;
    text-align: end;
  }

  .bank_amount {
    width: 100px;
    min-width: 100px;
    max-width: 100px;
    text-align: end;
  }
}

@media screen and (max-width: 1023px) {
  .detail-card {
    .remark {
      width: 135px;
      min-width: 135px;
      max-width: 135px;
    }
  }
}
</style>