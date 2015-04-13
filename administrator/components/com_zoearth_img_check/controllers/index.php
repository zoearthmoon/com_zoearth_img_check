<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ZoearthImgCheckControllerIndex extends JControllerLegacy
{
    function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect('index.php?option=com_zoearth_img_check&view=check','', 'notice');
    }
}