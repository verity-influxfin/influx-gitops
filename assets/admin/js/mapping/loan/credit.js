class Credit
{
	constructor(credit) {
		this.id = credit.id;
		this.setProduct(credit);
		this.level = credit.level;
		this.points = credit.points;
		this.amount = credit.amount;
		this.setRemark(credit);
		this.expiredAt = credit.expired_at;
		this.createdAt = credit.created_at;
	}

	setProduct(credit) {
		if (!credit.product) return;
		this.product = {}
		this.product.id = credit.product.id;
		this.product.sub_product_id = credit.product.sub_product_id;
		this.product.name = credit.product.name;
	}
	setRemark(credit) {
		if (!credit.remark) return;
		this.remark = {}
		this.remark.scoreHistory = credit.remark.scoreHistory ?? [];
	}

	getCreatedAtAsDate() {
		return new DateTime(this.createdAt).values();
	}

	getExpiredAtAsDate() {
		return new DateTime(this.expiredAt).values();
	}
}
