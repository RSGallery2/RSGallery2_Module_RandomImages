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

//--- Parameters --------------------------------------------------------------
// Number of random images to display
$count			= (int) $params->get('count', 		'1');
// Horizontal (1) or vertical (0)
$display_case 	= (int) $params->get('display', 		'0');
// Select only specific gallery: 0: No, 1: Yes, or from list of galleries
$usegalselect 	= (int) $params->get('usegalselect',	'0');
$galselect 		= $params->get('galselect', 	'');

// Get RSGallery2 Itemid from first component menu item for use in links
$RSG2Itemid = Null;
$query = $database->getQuery(true);
$query->select('id');
$query->from('#__menu');
$query->where('published = 1');
$query->where("link like 'index.php?option=com_rsgallery2%'");
$query->order('link');
$database->setQuery($query);
$RSG2ItemidObj = $database->loadObjectList();
if (count($RSG2ItemidObj) > 0) {
	$RSG2Itemid = $RSG2ItemidObj[0]->id;
}

//--- Db image selections preparation -------------------------------------------

$Rsg2DbSelections = new Rsg2DbSelections ();

//--- Image links preparation -------------------------------------------

$Rsg2ImageRoutes = new Rsg2ImageRoutes ();

//--- Take View Access into account -------------------------------------------


//--- Select specific galleries and possibly subs -----------------------------



//--- Query random images -----------------------------------------------------

// Query to get limited ($count) number of random images from selected galleries ($list)
$result = Null;
$query = $database->getQuery(true);
$query->select('*');
$query->from('#__rsgallery2_files');
$query->where('published = 1');
if ($usegalselect) {
	$query->where('gallery_id IN ('.$galselect.')');
}
$query->order('rand()');
$database->setQuery($query,0,$count);	//$count is the number of results to return
$randomImages = $database->loadObjectList();
if(!$randomImages){
	// Error handling
}

// Query to get limited ($count) number of latest images
$latestImages = $Rsg2DbSelections->LatestImagesLimited ($count, $gallerySelection);
if(!$latestImages){
	// Error handling
	// ToDo: Ask module administrator if a message is required (?debug) and to provide this error message
	// enqueue message
}

//--- Output ------------------------------------------------------------------

// Let's display what we've gathered: get the layout either horizontal (1) or vertical (0)
if ($display_case == 0) {
	require JModuleHelper::getLayoutPath('mod_rsgallery2_random_images', 'vertical');
} else {
	require JModuleHelper::getLayoutPath('mod_rsgallery2_random_images', 'horizontal');
}



