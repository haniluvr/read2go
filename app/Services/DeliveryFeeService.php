<?php

namespace App\Services;

use App\Models\Library;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryFeeService
{
    protected $baseFee;
    protected $feePerKm;
    protected $googleMapsApiKey;

    public function __construct()
    {
        $this->baseFee = config('app.delivery_fee_base', env('DELIVERY_FEE_BASE', 50));
        $this->feePerKm = config('app.delivery_fee_per_km', env('DELIVERY_FEE_PER_KM', 10));
        $this->googleMapsApiKey = config('services.google_maps.api_key', env('GOOGLE_MAPS_API_KEY'));
    }

    /**
     * Calculate delivery fee based on distance
     */
    public function calculateDeliveryFee(Library $library, User $user): float
    {
        $distance = $this->calculateDistance($library, $user);
        
        if ($distance === null) {
            // If distance calculation fails, return base fee
            return $this->baseFee;
        }

        // Calculate fee: base fee + (distance in km * fee per km)
        $fee = $this->baseFee + ($distance * $this->feePerKm);
        
        return round($fee, 2);
    }

    /**
     * Calculate distance between library and user address
     */
    protected function calculateDistance(Library $library, User $user): ?float
    {
        // If both have coordinates, use Haversine formula
        if ($library->latitude && $library->longitude && $user->latitude && $user->longitude) {
            return $this->haversineDistance(
                $library->latitude,
                $library->longitude,
                $user->latitude,
                $user->longitude
            );
        }

        // If Google Maps API key is available, use Distance Matrix API
        if ($this->googleMapsApiKey) {
            return $this->getDistanceFromGoogleMaps($library, $user);
        }

        // Fallback: use barangay-based estimation
        return $this->estimateDistanceByBarangay($library, $user);
    }

    /**
     * Calculate distance using Haversine formula (in kilometers)
     */
    protected function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Get distance from Google Maps Distance Matrix API
     */
    protected function getDistanceFromGoogleMaps(Library $library, User $user): ?float
    {
        try {
            $origin = "{$library->latitude},{$library->longitude}";
            $destination = "{$user->latitude},{$user->longitude}";

            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins' => $origin,
                'destinations' => $destination,
                'key' => $this->googleMapsApiKey,
                'units' => 'metric',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['rows'][0]['elements'][0]['distance']['value'])) {
                    // Distance in meters, convert to kilometers
                    $distanceInMeters = $data['rows'][0]['elements'][0]['distance']['value'];
                    return round($distanceInMeters / 1000, 2);
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Google Maps Distance API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Estimate distance based on barangay (simplified estimation)
     */
    protected function estimateDistanceByBarangay(Library $library, User $user): float
    {
        // Simplified estimation: if same barangay = 2km, different = 5km average
        // In production, you'd have a distance matrix for barangays
        if ($library->address && $user->barangay) {
            // Very basic estimation
            return 5.0; // Default 5km estimation
        }

        return 5.0; // Default fallback
    }
}

