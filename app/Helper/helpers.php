<?php
if (!function_exists('midtrans_config')) {
    function midtrans_config($key, $default = null) {
        $value = null;
        try {
            $value = \App\Models\Setting::getValue($key);
        } catch (\Exception $e) {
            // fallback ke .env jika DB belum siap
        }

        // fallback ke env jika null atau string kosong
        if ($value === null || trim($value) === '') {
            return env('MIDTRANS_' . strtoupper($key), $default);
        }

        return $value;
    }
}
