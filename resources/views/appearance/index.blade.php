<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appearance Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                <div class="p-8 text-gray-900 border-b border-gray-200/50">
                    <form method="POST" action="{{ route('appearance.update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                            <!-- Basic & Theme Settings -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Branding</h3>
                                
                                <div class="mb-4">
                                    <x-input-label for="slug" value="Custom URL (Slug)" />
                                    <div class="flex mt-1">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            {{ url('/') }}/
                                        </span>
                                        <input type="text" name="slug" id="slug" value="{{ old('slug', $landingPage->slug) }}" class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="logo" value="Logo" />
                                    @if($appearance->logo_path)
                                        <div class="mt-2 mb-2">
                                            <img src="{{ asset('storage/' . $appearance->logo_path) }}" alt="Current Logo" class="h-16 rounded shadow">
                                        </div>
                                    @endif
                                    <input type="file" id="logo" name="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="theme_color" value="Theme Color" />
                                    <div class="flex items-center gap-4 mt-1">
                                        <input type="color" id="theme_color" name="theme_color" value="{{ old('theme_color', $appearance->theme_color) }}" class="h-10 w-20 cursor-pointer border-gray-300 rounded shadow-sm">
                                        <span class="text-sm text-gray-500">Select your primary color</span>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('theme_color')" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="about_text" value="About Text (Bio)" />
                                    <textarea id="about_text" name="about_text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('about_text', $appearance->about_text) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('about_text')" />
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="space-y-6 md:border-l md:border-gray-100 md:pl-8">
                                <h3 class="text-lg font-medium text-gray-900">Social Media Links</h3>
                                
                                @php
                                    $socials = $appearance->social_links ?? [];
                                @endphp

                                <div class="mb-4 flex items-center">
                                    <div class="w-10 flex-shrink-0 text-gray-400">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <x-text-input name="social_links[instagram]" type="text" class="block w-full" placeholder="Instagram URL" value="{{ $socials['instagram'] ?? '' }}" />
                                    </div>
                                </div>

                                <div class="mb-4 flex items-center">
                                    <div class="w-10 flex-shrink-0 text-gray-400">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93v7.2c-.01 2.63-1.07 5.25-3 7.15-1.92 1.91-4.52 2.97-7.23 2.94-2.7-.01-5.32-1.09-7.24-3.02C-1.87 20.35-.49 16.92 1.34 15.01c1.86-1.92 4.38-3 7.02-3h.2v4.06c-1.57.04-3.13.71-4.24 1.83-1.1 1.11-1.72 2.66-1.72 4.24.01 1.58.64 3.12 1.75 4.23 1.12 1.12 2.69 1.74 4.28 1.74 1.59 0 3.16-.63 4.28-1.75 1.11-1.12 1.73-2.69 1.73-4.28V.03h3.88z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <x-text-input name="social_links[tiktok]" type="text" class="block w-full" placeholder="TikTok URL" value="{{ $socials['tiktok'] ?? '' }}" />
                                    </div>
                                </div>

                                <div class="mb-4 flex items-center">
                                    <div class="w-10 flex-shrink-0 text-gray-400">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <x-text-input name="social_links[twitter]" type="text" class="block w-full" placeholder="Twitter URL" value="{{ $socials['twitter'] ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex items-center bg-gray-50/50 backdrop-blur-sm -mx-8 -mb-8 p-6 rounded-b-3xl border-t border-white/50">
                            <x-primary-button>{{ __('Save Appearance') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
