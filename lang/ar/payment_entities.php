<?php

return [
    'payment_entities' => 'جهات الدفع',
    'payment_entity' => 'جهة دفع',
    'create_new_payment_entity' => 'إضافة جهة دفع جديدة',
    'update_payment_entity' => 'تعديل بيانات جهة الدفع',
    'show_all_payment_entities' => 'عرض جميع جهات الدفع',
    
    // Fields
    'name' => 'اسم جهة الدفع',
    'name_ar' => 'اسم جهة الدفع بالعربية',
    'name_en' => 'اسم جهة الدفع بالإنجليزية',
    'type' => 'النوع',
    'type_bank' => 'بنك',
    'type_wallet' => 'محفظة إلكترونية',
    'status' => 'الحالة',
    'status_active' => 'مفعل',
    'status_inactive' => 'معطل',
    
    // Placeholders
    'select_type' => 'اختر النوع...',
    'enter_name_ar' => 'أدخل الاسم بالعربية...',
    'enter_name_en' => 'أدخل الاسم بالإنجليزية...',
    
    // UI Elements
    'no_payment_entities_found' => 'لم يتم العثور على جهات دفع!',
    
    // Validation
    'name_ar_required' => 'يرجى إدخال الاسم بالعربية',
    'name_en_required' => 'يرجى إدخال الاسم بالإنجليزية',
    'type_required' => 'يرجى تحديد النوع',
    'delete_restriction' => 'عفواً، لا يمكن حذف جهة الدفع لوجود حسابات بنكية مرتبطة بها!',
];
