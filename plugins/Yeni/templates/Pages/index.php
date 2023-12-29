<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable Example</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css"/>
    <style>
        .text-red{
            cursor: pointer;
            color: red;
        }
        .form-control{
            border: 1px solid black;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Section 1: Hiển thị thông tin -->
    <div id="infoSection">
        <h2>Thông tin hàng:</h2>
        <div>
            <div class="col-12">
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Họ tên khách: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="full_name" name="full_name" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Số đt: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="phone" name="phone" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Địa chỉ giao: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="addr" name="addr" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Tổng tiền: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="total_order" name="total_order" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Ghi chú: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="note" name="note" value="" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <form id="form_item" action="" name="form_item">
            <div id="selectedInfo" class="mb-2"></div>
        </form>
        <div class="row mb-2">
            <div class="col-6" style="display:flex;justify-content: flex-start">
                <button type="button" id="send_form" class="btn btn-primary me-3">Gửi</button>
                <button type="button" id="reset_form" class="btn btn-danger">Reset</button>
            </div>
        </div>
    </div>

    <!-- Section 2: DataTable -->
    <div id="dataTableSection">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Mã</th>
                    <th>Tên</th>
                    <th>Số lượng</th>
                    <th>Giá tiền</th>
                </tr>
                </thead>
                    <tbody id="jsonDisplay">
                    </tbody>
            </table>
    </div>
</div>

<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
<script type="text/javascript">
    (function(){
        emailjs.init("IixI2U_PjLkTBP_Ue");
    })();
</script>
<script>
    function showToday(){
        const currentDate = new Date();
        const day = currentDate.getDate();
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        const hours = currentDate.getHours();
        const minutes = currentDate.getMinutes();
        const seconds = currentDate.getSeconds();
        return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`
    }

    function groupByName(serialArray) {
        const groupedInputs = {};

        serialArray.forEach(input => {
            const name = input.name;

            if (!groupedInputs[name]) {
                groupedInputs[name] = [input.value];
            } else {
                groupedInputs[name].push(input.value);
            }
        });

        return groupedInputs;
    }

    function removeRow(code)
    {
        $('#' + code).remove();
    }

    function formReset()
    {
        $('#full_name').val("")
        $('#phone').val("")
        $('#addr').val("")
        $('#total_order').val("")
        $('#note').val("")
    }

    function gFormatNumber(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign +
                (j ? i.substr(0, j) + thousands : '') +
                i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
                (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    }
    function gFormatCurrency(value, currency_code) {
        value = gFormatNumber(value, 0, '.', ',')
        if (currency_code === 'VND') return value + ' ' + currency_code
        else return currency_code + ' ' + value
    }

    $(document).ready(function() {
        let jsonUrl = 'Json/ProductJson.json';

        fetch(jsonUrl)
            .then(response => response.json())
            .then(data => {
                let productHtml = ""
                Object.keys(data).forEach(key => {
                    let product = data[key];

                    if ("code" in product) {
                        // Tạo HTML để hiển thị thông tin sản phẩm
                        productHtml = `
                        <td>
                            <strong>${product.code}</strong><br>
                        </td>
                        <td>
                            <label>${product.name}</label> <br>
                        </td>
                        <td>
                            <strong> 0</strong><br>
                        </td>
                        <td>
                            <strong> ${product.total}</strong><br>
                        </td>
                    `;
                    } else {
                        let productDetail = ""
                        Object.keys(product).forEach(k => {
                            let detail = product[k]
                            productDetail += `
                                <strong>${detail.total}</strong> | <strong>${detail.code}</strong> | ${detail.name}<br>
                                `
                        })

                        productHtml = `
                        <td>
                            <strong>${key}</strong> <br>
                        </td>
                        <td>`
                            +
                                productDetail
                            +
                        `</td>
                        <td>
                            <br>
                        </td>
                        <td>
                            <br>
                        </td>
                        `;
                    }

                    document.getElementById('jsonDisplay').innerHTML += productHtml;

                });

                let table = new DataTable('#dataTable');
                $('#dataTable tbody tr').hover(function(){
                    $(this).css('cursor', "pointer")
                })
                // Xử lý sự kiện click trên hàng DataTable
                $('#dataTable tbody').on('click', 'tr', function() {
                    let data = table.row(this).data();
                    let code = $(data[0]).text()
                    let name = $(data[1]).text()

                    let html = `<div class="row" id="${code}">
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-1">
                                    <label class="text-red" data-id="${code}" onclick="removeRow('${code}')"> Xóa </label>
                                </div>
                                <div class="col-3">
                                    <label>Mã: </label>
                                    <input type="text" class="form-control" name="code" value="${code}" />
                                </div>

                                <div class="col-4">
                                    <label>Tên: </label>
                                    <input type="text" class="form-control" name="name" value="${name}" />
                                </div>
                                <div class="col-1">
                                </div>
                                <div class="col-3">
                                    <label>Số lượng: </label>
                                    <input type="number" class="form-control" name="quantity" value="" />
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>`

                    $('#selectedInfo').append(html)

                });
            })
            .catch(error => console.error('Error fetching JSON:', error));

        //reset
        $('#reset_form').click(function(){
            formReset()
        })

        //send form
        $('#send_form').click(function(){
            $('#send_form').text("Đang gửi. Chờ xíu nha..")
            let full_name = $('#full_name').val()
            let phone = $('#phone').val()
            let addr = $('#addr').val()
            let total_order = $('#total_order').val()
            let note = $('#note').val()
            let serializedArray = $("form").serializeArray();
            let groupedInputs = groupByName(serializedArray);
            let code_array = groupedInputs['code']
            let qty_array = groupedInputs['quantity']
            let name_array = groupedInputs['name']
            let html = ""
            if(code_array != null && code_array.length > 0)
            {
                html = `<table style="border-collapse: collapse; width: 100%;" border="1"><colgroup><col style="width: 15%;"><col style="width: 20%;"><col style="width: 50%;"><col style="width: 15%;"></colgroup>
                        <tbody>
                        <tr>
                        <td>Số thứ tự</td>
                        <td>Mã hàng</td>
                        <td>Tên hàng</td>
                        <td>Số lượng</td>
                        </tr>`;
                let count = 1
                code_array.forEach(function(element, index) {
                    html += `<tr>
                            <td>${count}</td>
                            <td>${element}</td>
                            <td>${name_array[index]}</td>
                            <td>${qty_array[index]}</td>
                        </tr>`
                    count++
                });

                html += `</tbody>
                     </table>`
            }

            let templateParams = {
                customer_name: full_name,
                customer_phone: phone,
                customer_addr: addr,
                customer_total: gFormatCurrency(total_order, "VND"),
                customer_note: note,
                message: html,
                customer_date: showToday(),
            };

            emailjs.send('service_wc467ip', 'template_zfvbd5n', templateParams)
                .then(function(response) {
                    alert('Gửi mail thành công! Kiểm tra email nào', response.status, response.text);
                    $('#send_form').text("Gửi")
                    $('#selectedInfo').html("")
                    formReset()
                }, function(error) {
                    $('#send_form').text("Gửi")
                    alert('Gửi mail thất bại, alo kĩ thuật nhen...', error);
                });
        })


    });
</script>

</body>
</html>
