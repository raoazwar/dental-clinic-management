<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}
            </h2>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="flex-shrink-0">
                            @if($user->profile_photo_url)
                                <img class="h-20 w-20 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 text-2xl font-medium">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">User Information</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'administrator' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Verification</dt>
                                    <dd class="text-sm">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unverified</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Account Details</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->updated_at->format('F d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">User ID</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->id }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        @if(!$user->hasVerifiedEmail())
                            <form action="{{ route('users.resend-verification', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Resend Verification Email
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('users.edit', $user) }}" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit User
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 