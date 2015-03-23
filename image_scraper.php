<?php


    // -- Class Name : image_Scraper
    // -- Purpose : To download images form websites.
    // -- Created On : 10/3/2015 by Robert Gabriel @wobert_gabriel
	class image_Scraper{


        var $link; //Link from user to search
        var $stored_images;	
        var $srcs;		//Image srcs
        var $imageTypes;	// Image types
        var $saveFolder =  'downloads/'; // this is the folder to save the images to.


    public
    function __construct($link) {
        echo $link;
        $this->link = $link; // Stores the link to the varable above
        $this->stripUrl();
        $this->getHTML_Contents();
        $this->stripSrc();
        $this->imageTypes();
        $this->saveImage();
      //  $this->showErrors(); Incase something isnt working
    }



    public
    function showErrors(){

        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);

    }


        

// -- Function Name : stripUrl
// -- Params : $url
// -- Purpose : 
    public
    function stripUrl(){
        $host = parse_url( $this->link, PHP_URL_HOST); // Parses the url for use the name : example.com
        echo "http://".$host . "<br>";	// displays it for testing
    }

        

// -- Function Name : getHTML_Contents
// -- Params : $url
// -- Purpose : 
    public
    function getHTML_Contents(){
        
        $contents = file_get_contents( $this->link); // Gets content from the users requested url
        $frst_image = preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $contents, $matches ); // Parises the images from the html content
        $this->stored_images = $matches; // Stores all the images to the local varabile.
        
    }

        

// -- Function Name : stripSrc
// -- Params : $matches
// -- Purpose : 
    public
    function stripSrc(){
        $matches = $this->stored_images; // Gets the varablies
        $count = count($matches[0]); // Counts the amount of images in the page
        $srcs[] = "";
        for($x = 0; $x<= $count-1; $x++){
            $html5 = $matches[0][$x];
            preg_match( '@src="([^"]+)"@' ,  $html5, $match );
            // Provides: <body text='black'>
			$src = array_pop($match);
            $src= str_replace("//","",$src);
            // will return /images/image.jpg
            echo "<br>" . $x ." : " . $src . "<br>";
            $this->srcs[$x] = $src;
        }
      
    }

    public
    function imageTypes(){
        
         $srcs = $this->srcs; 
        $count2 = count($srcs);
        for($r=0;$r<= $count2-1;$r++ ){
            
             $this->imageTypes[$r] = substr($this->srcs[$r], -3);
            
        }  
    }
        

// -- Function Name : saveImage
// -- Params : $srcs
// -- Purpose : 
    public
    function saveImage(){
        $srcs = $this->srcs; 
        $count2 = count($srcs);
        for($r=0;$r<= $count2-1;$r++ ){
            echo $srcs[$r];
            $ch = curl_init($srcs[$r]);
            $fp = fopen($this->saveFolder . $r ."." . $this->imageTypes[$r], 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            }
        }
    }

    ?>