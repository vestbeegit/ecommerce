{{-- Shop by type / Collections section with dummy images from the internet --}}
@php
    $collections = [
        ['name' => 'Men', 'image' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?w=600&q=80', 'slug' => 'men'],
        ['name' => 'Women', 'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80', 'slug' => 'women'],
        ['name' => 'Kids', 'image' => 'https://images.unsplash.com/photo-1503919545889-aef636e10ad4?w=600&q=80', 'slug' => 'kids'],
        ['name' => 'Electronics', 'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=600&q=80', 'slug' => 'electronics'],
        ['name' => 'Home & Living', 'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&q=80', 'slug' => 'home-living'],
        ['name' => 'Sports', 'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600&q=80', 'slug' => 'sports'],
        ['name' => 'Accessories', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80', 'slug' => 'accessories'],
        ['name' => 'Deals', 'image' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?w=600&q=80', 'slug' => 'deals'],
    ];
@endphp
<section class="container mt-14 max-lg:px-8 max-md:mt-8 max-sm:mt-6" aria-label="@lang('shop::app.home.index.collections')">
    <h2 class="font-dmserif text-3xl max-md:text-2xl max-sm:text-xl mb-6 text-center">
        {{ __('shop::app.home.index.collections') }}
    </h2>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:gap-6">
        @foreach ($collections as $item)
            <a
                href="{{ route('shop.search.index') }}?query={{ urlencode($item['name']) }}"
                class="group block overflow-hidden rounded-2xl bg-zinc-100 transition hover:shadow-lg max-md:rounded-xl"
                aria-label="{{ $item['name'] }}"
            >
                <div class="aspect-square overflow-hidden rounded-2xl max-md:rounded-xl">
                    <img
                        src="{{ $item['image'] }}"
                        alt="{{ $item['name'] }}"
                        width="300"
                        height="300"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                        loading="lazy"
                    />
                </div>
                <p class="mt-3 text-center text-base font-medium text-gray-900 max-md:mt-2 max-md:text-sm">
                    {{ $item['name'] }}
                </p>
            </a>
        @endforeach
    </div>
</section>
