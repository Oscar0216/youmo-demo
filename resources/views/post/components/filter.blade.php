@foreach ($posts as $post)
    <div class="col-span-12 sm:col-span-6 lg:col-span-4">
        @include('post/components/card', ['post' => $post])
    </div>
@endforeach