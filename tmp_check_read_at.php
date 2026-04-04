<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$l = App\Models\Lecture::first();
if (!$l) {
    echo "no lecture\n";
    return;
}
var_dump($l->read_at);
echo 'class:'.get_class($l->read_at)."\n";
