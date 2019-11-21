class VirtualAccounts
{
	constructor(virtualAccounts) {
		if (virtualAccounts.borrower) {
			this.borrower = new VirtualAccount(virtualAccounts.borrower)
		}
		if (virtualAccounts.investor) {
			this.investor = new VirtualAccount(virtualAccounts.investor)
		}
	}
}
