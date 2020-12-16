<?
/** 
 * Logging class:
 * - contains lfile, lwrite and lclose public methods
 * - lfile sets path and name of log file
 * - lwrite writes message to the log file (and implicitly opens log file)
 * - lclose closes log file
 * - first call of lwrite method will open log file implicitly
 * - message is written with the following format: [d/M/Y:H:i:s] (script name) message
 */
class Logging {
    // declare log file and file pointer as private properties
    private $log_file, $fp;
    // set log file (path and name)
    public function lfile($path) {
        $this->log_file = $path;
    }
    // write message to the log file
    public function lwrite($message) {
        // if file pointer doesn't exist, then open log file
        if (!is_resource($this->fp)) {
            $this->lopen();
        }
        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        // define current time and suppress E_WARNING if using the system TZ settings
        // (don't forget to set the INI setting date.timezone)
        $time = @date('[d/M/Y:H:i:s]');
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
    }
    // close log file (it's always a good idea to close a file when you're done with it)
    public function lclose() {
        fclose($this->fp);
    }
    // open log file (private method)
    private function lopen() {
        // in case of Windows set default log file
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'c:/php/logfile.txt';
        }
        // set default log file for Linux and other systems
        else {
            $log_file_default = '/tmp/logfile.txt';
        }
        // define log file from lfile method or use previously set default
        $lfile = $this->log_file ? $this->log_file : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
    }
}

function add_activity_boxberry_send() {
    $handlerUrl = 'https://app.cmpgroup.ru/odas/handler_boxberry_send.php';
    $newActivity = CRest::call(
    'bizproc.activity.add',
    [
        'CODE' => 'activityBoxberrySend',
        'HANDLER' => $handlerUrl,
        'AUTH_USER_ID' => 1,
        'NAME' => [
                    'ru' => 'Активити отправки заказа в Boxberry',
                    'en' => 'Activity summa works'
                    ],
        'DESCRIPTION' => 'Активити отправки заказа в Boxberry',
        'PROPERTIES' => [
            'ORDER_ID' => [
                'Name' => [
                    'ru' => 'ID заказа ИМ',
                    'en' => 'Order ID'
                    ],
                'Description' => [
                    'ru' => 'Введите ID заказа',
                    'en' => 'Enter Order ID'
                    ],
                'Type' => 'string',
                'Required' => 'Y',
                'Multiple' => 'N',
                'Default' => null
            ],
			'price' => [
                'Name' => [
                    'ru' => 'Объявленная стоимость посылки',
                    'en' => 'price'
                    ],
                'Description' => [
                    'ru' => 'Введите объявленную стоимость',
                    'en' => 'Enter price'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'payment_sum' => [
                'Name' => [
                    'ru' => 'Сумма к оплате с получателя',
                    'en' => 'payment_sum'
                    ],
                'Description' => [
                    'ru' => 'Введите cумму к оплате с получателя',
                    'en' => 'Enter payment sum'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'vid' => [
                'Name' => [
                    'ru' => 'Способ доставки',
                    'en' => 'Vid'
                    ],
                'Description' => [
                    'ru' => 'Введите способ доставки 1- Доставка до пункта выдачи (ПВЗ) 2 - Курьерская доставка (КД)',
                    'en' => 'Enter Vid'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'index' => [
                'Name' => [
                    'ru' => 'Почтовый индекс',
                    'en' => 'Postcode'
                    ],
                'Description' => [
                    'ru' => 'Введите Почтовый индекс',
                    'en' => 'Enter Postcode'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'city' => [
                'Name' => [
                    'ru' => 'Город доставки',
                    'en' => 'City'
                    ],
                'Description' => [
                    'ru' => 'Введите Город доставки',
                    'en' => 'Enter City'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'addressp' => [
                'Name' => [
                    'ru' => 'Адрес доставки',
                    'en' => 'Addressp'
                    ],
                'Description' => [
                    'ru' => 'Введите Адрес доставки',
                    'en' => 'Enter addressp'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'kod_pvz' => [
                'Name' => [
                    'ru' => 'Код ПВЗ',
                    'en' => 'kod_pvz'
                    ],
                'Description' => [
                    'ru' => 'Введите Код ПВЗ',
                    'en' => 'Enter kod_pvz'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'fio' => [
                'Name' => [
                    'ru' => 'ФИО',
                    'en' => 'fio'
                    ],
                'Description' => [
                    'ru' => 'Введите ФИО',
                    'en' => 'Enter fio'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'phone' => [
                'Name' => [
                    'ru' => 'Телефон',
                    'en' => 'phone'
                    ],
                'Description' => [
                    'ru' => 'Введите Телефон',
                    'en' => 'Enter phone'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'email' => [
                'Name' => [
                    'ru' => 'email',
                    'en' => 'email'
                    ],
                'Description' => [
                    'ru' => 'Введите email',
                    'en' => 'Enter email'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'weight' => [
                'Name' => [
                    'ru' => 'Вес посылки',
                    'en' => 'weight'
                    ],
                'Description' => [
                    'ru' => 'Введите Вес посылки',
                    'en' => 'Enter weight'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			'issue' => [
                'Name' => [
                    'ru' => 'Действия с упаковкой',
                    'en' => 'issue'
                    ],
                'Description' => [
                    'ru' => 'Со вскрытием или без',
                    'en' => 'Enter issue'
                    ],
                'Type' => 'string',
                'Required' => 'N',
                'Multiple' => 'N',
                'Default' => null
            ],
			
        ],
        
        'RETURN_PROPERTIES' => [
            'track' => [
                'Name' => [
                    'ru' => 'Трек-номер посылки для отслеживания',
                    'en' => 'Track number'
                ],
                'Type' => 'string',
                'Multiple' => 'N',
                'Default' => null
            ],
			'label' => [
                'Name' => [
                    'ru' => 'Ссылка на печать этикетки (генерируется если не передан штрих-код интернет-магазина)',
                    'en' => 'Label'
                ],
                'Type' => 'string',
                'Multiple' => 'N',
                'Default' => null
            ],
            'status' => [
                'Name' => [
                    'ru' => 'Статус выполнения активити',
                    'en' => 'Activity status'
                ],
                'Type' => 'string',
                'Multiple' => 'N',
                'Default' => null
            ],
        ]
    ]
    );
}

function del_activity_boxberry_send() {
    $deleteActivity = CRest::call(
        'bizproc.activity.delete',
        [
            'CODE' => 'activityBoxberrySend'
        ]
    );
    
}

?>