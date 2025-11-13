<?php

namespace App\Services;

class AddressValidationService
{
    /**
     * Quezon City barangays list
     */
    protected $barangays = [
        'Alicia', 'Amihan', 'Apolonio Samson', 'Baesa', 'Bagbag', 'Bagong Silangan',
        'Bagumbayan', 'Bagumbuhay', 'Bahay Toro', 'Balingasa', 'Balong Bato', 'Batasan Hills',
        'Bayanihan', 'Blue Ridge A', 'Blue Ridge B', 'Botocan', 'Bungad', 'Camp Aguinaldo',
        'Capri', 'Central', 'Claro', 'Commonwealth', 'Culiat', 'Damar', 'Damayan',
        'Damayang Lagi', 'Del Monte', 'Diliman', 'Doña Aurora', 'Doña Imelda', 'Doña Josefa',
        'Duyan-Duyan', 'E. Rodriguez', 'East Kamias', 'Escopa I', 'Escopa II', 'Escopa III',
        'Escopa IV', 'Fairview', 'Galas', 'Greater Lagro', 'Gulod', 'Holy Spirit', 'Horseshoe',
        'Immaculate Concepcion', 'Kaligayahan', 'Kalusugan', 'Kamuning', 'Katipunan', 'Kaunlaran',
        'Kristong Hari', 'Krus na Ligas', 'Laging Handa', 'Libis', 'Lourdes', 'Loyola Heights',
        'Maharlika', 'Malaya', 'Mangga', 'Manresa', 'Mariana', 'Mariblo', 'Marilag', 'Masagana',
        'Masambong', 'Matalahib', 'Matandang Balara', 'Milagrosa', 'Nagkaisang Nayon', 'Nayon Kaunlaran',
        'New Era', 'North Fairview', 'Novaliches Proper', 'N.S. Amoranto', 'Obrero', 'Old Capitol Site',
        'Paang Bundok', 'Pag-ibig sa Nayon', 'Paligsahan', 'Paltok', 'Pansol', 'Paraiso',
        'Pasong Putik Proper', 'Pasong Tamo', 'Payatas', 'Phil-Am', 'Pinagkaisahan', 'Pinyahan',
        'Project 6', 'Quirino 2-A', 'Quirino 2-B', 'Quirino 2-C', 'Quirino 3-A', 'Quirino 3-B',
        'Ramon Magsaysay', 'Roxas', 'Sacred Heart', 'Salvacion', 'San Agustin', 'San Antonio',
        'San Bartolome', 'San Isidro', 'San Isidro Galas', 'San Jose', 'San Martin de Porres',
        'San Roque', 'San Vicente', 'Sangandaan', 'Santa Cruz', 'Santa Lucia', 'Santa Monica',
        'Santa Teresita', 'Santo Cristo', 'Santo Domingo', 'Santo Niño', 'Santol', 'Sauyo',
        'Sikatuna Village', 'Silangan', 'Socorro', 'South Triangle', 'St. Ignatius', 'St. Peter',
        'Tagumpay', 'Talayan', 'Talipapa', 'Tandang Sora', 'Tatalon', 'Teachers Village East',
        'Teachers Village West', 'Ugong Norte', 'Unang Sigaw', 'University of the Philippines',
        'Valencia', 'Vasra', 'Veterans Village', 'Villa Maria Clara', 'West Kamias', 'West Triangle',
        'White Plains',
    ];

    /**
     * Validate if address is in Quezon City
     */
    public function validateQuezonCityAddress(string $address, ?string $barangay = null): bool
    {
        $addressLower = strtolower($address);
        $qcKeywords = ['quezon city', 'qc', 'q.c.'];
        
        $hasQcKeyword = false;
        foreach ($qcKeywords as $keyword) {
            if (strpos($addressLower, $keyword) !== false) {
                $hasQcKeyword = true;
                break;
            }
        }

        if (!$hasQcKeyword) {
            return false;
        }

        // If barangay is provided, validate it
        if ($barangay) {
            return $this->isValidBarangay($barangay);
        }

        return true;
    }

    /**
     * Check if barangay is valid
     */
    public function isValidBarangay(string $barangay): bool
    {
        return in_array($barangay, $this->barangays);
    }

    /**
     * Get all barangays
     */
    public static function getBarangays(): array
    {
        $instance = new self();
        return $instance->barangays;
    }

    /**
     * Get barangay coordinates (simplified - using QC center coordinates)
     * In production, you'd have a database of barangay coordinates
     */
    public function getBarangayCoordinates(string $barangay): ?array
    {
        // Default QC coordinates (city center)
        // In production, this should be a database lookup
        return [
            'latitude' => 14.6760,
            'longitude' => 121.0437,
        ];
    }
}

