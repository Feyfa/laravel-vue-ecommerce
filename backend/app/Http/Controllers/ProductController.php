<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Product;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(string $user_id_seller)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_seller' => $user_id_seller
            ],
            [
                'user_id_seller' => ['required', 'integer'],
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        /* GET PRODUCT */
        $products = Product::where('user_id_seller', $validate['user_id_seller'])
                           ->get();
        /* GET PRODUCT */

        return response()->json(['status' => 200, 'products' => $products], 200);
    }

    public function show(string $user_id_seller, string $id)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_seller' => $user_id_seller,
                'id' => $id
            ],
            [
                'user_id_seller' => ['required', 'integer'],
                'id' => ['required', 'integer'],
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 402, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        /* GET ONE PRODUCT */
        $products = Product::where('user_id_seller', $validate['user_id_seller'])
                           ->where('id', $validate['id'])
                           ->get();
        /* GET ONE PRODUCT */
        
        return response()->json(['status' => 200, 'products' => $products]);
    }

    public function store(Request $request)
    {
        /* VALIDATE AND GET */
        $validator = Validator::make($request->all(), [
            'user_id_seller' => ['required', 'integer'],
            'img' => ['image', 'file', 'max:1024', 'required'],
            'name' => ['required', 'min:3'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:1']
        ]);

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATE AND GET */

        /* CREATE PRODUCT AND STORE IMG */
        $validate['img'] = $request->file('img')->store('product-imgs');

        Product::create($validate);
        /* CREATE PRODUCT AND STORE IMG */
        
        /* GET PRODUCT */
        $products = Product::where('user_id_seller', $validate['user_id_seller'])
                           ->get();
        /* GET PRODUCT */

        return response()->json(['status' => 200, 'message' => 'Add Product Success', 'products' => $products], 200);
    }

    public function update(string $user_id_seller, string $id, Request $request)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_seller' => $user_id_seller,
                'id' => $id,
                'oldImg' => $request->oldImg,
                'img' => $request->img,
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock
            ],
            [
                'user_id_seller' => ['required', 'integer'],
                'id' => ['required', 'integer'],
                'oldImg' => ['required'],
                'img' => ['image', 'file', 'max:1024', 'required'],
                'name' => ['required', 'min:3'],
                'price' => ['required', 'integer', 'min:1'],
                'stock' => ['required', 'integer', 'min:1']
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        /* DELETE IMG PROVIOUS AND ADD IMG */
        if($request->file('img'))
        {
            if($validate['old_img'])
            {
                Storage::delete($validate['old_img']);
            }

            $validate['img'] = $request->file('img')->store('product-imgs');
        }
        /* DELETE IMG PROVIOUS AND ADD IMG */

        /* UPDATE PRODUCT */
        Product::where('id', $validate['id'])
               ->update($validate);
        /* UPDATE PRODUCT */

        /* GET PRODUCT */
        $products = Product::where('user_id_seller', $validate['user_id_seller'])
                           ->get();
        /* GET PRODUCT */

        return response()->json(['status' => 200, 'message' => 'Update Product Success', 'products' => $products]);
    }

    public function delete(string $user_id_seller, string $id)
    {
        /* VALIDATOR AND GET */
        $validator = Validator::make(
            [
                'user_id_seller' => $user_id_seller,
                'id' => $id
            ],
            [
                'user_id_seller' => ['required', 'integer'],
                'id' => ['required', 'integer'],
            ]
        );

        if($validator->fails())
            return response()->json(['status' => 402, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATOR AND GET */

        /* DELETE */
        Keranjang::where('product_id', $validate['id'])
                 ->delete();

        $product = Product::where('id', $validate['id'])
                          ->first();

        // Storage::delete($product->img);

        $product->delete();
        /* DELETE */

        /* GET PRODUCT */
        $products = Product::where('user_id_seller', $validate['user_id_seller'])
                           ->get();
        /* GET PRODUCT */

        return response()->json(['status' => 200, 'message' => 'Delete Product Success', 'products' => $products], 200);
    }
}
