<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Availability;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('availability_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('surname');
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->date('birthdate')->nullable();
            $table->text('bio')->nullable();
            $table->string('interests')->nullable();
            $table->set('relationship_status', [
                'Single',
                'In a relationship',
                'In a open relationship',
                'In more than one relationship',
                "It\'s complicated",
                'Married',
                'Widowed',
                'Divorced',
                'Separated',
                'dontwannasay'
            ])->default('dontwannasay');
            $table->set(
                'sexual_orientation', [
                        'Lesbian',
                        'Gay',
                        'Bisexual',
                        'Transgender',
                        'Queer',
                        'Intersex',
                        'Asexual',
                        '+',
                        'dontwannasay',
                        'Hetero'
                ]
            )->default('dontwannasay');
            $table->string('languages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
