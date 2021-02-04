<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Storage;
use Auth;
use DB;
use DateTime;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //show all products
        return response()->json(
            Product::all(),
            200
        );
    }

    //show products of authorized users
    public function showWithAuth() {
        return response()->json(
            Product::where('user_id', Auth::id())->get(),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        //validate product before storing
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'units' => 'required',
            'price' => 'required',
        ]);

        // $product = Product::create([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'units' => $request->units,
        //     'price' => $request->price,
        //     'image' => $request->image,
        //     'category' => $request->category 
        // ]); 

        //save product with a qrcode
        $product = new Product;
        $product->id = $request->id;
        $id = $product->id;

        $image = \QrCode::format('png')
            ->size(300)->errorCorrection('H')
            ->generate('http://localhost:8000/products/$id');

        $file_name = time().'.png';
        $output_file = '/public/qrcode_img/'.$file_name;
        Storage::disk('local')->put($output_file, $image);

        
        $product->name = $request->name;
        $product->description = $request->description;
        $product->units = $request->units;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->image = $request->image;
        $product->expires_at = new DateTime(add(new DateInterval('P15D')));
        $product->user_id = Auth::id();
        $product->qrcodeUrl = url('/').'/storage/app/public/qrcode_img/'.$file_name;

        $product->save();

        return response()->json([
            'status' => (bool) $product,
            'data'  => $product,
            'message' => $product ? 'Product successfully stored' : 'Error storing product'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    //Uploading a file
    public function uploadFile(Request $request) {
        if($request->hasFile('image')) {
            $name = time()."_".$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $name);
        }
        return response()->json([
            asset("images/$name"),
            201,
            'message' => asset("images/$name") ? 'Image saved' : 'Image failed to save'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'units' => 'required',
            'price' => 'required',
        ]);

        $status = $product->update(
            $request->only(['name', 'description', 'units', 'price', 'image', 'category'])
        );

        return response()->json([
            'data' => $product,
            'status' => $status,
            'message' => $status ? 'Product successfully updated!' : 'Error updating product'
        ]);
    }

    //updating units
    public function updateUnits(Request $request, Product $product) {
        $product->units = $product->units + $request->get('units');
        $status = $product->save();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Units added!' : 'Error adding product units'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */ 
    public function destroy(Product $product)
    {
        $status = $product->delete();

        return response()->json([
            'data' => $product,
            'status' => $status,
            'message' => $status ? 'Product Deleted!' : 'Error deleting product'
        ]);
    }

    public function countFruits() {
        return response()->json(Product::where('category', 'fruit')->get()->count(), 200);
    }
    public function countVegetables() {
        return response()->json(Product::where('category', 'vegetable')->get()->count(), 200);
    }
}
