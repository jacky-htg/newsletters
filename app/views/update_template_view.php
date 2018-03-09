<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator,editor')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

if($_POST['data']){
    $data->saveTemplate(Core_Array::getGet('id_template'), $_POST['data']);
    header('Location:?t=edit_template&id_template='.Core_Array::getGet('id_template'));
}

$template = $data->getTemplate(Core_Array::getGet('id_template'));
?>
<div id="newsletter-container" style="display:flex; flex-direction: row;">
    <?php echo $template['body_temp'];?>
    
    <div class="aside" style="background:#efefef;flex:1; border:1px solid #fff;padding:1%;">
        <button id="done-editing"><i class="fas fa-check-square"></i> DONE</button>
        <button id="revert-editing" onclick="location.href='?t=update_template&id_template=<?php echo $template['id_template'];?>'"><i class="fas fa-undo"></i> REVERT</button>
        <h2>Add Element</h2>
        <div id="sortable1" class="connectedSortable">
            <p data-type="heading">HEADING</p>
            <p data-type="image">IMAGE</p>
            <p data-type="link-image">LINK IMAGE</p>
            <p data-type="link">LINK</p>
            <p data-type="textarea">RICH TEXT</p>
        </div>
    </div>
</div>

<div id="modal-form">
    <div>
        <a title="Close" class="modal-form-close"><i class="fa fa-window-close"></i> Close</a>
        <div id="form" style="background: #fff;"></div>
    </div>
</div>
<div style="display: none;" id="clone-element">
    <div class="editable" data-type="heading">
        <h3 style="border-bottom:5px solid #d22027; width:35%;">HEADING</h3>
    </div>
    
    <div class="editable" data-type="textarea">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc rutrum sed nisl sit amet facilisis. Integer vitae viverra justo. Vestibulum sit amet enim venenatis velit scelerisque sodales a molestie sem. Quisque nulla ipsum, fringilla quis leo non, bibendum sagittis massa. Integer et dictum massa. Vivamus ac efficitur lectus. Donec elementum, urna quis convallis malesuada, tortor sapien faucibus turpis, ut malesuada magna enim a mi. Maecenas sollicitudin nulla in odio ullamcorper congue. Morbi lacinia diam quis semper dapibus. Praesent at diam lacus.</p>
    </div>
    
    <p class="editable" data-type="link"><a href="" style="border:none;text-decoration:none;color:#333;font-weight:bold;">Potensi Radikalisme Siswa SMU</a></p>
    <p class="editable" data-type="link-image"><a href=""><img src="http://newsletter.independen.id/img/angka-partisipasi-jihad-siswa-smu.jpg" style="border:none; width:100%;" /></a></p>
    <p class="editable" data-type="image"><img src="http://newsletter.independen.id/img/angka-partisipasi-jihad-siswa-smu.jpg" style="border:none; width:100%;" /></p>
</div>
<style>
    #modal-form{
    position:fixed;
    background-color: rgba(0,0,0,0.8);
    top:0;
    right:0;
    bottom:0;
    left:0;
    z-index:1000;
    opacity:0;
    pointer-events:none;
    -webkit-transition:all 0.3s;
    -moz-transition:all 0.3s;
    transition:all 0.3s;
}
#modal-form>div {
    position:relative;
    padding:2rem;
    display: flex;
    justify-content: center;
    height: 100vh;
    align-items: center;
    flex-direction: column;
}
#modal-form .modal-form-close {
    color:#aaa;
    line-height:50px;
    font-size:100%;
    position:absolute;
    right:0;
    text-align:center;
    top:0;
    width:70px;
    text-decoration:none;
    cursor:pointer;
}
#modal-form .modal-form-close:hover {
    color:#000;
}

#modal-form #form {
    padding:2%;
    width: 600px;
    overflow-y: hidden;
}

#modal-form #form div {
    padding: 1%;
}

#modal-form #form textarea{
    width: 100%;
    height: 300px;
}

#modal-form #form input {
    width: 100%;
}

#modal-form #form button {
    padding: 1%;
}

#sortable1 p {
    border: 1px solid #aaa;
    padding:1%;
    background:lightgreen;
}

#done-editing, #revert-editing{
    border:1px solid #fff;
    padding:2%;
    color: #fff;
}

#done-editing {
    background:#444;
}

#revert-editing {
    background:darkred;
}

#revert-editing:hover {
    background:red;
}

#done-editing:hover{
    background:darkgreen;
}

