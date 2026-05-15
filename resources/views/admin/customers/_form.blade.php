<x-admin.card class="mb-6">
    <div class="grid gap-5 md:grid-cols-2">
        <x-admin.field label="Name" for="c-name" :required="true">
            <input
                id="c-name"
                type="text"
                name="name"
                value="{{ old('name', $customer->name) }}"
                required
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
            />
        </x-admin.field>

        <x-admin.field label="Mobile" for="mobile" :required="true" hint="10-digit mobile number.">
            <input
                id="mobile"
                type="tel"
                name="mobile"
                value="{{ old('mobile', $customer->mobile) }}"
                pattern="[0-9]{10}"
                maxlength="10"
                minlength="10"
                inputmode="numeric"
                required
                placeholder="9876543210"
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm tabular-nums shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            />
        </x-admin.field>

        <x-admin.field label="Email" for="c-email" :required="true">
            <input
                id="c-email"
                type="email"
                name="email"
                value="{{ old('email', $customer->email) }}"
                required
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
            />
        </x-admin.field>

        <x-admin.field label="Location" for="location">
            <input
                id="location"
                type="text"
                name="location"
                value="{{ old('location', $customer->location) }}"
                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
            />
        </x-admin.field>

        <div class="md:col-span-2">
            <x-admin.field label="Profile photo" for="user_image">
                <input
                    type="file"
                    name="user_image"
                    id="user_image"
                    accept="image/*"
                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100 dark:text-slate-300 dark:file:bg-violet-500/10 dark:file:text-violet-300"
                    onchange="previewCustomerImage(event)"
                />
            </x-admin.field>
        </div>

        <div class="flex flex-col gap-3 md:col-span-2 md:flex-row md:items-center md:justify-between">
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/50">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500/30"
                    {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}
                />
                <span class="text-sm font-medium text-slate-800 dark:text-slate-200">Active account</span>
            </label>

            @php
                $existingImage = !empty($customer->user_image) ? asset('storage/' . $customer->user_image) : asset('storage/images/default-avatar.png');
            @endphp
            <img
                id="customer-image-preview"
                src="{{ $existingImage }}"
                alt="Avatar preview"
                width="72"
                height="72"
                class="h-[72px] w-[72px] rounded-2xl border border-slate-200 object-cover dark:border-slate-700 {{ empty($customer->user_image) ? 'opacity-60' : '' }}"
            />
        </div>
    </div>
</x-admin.card>

<div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
    <x-admin.button href="{{ route('customers.index') }}" variant="secondary" type="button">Cancel</x-admin.button>
    <x-admin.button type="submit" variant="primary">Save customer</x-admin.button>
</div>

<script>
    function previewCustomerImage(event) {
        const [file] = event.target.files;
        const preview = document.getElementById('customer-image-preview');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('opacity-60');
        }
    }
</script>
