    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div>
            <img src='{{ asset($post->image) }}' style='height: 50%; width: 50%; object-fit: cover;'>
        </div>
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $post->title }}
            </h3>
            <p class="mt-1 max-w-2xl text-lg text-gray-500">
                {{ $post->description }}
            </p>
            <a href="{{ route('posts.edit', $post)}}" class="btn btn-primary edit_post">{{ __('post.edit') }}</a>
            <button data-href="{{ route('posts.destroy', $post)}}" class="btn btn-danger delete_post">{{ __('post.delete') }}</a>
        </div>
    </div>