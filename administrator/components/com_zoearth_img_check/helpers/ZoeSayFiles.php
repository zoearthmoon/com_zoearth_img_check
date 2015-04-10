<?php
/** 
 * @author      Zoearth
 */

class ZoeSayFiles
{
    static public function SayFiles($data=array(),$isEdit=TRUE,$showPic=FALSE)
    {
        $type = strtolower(substr($data['fileName'],-3,3));
        switch ($type)
        {
            case "jpg":
            case "png":
            case "gif":
            case "bmp":
                $icon = '<i class="icon-picture"></i>';break;
            default:
                $icon = '<i class="icon-file"></i>';break;
        }
        
        $data['note'] = nl2br(str_replace('"','',$data['note']));
        $html = '<div class="btn-group" id="fileSpan'.$data['guid'].'" >';
        if ($showPic && in_array($type,array('jpg','png','gif','bmp')))
        {
            $html .= '<a class="btn btn-primary sayFiles" target="_blank" data-trigger="hover" rel="popover" 
                        data-content="'.$data['note'].'" title="'.$data['note'].'" 
                        href="'.CONTROLLER_BASE.'&view=Files&task=getFiles&guid='.$data['guid'].'&fileName='.$data['fileName'].'" >
                    <img class="img-rounded" style="max-width:150px;max-height:150px;" 
                        src="'.CONTROLLER_BASE.'&view=Files&task=getFiles&guid='.$data['guid'].'&fileName='.$data['fileName'].'" ></a>';
        }
        else
        {
            $html .= '<a class="btn btn-primary sayFiles" target="_blank" data-trigger="hover" rel="popover" 
                    data-content="'.$data['note'].'" title="'.$data['note'].'" 
                    href="'.CONTROLLER_BASE.'&view=Files&task=getFiles&guid='.$data['guid'].'&fileName='.$data['fileName'].'" >';
            $html .= $icon;
            $html .= $data['fileName'].'</a>';
        }
        $html .= '</div>';
        return $html;
    }
}