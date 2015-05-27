@include('partials.error')

<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update Role</h4>
            </div>
            <div class="modal-body">

                <div id="loadding" class="text-center">Loading...
                    <img src="{!!asset('image/ajax-loader.gif') !!}" alt=""/>
                </div>
                <div id="flashMessage"></div>
                <form id="FormUserEdit" class="form-horizontal" action="{!! action('Admin\UsersController@postEdit') !!}" method="post">
                    <input type="hidden" id="_token" name="_token" value="{!!csrf_token()!!}"/>
                    <input type="hidden" name="id" id="user_id" value="{!! $user->id !!}"/>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="inputName" value="{!! $user->name !!}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" id="inputEmail" value="{!! $user->email !!}" placeholder="Enter Email">
                        </div>
                    </div>

                   {{-- <div class="form-group">
                        <label for="role" class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-10">
                            {!! Form::select('roles[]', $roles, null, ['class' => 'form-control',
                            'id' => 'select', 'multiple'=>'multiple'])  !!}
                        </div>
                    </div>--}}
                    <div class="form-group">
                        <label for="roles" class="col-sm-2 control-label">Roles</label>
                        <div class="col-sm-10">
                            {!! Form::select('roles[]', $roles, $user->roles->lists('id'), ['class' => 'form-control select2-custom', 'id' => 'select', 'multiple'=>'multiple']) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnUsersEdit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/setDefaultValidation.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>
<script>

    $(document).ready(function(){

       // editEntrustUser('#FormUserEdit', '#UsersData','/admin/users/edit/','/admin/users/delete/');
        setDefaultValidation();
        $.validator.addMethod("customemail",
                function(value, element) {
                    return /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(value);
                },
                "Sorry, I've enabled very strict email validation"
        );
        checkAjax = false;
        $("#loadding").hide();

        $('#FormUserEdit').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    customemail: true,
                    remote: {
                        url: "{{ action('Admin\UsersController@checkEditEmail') }}",
                        data: {
                            id : function(){
                                return $('#user_id').val()
                            },
                            email : function(){
                                return $('#inputEmail').val()
                            }
                        },
                        type: 'GET'
                    }
                },
                'roles[]' : {
                    required: true
                }
            },
            messages:{
                email: {
                    remote : 'Name Exists'
                }
            },
            submitHandler: function(event) {
                var id = url.split('/');
                $("#loadding").show();
                if(checkAjax) return;

                checkAjax = true;
                $.ajax({
                    url : $('#FormUserEdit').attr('action'),
                    type : 'POST',
                    data : $('#FormUserEdit').serialize(),
                    success : function(result){

                        if(typeof (result) != 'undefined'){

                            var flashMessage = '<div class="alert alert-success"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>';
                            flashMessage += 'Edit success </div>';
                            $('#flashMessage').html(flashMessage);

                            $('#UsersData'+' tr td a:first-child').each(function(key, value){
                                var urlPerms = $(this).attr('href');

                                if(urlPerms == url){
                                    var thisElement = $(this).closest('tr');
                                    $.each(result, function(index, object){
                                        var value1 = '';
                                        value1 += '<td>'+object.name+'</td>';
                                        value1 += '<td>'+object.email+'</td>';
                                        value1 += '<td><select class="form-control" name="roles">';
                                        $.each(object.roles, function(key, role){
                                            value1 += '<option value="'+role.id+'">'+role.name+'</option>';
                                        });
                                        value1 += '</select></td>';
                                        value1 += '<td><a href="/admin/users/edit/'+object.id+'" class="iframe btn btn-xs btn-default btn-edit cboxElement"' +
                                        ' data-toggle="modal">Edit</a>' +
                                        ' <a href="/admin/users/delete/'+object.id+'" class="iframe btn btn-xs btn-danger cboxElement"' +
                                        'data-id="'+object.id+'">Delete</a></td>';
                                        value1 +=  '<td><input type="checkbox" name="selectedCheck[]" value="'+object.id+'" class="selectedCheck"/></td>';

                                        thisElement.html(value1);
                                    });
                                    $("#loadding").hide();
                                    $('#FormUserEdit'+' button:last-child').hide();
                                    return;
                                }
                            });
                        }
                    }
                }).always(function(){
                    checkAjax = false;
                });
            }
        });
    });
    $('#select').select2({
        placeholder: 'Select Roles...', allowClear: true
    });


</script>
