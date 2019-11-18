class JointCredit
{
    constructor(jointCredit) {
        this.setStageMapping();
        this.setStatus(jointCredit);
        this.setMessages(jointCredit);
    }

    setStatus(jointCredit) {
        this.status = this.mapStatus(jointCredit.status);
    }

    setMessages(jointCredit) {
        this.messages = [];
        if (!jointCredit.messages) return;

        for (var i = 0; i < jointCredit.messages.length; i++) {
            jointCredit.messages[i].status = this.mapStatus(jointCredit.messages[i].status);
            jointCredit.messages[i].stage = this.mapStage(jointCredit.messages[i].stage);
            this.messages.push(jointCredit.messages[i]);
        }
    }

    mapStatus(status) {
        if (status == "success") {
            return "驗證成功";
        }

        if (status == "pending") {
            return "待人工驗證";
        }

        if (status == "failure") {
            return "退件";
        }

        return "未定義";
    }

    mapStage(stage) {
        if (this.stageMapping[stage]) {
            return this.stageMapping[stage];
        }
        return '未定義';
    }

    setStageMapping() {
        this.stageMapping = {
            'bank_loan' : '銀行借款資訊',
            'bad_debts' : '逾期、催收或呆帳資訊',
            'main_debts' : '主債務債權轉讓及清償資訊',
            'extra_debts' : '共同債務/從債務/其他債務資訊',
            'transfer_debts' : '共同債務/從債務/其他債務轉讓資訊',
            'bounced_checks' : '退票資訊',
            'lost_contacts' : '拒絕往來資訊',
            'credit_card_info' : '信用卡資訊',
            'credit_card_accounts' : '信用卡帳戶資訊',
            'credit_card_debts' : '信用卡債權在轉讓',
            'browsed_hits' : '被查詢紀錄',
            'browsed_hits_by_electrical_pay' : '被電子支付或電子票證發行機構查詢紀錄',
            'browsed_hits_by_itself' : '當事人查詢紀錄',
            'extra_messages' : '附加訊息',
            'credit_scores' : '信用評分',
            'report_expirations' : '信用報告上右上方列印的日期',
        };
    }
}
