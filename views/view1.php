<?php
/**
* The functions file for for displaying the mp3 player
*
* @package mp3-player
*/


class view1{


    function getStylesheet() {

        return '/views/style.css';
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

                //$javascript.="playlist[".$post_id."][".$i."]='".$_SERVER['PHP_SELF'].'/?mp3PlayerFile='.$id.'&title='.$title ."';\n";

                $i++;   

            }
        }

        $javascript.='</script>';
        
        $output=$javascript;

        //using the first gallery-image as cover
        $imageCover =$objMp3Model->imgResized(300,300);

        $output.= '<div id="mp3PostId_'.$objMp3Model->getPostId().'"  class="simple-mp3-player-holder" >';
        
        $output.= '<div><img  src="'.$imageCover.'" /><div class="clear-responsive" ></div></div>';
        
        $output .= '<div class="simple-mp3-player">';


        /* Loop through each attachment. */

        $i=0;

        if(count( $objMp3Model->getItems())>0){
            foreach ( $objMp3Model->getItems() as $attachment ) {
                $r++;
                if($r < 8){

                    $class=' hidePage1 ';
                }else{
                    $class=' hidePage2 ';

                    $page='<a class="nextPage" id="nextPage_'.$objMp3Model->getPostId().'"> << More >>..</a>';
                }

                $output .= '<div class="mp3-item '.$class.' "  >';
               

                if($attachment->downloadUrl()){

                    $output .='<a class="download" style="float:right;line-height:12px;" href="'.$attachment->downloadUrl().'" >&nbsp</a>';
                }
                $output .='<a class="playBtn" Id="track_'.$objMp3Model->getPostId().'_'.$i.'" onclick="return false" href="#" class="playBtn" >'.$attachment->title() .'</a>

                </div>';
                $i++;
            }  
        }
        
        if($r>5){
        
            $output.=$page;
        }

        $output .='</div>';

        $output .='<div class="playerholder" >';

        $output .= $this->renderPlayer($objMp3Model->getPostId());

        $output .='</div><div class="playerclear" ><!--empty commment--></div>
        </div>';


        return $output;
    }


    function renderPlayer($post_id){

        $player='
        <div id="playercontroller_'.$post_id.'" class="playercontroller">
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
