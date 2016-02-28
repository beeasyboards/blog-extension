<?php namespace BeEasy\BlogExtension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTempsTable extends Migration
{

    public function up()
    {
        Schema::table('rainlab_blog_posts', function ($table) {
            $table->string('subtitle');
        });
    }

    public function down()
    {
        Schema::table('rainlab_blog_posts', function ($table) {
            $table->dropColumn('subtitle');
        });
    }

}
