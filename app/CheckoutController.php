<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Checkout store method called.');
        $user = User::find(auth()->user()->id);
        $products = $user->cart;
        $total = Cart::getCartTotal($products);


            /** Create the order record */
            $order = new Order();
            $order->user_id = $user->id;
            $order->status = OrderStatus::Paid;
            $order->total = $total;
            $order->stripe_session_id = Str::random(20);
            $order->save();

            /** Create the payment record */
            $payment = new Payment();
            $payment->user_id = $user->id;
            $payment->order_id = $order->id;
            $payment->status = paymentStatus::Paid;
            $payment->total_amount = $total;
            $payment->stripe_session_id =Str::random(20);
            $payment->type = 'card';
            $payment->save();

            /** Create the order_product records */
            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->price
                ]);
            }

            /** Create the order_details records */
            $order->detail()->create([
                'customer_name' =>  $user ->name,
                'customer_email' =>  $user ->email,
                'customer_phone' =>  null,
                'country' => 'test country',
                'city' =>'test city',
                'postalcode' => 'test postalcode',
                'province' =>  null,
                'address1' => 'test address1',
                'address2' => null,
            ]);

            /** Empty the cart */
            $user->cart()->detach();

            return true;

    }

    
}
