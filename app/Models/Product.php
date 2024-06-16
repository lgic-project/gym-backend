<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // Guarded attributes to prevent mass assignment vulnerabilities
    protected $guarded = ['id'];

    // Append the image attribute to the model's array and JSON form
    protected $appends = ['image'];

    /**
     * Get the URL of the product's image.
     * If the product has no image, return the default image URL.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        if ($this->hasMedia()) {
            return $this->getFirstMediaUrl() ?: asset('images/default-product.png');
        }
        return asset('images/default-product.png');
    }

    /**
     * Scope a query to only include products that match the search query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        return $query;
    }

    /**
     * Get the orders that include the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products');
    }
}
