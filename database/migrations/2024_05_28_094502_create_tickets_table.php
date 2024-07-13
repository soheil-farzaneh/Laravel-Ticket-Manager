<?php

use Aqayepardakht\TicketManager\Utils\ConfigHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    protected $ticketTable;
    protected $replyTable;

    public function __construct()
    {
        $this->ticketTable = ConfigHelper::getTicketTable();
        $this->replyTable = ConfigHelper::getReplyTable();
    }

    public function up()
    {
        Schema::create($this->ticketTable, function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('department', ['financial', 'general', 'technical']);
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', ['waiting', 'pending', 'answered', 'closed', 'customerResponse', 'adminCreated']);
            $table->enum('satisfaction', ['happy', 'unhappy'])->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('opts');
            $table->softDeletes();;
            $table->timestamps();
        });

        Schema::create($this->replyTable, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id')->on('tickets')->references('id')->onDelete('cascade');
            $table->longText('text')->nullable();
            $table->string('ip')->nullable();
            $table->string('file')->nullable();
            $table->enum('created_by', ['admin', 'user']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->ticketTable);
        Schema::dropIfExists($this->replyTable);
    }
};