<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TelegramBot\TelegramOrderController;
use App\Models\Order\Detail;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\Type as ProductType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class POSController extends Controller
{
    public function getProducts()
    {
        $data = ProductType::select('id', 'name')
        ->with([
            'products:id,name,image,type_id,unit_price'
        ])
        ->get();

        return response()->json($data, Response::HTTP_OK);
    }

    public function makeOrder(Request $req)
    {
        //==============================>> Get Current Login User
        $user = JWTAuth::parseToken()->authenticate();

        //==============================>> Check validation
        $this->validate($req, [
            'cart'      => 'required|json',
            'status_id' => 'required'
        ]);

        // ===>> Create Order
        $order                  = new Order;
        $order->cashier_id      = $user->id;
        $order->status_id       = 1;
        $order->total_price     = 0;
        $order->receipt_number  = $this->generateReceiptNumber();
        $order->save();

        // ===>> Find Total Price & Order Detail
        $details    = [];
        $totalPrice = 0;
        $cart       = json_decode($req->cart);

        //return $cart;

        foreach ($cart as $productId => $qty) {

            $product = Product::find($productId);
            if ($product) {
                $total_price_this_product = 0;
                $total_price_this_product =$qty * $product->unit_price;
                $details[] = [
                    'order_id'      => $order->id,
                    'product_id'    => $productId,
                    'qty'           => $qty,
                    'unit_price'    => $product->unit_price,
                    'total_price_this_product' => $total_price_this_product
                ];

                $totalPrice +=  $qty * $product->unit_price;
            }
        }

        // ===>> Save tot Details
        Detail::insert($details);

        // ===>> Update Order
        $order->total_price     = $totalPrice;
        $order->ordered_at      = Date('Y-m-d H:i:s');
        $order->save();


        // ===> Get Data for Client Reponse to view the order in Popup.
        $data = Order::select('*')
        ->with([
            'cashier:id,name,type_id',
            'cashier.type:id,name',

            'details:id,order_id,product_id,unit_price,qty,total_price_this_product',
            'details.product:id,name,type_id',
            'details.product.type:id,name',

            'status:id,name,color'
        ])
        ->find($order->id);

        // Send Notification
        // $sendOrderNotification = TelegramOrderController::sendOrderNotification($order);

        return response()->json([
            'order'         => $data,
            'message'       => 'ការបញ្ជាទិញត្រូវបានបង្កើតដោយជោគជ័យ។'
        ], Response::HTTP_OK);
    }

    function generateReceiptNumber()
    {
        $number = rand(1000000, 9999999);
        $check  = Order::where('receipt_number', $number)->first();
        if ($check) {
            return $this->generateReceiptNumber();
        }
        return $number;
    }
}
