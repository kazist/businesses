<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Business\Addons\Categories\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Business\Addons\Categories\Models\CategoriesModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class CategoryController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new CategoriesModel;

        $categories = $model->getBusinessCategory();

        $data_arr['categories'] = $categories;

        $this->html = $this->render('Businesses:Addons:Categories:views:categories.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
