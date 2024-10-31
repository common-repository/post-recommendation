<?php
/**
 * @name          : Post Recommendation
 * @version	      : 1.0
 * @package       : apptha
 * @subpackage    : article-recommendation
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Creation Date : June 27 2012
 * @Email         : kranthikumar@contus.in
 * @Modified Date : June 27 2012
 * @Purpus        : This file is used to do database operations when plugin is uninstalling time  
 * */
function unInstallAllData(){
	
 delete_option('artical-img-width');
 delete_option('artical-img-height');
 delete_option('artical-window-show-img');
 delete_option('artical-window-position');
 delete_option('artical-default-img');
 delete_option('show-article-popup');
 delete_option('show-facebook-like');
 delete_option('article-facebook-like');
 delete_option('article_apptha_get_lic_key');	
}

?>