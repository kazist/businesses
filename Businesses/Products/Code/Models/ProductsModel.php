<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Businesses\Products\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Businesses\Businesses\Code\Models\BusinessModel;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class ProductsModel extends BaseModel {

    public function saveProduct($form) {

        $factory = new KazistFactory();

        $id = $factory->saveRecord('#__businesses_businesses_products', $form);

        if ($id) {
            $this->saveProductPhotos($id);
        }

        return $id;
    }

    public function saveProductPhotos($product_id) {

        $factory = new KazistFactory();

        $media_ids = $factory->uploadMedia('photo');

        if (!empty($media_ids)) {
            foreach ($media_ids as $media_id) {
                $std_class = new \stdClass();

                $std_class->product_id = $product_id;
                $std_class->image = $media_id;

                $factory->saveRecord('#__businesses_businesses_products_images', $std_class);
            }
        }
    }

    public function getProduct($product_id) {

        $factory = new KazistFactory();

        $session = $factory->getSession();

        //$email->debug_exit = true;
        $uri = $session->get('uri');

        $query = new Query();
        $query->select('bb.*');
        $query->from('#__businesses_businesses_products', 'bb');
        if ($product_id) {
            $query->where('id=:id');
            $query->setParameter('id', (int) $product_id);
        } else {
            $query->where('1=-1');
        }


        $record = $query->loadObject();

        $record->url = $this->generateUrl('businesses.businesses.products.detail', array('id', $product_id));

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

        $businessModel = new BusinessModel();

        $item_obj = (is_object($item)) ? $item : new \stdClass();

        if (isset($item->business_id)) {
            $business = $businessModel->getBusiness($item->business_id);

            $item_obj->title = ucwords($item->title);
            $item_obj->photos = $this->getProductImages($item->id);
            $item_obj->business = $businessModel->getItemAdditionDetails($business, false);

            // print_r($item_obj);
            //  exit;
        }
        return $item_obj;
    }

    public function getProductImages($product_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mm.file, mm.title');
        $query->from('#__businesses_businesses_products_images', 'bpi');
        $query->leftJoin('bpi', '#__media_media', 'mm', 'mm.id = bpi.image');
        if ($product_id) {
            $query->where('bpi.product_id=:product_id');
            $query->setParameter('product_id', (int) $product_id);
        } else {
            $query->where('1=-1');
        }



        $records = $query->loadObjectList();

        return $records;
    }

    public function deleteProduct($product_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->delete('#__businesses_businesses_products');
        $query->where('id=:id');
        $query->setParameter('id', (int) $product_id);
        $query->execute();
    }

    public function deleteProductImage($product_image_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->delete('#__businesses_businesses_products_images');
        $query->where('id=:id');
        $query->setParameter('id', (int) $product_image_id);
        $query->execute();
    }

    public function getBusinesses() {

        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query->select('bb.*');
        $query->from('#__businesses_businesses', 'bb');
        if ($user->id) {
            $query->where('bb.created_by=:created_by');
            $query->setParameter('created_by', (int) $user->id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('bb.name');


        // print_r((string)$query); exit;

        $records = $query->loadObjectList();

        return $records;
    }

    public function getBusinessesOptions() {

        $tmp_array = array();
        $businesses = $this->getBusinesses();


        if (!empty($businesses)) {
            foreach ($businesses as $business) {
                $tmp_array[] = array('text' => $business->name, 'value' => $business->id);
            }
        }

        return $tmp_array;
    }

}
