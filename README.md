# RSGallery2_Module_RandomImages
The RSGallery2 Module RandomImages shows as the name suggests random images from RSGallery2 galleries.

* .
* .
* .
* .

##General

* .
* .
* .
* .

##Parameter in backend

* **Number of rows:** Number of vertical images  (Images per column)  
* **Number of columns:** Number of horizontal images (Images per row)  
The number of displayed images is the result from Count = row number times column number. Therefore Count image names are fetched from the Database and prepared for the display  
* **Select galleries to show images from:** e.g. 3,8,42. The used galleries may be restricted to a selection. Please use a comma separated list. Select galleries to show images from where multiple galleries are separated by a comma, e.g. '3,8,42' or '5'. If you want to show images from all galleries enter '0'. The access level of user will be taken into account, so if a user is not allowed to see gallery 2 its images won't show even if you put it here.The gallery id numbers to use can be found here: Backend > Components > RSGallery2 > Galleries, in the column ID  
...

