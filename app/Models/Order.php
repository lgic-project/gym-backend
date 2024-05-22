<?php

namespace App\Models;

// Import necessary classes from Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // Use Auth facade
use App\Models\User; // Reference User model
use App\Models\Product; // Reference Product model

// Define the Order model class
class Order extends Model
{
    use HasFactory; // Enable factory support for this model

    protected $guarded = ['id']; // Prevent mass assignment on 'id' attribute

    /**
     * Scope a query to filter orders based on the authenticated API user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($q) // Filter orders based on the authenticated user
    {
        if (Auth::guard('api')->check()) { // Check for authenticated API user
            $q->where('user_id', Auth::guard('api')->id()); // Filter orders by the authenticated user's ID
        }
        return $q; // Return the modified query
    }

    /**
     * Get the user that owns the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() // Define the relationship to User model
    {
        return $this->belongsTo(User::class, 'user_id'); // Relationship to User model
    }

    /**
     * The products that belong to the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() // Define relationship to Product model via pivot table
    {
        return $this->belongsToMany(Product::class, 'order_products'); // Relationship to Product model
    }
}
