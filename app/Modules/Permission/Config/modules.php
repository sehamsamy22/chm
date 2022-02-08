<?php

// add new permission like this ('addPermissions' => ['force_delete' => 'حذف نهائى'])
// except permissions from default like this ('exceptPermissions' => ['-edit', '-delete'])
return [
    [
        'name' => 'role',
        'slug' => 'نظام الصلاحيات',
    ],
    [
        'name' => 'admin',
        'slug' => 'المديرين',
    ],
    [
        'name' => 'order',
        'slug' => 'الطلبات',
        'exceptPermissions' => ['-create', '-delete'],
        'addPermissions' => ['export-excel' => 'تصدير للإكسل', 'import-excel' => 'استيراد من الإكسل']
    ],
    [
        'name' => 'setting',
        'slug' => 'إعدادات الموقع',
        'exceptPermissions' => ['-create', '-delete']
    ],
    [
        'name' => 'ad',
        'slug' => 'الاعلانات',
    ],
    [
        'name' => 'cart',
        'slug' => 'السلة',
        'exceptPermissions' => ['-create', '-delete', '-update'],
    ],
    [
        'name' => 'category',
        'slug' => 'الاقسام',
    ],
    [
        'name' => 'product',
        'slug' => 'المنتجات',
        'addPermissions' => ['export-excel' => 'تصدير للإكسل', 'import-excel' => 'استيراد من الإكسل']
    ],
    [
        'name' => 'coupon',
        'slug' => 'الكوبونات',
        'addPermissions' => ['export-excel' => 'تصدير للإكسل', 'import-excel' => 'استيراد من الإكسل']
    ],
    [
        'name' => 'subscribe',
        'slug' => 'الاشتراكات',
        'exceptPermissions' => ['-create', '-update'],
    ],
    [
        'name' => 'contact',
        'slug' => 'رسائل التواصل',
        'exceptPermissions' => ['-create', '-update'],
    ],
    [
        'name' => 'payment',
        'slug' => 'طرق الدفع',
    ],

    [
        'name' => 'blog',
        'slug' => 'المدونات',
    ],
    [
        'name' => 'page',
        'slug' => 'الصفحات الثابتة',
    ],
    [
        'name' => 'user',
        'slug' => 'المستخدمين',
        'addPermissions' => ['export-excel' => 'تصدير للإكسل', 'import-excel' => 'استيراد من الإكسل']
    ],
    [
        'name' => 'city',
        'slug' => 'مدن',
    ],
    [
        'name' => 'area',
        'slug' => 'مناطق',
    ],
    [
        'name' => 'blog-category',
        'slug' => 'أقسام المدونات ',
    ],
    [
        'name' => 'option',
        'slug' => 'خصائص ',
    ],
    [
        'name' => 'category-option',
        'slug' => 'خصائص القسم',
    ],
    [
        'name' => 'list',
        'slug' => 'قوائم المنتجات',
    ],
    [
        'name' => 'color',
        'slug' => 'الوان المنتجات',
    ],
    [
        'name' => 'promotion',
        'slug' => ' العروض',
    ],
       [
        'name' => 'brand',
        'slug' => 'الماركات',
    ],
];
