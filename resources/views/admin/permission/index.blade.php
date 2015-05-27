@extends('app')

@section('content')
{{--@if(Session::has('selected'))
    {{dd(Session::get('selected'))}}
@endif--}}


    <form action="" method="post" id="formPerms">
        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        <div class="col-md-6">
            <div id="ajaxLoading" class="pull-right">Loading...
                <img src="{!!asset('image/ajax-loader.gif') !!}" alt=""/>
            </div>
            <div class="pull-left">
                @if(isset($selected) && $selected == 'displaydelete')
                    {!! Form::select('option', $listRestore, !empty($selected) ? $selected : null, ['class' => 'form-control', 'id' => 'option' ]) !!}
                @else
                    {!! Form::select('option', $select, !empty($selected) ? $selected : null, ['class' => 'form-control', 'id' => 'option' ]) !!}
                @endif
            </div>
        </div>

   {{-- <form action="" method="get" id="formPerms">--}}
        {{--<input type="hidden" name="_token" value="{{csrf_token()}}"/>--}}
        <div class="col-md-6">

            <a href="{{URL::to('admin/permission/create')}}" class="btn btn-sm btn-info iframe col-md-offset-6 linkCreate"><span class="glyphicon glyphicon-plus-sign"></span> Create</a>

            <div class="pull-right input-group col-md-4">
                <input type="text" id="search" name="search" class="form-control" placeholder="Search for...">
                  <span class="input-group-btn">
                    <button class="btn btn-default" id="btnSearch" type="submit">Go!</button>
                  </span>
            </div>
        </div>
        <div id="dataPermissions">
        @include('admin.permission.pagination')
        </div>
   </form>
@stop
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/myFunction.js') }}"></script>
    <script>
       /* var url;
        $('tbody tr td a.btn-edit').click(function (event) {
             event.preventDefault();
             window.url = $(this).attr('href');
        });*/
        showModalFinal();
        restoreAjax();

        $('document').ready(function () {
            $('#btnSearch').click(function(){
                 $('#formPerms').attr('method', 'GET');
            });

            changeSelect('#option', '#formPerms', '/admin/permission');
            deleteAjax('/admin/permission/delete/');
            search('/admin/permission', 'dataPermissions');
            AjaxPagination('dataPermissions');
        });
    </script>

    {{--<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\RoleFormRule', '#FormRoleEdit'); !!}--}}
@endsection