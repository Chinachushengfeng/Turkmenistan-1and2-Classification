<?php
	
	
//1开门成�?
//2到位成功  
//3:没扫描到 
//4条码不符 
//5超重 
//6过轻 
//7回收成功 
//8回收失败 
//9退瓶成�?
//10关门成功 
//11机内有瓶�?
//12图像识别失败
 



date_default_timezone_set("PRC");	

include("IncDB.php");
include("function/sql.php");


error_reporting(0);
       $close=$_GET['close'];
	    $status=select("command","recognitionstatus");
 
        if($close=="1")
		{
			
			
		 //	$sql="update command set command='2'";
		 //	mysqli_query($link,$sql);	
 
$sql="select * from command ";
$comresult=  mysqli_query($link,$sql);
$comresult=mysqli_fetch_array($comresult);
$transactionid=$comresult['transactionid'];
					 		// transactiondone=2 是用户按了结束，说明transaction结束	
$sql="update user_transaction set  transactiondone=2  where transactionid='$transactionid'";//標記結束transaction    //每次在載入首頁時候會檢查是否�?標記並上傳�?
mysqli_query($link,$sql);


			
		if($status=="10")	
		{ 
		
 $arr=array('success'=>'close');    
    echo json_encode($arr); 
			
			
			exit; 
		}
	 
 $arr=array('success'=>'undone');    
    echo json_encode($arr); 
		

		
 exit;
		}
		 
		
		
 
  
 
 
 
 
 
 

$command=select("command","recognitionstatus");  //此command非之前的command 注意标记号！！！



$transactionid=select("command","transactionid");
$dateline=time(); 
$statecode=select("command","statecode");
$user=    "unknown";
$diam=select("command","diam");
$bors=select("command","bors");
$charityid=select("command","charityid");


$sql="select * from charityname where charityid='$charityid'";
$result=mysqli_query($link,$sql);
$result=mysqli_fetch_array($result);

$charityname=$result['chcharityname'];




 
	  
	  $payplatform="RVM";
 
 


$barcode=select("command","barcode");
$brand=select("barcode","brand");
$bottleinfo=select("barcode","bottleinfo");
$weight=strval(select("command","weight")*0.1);
 $block=select("command","recognitionstatus");
 
  $errorcode=select("command","errorcode");
 
 

 
 
 
 
//$recognitionstatus=select("command","command"); 
$rebateordonate='0';
$bottlevalue=select("barcode where barcode='$barcode'","value");  //查询当前扫描到的barcode的价格�?

  
$metal=select("barcode where barcode='$barcode'","metal");  //查询当前扫描到的barcode的价格�?



 

if (!$bottlevalue)
{
$bottlevalue=5;
$metal=0;
}

 

 
 $storageplastic=select("command","storageplastic");
  $storagecan=select("command","storagecan");
 
 $limited=select("command","limitedvalue");
 $octreceipt=select("ocl","receipt");

 
 
$transactiondone='0'; 

 
	





//error_reporting(0);
//微信扫码  	    
// header("Content-type: text/html; charset=gb2312"); 
//$user=$_GET['user'];
 
 
 
 
// if ($user) 
 //{
 
	 
 // $sql="INSERT INTO user (user, islogin) VALUES ('$user', '1') ";
