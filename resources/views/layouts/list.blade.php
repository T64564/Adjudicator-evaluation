<table class="table table-striped table-hover">
    <thead>
        <tr>
            @foreach ($heads as $head) 
                <th>
                    {{ $head }}
                </th>
            @endforeach
        </tr>
    </thead>
    @foreach ($values as $value)
        <tr>
            @foreach ($keys as $key)
                <td>
                    {{ $value->$key}}
                </td>
            @endforeach
            <td>
                {!! link_to("$controller/$value->id/edit", 'Edit', ['class' => 'btn btn-primary']) !!}
            </td>
            <td>
                {!! delete_form([$controller, $value->id]) !!}
            </td>
        </tr>
    @endforeach
</table>
