<x-splade-data :default="['activeMenu'=>'pos']" remember="pos" local-storage>
    <!-- noprint-area -->
    <div class="hide-print flex justify-between h-screen antialiased text-primary-800 overflow-scroll">
        <!-- left sidebar -->
        @include('tomato-pos::layouts.parts.left-sidebar')

        <!-- page content -->
        <div class="flex-grow flex">
            @yield('content')
        </div>
    </div>
</x-splade-data>
<x-splade-script>
    if(localStorage.getItem("splade") && typeof document !== undefined){
    let spladeStorage = JSON.parse(localStorage.getItem("splade"));
    let dark = spladeStorage?.admin?.dark;
    document.body.classList[dark ? "add" : "remove"]("dark-scrollbars");
    document.documentElement.classList[dark ? "add" : "remove"]("dark");
    let htmlEl = document.querySelector("html");

    if ("{{app()->getLocale()}}" === "ar") {
    htmlEl.setAttribute("dir", "rtl");
    } else {
    htmlEl.setAttribute("dir", "ltr");
    }
    }

</x-splade-script>
