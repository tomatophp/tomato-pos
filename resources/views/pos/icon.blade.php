@if(auth('web')->user()->can('admin.pos.index'))
    <x-tomato-admin-dropdown-item type="link" href="{{route('admin.pos.index')}}" icon="bx bxs-rocket" :label="__('POS')"  />
@endif
