<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My LP Builder') }}
        </h2>
    </x-slot>

    <div class="py-6 relative" x-data="lpBuilder()">
        <!-- Quill Editor Integration -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-800 bg-green-100/50 backdrop-blur border border-green-200 p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <ul class="mb-4 text-sm text-red-600 bg-red-100/50 backdrop-blur border border-red-200 p-3 rounded-lg" style="{{ $errors->any() ? '' : 'display:none;' }}">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>

            <!-- flex-row with items-stretch (default) allows sticky to work inside right column -->
            <div class="flex flex-col lg:flex-row gap-8 w-full">
                <!-- Left Pane: Editor Options -->
                <div class="w-full lg:w-[58%] lg:max-w-[58%] min-w-0 space-y-8">
                    
                    <!-- General Settings Form -->
                    <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                        <div class="p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                General Info
                            </h3>
                            <form method="POST" action="{{ route('my-lp.update') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-5">
                                    <x-input-label for="title" value="Page Title" class="font-semibold text-gray-700" />
                                    <input id="title" name="title" type="text" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" x-model="title" required />
                                </div>
                                <div class="mb-5">
                                    <x-input-label for="description" value="Description" class="font-semibold text-gray-700" />
                                    <textarea id="description" name="description" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" rows="3" x-model="description"></textarea>
                                </div>
                                <div class="mb-6">
                                    <x-input-label for="image" value="Main Background Image" class="font-semibold text-gray-700" />
                                    <div class="mt-2 border-2 border-dashed border-indigo-200 rounded-2xl p-6 text-center bg-indigo-50/30 hover:bg-indigo-50/50 transition">
                                        <input type="file" id="image" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" @change="
                                            if($event.target.files.length > 0) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => imagePreview = e.target.result;
                                                reader.readAsDataURL($event.target.files[0]);
                                            }
                                        ">
                                    </div>
                                </div>
                                <div>
                                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                                        Save General Info
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Products List & Manager -->
                    <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                        <div class="p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                Products
                            </h3>
                            
                            <!-- Existing Products -->
                            <div class="space-y-4 mb-8">
                                @foreach($products as $prod)
                                    <div class="relative mb-4">
                                        <div class="bg-white/80 rounded-2xl p-5 border border-purple-100 shadow-sm flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                @if($prod->image_path)
                                                    <img src="{{ asset('storage/'.$prod->image_path) }}" class="w-16 h-16 rounded-xl object-cover shadow">
                                                @else
                                                    <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center text-purple-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-bold text-gray-800 text-lg">{{ $prod->name }}</h4>
                                                    <div class="text-sm text-gray-500">
                                                        Rp {{ number_format($prod->sale_price ?: $prod->price, 0, ',', '.') }}
                                                        @if($prod->sale_price) <span class="line-through text-xs ml-1">Rp {{ number_format($prod->price, 0, ',', '.') }}</span> @endif
                                                    </div>
                                                    <div class="text-xs text-purple-600 mt-1 font-semibold">{{ $prod->addOns->count() }} Add-ons</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="editProduct({{ $prod->id }})" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('my-lp.product.destroy', $prod->id) }}" onsubmit="return confirm('Delete this product?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Nested Add-ons for this product -->
                                        <div class="ml-10 mt-3 space-y-3 border-l-2 border-pink-200 pl-4">
                                            @foreach($prod->addOns as $addon)
                                                <div class="bg-white/60 backdrop-blur-md rounded-xl p-3 border border-pink-100 flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        @if($addon->image_path)
                                                            <img src="{{ asset('storage/'.$addon->image_path) }}" class="w-8 h-8 rounded shadow object-cover">
                                                        @endif
                                                        <div>
                                                            <div class="font-bold text-gray-800 text-sm">{{ $addon->name }}</div>
                                                            <div class="text-xs text-gray-500">+Rp {{ number_format($addon->price, 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                    <form method="POST" action="{{ route('my-lp.addon.destroy', $addon->id) }}" onsubmit="return confirm('Delete this add-on?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                            
                                            <!-- Add Add-on Button -->
                                            <button x-show="!showAddonForm || targetProductId != {{ $prod->id }}" @click="openAddonForm({{ $prod->id }})" class="text-sm py-2 px-4 border border-dashed border-pink-300 bg-pink-50/50 hover:bg-pink-100 text-pink-600 font-bold rounded-lg transition flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Add new add-on
                                            </button>

                                            <!-- Add Add-on Form -->
                                            <div class="bg-white rounded-2xl p-5 border border-pink-200 shadow-sm mt-2" x-show="showAddonForm && targetProductId == {{ $prod->id }}" style="display: none;">
                                                <form method="POST" action="{{ route('my-lp.addon.store') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                                        <div>
                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Add-on Name</label>
                                                            <input type="text" name="name" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Price</label>
                                                            <input type="number" name="price" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                                        <div>
                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Delivery Type</label>
                                                            <select name="type" x-model="addonType" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                                                <option value="file">File Upload</option>
                                                                <option value="url">External URL</option>
                                                            </select>
                                                        </div>
                                                        <div x-show="addonType == 'url'">
                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">URL Link</label>
                                                            <input type="url" name="download_url" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500" placeholder="https://...">
                                                        </div>
                                                        <div x-show="addonType == 'file'">
                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">File Upload (Optional)</label>
                                                            <input type="file" name="file_upload" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-pink-100 file:text-pink-700">
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Image (Optional)</label>
                                                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-pink-100 file:text-pink-700">
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white text-sm font-bold py-1.5 px-4 rounded-lg shadow-sm transition">Save</button>
                                                        <button type="button" @click="showAddonForm = false" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-bold py-1.5 px-4 rounded-lg transition">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button x-show="!showProductForm" @click="openNewProductForm()" class="w-full py-4 border-2 border-dashed border-purple-300 bg-purple-50/50 hover:bg-purple-50 text-purple-600 font-bold rounded-2xl transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add New Product
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Pane: Mobile Preview (Sticky) -->
                <div class="w-full lg:w-[42%] lg:max-w-[360px] flex-shrink-0 relative">
                    <div class="sticky top-24 pt-4 flex flex-col items-center w-full h-[calc(100vh-120px)]">
                        <div class="bg-black text-white text-xs font-mono mb-2 px-3 py-1 rounded-full opacity-60 flex-shrink-0">Live Preview</div>
                        
                        <!-- Device Frame -->
                        <div class="w-full h-full bg-white border-[12px] border-gray-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative flex flex-col flex-1">
                            
                            <!-- Notch -->
                            <div class="absolute top-0 inset-x-0 h-6 bg-gray-900 rounded-b-2xl w-40 mx-auto z-20"></div>

                            <!-- Mock Content inside device -->
                            <div class="flex-1 overflow-y-auto w-full relative pb-20" style="background-color:#FAF8F1;">
                                
                                <!-- Header -->
                                <div class="relative rounded-b-[2rem] overflow-hidden shadow-md" style="background:#121212; padding: 2.5rem 1rem 1.5rem; text-align:center;">
                                    <template x-if="imagePreview">
                                        <div class="absolute inset-0 opacity-20 bg-cover bg-center" :style="'background-image: url(' + imagePreview + ')'"></div>
                                    </template>

                                    <div class="relative z-10">
                                        <div class="relative inline-block mb-3">
                                            <div class="w-16 h-16 rounded-full mx-auto border-4 flex items-center justify-center text-white text-2xl font-bold shadow-md" style="border-color:#FAEAB1;" :style="'background:' + themeColor">
                                                <span x-text="title.charAt(0) || 'L'" style="display:block;"></span>
                                            </div>
                                            <div class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2" style="background:#22c55e; border-color:#121212;"></div>
                                        </div>

                                        <div class="font-bold text-white text-sm tracking-tight leading-tight" x-text="title || 'My Landing Page'"></div>
                                        <div class="text-xs mt-1 px-2 leading-relaxed" style="color:#FAEAB1;" x-text="description || 'Deskripsi halaman Anda...'"></div>
                                    </div>
                                </div>

                                <!-- Product Section -->
                                <div class="px-3 pt-5 space-y-2">
                                    <h2 class="text-center font-black text-[9px] uppercase tracking-widest mb-3" style="color:#121212; text-decoration:underline; text-decoration-color:#FAEAB1; text-decoration-thickness:3px; text-underline-offset:4px; font-style:italic;">Katalog Produk</h2>

                                    <template x-for="prod in products" :key="prod.id">
                                        <div class="group relative bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex items-center p-2.5 cursor-pointer hover:border-primary" style="transition: all 0.2s;">
                                            <div class="w-14 h-14 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden shadow-inner" style="background-color:#FAF8F1; color:#34656D;">
                                                <template x-if="prod.image">
                                                    <img :src="prod.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!prod.image">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </template>
                                            </div>

                                            <div class="ml-2.5 flex-grow">
                                                <div class="font-bold text-[11px] leading-tight" style="color:#121212;" x-text="prod.name"></div>
                                                <div class="flex items-center mt-1 gap-1">
                                                    <template x-if="prod.sale_price > 0">
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-white text-[9px] font-bold px-1.5 py-0.5 rounded italic" style="background:#ef4444;">IDR <span x-text="formatNumber(prod.sale_price)"></span></span>
                                                            <span class="text-slate-400 text-[9px] line-through italic">IDR <span x-text="formatNumber(prod.price)"></span></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!prod.sale_price">
                                                        <span class="text-[9px] font-bold" style="color:#6b7280;">IDR <span x-text="formatNumber(prod.price)"></span></span>
                                                    </template>
                                                </div>
                                            </div>

                                            <div class="ml-1 border border-slate-200 text-slate-400 p-2 rounded-full" style="font-size:10px;">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="px-3 mt-6">
                                    <div class="font-black text-[11px] italic text-center" style="color:#121212;">Pesan Sekarang & Buktikan!</div>
                                    <div class="mt-3 text-white font-black text-center text-[10px] py-3 rounded-xl shadow-md" :style="'background:' + themeColor">
                                        🚀 LIHAT KATALOG
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <!-- Product Form Modal -->
    <div x-show="showProductForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.outside="showProductForm = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-bold text-xl text-gray-800" x-text="editProductId ? 'Edit Product' : 'Add New Product'"></h4>
                <button type="button" @click="showProductForm = false" class="text-gray-400 hover:text-gray-600 transition bg-white rounded-full p-2 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                <form method="POST" action="{{ route('my-lp.product.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" x-model="editProductId">
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name" x-model="pName" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" required>
                    </div>

                    <div class="mb-5" wire:ignore>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                        <input type="hidden" name="description" x-model="pDescription" id="pDescriptionInput">
                        <div id="editor-container" style="height: 150px;" class="rounded-xl border-gray-300 shadow-sm"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Regular Price</label>
                            <input type="number" name="price" x-model="pPrice" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Sale Price</label>
                            <input type="number" name="sale_price" x-model="pSalePrice" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Delivery Type</label>
                            <select name="type" x-model="pType" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="file">File Upload (ZIP/PDF)</option>
                                <option value="url">External URL</option>
                            </select>
                        </div>
                        <div x-show="pType == 'url'">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">URL Link</label>
                            <input type="url" name="download_url" x-model="pDownloadUrl" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="https://...">
                        </div>
                        <div x-show="pType == 'file'">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Product File</label>
                            <input type="file" name="file_upload" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Product Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700">
                    </div>

                    <div class="mb-5 bg-purple-50/50 p-4 rounded-xl border border-purple-100">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" name="is_unlimited_qty" value="1" x-model="pUnlimited" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 mr-2 w-5 h-5">
                            <label class="text-sm font-semibold text-gray-700">Unlimited Quantity</label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="!pUnlimited">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stock Qty</label>
                                <input type="number" name="qty" x-model="pQty" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Limit per checkout</label>
                                <input type="number" name="limit_per_checkout" x-model="pLimit" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="showProductForm = false" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-xl transition">Cancel</button>
                        <button type="submit" class="w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition ml-auto">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        function lpBuilder() {
            return {
                title: @json($landingPage->title),
                description: @json($landingPage->description),
                imagePreview: @json($landingPage->image_path ? asset('storage/' . $landingPage->image_path) : ''),
                themeColor: @json($landingPage->appearance->theme_color ?? '#34656D'),
                socialLinks: @json($landingPage->appearance->social_links ?? []),
                
                products: {!! collect($products)->map(function($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'description' => $p->description,
                        'price' => $p->price,
                        'sale_price' => $p->sale_price,
                        'type' => $p->type,
                        'is_unlimited_qty' => $p->is_unlimited_qty,
                        'qty' => $p->qty,
                        'limit_per_checkout' => $p->limit_per_checkout,
                        'download_url' => $p->download_url,
                        'image' => $p->image_path ? asset('storage/'.$p->image_path) : null,
                        'addons' => $p->addOns->map(function($a) { 
                            return ['name' => $a->name, 'price' => $a->price]; 
                        })->values()->all()
                    ];
                })->toJson() !!},

                showProductForm: false,
                editProductId: null,
                pName: '', pPrice: '', pSalePrice: '', pType: 'file', pUnlimited: true, pDescription: '',
                pDownloadUrl: '', pQty: '', pLimit: '',
                quillEditor: null,

                initEditor() {
                    if (!this.quillEditor) {
                        this.quillEditor = new Quill('#editor-container', {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    ['bold', 'italic', 'underline'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    ['link', 'clean']
                                ]
                            }
                        });
                        this.quillEditor.on('text-change', () => {
                            this.pDescription = this.quillEditor.root.innerHTML;
                        });
                    }
                },
                
                showAddonForm: false,
                targetProductId: null,
                addonType: 'file',

                openAddonForm(productId) {
                    this.targetProductId = productId;
                    this.addonType = 'file';
                    this.showAddonForm = true;
                },

                openNewProductForm() {
                    this.editProductId = null;
                    this.pName = ''; this.pPrice = ''; this.pSalePrice = ''; this.pDescription = '';
                    this.pType = 'file'; this.pUnlimited = true;
                    this.pDownloadUrl = ''; this.pQty = ''; this.pLimit = '';
                    this.showProductForm = true;
                    setTimeout(() => {
                        this.initEditor();
                        if(this.quillEditor) this.quillEditor.root.innerHTML = '';
                    }, 100);
                },

                editProduct(id) {
                    const prod = this.products.find(p => p.id === id);
                    if(prod) {
                        this.editProductId = prod.id;
                        this.pName = prod.name;
                        this.pPrice = prod.price;
                        this.pSalePrice = prod.sale_price;
                        this.pType = prod.type || 'file';
                        this.pUnlimited = prod.is_unlimited_qty == 1;
                        this.pDownloadUrl = prod.download_url || '';
                        this.pQty = prod.qty || '';
                        this.pLimit = prod.limit_per_checkout || '';
                        this.pDescription = prod.description || '';
                        
                        // Set standard fields in form if needed or just use models
                        this.showProductForm = true;
                        
                        setTimeout(() => {
                            this.initEditor();
                            if(this.quillEditor) this.quillEditor.root.innerHTML = this.pDescription;
                        }, 100);
                    }
                },

                formatNumber(num) {
                    return Number(num).toLocaleString('id-ID');
                },

                getSocialIcon(platform) {
                    platform = platform.toLowerCase();
                    if (platform.includes('instagram')) return '<i class="fab fa-instagram"></i>';
                    if (platform.includes('tiktok'))    return '<i class="fab fa-tiktok"></i>';
                    if (platform.includes('youtube'))   return '<i class="fab fa-youtube"></i>';
                    if (platform.includes('whatsapp'))  return '<i class="fab fa-whatsapp"></i>';
                    if (platform.includes('facebook'))  return '<i class="fab fa-facebook"></i>';
                    if (platform.includes('twitter'))   return '<i class="fab fa-twitter"></i>';
                    return '<i class="fas fa-globe"></i>';
                }
            }
        }
    </script>
</x-app-layout>
