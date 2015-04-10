<?php
/**
 * @author      Zoearth
 */
 
class ZoeHtml
{
    static public function editor($name,$text='',$with="100%",$height="400px")
    {
        $wysiwyg = JFactory::getEditor();
        return $wysiwyg->display($name,$text,$with,$height,'','',array ('article','pagebreak','readmore'));
    }
    
    //20140429 zoearth Modal 功能
    static public function modal($option=array())
    {
        if (!(isset($option['key']) && $option['key'] != ''))
        {
            JError::raiseWarning(100,'ZoeHtml::modal 缺少 key 參數');
            return FALSE;
        }
        if (!(isset($option['bind']) && $option['bind'] != ''))
        {
            JError::raiseWarning(100,'ZoeHtml::modal 缺少 bind 參數');
            return FALSE;
        }
        
        $key   = $option['key'];
        $title = $option['title'];
        $bind  = $option['bind'];
        
        $top   = (isset($option['top']) ? $option['top']:'10%');
        $left  = (isset($option['left']) ? $option['left']:'50%');
        $width = (isset($option['width']) ? $option['width']:'560px');
        $height= (isset($option['height']) ? $option['height']:'80%');
        
        //20140430 zoearth 從bind物件取得值
        $getData = array();
        if (isset($option['addData']))
        {
            if (is_array($option['addData']))
            {
                $getData = $option['addData'];
            }
            else
            {
                $getData[] = $option['addData'];
            }
        }
        
        ?>
        <script language="Javascript">
        function <?php echo $key?>Init(){
            jQuery("<?php echo $bind?>").unbind('click').bind('click',function(){
                //20140430 zoearth 把值放進隱藏欄位
                <?php foreach ($getData as $name):?>
                    jQuery('#<?php echo $key.'_'.$name?>').val(jQuery(this).attr('<?php echo $name?>'));
                <?php endforeach;?>

                //20140520 zoearth 呼叫事件
                jQuery('#<?php echo $key?>Modal').unbind('show').on('show', function () {
                    if (typeof <?php echo $key?>ModalShow == 'function')
                    {
                        <?php echo $key?>ModalShow(get<?php echo $key?>Data());
                    }
                });
                jQuery('#<?php echo $key?>Modal').unbind('shown').on('shown', function () {
                    if (typeof <?php echo $key?>ModalShown == 'function')
                    {
                        <?php echo $key?>ModalShown(get<?php echo $key?>Data());
                    }
                });
                jQuery('#<?php echo $key?>Modal').unbind('hide').on('hide', function () {
                    if (typeof <?php echo $key?>ModalHide == 'function')
                    {
                        <?php echo $key?>ModalHide(get<?php echo $key?>Data());
                    }
                });
                jQuery('#<?php echo $key?>Modal').unbind('hidden').on('hidden', function () {
                    if (typeof <?php echo $key?>ModalHidden == 'function')
                    {
                        <?php echo $key?>ModalHidden(get<?php echo $key?>Data());
                    }
                });

                //20140520 zoearth 顯示modal
                jQuery('#<?php echo $key?>Modal').modal({keyboard: false},'toggle');
                
            });
        }
        function get<?php echo $key?>Data()
        {
            //20140430 zoearth 取得隱藏欄位
            var data = {};
            <?php foreach ($getData as $name):?>
                data.<?php echo $name?> = jQuery('#<?php echo $key.'_'.$name?>').val();
            <?php endforeach;?>
            return data;
        }
        jQuery(document).ready(function() {
            <?php echo $key?>Init();
        });
        </script>
        <!-- Modal -->
        <?php foreach ($getData as $name):?>
            <input type="hidden" id="<?php echo $key.'_'.$name?>" value="">
        <?php endforeach;?>
        <div style="left:<?php echo $left;?>;width:<?php echo $width;?>;height:<?php echo $height;?>;"  id="<?php echo $key?>Modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel"><?php echo $title;?></h3>
            </div>
            <div id="<?php echo $key?>ModalBody" class="modal-body">
            </div>
        </div>
        <?php
    }
}