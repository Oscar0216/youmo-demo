<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('posts.update', $post) }}" accept-charset="UTF-8" id="post_edit_form" novalidate="novalidate" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h4 class="modal-title">更新Post</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
  
            <div class="modal-body">
                <div class="row">            
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="title">{{ __('post.title') }}:*</label>
                            <input type="text" name="title" id="title" class="form-control" value='{{  $post->title }}' required="" placeholder="title" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="description">{{ __('post.description') }}:</label>
                            <textarea name="description" id="description" cols="50" rows="10" class="form-control" aria-hidden="true">{{  $post->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="image">{{ __('post.image') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <img class='m-auto' src='{{ asset($post->image) }}'/>
                                <input type="file" name="image" id="upload_image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <div class="flex items-center">
                                <input type="radio" name="active" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value='1' {{ ($post->active ? "checked" : "") }}>
                                <label class="ml-3 block text-sm font-medium text-gray-700">
                                    {{ __('post.active') }}
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="active" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value='0' {{ ($post->active ? "" : "checked") }}>
                                <label class="ml-3 block text-sm font-medium text-gray-700">
                                    {{ __('post.inactive') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('post.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('post.save') }}</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->