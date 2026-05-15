@csrf

<x-admin.card title="Product details" subtitle="Core catalog information for this SKU." class="mb-6">
    <div class="grid gap-5 lg:grid-cols-2">
        <x-admin.field label="Product name" for="name" :required="true">
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $product->name ?? '') }}"
                required
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500"
                placeholder="e.g. Oak dining chair"
            />
        </x-admin.field>

        <x-admin.field label="Category" for="category_id" :required="true">
            <select
                name="category_id"
                id="category_id"
                required
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
            >
                <option value="" disabled {{ old('category_id', $product->category_id ?? '') ? '' : 'selected' }}>Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Color" for="color_name" hint="Optional finish or upholstery label.">
            <input
                type="text"
                name="color_name"
                id="color_name"
                value="{{ old('color_name', $product->color_name ?? '') }}"
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                placeholder="Navy, walnut, etc."
            />
        </x-admin.field>

        <div class="grid gap-5 sm:grid-cols-2 lg:col-span-2">
            <x-admin.field label="Quantity (units)" for="quantity" :required="true">
                <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    value="{{ old('quantity', $product->quantity ?? '') }}"
                    required
                    min="0"
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm tabular-nums shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                />
            </x-admin.field>
            <x-admin.field label="Stock on hand" for="stock" :required="true">
                <input
                    type="number"
                    name="stock"
                    id="stock"
                    value="{{ old('stock', $product->stock ?? '') }}"
                    required
                    min="0"
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm tabular-nums shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                />
            </x-admin.field>
        </div>

        <x-admin.field label="Price (₹)" for="price" :required="true">
            <input
                type="text"
                name="price"
                id="price"
                value="{{ old('price', $product->price ?? '') }}"
                required
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm tabular-nums shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                inputmode="decimal"
            />
        </x-admin.field>

        <div class="flex items-end pb-1">
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/50">
                <input
                    type="checkbox"
                    name="is_featured"
                    id="is_featured"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500/30"
                    {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                />
                <span class="text-sm font-medium text-slate-800 dark:text-slate-200">Feature this product on storefront highlights</span>
            </label>
        </div>
    </div>
</x-admin.card>

<x-admin.card title="Media" subtitle="Drag an image onto the drop zone or browse from your device." class="mb-6">
    <div
        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 p-6 transition dark:border-slate-600 dark:bg-slate-900/40"
        x-data="productImageDropzone()"
        x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        x-on:drop.prevent="onDrop($event)"
        :class="dragging ? 'border-violet-400 bg-violet-50/50 dark:border-violet-500 dark:bg-violet-950/30' : ''"
    >
        <input type="file" accept="image/*" name="image" id="image" x-ref="fileInput" class="sr-only" onchange="previewImage(event)" />
        <div class="flex flex-col items-center justify-center gap-3 text-center sm:flex-row sm:text-left">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
                <svg class="h-7 w-7 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-900 dark:text-white">Product image</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">PNG or JPG up to ~2MB. Square assets look best.</p>
            </div>
            <x-admin.button type="button" variant="secondary" size="sm" @click="pick()">Browse files</x-admin.button>
        </div>
        <div class="mt-6 flex justify-center sm:justify-start">
            <img
                id="image-preview"
                src="{{ !empty($product->image) ? asset('storage/' . $product->image) : '' }}"
                alt="Preview"
                class="max-h-48 rounded-2xl border border-slate-200 object-cover shadow-sm dark:border-slate-700 {{ !empty($product->image) ? '' : 'hidden' }}"
                width="220"
            />
        </div>
    </div>
</x-admin.card>

<div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
    <x-admin.button href="{{ route('products.index') }}" variant="secondary" type="button">Cancel</x-admin.button>
    <x-admin.button type="submit" variant="primary">
        {{ $submitLabel }}
    </x-admin.button>
</div>

<script>
    function productImageDropzone() {
        return {
            dragging: false,
            pick() {
                this.$refs.fileInput.click();
            },
            onDrop(e) {
                this.dragging = false;
                const input = this.$refs.fileInput;
                const dt = new DataTransfer();
                for (const file of e.dataTransfer.files) {
                    if (file.type.startsWith('image/')) {
                        dt.items.add(file);
                    }
                }
                if (dt.files.length) {
                    input.files = dt.files;
                    previewImage({ target: input });
                }
            },
        };
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
