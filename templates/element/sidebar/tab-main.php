<ul class="list-unstyled ps-0">
        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Home' ? 'active' : ''; ?>">
            <a href="/" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fas fa-tachometer-alt me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Dashboard") ?></div>
            </a>
        </li>

<!--    <li class="mb-1 --><?php //echo $this->getRequest()->getParam('controller') == 'ManageSystem' ? 'active' : ''; ?><!--">-->
<!--        <a href="/manage-system" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">-->
<!--            <i class="fas fa-tachometer-alt me-2 aside-menu-icon"></i>-->
<!--            <div class="text-start">--><?//= __("JLPT") ?><!--</div>-->
<!--        </a>-->
<!--    </li>-->

    <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3"
                data-bs-toggle="collapse" data-bs-target="#purchasing-collapse" aria-expanded="<?php echo in_array($this->getRequest()->getParam('controller'), ['Product']) ? 'true' : 'false'; ?>">
            <i class="fa-regular fa-credit-card me-2 aside-menu-icon"></i>
            <div class="text-start"><?= __("Inventory") ?></div>
        </button>
        <div class="collapse bg-white border-end border-light <?php echo in_array($this->getRequest()->getParam('controller'), ['Product']) ? 'show' : ''; ?>"
             id="purchasing-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li
                    class="<?php echo ($this->getRequest()->getParam('controller') == 'Product' && $this->getRequest()->getParam('action') == 'index') ? 'active' : ''; ?>">
                    <a href="/product"
                       class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                        <i class="fa-regular fa-circle"></i>
                        <div class="text-start"><?= __("Product") ?></div>
                    </a>
                </li>

                <li
                    class="<?php echo ($this->getRequest()->getParam('controller') == 'Product' && $this->getRequest()->getParam('action') == 'setProductList') ? 'active' : ''; ?>">
                    <a href="/product/set-product-list"
                       class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                        <i class="fa-regular fa-circle"></i>
                        <div class="text-start"><?= __("Set Product") ?></div>
                    </a>
                </li>

                <li
                    class="<?php echo ($this->getRequest()->getParam('controller') == 'Product' && $this->getRequest()->getParam('action') == 'importExcel') ? 'active' : ''; ?>">
                    <a href="/product/importExcel"
                       class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                        <i class="fa-regular fa-circle"></i>
                        <div class="text-start"><?= __("From Excel") ?></div>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3"
                data-bs-toggle="collapse" data-bs-target="#purchasing-collapse" aria-expanded="<?php echo in_array($this->getRequest()->getParam('controller'), ['Purchase']) ? 'true' : 'false'; ?>">
            <i class="fa-regular fa-credit-card me-2 aside-menu-icon"></i>
            <div class="text-start"><?= __("Purchasing") ?></div>
        </button>
        <div class="collapse bg-white border-end border-light <?php echo in_array($this->getRequest()->getParam('controller'), ['Purchase']) ? 'show' : ''; ?>"
             id="purchasing-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li
                    class="<?php echo $this->getRequest()->getParam('controller') == 'Purchase' ? 'active' : ''; ?>">
                    <a href="/purchase"
                       class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                        <i class="fa-regular fa-circle"></i>
                        <div class="text-start"><?= __("Request") ?></div>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3"
                data-bs-toggle="collapse" data-bs-target="#quoting-collapse" aria-expanded="<?php echo in_array($this->getRequest()->getParam('controller'), ['Quoting']) ? 'true' : 'false'; ?>">
            <i class="fa-regular fa-credit-card me-2 aside-menu-icon"></i>
            <div class="text-start"><?= __("Quoting") ?></div>
        </button>
        <div class="collapse bg-white border-end border-light <?php echo in_array($this->getRequest()->getParam('controller'), ['Quoting']) ? 'show' : ''; ?>"
             id="quoting-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li
                    class="<?php echo $this->getRequest()->getParam('controller') == 'Quoting' ? 'active' : ''; ?>">
                    <a href="/quoting"
                       class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                        <i class="fa-regular fa-circle"></i>
                        <div class="text-start"><?= __("Quoting") ?></div>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3"
                data-bs-toggle="collapse" data-bs-target="#report-collapse" aria-expanded="<?php echo in_array($this->getRequest()->getParam('controller'), ['Report']) ? 'true' : 'false'; ?>">
            <i class="fa-regular fa-credit-card me-2 aside-menu-icon"></i>
            <div class="text-start"><?= __("Report") ?></div>
        </button>
        <div class="collapse bg-white border-end border-light <?php echo in_array($this->getRequest()->getParam('controller'), ['Report']) ? 'show' : ''; ?>"
             id="report-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li
                    class="<?php echo $this->getRequest()->getParam('controller') == 'Report' ? 'active' : ''; ?>">
                    <a href="/report"
                       class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                        <i class="fa-regular fa-circle"></i>
                        <div class="text-start"><?= __("Report") ?></div>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Config' ? 'active' : ''; ?>">
        <a href="/config" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
            <i class="fas fa-tachometer-alt me-2 aside-menu-icon"></i>
            <div class="text-start"><?= __("Config") ?></div>
        </a>
    </li>
</ul>
