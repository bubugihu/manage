<div class="form-add" id="form-add-project" >
    <?= $this->Form->create(null, [
        'url' => [
            'controller' => 'Product',
            'action' => 'create',
        ],
        'method' => 'post',
        'novalidate',
        'enctype' => 'multipart/form-data',
        'id' => 'create-form',
        'autocomplete' => 'off',
        'class' => ['needs-validation'],
    ]); ?>
    <div class="form-header d-flex justify-content-between align-items-center px-3 py-1 border-bottom">
        <div class="form-breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-end m-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><?= __('Product') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __("Create") ?></li>
                </ol>
            </nav>
        </div>
        <div class="form-button-group">
            <button class="btn btn-success rounded-0" id="btn-save" type="submit">
                <i class="fa-regular fa-floppy-disk"></i>
                <?= __('Save') ?>
            </button>
            <a href="/product/" class="btn btn-danger mx-2 rounded-0" id="cancel">
                <i class="fa-solid fa-xmark"></i>
                <?= __('Cancel') ?>
            </a>
        </div>
    </div>
    <div class="form-title mx-3">
        <p id="title"><?=  __('Detail') ?></p>
    </div>
    <div class="form-body px-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 offset-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Code') ?><span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="code" id="code">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Name Product') ?><span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="name" id="name">

                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Category') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <select class="form-select" name="category_id" id="category_id">
                                        <?php foreach(CATEGORY as $key=>$value) : ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Unit') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <select class="form-select" name="unit_id" id="unit_id">
                                        <?php foreach(UNIT as $key=>$value) : ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Price') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="number" class="form-control" value="" name="price" id="price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Set Product') ?></label>
                                <div class="col-8">
                                    <select name="set_product" id="set_product" class="form-select select2">
                                        <option>1</option>
                                        <option>12</option>
                                        <option>123</option>
                                    </select>

                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Avatar') ?></label>
                                <div class="col-8">
                                    <input type="file" class="form-control" value="" name="avatar" id="avatar">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Avatar Display') ?></label>
                                <div class="col-8">
                                    <?php if(!empty($data_request['avatar'])): ?>
                                        <img src="<?= $this->Url->image("yeni/".$data_request['avatar'], ['fullBase' => true]) ?>" class="img-fluid"  width="300" height="400"/>
                                    <?php endif; ?>
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
        $("#create-form").validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                level: {
                    required: true,
                },
                birthday_display: {
                    required: true,
                },
                where_from: {
                    required: true,
                },
                exam: {
                    required: true,
                },
                pic: {
                    required: true,
                },
                avatar: {
                    required: true,
                },

            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $('#set_product').select2({
            tags: true
        });
    })
</script>
