class Verification
{
	constructor(verification) {
		this.id = verification.id;
		this.name = verification.name;
		this.description = verification.description;
		this.status = verification.status;
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
}
