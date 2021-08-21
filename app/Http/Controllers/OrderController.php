<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\Product;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource. 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Order::with(['product'])->get(), 200);
    }

    public function allOrders() {
        // return response()->json(Order::all(), 200);
        $data = Order::latest()->paginate(8);
        return response()->json($data, 200);
    }
    // public function paginatedOrders() {
    //     $data = Order::latest()->paginate(8);
    //     return response()->json($data, 200);
    // }

    //Delivered method
    public function deliverOrder(Order $order) {
        $order->is_delivered = true;
        $status = $order->save();

        return response()->json([
            'status' => $status,
            'data' => $order,
            'message' => $status ? 'Order Delivered' : 'Error Delivering order'
        ]);
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

    // public function multiStore(Request $request) {

    // }

    public function store(Request $request)
    {
        $order = Order::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'address' => $request->address
        ]);
        $order = new Order;
        //retrieve all products(with their orders)
        $products = Product::where('user_id', Auth::id())->get();
        $orders = Order::with(['product'])->get();
        $order->quantity = $request->quantity;
        $quantity = $order->quantity;
        Product::where('id', $request->product_id)->decrement('units',$request->quantity);
        
        // foreach($orders as $ord) {
        //     
        // }

        

        return response()->json([
            'status' => (bool) $order,
            'data' => $order,
            'message' => $order ? 'Order created' : 'Error creating order'
        ]);
    }

    public function decrementQuantity() {
        // return response->json(
        //     Product::where('user_id', Auth::id())->decrement('units',$ord->quantity), 200
        // );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json($order, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $status = $order->update(
            $request->only(['quantity'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Order Updated!' : 'Error Updating order'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $status = $order->delete(); 

        return repsonse()->json([
            'status' => $status,
            'message' => $status ? 'Order deleted!' : 'Error deleting order'
        ]);
    }

    public function countDelivered() {
        return response()->json(Order::where('is_delivered', 1)->get()->count(), 200);
    }

    public function pendingOrders() {
        return response()->json(Order::where('is_delivered', 0)->get()->count(), 200);
    }
}
