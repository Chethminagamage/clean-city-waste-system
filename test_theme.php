<?php
// Simple test script to debug theme saving
require_once 'bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Find a resident user
$user = User::where('role', 'resident')->first();

if ($user) {
    echo "Found user: {$user->email}\n";
    echo "Current theme: " . ($user->theme_preference ?? 'null') . "\n";
    
    // Test updating theme
    $oldTheme = $user->theme_preference;
    $newTheme = $oldTheme === 'dark' ? 'light' : 'dark';
    
    echo "Updating theme from '{$oldTheme}' to '{$newTheme}'\n";
    
    $user->theme_preference = $newTheme;
    $result = $user->save();
    
    echo "Save result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Verify the change
    $user->refresh();
    echo "New theme preference: " . ($user->theme_preference ?? 'null') . "\n";
} else {
    echo "No resident user found\n";
}
?>
