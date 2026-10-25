<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\PurchaseRequest;
use App\Models\RentalBooking;
use App\Models\RentalInspection;
use App\Models\SalesTransaction;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleImage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive automotive demo data.
     */
    public function run(): void
    {
        // 1. Create Core Users
        $password = Hash::make('password');

        $admin = User::create([
            'name' => 'Tariku Meseret',
            'email' => 'admin@autodrive.com',
            'password' => $password,
            'role' => 'admin',
            'phone' => '+1 (555) 019-2831',
            'address' => '100 Ocean Drive, Suite 500, Miami, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-ADM-99482',
        ]);

        $admin2 = User::create([
            'name' => 'Tewodros Kassahun',
            'email' => 'tewodros.admin@autodrive.com',
            'password' => $password,
            'role' => 'admin',
            'phone' => '+1 (555) 019-7721',
            'address' => '100 Ocean Drive, Suite 502, Miami, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-ADM-88391',
        ]);

        $rentalManager = User::create([
            'name' => 'Merawi Ayele',
            'email' => 'rental@autodrive.com',
            'password' => $password,
            'role' => 'rental_manager',
            'phone' => '+1 (555) 018-9102',
            'address' => '240 Brickell Ave, Miami, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-RNT-48192',
        ]);

        $rentalManager2 = User::create([
            'name' => 'Bereket Tadesse',
            'email' => 'bereket.rental@autodrive.com',
            'password' => $password,
            'role' => 'rental_manager',
            'phone' => '+1 (555) 018-4493',
            'address' => '245 Brickell Ave, Miami, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-RNT-59281',
        ]);

        $salesManager = User::create([
            'name' => 'Fitsum Gashaw',
            'email' => 'sales@autodrive.com',
            'password' => $password,
            'role' => 'sales_manager',
            'phone' => '+1 (555) 017-7431',
            'address' => '450 Biscayne Blvd, Miami, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-SLS-39104',
        ]);

        $supportAgent = User::create([
            'name' => 'Yosef Habtamu',
            'email' => 'support@autodrive.com',
            'password' => $password,
            'role' => 'customer_support',
            'phone' => '+1 (555) 014-6621',
            'address' => '12 Collins Ave, Miami Beach, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-SPT-88120',
        ]);

        $customer1 = User::create([
            'name' => 'Kassaw Getahun',
            'email' => 'customer@autodrive.com',
            'password' => $password,
            'role' => 'customer',
            'phone' => '+1 (555) 011-3392',
            'address' => '780 Palm Way, Coral Gables, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-CUS-10294',
        ]);

        $customer2 = User::create([
            'name' => 'Bisrat Getachew',
            'email' => 'sophia@example.com',
            'password' => $password,
            'role' => 'customer',
            'phone' => '+1 (555) 013-5582',
            'address' => '910 Sunset Islands, Miami, FL',
            'status' => 'active',
            'driver_license_number' => 'DL-CUS-55421',
        ]);

        // 2. Create Vehicle Categories
        $categoriesData = [
            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'description' => 'Bespoke prestige, handcrafted cabins, unmatched acoustic serenity and refined elegance.',
                'icon' => 'sparkles',
                'image' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Track-bred agility, visceral acceleration, racing pedigree and razor-sharp steering.',
                'icon' => 'bolt',
                'image' => 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Sedan',
                'slug' => 'sedan',
                'description' => 'Executive aerodynamic silhouettes, high-speed stability and supreme daily road comfort.',
                'icon' => 'briefcase',
                'image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'SUV',
                'slug' => 'suv',
                'description' => 'Commanding elevated perspectives, all-terrain confidence and generous passenger volume.',
                'icon' => 'globe-alt',
                'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Electric',
                'slug' => 'electric',
                'description' => 'Instantaneous zero-rpm torque, silent grand touring and intelligent autonomic assistance.',
                'icon' => 'cpu-chip',
                'image' => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Pickup',
                'slug' => 'pickup',
                'description' => 'Heavy-duty steel architecture, multi-ton towing capability and refined interior luxury.',
                'icon' => 'truck',
                'image' => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[$data['slug']] = VehicleCategory::create($data);
        }

        // 3. Create Vehicles
        $vehiclesData = [
            [
                'category_id' => $categories['sports']->id,
                'brand' => 'Porsche',
                'model' => '911 GT3 RS',
                'year' => 2024,
                'vin' => 'WP0AC2A94RS109283',
                'license_plate' => 'GT3-911',
                'color' => 'Guards Red with Carbon Aero',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 2,
                'mileage' => 2150,
                'engine_capacity' => '4.0L Naturally Aspirated Flat-6 (518 HP)',
                'daily_rate' => 950.00,
                'selling_price' => 279000.00,
                'listing_type' => 'both',
                'status' => 'available',
                'condition' => 'certified_preowned',
                'description' => 'The definitive road-legal motorsport instrument. Featuring DRS aerodynamics, active carbon wings, front-axle lift system, and an unforgettable 9,000 RPM redline.',
                'features' => ['Weissach Package', 'Ceramic Composite Brakes (PCCB)', 'Carbon Full Bucket Seats', 'Chrono Package', 'Front Axle Lift System', 'Bose Surround Audio'],
                'main_image' => 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['luxury']->id,
                'brand' => 'Mercedes-Maybach',
                'model' => 'S 680 4MATIC',
                'year' => 2024,
                'vin' => 'W1K2231761A993812',
                'license_plate' => 'MYB-680',
                'color' => 'Two-Tone Obsidian Black & High-Tech Silver',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 4,
                'mileage' => 4800,
                'engine_capacity' => '6.0L Bi-Turbo V12 (621 HP)',
                'daily_rate' => 1200.00,
                'selling_price' => 315000.00,
                'listing_type' => 'both',
                'status' => 'available',
                'condition' => 'new',
                'description' => 'Unsurpassed pinnacle of executive travel. Equipped with rear first-class reclining suites, active road-noise cancellation, Burmester 4D High-End audio, and champagne flute refrigeration.',
                'features' => ['Executive Rear Lounge', 'Burmester High-End 4D Sound', 'Rear Entertainment Touchscreens', 'Electrically Powered Comfort Doors', 'Champagne Chiller Compartment', 'Air Suspension'],
                'main_image' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['sedan']->id,
                'brand' => 'BMW',
                'model' => 'M5 Competition',
                'year' => 2024,
                'vin' => 'WBS83EJ020CL39102',
                'license_plate' => 'M5-COMP',
                'color' => 'Marina Bay Blue Metallic',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 5,
                'mileage' => 8400,
                'engine_capacity' => '4.4L Twin-Turbocharged V8 (617 HP)',
                'daily_rate' => 620.00,
                'selling_price' => 128500.00,
                'listing_type' => 'rental',
                'status' => 'available',
                'condition' => 'certified_preowned',
                'description' => 'The benchmark performance super-sedan. Switchable M xDrive allows instant transitions between high-grip AWD and pure rear-wheel-drive drift capability.',
                'features' => ['M Drivers Package', 'Carbon Ceramic Brakes', 'Bowers & Wilkins Diamond Surround', 'Head-Up Display with Track Mode', 'Merino Leather Interior'],
                'main_image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['suv']->id,
                'brand' => 'Land Rover',
                'model' => 'Range Rover SV Autobiography',
                'year' => 2024,
                'vin' => 'SALWS2V40PA819201',
                'license_plate' => 'RR-SV01',
                'color' => 'Batumi Gold with Corinthian Bronze Roof',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 5,
                'mileage' => 6100,
                'engine_capacity' => '4.4L Twin-Turbo V8 (606 HP)',
                'daily_rate' => 850.00,
                'selling_price' => 242000.00,
                'listing_type' => 'both',
                'status' => 'available',
                'condition' => 'new',
                'description' => 'The absolute authority in luxury off-road composure. All-wheel steering, adaptive air suspension, active noise cancellation, and handcrafted semi-aniline leather.',
                'features' => ['All-Wheel Steering', 'Meridian Signature Sound System', 'SV Bespoke Semi-Aniline Leather', 'ClearSight Ground View', 'Executive Class Comfort Plus'],
                'main_image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['electric']->id,
                'brand' => 'Porsche',
                'model' => 'Taycan Turbo S Cross Turismo',
                'year' => 2024,
                'vin' => 'WP0BC2Y17PSA90231',
                'license_plate' => 'EV-TURBO',
                'color' => 'Frozen Blue Metallic',
                'transmission' => 'Automatic',
                'fuel_type' => 'Electric',
                'seating_capacity' => 5,
                'mileage' => 3200,
                'engine_capacity' => 'Dual Permanent-Magnet Motors (938 HP)',
                'daily_rate' => 780.00,
                'selling_price' => 218000.00,
                'listing_type' => 'both',
                'status' => 'available',
                'condition' => 'new',
                'description' => 'Unbelievable 0-60 in 2.3 seconds with 800V ultra-fast architecture. Combines sports car handling with all-weather gravel mode and generous wagon utility.',
                'features' => ['800-Volt Architecture', 'Porsche Active Ride Suspension', 'Burmester 3D High-End Audio', 'Off-Road Design Package', 'Passenger Display Screen'],
                'main_image' => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['suv']->id,
                'brand' => 'Lamborghini',
                'model' => 'Urus Performante',
                'year' => 2023,
                'vin' => 'ZHWUA2ZL2PLA88129',
                'license_plate' => 'URUS-PERF',
                'color' => 'Verde Mantis with Exposed Carbon Hood',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 5,
                'mileage' => 7500,
                'engine_capacity' => '4.0L Twin-Turbocharged V8 (657 HP)',
                'daily_rate' => 1400.00,
                'selling_price' => 325000.00,
                'listing_type' => 'both',
                'status' => 'reserved', // demo reserved status
                'condition' => 'certified_preowned',
                'description' => 'The Super SUV that recalibrated performance dynamics. Carbon fiber hood, titanium Akrapovic exhaust, and specialized RALLY drive mode.',
                'features' => ['Titanium Akrapovic Exhaust', 'Carbon Fiber Aero Package', 'Alcantara Sport Seats', 'Bang & Olufsen 3D Sound', 'Rally Drive Mode'],
                'main_image' => 'https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['luxury']->id,
                'brand' => 'Aston Martin',
                'model' => 'DB12 Super Tourer',
                'year' => 2024,
                'vin' => 'SCFRMDDY3RG091823',
                'license_plate' => 'DB12-AM',
                'color' => 'Satin Aston Martin Racing Green',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 4,
                'mileage' => 1400,
                'engine_capacity' => '4.0L Twin-Turbo V8 (671 HP)',
                'daily_rate' => 1100.00,
                'selling_price' => 298000.00,
                'listing_type' => 'sale',
                'status' => 'available',
                'condition' => 'new',
                'description' => 'The worlds first Super Tourer. Seamlessly merges muscular stance with hand-stitched Bridge of Weir leather and bespoke Bowers & Wilkins audio.',
                'features' => ['Bowers & Wilkins 1,170W Audio', 'Electronic Rear Differential', 'Adaptive Bilstein DTX Dampers', 'Bridge of Weir Semi-Aniline Hide'],
                'main_image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['pickup']->id,
                'brand' => 'Ford',
                'model' => 'F-150 Lightning Platinum',
                'year' => 2024,
                'vin' => '1FTVW1EV6PW092834',
                'license_plate' => 'EV-F150',
                'color' => 'Star White Metallic Tri-Coat',
                'transmission' => 'Automatic',
                'fuel_type' => 'Electric',
                'seating_capacity' => 5,
                'mileage' => 9800,
                'engine_capacity' => 'Extended-Range Battery Dual-Motor (580 HP)',
                'daily_rate' => 380.00,
                'selling_price' => 89500.00,
                'listing_type' => 'both',
                'status' => 'available',
                'condition' => 'certified_preowned',
                'description' => 'Electrified workhorse meets executive luxury. 9.6kW Pro Power Onboard generator, Mega Power Frunk, and Ford BlueCruise hands-free highway driving.',
                'features' => ['Ford BlueCruise Hands-Free', '9.6 kW Pro Power Onboard', 'Max Recline Heated/Cooled Seats', 'Mega Power Front Trunk', 'B&O Unleashed 18-Speaker Sound'],
                'main_image' => 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['suv']->id,
                'brand' => 'Mercedes-AMG',
                'model' => 'G 63',
                'year' => 2024,
                'vin' => 'W1NYX7KJ2PA748291',
                'license_plate' => 'G63-AMG',
                'color' => 'G manufaktur Night Black Magno',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'seating_capacity' => 5,
                'mileage' => 11200,
                'engine_capacity' => 'Handcrafted 4.0L AMG V8 Biturbo (577 HP)',
                'daily_rate' => 1050.00,
                'selling_price' => 239000.00,
                'listing_type' => 'rental',
                'status' => 'rented', // currently active rental demo
                'condition' => 'used',
                'description' => 'The immortal icon of off-road supremacy. Triple locking differentials, side-exit performance exhaust roar, and AMG Ride Control suspension.',
                'features' => ['AMG Night Package Magno', 'Side-Exit Sport Exhaust', 'Burmester Surround Sound', 'Triple 100% Differential Locks', 'AMG Carbon Interior'],
                'main_image' => 'https://images.unsplash.com/photo-1520050206274-a1ae44613e6d?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sports']->id,
                'brand' => 'Ferrari',
                'model' => '296 GTB Assetto Fiorano',
                'year' => 2023,
                'vin' => 'ZFF98NMA1P0293812',
                'license_plate' => '296-GTB',
                'color' => 'Rosso Corsa with Argento Nürburgring Livery',
                'transmission' => 'Dual-Clutch',
                'fuel_type' => 'Hybrid',
                'seating_capacity' => 2,
                'mileage' => 3400,
                'engine_capacity' => '3.0L Twin-Turbo V6 Plug-in Hybrid (819 HP)',
                'daily_rate' => 1850.00,
                'selling_price' => 385000.00,
                'listing_type' => 'sale',
                'status' => 'sold', // demo sold vehicle
                'condition' => 'certified_preowned',
                'description' => 'Maranellos hybrid berlinetta redefining fun to drive. Assetto Fiorano track dynamics package with Multimatic dampers and extensive carbon fiber weight reduction.',
                'features' => ['Assetto Fiorano Package', 'Multimatic Racing Shock Absorbers', 'Carbon Fiber Racing Seats', 'Passenger Display', 'Titanium Exhaust Pipes'],
                'main_image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
            ],
        ];

        $createdVehicles = [];
        foreach ($vehiclesData as $vData) {
            $vehicle = Vehicle::create($vData);
            $createdVehicles[] = $vehicle;

            // Add gallery images
            VehicleImage::create([
                'vehicle_id' => $vehicle->id,
                'image_url' => $vehicle->main_image,
                'caption' => "{$vehicle->brand} {$vehicle->model} - Front 3/4 Exterior View",
                'is_primary' => true,
            ]);

            VehicleImage::create([
                'vehicle_id' => $vehicle->id,
                'image_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80',
                'caption' => "{$vehicle->brand} {$vehicle->model} - Side Profile & Aerodynamics",
                'is_primary' => false,
            ]);

            VehicleImage::create([
                'vehicle_id' => $vehicle->id,
                'image_url' => 'https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?auto=format&fit=crop&w=1200&q=80',
                'caption' => "{$vehicle->brand} {$vehicle->model} - Cockpit & Interior Finishes",
                'is_primary' => false,
            ]);
        }

        // 4. Create Realistic Rental Bookings
        // Active Booking (Mercedes G 63 rented by Jonathan)
        $g63 = $createdVehicles[8]; // G 63
        $activeBooking = RentalBooking::create([
            'booking_number' => 'BK-20261015-8812',
            'user_id' => $customer1->id,
            'vehicle_id' => $g63->id,
            'start_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'total_days' => 5,
            'daily_rate' => $g63->daily_rate,
            'subtotal' => $g63->daily_rate * 5,
            'insurance_fee' => 250.00,
            'tax_amount' => round(($g63->daily_rate * 5 + 250) * 0.07, 2),
            'total_amount' => round(($g63->daily_rate * 5 + 250) * 1.07, 2),
            'paid_amount' => round(($g63->daily_rate * 5 + 250) * 1.07, 2),
            'status' => 'active',
            'pickup_location' => 'AutoDrive Miami Flagship Terminal',
            'return_location' => 'AutoDrive Miami Flagship Terminal',
            'notes' => 'VIP Airport Concierge pickup requested.',
            'approved_by' => $rentalManager->id,
            'approved_at' => Carbon::now()->subDays(3),
        ]);

        // Pickup inspection for active booking
        RentalInspection::create([
            'rental_booking_id' => $activeBooking->id,
            'vehicle_id' => $g63->id,
            'inspector_id' => $rentalManager->id,
            'type' => 'pickup',
            'odometer_reading' => 11200,
            'fuel_level' => 'Full',
            'exterior_condition' => 'Pristine, zero body imperfections noted',
            'interior_condition' => 'Sanitized and detailed',
            'damages_notes' => 'None',
            'damage_charge' => 0,
            'late_days' => 0,
            'late_fee' => 0,
            'inspection_date' => Carbon::now()->subDays(2),
        ]);

        // Payment for active booking
        Payment::create([
            'payment_number' => 'PAY-20261015-001',
            'payable_type' => RentalBooking::class,
            'payable_id' => $activeBooking->id,
            'user_id' => $customer1->id,
            'amount' => $activeBooking->total_amount,
            'payment_method' => 'simulated_card',
            'payment_status' => 'paid',
            'transaction_reference' => 'TXN-CARD-9910482',
            'notes' => 'Full rental booking payment verified.',
            'paid_at' => Carbon::now()->subDays(2),
            'verified_by' => $rentalManager->id,
            'verified_at' => Carbon::now()->subDays(2),
        ]);

        // Pending Booking (Porsche 911 GT3 RS requested by Sophia)
        $porsche911 = $createdVehicles[0];
        RentalBooking::create([
            'booking_number' => 'BK-20261020-1923',
            'user_id' => $customer2->id,
            'vehicle_id' => $porsche911->id,
            'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'total_days' => 3,
            'daily_rate' => $porsche911->daily_rate,
            'subtotal' => $porsche911->daily_rate * 3,
            'insurance_fee' => 300.00,
            'tax_amount' => round(($porsche911->daily_rate * 3 + 300) * 0.07, 2),
            'total_amount' => round(($porsche911->daily_rate * 3 + 300) * 1.07, 2),
            'paid_amount' => 0,
            'status' => 'pending',
            'pickup_location' => 'AutoDrive Miami Flagship Terminal',
            'return_location' => 'AutoDrive Miami Flagship Terminal',
            'notes' => 'Track day insurance package inquired.',
        ]);

        // Completed Past Booking (Range Rover Autobiography rented previously by Jonathan)
        $rangeRover = $createdVehicles[3];
        $completedBooking = RentalBooking::create([
            'booking_number' => 'BK-20261001-4412',
            'user_id' => $customer1->id,
            'vehicle_id' => $rangeRover->id,
            'start_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'end_date' => Carbon::now()->subDays(11)->format('Y-m-d'),
            'total_days' => 4,
            'daily_rate' => $rangeRover->daily_rate,
            'subtotal' => $rangeRover->daily_rate * 4,
            'insurance_fee' => 200.00,
            'tax_amount' => round(($rangeRover->daily_rate * 4 + 200) * 0.07, 2),
            'total_amount' => round(($rangeRover->daily_rate * 4 + 200) * 1.07, 2),
            'paid_amount' => round(($rangeRover->daily_rate * 4 + 200) * 1.07, 2),
            'status' => 'completed',
            'pickup_location' => 'AutoDrive Miami Flagship Terminal',
            'return_location' => 'AutoDrive Miami Flagship Terminal',
            'approved_by' => $rentalManager->id,
            'approved_at' => Carbon::now()->subDays(16),
        ]);

        // Return inspection for completed booking
        RentalInspection::create([
            'rental_booking_id' => $completedBooking->id,
            'vehicle_id' => $rangeRover->id,
            'inspector_id' => $rentalManager->id,
            'type' => 'return',
            'odometer_reading' => 6100,
            'fuel_level' => 'Full',
            'exterior_condition' => 'Pristine, no scratches detected',
            'interior_condition' => 'Immaculate condition',
            'damages_notes' => 'Returned on schedule, vehicle passed all return diagnostic gates.',
            'damage_charge' => 0,
            'late_days' => 0,
            'late_fee' => 0,
            'inspection_date' => Carbon::now()->subDays(11),
        ]);

        // Payment for completed booking
        Payment::create([
            'payment_number' => 'PAY-20261001-092',
            'payable_type' => RentalBooking::class,
            'payable_id' => $completedBooking->id,
            'user_id' => $customer1->id,
            'amount' => $completedBooking->total_amount,
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'transaction_reference' => 'TXN-CARD-1192842',
            'paid_at' => Carbon::now()->subDays(15),
            'verified_by' => $rentalManager->id,
            'verified_at' => Carbon::now()->subDays(15),
        ]);

        // 5. Create Purchase Requests & Completed Sale
        // Sold Ferrari 296 GTB transaction to Sophia
        $ferrari = $createdVehicles[9]; // Ferrari
        $ferrariPR = PurchaseRequest::create([
            'request_number' => 'PR-20260920-001',
            'user_id' => $customer2->id,
            'vehicle_id' => $ferrari->id,
            'offering_price' => 385000.00,
            'status' => 'completed',
            'customer_notes' => 'Enclosed transporter delivery to private residence requested.',
            'staff_notes' => 'Wire funds fully received and cleared by compliance.',
            'reviewed_by' => $salesManager->id,
            'reviewed_at' => Carbon::now()->subDays(25),
        ]);

        $ferrariSale = SalesTransaction::create([
            'transaction_number' => 'TX-20260922-9901',
            'purchase_request_id' => $ferrariPR->id,
            'vehicle_id' => $ferrari->id,
            'customer_id' => $customer2->id,
            'sales_manager_id' => $salesManager->id,
            'sale_price' => 385000.00,
            'tax_amount' => 26950.00,
            'fees_amount' => 1500.00,
            'total_amount' => 413450.00,
            'paid_amount' => 413450.00,
            'payment_status' => 'paid',
            'sale_date' => Carbon::now()->subDays(24)->format('Y-m-d'),
            'invoice_number' => 'INV-2026-0001',
            'notes' => 'Title transferred, bill of sale notarized.',
        ]);

        Payment::create([
            'payment_number' => 'PAY-20260922-001',
            'payable_type' => SalesTransaction::class,
            'payable_id' => $ferrariSale->id,
            'user_id' => $customer2->id,
            'amount' => 413450.00,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'paid',
            'transaction_reference' => 'WIRE-FED-88491028',
            'notes' => 'Wire transfer from J.P. Morgan Chase verified.',
            'paid_at' => Carbon::now()->subDays(24),
            'verified_by' => $salesManager->id,
            'verified_at' => Carbon::now()->subDays(24),
        ]);

        // Pending Purchase Request (Aston Martin DB12 by Jonathan)
        $astonMartin = $createdVehicles[6];
        PurchaseRequest::create([
            'request_number' => 'PR-20261016-002',
            'user_id' => $customer1->id,
            'vehicle_id' => $astonMartin->id,
            'offering_price' => 295000.00,
            'status' => 'under_review',
            'customer_notes' => 'Would like to arrange vehicle inspection and pre-purchase diagnostic scan on Friday.',
            'staff_notes' => 'Customer is pre-qualified. Preparing vehicle provenance file.',
            'reviewed_by' => $salesManager->id,
            'reviewed_at' => Carbon::now()->subDay(),
        ]);

        // 6. Create Support Tickets and Messages
        $ticket1 = SupportTicket::create([
            'ticket_number' => 'SUP-20261017-001',
            'user_id' => $customer1->id,
            'assigned_to' => $supportAgent->id,
            'subject' => 'Chauffeur and airport terminal delivery options',
            'category' => 'rental',
            'priority' => 'medium',
            'status' => 'in_progress',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket1->id,
            'user_id' => $customer1->id,
            'message' => 'Hello team, for my upcoming rental, do you provide private terminal tarmac delivery at Miami Executive Airport (KTMB)?',
            'is_staff' => false,
            'created_at' => Carbon::now()->subHours(18),
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket1->id,
            'user_id' => $supportAgent->id,
            'message' => 'Greetings Ato Kassaw. Yes, our white-glove team provides tarmac delivery at KTMB FBOs (Signature and Reliance). Our concierge driver will meet your flight with the vehicle pre-conditioned.',
            'is_staff' => true,
            'created_at' => Carbon::now()->subHours(12),
        ]);

        // 7. Activity Logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'SYSTEM_INIT',
            'description' => 'AutoDrive platform initialized with inventory and role-based policies.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'AutoDrive Server Core',
        ]);

        ActivityLog::create([
            'user_id' => $salesManager->id,
            'action' => 'SALE_COMPLETED',
            'description' => 'Finalized sale for 2023 Ferrari 296 GTB (INV-2026-0001).',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'entity_type' => SalesTransaction::class,
            'entity_id' => $ferrariSale->id,
        ]);
    }
}
