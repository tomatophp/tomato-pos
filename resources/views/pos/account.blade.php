<x-tomato-admin-container label="{{__('Attach Account')}}">
    <x-splade-form :default="['loginBy' => config('tomato-crm.login_by')]" class="flex flex-col space-y-4" action="{{route('admin.pos.account.store')}}" method="post">
        <div class="grid grid-cols-2 gap-4">
            <x-splade-input label="{{__('Name')}}" name="name" type="text"  placeholder="{{__('Name')}}" />
            <x-splade-input label="{{__('Email')}}" name="email" type="email"  placeholder="{{__('Email')}}" />
            <x-splade-input class="col-span-2" label="{{__('Phone')}}" name="phone" type="tel"  placeholder="{{__('Phone')}}" />
        </div>
        <x-splade-textarea label="{{__('Address')}}" name="address" placeholder="{{__('Address')}}" autosize />

        <div class="flex justify-start gap-2 pt-3">
            <x-tomato-admin-submit  label="{{__('Save')}}" :spinner="true" />
            <x-tomato-admin-button secondary @click.prevent="modal.close" label="{{__('Cancel')}}"/>
        </div>

    </x-splade-form>
</x-tomato-admin-container>
