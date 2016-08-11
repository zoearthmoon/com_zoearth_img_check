<?php
defined('_JEXEC') or die ('Restricted access');

//這邊的動作是單純設定html
class plgSysTemZoearth_Img_Flow_Page extends JPlugin 
{
    function onAfterRender()
    {
        if (Z2HelperQueryData::isSite() && @$_GET['cccc'] == '1')
        {
            require_once JPATH_SITE.DS.'plugins'.DS.'z2'.DS.'zoearth_img_flow'.DS.'zoearth_img_flow_helper.php';
            $response  = JResponse::getBody();
            $setBody   = FALSE;
            //轉換頁面
            $response  = ZoearthImgFlowHelper::render($response,$setBody);
            if ($setBody)
            {
                JResponse::setBody($response);
            }
        }
    }
}