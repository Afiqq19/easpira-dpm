<?php
$p = App\Models\Periode::first();
if ($p) {
    App\Models\Organisasi::query()->update(['active_periode_id' => $p->id]);
    echo "Done";
}
