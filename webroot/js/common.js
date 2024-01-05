function gFormatCurrency(value, currency_code) {
    value = gFormatNumber(value, 0, '.', ',')
    if (currency_code === 'VND') return value + ' ' + currency_code
    else return currency_code + ' ' + value
}