</style>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="./templates/js/tinymce/tinymce.min.js"></script>  
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
<script>
    $(document).ready(function(){
        editable();
    });
    $('.modal-form-close').on('click', function(){
        $('#modal-form').css({'opacity': 0, 'pointer-events': 'none' });
    });
    
    function editElement(self) { 
        var parent = $(self).parent().parent();
        var html;
        $('.add-element').detach();
        $('.edit-element').detach();
        parent.css('border', "none");
        
        if('link' === parent.data('type')){
            html = editLink(parent);
        }
        else if('text' === parent.data('type')){
            html = editText(parent);
        }
        else if('textarea' === parent.data('type')){
            html = editTextarea(parent);
        }
        else if('image' === parent.data('type')){
            html = editImage(parent);
        }
        else if('link-image' === parent.data('type')){
            html = editLinkImage(parent);
        }
        else if('heading' === parent.data('type')){
            html = editHeading(parent);
        }
        
        openmodal(html, parent.data('type'));        
    }
    
    function openmodal(html, type){
        $('#modal-form').find('div#form').html(html);
        if('textarea' === type) {
            tinymce.init({ 
                selector:'textarea',
                menubar: false,
                toolbar: 'styleselect | bold italic | link image',
                plugins : 'advlist autolink link image lists charmap print preview'
            });
        }
        $('#modal-form').css({'opacity': 100, 'pointer-events': 'auto'});
        $('#modal-form').find('button.modal').click(function(){
            var id = $(this).data('id'); 
            var form = $(this).parent().parent();
            if ('text' === type) {
                $('#'+id).html(form.find('input').val());
            }
            else if ('link' === type){
                $('#'+id).find('a').attr('href', form.find('.url').val()).html(form.find('.text').val());
            }
            else if ('link-image' === type){
                $('#'+id).find('a').attr('href', form.find('.url').val()).find('img').attr('src', form.find('.image').val());
            }
            else if ('image' === type){
                $('#'+id).find('img').attr('src', form.find('input').val());
            }
            else if ('textarea' === type) {
                $('#'+id).html(tinyMCE.activeEditor.getContent());
            }
            else if ('heading' === type) {
                $('#'+id).find('h3').html(form.find('input').val());
            }
            
            $('#modal-form').css({'opacity': 0, 'pointer-events': 'none' });
        });
    }
    
    function editLink(obj){
        return "<h2>Edit Link</h2><div><label>text</label><input class='text' value='"+obj.find('a').html()+"'></div><div><label>Link</label><input class='url' type='url' value='"+obj.find('a').attr('href')+"'></div><div><button class='modal' data-id='"+obj.attr('id')+"'>Update</button></div>";
    }
    
    function editLinkImage(obj){
        return "<h2>Edit Link Image</h2><div><label>Image</label><input class='image' value='"+obj.find('img').attr('src')+"'></div><div><label>Link</label><input class='url' type='url' value='"+obj.find('a').attr('href')+"'></div><div><button class='modal' data-id='"+obj.attr('id')+"'>Update</button></div>";
    }
    
    function editText(obj){
        return "<h2>Edit Text</h2><div><input value='"+obj.html()+"'></div><div><button class='modal' data-id='"+obj.attr('id')+"'>Update</button></div>";
    }
    
    function editTextarea(obj){
        return "<h2>Edit Rich Text</h2><div><textarea>"+obj.html()+"</textarea></div><div><button class='modal' data-id='"+obj.attr('id')+"'>Update</button></div>";
    }
    
    function editImage(obj){
        return "<h2>Edit Image</h2><div><input type='url' value='"+obj.find('img').attr('src')+"'></div><div><button class='modal' data-id='"+obj.attr('id')+"'>Update</button></div>";
    }
    
    function editHeading(obj){
        return "<h2>Edit Heading</h2><div><input value='"+obj.find('h3').html()+"'></div><div><button class='modal' data-id='"+obj.attr('id')+"'>Update</button></div>";
    }
    
    function deleteElement(obj){
        $(obj).parent().parent().detach();
    }
    
    function editable () {
        $('.editable').mouseenter(function(){
            $(this).find('.edit-element').detach();
            $(this).css('border', "1px dashed #aaa");
            var html = "<p class='edit-element' style='text-align:right;'><span onclick='deleteElement(this)'><i class='far fa-minus-square'></i></span> | <span onclick='editElement(this)'><i class='far fa-edit'></i></span></p>";
            if ('edit-only' === $(this).data('mode')) {
                html = "<p class='edit-element' style='text-align:right;'><span onclick='editElement(this)'><i class='far fa-edit'></i></span></p>";        
            }
            $(this).prepend(html);
        }).mouseleave(function(){
            $(this).css('border', "none");
            $('.edit-element').detach();
        });
    }
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function guid() {
        function s4() {
          return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    }

    $( function() {
        $("#sortable1").sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();

        $("#sortable2").sortable({
            connectWith: ".connectedSortable",
            receive : function (event, ui) {
                ui.item.clone().appendTo('#sortable1');
                var html = $('#clone-element').find('.editable[data-type="'+$(ui.item).data('type')+'"]').clone();
                html.attr('id', guid());
                $(ui.item).replaceWith(html);
                editable();
            }
        }).disableSelection();
    });
    
    $('#done-editing').click(function(){
        var newsletter = $('#newsletter-container').clone();
        $(newsletter).find('.aside').detach();
        $.post("?t=update_template&id_template=<?php echo $template['id_template'];?>", {data:$(newsletter).html()})
                .always(function(){
             location.href="?t=edit_template&id_template=<?php echo $template['id_template'];?>";
         });
    });
</script>