<?php
//news_test.php

//required to run a test case
//require_once'../inc_0700/config_inc.php';
require_once 'simpletest/autorun.php';
require_once 'simpletest/web_tester.php';

include 'News.php';
include 'Feed.php';
session_start();
class TestOfNews extends UnitTestCase
{
    function testGetNewsNav()
    {
        $expectedResult = '<div id="sidebar-left"><h3 class="sidebar-left-header">Categories</h3><ul id="toggle-view"><li><h5>Sports</h5><span>&#43;</span><div class="panel"><p><strong><a href="feed_view.php?id=1">Football</a></strong></p><p><strong><a href="feed_view.php?id=2">Soccer</a></strong></p><p><strong><a href="feed_view.php?id=3">Tennis</a></strong></p></div></li><li><h5>Technology</h5><span>&#43;</span><div class="panel"><p><strong><a href="feed_view.php?id=5">Apple</a></strong></p><p><strong><a href="feed_view.php?id=4">Medical</a></strong></p><p><strong><a href="feed_view.php?id=6">Samsung</a></strong></p></div></li><li><h5>Politics</h5><span>&#43;</span><div class="panel"><p><strong><a href="feed_view.php?id=8">Europe</a></strong></p><p><strong><a href="feed_view.php?id=9">Trump</a></strong></p><p><strong><a href="feed_view.php?id=7">U.S.</a></strong></p></div></li></ul></div>';
        
        
        $myNews = new News();
        //test 1 test string match
        $this->assertEqual($myNews->getNewsNav(0),$expectedResult,"We expect the result is the news nav");

        //test 2 test string match regardless of what is passed in this case 100
        $this->assertEqual($myNews->getNewsNav(100),$expectedResult,"This test should pass, method only uses id to check for session active feed");
        
        //test 2 test string match regardless of what is passed in this case string "hello"
        $this->assertEqual($myNews->getNewsNav("hello"),$expectedResult,"Should Pass, the method assumes the index or feed view pages already checked for invalid input");
        
        
        $myFeed = new Feed(1);
        $_SESSION["feeds"]["1"] = serialize($myFeed);
        
        //test 3 test the string don't match, the expected result should include an extra line with the time of feed    
        //$this->assertEqual($myNews->getNewsNav(1),$expectedResult,"This test should fail. The news nav now has an extra line that shows the time the feed was catched");
        
        
        
        $expectedResult ='<div id="sidebar-left"><h3 class="sidebar-left-header">Categories</h3><ul id="toggle-view"><li><h5>Sports</h5><span>&#43;</span><div class="panel"><p><strong><a href="feed_view.php?id=1">Football</a></strong></p><p><strong><a href="feed_view.php?id=2">Soccer</a></strong></p><p><strong><a href="feed_view.php?id=3">Tennis</a></strong></p></div></li><li><h5>Technology</h5><span>&#43;</span><div class="panel"><p><strong><a href="feed_view.php?id=5">Apple</a></strong></p><p><strong><a href="feed_view.php?id=4">Medical</a></strong></p><p><strong><a href="feed_view.php?id=6">Samsung</a></strong></p></div></li><li><h5>Politics</h5><span>&#43;</span><div class="panel"><p><strong><a href="feed_view.php?id=8">Europe</a></strong></p><p><strong><a href="feed_view.php?id=9">Trump</a></strong></p><p><strong><a href="feed_view.php?id=7">U.S.</a></strong></p></div></li><p>Last update: '.date("h:i",$myFeed->timeCreated).'<a href="feed_view.php?id=1&amp;refresh=true"><strong>&#x21bb;</strong></a></p></ul></div>';
        
        //test 4 tests string match when a valid id is passed
        $this->assertEqual($myNews->getNewsNav(1),$expectedResult,"This test should pass.");
        session_unset();
    }
    
    function testGetNewsFeed()
    {
        $expectedResult = '<div id="sidebar-right"><h1>Football</h1><p>Here are the news</p></div>';
        $myNews = new News();
        //test 5 tests the output to be a feed belonging to id 1
        $this->assertEqual($myNews->getNewsFeed(1),$expectedResult,"Test should pass. Tests the result of an id = 1");
        
        //test 6 and test 7 $_GET["refresh"] is set to true and the feed is created with the current time
        $_GET["refresh"] = true;
        $this->assertEqual($myNews->getNewsFeed(1),$expectedResult,"Test should pass. Tests the result of an id = 1 refresh = true");
        $newFeed = unserialize($_SESSION["feeds"][1]);
        $this->assertEqual($newFeed->timeCreated,time(),"Test should pass. Tests the result of the new feed time being the same as current time");
        
        //test 8 if there is no feed in the session creates a new feed and stores it in the session
        session_unset();
        $this->assertNull($_SESSION["feeds"][1], "Test if the session feeds id 1 exists, should pass");
        
        //test 9 now that we know there is nothing in the session we set data and check again
        $myNews->getNewsFeed(1);
        $this->assertNotNull($_SESSION["feeds"][1], "Test if the session feeds id 1 exists, should pass");
        
        //finally if the id = 0, creates a default top stories view
        $expectedResult = '<div id="sidebar-right"><h1>Top Stories</h1><p>Here are the news</p></div>';
        $this->assertEqual($myNews->getNewsFeed(0),$expectedResult,"Test should pass. Tests the result of an id = 0");
    }
}
