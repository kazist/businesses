<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Categories\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Businesses\Businesses\Code\Models\BusinessModel;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class CategoriesModel extends BaseModel {

    public function getBusiness($category_id) {

        $factory = new KazistFactory();

        $session = $factory->getSession();

        //$email->debug_exit = true;
        $uri = $session->get('uri');

        $query = new Query();
        $query->select('bb.*');
        $query->from('#__businesses_businesses', 'bb');

        if ($category_id) {
            $query->where('bb.category_id=:category_id');
            $query->setParameter('category_id', (int) $category_id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('id ', 'DESC');



        $record = $query->loadObject();

        return $record;
    }

    //put your code here
    public function getAdditionalDetail($items) {

        $tmp_array = array();

        if (!empty($items)) {
            foreach ($items as $item) {
                $tmp_array[] = $this->getItemAdditionDetails($item);
            }
        }

        return $tmp_array;
    }

    public function getItemAdditionDetails($item) {

        $item_obj = (is_object($item)) ? $item : new \stdClass();
        $businessModel = new BusinessModel;

        $business = $this->getBusiness($item->id);

        $item_obj->title = ucwords($item->title);
        $item_obj->business = $businessModel->getItemAdditionDetails($business, false);

        return $item_obj;
    }

}
