<?php

namespace App\Enums;

enum Shipment: string
{
  case preparing = 'Đang lấy hàng';
  case delivering = 'Đang giao';
  case successful = 'Thành công';
  case canceled = 'Huỷ';
}
