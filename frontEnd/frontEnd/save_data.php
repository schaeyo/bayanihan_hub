<?php
include 'db_connection.php'; 
session_start();

// Population data
$childrenMale = $_POST['childrenMale'] ?? 0;
$childrenFemale = $_POST['childrenFemale'] ?? 0;
$youthMale = $_POST['youthMale'] ?? 0;
$youthFemale = $_POST['youthFemale'] ?? 0;
$adultsMale = $_POST['adultsMale'] ?? 0;
$adultsFemale = $_POST['adultsFemale'] ?? 0;
$seniorsMale = $_POST['seniorsMale'] ?? 0;
$seniorsFemale = $_POST['seniorsFemale'] ?? 0;

// Residential data
$singleFamily = $_POST['singleFamily'] ?? 0;
$multiFamily = $_POST['multiFamily'] ?? 0;
$apartments = $_POST['apartments'] ?? 0;
$occupied = $_POST['occupied'] ?? 0;
$vacant = $_POST['vacant'] ?? 0;

// Calculate totals
$male = $childrenMale + $youthMale + $adultsMale + $seniorsMale;
$female = $childrenFemale + $youthFemale + $adultsFemale + $seniorsFemale;

$sql = "INSERT INTO analytics_data (
    male_population, female_population, 
    children_male, children_female,
    youth_male, youth_female,
    adults_male, adults_female,
    seniors_male, seniors_female,
    single_family_units, multi_family_units, apartment_units,
    occupied_residential, vacant_residential
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->bind_param("iiiiiiiiiiiiiii", 
    $male, $female, 
    $childrenMale, $childrenFemale,
    $youthMale, $youthFemale,
    $adultsMale, $adultsFemale,
    $seniorsMale, $seniorsFemale,
    $singleFamily, $multiFamily, $apartments,
    $occupied, $vacant
);

if ($stmt->execute()) {
    echo "Data saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$con->close();
?>