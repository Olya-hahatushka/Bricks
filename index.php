<?php
/*mb_internal_encoding("UTF-8");
echo "кодировка".mb_internal_encoding()."<br>";*/
function create_db(){
	try{
		$pdo = new PDO("mysql:host=localhost","root");
		$pdo->exec("create database my_db2");  //создаем базу данных
		$pdo->exec("use my_db2");  // подключаемся к базе данных
		$pdo->exec("create table experiments(id_exp INT not null prymary key, data INT, lastname CHAR)");//т.эксп.
		$pdo->exec("create table bricks (id_exp INT, numb INT , score INT, fraction FLOAT)");  //создаем таблицу результатов
	}
	catch(PDOException $ex){
		die ("Error: ".$ex->getMessage());
	}
	return $pdo;
}
function cube(){
	define("Q",36000);
    $d="";
    $c2=0;$c3=0;$c4=0;$c5=0;$c6=0;$c7=0;$c8=0;$c9=0;$c10=0;$c11=0;$c12=0;
    $d=[];$e=[];
    //$lastname=array("Симка","Нолик","Мася","Папус","Фаер","Верта","Игрек","Шпуля");  //массив экспериментаторов
    $exp=rand(2,5);  //кол-во экспериментов
    $r="<table class='my_table'><tr><td>№<br>эксп.</td><td>Кол-во<br>очков</td><td>Сколько раз<br>выпало</td><td>
		В % соотн-ии</td></tr><tr>";  //заголовок таблицы результатов
    $exper="<table class='my_table'><tr><td>№<br>эксп.</td><td>Дата</td><td>Фамилия</td></tr><tr>";//заг. табл.эксп-нтов
    try{
        $pdo = new PDO("mysql:host=localhost;dbname=my_db2","root");
    }
    catch(PDOException $ex){
		if ($ex->getCode()==1049){
            $pdo=create_db();
        }
        else
			die ("Error: ".$ex->getMessage());
    }
    $pdo->exec("DROP TABLE experiments");  //удаляем таблицу экспериментов
    $pdo->exec("DROP TABLE bricks");  //удаляем таблицу результатов
    $pdo->exec("CREATE TABLE IF NOT EXISTS experiments (id_exp INT, data char(30), lastname CHAR(30))");  //создаем табл. экспериментов
    $pdo->exec("CREATE TABLE IF NOT EXISTS bricks (id_exp INT, numb INT, score INT, fraction decimal(5,2))");  //созд.т.рез-тов
    for ($j=1;$j<=$exp;$j++){  //эксперимент
		$day=rand(1,30);
		if ($day<10) $day="0".$day;
		$month=rand(1,12);
		if ($month<10) $month="0".$month;
        $data=$day.".".$month.".15";
            $ln = $lastname[rand(0,count($lastname)-1)];
        $pdo->prepare("INSERT INTO experiments (id_exp, data, lastname) VALUES(:id, :d, :ln)")->
            execute(array(':id'=>$j,':d'=>$data, ':ln'=>$ln));
        for ($i=0;$i<36000;$i++){
            $c=rand(1,6)+rand(1,6);
            switch ($c){
                case 2: $d[2]=$c2++; $e[2]=($c2/Q)*100; break;
				case 3: $d[3]=$c3++; $e[3]=($c3/Q)*100; break;
				case 4: $d[4]=$c4++; $e[4]=($c4/Q)*100; break;
				case 5: $d[5]=$c5++; $e[5]=($c5/Q)*100; break;
				case 6: $d[6]=$c6++; $e[6]=($c6/Q)*100; break;
				case 7: $d[7]=$c7++; $e[7]=($c7/Q)*100; break;
				case 8: $d[8]=$c8++; $e[8]=($c8/Q)*100; break;
				case 9: $d[9]=$c9++; $e[9]=($c9/Q)*100; break;
				case 10: $d[10]=$c10++; $e[10]=($c10/Q)*100; break;
				case 11: $d[11]=$c11++; $e[11]=($c11/Q)*100; break;
				case 12: $d[12]=$c12++; $e[12]=($c12/Q)*100; break;
            }
        }
        ksort($d);
        $cube=$pdo->prepare("INSERT INTO bricks (id_exp, numb, score, fraction) VALUES(:id, :n, :s, :f)");
            for ($i=2;$i<=12;$i++){
                $cube->execute(array(':id'=>$j,':n'=>$i, ':s'=>$d[$i], ':f'=>$e[$i]));
            }
    }/*
    $rez=$pdo->query('SELECT * FROM bricks')->fetchAll(PDO::FETCH_ASSOC);
    for ($i=0;$i<count($rez);$i++){
        foreach($rez[$i] as $key => $val)
            {$r.="<td>".$val."</td>";}
        $r.="</tr>";
    }
    $r.="</table>";
    echo $r;*/
    $experim=$pdo->query('SELECT * FROM experiments')->fetchAll(PDO::FETCH_ASSOC);
    for ($i=0;$i<count($experim);$i++){
        foreach($experim[$i] as $key => $val){
			$j=$i+1;
            {$exper.="<td><a style='text-decoration:none;color:black' href='rezult.php?name=$j' target='blank'>
			$val</a></td>";}
		}
        $exper.="</tr>";
    }
    $exper.="</table>";
    echo $exper;
}
//$k=cube();
$pdo=null;
?>