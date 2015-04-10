<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ActionList
{
    public static function showArray()
    {
        $con = array();       
        //20140425 zoearth
        $con['LoanList']['title'] = "借貸申請管理";
        $con['LoanList']['action']['index']  = "借貸申請列表";
        $con['LoanList']['action']['add'] = "借貸申請新增";
        $con['LoanList']['action']['modify'] = "借貸申請更新";
        $con['LoanList']['action']['delete'] = "借貸申請刪除";

        $con['LoanRecord']['title'] = "借貸申請管理";
        $con['LoanRecord']['action']['index']  = "借貸申請列表";
        $con['LoanRecord']['action']['add'] = "借貸申請新增";
        $con['LoanRecord']['action']['modify'] = "借貸申請更新";
        $con['LoanRecord']['action']['delete'] = "借貸申請刪除";

        $con['LoanBank']['title'] = "銀行管理";
        $con['LoanBank']['action']['index']  = "銀行列表";
        $con['LoanBank']['action']['add'] = "銀行新增";
        $con['LoanBank']['action']['modify'] = "銀行更新";
        $con['LoanBank']['action']['delete'] = "銀行刪除";
        
        $con['LoanSource']['title'] = "進件商管理";
        $con['LoanSource']['action']['index']  = "進件商列表";
        $con['LoanSource']['action']['add'] = "進件商新增";
        $con['LoanSource']['action']['modify'] = "進件商更新";
        $con['LoanSource']['action']['delete'] = "進件商刪除";

        $con['LoanSales']['title'] = "業務管理";
        $con['LoanSales']['action']['index']  = "業務列表";
        $con['LoanSales']['action']['add'] = "業務新增";
        $con['LoanSales']['action']['modify'] = "業務更新";
        $con['LoanSales']['action']['delete'] = "業務刪除";
        
        $con['LoanServiceItem']['title'] = "服務項目管理";
        $con['LoanServiceItem']['action']['index']  = "服務項目列表";
        $con['LoanServiceItem']['action']['add'] = "服務項目新增";
        $con['LoanServiceItem']['action']['modify'] = "服務項目更新";
        $con['LoanServiceItem']['action']['delete'] = "服務項目刪除";
        
        $con['LoanCustomer']['title'] = "客戶管理";
        $con['LoanCustomer']['action']['index']  = "客戶列表";
        $con['LoanCustomer']['action']['add'] = "客戶新增";
        $con['LoanCustomer']['action']['modify'] = "客戶更新";
        $con['LoanCustomer']['action']['delete'] = "客戶刪除";
        
        $con['LoanSituation']['title'] = "申請情況類別";
        $con['LoanSituation']['action']['index']  = "情況列表";
        $con['LoanSituation']['action']['add'] = "情況新增";
        $con['LoanSituation']['action']['modify'] = "情況更新";
        $con['LoanSituation']['action']['delete'] = "情況刪除";
        
        return $con;
    }
    
    public static function showMenuArray()
    {
        $menu = array();
        $i = 0;
        
        
        $menu[$i]['name'] = "借貸申請";
        $menu[$i]['icon'] = "icon-map-marker";
        $menu[$i]['controllers'] = array(
                'LoanRecord',
                'LoanCustomer',
                'LoanBank',
                'LoanSource',
                'LoanSales',
                'LoanSituation',
                //'LoanServiceItem',
                );
        
        $menu[$i]['controllerNames'] = array(
                '借貸申請管理',
                '客戶管理',
                '銀行管理',
                '進件商管理',
                '業務管理',
                '申請情況類別',
                //'服務項目管理',
                );
        
        $menu[$i]['modelNames'] = array(
                'com_loan_customer',
                'com_loan_customer',
                'com_loan_customer',
                'com_loan_customer',
                'com_loan_customer',
                'com_loan_customer',
                //'com_loan_customer',
                );
        
        $menu[$i]['auth'] = array(
                "core.admin",
                "core.admin",
                "core.admin",
                "core.admin",
                "core.admin",
                "core.admin",
                //"core.admin",
                );
        
        $i++;
        
        return $menu;
    }
    
    public static function showDSArray()
    {
        $ds = array();
        
        // 申請人類型 applyType :1.公司 2.個人
        $ds['applyType']['1']['name'] = '公司';
        $ds['applyType']['1']['color'] = '0000FF';
        $ds['applyType']['2']['name'] = '個人';
        $ds['applyType']['2']['color'] = 'FF0000';

        //state:狀態 "狀態改成  1.未審核  2.審核中  3.過件   4.核准  5.未通過 6.作廢  
        $ds['state']['1']['name'] = '未審核';
        $ds['state']['1']['color'] = '0000FF';
        $ds['state']['2']['name'] = '審核中';
        $ds['state']['2']['color'] = '9988FF';
        $ds['state']['3']['name'] = '過件';
        $ds['state']['3']['color'] = '3158C4';
        $ds['state']['4']['name'] = '核准';
        $ds['state']['4']['color'] = '77BBCC';
        $ds['state']['5']['name'] = '未通過';
        $ds['state']['5']['color'] = 'FF0000';
        $ds['state']['6']['name'] = '作廢';
        $ds['state']['6']['color'] = 'A18225';
        
        //有效
        $ds['active']['1']['name'] = '有效';
        $ds['active']['1']['color'] = '0000FF';
        $ds['active']['0']['name'] = '無效';
        $ds['active']['0']['color'] = 'FF0000';
        
        //有無
        $ds['have']['1']['name'] = '有';
        $ds['have']['1']['color'] = '0000FF';
        $ds['have']['2']['name'] = '無';
        $ds['have']['2']['color'] = 'FF0000';
        
        return $ds;
    }
}