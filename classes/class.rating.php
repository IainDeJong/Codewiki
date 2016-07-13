<?php

class RatingSystem
{
    public function __construct($db)
    {
        $this->db = $db;
    }
    
//=========================================
    
    public function ratingShow($id)
    {
        $rating = $this->getPageRating($id);
        
        $ratingcount = count($rating);
        $ratingsum = array_sum($rating);
        $ratingavg = $ratingsum / $ratingcount;        
        $ratingrounded = round($ratingavg, 1);
        
        return $ratingrounded;
    }
    
//=========================================    
    
    public function ratingCalc($id, $score, $userid)
    {
        $this->savePageRating($id, $score, $userid);
        
        echo '<p id="ratingshowref">'.$this->ratingShow($id).'</p>';
    }
    
//=========================================
    
    public function ratingFormShow($id, $user)
    {
        $userid = $user->getActiveUserId();
        
        if ($userid !== false)
        {
            return '<form><select id="ratinginput">
                    <option value=1>1</option>
                    <option value=2>2</option>
                    <option value=3>3</option>
                    <option value=4>4</option>
                    <option value=5>5</option>
                    <option value=6>6</option>
                    <option value=7>7</option>
                    <option value=8>8</option>
                    <option value=9>9</option>
                    <option value=10>10</option>
                    </select>                    
                <input type="button" name="ratingbutton" id="ratingbuttonajax"
                value="rate!" onclick="ajaxRater('.$id.','.$userid.')"></form>'; 
        }
        else
        {
            return 'Please log in to rate this article.';
        }
    }
    
//=============================================
    
    public function getPageRating($pageid)
    {
        $sql = 'SELECT rating FROM rating WHERE pages_id =(:pageid)';
        
        $stm = $this->db->prepareStatement($sql);
        $stm->bindParam(':pageid', $pageid, PDO::PARAM_STR);
        $result = $this->db->executeGetArrays($stm);
    
        foreach ($result as $value)
        {
            $rating[] = $value[0];
        }

        if (isset($rating))
        {
            return $rating;
        }
        else
        {
            $rating = array(5,5);
            return $rating;
        }
    }

//=============================================

    protected function savePageRating($pageid, $rating, $userid)
    {
        $sql = 'INSERT INTO rating (pages_id, rating, users_id) VALUES ((:pageid), (:rating), (:userid))';
        $userdata = array(':pageid' => $pageid, ':rating' => $rating, ':userid' => $userid);
        return $this->db->doAction($sql, $userdata);
    }

//=============================================

    public function checkPageRated($pageid)
    { 
        $sql = 'SELECT users_id FROM rating WHERE pages_id =(:pageid)';
        
        $stm = $this->db->prepareStatement($sql);
        $stm->bindParam(':pageid', $pageid, PDO::PARAM_STR);
        $result = $this->db->executeGetArrays($stm);
        return $result;
    }
}