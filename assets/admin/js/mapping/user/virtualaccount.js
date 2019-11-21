class VirtualAccount
{
	constructor(virtualAccount) {
		this.id = parseInt(virtualAccount.id);
		this.account = virtualAccount.account;
		this.createdAt = virtualAccount.created_at;
		this.setFunds(virtualAccount)
	}

	setFunds(virtualAccount) {
		if (!virtualAccount.funds) return;
		this.funds = {}
		this.funds.total = parseInt(virtualAccount.funds.total);
		this.funds.frozen = parseInt(virtualAccount.funds.frozen);
		this.funds.lastRechargeAt = virtualAccount.funds.last_recharge_date;
	}
}
