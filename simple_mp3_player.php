<?php
/**
* Simple_mp3_player
* Controller for the views
*
* @package mp3-player
*/


$countGlobal=0;


class simple_mp3_player{

    private $imageTag;

    function javascript_and_css(){   


        echo '<script type="text/javascript">
        //<![CDATA[
        var site_url=  \''.site_url().'\';                            
        //]]>
        </script>';


        return ;

    }

 
    function simple_mp3_player_enqueue_script() {

        wp_enqueue_script('jquery');
        add_action('wp_print_scripts', array($this,'javascript_and_css'));
        wp_enqueue_script('simple_mp3_player_js2', WP_CONTENT_URL . '/plugins/mp3-player/js.js', array('jquery'));
      

    }

    function __CONSTRUCT($view){


        wp_enqueue_style('simple_mp3_style', WP_CONTENT_URL . '/plugins/mp3-player'.$view->getStylesheet());


        /* Load any scripts needed. */


        add_action( 'template_redirect', array($this,'simple_mp3_player_enqueue_script' ));

        //add_action( 'template_redirect', array($this,'simple_mp3_player_enqueue_script' ));

        return;
    }




    function controller($attr,$view) {

        global $post;

        /* Orderby. */
        if ( isset( $attr['orderby'] ) ) {
            $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
            if ( !$attr['orderby'] )
                unset( $attr['orderby'] );
        }

        /* Default gallery settings. */
        $defaults = array(
            'order' => 'ASC',
            'orderby' => 'menu_order ID',
            'id' => $post->ID,
            'link' => 'full',
            'itemtag' => 'dl',
            'icontag' => 'dt',
            'captiontag' => 'dd',
            'columns' => 3,
            'size' => 'thumbnail',
            'include' => '',
            'exclude' => '',
            'numberposts' => -1,
            'offset' => '',
            'sc'=>''
        );

        /* Merge the defaults with user input. Make sure $id is an integer. */
        extract(shortcode_atts( $defaults, $attr ) );
        $id = intval( $id );


        $rep=new mp3_player_repository();

        $attachments=$rep->getAttachmentsFromSoundcloud($sc);
        $attachments=$rep->getAttachmentsFromGallery($id);

        $mp3PlayerModel=new mp3_player_model();
        $mp3PlayerModel->setItems($attachments);
        /* If is feed, leave the default WP settings. We're only worried about on-site presentation. */
        if ( is_feed() ) {
            return 'music on website';
        }

        $mp3PlayerModel->setPostId($id);
        $mp3PlayerModel->setTitle($title);

        add_action('wp_footer',array($this,'renderFlashPlayer'));

        $output=$view->render($mp3PlayerModel);

        return $output;

    }




    function renderFlashPlayer(){

         //flash no longer supported. Using html5 
        $out=' <audio id="html5Player" src=""  ></audio>';
        echo $out;   
    }

}
?>
