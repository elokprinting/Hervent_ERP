<?php

namespace App;

enum UserRole: string
{
    case Admin = 'admin';
    case ManagerSales = 'manager_sales';
    case Sales = 'sales';
    case ManagerOperasional = 'manager_operasional';
    case ProQc = 'proqc';
    case Marketing = 'marketing';
    case Finance = 'finance';
    case Ceo = 'ceo';
}
