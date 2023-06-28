<div id="page-wrapper">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">託收票據明細表</h1>
        </div>
        <div class="col-md-6">
            <button class="headerButton exportExcel" @click="exportExcel(true)">匯出完整excel</button>
            <button class="headerButton exportExcel" @click="exportExcel(false)">匯出部分excel</button>
            <button class="headerButton" id="addCheck" @click="addCheck()">新增託收票據</button>
            
        </div>
    </div>

    <div id="upsertModal" class="modal">
        <div class="modal-content">
            <span class="close">X</span>
            <h3 v-if="isUpsert">託收票據-編輯</h3>
            <h3 v-else>託收票據-新增</h3>
            
            <div class="modal-card mb-3" v-if="isUpsert">
                <div class="row">
                    <div class="col-md-12">
                        <label>票據ID：{{ upsertData.id }}</label>
                        <label class="ml-3">選擇編輯項目：</label>
                        <select class="insertSelect" v-model="updateType">
                            <option value="">請選擇</option>
                            <option value="modifyCheck">更改票據資訊</option>
                            <option value="renew">更新兌現結果</option>
                            <option value="retrieve">領回託收票據</option>
                            <option value="reCollect">託收票據</option>
                            <option value="stopTracking">結束追蹤票據</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>會員ID：{{ upsertData.user_id }}</label>
                        <label class="ml-3">案號：{{ upsertData.target_no }}</label>
                        <label class="ml-3">期數：{{ upsertData.instalment }}</label>
                        <label class="ml-3">發票人：{{ upsertData.cheque_drawer }}</label>
                        <label class="ml-3">發票號碼：{{ upsertData.cheque_no }}</label>
                        <label class="ml-3">發票金額：{{ upsertData.cheque_amount }}</label>
                    </div>
                </div>
            </div>

            <div class="modal-card mb-3" v-if="updateType == 'renew'">
                <div class="row">
                    <div class="col-md-12">
                        <label>是否已兌現：</label>
                        <select class="insertSelect" v-model="upsertData.cash_status">
                            <option value="">請選擇</option>
                            <option value="1">兌現成功</option>
                            <option value="2">兌現失敗</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3" v-if="upsertData.cash_status == 1">
                        <label>入帳日：</label>
                        <input
                            type="date" 
                            class="insertInput" 
                            v-model="upsertData.posting_date"
                            value="null"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important; width: 120px;"
                        >
                    </div>
                    <div class="col-md-3" v-if="upsertData.cash_status == 2">
                        <label>領回日期：</label>
                        <input
                            type="date"
                            class="insertInput" 
                            v-model="upsertData.retrieve_date"
                            value="null"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important; width: 120px;"
                        >
                    </div>
                </div>
                <div class="row mt-1" v-if="upsertData.cash_status == 2">
                    <div class="col-md-12">
                        <label style="float: left;">未兌現原因：</label>
                        <textarea 
                            class="remarkArea"
                            v-model="upsertData.outstanding_reason"
                            rows="8" 
                            cols="60"
                        ></textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12" style="text-align: end;">
                        <button class="cancelBtn" @click="cancelForm()">取消</button>
                        <button @click="submitEditForm(upsertData.id)" class="submitBtn">送出</button>
                    </div>
                </div>
            </div>

            <div class="modal-card mb-3" v-if="updateType == 'retrieve'">
                <div class="row">
                    <div class="col-md-12">
                        <label>領回日期：</label>
                        <input
                            type="date" 
                            class="insertInput" 
                            v-model="upsertData.retrieve_date"
                            value="null"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important; width: 120px;"
                        >
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <label style="float: left;">領回原因：</label>
                        <textarea 
                            class="remarkArea"
                            v-model="upsertData.retrieve_reason"
                            rows="8" 
                            cols="60"
                        ></textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12" style="text-align: end;">
                        <button class="cancelBtn" @click="cancelForm()">取消</button>
                        <button @click="submitEditForm(upsertData.id)" class="submitBtn">送出</button>
                    </div>
                </div>
            </div>

            <div class="modal-card mb-3" v-if="updateType == 'reCollect'">
                <div class="row">
                    <div class="col-md-4">
                        <label>託收銀行：</label>
                        <select class="insertSelect" v-model="upsertData.collection_bank" @change="selectCollectBank()">
                            <option value="">請選擇</option>
                            <template v-for="(value, key, index) in bankArr">
                                <option :value="key">{{ value.bank_name }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>託收分行：</label>
                        <select class="insertSelect" v-model="upsertData.collection_branch">
                            <option value="">請選擇</option>
                            <template v-for="(value, key, index) in collectBranchArr">
                                <option :value="key">{{ value }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>受款人帳號：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.payee_account"
                        >
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <label>受款人戶名：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.payee"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>託收日：</label>
                        <input
                            type="date" 
                            class="insertInput" 
                            v-model="upsertData.collection_date"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important; width: 120px;"
                        >
                    </div>
                </div>
              
                <div class="row mt-2">
                    <div class="col-md-12" style="text-align: end;">
                        <button class="cancelBtn" @click="cancelForm()">取消</button>
                        <button @click="submitEditForm(upsertData.id)" class="submitBtn">送出</button>
                    </div>
                </div>
            </div>

            <div class="modal-card mb-3" v-if="updateType == 'stopTracking'">
                <div class="row">
                    <div class="col-md-12">
                        <label>結束追蹤日期：</label>
                        <input
                            type="date" 
                            class="insertInput" 
                            v-model="upsertData.stop_tracking"
                            value="null"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important; width: 120px;"
                        >
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <label style="float: left;">領結束追蹤原因：</label>
                        <textarea 
                            class="remarkArea"
                            v-model="upsertData.endtrack_reason"
                            rows="8" 
                            cols="60"
                        ></textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12" style="text-align: end;">
                        <button class="cancelBtn" @click="cancelForm()">取消</button>
                        <button @click="submitEditForm(upsertData.id)" class="submitBtn">送出</button>
                    </div>
                </div>
            </div>
    
            <div class="modal-card" v-if="isUpsert == false || updateType == 'modifyCheck'">
                <div class="row">
                    <div class="col-md-3">
                        <label>發票會員ID：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.user_id"
                        >
                    </div>
                    <div class="col-md-1">
                        <button @click="searchTargetNo(upsertData.user_id)">查詢</button>
                    </div>
                    <div class="col-md-4">
                        <label>發票人：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.cheque_drawer"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>案號：</label>
                        <select class="insertSelect" v-model="upsertData.target_no">
                            <option value="">請選擇</option>
                            <template v-for="target_no in targetsList">
                                <option :value="target_no">{{ target_no }}</option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>發票人帳號：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.drawer_bankaccout"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>付款銀行：</label>
                        <select class="insertSelect" v-model="upsertData.payment_bank" @change="selectPaymentBank()">
                            <option value="">請選擇</option>
                            <template v-for="(value, key, index) in bankArr">
                                <option :value="key">{{ value.bank_name }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>付款分行：</label>
                        <select class="insertSelect" v-model="upsertData.payment_branch">
                            <option value="">請選擇</option>
                            <template v-for="(value, key, index) in paymentBranchArr">
                                <option :value="key">{{ value }}</option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>票據號碼：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.cheque_no"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>票據金額：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.cheque_amount"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>票據到期日：</label>
                        <input
                            type="date" 
                            class="insertInput" 
                            v-model="upsertData.cheque_due_date"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important;"
                        >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>期數：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.instalment"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>禁背轉票據：</label>
                        <select class="insertSelect" v-model="upsertData.is_nonnegotiable">
                            <option value="">請選擇</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>個人票：</label>
                        <select class="insertSelect" v-model="upsertData.is_personal">
                            <option value="">請選擇</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-4">
                        <label>託收銀行：</label>
                        <select class="insertSelect" v-model="upsertData.collection_bank" @change="selectCollectBank()">
                            <option value="">請選擇</option>
                            <template v-for="(value, key, index) in bankArr">
                                <option :value="key">{{ value.bank_name }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>託收分行：</label>
                        <select class="insertSelect" v-model="upsertData.collection_branch">
                            <option value="">請選擇</option>
                            <template v-for="(value, key, index) in collectBranchArr">
                                <option :value="key">{{ value }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>託收日：</label>
                        <input
                            type="date" 
                            class="insertInput" 
                            v-model="upsertData.collection_date"
                            min="2023-01-01" 
                            max="2030-12-31"
                            style="line-height:20px !important;"
                        >
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-4">
                        <label>收款會員ID：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.payee_id"
                        >
                    </div>
                    <!-- <div class="col-md-4">
                        <label>收款人戶名：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.payee"
                        >
                    </div>
                    <div class="col-md-4">
                        <label>收款帳號：</label>
                        <input
                            type="text" 
                            class="insertInput" 
                            v-model="upsertData.payee_account"
                        >
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label style="float: left;">備註：</label>
                        <textarea 
                            class="remarkArea"
                            v-model="upsertData.remark"
                            rows="8" 
                            cols="60"
                        ></textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label style="float: left;">票據圖片：</label>
                        <input type="file" @change="uploadFile" ref="file">
                        <img v-if="!editImage" :src="upsertData.image">
                    </div>
                    <div class="col-md-6" style="text-align: end;">
                        <button class="cancelBtn" @click="cancelForm()">取消</button>
                        <button v-if="!isUpsert" class="submitBtn" @click="submitForm()">送出</button>
                        <button v-else class="submitBtn" @click="submitEditForm(upsertData.id)">送出</button>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
    
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close">X</span>
            <template v-for="item in detailData">
                <template v-if="item.review_status == 0 && isSupervisor">
                    <div class="row mb-3" style="display: flex;">
                        <h3 style="width: 20%;">審核票據資訊</h3>
                        <button 
                            style="
                                display: flex;
                                justify-content: center;
                                padding: 12px 14px;
                                width: 124.5px;
                                background: #4CBF7A;
                                color:#fefefe;
                                border-radius: 4px;
                                border-color: #4CBF7A;
                                font-size: 16px;"
                            @click="approveModify(item, true)"
                        >核可</button>
                        <button 
                            style="
                                display: flex;
                                justify-content: center;
                                padding: 12px 14px;
                                width: 124.5px;
                                background: #DB2F2F;
                                color:#fefefe;
                                border-radius: 4px;
                                border-color: #DB2F2F;
                                font-size: 16px;
                                margin-left: 10px;"
                            @click="approveModify(item, false)"
                        >退回</button>
                    </div>
                    <div v-if="item.edit_status == 0" class="editBar">刪除</div>
                    <div v-else-if="item.edit_status == 1" class="editBar">新增</div>
                    <div v-else-if="item.edit_status == 2" class="editBar">更新票據資訊</div>
                    <div v-else-if="item.edit_status == 3" class="editBar">更新兌現結果</div>
                    <div v-else-if="item.edit_status == 4" class="editBar">領回託收票據</div>
                    <div v-else-if="item.edit_status == 5" class="editBar">託收票據</div>
                    <div v-else>結束追蹤票據</div>

                    <div class="row" style="width: 80%; margin: auto;">
                        <div class="col-md-3 detail_divide">
                            <div class="row">
                                <label class="left-label">會員ID</label>
                                <label class="right-label">{{ item.content.user_id }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">發票人</label>
                                <label class="right-label">{{ item.content.cheque_drawer }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">付款銀行</label>
                                <label class="right-label" v-if="item.content.payment_bank">{{ bankArr[item.content.payment_bank]['bank_name'] + '-' + bankArr[item.content.payment_bank]['branch'][item.content.payment_branch]}}</label>
                                <!-- bankArr[item.content.payment_bank]['bank_name'] -->
                            </div>
                            <div class="row">
                                <label class="left-label">發票人帳號</label>
                                <label class="right-label">{{ item.content.drawer_bankaccout }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">票據到期日</label>
                                <label class="right-label">{{ item.content.cheque_due_date }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">狀態</label>
                                <label class="right-label" v-if="item.content.status == 0">標記刪除</label>
                                <label class="right-label" v-else-if="item.content.status == 1">正常（託收中）</label>
                                <label class="right-label" v-else-if="item.content.status == 2">已領回</label>
                                <label class="right-label" v-else-if="item.content.status == 3">未託收</label>
                                <label class="right-label" v-else>已結束</label>
                            </div>
                            
                        </div>
                        
                        <div class="col-md-3 detail_divide">
                            <div class="row">
                                <label class="left-label">案號</label>
                                <label class="right-label">{{ item.content.target_no }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">期數</label>
                                <label class="right-label">{{ item.content.instalment }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">票據號碼</label>
                                <label class="right-label">{{ item.content.cheque_no }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">票據金額</label>
                                <label class="right-label">{{ item.content.cheque_amount }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">禁背轉票據</label>
                                <label class="right-label" v-if="item.content.is_nonnegotiable == 1">是</label>
                                <label class="right-label" v-else>否</label>
                            </div>
                            <div class="row">
                                <label class="left-label">個人票</label>
                                <label class="right-label" v-if="item.content.is_personal == 1">是</label>
                                <label class="right-label" v-else>否</label>
                            </div>
                        </div>

                        <div class="col-md-3 detail_divide">
                            <div class="row">
                                <label class="left-label">受款人會員ID</label>
                                <label class="right-label">{{ item.content.payee_id }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">受款人戶名</label>
                                <label class="right-label">{{ item.content.payee }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">受款人帳號</label>
                                <label class="right-label">{{ item.content.payee_account }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">託收銀行</label>
                                <label class="right-label" v-if="item.content.collection_bank">{{ bankArr[item.content.collection_bank]['bank_name'] + '-' + bankArr[item.content.collection_bank]['branch'][item.content.collection_branch] }}</label>
                                <!-- bankArr[item.content.collection_bank]['bank_name'] -->
                            </div>
                            <div class="row">
                                <label class="left-label">託收日</label>
                                <label class="right-label">{{ item.content.collection_date }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">入帳日</label>
                                <label class="right-label">{{ item.content.posting_date }}</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            
                            <div class="row">
                                <label class="left-label">是否已兌現</label>
                                <label class="right-label" v-if="item.content.cash_status == 1">兌現成功</label>
                                <label class="right-label" v-else-if="item.content.cash_status == 2">兌現失敗</label>
                                <label class="right-label" v-else>確認中</label>
                            </div>
                            <div class="row">
                                <label class="left-label">未兌現原因</label>
                                <label class="right-label">{{ item.content.outstanding_reason }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">領回日期</label>
                                <label class="right-label">{{ item.content.retrieve_date }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">領回原因</label>
                                <label class="right-label">{{ item.content.retrieve_reason }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">結束追蹤日期</label>
                                <label class="right-label">{{ item.content.stop_tracking }}</label>
                            </div>
                            <div class="row">
                                <label class="left-label">備註</label>
                                <label class="right-label">{{ item.content.remark }}</label>
                            </div>
                            
                        </div>
                    </div>

                </template>
            </template>

            <h3>票據更動紀錄</h3>
            <div id="detail_wrapper">
                <table 
                    id="table_detail" 
                    align=center 
                    border=1
                >
                    <tr style="background: grey; color:#fefefe;">
                        <th>編輯項目</th>
                        <th>更改日期</th>
                        <th>編輯人員</th>
                        <th>票據資訊</th>
                    </tr>
                    
                    <template v-for="item in detailData">
                        <template v-if="item.review_status == 1">
                            <tr @click="showHideRow('hidden_row' + item.index);">
                                <td v-if="item.edit_status == 1">新增</td>
                                <td v-else-if="item.edit_status == 2">更新票據資訊</td>
                                <td v-else-if="item.edit_status == 3">更新兌現結果</td>
                                <td v-else-if="item.edit_status == 4">領回託收票據</td>
                                <td v-else-if="item.edit_status == 5">託收票據</td>
                                <td v-else-if="item.edit_status == 6">結束追蹤票據</td>
                                <td v-else>刪除</td>
                                <td>{{ item.date }}</td>
                                <td>{{ item.admin }}</td>
                                <td>請點擊該欄</td>
                            </tr>    
                            <tr :id="'hidden_row' + item.index" class="hidden_row">
                                <td colspan=4>
                                    <div class="row">
                                        <div class="col-md-3 detail_divide">
                                            <div class="row">
                                                <label class="left-label">會員ID</label>
                                                <label class="right-label">{{ item.content.user_id }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">發票人</label>
                                                <label class="right-label">{{ item.content.cheque_drawer }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">付款銀行</label>
                                                <label class="right-label" v-if="item.content.payment_bank">{{ bankArr[item.content.payment_bank]['bank_name'] + '-' + bankArr[item.content.payment_bank]['branch'][item.content.payment_branch]}}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">發票人帳號</label>
                                                <label class="right-label">{{ item.content.drawer_bankaccout }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">票據到期日</label>
                                                <label class="right-label">{{ item.content.cheque_due_date }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">狀態</label>
                                                <label class="right-label" v-if="item.content.status == 0">標記刪除</label>
                                                <label class="right-label" v-else-if="item.content.status == 1">正常（託收中）</label>
                                                <label class="right-label" v-else-if="item.content.status == 2">已領回</label>
                                                <label class="right-label" v-else-if="item.content.status == 3">未託收</label>
                                                <label class="right-label" v-else>已結束</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 detail_divide">
                                            <div class="row">
                                                <label class="left-label">案號</label>
                                                <label class="right-label">{{ item.content.target_no }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">期數</label>
                                                <label class="right-label">{{ item.content.instalment }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">票據號碼</label>
                                                <label class="right-label">{{ item.content.cheque_no }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">票據金額</label>
                                                <label class="right-label">{{ item.content.cheque_amount }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">禁背轉票據</label>
                                                <label class="right-label" v-if="item.content.is_nonnegotiable == 1">是</label>
                                                <label class="right-label" v-else>否</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">個人票</label>
                                                <label class="right-label" v-if="item.content.is_personal == 1">是</label>
                                                <label class="right-label" v-else>否</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3 detail_divide">
                                            <div class="row">
                                                <label class="left-label">受款人會員ID</label>
                                                <label class="right-label">{{ item.content.payee_id }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">受款人戶名</label>
                                                <label class="right-label">{{ item.content.payee }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">受款人帳號</label>
                                                <label class="right-label">{{ item.content.payee_account }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">託收銀行</label>
                                                <label class="right-label" v-if="item.content.collection_bank">{{ bankArr[item.content.collection_bank]['bank_name'] + '-' + bankArr[item.content.collection_bank]['branch'][item.content.collection_branch] }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">託收日</label>
                                                <label class="right-label">{{ item.content.collection_date }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">入帳日</label>
                                                <label class="right-label">{{ item.content.posting_date }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            
                                            <div class="row">
                                                <label class="left-label">是否已兌現</label>
                                                <label class="right-label" v-if="item.content.cash_status == 1">兌現成功</label>
                                                <label class="right-label" v-else-if="item.content.cash_status == 2">兌現失敗</label>
                                                <label class="right-label" v-else>確認中</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">未兌現原因</label>
                                                <label class="right-label">{{ item.content.outstanding_reason }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">領回日期</label>
                                                <label class="right-label">{{ item.content.retrieve_date }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">領回原因</label>
                                                <label class="right-label">{{ item.content.retrieve_reason }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">結束追蹤日期</label>
                                                <label class="right-label">{{ item.content.stop_tracking }}</label>
                                            </div>
                                            <div class="row">
                                                <label class="left-label">備註</label>
                                                <label class="right-label">{{ item.content.remark }}</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img 
                                                :src="item.content.image" 
                                                style="width: 50%;"
                                            />    
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
        
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table>
                        <tr>
                            <td> 
                                會員ID： 
                            </td>
                            <td>
                                <input 
                                    type="" 
                                    value="null" 
                                    v-model="searchData.user_id"    
                                />
                            </td>
                            <td style="padding-left: 10px;"> 
                                發票人： 
                            </td>
                            <td>
                                <input 
                                    type="text" 
                                    value="" 
                                    v-model="searchData.cheque_drawer"
                                />
                            </td>
                            <td style="padding-left: 10px;">
                                票據號碼： 
                            </td>
                            <td>
                                <input 
                                    type="text" 
                                    value="" 
                                    v-model="searchData.cheque_no"
                                />
                            </td>
                        </tr>
                        <tr style="vertical-align: baseline;">
                            <td style="padding: 14px 0;"> 
                                票據到期日 從： 
                            </td>
                            <td>
                                <input
                                    type="date" 
                                    v-model="sdate"
                                    value=""
                                    min="2023-01-01" 
                                    max="2030-12-31"
                                    placeholder="不指定區間"
                                >
                            </td>
                            <td style="padding-left: 10px;">  
                                到： 
                            </td>
                            <td>
                                <input
                                    type="date" 
                                    v-model="edate"
                                    value=""
                                    min="2023-01-01" 
                                    max="2030-12-31"
                                    placeholder="不指定區間"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                是否到期： 
                            </td>
                            <td>
                                <select v-model="searchData.date_expire">
                                    <option value="">請選擇</option>
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </td>
                            <td style="padding-left: 10px;"> 
                                兌現狀態： 
                            </td>
                            <td>
                                <select v-model="searchData.cash_status">
                                    <option value="">請選擇</option>
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </td>
                            <td style="padding-left: 10px;"> 
                                票據狀態： 
                            </td>
                            <td>
                                <select v-model="searchData.status">
                                    <option value="">請選擇</option>
                                    <option value="1">正常（託收中）</option>
                                    <option value="2">已領回</option>
                                    <option value="3">未託收</option>
                                    <option value="4">已結束</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-default" @click="goSearch()">查詢</button>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>編輯票據資訊</th>
                                    <th>Detail</th>
                                    <th>票據ID</th>
                                    <th>狀態</th>
                                    <th>發票人</th>
                                    <th>會員ID</th>
                                    <th>案號</th>
                                    <th>期數</th>
                                    <th>票據號碼</th>
                                    <th>票據金額</th>
                                    <th>票據到期日</th>
                                    <th>是否已兌現</th>
                                    <th>入帳日</th>
                                    <th>託收日</th>
                                    <th>受款人戶名</th>
                                    <th>領回日期</th>
                                    <th>結束追蹤日期</th>
                                    <th>備註</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in tableData" :key="item.id">
                                    <template v-if="item.status != 0">
                                        <template v-if="item.review_status == 1">
                                            <td>
                                                <button 
                                                    class="editBtn"
                                                    @click="editForm(item.id)"
                                                >編輯</button>
                                                <button 
                                                    class="deleteBtn"
                                                    @click="deleteForm(item)"
                                                >刪除</button>
                                            </td>
                                        </template>
                                        <template v-else>
                                            <td>主管審核中</td>
                                        </template>
                                        <td>
                                            <button 
                                                class="editBtn"
                                                @click="detailForm(item.id)"
                                            >Detail</button>
                                        </td>
                                        <td>{{ item.id }}</td>
                                        <template v-if="item.status == 0">
                                            <td>標記刪除</td>
                                        </template>
                                        <template v-else-if="item.status == 1">
                                            <td>正常(託收中)</td>
                                        </template>
                                        <template v-else-if="item.status == 2">
                                            <td>已領回</td>
                                        </template>
                                        <template v-else-if="item.status == 3">
                                            <td>未託收</td>
                                        </template>
                                        <template v-else>
                                            <td>已結束</td>
                                        </template>
                                        <td>{{ item.cheque_drawer }}</td>
                                        <td>{{ item.user_id }}</td>
                                        <td>{{ item.target_no }}</td>
                                        <td>{{ item.instalment }}</td>
                                        <td>{{ item.cheque_no }}</td>
                                        <td>{{ item.cheque_amount }}</td>
                                        <td>{{ item.cheque_due_date }}</td>
                                        <template v-if="item.cash_status == 0">
                                            <td>確認中</td>
                                        </template>
                                        <template v-else-if="item.cash_status == 1">
                                            <td>兌現成功</td>
                                        </template>
                                        <template v-else>
                                            <td>兌現失敗</td>
                                        </template>
                                        <td>{{ item.posting_date }}</td>
                                        <td>{{ item.collection_date }}</td>
                                        <td>{{ item.payee }}</td>
                                        <td>{{ item.retrieve_date }}</td>
                                        <td>{{ item.stop_tracking }}</td>
                                        <td>{{ item.remark }}</td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <nav style="text-align: center;">
                    <ul class="pagination">
                        <li class="page-item">
                            <button 
                                type="button" 
                                class="page-link" 
                                v-if="page != 1"
                                @click="changePage('previous', page)"
                            > 上一頁 </button>
                        </li>
                        <li class="page-item">
                            <button 
                                type="button" 
                                class="page-link" 
                                v-for="pageNumber in totalPages"
                                @click="changePage('this', pageNumber)"
                            > {{ pageNumber }} </button>
                        </li>
                        <li class="page-item">
                            <button 
                                type="button" 
                                class="page-link" 
                                v-if="page < totalPages"
                                @click="changePage('next', page)"
                            > 下一頁 </button>
                        </li>
                    </ul>
                </nav>  
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
var loginData = '<?php print_r(json_encode($login_info))?>';
var loginInfo = JSON.parse(loginData);
var p2p_orm_host = '<?php print_r(getenv('ENV_P2P_ORM_HTTPS_HOST'))?>';


$(document).ready(function () {
    var modal = document.getElementById('upsertModal');
    var modal1 = document.getElementById('detailModal');
    var span = document.getElementsByClassName('close')[0];
    var span1 = document.getElementsByClassName('close')[1];
    
    span.onclick = function() {
        modal.style.display = 'none';
    }
    span1.onclick = function() {
        modal1.style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == modal || event.target == modal1) {
            modal.style.display = 'none';
            modal1.style.display = 'none';
        }
    }
})
    
const v = new Vue({
    el: '#page-wrapper',
    data() {
        return {
            isUpsert: false,
            updateType: '',
            tableData: [],
            upsertData: {
                user_id: '',
                cheque_drawer: '',
                target_no: '',
                drawer_bankaccout: '',
                payment_bank: '',
                payment_branch: '',
                cheque_no: '',
                cheque_amount: '',
                cheque_due_date: '',
                instalment: '',
                is_nonnegotiable: '',
                is_personal: '',
                collection_bank: '',
                collection_branch: '',
                collection_date: '',
                payee_id: '',
                payee: '',
                payee_account: '',
                remark: '',
                cash_status: 0,
                posting_date: null,
                outstanding_reason: '',
                retrieve_date: null,
                retrieve_reason: '',
                stop_tracking: null,
                endtrack_reason: ''
            },
            image: null,
            editImage: false,
            detailData: [],
            sdate: null,
            edate: null,
            searchData: {
                user_id: null,
                cheque_no: null,
                cheque_drawer: null,
                date_expire: null,
                cash_status: null,
                status: null
            },
            bankArr: {},
            paymentBranchArr: {},
            collectBranchArr: {},
            page: 1,
            pageSize: 50,
            total: 0,
            targetsList: []
        }
    },
    computed: {
        isSupervisor() {
            // 1: 執行長, 10: 財務主管
            return ['1', '10'].includes(loginInfo.group_id);
        },
        totalPages() {
            return Math.ceil(this.total / this.pageSize);
        }
    },
    mounted() {
        this.getCheque();
        this.getBankArr();
    },
    methods: {
        getCheque() {
            let data = {};
            axios.post(`${p2p_orm_host}/user_cheque?page=${this.page}&page_size=${this.pageSize}`, data)
            .then((res) => {
                this.tableData = res.data; 
                
                // 移除回傳tableData內的 { total: x }
                for (let i = 0; i < this.tableData.length; i++) {
                    for (let [key, val] of Object.entries(this.tableData[i])) {
                        if (key == 'total') {
                            this.total = val
                            let index = this.tableData.indexOf(this.tableData[i])
                            if (index > -1) { 
                                this.tableData.splice(index, 1); 
                            }
                        }
                    }
                }
            })
            .catch((err) => {
                console.log(err);
            })
        },
        goSearch() {
            if (this.sdate == '' || this.edate == '') {
                this.sdate = null;
                this.edate = null;
            }
            for (let [key, val] of Object.entries(this.searchData)) {
                if (val == '') {
                    this.searchData[key] = null;
                }
            }
            
            if (this.sdate != null && this.edate != null) {
                axios.post(`${p2p_orm_host}/user_cheque?sdate=${this.sdate}&edate=${this.edate}&page=${this.page}&page_size=${this.pageSize}`, this.searchData)
                .then((res) => {
                    this.tableData = res.data;

                    // 移除回傳tableData內的 { total: x }
                    for (let i = 0; i < this.tableData.length; i++) {
                        for (let [key, val] of Object.entries(this.tableData[i])) {
                            if (key == 'total') {
                                this.total = val
                                let index = this.tableData.indexOf(this.tableData[i])
                                if (index > -1) { 
                                    this.tableData.splice(index, 1); 
                                }
                            }
                        }
                    }
                })
                .catch((err) => {
                    console.log(err);
                })
            } else {
                axios.post(`${p2p_orm_host}/user_cheque?page=${this.page}&page_size=${this.pageSize}`, this.searchData)
                .then((res) => {
                    this.tableData = res.data;

                    // 移除回傳tableData內的 { total: x }
                    for (let i = 0; i < this.tableData.length; i++) {
                        for (let [key, val] of Object.entries(this.tableData[i])) {
                            if (key == 'total') {
                                this.total = val
                                let index = this.tableData.indexOf(this.tableData[i])
                                if (index > -1) { 
                                    this.tableData.splice(index, 1); 
                                }
                            }
                        }
                    }
                })
                .catch((err) => {
                    console.log(err);
                })
            }

        },
        uploadFile() {
            this.image = this.$refs.file.files[0];
            this.editImage = true
        },
        submitForm() {
            const formData = new FormData();

            if (['', 0, null].includes(this.upsertData.target_no)) {
                alert('案號為必填欄位，請填入正確之案號。');
            }
            
            this.upsertData['edit_status'] = 1;
            this.upsertData['admin_id'] = loginInfo.id;
            var ipInfo;
            $.getJSON('https://api.db-ip.com/v2/free/self', function(data) {
                ipInfo = data;
            });
            setTimeout(() => {
                this.upsertData['created_ip'] = ipInfo.ipAddress;
                this.upsertData['updated_ip'] = ipInfo.ipAddress;

                formData.append('data', JSON.stringify(this.upsertData));
                if (this.image != null) {
                    formData.append('file', this.image);
                }
                const headers = { 'Content-Type': 'multipart/form-data' };

                axios.post(`${p2p_orm_host}/user_cheque/insert`, formData, { headers })
                .then((res) => {
                    alert('新增票據成功');
                    document.location.reload();
                })
                .catch((err) => {
                    if (err.response.data.detail == 'target_no not found') {
                        alert('案號錯誤，請輸入正確的案號。');
                    }
                    console.log(err);
                });
            }, "1000");
        },
        submitEditForm(check_id) {
            const formData = new FormData();

            if (['', 0, null].includes(this.upsertData.target_no)) {
                alert('案號為必填欄位，請填入正確之案號。');
            }

            if (this.updateType == 'modifyCheck') {
                this.upsertData['edit_status'] = 2;
            } else if (this.updateType == 'renew') {
                this.upsertData['edit_status'] = 3;
                if (this.upsertData.cash_status == 1) {
                    this.upsertData['status'] = 4;
                } else if (this.upsertData.cash_status == 2) {
                    this.upsertData['status'] = 2;
                }
            } else if (this.updateType == 'retrieve') {
                this.upsertData['edit_status'] = 4;
                this.upsertData['status'] = 2;
            } else if (this.updateType == 'reCollect') {
                this.upsertData['edit_status'] = 5;
                this.upsertData['status'] = 1;
            } else {
                this.upsertData['edit_status'] = 6;
                this.upsertData['status'] = 4;
            }
            this.upsertData['admin_id'] = loginInfo.id;
            this.upsertData['cheque_id'] = check_id;
            var ipInfo;
            $.getJSON(`https://api.db-ip.com/v2/free/self`, function(data) {
                ipInfo = data;
            });
            setTimeout(() => {
                this.upsertData['updated_ip'] = ipInfo.ipAddress;

                formData.append('data', JSON.stringify(this.upsertData));
                if (this.image != null) {
                    formData.append('file', this.image);
                }
                const headers = { 'Content-Type': 'multipart/form-data' };

                axios.put(`${p2p_orm_host}/user_cheque/edit`, formData, { headers })
                .then((res) => {
                    alert('編輯票據成功');
                    document.location.reload();
                })
                .catch((err) => {
                    console.log(err);
                });
            }, "1000");
        },
        editForm(check_id) {
            this.isUpsert = true;
            let modal = document.getElementById('upsertModal');
            modal.style.display = 'block';
            
            for (let i=0; i < this.tableData.length; i++) {
                if (check_id == this.tableData[i].id) {
                    this.upsertData = this.tableData[i];
                }
            }

            try { this.paymentBranchArr = this.bankArr[this.upsertData.payment_bank]['branch']; }
            catch (err) { this.paymentBranchArr = {}; }
            try { this.collectBranchArr = this.bankArr[this.upsertData.collection_bank]['branch']; } 
            catch (err) { this.collectBranchArr = {}; }
        },
        deleteForm(item) {
            let yes = confirm('確定刪除該票據？');

            if (yes) {
                const formData = new FormData();
    
                item['admin_id'] = loginInfo.id;
                item['cheque_id'] = item.id;
                item['status'] = 0;
                item['edit_status'] = 0;
                var ipInfo;
                $.getJSON(`https://api.db-ip.com/v2/free/self`, function(data) {
                    ipInfo = data;
                });
                setTimeout(() => {
                    item['updated_ip'] = ipInfo.ipAddress;
    
                    formData.append('data', JSON.stringify(item));
                    const headers = { 'Content-Type': 'multipart/form-data' };
                    axios.put(`${p2p_orm_host}/user_cheque/edit`, formData, { headers })
                    .then((res) => {
                        document.location.reload();
                    })
                    .catch((err) => {
                        console.log(err);
                    });
                }, "1000");
            }
        },
        detailForm(check_id) {
            let modal = document.getElementById('detailModal');
            modal.style.display = 'block';

            axios.get(`${p2p_orm_host}/user_cheque/detail`, { params: { cheque_id: check_id } })
            .then((res) => {
                this.detailData = res.data;
                for (i = 0; i < this.detailData.length; i ++) {
                    this.detailData[i]['index'] = i;
                    let dateFormat = new Date(this.detailData[i].updated_at * 1000);
                    let date = dateFormat.getFullYear()+ "/" + (dateFormat.getMonth()+1)+ "/" + dateFormat.getDate()+ " " + dateFormat.getHours()+ ":" + dateFormat.getMinutes()+ ":" + dateFormat.getSeconds();
                    this.detailData[i]['date'] = date;
                }
                console.log(this.detailData);
            })
            .catch((err) => {
                console.log(err);
            })
        },
        showHideRow(row) {
            $("#" + row).toggle();
        },
        addCheck() {
            var modal = document.getElementById('upsertModal');
            modal.style.display = 'block';

            this.isUpsert = false;
            this.updateType = '';
            this.upsertData = {
                user_id: '',
                cheque_drawer: '',
                target_no: '',
                drawer_bankaccout: '',
                payment_bank: '',
                payment_branch: '',
                cheque_no: '',
                cheque_amount: '',
                cheque_due_date: null,
                instalment: '',
                is_nonnegotiable: '',
                is_personal: '',
                collection_bank: '',
                collection_branch: '',
                collection_date: null,
                payee_id: '',
                payee: '',
                payee_account: '',
                remark: '',
                cash_status: 0,
                posting_date: null,
                outstanding_reason: '',
                retrieve_date: null,
                retrieve_reason: '',
                stop_tracking: null,
                endtrack_reason: ''
            };
        },
        cancelForm() {
            let modal = document.getElementById('upsertModal');
            modal.style.display = 'none';
        },
        approveModify(modifyCheck, isApprove) {
            axios.put(`${p2p_orm_host}/user_cheque/review?cheque_id=${modifyCheck.cheque_id}&review=${isApprove}`)
            .then((res) => {
                if (isApprove == true) { alert('審核成功'); }
                else if (isApprove == false) { alert('退回成功'); }
                document.location.reload();
            }).catch((err) => {
                console.log(err);
            });
        },
        getBankArr() {
            axios.get(`${p2p_orm_host}/banks`)
            .then((res) => {
                this.bankArr = res.data;
            })
            .catch((err) => {
                console.log(err);
            })
        },
        selectPaymentBank() {
            try {
                this.paymentBranchArr = this.bankArr[this.upsertData.payment_bank]['branch'];
            } catch (err) {
                this.paymentBranchArr = {};                
            }
        },
        selectCollectBank() {
            try {
                this.collectBranchArr = this.bankArr[this.upsertData.collection_bank]['branch'];
            } catch (err) {
                this.collectBranchArr = {};                
            }
        },
        changePage(type, current_page) {
            if (type == 'previous') { this.page--; }
            else if (type == 'next') { this.page++; }
            else { this.page = current_page; }
            this.goSearch();
        },
        exportExcel(is_all) {
            if (this.sdate != null && this.edate != null) {
                axios.post(`${p2p_orm_host}/user_cheque/excel?sdate=${this.sdate}&edate=${this.edate}&all_data=${is_all}`, this.searchData, { responseType: 'blob' })
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', '託收票據.xlsx'); 
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch((err) => {
                    console.log(err);
                })
            } else {
                axios.post(`${p2p_orm_host}/user_cheque/excel?all_data=${is_all}`, this.searchData, { responseType: 'blob' })
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', '託收票據.xlsx'); 
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch((err) => {
                    console.log(err);
                })
            }
        },
        searchTargetNo(user_id) {
            axios.get(`${p2p_orm_host}/user_cheque/id?user_id=${user_id}`)
            .then((res) => {
                this.upsertData.cheque_drawer = res.data.name;
                this.targetsList = res.data.target_no
            })
            .catch((err) => {
                console.log(err);
            })            
        }
    },
});

</script>
<style>
.headerButton {
    margin: 40px 0 20px;
    color: white;
    border-color: white;
    border-radius: 16px;
    height: 44px;
    box-shadow: 2px 4px 14px rgba(219, 47, 47, 0.08), 0px 4px 8px rgba(219, 47, 47, 0.16);
}
.exportExcel {
    background: #036FB7;
}
#addCheck {
    width: 120px;
    background: #DB2F2F;
}   
h1, h2, h3, h4, h5 {
    font-weight: 700;
}
.insertInput {
    width: 60%;
    border-radius: 8px;
}
.insertSelect {
    border-radius: 8px;
    height: 24px;
    border: 2px solid;
    margin: 2px 0 2px 0;
}
.remarkArea {
    border-radius: 8px;
    border: 2px solid;
    float: left;
}
.influxBtn, .editBtn, .deleteBtn {
    box-shadow: 2px 4px 14px rgba(3, 111, 183, 0.08), 0px 4px 8px rgba(3, 111, 183, 0.16);
    border-radius: 4px;
}
.deleteBtn {
    background: #DB2F2F;
    border-color: #DB2F2F;
    color: white;
}
.cancelBtn {
    width: 88px;
    height: 44px;
    border-color: white;
    background: #F2F2F2;
    box-shadow: 2px 4px 14px rgba(3, 111, 183, 0.08), 0px 4px 8px rgba(3, 111, 183, 0.16);
    border-radius: 16px;
    font-weight: 600;
    font-size: 16px;
}
.submitBtn {
    width: 88px;
    height: 44px;
    color: white;
    border-color: white;
    background: #036FB7;
    box-shadow: 2px 4px 14px rgba(3, 111, 183, 0.08), 0px 4px 8px rgba(3, 111, 183, 0.16);
    border-radius: 16px;
    font-weight: 600;
    font-size: 16px;
}
th {
    text-align: center !important;
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
    width: 75%;
}
.modal-card {
    border: solid;
    border-radius: 4px;
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

/* detail modal 區塊 */
.editBar {
    width: 80%;
    height: 30px;
    background-color: #FFC466;
    color: black;
    text-align: center;
    margin: auto;
}

.detail_divide {
    border-right: 2px solid black;
}
.left-label {
    float: left;
    margin-left: 5px;
}
.right-label {
    float: right;
    margin-right: 5px;
}

#detail_wrapper {
    margin: 0 auto;
    padding: 0px;
    text-align: center;
    width: 995px;
}
#detail_wrapper h1 {
    margin-top: 50px;
    font-size: 45px;
    color: #585858;
}
#detail_wrapper h1 p {
    font-size: 20px;
}
#table_detail {
    width: 90%;
    text-align: center;
    border-collapse: collapse;
    color: #2E2E2E;
    border: #A4A4A4;

    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
#table_detail thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}
#table_detail th, #table_detail td {
    padding: 12px 15px;
}
#table_detail tr:hover {
    background-color: #F2F2F2;
}
#table_detail .hidden_row {
    display: none;
}

button.page-link {
    display: inline-block;
    font-size: 20px;
    color: #036FB7;
    font-weight: 500;
}

</style>