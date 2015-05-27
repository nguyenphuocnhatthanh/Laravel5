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
                <form id="FormRoleEdit" class="form-horizontal" action="{!! action('Admin\AdminRolesController@postEdit') !!}" method="post">
                    <input type="hidden" id="_token" name="_token" value="{!!csrf_token()!!}"/>
                    <input type="hidden" name="id" id="role_id" value="{!! $role->id !!}"/>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="inputName" value="{!! $role->name !!}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="display_name" class="col-sm-2 control-label">Display name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="display_name" id="inputDisplayName" value="{!! $role->display_name !!}" placeholder="Enter Display name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="description" id="inputName" value="{!! $role->description !!}" placeholder="Enter Display description">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="permissions" class="col-sm-2 control-label">Permission</label>
                        <div class="col-sm-10">
                            @if(count($permissions) != 0)

                                <ul>
                                    @foreach($permissionsFinally as $name => $displayPermissions)

                                        <li> <label class="control-label" for="{{$name}}">{{$name}}</label>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" class="PermsAll" @if(in_array('checkAll', $displayPermissions))
                                                        {{'checked'}}
                                                    @endif />
                                                    <label for="PermsAll"> checkall</label>
                                                </li>
                                                @foreach($displayPermissions['data'] as $key => $displayPermission)
                                                    <li>
                                                        <input class="checkboxPerms" type="checkbox" name="permissions[]"
                                                               value="{{$displayPermission['id']}}" {{ in_array('checked', $displayPermission)? 'checked' : '' }}/>
                                                        <label for="{{$displayPermission['name']}}">{{$displayPermission['name']}}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>

                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnRolesEdit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/setDefaultValidation.js') }}"></script>
<script>
    $(document).off('click','.checkboxPerms').on('click','.checkboxPerms',function() {
        $(this).parents('ul li').find( ".PermsAll" ).removeAttr('checked');
        var check = $(this).parents('ul li').find( ".checkboxPerms:checked" ).length;
        if(check < 4) {
            $(this).parents('ul li').find( ".PermsAll" ).removeAttr('checked');
        }else{
            $(this).parents('ul li').find( ".PermsAll" ).prop('checked', true);
        }
    });
    $(document).off('click','.PermsAll').on('click','.PermsAll',function() {

        $(this).parents('li').find( ".checkboxPerms" ).prop('checked', this.checked);

    });

    $(document).ready(function(){
       /* $('.checkboxPerms').click(function() {
            $(this).parents('li').find( ".PermsAll" ).removeAttr('checked');
        });
        $('.PermsAll').click(function() {
            $(this).parents('li').find( ".checkboxPerms" ).removeAttr('checked','checked');

        });*/

        //editEntrust('#FormRoleEdit', '#RolesData', "/admin/role/edit/", "/admin/role/delete/");
        setDefaultValidation();
        checkAjax = false;
        $("#loadding").hide();
        $('#FormRoleEdit').validate({
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
                display_name: {
                    required: true
                },
                'permissions[]' : {
                    required: true
                }
            },
            messages:{
                name: {
                    remote : 'Name Exists'
                }
            },
            submitHandler: function(event) {
                if(checkAjax) return;
                $("#loadding").show();
                checkAjax = true;
                $.ajax({
                    url : $('#FormRoleEdit').attr('action'),
                    type : 'POST',
                    data :$('#FormRoleEdit').serialize(),
                    success : function(result){

                        if(typeof (result) != 'undefined'){
                            var flashMessage = '<div class="alert alert-success"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>';
                            flashMessage += 'Edit success </div>';
                            $('#flashMessage').html(flashMessage);

                            $('#RolesData'+' tr td a:first-child').each(function(key, value){
                                var urlPerms = $(this).attr('href');

                                if(urlPerms == url){
                                    var thisElement = $(this).closest('tr');

                                    $.each(result, function(index, object){
                                       var value1 = '';
                                        value1 += '<td>'+object.name+'</td>';
                                        value1 += '<td>'+object.display_name+'</td>';
                                        value1 += '<td>'+object.description+'</td>';
                                        value1 += '<td><a href="/admin/role/edit/'+object.id+'" class="iframe btn btn-xs btn-default btn-edit cboxElement"' +
                                        ' data-toggle="modal">Edit</a>' +
                                        ' <a href="/admin/role/delete/'+object.id+'" class="iframe btn btn-xs btn-danger cboxElement"' +
                                        'data-id="'+object.id+'">Delete</a></td>';
                                        value1 +=  '<td><input type="checkbox" name="selectedCheck[]" value="'+object.id+'" class="selectedCheck"/></td>';

                                        thisElement.html(value1);
                                    });
                                    $("#loadding").hide();
                                    $('#FormRoleEdit'+' button:last-child').hide();
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

</script>

{{--{!! JsValidator::formRequest('App\Http\Requests\RoleFormRule', '#FormRoleEdit'); !!}--}}
