<?php

/**

* The functions file for for displaying the mp3 player

*

* @package mp3-player

*/



class view3{


    function getStylesheet() {

        return '/views/style3.css';

    }


    function render($objMp3Model){

        global  $countGlobal ;

        // generating the js playlist

        if($countGlobal<1){

            $p='var playlist=new Array();';

        }

        $countGlobal++;

        $javascript='<script type="text/javascript">
        '.$p.'  
        playlist['.$objMp3Model->getPostId().']=new Array();';

        $i=0;

        if(count($objMp3Model->getItems())>0){

            foreach ( $objMp3Model->getItems() as $attachment ) {

                $javascript.='playlist['.$objMp3Model->getPostId().']['.$i.']="'.$attachment->streamUrl() .'";'."\n";

                $i++;   

            }

        }

        $javascript.='</script>';

        $output=$javascript;

        //using the first gallery-image as cover

        $output.='<div  class="simple-mp3-player-holder" >';

        $output .= '<div class="simple-mp3-player">';

        /* Loop through each attachment. */

        $i=0;

        $img=$objMp3Model->imgResized(104,104);

        if(count( $objMp3Model->getItems())>0){

            foreach ( $objMp3Model->getItems() as $attachment ) {

                $output .= '<div class="mp3-item" >';

                $output .= '<div >';

                $output.='<h3>'.$attachment->getUsername().'</h3>';

                $output.='<a href="'.$attachment->getSoundcloudPermalink().'">on soundcloud</a>';

                if(intval($attachment->getArtwork())){

                    $output.='<img style="padding:2px" src="'.$attachment->getArtwork().'" >';

                }else{

                    $output.='<img style="padding:2px" src="'.$img.'" >';

                }

                $output .='<div>';

                // $output .'<a class="download" style="float:right;line-height:12px;" href="'.$attachment->downloadUrl().'" >&nbsp</a> ';

                $output .='<a class="playBtn" Id="track_'.$objMp3Model->getPostId().'_'.$i.'" onclick="return false" href="#" class="playBtn" >PLAY</a>'.$attachment->title() .'

                </div>';

                $i++;

                $output .= '</div>';

                $output .= '</div>';

            }  

        }


        $output .= $this->renderPlayer($objMp3Model->getPostId()).'

        </div>    

        <div class="playerclear" ><!--empty commment--></div>

        </div>';


        return $output;

    }


    function renderPlayer($post_id){

        $player='<div style="display:none;" id="playercontroller_'.$post_id.'" class="playercontroller">

        <div id="playerplay'.$post_id.'" class="button play "><a href="#" >PLAY</a>

		</div>

        <div id="playerpause'.$post_id.'" class="button pause"><a href="#">PAUSE</a></div>

        <div id="playerstop'.$post_id.'" class="button stop"><a href="#">STOP</a></div>

        <div class="timeline"></div>

        <div class="carpe_horizontal_slider_track">

        <div class="carpe_slider_slit"></div>

        <div class="timeline carpe_slider" id="slider'.$post_id.'" display="display1" style="left:0px"></div>

        </div>                   

        <span class="info_bytes" id="info_bytes'.$post_id.'">loaded</span>

        </div>';  

        

        return $player;

    
    }

}

?>
