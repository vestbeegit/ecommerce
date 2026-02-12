<v-shimmer-image {{ $attributes }}>
    <div class="{{ ($attributes->get('class') ?? '') . ' shimmer' }}" style="aspect-ratio: 1;"></div>
</v-shimmer-image>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shimmer-image-template"
    >
        <span class="block h-full w-full">
            <div
                :id="'image-shimmer-' + $.uid"
                class="shimmer block h-full w-full"
                v-if="isLoading"
            >
            </div>

            <img
                v-bind="$attrs"
                :data-src="effectiveSrc"
                :id="'image-' + $.uid"
                @load="onLoad"
                @error="onError"
                v-show="! isLoading"
                v-if="lazy"
            >

            <img
                v-bind="$attrs"
                :src="effectiveSrc"
                :id="'image-' + $.uid"
                @load="onLoad"
                @error="onError"
                v-show="! isLoading"
                v-else
            >
        </span>
    </script>

    <script type="module">
        app.component('v-shimmer-image', {
            template: '#v-shimmer-image-template',

            inheritAttrs: false,

            props: {
                lazy: {
                    type: Boolean,
                    default: true,
                },

                src: {
                    type: String,
                    default: '',
                },

                fallback: {
                    type: String,
                    default: '',
                },
            },

            data() {
                return {
                    isLoading: true,
                    currentSrc: this.src,
                    errorFallbackUsed: false,
                };
            },

            computed: {
                effectiveSrc() {
                    return this.errorFallbackUsed ? this.fallback : this.currentSrc;
                },
            },

            watch: {
                src: {
                    immediate: true,
                    handler(v) {
                        this.currentSrc = v;
                        this.errorFallbackUsed = false;
                    },
                },
            },

            mounted() {
                let self = this;

                if (! this.lazy) {
                    return;
                }

                let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyImage = document.getElementById('image-' + self.$.uid);
                            if (lazyImage) {
                                lazyImage.src = lazyImage.dataset.src || self.effectiveSrc;
                            }
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });

                lazyImageObserver.observe(document.getElementById('image-shimmer-' + this.$.uid));
            },

            methods: {
                onLoad() {
                    this.isLoading = false;
                },
                onError() {
                    if (this.fallback && ! this.errorFallbackUsed) {
                        this.errorFallbackUsed = true;
                        this.currentSrc = this.fallback;
                        this.isLoading = false;
                        this.$nextTick(() => {
                            let el = document.getElementById('image-' + this.$.uid);
                            if (el) {
                                el.src = this.fallback;
                                el.srcset = this.fallback + ' 1x';
                            }
                        });
                    } else {
                        this.isLoading = false;
                    }
                },
            },
        });
    </script>
@endPushOnce
