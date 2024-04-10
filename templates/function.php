<?php

function startGenerate(){
    
$host = 'localhost'; 
$user = 'root';     
$pass = '';          
$name = 'football-matches';      	
$link = mysqli_connect($host, $user, $pass, $name);

if ($link->connect_error) {
    die("Ошибка подключения: " . $link->connect_error);
  }

mysqli_query($link, "SET NAMES 'utf8'");

//обнуление таблицы матчей
$sql = "DELETE FROM matches";
if ($link->query($sql) !== TRUE) {
    echo "Ошибка удаления записей: " . $link->error;
  }
  $link->close();
}



function getAllTeam()
{
    $host = 'localhost'; 
    $user = 'root';     
    $pass = '';          
    $name = 'football-matches';      	
    $link = mysqli_connect($host, $user, $pass, $name);
    if ($link->connect_error) {
        die("Ошибка подключения: " . $link->connect_error);
    }
    mysqli_query($link, "SET NAMES 'utf8'");

    
    $sql = "SELECT * FROM team";
    //$res = mysqli_query($link, $sql); 
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $allCommands[] = [
            'id' => $row["id"],
            'name' => $row["name"],
            'enName' => $row["name_en"],
            'city' => $row["city"],
        ];
    }
    } else 
    {
        echo $sql;
        echo "Таблица пуста";
    }
    $link->close();
    return $allCommands;
}



function getMatchesOneTeam($allCommands, $number)
{
    $host = 'localhost'; 
    $user = 'root';     
    $pass = '';          
    $name = 'football-matches';      	
    $link = mysqli_connect($host, $user, $pass, $name);
    if ($link->connect_error) {
        die("Ошибка подключения: " . $link->connect_error);
    }
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql = "SELECT a.id_match,
        a.id_team1,
        a.id_team2,
        b.name AS team2_name,
        b.city AS team2_city,
        b.name_en AS team2_name_en,
        b2.name AS team1_name,
        b2.city AS team1_city,
        b2.name_en AS team1_name_en,
        a.data,
        a.tour,
        a.city
    FROM matches a 
    JOIN team b ON  a.id_team1 = b.id    
    JOIN team b2 ON  a.id_team2 = b2.id 
    WHERE ( b.name = '" ;
    
    $sql .= $allCommands['name'] . "' OR b2.name = '" . $allCommands['name'] . "') AND a.tour = '" . $number . "'ORDER BY a.data";
    
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $matchesOneTeam[] =[
                'id_match' => $row["id_match"],
                'id_team1' => $row["id_team1"],
                'id_team2' => $row["id_team2"],
                'team2_name' => $row["team2_name"],
                'team2_city' => $row["team2_city"],
                'team2_name_en' => $row["team2_name_en"],
                'team1_name' => $row["team1_name"],
                'team1_city' => $row["team1_city"],
                'team1_name_en' => $row["team1_name_en"],
                'data' => $row["data"],
                'tour' => $row["tour"],
                'city' => $row["city"]  
            ];
        }
    }
    else 
    {
        echo $sql;
        echo "Таблица пуста";
  }
  $link->close();
  return $matchesOneTeam;
}

function createMatches($teams, int $tour){
    $matches2Tur = generateRandomMatches($teams, $tour);

    $host = 'localhost'; 
    $user = 'root';     
    $pass = '';          
    $name = 'football-matches';      	
    $link = mysqli_connect($host, $user, $pass, $name);
    if ($link->connect_error) {
        die("Ошибка подключения: " . $link->connect_error);
    }
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql = "INSERT INTO matches (id_match ,id_team1 , id_team2 , city, data, tour) VALUES ";

    $i = 1;
    foreach ($matches2Tur as $match) {
    $sql .= "('{$i}','{$match['team1']['id']}', '{$match['team2']['id']}','{$match['city']}' ,'{$match['date']}', '{$match['tour']}'), ";
    $i++;
    }
    $sql = rtrim($sql, ', ');
    if ($link->query($sql) !== TRUE) {
        echo $sql;
    echo "Ошибка добавления записей: " . $link->error;
    }
    $link->close();
    
}

function generateRandomMatches($teams, int $tour)
    {
    $matches = [];
    $date = new DateTime();
    if ($tour == 2){
        $date->modify('+190 day');
    }
    for ($j = 0; $j < count($teams); $j++) {
        $team1 = $teams[$j];        
        for ($i = $j + 1;  $i < count($teams);  $i++) {
            $team2 = $teams[$i];

            $match = [
                'team1' => $team1,
                'team2' => $team2,
                'city' => $team1['city'],
                'tour' => $tour,
                
            ];
            
            $matches[] = $match;
        }
    }

    shuffle($matches);

    for($i = 0; $i < count($matches); $i++){
        $dateString = $date->format('Y-m-d H:i:s');
        $matches[$i]['date'] = $dateString;               
        $date->modify('+1 day');
    }
    return $matches;
}



