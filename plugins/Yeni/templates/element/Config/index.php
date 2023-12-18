<div class="tab-content" id="nav-tabContent">
    <?= $this->Form->create(null, [
        'url' => [
            'controller' => 'Config',
            'action' => 'create',
        ],
        'method' => 'post',
        'novalidate',
        'enctype' => 'multipart/form-data',
        'id' => 'create-form',
        'autocomplete' => 'off',
        'class' => ['needs-validation'],
    ]); ?>
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <div class="form-header d-flex justify-content-between align-items-center px-3 py-1 form-title mx-3">
            <?= __("List Config") ?>
            <div class="form-button-group">
                <button class="btn btn-success rounded-0" id="btn-save" type="submit">
                    <i class="fa-regular fa-floppy-disk"></i>
                    <?= __('Save') ?>
                </button>
            </div>
        </div>
        <div class="card m-3 rounded-0">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0" style="background-color: #c5c5c5;">
                        <div class="table-responsive">
                            <div class="css-table action-dropdown">
                                <div class="css-table-header">
                                    <div style="width: 20%"><br><?= __("Name") ?></div>
                                    <div style="width: 10%"><br><?= __("Value") ?></div>
                                    <div style="width: 10%"><br><?= __("Unit") ?></div>
                                    <div style="width: 60%"><br><?= __("Note") ?></div>
                                </div>

                                <div class="css-table-body">
                                    <div class="css-table-row-input table-striped">
                                        <div>
                                            <input class="form-control" type="text" name="name[]" value="<?= $results[0]->name ?? "" ?>">
                                        </div>
                                        <div>
                                            <input type="text" class="form-control" name="value[]" value="<?= $results[0]->value ?? "" ?>">
                                        </div>
                                        <div>
                                            <input class="form-control" type="text" name="unit[]" value="<?= $results[0]->unit ?? "" ?>">
                                        </div>
                                        <div>
                                            <textarea class="form-control" name="note[]"> <?= $results[0]->note ?? "" ?> </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="css-table-body">
                                    <div class="css-table-row-input table-striped">
                                        <div>
                                            <input class="form-control" type="text" name="name[]" value="<?= $results[1]->name ?? "" ?>">
                                        </div>
                                        <div>
                                            <input type="text" class="form-control" name="value[]" value="<?= $results[1]->value ?? "" ?>">
                                        </div>
                                        <div>
                                            <input class="form-control" type="text" name="unit[]" value="<?= $results[1]->unit ?? "" ?>">
                                        </div>
                                        <div>
                                            <textarea class="form-control" name="note[]"><?= $results[1]->note ?? "" ?>  </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<script>
    $(document).ready(function() {
        $('#importPurchase').on("change", function () {
            $('#uploadFile').submit();
        });
    })

</script>
