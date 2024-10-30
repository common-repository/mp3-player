<?php
/**
* The functions file for for displaying the mp3 player
*
* @package mp3-player
*/



class view2{

    
    function getStylesheet() {
        
        return '/views/style2.css';

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

        $output.= '<div id="mp3PostId_'.$objMp3Model->getPostId().'"  class="simple-mp3-player-holder" >';

        $output .= '<div class="simple-mp3-player">';


        /* Loop through each attachment. */

        $i=0;
        
        $output.='<img src="'.$objMp3Model->imgResized(214,200).'" />';
        

        if(count( $objMp3Model->getItems())>0){
			
            foreach ( $objMp3Model->getItems() as $attachment ) {

                $title = $attachment->title();

                $output.= '<div class="mp3-item"  >';

                $filename=$id;

                $title;
                
                if($attachment->downloadUrl()){

                $output .='<a class="download" style="float:right;line-height:12px;" href="'.$attachment->downloadUrl().'" >&nbsp</a>';

                }
               
                $output .='<a class="playBtn" Id="track_'.$objMp3Model->getPostId().'_'.$i.'" onclick="return false" href="#" class="playBtn" >'.$attachment->title() .'</a>

                </div>';

                $i++;

            }  
        }

        $output .= $this->renderPlayer($objMp3Model->getPostId()).'
        </div>
        <div class="playerclear" ><!--empty commment--></div>
        </div>';


        return $output;
    }


    function renderPlayer($post_id){

        $player=' 
        <div id="playercontroller_'.$post_id.'" class="playercontroller">
        <div class="displayTrackName" >Ready</div>
        <div id="playerplay'.$post_id.'" class="button play "><a href="#" >PLAY</a></div>
        <div id="playerpause'.$post_id.'" class="button pause"><a href="#">PAUSE</a></div>
        <div id="playerstop'.$post_id.'" class="button stop"><a href="#">STOP</a></div>
        <div class="timeline"></div>
        <div class="carpe_horizontal_slider_track">
        <div class="carpe_slider_slit"></div>
        <div class="carpe_slider_slit2" id="sliderWidth'.$post_id.'"  ></div>
        <div class="timeline carpe_slider" id="slider'.$post_id.'" display="display1" style="left:0px"></div>
        </div>                   
        <span class="info_bytes" id="info_bytes'.$post_id.'">0%</span>
        </div>';  
        
        return $player;
    
    }
}
?>
