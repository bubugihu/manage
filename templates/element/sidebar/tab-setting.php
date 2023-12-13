<ul class="list-unstyled ps-0">
    <?php if ($user_login->group_user_id == PROJECT_OWNER) : ?>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'PartNo' ? 'active' : ''; ?>">
            <a href="<?php echo URL_QUOTING ?>part-no" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-barcode me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Part No") ?></div>
            </a>
        </li>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Team' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>team" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-user-group me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Teams") ?></div>
            </a>
        </li>

        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3"
                    data-bs-toggle="collapse" data-bs-target="#function-collapse" aria-expanded="<?php echo in_array($this->getRequest()->getParam('controller'), ['Function', 'Permission']) ? 'true' : 'false'; ?>">
                <i class="fa-solid fa-user-check me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Permission") ?></div>
            </button>
            <div class="collapse bg-white border-end border-light <?php echo in_array($this->getRequest()->getParam('controller'), ['Function', 'Permission']) ? 'show' : ''; ?>"
                 id="function-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li
                        class="<?php echo $this->getRequest()->getParam('controller') == 'Function' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>function"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("List Function") ?></div>
                        </a>
                    </li>
                    <li
                        class="<?php echo $this->getRequest()->getParam('controller') == 'Permission' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>permission"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("List Permission") ?></div>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Vendor' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>vendor" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-truck-field me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Vendors") ?></div>
            </a>
        </li>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Project' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>project" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-list me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Projects") ?></div>
            </a>
        </li>

        <li class="<?php echo $this->getRequest()->getParam('controller') == 'Document' ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL_ADMIN ?>document"
                class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-folder me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Document") ?></div>
            </a>
        </li>
        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'CheckFollow' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>check-follow" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-check-square me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Check Follow") ?></div>
            </a>
        </li>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Customer' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>customer" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-handshake-simple me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Customer") ?></div>
            </a>
        </li>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'Department' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>department" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-building me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Department") ?></div>
            </a>
        </li>

        <li class="mb-1 <?php echo $this->getRequest()->getParam('controller') == 'WoTravelerTemplate' ? 'active' : ''; ?>">
            <a href="<?php echo URL_ADMIN ?>wo-traveler-template" class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3 aside-none-after">
                <i class="fa-solid fa-layer-group me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("WO Traveler Template") ?></div>
            </a>
        </li>

        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed fs-5 p-3"
                    data-bs-toggle="collapse" data-bs-target="#config-collapse" aria-expanded="<?php echo in_array($this->getRequest()->getParam('controller'), ['Unit', 'Currency','TemplateMail' , 'Office', 'ExchangeRate', 'CompanyText', 'RepositoryManager', 'Language','UpdatePricePartNo', 'LaborEstimateDetail']) ? 'true' : 'false'; ?>">
                <i class="fa-solid fa-cog me-2 aside-menu-icon"></i>
                <div class="text-start"><?= __("Configs") ?></div>
            </button>
            <div class="collapse bg-white border-end border-light <?php echo in_array($this->getRequest()->getParam('controller'), ['Unit', 'Currency','TemplateMail', 'Office', 'ExchangeRate', 'CompanyText', 'RepositoryManager', 'Language','UpdatePricePartNo', 'LaborEstimateDetail']) ? 'show' : ''; ?>"
                 id="config-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li
                        class="<?php echo $this->getRequest()->getParam('controller') == 'TemplateMail' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>config/template-mail"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("Template Mail") ?></div>
                        </a>
                    </li>
                    <li
                        class="<?php echo $this->getRequest()->getParam('controller') == 'Unit' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>config/unit"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("List Units") ?></div>
                        </a>
                    </li>
                    <li
                        class="<?php echo $this->getRequest()->getParam('controller') == 'Currency' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>config/currency"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("List Currency") ?></div>
                        </a>
                    </li>
                    <li class="<?php echo $this->getRequest()->getParam('controller') == 'Office' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>config/office"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("Shipping - Billing") ?></div>
                        </a>
                    </li>
                    <li
                        class="<?php echo $this->getRequest()->getParam('controller') == 'ExchangeRate' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>config/exchange-rate"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("Exchange Rate") ?></div>
                        </a>
                    </li>
                    <li class="<?php echo $this->getRequest()->getParam('controller') == 'CompanyText' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ADMIN ?>config/company-text"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start"><?= __("Company Text") ?></div>
                        </a>
                    </li>
                    <li class="<?php echo $this->getRequest()->getParam('controller') == 'Language' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL_ADMIN ?>language/change-language"
                           class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <?= __('Change Language') ?>
                        </a>
                    </li>
                    <li class="<?php echo $this->getRequest()->getParam('controller') == 'UpdatePricePartNo' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL_ADMIN ?>config/update-price-part-no"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start">Update Price Part No</div>
                        </a>
                    </li>
                    <li class="<?php echo $this->getRequest()->getParam('controller') == 'LaborEstimateDetail' ? 'active' : ''; ?>">
                        <a href="<?php echo BASE_URL_QUOTING ?>/labor-estimate-detail"
                           class="btn btn-toggle d-inline-flex align-items-baseline rounded border-0 collapsed p-2 aside-none-after aside-menu-child">
                            <i class="fa-regular fa-circle"></i>
                            <div class="text-start">Labor Estimate Detail</div>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    <?php endif; ?>
</ul>
