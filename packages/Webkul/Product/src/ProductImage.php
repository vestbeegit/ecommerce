<?php

namespace Webkul\Product;

use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Contracts\Wishlist;
use Webkul\Product\Repositories\ProductRepository;

class ProductImage
{
    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * Retrieve collection of gallery images.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getGalleryImages($product)
    {
        if (! $product) {
            return [];
        }

        $images = [];

        foreach ($product->images as $image) {
            if (! Storage::has($image->path)) {
                continue;
            }

            $images[] = $this->getCachedImageUrls($image->path);
        }

        if (
            ! $product->parent_id
            && ! count($images)
            && ! count($product->videos ?? [])
        ) {
            $images[] = $this->getFallbackImageUrls();
        }

        /*
         * Product parent checked already above. If the case reached here that means the
         * parent is available. So recursing the method for getting the parent image if
         * images of the child are not found.
         */
        if (empty($images)) {
            $images = $this->getGalleryImages($product->parent);
        }

        return $images;
    }

    /**
     * Get product variant image if available otherwise product base image.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist  $item
     * @return array
     */
    public function getProductImage($item)
    {
        if ($item instanceof Wishlist) {
            if (isset($item->additional['selected_configurable_option'])) {
                $product = $this->productRepository->find($item->additional['selected_configurable_option']);
            } else {
                $product = $item->product;
            }
        } else {
            $product = $item->product;
        }

        return $this->getProductBaseImage($product);
    }

    /**
     * This method will first check whether the gallery images are already
     * present or not. If not then it will load from the product.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array
     * @return array
     */
    public function getProductBaseImage($product, ?array $galleryImages = null)
    {
        if (! $product) {
            return;
        }

        return $galleryImages
            ? $galleryImages[0]
            : $this->otherwiseLoadFromProduct($product);
    }

    /**
     * Load product's base image.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    protected function otherwiseLoadFromProduct($product)
    {
        $images = $product?->images;

        if ($images && $images->count() && Storage::has($images[0]->path)) {
            return $this->getCachedImageUrls($images[0]->path);
        }

        return $this->getFallbackImageUrls();
    }

    /**
     * Get cached urls configured for intervention package.
     *
     * @param  string  $path
     */
    private function getCachedImageUrls($path): array
    {
        if (! Storage::has($path)) {
            return $this->getFallbackImageUrls();
        }

        $url = Storage::url($path);

        return [
            'small_image_url'    => $url,
            'medium_image_url'   => $url,
            'large_image_url'    => $url,
            'original_image_url' => $url,
        ];
    }

    /**
     * Get fallback urls.
     */
    private function getFallbackImageUrls(): array
    {
        $placeholders = config('placeholder_images.products', []);
        $placeholder = ! empty($placeholders)
            ? $placeholders[array_rand($placeholders)]
            : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&q=80';

        $smallImageUrl = core()->getConfigData('catalog.products.cache_small_image.url')
                        ? Storage::url(core()->getConfigData('catalog.products.cache_small_image.url'))
                        : $placeholder;

        $mediumImageUrl = core()->getConfigData('catalog.products.cache_medium_image.url')
                        ? Storage::url(core()->getConfigData('catalog.products.cache_medium_image.url'))
                        : $placeholder;

        $largeImageUrl = core()->getConfigData('catalog.products.cache_large_image.url')
                        ? Storage::url(core()->getConfigData('catalog.products.cache_large_image.url'))
                        : $placeholder;

        return [
            'small_image_url'    => $smallImageUrl,
            'medium_image_url'   => $mediumImageUrl,
            'large_image_url'    => $largeImageUrl,
            'original_image_url' => $placeholder,
        ];
    }
}
