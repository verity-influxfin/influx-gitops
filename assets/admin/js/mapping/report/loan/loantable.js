class LoanTable
{
    constructor(table) {
        this.rows = [];
        for (var row in table) {
            this.rows.push(new LoanRow(row, table[row]));
        }
    }
}