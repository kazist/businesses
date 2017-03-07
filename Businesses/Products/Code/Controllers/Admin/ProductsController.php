<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of ProductsController
 *
 * @author sbc
 */

namespace Businesses\Businesses\Products\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Businesses\Businesses\Products\Code\Models\ProductsModel;

class ProductsController extends BaseController {

    public function indexAction() {

        $this->model = new ProductsModel();

        $items = $this->model->getRecords();
        $items = $this->model->getAdditionalDetail($items);

        $data_arr['items'] = $items;

        $this->html = $this->render('Businesses:Businesses:Products:Code:views:admin:table.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function detailAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Businesses/generalviews/';

        $this->model = new ProductsModel();


        $item = $this->model->getRecord();
        $item = $this->model->getItemAdditionDetails($item);

        //print_r($item); exit;

        $data_arr['item'] = $item;

        $this->html = $this->render('Businesses:Businesses:Products:Code:views:admin:detail.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function editAction() {
        $factory = new KazistFactory();

        $this->model = new ProductsModel();

        $item = $this->model->getRecord();
        $item = $this->model->getItemAdditionDetails($item);

        $businesses = $this->model->getBusinessesOptions();

        $data_arr['item'] = $item;
        $data_arr['businesses'] = $businesses;
        $data_arr['business_id'] = $this->request->request->get('business_id');

        $this->html = $this->render('Businesses:Businesses:Products:Code:views:admin:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
