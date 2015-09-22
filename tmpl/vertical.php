<?php
/**
* RSGallery2 random images module: shows random images from the Joomla extension RSGallery2 (www.rsgallery2.nl).
* @copyright (C) 2012 RSGallery2 Team
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
**/

defined('_JEXEC') or die('Restricted access');
?>
<div class="mod_rsgallery2_random_images">
	<table class="mod_rsgallery2_random_images_table">
		<?php
		foreach ($randomImages as $image) {
			$filename_a       = $image->name;
			$title_a          = $image->title;
			$description_a    = $image->descr;
			$id_a             = $image->id;
			$limitstart_a     = $image->ordering - 1;
			$catid_a          = $image->gallery_id;
			?>
			<tr>
				<td>
					<div class="mod_rsgallery2_random_images-shadow">
						<a href="<?php echo JRoute::_('index.php?option=com_rsgallery2&page=inline&id='.$id_a.'&Itemid='.$RSG2Itemid);?>">
							<img src="<?php echo imgUtils::getImgThumb($filename_a); ?>" alt="<?php echo $title_a; ?>" border="0" />
						</a>
					</div>
				</td>
			</tr>
		<?php 
		}
		?>
	</table>
</div>

