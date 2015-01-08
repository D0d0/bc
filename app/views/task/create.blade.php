@extends('layouts.center_content')

@section('js')
{{HTML::style('css/font-awesome.min.css')}}
{{HTML::style('css/summernote.css')}}
{{HTML::script('js/summernote.min.js')}}
{{HTML::script('js/ace/ace.js')}}
{{HTML::script('js/ace/ext-language_tools.js')}}
{{ HTML::style('css/datepicker.min.css') }}
{{ HTML::script('js/moment.min.js') }}
{{ HTML::script('js/datepicker.min.js') }}
{{ HTML::script('js/spin.min.js') }}
{{ HTML::script('js/ladda.min.js') }}
{{ HTML::style('css/ladda-themeless.min.css') }}
@stop

@section('style')
#editor{
height: 300px;
}
@stop

@section('ready_js')
$('.summernote').summernote({
    height: 300
}).code('{{ $article->text or '' }}');

var editor = ace.edit("editor");
    editor.setOptions({
    enableBasicAutocompletion: true
});

editor.setTheme("ace/theme/merbivore");
editor.getSession().setMode("ace/mode/c_cpp");
editor.$blockScrolling = Infinity;

$('#deadline').datetimepicker({
language: 'sk',
});

$('#start').datetimepicker({
    language: 'sk',
});

$('#save').on('click', function(e){
    e.preventDefault();
    var l = Ladda.create(this);
    l.start();
    $('#name').val('');
    $('#start').val('');
    $('#deadline').val('');
    editor.setValue('', -1);
    $('.summernote').summernote().code('');
    l.stop();
});
@stop

@section('center')
<div class="col-md-12">
    <form class="form-horizontal clearfix" role="form">
        {{ Form::hidden('id', isset($article) && isset($article->id) ? $article->id : '', array('id' => 'id'))}}
        <div class="form-group">
            <label for="name" class="col-md-1 control-label">{{ Lang::get('article.name') }}</label>
            <div class="col-md-11">
                <input type="text" id="name" placeholder="{{ Lang::get('article.name') }}" class="form-control" value="{{ $article->title or ''}}">
            </div>
        </div>
        <div class="form-group">
            <label for="start" class="col-md-1 control-label">{{ Lang::get('article.start') }}</label>
            <div class="col-md-11">
                <div class="input-group date">
                    <input type="text" class="form-control" placeholder=".Start" maxlength="10" id="start">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="deadline" class="col-md-1 control-label">{{ Lang::get('article.deadline') }}</label>
            <div class="col-md-11">
                <div class="input-group date">
                    <input type="text" class="form-control" placeholder=".Deadline" maxlength="10" id="deadline">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-1 control-label">{{ Lang::get('article.text') }}</label>
            <div class="col-md-11">
                <div class="summernote"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-1 control-label">{{ Lang::get('article.editor') }}</label>
            <div class="col-md-11">
                <div id="editor"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-1 col-md-offset-9">
                <button class="btn btn-primary ladda-button" id="save" data-style="zoom-in">
                    <span class="ladda-label">{{ Lang::get('article.save') }}</span>
                </button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-default" id="send">{{ Lang::get('article.send') }}</button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-default" id="trash">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>
            </div>
        </div>
    </form>
</div>
@stop