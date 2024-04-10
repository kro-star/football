<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	mb_internal_encoding('UTF-8');

include 'function.php';
     

$host = 'localhost'; 
$user = 'root';     
$pass = '';          
$name = 'football-matches';      	
$link = mysqli_connect($host, $user, $pass, $name);

if ($link->connect_error) {
    die("Ошибка подключения: " . $link->connect_error);
  }

mysqli_query($link, "SET NAMES 'utf8'");

startGenerate();
 

  for($number = 1; $number < 3; $number++)
  {
    $allCommands = getAllTeam();
    $matches2Tur = generateRandomMatches($allCommands, $number);

    $sql = "INSERT INTO matches (id_match ,id_team1 , id_team2 , city, data, tour) VALUES ";

    $i = 1;

    foreach ($matches2Tur as $match) {
        $idMatch = $i + (190 * ($number - 1));
        $sql .= "('{$idMatch}','{$match['team1']['id']}', '{$match['team2']['id']}','{$match['city']}' ,'{$match['date']}', '{$number}'), ";
        $i++;
    }
    $sql = rtrim($sql, ', ');
    if ($link->query($sql) !== TRUE) {
        echo $sql;
    echo "Ошибка добавления записей: " . $link->error;
    }


    checkHomeGuess($allCommands, $number);


        // вывод матчей
        $finalMatches = getFinalMatches($number);
        ?>
        <div class="container">
                    <div class="row">
                        <div class="col-12 tour  p-3 ">
                            <?php echo ($number)?> тур
                        </div>
        <?php for($i = 0; $i < count($finalMatches); $i++ )
        {?>          
                        <div class="col-12 p-0">
                            <div class="match pt-3 pb-5 " data-id-match = "<?php echo ($finalMatches[$i]['id_match'])?>" data-name-team1="<?php echo ($finalMatches[$i]['team1_name_en'])?>" data-name-team2 ="<?php echo ($finalMatches[$i]['team2_name_en'])?>">
                                <div class="d-flex w-100 mb-4">
                                    <div class="row w-100">
                                        <div class="col-5">
                                            <div class="team cursor-pointer text-right pt-3 pb-3 " data-id-team = "<?php echo ($finalMatches[$i]['id_team1'])?>" data-name="<?php echo ($finalMatches[$i]['team1_name_en'])?>">
                                            <?php echo ($finalMatches[$i]['team1_name'])?>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <div class="score p-3  mr-3"> 0 </div>
                                                <div class="text-center pt-3 pb-3">:</div>
                                                <div class="score p-3  ml-3" > 0 </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="team cursor-pointer pt-3 pb-3" data-id-team = "<?php echo ($finalMatches[$i]['id_team2'])?>" data-name="<?php echo ($finalMatches[$i]['team2_name_en'])?>" >
                                                <?php echo ($finalMatches[$i]['team2_name'])?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    Дата: <?php echo ($finalMatches[$i]['data'])?>
                                </div>                    
                                <div class="text-center">
                                    Город: <?php echo ($finalMatches[$i]['city'])?>
                                </div>
                            </div>
                        </div> 

    <?php }?>
            
                </div>
            </div>
            <?php 
}

$link->close();



?>