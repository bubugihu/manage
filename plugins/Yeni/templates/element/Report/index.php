
<style>
    #lineChart {
        width: 950px; /* Set the desired width */
        height: 400px; /* Set the desired height */
    }
    .chart{
        height: 400px;
        width: 1000px;
    }
    .dropdown-item-text{
        margin: 1px 10px;
        text-decoration: none;
        color: black;
    }

    .bordered{
        font-weight: bold;
    }
    .text-decoration-unset{
        text-decoration: unset;
    }
</style>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">

        <!-- Canvas for drawing the line chart -->
        <div class="chart">
            <canvas id="lineChart" width="950" height="400"></canvas>
        </div>
        <div class="form-title mx-3">
            <?= __("List Order") ?>
        </div>
        <div class="card m-3 rounded-0">
            <form id="form-filter" class="form-filter" action="/yeni/report/" method="get">
            <div class="card-header p-1">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <ul class="nav nav-pills d-none">
                            <li class="nav-item px-1">
                                <label class="btn btn-outline-secondary rounded-0" for="importOrder">
                                    <i class="fa-solid fa-plus"></i>
                                    <?= __('Load orders') ?>
                                </label>
                                <?= $this->Form->create(null, [
                                    'url' => 'yeni/report/importOrder',
                                    'method' => 'post',
                                    'id' => 'uploadFile',
                                    'class' => ['d-none'],
                                    'enctype' => 'multipart/form-data'
                                ]); ?>
                                <input type="file" id="importOrder" name="file_import">
                                <?= $this->Form->end(); ?>
                            </li>
                            <li class="nav-item px-1">
                                <label class="btn btn-outline-secondary rounded-0" for="exportOrder">
                                    <i class="fa-solid fa-minus"></i>
                                    <?= __('Export orders') ?>
                                </label>
                                <?= $this->Form->create(null, [
                                    'url' => 'yeni/report/exportOrder',
                                    'method' => 'post',
                                    'id' => 'uploadFile',
                                    'class' => ['d-none'],
                                    'enctype' => 'multipart/form-data'
                                ]); ?>
                                <input type="submit" id="exportOrder" name="file_export">
                                <?= $this->Form->end(); ?>
                            </li>

                        </ul>
                        <ul class="nav nav-pills">
                            <li class="nav-item px-1 ">
                                <select class="form-select" name="month" id="month">
                                    <?php for($i = 1; $i <= 12; $i++) : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </li>
                            <li class="nav-item px-1 ">
                                <select class="form-select" name="year" id="year">
                                    <?php for($i = 2023; $i <= 2025; $i++) : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </li>
                        </ul>
                        <div class="nav-end">
                            <div class="nav-end">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <div class="bb-search">
                                                <i class="fa fa-search"></i>
                                                <input type="text" class="form-control" placeholder="<?= __("type") ?> ..." name="key_search" value="<?= $_GET['key_search'] ?? ''; ?>" autocomplete="off"/>
                                                <button class="btn btn-primary" type="submit"><?= __("Search") ?></button>
                                            </div>
                                        </li>
                                    </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            </form>

            <div class="card-body">
                <div class="row">
                    <div class="col-3 bordered">
                        Current Income Shopee
                    </div>
                    <div class="col-3 bordered">
                        Current Income Zalo
                    </div>
                    <div class="col-3 bordered">
                        Current OutCome
                    </div>
                    <div class="col-3 bordered">
                        Current Cost Incurred
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                         <?= $total_income_shopee ?? 0 ?>
                    </div>
                    <div class="col-3">
                        <?= $total_income_zalo ?? 0 ?>
                    </div>
                    <div class="col-3">
                        <?= $total_outcome ?? 0 ?>
                    </div>
                    <div class="col-3">
                        <?= $this->Html->link(
                            $total_incurred ?? 0,
                            [
                                'controller' => 'Product',
                                'action' => 'costIncurred',
                                '?' => [ // Thêm query string
                                    'month' => $month ?? 1,
                                    'year' => $year ?? 2025
                                ]
                            ],
                            [
                                'class' => 'text-decoration-unset',
                                'target'=>  'blank'
                            ]
                        ) ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0" style="background-color: #c5c5c5;">
                        <div class="table-responsive">
                            <div class="css-table action-dropdown">
                                <div class="css-table-header">
                                    <div style="width: 1%"><br><?= __("Action") ?></div>
                                    <div style="width: 3%"><br><?= __("Code") ?></div>
                                    <div style="width: 2%"><br><?= __("Customer") ?></div>
                                    <div style="width: 2%"><br><?= __("Address") ?></div>
                                    <div style="width: 2%"><br><?= __("Total") ?></div>
                                    <div style="width: 2%"><br><?= __("Total Actual") ?></div>
                                    <div style="width: 2%"><br><?= __("Status") ?></div>
                                </div>

                                <?php if (!empty($list_orders)) : ?>
                                    <div class="css-table-body">
                                        <?php foreach ($list_orders as $key => $value) : ?>
                                            <div class="css-table-row-input table-striped">
                                                <div class="action-col">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="dropdown dropend" title="More Action">
                                                            <button type="button" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical text-secondary"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="/yeni/report/view/<?= $value->id . $this->Format->renderParameterURL() ?>" class="view dropdown-item-text" target="_blank" title="View">
                                                                        <i class="fa fa-eye"></i> Xem
                                                                    </a>
                                                                </li>
                                                                <?php if($value->status == STATUS_QUOTING_NEW) :?>
                                                                <li>
                                                                    <a href="/yeni/report/delete/<?= $value->order_code. $this->Format->renderParameterURL() ?>" onclick="return confirm('Are you sure?');" class="delete dropdown-item-text" title="View">
                                                                        <i class="fa fa-trash"></i> Xoá
                                                                    </a>
                                                                </li>
                                                                <?php endif; ?>
                                                                <?php if($value->status == STATUS_QUOTING_NEW) :?>
                                                                    <li>
                                                                        <a href="/yeni/report/confirm/<?= $value->order_code . $this->Format->renderParameterURL() ?>" onclick="return confirm('Are you sure?');" class="confirm dropdown-item-text" title="View">
                                                                            <i class="fa fa-check-circle"></i> Xác nhận
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div>
                                                    <?= $value->order_code ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->customer_name ?? "" ?>
                                                </div>
                                                <div>
                                                    <?= $value->customer_addr ?? "" ?>
                                                </div>
                                                <div>
                                                    <?= $value->total_display ?? 0 ?>
                                                </div>
                                                <div>
                                                    <?= $value->total_actual_display ?? 0 ?>
                                                </div>
                                                <div>
                                                    <?= $value->status_display ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="caption-footer">
                                        No Data Available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-bottom">
                <div class="d-flex flex-row justify-content-between align-items-center px-4 py-0">
                    <?php echo (isset($paginate)) ? $paginate : ''; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/js/chart.js/Chart.min.js"></script>


