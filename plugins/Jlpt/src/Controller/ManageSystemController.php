<?php

namespace Jlpt\Controller;

use Jlpt\Library\Business\ManageSystem;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \Jlpt\Controller\AppController;
class ManageSystemController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->business_manage_system = new ManageSystem();
    }

    /**
     * @return void
     */
    public function index()
    {
        if($this->getRequest()->is('post')) {
            $this->business_manage_system->format_birthday_BE($_POST);
            if($this->business_manage_system->update($_POST, $_FILES))
            {
                $this->Flash->success("Successfully.");
            }else{
                $this->Flash->error("Failed.");
            }
        }

        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $arr['key_write'] = $key_write = $_GET['key_write'] ?? "";
        $arr['key_payment'] = $key_payment = $_GET['key_payment'] ?? "";
        $arr['key_exam'] = $key_exam = $_GET['key_exam'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_customer = $this->business_manage_system->getList($key_search, $key_write, $key_payment,$key_exam, $page, false);
        $paginate = $this->Common->displayPaginationBelow(LIMIT, $page, $list_customer->count(), $arr);

        $this->set('list_customers',$list_customer->all()->toList());
        $this->set('paginate',$paginate);
        $data_request = !empty($list_customer->all()->toList()) ? $list_customer->all()->toList()[0] : [];
        $this->set('data_request', $data_request);

    }

    public function view($id)
    {
        if($this->getRequest()->is('post')) {
            $this->business_manage_system->format_birthday_BE($_POST);
            if($this->business_manage_system->update($_POST, $_FILES))
            {
                $this->Flash->success("Successfully.");
            }else{
                $this->Flash->error("Failed.");
            }
        }
        $this->set('data_request', $this->business_manage_system->getOne($id));
    }

    public function create()
    {
        if($this->getRequest()->is('post')) {
            $this->business_manage_system->format_birthday_BE($_POST);
            if($id = $this->business_manage_system->create($_POST, $_FILES))
            {
                $this->Flash->success("Successfully.");
                $this->redirect("/manage-system/view/$id");
            }else{
                $this->Flash->error("Failed.");
            }
        }
    }

    public function delete($id)
    {
        if($this->business_manage_system->delete($id))
        {
            $this->Flash->success("Successfully.");
        }else{
            $this->Flash->error("Failed.");
        }
        return $this->redirect('/manage-system/');
    }

    public function export($output_type = 'D', $file = 'my_spreadsheet.xlsx')
    {
        $arr['key_search'] = $key_search = $_GET['key_search'] ?? "";
        $arr['key_write'] = $key_write = $_GET['key_write'] ?? "";
        $arr['key_payment'] = $key_payment = $_GET['key_payment'] ?? "";
        $arr['key_exam'] = $key_exam = $_GET['key_exam'] ?? "";
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)trim($_GET['page']) : 1;

        $list_customer = $this->business_manage_system->getList($key_search, $key_write, $key_payment,$key_exam, $page, true);
        $results = $list_customer->all()->toList();
        $file_name = "BUI_GIA_HUNG_" . date('Y-m-d'). ".xlsx";
        $this->set(compact('results', 'output_type', 'file_name'));
        $this->viewBuilder()->setLayout('xls/default');
        $this->viewBuilder()->setTemplate('export_excel');
        $this->response->withDownload('shipment_' . date('Y-m-d'). '.xlsx');
        $this->render();

        return;
    }

    public function import()
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
                        $params = [];
                        foreach ($dataInput as $key => $value)
                        {
                            if($key == 0)
                            {
                                continue;
                            }
                            if($value['A'] == null)
                            {
                                break;
                            }
                            $params[$key]['code'] = $value['B'];
                            $params[$key]['first_name'] = $value['C'];
                            $params[$key]['last_name'] = $value['D'];
                            $params[$key]['year'] = $value['E'];
                            $params[$key]['month'] = $value['F'];
                            $params[$key]['day'] = $value['G'];
                            $params[$key]['gender'] = $value['H'];
                            $params[$key]['cccd'] = $value['I'];
                            $params[$key]['phone'] = $value['J'];
                            $params[$key]['email'] = $value['K'];
                            $params[$key]['level'] = $value['L'];
                            $params[$key]['where_from'] = $value['M'];
                            $params[$key]['exam'] = $value['N'];
                            $params[$key]['referral'] = $value['O'];
                            $params[$key]['pic'] = $value['P'];
                            $params[$key]['avatar'] = $value['Q'];

                        }
                        if($this->business_manage_system->saveList($params))
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
        return $this->redirect('/manage-system/');
    }
}
