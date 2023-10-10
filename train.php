<?php
require_once('vendor/autoload.php');
setlocale(LC_ALL, 'en_US.UTF-8');
$samples = [];
$labels = [];
// https://github.com/RubixML/ML/issues/189

$counter = 0;
$handle = fopen(sprintf('%s/data/train.csv', __DIR__), 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $counter++;
        if ($counter > 20000) {
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

// 1. Transforming - Pipeline (middleware)
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Pipeline;
use Rubix\ML\Tokenizers\NGram;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Transformers\ZScaleStandardizer;

$pipeline = new Pipeline([
    new TextNormalizer(),
    new WordCountVectorizer(10000, 1, 0.4, new NGram(1, 2)),
    new TfIdfTransformer(1.0, false),
    new ZScaleStandardizer(),
], new GaussianNB());

// 2. Training
$dataset = new Labeled($samples, $labels);
$pipeline->train($dataset);

// // Save model
$filename = 'model.rbx';
$model = new PersistentModel($pipeline, new Filesystem($filename));
$model->save();

// // Load model
// $pipeline = PersistentModel::load(new Filesystem($filename));

// // Add to model ne values
// $dataset = new Labeled([['Urodziny mamy to często spore wyzwanie dla najbliższych']], ['pl']);
// $pipeline->partial($dataset);

// // Prediction
// $dataset = new Unlabeled([
//     ['Where are you going?'],
//     ['Utilisez les suggestions ci-dessous et mettez-les sur une carte de vœux'],
//     ['Che succede?'],
//     ['Skorzystaj z poniższych propozycji i umieść je na kartce okolicznościowej'],
// ]);

// $prediction = $pipeline->predict($dataset);

// var_dump($prediction);
