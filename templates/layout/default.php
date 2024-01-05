<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

    <title>Yeni Party</title>
    <meta content="Yeni" name="description">
    <meta content="yeni, bong bong" name="keywords">
    <meta property="og:title" content="Yeni">
    <meta property="og:type" content="Website">
    <meta property="og:url" content="https://yeniparty.com/">
    <meta property="og:description" content="Yeni party Phụ kiện trang trí Sinh Nhật">
    <meta property="og:image" content="<?= BASE_URL . 'image/yeni_logo.jpg' ?>"/>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL . 'image/yeni_logo.jpg' ?>">

    <!-- CSS -->
    <link href="<?= BASE_URL ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>plugins/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>plugins/image-uploader/dist/image-uploader.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>plugins/bootstrap-select/bootstrap-select.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>plugins/icheck-material/css/icheck-material.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/print.css" media="print">
    <link rel="stylesheet" href="<?= BASE_URL ?>plugins/jquery-ui/jquery-ui.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css?v=1">


    <script src="<?= BASE_URL ?>js/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?= BASE_URL ?>plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= BASE_URL ?>js/jquery/jquery-validation-1.19.4/dist/jquery.validate.js"></script>
    <script src="<?= BASE_URL ?>plugins/popper/popper.min.js"></script>
    <script src="<?= BASE_URL ?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="<?= BASE_URL ?>plugins/ckeditor/ckeditor.js"></script>
    <script src="<?= BASE_URL ?>plugins/select2/dist/js/select2.min.js"></script>
    <script src="<?= BASE_URL ?>plugins/image-uploader/src/image-uploader.js"></script>
    <script src="<?= BASE_URL ?>plugins/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="<?= BASE_URL ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <!-- Pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <?= $this->fetch('css'); ?>
