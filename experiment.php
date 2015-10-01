<?php
/*mb_internal_encoding("UTF-8");
echo "кодировка".mb_internal_encoding()."<br>";*/
if (!isset($_POST['ln']))
	$_POST['ln']="";
$ln=$_POST['ln'];  //фамилия экспериментатора
if (!isset($_POST['exp']))
	$_POST['exp']="";
$exp=$_POST['exp'];  //кол-во экспериментов

function create_db(){
	try{
		$pdo = new PDO("mysql:host=localhost","root");
		$pdo->exec("create database my_db2");  //создаем базу данных
		$pdo->exec("use my_db2");  // подключаемся к базе данных
		$pdo->exec("create table experiments(id_exp INT not null primary key, data char(20), lastname CHAR(20))");//т.эксп.
		$pdo->exec("create table bricks (id_exp INT, numb INT , score INT, fraction decimal(5,2))");  //созд.т.рез-тов
	}
	catch(PDOException $ex){
		die ("Error: ".$ex->getMessage());
	}
	return $pdo;
}
	define("Q",36000);
    $d="";
    $c2=0;$c3=0;$c4=0;$c5=0;$c6=0;$c7=0;$c8=0;$c9=0;$c10=0;$c11=0;$c12=0;
    $d=[];$e=[];
    //$lastname=array("Симка","Нолик","Мася","Папус","Фаер","Верта","Игрек","Шпуля");  //массив экспериментаторов
    $r="<table class='my_table'><tr><td>№<br>эксп.</td><td>Кол-во<br>очков</td><td>Сколько раз<br>выпало</td><td>
		В % соотн-ии</td></tr><tr>";  //заголовок таблицы результатов
    $exper="<table class='my_table'><tr><td>№<br>эксп.</td><td>Дата</td><td>Фамилия</td></tr><tr>";//заг.табл.эксп-нтов
    
	/*--------------------------------------------подключаемся к бд с пом. pdo-------------------------------------*/
	try{
        $pdo = new PDO("mysql:host=localhost;dbname=my_db2","root");
    }
    catch(PDOException $ex){
		if ($ex->getCode()==1049){  //если базы данных не существует
            $pdo=create_db();
        }
        else
			die ("Error: ".$ex->getMessage());
    }
	
	/*--------------------------------создаем таблицы данных, если их нет------------------------------------------*/
    $pdo->exec("CREATE TABLE IF NOT EXISTS experiments (id_exp INT not null primary key, data char(20), lastname CHAR(20))");//т. эксп-в
    $pdo->exec("CREATE TABLE IF NOT EXISTS bricks (id_exp INT, numb INT, score INT, fraction decimal(5,2))");//т.рез-в
    	$day=rand(1,30);
		if ($day<10) $day="0".$day;
		$month=rand(1,12);
		if ($month<10) $month="0".$month;
        $data=$day.".".$month.".15";
		
	/*------------------------------заносим в таблицу экспериментов данные-----------------------------------------*/
        $pdo->prepare("INSERT INTO experiments (id_exp, data, lastname) VALUES(:id, :d, :ln)")->
            execute(array(':id'=>$exp,':d'=>$data, ':ln'=>$ln));
        for ($i=0;$i<36000;$i++){  //проводим эксперимент
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
		
	/*----------------------------------заносим данные в таблицу результатов---------------------------------------*/
        $cube=$pdo->prepare("INSERT INTO bricks (id_exp, numb, score, fraction) VALUES(:id, :n, :s, :f)");
            for ($i=2;$i<=12;$i++){
                $cube->execute(array(':id'=>$exp,':n'=>$i, ':s'=>$d[$i], ':f'=>$e[$i]));
            }
			
	/*----------------------------------выводим таблицу экспериментов на экран-------------------------------------*/
    $experim=$pdo->query('SELECT * FROM experiments')->fetchAll(PDO::FETCH_ASSOC);  //массив табл. экспериментов
    for ($i=0;$i<count($experim);$i++){
        foreach($experim[$i] as $key => $val){
			$j=$i+1;
            {$exper.="<td><a style='text-decoration:none;color:black' href='rezult.php?nexp=$j' target='blank'>
			$val</a></td>";}
		}
        $exper.="</tr>";
    }
    $exper.="</table>";
    echo $exper;
$pdo=null;
?>