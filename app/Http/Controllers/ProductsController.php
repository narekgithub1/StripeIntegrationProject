<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    public function __construct()
    {

    }

    public function create(Request $request)
    {

//        try {
//            $validator = Validator::make($request->toArray(), [
//                'name' => 'required',
//                'amount' => 'required|numeric',
//                'count' => 'required|numeric',
//            ]);
//
//            if ($validator->fails()) {
//                ($errors = $validator->errors());
//            }
            $data = [
                'name' => $request->name,
                'amount' => $request->amount,
                'count' => $request->count,
                'seller_id' => auth()->user()->id
            ];

            $product = Product::create($data);
        return ('/home');
//            return response()->json(['message' => 'success'], 201);
//        } catch (\Exception $e) {
//            return response()->json([
//                $errors], 400);
//        }
    }

    public function showAllProducts()
    {
        return response()->json(Product::all());
    }

    public function showOneProduct($id)
    {
        return response()->json(Product::find($id));
    }

    public function update($id, Request $request)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()], 204);
        }
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        return response([
            "message" => "Product successfully deleted"
        ]);
    }

    public function showMyProduct()
    {
        $userId = auth()->user()->id;
        $myProducts = Product::where('seller_id', '=', $userId)->all();

        return response()->json($myProducts, 201);
    }

    public function searchProduct(Request $request)
    {
        $myProducts = Product::where('name', 'ILIKE', '%' . $request->keyword . '%')->all();
        return response()->json($myProducts);
    }

}
