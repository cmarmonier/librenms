<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMplsLspPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpls_lsp_paths', function (Blueprint $table) {
            $table->increments('lsp_path_id');
            $table->unsignedInteger('lsp_id');
            $table->unsignedInteger('path_oid');
            $table->unsignedInteger('device_id')->index();
            $table->enum('mplsLspPathRowStatus', array('active','notInService','notReady','createAndGo','createAndWait','destroy'));
            $table->bigInteger('mplsLspPathLastChange');
            $table->enum('mplsLspPathType', array('other', 'primary', 'standby', 'secondary'));
            $table->unsignedInteger('mplsLspPathBandwidth');
            $table->unsignedInteger('mplsLspPathOperBandwidth');
            $table->enum('mplsLspPathAdminState', array('noop', 'inService', 'outOfService'));
            $table->enum('mplsLspPathOperState', array('unknown','inService','outOfService','transition'));
            $table->enum('mplsLspPathState', array('unknown', 'active', 'inactive'));
            $table->string('mplsLspPathFailCode', 64);
            $table->string('mplsLspPathFailNodeAddr', 32);
            $table->unsignedInteger('mplsLspPathMetric');
            $table->unsignedInteger('mplsLspPathOperMetric');
            $table->bigInteger('mplsLspPathTimeUp')->nullable();
            $table->bigInteger('mplsLspPathTimeDown')->nullable();
            $table->unsignedInteger('mplsLspPathTransitionCount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpls_lsp_paths');
    }
}
