<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\Package::all() as $p) {
    if (!$p->slug) {
        $p->slug = \Illuminate\Support\Str::slug($p->name);
        $p->save();
    }
}
foreach(\App\Models\Car::all() as $c) {
    if (!$c->slug) {
        $c->slug = \Illuminate\Support\Str::slug($c->name);
        $c->save();
    }
}
echo "Slugs updated successfully\n";
