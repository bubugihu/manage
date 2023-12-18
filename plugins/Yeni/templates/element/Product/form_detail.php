<div class="form-detail">
    <div class="form-title mx-3">
        <p id="title-detail"><?= __('Set Detail') ?></p>
    </div>
    <div class="card m-3 rounded-0">
        <div class="card-header">
            <div class="notification-detail">
            </div>
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="btn btn-success" type="button" id="btn-add-order-purchasing">
                        <i class="fa-solid fa-plus"></i>
                        <?= __('Add Detail') ?>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0" style="background-color: #c5c5c5;">
                    <div class="table-responsive">
                        <div class="css-table" style="width: 100%">
                            <div class="css-table-header">
                                <div style="width: 2%"><br><?= __('Action') ?></div>
                                <div style="width: 8%"><br><?= __('Code Product') ?><span style="color: red">*</span></div>
                                <div style="width: 8%"><br><?= __('Name Product') ?><span style="color: red">*</span></div>
                                <div style="width: 8%"><br><?= __('Quantity') ?><span style="color: red">*</span></div>
                            </div>
                            <div class="css-table-body" id="table-order-purchasing">
                                <?php if(!empty($list_order)): ?>
                                    <?php foreach($list_order as $key => $order): ?>
                                        <div class="css-table-row-input table-striped input-order" data-row="<?= $key ?>" id="row-<?= $key ?>">
                                            <div class="text-center">
                                                <input type="hidden" id="check-disable-<?= $key ?>" name="order[<?= $key ?>][check_disable]" value="<?= !empty($order['check_disable']) ? $order['check_disable'] : false ?>" />
                                                <?php if((!($order['check_disable'])) && isset($check_count) && $key >= $check_count && empty($type) || (!empty($type) && $type == SHIPMENT_TYPE_PUR )): ?>
                                                    <a href="javascript:void(0)" onclick="deleteOrder(<?= $key ?>)" title="Delete" style="display: inline-block;">
                                                        <i class="fa-regular fa-rectangle-xmark" style="float:left; padding-right: 5px; color: red;font-size: 12px;"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if(!empty($type) && $type == SHIPMENT_TYPE_QUO ): ?>
                                                    <input type="checkbox" class="form-checkbox checkbox-detail-quoting" id="checkbox-<?= $key ?>" name="order[<?= $key ?>][checkbox]"
                                                        <?= (!empty($order['checkbox'])) ? 'checked' : ((!empty($order['quantity']) && !empty($shipment) && empty($data_request)) ? 'checked' : '') ?>
                                                        <?= (!empty($order['check_disable']) && $order['check_disable']) ? 'disabled' : '' ?>
                                                    />
                                                <?php endif; ?>
                                            </div>
                                            <div class="select2-form-select-custom">
                                                <select data-row="<?= $key ?>" name="order[<?= $key ?>][part_no_id]" id="select-partno-<?= $key ?>" class="form-select select2 select-partno <?= $class_disabled ?> <?= !empty($errors['order'][$key]['part_no_id']) ? 'is-invalid' : '' ?>" <?= $flag_disabled ?> <?= (!empty($order['check_disable']) && $order['check_disable']) ? 'disabled' : '' ?>>
                                                    <option value="">Choose Part No</option>
                                                    <?php if(!empty($list_part_no)): ?>
                                                        <?php foreach($list_part_no as $partno): ?>
                                                            <option value="<?= $partno->id ?>" <?= (!empty($order['part_no_id']) && $order['part_no_id'] == $partno->id) ? 'selected' : '' ?>><?= $partno->code ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    <?php if (!empty($errors['order'][$key]['part_no_id']))
                                                        foreach ($errors['order'][$key]['part_no_id'] as $msg) {
                                                            echo $msg . '<br>';
                                                            break;
                                                        }  ?>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control <?= !empty($errors['order'][$key]['hawb_number']) ? 'is-invalid' : '' ?>" id="input-hawb-number-<?= $key ?>" name="order[<?= $key ?>][hawb_number]"
                                                       value="<?=
                                                       (!empty($order['hawb_number']) && $order['hawb_number'] != ' ') ? $order['hawb_number'] : ''
                                                       ?>"
                                                    <?= (!empty($order['check_disable']) && $order['check_disable']) ? 'readonly' : '' ?>
                                                />
                                                <div class="invalid-feedback">
                                                    <?php if (!empty($errors['order'][$key]['hawb_number']))
                                                        foreach ($errors['order'][$key]['hawb_number'] as $msg) {
                                                            echo $msg . '<br>';
                                                            break;
                                                        }  ?>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control <?= !empty($errors['order'][$key]['serial_number']) ? 'is-invalid' : '' ?>" id="input-serial-number-<?= $key ?>" name="order[<?= $key ?>][serial_number]"
                                                       value="<?=
                                                       (!empty($order['serial_number']) && $order['serial_number'] != ' ') ? $order['serial_number'] : ''
                                                       ?>"
                                                    <?= (!empty($order['check_disable']) && $order['check_disable']) ? 'readonly' : '' ?>
                                                />
                                                <div class="invalid-feedback">
                                                    <?php if (!empty($errors['order'][$key]['serial_number']))
                                                        foreach ($errors['order'][$key]['serial_number'] as $msg) {
                                                            echo $msg . '<br>';
                                                            break;
                                                        }  ?>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control" id="input-request-quantity-<?= $key ?>" name="order[<?= $key ?>][request_quantity]"
                                                       value="<?=
                                                       ((isset($order['request_quantity']) || ($order['request_quantity'] == '' && !empty($data_request))) ? $order['request_quantity'] :
                                                           ((!empty($shipment) && !empty($list_request_quantity[$shipment->id][$key]) && isset($list_request_quantity[$shipment->id][$key]['view_request_quantity'])) ? $list_request_quantity[$shipment->id][$key]['view_request_quantity'] :
                                                               ((!empty($order['check_disable']) && $order['check_disable'] && !empty($check_is_first) && $check_is_first) ? 0 : '')
                                                           )
                                                       )
                                                       ?>"
                                                       readonly
                                                />
                                            </div>
                                            <div>
                                                <input data-row="<?= $key ?>" type="number" class="form-control <?= !empty($errors['order'][$key]['quantity']) ? 'is-invalid' : '' ?>" id="input-quantity-<?= $key ?>" name="order[<?= $key ?>][quantity]"
                                                       value="<?=
                                                       ((!empty($order['quantity']) || (isset($order['quantity']) && !empty($shipment) && $shipment['type'] == SHIPMENT_TYPE_PUR)) ? $order['quantity'] :
                                                           ((empty($order['check_disable']) && $order['check_disable']) ? 0 :
                                                               ((!empty($shipment) && !empty($list_request_quantity[$shipment->id][$key]) && isset($list_request_quantity[$shipment->id][$key]['view_request_quantity'])) ? $list_request_quantity[$shipment->id][$key]['view_request_quantity'] : 0))
                                                       )
                                                       ?>"
                                                    <?= !empty($order['request_quantity']) ? 'max=' . $order['request_quantity'] : ((!empty($shipment) && !empty($list_request_quantity[$shipment->id][$key]['view_request_quantity']) ? 'max=' . $list_request_quantity[$shipment->id][$key]['view_request_quantity'] : '')) ?>
                                                    <?= (!empty($order['check_disable']) && $order['check_disable']) ? 'readonly' : '' ?>
                                                />
                                                <div class="invalid-feedback">
                                                    <?php if (!empty($errors['order'][$key]['quantity']))
                                                        foreach ($errors['order'][$key]['quantity'] as $msg) {
                                                            echo $msg . '<br>';
                                                            break;
                                                        }  ?>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="number" class="form-control <?= !empty($errors['order'][$key]['qty_after_shipping']) ? 'is-invalid' : '' ?>" id="input-qty-after-shipping-<?= $key ?>" name="order[<?= $key ?>][qty_after_shipping]"
                                                       value="<?=
                                                       isset($order['qty_after_shipping']) ? $order['qty_after_shipping'] : 1
                                                       ?>"
                                                    <?= !empty($order['request_quantity']) ? 'max=' . $order['request_quantity'] : ((!empty($shipment) && !empty($list_request_quantity[$shipment->id][$key]['view_request_quantity']) ? 'max=' . $list_request_quantity[$shipment->id][$key]['view_request_quantity'] : '')) ?>
                                                />
                                                <div class="invalid-feedback">
                                                    <?php if (!empty($errors['order'][$key]['qty_after_shipping']))
                                                        foreach ($errors['order'][$key]['qty_after_shipping'] as $msg) {
                                                            echo $msg . '<br>';
                                                            break;
                                                        }  ?>
                                                </div>
                                            </div>
                                            <div>
                                                <textarea class="form-control <?= !empty($errors['order'][$key]['special_instructions']) ? 'is-invalid' : '' ?>" id="input-special-instructions-<?= $key ?>" name="order[<?= $key ?>][special_instructions]" rows="1" <?= (!empty($order['check_disable']) && $order['check_disable']) ? 'readonly' : '' ?>><?=
                                                    !empty($order['special_instructions']) ? $order['special_instructions'] : ''
                                                    ?></textarea>
                                                <div class="invalid-feedback">
                                                    <?php if (!empty($errors['order'][$key]['special_instructions']))
                                                        foreach ($errors['order'][$key]['special_instructions'] as $msg) {
                                                            echo $msg . '<br>';
                                                            break;
                                                        }  ?>
                                                </div>
                                            </div>
                                            <div class="select2-form-select-custom">
                                                <select data-row="<?= $key ?>" name="order[<?= $key ?>][status]" id="select-status-<?= $key ?>" class="form-select select2 select-status" <?= (!empty($order['check_disable']) && $order['check_disable'] && empty($shipment)) ? 'disabled' : '' ?>>
                                                    <?php foreach(SHIPMENT_STATUS as $index => $value): ?>
                                                        <option value="<?= $index ?>" <?= (!empty($order['status']) && $order['status'] == $index) ? 'selected' : '' ?>><?= $value ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-bottom">
            <div class="d-flex flex-row justify-content-between align-items-center px-4 py-0">
                <div></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    })
</script>
