<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Addons\Menubusinesses\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Businesses\Businesses\Code\Models\BusinessesModel;
use Kazist\Service\Database\Query;

class MenubusinessesModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getBusinesses() {

        $factory = new KazistFactory();
        $businessModel = new BusinessesModel();
        $db = $factory->getDbo();

        $query = $factory->getQueryBuilder('#__businesses_businesses', 'bb');

        $query->having('bb.logo<>\'\'');
        $query->where('bb.published=1');
        $query->orderBy('bb.payment_status', 'DESC');
        $query->orderBy('bb.date_created', 'DESC');

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $records = $query->loadObjectList();

        $records = $businessModel->getAdditionalDetail($records);

        return $records;
    }

}
