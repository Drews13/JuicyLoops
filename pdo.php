<?php

$db = new PDO('mysql:host=JuicyLoops;dbname=juicyloops', 'root', 'phpjuicyloops');

function GetUserName($db,$UserId)
{
    try 
    {
        $stmt = $db->prepare("SELECT name FROM user WHERE id = ?");
        $stmt->execute(array($UserId));
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = $info['name'];
        return $res;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetSampleName($db,$SampleId)
{
    try 
    {
        $stmt = $db->prepare("SELECT name FROM sample WHERE id = ?");
        $stmt->execute(array($SampleId));
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = $info['name'];
        return $res;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetSampleAuthorID($db,$SampleId)
{
    try 
    {
        $stmt = $db->prepare("SELECT author_id FROM sample WHERE id = ?");
        $stmt->execute(array($SampleId));
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = $info['author_id'];
        return $res;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function Get10Comments($db)
{
    try 
    {
        $stmt = $db->prepare("SELECT * FROM comment");
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) 
        {
            $res[$i] = array('id' => $row['id'], 'author_id' => $row['author_id'], 'comment_date' => $row['date'], 'sample_id' => $row['sample_id'], 'author_name' => GetUserName($db, $row['author_id']), 'sample_name' => GetSampleName($db, $row['sample_id']), 'musician_name' => GetUserName($db, GetSampleAuthorID($db, $row['sample_id'])), 'musician_id' => GetSampleAuthorID($db, $row['sample_id']), 'text' => $row['text']);
            $i++;
        }
        for ($j = $i - 1; $j > $i - 11; $j--)
        {
            $res2[$i - $j - 1] = $res[$j];
            if ($j == 0) break;
        }
        return $res2;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function LoadLoopChar($db, $name, $description, $category, $bpm, $keynote, $date, $author_id)
{
    try 
    {
        $stmt = $db->prepare("INSERT INTO `sample` (`id`, `name`, `description`, `category`, `bpm`, `keynote`, `date`, `author_id`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(array($name, $description, $category, $bpm, $keynote, $date, $author_id));
        $stmd = $db->prepare("SELECT MAX(id) as num FROM sample");
        $stmd->execute();
        $info = $stmd->fetch(PDO::FETCH_ASSOC);
        return $info['num'];
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function Get10Loops($db,$PageId)
{
    try 
    {
        $stmt = $db->prepare("SELECT * FROM sample");
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) 
        {
            $res[$i] = array('id' => $row['id'], 'loopname' => $row['name'], 'description' => $row['description'], 'category' => $row['category'], 'bpm' => $row['bpm'], 'keynote' => $row['keynote'], 'loopdate' => $row['date'], 'author_id' => $row['author_id'], 'wav_file' => "loops/" . $row['id'] . ".wav", 'author_name' => GetUserName($db, $row['author_id']));
            $i++;
        }
        $PagesAmount = (int)($i / 10) + 1;
        $CurrentLoops = ($PageId - 1) * 10 + 1;
        for ($j = $i - $CurrentLoops; $j > $i - $CurrentLoops - 10; $j--)
        {
            $res2[$i - $j - $CurrentLoops] = $res[$j];
            if ($j == 0) break;
        }
        $res3[1] = $PagesAmount;
        $res3[2] = $res2;
        return $res3;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }      
}


function GetLoop($db, $SampleId)
{
    try 
    {
        $stmt = $db->prepare("SELECT * FROM sample WHERE id = ?");
        $stmt->execute(array($SampleId));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = array('id' => $row['id'], 'loopname' => $row['name'], 'description' => $row['description'], 'category' => $row['category'], 'bpm' => $row['bpm'], 'keynote' => $row['keynote'], 'loopdate' => $row['date'], 'author_id' => $row['author_id'], 'wav_file' => "loops/" . $row['id'] . ".wav", 'author_name' => GetUserName($db, $row['author_id']));
        return $res;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetAuthorLoops($db, $AuthorId)
{
    try 
    {
        $stmt = $db->prepare("SELECT * FROM sample WHERE author_id = ?");
        $stmt->execute(array($AuthorId));
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) 
        {
            $res[$i] = array('id' => $row['id'], 'loopname' => $row['name'], 'description' => $row['description'], 'category' => $row['category'], 'bpm' => $row['bpm'], 'keynote' => $row['keynote'], 'loopdate' => $row['date'], 'author_id' => $row['author_id'], 'wav_file' => "loops/" . $row['id'] . ".wav", 'author_name' => GetUserName($db, $row['author_id']));
            $i++;
        }
        for ($j = $i - 1; $j >= 0; $j--)
        {
            $res2[$i - $j - 1] = $res[$j];
            if ($j == 0) break;
        }
        return $res2;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }      
}

function GetComments($db, $SampleId)
{
    try 
    {
        $stmt = $db->prepare("SELECT * FROM comment WHERE sample_id = ?");
        $stmt->execute(array($SampleId));
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) 
        {
            $res[$i] = array('id' => $row['id'], 'author_id' => $row['author_id'], 'date' => $row['date'], 'sample_id' => $row['sample_id'], 'author_name' => GetUserName($db, $row['author_id']), 'text' => $row['text']);
            $i++;
        }
        return $res;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }    
}

function LoadComment($db, $ComText, $AuthorId, $SampleId, $LoopDate)
{
    try 
    {
        $stmt = $db->prepare("INSERT INTO `comment` (`id`, `author_id`, `sample_id`, `text`, `date`) VALUES (NULL, ?, ?, ?, ?)");
        $stmt->execute(array($AuthorId, $SampleId, $ComText, $LoopDate));    
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetCommentsAmount($db, $SampleId)
{
    try 
    {
        $stmt = $db->prepare("SELECT count(*) AS num FROM comment WHERE sample_id = ?");
        $stmt->execute(array($SampleId));
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        return $info['num'];
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetUserPassword($db, $EMail)
{
    try 
    {
        $stmt = $db->prepare("SELECT hash FROM user WHERE email = ?");
        $stmt->execute(array($EMail));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = $row['hash'];
        return $res;
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetUserID($db, $EMail) 
{
    try 
    {
        $stmt = $db->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->execute(array($EMail));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = $row['id'];
        return $res;
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function CheckForUser($db, $EMail)
{
    try 
    {
        $stmt = $db->prepare("SELECT count(*) AS num FROM `user` WHERE `email` = ?");
        $stmt->execute(array($EMail));
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($info['num'] == 0) return true;
        else return false;
    }
    catch (PDOException $e) 
    {
      print "Error!: " . $e->getMessage();
      die();
    }
}

function RegisterUser($db, $NickName, $EMail, $Password)
{
    try 
    {
        $stmt = $db->prepare("INSERT INTO `user` (`id`, `email`, `name`, `hash`) VALUES (NULL, ?, ?, ?)");
        $stmt->execute(array($EMail, $NickName, $Password));
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetAuthorID($db, $Name)
{
    try 
    {
        $stmt = $db->prepare("SELECT id FROM user WHERE name = ?");
        $stmt->execute(array($Name));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $res = $row['id'];
        return $res;
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
        die();
    }
}

function GetAllLoops($db)
{
    try 
    {
        $stmt = $db->prepare("SELECT * FROM sample");
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) 
        {
            $res[$i] = array('id' => $row['id'], 'loopname' => $row['name'], 'description' => $row['description'], 'category' => $row['category'], 'bpm' => $row['bpm'], 'keynote' => $row['keynote'], 'loopdate' => $row['date'], 'author_id' => $row['author_id'], 'wav_file' => "loops/" . $row['id'] . ".wav", 'author_name' => GetUserName($db, $row['author_id']));
            $i++;
        }
        for ($j = $i - 1; $j >= 0; $j--)
        {
            $res2[$i - $j - 1] = $res[$j];
            if ($j == 0) break;
        }
        return $res2;
    }
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage();
        die();
    }    
}
