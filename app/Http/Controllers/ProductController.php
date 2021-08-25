<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Storage;
use Auth;
use DB;
use DateTime;
use Spatie\Searchable\Search;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json(
        //     Product::all(),
        //     200
        // ); 
        $data = Product::latest()->paginate(4);
        
        return response()->json(
            $data
        );
    }
    // public function paginatedProducts() {
    //     //show all products with pagination
    //     $data = Product::latest()->paginate(4);
        
    //     return response()->json(
    //         $data
    //     );
    // }

    //instant search
    public function searchProduct(Request $request) {
        $results = (new Search())
        ->registerModel(Product::class, ['name', 'category'])
        ->search($request->input('query'));

        return response()->json([
            'status' => (bool) $results,
            'data' => $results,
            'message' => $results ? 'Results retrieved' : 'Failed to retrieve results'
        ]);
    }

    //show products of one vendor (authorized)
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
            'name' => 'required|string',
            'description' => 'required|string',
            'units' => 'required',
            'price' => 'required',
            // 'certificate' => 'required|max:20480|mimes:pdf',
            // 'certificate' => 'required'
        ]);

        $product = new Product;      

        // qrcode (png) file name
        $file_name = time().'.png';

        
        $product->name = $request->name;
        $product->description = $request->description;
        $product->units = $request->units;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->image = $request->image;
        $product->user_id = Auth::id();
        $product->qrcodeUrl = url('/').'/storage/app/public/qrcode_img/'.$file_name;
        $product->certificate = $request->certificate;

        $product->save();

        // generating a URL for qrcode and storing the png locally
        $p_id = $product->id;
        $image = \QrCode::format('png')
            ->size(300)->errorCorrection('H')
            ->generate("http://localhost:8000/products/$p_id");
        
        $output_file = '/public/qrcode_img/'.$file_name;
        Storage::disk('local')->put($output_file, $image);

        // json response
        return response()->json([
            'status' => (bool) $product,
            'data'  => $product,
            'message' => $product ? 'Product successfully stored' : 'Error storing product',
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

    //Uploading an image
    public function uploadImage(Request $request) {
        // if($request->hasFile('image')) {
        //     $name = time()."_".$request->file('image')->getClientOriginalName();
        //     $request->file('image')->move(public_path('images'), $name);
        // }
        // return response()->json([
        //     asset("images/$name"),
        //     201,
        //     // 'message' => asset("images/$name") ? 'Image saved' : 'Image failed to save'
        // ]);
        if($request->hasFile('image')) {
            $name = time()."_".$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $name);
        }
        return response()->json(asset("images/$name"),201);
    }

    //uploading a pdf(certicate)
    public function uploadPdf(Request $request) {

        // if($request->hasFile('certificate')) {
        //     $name = time()."_".$request->file('certificate')->getClientOriginalName();
        //     $request->file('certificate')->move(public_path('certs'), $name);
    
        //     return response()->json([
        //         asset("certs/$name"),
        //         201,
        //         // 'message' => asset("certs/$name") ? 'Cert saved' : 'Cert failed to save'
        //     ]);
        // } 
        if($request->hasFile('certificate')){ 
            $name = time()."_".$request->file('certificate')->getClientOriginalName();
            $request->file('certificate')->move(public_path('certs'), $name);
        }
        return response()->json(asset("certs/$name"),201);
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
