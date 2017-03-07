<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

namespace Businesses\Businesses\Code\Listeners;

defined('KAZIST') or exit('Not Kazist Framework');

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Finance\Payments\Code\Events\PaymentEvent;
use Businesses\Businesses\Code\Models\PaymentModel;

/**
 * Some of Events to be fired
 * - user.before.registration
 * - user.after.registration
 * - user.before.login
 * - user.after.login
 * - user.before.save
 * - user.after.save
 * - user.before.delete
 * - user.after.delete
 * 
 */
class PaymentListener implements EventSubscriberInterface {

    public $container = '';

    public function onPaymentNotification(PaymentEvent $event) {
        global $sc;
        $this->container = $sc;

        $payment = $event->getPayment();
        $subset = $event->getSubset();

        if ($subset->path == 'businesses/businesses') {

            $paymentModel = new PaymentModel();
            $paymentModel->paymentSuccessful($payment);
        }
    }

    public function onPaymentSuccessful(PaymentEvent $event) {
        global $sc;
        $this->container = $sc;

        $payment = $event->getPayment();
        $subset = $event->getSubset();

        if ($subset->path == 'businesses/businesses') {

            $paymentModel = new PaymentModel();
            $paymentModel->paymentSuccessful($payment);
        }
    }

    public function onPaymentFail(PaymentEvent $event) {
        global $sc;
        $this->container = $sc;

        $payment = $event->getPayment();
        $subset = $event->getSubset();

        if ($subset->path == 'businesses/businesses') {

            $paymentModel = new PaymentModel();
            $paymentModel->paymentFail($payment);
        }
    }

    public function onPaymentInvalid(PaymentEvent $event) {
        global $sc;
        $this->container = $sc;

        $payment = $event->getPayment();
        $subset = $event->getSubset();

        if ($subset->path == 'businesses/businesses') {

            $paymentModel = new PaymentModel();
            $paymentModel->paymentFail($payment);
        }
    }

    public function onPaymentPending(PaymentEvent $event) {
        global $sc;
        $this->container = $sc;
    }

    public function onPaymentComplete(PaymentEvent $event) {
        global $sc;
        $this->container = $sc;

        $payment = $event->getPayment();
        $subset = $event->getSubset();

        if ($subset->path == 'businesses/businesses') {
            $paymentModel = new PaymentModel();
            $paymentModel->paymentComplete($payment);
        }
    }

    public function onPaymentCancel(PaymentEvent $event) {

        global $sc;
        $this->container = $sc;

        $payment = $event->getPayment();
        $subset = $event->getSubset();

        if ($subset->path == 'businesses/businesses') {

            $paymentModel = new PaymentModel();
            $paymentModel->paymentCancel($payment);
        }
    }

    public static function getSubscribedEvents() {
        return array(
            'payment.notification' => 'onPaymentNotification',
            'payment.successful' => 'onPaymentSuccessful',
            'payment.fail' => 'onPaymentFail',
            'payment.invalid' => 'onPaymentInvalid',
            'payment.pending' => 'onPaymentPending',
            'payment.complete' => 'onPaymentComplete',
            'payment.cancel' => 'onPaymentCancel',
        );
    }

}
