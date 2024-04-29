<x-splade-form preserve-scroll method="GET" action="{{route('admin.pos.index')}}" :default="['search' => request()->get('search') ?? null]">
    <div class="flex  flex-row relative">
        <div class="absolute left-5 top-3 px-2 py-2 rounded-full bg-primary-500 text-white">
            <x-heroicon-s-magnifying-glass class="w-6 h-6" />
        </div>
        <input
            type="text"
            class="bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 dark:text-zinc-300 rounded-lg shadow text-lg full w-full h-16 py-4 pl-16 transition-shadow focus:shadow-2xl focus:outline-none"
            placeholder="{{__('Scan Barcode or just input product SKU')}}"
            v-model="form.search"
        />
    </div>
</x-splade-form>
