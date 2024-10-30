<?php
class mp3_player_model {

    function getItems(){

        return $this->objItems;
    }


    function getImageForCover(){

        return $this->img;
    }


    function getPostId(){

        return $this->postId;
    }


    function getTitle(){

        return $this->title;
    }


    function setItems($Obj){
        $this->objItems=$Obj;
    }


    function setImageForCover($img){

        $this->img=$img;
    }


    function setPostId($postId){

        $this->postId=$postId;
    }


    function setTitle($title){

        $this->title=$title;
    }


    function imgResized($width,$height){

        $children = array(
            'post_parent' => $this->getPostId(),
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            //'post_mime_type' => 'image',
            //'post_mime_type' => 'audio',
            'post_mime_type' => '',
            'order' => 'ASC',
            'orderby' => 'menu_order ID'
            );

        /* Get image attachments. If none, return. */
        $attachments = get_children( $children );

        $img_src='';

        if(count($attachments)>0){
            
            foreach ( $attachments as $id => $attachment ){
            
                //image for cover
                if($attachment->post_mime_type=='image/jpeg' || $attachment->post_mime_type=='image/png' ){    
            
                    $img_src = get_attached_file( $id);

                }
            }
        }

        $cover= new SimpleImage();

        $uploadDir=wp_upload_dir();

        $dir=$uploadDir['basedir'].'/mp3-player-cover/';

        if(!is_dir($dir)){

            mkdir($dir);

        }

        $cover->cacheDir=$dir;
        
        $cover->cacheDirUrl=$uploadDir['baseurl'] .'/mp3-player-cover/';
        
        $image=$cover->get($img_src,$width,$height); //300

        return  $image;

    }

}
?>
