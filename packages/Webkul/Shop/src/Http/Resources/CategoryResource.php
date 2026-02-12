<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $logoExists = $this->logo_path && Storage::disk('public')->exists($this->logo_path);
        $bannerExists = $this->banner_path && Storage::disk('public')->exists($this->banner_path);

        $categoryPlaceholders = config('placeholder_images.categories', []);
        $defaultPlaceholder = $categoryPlaceholders['default'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&q=80';
        $slug = strtolower((string) ($this->slug ?? ''));
        $name = strtolower((string) ($this->name ?? ''));
        $directUrl = $categoryPlaceholders[$slug] ?? $categoryPlaceholders[$name] ?? $defaultPlaceholder;

        $logoUrls = $logoExists
            ? [
                'small_image_url'    => Storage::disk('public')->url($this->logo_path),
                'medium_image_url'   => Storage::disk('public')->url($this->logo_path),
                'large_image_url'    => Storage::disk('public')->url($this->logo_path),
                'original_image_url' => Storage::disk('public')->url($this->logo_path),
            ]
            : [
                'small_image_url'    => $directUrl,
                'medium_image_url'   => $directUrl,
                'large_image_url'    => $directUrl,
                'original_image_url' => $directUrl,
            ];

        $bannerUrls = $bannerExists
            ? [
                'small_image_url'    => Storage::disk('public')->url($this->banner_path),
                'medium_image_url'   => Storage::disk('public')->url($this->banner_path),
                'large_image_url'    => Storage::disk('public')->url($this->banner_path),
                'original_image_url' => Storage::disk('public')->url($this->banner_path),
            ]
            : [
                'small_image_url'    => $directUrl,
                'medium_image_url'   => $directUrl,
                'large_image_url'    => $directUrl,
                'original_image_url' => $directUrl,
            ];

        return [
            'id'           => $this->id,
            'parent_id'    => $this->parent_id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'url'          => $this->url ?? url($this->slug),
            'status'       => $this->status,
            'position'     => $this->position,
            'display_mode' => $this->display_mode,
            'description'  => $this->description,
            'logo'         => $logoUrls,
            'banner'       => $bannerUrls,
            'meta'         => [
                'title'       => $this->meta_title,
                'keywords'    => $this->meta_keywords,
                'description' => $this->meta_description,
            ],
            'translations' => $this->translations,
            'additional'   => $this->additional,
        ];
    }
}
