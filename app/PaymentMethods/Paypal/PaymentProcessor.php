<?php

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
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
                ->setSku($ticket->place->id)
                ->setPrice($ticket->place->price);

            $subTotal += $ticket->place->price;

            $items[] = $item;
        }

        $shipping = $order->shipping->price;
        $tax = 0;


        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping($shipping)
            ->setTax($tax)
            ->setSubtotal($subTotal);

        $total = $subTotal + $shipping + $tax;



        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($total)
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



        return $approvalUrl;

    }

    public function confirm(Request $request)
    {

        if ($request->get('success', false)) {
            $paymentId = $request->get('paymentId');
            $payment = Payment::get($paymentId, $apiContext);

            $execution = new PaymentExecution();
            $execution->setPayerId($request->get('PayerID', null));

            $transaction = new Transaction();
            $amount = new Amount();
            $details = new Details();

            $shipping = $order->shipping->price;
            $tax = 0;

            $details->setShipping($shipping)
                ->setTax($tax)
                ->setSubtotal(17.50);

            $amount->setCurrency('USD');
            $amount->setTotal(21);
            $amount->setDetails($details);
            $transaction->setAmount($amount);

            $execution->addTransaction($transaction);

            try {
                $result = $payment->execute($execution, $apiContext);

                ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);

                try {
                    $payment = Payment::get($paymentId, $apiContext);
                } catch (Exception $ex) {
                    ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
                    exit(1);
                }
            } catch (Exception $ex) {
                ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
                exit(1);
            }

            ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);

            return $payment;
        } else {
            ResultPrinter::printResult("User Cancelled the Approval", null);
            exit;
        }
    }

}