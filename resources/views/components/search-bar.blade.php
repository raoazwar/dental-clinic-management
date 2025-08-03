<div class="relative" x-data="searchBar()" @click.outside="showResults = false">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input 
            type="text" 
            x-model="query" 
            @input.debounce.300ms="search()"
            @focus="showResults = true"
            @keydown.escape="clearSearch()"
            placeholder="Search users, products, categories, sales..."
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        >
        <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <div x-show="showResults && results.length > 0" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-96 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto">
        <template x-for="result in results" :key="result.id + result.type">
            <a :href="result.url" 
               @click="showResults = false"
               class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                             :class="{
                                 'bg-blue-100 text-blue-600': result.type === 'user',
                                 'bg-green-100 text-green-600': result.type === 'product',
                                 'bg-purple-100 text-purple-600': result.type === 'category',
                                 'bg-orange-100 text-orange-600': result.type === 'sale'
                             }">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path x-show="result.type === 'user'" fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                <path x-show="result.type === 'product'" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                <path x-show="result.type === 'category'" d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                <path x-show="result.type === 'sale'" d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 truncate" x-text="result.title"></p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 capitalize" x-text="result.type"></span>
                        </div>
                        <p class="text-sm text-gray-500 truncate" x-text="result.subtitle"></p>
                        <div class="flex items-center space-x-4 mt-1">
                            <p class="text-xs text-gray-400" x-text="result.description"></p>
                            <template x-if="result.price">
                                <p class="text-xs font-medium text-green-600" x-text="result.price"></p>
                            </template>
                            <template x-if="result.total">
                                <p class="text-xs font-medium text-green-600" x-text="result.total"></p>
                            </template>
                            <template x-if="result.quantity !== undefined">
                                <p class="text-xs text-gray-500" x-text="`Qty: ${result.quantity}`"></p>
                            </template>
                        </div>
                    </div>
                </div>
            </a>
        </template>
    </div>
</div>

<script>
function searchBar() {
    return {
        query: '',
        results: [],
        loading: false,
        showResults: false,

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.showResults = false;
                return;
            }

            this.loading = true;
            this.showResults = true;

            try {
                const response = await fetch('/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ query: this.query })
                });

                const data = await response.json();

                if (data.success) {
                    this.results = [
                        ...data.results.users,
                        ...data.results.products,
                        ...data.results.categories,
                        ...data.results.sales
                    ];
                } else {
                    this.results = [];
                }
            } catch (error) {
                this.results = [];
            } finally {
                this.loading = false;
            }
        },

        clearSearch() {
            this.query = '';
            this.results = [];
            this.showResults = false;
        }
    }
}
</script> 