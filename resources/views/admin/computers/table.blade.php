@push('js')
    <script type="text/javascript">
        const $tableID = $('#table');


        $tableID.on('click', '.table-delete', function (e) {
            e.preventDefault();
            const $row = $(this).parents('tr');
            const headers = [];

            // const headers = $tableID.find('th:not(:empty)');
            const $td = $row.find('td').each(function () {

                headers.push($(this).text().toLowerCase());
            });
            const h = {};
            const dataSend = {
                "_token": "{{ csrf_token() }}"
            }
            // const dataSend = [];

            // // Get the headers (add special header logic here)
            // $($row.shift()).find('th:not(:empty)').each(function () {

            //     headers.push($(this).text().toLowerCase());
            // });

            // // Use the headers from earlier to name our hash keys
            headers.forEach((header, i) => {

            h[header] = $td.eq(i).text();
            });
            dataSend['name'] = headers[1];
            dataSend['description'] = headers[2];
            dataSend['local'] = headers[3];
            console.log(h);
            console.log(headers);
            console.log(dataSend);

            <?php
            if (isset($contexto) && $contexto == \MediaManager\Models\Group::class) {
                ?>
                $.ajax({
                    type: "POST",
                    url: "/api/admin/groups/{!! $contextoId !!}/"+headers[0]+"/rmdispositivo",
                    data: dataSend,
                    success: function() {
    
                        $row.detach();
                    },
                    dataType: "json",
                });
                <?php
            } else {
                ?> 
                $.ajax({
                    type: "DELETE",
                    url: "/api/admin/computers/"+headers[0],
                    data: dataSend,
                    success: function() {
    
                        $row.detach();
                    },
                    dataType: "json",
                });
                <?php
            }
            ?>
            toastr.success('', "Dispositivo deletado com sucesso");
            $row.detach();
        });
    </script>
@endpush

@if (method_exists($computers,'onEachSide'))
    {{ $computers->onEachSide(10)->links() }}
@endif
<table id="table" class="table table-striped">
    <thead>
        <tr>
            <td>ID</td>
            <td>Nome</td>
            <td>Grupo</td>
           
            <?php /* <td>Último Acesso</td><td>Token</td><td>Acessos</td>
            <td>Created at</td>*/ ?>
            <td colspan="2">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($computers as $computer)
        <tr>
            <td>{{$computer->id}}</td>
            <td><a href="{{ route('admin.media-manager.computers.edit',$computer->id)}}">{{!empty($computer->name)?$computer->name:$computer->token}}</a></td>
            <td>{{($computer->group?$computer->group->name:'Sem grupo')}}</td>
            
            <?php /*<td>{{$computer->updated_at->format('d/m/Y h:i:s')}}</td><td>{{$computer->token}}</td><td>{{$computer->acessos()->count()}}</td>
            <td>{{$computer->created_at->format('d/m/Y h:i:s')}}</td>*/ ?>
            <td>
                <!--<a href="{{ route('admin.media-manager.computers.show',$computer->id)}}" class="btn btn-primary">Visualizar</a>
                
                <a href="{{ route('admin.media-manager.computers.edit',$computer->id)}}" class="btn btn-primary">Editar</a>-->
                
            <?php
            if (isset($contexto) && $contexto == \MediaManager\Models\Group::class) {
                ?>
                <form action="{{ route('api.admin.groups.adddispositivo', [ 'group' => $contextoId, 'computer' => $computer->id] )}}" method="post">
                    @csrf
                    @method('POST')
                    <button class="btn btn-danger table-delete" type="submit">Deletar</button>
                </form>
                <?php
            } else {
                ?> 
                <form action="{{ route('admin.media-manager.computers.destroy', $computer->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger table-delete" type="submit">Deletar</button>
                </form>
                <?php
            }
            ?>
            </td> 
        </tr>
        @endforeach
    </tbody>
</table>
@if (method_exists($computers,'onEachSide'))
    {{ $computers->onEachSide(10)->links() }}
@endif