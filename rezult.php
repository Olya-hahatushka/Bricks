<!DOCTYPE html>
<?php
function create_db(){
	try{
		$pdo = new PDO("mysql:host=localhost","root");
		/*$pdo->exec("create database my_db2");  //создаем базу данных
		$pdo->exec("use my_db2");  // подключаемся к базе данных
		$pdo->exec("create table experiments(id_exp INT not null prymary key, data INT, lastname CHAR)");//т.эксп.
		$pdo->exec("create table bricks (id_exp INT, numb INT , score INT, fraction FLOAT)");  //создаем таблицу результатов
	*/}
	catch(PDOException $ex){
		die ("Error: ".$ex->getMessage());
	}
	return $pdo;
}

	/*---------------------------------------подключаемся к бд с пом. pdo------------------------------------------*/
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
if (isset($_GET['ln']))
	$ln=$_GET['ln'];
if (isset($_GET['nexp'])){
	$nexp=$_GET['nexp'];
$exper="<h3>$nexp</h3><table class='my_table'><tr><td>Кол-во<br>очков</td><td>Ск. раз<br>выпало</td><td>
	В %<br>соотн-ии</td></tr><tr>";//заг. табл.эксп-нтов
	
	/*----------------------------выводим результаты для данного эксперимента--------------------------------------*/
	$rez=$pdo->query('SELECT numb, score, fraction FROM bricks WHERE id_exp='.$nexp.' ORDER BY numb')->
		fetchAll(PDO::FETCH_ASSOC);
	for ($i=0;$i<count($rez);$i++){
        foreach($rez[$i] as $key => $val){
            {$exper.="<td>$val</td>";}
		}
        $exper.="</tr>";
    }
    $exper.="</table>";
    echo $exper;
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>table</title>
        <style>
            .my_table{
                border:solid 1px brown;
                border-spacing:0px;
                background-color:bisque;
            }
            td{
                border:solid 1px brown;
            }
        </style>
    </head>
    <body>
    </body>
</html>