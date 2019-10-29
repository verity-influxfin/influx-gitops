class BankAccounts
{
	constructor(bankAccounts) {
		if (bankAccounts.borrower) {
			this.borrower = new BankAccount(bankAccounts.borrower)
		} else if (bankAccounts.investor) {
			this.investor = new BankAccount(bankAccounts.investor)
		}
	}
}
