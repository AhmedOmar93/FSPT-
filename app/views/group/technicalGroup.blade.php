@extends('layout.mainTheme')

@section('header')
    <style type="text/css">
        textarea{
            resize:none;
        //    min-height: 100px;
        }
        .box-footer{min-height: 50px;}

    </style>
@stop

@section('content')


    <div class='col-md-8'>

        <div class="box box-{{ProfileController::get_class()}} box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Add New Group</h3>
            </div><!-- /.box-header -->
            <div class='box-body'>

                <form action="{{  URL::route('technical-group-create-post') }}" method="post" id="createTGroup" enctype="multipart/form-data">

                    <div class="form-group">
                        <span class="input-group-addon1">Group Name</span>
                        <input type="text" class="form-control" name="name" id="name" {{ (Input::old('name'))?'value = "'.e(Input::old('name')).'"':'' }}>
                        @if($errors->has('name'))
                            {{ $errors->first('name') }}
                        @endif

                    </div>
                    <div class="form-group">
                        <span class="input-group-addon1">Group Description</span>
            <textarea type="text" class="form-control" name="group_des" id="group_des" {{ (Input::old('group_des'))?'value = "'.e(Input::old('group_des')).'"':'' }}>
            </textarea>
                        @if($errors->has('group_des'))
                            {{ $errors->first('group_des') }}
                        @endif
                    </div>

                    <div class="form-group">
                        <span class="input-group-addon1">Group Image</span>
                        <input type="file" class='form-control' name="image" id="image" >
                    </div>
                    <div class="box-footer">
                        <input class='btn btn-{{ProfileController::get_class()}} pull-right'  type="submit" value="Add New Group">
                    </div>
                    {{ Form::token() }}
                </form>


            </div>
        </div>
    </div>
    {{ HTML::script('assests//group//employeeGroup.js') }}


@endsection
@stop