//$result=mysqli_query($link,$sql); 
 
 
//} 
 
  
 				 
 
 
 
 

 
   
 //1开门成�?2到位成功  3:没扫描到 4条码不符 5超重 6过轻 7回收成功 8回收失败 9退瓶成�?10关门成功 11机内有瓶�?12图像识别失败
 

    if ($command==70 )   //&& $cishu<10  //success
	
	{
		   		 
			 set_time_limit(1000);//无限请求超时时间    
			$i=0;    
			while (true){    
				//sleep(1);    
				usleep(1000);//0.5�?  
				$i++;    
					
				       
				  
 $sql="insert into user_transaction  (user,transactionid,dateline,barcode,metal,weight,bors,diam,recognitionstatus,rebateordonate,bottlevalue,payplatform) 
			  
			  values ('$user','$transactionid','$dateline','$barcode','$metal','$weight','$bors','$diam','1','0','$bottlevalue','$payplatform')";
			    
			  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 
			 mysqli_query($link,$sql);	 
			 
			 
			 
				  
		 	  $sql="update command set recognitionstatus=0 ,diam=0,weight=0,isbottle=0";
			 mysqli_query($link,$sql);	 
			 
			 
			   
			 
			 
	$sql="select sum(bottlevalue) as totalvalue from user_transaction where transactionid='$transactionid'and recognitionstatus=1";
$totalvalue=  mysqli_query($link,$sql);
$totalvalue=mysqli_fetch_array($totalvalue);
$totalvalue=$totalvalue['totalvalue'];		 
			 
  
$bottlevalue=$bottlevalue*0.01;

$bottlevalue=number_format($bottlevalue,2);
 
 if($metal==1)
{

	 
	 
			$sql = "update command set can=can+1,can_value=can_value+'$bottlevalue',metal=0"; 
		mysqli_query($link, $sql);
 
 
		
		 
	
}
elseif($metal==0)
{
	
		$sql = "update command set bottle=bottle+1,pet_value=pet_value+'$bottlevalue',metal=0"; 
		mysqli_query($link, $sql);
		
	
	
}
 
elseif($metal==2)
{
	
		$sql = "update command set glass=glass+1,glass_value=glass_value+'$bottlevalue',metal=0"; 
		mysqli_query($link, $sql);
		
	
	
}
 
 
  
 
// 	$arr=array('success'=>"1",'num'=>1,'value'=>$barcode,'bottlevalue'=>$bottlevalue,'totalvalue'=>$totalvalue,'metal'=>$metal,);    
   $arr=array('success'=>"1",'num'=>1,'value'=>$barcode,'bottlevalue'=>$bottlevalue,'totalvalue'=>1,'metal'=>$metal,);    
    echo json_encode($arr); 
					
					
					
 
 



  if($totalvalue==$limited)
 {
	 $sql="update command set command=2";
			 // mysqli_query($link,$sql);	
  }
			 
			 
			 
			 
				   exit();  



			} 
	  
	 
	  
}  
      
        
	//1开门成�?2到位成功  3:没扫描到 4条码不符 5超重 6过轻 7回收成功 8回收失败 9退瓶成�?10关门成功 11机内有瓶�?12图像识别失败
 

elseif ($command==5   )    //overweight
	
	{
		   
		  	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"2",'num'=>'0','value'=>$barcode);    
        echo json_encode($arr);    
      

 
 	    
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt)
  
  values ('$user','$transactionid','$dateline','$statecode','$barcode','$weight','$bors','$diam','2','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 

 mysqli_query($link,$sql);	 
      $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
 
 
       exit();  



} 
	  
	 
	 
	  
} 






elseif ( $command==6 )    //overweight
	
	{
		   
		  	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"8",'num'=>'0','value'=>$barcode);    
        echo json_encode($arr);    
      

 
 	    
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt)
  
  values ('$user','$transactionid','$dateline','$statecode','$barcode','$weight','$bors','$diam','5','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 

 mysqli_query($link,$sql);	 
      $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
 
 
       exit();  



} 
	  
	 
	 
	  
} 






       
	//1开门成�?2到位成功  3:没扫描到 4条码不符 5超重 6过轻 7回收成功 8回收失败 9退瓶成�?10关门成功 11机内有瓶�?12图像识别失败
 
elseif ($command==4   )   //条码不匹�?
	
	{
		 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
	  
        $arr=array('success'=>"3",'num'=>"33",'value'=>$barcode);    
        echo json_encode($arr);    
     
		
		
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt)
  
  values ('$user','$transactionid','$dateline','$statecode','$barcode','$weight','$bors','$diam','3','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 

 mysqli_query($link,$sql);	 
 
 
 
 
      $sql="update command set recognitionstatus=0 ,diam=0,weight=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
 
       exit();  



} 
	  
	}
	  
	  	//1开门成�?2到位成功  3:没扫描到 4条码不符 5超重 6过轻 7回收成功 8回收失败 9退瓶成�?10关门成功 11机内有瓶�?12图像识别失败
 
 
