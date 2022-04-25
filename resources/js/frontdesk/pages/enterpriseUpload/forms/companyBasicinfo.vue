<template>
  <form class="principal-passbook">
    <div
      class="
        row
        no-gutters
        align-items-end
        uploadform-content-title
        pb-3
        mb-3
        title-border-bottom
      "
    >
      <div class="col-auto">公司基本資料</div>
      <div class="col"></div>
      <div class="col-auto uploadform-content-title-hint">*為必要填寫欄位</div>
    </div>
    <div class="row no-gutters">
      <div class="col">
        <div>(1)*連絡人姓名：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
      <div class="col mx-3">
        <div>(2)*連絡人職稱：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
      <div class="col">
        <div>(3)*連絡電話：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
    </div>
    <div class="row no-gutters mt-2">
      <div class="col">
        <div>(4)*Email：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
      <div class="col mx-3">
        <div>(5)*傳真號碼：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
      <div class="col">
        <div>(6)*聯絡人分機：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
    </div>
    <div class="title-border-bottom my-4"></div>
    <div class="row no-gutters">
      <div class="col">
        <div>(1)*財務主管姓名：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
      <div class="col mx-3">
        <div>(2)*財務主管分機：</div>
        <div class="mt-2 input-group input-group-sm">
          <input type="text" class="form-control" required />
        </div>
      </div>
      <div class="col"></div>
    </div>
    <div class="title-border-bottom my-4"></div>
    <div class="row no-gutters">
      <div class="col-12 mb-3">一.</div>
      <div class="col-12">
        *是否曾變更負責人：
        <label>
          <input type="radio" name="principal" required />
          是
        </label>
        <label>
          <input type="radio" name="principal" id="" />
          否
        </label>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">(1) 變更負責人時間：</div>
        <div class="col-auto input-group input-group-sm w-25">
          <input type="text" class="form-control" />
        </div>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">(2) 變更負責人原因：</div>
        <span class="col input-group input-group-sm">
          <input type="text" class="form-control" />
        </span>
      </div>
    </div>
    <div class="title-border-bottom my-4"></div>
    <div class="row no-gutters">
      <div class="col-12 mb-3">二.</div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto">(1)*公司股東人數：</div>
        <span class="col input-group input-group-sm pr-3">
          <input type="text" class="form-control" />
        </span>
      </div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto pl-3">(2)*公司員工人數：</div>
        <div class="col input-group input-group-sm">
          <input type="text" class="form-control" />
        </div>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">(3)*屬於受嚴重特殊傳染性肺炎影響之企業：</div>
        <label>
          <input type="radio" name="covid" required />
          是
        </label>
        <label>
          <input type="radio" name="covid" id="" />
          否
        </label>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">
          (4)*受上述影響致財務困難，支票存款戶經票據交換所註記為 ⌜紓困⌟：
        </div>
        <label>
          <input type="radio" name="bailout" required />
          是
        </label>
        <label>
          <input type="radio" name="bailout" id="" />
          否
        </label>
      </div>
    </div>
    <div class="title-border-bottom my-4"></div>
    <div class="row no-gutters">
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto">(1)*是否公開發行：</div>
        <label>
          <input type="radio" name="pb" required />
          是
        </label>
        <label>
          <input type="radio" name="pb" id="" />
          否
        </label>
      </div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto pl-3">(2)有公開發行計畫：</div>
        <label>
          <input type="radio" name="pu_plan" />
          有
        </label>
        <label>
          <input type="radio" name="pu_plan" id="" />
          無
        </label>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">(3)*預計辦理公開發行年份：</div>
        <span class="col-auto input-group input-group-sm w-25">
          <input type="text" class="form-control" />
        </span>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">(4)*是否有海外投資：</div>
        <label>
          <input type="radio" name="bailout" required />
          是
        </label>
        <label>
          <input type="radio" name="bailout" id="" />
          否
        </label>
      </div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto">(5)*企業專業證照/專利：</div>
        <label>
          <input type="radio" name="patent" required />
          有
        </label>
        <label>
          <input type="radio" name="patent" id="" />
          無
        </label>
      </div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto pl-3">(6)*企業專業證照/專利名稱：</div>
        <div class="col input-group input-group-sm">
          <input type="text" class="form-control" />
        </div>
      </div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto">(7)*企業自有品牌：</div>
        <label>
          <input type="radio" name="band" required />
          有
        </label>
        <label>
          <input type="radio" name="band" id="" />
          無
        </label>
      </div>
      <div class="col-6 d-flex no-gutters my-2">
        <div class="col-auto pl-3">(8)*企業自有品牌名稱：</div>
        <div class="col input-group input-group-sm">
          <input type="text" class="form-control" />
        </div>
      </div>
      <div class="col-12 d-flex no-gutters my-2">
        <div class="col-auto">(9)*企業是否從事衍生性金融商品操作：</div>
        <label>
          <input type="radio" name="fin" required />
          是
        </label>
        <label>
          <input type="radio" name="fin" id="" />
          否
        </label>
      </div>
    </div>
    <div class="row no-gutters mt-3 justify-content-end">
      <button class="btn uploadform-btn-primary" @click="onSubmit">
        確認儲存
      </button>
    </div>
  </form>
</template>

<script>
export default {
  computed: {
    caseId() {
      return this.$route.query['case-id'] ?? ''
    }
  },
  methods: {
    onSubmit() {
      this.$router.push('/enterprise-upload/overview/company?case-id=' + this.caseId)
    }
  },
}
</script>

<style lang="scss" scoped>
.principal-passbook {
  .passbook-content {
    height: 482px;
  }
  .title-border-bottom {
    border-bottom: 1.5px solid #f3f3f3;
  }
  .info-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 1.5px solid #f3f3f3;
    margin-bottom: 20px;
  }
}
</style>
