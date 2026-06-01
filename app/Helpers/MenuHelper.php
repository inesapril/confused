<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function canAccess($menu)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Get user's roles
        $userRoles = $user->getRoleNames()->toArray();
        
        // Owner can access all menus
        if (in_array('Owner', $userRoles)) {
            return true;
        }
        
        // Define menu access for each role
        $roleMenus = [
            'Persediaan' => ['dashboard', 'barang', 'persediaan', 'penyesuaian_persediaan', 'barang-masuk'],
            'Produksi' => ['dashboard', 'persediaan', 'pesanan']
        ];
        
        // Check if user has access to the menu
        foreach ($userRoles as $role) {
            if (isset($roleMenus[$role]) && in_array($menu, $roleMenus[$role])) {
                return true;
            }
        }
        
        return false;
    }
}
