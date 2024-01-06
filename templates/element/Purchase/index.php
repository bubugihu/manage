<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <div class="form-title mx-3">
            <?= __("List Purchasing") ?>
        </div>
        <div class="card m-3 rounded-0">
            <div class="card-header p-1">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="btn btn-success rounded-0" href="/manage-system/create" id="btn-add-form">
                                        <i class="fa-solid fa-plus"></i>
                                        <?= __("New") ?>
                                    </a>
                                </li>

<!--                                <li class="nav-item px-1">-->
<!--                                    <a id="exportExcelBtn" class="btn btn-outline-secondary rounded-0" href="/manage-system/export">-->
<!--                                        <i class="fa-solid fa-file-export"></i>-->
<!--                                        --><?//= __("Export EXCEL") ?>
<!--                                    </a>-->
<!--                                </li>-->
                                <li class="nav-item px-1">
                                    <label class="btn btn-outline-secondary rounded-0" for="importPurchase">
                                        <i class="fa-solid fa-plus"></i>
                                        <?= __('Import EXCEL') ?>
                                    </label>
                                    <?= $this->Form->create(null, [
                                        'url' => '/purchase/import',
                                        'method' => 'post',
                                        'id' => 'uploadFile',
                                        'class' => ['d-none'],
                                        'enctype' => 'multipart/form-data'
                                    ]); ?>
                                    <input type="file" id="importPurchase" name="file_import">
                                    <?= $this->Form->end(); ?>
                                </li>

                        </ul>

                        <div class="nav-end">
                            <div class="nav-end">
                                <form class="form-filter" action="/purchase/" method="get">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <select name="key_status" class="form-select" id="key_status" >
                                                <option value="" >Status ALL</option>
                                                <option value="0" <?= (isset($_GET['key_status']) && $_GET['key_status'] == "0") ? "selected" : "" ?> >Status New</option>
                                                <option value="1" <?= (isset($_GET['key_status']) && $_GET['key_status'] == "1") ? "selected" : "" ?> >Status Process</option>
                                                <option value="1" <?= (isset($_GET['key_status']) && $_GET['key_status'] == "2") ? "selected" : "" ?> >Status Done</option>
                                            </select>
                                        </li>
                                        <li class="nav-item">
                                            <div class="bb-search">
                                                <i class="fa fa-search"></i>
                                                <input type="text" class="form-control" placeholder="<?= __("type") ?> ..." name="key_search" value="<?= isset($key_search) ? $key_search : ''; ?>" />
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
                                    <div style="width: 2%"><br><?= __("Action") ?></div>
                                    <div style="width: 5%"><br><?= __("Date") ?></div>
                                    <div style="width: 5%"><br><?= __("Code Product") ?></div>
                                    <div style="width: 5%"><br><?= __("Name Product") ?></div>
                                    <div style="width: 5%"><br><?= __("Unit") ?></div>
                                    <div style="width: 25%"><br><?= __("Quantity") ?></div>
                                    <div style="width: 5%"><br><?= __("Price") ?></div>
                                    <div style="width: 5%"><br><?= __("Total") ?></div>
                                    <div style="width: 5%"><br><?= __("Source") ?></div>
                                    <div style="width: 5%"><br><?= __("Date") ?></div>
                                    <div style="width: 5%"><br><?= __("Actual Date") ?></div>
                                    <div style="width: 5%"><br><?= __("Note") ?></div>
                                    <div style="width: 10%"><br><?= __("Status") ?></div>
                                </div>

                                <?php if (!empty($list_product)) : ?>
                                    <div class="css-table-body">
                                        <?php foreach ($list_product as $key => $value) : ?>
                                            <div class="css-table-row-input table-striped">
                                                <div class="action-col">
                                                    <div class="d-flex justify-content-center">
                                                            <a href="/purchase/view/<?= $value->id . $this->Format->renderParameterURL() ?>" class="view" title="View">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        <a href="/purchase/delete/<?= $value->id . $this->Format->renderParameterURL() ?>" onclick="return confirm('Are you sure?');" class="delete" title="View">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>

                                                </div>
                                                <div>
                                                    <?= $value->is_write_display ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->is_payment_display ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->is_picture_display ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->code ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->full_name_display ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->birthday_display ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->gender_display ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->cccd ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->phone ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= strtoupper(htmlspecialchars($value->level ?? '')) ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->where_from ?? '') ?>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($value->exam_display ?? '') ?>
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
<script>
    $(document).ready(function() {
        $('#importPurchase').on("change", function () {
            $('#uploadFile').submit();
        });
    })

</script>
