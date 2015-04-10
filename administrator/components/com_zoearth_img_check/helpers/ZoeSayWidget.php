<?php
/**
 * @author      Zoearth
*/
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.COM_NAME.DS.'models', MODULE_NAME.'Model');

class ZoeSayWidget
{
    static public function sayWidget($options=array())
    {
        $types = array('PieChart','AreaChart');
        $dataTypes = array();
        $dataTypes[] = 'money';//借款金額
        $dataTypes[] = 'record';//件數
        $dataTypes[] = 'source';//進件商
        $dataTypes[] = 'bank';//銀行
        
        if (!(@$options['id'] != '' && @$options['type'] != '' && in_array($options['type'],$types) && in_array($options['dataType'],$dataTypes)))
        {
            return '<div class="alert">錯誤!小工具缺少類型!</div>';
        }
        if (@$options['model'] == '' || @$options['controller'] == '' || @$options['action'] == '')
        {
            return '';
        }
        //$options['url'] = $options['model'].'/'.$options['controller'].'/'.$options['action'].'/dataType/'.$options['dataType'];
        $options['url'] = Juri::base().'index.php?option='.$options['model'].'&view='.$options['controller'].'&task='.$options['action'].'&dataType='.$options['dataType'];
        
        $document = JFactory::getDocument();
        $document->addScript('https://www.google.com/jsapi', 'text/javascript');
        $document->addScript('components/com_loan_customer/media/js/zoe_widget.js', 'text/javascript');

        //http://localhost/joomla3/administrator/index.php?
        //option=com_loan_customer&
        //view=LoanRecord&
        //task=widget&
        //dataType=money&
        //sdate=2013-11&
        //edate=2014-05&_=1400663226733
        
        return self::frame($options);
    }
    
    static public function frame(&$options=array())
    {
        $html = '';
        $html = '
        <script language="Javascript">
        //20140415 zoearth 取得設定
        jQuery(document).ready(function() {
            zpShowWidget'.@$options['id'].'();
        });
        function zpShowWidget'.@$options['id'].'()
        {
            var options = JSON.parse(\''.json_encode($options).'\');
            var chart = new google.visualization.'.@$options['type'].'(document.getElementById(\''.@$options['id'].'\'));
            
            var goData = setupWidgetData(options);
            //console.log(goData);
            var data = google.visualization.arrayToDataTable(goData);
            chart.draw(data,setupOptions(options));
        }
        </script>';
        
        $html .= '
        <div class="span'.@$options['span'].'">
        <div class="widget-box">
            <div id="'.$options['id'].'Setup" class="widget-header widget-header-flat widget-header-small">
                <h5>
                    <i class="'.@$options['icon'].'"></i>
                    '.$options['title'].'
                </h5>
                '.self::searchOption($options).'
            </div>
            <div class="widget-body">
                <div class="widget-main '.$options['id'].'Setup">
                    <div id="'.$options['id'].'"></div>
                </div>
            </div>
        </div>
        </div>';
        return $html;
    }
    
    //20140416 zoearth 搜尋選項
    static public function searchOption($options=array())
    {
        $html = '';
        switch ($options['dataType'])
        {
            case 'money';//車輛採購單
                $html .= '
                <div class="input-prepend">
                    <span class="add-on" >貸款日</span>
                    <input class="searchInput date-picker" id="sdate" name="sdate" type="text" value="'.date("Y-m",strtotime("-6 month")).'" placeholder="起始時間">
                    <span class="add-on" >到</span>
                    <input class="searchInput date-picker" id="edate" name="edate" size="16" type="text" value="'.date("Y-m").'" placeholder="結束時間" >
                    <button class="btn btn-primary" type="button" onclick="zpShowWidget'.@$options['id'].'();"><i class="icon-search"></i></button>
                </div>';
                break;
            case 'record';//車輛銷售單
                $html .= '
                <div class="input-prepend">
                    <span class="add-on" >日期</span>
                    <input class="searchInput date-picker" id="sdate" name="sdate" type="text" value="'.date("Y-m",strtotime("-6 month")).'" placeholder="起始時間">
                    <span class="add-on" >到</span>
                    <input class="searchInput date-picker" id="edate" name="edate" size="16" type="text" value="'.date("Y-m").'" placeholder="結束時間" >
                    <button class="btn btn-primary" type="button" onclick="zpShowWidget'.@$options['id'].'();"><i class="icon-search"></i></button>
                </div>';
                break;
            case 'source';//進件商
                //20140522 zoearth 選擇銀行
                $LoanSource_DB = JModelLegacy::getInstance('LoanSource', 'PlusNewsPaperModel');
                $sourceOptions = $LoanSource_DB->getAll();
                $selectHtml = '';
                $selectHtml .= '<select class="searchInput" name="guid" >';
                foreach ($sourceOptions as $row)
                {
                    $selectHtml .= '<option value="'.$row['guid'].'">'.$row['name'].'</option>';
                }
                $selectHtml .= '</select>';
                $html .= '
                <div class="input-prepend">
                    <span class="add-on" >日期</span>
                    <input class="searchInput date-picker" id="sdate" name="sdate" type="text" value="'.date("Y-m",strtotime("-6 month")).'" placeholder="起始時間">
                    <span class="add-on" >到</span>
                    <input class="searchInput date-picker" id="edate" name="edate" size="16" type="text" value="'.date("Y-m").'" placeholder="結束時間" >
                    <span class="add-on" >進件商</span>
                    '.$selectHtml.'
                    <button class="btn btn-primary" type="button" onclick="zpShowWidget'.@$options['id'].'();"><i class="icon-search"></i></button>
                </div>';
                break;
            case 'bank';//銀行
                //20140522 zoearth 選擇銀行
                $LoanBank_DB = JModelLegacy::getInstance('LoanBank', 'PlusNewsPaperModel');
                $bankOptions = $LoanBank_DB->getAll();
                $selectHtml = '';
                $selectHtml .= '<select class="searchInput" name="guid" >';
                foreach ($bankOptions as $row)
                {
                    $selectHtml .= '<option value="'.$row['guid'].'">'.$row['name'].'</option>';
                }
                $selectHtml .= '</select>';
                $html .= '
                <div class="input-prepend">
                    <span class="add-on" >日期</span>
                    <input class="searchInput date-picker" id="sdate" name="sdate" type="text" value="'.date("Y-m",strtotime("-6 month")).'" placeholder="起始時間">
                    <span class="add-on" >到</span>
                    <input class="searchInput date-picker" id="edate" name="edate" size="16" type="text" value="'.date("Y-m").'" placeholder="結束時間" >
                    <span class="add-on" >銀行</span>
                    '.$selectHtml.'
                    <button class="btn btn-primary" type="button" onclick="zpShowWidget'.@$options['id'].'();"><i class="icon-search"></i></button>
                </div>';
                break;
        }
        return $html;
    }
    
    //20140522 zoearth 整理陣列
    static public function arraySetup($input=array())
    {
        $output = array();
        $firstCount = 0;
        foreach ($input as $key=>$val)
        {
            if (!($firstCount > 0 ))
            {
                $firstCount = count($val);
                $output[] = $val;
            }
            else
            {
                //20140522 zoearth 確認陣列長度一樣
                for ($i=0;$i<$firstCount;$i++)
                {
                    if (!isset($val[$i]))
                    {
                        $val[$i] = 0;
                    }
                }
                ksort($val);
                //20140522 zoearth 重新整理陣列
                $newVal = array();
                foreach ($val as $goVal)
                {
                    $newVal[] = $goVal;
                }
                $output[] = $newVal;
            }
        }
        return $output;
    }
}