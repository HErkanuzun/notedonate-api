<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('string'); // string, integer, boolean, json, array
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        // Varsayılan ayarları ekle
        $defaultSettings = [
            // Site Genel Ayarları
            [
                'key' => 'site.name',
                'value' => 'NoteAPP',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Site adı',
                'is_public' => true
            ],
            [
                'key' => 'site.description',
                'value' => 'Not paylaşım ve sınav hazırlık platformu',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Site açıklaması',
                'is_public' => true
            ],
            [
                'key' => 'site.logo',
                'value' => '/images/logo.png',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Site logo yolu',
                'is_public' => true
            ],

            // Tema Ayarları
            [
                'key' => 'theme.mode',
                'value' => 'light',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'Site tema modu (light/dark)',
                'is_public' => true
            ],
            [
                'key' => 'theme.primary_color',
                'value' => '#4F46E5',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'Ana tema rengi',
                'is_public' => true
            ],
            [
                'key' => 'theme.secondary_color',
                'value' => '#6B7280',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'İkincil tema rengi',
                'is_public' => true
            ],
            [
                'key' => 'theme.font_family',
                'value' => 'Inter',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'Site yazı tipi',
                'is_public' => true
            ],

            // Kullanıcı Arayüzü Ayarları
            [
                'key' => 'ui.sidebar_position',
                'value' => 'left',
                'group' => 'ui',
                'type' => 'string',
                'description' => 'Kenar çubuğu pozisyonu (left/right)',
                'is_public' => true
            ],
            [
                'key' => 'ui.show_breadcrumbs',
                'value' => 'true',
                'group' => 'ui',
                'type' => 'boolean',
                'description' => 'Sayfa yol haritasını göster',
                'is_public' => true
            ],
            [
                'key' => 'ui.items_per_page',
                'value' => '10',
                'group' => 'ui',
                'type' => 'integer',
                'description' => 'Sayfa başına gösterilecek öğe sayısı',
                'is_public' => true
            ],

            // Sosyal Medya Ayarları
            [
                'key' => 'social.facebook',
                'value' => '',
                'group' => 'social',
                'type' => 'string',
                'description' => 'Facebook sayfa linki',
                'is_public' => true
            ],
            [
                'key' => 'social.twitter',
                'value' => '',
                'group' => 'social',
                'type' => 'string',
                'description' => 'Twitter sayfa linki',
                'is_public' => true
            ],
            [
                'key' => 'social.instagram',
                'value' => '',
                'group' => 'social',
                'type' => 'string',
                'description' => 'Instagram sayfa linki',
                'is_public' => true
            ],

            // SEO Ayarları
            [
                'key' => 'seo.meta_keywords',
                'value' => 'notlar,sınavlar,eğitim,öğrenci',
                'group' => 'seo',
                'type' => 'string',
                'description' => 'Meta anahtar kelimeler',
                'is_public' => true
            ],
            [
                'key' => 'seo.meta_description',
                'value' => 'Not paylaşım ve sınav hazırlık platformu',
                'group' => 'seo',
                'type' => 'string',
                'description' => 'Meta açıklama',
                'is_public' => true
            ],
            [
                'key' => 'seo.google_analytics_id',
                'value' => '',
                'group' => 'seo',
                'type' => 'string',
                'description' => 'Google Analytics ID',
                'is_public' => false
            ]
        ];

        // Varsayılan ayarları veritabanına ekle
        foreach ($defaultSettings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
