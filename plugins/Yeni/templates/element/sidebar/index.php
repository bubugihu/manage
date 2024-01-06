<aside class="bd-sidebar">
    <div class="bg-light">
        <ul class="nav nav-pills d-flex justify-content-center aside-tab" role="tablist">
            <li class="nav-item mx-1" role="presentation">
                <button class="btn rounded-0 <?php echo in_array($this->getRequest()->getParam('controller'), ['Home', 'ManageSystem', 'Purchasing', 'Config', 'Quoting', 'Report', 'Expenses', 'Product']) ? 'active' : ''; ?>"
                        id="pills-aside-main-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#pills-aside-main"
                        type="button"
                        role="tab"
                        aria-controls="pills-aside-main"
                        aria-selected="true">
                    <i class="fa-solid fa-folder-tree"></i>
                </button>
            </li>
<!--            <li class="nav-item mx-1" role="presentation">-->
<!--                <button class="btn rounded-0 --><?php //echo in_array($this->getRequest()->getParam('controller'), ['Materials', 'PartNo', 'Team', 'Function', 'Permission', 'Vendor', 'Project', 'Customer', 'Unit', 'Currency','TemplateMail' , 'Office', 'ExchangeRate', 'CompanyText', 'RepositoryManager', 'Department', 'CheckFollow', 'WoTravelerTemplate', 'Language', 'UpdatePricePartNo', 'LaborEstimateDetail']) ? 'active' : ''; ?><!--"-->
<!--                        id="pills-aside-setting-tab"-->
<!--                        data-bs-toggle="pill"-->
<!--                        data-bs-target="#pills-aside-setting"-->
<!--                        type="button"-->
<!--                        role="tab"-->
<!--                        aria-controls="pills-aside-setting"-->
<!--                        aria-selected="false">-->
<!--                    <i class="fa-solid fa-gears"></i>-->
<!--                </button>-->
<!--            </li>-->
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade <?php echo in_array($this->getRequest()->getParam('controller'), ['Home', 'ManageSystem', 'Purchasing', 'Config', 'Quoting', 'Report', 'Expenses', 'Product']) ? 'show active' : ''; ?>" id="pills-aside-main" role="tabpanel" aria-labelledby="pills-aside-main-tab" tabindex="0">
                <?php echo $this->element('sidebar/tab-main'); ?>
            </div>
<!--            <div class="tab-pane fade --><?php //echo in_array($this->getRequest()->getParam('controller'), ['Materials', 'PartNo', 'Team', 'Function', 'Permission', 'Vendor', 'Project', 'Customer', 'Unit', 'Currency','TemplateMail' , 'Office', 'ExchangeRate', 'CompanyText', 'RepositoryManager', 'Department', 'CheckFollow', 'WoTravelerTemplate', 'LaborEstimateDetail']) ? 'show active' : ''; ?><!--" id="pills-aside-setting" role="tabpanel" aria-labelledby="pills-aside-setting-tab" tabindex="0">-->
<!--                --><?php //echo $this->element('sidebar/tab-setting'); ?>
<!--            </div>-->
        </div>

    </div>
</aside>
