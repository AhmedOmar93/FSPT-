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
                      <h3 class="box-title">Add New Course</h3>
                    </div><!-- /.box-header -->
                <div class='box-body'>

    <form action="{{  URL::route('group-create-post') }}" method="post" id="createGroup" enctype="multipart/form-data">
        
        <div class="form-group">
            <span class="input-group-addon1">Course Name</span>
            <input type="text" class="form-control" name="name" id="name" {{ (Input::old('name'))?'value = "'.e(Input::old('name')).'"':'' }}>
            @if($errors->has('name'))
                {{ $errors->first('name') }}
            @endif

        </div>
        <div class="form-group">
            <span class="input-group-addon1">Course Description</span>
            <textarea type="text" class="form-control" name="group_des" id="group_des" {{ (Input::old('group_des'))?'value = "'.e(Input::old('group_des')).'"':'' }}>
            </textarea>
            @if($errors->has('group_des'))
                {{ $errors->first('group_des') }}
            @endif
        </div>


        

        <div class="form-group">
            <span class="input-group-addon1">Course Syllable</span>
            <textarea type="text"  class='form-control' name="group_Syllable" id="group_Syllable" {{ (Input::old('group_Syllable'))?'value = "'.e(Input::old('group_Syllable')).'"':'' }}>
            </textarea>
            @if($errors->has('group_Syllable'))
                {{ $errors->first('group_Syllable') }}
            @endif
        </div>

        <div class="form-group">
           <span class="input-group-addon1">Grade Police</span>
           <textarea type="text" class='form-control' name="group_police" id="group_police" {{ (Input::old('group_police'))?'value = "'.e(Input::old('group_police')).'"':'' }}>
           </textarea>
            @if($errors->has('group_police'))
                {{ $errors->first('group_police') }}
            @endif
        </div>


        <div class="form-group">
           <span class="input-group-addon1">Expire Date</span>
           <input type="date" class='form-control' name="expire_date" id="expire_date" >
        </div>

        <div class="form-group">
           <span class="input-group-addon1">Course Image</span>
           <input type="file" class='form-control' name="image" id="image" >
        </div>
        <div class="box-footer">
            <input class='btn btn-{{ProfileController::get_class()}} pull-right'  type="submit" value="Add New Course">
        </div>

        {{ Form::token() }}
    </form>
                    {{ HTML::script('assests//group//createGroup.js') }}
</div>
    </div>
@endsection
@stop