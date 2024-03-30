<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Party</title>
    <meta content="Yeni" name="description">
    <meta content="yeni, bong bong" name="keywords">
    <meta property="og:title" content="Yeni">
    <meta property="og:type" content="Website">
    <meta property="og:url" content="https://yeniparty.com/">
    <meta property="og:description" content="Yeni party Phụ kiện trang trí Sinh Nhật">
    <meta property="og:image" content="<?= BASE_URL . 'image/yeni_logo.jpg' ?>"/>
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL . 'image/yeni_logo.jpg' ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css"/>
    <script src="<?= BASE_URL . 'js/common.js' ?>"></script>
    <script src="<?= BASE_URL . 'js/product.js' ?>"></script>
    <link rel="stylesheet" href="<?= BASE_URL;?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
    <script src="<?= BASE_URL;?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <style>
        .text-red{
            cursor: pointer;
            color: red;
        }
        .form-control{
            border: 1px solid black;
        }
        .font-weight-bold{
            font-weight: 700;
            text-align: center;
        }
        .text-align-center{
            text-align: center;
        }
        .vertical-align-top{
            vertical-align: top;
        }
        .btn-a{
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Section 1: Hiển thị thông tin -->
    <div class="mb-4 mt-2"><a href="yeni/report/" class="btn-a"><button class="btn btn-success">Đi đến Màn hình quản lý</button></a></div>
    <div id="infoSection">
        <h2>Thông tin đặt hàng:</h2>
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
                        <label>Ghi chú: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="note" name="note" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Tiền ship: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="ship" name="ship" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Tổng tiền: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="total_order"  readonly name="total_order" value="" autocomplete="off"/>
                        <input type="hidden" class="form-control" id="total_order_hide" name="total_order_hide" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Ngày đặt: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control datepicker" id="order_date"  readonly name="order_date" value=""/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Thực nhận: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="total_actual" name="total_actual" value="" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <form id="form_item" action="" name="form_item">
            <div id="selectedInfo" class="mb-2"></div>
            <div class="mb-2" style="text-align: end">Tổng tiền hàng: <label id="total_orders"></label></div>
            <div><input type="hidden" id="total_orders_hide"></div>
        </form>
        <div class="row mb-2">
            <div class="col-6" style="display:flex;justify-content: flex-start">
                <button type="button" id="send_form" class="btn btn-primary me-3">Tạo order</button>
                <button type="button" id="reset_form" class="btn btn-danger me-3">Reset</button>
                <button type="button" id="show-modal" class="btn btn-success">Xem lại order</button>
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
                <th>Giá mua</th>
                <th>Giá bán</th>
                <th>Tồn kho</th>
            </tr>
            </thead>
            <tbody id="jsonDisplay">
                <?php if(!empty($list_products)) : ?>
                    <?php foreach($list_products as $key => $value) : ?>
                        <tr data-set="<?= $value['is_set'] ?>">
                            <td>
                                <strong><?= $value['code'] ?></strong><br>
                            </td>
                            <td>
                                <label><?= $value['name'] ?></label> <br>
                            </td>
                            <td>
                                <strong><?= $value['p_price'] ?></strong><br>
                            </td>
                            <td>
                                <strong><?= $value['q_price'] ?></strong><br>
                            </td>
                            <td>
                                <strong><?= $value['total'] ?></strong><br>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="min-height: 500px">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận order</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Đóng</button>
                <button type="button" id="confirm_order" class="btn btn-primary">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<!-- Include thư viện SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
<script type="text/javascript">
    (function(){
        emailjs.init("IixI2U_PjLkTBP_Ue");
    })();
</script>

<script>
    function exportToExcel(file_name) {
        let file_export = file_name + ".xlsx"
        var table = document.getElementById("table_export");
        var wb = XLSX.utils.table_to_book(table);
        XLSX.writeFile(wb, file_export);
    }

    function showToday(){
        const currentDate = new Date($('#order_date').val());
        const day = currentDate.getDate();
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        const hours = currentDate.getHours();
        const minutes = currentDate.getMinutes();
        const seconds = currentDate.getSeconds();
        return `${day}/${month}/${year}`
    }

    function showOrderCode(){
        const currentDate = new Date();
        const day = currentDate.getDate();
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        const hours = currentDate.getHours();
        const minutes = currentDate.getMinutes();
        const seconds = currentDate.getSeconds();
        return `ZALO_${day}_${month}_${year}_${hours}_${minutes}_${seconds}`
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

    function removeRow(element)
    {
        var nearestRoot = element.closest('.root');

        if (nearestRoot.length > 0) {
            nearestRoot.remove()
        }
    }

    function formReset()
    {
        $('#full_name').val("")
        $('#phone').val("")
        $('#addr').val("")
        $('#total_order').val("")
        $('#note').val("")
        $('#ship').val("")
        $('#total_orders').text("")
        $('#total_actual').val("")
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
        $('.datepicker').datepicker({
            orientation: "bottom left",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            todayHighlight: true,
        });
        $('.datepicker').datepicker("setDate",new Date())

        let total_price_product = 0
        let table = new DataTable('#dataTable', {
            columns: [
                null,
                null,
                { searchable: false },
                { searchable: false },
                { searchable: false },
            ]
        });
        $('#dataTable tbody tr').hover(function(){
            $(this).css('cursor', "pointer")
        })
        $('#dataTable tbody').on('click', 'tr', function() {
            let data = table.row(this).data();
            let code = $(data[0]).text()
            let name = $(data[1]).text()
            let price = $(data[3]).text()
            let html = `<div class="row root" id="${code}">
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-1">
                                    <label class="text-red" data-id="${code}" onclick="removeRow($(this))"> Xóa </label>
                                </div>
                                <div class="col-2">
                                    <label>Mã: </label>
                                    <input type="text" class="form-control" name="code" value="${code}" />
                                </div>

                                <div class="col-4">
                                    <label>Tên: </label>
                                    <input type="text" class="form-control" name="name" value="${name}" />
                                </div>
                                <div class="col-1">
                                    <label>Số lượng: </label>
                                    <input type="number" class="form-control qty" name="quantity" value="0" />
                                </div>
                                <div class="col-2">
                                    <label>Đơn giá: </label>
                                    <input type="text" class="form-control price" name="price" value="${price}" />
                                </div>
                                <div class="col-2">
                                    <label>Thành tiền: </label>
                                    <input type="text" class="form-control price_display" readonly name="price_display" value="0" />
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>`

            $('#selectedInfo').append(html)
            $('.price').change(function()
            {
                total_price_product = 0
                $('.price').each(function (index)
                {
                    let row = $(this).closest('div.row');
                    let qtyInput  = row.find('.qty').val()
                    total_price_product += (parseFloat($(this).val()) * parseFloat(qtyInput))
                    row.find('.price_display').val((parseFloat($(this).val()) * parseFloat(qtyInput)))
                });
                $('#total_orders_hide').val(total_price_product)
                $('#total_orders').text(gFormatCurrency(total_price_product, "VND"))
                let ship = $('#ship').val() !== "" ? $('#ship').val() : 0
                let total_order_hide = total_price_product + parseFloat(ship)

                $('#total_order').val(gFormatCurrency(total_order_hide, "VND"))
                $('#total_order_hide').val(total_order_hide)
                $('#total_actual').val(total_price_product)

            })

            $('.qty').change(function()
            {
                total_price_product = 0
                $('.qty').each(function (index)
                {
                    let row = $(this).closest('div.row');
                    let qtyInput  = row.find('.price').val()
                    total_price_product += (parseFloat($(this).val()) * parseFloat(qtyInput))
                    row.find('.price_display').val((parseFloat($(this).val()) * parseFloat(qtyInput)))
                });
                $('#total_orders_hide').val(total_price_product)
                $('#total_orders').text(gFormatCurrency(total_price_product, "VND"))
                let ship = $('#ship').val() !== "" ? $('#ship').val() : 0
                let total_order_hide = total_price_product + parseFloat(ship)

                $('#total_order').val(gFormatCurrency(total_order_hide, "VND"))
                $('#total_order_hide').val(total_order_hide)
                $('#total_actual').val(total_price_product)
            })

        });
        //ship change
        $('#ship').change(function()
        {
            total_price_product = $('#total_orders_hide').val()
            let ship = $('#ship').val() !== "" ? $('#ship').val() : 0
            let total_order_hide = parseFloat(total_price_product) + parseFloat(ship)
            $('#total_order').val(gFormatCurrency(total_order_hide, "VND"))
            $('#total_order_hide').val(total_order_hide)
            $('#total_actual').val(total_price_product)
        })
        //reset
        $('#reset_form').click(function(){
            formReset()
        })

        var array_form = []
        var html_message = ""
        //send form
        $('#send_form').click(function(){
            let full_name = $('#full_name').val()
            let phone = $('#phone').val()
            let addr = $('#addr').val()
            let total_order = $('#total_order').val()
            let total_order_form = $('#total_order_hide').val()
            let total_actual = $('#total_actual').val()
            let note = $('#note').val()
            let ship = $('#ship').val()
            let order_date = showToday()
            let order_code = showOrderCode()
            let array_info = ["Tên: " + full_name, "ĐT: " +phone, "Địa chỉ: " + addr, "Tiền hàng: " + gFormatCurrency(total_price_product, "VND"), "Ghi chú: " + note,"Tiền ship: " +gFormatCurrency(ship, "VND"), "Tổng tiền: " + total_order,"Ngày: " + order_date]
            let serializedArray = $("form").serializeArray();
            let groupedInputs = groupByName(serializedArray);
            let code_array = groupedInputs['code']
            let qty_array = groupedInputs['quantity']
            let name_array = groupedInputs['name']
            let price_array = groupedInputs['price']
            array_form = [order_code, full_name, phone, addr, total_order_form, note, ship, code_array, qty_array, price_array, order_date, total_actual, name_array]
            let html = ""
            if(code_array != null && code_array.length > 0)
            {
                html = `<table id="table_export" class="table-bordered" style="border-collapse: collapse; width: 100%;" border="1"><colgroup><col style="width: 20%;"><col style="width: 10%;"><col style="width: 30%;"><col style="width: 10%;"><col style="width: 15%;"><col style="width: 15%;"><col style="width: 15%;"></colgroup>
                        <tbody>
                        <tr>
                        <td class="font-weight-bold">Thông tin</td>
                        <td class="font-weight-bold">Mã hàng</td>
                        <td class="font-weight-bold">Tên hàng</td>
                        <td class="font-weight-bold">Số lượng</td>
                        <td class="font-weight-bold">Đơn giá</td>
                        <td class="font-weight-bold">Thành tiền</td>
                        </tr>`;
                if(code_array.length > array_info.length)
                {
                    code_array.forEach(function(element, index) {
                        if(index == 0)
                        {
                            html += `<tr>
                                <td rowspan="${code_array.length}" class="vertical-align-top">${array_info[0]}<br>${array_info[1]}<br>${array_info[2]}<br>${array_info[3]}<br>${array_info[4]}<br>${array_info[5]}<br><span style="font-weight: 700;color: red">${array_info[6]}</span><br>${array_info[7]}</td>
                                <td>${element}</td>
                                <td>${name_array[index]}</td>
                                <td class="text-align-center">${qty_array[index]}</td>
                                <td class="text-align-center">${price_array[index]}</td>
                                <td class="text-align-center">${gFormatCurrency(price_array[index] * qty_array[index], "VND")}</td>
                            </tr>`
                        }else if(index > 8){
                            html += `<tr>
                                <td>${element}</td>
                                <td>${name_array[index]}</td>
                                <td class="text-align-center">${qty_array[index]}</td>
                                <td class="text-align-center">${price_array[index]}</td>
                                <td class="text-align-center">${gFormatCurrency(price_array[index] * qty_array[index], "VND")}</td>
                            </tr>`
                        }
                        else{
                            html += `<tr>
                                <td>${element}</td>
                                <td>${name_array[index]}</td>
                                <td class="text-align-center">${qty_array[index]}</td>
                                <td class="text-align-center">${price_array[index]}</td>
                                <td class="text-align-center">${gFormatCurrency(price_array[index] * qty_array[index], "VND")}</td>
                            </tr>`
                        }
                    });
                }else{
                    array_info.forEach(function(element, index) {
                        if(index == 0)
                        {
                            html += `<tr>
                                <td rowspan="${array_info.length}" class="vertical-align-top">${array_info[0]}<br>${array_info[1]}<br>${array_info[2]}<br>${array_info[3]}<br>${array_info[4]}<br>${array_info[5]}<br><span style="font-weight: 700;color: red">${array_info[6]}</span><br>${array_info[7]}</td>
                                <td>${code_array[index]}</td>
                                <td>${name_array[index]}</td>
                                <td class="text-align-center">${qty_array[index]}</td>
                                <td class="text-align-center">${price_array[index]}</td>
                                <td class="text-align-center">${gFormatCurrency(price_array[index] * qty_array[index], "VND")}</td>
                            </tr>`
                        }else if((index+1) > code_array.length){
                            html += `<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>`
                        }
                        else{
                            html += `<tr>
                                <td>${code_array[index]}</td>
                                <td>${name_array[index]}</td>
                                <td class="text-align-center">${qty_array[index]}</td>
                                <td class="text-align-center">${price_array[index]}</td>
                                <td class="text-align-center">${gFormatCurrency(price_array[index] * qty_array[index], "VND")}</td>
                            </tr>`
                        }

                    });
                }


                html += `</tbody>
                     </table>`
            }



            $('#modal-body').html(html)
            html_message = html
            $('.modal').css("display","block")
        })

        $('#confirm_order').click(function(){
            $('#confirm_order').text("Đang gửi. Chờ xíu nha..")
            //call ajax
            let url = "<?= BASE_URL?>pages";
            $.ajax({
                type: "POST",
                data: {
                    array_form: array_form,
                    html_message: html_message
                },
                dataType: "json",
                headers: {
                    'X-CSRF-Token': <?= json_encode($this->request->getAttribute('csrfToken')); ?>
                },
                url: url,
                success: function(data) {
                    if(data.status)
                    {
                        alert('Tạo order thành công');
                        //export excel
                        let full_name = $('#full_name').val()

                        // exportToExcel(full_name.replace(/\s+/g, '_'))

                        let phone = $('#phone').val()
                        let addr = $('#addr').val()
                        let total_order = $('#total_order').val()
                        let total_actual = $('#total_actual').val()
                        let note = $('#note').val()
                        let ship = $('#ship').val()
                        let order_code = showOrderCode()
                        let templateParams = {
                            customer_name: full_name,
                            customer_phone: phone,
                            customer_addr: addr,
                            customer_total: total_order,
                            customer_note: note,
                            customer_ship: ship,
                            customer_total_actual: total_actual,
                            customer_order_code: order_code,
                            message: html_message,
                            customer_date: showToday(),
                            to_email: "buibavuong123456@gmail.com",
                        };

                        emailjs.send('service_wc467ip', 'template_zfvbd5n', templateParams)
                            .then(function(response) {

                            }, function(error) {

                            });


                        $('#confirm_order').text("Xác nhận")
                        $('#selectedInfo').html("")
                        formReset()
                    }else{
                        $('#confirm_order').text("Xác nhận")
                        alert('Tạo order thất bại, alo kĩ thuật nhen...');
                    }
                    $('.modal').css("display","none")
                },
                error: function() {
                    $('#confirm_order').text("Xác nhận")
                    alert('Tạo order thất bại, alo kĩ thuật nhen...');
                    $('.modal').css("display","none")
                }
            });
        })

        $('#show-modal').click(function()
        {
            $('.modal').css("display","block")
        })

        $('.close-modal').click(function()
        {
            $('.modal').css("display","none")
        })

    });
</script>

</body>
</html>
