class JobCredit
{
    constructor(jobCredit) {
        this.setStageMapping();
        this.setStatus(jobCredit);
        this.setFile(jobCredit);
		this.setMessages(jobCredit);
		this.setLicenseStatus(jobCredit);
		this.setProLevel(jobCredit);
		this.setSalary(jobCredit);
		this.setIncomeProveImages(jobCredit);
		this.setAuxiliaryImages(jobCredit);
		this.setLicenseImages(jobCredit);
		this.setBusinessImages(jobCredit);
	}
	setLicenseStatus(jobCredit) {
        if (!jobCredit.license_status) this.licenseStatus= "";
		this.licenseStatus = jobCredit.license_status;
	}
	setSalary(jobCredit) {
        if (!jobCredit.salary) this.salary= "";
		this.salary = jobCredit.salary;
	}
	setProLevel(jobCredit) {
        if (!jobCredit.pro_level) this.proLevel= "";
		this.proLevel = jobCredit.pro_level;
	}
    setFile(jobCredit) {
        if (!jobCredit.file) this.file= "";
        this.file = jobCredit.file;
	}

    setStatus(jobCredit) {
        this.status = this.mapStatus(jobCredit.status);
    }

    setMessages(jobCredit) {
        this.messages = [];
        if (!jobCredit.messages) return;

        for (var i = 0; i < jobCredit.messages.length; i++) {
            jobCredit.messages[i].status = this.mapStatus(jobCredit.messages[i].status);
            jobCredit.messages[i].stage = this.mapStage(jobCredit.messages[i].stage);
            this.messages.push(jobCredit.messages[i]);
        }
    }

    setIncomeProveImages(jobCredit) {
        this.incomeProveImages = [];
        if (!jobCredit.income_prove_images) return;
        this.incomeProveImages = jobCredit.income_prove_images;
    }

    setAuxiliaryImages(jobCredit) {
        this.auxiliaryImages = [];
        if (!jobCredit.auxiliary_images) return;
        this.auxiliaryImages = jobCredit.auxiliary_images;
    }

    setLicenseImages(jobCredit) {
        this.licenseImages = [];
        if (!jobCredit.license_images) return;
        this.licenseImages = jobCredit.license_images;
    }

    setBusinessImages(jobCredit) {
        this.businessImages = [];
        if (!jobCredit.business_images) return;
        this.businessImages = jobCredit.business_images;
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
            'correctness' : '勞工保險異動查詢',
            'download_time' : '網頁下載時間',
            'time_matches' : '查詢日期起訖',
            'applicant_detail' : '個人資訊',
            'insurance_enrollment' : '是否為勞工',
            'company' : '現職公司名稱',
            'current_job' : '現職工作年資',
            'total_job' : '總工作年資',
            'job' : '工作',
            'salary' : '月薪',
            'top_company' : '是否為千大企業之員工',
            'high_salary' : '是否為高收入族群',
        };
    }
}
