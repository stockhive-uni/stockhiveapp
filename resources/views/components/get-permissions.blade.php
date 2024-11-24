<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

$perms = DB::select("SELECT DISTINCT permission.id, permission.name AS permissionName, category.name AS categoryName FROM permission, role_permission, user_role, category WHERE permission.id = role_permission.permission_id AND role_permission.role_id = user_role.role_id AND permission.category_id = category.id AND user_role.user_id = " . $id);
global $permissions;
$permissions = collect($perms);

/*
Stock Management
1 - Create Order
2 - View Stock
3 - Add/Remove Stock
4 - Generate Stock Reports

Sales
5 - Make Sales
6 - Generate Sales Invoices

Logistics
7 - Receive Stock
8 - Generate Delivery Reports
9 - Note Over-Deliveries
10 - Complete Over-Deliveries

Inventory
11 - Shelf Stock
12 - Stock Checks
13 - Generate Stock Check Reports

Admin
14 - Create Users
15 - Edit Users
16 - Delete Users
17 - Generate User Reports



if (in_array(permission_id, $permissions)) {
    // Do Thing
}
*/
?>