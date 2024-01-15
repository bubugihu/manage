<?php

namespace Yeni\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \Yeni\Controller\AppController;
use Yeni\Library\Api\Gmail_Api;
use Yeni\Library\Business\Orders;
use Yeni\Library\Business\Report;
use Yeni\Model\Table\SetProductTable;

class ReportController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->business_report = new Report();
        $this->business_order = new Orders();
        $this->business_api_gmail = new Gmail_Api();
    }

    public function index()
    {
        $content = "bui gia hung n5";
//        $mail_array = $this->business_api_gmail->getMailAcb();
        $mail_array = [
            0=> [
                2 => '


Kính gửi Quý khách hàng.

ACB trân trọng thông báo tài khoản *24692047* của Quý khách đã thay đổi số
dư như sau:
Số dư mới của tài khoản trên là: *82,551.00 VND* tính đến *15/01/2024*.
Giao dịch mới nhất:Ghi có *+10,000.00 VND*.
Nội dung giao dịch: *MBVCB.5076134614.082170.BUI GIA HUNG N5.CT TU
1042536477 BUI GIA HUNG TOI 24692047 BUI GIA HUNG TAI ACB GD 082170-011524
13:36:03*.

Cảm ơn Quý khách hàng đã sử dụng Sản phẩm/ Dịch vụ của ACB. Chúng tôi mong
được tiếp tục phục vụ Quý khách hàng.
Trân trọng.


------------------------------------------------------------------------------------------------------------------
Dear Customers,

ACB respectfully updates your *24692047* account balance, as follows:
Updated account balance: *82,551.00 VND* up to *15/01/2024*.
Latest transaction: Credit *+10,000.00 VND*.
Content: *MBVCB.5076134614.082170.BUI GIA HUNG N5.CT TU 1042536477 BUI GIA
HUNG TOI 24692047 BUI GIA HUNG TAI ACB GD 082170-011524 13:36:03*.

Thank you for using ACB’s Product/Service. We look forward to serving you
in the future.
Yours faithfully,


------------------------------------------------------------------------------------------------------------------

Đây là dịch vụ Email tự động của ACB, Quý khách vui lòng không "Reply".
Mọi thông tin cần hỗ trợ, Quý khách vui lòng liên hệ:
Trung Tâm Dịch Vụ Khách Hàng 24/7
1900545486 - (028) 38 247 247
1800577775 (Miễn phí - Dành cho KH ưu tiên)

Please do not reply to this automated email. For further information,
please contact Call Center
24/7 at 1900545486 – (028) 38 247 247
1800577775 (free and available for priority customers.)
------------------------------------------------------------------------------------------------------------------ '
            ],
            1=>[
                2 => '


Kính gửi Quý khách hàng.

ACB trân trọng thông báo tài khoản *24692047* của Quý khách đã thay đổi số
dư như sau:
Số dư mới của tài khoản trên là: *92,551.00 VND* tính đến *15/01/2024*.
Giao dịch mới nhất:Ghi có *+10,000.00 VND*.
Nội dung giao dịch: *BUI GIA HUNG N1 GD 929275-011524 15:08:43*.

Cảm ơn Quý khách hàng đã sử dụng Sản phẩm/ Dịch vụ của ACB. Chúng tôi mong
được tiếp tục phục vụ Quý khách hàng.
Trân trọng.


------------------------------------------------------------------------------------------------------------------
Dear Customers,

ACB respectfully updates your *24692047* account balance, as follows:
Updated account balance: *92,551.00 VND* up to *15/01/2024*.
Latest transaction: Credit *+10,000.00 VND*.
Content: *BUI GIA HUNG N1 GD 929275-011524 15:08:43*.

Thank you for using ACB’s Product/Service. We look forward to serving you
in the future.
Yours faithfully,


------------------------------------------------------------------------------------------------------------------

Đây là dịch vụ Email tự động của ACB, Quý khách vui lòng không "Reply".
Mọi thông tin cần hỗ trợ, Quý khách vui lòng liên hệ:
Trung Tâm Dịch Vụ Khách Hàng 24/7
1900545486 - (028) 38 247 247
1800577775 (Miễn phí - Dành cho KH ưu tiên)

Please do not reply to this automated email. For further information,
please contact Call Center
24/7 at 1900545486 – (028) 38 247 247
1800577775 (free and available for priority customers.)
------------------------------------------------------------------------------------------------------------------ '
            ]
        ];
        dd($mail_array);
        $result = $this->business_api_gmail->checkBodyMail($mail_array, $content);
//        dd($result);
        $arr['key_search']  = $_GET['key_search'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;
		$arr['month']  = $_GET['month'] ?? date("m");;
        $arr['year']  = $_GET['year'] ?? date("Y");;

        $list_orders = $this->business_report->getList($arr, $page, false);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_orders->count(), $arr);

        $this->set('total_orders',$list_orders->count());
        $this->set('list_orders',$list_orders->all()->toList());
        $this->set('paginate',$paginate);
        $this->set('month', $arr['month']);
        $this->set('year', $arr['year']);
        //chart
        $current_year = intval(date("Y"));
        $getMonthlyYear = $this->business_report->getMonthlyYear($current_year);
        $labels = $this->business_report->matchingNumber();

        //profit
        $list_profit = $this->business_report->getProfit($current_year);
        $this->set('labels',$labels);
        $this->set('getMonthlyYear',$getMonthlyYear);
        $this->set('list_profit',$list_profit);
    }

    public function importOrder()
    {
        if ($this->getRequest()->is('POST'))
        {
            $extensions = explode(".", $_FILES['file_import']['name']);
            $result = [];
            if ($extensions[1] == 'xlsx' || $extensions[1] == 'XLSX')
            {
                $file_name = $_FILES['file_import']['tmp_name'];
                $file = new Xlsx();
                $objPHPExcel = $file->load($file_name);
                // Customer sheet
                $getSheet = $objPHPExcel->getSheet(0);

                if (!empty($getSheet))
                {
                    $dataInput = $getSheet->toArray(null, true, true, true);
                    if (count($dataInput) > 0)
                    {
                        $results = [];

                        foreach ($dataInput as $key => $value)
                        {
                            if($key <= 1)
                            {
                                continue;
                            }

                            $results[$key] = $this->business_report->formatValueOrder($key, $value, $getSheet);

                        }

                        if($this->business_report->saveListOrder($results))
                        {
                            $this->Flash->success("Successfully.");
                        }else{
                            $this->Flash->error(__("Failed import"));
                        }
                    }
                    else
                    {
                        $this->Flash->error(__("Failed no data"));
                    }
                }
                else
                {
                    $this->Flash->error(__("Failed no sheet"));
                }
            }
            else
            {
                $this->Flash->error(__("Failed not xlsx"));
            }
        }
        return $this->redirect('/yeni/report/');
    }

    public function view($id)
    {
        $order = $this->business_report->getOne($id);
        $this->set('order', $order);
    }

    public function confirm($order_code)
    {
        if($this->business_report->confirm($order_code))
            $this->Flash->success("Confirm successfully");
        else
            $this->Flash->error("Can not confirm");
        return $this->redirect('/yeni/report/');
    }

    public function exportOrder($output_type = 'D')
    {
        $list_orders = $this->business_order->getListExport(1, true);
        $results = $list_orders->all()->toList();
        $set_product_model = new SetProductTable();
        $list_set_product = $set_product_model->find('list', [
            'fields' => ['id','name', 'code','del_flag'],
            'conditions' => ['SetProduct.del_flag' => UNDEL],
            'keyField' => 'code',
            'valueField' => function($value) {
                return $value['name'];
            },
        ])->contain(['SetProductDetail'])->toArray();
        $file_name = "Order_" . date('Y-m-d'). ".xlsx";
        $this->set(compact('results', 'output_type', 'file_name','list_set_product'));
        $this->viewBuilder()->setLayout('xls/default');
        $this->viewBuilder()->setTemplate('export_excel');
        $this->response->withDownload('Inventory_' . date('Y-m-d'). '.xlsx');
        $this->render();

        return;
    }

    public function delete($order_code)
    {
        $connection = ConnectionManager::get('default');
        try{
            $connection->begin();
            $this->business_report->delete($order_code);
            $this->Flash->success("Delete successfully.");
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $this->Flash->error("Delete failed.");
            $connection->rollback();
        }
        $connection->commit();
        return $this->redirect(['action' => "index"]);
    }
}
