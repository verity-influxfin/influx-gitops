class RelatedUser
{
	constructor(relatedUser) {
		this.reason = relatedUser.reason;
		this.user = {}
		this.user.id = relatedUser.id;
		this.user.borrower_status = relatedUser.borrower_status;
		this.user.investor_status = relatedUser.investor_status;
	}
}
