class Percentage
{
    convertDecimalToPercentage(floatVal)
    {
        var percentage = (floatVal * 100).toFixed(2)
        return percentage.toString() + '%';
    }
}