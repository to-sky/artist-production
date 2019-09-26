<?php


namespace App\Services;


use App\Libs\Kartina\Purchase;
use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\ShippingZone;
use App\Models\User;
use App\PaymentMethods\AbstractPaymentProcessor;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    protected $_ticketService;
    protected $_mailService;
    protected $_api;

    public function __construct(TicketService $ticketService, MailService $mailService)
    {
        $this->_ticketService = $ticketService;
        $this->_mailService = $mailService;
        $this->_api = new Purchase();
    }

    /**
     * Compile order
     *
     * @param Request $request
     * @param User $user
     * @param Address $address
     * @param Address|null $additionalAddress
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkout(Request $request, User $user, Address $address, Address $additionalAddress = null)
    {
        /** @var ShippingZone|null $shippingZone */
        $shippingZone = ShippingZone::find($request->get('shipping_zone_id'));
        /** @var PaymentMethod|null $paymentMethod */
        $paymentMethod = PaymentMethod::find($request->get('payment_method_id'));

        $data = [
            'status' => Order::STATUS_PENDING,
            'tax' => Cart::tax(),
            'subtotal' => Cart::subtotal(),
            'user_id' => $user->id,
            'shipping_price' => $shippingZone->price ?? 0,
            'shipping_status' => Shipping::STATUS_IN_PROCESSING,
            'shipping_zone_id' => $shippingZone->id ?? null,
            'payment_method_id' => $paymentMethod->id ?? null,
        ];

        $order = Order::create(array_merge($data, $request->all()));

        if ($paymentMethod) {
            $order->service_price = $paymentMethod->calculateServicePrice($order->subtotal);
            $order->save();
        }

        $this->castAddress($order, $additionalAddress ?: $address, ShippingAddress::class);
        $this->castAddress($order, $address, BillingAddress::class);

        $this->_ticketService->attachCartToOrder($order);

        if ($this->shouldHaveKartinaId($order)) {
            $this->confirmKartinaOrder($order, $user);
        }

        $paymentProcessor = $this->getPaymentMethod($paymentMethod->id ?? 0);
        if ($paymentProcessor) {
            return $paymentProcessor->process($order);
        }

        return redirect('payment.error');
    }

    /**
     * Confirm payment
     *
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function confirm(Order $order, Request $request)
    {
        $processor = $this->getPaymentMethod($order->payment_method_id);

        if ($this->shouldHaveKartinaId($order)) {
            $this->confirmPaymentKartinaOrder($order);
        }

        return $processor->confirm($order, $request);
    }

    /**
     * Cancel payment
     *
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function cancel(Order $order, Request $request)
    {
        $processor = $this->getPaymentMethod($order->payment_method_id);

        return $processor->cancel($order, $request);
    }

    /**
     * Get payment method processor object
     *
     * @param $id
     * @return bool|AbstractPaymentProcessor
     */
    public function getPaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);


        @$paymentMethodClass = '\\App\\PaymentMethods\\' . $paymentMethod->name . '\\' . $paymentMethod->name . 'PaymentProcessor';
        if (class_exists($paymentMethodClass)) {
            return new $paymentMethodClass($this->_mailService, $this->_ticketService);
        }

        return false;
    }

    /**
     * Cast $address model data into $class and attach to $order
     *
     * @param Order $order
     * @param Address $address
     * @param string $class
     * @return Address|null
     */
    protected function castAddress(Order $order, Address $address, $class)
    {
        $data = $address->only([
            'first_name',
            'last_name',
            'street',
            'house',
            'apartment',
            'post_code',
            'city',
        ]);

        $data['country'] = $address->country->name;
        $data['order_id'] = $order->id;

        try {
            $address = $class::create($data);
        } catch (\Exception $e) {
            return null;
        }

        return $address;
    }

    /**
     * Checks if current order should have user on kartina.tv
     *
     * @param Order $order
     *
     * @return bool
     */
    protected function shouldHaveKartinaId(Order $order)
    {
        foreach ($order->tickets as $ticket) {
            if ($ticket->kartina_id) return true;
        }

        return false;
    }

    public function getKartinaClientForUser(User $user)
    {
        if (!$user->kartina_id) {
            $resp = $this->_api->registerClient([
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->profile->phone ?? null,
            ]);

            $user->kartina_id = $resp['clientId'];
            $user->save();
        }

        return $this->_api->loginClient($user->email);
    }

    protected function confirmKartinaOrder(Order $order, User $user = null)
    {
        if (empty($user)) $user = $order->user;

        $this->getKartinaClientForUser($user);

        $kartinaOrder = $this->_api->getOrder();

        $order->kartina_id = $kartinaOrder['orderId'];
        $order->save();

        $this->_api->setDeliveryType($order->kartina_id);
        $this->_api->setPaymentType($order->kartina_id);
        $this->_api->confirmOrder($order->kartina_id);
    }

    protected function confirmPaymentKartinaOrder(Order $order)
    {
        $this->_api->confirmOrder($order->kartina_id, 1);
    }
}
