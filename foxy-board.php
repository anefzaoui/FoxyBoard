<?php  
    /* 
    Plugin Name: Foxy Board
    Plugin URI: http://wordpress.org/plugins/foxy-board/
    Version: 0.5.1
    Author: Ahmed Nefzaoui
    Description: Easily Embed and Preview (Mozilla) Firefox Marketplace Apps in a stylish and modern way.
    */  
	class Foxyboard{
	
	static function initialize(){
		add_action('init',array(__CLASS__,'register_all'));
	}
	
	static function handle_fx_app_preview($atts){	
	if(!(isset ($atts[id])))$atts[id] = "arabic-mozilla";
	if( (!(isset ($atts[theme]))) || ($atts[theme]!="blue") || ($atts[theme]!="green") )$atts[theme] = "blue";
	return self::fetch_single_app($atts);
	}
	
	static function fetch_single_app($atts){
	$blockstyle="block";
	$nonestyle="none";
	$thumb="128";
	$fx_single_app = self::curl("https://marketplace.firefox.com/api/v1/apps/app/$atts[id]");
	$single_app_url = "https://marketplace.firefox.com/app/$fx_single_app->slug";
	$res='<div class="fx-single-app-container">'
	.'<div class="fx-single-app-child-container">'
	.'<div class="fx-single-app-picture"><img src="'.$fx_single_app->icons->$thumb.'"/></div>'
	.'	<div class="fx-single-app-info">'
	.'		<div class="fx-single-app-name"><a target="_blank" href="https://marketplace.firefox.com/app/'.$fx_single_app->slug.'">'.$fx_single_app->name.'</a></div>'
	.'		<div class="fx-single-app-dev-version">Version: '.$fx_single_app->current_version.'</div>'
	.'		<div class="fx-single-app-dev-name">By: '.$fx_single_app->author.'</div>'
	.'		<div class="fx-single-app-rating"><div class="stars stars-'.self::getnumreviews($fx_single_app->ratings).'"></div><div class="fx-single-app-review-status">'.self::getreviewmessage($fx_single_app->ratings).'</div></div>'
	.'	</div>'
	.'</div>'
	.'<div class="qr-code" onclick="this.parentNode.childNodes[3].style.display=&#39;block&#39;">QR Code</div>'
	.'<a target="_blank" href="'.$single_app_url.'" class="gotoappbtn">Get it ('.self::app_price($fx_single_app->price).')</a>'
	.'<img id="mysingleappqr" onclick="this.style.display=&#39;none&#39;" class="the-qr" src="http://api.qrserver.com/v1/create-qr-code/?size=130x130&data='.$single_app_url.'"></img>'
	.'</div>';
	
	return $res;
	}
	/* App related functions */
	static function app_price($price){
	if($price===null){
	return "free";
	}
	return $price;
	}
	static function getreviewmessage($revmsg){
	if($revmsg->count==0){
	return "No reviews yet.";
	}
	return "Reviews: ".$revmsg->count;
	}
	
	static function getnumreviews($revmsg){
	if($revmsg->count!=0){
	return round($revmsg->average);
	}
	return 0;
	}
	/* END App related functions */
	static function curl($url){
	$f = curl_init($url);
	curl_setopt($f, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($f, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($f, CURLOPT_RETURNTRANSFER, True);
	curl_setopt($f, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($f, CURLOPT_TIMEOUT, 5);
	
	
	return json_decode(curl_exec($f));
	}
	static function fx_box_init() {
        add_meta_box('Foxyboard::fx_box_init', 'Foxy Board Widget', 'Foxyboard::foxy_board_widget', 'post', 'side', 'high');
    }

    static function foxy_board_widget() {

        $example = "[firefox-app id=&quot;arabic-mozilla&quot;]";
        echo '<p><label>How to use the Foxy Board Widget:</label><br>
        <hr>
        <label><b>Single App:</b></label>
         <ul>
           <li>
               <label for="package_name">Past the app url here</label>
			   <input type="text" id="fxb-package-url" name="package_name" value="" style="width:100%"></input>
			   <p id="error-msg-fx-board-widget"></p>
			   <br/>
			   <label for="package_name">Or edit this text and replace the current id with the slug of your app then insert it manually.</label>
			   <input type="text" id="fxb-package-id" name="package_name" value="' . $example . '" style="width:100%"></input><br/>
           </li>
        </ul>
		Inset button only works in <b>Visual</b> editing mode.<br/>
		<input type="button" class="button button-primary button-large" onclick="results()" value="Insert"/>
	   ';
    }
	static function register_all(){
				wp_register_style('general_style', plugins_url('style.css',__FILE__ ));
				wp_enqueue_style('general_style');	
				wp_enqueue_script('the_js', plugins_url('/jslib.js',__FILE__) );
				add_action('admin_menu', array(__CLASS__,'Foxyboard::fx_box_init'));
				add_shortcode('firefox-app',array(__CLASS__,'Foxyboard::handle_fx_app_preview'));
	
			}
			
	}
	FoxyBoard::initialize();
    ?>