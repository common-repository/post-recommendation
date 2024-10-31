<?php
/*
Plugin Name: Post Recommendation
Plugin URI: http://www.apptha.com/category/extension/Wordpress/post-recommendation
Description: By using Post Recommendation, you can show next blog or post to visitors (blog readers) in recommended box. It helps visitors to know about next post. The recommended box can be placed in any one of three positions Top right, Bottom left and Bottom right. You can show post image and description in recommended box and these are optionals. 
Version:1.0
Author: Apptha
Author URI: http://www.apptha.com
License: GNU General Public License version 2 or later; see LICENSE.txt
*/
include_once 'database/install.php';
include_once 'database/uninstall.php';
include_once 'articlesuportajax.php';
define('SITE_URL', get_site_url() );
define('PLUGIN_NAME' , plugin_basename(__FILE__) );
define('PLUGIN_DIR_PATH' ,  get_site_url().'/wp-content/plugins/'.dirname(plugin_basename(__FILE__)) );
function atricleAddSettingsSubMenu(){  //create Post Recommended submenu in settings menu
							$page_title = 'Settings';
							$menu_title = 'Post Recommend';
							$menu_slug = 'post-settings';
							$capability = 4;
							$function = 'atricleShowSettingsMenuOptions';
							add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
}
function atricleShowSettingsMenuOptions(){  //creating article settings menu options 
	
	    $articleFormSubmit = filter_input(INPUT_POST , 'aritcle-submit');
	    $uploadImgSubmit = filter_input(INPUT_POST , 'article-default-image-submit');
	    $msg = 0;	
		if(isset($articleFormSubmit))
		{
			$msg = updateIntoDB($_POST);
		}
		
		else if(isset($uploadImgSubmit))
		{
			
			$msg = fileUploadStoreinDB($_FILES , dirname(__FILE__) );
			
		}
		 $showPopUpAt =  get_option('artical-window-position'); 
		 $showArticlePopUp = get_option('show-article-popup');
		 $articleWindow =  get_option('artical-window-show-img');
		 $showFacebookLike = get_option('show-facebook-like');
		 
		    $site_url = get_bloginfo('url');
            $split_title = get_option('article_apptha_get_lic_key');// $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='get_title_key'");
            $get_title = trim($split_title);
            $strDomainName = SITE_URL;
            preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
         
            preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
           
        $customerurl = $matches['domain'];
            $customerurl = str_replace("www.", "", $customerurl);
            $customerurl = str_replace(".", "D", $customerurl);
            $customerurl = strtoupper($customerurl);
            $get_key     = article_generate($customerurl);
            
 ?>
					<div id="icon-options-general" class="icon32" ><br></div><h2 style="float: left;">Post Recommendation Settings</h2>
					<?php if($msg){ //showing message when update form  
					?> 
					<div style="clear: both;" id="setting-error-settings_updated" class="updated settings-error"> 
					<p><strong><?php echo $msg; ?> </strong></p></div>
					<?php } //if end ?>
					
	 	<script src="<?php echo PLUGIN_DIR_PATH.'/js/articalpopup.js'; ?>" type="text/javascript"></script>
	 	<script type="text/javascript">
	 	var url = '<?php echo SITE_URL; ?>';
		</script>
		<script src="<?php echo  PLUGIN_DIR_PATH.'/js/facebox.js'; ?>" type="text/javascript"></script>
	 	<script src="<?php echo  PLUGIN_DIR_PATH.'/js/facebox_admin.js'; ?>" type="text/javascript"></script>
	 	<link href="<?php echo   PLUGIN_DIR_PATH.'/css/facebox_admin.css';?>" media="screen" rel="stylesheet" type="text/css" />
		
	 	
<script type="text/javascript">

    function validateKey()
           {
        	   var Licencevalue = document.getElementById("get_license").value;
        	   Licencevalue = Licencevalue.trim();
                   if(Licencevalue == "" || Licencevalue !="<?php echo $get_key ?>"){
            	   alert('Please enter valid license key');
            	   document.getElementById("get_license").value = '';
            	   document.getElementById("get_license").focus();
            	   return false;
        	   }
                   else
                       {
                            alert('Valid License key is entered successfully');
                            document.getElementById("showbutnowandlicence").style.display = 'none';
            	           return true;
                       }

           }
    var apptha1 = jQuery.noConflict();
    apptha1(document).ready(function(apptha) {
      apptha1('a[rel*=facebox]').facebox()
    }) 
    
</script>
<input type="hidden" name="siteUrl" id="siteUrl" value=<?php echo SITE_URL; ?> />
<?php 	 	
if(isset($_POST['submit_license']))
    {
       $keyValu = strip_tags(stripslashes($_POST['get_license']));
       update_option('article_apptha_get_lic_key', $keyValu);
      
    }
   else{ 
 // echo $get_title .' ------ '.$get_key;
        if($get_title != $get_key)
        {
         ?>
<p id='showbutnowandlicence'>
    <a href="#mydiv" rel="facebox">
         <img  style="margin-right: 15px;" src="<?php echo PLUGIN_DIR_PATH.'/images/licence.png'?>" align="right">
    </a>
    <a href="http://www.apptha.com/shop/checkout/cart/add/product/82/" target="_new">
         <img  src="<?php echo PLUGIN_DIR_PATH.'/images/buynow.png'?>" align="right" style="padding-right:5px;">
    </a>
</p>

<div id="mydiv" style="display:none;width:500px;background:#fff;">
<form method="POST" action=""  onSubmit="return validateKey()">
    <h2 align="center">License Key</h2>
   <div align="center"><input autocomplete="off" type="text" name="get_license" id="get_license" size="58" value="" />
   <input type="submit" name="submit_license" id="submit_license" value="Save"/></div>
</form>
</div>
<?php } } //if end for show licenc key and buynow buttons ?>
	
	 	
	 <form name="artical_settings" method="post"  action="<?php echo $_SERVER['PHP_SELF'].'?page=post-settings' ?>" enctype="multipart/form-data">	
	 
			 <table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							Post window
						</th>
						<td>
						<?php 
						
						if(is_array($articleWindow))
						{
							$check1 = in_array(1 , $articleWindow );
							$check2 = in_array(2 , $articleWindow );
							$check3 = in_array(3 , $articleWindow );
						}	
						
						?>
						<input type="checkbox" name="artical-window-show-img[]" <?php if($check1) echo 'checked="checked"';  ?>    value="1"  /> Show Image<br/>
						<input type="checkbox" name="artical-window-show-img[]" <?php if($check3) echo 'checked="checked"';  ?>    value="3"/> Show Description
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							Post window position
						</th>
						<td >
								 <select name="artical-window-position" id="artical-window-position">
								 <option <?php if($showPopUpAt == 1 ){ echo 'selected="selected"'; } ?> value="1">Bottom-Right</option>
								 <option <?php if($showPopUpAt == 2 ){ echo 'selected="selected"'; } ?> value="2">Bottom-Left</option>
								 <option <?php if($showPopUpAt == 3 ){ echo 'selected="selected"'; } ?> value="3">Top-Right</option>
							 </select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							Default Post Image<br>
						</th>
						<td  style="padding-right: 0px;" width="140px">
						<p>							
						<input  style="float: left;" type="file" name="article-default-image" id="article-default-image" > 
						<div>
						<img width="50" title="<?php echo get_option('artical-default-img'); ?>" src="<?php echo PLUGIN_DIR_PATH.'/images/'.get_option('artical-default-img'); ?> ">
						</div>
						
						</td>	
						<td>
						<input type="submit"  style="margin-top: 7px;" name="article-default-image-submit" id="article-default-image-submit" class="button" value="Upload"> 
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							Show Facebook and Twitter like Buttons
						</th>
						<td>
						<input type="hidden" id = 'showfacebooklikeoption' value=<?php echo $showFacebookLike; ?>  name = 'showfacebooklikeoption'  >
						 	<input type="radio" name="show-facebook-like" id="show-facebook-like"  value="1"  <?php if($showFacebookLike){ echo 'checked="checked" '; } ?>  />Yes
						 	<input type="radio" name="show-facebook-like" id="show-facebook-like"  value="0" <?php if(!$showFacebookLike){ echo 'checked="checked" '; } ?> />No
						 	
						</td>
					</tr>	
				</tbody>
				
			</table>	
              <input style="margin-top: 20px;" type="submit" onclick="return articleFormValidate();" name="aritcle-submit" id="aritcle-submit" class="button-primary" value="Save Changes">
     </form>       
 
   <?php 
}  
add_action('admin_menu', 'atricleAddSettingsSubMenu'); //creating submenu in settings menu
function articleShowPopupBox($content)
 {  
	 global $wp_query , $post;
	
	 $post_obj = $wp_query->get_queried_object();
	 $post_type = $post_obj->post_type;
	 $split_title = get_option('article_apptha_get_lic_key');
	if($post_type == 'post') //if post is set then 
    {    
  	         	$defaultArticleImg = PLUGIN_DIR_PATH.'/images/'.get_option('artical-default-img');
	  			$size = array(70,70 ); //get size of article image which is given at backend
	  			$imgWidth = get_option('artical-img-width');
	  			$imgHeight = get_option('artical-img-height') ;
  				$articleWindow =  get_option('artical-window-show-img');
							if(is_array($articleWindow))
							{
								$showArticleImage = in_array(1 , $articleWindow ); //for show image
								$showArticleTitle = in_array(2 , $articleWindow ); //for show title
								$showArticleDesc = in_array(3 , $articleWindow ); //for show description
							}	
		  	?>
		 <div id="appthContentPopup" style="overflow: auto;">
		   		<?php  echo ucwords($post_obj->post_content); //show the content of the post; ?>
 		</div>
<?php 		 
		        if (isset($_POST['data'])){
		    		$post_id = $_POST['data'];
				}
				else{
				    $post_id = "";
			    }
			  
				$previousPostObj = get_previous_post( ); //get next post data
				$nextPostObj     = get_next_post( ); //get previous post data
				 
				if($nextPostObj->ID) //if next post id is set then 
				{
					$nextPostId = $nextPostObj->ID ;
					$nextPostTitle = $nextPostObj->post_title;
					$nextPostLink = $nextPostObj->guid ;
					if(current_theme_supports('post-thumbnails') )
					{
						$nextPostImg = get_the_post_thumbnail( $nextPostId , $size, $attr );	
					}
					
					$nextPostContent = $nextPostObj->post_content;
				}
				if(!$nextPostObj->ID) //if no next post then get first post in post list
				{
					$categories = get_categories(array('type' => 'post','child_of' => 0,'orderby' => 'name','order' => 'ASC','hide_empty' => true)); //getting categories list for get first post 
					if(is_array($categories) )
					{
						  $catIdIs = $categories[0]->cat_ID;  //get the first cat_id
						  $args = array(  'numberposts' => 1,  'orderby' => 'ID','order'=> 'ASC' ); //get for post list
						  $myposts = get_posts( $args );
					  if(is_array($myposts)) //if any post in that category then we use that post_id
					  {
					  	  $postId = $myposts[0]->ID;
						  $previousPostObj = get_post($postId);
					  }
					}
		  		
				}
				if($previousPostObj->ID ){ //use to get previous post id
					
					 $prePostId = $previousPostObj->ID ;
				 	 $prePostTitle = $previousPostObj->post_title;
					 $prePostLink = $previousPostObj->guid ;
					 if(current_theme_supports('post-thumbnails') ){
					 $prePostImg = get_the_post_thumbnail( $prePostId , $size, $attr );
					 }
					 $prePostContent = $previousPostObj->post_content;
					
				}
				
				 if($prePostId || $nextPostId ) //if any post is there then only u can show the post
				 { 
				 	$showPopUpAt = get_option('artical-window-position');//for show popup box at 3 places
				 		
						 $flagForShowPopup = 0;	
						 $readTextStyle = '';
						 $closeImgRight = '';
				 		 if($showPopUpAt == 1) //bottom-right place
						 {
						 	$showAt = 'style = "right:-290px;" ';
						 	$flagForShowPopup = 1;
						 	$readTextStyle = "style='left: -26px'";
						 	 
						 }	
						 if($showPopUpAt == 2)//bottom-left place
						 {
						 	$showAt = 'style = "left: 0px;" ';
						 	$flagForShowPopup = 2;
						 	$readTextStyle = "style='right:-26px'";
						 
						 }
						 if($showPopUpAt == 3)//top-right place
						 {  
						 	$showAt = 'top';
						 	$flagForShowPopup = 3;
						 	$readTextStyle = "style='left: -26px'";
						 	
						 }
		 		    $facebookApiId = get_option('article-facebook-like');
		 		    $showFLike = get_option('show-facebook-like'); //show facebook like or not
		 		    $pageURL = str_replace('&', '%26', get_permalink()); //get post url for facebook like
?>
			<head > 
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>yensdesign.com - How to create a stuning and smooth popup in jQuery</title>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
				<title>yensdesign.com - How to create a stuning and smooth popup in jQuery</title>
				<link href="<?php echo PLUGIN_DIR_PATH.'/css/articalstyle.css';?>" media="screen" rel="stylesheet" type="text/css" />
			 	<script src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js" type="text/javascript"></script>
				<script src="<?php echo PLUGIN_DIR_PATH.'/js/articalpopup.js'; ?>" type="text/javascript"></script>

<script type="text/javascript">
$ = jQuery.noConflict();
$(function(){
		
		var $box = $("#appthContentPopup"),
		$inner = $("> .inner", $box),
		innerOuterHeight = $inner.outerHeight();
		boxHeight = $box.outerHeight();   //div height
		boxOffsetTop = $box.offset().top;

			$(function(){
		      function yourfunction(event) {
		    	  var showPopUpAt = parseInt(document.getElementById('showpopupatposition').value);
		    	 switch(showPopUpAt)
		    	 {
		    	 case 3  :  $("#popuptop").addClass('fly_close');  
	   			  			$("#popuptop").css("right","-300px");
			    	 		break;
		    	 case 2  :
		    		 		
		   			  		$("#popup").addClass('fly_close_left');	  
		   			  		$("#popup").css("left","-300px");  
			    	       break;
			     default : 	 
						     
				   			  $("#popup").addClass('fly_close');  
				   			  $("#popup").css("right","-300px");       
			     } 
		    	  
	   			  document.getElementById('showflag').value = 0;	    
	        }
		    $('#popupContactClose').click(yourfunction);
			});

			
			   function showPopUpAtPosition(position,openClassName,closeClassName) {

				   var elem =  $("#appthContentPopup");
		            var divend = elem[0].scrollHeight + elem.offset().top;
		            divend = divend + 30;
		            var pagescroll = $(this).scrollTop() + $(this).height();
		   			if(divend <= pagescroll){
		   				$("#popup").css("display","block");
		   				$("#popup").css(position,"-1px");  	 
		   				$("#popup").addClass(openClassName);  
		   				$("#popuptop").css("display","block");
		   				$("#popuptop").css(position,"-1px");  	 
		   				$("#popuptop").addClass(openClassName); 			
		   			}
					else { 
								$("#popup").css("display","block");
				   				$("#popup").addClass(closeClassName);  
				   				$("#popup").css(position,"-300px"); 
				   				
				   				$("#popuptop").css("display","block");
				   				$("#popuptop").addClass(closeClassName);  
				   				$("#popuptop").css(position,"-300px");
								  	
						} 
				    	   
		        }
			   		
		
		$(window).scroll(function(){

         var flag = parseInt(document.getElementById('showflag').value);
         var showPopUpAt = parseInt(document.getElementById('showpopupatposition').value);
	   if(flag)
	   {   
		        switch(showPopUpAt)
		        {
			
		        case 2 : 
							showPopUpAtPosition('left','fly_open_left','fly_close_left');
		
		            break;
		        case 3 :		
		        	showPopUpAtPosition('right','fly_open','fly_close');
		        	break;
		        default :   showPopUpAtPosition('right','fly_open','fly_close');
		
		        }//switch end hear 				
            
			
         }
  
		});
});
</script> 
</head>
 <body >

		    <input type="hidden" name='popupboxposition' id='popupboxposition' value=<?php echo $flagForShowPopup; ?> />
	  
	    <input type="hidden" name='showflag' id='showflag' value="1" />
	    <input type="hidden" name="showpopupatposition" id="showpopupatposition" value="<?php echo $showPopUpAt; ?>" />
	
	<div id="popupContact">

	<?php if($showAt == 'top')  //oncontextmenu="return false;"
	{
		echo '<div id="popuptop" >';
		
	}else{
	?>
			<div id="popup" <?php echo $showAt; ?>   >
	<?php }?>	
	<div id ="readnexttext" <?php echo  $readTextStyle;?>  onclick = "showPopupBoxDisplay()">
		READ NEXT </div>
        	<div id="popup_wrapper">
          		<div id='closeimageid' class="close_image" <?php echo $closeImgRight; ?> >
					<a class="close_click"   id="popupContactClose" >x</a>
				</div>	           
            <div class="popup_main">
            <div class="strip-like">
            <a href="#" style="cursor: default;">READ NEXT</a>
                
        <?php 
      		 if($showFLike){
		?>					
					<div id="facebookTwittershare" class="facebookTwittershare" >
							<div class='facebookstyles'>
										<a href="" class="fbshare" id="fbshare" target="_blank" ></a>
					                    <iframe
				                            src="http://www.facebook.com/plugins/like.php?href=<?php echo $pageURL; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=21"
				                            scrolling="no" frameborder="0"
				                            style="border: none; overflow: hidden; width: 100px; height: 25px;" allowTransparency="true" > 
				                        </iframe>
				            </div>
				            <div class='twitterlike' >
				                         <a href="http://twitter.com/share" class="twitter-share-button"
				                            data-count="horizontal">Tweet</a>
				                          <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				   			</div>
					</div>
	 	
	    <?php } //if end for show facebook ?>
                
                </div>
                <div style="clear: both;" id="popupbox"> 
<?php   $articleTigle = "";
		                    if($showArticleImage) //if u allow to show image option in backend then only it display
		                    { 
		                    	echo '<div style="width:28%;float: left;margin-left:7px;"> ';
								if($nextPostId) //for next post img
								{ 
								    if($nextPostImg) //if next post img is set
								    {
										echo "<a href='$nextPostLink'>$nextPostImg </a> ";
								    }	
									else{
										echo '
										<a href="'.$nextPostLink.'">
										<img  style="width:70px;height:70px;" src="'.$defaultArticleImg.'" />
										</a>';		
									}
								}
								else{
										    if($prePostImg) //if pre post img is set
										    {
												echo "<a href='$prePostLink'>$prePostImg </a> ";
										    }	
											else
											{
												echo '
												<a href="'.$prePostLink.'">
												<img style="width:70px;height:70px;" src="'.$defaultArticleImg.'" />
												</a>';		
											}
								}	
								echo '</div>';
		                    }//show image if is end		
		                    else{ 
		                    	  //if aritle img and descri is not set
		                    	   $articleTigle = "style = 'padding-left: 11px;' ";
		                    }
						 ?>                   
			<div class="popup_text" <?php echo $articleTigle;?> >
				<h4>
						 <?php 
                          $title =  getTitleOfArticle(trim($nextPostTitle)); //get title of the post for next post
			               if(1) //show title of the article flag
			               {   						
								if($nextPostId){
									
								?>
									<a href="<?php echo $nextPostLink;  ?>"><?php   echo $title; ?></a>
								<?php 	
								}
								else{
								  $title =  getTitleOfArticle(trim($prePostTitle)); //get title of the post for previous post
								?>
									<a href="<?php echo $prePostLink; ?>"><?php echo $title; ?></a>
								<?php 	
								}
			               }//if end for show title of the article
?>
                  </h4>
                      <?php 
                   
						if($showArticleDesc) //if allow the user to show description of article then only we show it
						{		
								if($nextPostId){
									
									  $content = getContentOfArticle($nextPostContent); //get limitel areticl content
								?>
									<p  style="margin:0px; line-height: 16px;font-size: 12px; " class='article-content-style' ><?php echo $content; ?> </p>
								<?php 	
								}
								
								else{ 
									$content = getContentOfArticle($prePostContent);
								?>
									<p  style="margin-bottom: 2px;line-height: 16px;font-size: 12px;" class='article-content-style'><?php echo $content; ?></p>
								<?php 	
								}
						  }//if end for show descript option	?>  
                    
                  <?php if($previousPostObj->ID){ ?>	
						
						<input type="hidden" value="<?php echo $previousPostObj->ID; ?>" name="prepostid" id="prepostid" >
					<!--	  <a style="float:left" onclick="getNextPostIdValue(1)" ><?php echo '<< PREVIOUS'; ?></a>  
						 
						  <a style="float:left" href="<?php echo $prePostLink; ?>"><?php echo '<< PREVIOUS'; ?></a> -->
						
				<?php } ?>	  
                    
                <?php if($nextPostObj->ID){  ?>	
						<input type="hidden" value="<?php echo $nextPostObj->ID; ?>" name="nextpostid" id="nextpostid" >
				
				<?php } ?>
		     </div>
       		</div> <!-- div popupbox end hear -->
        </div> <!-- popup main div end -->	
        
      <?php
      if(strlen($split_title) < 10 )
      {
      	echo '<div id="article-power-by-apptha">
              <a href="http://www.apptha.com" target="new">Powered By Apptha</a>
              </div>';
      }
      else{
      	  echo "<input type='hidden' id='article-power-by-apptha' name='article-power-by-apptha' /> ";
      } ?>
      </div>
    </div>   
  </div>
</body>
	<?php 		  
 		}//if end for show popup box
     }//if end hear for post
     else{
     	 return $content;
     }
	  
 } //function end hear
	
	  add_filter('the_content', 'articleShowPopupBox'); //this filter call when the post is loading
      register_activation_hook(__FILE__ , 'articleTableInstalling'); //Activate plugin
      register_deactivation_hook(__FILE__, 'unInstallAllData' ); //DeActivate Plugin
   
	 function  getTitleOfArticle($nextPostTitle){ //for get title of the post
	 	  				
	                        $len = strlen($nextPostTitle);
							if($len > 25)
							$title = substr($nextPostTitle,0,25).'...';
							else
							$title = substr($nextPostTitle,0,25);    
							$title = ucfirst($title);
							return $title;
							
	 }
	 function   getContentOfArticle($nextPostContent){ //for get content of the post
	 					
	 	$nextPostContent = preg_replace('/<img[^>]+./','', $nextPostContent);
	 	$nextPostContent = preg_replace('/<a[^>]+./','', $nextPostContent);
	 	
	 						$content1 = trim($nextPostContent);
	 					    $content =	trim(ucfirst(substr($content1 , 0 , 75)));
	                     	$len = trim(strlen($content1));
	                
							if($len > 75){
							 $content .= '...';
								
							}
							return $content;			
	 }			
