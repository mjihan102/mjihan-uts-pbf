<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Create
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'category_id' => 'required|exists:categories,id',
            'expired_at' => 'nullable|date',
            'modified_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        if ($request->hasFile('image')) {
            // Save the image file and get its path
            $path = $request->file('image')->store('images', 'public');
            $payload['image'] = $path;
        }

        $product = Product::create($payload);

        return response()->json([
            'msg' => 'Data Produk berhasil disimpan',
            'product' => $product,
        ], 201);
    }

    // Read
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['msg' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    // Update
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['msg' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255',
            'description' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'image' => 'sometimes|image',
            'category_id' => 'sometimes|required|exists:categories,id',
            'expired_at' => 'nullable|date',
            'modified_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        if ($request->hasFile('image')) {
            // Save the new image file and get its path
            $path = $request->file('image')->store('images', 'public');
            $payload['image'] = $path;
        }

        $product->update($payload);

        return response()->json([
            'msg' => 'Data Produk berhasil diperbarui',
            'product' => $product,
        ], 200);
    }

    // Delete
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['msg' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['msg' => 'Data Produk berhasil dihapus'], 200);
    }
}
