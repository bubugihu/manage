<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <div class="form-title mx-3">
            <?= __("List Set Product") ?>
        </div>
        <div class="card m-3 rounded-0">
            <div class="card-header p-1">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="btn btn-success rounded-0" href="/product/create-set-product" id="btn-add-form">
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
                                <label class="btn btn-outline-secondary rounded-0" for="importSetProduct">
                                    <i class="fa-solid fa-plus"></i>
                                    <?= __('Import EXCEL') ?>
                                </label>
                                <?= $this->Form->create(null, [
                                    'url' => '/product/import-set-product',
                                    'method' => 'post',
                                    'id' => 'uploadFile',
                                    'class' => ['d-none'],
                                    'enctype' => 'multipart/form-data'
                                ]); ?>
                                <input type="file" id="importSetProduct" name="file_import">
                                <?= $this->Form->end(); ?>
                            </li>

                        </ul>

                        <div class="nav-end">
                            <div class="nav-end">
                                <form class="form-filter" action="/product/set-product-list/" method="get">
                                    <ul class="nav nav-pills">
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
                                    <div style="width: 1%"><br><?= __("Action") ?></div>
                                    <div style="width: 1%"><br><?= __("Code") ?></div>
                                    <div style="width: 1%"><br><?= __("Name") ?></div>
                                    <div style="width: 5%"><br><?= __("Items") ?></div>
                                </div>

                                <?php if (!empty($list_product)) : ?>
                                    <div class="css-table-body">
                                        <?php foreach ($list_product as $key => $value) : ?>
                                            <div class="css-table-row-input table-striped">
                                                <div class="action-col">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="/product/view/<?= $value->id . $this->Format->renderParameterURL() ?>" class="view" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="/product/delete/<?= $value->id . $this->Format->renderParameterURL() ?>" onclick="return confirm('Are you sure?');" class="delete" title="View">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>

                                                </div>
                                                <div>
                                                    <?= $value->code ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->name ?? '' ?>
                                                </div>
                                                <div>
                                                    <?= $value->note ?? '' ?>
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
        $('#importSetProduct').on("change", function () {
            $('#uploadFile').submit();
        });
    })

</script>
