class Target
{
	constructor(target) {
		this.id = parseInt(target.id);
		this.number = target.number;
		this.reason = target.reason;
		this.image = target.image;
		this.expireAt = parseInt(target.expire_at);
		this.setStatus(target)
		this.setAmount(target);
		this.setProduct(target);
	}

	setStatus(target) {
		console.log(target)
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
}
