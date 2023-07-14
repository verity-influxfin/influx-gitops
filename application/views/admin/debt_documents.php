<div id="page-wrapper">

    <div id="addTargetModal" class="modal"> 
        <div class="modal-content">
            <span class="close">X</span>
            <h3>添加案件</h3>
            <hr class="solid">

            <div class="panel-heading" style="background-color: #ddd;">
                <div class="row mt-2">
                    <div class="col-md-3 mt-2">
                        *搜尋：
                    </div>
                    <div class="col-md-9">
                        <input
                            type="text" 
                            class="insertInput"
                            placeholder="案號/會員編號/借款人姓名" 
                            v-model="searchTargetKeyword"
                        >
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <button @click="searchTargets()">查詢</button>
                        <button class="ml-2" @click="searchTargetKeyword=''">清空</button>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>請選擇</th>
                                <th>案號</th>
                                <th>申請日期</th>
                                <th>放款日期</th>
                                <th>放款金額</th>
                                <th>會員ID</th>
                                <th>會員姓名</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in targetsList" :key="item.target_id">
                                <td>
                                    <input 
                                        type="checkbox"
                                        @click="addTargets(item)"
                                    >
                                </td>
                                <td>{{ item.target_no }}</td>
                                <td>{{ item.create_date }}</td>
                                <td>{{ item.loan_date }}</td>
                                <td>{{ item.amount }}</td>
                                <td>{{ item.user_id }}</td>
                                <td>{{ item.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    請選擇案件分類：
                </div>
                <div class="col-md-9">
                    <input
                        class="ml-3"
                        type="radio"
                        name="group1"
                        id="item1"
                        value="1"
                        v-model="targetType"
                    >
                    <label for="item1">一般消費借貸</label>
                    <input 
                        class="ml-3"
                        type="radio"
                        name="group1"
                        id="item2"
                        value="2"
                        v-model="targetType"
                    >
                    <label for="item2">存資融資</label>
                    <input 
                        class="ml-3"
                        type="radio" 
                        name="group1" 
                        id="item3" 
                        value="3"
                        v-model="targetType"
                    >
                    <label for="item3">不動產抵押信託（不動產二胎）</label>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <button class="cancelBtn" @click="cancelModal()">取消</button>
                    <button class="addBtn ml-2" @click="insertTargets()">確定新增</button>
                </div>
            </div>
        </div>
    </div>

    <div id="uploadModal" class="modal"> 
        <div class="modal-content">
            <span class="close">X</span>
            <h3>請選擇PDF / 圖片</h3>
            <hr class="solid">

            <div class="modal-card mb-3">
                <input 
                    id="fileInput" 
                    type="file" 
                    @change="uploadFile" 
                    ref="file" 
                    multiple
                >
                <div id="fileNames"></div>
                <button class="cancelBtn mt-5" @click="cancelModal()">取消</button>
                <button class="addBtn ml-2 mt-5" @click="confirmUpload('file')">確認上傳</button>
            </div>
        </div>
    </div>

    <div id="infoModal" class="modal"> 
        <div class="modal-content">
            <span class="close">X</span>
            <h3>{{ infoTitle }}</h3>
            <hr class="solid">

            <div class="modal-card mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <label>案件類型</label>
                    </div>
                    <div class="col-md-2">
                        <template v-if="infoData.type == 1">一般消費借貸</template>
                        <template v-if="infoData.type == 2">存資融資</template>
                        <template v-if="infoData.type == 3">不動產抵押信託(不動產二胎)</template>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>案號</label>
                    </div>
                    <div class="col-md-10">
                        {{ infoData.target_no }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>會員ID</label>
                    </div>
                    <div class="col-md-10">
                        {{ infoData.user_id }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>會員姓名</label>
                    </div>
                    <div class="col-md-10">
                        {{ infoData.name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>申請日期</label>
                    </div>
                    <div class="col-md-10">
                        {{ infoData.create_date }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>放款日期</label>
                    </div>
                    <div class="col-md-10">
                        {{ infoData.loan_date }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>放款金額</label>
                    </div>
                    <div class="col-md-10">
                        {{ infoData.amount }}
                    </div>
                </div>

                <div class="modal-card mt-3">
                    <h5 style="font-weight: 700;">PDF檔案 / 圖片</h4>
                    <template v-for="item in infoFiles">
                        <div class="row mt-2 ml-2" v-if="item.includes('pdf')">
                            <a :href="item">{{ item.split('/')[item.split('/').length -1] }}</a>
                        </div>
                        <div class="row mt-2 ml-2" v-else>
                            <img 
                                :src="item" 
                                style="width: 50%;"
                            />  
                        </div>
                    </template>
                    <div class="row mt-2 ml-2">
                        <input 
                            id="fileInput" 
                            type="file" 
                            ref="refile" 
                            multiple
                        >
                    </div>
                    <div class="row mt-2 ml-2">
                        <label style="color: red;">*注意！重新上傳後將覆蓋新檔案！</label>
                    </div>
                    <button class="addBtn ml-2 mt-2" @click="confirmUpload('refile')">確認重新上傳</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">企金紙本債權文件存檔</h1>
        </div>
        <div class="col-md-8">
            <button 
                class="addTargetBtn" 
                @click="addTargetModal()"
            >添加案件</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li role="presentation" class="active">
                    <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true" @click="tab='tab1'">一般消費借貸</a>
                </li>
                <li role="presentation">
                    <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false" @click="tab='tab2'">存資融資</a>
                </li>
                <li role="presentation">
                    <a role="tab" data-toggle="tab" aria-controls="test3" aria-expanded="false" @click="tab='tab3'">不動產抵押信託</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td> 
                        案號： 
                    </td>
                    <td>
                        <input 
                            type="" 
                            value="" 
                            placeholder="請輸入"
                            v-model="searchDebtData.target_no"
                        />
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        上傳狀態：
                    </td>
                    <td>
                        <select 
                            style="width: 180px; height:27px;"
                            v-model="searchDebtData.status"
                        >
                            <option value="">請選擇</option>
                            <option value="0">未完成上傳</option>
                            <option value="1">已完成上傳</option>
                        </select>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button @click="searchDebtDocs()">查詢</button>
                    </td>
                    <td>
                        <button @click="clearSearchDebt()">清空</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5" v-show="tab == 'tab1'">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>案號</th>
                            <th>上傳狀態</th>
                            <th>會員ID</th>
                            <th>會員姓名</th>
                            <th>申請日期</th>
                            <th>放款日期</th>
                            <th>放款金額</th>
                            <th>本票</th>
                            <th>借貸契約</th>
                            <th>動撥申請書</th> 
                            <th>案件資訊</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in debtDocsList" :key="item.id">
                            <template v-if="item.type == 1">
                                <td>{{ item.target_no }}</td>
                                <template v-if="item.upload_status == 0">
                                    <td>未完成上傳</td>
                                </template>
                                <template v-if="item.upload_status == 1">
                                    <td>已完成上傳</td>
                                </template>
                                <td>{{ item.user_id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.create_date }}</td>
                                <td>{{ item.loan_date }}</td>
                                <td>{{ item.amount }}</td>
                                <td>
                                    <template v-if="item.document.cashier_cheque.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'cashier_cheque')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'cashier_cheque')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.loan_contract.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'loan_contract')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'loan_contract')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.drawdown_request.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'drawdown_request')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'drawdown_request')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <button @click="toTargetPage(item.target_id)">案件資訊</button>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel-body mt-5" v-show="tab == 'tab2'">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>案號</th>
                            <th>上傳狀態</th>
                            <th>會員ID</th>
                            <th>會員姓名</th>
                            <th>申請日期</th>
                            <th>放款日期</th>
                            <th>放款金額</th>
                            <th>本票</th>
                            <th>買賣契約書（借款人賣出）</th>
                            <th>買賣契約書（借款人買回）</th>
                            <th>動撥申請書</th>
                            <th>案件資訊</th>
                         </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in debtDocsList" :key="item.id">
                            <template v-if="item.type == 2">
                                <td>{{ item.target_no }}</td>
                                <template v-if="item.upload_status == 0">
                                    <td>未完成上傳</td>
                                </template>
                                <template v-if="item.upload_status == 1">
                                    <td>已完成上傳</td>
                                </template>
                                <td>{{ item.user_id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.create_date }}</td>
                                <td>{{ item.loan_date }}</td>
                                <td>{{ item.amount }}</td>
                                <td>
                                    <template v-if="item.document.cashier_cheque.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'cashier_cheque')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'cashier_cheque')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.sell_contract.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'sell_contract')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'sell_contract')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.buy_contract.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'buy_contract')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'buy_contract')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.drawdown_request.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'drawdown_request')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'drawdown_request')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <button>案件資訊</button>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="panel-body mt-5" v-show="tab == 'tab3'">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>案號</th>
                            <th>上傳狀態</th>
                            <th>會員ID</th>
                            <th>會員姓名</th>
                            <th>申請日期</th>
                            <th>放款日期</th>
                            <th>放款金額</th>
                            <th>本票</th>
                            <th>借貸契約</th>
                            <th>信託契約</th>
                            <th>他項權利證明書</th>
                            <th>動撥申請書</th>
                            <th>原始債權本票</th>
                            <th>原始債權契約 </th>
                            <th>債權讓與通知書</th>
                            <th>原他項權利證明書</th>
                            <th>土地建物謄本</th>
                            <th>案件資訊</th> 
                            </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in debtDocsList" :key="item.id">
                            <template v-if="item.type == 3">
                                <td>{{ item.target_no }}</td>
                                <template v-if="item.upload_status == 0">
                                    <td>未完成上傳</td>
                                </template>
                                <template v-if="item.upload_status == 1">
                                    <td>已完成上傳</td>
                                </template>
                                <td>{{ item.user_id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.create_date }}</td>
                                <td>{{ item.loan_date }}</td>
                                <td>{{ item.amount }}</td>
                                <td>
                                    <template v-if="item.document.cashier_cheque.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'cashier_cheque')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'cashier_cheque')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.loan_contract.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'loan_contract')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'loan_contract')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.trust_deed.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'trust_deed')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'trust_deed')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.other_rights_certificate.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'other_rights_certificate')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'other_rights_certificate')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.drawdown_request.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'drawdown_request')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'drawdown_request')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.original_cheque.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'original_cheque')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'original_cheque')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.original_debt_contract.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'original_debt_contract')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'original_debt_contract')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.transfer_notice.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'transfer_notice')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'transfer_notice')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.original_other_rights_certificate.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'original_other_rights_certificate')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'original_other_rights_certificate')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <template v-if="item.document.land_building_transcriptions.length > 0">
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-circle"
                                            @click="openInfo(item, 'land_building_transcriptions')"
                                        ><i class="fa fa-check"></i></button>
                                    </template>
                                    <template v-else>
                                        <button
                                            @click="startUpload(item.id, 'land_building_transcriptions')"
                                        >上傳文件</button>
                                    </template>
                                </td>
                                <td>
                                    <button>案件資訊</button>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
var p2p_orm_host = '<?php print_r(getenv('ENV_P2P_ORM_HTTPS_HOST'))?>';

$(document).ready(function () {
    var modal = document.getElementById('addTargetModal');
    var modal1 = document.getElementById('uploadModal');
    var modal2 = document.getElementById('infoModal');
    var span = document.getElementsByClassName('close')[0];
    var span1 = document.getElementsByClassName('close')[1];
    var span2 = document.getElementsByClassName('close')[2];
    
    span.onclick = function() {
        modal.style.display = 'none';
    }
    span1.onclick = function() {
        modal1.style.display = 'none';
    }
    span2.onclick = function() {
        modal2.style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == modal || event.target == modal1 || event.target == modal2) {
            modal.style.display = 'none';
            modal1.style.display = 'none';
            modal2.style.display = 'none';
        }
    }
})

const v = new Vue({
    el: '#page-wrapper',
    data() {
        return {
            tab: 'tab1',
            searchTargetKeyword: '',
            targetsList: [],
            targetType: '',
            insertTargetsList: [],
            searchDebtData: {
                target_no: '',
                status: ''
            },
            debtDocsList: [],
            files: null,
            uploadId: null,
            uploadColumn: null,
            infoTitle: '',
            infoTitleMap: {
                cashier_cheque: '本票',
                loan_contract: '借貸契約',
                trust_deed: '信託契約',
                other_rights_certificate: '他項權利證明書',
                drawdown_request: '動撥申請書',
                sell_contract: '買賣契約書(借款人賣出)',
                buy_contract: '買賣契約書(借款人買回)',
                original_cheque: '原始債權本票',
                original_debt_contract: '原始債權契約',
                transfer_notice: '債權讓與通知書',
                original_other_rights_certificate: '原他項權利證明書',
                land_building_transcriptions: '土地建物謄本'
            },
            infoData: {},
            infoFiles: {}
        }
    },
    mounted() {
    },
    methods: {
        addTargetModal() {
            let modal = document.getElementById('addTargetModal');
            modal.style.display = 'block';
            this.searchTargetKeyword = '';
            this.targetsList = [];
            this.targetType = '';
            this.insertTargetsList = [];
        },
        searchTargets() {
            params = { query: this.searchTargetKeyword };
            axios.get(`${p2p_orm_host}/enterprise_document/targets`, { params: params })
            .then((res) => {
                this.targetsList = res.data;
            }).catch((err) => {
                console.log(err);
            })
        },
        addTargets(target) {
            if (this.containsObject(target, this.insertTargetsList)) {
                this.insertTargetsList.splice(this.insertTargetsList.findIndex((v) => v.target_id === target.target_id), 1);
            } else {
                this.insertTargetsList.push(target);
            }
        },
        containsObject(obj, list) {
            for (let i = 0; i < list.length; i++) {
                if (list[i] === obj) {
                    return true;
                }
            }
            return false;
        },
        cancelModal() {
            let modal = document.getElementById('addTargetModal');
            let modal1 = document.getElementById('uploadModal');
            modal.style.display = 'none';
            modal1.style.display = 'none';
        },
        insertTargets() {
            if (this.insertTargetsList.length == 0 || this.targetType == '') {
                alert('請選擇案件與案件分類');
            } else {
                var ipInfo;
                $.getJSON(`https://api.db-ip.com/v2/free/self`, function(data) {
                    ipInfo = data;
                });
                setTimeout(() => {
                    for (let i = 0; i < this.insertTargetsList.length; i ++) {
                        this.insertTargetsList[i]['type'] = this.targetType;
                        this.insertTargetsList[i]['created_ip'] = ipInfo.ipAddress;
                    }

                    axios.post(`${p2p_orm_host}/enterprise_document/insert`, this.insertTargetsList)
                    .then((res) => {
                        if (res.data == 'SUCCESS') {
                            alert('新增成功');
                        } else {
                            let target_no_str = '以下案件已添加至列表，添加失敗 案號：';
                            for (let i = 0; i < res.data.length; i ++) {
                                target_no_str = target_no_str + res.data[i].target_no + ', ';
                            }
                            alert(target_no_str);
                        }
                    }).catch((err) => {
                        console.log(err);
                    })
                }, 1000);
            }
        },
        searchDebtDocs() {
            let params = {}
            if (this.searchDebtData.target_no != '') {
                params['target_no'] = this.searchDebtData.target_no;
            }
            if (this.searchDebtData.status != '') {
                params['upload_status'] = parseInt(this.searchDebtData.status);
            }
            axios.get(`${p2p_orm_host}/enterprise_document/enterprise`, { params: params })
            .then((res) => {
                this.debtDocsList = res.data;
                console.log(this.debtDocsList);
            }).catch((err) => {
                console.log(err);
            })
        },
        clearSearchDebt() {
            this.searchDebtData.target_no = '';
            this.searchDebtData.status = '';
        },
        startUpload(tabelId, documentCol) {
            let modal = document.getElementById('uploadModal');
            modal.style.display = 'block';

            this.uploadId = tabelId;
            this.uploadColumn = documentCol;
        },
        uploadFile() {
            let files = document.getElementById('fileInput').files;
            let fileNamesDiv = document.getElementById('fileNames');
            fileNamesDiv.innerHTML = ""; // 清空显示区域

            for (var i = 0; i < files.length; i++) {
                let fileName = files[i].name;
                let span = document.createElement('span');
                let brElement = document.createElement('br')
                span.textContent = fileName;
                fileNamesDiv.appendChild(span);
                fileNamesDiv.appendChild(brElement);
            }
        },
        confirmUpload(type) {
            if (type == 'file') {
                this.files = this.$refs.file.files;
            } else if (type == 'refile') {
                this.files = this.$refs.refile.files;
            }
            const formData = new FormData();
            const headers = { 'Content-Type': 'multipart/form-data' };
            
            let ipInfo;
            $.getJSON('https://api.db-ip.com/v2/free/self', function(data) {
                ipInfo = data;
            });
            setTimeout(() => {
                let params = {
                    id: this.uploadId,
                    document_collumn: this.uploadColumn,
                    updated_ip: ipInfo.ipAddress
                }
                console.log(params);
                if (this.files != null) {
                    for (let i = 0; i < this.files.length; i ++) {
                        formData.append('files', this.files[i]);
                    }
                    axios.post(`${p2p_orm_host}/enterprise_document/uploadfiles`, formData, { params, headers })
                    .then((res) => {
                        alert('上傳成功');
                        let modal = document.getElementById('uploadModal');
                        modal.style.display = 'none';
                        modal = document.getElementById('infoModal');
                        modal.style.display = 'none';

                        this.searchDebtDocs();
                    }).catch((err) => {
                        console.log(err);
                    })
                }
            }, 1000);

        },
        openInfo(info, colName) {
            let modal = document.getElementById('infoModal');
            modal.style.display = 'block';

            this.infoTitle = this.infoTitleMap[colName];
            this.infoData = info;
            this.infoFiles = this.infoData.document[colName];
            this.uploadId = this.infoData.id;
            this.uploadColumn = colName;
        },
        toTargetPage(target_id) {
            window.open(`/admin/target/detail?id=${target_id}`);
        }
    }
})

</script>
<style>

.addTargetBtn {
    margin: 40px 0 20px; 
    background-color: cornflowerblue; 
    color: white;
    border-color: cornflowerblue;
    border-radius: 10px;
    width: 100px;
    height: 30px
}
h3 {
    font-weight: 700;
}
hr.solid {
    border-top: 2px solid #bbb;
}
.insertInput {
    width: 70%;
    border-radius: 4px;
}

.cancelBtn {
    background-color: red;
    border-color: red;
    border-radius: 4px;
    color: white;
}
.addBtn {
    background-color: cornflowerblue; 
    border-color: cornflowerblue;
    color: white;
    border-radius: 4px;
}

/* modal區塊 */
body { font-family: Arial, Helvetica, sans-serif; }
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    margin: 0 0 0 200px;
}
.modal-content {
    background-color: #fefefe;
    margin: 0 100px;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
}
.modal-card {
    border: 2px solid #bbb;
    border-radius: 2px;
    padding: 15px;
}
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close:hover, .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

</style>