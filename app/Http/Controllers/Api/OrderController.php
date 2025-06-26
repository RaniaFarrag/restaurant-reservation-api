<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Meal;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\PaymentStrategies\PaymentCalculator;
use App\Services\PaymentStrategies\PaymentStrategyResolver;


class OrderController extends Controller
{
    
    public function placeOrder(Request $request)
    {
         $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'meals' => 'required|array|min:1',
            'meals.*.id' => 'required|exists:meals,id',
            'meals.*.quantity' => 'required|integer|min:1',
        ]);

        $reservation_id = $request->reservation_id;
        $customer_id = Reservation::find($reservation_id)->customer_id;
        $table_id = Reservation::find($reservation_id)->table_id;
        $user = $request->user();

        $total = 0;
        $orderDetails = [];

        DB::beginTransaction();
        try{
            foreach($request->meals as $mealData){
                $meal = Meal::find($mealData['id']);

                if($meal->available_quantity < $mealData['quantity']){
                    throw new \Exception("Not enough quantity for meal: {$meal->description} and ID = {$meal->id}");
                }

                $price = $meal->price;
                $discount = ($meal->discount / 100) * $price;
                $finalPrice = ($price - $discount) * $mealData['quantity'];
                $total += $finalPrice;

                // Decrease quantity
                $meal->available_quantity -= $mealData['quantity'];
                $meal->save();

                $orderDetails[] = [
                    'meal_id' => $meal->id,
                    'amount_to_pay' => $finalPrice,
                ];
            }

            $order = Order::create([
                'reservation_id' => $reservation_id,
                'customer_id' => $customer_id,
                'table_id' => $table_id,
                'user_id' => $user->id,
                'total' => $total,
                'paid' => false,
                'date' => now()->toDateString(),
            ]);

            foreach ($orderDetails as $item) {
                $item['order_id'] = $order->id;
                OrderDetail::create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'total' => $total,
                'message' => 'Order Placed Successfully!'
            ]);


        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }


    }

    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|in:tax_and_service,service_only',
        ]);

        $order = Order::find($request->order_id);

        if ($order->paid) {
            return response()->json(['message' => 'Order already paid.'], 400);
        }

        try{
            $strategy = PaymentStrategyResolver::resolve($request->method);

            $calculator = new PaymentCalculator();
            $calculator->setStrategy($strategy);

            $finalAmount = $calculator->calculate($order->total);

            $order->update([
                'paid' => true,
                'total' => $finalAmount,
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'final_total' => number_format($finalAmount, 2),
                'payment_method' => $request->method,
                'message' => 'Order Paid Successfully',
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
    }
    


    }

}
