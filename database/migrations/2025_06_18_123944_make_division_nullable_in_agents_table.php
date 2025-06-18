<?php
// database/migrations/xxxx_xx_xx_xxxxxx_make_division_nullable_in_agents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->string('division')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->string('division')->nullable(false)->change();
        });
    }
};