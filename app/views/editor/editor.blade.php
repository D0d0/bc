@extends('layouts.center_content')

@section('style')
#editor{
height: 300px;
}

.panel{
    margin-top: 1em;
}
@stop

@section('center')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Editor</h3>
    </div>
    <div class="panel-body">
        <div id="editor"></div>
        <div>
            <input type="button" id="toggle" value="Toggle">
        </div>
    </div>
</div>
<div id="container">
    <p>
        <input type="text" id="input" placeholder="Shout something&hellip;"/>
        <input type="button" id="shout" value="shout"/>
    <p>
    <ul id="shouts" class="content">
    </ul>
</div>
<script src="js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="js/ace/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<script src="js/bcsocket-uncompressed.js"></script>
<script src="js/share.uncompressed.js"></script>
<script src="js/ace_c.js"></script>
<script>
    var randomDocName = function (length) {
        var chars, x;
        if (length == null) {
            length = 32;
        }
        chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=";
        var name = [];
        for (x = 0; x < length; x++) {
            name.push(chars[Math.floor(Math.random() * chars.length)]);
        }
        return name.join('');
    };

    var editor = ace.edit("editor");
    editor.setOptions({
        enableBasicAutocompletion: true
    });

    editor.setTheme("ace/theme/merbivore");
    editor.getSession().setMode("ace/mode/javascript");
    editor.$blockScrolling = Infinity;
    var docName = null;
    if (document.location.hash) {
        docName = document.location.hash.slice(1);
    } else {
        docName = randomDocName();
    }
    console.log(docName);
    sharejs.open("code:" + docName, 'text', 'http://62.169.176.249:8000/channel', function (error, doc) {
        doc.attach_ace(editor);
    });

    sharejs.open("shout:" + docName, 'text', 'http://62.169.176.249:8000/channel', function (error, doc) {
        function addShout(txt) {
            var li = $('<li />').html(txt)
            $('#shouts').append(li);
        }

        function shoutOut() {
            if(!$.trim($('#input').val())){
                return false;
            }
            var s = $.trim($('#input').val());
            $('#input').val('');
            doc.shout(s);
            addShout('You shouted "' + s + '"');
        }

        $('#shout').on('click', function () {
            shoutOut()
        });

        $('#input').keyup(function (e) {
            if (e.keyCode == 13) {
                shoutOut();
            }
        });

        doc.on('shout', function (msg) {
            addShout('You hear "' + msg + '"');
        });

        addShout('Connected');
    });

    $('#toggle').on('click', function () {
        editor.setReadOnly(!editor.getReadOnly());
        if (editor.getReadOnly()) {
            $(editor.container).append($('<div />').css({
                'position': 'absolute',
                'top': 0,
                'bottom': 0,
                'left': 0,
                'right': 0,
                'background': 'rgba(150,150,150,0.5)',
                'z-index': 100
            }).attr('id', 'cover'));
        } else {
            $('#cover').remove();
        }
    });
</script>
@stop