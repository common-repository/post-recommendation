/***************************/
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
 * @Purpus        : This file is used to show popup article box in front end and do form validations  
 * */				
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;

var rightclosevalue ;
var rightopenvalue;
var opacityVal = 1;
var opacityIncr = 0;
function articleFormValidate(){
	
	var w = width = parseInt(document.getElementById('artical-img-width').value);
	var h = height = parseInt( document.getElementById('artical-img-height').value);
	
	var fail = 0;

	height = IsNumeric(height);
	widht = IsNumeric(width);
	

			if(widht  ){
				if(w > 0 && w< 101){
					document.getElementById('artical-img-width-error').style.display = 'none';
				}
				else{
					document.getElementById('artical-img-width-error').style.display = 'block';	
					fail = 1;
				}
			
			}
			else{
				document.getElementById('artical-img-width-error').style.display = 'block';	
				fail = 1;
			}
			if(height  )
			{
				if(h > 0 && h< 101){
					document.getElementById('artical-img-height-error').style.display = 'none';
				}
				else{
					document.getElementById('artical-img-height-error').style.display = 'block';	
					fail = 1;
				}
				
			}
			else
			{
					document.getElementById('artical-img-height-error').style.display = 'block';
					fail = 1;
			}
			
	if(fail)
		return false;
	else
	return true;
	
}

function IsNumeric(strString) //  check for valid numeric strings	
{
	if(!/\D/.test(strString)) return true;//IF NUMBER
	else if(/^\d+\.\d+$/.test(strString)) return true;//IF A DECIMAL NUMBER HAVING AN INTEGER ON EITHER SIDE OF THE DOT(.)
	else return false;
}

