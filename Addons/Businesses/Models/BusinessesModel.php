<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Addons\Businesses\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class BusinessesModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getBusinesses() {

        $query = $this->getQuery();

        $records = $query->loadObjectList();


        return $records;
    }

    public function getQuery() {

        $query = new Query();

        $query->select('bb.*, mm.file as business_file, bc.title AS category_name');
        $query->from('#__businesses_businesses', 'bb');
        $query->leftJoin('bb', '#__media_media', 'mm', 'mm.id = bb.logo');
        $query->leftJoin('bb', '#__businesses_categories', 'bc', 'bc.id = bb.category_id');
        $query->where('published = 1');
        $query->orderBy('bb.payment_status ', 'DESC');
        $query->orderBy('bb.date_created', ' DESC');

        return $query;
    }

}
