<?php
//feed_view_test.php

require_once 'simpletest/autorun.php';
require_once 'simpletest/web_tester.php';

class TestOfFeedViewPage extends WebTestCase
{
    function testFeedView(){
        //test 1 the page redirects to index.php as default in folder
        $this->assertTrue($this->get("http://neoazareth.com/wn16/news/"));
        
        //test 2 page redirects to index if no id is passed
        $this->setMaximumRedirects(0);
        $this->get('http://neoazareth.com/wn16/news/feed_view.php');
        $this->assertResponse(array(301, 302, 303, 307));
        
        //test 3 page contains string football associated with id 1
        $this->get('http://neoazareth.com/wn16/news/feed_view.php?id=1');
        $this->assertText("Football");
        
        //test 4 page redirects to index when the id is a string
        $this->get('http://neoazareth.com/wn16/news/feed_view.php?id="hello"');
        $this->assertResponse(array(301, 302, 303, 307));
        
        //test 5 page redirects to index if the id does not exists...
        $this->get('http://neoazareth.com/wn16/news/feed_view.php?id=5000');
        $this->assertResponse(array(301, 302, 303, 307));
    }
    
    
    
    
    
}