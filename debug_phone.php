<?php
// Quick debug script
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Packages phone_visibility ===" . PHP_EOL;
$packages = App\Models\Package::all(['name', 'phone_visibility']);
foreach ($packages as $p) {
    echo "  {$p->name}: [{$p->phone_visibility}]" . PHP_EOL;
}

echo PHP_EOL . "=== Juma Cheche subscription ===" . PHP_EOL;
$user = App\Models\User::where('name', 'like', '%Juma%')->first();
if ($user) {
    echo "  User: {$user->name}, Phone in DB: {$user->phone}" . PHP_EOL;
    $sub = $user->activeSubscription;
    if ($sub) {
        echo "  Active sub found: {$sub->package_name_snapshot}" . PHP_EOL;
        echo "  phone_visibility_snapshot: [{$sub->phone_visibility_snapshot}]" . PHP_EOL;
    } else {
        echo "  No active subscription!" . PHP_EOL;
        $allSubs = App\Models\UserPackage::where('user_id', $user->id)->get(['package_name_snapshot','phone_visibility_snapshot','status','start_date','end_date']);
        foreach ($allSubs as $s) {
            echo "  Sub: {$s->package_name_snapshot} | [{$s->phone_visibility_snapshot}] | {$s->status} | {$s->start_date} - {$s->end_date}" . PHP_EOL;
        }
    }
    $details = $user->currentPackageDetails();
    echo "  currentPackageDetails phone_visibility: [{$details['phone_visibility']}]" . PHP_EOL;
    echo "  Condition (=== 'No'): " . ($details['phone_visibility'] === 'No' ? 'TRUE - phone should be hidden' : 'FALSE - phone will show') . PHP_EOL;
} else {
    echo "  Juma not found" . PHP_EOL;
}
