@extends('app')

@section('content')

    <form action="" method="post" id="formRole">
        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        <div class="col-md-6">
            <div id="ajaxLoading"  class="pull-right">Loading...
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
        <div class="col-md-6">
            <div class="pull-right">
                <a href="{{URL::to('admin/role/create')}}" class="btn btn-sm btn-info iframe col-md-offset-6 linkCreate"><span class="glyphicon glyphicon-plus-sign"></span> Create</a>

                <div class="pull-right input-group col-md-4">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                         <button class="btn btn-default" id="btnSearch" type="submit">Go!</button>
                    </span>
                </div>
            </div>
        </div>
    <div id="dataRoles">
        @include('admin.role.pagination')
    </div>
    </form>

    <div id="displayModal"></div>

<script type="text/javascript" src="{{ asset('js/myFunction.js') }}"></script>
<script>
    showModalFinal();
    restoreAjax();
    $('document').ready(function () {
        $('#btnSearch').click(function(){
            $('#formRole').attr('method', 'GET');
        });

        changeSelect('#option', '#formRole', '/admin/role');
        deleteAjax('/admin/role/delete/');
        search('/admin/role', 'dataRoles');
        AjaxPagination('dataRoles');
    });
</script>
@stop