<script>
    $(document).ready(function() {
        // set month year
        const current_month = <?= $month ?? 1 ?>;
        const current_year = <?= $year ?? 2024 ?>;
        $('#month').val(current_month)
        $('#year').val(current_year)

        $('#month').on("change", function () {
            $('#form-filter').submit();
        });

        $('#year').on("change", function () {
            $('#form-filter').submit();
        });

        $('#importOrder').on("change", function () {
            $('#uploadFile').submit();
        });

        const labels = [
            <?php if (isset($labels) && count($labels) > 0) foreach ($labels as $day => $val) echo '"'. $val . '", ' ?>
        ]
        labels.push("Next")
        const data_count_order_zalo = [
            <?php if (isset($labels) && count($labels) > 0)
                foreach ($labels as $key => $val)
                {
                    if(isset($getMonthlyYear['zalo'][$key]))
                    {
                        echo '"'. $getMonthlyYear['zalo'][$key]->count_order . '", ' ;
                    }else{
                        echo '"'. 0 . '", ' ;
                    }
                }

            ?>
        ]

        const data_sum_price_zalo = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($getMonthlyYear['zalo'][$key]))
                {
                    echo '"'. $getMonthlyYear['zalo'][$key]->sum_price . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_count_order_total = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($getMonthlyYear['total'][$key]))
                {
                    echo '"'. $getMonthlyYear['total'][$key]["count_order"] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_sum_price_total = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($getMonthlyYear['total'][$key]))
                {
                    echo '"'. $getMonthlyYear['total'][$key]["sum_price"] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_profit = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($list_profit['total'][$key]))
                {
                    echo '"'. $list_profit['total'][$key]["profit"] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_expense = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($list_profit['total'][$key]))
                {
                    echo '"'. $list_profit['total'][$key]["expense"] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_profit_shopee = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($list_profit['SHOPEE'][$key]))
                {
                    echo '"'. $list_profit['SHOPEE'][$key]["profit"] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_profit_zalo = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($list_profit['ZALO'][$key]))
                {
                    echo '"'. $list_profit['ZALO'][$key]["profit"] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_out_come = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($list_outcome[$key]))
                {
                    echo '"'. $list_outcome[$key] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }
            ?>
        ]

        const data_cost_inventory = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($cost_inventory[$key]))
                {
                    echo '"'. $cost_inventory[$key] . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }
            ?>
        ]

        const data_count_order_shopee = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($getMonthlyYear['shopee'][$key]))
                {
                    echo '"'. $getMonthlyYear['shopee'][$key]->count_order . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]

        const data_sum_price_shopee = [
            <?php if (isset($labels) && count($labels) > 0)
            foreach ($labels as $key => $val)
            {
                if(isset($getMonthlyYear['shopee'][$key]))
                {
                    echo '"'. $getMonthlyYear['shopee'][$key]->sum_price . '", ' ;
                }else{
                    echo '"'. 0 . '", ' ;
                }
            }

            ?>
        ]


        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    borderColor: 'blue',
                    data: data_sum_price_total,
                    fill: false,
                },
                {
                    label: 'Profit',
                    borderColor: 'green',
                    data: data_profit,
                    fill: false,
                },
                {
                    label: 'Outcome',
                    borderColor: 'red',
                    data: data_out_come,
                    fill: false,
                },
                {
                    label: 'Inventory',
                    borderColor: 'yellow',
                    data: data_cost_inventory,
                    fill: false,
                },
            ],
        };

