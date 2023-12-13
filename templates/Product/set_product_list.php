<section class="content-wrapper w-100 py-3">
    <nav>
        <div class="notification">
            <?php echo $this->Flash->render(); ?>
        </div>
        <div class="nav nav-tabs ps-3" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                <i class="fa-solid fa-bookmark me-2"></i>
                <?= __('Set Product') ?>
            </button>
        </div>
    </nav>
    <?= $this->element('Product/set_product_list'); ?>
</section>
