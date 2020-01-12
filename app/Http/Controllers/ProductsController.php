<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //
    
    public function index()
    {
        $products = DB::table('products')->get(); // it will get the entire table

        return view('products', compact('products'));
    }
    
 
    public function cart()
    {
        return view('cart');
    }
    public function addToCart($id)
    {
       $product = DB::table('products')->find($id);

        if(!$product) {
 
            abort(404);
 
        }
 
        $cart = session()->get('cart');
 
        // if cart is empty then this the first product
        if(!$cart) {
 
            $cart = [
                    $id => [
                        "name" => $product->name,
                        "quantity" => 1,
                        "discount"=>0,
                        "price" => $product->price,
                        "photo" => $product->photo
                    ]
            ];
 
            session()->put('cart', $cart);
 
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }
 
        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {
 
            $cart[$id]['quantity']++;
 
            session()->put('cart', $cart);
 
            return redirect()->back()->with('success', 'Product added to cart successfully!');
 
        }
 
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "discount"=>0,
            "price" => $product->price,
            "photo" => $product->photo
        ];
 
        session()->put('cart', $cart);
 
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

public function update(Request $request)
    {
        if($request->id and $request->quantity)
        {
            $cart = session()->get('cart');
 
            $cart[$request->id]["quantity"] = $request->quantity;
 
            session()->put('cart', $cart);
 
            session()->flash('success', 'Cart updated successfully');
        }
    }
 
    public function remove(Request $request)
    {
        if($request->id) {
 
            $cart = session()->get('cart');
 
            if(isset($cart[$request->id])) {
 
                unset($cart[$request->id]);
 
                session()->put('cart', $cart);
            }
 
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function applyPromo(Request $request)
    {
		
        if ($request->promocode=="NewYear2020"|| $request->promocode=="FirstBag" )
		  {

            $cart = session()->get('cart');
            if($request->promocode=="NewYear2020")
            {
                $discount=20;
            }
            else if($request->promocode=="FirstBag")
            {
                 $discount=10;
            }

 
            $cart[$request->id]["discount"] =$discount;
 
            session()->put('cart', $cart);
 
            echo 'Promo Applied successfully';

		  }
		else
		  {
		     echo 'Invalid Promo Code';
		  }	

      
           
    }

}
