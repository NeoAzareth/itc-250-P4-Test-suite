<?php
/**
 *News.php a class that handles news objects
 *
 * @author Israel Santiago, Milo Sherman and Jaykel Torres
 * @version 1.0 2016/02/23
 * @link http://neoazareth.com/wn16/news/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see News.php
 * @see Feed.php
 * @todo none
 */
class News
{
    //instance variables
    public $categories = [];
    public $subCategories = [];
    
    /**
     *constructor
     */
    public function __construct()
    {
    	//queries the DB twice for categories and subcategories
        /*$sqlCats = "SELECT CategoryID, CategoryName FROM wn16_news_categories";
        $sqlSubCats = "
        SELECT f.FeedID,f.CategoryID,f.FeedName FROM wn16_feeds f ORDER BY f.FeedName ASC";
        $cats = 
            mysqli_query(IDB::conn(),$sqlCats) 
            or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
        $subCats = 
            mysqli_query(IDB::conn(),$sqlSubCats) 
            or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
        //here the $subCats query is converted to an associative array
        $subCats = mysqli_fetch_all($subCats,MYSQLI_ASSOC);
        $cats = mysqli_fetch_all($cats,MYSQLI_ASSOC);*/
        
        
        $this->categories = array (
            array("CategoryID"=>"1","CategoryName"=>"Sports"),
            array("CategoryID"=>"2","CategoryName"=>"Technology"),
            array("CategoryID"=>"3","CategoryName"=>"Politics"),
            );
        
        
        $this->subCategories = array (
              array ("FeedID"=>"5","CategoryID"=>"2","FeedName"=>"Apple"),
              array ("FeedID"=>"8","CategoryID"=>"3","FeedName"=>"Europe"),
              array ("FeedID"=>"1","CategoryID"=>"1","FeedName"=>"Football"),
              array ("FeedID"=>"4","CategoryID"=>"2","FeedName"=>"Medical"),
              array ("FeedID"=>"6","CategoryID"=>"2","FeedName"=>"Samsung"),
              array ("FeedID"=>"2","CategoryID"=>"1","FeedName"=>"Soccer"),
              array ("FeedID"=>"3","CategoryID"=>"1","FeedName"=>"Tennis"),
              array ("FeedID"=>"9","CategoryID"=>"3","FeedName"=>"Trump"),
              array ("FeedID"=>"7","CategoryID"=>"3","FeedName"=>"U.S.")
            );
        
        
    }
    
    /**
     *function that displays the News navigation menu
     */
    public function getNewsNav($id)
    {
        $myResult = '<div id="sidebar-left"><h3 class="sidebar-left-header">Categories</h3><ul id="toggle-view">';
        
        //if(mysqli_num_rows($this->categories) > 0)
        if($this->categories)
        {#there are records - present data
            //while($row = mysqli_fetch_assoc($this->categories))
            foreach($this->categories as $category)
            {# pull data from associative array
                $myResult .= '<li><h5>'.$category['CategoryName'].'</h5><span>&#43;</span><div class="panel">';
                    if($this->subCategories)
                    {#there are records - present data
                        foreach ($this->subCategories as $feed)
                        {# loops through the feeds array to find feeds that belong to the category
                            if($category["CategoryID"] == $feed["CategoryID"])
                            {#if $row categoryid is equal to the $feed categoryid they belong together
                                $myResult .= '<p><strong><a href="feed_view.php?id='.$feed["FeedID"].'">'.$feed["FeedName"].'</a></strong></p>';
                            }
                        }
                    }
                $myResult .='</div></li>';
            }
        }
        if ($id != 0 && isset($_SESSION["feeds"]["$id"])) {
        	//if there is one unserializes the object
            $myFeed = unserialize($_SESSION["feeds"]["$id"]);
            /*it then checks if the time is still valid and that the user doesn't
            *want to refresh the feed*/
            if ($myFeed->timeCreated + 600 > time() && !(isset($_GET["refresh"]))){
                $myResult .= '<p>Last update: '.date("h:i",$myFeed->timeCreated).'<a href="feed_view.php?id='.$myFeed->id.'&amp;refresh=true"><strong>&#x21bb;</strong></a></p>';
            }
        }
        $myResult .= '</ul></div>';
        return $myResult;
        //return "<h1>Hello</h1>";
    }
    
    /**
     *this function handles the logic behind checking the session for an existing
     *valid feed, pulling data from the database or creating a default top stories view
     *
     *@param $id to be used to idetify the subcategory
     *@todo refactor
     */
    public function getNewsFeed($id)
    {
        $myResult ='';
    	//checks if there is an object with the given id on the session
        if ($id != 0 && isset($_SESSION["feeds"]["$id"])) {
        	//if there is one unserializes the object
            $myFeed = unserialize($_SESSION["feeds"]["$id"]);
            /*it then checks if the time is still valid and that the user doesn't
            *want to refresh the feed*/
            if ($myFeed->timeCreated + 600 > time() && !(isset($_GET["refresh"]))){
                $myResult = $myFeed->getFeed();
            }
            else
            {#if not creates a fresh feed and saves the object into the session
                $myFeed = new Feed($id);
                $_SESSION["feeds"]["$id"] = serialize($myFeed);
                $myResult = $myFeed->getFeed();
            }
        } else {#if there is no object in session
            if ($id > 0) {#checks that the id is not equal 0 (0 is a top stories view)
                $myFeed = new Feed($id);
                //saves the feed into the session
                $_SESSION["feeds"]["$id"] = serialize($myFeed);
            } else {#creates a top stories view by default
                $myFeed = new Feed();
            }
            $myResult = $myFeed->getFeed();
        }
        return $myResult;
    }
}