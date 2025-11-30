<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropInstructionsFromQuestionnairesTable extends Migration
{
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('instructions');
        });
    }

    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->text('instructions')->nullable();
        });
    }
}