elseif ($command==3   )   //条码没扫�?
	
	{
		 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
	  
//        $arr=array('success'=>"3",'num'=>"33",'value'=>"1");    
        echo '{"success":"6","num":"33","value":" "}';    //没扫�?
     
		
		
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt)
  
  values ('$user','$transactionid','$dateline','$statecode','0','$weight','$bors','$diam','6','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 

 mysqli_query($link,$sql);	 
 
 
 
 
      $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
 
       exit();  



} 
	  
	}

	//1开门成�?2到位成功  3:没扫描到 4条码不符 5超重 6过轻 7回收成功 8回收失败 9退瓶成�?10关门成功 11机内有瓶�?12图像识别失败
 
 
elseif ($command==12)   //图像识别不通过 
	
	{
		  
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"4",'num'=>"0",'value'=>$barcode);    
        echo json_encode($arr);    
      
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt)
  
  values ('$user','$transactionid','$dateline','$statecode','$barcode','$weight','$bors','$diam','4','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 

 mysqli_query($link,$sql);	 
 
 
     $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
       exit();  



} 
	 

 
	  
} 



elseif ($command==13)   //条码与图像识别不符合  
	
	{
		  
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"7",'num'=>"0",'value'=>$barcode);    
        echo json_encode($arr);    
      
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt) 
  
  values ('$user','$transactionid','$dateline','$statecode','$barcode','$weight','$bors','$diam','7','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 

 mysqli_query($link,$sql);	 
 
 
     $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
       exit();  



} 
	 

 
	  
} 



elseif ($command==14)   
	
	{
		  
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"9",'num'=>"0",'value'=>$barcode);    
        echo json_encode($arr);    
  	 
 
 
     $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
       exit();  



} 
	 

 
	  
}

elseif ($command==15)   //条码与图像识别不符合  
	
	{
		  
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"10",'num'=>"0",'value'=>$barcode);    
        echo json_encode($arr);    
      
 
 
 
     $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
       exit();  



} 
	 

 
	  
}

elseif ($command==8)   //    欺诈
	
	{
		  
	 
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
		       $arr=array('success'=>"5",'num'=>"0",'value'=>$barcode);    
        echo json_encode($arr);    
		
		
		
		
       $sql="insert into user_transaction  (user,transactionid,dateline,statecode,barcode,weight,bors,diam,recognitionstatus,charityid,rebateordonate,bottlevalue,payplatform,charityname,octreceipt) 
  
  values ('$user','$transactionid','$dateline','$statecode','$barcode','$weight','$bors','$diam','5','$charityid','$rebateordonate','$bottlevalue','$payplatform','$charityname','$octreceipt')";
  
  mysqli_query($link,$sql);	 
 
  //echo  $transactionid,$dateline,$statecode,$user,$barcode,$brand,$bottleinfo,$weight,$recognitionstatus,$rebateordonate,$bottlevalue;
 
 
 
  $sql="update command set recognitionstatus=0 ,diam=0,weight=0";
 mysqli_query($link,$sql);	 
 
 
 
       exit();  
  
		} 
	  
	  
} 

	
	
elseif ($block==19)   //图像识别不通过 
	
	{
		
		 
		 
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
	while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"19" );    
        echo json_encode($arr);    
      
		  
		  
	  
	 
		   exit();  
		   
		   
			
			
		}
		
	
	}
	
	
	
	
	
elseif  ($errorcode & 0x10  || $errorcode & 0x01  ||  $errorcode & 0x02  || $errorcode & 0x20 || $errorcode & 0x80  )
 
	{
		
		 
		 
	 
 set_time_limit(1000000);//无限请求超时时间    
$i=0;    
	while (true){    
    //sleep(1);    
    usleep(1000000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"20" );    
        echo json_encode($arr);    
      
		  
		  
	  
	 
		   exit();  
		   
		   
			
			
		}
		
	
	}
	
	
	
	
	
 


else 

{

  
      
        
 set_time_limit(100000);//无限请求超时时间    
$i=0;    
while (true){    
    //sleep(1);    
    usleep(100000);//0.5�?  
    $i++;    
        
      
        $arr=array('success'=>"0",'num'=>'1','value'=>"1");    
        echo json_encode($arr);    
        exit();  

    

 
}




      
}


 

	 
		
		
	 
  
	 
  
   
 ?>
 
 
   