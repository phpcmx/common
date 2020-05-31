<?php

use app\Bootstrap;
use phpcmx\common\app\App;

include_once './vendor/autoload.php';

App::registerBootstrap(new Bootstrap());
$app = App::run();
