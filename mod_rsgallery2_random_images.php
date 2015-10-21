<?php

/**
* RSGallery2 random images module: 
* Shows random images from the Joomla extension RSGallery2 (www.rsgallery2.nl).
* @copyright (C) 2012-2015 RSGallery2 Team
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @version 4.0.0
**/

// ToDo: Regard user privacy: show only images for gallery owner or common released ....


defined('_JEXEC') or die();

// Returns lists of galleries and images 
require_once dirname(__FILE__) . '/Rsg2DbSelections.php';

// Returns links to be used in Jroute() to images views inside galleries
require_once dirname(__FILE__) . '/Rsg2ImageRoutes.php';

// Initialise RSGallery2 and other variables
require_once(JPATH_BASE.'/administrator/components/com_rsgallery2/init.rsgallery2.php');
//$database = &JFactory::getDbo();

// Add styling
$document = JFactory::getDocument();
$url = JURI::base().'modules/mod_rsgallery2_random_images/css/mod_rsgallery2_random_images.css';
$document->addStyleSheet($url);

global $rsgConfig;

//--- Parameters --------------------------------------------------------------
// Number of random images to display = number of rows times the number of columns
$countRows			= (int) $params->get('countrows', 		'1');
$countColumns		= (int) $params->get('countcolumns',	'1');
$count				= $countRows * $countColumns;
// Select one or more galleries and set if their subgalleries (children) should be included
$galleryIds			= $params->get('galleryids', 			'0'); //string, e.g. 3,8,42
$includeChildren	= $params->get('includechildren', 		'0');
// Display type of image to show: thumb (0), display (1), original (2)
$displayType 		= (int) $params->get('displaytype', 	'0');
// CSS height and/or width attribute for the img and the div element (0=no attribute)
$imageHeight 		= (int) $params->get('imageheight', 	'0');
$imageWidth 		= (int) $params->get('imagewidth', 		'0');
$divHeight 			= (int) $params->get('divheight', 		'0');
$divWidth 			= (int) $params->get('divwidth', 		'0');
// ... for the div with class mod_rsgallery2_random_galleries_name
$divNameHeight		= (int) $params->get('divnameheight', 	'0');
//$divNameWidth		= (int) $params->get('divnamewidth', 	'0');	// The width setting of the class mod_rsgallery2_random_galleries_attibute would overrule this, so makes no sense to do this now?
// Display the gallery name
$displayName 		= $params->get('displayname', 			'0');
// Display the date and its format
$displayDate 		= $params->get('displaydate', 			'0');
$dateFormat 		= $params->get('dateformat', 			'd-m-Y');
$ImageLinkType      = $params->get('imagelinktype', 		'0');

$layout             = $params->get('layout',                 'default');

//--- Collect CSS styling from parameters -------------------------------
// Get CSS image height/width attributes
$imgAttributes="";
if ($imageHeight > 0) $imgAttributes .= ' height="'.$imageHeight.'px"';
if ($imageWidth > 0)  $imgAttributes .= ' width="'.$imageWidth.'px"';

// Get CSS image height/width attributes
$divAttributes="";
if (($divHeight) or ($divWidth)) {
	$divAttributes .= 'style=overflow:hidden;';
	if ($divHeight > 0) $divAttributes .= 'height:'.$divHeight.'px;';
	if ($divWidth > 0)  $divAttributes .= 'width:'.$divWidth.'px;';
	$divAttributes .= '"';
}
$divNameAttributes="";
if (($divNameHeight)) {
	$divNameAttributes .= 'style=overflow:hidden;';
	if ($divNameHeight > 0) $divNameAttributes .= 'height:'.$divNameHeight.'px;';
	//if ($divNameWidth > 0)  $divNameAttributes .= 'width:'.$divNameWidth.'px;';// The width setting of the class mod_rsgallery2_random_galleries_attibute would overrule this, so makes no sense to do this now?
	$divAttributes .= '"';
}

//--- Db image selections preparation -------------------------------------------

$Rsg2DbSelections = new Rsg2DbSelections ();

//--- Image links preparation -------------------------------------------

$Rsg2ImageRoutes = new Rsg2ImageRoutes ();

//--- Take View Access into account -------------------------------------------

$user 		= JFactory::getUser();
$groups		= $user->getAuthorisedViewLevels();
$groupsIN 	= implode(", ",array_unique ($groups));
$superAdmin = $user->authorise('core.admin');

//--- Select specific galleries and possibly subs -----------------------------

// Selection requested ?
if ($galleryIds) {
	$galleryArray = explode(',', $galleryIds);
    
	// Include children?
	if ($includeChildren) {
        
		// All galleries
		$allGalleries = $Rsg2DbSelections->ListOfAllGalleriesOrdered (); 
		
		// Collect children -> 2dim. array $children[parentid][]
		// Establish the hierarchy by first getting the children 
		$children = array();
		if ( $allGalleries ) {
			foreach ( $allGalleries as $v ) {
				$pt     = $v->parent;
				$list   = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
        
		// Function to build children tree list 
		function treerecurse($ParentId,  $list, &$children, $maxlevel=20, $level=0) {
			// if there are children for this id and the max.level isn't reached
			if (@$children[$ParentId] && $level <= $maxlevel) {
				// Add each child to the $list and ask for its children
				foreach ($children[$ParentId] as $v) {
					$id = $v->id;	//gallery id
					$list[$id] = $v;
					$list[$id]->level = $level;
					//$list[$id]->children = count(@$children[$id]);
					$list = treerecurse($id,  $list, $children, $maxlevel, $level+1);
				}
			}
			return $list;
		}

		// Get the children of the user selected galleries
		$extendedSelection = $galleryArray;
		foreach ($galleryArray as $galUser) {
            // Get list of galleries with (grand)children in the right order and with level info
    		$recursiveGalleriesList = treerecurse( $galUser, array(), $children, 20, 0 );
			foreach ($recursiveGalleriesList as $gal) {
				array_push($extendedSelection, $gal->id);
			}
		}
		$gallerySelection = implode(", ",array_unique ($extendedSelection));
	} else {	// Don't include children
		$gallerySelection = implode(", ",array_unique ($galleryArray));
	}
} else {
	// No 'where' clause needed to limit the search of galleries from
	$gallerySelection = 0;
} 

//--- Query random images -----------------------------------------------------

/*	NOTE TODO Access should be checked for galleries, not for images
// If user is not a Super Admin then use View Access Levels
if (!$superAdmin) { // No View Access check for Super Administrators
	$query->where('access IN ('.$groupsIN.')'); //@todo use trash state: published=-2
}
*/

// Query to get limited ($count) number of random images
$randomImages = $Rsg2DbSelections->RandomImagesLimited ($count, $gallerySelection);
if(!$randomImages){
	// Error handling
	// ToDo: Ask module administrator if a message is required (?debug) and to provide this error message
	// enqueue message
}

//--- Output ------------------------------------------------------------------

// Let's display what we've gathered: get the layout
require JModuleHelper::getLayoutPath('mod_rsgallery2_random_images', $layout);


