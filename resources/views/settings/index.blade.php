@extends('tomato-pos::layouts.master')

@section('content')
    <div class="h-full w-full">
        <div class="bg-primary-50 border rounded-xl my-4 mx-4">
            <div class="flex justify-between gap-4 my-4 mx-4">
                <div>
                    <h1 class="text-xl font-bold">{{__('POS Settings')}}</h1>
                </div>
            </div>
        </div>
        <div class="my-4 mx-4">
            <x-tomato-settings-card :title="__('My Current Branch')" :description="__('Select Your Current Branch')">
                <x-splade-form method="post" action="{{route('admin.pos.settings.update')}}" class="mt-6 space-y-6" :default="$settings">

                    <x-splade-select
                        name="pos_branch_id"
                        :label="__('Your Branch')"
                        :placeholder="__('Select Branch')"
                        remote-root="data"
                        remote-url="{{route('admin.branches.api')}}"
                        option-value="id"
                        option-label="name"
                        choices
                    />

                    <x-splade-input name="cashier_name" label="{{__('Cashier Name')}}" placeholder="{{__('Your Name')}}" />
                    <x-splade-textarea autosize name="branch_note" label="{{__('Branch Note')}}" placeholder="{{__('Branch Note')}}" />

                    <div class="flex items-center gap-4">
                        <x-splade-submit :label="trans('tomato-admin::global.save')" />
                    </div>
                </x-splade-form>
            </x-tomato-settings-card>
        </div>
    </div>
@endsection
