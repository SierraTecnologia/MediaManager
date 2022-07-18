@push('js')
    <script type="text/javascript">
        const $tableID = $('#table');
        const $BTN = $('#export-btn');
        const $EXPORT = $('#export');

        const newTr = `
        <tr class="hide">
        <td class="pt-3-half" contenteditable="true">Example</td>
        <td class="pt-3-half" contenteditable="true">Example</td>
        <td class="pt-3-half" contenteditable="true">Example</td>
        <td class="pt-3-half">
            <span class="table-up"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-up" aria-hidden="true"></i></a></span>
            <span class="table-down"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-down" aria-hidden="true"></i></a></span>
        </td>
        <td>
            <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
        </td>
        </tr>`;

        $('.table-add').on('click', 'i', () => {

            const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

            if ($tableID.find('tbody tr').length === 0) {

                $('tbody').append(newTr);
            }

            $tableID.find('table').append($clone);
        });

        $tableID.on('click', '.table-remove', function () {

            $(this).parents('tr').detach();
        });

        $tableID.on('click', '.table-up', function () {

            const $row = $(this).parents('tr');

            if ($row.index() === 0) {
                return;
            }

            $row.prev().before($row.get(0));
        });

        $tableID.on('click', '.table-down', function () {

            const $row = $(this).parents('tr');
            $row.next().after($row.get(0));
        });

        $tableID.on('click', '.table-saved', function () {

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
                if ($td.eq(i).text() == "Clique p/ Editar") {
                    h[header] = "";
                } else {
                    h[header] = $td.eq(i).text();
                }
            });
            dataSend['name'] = headers[1];
            dataSend['description'] = headers[2];
            dataSend['local'] = headers[3];
            console.log(h);
            console.log(headers);
            console.log(dataSend);
            $.ajax({
                type: "POST",
                url: '/admin/active/'+headers[0],
                data: dataSend,
                success: function() {

                    $row.detach();
                },
                dataType: "json",
            });
            toastr.success('', "Dispositivo ativado com sucesso");
            $row.detach();
        });

        // A few jQuery helpers for exporting only
        jQuery.fn.pop = [].pop;
        jQuery.fn.shift = [].shift;

        $BTN.on('click', () => {

            const $rows = $tableID.find('tr:not(:hidden)');
            const headers = [];
            const data = [];

            // Get the headers (add special header logic here)
            $($rows.shift()).find('th:not(:empty)').each(function () {

                headers.push($(this).text().toLowerCase());
            });

            // Turn all existing rows into a loopable array
            $rows.each(function () {
                const $td = $(this).find('td');
                const h = {};

                // Use the headers from earlier to name our hash keys
                headers.forEach((header, i) => {

                h[header] = $td.eq(i).text();
                });

                data.push(h);
            });

            // Output the result
            $EXPORT.text(JSON.stringify(data));
        });

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

            $.ajax({
                type: "DELETE",
                url: "/api/admin/computers/"+headers[0],
                data: dataSend,
                success: function() {

                    $row.detach();
                },
                dataType: "json",
            });
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
            <td>Descrição</td>
            <td>Local</td>
            <?php /*<td>Token</td><td>Grupo</td><td>Último Acesso</td>
            <td>Acessos</td>
            <td>Created at</td> */ ?>
            <td colspan="2">Ação</td>
        </tr>
    </thead>
    <tbody>
        @foreach($computers as $computer)
        <?php /*<form accept-charset="utf-8" class="form-horizontal" url="{{route('admin.media-manager.computers.active', $computer->id)}}" method="POST">*/ ?>
        <tr>
            <td>{{$computer->id}}</td>
            <td contenteditable="true">Clique p/ Editar</td>
            <td contenteditable="true">Clique p/ Editar</td>
            <td contenteditable="true">Clique p/ Editar</td>
            <?php /*{!! Former::text('name')->required()->label(false) !!}<td>{{$computer->token}}</td><td>{!! Former::select('group_id')->options($groups, 2)->label(false) !!} </td><td>{{$computer->updated_at->format('d/m/Y h:i:s')}}</td>
            <td>{{$computer->acessos()->count()}}</td>
            <td>{{$computer->created_at->format('d/m/Y h:i:s')}}</td> */ ?>
            <td>
                <button class="btn btn-primary table-save table-saved" type="submit">Ativar</button>
                <!--
                <a href="{{ route('admin.media-manager.computers.active', $computer->id)}}" class="btn btn-primary">Ativar</a>
                <a href="{{ route('admin.media-manager.computers.edit',$computer->id)}}" class="btn btn-primary">Editar</a>
                <form action="{{ route('admin.media-manager.computers.destroy', $computer->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Deletar</button>
                </form>-->
                <form action="{{ route('admin.media-manager.computers.destroy', $computer->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger table-delete" type="submit">Deletar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@if (method_exists($computers,'onEachSide'))
    {{ $computers->onEachSide(10)->links() }}
@endif