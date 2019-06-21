<?php

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment as Pa;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PaymentProcessor
{
    public function process(Order $order)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $items = [];

        $subTotal = 0;

        foreach ($order->tickets() as $ticket) {

            $item = new Item();
            $item->setName($ticket->place->number)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setSku($ticket->place->id)// Similar to `item_number` in Classic API
                ->setPrice($ticket->place->price);

            $subTotal += $ticket->place->price;

            $items[] = $item;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $baseUrl = getBaseUrl();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true")
            ->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $request = clone $payment;

        try {
            $payment->create($apiContext);
        } catch (Exception $ex) {
            ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
            exit(1);
        }

        $approvalUrl = $payment->getApprovalLink();

        ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

        return $payment;

    }

}