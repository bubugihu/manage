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
                        <input type="text" class="form-control" id="full_name" name="full_name" value="" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Số đt: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="phone" name="phone" value="" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Địa chỉ giao: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="addr" name="addr" value="" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Tổng tiền: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="total_order" name="total_order" value="" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Ghi chú: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="note" name="note" value="" />
                    </div>
                </div>
            </div>
        </div>
        <div id="selectedInfo" class="mb-2"></div>
        <div class="row mb-2">
            <div class="col-2" style="display:flex;justify-content: flex-start">
                <button type="button" id="send_form" class="btn btn-primary me-3">Gửi</button>
                <button type="button" id="reset_form" class="btn btn-danger">Reset</button>
            </div>
        </div>
    </div>

    <!-- Section 2: DataTable -->
    <div id="dataTableSection">
        <form id="form_item" >
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Số lượng</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody id="jsonDisplay">
                </tbody>
            </table>
        </form>
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

                    let html = `<div class="row" id="${code}">
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-1">
                                    <label class="text-red" data-id="${code}" onclick="removeRow('${code}')"> Remove </label>
                                </div>
                                <div class="col-3">
                                    <label>Code: </label>
                                    <input type="text" name="code" value="${code}" />
                                </div>
                                <div class="col-1">
                                </div>
                                <div class="col-3">
                                    <label>Quantity: </label>
                                    <input type="number" name="quantity" value="" />
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>`

                    $('#selectedInfo').append(html)

                    //

                });
            })
            .catch(error => console.error('Error fetching JSON:', error));

        //reset
        $('#reset_form').click(function(){
            formReset()
        })

        //send form
        $('#send_form').click(function(){
            formReset()
        })


    });
</script>

</body>
</html>
