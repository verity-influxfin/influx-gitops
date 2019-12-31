class Target
{
	constructor(target) {
		this.id = parseInt(target.id);
		this.number = target.number;
		this.reason = target.reason;
		this.image = target.image;
		this.credit = target.credit;
		this.isDelay = target.is_delay;
		this.interests = target.interests;
		this.instalment = target.instalment;
		this.targetData = target.targetData;
		this.productTargetData = target.productTargetData;
		this.creditTargetData = target.creditTargetData;
		this.expireAt = parseInt(target.expire_at);
		this.loanAt = parseInt(target.loan_at);
		this.setStatus(target);
		this.setAmount(target);
		this.setProduct(target);
		this.setRepayment(target);
	}

	setStatus(target) {
		if (!target.status) return;
		this.status = {}
		this.status.id = target.status.id;
		this.status.text = target.status.text;
	}

	setAmount(target) {
		this.amount = {};
		this.amount.requested = parseInt(target.requested_amount);
		this.amount.approved = parseInt(target.approved_amount);
		this.amount.remaining = parseInt(target.remaining);
		this.amount.principal = parseInt(target.principal);
	}

	setProduct(target) {
		this.product = {};
		this.product.id = parseInt(target.product.id);
		this.product.name = target.product.name;
	}

	setRepayment(target) {
		this.repayment = {};
		this.repayment.id = target.repayment.id;
		this.repayment.text = target.repayment.text;
	}
}
