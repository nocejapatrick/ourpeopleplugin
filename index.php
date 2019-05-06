<?php 
// Plugin Name: Our People Editor
// Plugin URI: www.sample.com
// Description: To edit the Our People Section
// Author: Patrick Neil E. Noceja
// Author URI: www.nocejapatrick.com

defined('ABSPATH') or die('Hey, what are you doing here?');

class OurPeople {

    function __construct(){
        add_action('admin_menu',[$this,'create_page']);
        add_action('wp_ajax_my_action',[$this,'my_action']);
        add_action('wp_ajax_update_pic',[$this,'update_pic']);
        add_action('wp_ajax_delete_person',[$this,'delete_person']);
        add_action('wp_ajax_insert_person',[$this,'insert_person']);
    }

    function activate(){
        // echo "QWEQW";
        $this->creating_table();
    }

    function deactivate(){
        
    }

    function uninstall(){
        
    }

    function creating_table(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'ourpeople';
	
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            img_url text NOT NULL,
            position text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    function my_page(){
        $success = '';
        global $wpdb;
        if(isset($_POST['submit'])){
            $imgPath = $_POST['my-img-path'];
            $position = $_POST['position'];
            $imgName = explode('.',$imgPath)[0];
            $imgExt = explode('.',$imgPath)[1];
            $imgName = $imgName.'-150x150.';
            $imgPath2 = $imgName.$imgExt;
            $wpdb->insert(
                'wp_ourpeople',
                array(
                    'img_url'=>$imgPath2,
                    'position'=>$position
                )
            );
            $success = 'Upload Successfully';
        }
        $peopleResult = $wpdb->get_results("SELECT * FROM wp_ourpeople",OBJECT);
        
        ?>
        <style>
            h1{
                font-size: 42.7px;
                color:#ed1c24;
                font-weight: bold;
                font-style: normal;
                font-stretch: normal;
                line-height: normal;
                letter-spacing: normal;
            }
            .row{
                display: flex;
            }
            .person{
                margin-top: 40px;
                text-align:center;
                width:150px;
            }

            .person-pic{
                width: 100px;
                height: 100px;
                border-radius: 100%;
                background: black;
                margin:auto;
                cursor:pointer;
                border: solid 1px #979797;
            }

            .person-title{
                font-size: 17.5px;
                font-weight: bold;
                color: #333333;
                margin-top: 20px;
                max-width:100%;
                word-wrap: break-word;
            }
            img{
                width:100%;
            }
            .img-holder{
                width: 200px;
                height: 230px;
                border-radius: 100%;
                border: solid 1px #979797;
            }
            input[type=text]{
                width:100%; 
                background:#d7d7d7;
                padding:8px 16px;
                border-radius:50px;
            }
            input[type=text]::placeholder{
                color:white;
            }
            .justify-content-between{
                justify-content: space-between;
            }
            .flex-wrap{
                flex-wrap:wrap;
            }
            .align-items-center{
                align-items:center;
            }
            .position{
                margin-top:20px;
            }
            .form-container{
                margin-left:40px;
            }
            form{
                width:800px;
                margin:auto;
            }
            *{
                box-sizing:border-box;
            }
            button{
                margin-top:20px;
            }
            .mt-20{
                margin-top:20px;
            }
            .delete{
                justify-content:flex-end; 
                cursor:pointer;
                font-weight:bold;
            }
            .add-person{
                position:relative;
                border:4px dashed #b3b3b3;
                cursor:pointer;
            }
            .gray-circle{
                content:'';
                position:absolute;
                top:45%;
                left:50%;
                transform:translate(-50%,-50%);
                width:85px;
                height:85px;
                border-radius:100%;
                background:#b3b3b3;
            }
            .vertical{
                position:absolute;
                top:45%;
                left:50%;
                transform:translate(-50%,-50%);
                width:10px;
                height:60px;
                background:#f1f1f1;
            }
            .horizontal{
                position:absolute;
                top:45%;
                left:50%;
                transform:translate(-50%,-50%);
                width:53px;
                height:10px;
                background:#f1f1f1;
            }
            .add-person-description{
                position:absolute;
                top:80%;
                left:50%;
                transform:translate(-50%,-50%);
            }
        </style>
        <h1>Our People Editor</h1>
        <!-- <form action="">
        <input type="button" id="upload_image_button" value="Upload Image">
        <input type="text" name="profileimage" id="profileimagetxt" class="regular-text" /><br />
            <button type="submit">Submit</button>
        </form> -->
        <!-- <form action=" <?php echo get_site_url().'/wp-admin/admin.php?page=our-people';?>" method="post">
            <div class="upload row align-items-center">
                <div class="img-holder" style="flex-grow:1">
                    <img src="http://localhost/03_cms/wp-content/uploads/2019/05/Pad-Thai.jpg" alt="">
                </div>
                <div class="form-container" style="flex-grow:12"> 
                    <div class="row">
                        <button type="submit" class="upload_image_button button">Upload</button>
                        <input type="text" class="my-img-path" name="my-img-path" placeholder="Upload" autocomplete = "off">
                    </div>
                    <div class="row">
                        <input type="text" class="position" name="position" placeholder="Position" autocomplete = "off">
                    </div>
                    <div class="row">
                         <button type="submit" name="submit">Submit</button>
                    </div>
                </div>
            </div> -->
        </form>
        <div style="max-width:686px; margin:auto;">
            <div class="row justify-content-between flex-wrap">
                 <div class="person add-person">
                     <div class="gray-circle"></div>
                     <div class="vertical"></div>
                     <div class="horizontal"></div>
                     <div class="add-person-description">Add Person</div>
                 </div>
                <?php foreach($peopleResult as $people => $value){ ?>
                <div class="person">
                    <div class="row delete" data-id= "<?php echo $value->id; ?>">X</div>
                    <div class="person-pic" data-id= "<?php echo $value->id; ?>" style="background: url('<?php echo $value->img_url;?>') no-repeat center center; background-size:cover;"></div>
                    <input type="text" class="my-inputs mt-20" data-id= "<?php echo $value->id; ?>" value="<?php echo $value->position;?>">
                </div>
                <?php } ?>
            </div>
        </div>
        <script>
            jQuery(window).ready(function($){
                $('.my-img-path').click(function() {
                    var send_attachment_bkp = wp.media.editor.send.attachment;
                    var button = $(this);
                    wp.media.editor.send.attachment = function(props, attachment) {
                        wp.media.editor.send.attachment = send_attachment_bkp;
                        $('.img-holder').css({'background-image':'url(' + attachment.url + ')','background-repeat':'no-repeat','background-size':'cover','background-position':'center center'});
                        $(button).val(attachment.url);
                        // $(button).prev().val(attachment.id);
                        // wp.media.editor.send.attachment = send_attachment_bkp;
                    }
                    wp.media.editor.open(button);
                    return false;
                });

                $('.person-pic').on('click',function(e) {
                    e.stopPropagation();
                    var send_attachment_bkp = wp.media.editor.send.attachment;
                    var button = $(this);
                    var data = {
                        'action':'update_pic',
                        id:$(this).attr('data-id'),
                    };
                    wp.media.editor.send.attachment = function(props, attachment) {
                        wp.media.editor.send.attachment = send_attachment_bkp;
                        $(button).css({'background-image':'url(' + attachment.url + ')','background-repeat':'no-repeat','background-size':'cover','background-position':'center center'});
                        data.inputData = attachment.url;
                        $.post(ajaxurl,data,function(response){
                          console.log(response);
                        });
                    }
                    wp.media.editor.open(button);
                    return false;
                });

               

                $('.my-inputs').on('blur',function(e){

                    var data = {
                        'action':'my_action',
                        id:$(this).attr('data-id'),
                        inputData: $(this).val()
                    };

                    $.post(ajaxurl,data,function(response){
                     
                    });
                });

                // <div class="person-pic" data-id= "<?php echo $value->id; ?>" style="background: url('<?php echo $value->img_url;?>') no-repeat center center; background-size:cover;"></div>
                // <input type="text" class="my-inputs mt-20" data-id= "<?php echo $value->id; ?>" value="<?php echo $value->position;?>">

                $('.add-person').click(function(){
                    $(this).html('');
                    $(this).css('border','none');
                    var dele = $("<div></div").addClass('row').addClass('delete').text('X');
                    var personpic = $("<div></div").addClass('person-pic').css('background','black').attr('data-url','');
                    var position = $("<input>").attr('type','text').attr('placeholder','Position').addClass('my-inputs').addClass('mt-20').click(function(e){e.stopPropagation();}).blur(function(e){
                        var data = {
                            action:'insert_person',
                            img_url:$($(this).siblings()[1]).attr('data-url'),
                            inputData:$(this).val()
                        };
                        $.post(ajaxurl,data,function(response){
                            // console.log(response);
                            location.reload();
                        });
                    });
                    $(this).append(dele);
                    $(this).append(personpic);
                    $(this).append(position);

                    var send_attachment_bkp = wp.media.editor.send.attachment;
                    var button = $(this);
                    wp.media.editor.send.attachment = function(props, attachment) {
                        wp.media.editor.send.attachment = send_attachment_bkp;
                        $(button).find('.person-pic').attr('data-url',attachment.url).css({'background-image':'url(' + attachment.url + ')','background-repeat':'no-repeat','background-size':'cover','background-position':'center center'});
                        $(button).find('.my-inputs').focus();
                    }
                    wp.media.editor.open(button);
                    return false;
                });

                $('.delete').click(function(){
                    $(this).parent().remove();
                    var data = {
                        'action':'delete_person',
                        id:$(this).attr('data-id'),
                    };
                    $.post(ajaxurl,data,function(response){
                        console.log(response);
                    });
                });
            });
        </script>
        <?php
    }

    function create_page(){
        add_menu_page('Our People Editor', 'Our People Editor', 'manage_options', 'our-people', [$this,'my_page']);
    }

    function my_action(){
        global $wpdb;
        $id = $_REQUEST['id'];
        $data = $_REQUEST['inputData'];
        $wpdb->query("UPDATE wp_ourpeople SET position = '$data' where id = '$id'");
        wp_die();
    }

    function update_pic(){
        global $wpdb;
        $id = $_REQUEST['id'];
        $imgPath = $_REQUEST['inputData'];
        $imgName = explode('.',$imgPath)[0];
        $imgExt = explode('.',$imgPath)[1];
        $imgName = $imgName.'-150x150.';
        $imgPath2 = $imgName.$imgExt;
        $wpdb->query("UPDATE wp_ourpeople SET img_url = '$imgPath2' where id = '$id'");
        wp_die();
    }

    function delete_person(){
        global $wpdb;
        $id = $_REQUEST['id'];
        $wpdb->query("DELETE FROM wp_ourpeople where id = '$id'");
        wp_die();
    }

    function insert_person(){
        global $wpdb;
        $imgPath = $_REQUEST['img_url'];
        $position = $_REQUEST['inputData'];
        $imgName = explode('.',$imgPath)[0];
        $imgExt = explode('.',$imgPath)[1];
        $imgName = $imgName.'-150x150.';
        $imgPath2 = $imgName.$imgExt;
        $wpdb->insert(
            'wp_ourpeople',
            array(
                'img_url'=>$imgPath2,
                'position'=>$position
            )
        );
        wp_die();
    }
}

if(class_exists('OurPeople')){
    $ourpeople = new OurPeople();
}

register_activation_hook(__FILE__,array($ourpeople,'activate'));
register_deactivation_hook(__FILE__,array($ourpeople,'deactivate'));

?>