<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
      integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn"
      crossorigin="anonymous"
    />
    <title>公司徵信報告</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  </head>

  <body>
    <div id="app">
      <!-- header -->
      <div class="header">
        <div class="row no-gutters justify-content-between title-row">
          <div class="col-auto case-item">
            <span>案件編號:</span>
            <input type="text" v-model="apiInfo.targetNo" />
          </div>
          <div class="case-title">徵信報告</div>
          <div class="col-auto case-item">
            <span>產出日期:</span>
            <input type="text" v-model="apiInfo.createdAt" />
          </div>
        </div>
        <div class="row no-gutters justify-content-between select-row">
          <div class="col pr-2">
            <button
              class="btn btn-select w-100"
              :class="{active: activeSec === 'sec-1'}"
              v-on:click="change('sec-1')"
            >
              企業＋負責人基本資訊
            </button>
          </div>
          <div class="col pr-2">
            <button
              class="btn btn-select w-100"
              :class="{active: activeSec === 'sec-2'}"
              v-on:click="change('sec-2')"
            >
              企業徵信資訊
            </button>
          </div>
          <div class="col">
            <button
              class="btn btn-select w-100"
              :class="{active: activeSec === 'sec-3'}"
              v-on:click="change('sec-3')"
            >
              負責人配偶，保證人資訊
            </button>
          </div>
        </div>
      </div>
      <!-- 企業+負責人基本資訊 -->
      <div class="main" v-show="activeSec === 'sec-1'">
        <div class="row no-gutters">
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div class="item-1">公司名稱：</div>
            <input type="text" v-model="apiInfo.basicInfo.companyName" />
          </div>
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div>統一編號：</div>
            <input type="text" v-model="apiInfo.basicInfo.taxIDNumber" />
          </div>
        </div>
        <div class="row no-gutters col-item">
          <div class="col-auto p-2 item-1">登記所在地址：</div>
          <div class="col p-2">
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.basicInfo.companyRegisterAddress"
            />
          </div>
        </div>
        <div class="row no-gutters col-item">
          <div class="col-auto p-2 item-1">主要營業場所地址：</div>
          <div class="col p-2">
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.basicInfo.companyBusinessAddress"
            />
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div class="item-1">(實收)資本額：</div>
            <div class="">
              <input
                type="text"
                class="w-75"
                v-model="apiInfo.basicInfo.capital"
              />
              <span class="w-25">千元</span>
            </div>
          </div>
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div>核准設立日期</div>
            <div class="col d-flex justify-content-end">
              <input
                type="text"
                class="tiny-input"
                v-model="apiInfo.basicInfo.companySetDate.year"
              />
              <div class="px-1">年</div>
              <input
                type="text"
                class="tiny-input"
                v-model="apiInfo.basicInfo.companySetDate.month"
              />
              <div class="px-1">月</div>
              <input
                type="text"
                class="tiny-input"
                v-model="apiInfo.basicInfo.companySetDate.day"
              />
              <div class="px-1">日</div>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div
            class="
              col-6 col-item
              d-flex
              justify-content-between
              p-2
              align-items-center
            "
          >
            <div>公司型態：</div>
            <div class="col d-flex flex-wrap">
              <div class="col-6">
                <input
                  type="radio"
                  name="type"
                  value="1"
                  v-model="apiInfo.basicInfo.companyType"
                />股份有限公司
              </div>
              <div>
                <input
                  type="radio"
                  name="type"
                  value="2"
                  v-model="apiInfo.basicInfo.companyType"
                />合夥
              </div>
              <div class="col-6">
                <input
                  type="radio"
                  name="type"
                  value="3"
                  v-model="apiInfo.basicInfo.companyType"
                />有限公司
              </div>
              <div>
                <input
                  type="radio"
                  name="type"
                  value="4"
                  v-model="apiInfo.companyType"
                />獨資
              </div>
            </div>
          </div>
          <div
            class="
              col-3 col-item
              d-flex
              p-2
              align-items-center
              justify-content-between
            "
          >
            <div>公司規模：</div>
            <div class="d-block">
              <div>
                <input
                  type="radio"
                  name="size"
                  value="1"
                  v-model="apiInfo.basicInfo.companyScale"
                />中小企業
              </div>
              <div>
                <input
                  type="radio"
                  name="size"
                  value="2"
                  v-model="apiInfo.basicInfo.companyScale"
                />非中小企業
              </div>
            </div>
          </div>
          <div
            class="
              col-3 col-item
              d-flex
              justify-content-between
              p-2
              align-items-center
            "
          >
            <div>員工人數：</div>
            <input
              type="text"
              class="tiny-input"
              v-model="apiInfo.basicInfo.numberOfEmployees"
            />
            <div>人</div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div class="item-1">產業別名稱 (主計處)</div>
            <input type="text" v-model="apiInfo.basicInfo.industryName" />
          </div>
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div>營業種類:</div>
            <div class="col d-flex align-items-center justify-content-end">
                <input 
                    type="radio" 
                    name="business-type" 
                    v-model="apiInfo.enterpriseInvestigation.business.type" 
                    value="1" 
                />
                <label>製造</label>
                <input 
                    type="radio" 
                    name="business-type" 
                    v-model="apiInfo.enterpriseInvestigation.business.type" 
                    value="2" 
                />
                <label>買賣</label>
                <input 
                    type="radio" 
                    name="business-type" 
                    v-model="apiInfo.enterpriseInvestigation.business.type" 
                    value="3" 
                />
                <label>其他</label>
            </div>
          </div>
        </div>
        <div class="row-title row no-gutters my-2">
          公司沿革：
          <div class="row-title-tip">(依經濟部商業司)</div>
        </div>
        <div class="row no-gutters">
          <div class="col-3 col-item p-2 text-center">日期</div>
          <div class="col-2 col-item p-2 text-center">負責人</div>
          <div class="col-2 col-item p-2">(實收)資本額</div>
          <div class="col col-item p-2 text-center">重大事項</div>
        </div>
        <div>
          <div class="row no-gutters col-item" id="sec-1-company-history">
            <div
              class="row no-gutters"
              v-for="(item,index) in apiInfo.basicInfo.history"
            >
              <div class="col-3 p-2 d-flex">
                <input type="text" class="tiny-input" v-model="item.year" />
                <div class="px-1">年</div>
                <input type="text" class="tiny-input" v-model="item.month" />
                <div class="px-1">月</div>
                <input type="text" class="tiny-input" v-model="item.day" />
                <div class="px-1">日</div>
              </div>
              <div class="col-2 p-2 text-center">
                <input type="text" class="w-75" v-model="item.companyOwner" />
              </div>
              <div class="col-2 p-2">
                <input type="text" class="w-50" v-model="item.capital" />
                <span class="ml-2">千元</span>
              </div>
              <div class="col p-2 text-center">
                <input type="text" placeholder="" v-model="item.description" />
              </div>
              <div class="col-auto p-2">
                <button
                  class="btn btn-1 btn-primary"
                  v-on:click="apiInfo.basicInfo.history.splice(index,1)"
                >
                  -
                </button>
                <button
                  class="btn btn-1 btn-secondary"
                  v-on:click="apiInfo.basicInfo.history.splice(index+1,0,{...templates.history})"
                >
                  +
                </button>
              </div>
            </div>
          </div>
          <div class="row-title row no-gutters my-2">
            經營團隊：
            <div class="row-title-tip">(依變更登記事項表)</div>
          </div>
          <div class="row no-gutters">
            <div class="col-3 col-item p-2 text-center">職稱</div>
            <div class="col col-item p-2 text-center">姓名</div>
            <div class="col-4 col-item p-2 text-center">統一編號</div>
          </div>
          <div>
            <div class="row no-gutters col-item" id="sec-1-company-team">
              <div
                class="row no-gutters w-100"
                v-for="(item,index) in apiInfo.basicInfo.team.list"
              >
                <div class="col-3 p-2 text-center">
                  <input type="text" class="w-50" v-model="item.jobTitle" />
                </div>
                <div class="col p-2 text-center">
                  <input type="text" class="w-50" v-model="item.name" />
                </div>
                <div class="col-3 p-2 text-center">
                  <input
                    type="text"
                    placeholder=""
                    v-model="item.identityNumber"
                  />
                </div>
                <div class="col-auto p-2">
                  <button
                    class="btn btn-1 btn-primary"
                    v-on:click="apiInfo.basicInfo.team.list.splice(index,1)"
                  >
                    -
                  </button>
                  <button
                    class="btn btn-1 btn-secondary"
                    v-on:click="apiInfo.basicInfo.team.list.splice(index+1,0,{...templates.teamList})"
                  >
                    +
                  </button>
                </div>
              </div>
            </div>
            <div class="row no-gutters col-item p-2 justify-content-between">
              <div>股東人數</div>
              <div class="">
                <input
                  class="tiny-input"
                  v-model="apiInfo.basicInfo.team.numberOfShareholders"
                /><label>人</label>
              </div>
            </div>
          </div>
          <div class="row-title row no-gutters my-2 justify-content-between">
            負責人資訊：
            <div class="d-flex row-title-input">
              <div>
                是否擔任保證人
                <input
                  type="radio"
                  name="guarantor"
                  v-model="apiInfo.basicInfo.ownerInfo.isGuarantor"
                  value="true"
                /><label>是</label>
                <input
                  type="radio"
                  name="guarantor"
                  v-model="apiInfo.basicInfo.ownerInfo.isGuarantor"
                  value="false"
                /><label>否</label>
              </div>
              <div class="ml-3">
                是否為實際負責人
                <input
                  type="radio"
                  name="actual"
                  v-model="apiInfo.basicInfo.ownerInfo.isRealOwner"
                  value="true"
                /><label>是</label>
                <input
                  type="radio"
                  name="actual"
                  v-model="apiInfo.basicInfo.ownerInfo.isRealOwner"
                  value="true"
                /><label>否</label>
              </div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-6 col-item d-flex justify-content-between p-2">
              <div class="item-1">負責人：</div>
              <input type="text" v-model="apiInfo.basicInfo.ownerInfo.name" />
            </div>
            <div class="col-6 col-item d-flex justify-content-between p-2">
              <div>負責人統一編號：</div>
              <input
                type="text"
                v-model="apiInfo.basicInfo.ownerInfo.identityNumber"
              />
            </div>
          </div>
          <div>
            <div class="row no-gutters col-item">
              <div class="col-auto p-2 item-1">戶籍地址：</div>
              <div class="col p-2">
                <input
                  type="text"
                  class="w-100"
                  v-model="apiInfo.basicInfo.ownerInfo.residenceAddress"
                />
              </div>
            </div>
            <div class="row no-gutters">
              <div class="col-6 col-item d-flex justify-content-between p-2">
                <div>出生年月日</div>
                <div class="col d-flex justify-content-end">
                  <input
                    type="text"
                    class="tiny-input"
                    v-model="apiInfo.basicInfo.ownerInfo.birthday.year"
                  />
                  <div class="px-1">年</div>
                  <input
                    type="text"
                    class="tiny-input"
                    v-model="apiInfo.basicInfo.ownerInfo.birthday.month"
                  />
                  <div class="px-1">月</div>
                  <input
                    type="text"
                    class="tiny-input"
                    v-model="apiInfo.basicInfo.ownerInfo.birthday.day"
                  />
                  <div class="px-1">日</div>
                </div>
              </div>
              <div class="col-6 col-item d-flex justify-content-between p-2">
                <div class="item-1">從事本行業年度</div>
                <input
                  type="text"
                  v-model="apiInfo.basicInfo.ownerInfo.startYear"
                />
                <div class="pl-2">年</div>
              </div>
            </div>
            <div class="row no-gutters">
              <div class="col-6 col-item p-2 text-center">兼任公司名稱</div>
              <div class="col-6 col-item p-2 text-center">職稱</div>
            </div>
            <div
              class="row no-gutters col-item"
              id="sec-1-responsible-concurrently"
            >
              <div
                class="d-flex w-100"
                v-for="(item,index) in apiInfo.basicInfo.ownerInfo.otherCompany"
              >
                <div class="col-6 p-2 text-center">
                  <input type="text" v-model="item.companyName" />
                </div>
                <div class="col p-2 text-center">
                  <input type="text" placeholder="" v-model="item.jobTitle" />
                </div>
                <div class="col-auto p-2">
                  <button
                    class="btn btn-1 btn-primary"
                    v-on:click="apiInfo.basicInfo.ownerInfo.otherCompany.splice(index,1)"
                  >
                    -
                  </button>
                  <button
                    class="btn btn-1 btn-secondary"
                    v-on:click="apiInfo.basicInfo.ownerInfo.otherCompany.splice(index+1,0,{...templates.otherCompany})"
                  >
                    +
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 企業徵信資訊 -->
      <div class="main" v-show="activeSec === 'sec-2'">
        <div class="row-title row no-gutters my-2">營業現況：</div>
        <div class="row no-gutters">
          <div class="col-12 col-item d-flex justify-content-between p-2">
            <div class="item-1">主要業務：</div>
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.enterpriseInvestigation.business.mainBusiness"
            />
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div class="item-1">商業計劃書</div>
            <div class="col d-flex justify-content-end">
              <a
                v-if="apiInfo.enterpriseInvestigation.business.plan"
                v-bind:href="apiInfo.enterpriseInvestigation.business.plan"
                >連結</a
              >
            </div>
          </div>
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div>訪廠影片（照片）</div>
            <div class="col d-flex justify-content-end">
              <a
                v-if="apiInfo.enterpriseInvestigation.business.placeData"
                v-bind:href="apiInfo.enterpriseInvestigation.business.placeData"
                >連結</a
              >
            </div>
          </div>
        </div>
        <div class="row-title row no-gutters my-2">企業資產狀況：</div>
        <div class="row no-gutters">
          <div class="col-12 col-item d-flex justify-content-between p-2">
            <div class="item-1">主要營業場所是否自有：</div>
            <div class="col d-flex align-items-center justify-content-end">
              <input
                type="radio"
                name="place-self"
                v-model="apiInfo.enterpriseInvestigation.assets.isMainBusinessAddressOwner"
                value="true"
              /><label for="">是</label>
              <input
                type="radio"
                name="place-self"
                v-model="apiInfo.enterpriseInvestigation.assets.isMainBusinessAddressOwner"
                value="false"
              /><label for="">否</label>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div class="item-1">所有權人</div>
            <input
              placeholder=""
              v-model="apiInfo.enterpriseInvestigation.assets.owner"
            />
          </div>
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div>營業場所設定</div>
            <div class="col d-flex align-items-center justify-content-end">
              <input
                type="radio"
                name="place-set"
                v-model="apiInfo.enterpriseInvestigation.assets.businessSet"
                value="true"
              /><label for="">無</label>
              <input
                type="radio"
                name="place-set"
                v-model="apiInfo.enterpriseInvestigation.assets.businessSet"
                value="false"
              /><label for="">有</label>
            </div>
          </div>
        </div>
        <div class="row no-gutters col-item">
          <div class="col-auto p-2 item-1">主要營業場所地址：</div>
          <div class="col p-2">
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.enterpriseInvestigation.assets.mainBusinessAddress"
            />
          </div>
        </div>
        <div class="row-title row no-gutters my-2">企業財務狀況：</div>
        <div class="row no-gutters">
          <div class="col-4 col-item p-2 text-center">
            <div>聯徵日期年月 ***</div>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <div>授信總餘額 ***</div>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <div>往來銀行家數 ***</div>
          </div>
        </div>
        <div
          class="row no-gutters"
          v-for="item in apiInfo.enterpriseInvestigation.companyFinance.loanList"
        >
          <div class="col-4 col-item d-flex p-2 justify-content-center">
            <input type="text" class="tiny-input" v-model="item.year" />
            <label class="px-1">年</label>
            <input type="text" class="tiny-input" v-model="item.month" />
            <label class="px-1">月</label>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <input type="text" v-model="item.amount">
          </div>
          <div class="col-4 col-item d-flex p-2 justify-content-center">
            <input type="text" class="tiny-input" v-model="item.bankNum" />
          </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-item d-flex justify-content-between p-2">
                <div class="item-1">票債信是否有異常紀錄</div>
                <div class="col d-flex align-items-center justify-content-end">
                    <input 
                        type="radio" 
                        name="place-self" 
                        v-model="apiInfo.enterpriseInvestigation.companyFinance.badRecord" 
                        value="true" 
                    />
                    <label>是</label>
                    <input 
                        type="radio" 
                        name="place-self"
                        v-model="apiInfo.enterpriseInvestigation.companyFinance.badRecord" 
                        value="false" 
                    />
                    <label>否</label>
                </div>
            </div>
        </div>
        <div class="row no-gutters col-item">
          <div class="col p-2 item-1">企業信用評分</div>
          <div class="col-4 p-2 d-flex">
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.enterpriseInvestigation.companyFinance.creditScore"
            />
            <label for="">分</label>
          </div>
        </div>
        <div class="row-title-2  row no-gutters my-2">(1) 獲利能力</div>
        <div class="row no-gutters">
            <div class="col-4 col-item p-2 text-center">
                <div>年度 ***</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>淨營業收入 ***</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>毛利率 ***</div>
            </div>
        </div>
        <div class="row no-gutters" v-for="(item, index) in apiInfo.enterpriseInvestigation.companyFinance.profitability">
            <div class="col-4 col-item d-flex p-2 justify-content-center">
                <input type="text" class="tiny-input" v-model="item.year" />
            </div>
            <div class="col-4 col-item p-2 text-center">
                <input type="text" v-model="item.income">
            </div>
            <div class="col col-item d-flex p-2 justify-content-center">
                <input type="text" v-model="item.grossMargin" />
                <div class="ml-3">
                    <button class="btn btn-1 btn-primary" v-on:click="apiInfo.enterpriseInvestigation.companyFinance.profitability.splice(index,1)">
                        -
                    </button>
                    <button class="btn btn-1 btn-secondary"
                        v-on:click="apiInfo.enterpriseInvestigation.companyFinance.profitability.splice(index+1,0,{...templates.profitability})">
                        +
                    </button>
                </div>
            </div>
        </div>
        <div class="row-title-2 row no-gutters my-2">(2) 經營能力</div>
        <div class="row no-gutters">
            <div class="col-4 col-item p-2 text-center">
                <div>年度</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>存貨週轉率</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>淨營業週轉天數</div>
            </div>
        </div>
        <div class="row no-gutters" v-for="(item, index) in apiInfo.enterpriseInvestigation.companyFinance.operationCapacity">
            <div class="col-4 col-item d-flex p-2 justify-content-center">
                <input type="text" class="tiny-input" v-model="item.year" />
            </div>
            <div class="col-4 col-item p-2 text-center">
                <input type="text" v-model="item.rate">
            </div>
            <div class="col col-item d-flex p-2 justify-content-center">
                <input type="text" v-model="item.day" />
                <div class="ml-2">天</div>	
                <div class="ml-2">
                    <button class="btn btn-1 btn-primary"
                        v-on:click="apiInfo.enterpriseInvestigation.companyFinance.operationCapacity.splice(index,1)">
                        -
                    </button>
                    <button class="btn btn-1 btn-secondary"
                        v-on:click="apiInfo.enterpriseInvestigation.companyFinance.operationCapacity.splice(index+1,0,{...templates.profitability})">
                        +
                    </button>
                </div>
            </div>
        </div>
        <div class="row-title-2  row no-gutters my-2">(3) 資本結構</div>
        <div class="row no-gutters">
            <div class="col-4 col-item p-2 text-center">
                <div>年度</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>營收比</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>負債比</div>
            </div>
        </div>
        <div class="row no-gutters" v-for="(item, index) in apiInfo.enterpriseInvestigation.companyFinance.capitalStructure">
            <div class="col-4 col-item d-flex p-2 justify-content-center">
                <input type="text" class="tiny-input" v-model="item.year" />
            </div>
            <div class="col-4 col-item p-2 text-center">
                <input type="text" v-model="item.psr">
            </div>
            <div class="col col-item d-flex p-2 justify-content-center">
                <input type="text" v-model="item.debtRatio" />
                <div class="ml-2">天</div>
                <div class="ml-2">
                    <button class="btn btn-1 btn-primary"
                        v-on:click="apiInfo.enterpriseInvestigation.companyFinance.capitalStructure.splice(index,1)">
                        -
                    </button>
                    <button class="btn btn-1 btn-secondary"
                        v-on:click="apiInfo.enterpriseInvestigation.companyFinance.capitalStructure.splice(index+1,0,{...templates.profitability})">
                        +
                    </button>
                </div>
            </div>
        </div>
        <div class="row-title row no-gutters my-2">負責人財務狀況：</div>
        <div class="row no-gutters">
          <div class="col-4 col-item p-2 text-center">
            <div>聯徵資料年月</div>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <div>授信總餘額</div>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <div>信用卡餘額總計</div>
          </div>
        </div>
        <div
          class="row no-gutters"
          v-for="item in apiInfo.enterpriseInvestigation.companyOwnerFinance.loanList"
        >
          <div class="col-4 col-item d-flex p-2 justify-content-center">
            <input type="text" class="tiny-input" v-model="item.year" />
            <label class="px-1">年</label>
            <input type="text" class="tiny-input" v-model="item.month" />
            <label class="px-1">月</label>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <input type="text" v-model="item.balance">
          </div>
          <div class="col-4 col-item d-flex p-2 justify-content-center">
            <input type="text" v-model="item.cardBalance" />
          </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-item d-flex justify-content-between p-2">
                <div class="item-1">票債信是否有異常紀錄</div>
                <div class="col d-flex align-items-center justify-content-end">
                    <input 
                        type="radio" 
                        name="place-self" 
                        v-model="apiInfo.enterpriseInvestigation.companyOwnerFinance.badRecord" 
                        value="true" 
                    />
                    <label>是</label>
                    <input 
                        type="radio" 
                        name="place-self" 
                        v-model="apiInfo.enterpriseInvestigation.companyOwnerFinance.badRecord" 
                        value="false" 
                    />
                    <label>否</label>
                </div>
            </div>
        </div>
        <div class="row no-gutters col-item">
          <div class="col p-2 item-1">個人信用評分</div>
          <div class="col-4 p-2 d-flex">
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.enterpriseInvestigation.companyOwnerFinance.creditScore"
            />
            <label>分</label>
          </div>
        </div>
      </div>
      <!-- 負責人配偶,保證人資訊 -->
      <div class="main" v-show="activeSec === 'sec-3'">
        <div class="row-title row no-gutters my-2 justify-content-between">
          負責人配偶資訊：
          <div class="d-flex row-title-input">
            <div>
              是否擔任保證人
              <input
                type="radio"
                name="guarantor-spouse"
                v-model="apiInfo.enterpriseAssociates.spouse.isGuarantor"
                value="true"
              /><label>是</label>
              <input
                type="radio"
                name="guarantor-spouse"
                v-model="apiInfo.enterpriseAssociates.spouse.isGuarantor"
                value="false"
              /><label>否</label>
            </div>
            <div class="ml-3">
              是否為實際負責人
              <input
                type="radio"
                name="actual-spouse"
                v-model="apiInfo.enterpriseAssociates.spouse.isRealOwner"
                value="true"
              /><label>是</label>
              <input
                type="radio"
                name="actual-spouse"
                v-model="apiInfo.enterpriseAssociates.spouse.isRealOwner"
                value="false"
              /><label>否</label>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div class="item-1">姓名：</div>
            <input
              type="text"
              v-model="apiInfo.enterpriseAssociates.spouse.name"
            />
          </div>
          <div class="col-6 col-item d-flex justify-content-between p-2">
            <div>統一編號：</div>
            <input
              type="text"
              v-model="apiInfo.enterpriseAssociates.spouse.identityNumber"
            />
          </div>
        </div>
        <div>
          <div class="row no-gutters col-item">
            <div class="col-auto p-2 item-1">戶籍地址：</div>
            <div class="col p-2">
              <input
                type="text"
                class="w-100"
                v-model="apiInfo.enterpriseAssociates.spouse.residenceAddress"
              />
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-6 col-item d-flex justify-content-between p-2">
              <div>出生年月日</div>
              <div class="col d-flex justify-content-end">
                <input
                  type="text"
                  class="tiny-input"
                  v-model="apiInfo.enterpriseAssociates.spouse.birthday.year"
                />
                <div class="px-1">年</div>
                <input
                  type="text"
                  class="tiny-input"
                  v-model="apiInfo.enterpriseAssociates.spouse.birthday.month"
                />
                <div class="px-1">月</div>
                <input
                  type="text"
                  class="tiny-input"
                  v-model="apiInfo.enterpriseAssociates.spouse.birthday.day"
                />
                <div class="px-1">日</div>
              </div>
            </div>
            <div class="col-6 col-item d-flex justify-content-between p-2">
              <div class="item-1">勞保薪資收入（月）:</div>
              <input
                type="text"
                v-model="apiInfo.enterpriseAssociates.spouse.salary"
              />
              <div class="pl-2">元</div>
            </div>
          </div>
          <div class="row no-gutters">
            <div class="col-6 col-item p-2 text-center">兼任公司名稱</div>
            <div class="col-6 col-item p-2 text-center">職稱</div>
          </div>
          <div
            class="row no-gutters col-item"
            id="sec-3-responsible-concurrently"
          >
            <div
              class="d-flex w-100"
              v-for="(item,index) in apiInfo.enterpriseAssociates.spouse.otherCompany"
            >
              <div class="col-6 p-2 text-center">
                <input type="text" v-model="item.companyName" />
              </div>
              <div class="col p-2 text-center">
                <input type="text" placeholder="" v-model="item.jobTitle" />
              </div>
              <div class="col-auto p-2">
                <button
                  class="btn btn-1 btn-primary"
                  v-on:click="apiInfo.enterpriseAssociates.spouse.otherCompany.splice(index,1)"
                >
                  -
                </button>
                <button
                  class="btn btn-1 btn-secondary"
                  v-on:click="apiInfo.enterpriseAssociates.spouse.otherCompany.splice(index+1,0,{...templates.otherCompany})"
                >
                  +
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters mt-3">
            <div class="col-4 col-item p-2 text-center">
              <div>聯徵資料年月</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
              <div>授信總餘額</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
              <div>信用卡餘額總計</div>
            </div>
        </div>
        <div
          class="row no-gutters"
          v-for="item in apiInfo.enterpriseAssociates.spouse.loanList"
        >
          <div class="col-4 col-item d-flex p-2 justify-content-center">
            <input type="text" class="tiny-input" v-model="item.year" />
            <label class="px-1">年</label>
            <input type="text" class="tiny-input" v-model="item.month" />
            <label class="px-1">月</label>
          </div>
          <div class="col-4 col-item p-2 text-center">
            <input type="text" v-model="item.balance">
          </div>
          <div class="col-4 col-item d-flex p-2 justify-content-center">
            <input type="text" v-model="item.cardBalance" />
          </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12 col-item d-flex justify-content-between p-2">
                <div class="item-1">票債信是否有異常紀錄</div>
                <div class="col d-flex align-items-center justify-content-end">
                    <input type="radio" name="place-self" v-model="apiInfo.enterpriseAssociates.spouse.badRecord" value="true" /><label for="">是</label>
                    <input type="radio" name="place-self" v-model="apiInfo.enterpriseAssociates.spouse.badRecord" value="false" /><label for="">否</label>
                </div>
            </div>
        </div>
        <div class="row no-gutters col-item">
          <div class="col p-2 item-1">個人信用評分</div>
          <div class="col-4 p-2 d-flex">
            <input
              type="text"
              class="w-100"
              v-model="apiInfo.enterpriseAssociates.spouse.creditScore"
            />
            <label for="">分</label>
          </div>
        </div>
        <!-- 保證人資訊 -->
        <div v-for="(item,index) in apiInfo.enterpriseAssociates.guarantorList">
          <div>
            <div class="row-title row no-gutters my-2 justify-content-between">
              <div class="d-flex">
                <div class="">保證人資訊：</div>
                <div class="col-auto">
                  <button
                    class="btn btn-1 btn-primary"
                    :disabled="index===0 && apiInfo.enterpriseAssociates.guarantorList.length < 2"
                    v-on:click="apiInfo.enterpriseAssociates.guarantorList.splice(index,1)"
                  >
                    -
                  </button>
                  <button
                    class="btn btn-1 btn-secondary"
                    v-on:click="apiInfo.enterpriseAssociates.guarantorList.splice(index+1,0,{...templates.guarantor})"
                  >
                    +
                  </button>
                </div>
              </div>
              <div class="d-flex row-title-input">
                <div>
                  是否為實際負責人
                  <input
                    type="radio"
                    name="guarantor-actual"
                    v-model="item.isRealOwner"
                    value="true"
                  />
                  <label>是</label>
                  <input
                    type="radio"
                    name="guarantor-actual"
                    v-model="item.isRealOwner"
                    value="false"
                  />
                  <label>否</label>
                </div>
              </div>
            </div>
            <div class="row no-gutters">
              <div class="col-6 col-item d-flex justify-content-between p-2">
                <div class="item-1">姓名：</div>
                <input type="text" v-model="item.name" />
              </div>
              <div class="col-6 col-item d-flex justify-content-between p-2">
                <div>統一編號：</div>
                <input type="text" v-model="item.identityNumber" />
              </div>
            </div>
            <div>
              <div class="row no-gutters col-item">
                <div class="col-auto p-2 item-1">戶籍地址：</div>
                <div class="col p-2">
                  <input
                    type="text"
                    class="w-100"
                    v-model="item.residenceAddress"
                  />
                </div>
              </div>
              <div class="row no-gutters">
                <div class="col-6 col-item d-flex justify-content-between p-2">
                  <div>出生年月日</div>
                  <div class="col d-flex justify-content-end">
                    <input
                      type="text"
                      class="tiny-input"
                      v-model="item.birthday.year"
                    />
                    <div class="px-1">年</div>
                    <input
                      type="text"
                      class="tiny-input"
                      v-model="item.birthday.month"
                    />
                    <div class="px-1">月</div>
                    <input
                      type="text"
                      class="tiny-input"
                      v-model="item.birthday.day"
                    />
                    <div class="px-1">日</div>
                  </div>
                </div>
                <div class="col-6 col-item d-flex justify-content-between p-2">
                  <div class="item-1">勞保薪資收入（月）:</div>
                  <input type="text" v-model="item.salary" />
                  <div class="pl-2">元</div>
                </div>
              </div>
              <div class="row no-gutters col-item p-2">
                <div class="col-2">與負責人關係：</div>
                <div class="col d-flex align-items-center">
                  <input
                    type="radio"
                    v-model="item.ownerShip"
                    :value="1"
                  />
                  <label for="">血親</label>
                  <input
                    type="radio"
                    v-model="item.ownerShip"
                    :value="2"
                  />
                  <label for="">姻親</label>
                  <input
                    type="radio"
                    v-model="item.ownerShip"
                    :value="3"
                  />
                  <label for="">股東</label
                  ><input
                    type="radio"
                    v-model="item.ownerShip"
                    :value="4"
                  />
                  <label for="">朋友</label
                  ><input
                    type="radio"
                    v-model="item.ownerShip"
                    :value="5"
                  />
                  <label for="">其他</label>
                  <input
                    type="radio"
                    v-model="item.ownerShip"
                    :value="6"
                  />
                  <label for="">與經營有關之公司職員</label>
                </div>
              </div>
              <div class="row no-gutters col-item p-2">
                <div class="col-2">任職公司：</div>
                <div class="col d-flex align-items-center">
                  <input
                    type="radio"
                    v-model="item.inCompanyType"
                    :value="1"
                  />
                  <label for="">公家機關</label>
                  <input
                    type="radio"
                    v-model="item.inCompanyType"
                    :value="2"
                  />
                  <label for="">上市櫃公司</label>
                  <input
                    type="radio"
                    v-model="item.inCompanyType"
                    :value="3"
                  />
                  <label for="">專業人士</label
                  ><input
                    type="radio"
                    v-model="item.inCompanyType"
                    :value="4"
                  />
                  <label for="">借戶</label
                  ><input
                    type="radio"
                    v-model="item.inCompanyType"
                    :value="5"
                  />
                  <label for="">其他民營企業</label>
                  <input
                    type="radio"
                    v-model="item.inCompanyType"
                    :value="6"
                  />
                  <label for="">無</label>
                </div>
              </div>
            </div>
            <div class="row no-gutters mt-3">
            <div class="col-4 col-item p-2 text-center">
                <div>聯徵資料年月</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>授信總餘額</div>
            </div>
            <div class="col-4 col-item p-2 text-center">
                <div>信用卡餘額總計</div>
            </div>
            </div>
            <div class="row no-gutters" v-for="x in item.loanList">
              <div class="col-4 col-item d-flex p-2 justify-content-center">
                <input type="text" class="tiny-input" v-model="x.year" />
                <label class="px-1">年</label>
                <input type="text" class="tiny-input" v-model="x.month" />
                <label class="px-1">月</label>
              </div>
              <div class="col-4 col-item p-2 text-center">
                <input type="text" v-model="x.balance" />
              </div>
              <div class="col-4 col-item d-flex p-2 justify-content-center">
                <input type="text" v-model="x.cardBalance" />
              </div>
            </div>
            <div class="row no-gutters">
                <div class="col-12 col-item d-flex justify-content-between p-2">
                    <div class="item-1">票債信是否有異常紀錄</div>
                    <div class="col d-flex align-items-center justify-content-end">
                        <input type="radio" name="place-self" v-model="item.badRecord" value="true" /><label for="">是</label>
                        <input type="radio" name="place-self" v-model="item.badRecord" value="false" /><label for="">否</label>
                    </div>
                </div>
            </div>
            <div class="row no-gutters col-item">
              <div class="col p-2 item-1">個人信用評分</div>
              <div class="col-4 p-2 d-flex">
                <input type="text" class="w-100" v-model="item.creditScore" />
                <label for="">分</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center mt-3">
        <button class="btn btn-lg btn-primary" v-on:click="saveDatas">
          送出
        </button>
      </div>
    </div>
  </body>
  <style>
    body {
      padding: 30px;
    }

    label {
      margin: 0 5px;
    }

    input {
      border-radius: 5px;
      border: 1px solid #adadad;
    }

    input::placeholder {
      text-align: center;
    }

    .main {
      width: 900px;
      margin: auto;
    }

    .header {
      width: 900px;
      margin: auto;
      border-bottom: 1px solid #000;
      margin-bottom: 15px;
    }

    .col-item {
      border: 1px solid #000;
    }

    .item-1 {
      width: 250px;
    }

    .tiny-input {
      width: 50px;
    }

    .title-row {
      padding-bottom: 2px;
      border-bottom: 1px solid #000;
    }

    .case-title {
      font-size: 32px;
      font-weight: bold;
    }

    .row-title {
      font-size: 24px;
      font-weight: bold;
    }
    
    .row-title-2{
        font-weight: bold;
        font-size: 20px;
        color: darkblue;
    }

    .row-title-tip {
      margin-left: 4px;
      margin-top: 8px;
      font-size: 14px;
      color: #f00;
    }

    .row-title-input {
      font-size: 14px;
      margin-top: 14px;
    }

    .case-item {
      margin-top: 26px;
      font-size: 10px;
    }

    .select-row {
      padding: 8px;
      border-bottom: 1px solid #000;
    }

    .btn-select {
      font-size: 16px;
      border-radius: 10px;
      border: 1px solid #adadad;
    }

    .btn-select.active {
      background-color: lightskyblue;
    }

    .btn-1 {
      padding: 0 6px;
      font-size: 16px;
    }
  </style>
  <script>
    const app = new Vue({
      el: '#app',
      data: {
        activeSec: 'sec-1',
        templates: {
          history: {
            year: '',
            month: '',
            day: '',
            companyOwner: '',
            capital: '',
            description: '',
          },
          teamList: {
            jobTitle: '',
            name: '',
            identityNumber: '',
          },
          otherCompany: {
            companyName: '',
            jobTitle: '',
          },
          guarantor: {
            isRealOwner: null,
            name: '',
            identityNumber: '',
            residenceAddress: '',
            birthday: {
              year: '',
              month: '',
              day: '',
            },
            salary: '',
            loanList: [
              {
                year: '',
                month: '',
                subjectName: '短期放款',
                amount: '',
              },
              {
                year: '',
                month: '',
                subjectName: '中期放款',
                amount: '',
              },
              {
                year: '',
                month: '',
                subjectName: '長期放款',
                amount: '',
              },
              {
                year: '',
                month: '',
                subjectName: '短期擔保放款',
                amount: '',
              },
              {
                year: '',
                month: '',
                subjectName: '中期擔保放款',
                amount: '',
              },
              {
                year: '',
                month: '',
                subjectName: '長期擔保放款',
                amount: '',
              },
            ],
            badRecord:false,
            creditScore: 0,
            ownerShip: 0,
            inCompanyType: 0,
          },
          profitability:{
               year: '',
              income: '',
              grossMargin: ''
          },
          operationCapacity:{
              year:'',
              rate:'',
              day:''
          },
          capitalStructure:{
            year: '',
            psr: '',
            debtRatio: ''
          }
        },
        apiInfo: {
          targetNo: '',
          createdAt: '',
          basicInfo: {
            companyName: '',
            taxIDNumber: '',
            companyRegisterAddress: '',
            companyBusinessAddress: '',
            capital: '',
            companySetDate: {
              year: '',
              month: '',
              day: '',
            },
            companyType: 0,
            companyScale: 0,
            numberOfEmployees: '',
            industryName: '',
            industryCode: '',
            history: [
              {
                year: '',
                month: '',
                day: '',
                companyOwner: '',
                capital: '',
                description: '',
              },
            ],
            team: {
              list: [
                {
                  jobTitle: '',
                  name: '',
                  identityNumber: '',
                },
              ],
              numberOfShareholders: '',
            },
            ownerInfo: {
              name: '',
              isGuarantor: null,
              isRealOwner: null,
              identityNumber: '',
              residenceAddress: '',
              birthday: {
                year: '',
                month: '',
                day: '',
              },
              startYear: '',
              otherCompany: [
                {
                  companyName: '',
                  jobTitle: '',
                },
              ],
            },
          },
          enterpriseInvestigation: {
            business: {
              mainBusiness: '',
              type: 0,
              plan: '',
              placeData: '',
            },
            assets: {
              isMainBusinessAddressOwner: null,
              owner: '',
              businessSet: null,
              mainBusinessAddress: '',
            },
            companyFinance: {
              creditScore: '',
                  badRecord:false,
              loanList: [
                {
                  year: '',
                  month: '',
                  amount: '',
                  bankNum:''
                },
              ],
              profitability:[
                  {
                      year:'',
                      income:'',
                      grossMargin:''
                  }
              ],
              operationCapacity:[
                {
                    year: '',
                    rate: '',
                    day: ''
                }
              ],
              capitalStructure:[
                {
                    year:'',
                    psr:'',
                    debtRatio:''
                }
              ],
              netOperatingRevenue: [
                {
                  year: '',
                  amount: '',
                },
                {
                  year: '',
                  amount: '',
                },
                {
                  year: '',
                  amount: '',
                },
              ],
              businessTaxApplyType: 0,
            },
            companyOwnerFinance: {
              creditScore: '',
              badRecord:false,
              loanList: [
                {
                  year: '',
                  month: '',
                  balance: '',
                  cardBalance: '',
                },
              ],
            },
          },
          enterpriseAssociates: {
            spouse: {
              isGuarantor: null,
              isRealOwner: null,
              name: '',
              identityNumber: '',
              residenceAddress: '',
              birthday: {
                year: '',
                month: '',
                day: '',
              },
              salary: '',
              otherCompany: [
                {
                  companyName: '',
                  jobTitle: '',
                },
              ],
              loanList: [
                {
                  year: '',
                  month: '',
                  balance: '',
                  cardBalance: '',
                },
              ],
              badRecord:false,
              creditScore: '',
            },
            guarantorList: [
              {
                isRealOwner: null,
                name: '',
                identityNumber: '',
                residenceAddress: '',
                birthday: {
                  year: '',
                  month: '',
                  day: '',
                },
                salary: '',
                loanList: [
                  {
                    year: '',
                      month: '',
                      balance: '',
                      cardBalance: '',
                  },
                ],
                badRecord:false,
                creditScore: '',
                ownerShip: 0,
                inCompanyType: 0,
              },
            ],
          },
        },
      },
      mounted() {
        // get data
        this.getDatas();
      },
      methods: {
        change(activeSec) {
          this.activeSec = activeSec;
        },
        getDatas() {
          const url_string = window.location.href;
          const url_object = new URL(url_string);
          const target_id = url_object.searchParams.get("target_id");

          const url = `/admin/CertificationReport/get_data?target_id=${target_id}`;
          const isObject = (item) => {
            return item && typeof item === 'object' && !Array.isArray(item);
          };
          const merge = (target, source) => {
            let output = Object.assign({}, target);
            if (isObject(target) && isObject(source)) {
              Object.keys(source).forEach((key) => {
                if (isObject(source[key])) {
                  if (!(key in target))
                    Object.assign(output, { [key]: source[key] });
                  else output[key] = merge(target[key], source[key]);
                } else {
                  Object.assign(output, { [key]: source[key] });
                }
              });
            }
            return output;
          };

            axios.get(url).then(({ data }) => {
              if(data.status.code == 200 && data.response){
                this.apiInfo = merge(this.apiInfo, data.response);
              }else{
                alert(data.response[0]);
              }
            });
        },
        saveDatas() {
          const url = '/admin/CertificationReport/send_data';
          const url_string = window.location.href;
          const url_object = new URL(url_string);
          const target_id = url_object.searchParams.get("target_id");
            axios
              .post(url, {
                report_data: this.apiInfo,
                target_id: target_id
              })
              .then(({ data }) => {
                //after post
                alert(data.response[0]);
              });
        },
      },
    });
  </script>
</html>
