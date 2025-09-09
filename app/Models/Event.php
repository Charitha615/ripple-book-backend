<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location_details',
        'organizer',
        'images',
        'category',
        'tags',
        'url',
        'other_details',
        'admin_id',
        'is_visible'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'images' => 'array',
    ];

    /**
     * Relationship with the admin/user who created the event
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Process base64 images and save them to storage
     */
    public function processAndStoreImages($base64Images)
    {
        if (empty($base64Images)) {
            return null;
        }

        $storedImages = [];

        // If it's a single image, convert to array
        if (!is_array($base64Images)) {
            $base64Images = [$base64Images];
        }

        foreach ($base64Images as $base64Image) {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $image = substr($base64Image, strpos($base64Image, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                    continue; // Skip invalid image types
                }

                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                if ($imageData === false) {
                    continue; // Skip if base64 decoding fails
                }

                $fileName = 'events/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($fileName, $imageData);

                $storedImages[] = $fileName;
            }
        }

        return $storedImages;
    }

    /**
     * Delete associated images when event is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($event) {
            if ($event->images) {
                foreach ($event->images as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }
        });
    }
}
