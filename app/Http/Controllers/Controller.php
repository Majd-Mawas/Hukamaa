<?php

namespace App\Http\Controllers;

use App\Services\NotificationTemplateBuilder;

abstract class Controller
{
    public function __construct(public NotificationTemplateBuilder $notification_template_builder) {}
}
