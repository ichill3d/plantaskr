<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToCommentReactionsTable extends Migration
{
    public function up()
    {
        Schema::table('comment_reactions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->after('reactions_types_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('comment_reactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
