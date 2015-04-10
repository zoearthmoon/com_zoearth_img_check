<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ZoearthTwcnChangeControllerIndex extends JControllerLegacy
{
    function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect('index.php?option=com_zoearth_twcn_change&view=AddWord','', 'notice');
    }
}