class BankAccount
{
	constructor(bankAccount) {
		this.id = parseInt(bankAccount.id);
		this.bankCode = bankAccount.bank_code;
		this.branchCode = bankAccount.branch_code;
		this.account = bankAccount.account;
		this.isInvestor = bankAccount.is_investor;
		this.createdAt = parseInt(bankAccount.created_at);
	}

	setVerification(bankAccount) {
		if (!bankAccount.verification) return;
		this.verification = {}
		this.verification.status = bankAccount.verification.status;
		this.verification.verifiedAt = parseInt(bankAccount.verification.verified_at)
	}
}