</head>
<script>
    // Common
    const FORMAT_DATE = "<?= DATE_FORMAT_JS ?>"
    const DEFAULT_CURRENCY_CODE = "<?= DEFAULT_CURRENCY_CODE ?>"

    function gExchangeCurrency(value, currency_code_from, rate_from, currency_code_to, rate_to) {
        if(currency_code_from === currency_code_to) return value

        // Ex: Default = VND
        // Ex: USD -> VND gExchange(1, USD, 24000, VND, 1) = 24000
        if(currency_code_to === DEFAULT_CURRENCY_CODE) {
            return value * rate_from
        }
        // Ex: VND -> USD gExchange(24000, VND, 1, USD, 24000) = 1
        if(currency_code_from === DEFAULT_CURRENCY_CODE) {
            return value / rate_to
        }
        // Ex: JPY -> USD gExchange(24000, JPY, 100, USD, 24000) = 100
        // JPY -> VND = 2400000
        // VND -> USD = 100
        return (value * rate_from) / rate_to
    }

    function gParseFloat(value, default_return) {
        if(default_return === undefined)
            default_return = 0.0
        value = parseFloat(value)
        if(isNaN(value)) value = default_return
        return value
    }

    function gFormatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear()

        if (month.length < 2)
            month = '0' + month
        if (day.length < 2)
            day = '0' + day

        // format m/d/y
        return [month, day, year].join('/')
    }

    function gFormatNumber(number, decimals, dec_point, thousands_sep) {
        number = gParseFloat(number).toFixed(decimals)
        let nstr = number.toString()
        nstr += ''
        x = nstr.split('.')
        x1 = x[0]
        x2 = x.length > 1 ? dec_point + x[1] : ''
        var rgx = /(\d+)(\d{3})/
        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2')

        return x1 + x2
    }
    function gFormatCurrency(value, currency_code) {
        value = gFormatNumber(value, 4, '.', ',')
        if (currency_code === 'VND') return value + ' ' + currency_code
        else return currency_code + ' ' + value
    }

    var spinnerBtnInnerHtml = ''
    function gSpinnerBtnStart(element_jq) {
        spinnerBtnInnerHtml = element_jq.html()
        element_jq.empty()
        element_jq.attr('disabled', 'disabled')
        element_jq
            .prepend('<span class="spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
    }
    function gSpinnerBtnEnd(element_jq) {
        setTimeout(function () {
            element_jq.empty()
            element_jq.removeAttr('disabled')
            element_jq.prepend(spinnerBtnInnerHtml)
        }, 60)
    }

    function gSpinnerInputStart(element_jq) {
        element_jq.attr('readonly', 'readonly')
        element_jq
            .css('background', 'url("/img/loading-input.gif") no-repeat left center')
    }
    function gSpinnerInputEnd(element_jq) {
        setTimeout(function () {
            element_jq.removeAttr('readonly')
            element_jq.css('background', '')
        }, 60)
    }
    function gEscapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    function gDecodeHtml(str) {
        var map =
            {
                '&amp;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&quot;': '"',
                '&#039;': "'"
            };
        return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
    }

    // hide New button when main form is showing
    function gDisplayMainForm() {
        let blocker  = document.getElementsByClassName('form-add')[0];
        if (blocker !== undefined) {
            let observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.attributeName !== 'style') return;
                    let currentValue = mutation.target.style.display;
                    if (currentValue === 'none') {
                        document.getElementById('btn-add-form').style.display = "block";
                    } else {
                        document.getElementById('btn-add-form').style.display = "none";
                    }

                });
            });

            observer.observe(blocker, {attributes: true});
        }
    }
    // show loading and disable element in one second
    function gShowLoadingSaveBtnMainForm(element) {
        $(element).addClass('disabled')
        $(element).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...');
        setTimeout(function() {
            $(element).removeClass('disabled')
            $(element).html('<i class="fa-regular fa-floppy-disk"></i> ' +
                '<?= __("Save") ?>');
        }, 1000);
    }

    function findCurrencyByCode(el, code) {
        if(code === undefined)
            code = DEFAULT_CURRENCY_CODE
        let currency_id = el.find('option:eq(0)').val()
        el.find('option').each(function () {
            if($(this).text() == code) {
                currency_id = $(this).val()
            }
        })
        return currency_id
    }
    function gParseDate(input, format) {
        format = (format || 'yyyy-mm-dd').toLowerCase(); // default format
        var parts = input.match(/(\d+)/g),
            i = 0, fmt = {};
        // extract date-part indexes from the format
        format.replace(/(yyyy|dd|mm)/g, function(part) { fmt[part] = i++; });
        return new Date([parts[fmt['yyyy']], parts[fmt['mm']], parts[fmt['dd']]].filter(x => x !== undefined).join('-'));
    }
    $(document).ready(function() {
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if(element.parent().length) {
                    element.parent().append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $.validator.addMethod("greaterThan",
            function (value, element, params) {
                return this.optional( element ) || isNaN(value) && isNaN(params)
                    || (Number(value) > Number(params))
            }, '<?= __("Must be greater than {0}.") ?>')

        gDisplayMainForm();

        // add effect when click save button in main form
        $('.form-add button[type="submit"]').click(function() {
            gShowLoadingSaveBtnMainForm(this);
        });

    });
    jQuery.extend(jQuery.validator.messages, {
        required: "<?= __("This field is required.") ?>",
        number: "<?= __("Please enter a valid number.") ?>",
        min: jQuery.validator.format("<?= __("Please enter a value greater than or equal to {0}.") ?>"),
        max: jQuery.validator.format("<?= __("Please enter a value less than or equal to {0}.") ?>"),
        maxlength: jQuery.validator.format("<?= __("Please enter no more than {0} characters.") ?>"),
    });
</script>
<body class="bg-light">
<div id="loading-form">
    <div class="center-loading">
        <img src="<?php echo BASE_URL.'/img/loading.gif' ?>">
    </div>
</div>
<?php echo $this->element('header'); ?>
<div class="bd-gutter bd-layout">
    <?php echo $this->element('sidebar/index'); ?>
    <main class="bg-white bd-main order-1">
        <div class="bd-content">
            <?php if (isset($permission) && !$permission && FLAG_PERMISSION) { ?>
                <div class="notification">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        You do not have permission to access.
                    </div>
                </div>
            <?php }
            else {
                echo $this->fetch('content');
                echo $this->element('modal_alert');
                echo $this->element('modal_confirmation');
            }?>
        </div>
    </main>
</div>
<?= $this->fetch('js'); ?>
</body>
</html>
