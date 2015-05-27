@extends('app')

@section('content')

    @include('partials.error')

    <form action="" id="FormPermissionCreate" method="post" class="form-horizontal">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <input type="hidden" name="id" id="pers_id" value=""/>
       {{-- <div class="form-group has-error">

            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputError">
                <label class="control-label" for="inputError">Input with error and glyphicon</label>
            </div>
        </div>--}}
        <!--name TextField -->
        <div class="form-group error">
            <label for="name" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="{!! old('name'); !!}" placeholder="Name"/>
              {{--  <span class="help-inline">Please correct the error</span>--}}
            </div>
        </div>
        <!--display_name TextField -->
        <div class="form-group">
            <label for="display_name" class="col-sm-2 control-label">Display Name</label>
            <div class="col-sm-10">
                <input type="text" name="display_name" class="form-control" value="{!! old('display_name'); !!}" placeholder="Display Name"/>
            </div>
        </div>
        <!--description TextField -->
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10">
                <input type="text" name="description" class="form-control" value="" placeholder="Enter Description"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{URL::to('admin/permission')}}" class="btn btn-default">Back To List </a>
            </div>
        </div>

    </form>
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/setDefaultValidation.js') }}"></script>
    <script>
        $(document).ready(function(){
            setDefaultValidation();
            $('#FormPermissionCreate').validate({
                rules: {
                    name: {
                        required: true,
                        remote:{
                            url : "{{ action('Admin\PermissionsController@checkName')  }}",
                            data: {
                                _token : function(){
                                    return $('#_token').val();
                                },
                                id : function(){
                                    return $('#pers_id').val();
                                }
                            },
                            type: 'POST'
                        }

                    },
                    display_name: {
                        required: true
                    }
                },
                messages:{
                    name: {
                        remote : 'Name Exists'
                    }
                }
            });
        });

    </script>
@stop