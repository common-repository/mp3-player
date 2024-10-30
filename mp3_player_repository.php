<?php
class mp3_player_repository{




    function getAttachmentsFromGallery($id){
        /* Arguments for get_children(). */
        $children = array(
            'post_parent' => $id,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            //'post_mime_type' => 'image',
            //'post_mime_type' => 'audio',
            'post_mime_type' => '',
            'order' => 'ASC',
            'orderby' => 'menu_order ID',
        );


        /* Get image attachments. If none, return. */
        $attachments = get_children( $children );

        $img_src='';
        if(count($attachments)>0){
            foreach ( $attachments as $id => $attachment ){
                if($attachment->post_mime_type=='audio/mpeg'){

                    $itemObj= new mp3_player_item();
                    $itemObj->setTitle(esc_attr( $attachment->post_title ));
                    $itemObj->setStreamUrl(wp_get_attachment_url( $id ));
                    $itemObj->setDownloadUrl($_SERVER['PHP_SELF'].'/?mp3PlayerFile='.$id.'&title='.esc_attr( $attachment->post_title ));
                    

                    $this->attachments[]=$itemObj; 
                }
            }

      


        }
      
        return $this->attachments;
    }



    /**
    * put your comment there...
    * 
    */
    function scGetData($sc,$client_id){

        //echo $client_id;
        $request='http://api.soundcloud.com/playlists/'.$sc.'.json?client_id='.$client_id;
        $uploadDir=wp_upload_dir();

        $dir=$uploadDir['path'].'/mp3-player-soundcloud-cache';

        $playlistDir=$dir.'/'.md5($request);

        if(!is_dir($dir)){
            mkdir($dir);
        }

        if(!is_dir($playlistDir)){
            mkdir($playlistDir);
        }


        // Open a known directory, and proceed to read its contents
        if (is_dir($playlistDir)) {
            if ($dh = opendir($playlistDir)) {
                while (($file = readdir($dh )) !== false) {
                    if($file !='.' and $file !='..' ){
                        if(time()>(intval($file)+300)){

                            unlink($playlistDir.'/'.$file);
                        }else{
                            $gotCache=file_get_contents($playlistDir.'/'.$file);
                            break;
                        }
                    }
                }
                closedir($dh);
            }
        }


        if(!$gotCache){
            $gotNew=@file_get_contents($request);

            $data=$gotNew;
            file_put_contents ( $playlistDir.'/'.time(),$gotNew);
        }else{
            $data=$gotCache;

        }

        return $data;

    }



    function getAttachmentsFromSoundcloud($sc){
        if($sc!=''){ //$sc soundcloud 

            $settings = get_option( 'mp3_player_settings' );
            $client_id=$settings['mp3_player_soundcloud_CLIENT_ID'];

            $data=$this->scGetData($sc,$client_id);
            $s=json_decode( $data);

            //var_dump($s->tracks);  
            if(is_array($s->tracks)){
                foreach($s->tracks as $track){

                    $itemObj= new mp3_player_item();
                    $itemObj->setTitle($track->title);
                    $itemObj->setStreamUrl($track->stream_url.'?client_id='.$client_id);

                    if($track->download_url){
                        $itemObj->setDownloadUrl($track->download_url.'?client_id='.$client_id);
                    }
                    $itemObj->setUsername($track->user->username);
                    $itemObj->setSoundcloudPermalink($track->permalink_url);


                    $itemObj->setArtwork($track->user->avatar_url);

                    // $itemObj->setArtwork($track->artwork_url);

                    $this->attachments[]=$itemObj; 



                }

            }
        }

        return $this->attachments;
    }



}
?>