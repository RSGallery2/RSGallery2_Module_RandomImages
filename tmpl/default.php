<?php
/**
* RSGallery2 random images module: shows random images from the Joomla extension RSGallery2 (www.rsgallery2.org).
* @copyright (C) 2012-2018 RSGallery2 Team
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
**/

defined('_JEXEC') or die();

?>

<div class="mod_rsgallery2_latest_images">
	<table class="mod_rsgallery2_latest_images_table" >
		<?php 
		$ItemIdx = 0; 
		for ($row = 1; $row <= $countRows; $row++) {
			// If there still is am image to show, start a new row
			if (!isset($randomImages[$ItemIdx])) {
				break;
			}
			
			echo '<tr>';
			for ($column = 1; $column <= $countColumns; $column++) {
                $HTML = '';
				echo '<td>';
				// If there still is a gallery image to show, show it, otherwise, continue
				if (!isset($randomImages[$ItemIdx])) {
					break;
				}

				$image = $randomImages[$ItemIdx];
				// Get the name of the item to show
				$ItemIdxName = $image['name'];

				// Click on image shall link to a destination
				if ($ImageLinkType > 0)
				{
                    $url = '';
                    // destination ?
                    // echo "\$ImageLinkType".$ImageLinkType;
                    switch ($ImageLinkType)
                    {
                        case 1: // Link to gallery all images table view
                            $url = $Rsg2ImageRoutes->Link2ParentGallery ($image['gallery_id'], ($image['ordering'] -1));
                            break;
                        case 2: // Link to gallery single image view
                            $url = $Rsg2ImageRoutes->Link2GallerySingleImageView ($image['gallery_id'], $image['id']);
                            break;
                        case 3: // Link to gallery slide show
                            $url = $Rsg2ImageRoutes->Link2GallerySlideshowView($image['gallery_id']);
                            break;
                        case 4: // Link to image original size
                            $url = $Rsg2ImageRoutes->Link2ImageOriginalSize ($image['gallery_id'], $image['name']);
                            break;
                        case 5: // Link to image display size
                            $url = $Rsg2ImageRoutes->Link2ImageDisplaySize ($image['gallery_id'], $image['name']);
                            break;
                        case 6: // Link to image thumb size
                            $url = $Rsg2ImageRoutes->Link2ImageThumbSize ($image['gallery_id'], $image['name']);
                            break;
						case 7 : // Test 01	no inline 
							//<a href="..php echo JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$id_a.'&Itemid='.$RSG2Itemid);..>">
							////	<img src="<..php echo imgUtils::getImgThumbPath($filename_a); ..>" alt="<..php echo $title_a; ..>" border="0" />
							//	<img src="<..php echo imgUtils::getImgThumb($filename_a); ..>" alt="<..php echo $title_a; ..>" border="0" />
							//</a>
							// JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$id_a.'&Itemid='.$RSG2Itemid)
							$url = JRoute::_('index.php?option=com_rsgallery2'
								.'&id='.$image['id']
								.'&Itemid='.$Rsg2ImageRoutes->getRsg2MenuId());
							
                            break;
						case 8 : // Test 02
							//<a href="<..php echo JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$id_a.'&catid='.$catid_a.'&limitstart='.$limitstart_a.'&Itemid='.$RSG2Itemid);..>">
							////<img src="<..php echo imgUtils::getImgThumbPath($filename_a); ..>" alt="<..php echo $title_a; ..>" border="0" />
							//<img src="<..php echo imgUtils::getImgThumb($filename_a); ..>" alt="<..php echo $title_a; ..>" border="0" />
							//</a>
							// JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$id_a.'&catid='.$catid_a.'&limitstart='.$limitstart_a.'&Itemid='.$RSG2Itemid);
							$url = JRoute::_('index.php?option=com_rsgallery2'
								.'&page=inline'  // difffers to above
								.'&id='.$image['id']
//								.'&catid='.$catid_a
//								.'&limitstart='.$limitstart_a
								.'&Itemid='.$Rsg2ImageRoutes->getRsg2MenuId());
						
                            break;
                    }

                    $HTML .= '<a href="'.JRoute::_($url).'">';  // ToDo: Title ...
				}
				
				// Create HTML for image: get the url (with/without watermark) with img attributes
				if ($displayType == 1) {
					// *** display ***: 
					$watermark = $rsgConfig->get('watermark');
					//$imageUrl = $watermark ? waterMarker::showMarkedImage( $ItemIdxName ) : imgUtils::getImgDisplayPath( $ItemIdxName );
					$imageUrl = $watermark ? waterMarker::showMarkedImage( $ItemIdxName ) : imgUtils::getImgDisplay( $ItemIdxName );
					$HTML .= '<img class="rsg2-displayImage" src="'.$imageUrl.'" alt="'.$ItemIdxName.'" title="'.$ItemIdxName.'" '.$imgAttributes.'/>';
				} elseif ($displayType == 2) {
					// *** original ***
					$watermark = $rsgConfig->get('watermark');
					//$imageOriginalUrl = $watermark ? waterMarker::showMarkedImage( $ItemIdxName, 'original' ) : imgUtils::getImgOriginalPath( $ItemIdxName );
					$imageOriginalUrl = $watermark ? waterMarker::showMarkedImage( $ItemIdxName, 'original' ) : imgUtils::getImgOriginal( $ItemIdxName );
					$HTML .= '<img class="rsg2-displayImage" src="'.$imageOriginalUrl.'" alt="'.$ItemIdxName.'" title="'.$ItemIdxName.'" '.$imgAttributes.'/>';
				} else {
					// *** thumb ***
					//$imageThumbUrl = imgUtils::getImgThumbPath( $ItemIdxName );
					$imageThumbUrl = imgUtils::getImgThumb( $ItemIdxName );
					$HTML .= '<img class="rsg2-displayImage" src="'.$imageThumbUrl.'" alt="'.$ItemIdxName.'" title="'.$ItemIdxName.'" '.$imgAttributes.'/>';
				}
				$name	= $image['name'];
				$date	= $image['date'];

				// Click on image shall lead to gallery view
                if ($ImageLinkType > 0)
				{
					$HTML .= "</a>";
				}

//                echo '<br>(3)\$HTML: "'.htmlentities($HTML).'"<br> ';
				// Show it
			?>
				<div class="mod_rsgallery2_latest_images_attibutes" <?php echo $divAttributes;?>>
					<div class="mod_rsgallery2_latest_images-cell">
							<?php echo $HTML;?>
					</div>
					
					<div style="clear:both;"></div>
                <?php
					if ($displayName) {
				?>
						<div class="mod_rsgallery2_latest_images_name" <?php echo $divNameAttributes;?>>
							<?php echo $name;?>
						</div>
				<?php
					}
					if ($displayDate) {
				?>
						<div class="mod_rsgallery2_latest_images_date">
							<?php echo date($dateFormat,strtotime($date));  ?>
						</div>
				<?php
					}
				?>
				</div>
	<?php
				$ItemIdx++;
				echo '</td>';
			}	
			echo '</tr>';
		}
		
	?>
	</table>
	<table class="mod_rsgallery2_latest_images_table" >
	<?php
	?>
	</table>
</div>


