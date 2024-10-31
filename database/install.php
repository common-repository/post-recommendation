<?php
/**
 * @name          : Post Recommendation
 * @version	      : 1.0
 * @package       : apptha
 * @subpackage    : article-recommendation
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Creation Date : Jan 27 2012
 * @Email         : kranthikumar@contus.in
 * @Modified Date : Jan 27 2012
 * @Purpus        : This file is used to do database operations when plugin is install and store backend options when u click on save changes button 
 * */
function articleTableInstalling(){
  
 update_option('artical-img-width',90);
 update_option('artical-img-height',90);
 $popUpOptions = array('1','2','3');
 update_option('artical-window-show-img',$popUpOptions);
 update_option('artical-window-position',1);
 update_option('artical-default-img','articledefaultimg.jpg');
 update_option('show-article-popup',1);
 update_option('show-facebook-like',1);
 
}
function updateIntoDB($updateArrayValues = array()) //user click on save changer button it wil store in db
{
	//print_r($updateArrayValues);
 update_option('artical-img-width',$updateArrayValues['artical-img-width']);
 update_option('artical-img-height',$updateArrayValues['artical-img-height']);
 $popUpOptions = $updateArrayValues['artical-window-show-img'];
 update_option('artical-window-show-img',$popUpOptions);
 update_option('artical-window-position',$updateArrayValues['artical-window-position'] );
 update_option('show-article-popup',  $updateArrayValues['show-article-popup'] );
 update_option('article-facebook-like', $updateArrayValues['article-facebook-like'] );
 update_option('show-facebook-like',$updateArrayValues['show-facebook-like']);
 return " Settings saved"; 
}

function fileUploadStoreinDB($fileUploadSubmit = array() , $pluginDir) //when user upload default img form local system it wil store in image foulder
{   
	
	$imgType   = $_FILES["article-default-image"]["type"];
	$uploadDir = $pluginDir.'/images/'.$_FILES["article-default-image"]["name"];
	$size      = $_FILES["article-default-image"]["size"];
	if(!$size)
	{
		$returnVal =  "please upload image";
		return $returnVal;
	}
	
	if ( (($imgType == "image/gif")|| ($imgType == "image/jpeg")|| ($imgType == "image/pjpeg") || ($imgType == "image/PNG") || ($imgType == "image/png")  )  )
	{
			if ($_FILES["article-default-image"]["error"] > 0)
			  {
			 	  $returnVal =  "Error: " . $_FILES["article-default-image"]["error"] . "<br />";
			  }
			else
			  {
				  move_uploaded_file($_FILES["article-default-image"]["tmp_name"], $uploadDir );
	     		  $returnVal =  "Stored in: " . $uploadDir;
	     		  update_option('artical-default-img', $_FILES["article-default-image"]["name"]);
	     		  $returnVal = $_FILES["article-default-image"]["name"]." Image Updated Successfully ";
			  }
	}
	else
	{
		
		  $returnVal =  "invalide image ";
	}
		 return $returnVal;
}
?>