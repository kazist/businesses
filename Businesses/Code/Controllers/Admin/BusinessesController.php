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

namespace Businesses\Businesses\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Businesses\Businesses\Code\Models\BusinessesModel;

class BusinessesController extends BaseController {

    public function indexAction() {

        $this->model = new BusinessesModel($this->container, $this->request);

        $items = $this->model->getRecords();
        $items = $this->model->getAdditionalDetail($items);

        $data_arr['items'] = $items;

        $this->html = $this->render('Businesses:Businesses:Code:views:admin:table.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function detailAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Businesses/generalviews/';

        $this->model = new BusinessesModel($this->container, $this->request);

        $item = $this->model->getRecord();
        $item = $this->model->getItemAdditionDetails($item);

        //print_r($item); exit;

        $data_arr['item'] = $item;

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

        $this->html = $this->render('Businesses:Businesses:Code:views:admin:detail.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function editAction() {

        $this->model = new BusinessesModel($this->container, $this->request);

        $item = $this->model->getRecord();
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

        $this->html = $this->render('Businesses:Businesses:Code:views:admin:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
