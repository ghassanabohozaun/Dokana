<?php

return [
    // General / System
    'system_alert' => 'تنبيه نظام',
    'test_title' => 'إشعار تجريبي',
    'test_message' => 'هذا إشعار تجريبي تم توليده من النظام لفحص الإشعارات.',
    'digest_title' => 'الملخص اليومي',
    'digest_msg' => 'صباح الخير! لديك :contracts عقود توشك على الانتهاء، و :cheques شيكات مستحقة الدفع قريباً.',

    // Contracts
    'contract_expiring_title' => 'عقد يوشك على الانتهاء',
    'contract_expiring_msg' => 'العقد رقم :contract_no للعقار ":property" سينتهي خلال :days أيام.',
    'contract_expired_title' => 'عقد منتهي',
    'contract_expired_msg' => 'العقد رقم :contract_no للعقار ":property" انتهت صلاحيته اليوم.',
    'new_contract_title' => 'عقد إيجار جديد',
    'new_contract_msg' => 'تم إبرام عقد إيجار جديد رقم :contract.',
    'contract_cancelled_title' => 'إلغاء عقد إيجار',
    'contract_cancelled_msg' => 'تم تغيير حالة العقد رقم :contract إلى (ملغي)!',
    'contract_reactivated_title' => 'تفعيل عقد إيجار',
    'contract_reactivated_msg' => 'تمت إعادة تفعيل العقد رقم :contract ليكون سارياً.',

    // Financial / Cheques
    'cheque_due_title' => 'شيك يستحق قريباً',
    'cheque_due_msg' => 'الشيك رقم :cheque_no بقيمة :amount يستحق الصرف خلال :days أيام.',
    'cheque_overdue_title' => 'شيك متأخر',
    'cheque_overdue_msg' => 'الشيك رقم :cheque_no بقيمة :amount تأخر عن موعد استحقاقه!',
    'cheque_cleared_title' => 'تحصيل شيك',
    'cheque_cleared_msg' => 'تم تحصيل الشيك رقم :cheque_no بقيمة :amount بنجاح.',
    'cheque_bounced_title' => 'ارتجاع شيك',
    'cheque_bounced_msg' => 'تم ارتجاع الشيك رقم :cheque_no بقيمة :amount!',
    'cheque_reset_title' => 'تراجع عن إجراء',
    'cheque_reset_msg' => 'تم التراجع عن إجراء الشيك رقم :cheque_no بقيمة :amount وإعادته لقيد الانتظار.',
    'payment_collected_title' => 'تحصيل دفعة نقدية',
    'payment_collected_msg' => 'تم تحصيل دفعة بقيمة :amount للعقد :contract بنجاح.',
    'payment_reset_title' => 'تراجع عن تحصيل دفعة',
    'payment_reset_msg' => 'تم التراجع عن تحصيل دفعة بقيمة :amount للعقد :contract وإعادتها لقيد الانتظار.',
    'payment_deleted_title' => 'حذف دفعة نقدية',
    'payment_deleted_msg' => 'تم حذف دفعة بقيمة :amount خاصة بالعقد :contract.',

    // Properties
    'property_vacant_title' => 'حالة العقار متاح',
    'property_vacant_msg' => 'تم تغيير حالة العقار ":property" إلى متاح.',

    // UI Elements
    'notifications' => 'الإشعارات',
    'mark_all_read' => 'تحديد الكل كمقروء',
    'no_new_notifications' => 'لا توجد إشعارات جديدة حالياً',
    'view_all' => 'عرض كل الإشعارات',
    'new' => 'جديد',
    'just_now' => 'الآن',
    'tab_all' => 'الكل',
    'tab_financial' => 'المالية',
    'tab_contracts' => 'العقود',
    'tab_system' => 'النظام',
    'collect_now' => 'تحصيل الآن',
    
    // Actions & Confirmations
    'delete_selected' => 'حذف المحدد',
    'delete_all' => 'مسح الكل',
    'view_details' => 'عرض التفاصيل',
    'mark_as_read' => 'تحديد كمقروء',
    'confirm_delete_title' => 'تأكيد الحذف',
    'confirm_delete_selected_text' => 'هل أنت متأكد من مسح الإشعارات المحددة؟ لا يمكن التراجع عن هذا الإجراء.',
    'confirm_delete_all_text' => 'هل أنت متأكد من مسح كافة الإشعارات؟ لا يمكن التراجع عن هذا الإجراء.',
    'confirm_delete_single_text' => 'هل أنت متأكد من عملية الحذف؟ لا يمكن التراجع عن هذا الإجراء.',
];
