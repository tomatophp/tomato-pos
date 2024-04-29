<x-tomato-admin-layout :sidebar="false">
    <x-splade-data :default="['activeMenu'=>'pos']" remember="pos" local-storage>

        @include('tomato-pos::layouts.parts.left-sidebar')

        <!-- noprint-area -->
        <div class="antialiased text-primary-800 dark:text-gray-200">
            @yield('content')
        </div>
    </x-splade-data>
</x-tomato-admin-layout>
