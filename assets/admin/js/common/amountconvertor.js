class AmountConvertor
{
    splitByThousands(value) {
        try {
            var convertedNumbers = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        } catch(err) {
            return 0;
        }
        return convertedNumbers;
    }
}