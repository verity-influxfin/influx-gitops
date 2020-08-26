class JobCredit
{
    constructor(jobCredit) {
        this.setStageMapping();
        this.setStatus(jobCredit);
        this.setFile(jobCredit);
		this.setMessages(jobCredit);
		this.setLicenseStatus(jobCredit);
		this.setProLevel(jobCredit);
		this.setTax_id(jobCredit);
		this.setCompany(jobCredit);
		this.setIndustry(jobCredit);
		this.setJob_title(jobCredit);
		this.setEmployee(jobCredit);
		this.setPosition(jobCredit);
		this.setType(jobCredit);
		this.setSeniority(jobCredit);
		this.setJob_seniority(jobCredit);
		this.setSalary(jobCredit);
		this.setIncomeProveImages(jobCredit);
		this.setAuxiliaryImages(jobCredit);
		this.setLicenseImages(jobCredit);
		this.setBusinessImages(jobCredit);
		this.setWaitScan(jobCredit);
	}
	setLicenseStatus(jobCredit) {
        if (!jobCredit.license_status) this.licenseStatus= "";
		this.licenseStatus = jobCredit.license_status;
	}
	setTax_id(jobCredit) {
        if (!jobCredit.tax_id) this.tax_id= "";
		this.tax_id = jobCredit.tax_id;
	}
	setCompany(jobCredit) {
        if (!jobCredit.company) this.company= "";
		this.company = jobCredit.company;
	}
	setIndustry(jobCredit) {
        if (!jobCredit.industry) this.industry= "";
		this.industry = jobCredit.industry;
	}
	setJob_title(jobCredit) {
        if (!jobCredit.job_title) this.job_title= "";
		this.job_title = jobCredit.job_title;
	}
	setEmployee(jobCredit) {
        if (!jobCredit.employee) this.employee= "";
		this.employee = jobCredit.employee;
	}
	setPosition(jobCredit) {
        if (!jobCredit.position) this.position= "";
		this.position = jobCredit.position;
	}
	setType(jobCredit) {
        if (!jobCredit.type) this.type= "";
		this.type = jobCredit.type;
	}
	setSeniority(jobCredit) {
        if (!jobCredit.seniority) this.seniority= "";
		this.seniority = jobCredit.seniority;
	}
	setJob_seniority(jobCredit) {
        if (!jobCredit.job_seniority) this.job_seniority= "";
		this.job_seniority = jobCredit.job_seniority;
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

    setWaitScan(jobCredit) {
        this.scan_status = this.mapStatus(jobCredit.scan_status);
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
            'great_job' : '是否為優良職業認定',
            'high_salary' : '是否為高收入族群',
        };
    }
}
