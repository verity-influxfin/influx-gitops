class Target
{
	constructor(target) {
		this.id = parseInt(target.id);
		this.number = target.number;
		this.status = target.status;
		this.reason = target.reason;
		this.image = target.image;
		this.expireAt = parseInt(target.expire_at);
		this.setAmount(target);
		this.setProduct(target);
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
}
