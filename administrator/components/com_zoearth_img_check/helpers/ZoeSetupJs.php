<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ZoeSetupJs
{
    static function validate()
    {
        $document = JFactory::getDocument();
        $document->addScript('components/'.COM_NAME.'/media/js/jquery.validate.min.js', 'text/javascript');
        $document->addScript('components/'.COM_NAME.'/media/js/jquery.validate.default.js', 'text/javascript');
    }
    
    static function datePicker()
    {
        $document = JFactory::getDocument();
        $document->addScript('components/'.COM_NAME.'/media/js/jquery.datetimepicker.js', 'text/javascript');
        $document->addScript('components/'.COM_NAME.'/media/js/jquery.datetimepicker.setup.js', 'text/javascript');
        $document->addStyleSheet('components/'.COM_NAME.'/media/js/jquery.datetimepicker.css');
    }
    
    static function lightBox()
    {
        $document = JFactory::getDocument();
        $document->addScript('components/'.COM_NAME.'/media/js/lightbox/jquery.magnific-popup.min.js', 'text/javascript');
        $document->addStyleSheet('components/'.COM_NAME.'/media/js/lightbox/magnific-popup.css');
    }
}