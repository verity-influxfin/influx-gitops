class LoanTable
{
    constructor(table, amountConvertor) {
        this.rows = [];
        for (var row in table) {
            this.rows.push(new LoanRow(row, table[row], amountConvertor));
        }
    }
}