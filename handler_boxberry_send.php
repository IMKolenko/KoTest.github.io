<?
require_once('crest.php');
require_once('functions.php');

if (!isset($_REQUEST['properties'])) {
	echo 'Некорректный запрос';
	exit;
}

$log = new Logging();
 
// set path and name of log file (optional)
$log->lfile(__DIR__.'/logs/log.txt');

$from_b24 = $_REQUEST['properties'];

// Записываем полученный массив из Б24 в лог
$log->lwrite(json_encode($from_b24, TRUE));

$track = $from_b24['ORDER_ID'];

foreach ($from_b24['phone'] as $phone_row) {
	$phone = $phone_row['VALUE'];
}

foreach ($from_b24['email'] as $email_row) {
	$email = $email_row['VALUE'];
}

$price = mb_strstr($from_b24['price'],"|",true);
$payment_sum = mb_strstr($from_b24['payment_sum'],"|",true);

$label = 'ORDER_ID '.$from_b24['ORDER_ID'].' price '.$price.' payment_sum '.$payment_sum.' vid '.$from_b24['vid'].' index '.$from_b24['index'].' city '.$from_b24['city'].' addressp '.$from_b24['addressp'].' kod_pvz '.$from_b24['kod_pvz'].' fio '.$from_b24['fio'].' phone '.$phone.' email '.$email.' weight '.$from_b24['weight'];

// Подготовка запроса в boxberry

$SDATA=array();
//$SDATA['updateByTrack']='Трекинг-код ранее созданной посылки';
$SDATA['order_id']=$from_b24['ORDER_ID'];
//$SDATA['PalletNumber']='Номер палеты';
//$SDATA['barcode']='Штрих-код заказа';
$SDATA['price']=$price;
$SDATA['payment_sum']=$payment_sum;
//$SDATA['delivery_sum']='Стоимость доставки';
$SDATA['vid']=$from_b24['vid'];
if ($SDATA['vid'] == '2') {
	$SDATA['shop']=array(
		'name1'=> '010'
	);
	$SDATA['kurdost'] = array(
//		'index' => 'Индекс',
		'citi' => $from_b24['city'],
		'addressp' => $from_b24['addressp'],
//		'timesfrom1' => 'Время доставки, от',
//		'timesto1' => 'Время доставки, до',
//		'timesfrom2' => 'Альтернативное время, от',
//		'timesto2' => 'Альтернативное время, до',
//		'timep' => 'Время доставки текстовый формат',
//		'delivery_date' => "Дата доставки от +1 день до +5 дней от текущий даты (только для доставки по Москве, МО и Санкт-Петербургу)"
//		'comentk' => 'Комментарий'
	);
} else {
	$SDATA['shop']=array(
		'name'=> $from_b24['kod_pvz'],
		'name1'=> '010'
	);
}

$SDATA['customer']=array(
    'fio'=> $from_b24['fio'],
    'phone'=> $phone,
//    'phone2'=>'Доп. номер телефона',
    'email'=> $email,
//    'name'=>'Наименование организации',
//    'address'=>'Адрес',
//    'inn'=>'ИНН',
//    'kpp'=>'КПП',
//    'r_s'=>'Расчетный счет',
//    'bank'=>'Наименование банка',
//    'kor_s'=>'Кор. счет',
//    'bik'=>'БИК'
);

                
$SDATA['weights']=array(
    'weight'=> $from_b24['weight'],
//    'barcode'=>'Баркод 1-го места',
);


if($from_b24['issue'] == 'Y') {
	$SDATA['issue']=1;
} else {
	$SDATA['issue']=0;
}
$SDATA['sender_name']= 'NORWEGIAN Fish Oil';


// Предполагается что Вы уже создали массив $SDATA по описанному выше примеру.
// Отправляем массив на сервер boxberry используя CURL.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.boxberry.ru/json.php');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                  'token'=>'',
                  'method'=>'ParselCreate',
                  'sdata'=>json_encode($SDATA)
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch),1);
            if($data['err'] or count($data)<=0)
            {
                  // если произошла ошибка и ответ не был получен.
				$result = CRest::call(
					'bizproc.event.send',
					[
						'AUTH' => $_REQUEST['auth']['access_token'],
						'EVENT_TOKEN' => $_REQUEST['event_token'],
						'LOG_MESSAGE' => 'Ошибка: '.$data['err'],
						'RETURN_VALUES' => array(
							'status' => 'Произошла ошибка:'.$data['err'],
						)
					]
				);
				$log->lwrite('При отправке заказа: '.$SDATA['order_id'].'Произошла ошибка:'.$data['err']);
            }
            else
            {
				  
				$result = CRest::call(
					'bizproc.event.send',
					[
						'AUTH' => $_REQUEST['auth']['access_token'],
						'EVENT_TOKEN' => $_REQUEST['event_token'],
						'LOG_MESSAGE' => 'Запрос успешно выполнен',
						'RETURN_VALUES' => array(
							'track' => $data['track'],
							'label' => $data['label'],
							'status' => 'Заказ успешно отправлен в boxberry',
						)
					]
				);
				$log->lwrite('Отправка заказа: '.$SDATA['order_id'].' в боксберри успешна. Були получены следующие данные: Трэк-номер: '.$data['track'].' ссылка: '.$data['label']);
            }
			
$log->lclose();

?>
