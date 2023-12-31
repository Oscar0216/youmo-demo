@extends('layouts.app2')

@section('content')

<div class='px-2 pb-6 grid grid-cols-12'>
    <div class="lg:flex lg:items-center lg:justify-between py-6">
        <div class="flex-1 min-w-0 justify-left">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Posts
            </h1>
        </div>
        <div class="box-tools">
            <button 
                type="button" 
                class="btn btn-block btn-primary btn-modal" 
                data-href="{{ route('posts.create') }}" 
                data-container=".post_modal">
                <i class="fa fa-plus"></i> 
                {{ __('post.create') }}
            </button>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="order_filter"> {{ __('post.sort') }}</label>
                    <select class="form-control" id="order_filter" name="order_filter">
                        <option selected="selected" value="">{{ __('post.none') }}</option>
                        <option value="asc">{{ __('post.create_asc') }}</option>
                        <option value="desc">{{ __('post.create_desc') }}</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="search_field"> {{ __('post.search') }}</label>
                    <input type="text" name="search_field" id="search_field" class="form-control" placeholder="Search" aria-required="true">
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12">
        <div class='px-2 pb-6 grid grid-cols-12 gap-6 post_list'>
            @foreach ($posts as $post)
                <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                    @include('post/components/card', ['post' => $post])
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade post_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('inline_js')
    @parent
    <script type="text/javascript">
        $(document).ready( function(){
            var base_url = window.location.origin;

            $(document).on('click', '.btn-modal', function(e) {
                e.preventDefault();
                var container = $(this).data('container');

                $.ajax({
                    url: $(this).data('href'),
                    dataType: 'html',
                    success: function(result) {
                        $(container)
                            .html(result)
                            .modal('show');
                    },
                });
            });

            $('.post_modal').on('shown.bs.modal', function(e) {
                $('form#post_add_form, form#post_edit_form')
                    .validate({
                        rules: {
                            title: "required"
                        },
                    });
            });

            const editPost = (templateUrl) => {
                $('div.post_modal').load(templateUrl, function() {
                    $(this).modal('show');
                });
            }

            $(document).on('click', '.edit_post', function(e) {
                e.preventDefault();
                
                editPost($(this).attr('href'));
            });


            $(document).on('click', 'button.delete_post', function() {
                swal({
                    title: 'Are you sure?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            success: function(result) {
                                setTimeout(function(){
                                    window.location.reload();
                                }, 1000);
                            },
                        });
                    }
                });
            });

            $(document).on(
                'change', 
                `#order_filter`, 
                function(){
                    var create_sort = $('#order_filter').val();
                    var search = $('#search_field').val();

                    $.ajax({
                        method: 'GET',
                        url: 'api/v1/posts',
                        data: {
                            create_sort: create_sort,
                            search: search
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('.post_list').html(result.html);
                        },
                    });
                }
            );

            var timer, delay = 800;
            $(document).on(
                'input', 
                `#search_field`, 
                function(){
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        var create_sort = $('#order_filter').val();
                        var search = $('#search_field').val();

                        $.ajax({
                            method: 'GET',
                            url: 'api/v1/posts',
                            data: {
                                create_sort: create_sort,
                                search: search
                            },
                            dataType: 'json',
                            success: function(result) {
                                $('.post_list').html(result.html);
                            },
                        });
                    }, delay);
                }
            );
        });
        

    </script>
@endsection