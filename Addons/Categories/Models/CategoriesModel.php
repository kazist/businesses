<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Addons\Categories\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class CategoriesModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getBusinessCategory() {

        $query = $this->getQuery();

        $records = $query->loadObjectList();

        return $records;
    }

    public function getQuery() {
        $factory = new KazistFactory;
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('bc.*');
        $query->from('#__businesses_categories', 'bc');
        $query->where('bc.published=1');
        $query->orderBy('bc.id', 'DESC');

        return $query;
    }

}