// Chart configuration
        const config = {
            type: 'line',
            data: data,
            options: {
                tooltips: {
                    callbacks: {
                        title: function(tooltipItems, data) {
                            if(tooltipItems[0].datasetIndex == 0)
                            {
                                return `Shopee: ${data_count_order_shopee[tooltipItems[0].index]}  Orders, Income: ${gFormatCurrency(data_sum_price_shopee[tooltipItems[0].index], "VND")} \nZalo: ${data_count_order_zalo[tooltipItems[0].index]}  Orders, Income: ${gFormatCurrency(data_sum_price_zalo[tooltipItems[0].index], "VND")} \n`
                            }else if(tooltipItems[0].datasetIndex == 1){
                                return `Shopee: ${gFormatCurrency(data_profit_shopee[tooltipItems[0].index], "VND")} \nZalo: ${gFormatCurrency(data_profit_zalo[tooltipItems[0].index], "VND")} \nExpense: -${gFormatCurrency(data_expense[tooltipItems[0].index], "VND")} \n`
                            }else{
                                return `Total: ${gFormatCurrency(data_out_come[tooltipItems[0].index], "VND")}`
                            }
                        },
                        label: function(tooltipItem, data) {
                            if(tooltipItem.datasetIndex == 0)
                            {
                                return `Total: ${data_count_order_total[tooltipItem.index]}  Orders, Income: ${gFormatCurrency(data_sum_price_total[tooltipItem.index], "VND")}`
                            }else if(tooltipItem.datasetIndex == 1){
                                return `Total: ${gFormatCurrency(data_profit[tooltipItem.index], "VND")}`
                            }else{
                                return `Outcome: ${gFormatCurrency(data_out_come[tooltipItem.index], "VND")}`
                            }
                        }
                    },
                },
                scales: {
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return gFormatCurrency(value,"");
                            }
                        }
                    }]
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillStyle = '#000';
                                ctx.fillText(gFormatCurrency(data,""), bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        };

// Get the canvas element
        const ctx = document.getElementById('lineChart').getContext('2d');

// Create the line chart
        const myLineChart = new Chart(ctx, config);

    })

</script>
