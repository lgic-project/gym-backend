<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        if ($this->hasMedia()) {
            return $this->getFirstMedia() ? $this->getFirstMediaUrl() : asset('images/default-product.png');
        }
        return asset('images/default-product.png');
    }

 // Use the HasFactory trait to enable factory support for this model
  // Use the HasFactory trait to enable factory support for this model
   // Use the HasFactory trait to enable factory support for this model
    // Use the HasFactory trait to enable factory support for this model
     // Use the HasFactory trait to enable factory support for this model
      // Use the HasFactory trait to enable factory support for this model
       // Use the HasFactory trait to enable factory support for this model
        // Use the HasFactory trait to enable factory support for this model
    public function scopeSearch($query, $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        return $query;
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products');
    }
}
