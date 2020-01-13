class LoanRow
{
    constructor(name, row, amountConvertor) {
        this.amountConvertor = amountConvertor
        this.setName(name)
        this.setColumns(row)
    }

    setName(name) {
        if (name == 'new_office_workers') {
            this.name = '上班族-新戶';
        } else if (name == 'existing_office_workers') {
            this.name = '上班族-舊戶';
        } else if (name == 'new_students') {
            this.name = '學生-新戶';
        } else if (name == 'existing_students') {
            this.name = '學生-舊戶';
        } else {
            this.name = '總計';
        }
    }

    setColumns(row) {
        this.applicants = row.applicants;
        this.applications = row.applications;
        this.approvedPendingSigningAmount = this.amountConvertor.splitByThousands(row.approved_pending_signing_amount)
        this.matchRate = row.match_rate;
        this.matchedAmount = this.amountConvertor.splitByThousands(row.matched_amount)
        this.matchedApplicants = row.matched_applicants
        this.matchedApplications = row.matched_applications
        this.onTheMarket = row.on_the_market
        this.onTheMarketAmount = this.amountConvertor.splitByThousands(row.on_the_market_amount)
        this.pendingSigningApplicants = row.pending_signing_applicants
    }
}