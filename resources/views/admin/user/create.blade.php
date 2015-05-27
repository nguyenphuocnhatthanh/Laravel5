@extends('app')

@section('content')

    @include('partials.error')

    <form action="" method="post" class="form-horizontal" id="FormRoleCreate">
        <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token"/>

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="inputName" placeholder="Enter Name">
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Enter Email">
            </div>
        </div>
        <div class="form-group">
            <label for="roles" class="col-sm-2 control-label">Roles</label>
            <div class="col-sm-10">
                {!! Form::select('roles[]', $roles, null, ['class' => 'form-control', 'id' => 'select', 'multiple'=>'multiple']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary btn-default">Add</button>
            </div>
        </div>

    </form>
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/setDefaultValidation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>
    <script>

        $(document).ready(function(){
            $('.checkboxPerms').click(function() {
                $(this).parents('li').find( ".checkAll" ).removeAttr('checked');
            });
            $('.checkAll').click(function() {
                $(this).parents('li').find( ".checkboxPerms" ).removeAttr('checked','checked');

            });
            setDefaultValidation();
            $.validator.addMethod("customemail",
                    function(value, element) {
                        return /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(value);
                    },
                    "Sorry, I've enabled very strict email validation"
            );
            $('#FormRoleCreate').validate({
                rules: {
                    name: {
                        required: true,
                        remote:{
                            url : "{{ action('Admin\AdminRolesController@checkName')  }}",
                            data: {
                                _token : function(){
                                    return $('#_token').val();
                                },
                                id : function(){
                                    return $('#role_id').val();
                                }
                            },
                            type: 'POST'
                        }

                    },
                    email: {
                        required: true,
                        email: true,
                        customemail: true,
                        remote : {
                            url : "{{action('Admin\UsersController@checkEmail')}}",
                            data: {
                                email : function(){
                                    return $('#inputEmail').val();
                                }
                            },
                            type : 'GET'
                        }
                    },
                    'roles[]' : {
                        required: true
                    }
                },
                messages:{
                    email: {
                        remote : 'Email Exists'
                    }
                }
            });

        });
        $('#select').select2({
            placeholder: 'Select Roles...', allowClear: true
        });
    </script>
@stop
