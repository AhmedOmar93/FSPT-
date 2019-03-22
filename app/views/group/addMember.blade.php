@extends('layout.main')

@section('content')


    <script>
        var ext = $('#image').val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
            alert('invalid extension!');
        }
    </script>

    <form action="{{  URL::route('groups-add-member-post') }}" method="post" id="addMember" enctype="multipart/form-data">

        <div class="panel">
            group Code : <input type="text" name="code" id="code" >
        </div>

        <input type="submit" value="Add Member">

        {{ Form::token() }}
    </form>
@endsection
@stop