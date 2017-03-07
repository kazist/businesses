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

namespace Businesses\Businesses\Products\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Businesses\Businesses\Products\Code\Models\ProductsModel;

class ProductsController extends BaseController {

    public function indexAction() {

        $this->model = new ProductsModel();

        $items = $this->model->getRecords();
        $items = $this->model->getAdditionalDetail($items);

        $data_arr['items'] = $items;

        $this->html = $this->render('Businesses:Businesses:Products:Code:views:table.index.twig', $data_arr);

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

        $this->html = $this->render('Businesses:Businesses:Products:Code:views:detail.index.twig', $data_arr);

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

        $this->html = $this->render('Businesses:Businesses:Products:Code:views:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function saveAction() {
        $redirect = '';

        $factory = new KazistFactory();

        $this->model = new ProductsModel();

        $session = $factory->getSession();

        $user = $session->get('user');

        if ($user->id) {

            $form = $this->request->request->get('form');

            $this->model = new ProductsModel();
            $id = $this->model->saveProduct($form);

            if ($id) {
                $msg = 'Product Save Successfully.';
                $factory->enqueueMessage($msg, 'info');
                $redirect = $this->generateUrl('businesses.businesses.products.detail', array('id', $id));

                $this->saveProductPhotos($id);
            } else {

                $msg = 'Saving Product Failed.';
                $factory->enqueueMessage($msg, 'info');
                $redirect = $this->generateUrl('businesses.businesses.products.edit');
            }

            $redirect = $return_url;
        } else {
            $redirect = $this->generateUrl('login');
        }
    }

    public function deleteproductAction() {
        $factory = new KazistFactory();

        $this->model = new ProductsModel();

        $product_id = $this->request->request->get('product_id');

        $this->model = new ProductsModel();
        $id = $this->model->deleteProduct($product_id);

        $redirect = $this->generateUrl('businesses.businesses.products.myproduct');
        return $redirect;
    }

    public function deleteimageAction() {

        $factory = new KazistFactory();

        $this->model = new ProductsModel();

        $base_url = WEB_ROOT;

        $product_id = $this->request->request->get('product_id');
        $product_image_id = $this->request->request->get('product_image_id');

        $this->model = new ProductsModel();
        $this->model->deleteProductImage($product_image_id);

        $redirect = $this->generateUrl('businesses.businesses.products.edit', array('id', $product_id));

        return $redirect;
    }

}
