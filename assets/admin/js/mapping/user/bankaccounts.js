class BankAccounts
{
	constructor(bankAccounts) {
		if (bankAccounts.borrower) {
			this.borrower = new BankAccount(bankAccounts.borrower)
		}
		if (bankAccounts.investor) {
			this.investor = new BankAccount(bankAccounts.investor)
		}
	}
}
