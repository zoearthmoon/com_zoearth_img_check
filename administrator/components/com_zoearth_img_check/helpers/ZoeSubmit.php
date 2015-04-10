<?php
/**
 * @author      Zoearth
 */
 
class ZoeSubmit
{
    static public function _($option=array())
    {
        $onclick = '';
        if ($option['task'] == 'delete' && $option['guid'] > 0 )
        {
            $onclick .= 'ZoeDelete('.$option['guid'].');';
        }
        elseif ($option['task'] == 'active' && $option['guid'] > 0 )
        {
            $onclick .= 'ZoeActive('.$option['guid'].');';
        }
        else
        {
            foreach ($option as $key=>$val)
            {
                $onclick .= 'document.adminForm.'.$key.'.value=\''.$val.'\';';
            }
            $onclick .= 'Joomla.submitform();';
        }
        return $onclick;
    }
}