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
                <form id="FormRoleEdit" class="form-horizontal" action="{!! action('Admin\PermissionsController@postEdit') !!}" method="post">
                    <input type="hidden" id="_token" name="_token" value="{!!csrf_token()!!}"/>
                    <input type="hidden" name="id" id="perms_id" value="{!! $permission->id !!}"/>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="inputName" value="{!! $permission->name !!}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="display_name" class="col-sm-2 control-label">Display name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="display_name" id="inputDisplayName" value="{!! $permission->display_name !!}" placeholder="Enter Display name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="description" id="inputName" value="{!! $permission->description !!}" placeholder="Enter Display description">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="close" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/setDefaultValidation.js') }}"></script>
<script>

$(document).ready(function(){
    var checkAjax = false;
    setDefaultValidation();
    /*
    * Rule Form
     */
    $('#FormRoleEdit').validate({
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
                            return $('#perms_id').val();
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
        }
    });

    /*
    * Form Edit
     */
  //  $(document).on("submit", "#FormRoleEdit",function(e){
   // $('#FormRoleEdit').submit(function(e){
    editEntrust('#FormRoleEdit', 'table#PersData', "/admin/permission/edit/", "/admin/permission/delete/");

        /**
         * Close Form Edit
         */
        /*$('button.close,#close').click(function (e) {
            var id = url.split('/');
            id = id[id.length - 1];
            alert(id);
            /*//**e.preventDefault();
            $.ajax({
                url : '/admin/permission/edit',
                type : 'POST',
                data {

                }
                success: function (result) {
                    alert('123');
                }
            });*//*
        })*/
    });

</script>

{{--{!! JsValidator::formRequest('App\Http\Requests\RoleFormRule', '#FormRoleEdit'); !!}--}}
