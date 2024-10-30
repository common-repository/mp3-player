<?php
    class mp3_player_item{
        private $title;
        private $streamUrl;
        private $downloadUrl;
        private $vars;

        function  __CONSTRUCT(){

        }



        public function setUsername($username){
            $this->vars['username']=$username;

        }

        public function getUsername(){
            return $this->vars['username'];

        }


        public function setSoundcloudPermalink($link){
            $this->vars['soundcloudPermalink']=$link;

        }


        public function getSoundcloudPermalink(){
            return $this->vars['soundcloudPermalink'];

        }                     

        public function setArtwork($artwork){
            $this->vars['artwork']=$artwork;

        }               


        public function getArtwork(){
            return $this->vars['artwork'];

        }               




        public function setStreamUrl($url){
            $this->streamUrl=$url;
        }



        public function setDownloadUrl($url){
            $this->downloadUrl=$url;
        }

        public function setTitle($title){
            $this->title=$title;

        }


        public function downloadUrl(){
            return $this->downloadUrl;
        }


        public function streamUrl(){
            return $this->streamUrl;
        }
        public function title(){
            return $this->title;

        }

    }
?>