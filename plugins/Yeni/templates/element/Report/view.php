<style>

</style>
<section class="content-wrapper w-100 py-3">
    <nav>
        <div class="notification">
            <?php
            echo $this->Flash->render(); ?>
        </div>
    </nav>

    <div class="form-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12" style="border-bottom: 1px solid #cecece;">
                    <div class="row">
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Họ tên Khách:</b></label>
                            <div class="col-8">
                                <label><?= $order->customer_name ?? "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Mã order:</b></label>
                            <div class="col-8">
                                <label><?= $order->order_code ?? "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Số đt:</b></label>
                            <div class="col-8">
                                <label><?= $order->customer_phone ?? "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Tiền ship:</b></label>
                            <div class="col-8">
                                <label><?= $order->shipping ?? "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Địa chỉ</b>:</b></label>
                            <div class="col-8">
                                <label><?= $order->customer_addr ?? "" ?></label>
                            </div>
                        </div>

                        <div class="mb-3 col-6  row">
                            <label class="col-4" style="color: red"><b>Thực Nhận:</b></label>
                            <div class="col-8">
                                <label style="color: red; font-weight: 700"><?= $order->total_actual_display ?? "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Ngày order:</b></label>
                            <div class="col-8">
                                <label><?= !empty($order->order_date) ? $order->order_date->toDateString() :  "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Thu khách:</b></label>
                            <div class="col-8">
                                <label><?= $order->total_display ?? "" ?></label>
                            </div>
                        </div>
                        <div class="mb-3 col-6  row">
                            <label class="col-4"><b>Ghi chú:</b></label>
                            <div class="col-8">
                                <label><?= $order->note ?? "" ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h2>Thông tin hàng</h2>
            </div>
            <div class="row">
                <div class="col-2" style="font-weight: 700">
                    Mã code
                </div>
                <div class="col-2" style="font-weight: 700">
                    Tên hàng
                </div>
                <div class="col-2" style="font-weight: 700">
                    Số lượng
                </div>
                <div class="col-2" style="font-weight: 700">
                    Giá mua
                </div>
                <div class="col-2" style="font-weight: 700">
                    Giá bán
                </div>
                <div class="col-2" style="font-weight: 700">
                    Ghi chú
                </div>
            </div>
            <?php if(!empty($order->quoting)) : ?>
                <?php foreach($order->quoting as $quoting) : ?>
                    <div class="row" style="border-bottom: 1px solid black">
                        <div class="col-2">
                            <?= $quoting->code ?? "" ?>
                        </div>
                        <div class="col-2">
                            <?= $quoting->name ?? "" ?>
                        </div>
                        <div class="col-2">
                            <?= $quoting->quantity ?? 0 ?>
                        </div>
                        <div class="col-2">
                            <?= $quoting->p_price ?? 0 ?>
                        </div>
                        <div class="col-2">
                            <?= $quoting->price ?? 0 ?>
                        </div>
                        <div class="col-2">
                            <?= $quoting->note ?? "" ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

