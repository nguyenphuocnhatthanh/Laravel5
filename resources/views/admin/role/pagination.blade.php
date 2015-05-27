<div class="col-md-12 table-responsive">
    <div id="displayModal"></div>
    <table class="table table-striped" id="RolesData">
        <thead>
        {{--<th><a href="{{URL::to('/admin/permissions?sort=name&order=asc')}}">Name<i class="fa fa-fw fa-sort"></i></a></th>--}}
        <th>{!! sortBy('role','name', 'Name', isset($search) ? $search : null) !!}
            @include('admin.partials.sortIcon', ['column' => 'name'])
        </th>
        <th>{!! sortBy('role','display_name', 'Display Name', isset($search) ? $search : null) !!}@include('admin.partials.sortIcon', ['column' => 'display_name'])</th>
        <th>{!! sortBy('role','description', 'Description', isset($search) ? $search : null) !!}@include('admin.partials.sortIcon', ['column' => 'description'])</th>
        <th>Actions</th>
        <th><input type="checkbox" id="checkAll"/></th>
        </thead>
        <tbody>

        @if($roles != null)
            @foreach($roles as $role)
                <tr>
                    <td>{{$role->name}}</td>
                    <td>{{$role->display_name}}</td>
                    <td>{{$role->description}}</td>
                    <td>

                        @if(isset($selected) && $selected == 'displaydelete')
                            {!! link_to_asset('admin/role/restore/'.$role->id, 'Enable', ['class' => 'iframe btn btn-xs btn-primary btn-restore cboxElement', 'data-id' => $role->id]) !!}
                        @else
                            {!! link_to_asset('admin/role/edit/'.$role->id, 'Edit',
                            [
                            'class' => 'iframe btn btn-xs btn-default btn-edit cboxElement ',
                            'data-toggle' => 'modal'
                            ])
                            !!}
                            {!! link_to_asset('admin/role/delete/'.$role->id, 'Delete', ['class' => 'iframe btn btn-xs btn-danger cboxElement', 'data-id' => $role->id]) !!}
                        @endif
                    </td>
                    <td><input type="checkbox" name="selectedCheck[]" value="{{$role->id}}" class="selectedCheck"/></td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['objects' => $roles])


