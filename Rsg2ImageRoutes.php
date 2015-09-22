<?php
/**
 * Class Rsg2ImageRoutes
 * Contains functions to create jroute(s) url paths to different image views
 * The functions return links to be used in Jroute() to image views inside galleries
 * Example: image from latest image view need <A> link to parent gallery
 * @copyright (C) 2015 RSGallery2 Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version 4.0.6
 */
class Rsg2ImageRoutes
{
    /**
     * @var int|null $RSG2MenuId
     */
    public $RSG2MenuId = Null;

    /**
     * Get RSGallery2 Itemid from first component menu item for use in links
     * Use PageIdx if the needed menu item is not the first one
     * @param int $PageIdx Wanted to nth-1 occurrence of gallery view in joomla menu
     * @return int|null
     */
    public function getRsg2MenuId ($PageIdx=0)
    {
        $this->RSG2MenuId = Null;

        $database = JFactory::getDbo();
        $query = $database->getQuery(true);

        $query->select('id')
            ->from('#__menu')
            ->where('published = 1')
        //->where($db->quoteName('published') . ' LIKE '. $db->quote('\'%1%\''));
        ->where("link like 'index.php?option=com_rsgallery2%'")
            ->order('link');
        $database->setQuery($query);

        try {
            $RSG2ItemIdObj = $database->loadObjectList();
            if (count($RSG2ItemIdObj) > $PageIdx) {
                $this->RSG2MenuId = $RSG2ItemIdObj[$PageIdx]->id;
            }
        }
        catch (Exception $e)
        {
            // ToDo:  enque message
            // echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
        }

        return $this->RSG2MenuId;
    }


    /* ToDo: check following to improve above */


    private function Rsg2MenuIdTmp ()
    {
//------------------------------------------------------------------------
        $db =& JFactory::getDBO();

        // for sef ?
        $lang =& JFactory::getLanguage()->getTag();
        $uri = 'index.php?option=com_search&view=search';

        $db->setQuery('SELECT id FROM #__menu WHERE link LIKE ' . $db->Quote($uri . '%')
            . ' AND language=' . $db->Quote($lang) . ' LIMIT 1');

        $itemId = ($db->getErrorNum()) ? 0 : intval($db->loadResult());
//------------------------------------------------------------------------
        $db = JFactory::getDBO();
        $defaultRedirect = 'index.php?option=com_myapp&view=cpanel';
        $db->setQuery('SELECT `id` FROM #__menu WHERE `link` LIKE '
            . $db->Quote($defaultRedirect) . ' LIMIT 1');
        $itemId = ($db->getErrorNum()) ? 0 : intval($db->loadResult());
        if ($itemId) {
            $rpath = JRequest::getString('return',
                base64_encode(JRoute::_('index.php?Itemid=' . $itemId)));
        } else {
            $rpath = JRequest::getString('return',
                base64_encode(JRoute::_('index.php?option=com_myapp&view=cpanel')));
        }
    }

    /**
     * Link to parent gallery view with image set including given image
     * @param int $GalleryId parent gallery id of image
     * @param int $ImagePosition Position of image when available
     *            images are displayed. Example: on first page are 9
     *            pictures displayed ($ImagePosition 0..8). For the
     *            first image on page two the $ImagePosition is 9.
     *            Use sql image order -1 -> $image['ordering'] -1
     *            $ImagePosition != $ImageId as some images may be
     *            deleted already
     * @return string link for Jroute
     */
    public function Link2ParentGallery ($GalleryId, $ImagePosition)
    {
        $url = '';

        // Joomla gallery menu part
        if ($this->RSG2MenuId == Null)
        {
            $this->getRsg2MenuId ();
        }

        $url = 'index.php?option=com_rsgallery2'
            . '&Itemid='.$this->RSG2MenuId
            . '&catid=' . $GalleryId
            . '/itemPage/'.$ImagePosition
        ;

        return $url;
    }

    /**
     * Link to gallery single image view
     * @param int $GalleryId parent gallery id of image
     * @param int $ImageId image id
     * @return string link for Jroute
     */
    public function Link2GallerySingleImageView ($GalleryId, $ImageId)
    {
        $url = '';

        // Joomla gallery menu part
        if ($this->RSG2MenuId == Null)
        {
            $this->getRsg2MenuId ();
        }

        $url = 'index.php?option=com_rsgallery2'
            . '&Itemid='.$this->RSG2MenuId
            . '&catid=' . $GalleryId
            . '&id=' . $ImageId
        ;

        return $url;
    }

    /**
     * Link to gallery single image view
     * @param int $GalleryId parent gallery id of image
     * @return string link for Jroute
     */
    public function Link2GallerySlideshowView ($GalleryId)
    {
        $url = '';

        // Joomla gallery menu part
        if ($this->RSG2MenuId == Null)
        {
            $this->getRsg2MenuId ();
        }

        $url = 'index.php?option=com_rsgallery2'
            . '&Itemid='.$this->RSG2MenuId
            . '&catid=' . $GalleryId
            . '/asSlideshow'
        ;

        return $url;
    }



    /**
     * Link to gallery single image view
     * @param int $GalleryId parent gallery id of image
     * @param int $ImageId image id
     * @return string link for Jroute
     */
    public function Link2ImageOriginalSize ($GalleryId, $imageName)
    {
        $url = '';

        // Joomla gallery menu part
        if ($this->RSG2MenuId == Null)
        {
            $this->getRsg2MenuId ();
        }

        // echo ($imageName);
        //http://localhost/joomla3x/images/rsgallery/original/DSC_0849.jpg
        //localhost/joomla3x/images/rsgallery//DSC_0849.jpg.jpg
        $url = 'images/rsgallery/original/'
            . $imageName
        ;


        return $url;
    }

    /**
     * Link to gallery single image view
     * @param int $GalleryId parent gallery id of image
     * @param int $ImageId image id
     * @return string link for Jroute
     */
    public function Link2ImageDisplaySize ($GalleryId, $imageName)
    {
        $url = '';

        // Joomla gallery menu part
        if ($this->RSG2MenuId == Null)
        {
            $this->getRsg2MenuId ();
        }

        //http://localhost/joomla3x/images/rsgallery/display/DSC_0849.jpg.jpg
        $url = 'images/rsgallery/display/'
            . $imageName
            . ".jpg"
        ;


        return $url;
    }

    /**
     * Link to gallery single image view
     * @param int $GalleryId parent gallery id of image
     * @param int $ImageId image id
     * @return string link for Jroute
     */
    public function Link2ImageThumbSize ($imageName)
    {
        $url = '';

        // Joomla gallery menu part
        if ($this->RSG2MenuId == Null)
        {
            $this->getRsg2MenuId ();
        }

        //http://localhost/joomla3x/images/rsgallery/thumb/DSC_0849.jpg.jpg
        $url = 'images/rsgallery/thumb/'
            . $imageName
            . ".jpg"
        ;

        return $url;
    }





    public function __toString()
    {
        $OutTxt = 'Rsg2ImageRoutes::$RSG2MenuId= "';
        $OutTxt .= ($this->RSG2MenuId === null) ? 'null' : $this->RSG2MenuId;
        $OutTxt .= '"';

        return $OutTxt;
    }



}