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
           <label for="display_name" class="col-sm-2 control-label">Display name</label>
           <div class="col-sm-10">
               <input type="text" class="form-control" name="display_name" id="inputDisplay" placeholder="Enter Display name">
           </div>
       </div>
       
       <!--description TextField -->
       <div class="form-group">
           <label for="description" class="col-sm-2 control-label">Description</label>
           <div class="col-sm-10">
               <input type="text" class="form-control" name="description" id="inputDescription" placeholder="Enter Description">
           </div>
       </div>
       <div class="form-group">
           <label for="display_name" class="col-sm-2 control-label">Permission</label>
           <div class="col-sm-10">
               @if(count($permissions) != 0)
                 {{--  @foreach($permissions as $permission)
                       <label class="control-label" for="{{$permission->name}}">{{$permission->name}}</label>
                       <input type="checkbox" name="permissions[]" value="{{$permission->id}}"/>
                   @endforeach--}}
                   <ul>
                       <?php $i = 1; $j = 1;?>
                       @foreach($permissionsFinally as $name => $displayPermissions)
                           <li> <label class="control-label" for="{{$name}}">{{$name}}</label>
                               <ul>

                                   @foreach($displayPermissions as $key => $displayPermission)
                                       @if($key == 0)
                                           <li><input type="checkbox" class="checkAll"/><label for="checkall">checkall</label></li>
                                       @endif
                                       <li>  <input data-stt="{{$i.$j}}" type="checkbox" class="checkboxPerms" name="permissions[]" value="{{$displayPermission->id}}"/><label
                                                   for="{{$displayPermission->name}}">{{$displayPermission->name}}</label></li>
                                    <?php $j++;?>
                                   @endforeach
                               </ul>
                           </li>
                               <?php $i++;?>
                       @endforeach
                   </ul>

               @endif
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
    <script>

        $(document).off('click','.checkboxPerms').on('click','.checkboxPerms',function() {
            $(this).parents('ul li').find( ".checkAll" ).removeAttr('checked');
            var check = $(this).parents('ul li').find( ".checkboxPerms:checked" ).length;
            if(check < 4) {
                $(this).parents('ul li').find( ".checkAll" ).removeAttr('checked');
            }else{
                $(this).parents('ul li').find( ".checkAll" ).prop('checked', true);
            }
        });

        $(document).off('click','.checkAll').on('click','.checkAll',function() {

            $(this).parents('li').find( ".checkboxPerms" ).prop('checked', this.checked);

        });

        $(document).ready(function(){
     /*       $('.checkboxPerms').click(function() {
                $(this).parents('li').find( ".checkAll" ).removeAttr('checked');
            });
            $('.checkAll').click(function() {
                $(this).parents('li').find( ".checkboxPerms" ).removeAttr('checked','checked');

            });*/
            setDefaultValidation();
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
        });

    </script>
@stop