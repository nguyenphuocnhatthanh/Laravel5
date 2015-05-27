<div class="col-md-12">
    <div id="displayModal"></div>
    <table class="table table-striped" id="UsersData">
        <thead>
        <th>{!! sortBy('admin.users','name', 'Name', isset($search) ? $search : null) !!}
            @include('admin.partials.sortIcon', ['column' => 'name'])</th>
        <th>{!! sortBy('admin.users','email', 'Email', isset($search) ? $search : null) !!}
            @include('admin.partials.sortIcon', ['column' => 'email'])</th>
        <th>{!! sortBy('admin.users','roles', 'Roles', isset($search) ? $search : null) !!}
            @include('admin.partials.sortIcon', ['column' => 'roles'])</th>
        <th>Actions</th>
        <th><input type="checkbox" id="checkAll"/></th>
        </thead>
        <tbody>
{{--        {{dd($users)}}--}}
        @if($users != null)
            @foreach($users as $user)
                <tr>
                    <td class="vert-align">{{$user->name}}</td>
                    <td class="vert-align">{{$user->email}}</td>
                    @if(Session::has('sort'))
                        <?php
                            $roles = (Session::get('sort') == 'desc')
                                    ? $user->roles->sortByDesc(function($q){
                                                        return $q->id;
                                                    })
                                    : $user->roles->sortBy(function($q){
                                        return $q->id;
                                    });
                        ?>
                    @endif
                    <td class="vert-align">  {!! Form::select('roles', $user->roles->lists('name', 'id'), null, ['class' => 'form-control']) !!} </td>
                    <td class="vert-align">
                        @if(isset($selected) && $selected == 'displaydelete')
                            {!! link_to_asset('admin/users/restore/'.$user->id, 'Enable', ['class' => 'iframe btn btn-xs btn-primary btn-restore cboxElement', 'data-id' => $user->id]) !!}
                        @else
                            {!! link_to_asset('admin/users/edit/'.$user->id, 'Edit',
                            [
                            'class' => 'iframe btn btn-xs btn-default btn-edit cboxElement ',
                            'data-toggle' => 'modal'
                            ])
                            !!}
                            {!! link_to_asset('admin/users/delete/'.$user->id, 'Delete', ['class' => 'iframe btn btn-xs btn-danger cboxElement', 'data-id' => $user->id]) !!}
                        @endif
                    </td>
                    <td class="vert-align"><input type="checkbox" name="selectedCheck[]" value="{{$user->id}}" class="selectedCheck"/></td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['objects' => $users])