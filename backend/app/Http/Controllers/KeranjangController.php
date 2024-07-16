<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KeranjangController extends Controller
{
    public function index(string $user_id_buyer)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_buyer' => $user_id_buyer
            ],
            [
                'user_id_buyer' => ['required', 'integer'],
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */
        
        /* GET ITEM IN BASKET */
        $keranjangs = Keranjang::select(
                                    'keranjangs.*',
                                    DB::raw('COUNT(*) as product_count'),
                                    DB::raw('SUM(products.price) as price_total')
                                )
                                ->join('products', 'keranjangs.product_id', '=', 'products.id')
                                ->where('user_id_buyer', $validate['user_id_buyer'])
                                ->groupBy('product_id')
                                ->get();
        /* GET ITEM IN BASKET */
        
        /* CALCULATION PRICE */
        $totalPrice = 0;
        foreach($keranjangs as $keranjang)
        {
            if($keranjang->checked == 1) {
                $totalPrice += $keranjang->price_total; 
            }
        }
        /* CALCULATION PRICE */

        return response()->json(['status' => 200, 'keranjangs' => $keranjangs, 'totalPrice' => $totalPrice], 200);
    }

    public function store(string $user_id_buyer, string $product_id)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_buyer' => $user_id_buyer,
                'product_id' => $product_id,
            ],
            [
                'user_id_buyer' => ['required', 'integer'],
                'product_id' => ['required', 'integer'],
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        $validate['checked'] = 0;

        Keranjang::create($validate);

        return response()->json(['status' => 200, 'message' => 'Item Has Been Added To Basket'], 200);
    }

    public function delete(string $user_id_buyer, string $product_id)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_buyer' => $user_id_buyer,
                'product_id' => $product_id,
            ],
            [
                'user_id_buyer' => ['required', 'integer'],
                'product_id' => ['required', 'integer'],
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        /* DELETE KERANJANG */
        $keranjangs = Keranjang::where('user_id_buyer', $validate['user_id_buyer'])
                               ->where('product_id', $validate['product_id'])
                               ->get();
 
        foreach($keranjangs as $keranjang)
        {
            $keranjang->delete();
        }
        /* DELETE KERANJANG */

        /* GET ITEM IN BASKET */
        $keranjangs = Keranjang::select(
                                    'keranjangs.*',
                                    DB::raw('COUNT(*) as product_count'),
                                    DB::raw('SUM(products.price) as price_total')
                                )
                                ->join('products', 'keranjangs.product_id', '=', 'products.id')
                                ->where('user_id_buyer', $validate['user_id_buyer'])
                                ->groupBy('product_id')
                                ->get();
        /* GET ITEM IN BASKET */

        /* CALCULATION PRICE */
        $totalPrice = 0;
        foreach($keranjangs as $keranjang)
        {
            if($keranjang->checked == 1) {
                $totalPrice += $keranjang->price_total; 
            }
        }
        /* CALCULATION PRICE */

        return response()->json(['status' => 200, 'message' => 'Item In Basket Has Been Delete', 'keranjangs' => $keranjangs, 'totalPrice' => $totalPrice], 200);
    }

    public function checked(string $user_id_buyer, string $product_id, string $checked)
    {
         /* VALIDATOR AND GET */
         $validator = Validator::make(
            [
                'user_id_buyer' => $user_id_buyer,
                'product_id' => $product_id,
                'checked' => $checked,
            ],
            [
                'user_id_buyer' => ['required', 'integer'],
                'product_id' => ['required', 'integer'],
                'checked' => ['required', 'in:true,false']
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        /* CHANGE CHECKED */
        $keranjangs = Keranjang::where('product_id', $validate['product_id'])
                               ->where('user_id_buyer', $validate['user_id_buyer'])
                               ->get();
                    
        foreach($keranjangs as $keranjang)
        {
            $keranjang->checked = ($validate['checked'] === 'true') ? true : false;
            $keranjang->save();
        }
        /* CHANGE CHECKED */

        /* GET ITEM IN BASKET */
        $keranjangs = Keranjang::select(
                                    'keranjangs.*',
                                    DB::raw('COUNT(*) as product_count'),
                                    DB::raw('SUM(products.price) as price_total')
                                )
                                ->join('products', 'keranjangs.product_id', '=', 'products.id')
                                ->where('user_id_buyer', $validate['user_id_buyer'])
                                ->groupBy('product_id')
                                ->get();
        /* GET ITEM IN BASKET */

        /* CALCULATION PRICE */
        $totalPrice = 0;
        foreach($keranjangs as $keranjang)
        {
            if($keranjang->checked == 1) {
                $totalPrice += $keranjang->price_total; 
            }
        }
        /* CALCULATION PRICE */

        return response()->json(['status' => 200, 'keranjangs' => $keranjangs, 'totalPrice' => $totalPrice], 200);
    }
}
