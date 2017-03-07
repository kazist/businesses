<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Businesses\Businesses\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class BusinessesModel extends BaseModel {

    public $redirect;

    public function appendSearchQuery($query) {


        $package_id = $this->request->request->get('package_id');
        $category_id = $this->request->request->get('category_id');

        $query = parent::getQueryBuilder('#__businesses_businesses', 'bb');

        if ($package_id) {
            $query->where('bb.package_id=' . $package_id);
        }
        if ($category_id) {
            $query->where('bb.category_id=' . $category_id);
        }


        $query->orderBy('bb.payment_status ', 'DESC');
        $query->orderBy('bb.date_created ', 'DESC');

        return $query;
    }

    public function save($form) {

        $document = $this->container->get('document');
        $email = new Email();
        $factory = new KazistFactory();

        $default_gateway = $factory->getSetting('finance_gateway_default_gateway');

        $id = parent::save($form);

        if ($id) {
            $business = $this->getBusiness($id);
            $package_price = $this->getPackagePrice($business->package_price_id);

            $business->payment_amount = $package_price->price;
            $msg = 'Business Save Successfully.';

            $factory->saveRecord('#__businesses_businesses', $business);

            if ($form['new_payment'] || ($business->payment_amount && !$business->payment_status)) {
                $msg .= ' Proceed With Payment.';
                $factory->enqueueMessage($msg, 'info');
                $this->redirect = $this->generateUrl('finance.payments.newpayment', array(
                    'path' => 'businesses.businesses',
                    'gateway_id' => $default_gateway,
                    'pay_subset_id' => $document->subset_id,
                    'amount' => $business->payment_amount,
                    'item_id' => $business->id,
                    'description=' . 'Business: ' . $form['title']
                ));
            } else {
                $factory->enqueueMessage($msg, 'info');
                $this->redirect = $this->generateUrl('businesses.businesses.detail', array('id' => $id));
            }

            $this->saveBusinessPhotos($id);
        } else {

            $msg = 'Saving Business Failed.';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $this->generateUrl('businesses.businesses.edit');
        }

        return $id;
    }

    public function saveBusinessPhotos($business_id) {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $logo_ids = $factory->uploadMedia('form.logo', 'businesses.businesses', $business_id);
        $photo_ids = $factory->uploadMedia('form.photo', 'businesses.businesses', $business_id);

        $data_obj->id = $business_id;
        $data_obj->logo = (count($logo_ids)) ? $logo_ids[0] : 0;

        if ($data_obj->logo) {
            $factory->saveRecord('#__businesses_businesses', $data_obj);
        }

        foreach ($photo_ids as $key => $photo_id) {

            $new_data_obj = new \stdClass();
            $new_data_obj->business_id = $business_id;
            $new_data_obj->image = (count($photo_id)) ? $photo_id[0] : 0;

            if ($new_data_obj->image) {
                $factory->saveRecord('#__businesses_businesses_images', $new_data_obj, array('business_id=:business_id', 'image=:image'), $new_data_obj);
            }
        }
    }

    public function saveImages($blog_id) {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $media_ids = $factory->uploadMedia('image', null, null, 'business', 'business', 'business');

        $data_obj->id = $blog_id;
        $data_obj->image = (count($media_ids)) ? $media_ids[0] : 0;

        if ($data_obj->image) {
            $factory->saveRecord('#__businesses_businesses', $data_obj);
        }
    }

    public function sendMessage($form) {

        $tmp_array = array();
        $factory = new KazistFactory();
        $email = new Email();

        if ($this->sendMessageValidator($form)) {

            $tmp_array['business'] = $this->getBusiness($form['business_id']);
            $tmp_array['user'] = $this->getBusinessOwner($tmp_array['business']->created_by);
            $tmp_array['message'] = $form;

            $email->sendDefinedLayoutEmail('businesses.businesses.sendmessage', $tmp_array['user']->email, $tmp_array);
            $email->sendDefinedLayoutEmail('businesses.businesses.sendmessage.thankyou', $form['email'], $tmp_array);

            $factory->saveRecord('#__businesses_businesses_messages', $form);
        }
    }

    public function sendMessageValidator($form) {
        $is_valid = true;

        return $is_valid;
    }

    public function getBusiness($business_id) {

        $factory = new KazistFactory();

        $session = $factory->getSession();

        //$email->debug_exit = true;
        $uri = $session->get('uri');

        $query = new Query();
        $query->select('bb.*');
        $query->from('#__businesses_businesses', 'bb');
        if ($business_id) {
            $query->where('id=:id');
            $query->where('id', $business_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $this->getItemAdditionDetails($record);
    }

    public function getBusinesses($category_id = '', $business_id = '', $action = '', $offset = 0, $limit = 3) {

        $factory = new KazistFactory();


        $business = ($business_id) ? $this->getBusiness($business_id) : '';

        $query = new Query();
        $query->select('bb.*');
        $query->from('#__businesses_businesses', 'bb');
        $query->where('1=1');
        $query->orderBy('id ', 'DESC');

        if (is_object($business) && $action == 'related') {

            $business_name_arr = explode(' ', $business->name);

            if ($business->category_id) {
                $query->andWhere('category_id=:category_id');
                $query->setParameter('category_id', (int) $business->category_id);
            }

            foreach ($business_name_arr as $word) {
                $query->andWhere('name LIKE :name');
                $query->andWhere('name ', '%' . $word . '%');
            }
        } elseif ($action == 'featured') {
            $query->andWhere('featured=1');
        } elseif ($action == 'popular') {
            $query->addOrderBy('hit ', 'DESC');
        } else {
            
        }

        if ($category_id != '') {
            $query->andWhere('category_id=:category_id');
            $query->setParameter('category_id', (int) $category_id);
        }

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);


        $records = $query->loadObjectList();

        return $this->getAdditionalDetail($records);
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

    public function getItemAdditionDetails($item, $extended = true) {

        $item_obj = (is_object($item)) ? $item : new \stdClass();

        $item_obj->title = ucwords($item->title);
        $item_obj->logo = $this->getBusinessLogo($item);
        $item_obj->category = $this->getCategoryName($item->category_id);
        $item_obj->country = $this->getCountryName($item->country_id);
        $item_obj->location = $this->getLocationName($item->location_id);
        if ($extended) {
            $item_obj->images = $this->getBusinessPhotos($item->id);
            $item_obj->products = $this->getBusinessProducts($item->id);
            $item_obj->total_products = $this->getBusinessTotalProducts($item->id);
        }
        return $item_obj;
    }

    public function getLocationName($location_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('swr.*');
        $query->from('#__setup_regions', 'swr');
        if ($location_id) {
            $query->where('swr.id=:id');
            $query->setParameter('id', $location_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $record;
    }

    public function getCountryName($country_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('swc.*');
        $query->from('#__setup_countries', 'swc');
        if ($country_id) {
            $query->where('swc.id=:id');
            $query->setParameter('id', $country_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $record;
    }

    public function getCategoryName($category_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('bc.*');
        $query->from('#__businesses_categories', 'bc');
        if ($category_id) {
            $query->where('bc.id=:id');
            $query->setParameter('id', $category_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $record;
    }

    public function getBusinessProducts($business_id, $offset = 0, $limit = 6) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('bp.*');
        $query->from('#__businesses_businesses_products', 'bp');
        if ($business_id) {
            $query->where('bp.business_id=:business_id');
            $query->setParameter('business_id', $business_id);
        } else {
            $query->where('1=-1');
        }

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);


        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]->images = $this->getBusinessProductImages($record->id);
            }
        }

        return $records;
    }

    public function getBusinessTotalProducts($business_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('COUNT(*) AS total');
        $query->from('#__businesses_businesses_products', 'bp');
        if ($business_id) {
            $query->where('bp.business_id=:business_id');
            $query->setParameter('business_id', $business_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $record->total;
    }

    public function getBusinessProductImages($product_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mm.file, mm.title');
        $query->from('#__businesses_businesses_products_images', 'bpi');
        $query->leftJoin('bpi', '#__media_media', 'mm', 'mm.id = bpi.image');
        if ($product_id) {
            $query->where('bpi.product_id=:product_id');
            $query->setParameter('product_id', $product_id);
        } else {
            $query->where('1=-1');
        }



        $records = $query->loadObjectList();

        return $records;
    }

    public function loadBusinessProducts() {

        $paths = array();

        $object_arr = new \stdClass();
        $factory = new KazistFactory();


        $offset = $this->request->request->get('offset');
        $action = $this->request->request->get('action');

        $object_arr->business_id = $this->request->request->get('business_id');
        $object_arr->offset_products = ($action == 'previous') ? $offset - 6 : $offset + 6;

        $template = 'business.products.twig';
        $this->twig_paths[] = realpath(JPATH_ROOT . '/applications/Businesses/generalviews');
        $html = $factory->renderData($object_arr, $template, $paths);

        return $html;
    }

    public function getBusinessLogo($item) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mm.file, mm.title');
        $query->from('#__businesses_businesses', 'bb');
        $query->leftJoin('bb', '#__media_media', 'mm', 'mm.id = bb.logo');
        if ($item->id) {
            $query->where('bb.id=:id');
            $query->setParameter('id', $item->id);
        } else {
            $query->where('1=-1');
        }



        $record = $query->loadObject();
        //print_r($record); exit;

        return $record;
    }

    public function getBusinessPhotos($business_id) {

        $factory = new KazistFactory();



        $user_id = $this->request->request->get('user_id');
        $limit = $this->request->request->get('limit', 10);
        $offset = $this->request->request->get('offset', 0);

        $query = new Query();
        $query->select('mm.file, mm.title');
        $query->from('#__businesses_businesses_images', 'bai');
        $query->leftJoin('bai', '#__media_media', 'mm', 'mm.id = bai.image');

        if ($business_id) {
            $query->where('bai.business_id=:business_id');
            $query->setParameter('business_id', $business_id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('bai.id ', 'DESC');



        $records = $query->loadObjectList();


        return $records;
    }

    public function getPackagePrice($package_price_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mpp.*');
        $query->from('#__businesses_packages_prices', 'mpp');

        if ($package_price_id) {
            $query->where('mpp.id=:package_price_id');
            $query->setParameter('package_price_id', $package_price_id);
        } else {
            $query->where('1=-1');
        }



        $records = $query->loadObject();


        return $records;
    }

    public function getBusinessOwner($created_by) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('uu.*');
        $query->from('#__users_users', 'uu');
        if ($created_by) {
            $query->where('id=:id');
            $query->setParameter('id', $created_by);
        } else {
            $query->where('1=-1');
        }



        $record = $query->loadObject();

        return $record;
    }

    public function getCategories($parent_id = '') {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('bc.*');
        $query->from('#__businesses_categories', 'bc');
        if ($parent_id) {
            $query->where('bc.parent_id=:parent_id');
            $query->setParameter('parent_id', $parent_id);
        }
        $query->orderBy('bc.id ', 'DESC');



        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]->children = $this->getCategories($record->id);
            }
        }

        return $records;
    }

    public function getPackagePrices() {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mpp.*');
        $query->from('#__businesses_packages_prices', 'mpp');
        $query->orderBy('mpp.id ', 'DESC');



        $records = $query->loadObjectList();

        return $records;
    }

    public function getPackages($fetch_prices = false) {

        $factory = new KazistFactory();

        $query = new Query();
        $query->select('mp.*');
        $query->from('#__businesses_packages', 'mp');
        $query->orderBy('mp.id ', 'DESC');

        $records = $query->loadObjectList();

        if ($fetch_prices) {
            $tmp_arr = array();
            foreach ($records as $key => $record) {
                $record->prices = $factory->getRecords('#__businesses_packages_prices', 'jpp', array('jpp.package_id=:package_id'), array('package_id' => $record->id));
                $tmp_arr[$record->id] = $record;
            }

            $records = $tmp_arr;
        }

        return $records;
    }

    public function getCountries() {

        $tmparray = array();

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('swc.id AS value, swc.name AS text');
        $query->from('#__setup_countries', 'swc');
        $query->orderBy('swc.id ', 'DESC');



        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmparray[] = array('value' => $record->value, 'text' => $record->text);
            }
        }

        return $tmparray;
    }

    public function getLocations($country_id) {

        $tmparray = array();

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('swr.id AS value, swr.name AS text');
        $query->from('#__setup_countries', 'swc');
        $query->LeftJoin('swc', '#__setup_regions', 'swr', 'swc.code = swr.country');
        if ($country_id) {
            $query->where('swc.id=' . $country_id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('swr.id ', 'DESC');



        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmparray[] = array('value' => $record->value, 'text' => $record->text);
            }
        }

        return $tmparray;
    }

    public function deleteBusiness($business_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->delete('#__businesses_businesses');
        $query->where('id=:id');
        $query->setParameter('id', $business_id);

        $query->execute();
    }

    public function deleteBusinessImage($business_image_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->delete('#__businesses_businesses_images');
        $query->where('id=:id');
        $query->setParameter('id', $business_image_id);

        $query->execute();
    }

    public function getCategoriesOptions() {

        $tmp_array = array();
        $categories = $this->getCategories();


        if (!empty($categories)) {
            foreach ($categories as $category) {
                $tmp_array[] = array('text' => $category->title, 'value' => $category->id);
            }
        }

        return $tmp_array;
    }

}
