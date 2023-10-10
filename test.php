<?php

require_once('vendor/autoload.php');
setlocale(LC_ALL, 'en_US.UTF-8');

use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Datasets\Unlabeled;

// Load model
$filename = 'model.rbx';
$pipeline = PersistentModel::load(new Filesystem($filename));

$samples = [];
$labels = [];
$counter = 0;
$handle = fopen(sprintf('%s/data/test.csv', __DIR__), 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $counter++;
        if ($counter > 1000) {
            break;
        }
        if ($counter != 1) {
            $row = explode(',', $line, 2);
            $labels[] = $row[0];
            // $samples[] = $row[1];
            $samples[] = [$row[1]];
            if (!$row[1]) {
                var_dump($row);
            }
        }
    }

    fclose($handle);
}
$dataset = new Unlabeled($samples);
$prediction = $pipeline->predict($dataset);

$diffValues = count(array_diff($labels, $prediction));
$allValues = count($labels);
$score = (($allValues - $diffValues) / $allValues) * 100;
echo "\e[0;36;40mScore: \e[0;33;40m", $score, " \e[0mDifferent values: ", $diffValues, " \e[0;32;40mAll values: ", $allValues, "\e[0m";
