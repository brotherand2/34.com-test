<?php
namespace Home\Controller;
use Think\Controller;
class  TestController extends Controller
{
    public  function index()
    {
        $page=new \Think\Page(203,15);
        $page->lastSuffix=false;
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('first', '首页');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme','%HEADER% <br>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page->show();
        echo "test".$page->show();
        $this->display();
    }
}