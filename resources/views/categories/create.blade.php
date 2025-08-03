<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Category') }}
            </h2>
            <x-modern-button variant="outline" icon="back" href="{{ route('categories.index') }}">
                Back to Categories
            </x-modern-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Category Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter category name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter category description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div>
                                <label for="is_active" class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active Category</span>
                                </label>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex justify-end space-x-3">
                                <x-modern-button variant="outline" icon="back" href="{{ route('categories.index') }}">
                                    Cancel
                                </x-modern-button>
                                <x-modern-button variant="success" icon="save" type="submit">
                                    Create Category
                                </x-modern-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 