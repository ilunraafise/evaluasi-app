<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // Cek kalau kolom admin_id ada
            if (Schema::hasColumn('forms', 'admin_id')) {
                // Cek apakah foreign key ada, baru drop
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableForeignKeys('forms');
                foreach ($indexes as $index) {
                    if ($index->getLocalColumns()[0] === 'admin_id') {
                        $table->dropForeign($index->getName());
                    }
                }

                $table->renameColumn('admin_id', 'user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            if (Schema::hasColumn('forms', 'user_id')) {
                $table->renameColumn('user_id', 'admin_id');
                $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            }
        });
    }
};
