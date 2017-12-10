<?php 
// EDIT THE 2 LINES BELOW AS REQUIRED
$send_email_to = "136800719.com";
$email_subject = "明信片寄送请求";
function send_email($name,$recv_addr,$email_message,$phone)
{
  global $send_email_to;
  global $email_subject;
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
  //$headers .= "From: ".$email. "\r\n";
  $headers .= "From: cugbinbin@163.com\r\n";
  //$message = "<strong>Email = </strong>".$email."<br>";
  $message = "<strong>Email = </strong>".$recv_addr."<br>";
  $message .= "<strong>Name = </strong>".$name."<br>";  
  if(!empty($phone))
	$message .= "<strong>Phone = </strong>".$phone."<br>";
  $message .= "<strong>Message = </strong>".$email_message."<br>";
  @mail($send_email_to, $email_subject, $message,$headers);
  return true;
}

function validate($name,$recv_addr,$message,$phone)
{
  $return_array = array();
  $return_array['success'] = '1';
  $return_array['name_msg'] = '';
  $return_array['email_msg'] = '';
  $return_array['message_msg'] = '';
  if($recv_addr == '')
  {
    $return_array['success'] = '0';
    $return_array['email_msg'] = '收件人地址不能为空';
  }
  else
  {
    //$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    //if(!preg_match($email_exp,$email)) {
    //  $return_array['success'] = '0';
    //  $return_array['email_msg'] = 'enter valid email.';  
    //}
  }
  if($name == '')
  {
    $return_array['success'] = '0';
    $return_array['name_msg'] = '收件人名字不能为空';
  }
  else
  {
    $string_exp = "/^[A-Za-z .'-]+$/";
    if (!preg_match($string_exp, $name)) {
      $return_array['success'] = '0';
      $return_array['name_msg'] = 'enter valid name.';
    }
  }
  $string_exp = "/^[A-Za-z .'-]+$/";
  //if (preg_match($string_exp, $phone)) {
	//	$return_array['success'] = '0';
	//	$return_array['phone_msg'] = 'enter valid phone no.';
  //}			
  //if($message == '')
  //{
  //  $return_array['success'] = '0';
  //  $return_array['message_msg'] = 'message is required';
  //}
  //else
  //{
  //  if (strlen($message) < 2) {
  //    $return_array['success'] = '0';
   //   $return_array['message_msg'] = 'enter valid message.';
   // }
  }
  return $return_array;
}

$name = $_POST['recv_name']; // 明信片收件人作为发送标题
$recv_addr = $_POST['recv_addr']; // 收件人地址
$email = $_POST['email'];    // 请求寄送明信片发起者的邮箱
$message = $_POST['message'];// 想说的话
$phone = $_POST['send_phone'];    // 寄件人的电话

$return_array = validate($name,$recv_addr,$message,$phone);

if($return_array['success'] == '1')
{
	send_email($name,$recv_addr,$message,$phone);
}
header('Content-type: text/json');
echo json_encode($return_array);
die();
?>

