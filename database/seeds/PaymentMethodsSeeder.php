<?php

use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = require(dirname(__FILE__) . '/../data/payment_methods.php');

        foreach ($paymentMethods as $paymentMethod) {
            \App\Models\PaymentMethod::create($paymentMethod);
        }
    }
}
