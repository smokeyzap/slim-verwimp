<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'vw_orders_temp';
    protected $fillable = [ 
        'id',
        'customer_number',
        'item_number',
        'quantity',
        'total_amount',
        'sent',
    ];

    public function addOrder ($customer_number, $item_number, $quantity) 
    {
        //search item number if exist
        $order = Order::where('customer_number', $customer_number)
                        ->where('item_number', $item_number)
                        ->where('sent', 0)
                        ->first(); 


        //if not exist add the item
        if (count($order) == 0) {
            if ($quantity == 0) {
                die('Updated.');
            }
            Order::create([
                'customer_number' => $customer_number,
                'item_number' => $item_number,
                'quantity' => $quantity,
            ]);
            die("Item #$item_number has been added to cart.");
        } else {
            if ($quantity == 0) {
                $order->delete();
            }
            //if exist overwrite the quantity
            $order->update([
                'quantity' => $quantity
            ]);
            die('Quantity has been updated successfully.');
        } 
    }
}

