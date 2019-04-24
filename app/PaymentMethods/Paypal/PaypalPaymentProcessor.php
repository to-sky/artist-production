<?php

namespace App\PaymentMethods\Paypal;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;

class PaypalPaymentProcessor
{

    protected $_apiContext;

    public function __construct()
    {
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        $this->_apiContext->setConfig(config('paypal.settings'));
    }

    public function process(Order $order)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = [];

        $totalQuantity = 0;

        foreach ($order->tickets() as $ticket) {

            $item = new Item();
            $item->setName($ticket->place->number)
                ->setCurrency(Order::CURRENCY)
                ->setQuantity(1)
                ->setSku($ticket->place->id)
                ->setPrice($ticket->place->price);

            $totalQuantity += 1;

            $items[] = $item;
        }

        $shipping = $order->shipping->price;
        $tax = 0;
        $subTotal = $order->subTotal;


        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping($shipping)
            ->setTax($tax)
            ->setSubtotal($subTotal);

        $total = $subTotal + $shipping + $tax;



        $amount = new Amount();
        $amount->setCurrency(Order::CURRENCY)
            ->setTotal($total)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription(trans_choice('Ticket for :event|Tickets for :event', $totalQuantity, ['event' => 'Some event']))
            ->setInvoiceNumber(uniqid());


        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('payment.confirm', ['order' => $order->id]))
            ->setCancelUrl(route('payment.cancel', ['order' => $order->id]));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_apiContext);
        } catch (Exception $ex) {
            Redirect::route('payment.error', ['order' => $order->id]);
        }

        $approvalUrl = $payment->getApprovalLink();



        Redirect::away($approvalUrl);

    }

    public function confirm(Order $order, Request $request)
    {

        if ($request->get('success', false)) {
            $paymentId = $request->get('paymentId', null);
            $payment = Payment::get($paymentId, $this->_apiContext);

            $execution = new PaymentExecution();
            $execution->setPayerId($request->get('PayerID', null));

            $transaction = new Transaction();
            $amount = new Amount();
            $details = new Details();

            $shipping = $order->shipping->price;
            $tax = 0;
            $subTotal = $order->subTotal;

            $details->setShipping($shipping)
                ->setTax($tax)
                ->setSubtotal($subTotal);

            $total = $shipping + $tax + $subTotal;

            $amount->setCurrency('USD');
            $amount->setTotal($total);
            $amount->setDetails($details);
            $transaction->setAmount($amount);

            $execution->addTransaction($transaction);

            try {
                $result = $payment->execute($execution, $this->_apiContext);

                if ($result->getState() == 'approved') {
                    $order->update(['status' => Order::STATUS_CONFIRMED]);

                    Redirect::route('payment.success', ['order' => $order->id]);
                }
            } catch (Exception $ex) {
                Redirect::route('payment.error', ['order' => $order->id]);
            }

        } else {
            $order->update(['status' => Order::STATUS_CANCELED]);

            Redirect::route('payment.cancel', ['order' => $order->id]);
        }
    }

}