@extends('layouts.app2')

@section('content')

<div class='px-2 pb-6 grid grid-cols-12'>
    <div class="lg:flex lg:items-center lg:justify-between py-6">
        <div class="flex-1 min-w-0 justify-left">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Posts
            </h1>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4 justify-right">
            <a href="{{ route('posts.create') }}">
                <x-button type='button'>
                    Create
                </x-button>
            </a>
        </div>
    </div>
    <div class="col-span-12">
        <div class='px-2 pb-6 grid grid-cols-12 gap-6'>
        @foreach ($posts as $post)
            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                @include('post/components/card', ['post' => $post])
            </div>
        @endforeach
        </div>
    </div>
</div>

@endsection

@section('inline_js')
    @parent
@endsection