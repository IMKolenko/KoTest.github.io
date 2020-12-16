<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/application.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <title>Активити для БП</title>
</head>
<body>
    <?
        /*ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);*/
        require_once('crest.php');
        require_once('functions.php');
        //echo "hello world";
    ?>
    <div class="block">
        <div class="info">
            <?
            if (isset($_REQUEST['act']) && $_REQUEST['act'] == '1') {
                add_activity_boxberry_send();
                echo "Установлено Отправка заказа в boxberry";
            }
            
            if (isset($_REQUEST['act']) && $_REQUEST['act'] == '2') {
                del_activity_boxberry_send();
                echo "Удалено Отправка заказа в boxberry";
            }            
        
            ?>
        </div>
        <div id="desc_activ1" class="desc">Активити Отправка заказа в boxberry</div>
        <div id="btn_add_activ1">
            <a href="index.php?act=1" onclick="">Установить</a>
        </div>
        <div id="btn_del_activ1">
            <a href="index.php?act=2" onclick="">Удалить</a>
        </div>
    </div>
</body>
</html>