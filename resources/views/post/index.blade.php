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
            <button type="button" class="btn btn-block btn-primary btn-modal" 
                data-href="{{ route('posts.create') }}" 
                data-container=".post_modal">
                <i class="fa fa-plus"></i> 
                Create
            </button>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="order_filter">Sort</label>
                    <select class="form-control" id="order_filter" name="order_filter">
                        <option selected="selected" value="">沒有</option>
                        <option value="asc">建立時間(遞增)</option>
                        <option value="desc">建立時間(遞減)</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="search_field">Search</label>
                    <input type="text" name="search_field" id="search_field" class="form-control" placeholder="Search" aria-required="true">
                </div>
            </div>
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

<div class="modal fade post_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('inline_js')
    @parent
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
        crossorigin="anonymous"
    >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
        crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
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

            $(document).on('change', 
                            `#order_filter`, 
                            function(){
                                var create_sort = $('#order_filter').val();
                                window.location.href = base_url + "/posts" + "?create_sort=" + create_sort;
                            }
            );

            var timer, delay = 500;
            $(document).on(
                'input', 
                `#search_field`, 
                function(){
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        var search = $('#search_field').val();
                        window.location.href = base_url + "/posts" + "?search=" + search;
                    }, delay);
                }
            );
        });
        

    </script>
@endsection