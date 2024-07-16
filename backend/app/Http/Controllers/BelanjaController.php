<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BelanjaController extends Controller
{
    public function index(string $user_id_seller)
    {  
        /* GET PRODUCT EXCEPT MY PRODUCT */
        $products = Product::where('user_id_seller', '<>', $user_id_seller)
                           ->get();
        /* GET PRODUCT EXCEPT MY PRODUCT */

        return response()->json(['status' => 200, 'products' => $products], 200);
    }
}
