@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    <script>
        localStorage.setItem('categories', JSON.stringify(@json($categories)));
    </script>
@endpush

@php
    $hasImageCarousel = $customizations->contains(fn ($c) => $c->type === 'image_carousel');
    $defaultSliderImages = [
        ['image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1920&q=80', 'title' => 'Fashion Sale', 'link' => ''],
        ['image' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?w=1920&q=80', 'title' => 'Mega Deals', 'link' => ''],
        ['image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1920&q=80', 'title' => 'Electronics', 'link' => ''],
        ['image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1920&q=80', 'title' => 'Home & Living', 'link' => ''],
        ['image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80', 'title' => 'New Arrivals', 'link' => ''],
    ];
@endphp

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Default slider with dummy images when theme has no image carousel -->
    @if (!$hasImageCarousel)
        <x-shop::carousel
            :options="['images' => $defaultSliderImages]"
            aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
        />
    @endif

    <!-- Shop by type / Collections with dummy images from the internet -->
    @include('shop::home.partials.collections-dummy')

    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        <!-- Static content -->
        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <!-- Image Carousel -->
                <x-shop::carousel
                    :options="$data"
                    aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
                />

                @break
            @case ($customization::STATIC_CONTENT)
                @php
                    $html = $data['html'] ?? '';
                    $isCollectionBlock = str_contains($html, 'top-collection') || str_contains($html, 'bold-collection') || str_contains($html, 'game-container');
                @endphp
                @if (!$isCollectionBlock)
                    <!-- push style -->
                    @if (! empty($data['css']))
                        @push ('styles')
                            <style>
                                {{ $data['css'] }}
                            </style>
                        @endpush
                    @endif

                    <!-- render html -->
                    @if (! empty($data['html']))
                        {!! $data['html'] !!}
                    @endif
                @endif

                @break
            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />

                @break
            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />

                @break
        @endswitch
    @endforeach

    {{-- Always show categories and products for a proper storefront (even if theme customizations are minimal) --}}
    @php
        $rootCategoryId = $channel->root_category_id ?? 1;
        $defaultCategoryFilters = ['parent_id' => $rootCategoryId, 'sort' => 'asc', 'limit' => 10];
        $defaultProductFilters = ['sort' => 'created_at-desc', 'limit' => 12];
    @endphp

    <!-- Shop by Category (default section) -->
    <x-shop::categories.carousel
        :title="trans('shop::app.home.index.shop-by-category')"
        :src="route('shop.api.categories.index', $defaultCategoryFilters)"
        :navigation-link="route('shop.home.index')"
        aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
    />

    <!-- New Arrivals / All Products (default section) -->
    <x-shop::products.carousel
        :title="trans('shop::app.home.index.new-arrivals')"
        :src="route('shop.api.products.index', $defaultProductFilters)"
        :navigation-link="route('shop.search.index')"
        aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
    />
</x-shop::layouts>
