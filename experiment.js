$exp=0;  //кол-во экспериментов
function experiment(){
	$exp=$exp+1;
	$.ajax({
		type:'post',
		url:'experiment.php',
		data:{ln:$('#lastname').val(),exp:$exp},
		success:function(data){
			$('#experiment').html(data);
		}
	});
}