function checkHomeGuess($allCommands, $number){
    
    for($i = 0; $i < count($allCommands); $i++)
    {
        $homeTownMatch = $allCommands[$i]['city'];
        $matchesOneTeam = getMatchesOneTeam($allCommands[$i], $number);
        
        $townMatch = '';
        for($i = 0; $i < count($matchesOneTeam); $i++)
        {
            if($townMatch == '' && $matchesOneTeam[$i]['city'] == $homeTownMatch ){
                $townMatch = $homeTownMatch;
            } elseif($townMatch == '' && $matchesOneTeam[$i]['city'] != $homeTownMatch){
                $townMatch = $homeTownMatch;
                $matchesOneTeam[$i]['city'] = $homeTownMatch;
            } elseif($townMatch != $homeTownMatch && $matchesOneTeam[$i]['city'] != $homeTownMatch){
                $townMatch = $homeTownMatch;
                $matchesOneTeam[$i]['city'] = $homeTownMatch;
            }elseif($townMatch != $homeTownMatch && $matchesOneTeam[$i]['city'] == $homeTownMatch){
                $townMatch = $matchesOneTeam[$i]['city'];  
            }elseif($townMatch == $homeTownMatch && $matchesOneTeam[$i]['city'] == $homeTownMatch){
                $matchesOneTeam[$i]['city'] = $matchesOneTeam[$i]['team1_city'];
                $townMatch = $matchesOneTeam[$i]['team1_city'];
            }else{
                $townMatch = $matchesOneTeam[$i]['city'];
            }
        }
    ////////////изменение городов матчей
    $host = 'localhost'; 
    $user = 'root';     
    $pass = '';          
    $name = 'football-matches';      	
    $link = mysqli_connect($host, $user, $pass, $name);
    if ($link->connect_error) {
        die("Ошибка подключения: " . $link->connect_error);
    }
    mysqli_query($link, "SET NAMES 'utf8'");
        $sql = "INSERT INTO matches (id_match, id_team1, id_team2, data, city, tour ) VALUES ";

        for($i = 0; $i < count($matchesOneTeam); $i++)
        { 
            $sql .= "(" . $matchesOneTeam[$i]['id_match'] . ", " .$matchesOneTeam[$i]['id_team1'] . " , " . $matchesOneTeam[$i]['id_team2'] . ", '" . $matchesOneTeam[$i]['data'] . "', '" . $matchesOneTeam[$i]['city'] . "' , '" . $number .  "'), "; 
        }
        $sql = rtrim($sql, ', ');
        $sql .= " ON DUPLICATE KEY UPDATE id_match=VALUES(id_match),id_team1=VALUES(id_team1), id_team2=VALUES(id_team2), data=VALUES(data), city=VALUES(city), tour=VALUES(tour)";
        

        if ($link->query($sql) !== TRUE) {
            echo $sql;
        echo "Ошибка добавления записей: " . $link->error;
        }
            
        }
    $link->close();
}

function getFinalMatches($number){
    $host = 'localhost'; 
        $user = 'root';     
        $pass = '';          
        $name = 'football-matches';      	
        $link = mysqli_connect($host, $user, $pass, $name);
        if ($link->connect_error) {
            die("Ошибка подключения: " . $link->connect_error);
        }
        mysqli_query($link, "SET NAMES 'utf8'");
    $sql = "SELECT a.id_match,
            a.id_team1,
            a.id_team2,
            b.name AS team2_name,
            b.city AS team2_city,
            b.name_en AS team2_name_en,
            b2.name AS team1_name,
            b2.city AS team1_city,
            b2.name_en AS team1_name_en,
            a.data,
            a.tour,
            a.city
        FROM matches a 
        JOIN team b ON  a.id_team1 = b.id    
        JOIN team b2 ON  a.id_team2 = b2.id 
        WHERE a.tour = ";
        $sql .= $number . " ORDER BY a.data";
        
        $result = $link->query($sql);
        $finalMatches = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $finalMatches[] =[
                    'id_match' => $row["id_match"],
                    'id_team1' => $row["id_team1"],
                    'id_team2' => $row["id_team2"],
                    'team2_name' => $row["team2_name"],
                    'team2_city' => $row["team2_city"],
                    'team2_name_en' => $row["team2_name_en"],
                    'team1_name' => $row["team1_name"],
                    'team1_city' => $row["team1_city"],
                    'team1_name_en' => $row["team1_name_en"],
                    'data' => $row["data"],
                    'tour' => $row["tour"],
                    'city' => $row["city"]  
                ];
            }
        }
        else 
        {
            echo $sql;
            echo "Таблица пуста";
        }
        $link->close();
    return $finalMatches;
}