<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Addons\Businesses\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Businesses\Addons\Businesses\Models\BusinessesModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class BusinessesController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new BusinessesModel;


        $busiesses = $model->getBusinesses();


        $data['busiesses'] = $busiesses;

        $this->html = $this->render('Businesses:Addons:Businesses:views:businesses.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