function article_generate($domain)
{
$code= article_encrypt($domain);
$code = substr($code,0,25)."CONTUS";
return $code;
}	
function article_encrypt($tkey) {

$message =  "EW-ARMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";
		   //EW-MPGMP0EFIL9XEV8YZAL7KCCONTUS
	for($i=0;$i<strlen($tkey);$i++){
$key_array[]=$tkey[$i];
}
	$enc_message = "";
	$kPos = 0;
        $chars_str =  "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
	for($i=0;$i<strlen($chars_str);$i++){
$chars_array[]=$chars_str[$i];
}
	for ($i = 0; $i<strlen($message); $i++) {
		$char=substr($message, $i, 1);

		$offset = article_getOffset($key_array[$kPos], $char);
		$enc_message .= $chars_array[$offset];
		$kPos++;
		if ($kPos>=count($key_array)) {
			$kPos = 0;
		}
	}

	return $enc_message;
}
function article_getOffset($start, $end) {

    $chars_str =  "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
	for($i=0;$i<strlen($chars_str);$i++){
$chars_array[]=$chars_str[$i];
}

	for ($i=count($chars_array)-1;$i>=0;$i--) {
		$lookupObj[ord($chars_array[$i])] = $i;

	}

	$sNum = $lookupObj[ord($start)];
	$eNum = $lookupObj[ord($end)];

	$offset = $eNum-$sNum;

	if ($offset<0) {
		$offset = count($chars_array)+($offset);
	}

	return $offset;
}
?>