@if(auth('web')->user()->can('admin.pos.index'))
<x-splade-link href="{{route('admin.pos.index')}}">
    <button title="{{__('POS')}}" type="button" class="filament-icon-button flex items-center justify-center rounded-full relative hover:bg-gray-500/5 focus:outline-none text-success-500 focus:bg-success-500/10 dark:hover:bg-gray-300/5 w-10 h-10 ml-4 -mr-1">
        <span class="sr-only"></span>
        <x-heroicon-s-rocket-launch class="filament-icon-button-icon w-5 h-5" />
    </button>
</x-splade-link>
@endif
