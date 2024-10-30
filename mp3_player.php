<?php

/* 
@package mp3-player
*/ 

/*
Plugin Name: Mp3 Player
Plugin URI: http://simonhans.dk
Description: Renders an mp3 player from mp3-files in gallery
Version: 1.2
Author: Simon Hansen
Author URI: http://simonhans.dk
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/





add_shortcode('mp3','mp3_player_handler');

//hvilke views er installeret
$rgMp3playerViews=array('Default'=>'view1','Slim'=>'view2','Small cover'=>'view3');

#if(!is_admin()){ //only include for frontend ---TODO sims skal checkes, er blot disabled for at virke med WP 5
    require_once( dirname (__FILE__) . '/SimpleImage.php' );
    require_once( dirname (__FILE__) . '/mp3_player_model.php' );
    require_once( dirname (__FILE__) . '/views/view1.php' );
    require_once( dirname (__FILE__) . '/views/view2.php' );        
    require_once( dirname (__FILE__) . '/views/view3.php' );
    require_once( dirname (__FILE__) . '/simple_mp3_player.php' );
    require_once( dirname (__FILE__) . '/mp3-player-item.php' );
    require_once( dirname (__FILE__) . '/mp3_player_repository.php' );
    $settings = get_option( 'mp3_player_settings' );
    $mp3playerView=$settings['mp3_player_view'];
    //echo $mp3playerView;    
    if(!in_array($mp3playerView,$rgMp3playerViews)  ){
        $mp3playerView=$rgMp3playerViews['Default']; 
    }  

    $view=new $mp3playerView();
    $objmp3=new simple_mp3_player($view);//enqueue script : nødvendigt her pga. javscript
    add_action( 'template_redirect', 'mp3_player_download' );

#}

//include scripts for backend
if(is_Admin()){
    $plugin_directory = dirname(plugin_basename(__FILE__)); 
    require_once(  dirname(__FILE__). '/admin.php' );

}

function mp3_player_handler($attr){
    global $objmp3;
    global $view;
    $objmp3=new simple_mp3_player($view);//enqueue script
    add_action( 'template_redirect', 'mp3_player_download' );
    return $objmp3->controller($attr,$view); 
}




/**
* download script. 
* 
*/

function mp3_player_download(){
    $fileId=$_GET['mp3PlayerFile'];  //id of attachement

    // echo $fileId;exit; 

    if($fileId){
        if(!is_numeric($fileId)){
            exit();
        }

        $fullPath =get_attached_file( $fileId );

        //echo  $fullPath; exit;

        if ($fd = fopen ($fullPath, "r")) {
            $fsize = filesize($fullPath);
            //      header("Content-type: application/octet-stream");
            header("Content-type: audio/mp3");
            header("Content-Disposition: filename=\"".$_GET["title"]."\"");
            header("Content-length: $fsize");
            while(!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose ($fd);
     


        exit;

    }
}
?>
