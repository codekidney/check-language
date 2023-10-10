<?php
require_once('vendor/autoload.php');
require_once('config.php');
setlocale(LC_ALL, 'en_US.UTF-8');

use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Datasets\Unlabeled;

$start = microtime(TRUE);
$filename = 'model.rbx';
$pipeline = PersistentModel::load(new Filesystem($filename));

$text = $_POST['text'];
$dataset = new Unlabeled([[$text]]);
$prediction = $pipeline->predict($dataset);

$end = microtime(TRUE);

$predictedLabel = $prediction[0];
$language = $languagesLong[$predictedLabel];
$result = [
    'language'=> $language,
    'time'=> round(($end-$start), 4),
];
echo json_encode($result);
