<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL Trigger: auto-calculate order total_amount
        DB::unprepared('DROP TRIGGER IF EXISTS after_order_item_insert');
        DB::unprepared('
            CREATE TRIGGER after_order_item_insert
            AFTER INSERT ON order_item
            FOR EACH ROW
            BEGIN
                UPDATE `order`
                SET total_amount = total_amount + (NEW.price * NEW.quantity)
                WHERE id = NEW.order_id;
            END
        ');

        // Stored Procedure: atomic salary payment
        DB::unprepared('DROP PROCEDURE IF EXISTS PayEmployeeSalary');
        DB::unprepared('
            CREATE PROCEDURE PayEmployeeSalary(
                IN emp_id VARCHAR(36),
                IN pay_amount FLOAT,
                IN ledger_date VARCHAR(20)
            )
            BEGIN
                START TRANSACTION;
                UPDATE employee SET salary_due = salary_due - pay_amount WHERE id = emp_id;
                UPDATE daily_ledger SET salary_paid = salary_paid + pay_amount WHERE date = ledger_date;
                COMMIT;
            END
        ');

        // View: Low stock alert
        DB::unprepared('DROP VIEW IF EXISTS LowStockAlertView');
        DB::unprepared('
            CREATE VIEW LowStockAlertView AS
            SELECT id, name, quantity, unit, min_quantity
            FROM stock_item
            WHERE quantity <= min_quantity
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_order_item_insert');
        DB::unprepared('DROP PROCEDURE IF EXISTS PayEmployeeSalary');
        DB::unprepared('DROP VIEW IF EXISTS LowStockAlertView');
    }
};
