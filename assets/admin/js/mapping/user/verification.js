class Verification
{
	constructor(verification) {
		this.id = verification.id;
		this.name = verification.name;
		this.description = verification.description;
		this.status = verification.status;
		this.expiredAt = verification.expired_at;
		this.expired = verification.expired;
	}

	isPending() {
		return this.status == "pending";
	}

	success() {
		return this.status == "verified";
	}

	failure() {
		return this.status == "failure";
	}

	requireHumanReview() {
		return this.status == "human_review_required";
	}

	getExpiredAtHumanReadable() {
		var date = new DateTime(this.expiredAt)
		return date.years + "/" + date.months + "/" + date.days;
	}
}
