<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Log\Log;
use Cake\View\Exception\MissingTemplateException;
use Yeni\Library\Business\Orders;
use Yeni\Library\Business\Product;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
     *   be found and in debug mode.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found and not in debug mode.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->business_order = new Orders();
        $this->business_product = new Product();
    }
    public function index()
    {
        $this->viewBuilder()->disableAutoLayout();
        $this->set('layout',false);

        $list_products = $this->business_product->getListProduct([]);
        $this->set('list_products', $list_products);
        if ($this->request->is('ajax')) {
            $status = false;
            $result_create_order = $this->business_order->createOrderZalo($_POST['array_form']);
            if($result_create_order)
            {
                $result_create_quoting = $this->business_order->createQuotingZalo($_POST['array_form']);
                if($result_create_quoting)
                    $status = true;
            }
//            //save excel
//            $htmlString = $_POST['html_message'];
//
//            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
//            $spreadsheet = $reader->loadFromString($htmlString);
//
//            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
//            $writer->save('write.xls');

            return $this->response->withStringBody(json_encode(compact('status')));
        }
    }

    public function landing()
    {

    }

    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }
}
