<x-tomato-admin-layout>
    <div style="margin: -30px -40px -40px -40px">
        <x-splade-data :default="['activeMenu'=>'pos']" remember="pos" local-storage>
            <!-- noprint-area -->
            <div class="hide-print flex justify-between h-screen antialiased text-primary-800 dark:text-gray-200 overflow-scroll">
                <!-- left sidebar -->
                @include('tomato-pos::layouts.parts.left-sidebar')

                <!-- page content -->
                <div class="flex-grow flex">
                    @yield('content')
                </div>
            </div>
        </x-splade-data>
    </div>
</x-tomato-admin-layout>
