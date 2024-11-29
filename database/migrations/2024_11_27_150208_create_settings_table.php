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
            [
                'key' => 'site.favicon',
                'value' => '/images/favicon.ico',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Site favicon yolu',
                'is_public' => true
            ],

            // Tema Ayarları
            [
                'key' => 'theme.primary_color',
                'value' => '#4a90e2',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'Ana tema rengi',
                'is_public' => true
            ],
            [
                'key' => 'theme.secondary_color',
                'value' => '#2c3e50',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'İkincil tema rengi',
                'is_public' => true
            ],
            [
                'key' => 'theme.dark_mode',
                'value' => 'false',
                'group' => 'theme',
                'type' => 'boolean',
                'description' => 'Karanlık mod aktif/pasif',
                'is_public' => true
            ],
            [
                'key' => 'theme.font_family',
                'value' => 'Roboto, sans-serif',
                'group' => 'theme',
                'type' => 'string',
                'description' => 'Site yazı tipi',
                'is_public' => true
            ],

            // Dil Ayarları
            [
                'key' => 'locale.default',
                'value' => 'tr',
                'group' => 'locale',
                'type' => 'string',
                'description' => 'Varsayılan dil',
                'is_public' => true
            ],
            [
                'key' => 'locale.available',
                'value' => json_encode(['tr', 'en']),
                'group' => 'locale',
                'type' => 'json',
                'description' => 'Kullanılabilir diller',
                'is_public' => true
            ],

            // SEO Ayarları
            [
                'key' => 'seo.meta_description',
                'value' => 'NoteAPP - Notlarınızı paylaşın, sınavlara hazırlanın',
                'group' => 'seo',
                'type' => 'string',
                'description' => 'Meta açıklama',
                'is_public' => true
            ],
            [
                'key' => 'seo.meta_keywords',
                'value' => 'notlar, sınavlar, eğitim, öğrenim',
                'group' => 'seo',
                'type' => 'string',
                'description' => 'Meta anahtar kelimeler',
                'is_public' => true
            ],
            [
                'key' => 'seo.google_analytics',
                'value' => '',
                'group' => 'seo',
                'type' => 'string',
                'description' => 'Google Analytics kodu',
                'is_public' => false
            ],

            // Sosyal Medya Ayarları
            [
                'key' => 'social.facebook',
                'value' => '',
                'group' => 'social',
                'type' => 'string',
                'description' => 'Facebook sayfası URL',
                'is_public' => true
            ],
            [
                'key' => 'social.twitter',
                'value' => '',
                'group' => 'social',
                'type' => 'string',
                'description' => 'Twitter sayfası URL',
                'is_public' => true
            ],
            [
                'key' => 'social.instagram',
                'value' => '',
                'group' => 'social',
                'type' => 'string',
                'description' => 'Instagram sayfası URL',
                'is_public' => true
            ],

            // Mail Ayarları
            [
                'key' => 'mail.from_address',
                'value' => 'info@noteapp.com',
                'group' => 'mail',
                'type' => 'string',
                'description' => 'Gönderen mail adresi',
                'is_public' => false
            ],
            [
                'key' => 'mail.from_name',
                'value' => 'NoteAPP',
                'group' => 'mail',
                'type' => 'string',
                'description' => 'Gönderen adı',
                'is_public' => false
            ],

            // Sistem Ayarları
            [
                'key' => 'system.maintenance_mode',
                'value' => 'false',
                'group' => 'system',
                'type' => 'boolean',
                'description' => 'Bakım modu',
                'is_public' => true
            ],
            [
                'key' => 'system.items_per_page',
                'value' => '15',
                'group' => 'system',
                'type' => 'integer',
                'description' => 'Sayfa başına öğe sayısı',
                'is_public' => true
            ],
            [
                'key' => 'system.max_upload_size',
                'value' => '5242880', // 5MB in bytes
                'group' => 'system',
                'type' => 'integer',
                'description' => 'Maksimum dosya yükleme boyutu (byte)',
                'is_public' => true
            ],
            [
                'key' => 'system.allowed_file_types',
                'value' => json_encode(['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']),
                'group' => 'system',
                'type' => 'json',
                'description' => 'İzin verilen dosya türleri',
                'is_public' => true
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
