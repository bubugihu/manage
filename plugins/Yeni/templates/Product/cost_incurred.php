
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
</style>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">

        <div class="form-title mx-3">
            <?= __("List Order") ?>
        </div>
        <div class="card m-3 rounded-0">
            <form id="form-filter" class="form-filter" action="/yeni/product/cost-incurred" method="get">
            <div class="card-header p-1">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <ul class="nav nav-pills d-none">
                            <li class="nav-item px-1">
                                <label class="btn btn-outline-secondary rounded-0" for="importCost">
                                    <i class="fa-solid fa-plus"></i>
                                    <?= __('Cost Incurred') ?>
                                </label>
                                <?= $this->Form->create(null, [
                                    'url' => '/yeni/product/costIncurred',
                                    'method' => 'post',
                                    'id' => 'cost_incurred',
                                    'class' => ['d-none'],
                                    'enctype' => 'multipart/form-data'
                                ]); ?>
                                <input type="file" id="importCost" name="file_import">
                                <?= $this->Form->end(); ?>
                            </li>

                        </ul>
                        <ul class="nav nav-pills">
                            <li class="nav-item px-1 ">
                                <select class="form-select" name="month" id="month">
                                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                                        <option value="<?= $i ?>"
                                        <?= !empty($month) && $month == $i ? 'selected' : '' ?>
                                        ><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </li>
                            <li class="nav-item px-1 ">
                                <select class="form-select" name="year" id="year">
                                    <?php for ($i = 2023; $i <= 2025; $i++) : ?>
                                        <option value="<?= $i ?>"
                                            <?= !empty($year) && $year == $i ? 'selected' : '' ?>
                                        ><?= $i ?></option>
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
                                                <input type="text" class="form-control" placeholder="<?= __('type') ?> ..." name="key_search" value="<?= $_GET['key_search'] ?? ''; ?>" autocomplete="off"/>
                                                <button class="btn btn-primary" type="submit"><?= __('Search') ?></button>
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
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0" style="background-color: #c5c5c5;">
                        <div class="table-responsive">
                            <div class="css-table action-dropdown">
                                <div class="css-table-header">
                                    <div style="width: 1%"><br><?= __('Name') ?></div>
                                    <div style="width: 3%"><br><?= __('Unit') ?></div>
                                    <div style="width: 2%"><br><?= __('Quantity') ?></div>
                                    <div style="width: 2%"><br><?= __('Value') ?></div>
                                    <div style="width: 2%"><br><?= __('Note') ?></div>
                                    <div style="width: 2%"><br><?= __('Date') ?></div>
                                </div>

                                <?php if (!empty($list_result)) : ?>
                                    <div class="css-table-body">
                                        <?php foreach ($list_result as $key => $value) : ?>
                                            <div class="css-table-row-input table-striped">
                                                <div>
                                                    <?= $value->name ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->unit_name ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->quantity ?? 0 ?>
                                                </div>
                                                <div>
                                                    <?= !empty($value->value) ? number_format($value->value) : 0 ?>
                                                </div>
                                                <div>
                                                    <?= $value->note ?? 0 ?>
                                                </div>
                                                <div>
                                                    <?= $value->in_date->format('Y-m-d') ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
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
                    <?php echo $paginate ?? ''; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/js/chart.js/Chart.min.js"></script>


<script>
    $(document).ready(function() {
        // set month year
        $('#month').on("change", function () {
            $('#form-filter').submit();
        });

        $('#year').on("change", function () {
            $('#form-filter').submit();
        });

        $('#importCost').on("change", function () {
            $('#form-filter').submit();
        });

        })
</script>
