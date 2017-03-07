<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of BusinessesController
 *
 * @author sbc
 */

namespace Businesses\Businesses\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Businesses\Businesses\Code\Models\BusinessesModel;

class BusinessesController extends BaseController {

    public function indexAction() {

        $this->model = new BusinessesModel();

        $items = $this->model->getRecords();
        $items = $this->model->getAdditionalDetail($items);

        $data_arr['items'] = $items;

        $this->html = $this->render('Businesses:Businesses:Code:views:table.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function detailAction($id = '') {


        $this->model = new BusinessesModel();


        $item = $this->model->getRecord($id);
        $item = $this->model->getItemAdditionDetails($item);

        $countries = $this->model->getCountries();
        $locations = $this->model->getLocations($item->country_id);
        $packages = $this->model->getPackages(true);
        $package_prices = $this->model->getPackagePrices();
        $categories = $this->model->getCategories();

        $data_arr['view'] = $this->request->get('view');
        $data_arr['item'] = $item;

        $data_arr['countries'] = $countries;
        $data_arr['locations'] = $locations;
        $data_arr['categories'] = $categories;
        $data_arr['packages'] = $packages;
        $data_arr['package_prices'] = $package_prices;

        $this->html = $this->render('Businesses:Businesses:Code:views:detail.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function editAction($id = '') {

        $this->model = new BusinessesModel();

        $item = $this->model->getRecord($id);
        $item = $this->model->getItemAdditionDetails($item);

        $countries = $this->model->getCountries();
        $locations = $this->model->getLocations($item->country_id);
        $packages = $this->model->getPackages();
        $package_prices = $this->model->getPackagePrices();
        $categories = $this->model->getCategories();

        $data_arr['item'] = $item;
        $data_arr['countries'] = $countries;
        $data_arr['locations'] = $locations;
        $data_arr['categories'] = $categories;
        $data_arr['packages'] = $packages;
        $data_arr['package_prices'] = $package_prices;


        $this->html = $this->render('Businesses:Businesses:Code:views:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function saveAction($form_data = '') {

        if (WEB_IS_ADMIN) {
            return parent::saveAction($form_data);
        } else {

            $form_data = $this->request->request->get('form');

            $id = $this->model->save($form_data);

            if ($this->model->redirect <> '') {
                return $this->redirect($this->model->redirect);
            } else {
                return $this->redirectToRoute('businesses.businesses.detail', array('id' => $id));
            }
        }
    }

    public function sendmessageAction() {

        $factory = new KazistFactory();


        $form = $this->request->request->get('form');
        $business_id = $form['business_id'];


        $this->model = new BusinessesModel();
        $this->model->sendMessage($form);

        $redirect = $this->generateUrl('businesses.businesses.detail', array('id' => $business_id));

        return $this->redirect($redirect);
    }

    public function deletebusinessAction() {
        $factory = new KazistFactory();


        $business_id = $this->request->request->get('business_id');

        $this->model = new BusinessesModel();
        $id = $this->model->deleteBusiness($business_id);

        $redirect = $this->generateUrl('businesses.businesses.mybusiness');

        $this->redirect($redirect);
    }

    public function deleteimageAction() {

        $factory = new KazistFactory();


        $business_id = $this->request->request->get('business_id');
        $business_image_id = $this->request->request->get('business_image_id');

        $this->model = new BusinessesModel();
        $this->model->deleteBusinessImage($business_image_id);

        $redirect = $this->generateUrl('businesses.businesses.edit', array('id' => $business_id));

        $this->redirect($redirect);
    }

}
