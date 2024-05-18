<div class="form-add" id="form-add-project" >
    <?= $this->Form->create(null, [
        'url' => [
            'controller' => 'ManageSystem',
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
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><?= __('Customers') ?></a></li>
                </ol>
            </nav>
        </div>
        <div class="form-button-group">
            <button class="btn btn-success rounded-0" id="btn-save" type="submit">
                <i class="fa-regular fa-floppy-disk"></i>
                <?= __('Save') ?>
            </button>
            <a href="/manage-system/" class="btn btn-danger mx-2 rounded-0" id="cancel">
                <i class="fa-solid fa-xmark"></i>
                <?= __('Cancel') ?>
            </a>
            <button class="btn btn-success rounded-0" id="btn-excel" type="button">
                <i class="fa-regular fa-floppy-disk"></i>
                <?= __('Import Excel') ?>
            </button>
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
                                <label class="col-3 col-form-label text-end"><?= __('Code') ?></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="code" id="code">

                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Level') ?><span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="level" id="level">

                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('First Name') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="first_name" id="first_name">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Last Name') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="last_name" id="last_name">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('BirthDay') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="birthday_display" id="birthday_display">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('From') ?> <span style="color: red">*</span></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="where_from" id="where_from">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Referral') ?></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="referral" id="referral">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Exam') ?></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="exam" id="exam">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="radio-inline">
                                                <input type="radio" id="" name="is_write" value="1">&nbsp;<?= __('Write Done') ?>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label class="radio-inline" style="margin-left: 30px;">
                                                <input type="radio" id="" name="is_write" value="0" checked>&nbsp;<?= __('Not yet') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="radio-inline">
                                                <input type="radio" id="" name="is_payment" value="1">&nbsp;<?= __('Payment Done') ?>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label class="radio-inline" style="margin-left: 30px;">
                                                <input type="radio" id="" name="is_payment" value="0" checked>&nbsp;<?= __('Not yet') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"></label>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="radio-inline">
                                                <input type="radio" id="" name="is_picture" value="1">&nbsp;<?= __('Picture Done') ?>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label class="radio-inline" style="margin-left: 30px;">
                                                <input type="radio" id="" name="is_picture" value="0" checked>&nbsp;<?= __('Not yet') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Phone') ?></label>
                                <div class="col-8">
                                    <input type="text" class="form-control" value="" name="phone" id="phone">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Address') ?></label>
                                <div class="col-8">
                                    <textarea id="addr" class="form-control" name="addr" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Picture') ?></label>
                                <div class="col-8">
                                    <input type="file" class="form-control" value="" name="pic" id="pic">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-3 col-form-label text-end"><?= __('Picture Display') ?></label>
                                <div class="col-8">
                                    <?php if(!empty($data_request['pic'])): ?>
                                    <img src="<?= $this->Url->image("jlpt/".$data_request['pic'], ['fullBase' => true]) ?>" class="img-fluid"  width="75" height="100"/>
                                    <?php endif; ?>
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
                                        <img src="<?= $this->Url->image("jlpt/".$data_request['avatar'], ['fullBase' => true]) ?>" class="img-fluid"  width="300" height="400"/>
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
    <div style="display: none">
        <ul class="nav nav-pills">
            <li class="nav-item px-1">
                <?= $this->Form->create(null, [
                    'url' => '/jlpt/manage-system/import',
                    'method' => 'post',
                    'id' => 'uploadFile',
                    'class' => ['d-none'],
                    'enctype' => 'multipart/form-data'
                ]); ?>
                <input type="file" id="importQuoting" name="file_import">
                <?= $this->Form->end(); ?>
            </li>
        </ul>
    </div>
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

        $("#btn-excel").click(function(){
            $('#importQuoting').click();
        })
        $('#importQuoting').on("change", function () {
            $('#uploadFile').submit();
        });
    })
</script>
