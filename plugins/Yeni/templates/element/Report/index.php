
<style>
    #lineChart {
        width: 950px; /* Set the desired width */
        height: 400px; /* Set the desired height */
    }
    .chart{
        height: 400px;
        width: 1000px;
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
            <div class="card-header p-1">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <ul class="nav nav-pills">
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
                        </ul>

                        <div class="nav-end">
                            <div class="nav-end">
                                <form class="form-filter" action="yeni/report/" method="get">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <div class="bb-search">
                                                <i class="fa fa-search"></i>
                                                <input type="text" class="form-control" placeholder="<?= __("type") ?> ..." name="key_search" value="<?= $_GET['key_search'] ?? ''; ?>" autocomplete="off"/>
                                                <button class="btn btn-primary" type="submit"><?= __("Search") ?></button>
                                            </div>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                        </div>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0" style="background-color: #c5c5c5;">
                        <div class="table-responsive">
                            <div class="css-table action-dropdown">
                                <div class="css-table-header">
                                    <div style="width: 1%"><br><?= __("Action") ?></div>
                                    <div style="width: 3%"><br><?= __("Code") ?></div>
                                    <div style="width: 1%"><br><?= __("Date") ?></div>
                                    <div style="width: 2%"><br><?= __("Customer") ?></div>
                                    <div style="width: 2%"><br><?= __("Phone") ?></div>
                                    <div style="width: 2%"><br><?= __("Address") ?></div>
                                    <div style="width: 5%"><br><?= __("Shipping") ?></div>
                                    <div style="width: 2%"><br><?= __("Total") ?></div>
                                    <div style="width: 2%"><br><?= __("Note") ?></div>
                                </div>

                                <?php if (!empty($list_orders)) : ?>
                                    <div class="css-table-body">
                                        <?php foreach ($list_orders as $key => $value) : ?>
                                            <div class="css-table-row-input table-striped">
                                                <div class="action-col">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="/quoting/view/<?= $value->id . $this->Format->renderParameterURL() ?>" class="view" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="/quoting/delete/<?= $value->id . $this->Format->renderParameterURL() ?>" onclick="return confirm('Are you sure?');" class="delete" title="View">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>

                                                </div>
                                                <div>
                                                    <?= $value->order_code ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->order_date->toDateString() ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->customer_name ?? "" ?>
                                                </div>
                                                <div>
                                                    <?= $value->customer_phone ?? "" ?>
                                                </div>
                                                <div>
                                                    <?= $value->customer_addr ?? "" ?>
                                                </div>
                                                <div>
                                                    <?= $value->shipping ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->total_display ?? 0 ?>
                                                </div>
                                                <div>
                                                    <?= $value->note ?>
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
                    label: 'Income 2023',
                    borderColor: 'blue',
                    data: data_sum_price_total,
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
                            return `Shopee: ${data_count_order_shopee[tooltipItems[0].index]}  Orders, Income: ${gFormatCurrency(data_sum_price_shopee[tooltipItems[0].index], "VND")} \nZalo: ${data_count_order_zalo[tooltipItems[0].index]}  Orders, Income: ${gFormatCurrency(data_sum_price_zalo[tooltipItems[0].index], "VND")} \n`
                        },
                        label: function(tooltipItem, data) {
                            return `Total: ${data_count_order_total[tooltipItem.index]}  Orders, Income: ${gFormatCurrency(data_sum_price_total[tooltipItem.index], "VND")}`
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