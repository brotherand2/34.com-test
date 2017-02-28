<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2017/2/15
 * Time: 22:50
 */
echo  '哈哈';
function testPage()
{
    require "./ThinkPHP/Library/Think/Page.class.php";
    $page=new \Think\Page(203,15);
    $page->show();
}

testPage();