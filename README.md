# RSGallery2_Module_RandomImages
The RSGallery2 Module RandomImages shows as the name suggests random images from RSGallery2 galleries.

* The images are shown in a table view. The number of rows and columns define the the number of shown images.
* The administrator can specify certain galleries as source for the images to show. Sub galleries may be included.
* The administrator can select a display type, if the thumb-, the rsgallery2- or the original size of the image shall be used as a source for the display.
* The display width of the image and the surrounding Div may be set.
* The name of the gallery and the date of the image may be displayed.

* A click on the image may lead to
  * The parent gallery
	* Gallery single image view.
	* Gallery slideshow view
	* Original image
	* Gallery display image
	* Gallery thumb image

##General
 * User defined count images are selected and shown like in the RSGallery2 gallery view.
 * The images may be aligned in row and columns.
 * The sources to look for the images are all galleries or a defined list of galleries.
 * The displayed image size may may origin from the thumb-, the orginal- or the rsgallery display size.
 * The resulting set may be "garnered" with CSS styles set in the backend.
 * Each image may lead to the origin picture or the parent gallery or ...

##Parameter in backend

* **Number of rows:** Number of vertical images  (Images per column)  
* **Number of columns:** Number of horizontal images (Images per row)  
The number of displayed images is the result from Count = row number times column number. Therefore Count image names are fetched from the Database and prepared for the display  
* **Select galleries to show images from:** e.g. 3,8,42. The used galleries may be restricted to a selection. Please use a comma separated list. Select galleries to show images from where multiple galleries are separated by a comma, e.g. '3,8,42' or '5'. If you want to show images from all galleries enter '0'. The access level of user will be taken into account, so if a user is not allowed to see gallery 2 its images won't show even if you put it here.The gallery id numbers to use can be found here: Backend > Components > RSGallery2 > Galleries, in the column ID  
* **Include subgalleries:** (No/Yes)  When the range of galleries is limited by user input (see above) the child galleries may be included in the image search  
* **Display type of image to show:**  RSGallery2 stores the original (depending on settings), a display sized image and a thumbnail sized image. User can select which display type is to be used.
* **CSS img element height parameter:**  Enter the size of the CSS height attribute of the image to show in pixels. If '0' is entered there won't be a height specified for img element and the actual height of the image will be taken.  
* **CSS img element width parameter:** Enter the size of the CSS width attribute of the image to show in pixels. If '0' is entered there won't be a width specified for img element and the actual width of the image will be taken  
CSS width attribute for the img and the div element (0=no attribute)
Set size of image, use one (!) of height or width to make the image smaller. Set one or both settings for image height and width to 0, this will keep the aspect ratio of the image, if you set a size for both the image will be stretched. Do not set the size larger than the actual image size, e.g. when a thumb size image has a size of 50x50 pixels, don't set either size to larger than 50, rather use the larger display or the original image
* **CSS div element height parameter:** Set size of div, use this to crop the image. Enter the size of the CSS height attribute of the div that holds the image in pixels. This will crop the image. If '0' is entered there won't be a height specified for the div element and it will fit the actual height of the image  
* **CSS div element width parameter:** Set size of div, use this to crop the image. Enter the size of the CSS width attribute of the div that holds the image in pixels. This will crop the image. If '0' is entered there won't be a width specified for the div element and it will fit the actual width of the image
* **CSS div element height parameter for name of image:** CSS div element height parameter for name of image. Enter the size of the CSS height attribute of the dive that holds the name of the image, in pixels. If '0' is entered there won't be a height specified for the div element.<br />There is no width parameter to set the width of this div element since the width of the 'CSS div element width parameter' would overrule this   
* **Display image name:** (No/Yes) Do display image name below the image   
* **Display date:** (No/Yes) Do display image date below the image
* **Date format:** Select format type the date should display (for example, d-m-Y | d/m/Y | m-d-Y : G-i). See http://nl3.php.net/date for more info about this.
* **Link image to view type**  A click on the image shall link to the selected type. Single image/gallery view: The display area will be found in the first joomla menu linking to RSGallery2 galleries
Possible options  
   * Don't link image to anything  
   * Link to standard gallery view  
   * Link to single image view of gallery  
   * Link to slideshow view of gallery  
   * Link to original image (user) size  
   * Link to display image (RSGallery2) size  
   * Link to thumb image (RSGallery2) size  
