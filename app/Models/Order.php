<?php

namespace App\Models;

// Import necessary classes from Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Define the Order model class
class Order extends Model
{
    // Use the HasFactory trait to enable factory support for this model
    use HasFactory;

    // Protect the 'id' attribute from mass assignment
    protected $guarded = ['id'];

    // Define a query scope to filter ordesssssssssssssssssssssrs based on the authenticated API user
    public function scopeSearch($query)
    {
        // Check if there is an authenticated API user
        if (auth('api')->check()) {
            // Filter the orders to only those belonging to the authenticated user
            $query->where('user_id',  auth('api')->id());
        }
        // Return the modified query
        return $query;
    }

    // Define a relationship: an order belongs to a user
    public function user()
    {
        // Specify the relationship to the User model via the 'user_id' foreign key
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define a many-to-many relationship: an order has many products
    public function products()
    {
        // Specify the relationship to the Product model via the 'order_products' pivot table
        return $this->belongsToMany(Product::class, 'order_products');
    }
}
