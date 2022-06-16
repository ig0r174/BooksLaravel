<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BookStatus;

class CreateBookStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });
        $statuses = ['created', 'on editing', 'edited', 'checked', 'marked', 'ready'];
        foreach ($statuses as $status) {
            $bookStatus = new BookStatus();
            $bookStatus->status = $status;
            $bookStatus->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_statuses');
    }
}
