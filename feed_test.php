<?php
//feed_test.php

//required to run a test case
//require_once'../inc_0700/config_inc.php';
require_once 'simpletest/autorun.php';
require_once 'simpletest/web_tester.php';

include 'News.php';
include 'Feed.php';
//session_start();

class TestOfFeed extends WebTestCase
{
    function testGetFeed()
    {
        $expectedResult = '<div id="sidebar-right"><h1>Top Stories</h1><p>Here are the news</p></div>';
        
        $myFeed = new Feed();
        $this->assertEqual($myFeed->getFeed(),$expectedResult,"This test should pass, showing a top stories feed");
        
        $myFeed = new Feed(1);
        //$this->assertEqual($myFeed->getFeed(),$expectedResult,"This test should fail, showing an id 1 feed");
        
        $expectedResult = '<div id="sidebar-right"><h1>Football</h1><p>Here are the news</p></div>';
        $this->assertEqual($myFeed->getFeed(),$expectedResult,"This test should pass, showing an id 1 feed");
    }
}