<?php

return [
    "title" => "ملاحظات",
    "single" => "ملاحظة",
    "group" => "المحتوي",
    "pages" => [
        "groups" => "إدارة مجموعات الملاحظات",
        "status" => "إدارة حالات الملاحظات"
    ],
    "columns" => [
        "title" => "العنوان",
        "body" => "المحتوي",
        "date" => "التاريخ",
        "time" => "الوقت",
        "is_pined" => "مثبت",
        "is_public" => "عام",
        "icon" => "الرمز",
        "group" => "المجموعة",
        "status" => "الحالة",
        "background" => "الخلفية",
        "border" => "الحدود",
        "color" => "اللون",
        "font_size" => "حجم الخط",
        "font" => "الخط",
        "user_id" => "رقم المستخدم",
        "user_type" => "نوع المستخدم",
        "model_id" => "رقم النموذج",
        "model_type" => "نوع النموذج",
    ],
    "tabs" => [
        "general" => "عام",
        "style" => "الشكل"
    ],
    "actions" => [
        "view" => "عرض",
        "edit" => "تعديل",
        "delete" => "حذف",
        "notify" => [
            "label" => "اشعار المستخدم",
            "notification" => [
                "title" => "تم إرسال الإشعار",
                "body" => "تم إرسال الإشعار."
            ]
        ],
        "share" => [
            "label" => "مشاركة الملاحظة",
            "notification" => [
                "title" => "تم إنشاء رابط مشاركة الملاحظة",
                "body" => "تم إنشاء رابط مشاركة الملاحظة ونسخه إلى الحافظة."
            ]
        ],
        "user_access" => [
            "label" => "صلاحية المستخدم",
            "form" => [
                "model_id" => "المستخدمين",
                "model_type" => "نوع المستخدم",
            ],
            "notification" => [
                "title" => "تم تحديث صلاحية المستخدم",
                "body" => "تم تحديث صلاحية المستخدم."
            ]
        ],
        "checklist"=> [
            "label" => "قائمة المهام",
            "form" => [
                "checklist"=> "المهام",
            ],
            "state" => [
                "done" => "تم",
                "pending" => "قيد الانتظار"
            ],
            "notification" => [
                "title" => "قائمة المهام",
                "body" => "تم إضافة قائمة المهام.",
                "updated" => [
                    "title" => "قائمة المهام",
                    "body" => "تم تحديث قائمة المهام."
                ],
            ]
        ]
    ],
    "notifications" => [
        "edit" => [
            "title" => "تم تحديث الملاحظة",
            "body" => "تم تحديث الملاحظة."
        ],
        "delete" => [
            "title" => "تم حذف الملاحظة",
            "body" => "تم حذف الملاحظة."
        ]
    ]
];